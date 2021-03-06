<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="home-page-slider">

							<?php

							// from this point forward to the HoH is content pulled from the main site
							switch_site();

							$slider_query = get_home_slider();

							$slides; // array that will hold all "slides" (which are arrays containing show title, images, link et al)

							// open a loop for $slider_query to build out the slider HTML
							if ( $slider_query->have_posts() ) : while ( $slider_query->have_posts() ) : $slider_query->the_post();

								$slide['title'] = $post->post_title;
								$slide['link'] = get_permalink();
								$slide['poster'] = get_the_post_thumbnail( $post->ID, "small" );
								$slide['duration'] = get_post_meta( $post->ID, "duration", true );
								$intermissionNum = get_post_meta( $post->ID, "intermissions", true );

								switch ( $intermissionNum ) {
									case "":
										$slide['intermissions'] = "";
										break;
									case 0:
										$slide['intermissions'] = " | No Intermissions";
										break;
									case 1:
										$slide['intermissions'] = " | 1 Intermission";
										break;
									default:
										$slide['intermissions'] = " | " . $intermissionNum . " Intermissions";
										break;
								}

								//using "slider_image" meta field, grab attachment URL and store here
								$background_ID = get_post_meta( $post->ID, "slider_image", true );
								$backgroundURL = wp_get_attachment_url( $background_ID );
								$slide['background'] = $backgroundURL;

								// lastly, add this slide to our slides array
								$slides[] = $slide;

							endwhile;endif;

							wp_reset_postdata();

							/*echo "<pre>";
							print_r( $slides );
							echo "</pre>";*/

							// begin a foreach block to build out the actual URL, iterating through our slides
							?>

							<div id='gallery-block'>

							<?php
								$cntr = 0;
								foreach ( $slides as $slide ) {
									if( $cntr == 0 ) {
										$toAdd = "display:none;";
										$slideClass = "active-slide";
										$cntr++;
									} else {
										$toAdd = "";
										$slideClass = "";
										$cntr++;
									}
							?>
								<div class="slide <?php echo $slideClass; ?>" style="background-image: url('<?php echo $slide["background"]; ?>');" >
									<div class="slide-gradient">
										<div class='poster'><a href="<?php echo $slide['link']; ?>"><?php echo $slide['poster']; ?></a></div>
										<div class='show-info'>
											<a href="<?php echo $slide['link']; ?>"><h3><?php echo $slide['title']; ?></h3></a>
											<?php

											if ( $slide['duration'] != "" ) { ?>

											<div class="duration-block">
												<h4>DURATION</h4>
												<p><?php echo $slide['duration']; ?><?php echo $slide['intermissions']; ?></p>
											</div>

											<?php } ?>
											<a href="<?php echo $slide['link']; ?>" class="filled-buy" ><div>Buy Tickets</div></a>
											<div class="more-info"><a href="<?php echo $slide['link']; ?>" >More Info</a> ></div>
										</div>
									</div>
								</div>
							<?php	} ?>

							</div>

							<div id="slider-listing">
								<?php
								// let's build out the listing on the right side of the slider
								$cntr = 0;
								foreach ( $slides as $slide ) {
									if ( $cntr == 0 ) {
										$arrowClass = "active-arrow";
										$cntr++;
									} else {
										$cntr++;
										$arrowClass = "";
									}
									?>
									<div class="show-listing">
										<img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/dotted-arrow.png" class='dotted-arrow <?php echo $arrowClass; ?>'/>
										<div class='poster <?php echo $slide['link']; ?>'><a href="<?php echo $slide['link']; ?>"><?php echo $slide['poster']; ?></a></div>
										<div class='show-info'>
											<div class="show-title"><a href="<?php echo $slide['link']; ?>"><?php echo $slide['title']; ?></a></div>
											<a href="<?php echo $slide['link']; ?>" class="buy-tickets" ><div>Buy Tickets</div></a>
										</div>
									</div>
									<?php
								}

								?>
							</div>

							<script>
							var slides = $("#gallery-block").children();

							var someTime;

							function toggleThings( cntr ) {
								
								$($("#gallery-block > div")[cntr]).toggle();
								$($("#slider-listing > div > img.dotted-arrow")[cntr]).toggle();

								cntr = cntr == 3 ? 0 : cntr+1;
								$($("#gallery-block > div")[cntr]).toggle();
								$($("#slider-listing > div > img.dotted-arrow")[cntr]).toggle();

								someTime = setTimeout( toggleThings, 4000, cntr );
							}

							toggleThings( 0 );

							/*$("#gallery-block > div:gt(0)").hide();

							setInterval(function() { 
							  $('#gallery-block > div:first')
							    .fadeOut(1000)
							    .next()
							    .fadeIn(1000)
							    .end()
							    .appendTo('#gallery-block');
							},  5000);*/
							</script>

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							

							<?php endwhile; endif; ?>

						</div>

						<div id="top-sellers">

							<h1>Top Sellers</h1>
							<?php
								// Build out the query to grab first 12 top sellers (based on the meta field in show object)
								$args = array (
									'post_type'		=> 'show',
									'meta_key' 		=> 'top_seller',
									'meta_value'	=> 1,
									'posts_per_page'=> '12',
									'no_found_rows'	=> true
								);

								// revert to current site for theme check
								revert_site();

								// check if city option is selected.  If so, use its "shows" post meta array to add that limit to the query
                            	if ( MICRO_SHOWS != "" ) {
                            		$args['post__in'] = theme_arr("shows");
                            	}

                            	// switch back to main site for next chunk
                            	switch_site();

                            	//printDat($args);

								$seller_query = new WP_Query( $args );

								/*echo "<pre>";
								print_r( $seller_query );
								echo "</pre>";*/

								echo "<div class='top-sellers-list'>";

								// Commence the Loop!
								if( $seller_query->have_posts() ) : while( $seller_query->have_posts() ) : $seller_query->the_post();

									echo "<span class='top-seller-poster dropshadow'>";
									echo "<a href='" . get_the_permalink( $post->ID ) . "'>";
									echo the_post_thumbnail( 'full' );
									echo "</a></span>";

								endwhile; endif;

								// Everything from here is directly related to the current, rather than main, site
								revert_site();

								echo "</div>";
							?>

						</div>

						<div id="home-banner-image">
							<?php
								$theme_options = get_option( "tb_theme_options" );

								$banner_url = wp_get_attachment_url( $theme_options['banner_id'] );

								$banner_link = $theme_options['banner_link'];

								if ( $banner_url == false ) {
									// temporarily switch to main site, grab banner and link, then switch back over
									switch_site();

									$theme_options = get_option( "tb_theme_options" );
									$banner_url = wp_get_attachment_url( $theme_options['banner_id'] );
									$banner_link = $theme_options['banner_link'];

									// switch back to current site for the rest
									revert_site();
								}
							?>
							<a href="<?php echo $banner_link; ?>"><img src="<?php echo $banner_url; ?>" style="width:100%;"/></a>
						</div>

						<div id="beyond-buzz">
							<h1>Broadway News and Entertainment</h1>

							<div class="headline-container">
								<?php
	                            $args = array(
	                                'posts_per_page' => 3,
	                                'no_found_rows' => true,
	                                'post_type' => 'post'
	                            );
	                            $recent = new WP_Query($args);
	                            if($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post(); ?>

	                            <?php
	                            // set thumbnail (or placeholder) for current article
	                            if ( has_post_thumbnail() ) {
	                            	$imgURL = get_the_post_thumbnail_url( $post->ID, 'full' );
	                            } else {
	                            	$imgURL = get_template_directory_uri() . "/library/assets/placeholder.jpg";
	                            }
	                            ?>

	                            <a href="<?php the_permalink(); ?>"  class="home-recent">
	                            	<article id="recent-<?php the_ID(); ?>" style="background-image: url('<?php echo $imgURL; ?>');">
	                                    <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/beyond-buzz-logo-large.png" class="beyond-buzz-star" />
	                                    <div class="content">

	                                        <span class="recent-title"><?php the_title(); ?></span>
	                                        <?php $cats = get_the_category( get_the_ID() ); ?>
	                                        <span class="recent-meta"><?php echo get_the_date(); ?> <?php echo isset($cats[0])? " <span class='separator'>|</span> ".$cats[0]->name : ""; ?></span>
	                                    </div>

		                            </article>
		                        </a>

	                            <?php endwhile ; endif; ?>
	                        </div>

						</div>

						<div id="home-bottom-banner">
							<a href="<?php echo esc_url( home_url( '/' ) ) . 'about'; ?>"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/homepage-icons.jpg" style="width:100%;" /></a>
						</div>

				</div>

			</div>

<?php get_footer(); ?>
