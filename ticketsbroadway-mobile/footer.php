<div class="search" id="search-jump">
                        <?php get_search_form(); ?>
                    </div>	
<div class="to-top"><a href="#content">
    Top <img  class="flip-up" src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/arrow-button.png" style="width:15px;" />
    </a>
</div>
<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div class="footer-top wrap">
					<div class="footer-social">
						<a href="http://www.facebook.com/ticketsbroadway/" class="facebook-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/facebook.png" /></a>
						<a href="http://www.twitter.com/ticksbroadway/" class="twitter-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/twitter.png" /></a>
						<a href="https://www.instagram.com/ticketsbroadway/" class="instagram-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/instagram.png" /></a>
						<a href="https://www.pinterest.com/ticketsbroadway/" class="pinterest-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/pinterest.png" /></a>
						<a href="https://www.youtube.com/channel/UCiEZEbUab60ETExrru-SKUA" class="youtube-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/youtube.png" /></a>
					</div>

				</div>

				<div id="inner-footer" class="wrap cf">

					<div class="footer-columns">
						<div class="about-block column">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-mobile-nav-shows' ) ); ?>
						</div>

						<div class="info-block column">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-mobile-nav-info' ) ); ?>
						</div>
						<div class="contact-block column">
							<ul>
                                <li><a>Contact</a></li>
                            </ul>
							<p class="footer-email"><img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/email.png"  /></p>
							<p class="footer-phone"><img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/phone.png"  /></p>

						</div>
					</div>

					<div class="lower-inner-footer">
						<img src="<?php echo get_template_directory_uri(); ?>/library/assets/tickets-broadway-logo.png"  />
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/library/assets/mobile/tb-footer-credit-cards-norton.png" class="footer-cards" />
					</div>
                        <div class="copyright">
            &copy;<?php echo Date('Y'); ?>Tickets Broadway. All rights reserved
        </div>
				</div>

			</footer>
		</div>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
