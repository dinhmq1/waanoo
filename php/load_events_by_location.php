<?php
require('cxn.php');

// RESTRICTION ON ONLY NEW EVENTS PULLED
$date_search = date("Y-m-d H:m:s", strtotime(time() - 60*60*12)); // 12 HOURS EARLIER
define("DATE_TO_SEARCH_FROM", $date_search);

// ONLY FOR YQL EVENTS
function search_output_func_YQL($all_vars){
	extract($all_vars);
	
	$day = format_date($start_date);
	$hour = format_time($start_date);
	$addy = get_address($event_id);

	$search_output .= "
	<div class='eventSingle'>
		<table>
		<tr>
		<td>
			<h3>".strip_tags($event_title)."</h3><br>
			Date: $day  Time: $hour <br>
			Country: $country_name <br>
			Location: $addy, $venue_state, $venue_zip <br>
			Venue Name: $venue_name <br>
			Description: ".strip_tags($event_description)."<br>	
			Distance: ".round($distance, 1)." miles <br>
			Id: $event_id <br>
		</td>	
		</tr>
		</table>
	</div>
	<p>&nbsp;</p>
	";

	return $search_output;
	}

// DISTANCE CALC
function distance($lat1, $lon1, $lat2, $lon2, $unit) { 
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	
	if ($unit == "K") {
		return ($miles * 1.609344);
		} 
	else if ($unit == "N") {
	  return ($miles * 0.8684);
		} 
	else{
		return $miles;
		}
	}

function format_date($in_date){
	return date("m/d", strtotime($in_date));
	}
	
function format_time($in_date){
	return date("H:m", strtotime($in_date));
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
function get_address($event_id){
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT * FROM YQL_event_address WHERE event_id='$event_id'";
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
function get_all_event_list_YQL($event_id)
	{
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT * FROM YQL_events WHERE '$event_id' = event_id";
	$res = mysqli_query($cxn,$qry)
		or   die ("Couldn't execute query.".mysqli_error($cxn));
    $row2 = mysqli_fetch_assoc($res);
    return $row2;
	}




// YQL GET ADDRESS
function pull_YQL_events($lat, $lon){
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT * FROM YQL_event_address";
	$res = mysqli_query($cxn, $qry)
		or die ("couldn't do the db loc thing...");
	
	$main_array = array(); // create an emptry array
	
	while($row = mysqli_fetch_assoc($res)){
		extract($row);
		$d_total = distance($lat, $lon, $x_coord, $y_coord, "m");
		
		// the sub array
		$event = array(
			"origin" => "YQL", 
			"id" => $event_id,
			"distance" => $d_total,
			//"index" => 0,
			"lat" => $lat,
			"lon" => $lon
			);
	
		//$distances[$event_id] = $d_total;
		array_push($main_array, $event);
		}//end compare and extract loc data while.
	
	// NOW SORT THIS multidimen array
	$main_array = sortByOneKey($main_array, "distance");
	
	// MAIN LOOP
	$search_output = "";
	foreach ($main_array as $event_array){
		//$event_array = $main_array[$i];
		
/*
		echo "<pre>";
		print_r($event_array);
		echo "</pre>";
		
*/
		// THIS PULLS EVENT INFO VIA ID
		//echo "id: $event_id";
		//print_r($event_array);
		extract($event_array);
		
		if($origin == "YQL"){
		
			$event_row = get_all_event_list_YQL($id);
			if($event_row != null){
				extract($event_row);
				}

			$all_vars = array(
				"event_id" => $event_id,
				"event_description" => $event_description,
				"event_title" => $event_title,
				"start_date" => $start_date,
				"country_name" => $country_name,
				"venue_name" => $venue_name,
				"venue_zip" => $venue_zip,
				"venue_state" => $venue_state,
				"distance"=> $distance,
				"search_output" => $search_output
				);
		
			$search_output = search_output_func_YQL($all_vars); //see search_functions.php
			}
		else if($origin == "user"){
			// do user stuff here
			
			}
		}//end loop for location search	 FOREACH
	return $search_output;
	}


// LAT LNG FROM FRON END
$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];

$search_output = pull_YQL_events($lat, $lon);

// spit out the formatted stuff
echo $search_output;	
?>
