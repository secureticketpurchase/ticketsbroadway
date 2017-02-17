<?php

// "Show" class (corresponds to Ticket Network's "performer" objects)
// Knows its own events, categories, cities, cast/crew and venues

class Show {

	public $performerID; // ID in Ticket Network
	public $showID; // ID in Wordpress
	public $events = array(); // Array of event IDs for this Show
	public $cats = array(); // category array -> From WP_Post?
	public $venues = array(); // array of venue IDs where this Show's events are happening...purge periodically?
	public $cities = array(); // array of city IDs (objects?) where this Show's Events are happening...possibly purge periodically
	public $name; // name of the show (matches up to Performer name in API)
	public $parentCat = array(); // top-level category name
	public $cat = array(); // category name(s)
	public $grandchildCat = array(); // grandchild category name
	public $wpShow; // Holds the Wordpress Show object

	//global $wpdb; //WP database object...have fun!



	
	// Constructor function, takes either a stdClass (from Ticket Network API) or a WP Object
	public function __construct( $obj ) {

		//determine what type of object we've got, either build out a full Show object, or fetch and build based on the wp_ID in the wp_performers table
		/*if ( $obj instanceof WP_POST ) {
			makeShow( $obj );
		} else {*/
			// We've got an object from the API!
			// Start setting variables
			$this->performerID = $obj->id;
			$this->name = $obj->name;
			$this->check();
			//echo "Show " . $this->name . " with ID " . $this->performerID

	}

	// Confirm whether Performer has been imported previously
	// call save if it does not exist
	// else, call makeShow
	private function check( ) {
		$performerID = $this->performerID;
		//echo "checking for performerID " . $performerID . "<br />";

		global $wpdb;
        
        $showID = null;
        $results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_key = 'performerID' AND meta_value = $performerID", ARRAY_A );
        /*echo "Results are:<br />";
        echo "<pre>";
        print_r( $results );
        echo "</pre>";*/
        
        if($results){
            $showID = $results[0]['post_id'];
            $this->makeShow( $showID );
        }

        if( !$results ) {
        	$this->save();
        }

	}

	// Function to push new Show into DB
	// Eventually, returns Show's Wordpress ID
	public function save() {
		$performerID = $this->performerID;
		$post_title = $this->name;
		$post_author = 1; // hardcoded ID for only existing user in localhost WP install
		$post_type = "show";
		$post_status = "publish";
		$meta_input = array( "performerID" => $performerID );
		$compacted = compact( 'post_title', 'post_author', 'post_type', 'post_status', 'meta_input' );
		$showID = $this->showID = wp_insert_post( $compacted );
		
		$this->makeShow( $showID );

		//echo "Show saved in DB, WP ID = " . $showID . "<br />";
	}

	// grab WP object and initialize all class variables (events array, show categories, venues array, city array, city descriptions, etc)
	// accepts WP ID to kick things off
	private function makeShow( $showID ) {
		
		$this->wpShow = get_post( $showID );
		$this->showID = $showID;
		$this->venues = get_post_meta( $showID, 'venues', true );
		$this->cities = get_post_meta( $showID, 'cities', true );

		if ( !$this->venues )
			$this->venues = array();

		if ( !$this->cities )
			$this->cities = array();

	}

	// takes a venue ID as input, registers with Show if it isn't already
	public function addVenue( $venueID ) {
		$venues = $this->venues;
		if( in_array( $venueID, $venues ) === false ) {
			$venues[] = $venueID;
			update_post_meta( $this->wpShow->ID, 'venues', $venues );
			$this->venues = $venues;
		}
	}

	// takes a city name as input, registers with Show if it isn't already
	public function addCity( $city ) {
		$cities = $this->cities;
		if( in_array( $city, $cities ) === false ) {
			$cities[] = $city;
			update_post_meta( $this->wpShow->ID, 'cities', $cities );
			$this->cities = $cities;
		}
	}

	// return array of all Event objects associated with this Show
	// NOTE: add something like this, but returning certain subsets
	// MOAR NOTE:  Will likely need to spin these Events up in the constructor
	public function getEvents() {
		$eventArr = array();
		foreach( $this->events as $event ) {
			$daEvent = new Event( $event );
			$eventArr[] = $daEvent;
		}
		return $eventArr;
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

	// accepts array of category names
	// iterate through all categories, confirm their existence, then add to the Show (assuming it doesn't already have them)
	public function addCats( $cats ) {
		$wpID = $this->wpShow->ID;

		$showCats = wp_get_object_terms( $wpID, 'genre', array( 'fields' => 'ids' ) );

		foreach ( $cats as $cat ) {
			
			// go through the categories table, grab the WP ID corresponding to each cat ID from feed
			global $wpdb;
			$table = $wpdb->prefix . "categories";
			$results = $wpdb->get_row( "SELECT * FROM " . $table . " WHERE id = " . $cat, ARRAY_A );

			$showCats[] = $results['wp_id'];
		}
		$showCats = array_map( 'intval', $showCats );
		$showCats = array_unique( $showCats );

		$addedCats = wp_set_object_terms( $wpID, $showCats, 'genre', false );

		if ( is_wp_error( $addedCats ) ) {
			echo "error adding cats<br />";
		}

	}

	// pull Venue, category and City out of Event, associate with this Show
	public function addEvent( $obj ) {
		
	}

	// static function to take two Shows (objects or IDs?), add the associations of $orphan to $main, then brutally murder the $orphan
	static function merge( $main, $orphan ) {

	}

	// unwires an Event from this show
	// make static?
	public function killEvent( $eventID ) {

	}

	// unwires a City from this show
	public function killCity( $cityName ) {

	}

	// static function to determine whether show exists in WP...if so, return true
	static function exists( $anID ) {

		global $wpdb;

		$results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_key = 'performerID' AND meta_value = $anID", ARRAY_A );

		if ( $results ) {
			return true;
		} else {
			return false;
		}

	}
}

?>