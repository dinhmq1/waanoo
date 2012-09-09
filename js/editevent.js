// js for editing events
/**
 * NOTES:
 * - Using a lot of functions from postevent.js / keep this in mind.
 *  
 * 
 */

function editEvent(eventID) {
    eventData = {
                eventId: eventID
                };
    
    // do this using post event form, just prepopulate with old data
    $.ajax({
        type: "POST",
        url: "./php/getEventData.php",
        data: eventData,
        dataType: "json",
        success: function(result) {
            // do stuff on result
            var res = result.status;
            var msg = result.msg;
            console.log(res);
            if(res == 1) {
                $('#eventName').val(result.event_title);
                $('#eventLocation').val(result.address_text);
                $('#eventDateBegin').val(result.start_date);
                $('#eventDateEnd').val(result.end_date);
                $('#eventDescription').val(result.event_description);
                
                // contact stuff:
                if(result.isContactInfo == "1") {
                	$('#allowContactEvtent').attr('checked','checked');
                	$('#contactingOptions').show();
                	
                	if(result.contactType == "email") {
		                $('#emailContactInfo').val(result.contactInfo);
	                }
	                
		            if(result.contactType == "phone") {
		            	try{
		            		var splits = result.contactInfo.split("-");
			            	var p1 = splits[0];
			            	var p2 = splits[1];
			            	var p3 = splits[2];
			                $('#phone1ContactInfo').val(p1);
			                $('#phone2ContactInfo').val(p2);
			                $('#phone3ContactInfo').val(p3);
		            	} catch(e) {
		            		console.log("Phone number failed to split");
		            	}
		             }
	            }

                // added new stuff
                $('#txtEventHomepageURL').val(result.homepageURL);
        		$('#txtTagsInpt').val(result.tagsList);
        		if(result.isOutdoors == "1")
        			$('#chkOutdoorsEvent').attr('checked','checked'); // either undefined or "checked"
        		if(result.isFree == "1")
       				$("#freeEvent").attr('checked','checked');
       			else
       				$("#nonFreeEvent").attr('checked','checked');
       				
        		$("#priceCurrency").val(result.eventCurrency);
        		$('#eventPrice').val(result.eventPrice);
                
                // hidden
                $('#oldEventID').val(eventID);
                
                // change the onclick attribute to another function:
                // NOTE: MAKE SURE WE SET THIS BACK LATER
                $('#eventFormSubmitBtn').attr('onclick', 'reSubmitEvent()');
                
                // show the form
                //$('#postEventForm-wrapper').show();
                // ORIGINAL METHOD FROM THE ONCLICK
                open_post_event();
                }
            else {
                // DO SOME ALERT!
                alert("Could not edit event: " + msg);
                }
            }
        });
    }


