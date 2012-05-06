// event comment controller

function showEventComments() {
    $('#eventCommentWrapper').show();
    }
    
function hideEventComments() {
    $('#eventCommentWrapper').hide();
    }

// push the comments into the content area
function populateEventComments() {
    var container = "#eventMsgContainer";
    console.log("populating:" + container);
    
    var event_id = $('#eventIdMsg').val();
    
    eventData = {
        eventID: event_id
        };
    
    $.ajax({
		type: "POST",
		url: "./php/get_event_comments.php",
		data: eventData,
		dataType: "json",
		success: function(result) {
			// do stuff on result
            if(result.status == 1) {
                console.log("success: " + result);
                }
            } // end success
        }); // end AJAX call
    }

// post a comment


// modify the event id input
function changeEventId(event_id) {
    $('#eventIdMsg').val(event_id);
    }
