// footer popup controls and related functions

function aboutBtn() {
	console.log("about button");
	$('#aboutWaanoo').show();
	$('#dimmer').show();
	}

function aboutBoxClose() {
	$('#aboutWaanoo').hide();
	$('#dimmer').hide();
	}

function contactBtn(){
	console.log("contact button");
	$('#contactWaanoo').show();
	$('#dimmer').show();
	}

function contactBoxClose() {
	$('#contactWaanoo').hide();
	$('#dimmer').hide();
	}

/** temporary **/

function advertisingBtn() {
	$('#advertising-button').empty().append("<font color='red'>Coming Soon</font>");
	}

function helpBtn() {
	$('#help-button').empty().append("<font color='red'>Coming Soon</font>");
	}

function toolsBtn() {
	$('#tools-button').empty().append("<font color='red'>Coming Soon</font>");
	}
