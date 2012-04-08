<?php
// DONT MOVE/REMOVE THIS: MUST BE HERE BEFORE ANY CONTENT SENT TO BROWSER!!!!!!
session_start();
if(isset($_SESSION['signed_in'])  && $_SESSION['signed_in'] == true){
	$usr = strip_tags($_SESSION['fname']);
	$logged_in = "Hi, $usr";
	$logged_in_bool = true;
	}
else {
	$logged_in = "You are not logged in!";
	$logged_in_bool = false;
	}
?>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<html xmlns:fb="https://www.facebook.com/2008/fbml">
	
	<title>Waanoo</title>
	<?php
	require('scripts.php');	
	?>
</head>


<body>
	<div id="header">
		&nbsp;waan<span id='infin'>∞</span>.com
        
		<span class='headerNav'>
			<ul>
				<li>
					<span id="showMapButton" onClick='open_map_selector()'> 
						<a href="#">Location Wrong?</a>
					</span>
				</li>
					&nbsp;&nbsp;&nbsp;&nbsp;
		        <li>
					<span id="postEventButton" onClick='open_post_event()'>
						<a href="#">Post Event</a>
					</span>
				</li>
					&nbsp;&nbsp;&nbsp;&nbsp;
		        <li>
					<span id="advancedSearchButton" onclick='advancedSearchBtn()'>
						<a href="#">Advanced Search</a>
					</span>
				</li>
					&nbsp;&nbsp;&nbsp;&nbsp;
				<li>
					<span id="myEventsButton" onClick="openMyEvents()">
						<a href="#">My Events</a>
					</span>
				</li>
	        </ul>
		</span>
		
		
		<span class="login_msg" id="login_msg">
			<?php
			echo $logged_in;
			?>
		</span>
		
		<input type='hidden' id='loginStatus' value=<?php echo "'$logged_in_bool'"; ?> />
		
        <div id="advancedButton"> 
          <img height='28' src ="images/arrow.png"/>
        </div>
        
        <div id="advancedPanel">
			<span id='login-field'>
			<?php
				if($logged_in_bool == true){
					
					echo "<br><br>You are signed in as ".$usr.
						" would you like to logout?<br>
						<span id='logout-button' class='login-button'
						onClick='signOutMain()'>
							<a href='#' class='btnTemplate'>Sign Out!</a>
							</span>
						<span id='signout-errors'></span>";
					}
				else{
					echo 
					"login:<br />
					
						<form id='loginMainForm' action='#'>
								email:
							<input type='text' id='login-email' size='10' />
								password:
							<input type='password' id='login-password' size='10' />
							<!--
							<span class='login-button' onClick='signIn()'>
								GO!
							-->
							<input class='login-button' type='submit' value='Submit' style='font-size:85%;'/>
						</form>
						
					<!--
						<span id='loginNotes'></span>
							<span id='facebookBtn'>
							<b>Facebook Login</b></span>
					-->			
								
				<div id='fb-root'></div>				
				<!-- FACEBOOK: needs to come after the fb-root div -->
				<script src='fb/fbauth.js'></script>
				
				<div class='fb-login-button'>Login with Facebook</div>
				<div id='loader' style='display:none'>
					<img src='images/ajax-loader-transp-arrows.gif' alt='loading' />
				</div>
				
		<!--		
		<div class='fb-registration' data-fields=\"[{'name':'name'}, {'name':'email'}, {'name':'favorite_car','description':'What is your favorite car?','type':'text'}]\" 
	        data-redirect-uri=\"http://waanoo.com\" >
	      </div>
	      -->
				<div id='user-info'></div>
									
	                <br />
	                
	                Don't have an account? <br /> 
					<span id='signupBtn' style='font-size:85%;'>
						<a href='#' class='btnTemplate'>Sign Up!</a>
	                </span> 
		<br/>
			<form>
				<div id='signupPanel'> 
					email: <input type ='text' id='email' /><input type='hidden' id='email_test' value='0'/>
					<span id='emailIsValid'></span><br /> 
					Password: <input type ='password' id='password' size='10' /><br /> 
					Password again: <input type ='password' id='passwordcheck' size='10' /><br /> 
					First Name: <input type ='text' id='firstname' size='10' /><br /> 
					Last Name: <input type ='text' id='lastname'  size='10' /><br />
					Sex: 
					<select id='sex' /> <br /> <!--hey dumbass, this should be drop down-->
						<option value='M'>male</option>
						<option vlaue='F'>female</option>
					</select><br/>
					<div id='signup-errors'></div>
					<span id='submit-signup' onClick='signUpMain()'>
						<a href='#' class='btnTemplate'>Submit!</a>
						</span> <br /> 
					<!--Should prolly restate that you can just connect with facebook here-->
				</div>
			</form> ";
					}
				?>
			</span>
        </div>
	</div>

