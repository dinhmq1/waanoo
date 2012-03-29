<?php
session_start();
require('cxn.php');
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
	
function clean_fields($fields){
	//$cxn = $GLOBALS['cxn'];
	foreach($fields as $key => $value){
		$value = strip_tags($value);
		$value = trim($value);
		//$value = mysqli_real_escape_string($cxn, $value);
		}
	return $fields;
	}	
	
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$eventName = $_REQUEST['eventName'];
$eventLocation = $_REQUEST['eventLocation'];
$eventBegin = $_REQUEST['eventBegin'];
$eventEnd = $_REQUEST['eventEnd'];
$eventDescrip = $_REQUEST['eventDescription'];

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
	 * Check for empty
	 * check that lat and lng are valid
	 * check that address geocodes (again???)
	 * check that times regex to valid
	 * check that event time comes after current date
	 * also check that event end is not before start
	 * check that name and description have at least 5 letters in them maybe?
	 */

// clean a bit:
$all_fields = clean_fields($all_fields);
extract($all_fields);

// session variables
$uid = $_SESSION['user_id'];

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

// failure:
$arr = array("status" => 1, "message" => "event success!");
echo json_encode($arr);
?>
