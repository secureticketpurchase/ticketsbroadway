<?php
set_time_limit(0);
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

			<h1>This page clears out past events</h1>

			<?php

			global $wpdb;
			$table = $wpdb->prefix . "events";
			$date = date( 'Y-m-d H:i:s' ); //current time

			$query = "DELETE FROM $table WHERE time < '" . $date . "'";

			$result = $wpdb->query( $query, ARRAY_A );

			echo "<pre>";
			print_r( $result );
			echo "</pre>";

			//echo $date;

			// the following block implements and manages the cache
			// first clear log of today's searched terms, unless they were searched more than $max times
			$max = 50;
			$searched_table = $wpdb->prefix . "searched_terms";
			$delete_searched_query = "DELETE FROM $searched_table WHERE num_searched < $max";
			$searched_result = $wpdb->query( $delete_searched_query, ARRAY_A );

			printDat($searched_result);

			// now, taking remaining entries, foreach them to populate the caching table
			$toCacheQuery = "SELECT term FROM $searched_table";
			$toCache = $wpdb->get_col($toCacheQuery);


			$client = new SoapClient( WSDL );

			$params = array();
			$params[ 'websiteConfigID' ] = WEB_CONF_ID;

			$cache_table = $wpdb->prefix . 'cached_results';
			$delete_cached_query = "TRUNCATE table $cache_table";
			$numKilled = $wpdb->query( $delete_cached_query );
			
			// commence foreach loop, grabbing result sets for each term, and pushing them into the cache table
			foreach( $toCache as $term ){

				$params[ 'searchTerms' ] = $term;
				$params[ 'whereClause' ] = 'ParentCategoryID == 3';

				$result = $client->__soapCall( 'SearchEvents', array( 'parameters' => $params ) );

				if (is_soap_fault($result))
				{
					echo '<h2>Fault</h2><pre>';
					print_r($result);
					echo '</pre>';
				} else {
					echo "<h1>$term</h1>";
					printDat($result->SearchEventsResult->Event);
				}

				$toInsert = array_slice( $result->SearchEventsResult->Event, 0, 25 );
				$encodedRes = json_encode($toInsert);
				echo "<h1>toInsert</h1>";
				printDat($encodedRes);
				// build query to push result set into DB
				$insertArgs = array(
						"term"	=> $term,
						"result"=> $encodedRes
					);
				$wpdb->insert( $cache_table, $insertArgs );

				$insertedID = $wpdb->insert_id;
			}

			$autoCache[] = "chicago";
			$autoCache[] = "new york";
			$autoCache[] = "hamilton";

			foreach( $autoCache as $term ){

				$params[ 'searchTerms' ] = $term;
				$params[ 'whereClause' ] = 'ParentCategoryID == 3';

				$result = $client->__soapCall( 'SearchEvents', array( 'parameters' => $params ) );

				if (is_soap_fault($result))
				{
					echo '<h2>Fault</h2><pre>';
					print_r($result);
					echo '</pre>';
				} else {
					echo "<h1>$term</h1>";
					printDat($result->SearchEventsResult->Event);
				}

				$toInsert = array_slice( $result->SearchEventsResult->Event, 0, 25 );
				$encodedRes = json_encode($toInsert);
				echo "<h1>toInsert</h1>";
				printDat($encodedRes);
				// build query to push result set into DB
				$insertArgs = array(
						"term"	=> $term,
						"result"=> $encodedRes
					);
				$wpdb->insert( $cache_table, $insertArgs );

				$insertedID = $wpdb->insert_id;
			}

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
