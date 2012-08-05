// main script for posting events:

function open_post_event(){
    var loggedin = $('#loginStatus').val();
    console.log("logged in? " + loggedin);
    
    if(loggedin == 1 && loggedin != ""){
        console.log("showing event posting window");
        $('#postEventForm-wrapper').show();
        controlDimmer(1);
        }
    else {
        alert("you have to be signed in to do that!");
        }
    }

function close_post_event(){
    console.log("hiding event posting window");
    $('#postEventForm-wrapper').hide();
    controlDimmer(-1);
    }


function close_event_success_window(){
    $('#postEventSuccess').hide();
    controlDimmer(-1);
    }


function closeEventTags(){
    $('#eventTagsChoices').hide();
    }

/*** maps section ***/
// NOTE: map object = 'map2'

function loadScriptMiniMap() {
        // EXECUTES FOURTH
        console.log("appending google maps script to page");
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initMiniMap";
        document.body.appendChild(script);
        }

// NOTE: COORDINATES ARE GLOBALS FROM location_detection.js
	/*
	 * Call from main.js
	 */
    function initMiniMap() {      
        //Center of map upon init:
        var lat = latitude;//37.839479235926156;
        var lng = longitude;//-83.65678845996092;
        
        var map_center = new google.maps.LatLng(lat,lng);
        // NOTE: mapTypeId is required... lol wow.
        var myOptions = {
            center: map_center,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
            };
           
        //this needs to be global because im going to call it later. A lot.
        //map2 = null;
        map_2 = new google.maps.Map(document.getElementById("miniMapCanvas"),myOptions);
        
        you_icon = 'images/person_you.png';
        marker_pos_2 = new google.maps.LatLng(lat,lng);
    
        marker_you_2 = new google.maps.Marker({
                map:map_2,
                draggable:true,
                animation: google.maps.Animation.BOUNCE,
                position: marker_pos_2,
                icon: you_icon
            });
        // make a listener for the marker
        google.maps.event.addListener(marker_you_2, 'mouseup', reset_position_mini);

		setCoordsMini()
        }//end init func
       
    function setCoordsMini() {
    	var geocoder = new google.maps.Geocoder();
        var address = $('#eventLocation').val();
        
        if(address == ""){
            address = "45221";
            }
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map_2.setCenter(results[0].geometry.location);
                console.log(results);
                marker_you_2.setPosition(results[0].geometry.location);
                }
            });
    } 
    
    // reset coords when marker is dragged
    function reset_position_mini(){
        var current_position = marker_you_2.getPosition();
        
        map_2.setCenter(current_position);
        
        console.log("new position: " + current_position);
        
        var revGeocoder = new google.maps.Geocoder();
        // SHOULD GET REVERSE GEOCODE AND UPDATE INTO THE CORECT FIELD:
        revGeocoder.geocode({'latLng': current_position}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                // just set the field to the geocode return addy
                  $('#eventLocation').val(results[0].formatted_address);
                }
              else {
                alert("Geocoder failed due to: " + status);
              }
            });
        //$('#eventLocation').val(reverseGeocodePos);
        }

/*
 * Gets called from the button
 * Init is called from this every time.
 */
// geocodes address and resets map position
    function showMinimapCoords(){
        console.log("opening mini map for event");
        $('#postEventMiniMap').show();
        initMiniMap();
        
        }
// shows 
    function closePostEventMinimap() {
        $('#postEventMiniMap').hide();
    }
        
// tests a geocode 
function testGeocode(address){
    geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            return true;
            }
        else {
            return false;
            }
        });
    }


