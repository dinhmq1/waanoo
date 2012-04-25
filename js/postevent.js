// main script for posting events:

function open_post_event(){
	var loggedin = $('#loginStatus').val();
	console.log("logged in? " + loggedin);
	
	if(loggedin == 1 && loggedin != ""){
		console.log("showing event posting window");
		$('#postEventForm-wrapper').show();
		controlDimmer(1);
		
		initMiniMap();
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

function initMiniMap() {
		//open up the map
		console.log("initializing map");
		
		//postion for marker
		var lat_m = latitude;//37.79787684894448;
		var lng_m = longitude;//-83.7020318012207;
		
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
		map2 = new google.maps.Map(document.getElementById("miniMapCanvas"),myOptions);
		
		//add a marker:
		you_icon = 'images/person_you.png';
		marker_pos = new google.maps.LatLng(lat_m,lng_m);
	
		marker_pos = new google.maps.Marker({
				map:map2,
				draggable:true,
				animation: google.maps.Animation.BOUNCE,
				position: marker_pos,
				icon: you_icon
			});
		
		// make reverse geocoder object
		revGeocoder = new google.maps.Geocoder();
		// make a listener for the marker
		google.maps.event.addListener(marker_pos, 'mouseup', reset_position_mini);
		
		}//end init func
		
	var revGeocoder = null;


	function reset_position_mini(){
		var new_pos = marker_pos.getPosition();
		latitude_event = new_pos.lat();
		longitude_event = new_pos.lng();
		var current_position = new google.maps.LatLng(latitude_event,longitude_event);
		
		var current_position = marker_pos.getPosition();
		map2.setCenter(current_position);
		console.log("lat new: " + latitude_event + " lng new: " + longitude_event);
		
		
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

// geocodes address and resets map position
	function reset_coords(){
		console.log("begin client side geocode query");
		var geocoder = new google.maps.Geocoder();
		
		var address = $('#eventLocation').val();
		
		if(address == ""){
			address = "45221";
			}
		
		console.log(address);	
			
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map2.setCenter(results[0].geometry.location);
				console.log(results);
				marker_you.setPosition(results[0].geometry.location);
				}
			});
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
		/*	possible errors:
		 * 
		 * -empty fields
		 * -start date after end date
		 * --[address should always be valid??? --> test it again and 
		 * 		make sure that geocoding does NOT fail!]
		 * 	-also, maybe I should break down the geocoded addy into:
		 * 		-state
		 * 		-country
		 * 		-zip
		 * 		-address
		 * 
		 * --[dates should always be valid- jQuery UI plugin]
		 * 
		 * 
		 * More fields:
		 * 	-venue
		 * 	-price
		 * 	-is it a regular occcurance?
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
			testEmpty(eventDateBegin) && testEmpty(eventDateEnd) 
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
