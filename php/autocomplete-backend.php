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
        WHERE event_title LIKE ?";
$stm = $cxn->prepare($sql);
$stm->bind_param('s',$term);
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
//echo '[ { "id": "Botaurus stellaris", "label": "Great Bittern", "value": "Great Bittern" }, { "id": "Ixobrychus minutus", "label": "Little Bittern", "value": "Little Bittern" }, { "id": "Platalea leucorodia", "label": "Spoonbill", "value": "Spoonbill" }, { "id": "Turdus merula", "label": "Common Blackbird", "value": "Common Blackbird" }, { "id": "Erithacus rubecula", "label": "European Robin", "value": "European Robin" }, { "id": "Alca torda", "label": "Razorbill", "value": "Razorbill" }, { "id": "Loxia curvirostra", "label": "Common Crossbill", "value": "Common Crossbill" }, { "id": "Loxia leucoptera", "label": "Two-barred Crossbill", "value": "Two-barred Crossbill" }, { "id": "Gelochelidon nilotica", "label": "Gull-billed Tern", "value": "Gull-billed Tern" }, { "id": "Gavia adamsii", "label": "Yellow-billed Loon", "value": "Yellow-billed Loon" }, { "id": "Botaurus lentiginosus", "label": "American Bittern", "value": "American Bittern" }, { "id": "Plegadis falcinellus", "label": "Glossy Ibis", "value": "Glossy Ibis" } ]';
?>
