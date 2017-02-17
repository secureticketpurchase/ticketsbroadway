<?php get_header(); ?>

			<div id="content">            

				<div id="inner-content" class="wrap cf">
                    <div class="blog-wrapper">
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
                            <div class="beyond-the-buzz">
                                 <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/beyond-the-buzz-star-header.png">
                            <div class="buzz">
                        <?php if ( get_query_var( 'paged' ) ) { 
                        $paged = get_query_var( 'paged' );
                    } elseif ( get_query_var( 'page' ) ) {
                        $paged = get_query_var( 'page' );
                    } else { $paged = 1; }
                    $the_query = new WP_Query( array( 'posts_per_page' => 4, 'paged' => $paged ) );

                    if ( $the_query->have_posts() ) : 

                        $cntr = 0; //keeps track of articles, adds horizontal rule after first two

                        while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                            <?php $categories = get_the_category(); //array of categories attached to current post

                    ?>
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
                                
                                <?php
                    endwhile; endif; ?>
                                </div>
                                <div class="pagination">
                                    <?php
                                    $blog_url = get_permalink( get_option( 'page_for_posts' ) );
                                    $next_link = $blog_url.'/page/'.($paged + 1);
                                    $prev_link = $blog_url.'/page/'.($paged - 1);
                                    ?>
                                <?php if($paged === 1){ //First page show next only ?>
                                    <div class="lone"><a href="<?php echo $next_link; ?>">Next Page <img  class="flip" src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" /></a></div>
                                    <?php } else{ //page 2 or higher show prev and next page ?>
                                    <div class="left"><a href="<?php echo $prev_link; ?>"> <img  src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png"  /> Previous Page</a></div>
                                    <div class="right"><a href="<?php echo $next_link; ?>">Next Page <img  class="flip" src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" /></a></div>
                                    
                                    <?php } ?>
                                </div>
                        </div>
                    </div>
				</div>

			</div>
<?php get_footer(); ?>