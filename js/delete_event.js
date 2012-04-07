// for deleting events

function delEvent(event_id) {
	// make an ARE YOU SURE? Alert box. 
	var r = confirm("Are you sure you want to delete this event?")
	
	if(r == true) {
		eventData = {
					eventId: event_id
					};
		$.ajax({
			type: "POST",
			url: "./php/delete_event.php",
			data: eventData,
			dataType: "json",
			success: function(result){
				// do stuff on result
				var res = result.status;
				var msg = result.msg;
				
				if(res == 1) {
					// reload events
					load_events(latitude, longitude);
					}
				else {
					// DO SOME ALERT! "could not delete event"
					alert("Could not delete event!");
					}
				}
			});
		}
	}
