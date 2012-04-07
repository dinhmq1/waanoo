// signup logic

	// ON SignUp submit
	function signUpMain(){
		console.log("Validating the sign up fields");
		// do validations:
		var email = $('#email').val();
		var pass1 = $('#password').val();
		var pass2 = $('#passwordcheck').val();
		var fname = $('#firstname').val();
		var lname = $('#lastname').val();
		var sex = $('#sex').val();
		
		var fieldArr = [email, pass1, pass2, fname, lname, sex];
		var x;
		
		var error = false;
		// check that none are empty
		for(x in fieldArr){
			console.log("x: " + fieldArr[x]);
			if(fieldArr[x] == ""){
				console.log("found something empty");
				error = true;
				}
			}
		
		console.log("error=" + error);
		if(error == false){
			console.log("nothing empty");
			if(pass1 === pass2){
				if(pass1.length >= 8){
					// finally regex for email
					var re = /\S+@\S+/g;
					if(re.test(email)){
						// should be true if NOT matched
						var emailStatus = $('#email_test').val();
						console.log("email status: " + emailStatus);
						if(emailStatus == 0){
							// last validation test
							// THIS IS WHERE WE SHOULD SEE IF EMAIL ALREADY TAKEN!!!
							console.log("All validations passed");
							$('#signup-errors').empty().append("Success");
							
							// AJAX CALL WITH A PROMISE
							var promiseSignUp = mainSignUp(email, pass1, pass2, fname, lname, sex);
							promiseSignUp.success(function (data) {
								
								console.log("promise returned from sign up");
														//unpack json data into variables.
								var status = data.status;
								var error = data.error_msg;
								var usr = data.name;
								
								// if status is 1, success
								if(status === 1){
									// set the header to say: "you are logged in", and name
									$("#login_msg").empty().append("Hi " + usr);
									
									$('#signupPanel').hide();
									$('#advancedPanel').hide();
									
									// new success window
									signUpSuccessWindow(fname);
									
									// THIS SHOULD NOW CHANGE OTHER STATES:
									// need to change "not signed in" to hello blah blah blah
									// need to remove sign-Up button/ 
									// need to change signin field to logout button
									$('#signup-errors').empty();
									}
								else {
									console.log("Failed to sign up: ");
									$('#signup-errors').empty().append("Sorry, Failed to sign up. Try again.");
									}
								}); // end promise
							}
						else {
							console.log("email already in use");
							$('#signup-errors').empty().append("Sorry, that email is already in use!");
							}	
						}
					else
						$('#signup-errors').empty().append("Email is not in correct format");
					}
				else
					$('#signup-errors').empty().append("Password is not long enough");
				}
			else
				$('#signup-errors').empty().append("Passwords don't match");
			}
		else
			$('#signup-errors').empty().append("Something was empty");	
	} //end signup validation
		

// signup/ signin ajax
function mainSignUp(email, pass1, pass2, fname, lname, sex) {
	
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
function find_email(email) {

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
		else {
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
	
	$('#loginStatus').val('1');
	
	// CLEAR ALL FIELD VALUES:
	$('#email').val("");
	$('#password').val("");
	$('#passwordcheck').val("");
	$('#firstname').val("");
	$('#lastname').val("");
	$('#sex').val("");
	}

