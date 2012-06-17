// makes a map that shows a map of all the pageviews for an event

// open popup
function showPageviewMap() {
    $('#pageviewMapWrapper').show();
    controlDimmer(1);
    }

// close popup
function closePageviewMap() {
    $('#pageviewMapWrapper').hide();
    controlDimmer(-1);
    }

// make AJAX call to get pageview data for event
// also call helper functions to show map and popups
function openPageviewMap(event_id, map_id, embed) {
    // lat and lon are taken from globals
    eventData = {
        eventID: event_id
        };
    
    $.ajax({
        type: "POST",
        url: "./php/pageview_map_data.php",
        data: eventData,
        dataType: "json",
        success: function(result) {
            // do stuff on result
            if(result.status == 1) {
                console.log("success: " + result);
                //parsePageviewData(result.data);
                if(embed == false)
                    showPageviewMap();
                addPageviewMap(result.data, result.lat_event, result.lon_event, map_id);
                }
            else {
                console.log("failed: " + result)
                alert(result.message);
                }
            }
        });
    }

// not used, just for testing purposes.
function parsePageviewData(pData) {
    // none yet
    console.log("parsing pagewview data");
    
    // Looks like:
    // array_push($latLon, array("lat" => $lat, "lon" => $lon, "timestamp" => $ts));
    
    console.log(pData);
    /*
    var pageview;
    for(pageview in pData) {
        console.log("pageview:" + pageview);
        console.log("lat:" + pageview.lat);
        }
    */
    var lenArr = pData.length;
    for(var i = 0; i < lenArr; i++) {
        var lat = pData[i].lat;
        var lon = pData[i].lon;
        var ts = pData[i].timestamp;
        }
    }
    

// appends a google maps to #pageviewMap
// inside of #pageviewMapWrapper
// located in all_popups.php
// PASSED: lat and lng of event, and event pageview array
function addPageviewMap(pageviewData, lat_event, lon_event, map_id) {
    // map id on popup = "pageviewMap"
    // map id on embed = pageviewMapEmbed

    var map_center = new google.maps.LatLng(lat_event,longitude);
    
    // NOTE: mapTypeId is required... lol wow.
    var myOptions = {
        center: map_center,
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
            };

    //Make global for usefullness later perhaps
    pageviewMapObject = new google.maps.Map(document.getElementById(map_id),myOptions);
    
    //add a marker:
    var youIcon = 'images/person_you.png';
    var eventMarker = new google.maps.LatLng(lat_event, lon_event);
    var eventMarker = new google.maps.Marker({
                map: pageviewMapObject,
                draggable: true,
                animation: google.maps.Animation.BOUNCE,
                position: eventMarker,
                icon: youIcon
            });
            
    // add all the other markers
    var flagIcon = 'images/flag.png';
    var lenArr = pageviewData.length;
    for(var i = 0; i < lenArr; i++) {
        // access data
        var lat = pageviewData[i].lat;
        var lon = pageviewData[i].lon;
        var ts = pageviewData[i].timestamp;
        
        // make marker for pageview
        var pvMarker = new google.maps.LatLng(lat, lon);
        var marker_pos = new google.maps.Marker({
                map: pageviewMapObject,
                draggable: true,
                position: pvMarker,
                icon: flagIcon
            });
        }
    }    
    
    
    
    
    
    
    
    
