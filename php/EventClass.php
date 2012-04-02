<?php
session_start();
require("cxn.php");

class EventView {
	private $title_class;
	private $description_class;
	private $date_begin_class;
	private $date_end_class;
	private $event_id_class;
	private $user_id_class;
	private $distance_class;
	private $address_class;
	private $lat_class;
	private $lng_class;
	private $is_visible_class;
	private $is_public_class;
	private $del_btn_class;
	// the HTML:
	private $output;
	
	// constructor, pulls array and type of event
	public function __construct($field_array) {
		extract($field_array);
		// only pull:
			//title
			//description
			//date begin
			//date end
		$this->title_class = $event_title;
		$this->description_class = $event_description;
		$this->date_begin_class = $start_date;
		$this->date_end_class = $end_date;
		$this->venue_address = $address_class;
		}
	
	// delete button
	function deleteBtn($user_id, $event_id) {
		if($_SESSION['signed_in'] == true) { 
			$uid_session = $_SESSION['user_id'];
			if($user_id == $uid_session) {
				$this->del_btn_class = 
					"<div class='deleteBtn' id='del_$event_id' onClick='delEvent($event_id)'>
					Delete!
					</div>";
				}
			else 
				$this->del_btn_class = "";
			}
		else
			$this->del_btn_class = "";
		}
	}
