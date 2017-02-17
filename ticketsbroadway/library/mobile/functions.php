<?php

add_action( 'after_setup_theme', 'register_mobile_navs' );
function register_mobile_navs() {
    register_nav_menu( 'primary-mobile-nav', 'Primary Mobile Nav' );
    register_nav_menu( 'footer-mobile-nav-shows', 'Footer Mobile Nav Leftside' );
    register_nav_menu( 'footer-mobile-nav-info', 'Footer Mobile Nav Center' );
    register_nav_menu( 'footer-mobile-nav-contact', 'Footer Mobile Nav Rightside' );
}