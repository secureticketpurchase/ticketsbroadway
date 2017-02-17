<?php

global $post;

// get a list of theaters hooked up to this post
$venueIDs = get_post_meta( $post->ID, "venues", true );

$args = array (
		"include"			=> $venueIDs,
		"post_type"			=> "venue",
		"posts_per_page"	=> 2,
		'no_found_rows'		=> true
	);

$venues = get_posts( $args );

/*echo "<pre>";
print_r( $venues );
echo "</pre>";*/

if ( count( $venues ) > 0 ) {
	$cntr = 0;
	echo "<h3>Theaters</h3>";
	while ( $cntr < count( $venues ) ) {
		echo "<div class='theater-display'>";
		echo "<span class='theater-thumb'>";
		if ( has_post_thumbnail ( $venues[$cntr] ) ) {
			echo get_the_post_thumbnail( $venues[$cntr], 'medium' );
		} else {
			echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder-horizontal.jpg' class='placeholder' />";
		}
		echo "</span><span class='theater-data'>";
		echo "<h4><a href='" . get_permalink( $venues[$cntr]->ID ) . "'>" . $venues[$cntr]->post_title . "</a></h4>";
		echo "<p>" . $venues[$cntr]->post_excerpt . "</p>";
		echo "</div>";
		$cntr++;
	}
}

?>