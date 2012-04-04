// js for editing events

function editEvent(eventID){
	
	eventData = {
				eventId: eventID
				};
	
	// do this using post event form, just prepopulate with old data
	$.ajax({
		type: "POST",
		url: "./php/getEventData.php",
		data: eventData,
		dataType: "json",
		success: function(result){
			// do stuff on result
			var res = result.status;
			var msg = result.msg;
			
			if(res == 1) {
				/* FROM BACK END:
				 * "result" => 1, 
					"msg" => "completed", 
					"event_title" => $event_title,
					"event_description" => $event_description,
					"end_date" => $end_date,
					"start_date" => $start_date,
					"public" => $public,
					"address_text" => $address_text,
					"date_created" => $date_created  */
				
				$('#eventName').val(result.event_title);
				$('#eventLocation').val(result.address_text);
				$('#eventDateBegin').val(result.start_date);
				$('#eventDateEnd').val(result.end_date);
				$('#eventDateEnd').val(result.end_date);
				$('#eventDescription').val(result.event_description);
				
				// change the onclick attribute to another function:
				// NOTE: MAKE SURE WE SET THIS BACK LATER
				$('#eventFormSubmitBtn').attr('onclick', 'reSubmitEvent()');
				
				// show the form
				//$('#postEventForm-wrapper').show();
				// ORIGINAL METHOD FROM THE ONCLICK
				open_post_event()
				}
			else {
				// DO SOME ALERT!
				alert("Could not edit event: " + msg);
				}
			}
		});
	}


function reSubmitEvent() {
	console.log("re-submitting the event");
	
	}
