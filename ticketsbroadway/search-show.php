<?php

global $post; ?>

<div class="thumbnail dropshadow">

<?php if( has_post_thumbnail() ) {
		the_post_thumbnail( 'small' );
	} else {
		echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder'/>";
	} ?>

</div>

<div class="result-body">
	<header class="entry-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<a href="<?php the_permalink(); ?>" class="buy-tickets"><div>Buy Tickets</div></a>
	</header>

	<div class="show-info">
		<ul>
		<?php
		$venueIDs = get_post_meta( $post->ID, "venues", true );

		$args = array (
				"include"			=> $venueIDs,
				"post_type"			=> "venue",
				"posts_per_page"	=> 6
			);

		$venues = get_posts( $args );
		$numVenues = count( $venues );
		$cntr = 0;

		if ( $numVenues > 0 ) {
			echo "<h4>This show playing at:</h4>";
			foreach( $venues as $venue ) {
				$cntr++; ?>
				<li><a href="<?php echo get_permalink( $venue->ID ); ?>"><?php echo $venue->post_title; ?></a></li><?php if( $cntr < $numVenues && $cntr < 6) { echo "<span class='pipe'> | </span>"; } ?>
				<?php if ( $cntr == 6 ) {
					if ( $numVenues > 6 ) {
						echo " | <li><a href='the_permalink()' class='more-venues'>And More...</a></li>";
					}
					break;
				} ?>

				<?php
			}
		} else { ?>
			<span class="read-more"><a href="<?php the_permalink(); ?>" >Read More</a></span>
		<?php } ?>
		</ul>
	</div>
</div>