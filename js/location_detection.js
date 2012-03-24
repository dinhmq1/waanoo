// location detection brains
/** TO USE: call get_location() inside of the ready() wrapper **/

// this function will do an ajax call and load up the events based on your location
// Note: we should save this stuff in a cookie that does not expire so that this only happens once

	function load_events(lat, lon){
		// should reverse geocode right here and find closest address!!!
		// this should make it more obvious to the user why some events are being displayed
		
		coords = {
					latitude: lat,
					longitude: lon
					};
		
		$.post("./php/load_events_by_location.php", coords, function(results){
			$(".eventViewer").empty().append(results);
			});
		}


//this function is the callback of the browser location detector
	function show_map(position)
		{
		var latitude = position.coords.latitude;
		var longitude = position.coords.longitude;
		
		// globals
		lat = "latitude: " + latitude;
		lon = "Longitude: " + longitude;
		console.log(lat,lon);
		
		load_events(lat, lon);
		}


//main browser location detecting function
	function get_location() 
		{
		//EXECUTES SECOND
		//navigator.geolocation is a global broswer object
		navigator.geolocation.getCurrentPosition(show_map);
		
		}
