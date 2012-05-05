<?php
//error_reporting(0);
require('cxn.php');
require('HTML_output_lib.php');
session_start();

// FLAG FOR INCLUDE YQL DB EVENTS
$GLOBALS['include_YQL'] = false;

// RESTRICTION ON ONLY NEW EVENTS PULLED
$DEBUG = true;

$date_search = date("Y-m-d H:m:s", time() - 60*60*24*1); // 12 HOURS EARLIER
$date_search_2 = date("Y-m-d H:m:s", time() + 60*60*24*45); // two weeks ahead
if($DEBUG == true) {
	$date_search = date("Y-m-d H:m:s", time() - 60*60*24*365);
	}
define("DATE_TO_SEARCH_FROM", $date_search);
define("DATE_TO_SEARCH_TO", $date_search_2);

	
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
	$rows_per_page = 9;
	if($GLOBALS['include_YQL'] == false)
		$rows_per_page = 9;
	
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
					"search_output" => $search_output
					);
			
				$search_output = search_output_func_YQL($all_vars); //see search_functions.php
				}
			else if($origin == "user"){
				// do user stuff here
				$event_row = get_all_event_list_users($id);
				if($event_row != null){
					extract($event_row);
					}
				
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
					"contactType" => $contact_type
					);
			
				$search_output = search_output_func_users($all_vars); //see search_functions.php
				}
			}// end inner else
		$i++;
		}//end loop for location search	 FOREACH
	return $search_output;
	}


// LAT LNG FROM FRON END
$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];

// TRYING TO JUST DO ONE SCRIPT SO WE ADD SOME OPTIONS
if(isset($_REQUEST['offset'])) {
	$offset = $_REQUEST['offset'];
	}
else {
	// the first 15
	$offset = 0;
	}
	
$search_output = pull_ALL_events($lat, $lon, $offset);


// echo "offset: $offset";
// spit out the formatted stuff
echo $search_output;	
?>
