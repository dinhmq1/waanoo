<?php
// DONT REMOVE THIS
session_start();
if(isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true){
	$usr = $_SESSION['username'];
	$logged_in = "Hi, $usr";
	}
else
	$logged_in = "You are not logged in!";
?>


<html>
	<head>
	<meta charset="utf-8"/>
	<title>Waanoo</title>
<?php
require('scripts.php');	
?>

</head>
<body>
	<div id="header">&nbsp;waan<span id='infin'>∞</span>.com
       <!-- <div id='login-text'> [login]</div> -->
		
		<span class="login_msg" id="login_msg">
			<?php
			echo $logged_in;
			?>
		</span>	
		
		
        <div id="advancedButton"> 
          <img height='28' src ="images/arrow.png"/>
        </div>
        
        <div id="advancedPanel">
			<span id='login-field'>
			
                login with facebook:<br />
                
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
						<div id="signupPanel"> 
						email: <input type ="text" id="email" /><input type='hidden' id="email_test" value='0'/><br /> 
						<span id="emailIsValid"></span>
						Password: <input type ="password" id="password" size='10' /><br /> 
						Password again: <input type ="password" id="passwordcheck" size='10' /><br /> 
						First Name: <input type ="text" id="firstname" size='10' /><br /> 
						Last Name: <input type ="text" id="lastname"  size='10' /><br />
						Sex: 
						<select id="sex" /> <br /> <!--hey dumbass, this should be drop down-->
							<option value="M">male</option>
							<option vlaue="F">female</option>
						</select><br/>
						<div id="signup-errors"></div>
						<span class='login-button' id='submit-signup'>
						Submit!</span> <br /> 
						<!--Should prolly restate that you can just connect with facebook here-->
						</div>
					</form> 
			</span>
        </div>
	</div>
	
	
	
	
	
	

