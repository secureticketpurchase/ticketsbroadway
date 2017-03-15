<?php

function get_top_sellers($count = 12){
    $args = array (
        'post_type'		=> 'show',
        'meta_key' 		=> 'top_seller',
        'meta_value'	=> 1,
        'posts_per_page'=> $count,
        'no_found_rows'	=> true
    );

    // check if city option is selected.  If so, use its "shows" post meta array to add that limit to the query
    if ( MICRO_SHOWS != "" ) {
        $args['post__in'] = theme_arr("shows");
    }

    $seller_query = new WP_Query( $args );
    return $seller_query;
}

function get_home_slider($count = 4, $offset = 0){
    $args = array (
        'post_type'		=> 'show',
        'meta_key' 		=> 'display_in_slider',
        'meta_value'	=> true,
        'posts_per_page'=> $count,
        'no_found_rows'	=> true,
        'offset'        => $offset
    );
    
    $query = new WP_Query( $args );
    return $query;
}

function getVenueMap() {
    $mapAPIKey = "AIzaSyBpCWx_Mw3ATBE3iFRtRnYs2gfmby7Eirw";

    $address1 = urlencode( get_post_meta( get_the_ID(), "Street 1", true ) );
    $address2 = urlencode( get_post_meta( get_the_ID(), "Street 2", true ) );
    $zip = urlencode( get_post_meta( get_the_ID(), "zip", true ) );

    $toEcho = '<iframe frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=';
    $toEcho .= $mapAPIKey . "&q=";
    $toEcho .= $address1 . "," . $address2 . "," . $zip . '">';
    $toEcho .= "</iframe>";

    echo $toEcho;
}