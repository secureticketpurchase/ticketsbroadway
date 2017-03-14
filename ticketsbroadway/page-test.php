<?php
set_time_limit(0);
$timeBegun = new DateTime();
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ticketsbroadway
 */

//get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<h1>THIS IS A TEST PAGE!</h1>

			<?php

			$currTime;

			$conn = new TicketNetworkConnection();

			$newShows = array();

			$cntr = 0;

			// grab collection of performers from performers table
			global $wpdb;
			$query = "SELECT * FROM " . $wpdb->prefix . "performers";
			$perfArray = $wpdb->get_results( $query, ARRAY_A );

			//print_r( $perfArray );
			// we've got an array of unique Shows, cycle through and commence import
			foreach( $perfArray as $performer ) {

				$id = $performer['id'];
				$name = $performer['name'];

				$exists = Show::exists( $id );

				$newShows[] = $name;

				/*if ( $exists ) {
					echo "show $name exists, skipping this one";
					continue;
				} else {
					echo "show $name does not exist.  continuing with import.";
				}*/

				//echo "<br />Commencing import<br />";
					

				$show = new Show( (object) [
						'id' => $id,
						'name' => $name
						] );

				$venueIDs = array(); // Array to hold IDs for any Venues that appear while importing these events, for later processing
				$cities = array(); // Array to hold the cities associated with Venues, in case we need it to add them to Shows
				$cats = array(); // Array to hold categories to be added to show

				// Build a list of new events by comparing API and Show events
				$APIEvents = $conn->GetEventsByPerformer( $show->performerID );

				echo "<pre>";
				print_r( $APIEvents );
				echo "</pre>";
				
				// cycle through the new Events
				foreach( $APIEvents as $obj ) {

					/*if ( $exists )
						continue;*/
					echo "Event id is " . $obj->ID . "<br />";

					$event = new Event( $obj );
					$event->setPerformerID( $show->performerID );

					if( in_array( $event->venueID, $venueIDs ) === false )
						$venueIDs[] = $event->venueID;

					// NOTE: add in a check to confirm whether the fetched term exists in constants array...if not, fetch from API

					// grab categories from event, push into array for adding to show after loop
					$cats[] = $obj->ParentCategoryID;
					$cats[] = $obj->ChildCategoryID;
				}

				// cycle through array of venue IDs, either grabbing existing or creating/saving new ones, then register relationship between show and venue
				foreach( $venueIDs as $vID ) {

					/*if ( $exists )
						continue;*/

					// fetch venue from API, use to instantiate a new Venue object
					$vAPI = $conn->getVenue( $vID );
					$venue = new Venue( $vAPI );

					$city = new City( $vAPI->City, $vAPI->StateProvince );
					$city->addShow( $show->showID );
					$show->addCity( $vAPI->City  );

					$show->addVenue( $venue->wpVenueID );

					$venue->addShow( $show->showID );
				}

				// lastly, add categories to the show
				$show->addCats( $cats );

				$result = $wpdb->delete( $wpdb->prefix . 'performers', array( "id" => $id ) );

				//$cntr++;

				/*if ( $cntr > 24 ) {
					break;
				}*/
			}

			if ( !empty($newShows) ) {
				// Commence logic to ship out a notification email to Howie containing a list of all newly added shows
				$to = "john.parks@brafton.com";
				$subject = "New shows added to ticketsbroadway.com";

				$message = "<h1>Here's a list of shows recently added to ticketsbroadway.com</h1>";
				$message .= "<ul>";

				foreach( $newShows as $newShow ) {
					$message .= "<li>" . $newShow . "</li>";
				}

				$message .= "</ul>";

				$header = "From:john.parks@brafton.com \r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html\r\n";

				$mailResult = mail( $to, $subject, $message, $header );

				if ( $mailResult == true ) {
					echo "Message sent successfully";
				} else {
					echo "Message failed to send";
				}
			}

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
