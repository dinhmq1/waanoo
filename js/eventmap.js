// event map scripting
//ids used: EventMapWrapper... EventMapCanvas

function closeEventMap() {
	$('#dimmer').hide();
	$('#EventMapWrapper').hide();
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
	
	flagIco = 'images/person_you.png';
	//add a marker:
	var mrk = new google.maps.Marker({
				map: mapEvent,
				draggable: false,
				animation: google.maps.Animation.BOUNCE,
				position: map_center,
				icon: flagIco
			});
	}
