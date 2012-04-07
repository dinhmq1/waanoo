// this library will include functions that open my events window

function openMyEvents() 
{
	console.log("Open My Events was clicked.");
	$('#myEventsWrapper').show();
	$('#dimmer').show();
	
	var toSend = {
		current_lat: latitude,
		current_lon: longitude
		};
	
	$.ajax({
		type: "POST",
		url: "./php/my_events.php",
		data: toSend,
		dataType: "json",
		success: function(result){
			var content = result.content;
			var status = result.status;
			console.log("Content" + content + status);
			
			if(status == 2) {
				$('#myEventsContents').empty().append(content);
				}
			else if (status == 1) {
				$('#myEventsContents').empty().append("<br /> <br /> <br /> You are not logged in!");
				}
			else {
				$('#myEventsContents').empty().append("<br /> <br /> <br /> Sorry, could not load events!");
				}
			}
			
		});//end ajax
	
	
	}

function closeMyEvents()
{
	$('#myEventsWrapper').hide();
	$('#dimmer').hide();
}