/*** VALIDATION ***/

    //outline
        /*  possible errors:
         * 
         * -empty fields
         * -start date after end date
         * --[address should always be valid??? --> test it again and 
         *      make sure that geocoding does NOT fail!]
         *  -also, maybe I should break down the geocoded addy into:
         *      -state
         *      -country
         *      -zip
         *      -address
         * 
         * --[dates should always be valid- jQuery UI plugin]
         * 
         * 
         * More fields:
         *  -venue
         *  -price
         *  -is it a regular occcurance?
         * 
         */
    function testEmpty(testSubject){
        if(testSubject == "")
            return false;
        else
            return true;
        }
    
    function checkSignedIn(){
        return $.ajax({
            type: "POST",
            url: "./php/testsignedin.php"
            });
        }
    
    // pull phone inputs and test 
    function testContactPhone(){
        var p1 = $('#phone1ContactInfo').val();
        var p2 = $('#phone2ContactInfo').val();
        var p3 = $('#phone3ContactInfo').val();
        
        var re = /\d{3}/i;
        if(re.test(p1) && re.test(p2) && re.test(p3))
            return true;
        else
            return false;
        }
    
    // email validation test
    function testContactEmail() {
        var email = $('#emailContactInfo').val();
        
        var re = /\S+@\S+/g;
        if(re.test(email)) 
            return true;
            
        else
            return false;
        }
    
    
    function toMySQLTime(inTime) {
        var rePm = /PM$/g;
        var reAm = /AM$/g;
        
        var date = /(\d+-\d+-\d+)/g;
        //var time = / (\d+:\d+) /g;
        var hrs = /(\d+):/;
        //var mins = /\d+-\d+-\d+ \d+:(\d+) /g;
        var mins = /:(\d+)/;
        
        if(rePm.test(inTime)) {
            console.log("fixing a pm:" + inTime);
            var d = date.exec(inTime);
            console.log("d:" + d[0]);

            var hrsNew = hrs.exec(inTime) ;
            console.log("hrsNew:" + hrsNew[0]);
            
            var minsNew = mins.exec(inTime);
            console.log("minsNew:" + minsNew[0]);
            
            var newTime = d[0] + " " + (parseInt(hrsNew[1]) + 12) + ":" + minsNew[1];
            console.log("newtime:" + newTime);
            
            return newTime;
            }
        if(reAm.test(inTime)) {
            console.log("fixing a am");
            var d = date.exec(inTime);
            console.log("d:" + d[0]);

            var hrsNew = hrs.exec(inTime) ;
            console.log("hrsNew:" + hrsNew[0]);
            
            var minsNew = mins.exec(inTime);
            console.log("minsNew:" + minsNew[0]);
            
            var newTime = d[0] + " " + parseInt(hrsNew[0]) + ":" + minsNew[1];
            console.log("newtime:" + newTime);
            
            return newTime;
            }
            
        return false;
        }
    
    function submitNewEvent(){
        // Set up the things we need for validation:
        var errors = "";
        var errFlag = false; // start with no errors
        $('#ajaxLoaderPostEvent').show();
        console.log("submitting new event");
        
        // get our variables:
        var eventName = $('#eventName').val();
        var eventLoc = $('#eventLocation').val();
        var eventBegin = $('#eventDateBegin').val();
        var eventEnd = $('#eventDateEnd').val();
        var eventDescrip = $('#eventDescription').val();
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
            outdoors == true;
        } else {
            outdoors == false;
        }
        
        
        // image uploading info:
        var isImageBool = 0;
        if(isImage == 1) 
            isImageBool = 1;
        else 
            isImageBool = 0;
        console.log("is there an image: " + isImage);
        
        // convert date to MySQL format:
        console.log("converting times");
        eventBegin = toMySQLTime(eventBegin);
        eventEnd = toMySQLTime(eventEnd);
        
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
            
        // validate contact info
        if(isContactInfo != 0) {
            if(!(isContactInfoValid)) {
                errors += "Contact info is not valid. <br />";
                errFlag = true;
            }
        }
        
        
        // check empty
        if(!(testEmpty(eventName) && testEmpty(eventLoc) && 
            testEmpty(eventBegin) && testEmpty(eventEnd) 
            && testEmpty(eventDescrip))){
            errors += "A required field was empty! <br />";
            errFlag = true;
        }
        
        // check dates
        var re = /\d+-\d+-\d+ \d+:\d+/i;
        if(!(re.test(eventBegin) && re.test(eventEnd))){
            errors += "Dates are not valid! <br />";
            errFlag = true;
        }
        
        
        // decide wether to submit or not
        if(errFlag) {
            $('#eventPostErrors').empty().append(errors);
            $('#ajaxLoaderPostEvent').hide();

        } 
        else {
            console.log("Passes all validation");
            //now to geocode the address and check to see if it is OK
            var address = eventLoc;
            geocoder = new google.maps.Geocoder();
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    // status is good. Go ahead an submit form with the geocoded lat and lng
                    var party_position = results[0].geometry.location
                    var lat_event = party_position.lat();
                    var lng_event = party_position.lng();
                      
                    // format JSON
                    postEventData = {
                        latitude: lat_event,
                        longitude: lng_event,
                        eventName: eventName,
                        eventLocation: address,
                        eventBegin: eventBegin,
                        eventEnd: eventEnd,
                        eventDescription: eventDescrip,
                        isImageSubmitted: isImageBool,
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
                        url: "./php/postevent.php", 
                        data: postEventData,
                        dataType: "json",
                        success: function(result){
                            var msg = result.message;
                            var successMsg = result.status;
                            //alert("Data returned: " + msg);
                            if(successMsg == 1){
                                // clear fields
                                clearPostEvent();
                                // hide the form
                                $('#postEventForm-wrapper').hide();
                                controlDimmer(-1);
                                
                                // load up events again.
                                load_events(latitude, longitude);
                                }
                            else {
                                $('#ajaxLoaderPostEvent').hide();
                                $('#eventPostErrors').empty().append(msg);
                                }
                        
                            }
                        });
            } // end if ok
            else {
                // status was NOT OK
                $('#eventPostErrors').empty().append("Google maps geocoder failed to respond...<br />");
                }// end Not OK else
            });// end geocoder.
        }// end main else
    }// end submit new function


