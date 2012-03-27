<?php
require("cxn.php");


// GET DIFFS FOR LAT LONG
function distance($lat1, $lon1, $lat2, $lon2, $unit) { 

  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}
// about 0.5 * 0.5 units is ~ 50 miles
// 0.1*0.1 units = 8.7 miles
//739.50012118743 miles = distance(42.35, -71.123, 39.147, -84.623, "m") . " miles<br>";
echo distance(42.35, -71.123, 39.147, -84.623, "m") . " miles<br>";


$url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20upcoming.events%20where%20latitude%20%3E%2035%20AND%20latitude%20%3C%2047%20AND%20longitude%20%3C%20-70%20AND%20longitude%20%3E%20-100&format=json";

$response = file_get_contents($url);
$decoded_json = json_decode($response, true);

echo "<pre>";
print_r($decoded_json);
echo "</pre>";

$count = $decoded_json['query']['count'];
if($count >= 1) {

	for($i = 0; $i < $count; $i++) {
	
		$lat = $decoded_json['query']['results']['event'][$i]['latitude'];
		$lon = $decoded_json['query']['results']['event'][$i]['longitude'];
		
		$descrip = $decoded_json['query']['results']['event'][$i]['description'];
		$name = $decoded_json['query']['results']['event'][$i]['name'];
		
		$venue_name = $decoded_json['query']['results']['event'][$i]['venue_name'];
		$venue_address = $decoded_json['query']['results']['event'][$i]['venue_address'];
		$country = $decoded_json['query']['results']['event'][$i]['venue_country_name'];
		
		$date_posted = $decoded_json['query']['results']['event'][$i]['date_posted'];
		$utc_start = $decoded_json['query']['results']['event'][$i]['utc_start'];
		$utc_end = $decoded_json['query']['results']['event'][$i]['utc_end'];
		
		$ticket_url = $decoded_json['query']['results']['event'][$i]['ticket_url'];
		$yql_id = $decoded_json['query']['results']['event'][$i]['id'];
		
		$state = $decoded_json['query']['results']['event'][$i]['venue_state_name'];
		$zip = $decoded_json['query']['results']['event'][$i]['venue_zip'];
		
		// dates for MySQL DATETIME format
		$start_date_time = date("Y-m-d H:m:s", strtotime($utc_start));
		$end_date_time = date("Y-m-d H:m:s", strtotime($utc_end));
		$posted_date_time = date("Y-m-d H:m:s", strtotime($date_posted));
		
		echo "Date Posted: ".$posted_date_time;
		echo " start datetime: ".$start_date_time;
		echo " end datetime: ".$end_date_time;
		echo " Lat: ".$lat." Long: ".$lon;
		echo "<br>";
		
		// check if duplicate
		$qry = "SELECT * FROM YQL_events WHERE yql_id = '$yql_id'";
		$res = mysqli_query($cxn, $qry)
				or die ("query failed could not find yql_id ".mysqli_error($cxn));
		$num_test = mysqli_num_rows($res);
		
		
		$descrip = mysqli_real_escape_string($cxn, $descrip);
		$name = mysqli_real_escape_string($cxn, $name);
		$venue_name = mysqli_real_escape_string($cxn, $venue_name);
		
		if($num_test == 0){
			$public = 1;
			$qry =
				"INSERT INTO YQL_events
				(yql_id, event_title, event_description, end_date, start_date,
				date_created, public, country_name, venue_name, url_link, 
				venue_state, venue_zip)
				VALUES (
				'$yql_id', '$name', '$descrip', '$end_date_time', 
				'$start_date_time', '$posted_date_time', '$public', 
				'$country', '$venue_name', '$ticket_url', '$state', '$zip')
				";
			
			$res = mysqli_query($cxn, $qry)
				or die ("query failed to enter into DB ".mysqli_error($cxn));
			
			// update location table also if successful.
			$qry = "SELECT * FROM YQL_events WHERE yql_id = '$yql_id'";
			$res = mysqli_query($cxn, $qry)
				or die ("query failed, could no select ID ".mysqli_error($cxn));
			
			$id = mysqli_fetch_assoc($res);
			$new_id = $id['event_id'];
			
			echo " New id added: ".$new_id;
			
			$qry = "INSERT INTO YQL_event_address
					(event_id, address_text, x_coord, y_coord)
					VALUES (
					'$new_id', '$venue_address', '$lat', '$lon')
					";
					
			$res = mysqli_query($cxn, $qry)
				or die ("query failed could not add event address ".mysqli_error($cxn));
			
			}// end if not duplicate
		}// end loop
	}// end if

?>
