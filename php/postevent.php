<?php
session_start();
require('cxn.php');
require('simpleimage.php');
$GLOBALS['debug'] = false;

// first off, check that we are signed in:
if($_SESSION['signed_in'] != true) {
    $arr = array("status" => 0, 
        "message" => "Failed to create event... User not signed in!");
    echo json_encode($arr);
    exit();
    }

// CHECK FOR DUPLICATES
function check_for_dups($all_fields) {
    $cxn = $GLOBALS['cxn'];
    extract($all_fields);
    // $eventName pulled
    // $eventBegin
    
    $eventName = '%'.$name.'%';
    //$eventName = $name;
    
    // CHECK WITHIN 2 weeks
    $start_date_begin = date("Y-m-d H:m:s", strtotime($begin) - 60*60*24*14);
    $start_date_end = date("Y-m-d H:m:s", strtotime($begin) + 60*60*24*14);
    $date_create_begin = date("Y-m-d H:m:s", time() - 60*60*24*14);
    $date_create_end = date("Y-m-d H:m:s", time() - 60*60*24*14);
    
    $qry = "SELECT * FROM user_events 
            WHERE event_title LIKE ?
            AND (
            (start_date > ? AND start_date < ?) 
            OR 
            (date_created > ? AND date_created < ?)
            )";
            
    //echo $qry;
    $stm = $cxn->prepare($qry);
    $stm->bind_param(
            "sssss", 
            $eventName, 
            $start_date_begin, 
            $start_date_end, 
            $date_create_begin, 
            $date_create_end);
    $stm->execute();
    $stm->store_result(); 
    $count = $stm->num_rows;
    
    //echo "Count: $count";
    if($count == 0)
        return true;
    else
        return false;
    
    $stm->close();
    }


    
// CHECK TO MAKE SURE END IS GREATER THAN BEGINING
function dateCheckSensible($all_fields) {
    $end = strtotime($all_fields['end']);
    $start = strtotime($all_fields['begin']);
    if($end > $start && $start > time())
        return true;
    else
        return false;
    }
    
// CHECKS FOR VALID DATE FORMATTING 
function dateCheckValid($all_fields) {
    $end = $all_fields['end'];
    $start = $all_fields['begin'];
    $date_pattern = "/\d+-\d+-\d+ \d+:\d+/";
    if(preg_match($date_pattern, $end) && preg_match($date_pattern, $start))
        return true;
    else
        return false;
    }

// CHECK FOR EMPTINESS
function checkEmpties($all_fields) {
    foreach($all_fields as $key => $value){
        if(empty($value))
            return false;
        }
        return true;
    }

// STRIPS TAGS/ TRIMS WHITESPACE
function clean_fields($fields){
    //$cxn = $GLOBALS['cxn'];
    foreach($fields as $key => $value){
        $value = strip_tags($value);
        $value = trim($value);
        //$value = mysqli_real_escape_string($cxn, $value);
        }
    return $fields;
    }   
    
// clean contact info for email:
function verify_email($email){
    if (!empty($email)){    
        if (preg_match("/^\w[[:alnum:]\.-_]+@[[:alnum:]\.-_]+\.[[:alnum:]]{2,4}$/i", $email) and filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
            }
        else
            return false;   
        }//end if not empty clause
    else
        return false;
    } // end verify email. 
    
    
// clean contact info for phone:
function verify_phone($phone) {
    print_r($phone);
    $p1 = $phone['phone1'];
    $p2 = $phone['phone2'];
    $p3 = $phone['phone3'];
    
    if(preg_match("/[0-9]{3}/", $p1) and preg_match("/[0-9]{3}/", $p2) and preg_match("/[0-9]{4}/", $p3)) 
        return true;
    else
        return false;
    }
    

// process image:
    //Steps:
    /*
     * 1) Resize image and copy to thumbnails
     * 2) save link
     * 3) Resize image and copy to medium sizes
     * 4) save link
     * 5) Delete original image from temp
     * 6) If ALL OK:
     *      -return links to the main logic to put in DB
     * 7) IN MAIN:
     *      -'event_images' table structure:
     *      -image_id   event_id    image_url   description date_uploaded   list_order  active img_size
     */

