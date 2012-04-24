
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
			var login = "<form id='loginMainForm' action='#'>\
							<input class='loginField' type='text' id='login-email' placeholder=' email' size='10' />\
							<input class='loginField' type='password' id='login-password' placeholder=' password' size='10' />\
							<input class='login-button' type='submit' value='sign in' style='font-size:85%;'/>\
							<div class='login-button' id='signupBtn'>\
								<a href='#'>\
								<button>sign up!</button>\
								</a>\
							</div> \
						</form>";
			
			$('#login-logout-wrapper').empty().append(login);
			//$('#login_msg').empty().append("You are not logged in!");
			$('#loginStatus').val('0');
			}
		else{
			$('#signout-errors').empty().append("<font color='red'>There was an error logging out</font>");
			}
		});
	}
