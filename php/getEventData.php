<?php
session_start();
require("cxn.php");
$id = $_REQUEST['eventId'];


function mysqlTimeToAMPM($mysql_date) {
    // $mysql_date is like: 2012-06-23 19:18:20
    $date_section = substr($mysql_date, 0, 10);
    $hour = substr($mysql_date, 11, 2);
    $mins = substr($mysql_date, 14, 2);
    
    $ampm = "AM";
    if($hour > 12) {
        $ampm = "PM";
        $hour = $hour - 12;
        }
    
    return $date_section." ".$hour.":".$mins." ".$ampm;
}

if(@$_SESSION['signed_in'] == true) {

    // in this field:   event_id    user_id event_title event_description   end_date    start_date  date_created    public
    $sql = "SELECT 
            user_id, 
            event_title, 
            event_description, 
            end_date, 
            start_date, 
            date_created, 
            public,
            is_contactable,
            contact_info,
            contact_type,
            event_price,
            event_currency,
            homepage_url,
            tags_list,
            is_free,
            is_outdoors
            FROM user_events WHERE event_id=?";
    $stm = $cxn->prepare($sql);
    $stm->bind_param("i", $id);
    $stm->execute();
    
    $stm->bind_result($user_id, $event_title, $event_description, $end_date, $start_date, $date_created, $public, $is_contactable, $contact_info, $contact_type, $event_price, $event_currency,$homepage_url,$tags_list,$is_free,$is_outdoors);
    $stm->fetch();
    $stm->close();
    
    //IN THIS FIELD: address_id     event_id    address_text    x_coord y_coord
    $sql = "SELECT address_text FROM event_address WHERE event_id=? ";
    $stm = $cxn->prepare($sql);
    $stm->bind_param("i", $id);
    $stm->execute();
    
    $stm->bind_result($address_text);
    $stm->fetch();
    $stm->close();

    
    // make sure user is correct:
    if($_SESSION['user_id'] == $user_id or $_SESSION['privleges'] == "admin") {
        
        $start_date = mysqlTimeToAMPM($start_date);
        $end_date = mysqlTimeToAMPM($end_date);
        
        $arr = array(
            "status" => 1, 
            "msg" => "completed", 
            "event_title" => $event_title,
            "event_description" => $event_description,
            "end_date" => $end_date,
            "start_date" => $start_date,
            "public" => $public,
            "address_text" => $address_text,
            "date_created" => $date_created,
            "isContactInfo" => $is_contactable,
	        "contactInfo" => $contact_info,
	        "contactType" => $contact_type,
	        "eventPrice" => $event_price,
	        "eventCurrency" => $event_currency,
	        "homepageURL" => $homepage_url,
	        "tagsList" => $tags_list,
	        "isFree" => $is_free,
	        "isOutdoors" => $is_outdoors
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
