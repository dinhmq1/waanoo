<?php
require("cxn.php");

// send back a JSON array of possible matches. Must be short.
// should test this out first with a fake list
/*
 * example return value:
 * [ 
 * { "id": "Turdus merula", "label": "Common Blackbird", "value": "Common Blackbird" }, 
 * { "id": "Mimus polyglottos", "label": "Northern Mockingbird", "value": "Northern Mockingbird" }, 
 * { "id": "Fregata magnificens", "label": "Magnificent Frigatebird", "value": "Magnificent Frigatebird" } 
 * ]
 */


function shortener($longname) {
    // length of autocomplete result is $MAXLEN
    $MAXLEN = 25;
    if(strlen($longname) >= $MAXLEN) {
            return substr($longname, 0, $MAXLEN)."...";
        }
    else {
        return $longname;
        }
    }
    

$term = $_GET["term"];
$term = "%$term%";//echo $term;

// using Mysqli prepared statements
$sql = "SELECT event_title, event_description, event_id FROM user_events
        WHERE 
        	event_title LIKE ? OR 
        	event_description LIKE ? OR 
        	tags_list LIKE ?";
        
$stm = $cxn->prepare($sql);
$stm->bind_param('sss',$term, $term, $term);
$stm->execute();
$stm->bind_result($title, $descrip, $id);

$resArr = array();
$i = 0;

while($stm->fetch())  {
    $shortName = shortener($title);
    array_push($resArr, array("id"=>$i, "label"=>$shortName, "value"=>$title));
    $i++;
    //echo print_r(array("id"=>$i, "label"=>$shortName, "value"=>$title));
    }

$stm->close();
$cxn->close();

echo json_encode($resArr);
?>
