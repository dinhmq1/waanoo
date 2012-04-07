// this library will include functions that open my events window

function openMyEvents() 
{
	console.log("Open My Events was clicked.");
	$('#myEventsWrapper').show();
	$('#dimmer').show();
	}

function closeMyEvents()
{
	$('#myEventsWrapper').hide();
	$('#dimmer').hide();
}