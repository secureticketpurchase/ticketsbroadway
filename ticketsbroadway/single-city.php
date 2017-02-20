<?php
/*
 * CITY TEMPLATE
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class="sidebar d-2of7 t-1of3">
								<?php
								echo "<div class='sidebar-img'>";
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'medium' );
								} else {
									echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder'/>";
								}
								echo "</div>";

								$sidebar_image_id = get_post_meta( get_the_ID(), 'sidebar_image', true );

								$sidebar_image_url = wp_get_attachment_url( $sidebar_image_id );
								echo $sidebar_image_url;

								if ( $sidebar_image_url ) {
								?>
								<div class="sidebar-img">
									<img src="<?php echo $sidebar_image_url; ?>" />
								</div>

								<?php } ?>

								<div class="shows-listing">
									<h3>Top Shows in <?php echo $post->post_title; ?></h3>
									<?php display_shows( $post->ID, 3, true ); ?>
								</div>
								<div class="widget-area">
									<?php if ( dynamic_sidebar( 'cta-sidebar' ) ) : ?>
									<?php endif; ?>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 t-2of3" <?php post_class('cf'); ?> role="article">

								<!-- section to list out shows happening in this city -->
								<section class="show-listing">
									<h3>Shows Now Playing in <?php echo $post->post_title; ?></h3>
									<div id="shows-listing-container">
										<?php display_shows( $post->ID, 4, false ); ?>
									</div>

								</section>

								<section class="entry-content cf">
									<?php
										the_content();
									?>
								</section> <!-- end article section -->

								<section>
									<h3>Theatres</h3>

									<?php 
									// get a list of theaters hooked up to this post
									$cityName = get_post_meta( $post->ID, "cityName", true );

									$args = array (
											"post_type"			=> "venue",
											'meta_key'			=> 'city',
											'meta_value'		=> $cityName,
											"posts_per_page"	=> -1,
											"order"				=> "ASC",
											"orderby"			=> "title"
										);

									$venues = get_posts( $args );

									/*echo "<pre>";
									print_r( $venues );
									echo "</pre>";*/

									if ( count( $venues ) > 0 ) {
										$cntr = 0;
										while ( $cntr < count( $venues ) ) {
											echo "<div class='theater-display'>";
											echo "<span class='theater-thumb'><a href='" . get_permalink( $venues[$cntr]->ID ) . "' >";
											if ( has_post_thumbnail( $venues[$cntr] ) ) {
												echo get_the_post_thumbnail( $venues[$cntr], 'small' );
											} else {
												echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder'/>";
											}
											echo "</span></a><span class='theater-data'>";
											echo "<h4><a href='" . get_permalink( $venues[$cntr]->ID ) . "'>" . $venues[$cntr]->post_title . "</a></h4>";
											echo "<p>" . $venues[$cntr]->post_excerpt . "</p>";
											echo "</div>";
											$cntr++;
										}
									}
									?>
								</section>

							</div>

							<?php break; ?>

							<?php endwhile; ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						<!--</main>-->

				</div>

			</div>

<?php get_footer(); ?>
