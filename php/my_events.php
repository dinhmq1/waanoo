<?php
session_start();
require("cxn.php");
require("HTML_output_lib.php");

if($_SESSION['signed_in'] == true) {
	$uid = $_SESSION['user_id'];
	
	$query_events = "SELECT * FROM user_events 
					WHERE user_id ='$uid'
					ORDER BY date_created ASC";
	$result = mysqli_query($cxn,$query_events)
		or die("failed to find events");
	
	$count = mysqli_num_rows($result);
		
	if($count == 0) {
		//user has no events!
		$arr = array("status" => 1, "content" => "You have not created any events!");
		echo json_encode($arr);
		exit();
		}
	
	$search_output = "";
	
	while($row = mysqli_fetch_assoc($result)) {
		//event_id	user_id	event_title	event_description	end_date	start_date	date_created	public
		extract($row);
		
		// $event_id = the event id to find the address with
		$query_id = "SELECT * FROM event_address WHERE event_id = '$event_id'"; 
		$res = mysqli_query($cxn, $query_id)
			or die("failed to pull address");
		$row_addy = mysqli_fetch_assoc($res);
		//address_id	event_id	address_text	x_coord	y_coord
		extract($row_addy);
		
		$current_lat = $_REQUEST['current_lat'];
		$current_lon = $_REQUEST['current_lon'];
		$distance = distance($x_coord, $y_coord, $current_lat, $current_lon, "m");
		
		/* after everything is extracted:
			assemble the event and make the html output
			*/
			
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
	
	$arr = array("status" => 2, "content" => $search_output);
	echo json_encode($arr);
	}
else {
$arr = array("status" => 1, "content" => "You are not logged in!");
echo json_encode($arr);
}
?>
