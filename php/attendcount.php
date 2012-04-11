<?php
require("cxn.php");

if(isset($_REQUEST['eventID'])){
	$event_id = $_REQUEST['eventID'];
	$sql = "SELECT * FROM attendees 
			WHERE 
			event_id = '$event_id'";
	$qry = mysqli_query($cxn, $sql)
		or die("failed to select ftom attendees table");
	$count = mysqli_num_rows($qry);
	
	$arr = array("status" => 1, "count" => $count);
	echo json_encode($arr);
	}
else {
	$arr = array("status" => 0, "count" => 0);
	echo json_encode($arr);
	}
?>
