<?php
/*
 * CUSTOM POST TYPE TEMPLATE
 *
 * This is the custom post type post template. If you edit the post type name, you've got
 * to change the name of this template to reflect that name change.
 *
 * For Example, if your custom post type is "register_post_type( 'bookmarks')",
 * then your single template should be single-bookmarks.php
 *
 * Be aware that you should rename 'custom_cat' and 'custom_tag' to the appropiate custom
 * category and taxonomy slugs, or this template will not finish to load properly.
 *
 * For more info: http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class="sidebar d-2of7 t-1of3">
								<?php
								if ( has_post_thumbnail() ) {
									echo "<div class='sidebar-img'>";
									the_post_thumbnail( "medium" );
									echo "</div>";
								}
								?>

								<div class="address">
									<?php
									$address1 = get_post_meta( get_the_ID(), 'Street 1', true );
									$address2 = get_post_meta( get_the_ID(), 'Street 2', true );
									$city = get_post_meta( get_the_ID(), 'city', true );
									$state = get_post_meta( get_the_ID(), 'state', true );
									$zip = get_post_meta( get_the_ID(), 'zip', true );

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
									?>
								</div>

								<div class="widget-area">
									<?php if ( dynamic_sidebar( 'cta-sidebar' ) ) : ?>
									<?php endif; ?>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 t-2of3" <?php post_class('cf'); ?> role="article">

								<header class="article-header">

									<h1 class="single-title custom-post-type-title"><?php the_title(); ?></h1>

								</header>

								<section class="entry-content cf">
									<div class="venue-map-holder">
										<?php getVenueMap(); ?>
									</div>
									<?php
									if( $post->post_content != '' )
										the_content();
									else
										echo "<p>There is no content for this page as of yet.</p>"
									?>
								</section> <!-- end article section -->

								<section class="venue-notes">
									<h3>Venue Notes</h3>

									<?php
									$notes = get_post_meta( get_the_ID(), 'venue_notes', true );

									$notesLength = count( $notes );

									$cntr = 1;
									echo "<ul>";
									if ( $notes !== '' ) {
										foreach( $notes as $note ) {
											echo "<li><h4>$note<h4></li>";
											if( $cntr++ > $notesLength/2 )
												echo "</ul><ul>";
										}
									}
									echo "</ul>";
									?>
								</section>

								<section class="venue-show-list">
									<h2>Shows in This City</h2>
									<div id="shows-listing-container">

										<?php
										// pull out city name, snag city object, then use that object ID's to create show list
										$cityName = get_post_meta( get_the_ID(), 'city', true );
										$cityObj = get_page_by_title( $cityName, 'OBJECT', 'city' );
										?>

										<?php
										display_shows( $cityObj->ID, 4);
										?>
									</div>
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

						</main>
				</div>

			</div>

<?php get_footer(); ?>
