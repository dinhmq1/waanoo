<?php
session_start();
require("cxn.php");
$id = $_REQUEST['eventId'];

if($_SESSION['signed_in'] == true) {
	// not prepared, using pregreplace
	$id = preg_replace("#[^0-9]#", "", $id);
	$qry = "SELECT * FROM user_events WHERE event_id = $id";
	$res = mysqli_query($cxn, $qry)
		or die("could not pull user_id".mysqli_error($cxn));
	$row = mysqli_fetch_assoc($res);
	
	if($row['user_id'] == $_SESSION['user_id']) {
		// now delete the event:
		$qry = "DELETE FROM user_events WHERE event_id = $id";
		$res = mysqli_query($cxn, $qry)
			or die("could not delete event");
		
		// delete coords too:
		$qry = "DELETE FROM event_address WHERE event_id = $id";
		$res = mysqli_query($cxn, $qry)
			or die("could not delete coordinates");
		
		$arr = array("status" => 1, "msg" => "Event Deleted.");
		echo json_encode($arr);
		}
	else {
		$arr = array("status" => 0, "msg" => "User ID incorrect.");
		echo json_encode($arr);
		}
	}
else {
	$arr = array("status" => 0, "msg" => "You were not signed in.");
	echo json_encode($arr);
	}
?>
