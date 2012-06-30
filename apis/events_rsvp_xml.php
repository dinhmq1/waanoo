<?php
require("../php/cxn.php");

function pullUserInfo($uid) {
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM user_list WHERE user_id='$uid'";
    $res = mysqli_query($cxn, $qry) or die ("error:".mysqli_error($cxn));
    $row = mysqli_fetch_assoc($res);
    extract($row);
    // echo $email;
    return "<rsvp>
                <email>$email</email>
                <firstName>$first_name</firstName>
                <lastName>$last_name</lastName>
                <dateAdded>$date_added</dateAdded>
                <lastLogin>$last_login</lastLogin>
                <lastIP>$last_ip</lastIP>
                <privLevel>$privlege_level</privLevel>
                <sex>$sex</sex>
            </rsvp>";
    }

if(isset($_REQUEST['event_id'])) {
    $id = $_REQUEST['event_id'];
    $id = preg_replace("[^0-9]", "", $id);

    $qry = "SELECT * FROM  attendees WHERE event_id='$id'";
    $res = mysqli_query($cxn, $qry) or die ("error:".mysqli_error($cxn));

    $res_count = 0;
    $xml_assoc = "";
    while($row = mysqli_fetch_assoc($res)) {
        extract($row);
        //echo "$user_id <br />";
        $xml_assoc .= pullUserInfo($user_id);
        $res_count++;
        }
        
    $dt = date("F j, Y, g:i a");
    echo "<?xml version='1.0' encoding='utf-8'?>
            <query>
            <dateRequest>$dt</dateRequest>
            <numResult>$res_count</numResult>
            <rsvpList>$xml_assoc</rsvpList>
            </query>";
    }
else {
    echo "<?xml version='1.0' encoding='utf-8'?>
            <query>error</query>
            <numResult>0</numResult>
            <message>You must enter an event id in the event_id request param.</message>";
}
?>

