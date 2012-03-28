// main script for posting events:

function open_post_event(){
	console.log("showing event posting window");
	$('#postEventForm-wrapper').show();
	$('#dimmer').show();
	
	loadScriptMiniMap();
	}

function close_post_event(){
	console.log("hiding event posting window");
	$('#postEventForm-wrapper').hide();
	$('#dimmer').hide();
	
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
	
		marker_you = new google.maps.Marker({
				map:map2,
				draggable:true,
				animation: google.maps.Animation.BOUNCE,
				position: marker_pos,
				icon: you_icon
			});
		
		// make reverse geocoder object
		revGeocoder = new google.maps.Geocoder();
		// make a listener for the marker
		google.maps.event.addListener(marker_you, 'mouseup', reset_position_mini);
		
		}//end init func
		
	var revGeocoder = null;

	function reset_position_mini(){
		var new_pos = marker_you.getPosition();
		latitude = new_pos.lat();
		longitude = new_pos.lng();
		var current_position = new google.maps.LatLng(latitude,longitude);
		
		var current_position = marker_you.getPosition();
		map2.setCenter(current_position);
		console.log("lat new: " + latitude + " lng new: " + longitude);
		
		
		// SHOULD GET REVERSE GEOCODE AND UPDATE INTO THE CORECT FIELD:
		revGeocoder.geocode({'latLng': current_position}, function(results, status) {
	        if (status == google.maps.GeocoderStatus.OK) {
				// just set the field to the geocode return addy
		          $('#eventLocation').val(results[1].formatted_address);
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
		
		geocoder = new google.maps.Geocoder();
		var address = $('#eventLocation').val();
		console.log(address);	
			
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map2.setCenter(results[0].geometry.location);
				
				console.log(results);
				marker_you.setPosition(results[0].geometry.location);
				
				// call the other function that updates lat and lng
				//reset_position_mini();
				}
			});
		}
