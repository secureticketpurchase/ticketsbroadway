<?php
// knows its date, venue, show/performer, city

class Event {
	public $performerID;
	public $city;
	public $venueID;
	public $ID; //API ID (also index on table)
	public $date;

	// Constructor function, takes either a stdClass (from Ticket Network API) or a WP Object
	public function __construct( $obj ) {


		$this->ID = $obj->ID;
		$this->venueID = $obj->VenueID;
		$this->city = $obj->City;

		//convert API date into Epoch Time, then format for database
		$epochTime = strtotime( $obj->Date );
		$this->date = date( 'Y-m-d H:i:s', $epochTime );
		$this->check();

	}

	// function to set/update the Performer (show) associated with this Event
	public function setPerformerID( $perfID ) {
		global $wpdb;
		$table = $wpdb->prefix . 'events';
		$data = array( 'performer' => $perfID );
		$where = array( 'ID' => $this->ID );
		$format = array( '%d' );
		$result = $wpdb->update( $table, $data, $where, $format );
		$this->performerID = $perfID;
	}

	// save Event into DB
	public function save() {
		global $wpdb;
		$data = array(
				'id'		=> $this->ID,
				'time'		=> $this->date,
				'venue'		=> $this->venueID,
				'city'		=> $this->city
			);
		$table = $wpdb->prefix . 'events';
		$wpdb->insert( $table, $data );
	}

	// function to confirm existence or absence of an event, accepts ID from Ticket Network API
	private function check() {
		global $wpdb;
		$query = "SELECT * FROM " . $wpdb->prefix . "events WHERE ID = " . $this->ID;
		$result = $wpdb->get_row( $query );
		if ( $result ) {
			// event exists in database!  We're done here.
		} else {
			// event doesn't exist!  call save() to add to database
			$this->save();
		}

	}

	// function takes an ID, and deletes the corresponding event
	static function murder( $ID ) {

	}
}

?>