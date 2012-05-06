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
$comment_id = $_REQUEST['commentID'];
$uid = $_SESSION['user_id'];

// clean data
$event_id = preg_replace("#[^0-9]#", "", $event_id);
$comment_id = preg_replace("#[^0-9]#", "", $comment_id);
//echo "comment id:".$comment_id." event_id:".$event_id." uid:".$uid;


// check if exists:
$qry = "SELECT * FROM event_comments
        WHERE message_id='$comment_id'
        ";
$res = mysqli_query($cxn, $qry);
$row = mysqli_fetch_assoc($res);
$db_uid = $row['user_id'];

// check if admin
$qry = "SELECT * FROM user_list
        WHERE user_id='$uid'";
$res = mysqli_query($cxn, $qry);
$row = mysqli_fetch_assoc($res);
$privs = $row['privlege_level'];

//echo "uid:$uid db_id:$db_uid privs:$privs";
if($db_uid == $uid or $privs == 'admin') {
    // This is it.
    $qry = "DELETE FROM event_comments
            WHERE message_id='$comment_id'";
    $res = mysqli_query($cxn, $qry);
    if($res != false) {
        $arr = array("status" => 1, "message" => "Deleted!");
        echo json_encode($arr);
        }
    else {
        $arr = array("status" => 0, "message" => "Could not delete event!");
        echo json_encode($arr);
        }
    }
else {
    $arr = array("status" => 0, "message" => "Could not delete event! ..");
	echo json_encode($arr);
    }
?>
