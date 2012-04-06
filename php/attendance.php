<?php
session_start();
require('cxn.php');

$event_id = $_REQUEST['eventID'];
$event_id = preg_replace("#[^0-9]#", "", $event_id);

if(@$_SESSION['signed_in'] == true) {
	$uid = $_SESSION['user_id'];
	
	$sql = "SELECT * FROM attendees 
			WHERE user_id = '$uid' 
			AND
			event_id = '$event_id'";
	$qry = mysqli_query($cxn, $sql)
		or die("failed to select ftom attendees table");
	
	$row = mysqli_fetch_assoc($qry);
	if($row == null) {
		// user is not attending yet
		$sql = "INSERT INTO attendees
				(user_id, event_id)
				VALUES ('$uid', '$event_id')
				";
		$qry = mysqli_query($cxn, $sql)
			or die("failed to add attendance");
		
		$arr = array("msg" => "you are now attending!", "status" => 2);
		echo json_encode($arr);
		}
	else {
		// user already marked as attending. Remove from list.
		$sql = "DELETE FROM attendees
				WHERE  user_id = '$uid' 
				AND
				event_id = '$event_id'";
		$qry = mysqli_query($cxn, $sql)
			or die("failed to delete attendance record");

		$arr = array("msg" => "you are no longer attending", "status" => 1);
		echo json_encode($arr);
		}
	}
else {
	$arr = array("msg" => "not signed in", "status" => 0);
	echo json_encode($arr);
	}

?>
