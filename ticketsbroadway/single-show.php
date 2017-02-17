<?php
/*
 * CITY TEMPLATE
*/
?>

<?php get_header(); ?>

			<div id="content">

				<?php
				// variable to hold current tab...pull from URL, or default to "show"
				$tab = get_query_var( "tab", "show" );

				?>

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php
							// URL that will have appropriate tab parameter appended to it
							$daURL = get_permalink($post->ID) . "?tab=";
							?>

							<div class="sidebar d-2of7 t-1of3">
								<?php
								echo "<div class='sidebar-img'>";
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'medium' );
								} else {
									echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
								}
								echo "</div>";

								?>

								<?php
								$duration = get_post_meta( $post->ID, "duration", true );
								$intermissionNum = get_post_meta( $post->ID, "intermissions", true );

								switch ( $intermissionNum ) {
									case "":
										$intermissions = "Unknown";
										break;
									case 0:
										$intermissions = "No Intermissions";
										break;
									case 1:
										$intermissions = "1 Intermission";
									default:
										$intermissions = $intermissionNum . " Intermissions";
										break;
								}

								if ( $duration != "" ) { ?>

								<div class="duration-block">
									<h4>Duration</h4>
									<p><?php echo $duration; ?></p>
									<p><?php echo $intermissions; ?></p>
								</div>

								<?php } ?>

								<div class="widget-area">
									<?php if ( dynamic_sidebar( 'cta-sidebar' ) ) : ?>
									<?php endif; ?>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 t-2of3 show-content" <?php post_class('cf'); ?> role="article">

								<h2><?php echo $post->post_title; ?></h2>

								<!-- section to the four tabs for this show -->
								<ul class="show-nav">
									<li><a href='<?php echo $daURL . "show"; ?>' <?php if( $tab == "show" ) echo "class='active'"; ?> >The Show</a></li>
									<li><a href='<?php echo $daURL . "cast"; ?>' <?php if( $tab == "cast" ) echo "class='active'"; ?> >Cast & Creative</a></li>
									<li><a href='<?php echo $daURL . "venues"; ?>' <?php if( $tab == "venues" ) echo "class='active'"; ?>>Venues</a></li>
									<li><a href='<?php echo $daURL . "reviews"; ?>' <?php if( $tab == "reviews" ) echo "class='active'"; ?>>Reviews</a></li>
								</ul>

								<?php

								$dates = getDates();

								?>

								<!-- Section to display listing of events -->
								<section class="event-list">
									<h3>Show Tickets</h3>
									<select class="venue-selector" id="venue-selector">
										<option value="">All Venues</option>
										<?php
										// grab the array of venues from the show object
										$venues = get_post_meta( $post->ID, "venues", true );

										// iterate over the resulting array, adding a select option for each venue
										foreach ( $venues as $daVenue ) {
											$venue = get_post( $daVenue );
											echo "<option value='" . $venue->ID . "'>$venue->post_title</option>";
										}
										?>
									</select>
									<select class="month-selector" id="month-selector" name="mStr">
										<option value="0">January</option>
										<option value="1">February</option>
										<option value="2">March</option>
										<option value="3">April</option>
										<option value="4">May</option>
										<option value="5">June</option>
										<option value="6">July</option>
										<option value="7">August</option>
										<option value="8">September</option>
										<option value="9">October</option>
										<option value="10">November</option>
										<option value="11">December</option>
									</select>
									<script type="text/javascript">
										$('.month-selector').val(new Date().getMonth());
									</script>
									<input type="hidden" id="showID" value="<?php echo $post->ID; ?>" />
									<div id="events-table">
										<?php handleCalendar( $post->ID, $dates ); ?>
									</div>
								</section>

								<section class="entry-content cf">
									<?php
										get_template_part( "show", $tab );
									?>
								</section> <!-- end article section -->

								<section class="show-headlines">
									<h3>Recent Blog Posts</h3>
									<div class="headline-container">
										<?php
			                            $args = array(
			                                'posts_per_page' => 2,
			                                'no_found_rows' => true,
			                                'post_type' => 'post'
			                            );
			                            $recent = new WP_Query($args);
			                            if($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post(); ?>

			                            <?php
			                            // set thumbnail (or placeholder) for current article
			                            if ( has_post_thumbnail() ) {
			                            	$imgURL = get_the_post_thumbnail_url( $post->ID, 'small' );
			                            } else {
			                            	$imgURL = get_template_directory_uri() . "/library/assets/placeholder.jpg";
			                            }
			                            ?>

			                            <article class="show-recent" id="recent-<?php the_ID(); ?>">
		                                    <a href="<?php echo get_the_permalink(); ?>" class="thumbnail">
		                                    	<img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/beyond-buzz-logo-small.png" class="beyond-buzz-star" />
		                                    	<img src="<?php echo $imgURL; ?>" />
		                                    </a>
		                                    <div class="content">
		                                    	<?php $cats = get_the_category( get_the_ID() ); ?>
		                                        <span class="recent-category"><?php echo isset($cats[0])? $cats[0]->name : ""; ?></span>
		                                        <span class="recent-title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></span>
		                                        <span class="recent-date"><?php echo get_the_date(); ?></span>
		                                    </div>

			                            </article>

			                            <?php endwhile ; endif; ?>
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

						<!--</main>-->

				</div>

			</div>

<?php get_footer(); ?>
