<?php
require("../php/cxn.php");
require("../php/HTML_output_lib.php");

// RESTRICTION ON ONLY NEW EVENTS PULLED
$DEBUG = true;

$date_search = date("Y-m-d H:m:s", time() - 60*60*24*1); // 12 HOURS EARLIER
$date_search_2 = date("Y-m-d H:m:s", time() + 60*60*24*45); // two weeks ahead
if($DEBUG == true) {
    $date_search = date("Y-m-d H:m:s", time() - 60*60*24*365);
    }
define("DATE_TO_SEARCH_FROM", $date_search);
define("DATE_TO_SEARCH_TO", $date_search_2);



function add_xml_event_node($inpt_array) {
    extract($inpt_array);
    $urlRoot = "http://waanoo.com/";
    $search_output .= "
    <event>
      <name>".strip_tags($event_title)."</name>
      <description>".strip_tags($event_description)."</description>
      <eventID>$event_id</eventID> 
      <userID>$user_id</userID>  
      <startDate>$start_date</startDate>
      <endDate>$end_date</endDate>
      <venueAddress>".strip_tags($venue_address)."</venueAddress>
      <latitude>$lat</latitude>
      <logitude>$lon</logitude>
      <distance>$distance</distance>
      <isContactable>$isContactInfo</isContactable>
      <contactInfo>".strip_tags($contactInfo)."</contactInfo>
      <contactType>$contactType</contactType>
      <imageURL>$urlRoot$image_url</imageURL>
    </event>";
    
    return $search_output;
    }


/**********************************************************************/
/**
 * Pulls the location data from DB. Returns as an array for processing
 **/
function get_event_address($event_id, $lat_user, $lon_user) {
    $cxn = $GLOBALS['cxn'];
    $sql = "SELECT * FROM event_address WHERE event_id='$event_id'";
    $res = mysqli_query($cxn, $sql)
            or die("err:".mysqli_error($cxn));
    $row = mysqli_fetch_assoc($res);
    extract($row);
    
    $dis = distance($lat_user, $lon_user, $x_coord, $y_coord, "m");
    return array(
        'address_DB' => $address_text,
        'lat' => $x_coord,
        'lon' => $y_coord,
        'distance' => $dis
        );
    }

/**********************************************************************/

// passed from ajax.
$searchTerm = @$_REQUEST['term'];
$lat_user = @$_REQUEST['latitude'];
$lon_user = @$_REQUEST['longitude'];

if(!isset($_REQUEST['term']) and !isset($_REQUEST['latitude']) and !isset($_REQUEST['longitude'])) {
    $lat_user = "39.125053883634465";
    $lon_user = "-84.52039194072267";
    $searchTerm = "bible"; // lol
}


// offset checker:
if(isset($_REQUEST['offset'])) {
    $offset = $_REQUEST['offset'];
    }
else {
    // the first 15
    $offset = 0;
    }

// Manipulation of ajax terms:
$term = "%$searchTerm%";//echo $term;


// using Mysqli prepared statements
$sql = "SELECT 
            event_id,   
            user_id,
            event_title,
            event_description,
            end_date,
            start_date,
            date_created,
            public,
            is_contactable,
            contact_type,
            contact_info
        FROM user_events
        WHERE ? <= start_date 
        AND (
        event_title LIKE ?
        OR 
        event_description LIKE ?
        )
        LIMIT ?, 10";

$stm = $cxn->prepare($sql);
$d = DATE_TO_SEARCH_FROM; // THIS GETS PASSED BY REF. Has to be a var.
$stm->bind_param('sssi', $d, $term, $term, $offset);
$stm->execute();
$stm->bind_result($event_id,    
    $user_id,
    $event_title,
    $event_description,
    $end_date,
    $start_date,
    $date_created,
    $public_event,
    $is_contactable,
    $contact_type,
    $contact_info);
    
$stm->store_result();

$resArr = array();

// MAIN LOOP
$search_output = "";
$count = 0;

while($stm->fetch())  {
    if($event_id != NULL) {
        $addressArray = get_event_address($event_id, $lat_user, $lon_user);
        extract($addressArray);
        
        // Get image:
        // Get event images:
        $sql3 = "SELECT image_url FROM event_images 
                WHERE event_id='$event_id'
                AND
                img_size='2'
                ORDER BY date_uploaded DESC
                LIMIT 0, 1";
        $res3 = mysqli_query($cxn, $sql3);
        if($res3 != NULL) {
            $row3 = mysqli_fetch_assoc($res3);
            $image_url = $row3['image_url'];
            if(strlen($image_url) < 10)
                $image_url = "images/buttons/placeholder_icons/placeholder_200.png";
            }
        else
            $image_url = "images/buttons/placeholder_icons/placeholder_200.png";
        
        $all_vars = array(
            "event_id" => $event_id,
            "user_id" => $user_id,
            "event_description" => $event_description,
            "event_title" => $event_title,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "venue_address" => $address_DB,
            "lat" => $lat,
            "lon" => $lon,
            "distance"=> $distance,
            "search_output" => $search_output,
            "isContactInfo" => $is_contactable,
            "contactInfo" => $contact_info,
            "contactType" => $contact_type,
            "image_url" => $image_url
            );
                
        $search_output = add_xml_event_node($all_vars);
        $count++;
        }
    }

$stm->free_result();
$stm->close();
$cxn->close();

echo "<?xml version='1.0' encoding='utf-8'?>
        <query>
            <status>1</status>
            <numResult>$count</numResult>
            <events>$search_output</events>
        </query>"; 
?>
