// test for and keep track of attendance at events

function attendingEvent(event_id) {
	//test user sign in-- NOTE: IS AJAX PROMISE... 
	var testSignIn = checkSignedIn();
	testSignIn.success(function (data) {
		//check for truth
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
					
					if(status == 2){
						// RSPV recorded
						console.log("recorded status as attending");
						$(btnID).empty().append("Attending!");
						$(btnID).css("background-color", "#32C43C");
						}
					else if(status == 1) {
						// already attending
						console.log("recorded status as not attending");
						$(btnID).empty().append("Not Attending!");
						$(btnID).css("background-color", "#DC2C28");
						}
					else {
						console.log("user not signed in");
						}
					}
				});
			}
		else {
			alert("you must sign in to do that!");
			$('#advancedPanel').show();
			$('#advancedButton').rotate(-90);
			}
		});
	}
