<?php

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
		$count = mysqli_num_rows($qry);
		if($count == 0) {
			return "
				<span id='attendingBtn_$event_id' class='btnTemplate' onClick='attendingEvent($event_id)'>
					Attending?
				</span>
					&nbsp;
				<span id='attendingLoader_$event_id' style='display:none'>
					<img src='images/ajax-loader-transp-arrows.gif' />
				</span>";
			}
		else {
			return "
				<span id='attendingBtn_$event_id' class='btnTemplateGreen' onClick='attendingEvent($event_id)'>
					Already Attending
				</span>
					&nbsp;
				<span id='attendingLoader_$event_id' style='display:none'>
					<img src='images/ajax-loader-transp-arrows.gif' />
				</span>";
			}
		}
	else {
		return "
			<span id='attendingBtn_$event_id' class='btnTemplate' onClick='attendingEvent($event_id)'>
				Attending?
			</span>
				&nbsp;
			<span id='attendingLoader_$event_id' style='display:none'>
				<img src='images/ajax-loader-transp-arrows.gif' />
			</span>";
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
	//$addy = get_address($event_id);
	$del_btn = deleteBtn($user_id, $event_id);
	$edit_btn = editBtn($user_id, $event_id);
	$attend_btn = attendBtn($user_id, $event_id);
	$count_attend = getNumAttend($event_id);
	
	$search_output .= "
	<div class='eventSingle'>
		$del_btn
		$edit_btn
		
                <h3><b>".strip_tags($event_title)."</b></h3>
                <table class='eventSingle'>
			
                        
			<tr>
				<td><b>Date:</b></td> 
                                <td>".strip_tags($day)."</td> 
                        </tr> 
                        
                        
                        <tr>
                                 <td><b>Time:</b></td>
                                 <td>".strip_tags($hour)." </td>
			</tr>
                        
                        
			<tr>
				<td><b>Location:</b></td>
                                <td>".strip_tags($venue_address)."</td>
					&nbsp;&nbsp;					
					<br><br>
			</tr>
                        

			<tr>
				<td><b>Description:</b></td>
                                <td>".strip_tags($event_description)."</td>	
			</tr>
                        

			<tr>
				<td><b>Distance:</b></td>
                                <td>".round($distance, 1)." miles </td>
			</tr>
                
                
			<tr>
					<td onClick='openEventMap($lat, $lon, \"".strip_tags($venue_address)."\")'>
					<a href='#' class='btnTemplate'>Show Map</a>

					</td>
				<td id='attendingBtn_$event_id' onClick='attendingEvent($event_id)'>
					<a href='#' class='btnTemplate'>$attend_btn</a>
                                           &nbsp;&nbsp;<small>RSVP'd so far: $count_attend</small>
				</td>
					
			
                                    
       
			</tr>
		</table>

		
	</div>
	&nbsp;
	";
	
	return $search_output;
	}


// ONLY FOR YQL EVENTS
function search_output_func_YQL($all_vars){
	extract($all_vars);
	
	$day = format_date($start_date);
	$hour = format_time($start_date);
	$addy = get_address_YQL($event_id);
	
	//$del_btn = deleteBtn($user_id, $event_id);
	//$edit_btn = editBtn($user_id, $event_id);

	$search_output .= "
	<div class='eventSingle'>
		<table>
		<tr>
		<td>
			<h3>".strip_tags($event_title)."</h3>
			Date: $day  Time: $hour <br>
			Country: $country_name <br>
				<b>Location:</b> $addy, $venue_state, $venue_zip
					&nbsp;&nbsp;
					<span class='attendingBtn' onClick='openEventMap($lat, $lon, \"$addy $venue_state $venue_zip\")'> 
						Show Map
					</span>
					<br>
			
			Venue Name: $venue_name <br>
			Description: ".strip_tags($event_description)."<br>	
			Distance: ".round($distance, 1)." miles <br>
			Id: $event_id <br>
		</td>	
		</tr>
		</table>
	</div>
	<br />
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
	$hr = date("h", strtotime($in_date));
	$hr = intval($hr);
	return $hr.date(":m a", strtotime($in_date));
	}
	


?>
