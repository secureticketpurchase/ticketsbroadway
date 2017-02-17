
              <?php
                /*
                 * This is the default post format.
                 *
                 * So basically this is a regular post. if you don't want to use post formats,
                 * you can just copy ths stuff in here and replace the post format thing in
                 * single.php.
                 *
                 * The other formats are SUPER basic so you can style them as you like.
                 *
                 * Again, If you want to remove post formats, just delete the post-formats
                 * folder and replace the function below with the contents of the "format.php" file.
                */
              ?>
            <style>
                .hentry{
                    border:none;
                    padding:0px 5px;
                }
    </style>
              <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">
                  <?php  $cat = ""; $catId = 0;
                      $cats = get_the_category();
                      
                      if($cats[0]){
                        $catId = $cats[0]->term_id;
                        $cat = $cats[0]->name;
                      }?>
                  <span><?php echo $cat; ?></span>
                  <h1><?php the_title(); ?></h1>
                  <p class="byline entry-meta vcard">

                    <?php

                      if($cats[1]){
                        
                        $cat = $cats[1]->name;
                      }
                      printf( __( '', 'bonestheme' ).' %1$s %2$s',
                       /* the time the post was published */
                       '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                       /* the author of the post */
                       '<span class="by">'.__( '|', 'bonestheme' ).'</span> <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . $cat . '</span>'
                    ); ?>
                  </p> <?php // end article header ?>

                <section class="entry-content cf" itemprop="articleBody">
                    <div class="image-container">
                        <div class="image-inner-container">
                    <?php 
                                        
                                        if(has_post_thumbnail()){
                                            the_post_thumbnail('full');
                                        }else{ ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        <?php } ?>
                    </div>
                    </div>
                  <?php
                    // the content (pretty self explanatory huh)
                    the_content();

                  ?>
                </section> <?php // end article section ?>

                  <div class="related">
                        Related Articles
                    </div>
                  <?php 
                  
                  
                  $id = get_the_ID();
                  $args = array(
                    'posts_per_page'    => 2,
                      'post_type'   => 'post',
                      'cat' => $catId,
                      'post__not_in'    => array($id)
                  );
                  $related = new WP_Query($args);
                  if($related->have_posts()) : while ($related->have_posts()) : $related->the_post(); ?>

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
                  
                  <div class="view-all-entertainment">
                                <a href="">View All Entertainment News</a>
                            </div>
                <?php //comments_template(); ?>

              </article> <?php // end article ?>