function resizeAndSubmitImg($imageName, $event_id) {
    $cxn = $GLOBALS['cxn'];
    $image = new SimpleImage();
    //:--> ../images/img_temp/$imageName
    $imageTempDir = "../images/img_temp/".$imageName;
    
    $imageThumbnail = "../images/img_db/img_thumbnail/$imageName";
    $imageThumbURL = "images/img_db/img_thumbnail/$imageName";
    $imageMedium = "../images/img_db/img_medium/$imageName";
    $imageMedURL = "images/img_db/img_medium/$imageName";
    $image->load($imageTempDir); //this loads the image from our 'temp_img' directory.
    
    /*
    echo "eventid: $event_id, 
        imagename: $imageName, 
        imageThumbURL: $imageThumbURL";
    */
        
    // others:
        //../images/img_db/img_medium/
        //../images/img_db/img_thumbnail/
    //section actually resizes images
    $image->resizeToWidth(500); 
    $image->save($imageMedium);
    
    $image->resizeToWidth(100);
    $image->save($imageThumbnail);
    
    // for thumb
    $description = "";
    $sql = "INSERT INTO event_images 
            (event_id, image_url, description, date_uploaded, list_order, active, img_size)
            VALUES (?, ?, ?, NOW(), 1, 1, 1)
            ";
    $stm = $cxn->prepare($sql);
    $stm->bind_param("iss", $event_id, $imageThumbURL, $description);
    $stm->execute();
    $stm->close();
        
    
    // for medium
    $sql = "INSERT INTO event_images 
            (event_id, image_url, description, date_uploaded, list_order, active, img_size)
            VALUES (?, ?, ?, NOW(), 1, 1, 2)
            ";
    $stm = $cxn->prepare($sql);
    $stm->bind_param("iss", $event_id, $imageMedURL, $description);
    $stm->execute();
    $stm->close();
    }
    
    
/* FROM FRONT:
 * 
 * postEventData = {
        latitude: lat_event,
        longitude: lng_event,
        eventName: eventName,
        eventLocation: address,
        eventBegin: eventBegin,
        eventEnd: eventEnd,
        eventDescription: eventDescrip
        isImageSubmitted: isImageBool,
        imageFileName: imgFileName,
        isContactInfoActive: isContactInfo,
        contactInfoType: contactType,
        contactInfoContent: contactInfo
        *   --> phone: 
        *   --> phone1, phone2, phone3
        isFree : isFree,
        eventPrice: eventCost,
        eventCurrency: currencyType,
        isOutdoors: outdoors,
        homepageURL: homeURL,
        tagsList: tagsList
        };
    */
        
    
// pull from front
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$eventName = $_REQUEST['eventName'];
$eventLocation = $_REQUEST['eventLocation'];
$eventBegin = $_REQUEST['eventBegin'];
$eventEnd = $_REQUEST['eventEnd'];
$eventDescrip = $_REQUEST['eventDescription'];

$isImage = $_REQUEST['isImageSubmitted'];
$imageName = $_REQUEST['imageFileName'];

$isContactInfo = $_REQUEST['isContactInfoActive'];
$contactType = $_REQUEST['contactInfoType'];
$contactInfo = $_REQUEST['contactInfoContent'];

$isFree = $_REQUEST['isFree'];
$eventPrice = $_REQUEST['eventPrice'];
$eventCurrency = $_REQUEST['eventCurrency'];
$isOutdoors = $_REQUEST['isOutdoors'];
$homepageURL = $_REQUEST['homepageURL'];
$tagsList = $_REQUEST['tagsList'];

// session variables
$uid = $_SESSION['user_id'];

// encode to array for easy passing
$all_fields = array(
    "lat" => $latitude,
    "lng" => $longitude,
    "name" => $eventName,
    "loc" => $eventLocation,
    "begin" => $eventBegin,
    "end" => $eventEnd,
    "descrip" => $eventDescrip
    );

/// TO DO:
    /*
     * need to add validation:
     * ///Check for empty
     * check that lat and lng are valid
     * check that address geocodes (again???)
     * ///check that times regex to valid
     * ///check that event time comes after current date
     * ///also check that event end is not before start
     * check that name and description have at least 5 letters in them maybe?
     */

