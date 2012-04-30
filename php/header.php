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
		
		<input type='hidden' id='loginStatus' value=<?php echo "'$logged_in_bool'"; ?> />
		
		<!-- the following occurs entirely within this wrapper -->
		<div id='login-logout-wrapper'>
			
			<?php
				if($logged_in_bool == true){	
					echo "
						<div id='logoutWrapper'>
                        <span id='nameMsg'>
                            Hi ".$usr.
                            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
						<span id='logout-button' onClick='signOutMain()'>
							<a href='#' class='testBlackBtn'>Sign Out!</a>
							</span>
						<span id='signout-errors'></span>
                        &nbsp;
						</div>";
					}
				else{
					echo "
						<form id='loginMainForm' action='#'>
							<input class='loginField' type='text' id='login-email' placeholder=' email' size='10' />
							<input class='loginField' type='password' id='login-password' placeholder=' password' size='10' />
							
							<input class='testBlackBtn' type='submit' value='submit'  />
								
							&nbsp;&nbsp;&nbsp;
							
								<a href='#' id='signupBtn' class='testBlackBtn'>
									<!-- <img src='./images/buttons/btns_content/btn_signup_inactive.png' /> -->
                                    sign up!
								</a>
							&nbsp;
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
					";
					}
				?>
			
        </div> <!-- end #login-logout-wrapper -->
	</div><!-- end #header -->
	
