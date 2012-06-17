<?php 
if(isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $event_id = preg_replace("#[^0-9]#","",$event_id);
    }
else {
    header('Location: index.php');
    
    }

?>
<?php 
require 'php/header.php';
require 'php/cxn.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Your location:</span>
<br />
<br />
<?php
// Get event info
$sql = "SELECT * FROM user_events WHERE event_id='$event_id'";
$res = mysqli_query($cxn, $sql);
$row = mysqli_fetch_assoc($res);
// make available: 
//event_id  user_id     event_title     event_description   end_date    start_date  date_created    public  is_contactable  contact_type    contact_info
extract($row);

// Get location info:
$sql = "SELECT * FROM event_address WHERE event_id='$event_id'";
$res = mysqli_query($cxn, $sql);
$row = mysqli_fetch_assoc($res);
// make available:
//address_id    event_id    address_text    x_coord     y_coord
extract($row);

// Get user info
$sql = "SELECT * FROM user_list WHERE user_id='$user_id'";
$res = mysqli_query($cxn, $sql);
$row = mysqli_fetch_assoc($res);
// more vars available:
//user_id   email   password    first_name  last_name   date_added  last_login  last_ip     privlege_level  sex
extract($row);


// Get pageviews:
$sql = "SELECT * FROM pageviews WHERE event_id='$event_id'";
$res = mysqli_query($cxn, $sql);
//$row = mysqli_fetch_assoc($res);
$num_pageviews = mysqli_num_rows($res);


// Get RSVP info:
$sql = "SELECT 
        user_id AS uid, 
        event_id AS eid, 
        (SELECT first_name FROM user_list WHERE user_list.user_id = attendees.user_id)
        AS user_first_name,
        (SELECT last_name FROM user_list WHERE user_list.user_id = attendees.user_id)
        AS user_last_name,
        (SELECT email FROM user_list WHERE user_list.user_id = attendees.user_id)
        AS user_email
        FROM 
        attendees WHERE 
        event_id='$event_id'
        LIMIT 0, 50"; // NOTE THE 50 LIMIT!
$res = mysqli_query($cxn, $sql);
$num_RSVP = mysqli_num_rows($res);
$rsvp_name_array = Array();
while($row = mysqli_fetch_assoc($res)) {
    extract($row);
    $user = Array(
        "uid"=>$uid, 
        "user_first_name"=>$user_first_name, 
        "user_last_name"=>$user_last_name, 
        "user_email"=>$user_email
        );
    array_push($rsvp_name_array, $user);
    }


// format contact info
if($is_contactable == 1) {
    $contact_info_text = $contact_info;
    }
else 
    $contact_info_text = "N/A";
  
// display tabular stats
echo "<h1>Event: $event_title <br /></h1>
    Created by: $first_name $last_name <br />
    User email: $email <br />
    Event Contact info: $contact_info_text <br />
    Pageviews: $num_pageviews <br />
    Number of RSVPS: $num_RSVP  <br />
    Start Date: $start_date<br />
    End Date: $end_date<br />
    Date Created: $date_created<br />
    Where: $address_text <br />
    Lat, Lon: $x_coord, $y_coord <br />
    Description: $event_description<br />
    ";


// Make RSVP list:
echo "<h3>RSVP List:</h3>";
$arr = Array();
foreach($rsvp_name_array as $arr) {
    extract($arr);
    echo "$user_first_name $user_last_name | $user_email <br />";
    }

?>

<!-- Make pageview map -->
<h3>Pageview map: </h3>
    <div id='pageviewMapEmbed' style='position:relative;height:500px;width:800px;'>
    
    </div>
<script>
$(document).ready(function() {
   openPageviewMap(<?php echo $event_id;?>, "pageviewMapEmbed", true);
});
</script>


<br />
<?php require("php/all_popups.php"); ?>
<?php require 'php/footer.php';?>
