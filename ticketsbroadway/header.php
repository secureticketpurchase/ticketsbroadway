<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/library/assets/icons/14693.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-76344506-1', 'auto');
			ga('send', 'pageview');

		</script>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">

					<div class="above-nav">

						<a href="<?php echo home_url(); ?>" rel="nofollow" id="logo"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/tickets-broadway-logo.png" /></a>

						<?php get_search_form(); ?>

						<div class="header-phone-container">
							<div class="header-bubble"><span><a href="tel:8447337469">1-844-2SEESHOW</a></span><img src="<?php echo get_template_directory_uri(); ?>/library/assets/phone-number-hover.png" /></div>
							<img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/phone-speech-bubble.png" class="phone-btn" />
						</div>

					</div>
                    
					<nav role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement" style="position:relative">

						<?php wp_nav_menu(array(
    					         'container' => false,                           // remove nav container
    					         'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    					         'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
    					         'menu_class' => 'nav top-nav cf',               // adding custom nav class
    					         'theme_location' => 'main-nav',                 // where it's located in the theme
    					         'before' => '',                                 // before the menu
        			               'after' => '',                                  // after the menu
        			               'link_before' => '',                            // before each link
        			               'link_after' => '',                             // after each link
        			               'depth' => 0,                                   // limit the depth of the nav
    					         'fallback_cb' => ''                             // fallback function (if there is one)
						)); ?>

						<?php
						// for the populating of the two drop down menus, we need to reference the main site, if this isn't it
						switch_site();

			            // First, grab a list of Genres that have been cleared to appear in the dropdown menu
			            $genreArgs = array(
			                "taxonomy"		=>	"genre",
			                "fields"		=>	"all",
			                "meta_query"	=>	array(
			                    array(
			                        "key"		=> "dropdown_display",
			                        "value"		=>	true,
			                        "compare"	=>	"="
			                    )
			                )
			            );
			            $terms = get_terms( $genreArgs );
			            ?>
                        
                        <div class="drop-down-shows" id="drop-down-shows">
                            <div class="ul-genre-list">
                                <ul>
                                	<?php
                                	$cntr = 0;
                                	foreach( $terms as $term ) { ?>
                                	<li class="genre" data-genre="<?php echo $term->term_id; ?>"><a href="<?php echo esc_url( home_url( '/' ) ) . 'genre/' . $term->slug; ?>" class="<?php if($cntr==0){echo 'active';} ?>"><?php echo $term->name; ?></a></li>
                                	<?php $cntr++;
                                	} ?>
                                </ul>
                            </div>
                            <?php
                            $cntr = 0;
                            foreach( $terms as $term ) {
                            	echo "<div class='genre-show-list'";
                            	if ( $cntr == 0 )
                            		echo " style='display:block;'";
                            	echo "id='genre-show-list-$term->term_id'>";
                            	// grab list of 8 shows of this genre
                            	// start building $args array
                            	$termArgs = array (
                            		'taxonomy'	=>	'genre',
                            		'field'		=>	'slug',
                            		'terms'		=>	$term->slug
                            	);
                            	$args = array(
                            		'post_type'		=>	'show',
                            		'posts_per_page'=>	'8',
                            		'no_found_rows'	=>	true,
                            		'tax_query'		=> array( $termArgs ),
                            		'meta_key'		=> 'nav_display',
                            		'meta_value'	=> 1
                            	);

                            	// temporarily restore to the current blog for the theme setting check
                            	revert_site();

                            	// check if city option is selected.  If so, use its "shows" post meta array to add that limit to the query
                            	if ( MICRO_SHOWS != "" ) {
                            		$args['post__in'] = theme_arr("shows");
                            	}

                            	// switch back to main site for the next chunk
                            	switch_site();

                            	$shows = get_posts( $args );

                            	// iterate through show list, building out the poster image links
                            	foreach ( $shows as $show ) { ?>
                            		<div class="single-show">
                            			<a href="<?php echo $show->guid; ?>">
                            				<?php
                            				if ( has_post_thumbnail( $show->ID ) ) {
                            					echo get_the_post_thumbnail( $show->ID, 'small' );
                            				} else {
                            					echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
                            				}
                            				?>
                            			</a>
                            		</div>
                            	<?php }
                            	echo "</div>";
                            	$cntr++;
                            }
                            ?>
                        </div>

                        <?php
						// Get list of cities cleared to be displayed in dropdown
						$args = array (
							"post_type"		=> "city",
							"meta_key"		=> "display_city",
							"meta_value"	=> 1,
							"orderby"		=> "title",
							"order"			=> "ASC",
							"posts_per_page"=> -1
						);
						$cities = get_posts( $args );
						?>

                        <div class="drop-down-cities" id="drop-down-cities">
                        	<ul>
                        		<?php
                        		foreach( $cities as $city ) {
									$daLink = get_permalink( $city );
									echo "<li><a href='" . site_url() . "/event-results/?tosearch=" . $city->post_name . "'>$city->post_title</a></li>";
								}
								?>
                        	</ul>
                        </div>

                        <?php
                        // We're near the end of the header...revert to current site, to prevent interfering with future queries
                        revert_site();
						?>
                        
						<span class="top-social">
							<a href="http://www.facebook.com/ticketsbroadway/" class="facebook-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/facebook.png" /></a>
							<a href="http://www.twitter.com/ticksbroadway/" class="twitter-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/twitter.png" /></a>
							<a href="https://www.instagram.com/ticketsbroadway/" class="instagram-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/instagram.png" /></a>
							<a href="https://www.pinterest.com/ticketsbroadway/" class="pinterest-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/pinterest.png" /></a>
							<a href="https://www.youtube.com/channel/UCiEZEbUab60ETExrru-SKUA" class="youtube-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/youtube.png" /></a>
						</span>

					</nav>

				</div>

			</header>
