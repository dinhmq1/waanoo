<?php 
require 'php/header.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Detected your location:</span>

<div id="logo">
waan<span id='infin'>∞</span>
</div>

Events Near You:
<br />
<br />

<div class="eventViewer">
</div>

<div id="tempWindow">
</div>


<div id='dimmer'>
</div>

<br />

<div id="moreEventsBtn" onclick="loadMoreEvents()">
	<input type="hidden" id="eventOffset" value="14" />
	<a href='#' class='btnTemplate'>LOAD MORE!</a>
</div>


<div id='postEventSuccess'>
	<br />
	Event Posted Successfully!
	<br />
	<br />
		<div onClick='close_event_success_window()' 
			style='width:50%; margin-left:25%;'>
			<a href='#' class='btnTemplate'>Close!</a>
		</div>
</div>
<br />

<?php require 'php/footer.php';?>
