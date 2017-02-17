<?php
// venue class

class Venue {
	public $venueID; //ID in Ticket Network API
	public $wpVenueID; // venue ID in Wordpress
	public $city;
	public $street1;
	public $street2;
	public $zip;
	public $state;
	public $name;
	public $wpVenue; // WP Object representing Venue
	public $shows = array(); //array of show IDs with Events in this venue

	// Constructor function, takes either a stdClass (from Ticket Network API) or a WP Object
	public function __construct( $obj ) {

		// set class variables
		$this->venueID = $obj->ID;
		$this->name = $obj->Name;
		$this->city = $obj->City;
		$this->zip = $obj->ZipCode;
		$this->street1 = $obj->Street1;
		$this->street2 = $obj->Street2;
		$this->state = $obj->StateProvince;

		$this->check();

		//determine what type of object we've got, either build out a full Show object, or fetch and build based on the wp_ID in the wp_performers table
		/*if ( $obj instanceof WP_POST ) {
			makeVenue( $obj );
		} else {
			// We've got an object from the API!
			// Start setting variables
			$this->feedID = $obj->ID;
			$this->name = $obj->Name;
			$this->city = $obj->City;
			$this->zip = $obj->ZipCode;
		}*/

	}

	// confirm whether Venue exists
	// call save if not
	// else, call makeVenue
	private function check( ) {
		$venueID = $this->venueID;
		//echo "checking for venueID " . $venueID . "<br />";

		global $wpdb;
        
        $wpVenueID = null;
        $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_key = 'venueID' AND meta_value = $venueID", ARRAY_A );
        
        if( $results ){
            $wpVenueID = $results[0]['post_id'];
            $this->makeVenue( $wpVenueID );
        }

        if( !$results ) {
        	$this->save();
        }
	}

	public function save() {
		$venueID = $this->venueID;
		$post_title = $this->name;
		$post_author = 1; // hardcoded ID for only existing user in localhost WP install
		$post_type = "venue";
		$post_status = "publish";
		$meta_input = array( "venueID" => $venueID, "city" => $this->city, "Street 1" => $this->street1, "Street 2" => $this->street2, "state" => $this->state, "zip" => $this->zip );
		$compacted = compact( 'post_title', 'post_author', 'post_type', 'post_status', 'meta_input' );
		$wpVenueID = wp_insert_post( $compacted );
		
		$this->makeVenue( $wpVenueID );

	}

	public function makeVenue( $wpVenueID ) {
		$this->wpVenue = get_post( $wpVenueID );
		$this->wpVenueID = $wpVenueID;
		$this->shows = get_post_meta( $wpVenueID, 'shows', true );

		if ( !$this->shows )
			$this->shows = array();
	}

	// takes a show ID as input, registers with Venue if it isn't already
	public function addShow( $showID ) {
		$shows = $this->shows;
		if( in_array( $showID, $shows ) === false ) {
			//push ID into Shows array, update post meta
			$shows[] = $showID;
			$this->shows = $shows;
			update_post_meta( $this->wpVenue->ID, 'shows', $shows );
		}
		
	}

	// returns array of shows happening at this venue
	public function getShows() {
		$showArr = array();
		foreach( $this->shows as $show ) {
			$daShow = new Show( $show );
			$showArr[] = $daShow;
		}
		return $showArr;
	}

	// takes a Show ID and unwires it from this Venue
	public function killShow( $showID ) {
		
	}

}

?>