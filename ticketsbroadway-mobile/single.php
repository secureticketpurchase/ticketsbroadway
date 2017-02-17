<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
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
                                </div>
                        </div>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php
								/*
								 * Ah, post formats. Nature's greatest mystery (aside from the sloth).
								 *
								 * So this function will bring in the needed template file depending on what the post
								 * format is. The different post formats are located in the post-formats folder.
								 *
								 *
								 * REMEMBER TO ALWAYS HAVE A DEFAULT ONE NAMED "format.php" FOR POSTS THAT AREN'T
								 * A SPECIFIC POST FORMAT.
								 *
								 * If you want to remove post formats, just delete the post-formats folder and
								 * replace the function below with the contents of the "format.php" file.
								*/
								get_template_part( 'post-formats/format', get_post_format() );
							?>

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
											<p><?php _e( 'This is the error message in the single.php template.', 'bonestheme' ); ?></p>
									</footer>
							</article>

						<?php endif; ?>

					</main>

				</div>

			</div>

<?php get_footer(); ?>
