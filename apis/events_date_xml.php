<?php
require("../php/cxn.php");
require("../php/HTML_output_lib.php");

// RESTRICTION ON ONLY NEW EVENTS PULLED
$date_search = date("Y-m-d H:m:s", time() - 60*60*24*365); // 24 HOURS EARLIER
$distance_tolerance = 50; // miles
$GLOBALS['NUMRESULT'] = 50;

function add_xml_event_node($inpt_array) {
    extract($inpt_array);
    $urlRoot = "http://waanoo.com/";
    $search_output .= "
    <event>
      <name>$event_title</name>
      <description>$event_description</description>
      <eventID>$event_id</eventID> 
      <userID>$user_id</userID>  
      <startDate>$start_date</startDate>
      <endDate>$end_date</endDate>
      <venueAddress>$venue_address</venueAddress>
      <latitude>$lat</latitude>
      <logitude>$lon</logitude>
      <distance>$distance</distance>
      <isContactable>$isContactInfo</isContactable>
      <contactInfo>$contactInfo</contactInfo>
      <contactType>$contactType</contactType>
      <imageURL>$urlRoot$image_url</imageURL>
    </event>";
    
    return $search_output;
    }

function main($lat, $lon, $offset, $date_search, $distance_tolerance) {
    
    $rows_per_page = $GLOBALS['NUMRESULT'];
    $cxn = $GLOBALS['cxn'];
    // select everything where enddate is after this $date_searcg var
    // then you should order by start date DESC
    $sql = "SELECT * FROM user_events
            WHERE end_date >= '$date_search'
            ORDER BY start_date DESC
            LIMIT $offset, $rows_per_page";
            
    $res = mysqli_query($cxn, $sql)
            or die("could not pull events");
    //echo $sql;
    
    $count = mysqli_num_rows($res);
    //echo "Count:".$count;
    if($count == 0) {
        //user has no events!
        echo "<?xml version='1.0' encoding='utf-8' ?>
                <query>
                <status>0</status>
                <message>No Events!</message>
                <numResult>0</numResult>
                </query>";
        exit();
        }
    
    
    // all results will be appended to this var:
    $search_output = "";
    while($row = mysqli_fetch_assoc($res)) {
        
        //event_id  user_id event_title event_description   end_date    start_date  date_created    public
        extract($row);
        //echo "pulling event $event_id <br />";
        
        $query_id = "SELECT * FROM event_address WHERE event_id = '$event_id'"; 
        $res2 = mysqli_query($cxn, $query_id)
            or die("failed to pull address");
        $row_addy = mysqli_fetch_assoc($res2);
        //address_id    event_id    address_text    x_coord y_coord
        extract($row_addy);
        $distance = distance($x_coord, $y_coord, (double)$lat, (double)$lon, "m");
        //echo $distance."<br />";
        
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
        
        
        /* after everything is extracted:
            assemble the event and make the html output
            */
        $count_outputted = 0;
        if($distance <= $distance_tolerance) {
            $count_outputted += 1;
            $all_vars = array(
                "event_id" => $event_id,
                "user_id" => $user_id,
                "event_description" => $event_description,
                "event_title" => $event_title,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "venue_address" => $address_text,
                "lat" => $x_coord,
                "lon" => $y_coord,
                "distance"=> $distance,
                "search_output" => $search_output,
                "isContactInfo" => $is_contactable,
                "contactInfo" => $contact_info,
                "contactType" => $contact_type,
                "image_url" => $image_url
                );
        
            $search_output = add_xml_event_node($all_vars); //see search_functions.php
            }
        
        }
    $content = "<?xml version='1.0' encoding='utf-8'?><query><status>1</status><message>Got $count_outputted events!</message><numResult>$count_outputted</numResult><events>$search_output</events></query>";
    return $content;
    }

// look for if offset was sent from load more button
if(isset($_REQUEST['offset']))
    $offset = $_REQUEST['offset'];
else
    $offset = 0;

// for testing if we are within distance tolerances
$lat = @$_REQUEST['lat'];
$lon = @$_REQUEST['lon'];
if(isset($_REQUEST['lat']) == false or isset($_REQUEST['lon']) == false) {
    $lat = "39.13269879539557";
    $lon = "-84.50568744995115";
}

// extract the two vars from main
$results = main($lat, $lon, $offset, $date_search, $distance_tolerance);

echo $results;
?>
