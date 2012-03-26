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
	return $.ajax({
		type: "POST",
		url: "./php/signup.php", 
		data: signupInfos,
		dataType: "json"
		});
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
			var result = 1;
			}
		else{
			console.log("matched: " + email);
			$('#email_test').val("0");
			var result = 1;
			}
		});
	}
	
function find_email_prom(email){
	var toSend = {
					test_mail: email
				};
	return $.ajax({
		type: 'POST',
		url: "./php/checkEmail.php",
		data: toSend
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
