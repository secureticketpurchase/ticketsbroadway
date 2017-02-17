<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="home-page-slider">
                            <span class="mobile-slider-nav nav-right"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" style="width:100%;" /> </span>
                            <span class="mobile-slider-nav nav-left">  <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" style="width:100%;" /> </span>
                            <ul class="home-mobile-slider">
							<?php 

                            $slider = get_home_slider_alt();
                                $i = 0;
                            
                            if ($slider->have_posts()) : while ($slider->have_posts()) : $slider->the_post(); ?>
                                
                                <li class="home-slider-show <?php echo $i > 1 ? 'hiddenPoster' : ''; ?>"><a href="<?php echo get_the_permalink(); ?>"> <?php 
                                        
                                        if(has_post_thumbnail()){
                                            the_post_thumbnail('full');
                                        }else{ ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        <?php } ?></a></li>
							

							<?php ++$i;
                            endwhile; endif; 
                            ?>
                            </ul>
                            
						</div>

						<div id="top-sellers">

							<h1 class="home-top-sellers">Top Sellers</h1>
                            <div class='top-sellers-list'>
                                <ul class='top-seller-list'>
							<?php
								// Build out the query to grab first 12 top sellers (based on the meta field in show object)
								
              
								$seller_query = get_top_sellers(12);
                             
								// Commence the Loop!
								if( $seller_query->have_posts() ) : while( $seller_query->have_posts() ) : $seller_query->the_post();

									echo sprintf('<li class="top-seller"><a href="%s">%s</a><img src="%s" class="top-sller-arrow"></li>', get_the_permalink(), get_the_title(),  get_stylesheet_directory_uri().'/library/assets/mobile/arrow-button.png' );

								endwhile; endif;
                                echo sprintf('<li class="top-seller-view-all"><a href="find-a-show">View All Shows</a></li>');

							?>
                                </ul>
                            </div>
                            <h1 class="home-top-sellers">Shows</h1>
                            <div class='top-sellers-list'>
                                
                                    <!-- Use a wp menu here -->
                                    <?php wp_nav_menu( array( 
                                            'theme_location' => 'footer-mobile-nav-info',
                                            'link_after' => sprintf('<img src="%s" class="top-sller-arrow">',get_stylesheet_directory_uri().'/library/assets/mobile/arrow-button.png' ),
                                            'menu_class' => 'top-seller-list shows-navigation'
                                        ) ); ?>
                                
                            </div>
                            
						</div>


						<div id="beyond-buzz">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/beyond-the-buzz-star-header.png">
                            <div class="buzz">
                                

                                <?php
                                $args = array(
                                    'posts_per_page' => 3,
                                    'no_found_rows' => true,
                                    'post_type' => 'post'
                                );
                                $recent = new WP_Query($args);
                                if($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post(); ?>

                                <article class="mobile-home-recent" id="recent-<?php the_ID(); ?>">
                                    <div class="outer-img-container"><!-- sets size of div to total article container -->
                                        <div class="img-container"> <!--sets for display image in box with border -->
                                             <?php 
                                        
                                        if(has_post_thumbnail()){
                                            the_post_thumbnail('full');
                                        }else{ ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        <?php } ?>
                                        </div>
                                    </div>
                                    <div class="cont-container">
                                        <div class="content">
                                            <?php $cats = get_the_category(get_the_ID()); ?>
                                            <span class="recent-cat"><?php echo isset($cats[0])? $cats[0]->name : ""; ?></span>
                                            <span class="recent-title"><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></span>
                                            <span class="recent-meta"><?php echo get_the_date(); ?> <?php echo isset($cats[1])? "| ".$cats[1]->name : ""; ?></span>
                                        </div>
                                        <div class="arrow">
                                            <img  class="flip" src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" style="width:20px;" />
                                        </div>
                                    </div>

                                </article>



                                <?php endwhile ; endif; ?>
                            </div>
                            <div class="view-all-entertainment">
                                <a href="">View All Entertainment News <img class="flip" src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" /></a>
                            </div>
						</div>
				</div>
			</div>

<?php get_footer(); ?>
