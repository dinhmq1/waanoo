<?php
require("cxn.php");
require("HTML_output_lib.php");
session_start();

// RESTRICTION ON ONLY NEW EVENTS PULLED
$date_search = date("Y-m-d H:m:s", time() - 60*60*24); // 24 HOURS EARLIER
$distance_tolerance = 50; // miles

function main($lat, $lon, $offset, $date_search, $distance_tolerance) {
	$cxn = $GLOBALS['cxn'];
	$sql = "SELECT * FROM user_events
			WHERE end_date >= '$date_search'
			ORDER BY end_date ASC";
	$res = mysqli_query($cxn, $sql)
			or die("could not pull events");
	//echo $sql;
	
	$count = mysqli_num_rows($res);
	//echo "Count:".$count;
	if($count == 0) {
		//user has no events!
		$arr = array("status" => 0, "content" => "No events!");
		echo json_encode($arr);
		exit();
		}
	
	
	// all results will be appended to this var:
	$search_output = "";
	while($row = mysqli_fetch_assoc($res)) {
		
		//event_id	user_id	event_title	event_description	end_date	start_date	date_created	public
		extract($row);
		//echo "pulling event $event_id <br />";
		
		$query_id = "SELECT * FROM event_address WHERE event_id = '$event_id'"; 
		$res2 = mysqli_query($cxn, $query_id)
			or die("failed to pull address");
		$row_addy = mysqli_fetch_assoc($res2);
		//address_id	event_id	address_text	x_coord	y_coord
		extract($row_addy);
		$distance = distance($x_coord, $y_coord, $lat, $lon, "m");
		
		/* after everything is extracted:
			assemble the event and make the html output
			*/
		if($distance <= $distance_tolerance) {
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
				"search_output" => $search_output
				);
		
			$search_output = search_output_func_users($all_vars); //see search_functions.php
			}
		
		}
				
	$content_array = array("status" => 1, "content" => $search_output);
	return $content_array;
	}

// look for if offset was sent from load more button
if(isset($_REQUEST['offset']))
	$offset = $_REQUEST['offset'];
else
	$offset = 0;

// for testing if we are within distance tolerances
$lat = $_REQUEST['lat'];
$lon = $_REQUEST['lon'];

// extract the two vars from main
$results = main($lat, $lon, $offset, $date_search, $distance_tolerance);
extract($results);

// echo out as json to frontend
$arr = array("status" => $status, "content" => $content);
echo json_encode($arr);
?>
