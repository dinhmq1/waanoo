
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
							<input class='testBlackBtn' type='submit' value='submit'  />\
							&nbsp;&nbsp;&nbsp;\
							<div id='signupBtn'>\
								<a href='#' >\
									<img src='./images/buttons/btns_content/btn_signup_inactive.png' />\
								</a>\
							</div> \
						</form>	";
			
			$('#login-logout-wrapper').empty().append(login);
			//$('#login_msg').empty().append("You are not logged in!");
			$('#loginStatus').val('0');
			}
		else{
			$('#signout-errors').empty().append("<font color='red'>There was an error logging out</font>");
			}
		});
	}
