// some javascript goes here!
$(document).ready(function() {   
	
	//modify search bar to change size with window
	var width = $(window).width();
	$('#srch_bar').attr({size: width*0.04});
	
	
	//Shows Login Panel
		$('#advancedButton').toggle(
		function() {
			$('#advancedPanel').show();
			$(this).rotate(-90);
			//$(this).addClass('close');
		},
		function() {
			$('#advancedPanel').hide();
			$(this).rotate(0); //haha it rotates with regards to original position...
			//$(this).removeClass('close');
			}
		); // end toggle
		
	// another listener for if you click login message	
		$('.login_msg').toggle(
		function() {
			$('#advancedPanel').show();
			$('#advancedButton').rotate(-90);
			//$(this).addClass('close');
		},
		function() {
			$('#advancedPanel').hide();
			$('#advancedButton').rotate(0); //haha it rotates with regards to original position...
			//$(this).removeClass('close');
			}
		);
		
	// toggles Sign-Up panel
		$('#signupPanel').hide();
		$('#signupBtn').toggle(function(){
			$('#signupPanel').show();
			},
			function(){
			$('#signupPanel').hide();
			}
		);
	
	// geolocation scripting see -> location_detection.js
	
	get_location();
	
	
	
	
/**** SIGNUP SUBMISSION STUFF BELOW:  ****/
	// CHECK FOR VALID EMAIL	
		$('#email').keyup(function(){
			var email = $('#email').val();
			
			// returns BINARY - 1 = not matched = good
			// This is the nasty bit:
			//var res = 1;//find_email(email);
			var re = /\S+@\S+/g;
			
			// PROMISE FROM AJAX CALL
			var promise = find_email_prom(email);
			
			promise.success(function (data) {
				var res = data;
				if(res == 0 && re.test(email))
					$("#emailIsValid").empty().append("<font color='green'>good</font>");
				else
					$("#emailIsValid").empty().append("<font color='red'>bad</font>");
					
			});	
		});
		
		
	// ON SignUp submit
	$('#submit-signup').click(function(){
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
									
									}
								else{
									console.log("Failed to sign up: ");
									}
								}); // end promise
							}
						else{
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
	});
		
});  // end ready
