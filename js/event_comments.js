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
                $(container).empty().append(result.messages);
                }
            } // end success
        }); // end AJAX call
    }


// post a comment
function postEventComment() {
    console.log("posting comment");
    
    var event_id = $('#eventIdMsg').val();
    var message = $('#eventMsgComment').val();
    eventData = {
        eventID: event_id,
        message: message
        };
    
    $.ajax({
		type: "POST",
		url: "./php/post_event_comment.php",
		data: eventData,
		dataType: "json",
		success: function(result) {
			// do stuff on result
            if(result.status == 1) {
                console.log("success: " + result);
                
                // reload comments
                populateEventComments();
                
                // erase message space
                $('#eventMsgComment').val("");
                }
            if(result.status == 0) {
                alert(result.message);
                }
            } // end success
        }); // end AJAX call
    }


// modify the event id input
function changeEventId(event_id) {
    $('#eventIdMsg').val(event_id);
    }
