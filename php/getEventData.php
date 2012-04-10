<?php
session_start();
require("cxn.php");
$id = $_REQUEST['eventId'];

if(@$_SESSION['signed_in'] == true) {

	// in this field:	event_id	user_id	event_title	event_description	end_date	start_date	date_created	public
	$sql = "SELECT 
			user_id, event_title, event_description, end_date, start_date, date_created, public 
			FROM user_events WHERE event_id=?";
	$stm = $cxn->prepare($sql);
	$stm->bind_param("i", $id);
	$stm->execute();
	
	$stm->bind_result($user_id, $event_title, $event_description, $end_date, $start_date, $date_created, $public);
	$stm->fetch();
	$stm->close();
	
	//IN THIS FIELD: address_id		event_id	address_text	x_coord	y_coord
	$sql = "SELECT address_text FROM event_address WHERE event_id=? ";
	$stm = $cxn->prepare($sql);
	$stm->bind_param("i", $id);
	$stm->execute();
	
	$stm->bind_result($address_text);
	$stm->fetch();
	$stm->close();

	
	// make sure user is correct:
	if($_SESSION['user_id'] == $user_id) {
		$arr = array(
			"status" => 1, 
			"msg" => "completed", 
			"event_title" => $event_title,
			"event_description" => $event_description,
			"end_date" => $end_date,
			"start_date" => $start_date,
			"public" => $public,
			"address_text" => $address_text,
			"date_created" => $date_created
			);
		echo json_encode($arr);
		}
	else {
		$arr = array(
			"status" => 0,
			"msg" => "user ID does not match records"
			);
		echo json_encode($arr);
		}
	}
else {
	$arr = array(
		"status" => 0,
		"msg" => "not signed in"
		);
	echo json_encode($arr);
	}
?>
