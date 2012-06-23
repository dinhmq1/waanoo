<?php
require("../php/cxn.php");
require("../php/HTML_output_lib.php");

// FLAG FOR INCLUDE YQL DB EVENTS
$GLOBALS['include_YQL'] = false;
$GLOBALS['NUMRESULT'] = 50;

// RESTRICTION ON ONLY NEW EVENTS PULLED
$DEBUG = true;

$date_search = date("Y-m-d H:m:s", time() - 60*60*24*1); // 12 HOURS EARLIER
$date_search_2 = date("Y-m-d H:m:s", time() + 60*60*24*45); // two weeks ahead
if($DEBUG == true) {
    $date_search = date("Y-m-d H:m:s", time() - 60*60*24*365);
    }
define("DATE_TO_SEARCH_FROM", $date_search);
define("DATE_TO_SEARCH_TO", $date_search_2);

// LIMIT IS SET TO 20
// REQUEST: 'lat' 'lon' 'offset'

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


    
// FROM php.net
function sortByOneKey(array $array, $key, $asc = true) {
    $result = array();
        
    $values = array();
    foreach ($array as $id => $value) {
        $values[$id] = isset($value[$key]) ? $value[$key] : '';
        }
        
    if ($asc) {
        asort($values);
        }
    else {
        arsort($values);
        }
        
    foreach ($values as $key => $value) {
        $result[$key] = $array[$key];
        }
        
    return $result;
    }   
    
    
// YQL GET ADDY
function get_address_YQL($event_id){
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * 
            FROM YQL_event_address
            WHERE 
            event_id = '$event_id'
            ";
            
    $res = mysqli_query($cxn, $qry)
        or die ("failed to get address".mysqli_error($cxn));
    $row = mysqli_fetch_assoc($res);
    return $row['address_text'];
    }
    
// YQL GET LAN LNG
function get_event_lat_lng($event_id){
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM YQL_event_address WHERE event_id='$event_id'";
    $res = mysqli_query($cxn, $qry)
        or die ("failed to get address".mysqli_error($cxn));
    $row = mysqli_fetch_assoc($res);
    
    $lat = $row['x_coord'];
    $lon = $row['y_coord'];
    
    $arr = array("latitude" => $lat, "longitude" => $lon);
    return $arr;
    }

// pulls event info based on ID
function get_all_event_list_YQL($event_id){
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM YQL_events WHERE '$event_id' = event_id";
    $res = mysqli_query($cxn,$qry)
        or   die ("Couldn't execute query.".mysqli_error($cxn));
    $row2 = mysqli_fetch_assoc($res);
    return $row2;
    }

function get_all_event_list_users($event_id){
    $cxn = $GLOBALS['cxn'];
    $qry = "SELECT * FROM user_events WHERE '$event_id' = event_id";
    $res = mysqli_query($cxn,$qry)
        or   die ("Couldn't execute query.".mysqli_error($cxn));
    $row2 = mysqli_fetch_assoc($res);
    return $row2;
    }
    
function getImageURL($event_id) {
    // Get image:
    // Get event images:
    $cxn = $GLOBALS['cxn'];
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
        
    return $image_url;
}


