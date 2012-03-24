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
			Location: $addy <br>
			Venue Name: $venue_name <br>
			Description: ".strip_tags($event_description)."<br>	
			Id: $event_id <br>
		</td>	
		</tr>
		</table>
	</div>
	<p>&nbsp;</p>
	";

	return $search_output;
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
	//$lng = longitide coordinates
	//$lat = latitude coordinates only 6 decimal places
	//$event_id = will be key in the associative array

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
//need to use assort() to sort an associative array.
asort($distances); //now array is sorted by shortest distances first.

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
		
	$all_vars = array("event_id" => $event_id,
						"event_description" => $event_description,
						"event_title" => $event_title,
						"start_date" => $start_date,
						"country_name" => $country_name,
						"venue_name" => $venue_name,
						"search_output" => $search_output
						);

	$search_output = search_output_func($all_vars); //see search_functions.php
	}//end loop for location search	 FOREACH

// spit out the formatted stuff
echo $search_output;	
?>
