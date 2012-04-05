<?php 
require 'php/header.php';
//temporary
require("php/populate_YQL_events.php");
?>

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

<p>&nbsp;
</p>

<div id='dimmer'>
</div>

<div id='postEventSuccess'>
<br />
Event Posted Successfully!
<br />
<br />
	<div class='login-button' onClick='close_event_success_window()' 
		style='width:50%; margin-left:25%;'>
		Close!
	</div>
</div>

<div id="moreEventsBtn" onclick="loadMoreEvents()">
	<input type="hidden" id="eventOffset" value="7" />
	[LOAD MORE]
</div>

<br />

<?php require 'php/footer.php';?>
