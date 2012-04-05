// test for and keep track of attendance at events

function attendingEvent($event_id) {
	//test user sign in
	var testSignIn = checkSignedIn();
	testSignIn.success(function (data) {
		//check for truth
		if(data == 1){
			// user is signed in
			// call add attendance function
			console.log("adding attendance");
			
			}
		else {
			alert("you must sign in to do that!");
			$('#advancedPanel').show();
			$('#advancedButton').rotate(-90);
			}
		});
	}

