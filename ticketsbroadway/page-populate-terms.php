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

			<h1>This page populates terms for predictive search</h1>

			<?php

			global $wpdb;
			$table = $wpdb->prefix . "predictive_terms";

			$client = new SoapClient( WSDL );

			$params = array();
			$params[ 'websiteConfigID' ] = WEB_CONF_ID;

			// first, clear out all terms
			$delete_predictive_query = "TRUNCATE table $table";
			$numKilled = $wpdb->query( $delete_predictive_query );

			// next, ping API for all performers with a parent category of 3 (theater)
			$params[ 'parentCategoryID' ] = 3;

			$result = $client->__soapCall( 'GetPerformerByCategory', array( 'parameters' => $params ) );

			$performers = $result->GetPerformerByCategoryResult->Performer;

			//printDat( $performers );
			
			// commence foreach loop, saving each performer into the predictive_terms table
			foreach( $performers as $performer ){

				// first, establish if term is in DB yet
				$perfID = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE term = "' . $performer->Description . '"');
				$perfName = $performer->Description;

				if ( $perfID )
				{
					echo "<p>" . $perfName . " found!</p>";
				} else {
					echo "<p>" . $perfName . " not found.</p>";

					echo "<p>Inserting " . $perfName . "</p>";
					$insertArgs = array(
							"term" => $perfName
						);

					$inserted = $wpdb->insert($table, $insertArgs);

					if ( $inserted != false ) {
						echo "<p>" . $perfName . " inserted with id of " . $wpdb->insert_id . "</p>";
					} else {
						echo "<p>Error inserting " . $perfName . "</p>";
					}
				}
			}

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
