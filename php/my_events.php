<?php
session_start();
require("cxn.php");


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


function deleteBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) { 
		$uid_session = $_SESSION['user_id'];
		if($user_id == $uid_session or $_SESSION['privleges'] == "admin") {
			return 
				"<div class='deleteBtn' id='del_$event_id' onClick='delEvent($event_id)'>
				<a href='#' class='btnTemplate'>Delete!</a>
				</div>
				";
			}
		else 
			return "";
		}
	else
		return "";
	}

function editBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) { 
		$uid_session = $_SESSION['user_id'];
		if($user_id == $uid_session or $_SESSION['privleges'] == "admin") {
			return 
				"<div class='editBtn' id='edit_$event_id' onClick='editEvent($event_id)'>
				<a href='#' class='btnTemplate'>Edit!</a>
				</div>
				";
			}
		else 
			return "";
		}
	else
		return "";
	}

function attendBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) {
		$cxn = $GLOBALS['cxn'];
		$sql = "SELECT * FROM attendees 
				WHERE user_id = '$user_id' 
				AND
				event_id = '$event_id'";
		$qry = mysqli_query($cxn, $sql)
			or die("failed to select ftom attendees table");
		$row = mysqli_fetch_assoc($qry);
		if($row == null) {
			return "Attending ?";
			}
		else {
			return "<span style='background-color: #32C43C;' >Attending!</span>";
			}
		}
	else {
		return "Attending ?";
		}
	}

function getNumAttend($event_id) {
	$cxn = $GLOBALS['cxn'];
	$sql = "SELECT * FROM attendees
			WHERE event_id = '$event_id'
			";
	$res = mysqli_query($cxn, $sql)
		or die("error getting the attendees");
	$row_count = mysqli_num_rows($res);
	return $row_count;
	}
	
function search_output_func_users($all_vars){
	extract($all_vars);
	
	$day = format_date($start_date);
	$hour = format_time($start_date);

	$del_btn = deleteBtn($user_id, $event_id);
	$edit_btn = editBtn($user_id, $event_id);
	$attend_btn = attendBtn($user_id, $event_id);
	$count_attend = getNumAttend($event_id);
	
	$search_output .= "
	<div class='eventSingle'>
		$del_btn
		$edit_btn
		<ul>
			<li>
				<h3>".strip_tags($event_title)."</h3>
			</li>
			<li>
				<b>Date:</b> ".strip_tags($day)."  Time: ".strip_tags($hour)." <br>
			</li>
			<li>
					<b>Location:</b> ".strip_tags($venue_address)."
					&nbsp;&nbsp;
					
					<br>
			</li>
			<li>
				<b>Description:</b> ".strip_tags($event_description)."<br>	
			</li>
			<li>
				<b>Distance:</b> ".round($distance, 1)." miles <br>
			</li>
			<li>
					<span onClick='openEventMap($lat, $lon, \"$venue_address\")'>
					<a href='#' class='btnTemplate'>Show Map</a>
					</span>
				<span id='attendingBtn_$event_id' onClick='attendingEvent($event_id)'>
					<a href='#' class='btnTemplate'>$attend_btn</a>
				</span>
					&nbsp;&nbsp;&nbsp;
					<small>RSVP'd so far: $count_attend</small>
			</li>
		</ul>
	</div>
	&nbsp;
	";
	
	return $search_output;
	}



if($_SESSION['signed_in'] == true) {
	$uid = $_SESSION['user_id'];
	
	$query_events = "SELECT * FROM user_events 
					WHERE user_id ='$uid'
					ORDER BY date_created ASC";
	$result = mysqli_query($cxn,$query_events)
		or die("failed to find events");
	
	$array =array();
	
	if($row == null) {
		//user has no events!
		$arr = array("status" => 1, "content" => "You have not created any events!");
		echo json_encode($arr);
		exit();
		}
	
	$search_output = "";
	
	while($row = mysqli_fetch_assoc($result)){
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
		$distance = distance($x_coord, $y_coord, $current_lat, $current_lng, "m");
		
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
		
			$search_output = search_output_func_YQL($all_vars); //see search_functions.php
		}
	
	$arr = array("status" => 1, "content" => $search_output);
	echo json_encode($arr);
	}
else {
$arr = array("status" => 0, "content" => "You are not logged in!");
echo json_encode($arr);
}
?>