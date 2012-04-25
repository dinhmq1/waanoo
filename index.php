<?php 
require 'php/header.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Detected your location:</span>

    
    <br />
    <br />
	<div id="logo">
	<img src='images/logos/logo_main.png' />
	</div>
    <br />


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
        <a href="#">
            <img src='images/buttons/btns_headline/btn_fixlocation_inactive.png' />
        </a>
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

<div id="moreEventsBtn" onclick="loadMoreEvents()">
	<input type="hidden" id="searchType" value="location" />
	<input type="hidden" id="eventOffset" value="10" />
	<img src='images/buttons/btns_headline/btn_load_inactive.png' />
	
</div>
&nbsp;&nbsp;&nbsp;
	<span id="ajaxLoaderLoadMore">
		<img src="images/ajax-loader-transp-arrows.gif" />
	</span>

<?php require("php/all_popups.php"); ?>
<?php require 'php/footer.php';?>
