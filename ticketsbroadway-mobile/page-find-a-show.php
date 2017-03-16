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

				<?php
					$toSearch = get_query_var( "tosearch", "none" );
					$theGenre = get_query_var( "genre", "" );
					//echo $toSearch;

					// Create URL, to be loaded with additional parameters when clicking on sidebar filters
					$daURL = site_url('/') . 'find-a-show/?tosearch=' . $toSearch;

					if( $theGenre != "" ) {
						//build out taxonomy array for addition to search query
						$genreArr = array (
							'taxonomy'	=>	'genre',
							'field'		=>	'slug',
							'terms'		=>	$theGenre
						);
					}

					//echo $daURL;
				?>

				<div id="inner-content" class="wrap cf search-results">

					<?php
					switch_site();
					?>

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


							<div id="post-<?php the_ID(); ?>" class="d-5of7 find-a-show" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">


								<div class="show-search-form">
                                    <!--
                                    <div class="genre-select-cont sel-container">
                                        <span id="genre-display" class="sudo-select">Genre</span>
                                        <ul class="genre-select">
                                            <li>Genre 1</li>
                                            <li>Genre 2</li>
                                            <li>Genre 3</li>
                                            <li>Genre 4</li>
                                            <li>Genre 5</li>
                                            <li>Genre 6</li>
                                        </ul>
                                    </div>
                                    <div class="month-select-cont sel-container">
                                        <span id="month-display" class="sudo-select">Month</span>
                                        <ul class="month-select">
                                            <li>January</li>
                                        </ul>
                                    </div>
                                    <div class="venue-select-cont sel-container">
                                        <span id="venue-display" class="sudo-select">Venue</span>
                                        <ul class="venue-select">
                                            <li>Venue 1</li>
                                        </ul>
                                    </div>
                                    <div class="city-select-cont sel-container">
                                        <span id="city-display" class="sudo-select">city</span>
                                        <ul class="city-select">
                                            <li>Boston</li>
                                        </ul>
                                    </div>
                                    
                                    -->
                                    <?php 
                                    $args = array(
                                        'genre' => array(
                                            'label' => "",
                                        ),
                                        'city' => array(
                                            'label' => "",
                                        ),
                                        'month' => array(
                                            'label' => "",
                                        )
                                    );
                                    get_filter_form($args); ?>
								</div>

								<div>
									<h1 class="page-title"><?php echo ($theGenre == "")? "All" : $theGenre; ?></h1>

									<?php
									// Build out the query to grab first 12 top sellers (based on the meta field in show object)
									$paged = isset($_POST['search_paged']) ? intval($_POST['search_paged']) : 1;
									$shows_query = getShowResults();

									/*echo "<pre>";
									print_r( $seller_query );
									echo "</pre>";*/

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									$total = $shows_query->max_num_pages;
									$numP = $shows_query->found_posts;
									if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();

									?>	<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">
									<?php

										get_template_part( "show", "part" );

									?> </article><?php

										/*echo "<span class='top-seller-poster'>";
										if ( has_post_thumbnail() ) {
											echo the_post_thumbnail( 'full' );
										} else {
											echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
										}
										echo "</span>";*/

									endwhile; endif;

									echo "</div>";
								?>

							</div>

							<?php endwhile; ?>
							<?php wp_reset_query(); ?>
							<nav class="pagination">
							<?php $pages = array($paged -2, $paged -1, $paged, $paged + 1, $paged +2); ?>
								<ul class="page-numbers">
								<?php if($total != 1){ ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $next -1; ?>">←</a></li>
								<?php } ?>
								<?php for($i=0;$i<=count($pages);$i++){ 
									$next = $pages[$i];
									if($next < 1){ continue; } ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $next; ?>"><?php echo $next; ?></a></li>
								<?php } ?>
								<?php if($total > 1){ ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $next +1; ?>">→</a></li>
								<?php } ?>
								</ul>
							</nav>
							<?php else : ?>

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
						<?php revert_site(); ?>
				</div>

			</div>


<?php get_footer(); ?>
