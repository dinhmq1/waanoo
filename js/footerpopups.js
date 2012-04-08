// footer popup controls and related functions

function aboutBtn() {
	console.log("about button");
	$('#aboutWaanoo').show();
	}

function aboutBoxClose() {
	$('#aboutWaanoo').hide();
	}

function contactBtn(){
	console.log("contact button");
	$('#contactWaanoo').show();
	}

function contactBoxClose() {
	$('#contactWaanoo').hide();
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
