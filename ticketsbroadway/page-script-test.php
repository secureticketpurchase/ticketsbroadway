<?php
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

			<h1>THIS IS A SCRIPT TESTING PAGE!</h1>

			<?php

			$conn = new TicketNetworkConnection();
			global $wpdb;
			$table = $wpdb->prefix . "categories";

			$catArray = $conn->getCategories();
			echo "The size of the array is " . count( $catArray ) . "<br />";

			/*echo "<pre>";
			print_r( $catArray );
			echo "</pre>";*/

			echo "<h1>Lets get the initial table populated</h1>";

			// iterate through category array from API, populate categories table
			foreach( $catArray as $id=>$name ) {
				$data = array (
					'id'	=>	$id,
					'name'	=>	$name
				);
				if ( $term = term_exists( $name, 'genre' ) ) {
					// term already exists, grab its WP ID
					$termID = $term[ 'term_id' ];
					$data[ 'wp_id' ] = $termID;
				} else {
					// term not pulled into WP yet, push in and catch WP ID
					$term = wp_insert_term( $name, 'genre' );
					if ( is_wp_error( $term ) ) {
						echo "error adding term<br />";
					} else {
						$termID = $term['term_id'];
						$data[ 'wp_id' ] = $termID;
					}
				}

				//echo "Term id of $id with name $name has WP ID of $termID<br />";
				$wpdb->insert( $table, $data );
			}

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
