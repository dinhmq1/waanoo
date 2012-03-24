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
			find_email(email);
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
						if(emailStatus == 1){
							// last validation test
							// THIS IS WHERE WE SHOULD SEE IF EMAIL ALREADY TAKEN!!!
							console.log("All validations passed");
							$('#signup-errors').empty().append("Success");
							mainSignUp(email, pass1, pass2, fname, lname, sex);
							
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