function reSubmitEvent() {
	// Set up the things we need for validation:
    var errors = "";
    var errFlag = false; // start with no errors
    
    console.log("re-submitting the event");
    console.log("submitting new event");
        
    var eventName = $('#eventName').val();
    var eventLoc = $('#eventLocation').val();
    
    // fuckit, the got switched somewhere, so switchem again
    var eventBegin = $('#eventDateBegin').val();
    var eventEnd = $('#eventDateEnd').val();
    var eventDescrip = $('#eventDescription').val();
    var eventID = $('#oldEventID').val();
    var imgFileName = $('#imgFileLocation').val();
    var isImage = $('#isThereImage').val();
    var contact = $('#allowContactEvtent').attr('checked');
    
    // stuff added 7/7/2012
    var homeURL = $('#txtEventHomepageURL').val();
    var tagsList = $('#txtTagsInpt').val();
    var outdoors = $('#chkOutdoorsEvent').attr('checked'); // either undefined or "checked"
    var radioFree = $("#freeEvent").attr('checked');
    var radioNonFree = $("#nonFreeEvent").attr('checked');
    var currencyType = $("#priceCurrency").val();
    var eventCost = $('#eventPrice').val();
    var isFree = true;
    
    // event cost info.. convert to boolean
    if(radioNonFree == "checked") {
        if(!testEmpty(eventCost)) {
            errors += "If the event is not free, you should note the cost. <br />";
            errFlag = true;
        }
        isFree = false;
    } else {
        isFree = true;
    }
    
    // outdoors.. convert to boolean
    if(outdoors == "checked") {
        outdoors = true;
    } else {
        outdoors = false;
    }
    
    // convert date to MySQL:
    console.log("converting times");
    eventBegin = toMySQLTime(eventBegin);
    eventEnd = toMySQLTime(eventEnd);
    
    console.log("filename: " + imgFileName);
    console.log("ImageOK: " + isImage);
    
    // get optional contact info
    var isContactInfo = 0;
    var isContactInfoValid = false;  // so if (0 and False) or (1 and true)
    if(contact == "checked") {
        isContactInfo = 1;
        var contactType = $('#eventContactType').val();
        if(contactType == "email") {
            var contactInfo = $('#emailContactInfo').val();
            isContactInfoValid = testContactEmail();
            }
        if(contactType == "phone") {
            var contactInfo = {
                phone1: $('#phone1ContactInfo').val(),
                phone2: $('#phone2ContactInfo').val(),
                phone3: $('#phone3ContactInfo').val()
                }; // JSON for phone
            isContactInfoValid = testContactPhone();    
            }
        }
    else {
        isContactInfo = 0;
        var contactInfo = "none";
        var contactType = "none";
        }
    
    
    if(testEmpty(eventName) && testEmpty(eventLoc) && 
        testEmpty(eventBegin) && testEmpty(eventEnd) 
        && testEmpty(eventDescrip)){
        console.log("passed empty test validation");
        }
   	else {
   		errFlag = true;
   		errors += "Something important was empty.";
   	}
   	
   	var re = /\d+-\d+-\d+ \d+:\d+/i;
    if(!re.test(eventBegin) || !re.test(eventEnd)){
    	errFlag = true;
    	errors += "Date format incorrect";
    }
    
    if((isContactInfo == 0 && isContactInfoValid == false) || (isContactInfo == 1 && isContactInfoValid == true)) {
        console.log("passed contact info validation");
    } else {
    	errFlag = true;
    	errors += "Contact info not valid";
    }
    
    
    if(errFlag == false) {
        // do a call to the backend to check the session as signed in
        var testSignIn = checkSignedIn();
        testSignIn.success(function (data) {
	        if(data == 1){
	            // signed in
	            console.log("session verified login status");

				// Now test that address geocodes properly
                var address = eventLoc;
                geocoder = new google.maps.Geocoder();
                geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        // status is good. Go ahead an submit form with the geocoded lat and lng
                        var party_position = results[0].geometry.location
                        var lat_event = party_position.lat();
                        var lng_event = party_position.lng();
                        
                        postEventData = {
                            latitude: lat_event,
                            longitude: lng_event,
                            eventName: eventName,
                            eventLocation: address,
                            eventBegin: eventBegin,
                            eventEnd: eventEnd,
                            eventDescription: eventDescrip,
                            oldID: eventID,
                            isImageSubmitted: isImage,
                            imageFileName: imgFileName,
                            isContactInfoActive: isContactInfo,
                            contactInfoType: contactType,
                            contactInfoContent: contactInfo,
                            isFree : isFree,
	                        eventPrice: eventCost,
	                        eventCurrency: currencyType,
	                        isOutdoors: outdoors,
	                        homepageURL: homeURL,
	                        tagsList: tagsList
                            };
                        
                        // ajax call to post our event
                        $.ajax({
                            type: "POST",
                            url: "./php/editevent.php", 
                            data: postEventData,
                            dataType: "json",
                            success: function(result){
                                var msg = result.message;
                                var successMsg = result.status;
                                //alert("Data returned: " + msg);
                                if(successMsg == 1){
                                    //alert("Event Posted Successfully!");
                                    $('#postEventForm-wrapper').hide();
 
                                    console.log("closing dimmer");
                                    controlDimmer(-1);
                                    
                                    //$('#postEventSuccess').show();
                                    alert("Event updated!");
                                    load_events(latitude, longitude);
                                    
                                    clearPostEvent();
                                    }
                                else {
                                    $('#eventPostErrors').empty().append("<font color='red'>" + msg + "</font>");
                                    }
                            
                                }// end insde ajax result
                            });// end inside ajax call
                        }// end if Geocode status OK
                    else {
                        $('#eventPostErrors').empty().append("<font color='red'>That address is not valid!</font>");
                        }
                    }); // end inside geocoder
                }// end if signint data == 1
           		else {
	                // not signed in
	                console.log("not signed in...");
	                $('#eventPostErrors').empty().append("<font color='red'>You need to be signed in to do that</font>");
                }
            });// end inside sign in ajax test callback
        }// end if error flag is false
    else {
        $('#eventPostErrors').empty().append(errors);
        }
    }// end func
