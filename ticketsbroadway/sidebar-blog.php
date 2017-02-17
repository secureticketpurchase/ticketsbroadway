<?php
/**
 * The sidebar containing the blog widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 */
?>

	<div class="col-md-2 col-xs-0 blog-sidebar" role="complementary">
		<?php if ( is_single() || is_archive() ) { ?>
		<h3>Latest</h3>
		<ul>
		<?php
			// Build three latest posts block
			$sidebar_query = new WP_Query( array ( 'posts_per_page' => 3 ) );
			if ( $sidebar_query->have_posts() ) : while ( $sidebar_query->have_posts() ) : $sidebar_query->the_post();
		?>
				<li>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php echo get_the_post_thumbnail( get_the_ID(), 'small' ); ?></a>
					<h4><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
				</li>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</ul>
		<?php endif; ?>
		<?php } ?>
		<?php if ( dynamic_sidebar( 'blog-sidebar' ) ) : ?>
	</div><!-- #secondary -->
	<?php endif; ?>