<div id='map_wrapper'>
	Drag the marker to change your location:
	<div id='map_canvas'>
	</div>
	<span onClick='close_map_selector()'>
	<a href='#' class='btnTemplate'>I'm Done!</a>
	</span>
</div>

<div id='postEventForm-wrapper'>
	<h3>Post an Event:</h5>
	<span id='cancelPostEventBtn' onClick='close_post_event()'>
		<a href='#' class='btnTemplate'>Cancel</a>
		</span>
	<div id='postEventForm'>
		<form>
			Title: <input id='eventName' type='text' /><br />
			Where will it be?<input type='text' id='eventLocation' />
				<span id="setLocation" onClick='reset_coords()'>
				<a href='#' class='btnTemplate'>Test -></a>
				</span><br />
			When will it be?:<input type="text" id="eventDateBegin" name="date" /><br />
			When will it end?:<input type="text" id="eventDateEnd" name="date_end" /><br />
			Describe your event: 
			&nbsp; &nbsp; &nbsp; <span id="descriptionCount">0</span> / 500
			<br />
			<textarea name="event_description" id="eventDescription" rows="3" cols="40" maxlength="500"></textarea><br />
			<!-- Upload a photo for your event:<input type="file" name="image"><br /> -->
		<!--
			Tags: <input id='eventTags' type='text' /><br />
				<div id='eventTagsChoices'>
				Education: <input type="checkbox" id="t_education" value="education" /><br />
				Food: <input type="checkbox" id="t_food" value="food" /><br />
				Free Food: <input type="checkbox" id="t_ffood" value="free_food" /><br />
				Dancing: <input type="checkbox" id="t_dance" value="dancing" /><br />
				Club: <input type="checkbox" id="t_club" value="club" /><br />
				Religion: <input type="checkbox" id="t_religion" value="religion" /><br />
				Sports: <input type="checkbox" id="t_sports" value="sports" /><br />
				Jobs: <input type="checkbox" id="t_jobs" value="jobs" /><br />
				Live music: <input type="checkbox" id="t_lmusic" value="live_music" /><br />
				Ethnic: <input type="checkbox" id="t_ethnic" value="ethnic" /><br />
				University of cincinnati: <input type="checkbox" id="t_uc" value="UC" /><br />
				CCM <input type="checkbox" id="t_ccm" value="CCM" /><br />
				DAPP <input type="checkbox" id="t_daap" value="DAAP" /><br />
				Science <input type="checkbox" id="t_science" value="science" /><br />
				Engineering <input type="checkbox" id="t_eng" value="egineering" /><br />
				
				Custom: <input type="text" id="t_custom" /><br />
				<br />
					<span id='eventTagsBtn' onClick='closeEventTags()'>
						<a href='#' class='btnTemplate'>Close</a>
					</span>
				</div>
			
			Allow Users to Contact you? &nbsp; <input type="checkbox" id="allowContactEvtent" value="contact" /><br />
			<span id='contactingOptions'>
			<select id='eventContactType'>
				<option value='email'>email</option>
				<option value='phone'>phone</option>
			</select>
			Contact Info:<input type="text" id="contactInfo" /><br />
			</span>
		-->
			<input type='hidden' id='oldEventID' value="" />
		</form>
		<span id='eventPostErrors'> </span><br>
		<span  id='eventFormSubmitBtn' onClick='submitNewEvent()'>
			&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='btnTemplate'>Submit!</a>
		</span>
	</div>
	
	<div id='miniMapCanvas'>
		<!-- this is where we look up the reverse geocoding stuff -->
	</div>
</div>


<div id='EventMapWrapper'>
	Event Address: <span id='eventAddressText'></span>
	
	<div id='EventMapCanvas'>
	</div>
	<span onClick='getDirections()'>
		<a href='#' class='btnTemplate'>Get Directions</a>
	</span>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<span onClick='closeEventMap()'>
		<a href='#' class='btnTemplate'>I'm Done!</a>
	</span>
</div>
<div id='EventDirections'>
	<div id='EventDirectionsDisplay'></div>
</div>


<div id="myEventsWrapper">
	<span onClick="closeMyEvents()">
		<a href="#" class="btnTemplate" >close</a>
	</span>
	<div id="myEventsContents">
	</div>
</div>
