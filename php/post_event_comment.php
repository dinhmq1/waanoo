<?php
session_start();
require('cxn.php');

// check if user is signed in
if(@$_SESSION['signed_in'] != true) {
	$arr = array("status" => 0, 
		"message" => "User not signed in!");
	echo json_encode($arr);
	exit();
	}

// prepare needed vars
$event_id = $_REQUEST['eventID'];
$message = $_REQUEST['message'];
$uid = $_SESSION['user_id'];
$unixtime = time();

// clean data
$event_id = preg_replace("#[^0-9]#", "", $event_id);


// check for dumplicates:
$qry = "SELECT * FROM event_comments
        WHERE event_id='$event_id'
        ORDER BY timestamp ASC";
$res = mysqli_query($cxn, $qry);
$duplicate = 0;
while($row = mysqli_fetch_assoc($res)) {
    if(($row['user_id'] == $uid and $row['message'] == $message) or  
        ($row['user_id'] == $uid and $row['timestamp'] == $unixtime))
        $duplicate++;
    }

// reject if duplicate criteria met
if($duplicate > 0) {
    $arr = array("status" => 0, 
		"message" => "Duplicate event detected!");
	echo json_encode($arr);
	exit();
    }
    

$qry = "INSERT INTO event_comments
        (event_id, user_id, timestamp, message)
        VALUES (?, ?, ?, ?)";
$stm = $cxn->prepare($qry);
$stm->bind_param("iiis", $event_id, $uid, $unixtime, $message);
$stm->execute();
$stm->close();

$arr = array("status" => 1, 
		"message" => "message posted!");
echo json_encode($arr);
?>
