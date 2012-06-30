<?php
require("../php/cxn.php");
require("../php/HTML_output_lib.php");

/*** TO DO:
 * -check if signed in
 * -get uid
 * -clean the message
 * -clean the id
 * -generate timestamp
 * -post the data
 * 
 * 
 * -write retrieval script
 * -write text formatter for the comments
 */


function formatDate($timestamp) {
    return date("F j, Y, g:i a", $timestamp);
    }
    
function formatTime($timestamp) {
    return date("g:i a",$timestamp);
    }

// passed in user_id from event;
function getUserName($uid, $event_id) {
    // this selects the username of the message on the event
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM user_list
            WHERE user_id='$uid'";
    $res = mysqli_query($cxn, $qry);
    $row = mysqli_fetch_assoc($res);
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $privs = $row['privlege_level'];
    
    // select the event and check if the user id for the message == user id for event
    $qry2 = "SELECT user_id AS event_creator FROM user_events
            WHERE 
            event_id='$event_id'";
    $res2 = mysqli_query($cxn, $qry2);
    $row2 = mysqli_fetch_assoc($res2);
    if($row2 != NULL) {
        $event_creator = $row2['event_creator'];
    }
    else {
        $event_creator = 0;
    }
    
    //echo $event_creator." and uid is: ".$uid;
    if($privs == "admin"){
        return "$fname $lname (admin)";
        }
    if($event_creator == $uid) {
        return "$fname $lname (host)";
        }
    return "$fname $lname";
    }

function formatMsg($msg) {
    return substr($msg, 0, 500);
    }
    
// PASSED: message text, timestamp for msg, user id for comment, comment_id, event_id
function prepareComment($msg, $timestamp, $uid, $cid, $event_id) {
    $date = formatDate($timestamp);
    $name = getUserName($uid, $event_id);
    $msg = formatMsg($msg);
    return "<comment>
                <date>$date</date>
                <name>$name</name>
                <text>$msg</text>
                <userID>$uid</userID>
                <commentID>$cid</commentID>
                <eventID>$event_id</eventID>
            </comment>";
    } // end comment prep


$event_id = $_REQUEST['eventID'];
//echo "<br />eventid=".$event_id;
$event_id = preg_replace("#[^0-9]#", "", $event_id);
//echo "<br />eventid=".$event_id;

$qry = "SELECT * FROM event_comments
        WHERE event_id='$event_id'
        ORDER BY timestamp DESC";

$res = mysqli_query($cxn, $qry);
$numRows = mysqli_num_rows($res);
//echo "<br />result nums:".$numRows." eventid=".$event_id;

if($numRows > 0) {
    // parse results
    $txt = "";
    $counter = 0;
    while($row = mysqli_fetch_assoc($res)) {
        $counter++;
        $msg = $row['message'];
        $timestamp = $row['timestamp'];
        $uid = $row['user_id'];
        $cid = $row['message_id'];
        
        $txt .= prepareComment($msg, $timestamp, $uid, $cid, $event_id);
        }
    
    
    echo "<?xml version='1.0' encoding='utf-8'?>
            <query>
            <status>1</status>
            <numResult>$counter</numResult>
            <comments>$txt</comments>
            </query>";
    }
else {
    
    echo "<?xml version='1.0' encoding='utf-8'?>
            <query>
            <status>1</status>
            <numResult>0</numResult>
        </query>";
    }
?>
