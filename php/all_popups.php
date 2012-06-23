<!-- POPUPS FOR SITE -->

<div id='map_wrapper'>
    <div id='map_canvas'></div>
        <span onClick='close_map_selector()'>
        <a href='#' class='noclick'>
            <img src="./images/buttons/btns_content/btn_done_inactive.png" />
        </a>
    </span>
    
</div>


<div id='postEventForm-wrapper'>
    <h3 style='text-align: left; padding-left: 20px;'>Post an Event! </h3>
    <span id='cancelPostEventBtn' onClick='close_post_event()'>
        <a href='#' class='testBlackBtn noclick'>Cancel</a>
        </span>
    <div id='postEventForm'>
        <form>
            Title: <input id='eventName' type='text' /><br />
            Where will it be?<input type='text' id='eventLocation' />
                <span id="setLocation" onClick='reset_coords()'>
                <a href='#' class='testBlackBtn noclick'>Test -></a>
                </span><br />
            When will it be?:<input type="text" id="eventDateBegin" name="date" /><br />
            When will it end?:<input type="text" id="eventDateEnd" name="date_end" /><br />
            Describe your event: 
            &nbsp; &nbsp; &nbsp; <span id="descriptionCount">0</span> / 500
            <br />
            <textarea name="event_description" id="eventDescription" rows="4" cols="35" maxlength="500"></textarea><br />
            Upload Image: <a href="#" class='testBlackBtn' id='uploader'>upload!</a> 
                <!--    <small>(popup)</small> -->
                <span id='imgUploadedSpot'></span>
            <!-- Upload a photo for your event:<input type="file" name="image"><br /> -->
            <!--
            Tags: <input id='eventTags' type='text' /><br />
                <div id='eventTagsChoices'>
                Education: <input type="checkbox" id="t_education" value="education" /><br />
                Food: <input type="checkbox" id="t_food" value="food" /><br />
                Free Food: <input type="checkbox" id="t_ffood" value="free_food" /><br />
                Dancing: <input type="checkbox" id="t_dance" value="dancing" /><br />
                Club: <input type="checkbox" id="t_club" value="club" /><br />
                Religion: <input type="checkbox" id="t_religion" value="religion" /><br />
                Sports: <input type="checkbox" id="t_sports" value="sports" /><br />
                Jobs: <input type="checkbox" id="t_jobs" value="jobs" /><br />
                Live music: <input type="checkbox" id="t_lmusic" value="live_music" /><br />
                Ethnic: <input type="checkbox" id="t_ethnic" value="ethnic" /><br />
                University of cincinnati: <input type="checkbox" id="t_uc" value="UC" /><br />
                CCM <input type="checkbox" id="t_ccm" value="CCM" /><br />
                DAPP <input type="checkbox" id="t_daap" value="DAAP" /><br />
                Science <input type="checkbox" id="t_science" value="science" /><br />
                Engineering <input type="checkbox" id="t_eng" value="egineering" /><br />
                
                Custom: <input type="text" id="t_custom" /><br />
                <br />
                    <span id='eventTagsBtn' onClick='closeEventTags()'>
                        <a href='#' class='testBlackBtn'>Close</a>
                    </span>
                </div>
            -->
            
            <br />
            Allow Users to Contact you? &nbsp; <input type="checkbox" id="allowContactEvtent" value="contact" /><br />
            <span id='contactingOptions'>
            <select id='eventContactType'>
                <option value='email'>email</option>
                <option value='phone'>phone</option>
            </select>
            Contact Info:
                <span id='contactInfo'>
                    <input type="text" id="emailContactInfo" /><br />
                </span> 
            </span>
            
            
            <input type='hidden' id='imgFileLocation' value='' />
            <input type='hidden' id='isThereImage' value='0' />
            <input type='hidden' id='oldEventID' value="" />
        </form>
        <span id='eventPostErrors'> </span><br>
        <span  id='eventFormSubmitBtn' onClick='submitNewEvent()'>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='testBlackBtn noclick'>Submit!</a>
        </span>
        &nbsp;&nbsp;&nbsp;
        <span id="ajaxLoaderPostEvent">
            <img src="images/ajax-loader-transp-arrows.gif" />
        </span>
    </div>
    
        <div id='dragNdropMsg'>
        <small>note: drag and drop enabled on map</small>
        </div>
        <div id='miniMapCanvas'>
            <!-- this is where we look up the reverse geocoding stuff -->
    </div>
