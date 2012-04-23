// for showing and manipulating single event views

function eventSingleViewer(event_id){
    console.log("singleview for:" + event_id);
    
    $('#singleEventWrapper').show();
    $('#dimmer').show();
    
    eventData = {
        eventID: event_id,
        latitude: latitude,
        longitude: longitude
        };
    
    $.ajax({
		type: "POST",
		url: "./php/singleEvent.php",
		data: eventData,
		dataType: "json",
		success: function(result) {
			// do stuff on result
            if(result.status == 1) {
                $('#singleEventContent').empty().append(result.content);
                }
            }
        });
    }


function closeSingleEvent() {
    $('#singleEventWrapper').hide();
    $('#dimmer').hide();
    $('#singleEventContent').empty();
    }
