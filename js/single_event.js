// for showing and manipulating single event views

function eventSingleViewer(event_id){
    console.log("singleview for:" + event_id);
    
    $('#singleEventWrapper').show();
    
   controlDimmer(1);
    
    eventData = {
        eventID: event_id,
        latitude: latitude,
        longitude: longitude
        };
    console.log("lat lng: ");
    console.log(eventData);
    
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
    
    controlDimmer(-1);
    
    $('#singleEventContent').empty();
    }
