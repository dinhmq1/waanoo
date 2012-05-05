<?php

require("cxn.php");
/*
 * eventData = {
        eventID: event_id,
        latitude: latitude,
        longitude: longitude
        }; */


$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];
$event_id = $_REQUEST['eventID'];

// lat and lon from front
    $event_id = preg_replace("#[^0-9]#", "", $event_id);
    $lat = preg_replace("#[^0-9\.-]#", "", $lat);
    $lon = preg_replace("#[^0-9\.-]#", "", $lon);
    $timestamp = time(); // unix time.

/* DB STRUCT
 *  pageviews:
        id=int, autoinc, pk
        event_id = int, event id foreign key_
        lat = double (10,6)
        lon = double (10,6)
        timestamp = int, unix time for pageviews
		*/

$qry = "INSERT INTO pageviews (event_id, lat, lon, timestamp)
        VALUES(?,?,?,?)
        ";
$stm = $cxn->prepare($qry);
$stm->bind_param("iddi", $event_id, $lat, $lon, $timestamp);
$stm->execute();
$stm->close();
    
if($stm != false) {
    $json_array = array("status" => 1);
    echo json_encode($json_array);
    }
else {
    $json_array = array("status" => 0);
    echo json_encode($json_array);
    }
?>
