<?php

global $post; ?>

<div class="thumbnail dropshadow">

<?php if( has_post_thumbnail() ) {
		the_post_thumbnail( 'small' );
	} else {
		echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder-horizontal.jpg' class='placeholder dropshadow'/>";
	} ?>

</div>

<div class="result-body">
	<header class="entry-header article-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>	
	</header>

	<div class="address-block">
		<h4>Venue Address:</h4>
		<?php
		$address1 = get_post_meta( get_the_ID(), 'Street 1', true );
		$address2 = get_post_meta( get_the_ID(), 'Street 2', true );
		$city = get_post_meta( get_the_ID(), 'city', true );
		$state = get_post_meta( get_the_ID(), 'state', true );
		$zip = get_post_meta( get_the_ID(), 'zip', true );

		echo "<div class='entry-meta'>";
		if( $address1 )
			echo $address1 . "<br />";
		if( $address2 )
			echo $address2 . "<br />";
		if( $city )
			echo $city;
		if ( $state )
			echo ", " . $state;
		if ( $zip )
			echo " " . $zip;
		echo "</div>";
		?>
	</div>

	<div class="search-excerpt">
		<?php the_excerpt(); ?>
	</div>
</div>

<?php
?>