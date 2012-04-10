// test for and keep track of attendance at events

function attendingEvent(event_id) {
	//test user sign in-- NOTE: IS AJAX PROMISE... 
	var testSignIn = checkSignedIn();
	testSignIn.success(function (data) {
		//check for truth
	var loader = "#attendingLoader_" + event_id;
	$(loader).css("display", "inline");
		
		if(data == 1){
			// user is signed in
			// call add attendance function
			console.log("adding attendance");
			postEventData = {
				eventID: event_id
				};
			$.ajax({
				type: "POST",
				url: "./php/attendance.php", 
				data: postEventData,
				dataType: "json",
				success: function(result) {
					var msg = result.message;
					var status = result.status;
					var btnID = "#attendingBtn_" + event_id;
					
					$(btnID).removeClass("btnTemplateGreen");
					$(btnID).removeClass("btnTemplate");
					if(status == 2){
						// RSPV recorded
						console.log("recorded status as attending");
						$(btnID).addClass("btnTemplateGreen");
						$(btnID).empty().append("Already Attending");
						$(loader).css("display", "none");
						}
					else if(status == 1) {
						// already attending
						console.log("recorded status as not attending");
						$(btnID).addClass("btnTemplate");
						$(btnID).empty().append("Attending?");
						$(loader).css("display", "none");
						}
					else {
						console.log("user not signed in");
						$(btnID).addClass("btnTemplate");
						$(loader).css("display", "none");
						}
					}
				});
			}
		else {
			alert("you must sign in to do that!");
			$('#advancedPanel').show();
			$('#advancedButton').rotate(-90);
			$(loader).css("display", "none");
			}
		});
	}
