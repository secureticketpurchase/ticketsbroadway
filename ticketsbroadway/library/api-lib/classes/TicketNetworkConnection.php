<?php

//require_once('tnwsConstants.php');

// Class to handle setting up a SOAP client and making pings to Ticket Network API
class TicketNetworkConnection {

	private $client;
	
	public function __construct() {
		$this->client = new SoapClient( WSDL );
	}

	// call Ticket Network's SearchEvent API call
	// takes a string of search terms
	// return array of Event objects
	public function searchEvents( $toSearch ) {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;
		$params[ 'searchTerms' ] = $toSearch;
		$params[ 'onlyMine' ] = true;

		$result = $this->client->__soapCall( 'SearchEvents', array( 'parameters' => $params ) );

		$eventArray = $result->SearchEventsResult->Event;

		return $eventArray;

	}

	// call Ticket Network's GetEvents API call
	// takes a begin and end date
	// returns an array of events
	public function getEvents( $begin, $end ) {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;
		//$params[ 'whereClause' ] = "CountryID == 217"; // ensure only US events are returned

		if ( isset( $begin ) )
			$params[ 'beginDate' ] = $begin;
		
		if ( isset( $end ) )
			$params[ 'endDate' ] = $end;
		//$params[ 'onlyMine' ] = "true";

		$result = $this->client->__soapCall( 'GetEvents', array( 'parameters' => $params ) );

		$eventArray = $result->GetEventsResult->Event;

		return $eventArray;

	}

	// call Ticket Network's GetEvents API call
	// takes a Performer ID
	// returns an array of events
	public function getEventsByPerformer( $performerID ) {
		echo "getting events<br />";

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;
		//$params[ 'whereClause' ] = "CountryID == 217"; // ensure only US events are returned
		$params[ 'performerID' ] = $performerID;

		$result = $this->client->__soapCall( 'GetEvents', array( 'parameters' => $params ) );

		$eventArray = $result->GetEventsResult->Event;

		return is_array( $eventArray ) ? $eventArray : array( $eventArray );

	}

	// call Ticket Network's GetVenue API call
	// takes a Venue ID
	// returns a Venue array
	public function getVenue( $venueID ) {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;
		$params[ 'venueID' ] = $venueID;

		$result = $this->client->__soapCall( 'GetVenue', array( 'parameters' => $params ) );

		$venueArray = $result->GetVenueResult->Venue;

		return $venueArray;
	}

	// ping Ticket Network's GetEventPerformers API call
	// has no inputs
	// returns an array of performer objects
	// REMEMBER: "performers" in the API roughly correspond to our "shows"
	public function getPerformers() {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;

		$result = $this->client->__soapCall( 'GetEventPerformers', array( 'parameters' => $params ) ) ;

		$performerArray = $result->GetEventPerformersResult->EventPerformer;

		return $performerArray;
	}

	// grab a list of all Performers from Ticket Network API, by pinging GetEventPerformers and stripping out duplicate performer names (and event info)
	// takes no input, returns array of unique performers
	public function getUniquePerformers() {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;
		$params[ 'parentCategoryID' ] = 3;

		$result = $this->client->__soapCall( 'GetPerformerByCategory', array( 'parameters' => $params ) ) ;

		$performerArray = $result->GetPerformerByCategoryResult->Performer;

		$uniquePerformers = array();

		foreach ( $performerArray as $performer ) {
			if(!isset($uniquePerformers[$performer->ID])){
				$uniquePerformers[$performer->ID] = $performer->Description;
			}
		}

		//$uniqueNames = array_unique($performerNames);

		return $uniquePerformers;
	}

	// grab a list of all potential categories from the API
	// takes no input, returns array of categories
	public function getCategories() {

		$params = array();
		$params[ 'websiteConfigID' ] = WEB_CONF_ID;

		$result = $this->client->__soapCall( 'GetCategoriesMasterList', array( 'parameters' => $params ) );

		$cats = $result->GetCategoriesMasterListResult->Category;

		$catArray = array();

		foreach( $cats as $cat ) {
			$catArray[ $cat->ParentCategoryID ] = $cat->ParentCategoryDescription;
			$catArray[ $cat->ChildCategoryID ] = $cat->ChildCategoryDescription;
		}

		return $catArray;
	}
}

?>