</div>


<div id='EventMapWrapper'>
    <span id='eventAddressText'></span>
    
    <div id='EventMapCanvas'>
    </div>
    <span onClick='getDirections()'>
        <a href='#' class='testBlackBtn noclick'>Get Directions</a>
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <span onClick='closeEventMap()'>
        <a href='#' class='testBlackBtn noclick'>I'm Done!</a>
    </span>
</div>

<div id='EventDirections'>
    <div id='EventDirectionsDisplay'></div>
</div>


<div id="myEventsWrapper">
    <span class='closer' onClick="closeMyEvents()">
        <a href='#' class='testBlackBtn noclick'>
            <!-- 
            <img src='images/buttons/ps-x-button-50.png' width='30' />
            -->
            Close
        </a>
    </span>
    <br />
    <br />
    
    <div id="myEventsContents">
    </div>
    
    <div id='myEventsBlock'>
    </div>
</div>


<form>
    <div id='signupPanel'> 
        email: <input type ='text' id='email' /><input type='hidden' id='email_test' value='0'/>
            <span id='emailIsValid'></span><br /> 
        Password: <input type ='password' id='password' size='10' />
            <span id='password1Validating'></span>
            <br /> 
        Password again: <input type ='password' id='passwordcheck' size='10' />
            <span id='password2Validating'></span>
            <br /> 
        First Name: <input type ='text' id='firstname' size='10' /><br /> 
        Last Name: <input type ='text' id='lastname'  size='10' /><br />
        Sex: 
        <select id='sex' /> <br /> <!--hey dumbass, this should be drop down-->
            <option value='M'>male</option>
            <option value='F'>female</option>
        </select><br/>
        <div id='signup-errors'></div>
        <span id='submit-signup' onClick='signUpMain()'>
            <a href='#' class='testBlackBtn noclick'>Submit!</a>
            </span> 
        &nbsp;&nbsp;&nbsp;
            <span id='ajaxLoaderSignUp'>
                <img src='images/ajax-loader-transp-arrows.gif' />
            </span>
        <br /> 
        <!--Should prolly restate that you can just connect with facebook here-->
    </div>
</form>




<div id='postEventSuccess'>
    <br />
    Event Posted Successfully!
    <br />
    <br />
        <div onClick='close_event_success_window()' 
            style='width:50%; margin-left:25%;'>
            <a href='#' class='noclick'>Close!</a>
        </div>
</div>
<br />




<div id='singleEventWrapper'>
    <div class='closer'  id='singleEventCloser' onClick='closeSingleEvent()'>
        <!--
        <a href='#'>
            <img src='images/buttons/ps-x-button-50.png' width='30' />
            </a>
            -->
        
        <a href='#' class='testBlackBtn noclick'>
            Close
        </a>
        
    </div>
    
    <div id='singleEventContent'>
        </div>
</div>




<div id='pageviewMapWrapper'>
    <div class='closer'  id='singleEventCloser' onClick='closePageviewMap()'>
        <a href='#' class='testBlackBtn noclick'>
            Close
        </a>
    </div>
    
    <div id='pageviewMap'>
        </div>
</div>


<!--
<div id='rsvpMapWrapper'>
    <div class='closer'  id='singleEventCloser' onClick='closeRSVPMap()'>
        <a href='#' class='testBlackBtn noclick'>
            Close
        </a>
    </div>
    
    <div id='pageviewMap'>
        </div>
</div>
-->


<div id='eventCommentWrapper'>
    <div>
        Comment on this event:
        <form>
            <input id='eventIdMsg' value='0' type='hidden' />
            <textarea id='eventMsgComment' rows="3" cols="35" maxlength="500"></textarea>
                <br />
            <a href='#' class='testBlackBtn noclick' onClick='postEventComment()'>
                post
            </a>
        </form>
            <br />
    </div>
        
    <div id='eventMsgContainer'>
    </div>
</div>
