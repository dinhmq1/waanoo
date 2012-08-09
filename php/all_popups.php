<!-- POPUPS FOR SITE -->


<!--
	Fix location
	see location_detection.js
	we are manipulating map called "map_canvas"
-->
<div id='map_wrapper' class='popup'>
    <div id='map_canvas'></div>
    
    <span >
    	<input type="text" id="fixLocationInput" size="50"/>
    	<a href='#' class='testBlackBtn noclick' onClick='fixLocationInput()'>Go To</a>
    </span>
    <span>
    	&nbsp;&nbsp;&nbsp;
    	&nbsp;&nbsp;&nbsp;
    </span>
    <span onClick='close_map_selector()'>
        <a href='#' class='testBlackBtn noclick'>DONE</a>
    </span>
</div>



<!--
	Signup.
	See signup.js
-->
<form>
    <div id='signupPanel' class='popup'> 
        <table class='postTable' align='center' cellpadding='2'>
            <tr align='left'>
                <td>email:</td>
                <td><input class='userTextBox' type ='text' id='email' size='15'/></td>
                <td><input type='hidden' id='email_test' value='0'/>
                    <span id='emailIsValid' class='textError'></span>
                    </td>
            </tr>
            <tr align='left'>
                <td>Password:</td>
                <td><input class='userTextBox' type ='password' id='password' size='15' /></td>
                <td><span id='password1Validating' class='textError'></span>
                    </td>
            </tr>
            <tr align='left'>
                <td>Password again:</td>
                <td><input class='userTextBox' type ='password' id='passwordcheck' size='15' /></td>
                <td><span id='password2Validating' class='textError'></span>
                    </td>
            </tr>
            <tr align='left'>
                <td>First Name:</td>
                <td><input class='userTextBox' type ='text' id='firstname' size='15' /></td>
                <td>&nbsp;
                    </td>
            </tr>
            <tr align='left'>
                <td>Last Name:</td>
                <td><input class='userTextBox' type ='text' id='lastname'  size='15' /></td>
                <td>&nbsp;
                    </td>
            </tr>
            <tr align='left'>
                <td>Sex:</td>
                <td><select id='sex' /> <br />
                    <option value='M'>male</option>
                    <option value='F'>female</option>
                    </select></td>
                <td>&nbsp;
                    </td>
            </tr>
            <tr align='left'>
                <td><span id='ajaxLoaderSignUp'>
                    <img src='images/ajax-loader-transp-arrows.gif' />
                    </span></td>
                <td><span id='submit-signup' onClick='signUpMain()'>
                    <a href='#' class='testBlackBtn noclick'>Submit!</a>
                    </span></td>
                <td><span id='signup-errors' class='textError'></span>
                    </td>
            </tr>
        </table>
    </div>
</form>



<!--
	Post event
	see postevent.js
