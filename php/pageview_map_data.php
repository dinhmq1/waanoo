<?php
session_start();
require("cxn.php");

$allow_ALL = true;

if(!$allow_ALL) {
    // first off, check that we are signed in:
    if(@$_SESSION['signed_in'] != true) {
        $arr = array("status" => 0, 
            "message" => "User not signed in!");
        echo json_encode($arr);
        exit();
        }
}


/*
 * form front
 * 
 * eventData = {
        eventID: event_id
        };
        */

$event_id = $_REQUEST['eventID'];
$event_id = preg_replace("#[^0-9]#", "", $event_id);

// session variables
$uid = @$_SESSION['user_id'];


/***  OPTIONAL: makes it so only event creator can see map!
 */ 
$qry = "SELECT user_id FROM user_events 
        WHERE event_id='$event_id'
        ";
$res = mysqli_query($cxn, $qry);
$row = mysqli_fetch_assoc($res);
$db_uid = $row['user_id'];

// see if we should check
if(!$allow_ALL) {
    if($uid != $db_uid and @$_SESSION['privleges'] != "admin") {
        $arr = array("status" => 0, 
            "message" => "Event does not belong to user!");
        echo json_encode($arr);
        exit();
        }
    }
/***********************************************/



/** Get all pageview data from DB for corresponding event 
 */
$qry = "SELECT * FROM pageviews 
        WHERE event_id='$event_id'";
$res = mysqli_query($cxn, $qry);

$latLon = array();
while($row = mysqli_fetch_assoc($res)) {
    $lat = $row['lat'];
    $lon = $row['lon'];
    $ts = $row['timestamp'];
    
    array_push($latLon, array("lat" => $lat, "lon" => $lon, "timestamp" => $ts));
    }


/**** Get location data for the actual event, to center the map
 **/
$qry = "SELECT * FROM event_address 
        WHERE event_id='$event_id'
        ";
$res = mysqli_query($cxn, $qry);
$row = mysqli_fetch_assoc($res);
$lat_event = $row['x_coord'];
$lon_event = $row['y_coord'];

//$latLon = json_encode($latLon);
echo json_encode(array("status" => 1, "data" => $latLon, "lat_event" => $lat_event, "lon_event" => $lon_event));
?>
