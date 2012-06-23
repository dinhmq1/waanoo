TO DO:
======

* new highlighting color: #DB1D46


* host analytics:
    1. Pageview chart geographic
    2. RSVP chart geographic
    3. m/f ratio
    4. number of pageviews/ RSVPS total.
    5. Invite mailer
* search:
    1. Index the title 
    2. Index text address
    3. Index tags
* Post event:
    1. tags drop down. You can add more tags, another drop down appears.
    2. use angular.js and twitter bootstrap for this, 
    3. make the post event window tabbed, paginated, so it is not so confusing.
    4. allow temporary saving?
* Show most popular host:
    1. show users that have posted a lot of events
    2. exempt the admin roled users from this list.
* Somehow show related events. Just use same search algorithm as main search bar.
* change .eventViewer to an ID it should not be a class.    
* in single event: fix attending btn.
* in update php files. put space btwn phone number parts on HTMLouputlib
    

    
    * note: In main.js I added an event handler inside of the .on() 
    that prevents default on all <a> tags. This should be redone: there
    shoulb be a '.disableLink' class and then you can do it like this:
        $('.disableLink a').click( function(event) { event.preventDefault(); });
    
    
    * dimmer fix: added a counter main.js as global 'isPOPUP = 0;'
        * new event handler to track changes to this counter... 
    
    
    * for design:
        1. add a canvas based image resizer: we should be able to select a 
        perfect square.
        2. The image is then saved as this perfect square resize.
        3. Finally, we should still keep the large image so that we can display it on the single event viewing
        4. Make single event viewer box.
        5. loading bars for uploading image.
        6. loading bar for when you first load the page.
        7. For now the images can be reposition with javascript based on their dimensions
        
        
    * fix timepicker so that it is not 24hr clock
    
    
    _THIS IS DONE_ - just need to add for edit events
    -add to post event:
        Check box that allows you do add event created contact info:
        -like craigslist:
        ---> user can select either email or phone number as contact
        ---> appropriate box is appended to form if this happens.
        ---> box should be removed after event posted
        ---> add new DB column for contact info.
        ---> phone number should be 3 number fields on form
        ---> email can be regexed.
        
        
    
    -work on facebook authentication.
        -then import facebook events.

    -add the extra fields for event registration
        -scripting for the backend for this
        
    /// HAZY THINGS 
    -store the last good address for the user in DB.
        -then if we timeout detecting location, use this.
    
    -generally, the site is ugly, it is only functional

    /// DOWN THE ROAD THINGS
    -Browser/viewing testing
        -I.E.
        -Different resolutions
        -mobile
            -maybe a mobile detection setting that 
            can show where to find the app in the app store.
        -older versions of I.E.
        -look over CSS for things to fix
        
    
    -advanced search:
        -this will allow you to search by date
        -also you can search by keyword
        
    -others/ fixes
        -make the front end update things when signup/ signin

    Use promises for asychronous function calls.
    Tell other people about promises. An callbacks. 
    http://stackoverflow.com/questions/5316697/jquery-return-data-after-ajax-call-success
    



Basic site structure:
====================

* Main folder:
    1. Contains files that will be viewed by browser directly
    2. Contains documentation, sometimes an SQL dump or whatev. 
        
    Other folders:
        php
        -all backend PHP scripts
        -also header.php, scripts.php, footer.php, cxn.php
        js
        -contains all the javascript used on the site
        -should hold any library used, unless you can link to a google repository
        css
        -should have one large main CSS folder
        -may contain CSS for plugin elements. 
        img
        -contains images
        - 'img/stock' will be basic site images like arrows, ect
        - 'img/imgdb' will hold all images accessed by the mysql database.
        
        
        
        
        
Tables
    eventvalues
    columns: eventid (INT) (PK)(NN)(UQ)(AI) 
         datetime (DATETIME) (NN)
         location (VARCHAR(300)) (NN)
    
    uservalues
    columns: userid (INT) (NN)(UQ)(AI)
         joindate (DATETIME)
         profilepic (VARCHAR(300))
         password (VARCHAR(30)) (NN)
         sex (VARCHAR(6)) (NN)
         location (VARCHAR(300))
         name (VARCHAR(50)) (NN)
            
useful code for this project:

    https://github.com/a-r-d/ooz-message-board
        -has working AJAX scripts
        -has a very very similar structure
        -has a similar library of functions see php/lib.php
    
    GEOLOCATION via HTML 5
    
    http://html5demos.com/geo

    (Add more URLs as you find them!)

    Google maps:places... (im working on this now -aaron)
    http://code.google.com/apis/maps/documentation/places/
            
            

Latest Tables
==============

    5/5/12
    pageviews:
        id=int, autoinc, pk
        event_id = int, event id foreign key_
        lat = double (10,6)
        lon = double (10,6)
        timestamp = int, unix time for pageviews
        
        
    5/6/12
    event_comments:
        message_id = int, autoinc, pk
        event_id = int, event id foreign key_
        user_id = int, user is foreign key_
        timestamp = int, unix time for pageviews
        message = varchar 500
        
        
Layout
======

* backgound color: #EEEDEA
* content bars: #F8F8F8 
* popup opacity: 0.95;
        
    
Search algorith outline:
=========================

 --> take user input:
 
    $input = $_GET['search'];
    preg_replace("#[\{\[\}\]\\;\/]#', '', $input); //filter some nasties
    
    $res1 = search_location($input);        // see if we geocode anything!
    $res2 = search_date($input);            // see if we can get a date
    $res3 = search_text($input);            // see if we can search for text with what is left?
    
    function search_location($input)
        {
        //something like this:
        $strings = preg_split("#[ ]#, '', $input); //break into strings when spaces
        
        //then we match against five numbers for a zip code:
        
        // or do we just try to geocode the whole thing?
        
        return $res1;
        }
    
    function search_date($input)
        {
        
        
        }
    

