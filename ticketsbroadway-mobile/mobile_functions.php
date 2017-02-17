<?php

function mobile_stylesheets(){
//     wp_enqueue_style( 'bones-stylesheet-mobile', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' ); //add mobiel sheet
}
add_action( 'wp_enqueue_scripts', 'mobile_stylesheets' );
function fix_style(){
    wp_deregister_style('bones-stylesheet');
    wp_register_style( 'bones-stylesheet', get_stylesheet_directory_uri() . '/../ticketsbroadway/library/css/style.css', array(), '', 'all' ); //readjust for desktop styles
     wp_enqueue_style( 'bones-stylesheet-alt', get_stylesheet_directory_uri() .  '/../ticketsbroadway/library/css/style.css', array(), '', 'all' );
    wp_enqueue_style( 'bones-stylesheet-mobile', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' ); //add mobiel sheet
    wp_enqueue_script('bones-mobile-scripts', get_stylesheet_directory_uri(). '/library/js/mobile.js', array());
     
}
add_action('wp_print_styles', 'fix_style', 99999);

function get_top_sellers_alt($count = 12){
    $args = array (
        'post_type'		=> 'show',
        'meta_key' 		=> 'top_seller',
        'meta_value'	=> 1,
        'posts_per_page'=> $count,
        'no_found_rows'	=> true
    );

    $seller_query = new WP_Query( $args );
    return $seller_query;
}

function get_home_slider_alt($count = 4, $offset = 0){

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
function mobile_handleCalendar($postId, $dates = null,$venueId = ""){
    $addMore = false;
    
    if($_POST){
        $addMore = $_POST['data']['addMore'] == "true"? true : false;
        
    }
    
    $handleEvents = handleCalendar( $postId, $dates, "", true );
    
    $events = $handleEvents['events'];
    $nextWeek = $handleEvents['week'];
    $listings = "";
    if(count($events) > 0){
        foreach($events as $event){
            $listings .= "<li class='individual-event' id='event-{$event->id}'>";
            $date = date('F d', strtotime($event->time));
            $time = date('g:ia', strtotime($event->time));
            $purchase = site_url().'/?eventID='.$event->id;
            $listings .= "<span class='date'>{$date}</span><span class='time'>{$time}</span><span class='buy-tickets'><a href='{$purchase}'>Buy Tickets</a></span>";
        }
    }
    
    $html .= "<div class='showtimes' id='showtime-listings'>";
    $html .= "<h4>Showtimes</h4>";
    $html .= "<ul>";
    if($listings == ""){
        $listings = "There are no events";
    }
    $html .= $listings;
    $html .= "</ul>";
    $html .= "<a id='add-more-link' class='addmore' data-week='{$nextWeek->format('Y-m-d')}'>More Showtimes</a>";
    $html .= "</div>";
    if($addMore){
        echo json_encode(array($listings, $nextWeek));
        wp_die();
    }
    echo $html;
    if($_POST){
        wp_die();
    }
    
}
add_action( "wp_ajax_nopriv_add_mobile_calendar", "mobile_handleCalendar" );
add_action( "wp_ajax_add_mobile_calendar", "mobile_handleCalendar" );
