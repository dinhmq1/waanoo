// load more events onclick


// SEE LOCATION_DETECTION.JS for default loader function
function loadMoreEvents(){
	
	var off = $('#eventOffset').val();
	//GLOBALS:
	var lat = latitude;
	var lon = longitude;
	
	coords = {
					latitude: lat,
					longitude: lon,
					offset: off
					};
		
		$.post("./php/load_events_by_location.php", coords, function(results){
			// new offset:
			var newOff = 10 + Number(off);
			$('#eventOffset').val(newOff);
			console.log("new offset: " + newOff);
			
			// with results:
			$('.eventViewer').append(results);
			});

	}
