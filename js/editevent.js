// js for editing events

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
            
            if(res == 1) {
                /* FROM BACK END:
                    "result" => 1, 
                    "msg" => "completed", 
                    "event_title" => $event_title,
                    "event_description" => $event_description,
                    "end_date" => $end_date,
                    "start_date" => $start_date,
                    "public" => $public,
                    "address_text" => $address_text,
                    "date_created" => $date_created     */
                
                $('#eventName').val(result.event_title);
                $('#eventLocation').val(result.address_text);
                $('#eventDateBegin').val(result.start_date);
                $('#eventDateEnd').val(result.end_date);
                $('#eventDescription').val(result.event_description);
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
            
            // do a call to the backend to check the session as signed in
            var testSignIn = checkSignedIn();
            testSignIn.success(function (data) {
            
                if(data == 1){
                    // signed in
                    console.log("session verified login status");
                    
                    // regular expression for the dates:
                    // testing for: 2012-03-15 05:00
                    var re = /\d+-\d+-\d+ \d+:\d+/i;
                    
                    if(re.test(eventBegin) && re.test(eventEnd)){
                        console.log("passed date RE validation");
                        
                        // Validate for contact info
                        if((isContactInfo == 0 && isContactInfoValid == false) || (isContactInfo == 1 && isContactInfoValid == true)) {
                            console.log("passed contact info validation");
                            //now to geocode the address and check to see if it is OK
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
                                        contactInfoContent: contactInfo
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
                                                $('#singleEventWrapper').hide();
                                                $('#eventCommentWrapper').hide();
                                                
                                                console.log("closing dimmer");
                                                controlDimmer(-1);
                                                controlDimmer(-1);
                                                
                                                //$('#postEventSuccess').show();
                                                alert("Event updated!");
                                                load_events(latitude, longitude);
                                                
                                                $('#eventName').val("");
                                                $('#eventLocation').val("");
                                                $('#eventDateBegin').val("");
                                                $('#eventDateEnd').val("");
                                                $('#eventDescription').val("");
                                                
                                                $('#imgFileLocation').val("");
                                                $('#isThereImage').val("0");
                                                
                                                $('#allowContactEvtent').removeAttr("checked");
                                                $('#eventPostErrors').empty();
                                                }
                                            else {
                                                $('#eventPostErrors').empty().append("\
                                                <font color='red'>" + msg + "</font>");
                                                }
                                        
                                            }
                                        });
                                    
                                    }
                                else {
                                    $('#eventPostErrors').empty().append("\
                                    <font color='red'>That address is not valid!</font>");
                                    }
                                });
                            }
                        else {
                            $('#eventPostErrors').empty().append("\
                            <font color='red'>Contact info not valid!.</font>");
                            }
                        }
                    else {
                        $('#eventPostErrors').empty().append("\
                        <font color='red'>Dates are not incorrect format.</font>");
                        }
                    }
                else {
                    // not signed in
                    console.log("not signed in...");
                    $('#eventPostErrors').empty().append("\
                    <font color='red'>You need to be signed in to do that</font>");
                    }
                });
            }
        else {
            $('#eventPostErrors').empty().append("\
            <font color='red'>Something was empty</font>");
            }
        }
