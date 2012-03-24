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
		var msg = resultJSON.msg;
		var name = resultJSON.name;
		var id = resultJSON.id;
		
		// change the site so that we are logged in:
		// -add login abilities
		// -add login message
		
		if(status == 1){
			name = "Hi " + name + "!";
			$('#login_msg').empty().append(name);
			}
		else{
			// Login failed
			$('#loginNotes').empty().append("login failed");
			}
		
	}, "json");
	
	} // signin end
