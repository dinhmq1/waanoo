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
					
    ////*** REMOVED SOME STUFF REFFING THE BUTTON HERE
                    
					if(status == 2){
						// RSPV recorded
						console.log("recorded status as attending");
						$(btnID).attr("src", "./images/buttons/btns_content/btn_attend_active.png");
						$(loader).css("display", "none");
						getAttendCount(event_id);
						}
					else if(status == 1) {
						// already attending
						console.log("recorded status as not attending");
						$(btnID).attr("src", "./images/buttons/btns_content/btn_attend_inactive.png");
						$(loader).css("display", "none");
						getAttendCount(event_id);
						}
					else {
						console.log("user not signed in");
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

function getAttendCount(event_id) {
	// att_count_$event_id
	var count_id = "#att_count_" + event_id;
	
	var data =  {
				eventID: event_id
				};
	
	$.ajax({
		type: "POST",
		url: "./php/attendcount.php",
		data: data,
		dataType: "json",
		success: function(result){
			// do stuff on result
			var res = result.status;
			var count = result.count;
			
			if(res == 1) {
				console.log("new count: " + count);
				$(count_id).empty().append(count);
				}
			}
		});
	}
