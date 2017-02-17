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

				<div id="inner-content" class="wrap cf search-results">

							<div id="post-<?php the_ID(); ?>" class="d-5of7 find-a-show" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<div>
									<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
                                    <?php

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									if( have_posts() ) : while( have_posts() ) : the_post();

									?>	<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">
									<?php

										get_template_part( "show", "part" );

									?> </article><?php


									endwhile; endif;

									echo "</div>";
								?>

							</div>

						<!--</main>-->

				</div>

			</div>


<?php get_footer(); ?>
