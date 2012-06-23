<?php 
require 'php/header.php';
//temporary
//require("php/populate_YQL_events.php");
?>
<span id='latlngLoc'>Your location:</span>

    <br />
    <br />
    <div id="logo">
    <img src='images/logos/logo_main.png' />
    </div>
    <br />
    
    <!-- search bar -->
<form id='searchBarForm'>
<input type='text' class='searchBar' id='searchBarAuto' size='20' />
    &nbsp;
    <!-- 
    <a href='#' >go</a> -->
    <input type='submit' value='&#8734;' class='testBlackBtn'/>
    </form>
<br />

    <!-- 
    <span style='font-size:50%;'>
        Order By:</span>
        -->
    
    <span id="loadByLocation" class='testBlackBtn' onClick="loadEventsByLocation()">
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_location_inactive.png' />
            -->
            LOCATION
        </a>
    </span>
    
    &nbsp;&nbsp;
    <span id="loadByLocation" class='testBlackBtn' onClick="loadEventsByPopularity()">
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_location_inactive.png' />
            -->
            POPULAR
        </a>
    </span>
    
    
    &nbsp;
    &nbsp;
    <span id="loadByDate" class='testBlackBtn' onClick="loadEventsByDate()">
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_date_inactive.png' />
            -->
            DATE
        </a>
    </span>
    &nbsp;
    <span style='font-size:90%;'>|</span>
    &nbsp;
    <span id="postEventButton" class='testBlackBtn' onClick='open_post_event()'>
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_post_inactive.png' />
            -->
            POST
        </a>
    </span>
    
    &nbsp;&nbsp;
    <span id="myEventsButton" class='testBlackBtn' onClick="openMyEvents()">
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_myevents_inactive.png' />
            -->
            MY EVENTS
        </a>
    </span>
    
    &nbsp;&nbsp;
    <span id="showMapButton" class='testBlackBtn' onClick='open_map_selector()'> 
        <a href="#" class='noclick'>
            <!-- <img src='images/buttons/btns_headline/btn_fixlocation_inactive.png' />
            -->
            FIX LOCATION
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
    <a href="#" class='noclick'>
        <img src='images/buttons/btns_headline/btn_load_inactive.png' />
    </a>
</div>
&nbsp;&nbsp;&nbsp;
    <span id="ajaxLoaderLoadMore">
        <img src="images/ajax-loader-transp-arrows.gif" />
    </span>

<?php require("php/all_popups.php"); ?>
<?php require 'php/footer.php';?>
