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
	<header class="entry-header article-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>	
	</header>
</div>