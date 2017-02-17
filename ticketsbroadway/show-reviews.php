<?php

global $post;

// create a new query to snag a collection of reviews attached to this show
// Grab the post IDs of all post meta fields named "review_show_title" that have a value matching this Show's title
$args = array (
	'post_type'		=> 'review',
	'meta_key'		=> 'review_show_title',
	'meta_value'	=> $post->post_title,
	'posts_per_page'=> '12'
);

$reviews_query = new WP_Query( $args );

/*echo "<pre>";
print_r( $seller_query );
echo "</pre>";*/

echo "<div class='reviews-results-list'>";

// Commence the Loop!
if( $reviews_query->have_posts() ) : while( $reviews_query->have_posts() ) : $reviews_query->the_post();

	echo "<div class='review'>";
	echo the_content();
	echo get_post_meta( $post->ID, 'byline', true );
	echo "</div>";

endwhile; // end of the loop

wp_reset_postdata(); // restore main Post object

endif;

echo "</div>";

?>