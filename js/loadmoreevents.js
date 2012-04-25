// load more events onclick
// SEE LOCATION_DETECTION.JS for default loader function
function loadMoreEvents(){
	var off = $('#eventOffset').val();
	var searchType = $('#searchType').val();
	
	$('#ajaxLoaderLoadMore').show();
	if(searchType == "location") {
		//GLOBALS:
		var lat = latitude;
		var lon = longitude;
		
		coords = {
			latitude: lat,
			longitude: lon,
			offset: off
			};
		
		$('#ajaxLoaderLoadEvents').show();
		
		$.post("./php/load_events_by_location.php", coords, function(results){
			// new offset:
			var newOff = 10 + Number(off);
			$('#eventOffset').val(newOff);
			console.log("new offset: " + newOff);
			
			$('#ajaxLoaderLoadEvents').hide();
			$('#ajaxLoaderLoadMore').hide();
			
			// with results:
			$('.eventViewer').append(results);
			$('#searchType').val("location");
			});
		}
	
	if(searchType == "date") {
	
		latLng = {
		lat: latitude,
		lon: longitude,
		offset: off
		};
		$('#ajaxLoaderLoadEvents').show();
		$.ajax({
			type: "POST",
			url: "./php/load_events_by_date.php", 
			data: latLng,
			dataType: "json",
			success: function(result){
				var status = result.status;
				var content = result.content;
				console.log("Status of search: " + status);
				
				$('#ajaxLoaderLoadEvents').hide();
				$('#ajaxLoaderLoadMore').hide();
					
				if(status == 1) {
					var newOff = 10 + Number(off);
					$('#eventOffset').val(newOff);
					console.log("new offset: " + newOff);
				
					$('.eventViewer').append(content);
					$('#searchType').val("date");
                    
					}
				}
			});
		}
	}
	

function loadEventsByLocation() {
	var lat = latitude;
	var lon = longitude;
	$('#searchType').val("location");
	load_events(lat, lon)
	$('#eventOffset').val("10");
	}
	

function loadEventsByDate() {
	latLng = {
		lat: latitude,
		lon: longitude
		};
	
	$('#ajaxLoaderLoadEvents').show();
	$.ajax({
		type: "POST",
		url: "./php/load_events_by_date.php", 
		data: latLng,
		dataType: "json",
		success: function(result){
			$('#ajaxLoaderLoadEvents').hide();
			var status = result.status;
			var content = result.content;
			console.log("Status of search: " + status);
			if(status == 1) {
				$('.eventViewer').empty().append(content);
				$('#searchType').val("date");
				$('#eventOffset').val("10");
                
				}
			}
		});
	}
