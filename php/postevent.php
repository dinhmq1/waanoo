<?php

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

$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$eventName = $_REQUEST['eventName'];
$eventLocation = $_REQUEST['eventLocation'];
$eventBegin = $_REQUEST['eventBegin'];
$eventEnd = $_REQUEST['eventEnd'];
$eventDescrip = $_REQUEST['eventDescription'];


// failure:
$arr = array("status" => 0, "message" => "failed");
echo json_encode($arr);
?>
