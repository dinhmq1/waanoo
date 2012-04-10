<?php 
require 'php/header.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Detected your location:</span>

<div id="logo">
waan<span id='infin'>∞</span>
</div>

Events Near You:&nbsp;&nbsp;
	<span id="loadByLocation" onClick="loadEventsByLocation()">
		<a class='btnTemplate' href="#" >By Location</a>
	</span>
	&nbsp;&nbsp;
	<span id="loadByDate"onClick="loadEventsByDate()">
		<a class='btnTemplate' href="#" >By Date</a>
	</span>
	&nbsp;&nbsp;
	<span id="ajaxLoaderLoadEvents">
		<img src="images/ajax-loader-transp-arrows.gif" />
	</span>
<br />
<br />

<div class="eventViewer">
</div>

<div id="tempWindow">
</div>


<div id='dimmer'>
</div>

<br />

<div id="moreEventsBtn" onclick="loadMoreEvents()" class='btnTemplate'>
	<input type="hidden" id="searchType" value="location" />
	<input type="hidden" id="eventOffset" value="10" />
	LOAD MORE! 
	
</div>
&nbsp;&nbsp;&nbsp;
	<span id="ajaxLoaderLoadMore">
		<img src="images/ajax-loader-transp-arrows.gif" />
	</span>

<div id='postEventSuccess'>
	<br />
	Event Posted Successfully!
	<br />
	<br />
		<div onClick='close_event_success_window()' 
			style='width:50%; margin-left:25%;'>
			<a href='#' >Close!</a>
		</div>
</div>
<br />

<?php require 'php/footer.php';?>
