// search function ajax:

// main search ajax call:
    /*
     * On Backend:
    $searchTerm = $_GET['term'];
    $lat_user = $_GET['latitude'];
    $lon_user = $_GET['longitude'];
    */

function mainSearch(searchTerm) {
    // Note: latitude, longitude are globals
     eventData = {
        term: searchTerm,
        latitude: latitude,
        longitude: longitude
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
            if(result.status == 1) {
                console.log("search completed.");
                $('.eventViewer').empty().append(result.content);
                }
            }
        });
    }
