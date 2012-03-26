// signup/ signin ajax
function mainSignUp(email, pass1, pass2, fname, lname, sex){
	
	var signupInfos = {
						email_new: email, 
						password1: pass1,
						password2: pass2,
						first_name: fname,
						last_name: lname,
						user_sex: sex
					};
	$.post("./php/signup.php", signupInfos, function(json){
		
		//unpack json data into variables.
		var status = json.status;
		var error = json.error_msg;
		var usr = json.name;
		
		// if status is 1, success
		if(status === 1){
			// set the header to say: "you are logged in", and name
			$("#login_msg").empty().append("Hi " + usr);
			}
		else{
			console.log("Failed to sign up: " + error);
			}
		
		}, "json");
	}

// checks to see if email already taken
function find_email(email){

	var toSend = {
					test_mail: email
				};
	
	$.post("./php/checkEmail.php", toSend, function(result){
		console.log("result from checkEmail.php: " + result);
		var res = eval(result);
		if(res == 0){
			console.log("Not matched: " + email);
			$('#email_test').val("1");
			return 1;
			}
		else{
			console.log("matched: " + email);
			$('#email_test').val("0");
			return 0;
			}
		});
	}
	
function closeMe(idInpt){
	$(idInpt).hide();
	}	
	
function signUpSuccessWindow(fname){
	var window = "<div id='signUpWindow'> \
		<br\> \
		Hi " + fname + " \
		Thanks for signing up! <br \> \
		You are now logged in! <br \>\
		<span class='login-button' onClick=closeMe('#signUpWindow')> \
		Close \
		</span>\
		</div>";
	$('#tempWindow').empty().append(window);
	
	// CLEAR ALL FIELD VALUES:
	$('#email').val("");
	$('#password').val("");
	$('#passwordcheck').val("");
	$('#firstname').val("");
	$('#lastname').val("");
	$('#sex').val("");
	}
