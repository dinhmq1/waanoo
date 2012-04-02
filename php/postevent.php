<?php
session_start();
require('cxn.php');
$GLOBALS['debug'] = false;

// first off, check that we are signed in:
if($_SESSION['signed_in'] != true) {
	$arr = array("status" => 0, 
		"message" => "Failed to create event... User not signed in!");
	echo json_encode($arr);
	exit();
	}



/* FROM FRONT:
 * 
 * postEventData = {
		latitude: lat_event,
		longitude: lng_event,
		eventName: eventName,
		eventLocation: address,
		eventBegin: eventBegin,
		eventEnd: eventEnd,
		eventDescription: eventDescrip
		};
	*/
	
// CHECK FOR DUPLICATES
function check_for_dups($all_fields) {
	$cxn = $GLOBALS['cxn'];
	extract($all_fields);
	// $eventName pulled
	// $eventBegin
	
	$eventName = '%'.$name.'%';
	//$eventName = $name;
	
	$start_date_begin = date("Y-m-d H:m:s", strtotime($begin) - 60*60*24);
	$start_date_end = date("Y-m-d H:m:s", strtotime($begin) + 60*60*24);
	$date_create_begin = date("Y-m-d H:m:s", time() - 60*60*24);
	$date_create_end = date("Y-m-d H:m:s", time() - 60*60*24);
	
	$qry = "SELECT * FROM user_events 
			WHERE event_title LIKE ?
			AND (
			(start_date > ? AND start_date < ?) 
			OR 
			(date_created > ? AND date_created < ?)
			)";
			
	//echo $qry;
	$stm = $cxn->prepare($qry);
	$stm->bind_param(
			"sssss", 
			$eventName, 
			$start_date_begin, 
			$start_date_end, 
			$date_create_begin, 
			$date_create_end);
	$stm->execute();
	$stm->store_result(); 
	$count = $stm->num_rows;
	
	//echo "Count: $count";
	if($count == 0)
		return true;
	else
		return false;
	
	$stm->close();
	}


	
// CHECK TO MAKE SURE END IS GREATER THAN BEGINING
function dateCheckSensible($all_fields) {
	$end = strtotime($all_fields['end']);
	$start = strtotime($all_fields['begin']);
	if($end > $start && $end > time())
		return true;
	else
		return false;
	}
	
// CHECKS FOR VALID DATE FORMATTING 
function dateCheckValid($all_fields) {
	$end = $all_fields['end'];
	$start = $all_fields['begin'];
	$date_pattern = "/\d+-\d+-\d+ \d+:\d+/";
	if(preg_match($date_pattern, $end) && preg_match($date_pattern, $start))
		return true;
	else
		return false;
	}

// CHECK FOR EMPTINESS
function checkEmpties($all_fields) {
	foreach($all_fields as $key => $value){
		if(empty($value))
			return false;
		}
		return true;
	}

// STRIPS TAGS/ TRIMS WHITESPACE
function clean_fields($fields){
	//$cxn = $GLOBALS['cxn'];
	foreach($fields as $key => $value){
		$value = strip_tags($value);
		$value = trim($value);
		//$value = mysqli_real_escape_string($cxn, $value);
		}
	return $fields;
	}	
	
// pull from front
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$eventName = $_REQUEST['eventName'];
$eventLocation = $_REQUEST['eventLocation'];
$eventBegin = $_REQUEST['eventBegin'];
$eventEnd = $_REQUEST['eventEnd'];
$eventDescrip = $_REQUEST['eventDescription'];

// encode to array for easy passing
$all_fields = array(
	"lat" => $latitude,
	"lng" => $longitude,
	"name" => $eventName,
	"loc" => $eventLocation,
	"begin" => $eventBegin,
	"end" => $eventEnd,
	"descrip" => $eventDescrip
	);


/// TO DO:
	/*
	 * need to add validation:
	 * ///Check for empty
	 * check that lat and lng are valid
	 * check that address geocodes (again???)
	 * ///check that times regex to valid
	 * ///check that event time comes after current date
	 * ///also check that event end is not before start
	 * check that name and description have at least 5 letters in them maybe?
	 */

// clean a bit:
$all_fields = clean_fields($all_fields);
extract($all_fields);

// session variables
$uid = $_SESSION['user_id'];


// main validation check
if(checkEmpties($all_fields)) {
	
	if(dateCheckValid($all_fields)) {
	
		if(dateCheckSensible($all_fields)) {
			
			if(check_for_dups($all_fields)) {
				// debugger option
				if($GLOBALS['debug'] == false){
					// enter event to main table:
					$query_post = "INSERT INTO user_events 
						(user_id, event_title, event_description, end_date, 
						start_date, date_created, public) 
						VALUES (?, ?, ?, ?, ?, NOW(), 1)";
					$stm = $cxn->prepare($query_post);
					$stm->bind_param("issss", $uid, $name, $descrip, $begin, $end);
					$stm->execute();
					$stm->close();
					
					// pull most recent event for ID
					$query_id = "SELECT MAX(event_id) AS event_id FROM user_events";
					$result = mysqli_query($cxn,$query_id)
						or    die ("Couldn't retrieve event list.");
					$row = mysqli_fetch_assoc($result);
					$event_id = $row['event_id']; 
					// NOW WE HAVE: $event_id;
					
					// enter event to location table
					$qry = "INSERT INTO event_address (event_id, address_text, x_coord, y_coord) 
							VALUES (?, ?, ?, ?)";
					$stm = $cxn->prepare($qry);
					$stm->bind_param("isdd", $event_id, $loc, $lat, $lng);
					$stm->execute();
					$stm->close();
					
					// Success:
					$arr = array("status" => 1, "message" => "event success!");
					echo json_encode($arr);
					}
				}
			else {
				//failure
				$arr = array("status" => 0, "message" => "Failed to create event... 
					Duplicate event detected!");
				echo json_encode($arr);
				}
			}
		else {
			//failure
			$arr = array("status" => 0, 
				"message" => "Failed to create event... 
				Start Date was after end date or Date was before current date");
			echo json_encode($arr);
			}
		}
	else {
		//failure
		$arr = array("status" => 0, 
			"message" => "Failed to create event... 
			Date was not valid!");
		echo json_encode($arr);
		}
	}
else {
	//failure
	$arr = array("status" => 0, 
		"message" => "Failed to create event... 
		empty fields!");
	echo json_encode($arr);
	}
	
?>