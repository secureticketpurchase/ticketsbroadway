<?php
/*
 * CITY TEMPLATE
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php
							// URL that will have appropriate tab parameter appended to it
							$daURL = get_permalink($post->ID) . "?tab=";
							?>

							<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 show-content" <?php post_class('cf'); ?> role="article">
                                <div class="single-top">
                                    <div class="img-container">
                                        <?php 
                                        
                                        if(has_post_thumbnail()){
                                            the_post_thumbnail('full');
                                        }else{ ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        <?php } ?>
                                    </div>
                                    <div class="info-container">
                                        <h1><?php echo $post->post_title; ?></h1>
                                        <?php $cat = get_the_terms($post->ID, 'genre'); if($cat[0]){ ?>
                                        <span><?php echo $cat[0]->name; ?></span>
                                        <?php } ?>
                                        <a class="buy-tickets" id="buy-tickets-button">Buy Tickets</a><!-- js function .single-top .buy-tickets onclick replace main section with calander stuff-->
                                    </div>
                                </div>
								
                                <div class="header-style hideShowSchedule">
                                    <a class="the-show active" id="show-info-link">THE SHOW </a> | <a class="the-schedule" id="show-events-link"> BUY TICKETS </a>
                                </div>
				                

								<section class="entry-content cf show-info">
                                    <div class="pricing">
                                        <h4>Price</h4>
                                        <?php if( $duration = get_post_meta( $post->ID, "low_price", true) ){
                                                echo sprintf(' <a href="#">Buy Tickets</a>' );
                                            }else{
                                                echo "unknown";
                                                } 
                                        ?> 
                                    </div>
                                    <div class="runtime">
                                        <h4>Runtime</h4>
                                        <?php if( $duration = get_post_meta( $post->ID, "duration", true) ){
                                                    $intermission = get_post_meta( $post->ID, "intermissions", true);
                                                    echo $duration . ' '. $intermission;
                                                
                                            }else{
                                                echo "unknown";
                                                } 
                                        ?>
                                    </div>
                                    <div class="description">
                                        <h4>Show Description</h4>
                                        <?php
                                            get_template_part( "show", "show" );
                                        ?>
                                    </div>
                                    <?php 
                                    $memberIDs = get_post_meta( $post->ID, "cast_members", true );
                                    
                                    if($memberIDs){ ?>
                                        <div class="cast">
                                            <h4>Cast &amp; Crew</h4>
                                            <?php get_template_part("show", "cast"); ?>

                                        </div>
                                    <?php } ?>
                                    <div class="reviews">
                                        <h4>Reviews</h4>
                                    </div>
								</section> <!-- end article section -->


								<?php

								$dates = getDates();

								?>

								<!-- Section to display listing of events -->
								<section class="event-list">
                                    
                                    <div class="selectors">
                                        <select class="venue-selector" id="venue-selector-mobile">
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
                                        <select class="month-selector" id="month-selector-mobile" name="mStr">
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
                                    </div>
									<div id="events-table">
										<?php mobile_handleCalendar( $post->ID, $dates ); ?>
									</div>
								</section>

							</div>

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
