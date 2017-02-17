<?php

global $post; ?>

<div class="thumbnail">

<?php if( has_post_thumbnail() ) {
		the_post_thumbnail( 'small' );
	} else {
		echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder'/>";
	} ?>

</div>

<div class="result-body">
	<header class="entry-header article-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>	
	</header>
	<div class="cast-info">
		<?php
		$role = get_post_meta( $post->ID, "cast_or_crew", true );

		if ( $role == "Cast" ) {
			$job = " and has played the role of ";
			$job .= get_post_meta( $post->ID, "character_name", true ) . ".";
		} else {
			$job = " and has served as a ";
			$job .= get_post_meta( $post->ID, "crew_role", true ) . ".";
		}

		?>
		<p>This person is a <?php echo $role; ?> member<?php echo $job; ?></p>

	</div>

	<div class="search-excerpt">
		<?php the_excerpt(); ?>
	</div>

</div>