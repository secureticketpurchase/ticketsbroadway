			<footer class="footer-main" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div class="footer-top wrap">
					<div class="footer-social">
						<a href="http://www.facebook.com/ticketsbroadway/" class="facebook-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/facebook.png" /></a>
						<a href="http://www.twitter.com/ticksbroadway/" class="twitter-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/twitter.png" /></a>
						<a href="https://www.instagram.com/ticketsbroadway/" class="instagram-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/instagram.png" /></a>
						<a href="https://www.pinterest.com/ticketsbroadway/" class="pinterest-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/pinterest.png" /></a>
						<a href="https://www.youtube.com/channel/UCiEZEbUab60ETExrru-SKUA" class="youtube-icon" target="_blank" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/black/youtube.png" /></a>
					</div>

					<div class="email-signup">
						<?php echo do_shortcode( '[contact-form-7 id="1250" title="Newsletter Signup"]' ); ?>
					</div>
				</div>

				<div id="inner-footer" class="wrap cf">

					<div class="footer-columns">
						<div class="about-block column">
							<p class="footer-column-header">About Tickets Broadway</p>
							<?php wp_nav_menu( array( 'theme_location' => 'footer-about' ) ); ?>
						</div>

						<div class="info-block column">
							<p class="footer-column-header">Useful Information</p>
							<?php wp_nav_menu( array( 'theme_location' => 'footer-information' ) ); ?>
						</div>

						<div class="services-block column">
							<p class="footer-column-header">Services</p>
							<?php wp_nav_menu( array( 'theme_location' => 'footer-services' ) ); ?>
						</div>

						<div class="contact-block column">
							<p class="footer-column-header">Contact Information</p>
							<p class="footer-email"><a href="mailto:Info@ticketsbroadway.com">Info@ticketsbroadway.com</a></p>
							<p class="footer-phone"><a href="tel:8442733746">1-844-2SEESHOW</a></p>

						</div>
					</div>

					<div class="lower-inner-footer">
						<img src="<?php echo get_template_directory_uri(); ?>/library/assets/tickets-broadway-logo.png" class="footer-logo" />
						<div class="copywrite">&copy; <?php echo Date('Y'); ?> Tickets Broadway. All rights reserved.</div>
						<img src="<?php echo get_template_directory_uri(); ?>/library/assets/credit-norton.png" class="footer-cards" />
					</div>

				</div>

			</footer>

		</div>

		<?php
		// ping the table predictive_terms, spit out an array of terms for javascript to use
		global $wpdb;
		$table = $wpdb->prefix . "predictive_terms";

		$terms = $wpdb->get_col( "SELECT term FROM $table" );
		?>
		<script>
		var terms = <?php echo json_encode($terms); ?>;

		$( function() {
			jQuery( '#tosearch' ).autocomplete({
				source: terms
			});
		});
		</script>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
