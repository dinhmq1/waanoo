// event map scripting
//ids used: EventMapWrapper... EventMapCanvas

function closeEventMap() {
	$('#dimmer').hide();
	$('#EventMapWrapper').hide();
	$('#EventDirections').hide();
	}


function openEventMap(lat, lon, addy) {
	$('#dimmer').show();
	$('#EventMapWrapper').show();
	$('#eventAddressText').empty().append(addy);
	
	var map_center = new google.maps.LatLng(lat,lon);
	// NOTE: mapTypeId is required... lol wow.
	var myOptions = {
					center: map_center,
					zoom: 12,
					mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					
	//this needs to be global because im going to call it later. A lot.
	mapEvent = new google.maps.Map(document.getElementById("EventMapCanvas"),myOptions);
	
	flagIco = 'images/flag.png';
	//add a marker:
	var mrk = new google.maps.Marker({
				map: mapEvent,
				draggable: false,
				animation: google.maps.Animation.BOUNCE,
				position: map_center,
				icon: flagIco
			});
	}

// directions vars
var directionsDisplay;
var directionsService;

function getDirections() {
	// pull global current lat and lng
	$('#EventDirections').show();
	$('#EventDirectionsDisplay').empty();
	var lat = latitude;
	var lon = longitude;
	var addy = $('#eventAddressText').text();
	var coder = new google.maps.Geocoder();
	
	coder.geocode({'address': addy}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			//$('#latlngLoc').empty().append("Detected your location: " + results[0].formatted_address);
			// make directions on the map
			if(directionsDisplay != null)
				directionsDisplay.setMap(null);
			directionsDisplay = new google.maps.DirectionsRenderer();
			directionsDisplay.setMap(mapEvent);
			directionsDisplay.setPanel(document.getElementById('EventDirectionsDisplay'));
			
			// desintation as lat lng object
			var destination = results[0].geometry.location;
			var current_position = new google.maps.LatLng(lat,lon);
			
			
			var request = 	{
						origin: current_position,
						destination: destination,
						travelMode: google.maps.DirectionsTravelMode.DRIVING
						};
			
			directionsService = new google.maps.DirectionsService();
			directionsService.route(request, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(result);
					console.log(result);
					}
				});

			console.log(status);
	        }
	      else {
	        alert("Geocoder failed due to: " + status + addy);
	      }
	    });
	}
