<?php
require('cxn.php');

function search_output_func($all_vars)
	{
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
	
function get_address($event_id){
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT * FROM YQL_event_address WHERE event_id='$event_id'";
	$res = mysqli_query($cxn, $qry)
		or die ("failed to get address".mysqli_error($cxn));
	$row = mysqli_fetch_assoc($res);
	return $row['address_text'];
	}

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
function get_all_event_list($event_id)
	{
	$cxn = $GLOBALS['cxn'];
	$qry = "SELECT * FROM YQL_events WHERE '$event_id' = event_id";
	$res = mysqli_query($cxn,$qry)
		or   die ("Couldn't execute query.".mysqli_error($cxn));
    $row2 = mysqli_fetch_assoc($res);
    return $row2;
	}

$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];

$qry = "SELECT * FROM YQL_event_address";
$res = mysqli_query($cxn, $qry)
			or die ("couldn't do the db loc thing...");

$distances = array(); // create an emptry array

while($row = mysqli_fetch_assoc($res)){
	extract($row);

	//from above section where coordinates are pulled from google using XML
	$d_lat = $lat - $x_coord;
	$d_lng = $lon - $y_coord;

	//computes distance using pythagorean theorem
	$d_total = sqrt(pow($d_lat,2)+pow($d_lng,2));

	//adds distance to the empty array in 
	//id=key and distance=value association.
	$distances[$event_id] = $d_total;
	}//end compare and extract loc data while.

//now to sort the array
arsort($distances, $sort_flags = SORT_NUMERIC); //now array is sorted by shortest distances first.

$search_output = "";
//print_r($distances);

foreach ($distances as $event_id => $distance) 
	{
	// THIS PULLS EVENT INFO VIA ID
	//echo "id: $event_id";
	$event_row = get_all_event_list($event_id);
	if($event_row != null){
		extract($event_row);
		}
	
	$lat_lon_arr = 	get_event_lat_lng($event_id);
	if($lat_lon_arr != null){
		extract($lat_lon_arr);
		}
	
	$lat = preg_replace("#[^0-9\.-]#", "", $lat);
	$lon = preg_replace("#[^0-9\.-]#", "", $lon);
	//echo $lat.$lon."<br>";
	//echo "lat1: $lat lon1: $lon lat2: $latitude lon2: $longitude <br>";
	//echo "lat1: ".(float)$lat." lon1: ".(float)$lon." lat2: ".(float)$latitude." lon2: ".(float)$longitude."<br>";
	$distance = distance((float)$lat, (float)$lon, (float)$latitude, (float)$longitude, "m");
		
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

	$search_output = search_output_func($all_vars); //see search_functions.php
	}//end loop for location search	 FOREACH

// spit out the formatted stuff
echo $search_output;	
?>
