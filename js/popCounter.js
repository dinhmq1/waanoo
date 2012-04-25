/*** POPUP DIMMER DETECTION ***/

//GLOBAL FOR POPUPS
isPopup = 0;


/* abstract: this function controls the dimmer with a counter.
 * if zero- no popups are open, and there is no dimmer.
 * 
 * If greater than zero 
 * 
 * passed: toChange-> should = 1, or -1.
 * 
 * when opening a window call:
 *      controlDimmer(1);
 * 
 * when closing a window call:
 *      controlDimmer(-1);
 * 
 */

function controlDimmer(toChange) {
    isPopup += toChange;
    
    if(isPopup <= 0)
        $('#dimmer').hide();
    else
        $('#dimmer').show();
    }
