<?php
// City Class (has no corresponding object in API)
// Has an array of venues, cast, shows, zip codes(?)

class City {
	public $cityName; //name of city in API
	public $state;
	public $wpCity; //city Wordpress object
	public $venues = array(); //array of venue IDs
	public $shows = array(); // array of show IDs


	public function __construct( $name, $state ) {
		$this->cityName = $name;
		$this->state = $state;

		$this->check();
	}

	// confirm whether City exists
	// call save if not
	// else, call makeCity
	private function check( ) {
		$cityName = $this->cityName;

		global $wpdb;
        
        $wpCityID = null;
        $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_key = 'cityName' AND meta_value = '$cityName'", ARRAY_A );
        
        if( $results ){
            $wpCityID = $results[0]['post_id'];
            $this->makeCity( $wpCityID );
        }

        if( !$results ) {
        	$this->save();
        }
	}

	public function save() {
		$cityName = $this->cityName;
		$state = $this->state;
		$post_title = $cityName;
		$post_author = 1; // hardcoded ID for only existing user in localhost WP install
		$post_type = "city";
		$post_status = "publish";
		$meta_input  = array( "cityName" => $cityName, "state" => $state );
		$compacted = compact( 'post_title', 'post_author', 'post_type', 'post_status', 'meta_input' );
		$wpCityID = wp_insert_post( $compacted );
		
		$this->makeCity( $wpCityID );
	}

	public function makeCity( $wpCityID ) {
		$this->wpCity = get_post( $wpCityID );
		$this->shows = get_post_meta( $wpCityID, 'shows', true );

		if ( !$this->shows )
			$this->shows = array();
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

	// spin up array of Venue objects associated with this show, and return that array
	public function getVenues() {
		$venueArr = array();
		foreach( $this->venues as $venue ) {
			$daVenue = new Venue( $venue );
			$venueArr[] = $daVenue;
		}
		return $venueArr;
	}

	// takes a show ID as input, registers with City if it isn't already
	public function addShow( $showID ) {
		$shows = $this->shows;
		if( in_array( $showID, $shows ) === false ) {
			//push ID into Shows array, update post meta
			$shows[] = $showID;
			$this->shows = $shows;
			update_post_meta( $this->wpCity->ID, 'shows', $shows );
		}
		
	}

}

?>