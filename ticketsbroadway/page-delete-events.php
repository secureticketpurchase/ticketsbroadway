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

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
