<?php
global $post;
if ( get_the_content( $post->ID ) !== '' ) {
	the_content();	
} else {
	echo "<p>No content found for this page.</p>";
}

?>