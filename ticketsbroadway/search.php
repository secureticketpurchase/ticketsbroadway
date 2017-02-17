<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<div class="sidebar d-2of7 t-1of3">
						<div class="shows-listing">
							<h3>Top Shows</h3>
							<?php display_shows( "", 3, true ); ?>
						</div>
						<div class="widget-area">
							<?php if ( dynamic_sidebar( 'cta-sidebar' ) ) : ?>
							<?php endif; ?>
						</div>
					</div>

					<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 t-2of3" <?php post_class('cf'); ?> role="article">
						<h1 class="archive-title"><span><?php _e( 'Search Results for:', 'bonestheme' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h1>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<?php
						// get post-type to display here
						$theType = get_post_type();
						
						?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

								<?php
									echo get_template_part( "search", $theType );
								?>

								<?php //the_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'bonestheme' ) . '</span>' ); ?>

							</article>

						<?php endwhile; ?>

								<?php bones_page_navi(); ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Sorry, No Results.', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Try your search again.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the search.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div>

					</div>

			</div>

<?php get_footer(); ?>
