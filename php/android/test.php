<?php
if(isset($_REQUEST['status']) and isset($_REQUEST['message'])) {
	$stat = $_REQUEST['status'];
	$test = $_REQUEST['message'];
	
	// SHOULD BE:
	/*
	
		JSON_TO_SEND = {
			status: 1,
			message: "this is a test"
			};
	
	*/
	
	$arr = array("status" => $stat, "message" => $test);
	echo json_encode($arr);
	}
else {
	$arr = array("status" => 0, "message" => "Did not recieve any data, make sure to send 'status' and 'message'");
	echo json_encode($arr);
	}
?>
