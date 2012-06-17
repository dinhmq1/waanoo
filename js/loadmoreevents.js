// load more events onclick
// SEE LOCATION_DETECTION.JS for default loader function

// loadMoreEvents()
// increments based on loader type. This is all done in Jvascript and
// we save the offset on the page. It should actually be done in PHP with
// GET request parameters. This should be modified.
function loadMoreEvents(){
    var off = $('#eventOffset').val();
    var searchType = $('#searchType').val();
    
    $('#ajaxLoaderLoadMore').show();
    if(searchType == "location") {
        //GLOBALS:
        var lat = latitude;
        var lon = longitude;
        
        coords = {
            latitude: lat,
            longitude: lon,
            offset: off
            };
        
        $('#ajaxLoaderLoadEvents').show();
        
        $.post("./php/load_events_by_location.php", coords, function(results){
            // new offset:
            var newOff = 10 + Number(off);
            $('#eventOffset').val(newOff);
            console.log("new offset: " + newOff);
            
            $('#ajaxLoaderLoadEvents').hide();
            $('#ajaxLoaderLoadMore').hide();
            
            // with results:
            $('.eventViewer').append(results);
            $('#searchType').val("location");
            });
        }
    
    if(searchType == "date") {
    
        latLng = {
        lat: latitude,
        lon: longitude,
        offset: off
        };
        $('#ajaxLoaderLoadEvents').show();
        $.ajax({
            type: "POST",
            url: "./php/load_events_by_date.php", 
            data: latLng,
            dataType: "json",
            success: function(result){
                var status = result.status;
                var content = result.content;
                console.log("Status of search: " + status);
                
                $('#ajaxLoaderLoadEvents').hide();
                $('#ajaxLoaderLoadMore').hide();
                    
                if(status == 1) {
                    var newOff = 10 + Number(off);
                    $('#eventOffset').val(newOff);
                    console.log("new offset: " + newOff);
                
                    $('.eventViewer').append(content);
                    $('#searchType').val("date");
                    
                    }
                }
            });
        }
        
    if(searchType == "keyword") {
        // Note: latitude, longitude are globals
        
        var searchTerm = $('#searchBarAuto').val();
         eventData = {
            term: searchTerm,
            latitude: latitude,
            longitude: longitude,
            offset: off
            };
            
        console.log("lat, lng, search term: ");
        console.log(eventData);
        
        $.ajax({
            type: "POST",
            url: "./php/search_main.php",
            data: eventData,
            dataType: "json",
            success: function(result) {
                // do stuff on result
                
                $('#ajaxLoaderLoadEvents').hide();
                $('#ajaxLoaderLoadMore').hide();
                
                if(result.status == 1) {
                    var newOff = 10 + Number(off);
                    $('#eventOffset').val(newOff);
                    console.log("new offset: " + newOff);
                    $('#searchType').val("keyword");
                    
                    console.log("search completed.");
                    $('.eventViewer').append(result.content);
                    }
                }
            });
        }// end keyword search
        
    if(searchType == "popularity") {
        latLng = {
        lat: latitude,
        lon: longitude,
        offset: off
        };
        $('#ajaxLoaderLoadEvents').show();
        $.ajax({
            type: "POST",
            url: "./php/load_events_by_popularity.php", 
            data: latLng,
            dataType: "json",
            success: function(result){
                var status = result.status;
                var content = result.content;
                console.log("Status of search: " + status);
                
                $('#ajaxLoaderLoadEvents').hide();
                $('#ajaxLoaderLoadMore').hide();
                    
                if(status == 1) {
                    var newOff = 10 + Number(off);
                    $('#eventOffset').val(newOff);
                    console.log("new offset: " + newOff);
                
                    $('.eventViewer').append(content);
                    $('#searchType').val("popularity");
                    }
                }
            });
        }
    }
    

// Calls a function that loads events in location_detection.js
function loadEventsByLocation() {
    var lat = latitude;
    var lon = longitude;
    $('#searchType').val("location");
    load_events(lat, lon)
    $('#eventOffset').val("10");
    }
    

// loads events by date ordering. 
// still has a 50 mile radius limit.
function loadEventsByDate() {
    latLng = {
        lat: latitude,
        lon: longitude
        };
    
    $('#ajaxLoaderLoadEvents').show();
    $.ajax({
        type: "POST",
        url: "./php/load_events_by_date.php", 
        data: latLng,
        dataType: "json",
        success: function(result){
            $('#ajaxLoaderLoadEvents').hide();
            var status = result.status;
            var content = result.content;
            console.log("Status of search: " + status);
            if(status == 1) {
                $('.eventViewer').empty().append(content);
                $('#searchType').val("date");
                $('#eventOffset').val("10");
                
                }
            else if(status == 2) {
                    alert("All the events near you are expired!");
                }
            else {
                alert("Loading events failed, try again!");
                }
            } // end success callback
        });
    }


// loadEventsByPopularity()
// Loads with a 50 mile radius, highest pageview count first.

function loadEventsByPopularity() {
    // our req obj
    latLng = {
        lat: latitude,
        lon: longitude
        };
    
    // show swirling icon
    $('#ajaxLoaderLoadEvents').show();
    $.ajax({
        type: "POST",
        url: "./php/load_events_by_popularity.php", 
        data: latLng,
        dataType: "json",
        success: function(result){
            // hide loader
            $('#ajaxLoaderLoadEvents').hide();
            
            var status = result.status;
            var content = result.content;
            console.log("Status of search: " + status);
            
            if(status == 1) {
                // add new data
                $('.eventViewer').empty().append(content);
                
                // set search type
                $('#searchType').val("popularity");
                
                // set offset for loadmore.
                $('#eventOffset').val("10");
                
                }
            else {
                alert("Failed to retrieve events! Try Again!");
                }
                
            }
        });
    }
