
function callSignOut(){
	
	return $.ajax({
		type: "POST",
		url: "./php/signout.php"
		});
	}


function signOutMain(){
	
	var promiseSignOut = callSignOut();
	
	promiseSignOut.success(function (data) {
		if( data == 1){
			var login = "login with facebook:<br />\
						<form id='loginMainForm' action='#'>\
								email:\
							<input type='text' id='login-email' size='10' />\
								password:\
							<input type='password' id='login-password' size='10' />\
							<!--\
							<span class='login-button' onClick='signIn()'>\
								GO!\
							-->\
							<input class='login-button' type='submit' value='Submit' style='font-size:85%;'/>\
						</form>\
						<span id='loginNotes'></span>\
		                <br />\
		                Don't have an account? <br /> \
							<span id='signupBtn' onClick='showSignUp()'>\
								<a href='#' class='btnTemplate'>Sign Up!</a>\
							</span> \
		                \
		                <br/>\
							<form>\
								<div id='signupPanel'> \
								email: <input type ='text' id='email' /><input type='hidden' id='email_test' value='0'/>\
								<span id='emailIsValid'></span><br /> \
								Password: <input type ='password' id='password' size='10' /><br /> \
								Password again: <input type ='password' id='passwordcheck' size='10' /><br /> \
								First Name: <input type ='text' id='firstname' size='10' /><br /> \
								Last Name: <input type ='text' id='lastname'  size='10' /><br />\
								Sex: \
								<select id='sex' /> <br /> <!--hey dumbass, this should be drop down-->\
									<option value='M'>male</option>\
									<option vlaue='F'>female</option>\
								</select><br/>\
								<div id='signup-errors'></div>\
									<span id='submit-signup' onClick='signUpMain()'>\
									<a href='#' class='btnTemplate'>Submit!</a>\
									</span> <br /> \
								<!--Should prolly restate that you can just connect with facebook here-->\
								</div>\
							</form> ";
			
			$('#login-field').empty().append(login);
			$('#login_msg').empty().append("You are not logged in!");
			$('#loginStatus').val('0');
			}
		else{
			$('#signout-errors').empty().append("<font color='red'>There was an error logging out</font>");
			}
		});
	}
