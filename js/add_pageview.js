//pageviews functionality

function addEventPageview(event_id) {
    
    // lat and lon are taken from globals
    eventData = {
        eventID: event_id,
        latitude: latitude,
        longitude: longitude
        };
        
    console.log("lat lng: ");
    console.log(eventData);
    
    $.ajax({
		type: "POST",
		url: "./php/add_event_pageview.php",
		data: eventData,
		dataType: "json",
		success: function(result) {
			// do stuff on result
            if(result.status == 1) {
                console.log("pageview added successfully");
                }
            }
        });
    }
