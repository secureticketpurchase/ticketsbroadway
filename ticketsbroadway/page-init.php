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

			<h1>THIS IS AN INIT PAGE!</h1>

			<?php

			$conn = new TicketNetworkConnection();

			$perfArray = $conn->GetUniquePerformers();

			print_r( $perfArray );
			
			// we've got an array of unique Shows, cycle through and commence import
			foreach( $perfArray as $id=>$name ) {
					

				global $wpdb;

				$data = array (
					"id"	=> $id,
					"name"	=> $name
				);
				$table = $wpdb->prefix . 'performers';
        
		        
		        $wpdb->insert( $table, $data );
		        		
		    }

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
