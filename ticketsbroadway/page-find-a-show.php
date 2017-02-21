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

				<div id="inner-content" class="wrap cf search-results find-a-show">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class='sidebar d-2of7 t-1of3'>
                                                                    <?php 
                                    $args = array(
                                        'genre' => array(
                                            'label' => "yes",
                                            'style' => "list"
                                        ),
                                        'city' => array(
                                            'label' => "yes",
                                        ),
                                        'month' => array(
                                            'label' => "yes",
                                            'style' => "list"
                                        )
                                    );
                                    get_filter_form($args); ?>
							</div>

							<div id="post-<?php the_ID(); ?>" class="d-5of7 t-2of3" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
								<!--<pre>
								<?php print_r($_POST); ?>
								</pre>-->

								<header class="article-header">

									<h1 class="page-title"><?php the_title(); ?></h1>

								</header>

								<div class="show-search-form">
									
									    <input type="text" name="tosearch-find-a-show" placeholder="Search Shows"/>
									     <!-- // hidden 'products' value -->
									    <button value="Search" id="searchSubmit-find-a-show"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/search.png" /></button>
									   <script>
                                          $('input[name="tosearch-find-a-show"]').keyup(function(){
                                              console.log("hello");
                                              $('#searchform-find-a-show input[name="search_tosearch"]').val($('input[name="tosearch-find-a-show"]').val());
                                          })
                                        $('#searchSubmit-find-a-show').click(function(){
                                            $('#searchform-find-a-show').submit();
                                        })
                                    </script>
								</div>

								<div class="find-shows-featured">
									<h3>Featured Shows</h3>
									<div id="shows-listing-container">
										<input type="hidden" id="post-id" value="<?php echo $post->ID; ?>" />
										<?php display_shows( $post->ID, 4, true ); ?>
									</div>
								</div>

								<div>
									<h2>Search Results</h2>
									<?php
									// Call "getShowResults()", which is a function that takes all the various filters and search params, and returns a WP_Query object
									$paged = isset($_POST['search_paged']) ? intval($_POST['search_paged']) : 1;

									$shows_query = getShowResults();

									/*echo "The Result Query is:<br />";
									echo "<pre>";
									print_r( $shows_query ) ;
									echo "</pre>";*/

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									//if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();
									$total = $shows_query->max_num_pages;
									$numP = $shows_query->found_posts;
									if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();

									?>	<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">
									<?php

										get_template_part( "search", "show" );

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
							<?php
							if ( $total != 1 ) { ?>
							<nav class="pagination">
							<?php $pages = array($paged -2, $paged -1, $paged, $paged + 1, $paged +2); ?>
								<ul class="page-numbers">
								<?php if($total != 1 && $paged != 1){ ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $paged - 1; ?>">←</a></li>
								<?php } ?>
								<?php for($i=0;$i<count($pages);$i++){
									$next = $pages[$i];
									if( $next > $total ) break;

									if($next < 1 || $pages[$i] < 1){ continue; } ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $next; ?>"><?php echo $next; ?></a></li>
								<?php } ?>
								<?php if($total > 1  && $paged < $total){ ?>
									<li><a class="page-numbers search-page-paged"  data-page="<?php echo $paged + 1; ?>">→</a></li>
								<?php } ?>
								</ul>
							</nav>
							<?php } ?>
							
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

				</div>

			</div>


<?php get_footer(); ?>
