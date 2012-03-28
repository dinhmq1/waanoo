<?php
// DONT MOVE/REMOVE THIS: MUST BE HERE BEFORE ANY CONTENT SENT TO BROWSER!!!!!!
session_start();
if(isset($_SESSION['signed_in'])  && $_SESSION['signed_in'] == true){
	$usr = $_SESSION['fname'];
	$logged_in = "Hi, $usr";
	$logged_in_bool = true;
	}
else{
	$logged_in = "You are not logged in!";
	$logged_in_bool = false;
	}
?>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
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
					Location Wrong?</span>
				</li>
					&nbsp;&nbsp;&nbsp;&nbsp;
		        <li>
					<span id="postEventButton" onClick='open_post_event()'>Post Event</span>
				</li>
					&nbsp;&nbsp;&nbsp;&nbsp;
		        <li>
					<span id="advancedSearchButton">Advanced Search</span>
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
						LogOut!</span>
						<span id='signout-errors'></span>";
					}
				else{
					echo 
					"login with facebook:<br />
						email:
							<input type='text' id='login-email' size='10' />
						password:
							<input type='password' id='login-password' size='10' />
						<span class='login-button' onClick='signIn()'>
						GO!
						</span>
						<span id='loginNotes'></span>
		                <br />
		                Don't have an account? <br /> 
						<span class='login-button' id='signupBtn'>
		                Sign Up!
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
								<span class='login-button' id='submit-signup'>
								Submit!</span> <br /> 
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
	<span class='login-button' onClick='close_map_selector()'>
	I'm Done!
	</span>
</div>

<div id='postEventForm-wrapper'>
	<h3>Post an Event:</h5>
	<span id='cancelPostEventBtn' onClick='close_post_event()'>Cancel!</span>
	<div id='postEventForm'>
		<form>
			Title: <input id='eventName' type='text' /><br />
			Where will it be?<input type='text' id='eventLocation' />
			<span class='btn' id="setLocation" onClick='reset_coords()'>Test it!</span><br />
			When will it be?:<input type="text" id="eventDateBegin" name="date" /><br />
			When will it end?:<input type="text" id="eventDateEnd" name="date_end" /><br />
			Describe your event: <br />
			<textarea name="event_description" id="eventDescription" rows="3" cols="30" maxlength="500"></textarea><br />
			<!-- Upload a photo for your event:<input type="file" name="image"><br /> -->
			<!-- Tags (eg: food, pool): <input  id='eventTags' type='text'/><br /> -->
		</form>
		<span id='eventPostErrors'> </span><br>
		<span  class='btn' onClick='submitNewEvent()'>Submit!</span>
	</div>
	
	<div id='miniMapCanvas'>
		<!-- this is where we look up the reverse geocoding stuff -->
	
	</div>
</div>