-->
<div id='postEventForm-wrapper' class='popup'>
    <h3 style='text-align: left; padding-left: 20px;'>Post an Event! </h3>
    <span id='cancelPostEventBtn' onClick='close_post_event()'>
        <a href='#' class='testBlackBtn noclick'>Cancel</a>
    </span>
    <!-- acoridion start -->
    <div class='accordion' id='accordion'>
        <p class='head'><a href="#">Basic Information <small>(required)</small></a></p>
            <div class='accordionChild'>
            <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td>Title:</td>
                    <td><input id='eventName' type='text' size='30' /></td>
                </tr>
                <tr align='left'>
                    <td>Describe your event: </td>
                    <td>
                        <textarea name="event_description" id="eventDescription" rows="4" cols="35" maxlength="500"></textarea>
                    </td>  
                </tr>
                <tr align='left'>
                    <td>&nbsp;</td>
                    <td><span id="descriptionCount">0</span> / 500</td>
                </tr>
            </table>
            </div>
        <p class='head'><a href="#">Date and Time <small>(required)</small></a></p>
            <div class='accordionChild'>
                <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td>When will it be?:</td>
                    <td><input type="text" id="eventDateBegin" name="date" /></td>
                </tr>
                <tr align='left'>
                    <td>When will it end?:</td>
                    <td><input type="text" id="eventDateEnd" name="date_end" /></td>
                </tr>
                <tr align='left'>
                    <td>&nbsp;</td>
                    <td> <span id='lblEventDateErrors'></span></td>
                </tr>
                </table>
           </div>
        <p class='head'><a href='#'>Pick a location <small>(required)</small></a></p>
        <div class='accordionChild'>
            <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td>Where will it be?</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align='left'>
                    <td>
                    	<input type='text' id='eventLocation' size="50"/>
                        
                    </td>
                    <td>
                    	<span id="setLocation" onClick='showMinimapCoords()'>
                        	<a href='#' class='testBlackBtn noclick'>Check on map -></a>
                        </span>
                    </td>
                </tr>
                <tr align='left'>
                    
                    <td><p id='dragNdropMsg'>
                        <small>note: drag and drop enabled on map</small>
                        </p>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                </table>
        </div>
        <p class='head'><a href='#'>Contact Info</a></p>
        <div class='accordionChild'>
            <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td>Event Homepage URL: </td>
                    <td><input id='txtEventHomepageURL' size='30' type='text' />
                    </td>
                </tr>
                <tr align='left'>
                    <td>Allow Users to Contact you?</td>
                    <td><input type="checkbox" id="allowContactEvtent" value="contact" />
                    </td>
                </tr>
                <tr align='left' id='contactingOptions'>
                    <div>
                    <td><select id='eventContactType'>
                        <option value='email'>email</option>
                        <option value='phone'>phone</option>
                        </select></td>
                    <td>Contact Info:
                        <span id='contactInfo'>
                            <input type="text" id="emailContactInfo" />
                        </span> 
                    </td>
                    </div>
                </tr> 
            </table>
        </div>
        <p class='head'><a href='#'>Add a photo</a></p>
        <div class='accordionChild'>
            <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td>Upload Image: </td>
                    <td><a href="#" class='testBlackBtn' id='uploader'>upload!</a> </td>
                    <td><span id='imgUploadedSpot'></span></td>
                </tr>
            </table>
        </div>
        <p class='head'><a href='#'>Add some tags</a></p>
        <div class='accordionChild'>
            <table class='postTable' align='center' cellpadding='10'>
                <tr align='left'>
                    <td><select id='selectTags'>
                            <option>food</option>
                            <option>free food</option>
                            <option>BYOB</option>
                            <option>drinks</option>
                            <option>educational</option>
                            <option>music</option>
                            <option>dancing</option>
                            <option>University of Cincinnati</option>
                            <option>DAAP</option>
                            <option>Arts</option>
                            <option>Theater</option>
                            <option>jobs</option>
                            <option>career fair</option>
                        </select></td>
                    <td><input size='30' type='text' id='txtTagsInpt' /></td>
                    <td><span id='lblTagInputErrors'></td>
                </tr>
                <tr align='left'>
                    <td>Outdoor event:</td>
                    <td><input type='checkbox' value='true' id='chkOutdoorsEvent' /></td>
                </tr>
                <tr align='left'>
                    <td>Event cost:</td>
                    <td id='radioContainer'>
                    free - <input type='radio'  name='group1' value='free' id='freeEvent' /> | 
                    not free - <input type='radio'  name='group1' value='notfree' id='nonFreeEvent' />
                    </td>
                </tr>
                <tr class='eventCostSection' style='display:none;'>
                    <td>Event price:</td>
                    <td><input type='text' id='eventPrice' /></td>
                </tr>
                <tr class='eventCostSection' style='display:none;'>
                    <td>&nbsp;</td>
                    <td><select id='priceCurrency'>
                            <option>$-USA</option>
                            <option>$-Canadian</option>
                            <option>Pounds-British</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- hidden stuff for scripts to work -->
            <input type='hidden' id='imgFileLocation' value='' />
            <input type='hidden' id='isThereImage' value='0' />
            <input type='hidden' id='oldEventID' value="" />
    <hr />
    <span id='eventPostErrors' style='color:red;font-size:70%;'></span><br />
    <span  id='eventFormSubmitBtn' onClick='submitNewEvent()'>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='testBlackBtn noclick'>Submit!</a>
        </span>
        &nbsp;&nbsp;&nbsp;
        <span id="ajaxLoaderPostEvent">
            <img src="images/ajax-loader-transp-arrows.gif" />
        </span>
    <br />
    <br />
    <br />
</div>

<div id='postEventMiniMap' class='popup'>
    <p>Your Event:</p>
    <div id='miniMapCanvas'></div>
    <span id="setLocation" onClick='closePostEventMinimap()'>
        <a href='#' class='testBlackBtn noclick'>Close</a>
    </span><br />
</div>


<div id='EventMapWrapper' class='popup'>
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

<div id='EventDirections' class='popup'>
    <div id='EventDirectionsDisplay'></div>
</div>


<div id="myEventsWrapper" class='popup'>
    <div onClick='closeMyEvents()'>
            <a href='#' class='testBlackBtn noclick'>Close!</a>
        </div>
    <div id="myEventsContents">
    </div>
    <div id='myEventsBlock'>
    </div>
</div>




<div id='postEventSuccess' class='popup'>
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




<div id="singleEventWrapperWrapper" class='popup'>
	
	
	
	
	<!--<span class='closer'  id='singleEventCloser'>            
                <a href='#' class='closeButton noclick' onClick='closeSingleEvent()'>
                    <img src='./images/buttons/ps-x-button-50-inv.png'/>
                </a>
        </span> -->
            
	<div id='singleEventWrapper'>
		
		<a href='#' class='closeButton noclick' onClick='closeSingleEvent()'>
                    <img onmouseover="this.src='./images/buttons/ps-x-button-50.png'" onmouseout="this.src='./images/buttons/ps-x-button-50-inv.png'" src='./images/buttons/ps-x-button-50-inv.png'/>
                </a>
		
	    <div id='singleEventContent'>
	        </div>
	</div>


 <!-- () 
 		<div id='eventCommentWrapper'>
		<br />
	    <div>
	    	<span class="backgroundTextLowOP">
	        Post A Comment!
	        </span>
	        <form class="inline">
	            <input id='eventIdMsg' value='0' type='hidden' />
	            <textarea id='eventMsgComment' rows="3" cols="35" maxlength="500" placeholder="Say Something!"></textarea> 
	            
	            &nbsp;&nbsp;
	            <a href='#' class='testBlackBtn noclick' onClick='postEventComment()'>
	                post
	            </a>
	        </form>
	          
	    </div>
	    
	        <br />
	        
	    <div id='eventMsgContainer'>
	    </div>  
	    <br />
	            <br />
	            <br />
	</div>  -->

</div>

 



<div id='pageviewMapWrapper' class='popup'>
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
