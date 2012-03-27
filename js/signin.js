// main signin scripts

function signIn(){
	var email = $('#login-email').val();
	var password = $('#login-password').val();
	
	infos = {
		user_email: email,
		user_password: password
		};
	
	$.post("./php/signin.php", infos, function(resultJSON){
	
		var status = resultJSON.status;
		var msg = resultJSON.message;
		var name = resultJSON.name;
		var id = resultJSON.id;
		
		// change the site so that we are logged in:
		// -add login abilities
		// -add login message
		
		if(status == 1){
			// need to modify user object here
			
			var logout = "<br><br>You are signed in as " + name +
						" would you like to logout?<br>\
						<span id='logout-button' class='login-button'\
						onClick='signOutMain()'>\
						LogOut!</span>\
						<span id='signout-errors'></span>";
			
			$('#login-field').empty().append(logout);
			
			console.log("user logged in id: " + id);
			name = "Hi " + name + "!";
			$('#login_msg').empty().append(name);
			
			$('#advancedPanel').hide();
			}
		else{
			// Login failed
			$('#loginNotes').empty().append("login failed: " + "<small>" + msg + "</small>");
			}
		
	}, "json");
	
	} // signin end
