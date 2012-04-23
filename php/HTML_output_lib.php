<?php

/*** delete btn- pre filled args on js function ***/
function deleteBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) { 
		$uid_session = $_SESSION['user_id'];
		if($user_id == $uid_session or $_SESSION['privleges'] == "admin") {
			//echo "userid: $user_id, sessionid: $uid_session";
			return 
				"<div class='deleteBtn' id='del_$event_id' onClick='delEvent($event_id)'>
				<a href='#' class='btnTemplate'>Delete!</a>
				</div>";
			}
		else 
			return "";
		}
	else
		return "";
	}
	

/*** edit btn for events- pre-filled args on js function***/

function editBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) { 
		$uid_session = $_SESSION['user_id'];
		if($user_id == $uid_session or $_SESSION['privleges'] == "admin") {
			return 
				"<div class='editBtn' id='edit_$event_id' onClick='editEvent($event_id)'>
				<a href='#' class='btnTemplate'>Edit!</a>
				</div>";
			}
		else 
			return "";
		}
	else
		return "";
	}

/*** CHECKS IF IMAGE, IF THERE IS FIND IT AND MAKE A TAG ***/

function getEventImage($event_id) {
	$cxn = $GLOBALS['cxn'];
	$sql = "SELECT * FROM event_images 
			WHERE event_id='$event_id'
			AND
			active = 1
			AND
			img_size = 1
			ORDER BY list_order DESC";
	$res = mysqli_query($cxn, $sql)
		or die("image pull failed: ".mysqli_error($cxn));
	// order by list order... so then list_order descending and only get first result
	$count = mysqli_num_rows($res);
	$row = mysqli_fetch_assoc($res);
	$url = $row['image_url'];
	
	if($count > 0) {
		return "
				<img class='thumbImg' src='$url' />
			";
		}
	else {
		return "<img class='thumbImg' src='./images/buttons/placeholder_icons/placeholder_150.png' />";
		}
	}
	

/*** SHOWS CONTACT INFORMATION IF THERE IS ANY ****/	
	// maybe should like hide this by default? Make it an ajax call to go and get this info???
function contactInfoOrganizer($contactInfo, $contactType, $isContactInfo) {
	if($isContactInfo == 1) {
		if($contactType == "email") {
			return "<span class='contactDetails' >email at: ".strip_tags($contactInfo)."</span>";
			}
		if($contactType == "phone") {
			return "<span class='contactDetails' >call at: ".strip_tags($contactInfo)."</span>";
			}
		}
	else 
		return "";
	}


/*** SHOWS A BUTTON THAT CALLS JS FUNCTION WITH PRE-FILLED ARGS ***/

function attendBtn($user_id, $event_id) {
	if(@$_SESSION['signed_in'] == true) {
		$cxn = $GLOBALS['cxn'];
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT * FROM attendees 
				WHERE (user_id = '$user_id' 
				AND
				event_id = '$event_id')";
		$qry = mysqli_query($cxn, $sql)
			or die("failed to select ftom attendees table");
		$count = mysqli_num_rows($qry);
		if($count == 0) {
			return "
				<span id='attendingBtn_$event_id' onClick='attendingEvent($event_id)'>
				<img src='images/buttons/btns_content/btn_attend_inactive.png' />
				</span>
					&nbsp;
				<span id='attendingLoader_$event_id' style='display:none'>
					<img src='images/ajax-loader-transp-arrows.gif' />
				</span>";
			}
		else {
			return "
				<span id='attendingBtn_$event_id' onClick='attendingEvent($event_id)'>
				<img src='images/buttons/btns_content/btn_attend_active.png' />
				</span>
					&nbsp;
				<span id='attendingLoader_$event_id' style='display:none'>
					<img src='images/ajax-loader-transp-arrows.gif' />
				</span>";
			}
		}
	else { //NOT SIGNED IN
		return "
			<span id='attendingBtn_$event_id' onClick='attendingEvent($event_id)'>
			<img src='images/buttons/btns_content/btn_attend_inactive.png' />
			</span>
				&nbsp;
			<span id='attendingLoader_$event_id' style='display:none'>
				<img src='images/ajax-loader-transp-arrows.gif' />
			</span>";
		}
	}
	
/*** calcs number of people that click "attending" btn ***/

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
	
/** event description shortner **/

function eventDescriptionShortner($event_description) {
	$newDescrip = substr($event_description, 0, 50);
	return $newDescrip;
	}	
	
	
/*** the main output for user created events ***/	
	
function search_output_func_users($all_vars){
	extract($all_vars);
	/*
	echo "<pre>";
	print_r($all_vars);
	echo "</pre>";
	*/

	$day = format_date($start_date);
	$hour = format_time($start_date);
	//$addy = get_address($event_id);
	$del_btn = deleteBtn($user_id, $event_id);
	$edit_btn = editBtn($user_id, $event_id);
	$attend_btn = attendBtn($user_id, $event_id);
	$count_attend = getNumAttend($event_id);
	$event_img = getEventImage($event_id);
	$contact_info_div = contactInfoOrganizer($contactInfo, $contactType, $isContactInfo);
	$event_description = eventDescriptionShortner($event_description);
	
	$search_output .= "
	<div class='eventSingle'>
		
		<div class='eventImgContainer'>
			$event_img
		</div>
		
		<div class='eventInfoContainer'>
			
			<span class='eventTitle'>"
			.strip_tags($event_title)." 
			</span>
			
			<p class='space'></p>
				
			<b>Date: </b>".strip_tags($day)."
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				| 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b>Time: </b> ".strip_tags($hour)."            

			<p class='space'></p>
			
			<b>Description: </b>".strip_tags($event_description)." ... <br />
			
				
            <!-- HOLD OFF ON THIS:        
				<b>Location: </b>".strip_tags($venue_address)."       -->
			<!--
				<b>Distance: </b>".round($distance, 1)." miles. <br />
			-->
			
            $contact_info_div <br />
	    </div>        
           
            
        <div class='eventBtnContainer'>
			$del_btn
			<br />
			$edit_btn
			<br />
			<span onClick='openEventMap($lat, $lon, \"".strip_tags($venue_address)."\")'>
			<a href='#'>
				<img class='btnShowMap' src='images/buttons/btns_content/btn_map_inactive.png'/>
				</a>
			</span>
			
			<br />
			$attend_btn
			<br />
			<small>RSVP'd:</small> 
				<span id='att_count_$event_id'>
					$count_attend
				</span>
        </div>
            
	</div>
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


/** quick date formatter ***/ 
function format_date($in_date){
	return date("m.d.Y", strtotime($in_date));
	}
	
/*** quick time formatter for displaying times 
 * in standard american format from MySQL time ***/
function format_time($in_date){
	$hr = date("h", strtotime($in_date));
	$hr = intval($hr);
	return $hr.date(":i a", strtotime($in_date));
	}
	
?>
