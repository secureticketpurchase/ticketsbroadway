<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf blog">

					<div class="t-1of3 d-2of7">
						<?php get_sidebar( "blog" ); ?>

						<div class="widget-area">
							<?php if ( dynamic_sidebar( 'cta-sidebar' ) ) : ?>
							<?php endif; ?>
						</div>
					</div>

					<div id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php $categories = get_the_category(); //array of categories attached to current post ?>

							<article id="<?php the_ID(); ?>" class="single" >
								<h2><?php the_title(); ?></h2>
								<?php
								$video_meta = get_post_meta( get_the_ID(), 'brafton_video', true );
								if ( $video_meta == "" ) {
								?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="thumbnail"><?php echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?></a>
								<?php
								}
								?>
								<div class="article-header">
									<time datetime="<?php echo date(DATE_W3C); ?>" pubdate ><?php the_time('F jS, Y'); ?></time> | <span class="category"> <?php echo $categories[0]->name; ?></span> | 
									<span class="social-icons">
										<a href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&url=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/twitter.png" /></a>
										<a href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/facebook.png" /></a>
									</span>
								</div>
								<div class="entry-content">
									<?php the_content(); ?>
								</div>
							</article>

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

					</div>

				</div>

			</div>

<?php get_footer(); ?>
