<?php
session_start();
require("cxn.php");
require("HTML_output_lib.php");

$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];
$event_id = $_REQUEST['eventID'];

try {
    $event_id = preg_replace("#[^0-9]#", "", $event_id);
    
    $sql = "SELECT * FROM user_events
            WHERE event_id='$event_id'";
    $res = mysqli_query($cxn, $sql);
            //or die("could not pull event id");
    $row = mysqli_fetch_assoc($res);
    extract($row);
    
    $sql = "SELECT * FROM event_address
            WHERE event_id='$event_id'";
    $res = mysqli_query($cxn, $sql);
            //or die("coul not pull address from event");
    $row = mysqli_fetch_assoc($res);
    extract($row);
    
    // lat and lon from front
    $lat = preg_replace("#[^0-9\.-]#", "", $lat);
    $lon = preg_replace("#[^0-9\.-]#", "", $lon);
    
    // debug:
    //echo $lat."_".$lon."_".$x_coord."_".$y_coord;
    $distance = distance($lat, $lon, $x_coord, $y_coord, "m");
    
    $all_vars = array(
        "event_id" => $event_id,
        "user_id" => $user_id,
        "event_description" => $event_description,
        "event_title" => $event_title,
        "start_date" => $start_date,
        "end_date" => $end_date,
        "venue_address" => $address_text,
        "lat" => $x_coord,
        "lon" => $y_coord,
        "distance"=> $distance,
        "isContactInfo" => $is_contactable,
        "contactInfo" => $contact_info,
        "contactType" => $contact_type
        );
    
    echo json_encode(array("content" => singleEventOutput($all_vars), "status" => 1));
    }
catch (Exception $e) {
    echo json_encode(array("content" => $e->getMessage(), "status" => 0));
    }
?>