// process the contact info, if any
$isOk = false;
if($isContactInfo == 1) {
    if($contactType == "email") {
        $isOk = verify_email($contactInfo);
        }
    
    if($contactType == "phone") {
        $isOk = verify_phone($contactInfo);
        $contactInfo = $contactInfo['phone1'].$contactInfo['phone2'].$contactInfo['phone3'];
        }
    }
else
    $isContactInfo = 0; // in case something nasty happened.


// fix cost stuff
if($isFree == "true" or $isFree == true) {
    $isFree = 1;
    $eventPrice = 0;
} else {
    $isFree = 0;
    // now check if numeric
    if(!is_numeric($eventPrice)) {
        $errFlag = false;
        $errorList .= "Event price is not numeric!";
    }
}


// fix outdoors stuff
if($isOutdoors == "true" or $isOutdoors == true) {
    $isOutdoors = 1;
} else {
    $isOutdoors = 0;
}


// clean a bit:
$all_fields = clean_fields($all_fields);
extract($all_fields);


// do main validation.. set flags:
$errFlag = true;
$errorList = "";

if(!checkEmpties($all_fields)) {
    $errFlag = false;
    $errorList .= "Empty fields!";
}

if(!dateCheckValid($all_fields)) {
    $errFlag = false;
    $errorList .= "Date was not valid!";
}

if(!dateCheckSensible($all_fields)){
    $errFlag = false;
    $errorList .= "Start Date was after end date or Date was before current date.";
}

if(!check_for_dups($all_fields)){
    $errFlag = false;
    $errorList .= "Duplicate event detected!";
}

if(!(($isContactInfo == 0 and $isOk == false) or ($isContactInfo == 1 and $isOk == true))) {
    $errFlag = false;
    $errorList .= "Duplicate event detected!";
}

if($GLOBALS['debug'] == true) {
    $errFlag = false;
    $errorList .= "Debug mode enabled!";
}

if($errFlag){
    // event passed all validations:
    // enter event to main table:
    $query_post = "INSERT INTO user_events 
        (user_id, event_title, event_description, end_date, 
        start_date, date_created, public, is_contactable, contact_type, contact_info,
        event_price, event_currency, homepage_url, tags_list, is_free, is_outdoors) 
        VALUES (?, ?, ?, ?, ?, NOW(), 1, ?, ?, ?,
            ?, ?, ?, ?, ?, ?)";
    $stm = $cxn->prepare($query_post);
    /*echo "inserting: $uid, 
                    $name, 
                    $descrip, 
                    $end, 
                    $begin, 
                    $isContactInfo, 
                    $contactType, 
                    $contactInfo,
                    $eventPrice,
                    $eventCurrency,
                    $homepageURL,
                    $tagsList,
                    $isFree,
                    $isOutdoors";
                    */
    $stm->bind_param("issssissdsssii", 
                    $uid, 
                    $name, 
                    $descrip, 
                    $end, 
                    $begin, 
                    $isContactInfo, 
                    $contactType, 
                    $contactInfo,
                    $eventPrice,
                    $eventCurrency,
                    $homepageURL,
                    $tagsList,
                    $isFree,
                    $isOutdoors
                    );
    $stm->execute();
    $stm->close();
    
    // pull most recent event for ID
    $query_id = "SELECT MAX(event_id) AS event_id FROM user_events";
    $result = mysqli_query($cxn,$query_id)
        or    die ("Couldn't retrieve event list.");
    $row = mysqli_fetch_assoc($result);
    $event_id = $row['event_id']; 
    // NOW WE HAVE: $event_id;
    
    // enter event to location table
    $qry = "INSERT INTO event_address (event_id, address_text, x_coord, y_coord) 
            VALUES (?, ?, ?, ?)";
    $stm = $cxn->prepare($qry);
    $stm->bind_param("isdd", $event_id, $loc, $lat, $lng);
    $stm->execute();
    $stm->close();
    
    //echo "isImage: $isImage";
    /// NOW TO PROCESS IMAGE
    if($isImage == 1) {
        // then we will submit the image
        resizeAndSubmitImg($imageName, $event_id);
        }
    
    // Success:
    $arr = array("status" => 1, "message" => "event success!");
    echo json_encode($arr);

} else {
    $errMsg = "Failed to create event... $errorList";
    $arr = array("status" => 0, "message" => $errMsg);
    echo json_encode($arr);
}

?>
