// main signin scripts

function signIn(){
	var email = $('#login-email').val();
	var password = $('#login-password').val();
	
	infos = {
		user_email: email,
		user_password: password
		};
	
	if(email != "" && password != ""){
		console.log("sending ajax request");
		$.post("./php/signin.php", infos, function(resultJSON){
			console.log("returning ajax req");
			var status = resultJSON.status;
			var msg = resultJSON.message;
			var name = resultJSON.name;
			var id = resultJSON.id;

			if(status == 1){
				// need to modify user object here
				
				var logout = "<div id='logoutWrapper'>Hi " + name +
						"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\
						<span id='logout-button' onClick='signOutMain()'>\
							<a href='#' class='testBlackBtn'>Sign Out!</a>\
							</span>\
						<span id='signout-errors'></span>\
						</div>";
				
				$('#login-logout-wrapper').empty().append(logout);
				
				console.log("user logged in id: " + id);
				//name = "Hi " + name + "!";
				//$('#login_msg').empty().append(name);
				
				$('#advancedPanel').hide();
				$('#loginStatus').val('1');
				}
			else{
				// Login failed
				$('#loginNotes').empty().append("<small>Login failed,</small> "  + "<small>" + msg + "</small>");
				}
			}, "json");
		}
	else {
		$('#loginNotes').empty().append("Fields left empty!");
		}
	} // signin end
