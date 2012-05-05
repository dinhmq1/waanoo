<?php
session_start();
require('cxn.php');
require('simpleimage.php');
$GLOBALS['debug'] = false;

// first off, check that we are signed in:
if(@$_SESSION['signed_in'] != true) {
	$arr = array("status" => 0, 
		"message" => "Failed to create event... User not signed in!");
	echo json_encode($arr);
	exit();
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

// clean contact info for email:
function verify_email($email){
	if (!empty($email)){	
		if (preg_match("/^\w[[:alnum:]\.-_]+@[[:alnum:]\.-_]+\.[[:alnum:]]{2,4}$/i", $email) and filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			}
		else
			return false;	
		}//end if not empty clause
	else
		return false;
	} // end verify email. 
	
	
// clean contact info for phone:
function verify_phone($phone) {
	//print_r($phone);
	$p1 = $phone['phone1'];
	$p2 = $phone['phone2'];
	$p3 = $phone['phone3'];
	
	if(preg_match("/[0-9]{3}/", $p1) and preg_match("/[0-9]{3}/", $p2) and preg_match("/[0-9]{4}/", $p3)) 
		return true;
	else
		return false;
	}

function resizeAndSubmitImg($imageName, $event_id) {
	$cxn = $GLOBALS['cxn'];
	$image = new SimpleImage();
	//:--> ../images/img_temp/$imageName
	$imageTempDir = "../images/img_temp/".$imageName;
	
	$imageThumbnail = "../images/img_db/img_thumbnail/$imageName";
	$imageThumbURL = "images/img_db/img_thumbnail/$imageName";
	$imageMedium = "../images/img_db/img_medium/$imageName";
	$imageMedURL = "images/img_db/img_medium/$imageName";
	$image->load($imageTempDir); //this loads the image from our 'temp_img' directory.
	
	/*
	echo "eventid: $event_id, 
		imagename: $imageName, 
		imageThumbURL: $imageThumbURL";
	*/
		
	// others:
		//../images/img_db/img_medium/
		//../images/img_db/img_thumbnail/
	//section actually resizes images
	$image->resizeToWidth(500); 
	$image->save($imageMedium);
	
	$image->resizeToWidth(100);
	$image->save($imageThumbnail);
	
	// DIFFERENCE FROM THE REGULAR VERSION:
		/*	-select max list order from the imagelist where event id is matched
		 * 	-then increment by one
		 * 	-we are going to use the highest one
		 */
	$sql = "SELECT MAX(list_order) as max_list FROM event_images
			WHERE event_id='$event_id'";
	$res = mysqli_query($cxn, $sql)
			or die("could not select image list_order");
	$row = mysqli_fetch_assoc($res);
	$list_order = $row['max_list'];
	
	// add one
	$list_order++;
	
	// for thumb
	$description = "";
	$sql = "INSERT INTO event_images 
			(event_id, image_url, description, date_uploaded, list_order, active, img_size)
			VALUES (?, ?, ?, NOW(), ?, 1, 1)
			";
	$stm = $cxn->prepare($sql);
	$stm->bind_param("issi", $event_id, $imageThumbURL, $description, $list_order);
	$stm->execute();
	$stm->close();
		
	
	// for medium
	$sql = "INSERT INTO event_images 
			(event_id, image_url, description, date_uploaded, list_order, active, img_size)
			VALUES (?, ?, ?, NOW(), ?, 1, 2)
			";
	$stm = $cxn->prepare($sql);
	$stm->bind_param("issi", $event_id, $imageMedURL, $description, $list_order);
	$stm->execute();
	$stm->close();
	}	
	
	
// pull from front
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$eventName = $_REQUEST['eventName'];
$eventLocation = $_REQUEST['eventLocation'];
$eventBegin = $_REQUEST['eventBegin'];
$eventEnd = $_REQUEST['eventEnd'];
$eventDescrip = $_REQUEST['eventDescription'];
$oldID = $_REQUEST['oldID'];
$isImage = $_REQUEST['isImageSubmitted'];
$imageName = $_REQUEST['imageFileName'];

$isContactInfo = $_REQUEST['isContactInfoActive'];
$contactType = $_REQUEST['contactInfoType'];
$contactInfo = $_REQUEST['contactInfoContent'];


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


/***  only creator can edit
 */
$qry = "SELECT user_id FROM user_events 
        WHERE event_id='$event_id'
        ";
$res = mysqli_query($cxn, $qry);
$row = mysqli_fetch_assoc($res);
$db_uid = $row['user_id'];

if($uid != $db_uid or $_SESSION['privleges'] != "admin") {
	$arr = array("status" => 0, 
		"message" => "Event does not belong to user!");
	echo json_encode($arr);
	exit();
	}
/***********************************************/


// process the contact info, if any
$isOk = false;
if($isContactInfo == 1) {
	if($contactType == "email") {
		$isOk = verify_email($contactInfo);
		}
	
	if($contactType == "phone") {
		$isOk = verify_phone($contactInfo);
		$contactInfo = $contactInfo['phone1'].$contactInfo['phone2'].$contactInfo['phone3'];
		}
	}
else
	$isContactInfo = 0; // in case something nasty happened.


// main validation check
if(checkEmpties($all_fields)) {
	
	if(dateCheckValid($all_fields)) {
	
		if(dateCheckSensible($all_fields)) {
			
			// to lazy to remove this stucture
			if(($isContactInfo == 0 and $isOk == false) or ($isContactInfo == 1 and $isOk == true)) {
				// debugger option
				if($GLOBALS['debug'] == false){
					// enter event to main table:
					$query_post = "UPDATE user_events
						SET  user_id=?, event_title=?, event_description=?, 
						 start_date=?, end_date=?,
                         is_contactable=?, contact_type=?, contact_info=?
						WHERE event_id=?";
					$stm = $cxn->prepare($query_post);
					$stm->bind_param("issssissi", $uid, $name, $descrip, $begin, $end, $isContactInfo, $contactType, $contactInfo, $oldID);
					$stm->execute();
					$stm->close();
					
					/*
					// pull most recent event for ID
					$query_id = "SELECT MAX(event_id) 
								AS event_id FROM user_events";
					$result = mysqli_query($cxn,$query_id)
						or    die ("Couldn't retrieve event list.");
					$row = mysqli_fetch_assoc($result);
					$event_id = $row['event_id']; 
					// NOW WE HAVE: $event_id;
					*/
					
					$event_id = $oldID;
					
					// enter event to location table
					$qry = "UPDATE event_address 
							SET address_text=?, x_coord=?, y_coord=? 
							WHERE event_id=?";
					$stm = $cxn->prepare($qry);
					$stm->bind_param("sddi",  $loc, $lat, $lng, $event_id);
					$stm->execute();
					$stm->close();
					
					
					if($isImage == true) {
						// then we will submit the image
						resizeAndSubmitImg($imageName, $event_id);
						}
					
					// Success:
					$arr = array("status" => 1, "message" => "event success!");
					echo json_encode($arr);
					}
				}
			else {
				//failure
				$arr = array("status" => 0, "message" => "Failed to create event... 
					Contact info invalid!");
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