function clearPostEvent() {
    $('#ajaxLoaderPostEvent').hide();
    
    $('#eventName').val("");
    $('#eventLocation').val("");
    $('#eventDateBegin').val("");
    $('#eventDateEnd').val("");
    $('#eventDescription').val("");
    $('#imgFileLocation').val("");
    $('#isThereImage').val("0");
    
    $('#descriptionCount').empty().append("0");
    $('#lblEventDateErrors').empty();
    $('#txtEventHomepageURL').empty();
    $('#lblTagInputErrors').empty();
    $('#txtTagsInpt').empty();
    $('#eventPrice').empty();
    
    $('#allowContactEvtent').removeAttr("checked");
    $('#eventPostErrors').empty();
}

    /*
    function submitNewEvent(){
        $('#ajaxLoaderPostEvent').show();
        console.log("submitting new event");
        
        var eventName = $('#eventName').val();
        var eventLoc = $('#eventLocation').val();
        var eventBegin = $('#eventDateBegin').val();
        var eventEnd = $('#eventDateEnd').val();
        var eventDescrip = $('#eventDescription').val();
        var imgFileName = $('#imgFileLocation').val();
        var isImage = $('#isThereImage').val();
        var contact = $('#allowContactEvtent').attr('checked');
        
        // convert date to MySQL:
        console.log("converting times");
        eventBegin = toMySQLTime(eventBegin);
        eventEnd = toMySQLTime(eventEnd);
        
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
            
        
        console.log("filename: " + imgFileName);
        console.log("ImageOK: " + isImage);
        
        if(testEmpty(eventName) && testEmpty(eventLoc) && 
            testEmpty(eventBegin) && testEmpty(eventEnd) 
            && testEmpty(eventDescrip)){
            
            console.log("passed empty test validation");
            
            // do a call to the backend to check the session as signed in
            var testSignIn = checkSignedIn();
            testSignIn.success(function (data) {
            
                if(data == 1) {
                    // signed in
                    console.log("session verified login status");
                    
                    // regular expression for the dates:
                    // testing for: 2012-03-15 05:00
                    var re = /\d+-\d+-\d+ \d+:\d+/i;
                    
                    console.log("testing: eventBegin:" + eventBegin + "eventEnd:" + eventEnd);
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
                                    
                                    // image uploading info:
                                    
                                    var isImageBool = 0;
                                    if(isImage == 1) 
                                        isImageBool = 1;
                                    else 
                                        isImageBool = 0;
                                    
                                    console.log("is there an image: " + isImage);
                                    
                                    postEventData = {
                                        latitude: lat_event,
                                        longitude: lng_event,
                                        eventName: eventName,
                                        eventLocation: address,
                                        eventBegin: eventBegin,
                                        eventEnd: eventEnd,
                                        eventDescription: eventDescrip,
                                        isImageSubmitted: isImageBool,
                                        imageFileName: imgFileName,
                                        isContactInfoActive: isContactInfo,
                                        contactInfoType: contactType,
                                        contactInfoContent: contactInfo
                                        };
                                    
                                    // ajax call to post our event
                                    $.ajax({
                                        type: "POST",
                                        url: "./php/postevent.php", 
                                        data: postEventData,
                                        dataType: "json",
                                        success: function(result){
                                            var msg = result.message;
                                            var successMsg = result.status;
                                            //alert("Data returned: " + msg);
                                            if(successMsg == 1){
                                                //alert("Event Posted Successfully!");
                                                $('#ajaxLoaderPostEvent').hide();
                                                
                                                $('#postEventForm-wrapper').hide();
                                                controlDimmer(-1);
                                                //$('#postEventSuccess').show();
                                                load_events(latitude, longitude);
                                                
                                                // clear out the form fields on success
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
                                                $('#ajaxLoaderPostEvent').hide();
                                                $('#eventPostErrors').empty().append("\
                                                <font color='red'>" + msg + "</font>");
                                                }
                                        
                                            }
                                        });
                                    }
                                else {
                                    $('#ajaxLoaderPostEvent').hide();
                                    $('#eventPostErrors').empty().append("\
                                    <font color='red'>That address is not valid!</font>");
                                    }
                                });
                            } // end if for contact info validation
                        else {
                            $('#ajaxLoaderPostEvent').hide();
                            $('#eventPostErrors').empty().append("\
                            <font color='red'>Contact info not valid!</font>");
                            }
                        }
                    else {
                        $('#ajaxLoaderPostEvent').hide();
                        $('#eventPostErrors').empty().append("\
                        <font color='red'>Dates are not incorrect format.</font>");
                        }
                    }
                else {
                    // not signed in
                    console.log("not signed in...");
                    $('#ajaxLoaderPostEvent').hide();
                    $('#eventPostErrors').empty().append("\
                    <font color='red'>You need to be signed in to do that</font>");
                    }
                });
            }
        else {
            $('#ajaxLoaderPostEvent').hide();
            $('#eventPostErrors').empty().append("\
            <font color='red'>Something was empty</font>");
            }
        }
    */
