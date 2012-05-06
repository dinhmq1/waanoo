<?php
session_start();
require('cxn.php');

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

// PASSED: comment_id, user_id for comment, event_id
function deleteBtnComment($cid, $uid, $event_id) {
    $cxn = $GLOBALS['cxn'];
    
    // get user ID for comment
    $uid_session = $_SESSION['user_id'];
    
    $qry = "SELECT * FROM user_list
            WHERE user_id='$uid'";
    $res = mysqli_query($cxn, $qry);
    $row = mysqli_fetch_assoc($res);
    
    // get user id for admin
    $privs = $row['privlege_level'];
    
    if($uid_session == $uid or $privs == "admin")
        return "<a href='#' class='testBlackBtn' onClick='deleteEventComment($event_id, $cid)'>
                    delete
                </a>
            ";
    else 
        return "";
    }

function formatDate($timestamp) {
    return date("F j, Y, g:i a", $timestamp);
    }
    
function formatTime($timestamp) {
    return date("g:i a",$timestamp);
    }

function getUserName($uid) {
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM user_list
            WHERE user_id='$uid'";
    $res = mysqli_query($cxn, $qry);
    $row = mysqli_fetch_assoc($res);
    
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $privs = $row['privlege_level'];
    $db_uid = $row['user_id'];
    if($privs == "admin"){
        return "<font color='red'>$fname $lname *admin</font>";
        }
    if($uid == $db_uid) {
        return "<font color='blue'>$fname $lname *event owner</font>";
        }
    return "$fname $lname";
    }

function formatMsg($msg) {
    return substr($msg, 0, 500);
    }
    
// PASSED: message text, timestamp for msg, user id for comment, comment_id, event_id
function prepareComment($msg, $timestamp, $uid, $cid, $event_id) {
    $date = formatDate($timestamp);
   // $time = formatDate($timestamp);
    $name = getUserName($uid);
    $msg = formatMsg($msg);
    $delbtn = deleteBtnComment($cid, $uid, $event_id);
    return "<div class='eventComment'>
                <span style='font-size:50%;'>
                    <span>$date</span>
                    &nbsp;&nbsp;
                    |
                    &nbsp;&nbsp;
                    <span style='font-size:75%;'>
                        $name</span>
                    </span>
                    <span>
                        $delbtn
                        </span>
                <br />
                <span class='eventCommentText'>
                    ".strip_tags($msg)."
                </span>
            </div>
            ";
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
    while($row = mysqli_fetch_assoc($res)) {
        $msg = $row['message'];
        $timestamp = $row['timestamp'];
        $uid = $row['user_id'];
        $cid = $row['message_id'];
        
        $txt .= prepareComment($msg, $timestamp, $uid, $cid, $event_id);
        }
    
    $arr = array("status" => 1, "messages" => $txt);
    echo json_encode($arr);
    }
else {
    $arr = array("status" => 1, "messages" => "There are none yet!");
    echo json_encode($arr);
    }
?>
