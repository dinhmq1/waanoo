<?php 
require 'php/header.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Detected your location:</span>

    
    <br />
	<div id="logo">
	<img src='images/logos/logo_main.png' />
	</div>


	<span id="loadByLocation" onClick="loadEventsByLocation()">
		<a href="#" >
            <img src='images/buttons/btns_headline/btn_location_inactive.png' />
        </a>
	</span>
    
	&nbsp;&nbsp;
	<span id="loadByDate"onClick="loadEventsByDate()">
		<a href="#" >
            <img src='images/buttons/btns_headline/btn_date_inactive.png' />
        </a>
	</span>
	&nbsp;&nbsp;
	
    
    <span id="postEventButton" onClick='open_post_event()'>
        <a href="#">
            <img src='images/buttons/btns_headline/btn_post_inactive.png' />
        </a>
    </span>
    
    &nbsp;&nbsp;
    <span id="myEventsButton" onClick="openMyEvents()">
        <a href="#">
            <img src='images/buttons/btns_headline/btn_myevents_inactive.png' />
        </a>
    </span>
    
    &nbsp;&nbsp;
    <span id="showMapButton" onClick='open_map_selector()'> 
        <a href="#"><button>Fix Location</button></a>
    </span>
    
    &nbsp;&nbsp;
    <span id="ajaxLoaderLoadEvents">
		<img src="images/ajax-loader-transp-arrows.gif" />
	</span>
    
<br />
<br />

<div class="eventViewer">
    <img src="images/ajax-loader-transp-arrows.gif" />
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
