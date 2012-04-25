// this library will include functions that open my events window

function openMyEvents() {
	// this is in postevent.js 
	var testSignIn = checkSignedIn();
	testSignIn.success(function (data) {
		
		if(data == 1){
			console.log("Open My Events was clicked.");
			$('#myEventsWrapper').show();
            
			controlDimmer(1);
			
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
						$('#myEventsContents').empty().append("<br /> <br /> <br />" + content);
						}
					else {
						$('#myEventsContents').empty().append("<br /> <br /> <br /> Sorry, could not load events!");
						}
					}
				});//end ajax
			}// signed in
		else {
			alert("you have to be signed in to do that!");
			console.log("user not logged in");
			}// not signed in
		});// end promise
	}

function closeMyEvents(){
	$('#myEventsWrapper').hide();
    
	controlDimmer(-1);
}
