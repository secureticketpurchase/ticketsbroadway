<?php
/*
 Template Name: Custom Page Example
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class="sidebar d-2of7 t-1of3">
								<h3 class="contact-faq">
									<a href="">Visit our FAQ Page.</a>
								</h3>
								<div class="contact-sidebar-container">
									<a href="tel:8442733746" class="call-us">
										<img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/small-phone-speech-bubble.png" />
										<p>Call Us<p>
									</a>
									<div class="contact-info">
										<span class="phone-info">
											Toll Free<br />
											<a href="tel:8442733746"><span class="phone-number">1-844-2SEESHOW</span></a><br />
											<a href="tel:8442733746"><span class="phone-number">1-844-273-3746</span></a>
										</span>
									</div>

									<p class="contact-sidebar">Sales agents and customer service agents are available to assist you every day at the times listed below.</p>
									<p class="contact-sidebar">7:00 a.m. to 1:00 a.m. EST</p>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="body-content contactuspage d-5of7 t-2of3" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h2 class="page-title">Send Us a Message</h2>

									<p>We love to hear from our users. Please submit any questions, comments, or updated site information.</p>

								</header>

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										the_content();
									?>
								</section>

							</div>

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page-custom.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						<!--</main>-->

				</div>

			</div>


<?php get_footer(); ?>
