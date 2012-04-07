// some javascript goes here!
$(document).ready(function() {

	//hide the dimmer:
	$('#dimmer').hide();
	
	//modify search bar to change size with window
	var width = $(window).width();
	$('#srch_bar').attr({size: width*0.04});
	
	
	//Shows Login Panel
		$('#advancedButton').toggle(
		function() {
			$('#advancedPanel').show();
			$(this).rotate(-90);
			//$('#dimmer').show();
			//$(this).addClass('close');
		},
		function() {
			$('#advancedPanel').hide();
			$(this).rotate(0); //haha it rotates with regards to original position...
			//$(this).removeClass('close');
			//$('#dimmer').hide();
			}
		); // end toggle
		
	// another listener for if you click login message	
		$('.login_msg').toggle(
		function() {
			$('#advancedPanel').show();
			$('#advancedButton').rotate(-90);
			//$('#dimmer').show();
			//$(this).addClass('close');
		},
		function() {
			$('#advancedPanel').hide();
			$('#advancedButton').rotate(0); //haha it rotates with regards to original position...
			//$(this).removeClass('close');
			//$('#dimmer').hide();
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
/**** MAP/ LOCATION SCRIPTING ****/
	get_location();
	$('#map_wrapper').hide();
	

/**** EVENT POSTING SCRIPTING ***/
	$('#postEventForm-wrapper').hide();
	
	
/*** login ***/

	$("#loginMainForm").submit(function(event) {
		event.preventDefault();
		signIn()
		});
	
	
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
		

			
/*** POSTING EVENT DATA PICKER ***/	
		
	$(function() {  //shortcut for ready()
	$("#eventDateBegin").AnyTime_picker(
        { format: "%Y-%m-%d %H:%i",
          formatUtcOffset: "%: (%@)",
          hideInput: false,             //change later only for dev purposes
          //placement: "inline" 
          });
	});
	
	$(function() {  //shortcut for ready()
	$("#eventDateEnd").AnyTime_picker(
        { format: "%Y-%m-%d %H:%i",
          formatUtcOffset: "%: (%@)",
          hideInput: false,             //change later only for dev purposes
          //placement: "inline" 
          });
	});
		

	// popup box on event submission success
	$('#postEventSuccess').hide();
	

/**** EVENT EDITING WINDOW ****/


/*** SINGLE EVENT MAP ***/

	$('#EventMapWrapper').hide();
	$('#EventDirections').hide();
		
});  // end ready
