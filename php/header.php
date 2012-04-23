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
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" value="IE=9" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta name="description" content="Find local events and parties near you! 
              Want to let others know about your event and/or party? Share them on Waanoo!" />
              
	
	<title>Waanoo.com</title>
	<?php
	require('scripts.php');	
	?>
</head>


<body>
	<div id="header">
		<img id='headerLogo' src='images/logos/logo_header.png' />
        
		<span class='headerNav'>
			<ul>
				<li>
					
				</li>
				<!--
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
				-->
	        </ul>
		</span>
		
		<!--
		<span class="login_msg" id="login_msg">
			<?php
			echo $logged_in;
			?>
		</span>
		-->
		
		<input type='hidden' id='loginStatus' value=<?php echo "'$logged_in_bool'"; ?> />
		
		<!--
        <div id="advancedButton"> 
          <img height='28' src ="images/arrow.png"/>
        </div>
        -->
        
        <!--
        
        <div id="advancedPanel">
			<span id='login-field'>
			
			-->
			
			<?php
				if($logged_in_bool == true){
					
					echo "
						>Hi".$usr.
						"
						<span id='logout-button'
						onClick='signOutMain()'>
							<a href='#' class='btnTemplate'>Sign Out!</a>
							</span>
						<span id='signout-errors'></span>";
					}
				else{
					echo "
						<form id='loginMainForm' action='#'>
							<input class='loginField' type='text' id='login-email' placeholder=' email' size='10' />
							<input class='loginField' type='password' id='login-password' placeholder=' password' size='10' />
							<input class='login-button' type='submit' value='sign in' style='font-size:85%;'/>
						
						<div class='login-button'  id='signupBtn'>
							<a href='#'>
							<button>sign up!</button>
							</a>
						</div> 
						</form>
						
						
						<span id='loginNotes'></span>
						
								<!--	
										<span id='facebookBtn'>
										<b>Facebook Login</b></span>
								-->			
								<!-- 
								DISABLED TEMPORARILY 				
								<div id='fb-root'></div>				
								<script src='fb/fbauth.js'></script>

								<div class='fb-login-button'>Login with Facebook</div>
								<div id='loader' style='display:none'>
								<img src='images/ajax-loader-transp-arrows.gif' alt='loading' />
								</div>
								-->
								<!--		
								<div class='fb-registration' data-fields=\"[{'name':'name'}, {'name':'email'}, {'name':'favorite_car','description':'What is your favorite car?','type':'text'}]\" 
								data-redirect-uri=\"http://waanoo.com\" >
								</div>
								-->
								 
		<div id='user-info'></div>
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
						</span> 
					&nbsp;&nbsp;&nbsp;
						<span id='ajaxLoaderSignUp'>
							<img src='images/ajax-loader-transp-arrows.gif' />
						</span>
					<br /> 
					<!--Should prolly restate that you can just connect with facebook here-->
				</div>
			</form> ";
					}
				?>
			</span>
        </div>
	</div>

<div id='map_wrapper'>
	Move marker to reset location ... 
		<span onClick='close_map_selector()'>
		<a href='#' class='btnTemplate'>I'm Done!</a>
	</span>
	<div id='map_canvas'></div>
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
			<textarea name="event_description" id="eventDescription" rows="4" cols="35" maxlength="500"></textarea><br />
			Upload Image: <a href="#" class='btnTemplate' id='uploader'>upload!</a> 
				<!--	<small>(popup)</small> -->
				<span id='imgUploadedSpot'></span>
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
			-->
			
			<br />
			Allow Users to Contact you? &nbsp; <input type="checkbox" id="allowContactEvtent" value="contact" /><br />
			<span id='contactingOptions'>
			<select id='eventContactType'>
				<option value='email'>email</option>
				<option value='phone'>phone</option>
			</select>
			Contact Info:
				<span id='contactInfo'>
					<input type="text" id="emailContactInfo" /><br />
				</span>	
			</span>
			
			
			<input type='hidden' id='imgFileLocation' value='' />
			<input type='hidden' id='isThereImage' value='0' />
			<input type='hidden' id='oldEventID' value="" />
		</form>
		<span id='eventPostErrors'> </span><br>
		<span  id='eventFormSubmitBtn' onClick='submitNewEvent()'>
			&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='btnTemplate'>Submit!</a>
		</span>
		&nbsp;&nbsp;&nbsp;
		<span id="ajaxLoaderPostEvent">
			<img src="images/ajax-loader-transp-arrows.gif" />
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