// YQL GET ADDRESS
function pull_ALL_events($lat, $lon, $offset){
    //could be dirty
    /*
    $offset = preg_replace("#[^0-9]#", "", $offset);
    $rows_per_page = 7;
    if($GLOBALS['include_YQL'] == false)
        $rows_per_page = 10;
        */
    
    // date searching
    $d = DATE_TO_SEARCH_FROM;
    $d2 = DATE_TO_SEARCH_TO;
    
    // DO YQL SECTION FIRST
    $cxn = $GLOBALS['cxn'];
    $main_array = array(); // create an emptry array
    
    if($GLOBALS['include_YQL'] == true) {
        $qry = "SELECT * FROM YQL_event_address
                WHERE 
                '$d' <= (SELECT end_date FROM YQL_events 
                WHERE event_id = YQL_event_address.event_id)
                ";
                //LIMIT $offset, $rows_per_page
                
        $res = mysqli_query($cxn, $qry)
            or die ("couldn't do the db loc thing...");
        
        
        while($row = mysqli_fetch_assoc($res)){
            extract($row);
            $d_total = distance($lat, $lon, $x_coord, $y_coord, "m");
            
            // the sub array
            $event = array(
                "origin" => "YQL", 
                "id" => $event_id,
                "distance" => $d_total,
                "address_DB" => 0,
                "lat" => $x_coord,
                "lon" => $y_coord
                );
            //$distances[$event_id] = $d_total;
            array_push($main_array, $event);
            }//end compare and extract loc data while.
        }// END YQL
    
    
    // DO USER SECTION NEXT
    $qry = "SELECT * FROM event_address
            WHERE
            '$d' <= (SELECT end_date FROM user_events 
            WHERE event_id = event_address.event_id)";
            //LIMIT $offset, $rows_per_page
            
    $res = mysqli_query($cxn, $qry)
        or die ("couldn't do the db loc thing...");
    
    while($row = mysqli_fetch_assoc($res)){
        extract($row);
        $d_total = distance($lat, $lon, $x_coord, $y_coord, "m");
        // the sub array
        $event = array(
            "origin" => "user", 
            "id" => $event_id,
            "distance" => $d_total,
            "address_DB" => $address_text,
            "lat" => $x_coord,
            "lon" => $y_coord
            );
        //$distances[$event_id] = $d_total;
        array_push($main_array, $event);
        }//end compare and extract loc data while.
    
    
    // NOW SORT THIS multidimen array
    $main_array = sortByOneKey($main_array, "distance");
    
    // MAIN LOOP
    $search_output = "";
    
    // you have to do the limit down here haha
    $offset = preg_replace("#[^0-9]#", "", $offset);
    $rows_per_page = $GLOBALS['NUMRESULT'];
    if($GLOBALS['include_YQL'] == false)
        $rows_per_page = $GLOBALS['NUMRESULT'];
    
    $i = 0;
    foreach($main_array as $event_array) {
        // offset = 
        if($i > ($offset + $rows_per_page)) {
            //echo "break tripped: i=$i and Offset=$offset, rows/page=$rows_per_page";
            break;
            }
        if(!($i < $offset)) {
            //echo "continue tripped: i=$i and offset=$offset"; 

                /*
                        echo "<pre>";
                        print_r($event_array);
                        echo "</pre>";
                */
            // THIS PULLS EVENT INFO VIA ID
            //print_r($event_array);
            extract($event_array);
            
            if($origin == "YQL"){
            
                $event_row = get_all_event_list_YQL($id);
                if($event_row != null){
                    extract($event_row);
                    }
                
                $image_url = getImageURL($event_id);
    
                $all_vars = array(
                    "event_id" => $event_id,
                    "user_id" => 0,
                    "event_description" => $event_description,
                    "event_title" => $event_title,
                    "start_date" => $start_date,
                    "country_name" => $country_name,
                    "venue_name" => $venue_name,
                    "venue_zip" => $venue_zip,
                    "venue_state" => $venue_state,
                    "lat" => $lat,
                    "lon" => $lon,
                    "distance"=> $distance,
                    "search_output" => $search_output,
                    "image_url" => $image_url
                    );
            
                $search_output = add_xml_event_node($all_vars); //see search_functions.php
                }
            else if($origin == "user"){
                // do user stuff here
                $event_row = get_all_event_list_users($id);
                if($event_row != null){
                    extract($event_row);
                    }
                    
                $image_url = getImageURL($event_id);
                
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
            
                $search_output = add_xml_event_node($all_vars); //see search_functions.php
                }
            }// end inner else
        $i++;
        }//end loop for location search  FOREACH
        
    $content = "<?xml version='1.0' encoding='utf-8'?><query><status>1</status><message>Got $i events!</message><numResult>$i</numResult><events>$search_output</events></query>";

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
$results = pull_ALL_events($lat, $lon, $offset);
//$results = preg_replace("#[ ]#", "", $results);

echo $results;
?>
