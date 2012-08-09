// for showing and manipulating single event views

function eventSingleViewer(event_id){
    console.log("singleview for:" + event_id);
    
    $('#singleEventWrapperWrapper').show();
    
    $('#eventCommentWrapper').show();
    
    // add the dimmer
    controlDimmer(1);
    
    // control comments
    showEventComments();
    changeEventId(event_id);
    populateEventComments();
    
    // add a pageview for the event
    addEventPageview(event_id);
    
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
                // add data to event data holder
                $('#singleEventContent').empty().append(result.content);             
                }
            }
        });
    }


function closeSingleEvent() {
    $('#singleEventWrapperWrapper').hide();
    
    ('#eventCommentWrapper').hide();
    
    hideEventComments();
    controlDimmer(-1);
    
    $('#singleEventContent').empty();
    }
    
    
    
   
