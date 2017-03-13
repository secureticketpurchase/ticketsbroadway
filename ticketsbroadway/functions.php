<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// Load in API related libraries
include_once( 'library/api-lib/api-includes.php' );

include_once('library/mobile/functions.php');

include_once('functions/showutils.php');

include_once('functions/search.php');
// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );

  // enqueue Tickets Broadway specific scripts
  add_action( 'wp_enqueue_scripts', 'broadway_scripts');

  add_action( 'wp_enqueue_scripts', 'tickets_enqueue_scripts');

  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

  // run function for building out tickets table
  build_event_tbl();

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'cta-sidebar',
		'name' => __( 'Main Sidebar CTA', 'bonestheme' ),
		'description' => __( 'CTA(s) to appear all over the site', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

  register_sidebar(array(
    'id' => 'blog-sidebar',
    'name' => __( 'Blog Sidebar', 'bonestheme' ),
    'description' => __( 'Sidebar for all blog/post sections.', 'bonestheme' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
  ));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
  wp_enqueue_style('jquery-ui-min', get_template_directory_uri() . '/library/css/jquery-ui.min.css');
  wp_enqueue_style('jquery-ui-structure-min', get_template_directory_uri() . '/library/css/jquery-ui.structure.min.css');
  wp_enqueue_style('jquery-ui-theme-min', get_template_directory_uri() . '/library/css/jquery-ui.theme.min.css');
}

add_action('wp_enqueue_scripts', 'bones_fonts');

/*

END OF BONES THEME FUNCTIONS

Begin Tickets Broadway specific code

*/

// hook into init, register our various custom post types!
add_action( 'init', 'create_tb_post_types' );
function create_tb_post_types() {
  //register Show post type
  $showArgs = array(
    'labels' => array(
      'name'          => __( 'Shows' ),
      'singular_name'     => __( 'Show' ),
      'add_new_item'      => __( 'Add New Show' ),
      'edit_item'       => __( 'Edit Show' ),
      'new_item'        => __( 'New Show' ),
      'view_item'       => __( 'View Show' ),
      'search_items'      => __( 'Search Shows' ),
      'not_found'       => __( 'No shows found' ),
      'not_found_in_trash'  => __( 'No shows found in Trash' ),
      'all_items'       => __( 'All Shows' )
    ),
    'public'      =>  true,
    'has_archive' =>  true,
    'description'   =>  'Shows Tickets Broadway has inventory for.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'show'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true,
    'show_in_nav_menus' => true
  );

  $result = register_post_type( 'show', $showArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Show) issue here: " . $error_string . "<br />";
  }

  //register Venue post type
  $venueArgs =  array(
    'labels' => array(
      'name'          => __( 'Venues' ),
      'singular_name'     => __( 'Venue' ),
      'add_new_item'      => __( 'Add New Venue' ),
      'edit_item'       => __( 'Edit Venue' ),
      'new_item'        => __( 'New venue' ),
      'view_item'       => __( 'View venue' ),
      'search_items'      => __( 'Search Venues' ),
      'not_found'       => __( 'No venues found' ),
      'not_found_in_trash'  => __( 'No venues found in Trash' ),
      'all_items'       => __( 'All Venues' )
    ),
    'public'      =>  true,
    'description'   =>  'Venues where shows are potentially ocurring.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'venue'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'venue', $venueArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Venue) issue here: " . $error_string . "<br />";
  }


  //register City post type
  $cityArgs = array(
    'labels' => array(
      'name'          => __( 'Cities' ),
      'singular_name'     => __( 'City' ),
      'add_new_item'      => __( 'Add New City' ),
      'edit_item'       => __( 'Edit City' ),
      'new_item'        => __( 'New city' ),
      'view_item'       => __( 'View city' ),
      'search_items'      => __( 'Search Cities' ),
      'not_found'       => __( 'No cities found' ),
      'not_found_in_trash'  => __( 'No cities found in Trash' ),
      'all_items'       => __( 'All Cities' )
    ),
    'public'      =>  true,
    'description'   =>  'Cities holding Venues where shows are potentially ocurring.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'city'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'city', $cityArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (City) issue here: " . $error_string . "<br />";
  }


  //register Cast post type
  $castArgs = array(
    'labels' => array(
      'name'          => __( 'Cast & Crew' ),
      'singular_name'     => __( 'Cast' ),
      'add_new_item'      => __( 'Add New Cast' ),
      'edit_item'       => __( 'Edit Cast' ),
      'new_item'        => __( 'New Cast' ),
      'view_item'       => __( 'View Cast' ),
      'search_items'      => __( 'Search Cast & Crew' ),
      'not_found'       => __( 'No Cast found' ),
      'not_found_in_trash'  => __( 'No Cast found in Trash' ),
      'all_items'       => __( 'All Cast & Crew' )
    ),
    'public'      =>  true,
    'description'   =>  'Cast & Crew participating in Ticket Broadway shows.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'cast'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'cast', $castArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Cast) issue here: " . $error_string . "<br />";
  }

  //register Cast post type
  $reviewArgs = array(
    'labels' => array(
      'name'          => __( 'Reviews' ),
      'singular_name'     => __( 'Review' ),
      'add_new_item'      => __( 'Add New Review' ),
      'edit_item'       => __( 'Edit Review' ),
      'new_item'        => __( 'New Review' ),
      'view_item'       => __( 'View Review' ),
      'search_items'      => __( 'Search Reviews' ),
      'not_found'       => __( 'No Review found' ),
      'not_found_in_trash'  => __( 'No Reviews found in Trash' ),
      'all_items'       => __( 'All Reviews' )
    ),
    'public'      =>  true,
    'exclude_from_search' => true,
    'description'   =>  'Show reviews.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'review'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'review', $reviewArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Review) issue here: " . $error_string . "<br />";
  }
}

// hook (back) into init to register taxonomies!
add_action( 'init', 'create_ticket_tax' );
function create_ticket_tax() {

  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
    'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
    'search_items'      => __( 'Search Genres', 'textdomain' ),
    'all_items'         => __( 'All Genres', 'textdomain' ),
    'parent_item'       => __( 'Parent Genre', 'textdomain' ),
    'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
    'edit_item'         => __( 'Edit Genre', 'textdomain' ),
    'update_item'       => __( 'Update Genre', 'textdomain' ),
    'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
    'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
    'menu_name'         => __( 'Genre', 'textdomain' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'genre', 'with_front' => false ),
    'description'   => 'Genre for shows and cast.  Parent categories are Sports, Theater, Concerts or Other.',
  );

  //register_taxonomy( 'genre', array( 'show', 'cast' ), $args );
  register_taxonomy( 'genre', array( 'show' ), $args );

  register_taxonomy_for_object_type( 'genre', 'show' );
  //register_taxonomy_for_object_type( 'genre', 'cast' );

}

// create three tables required for managing inventory on theme activation (called in the general theme set up function above)
// one to hold events
// one to hold a list of performers, used to break up the import runs into manageable chunks
// lastly, a table holding the relationships between WP genres and Ticket Network categories
function build_event_tbl() {
  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();

  $table_name = $wpdb->prefix . "events";

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00',
    venue mediumint(9),
    performer mediumint(9),
    city text,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  $performer_name = $wpdb->prefix . "performers";
  $performerSql = "CREATE TABLE $performer_name (
    id mediumint(9) NOT NULL,
    name text,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  dbDelta( $performerSql );

  $category_name = $wpdb->prefix . "categories";
  $categorySql = "CREATE TABLE $category_name (
    id mediumint(9) NOT NULL,
    name text,
    wp_id mediumint(9),
    PRIMARY KEY  (id)
  ) $charset_collate;";
  dbDelta( $categorySql );

  $searched_terms_name = $wpdb->prefix . "searched_terms";
  $searchedSql = "CREATE TABLE $searched_terms_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    term text,
    num_searched mediumint(9) DEFAULT 1,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  dbDelta( $searchedSql );

  $cached_results_name = $wpdb->prefix . "cached_results";
  $cachedSql = "CREATE TABLE $cached_results_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    term text,
    result blob,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  dbDelta( $cachedSql );

  $predictive_name = $wpdb->prefix . "predictive_terms";
  $predictiveSql = "CREATE TABLE $predictive_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    term text,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  dbDelta( $predictiveSql );
}

// Let's create our Tickets Broadway specific theme options
// First, register a submenu under "Appearance" menu
add_action( "admin_menu", "add_theme_options_menu" );

function add_theme_options_menu() {
  add_theme_page( "Theme Options", "Theme Options", "manage_options", "tb_theme_options", "build_tb_theme_options" );
  //add_submenu_page( "themes.php", "Theme Options", "Theme Options", "manage_options", "tb_theme_options", "build_tb_theme_options" );
}

// Do all the work to initialize the theme options and make them available
add_action( "admin_init", "tb_settings_init" );
function tb_settings_init() {

  //push default options if they're not created yet
  if ( get_option( "tb_theme_options" ) == false ) {
    add_option( "tb_theme_options", apply_filters( "tb_default_options", tb_default_options() ) );
  }

  // Add a section to our submenu
  add_settings_section (
    "tb_settings_section",
    "Tickets Broadway Theme Options",
    "tb_section_callback",
    "tb_theme_options"
  );

  // Add option for banner image on homepage
  add_settings_field (
    "Banner Image",
    "Homepage Banner Image",
    "banner_callback",
    "tb_theme_options",
    "tb_settings_section"
  );

  // Add option for banner image link on homepage
  add_settings_field (
    "Banner Image Link",
    "Homepage Banner Link",
    "banner_link_callback",
    "tb_theme_options",
    "tb_settings_section"
  );

  // Add option for selecting a city (for spinning out microsites)
  add_settings_field (
    "City Selection",
    "Microsite City",
    "microsite_city_callback",
    "tb_theme_options",
    "tb_settings_section"
  );

  // Lastly, register them settings
  register_setting (
    "tb_theme_options",
    "tb_theme_options"
  );
}

// Set default theme options
function tb_default_options() {
  $defaults = array (
    'banner_id'   => '',
    'banner_link' => '',
    'city'        => ''
  );
  return apply_filters( "tb_default_options", $defaults );
}

/* Commence the various setting callbacks
---------------------------------------------------*/

// Section callback function
function tb_section_callback() {
  echo "<p>Theme settings for Tickets Broadway</p>";
}

// Build out homepage banner image option section
function banner_callback() {
  $options = get_option( "tb_theme_options" );
?>

  <label id="banner-image-label" style="cursor:pointer;">Select a banner image to display on the homepage: </label>
  <input id="banner_id" name="tb_theme_options[banner_id]" value ="<?php echo $options['banner_id']; ?>" type="text" style="display:none;" />
  <input type="button" class="upload_image_button" value="Select Image" name="banner_url" /><img src="<?php echo wp_get_attachment_url( $options['banner_id'] ); ?>" id="img_preview" style="max-width:1150px; margin-top:10px" />

<?php
}

// Build out homepage banner link section
function banner_link_callback() {
  $options = get_option( "tb_theme_options" );
?>

  <label id="banner-link-label">Enter the URL of the page you want the homepage banner to link to: </label>
  <input id="banner_link" name="tb_theme_options[banner_link]" value="<?php echo $options['banner_link']; ?>" type="text" />

<?php
}

// build out radio button list to select a city
function microsite_city_callback() {
  $options = get_option( "tb_theme_options" );

  // Get a list of cities currently in DB
  global $wpdb;

  $results = $wpdb->get_results( "select post_title from $wpdb->posts where post_type = 'city'", ARRAY_A );

  $html = '<select id="city_select" name="tb_theme_options[city]">';
  $html .= '<option value="none" ' . selected( $options['city'], '', false ) . '>No City</option>';
  foreach( $results as $result ) {
    $html .= '<option value="' . $result["post_title"] . '" ' . selected( $options['city'], $result["post_title"], false ) . '>' . $result["post_title"] . '</option>';
  }
  $html .= '</select>';

  echo $html;

}


// Function to build out the theme options page
function build_tb_theme_options() {

  settings_errors();

  ?>
  <form method="post" action="options.php" />

  <?php
    settings_fields( "tb_theme_options" );
    do_settings_sections( "tb_theme_options" );
    submit_button();
  ?>
  </form>
<?php
}

function tb_options_enqueue_scripts() {
  // Enqueue script that handles WP media uploader
  $script_url = get_template_directory_uri() . "/library/js/upload-media.js";
  wp_enqueue_script( "tb-upload-media", $script_url, array( "jquery" ) );

  wp_enqueue_media();
}
add_action( "admin_enqueue_scripts", "tb_options_enqueue_scripts" );



/* Commence Template related functions!
------------------------------------------*/
// function to build out a list of of shows using the standard "show preview" format
function display_shows ( $postID, $numPosts = 4, $topSeller = false, $offset = 0, $mobile = false ) {
  // what parameters might we need?

  if ( $_POST && isset($_POST['showData']) ) {

    $numPosts = 4;
    $topSeller = false;

    $postID = $_POST['showData']['postID'];
    $offset = $_POST['showData']['offset'];
  }

  // grab array of show IDs
  $showIDs = get_post_meta( $postID, 'shows', true );


  // establish total possible size of result set, use this to modulate offset
  $totalShows = count( $showIDs );
  /*echo "Total possible shows is $totalShows <br />";
  echo "Current offset is $offset <br />";
  echo "Requested number of shows is $numPosts <br />";*/

  // logic to set next and previous nav offset values
  if ( $numPosts < $totalShows) {
    //echo "Requested less than total<br />";
    if ( $offset == 0 ) {
      // We're at the beginning of the list
      $prevOffset = 0;
      $nextOffset = 1;
      //echo "Offset is zero, previous is $prevOffset and next is $nextOffset <br />";
    }
    if ( $offset+$numPosts == $totalShows ) {
      // We've hit the end of the list
      $nextOffset = $offset;
      $prevOffset = $offset - 1;
      //echo "End of list reached, previous is $prevOffset and next is $nextOffset <br />";
    }
    if ( $offset+$numPosts < $totalShows && $offset != 0 ) {
      $nextOffset = $offset + 1;
      $prevOffset = $offset - 1;
      //echo "We're in the middle of the list, previous is $prevOffset and next is $nextOffset <br />";
    }
  }

  // grab Show objects
  $args = array (
    "include" => $showIDs,
    "post_type" => "show",
    "posts_per_page" => $numPosts,
    "offset"  => $offset
  );
  if ( $topSeller ) {
    $args[ 'meta_key' ] = 'top_seller';
    $args[ 'meta_value' ] = 1;
  }
  $shows = get_posts( $args );

  if ( $mobile ) {
    return array( "shows" => $shows );
  }

  /*echo "<pre>";
  print_r( $shows );
  echo "</pre>";*/

  // start building out $html
  $cntr = 1;
  $html = "<div class='show-list'>";
  $html .= "<input type='hidden' id='post-id' value='" . $postID . "' />";
  // add previous shows nav
  if ( $totalShows > $numPosts ) {
    if ( $prevOffset == 0 && $nextOffset != 2 )
      $html .= '<div style="filter:grayscale(100%)">';
    
    $html .= "<a id='prev-shows-btn' ><input id='prev-shows-offset' value='" . $prevOffset . "' type='hidden' /><img src='" . get_template_directory_uri() . "/library/assets/icons/dotted-arrow.png' class='show-list-nav' /></a>";
    if ( $prevOffset == 0 && $nextOffset != 2 )
      $html .= "</div>";
  }
  foreach ( $shows as $show ) {
    if ( $cntr > $numPosts )
      break;
    $html .= "<div class='show-list-item'>";
    $html .= "<a href='" . get_permalink( $show ) . "'>";
    $html .= "<div class='show-poster dropshadow'>";
    if ( has_post_thumbnail( $show ) ) {
      $html .= get_the_post_thumbnail( $show, 'small' );
    } else {
      $html .= "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder' />";
    }
    $html .= "</div></a>";
    $html .= "<div class='show-title'><a href='" . get_permalink( $show ) . "'>" . $show->post_title . "</a></div>";
    $html .= "<a href='" . get_permalink( $show ) . "' ><div class='buy-tickets'>Buy Tickets</div></a>";
    $html .= "</div>";
    $cntr++;
  }
  // add next shows nav
  if ( $totalShows > $numPosts ) {
    if ( $nextOffset == $offset ) 
      $html .= '<div style="filter:grayscale(100%)">';

    $html .= "<a id='next-shows-btn' ><input id='next-shows-offset' value='" . $nextOffset . "' type='hidden' /><img src='" . get_template_directory_uri() . "/library/assets/icons/dotted-arrow.png' class='show-list-nav' /></a>";
    if ( $nextOffset == $offset )
      $html .= "</div>";
  }
  $html .= "</div>";

  // finally, let's echo out $html
  echo $html;

  if( $_POST && isset($_POST['showData']) ) {
    wp_die();
  }
}
add_action( "wp_ajax_nopriv_display_shows", "display_shows" );
add_action( "wp_ajax_display_shows", "display_shows" );

// function to spit out the start and end date of a given week (used for building out the event calendars)
function getStartEndDate( $week, $year ) {
  $dto = new DateTime();
  $dto->setISODate( $year, $week, 0 );
  $dates['start'] = $dto->format( "Y-m-d" );
  $dto->modify( "+6 days" ); // move forward one week
  $dates['end'] = $dto->format( "Y-m-d" );
  return $dates;
}

// function to fetch the "sunday date" of the calendar as it stands now
function getDates( $week = '', $month = '' ) {

  $year;
  $dates;
  // First up, let's check whether the above variables are set (which would indicate the user has interacted with the calendar)
  if ( $month == '' ) {
    if ( $week == '' ) {
      // ok!  Neither are set, so we're displaying the current week
      $date = new DateTime();
      $week = $date->format('W');
      $year = $date->format('Y');
      //$dateArr = getStartEndDate( $date->format('W'), $date->format('Y') );
    } else {
      // we're on a particular week!
      // note: add logic to handle swinging around to a new year
      $year = date('Y');
      //$dateArr = getStartEndDate( $week, date('Y') );
    }
  } else {
    // user has selected a month!
    $month += 1;
    $monthDateString = date('Y') . "-";
    $monthDateString .= $month . "-01";
    $date = new DateTime( $monthDateString ); // this should correspond to the first of the selected month
    /* TO DO: account for edge cases where year rolls over
    --------------------------------------------------------------------------------------------------------------------*/
    $week = $date->format('W');
    $year = $date->format('Y');
    //$dateArr = getStartEndDate( $date->format("W"), $date->format("Y") );
  }
  $startDate = new DateTime();
  $startDate->setISODate( $year, $week, 0 );

  $endDate = new DateTime();
  $endDate->setISODate( $year, $week, 0 );
  $endDate->modify( "+6 days" );

  $dates["start"] = $startDate;
  $dates["end"] = $endDate;

  return $dates;
}

// function to grab events based on a date range, venue, and performer
// accepts a show WP ID, venue WP ID, week (optional) or month (optional)
// defaults to spitting out the current week's events
function getShowEvents( $showID, $venueWPID='', $start, $end ) {
  // grab this Show's performer ID
  $perfID = get_post_meta( $showID, "performerID", true );

  $venueID;

  global $wpdb;
  // Put together the piece of the query that filters over performer ID
  $query = "SELECT * FROM " . $wpdb->prefix . "events WHERE performer = " . $perfID;

  // confirm whether venueID is set, if so, grab its API ID and add an additional filter to the query
  if ( $venueWPID != '' ) {
    $venueID = get_post_meta( $venueWPID, "venueID", true );

    $query .= " AND venue = " . $venueID;
  }

  $query .= " AND ( time >= '" . $start . " 00:00:00' AND time <= '" . $end . " 23:59:59' )";

  // Sort results by aescending event date
  $query .= " ORDER BY time ASC";
  // $events contains all the event objects
  $events = $wpdb->get_results( $query );

  return $events;
}

// function to handle events calendar ajax call
function handleCalendar( $showID, $dates=null, $venueWPID="", $mobile = false ) {
  $monthVal;
  if ( $dates == null ) {

    $showID = $_POST['data']['showID'];
    
    $monthVal = $_POST['data']['monthVal'];

    $venueWPID = $_POST['data']['venueVal'];
      
    $mobile = isset($_POST['data']['mobile'])? true : false;
    
    // grab "week" variable from $_POST, if set, use that to build start and end dates, else call "getDates"
    $week = $_POST['data']['week'];

    $today = new DateTime('today');
    // if there's no week value, and the selected month matches current month, choose current week
    if ( $week == "" && $today->format('n') - 1 == $monthVal ) {
      $start = new DateTime();
      $start->setISODate( $today->format('Y'), $today->format('W'), 0);
      $week = $start->format('Y-m-d');
    }

    //echo "Week is $week";
    if ( $week != '' ) {
      $trashDate = new DateTime( $week );
      $dates['start'] = new DateTime( $trashDate->format( 'Y-m-d' ) );
      $trashDate->modify( "+6 days" );
      $dates['end'] = new DateTime( $trashDate->format( 'Y-m-d' ) );
    } else if ( $week == '' ) {
      // check if current month matches selected month, select current week if so
      $today = new DateTime();
      if ( $today->format('n')-1 == $monthVal) {
        $week = $today->format('W');
        //printDat($week);
      }
      $dates = getDates( $week, $monthVal );
    }
    //var_dump($dates);
    //wp_die();
  }

  /*echo "<pre>";
  print_r($dates);
  echo "</pre>";*/

      // set previous and next week variables
  $prevWeek = new DateTime( $dates["start"]->format("Y-m-d") );
  $prevWeek->modify( "-1 week" );
  $nextWeek = new DateTime( $dates["start"]->format("Y-m-d") );
  $nextWeek->modify( "+1 week" );
    
  $fullEvents = getShowEvents( $showID, $venueWPID, $dates['start']->format( "Y-m-d" ), $dates['end']->format( "Y-m-d" ) );

    if($mobile){
        return array(
            'events' => $fullEvents,
            'week'  => $nextWeek
            );
    }
  /*echo "<pre>";
  print_r($fullEvents);
  echo "</pre>";*/

  // for each event, create a new array containing their ID, day of the week and time; push that into events array
  $events;
  foreach( $fullEvents as $event ) {
    $daDate = new DateTime( $event->time );
    $toInsert = array (
      "id"    => $event->id,
      "hour"  => $daDate->format( 'G' ),
      "minute"=> $daDate->format( 'i' ),
      "day"   => $daDate->format( 'w' )
    );
    $events[] = $toInsert;
  }


  // new DateTime to hold current start date...will be used to iterate over and populate the day headers without messing with the dates array
  $currWeek = new DateTime( $dates['start']->format("Y-m-d") );
  
  //start building out the HTML that will display the calendar
  $html = "<div class='cal-nav'><a id='prev-week-btn'><input type='hidden' id='prev-week' value='" . $prevWeek->format('Y-m-d') . "' /><img src='" . get_template_directory_uri() . "/library/assets/icons/dotted-arrow.png' /></a>";
  $html .= "<span id='date-range'>" . $dates['start']->format('M j') . " - " . $dates['end']->format( 'M j' ) . "</span>";
  $html .= "<a id='next-week-btn'><input type='hidden' id='next-week' value='" . $nextWeek->format('Y-m-d') . "' /><img src='" . get_template_directory_uri() . "/library/assets/icons/dotted-arrow.png' /></a></div>";
  $html .= "<table id='events-calendar'><tr class='days-heading'>";

  // while loop to create the day of the week headers (complete month/day indications)
  $cntr = 0;
  while( $cntr < 7 ) {
    $html .= "<td>" . $currWeek->format( 'D n/j' ) . "</td>";
    $currWeek->modify( '+1 day' );
    $cntr++;
  }
  $html .= "</tr>"; // finish off the day headings row

  // another loop, this one to populate in events on each day
  $cntr = 0;
  $html .= "<tr>";
  if ( isset( $events ) ) {
    while( $cntr < 7 ) {
      $html .= "<td>";

      // cycle through events array, grabbing any for current day (based on "day" value)
      foreach( $events as $event ) {

        $url = home_url( '/' ) . "tickets/?eventID=" . $event['id'];
        if( $event['day'] == $cntr ) {
          // build time variable, including converting from 24 hour to 12 hour time
          if ( $event['hour'] > 12 ) {
            $time = $event['hour']-12 . ":" . $event['minute'] . " PM";
          } else {
            $time = $event['hour'] . ":" . $event['minute'] . " AM";
          }
            
          $html .= "<a href='" . $url . "' class='show-time' id='" . $event['id'] . "' >" . $time . "</a>";
        }
      }
      $html .= "</td>";
      $cntr++;
    }
  } else {
    $html .= "<td colspan='7'>No events for this week</td>";
  }
  $html .= "</tr>";

  $html .= "<tr><td colspan='7' style='height: 50px'> </td></tr>";

  $html .= "</table>";


  echo $html;

  if( $_POST ) {
    wp_die();
  }
}
add_action( "wp_ajax_nopriv_add_calendar", "handleCalendar" );
add_action( "wp_ajax_add_calendar", "handleCalendar" );

// Pulls filter and search parameters from $_POST, then builds and spits out a WP_Query object
function getShowResults() {
  // first off, let's grab and initialize $_POST variables
  $spaged = 1;
  if ( isset($_POST['search_paged']) ){
      $spaged = intval($_POST['search_paged']);
  }
  if( isset( $_POST['search_tosearch'] ) ) {
    $toSearch = $_POST['search_tosearch'];
  }
  if ( isset($_POST['search_genre'] ) ) {
    $genre = $_POST['search_genre'];
  }
  if( isset( $_POST['search_city'] ) ) {
    $city = $_POST['search_city'];
  }
  if( isset( $_POST['search_month'] ) && $_POST['search_month'] != 'none' ) {
    $months = explode( ',', $_POST['search_month'] );
  }
  
  // Let's start populating the $args array for the query
  $args = array(
    'post_type' =>  'show',
    'posts_per_page'  =>  '12',
    'paged' => $spaged
  );

  // set Search parameter, if there's a search string yet
  if ( isset( $toSearch ) && $toSearch != "" ) {
    $args['s'] = $toSearch;
  }

  // build out array for passing into tax_query param, then add to $args
  if ( isset( $genre ) && $genre != "" ) {
    $genreArr = array(
      'taxonomy'  =>  'genre',
      'field'     =>  'slug',
      'terms'     =>  $genre
    );
    $args['tax_query'] = array( $genreArr );
  }

  // array holding any Show IDs coming from city filtering
  //$cityShowIDs = array();

  // next, we need to get a list of Show IDs (derived from any city and month values), and create an array of unique IDs
  if ( isset( $city ) && $city != "" ) {
    // grab "shows" post meta field and stuff into $showIDs
    $cityShowIDs = get_post_meta( $city, "shows", true );
  }

  // holds any Show IDs that come from month filtering
  //$wpShowIDs = array();

  // take array of months (as values), grab any Performer IDs with events happening during those months

  if ( isset( $months ) && $months !== "" && !empty($months[0])) {
    global $wpdb;

    //start building a query to select Performer IDs
    $monthQuery = "SELECT DISTINCT performer FROM " . $wpdb->prefix . "events WHERE Month(time) IN (";

    // build out "OR" block of months
    $cntr = 0;
    foreach( $months as $month ) {
      $cntr++;
      $monthQuery .= $month+1;
      if ( $cntr != count($months) ) {
        $monthQuery .= ",";
      }
    }
    $monthQuery .= ")";

    $performers = $wpdb->get_results( $monthQuery );

    // Now that we have an array of unique performers, grab the corresponding WP IDs
    $performersQuery = "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'performerID' AND meta_value IN (";
    // build "OR" block of Performer IDs
    $cntr = 0;
    foreach( $performers as $performer ) {
      $cntr++;
      $performersQuery .= $performer->performer;
      if ( $cntr != count($performers) ) {
        $performersQuery .= ",";
      }
    }
    $performersQuery .= ")";
    $wpShowResults = $wpdb->get_results( $performersQuery );
    // pull IDs out of results array and push into $wpShowIDs
    foreach ( $wpShowResults as $show ) {
      $wpShowIDs[] = $show->post_id;
    }
  }

  // final array of show IDs to be included in search results
  //$showIDs = array();
  
  if ( isset( $cityShowIDs ) && $cityShowIDs != "" ) {
    if ( isset( $wpShowIDs ) ) {
      // both filters have been applied, take the intersect
      $showIDs = array_intersect( $cityShowIDs, $wpShowIDs );
    } else {
      // only city filter is applied, just use that
      $showIDs = $cityShowIDs;
    }
  } elseif ( isset( $wpShowIDs ) ) {
    // only month filter is applied, use that
    
    $showIDs = $wpShowIDs;
  }

  // finally, if there are any IDs in $showIDs, add those to the $args param
  if ( isset( $showIDs ) ) {
    $args['post__in'] = $showIDs;
  }

  // instantiate our new Query, and return that bad boy
  $the_query = new WP_Query( $args );

  return $the_query;

}

function camelCase($str, array $noStrip = [])
{
  // non-alpha and non-numeric characters become spaces
  $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
  $str = trim($str);
  // uppercase the first character of each word
  $str = ucwords($str);
  $str = str_replace(" ", "", $str);
  $str = lcfirst($str);

  return $str;
}

function tickets_enqueue_scripts(){
  wp_enqueue_script( 'ticket-jquery-ui', get_template_directory_uri() . '/library/js/jquery-ui.min.js' );
  wp_enqueue_script( 'ticket-script', get_template_directory_uri() . '/library/js/tb-scripts.min.js' );
  wp_enqueue_script( 'ticket-handlebars', get_template_directory_uri() . '/library/js/handlebars.min.js' );
  wp_localize_script( 'ticket-script', 'ticket_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}



// function to register some new URL parameters as query vars
function tb_register_query_vars( $vars ) {
  $vars[] = "tosearch";
  $vars[] = "tab";
  $vars[] = "genre";
  $vars[] = "tosearch";

  return $vars;
}
add_filter( "query_vars", "tb_register_query_vars" );

function getEventResults( ) {
  $client = new SoapClient( WSDL );

  $params = array();
  $params[ 'websiteConfigID' ] = WEB_CONF_ID;
  //$toSearch = get_query_var( 'tosearch' );
  $toSearch = $_POST['data']['tosearch'];
  $params[ 'searchTerms' ] = $toSearch;
  $params[ 'whereClause' ] = 'ParentCategoryID == 3';


  // account for no search term or a single result

  $result = $client->__soapCall( 'SearchEvents', array( 'parameters' => $params ) );

  if (is_soap_fault($result))
    {
      echo '<h2>Fault</h2><pre>';
      print_r($result);
      echo '</pre>';
    }

    $eventResults  = $result->SearchEventResult->Event;

    foreach( $eventResults as $event ) {
      str_replace( "'", "&#39", $event->Name );
      str_replace( '"', "&#34", $event->Name );
    }

    // find something like handlebars to manipulate with javascript
    // use Javascript "map" function to pop out arrays
    //echo "<script>result = " . json_encode( $result->SearchEventsResult->Event ) . ";</script>";

    //return $result->SearchEventsResult->Event;
    header('Content-type: application/json');
    echo json_encode( $eventResults );
    die();
}
add_action( "wp_ajax_nopriv_get_event_results", "getEventResults" );
add_action( "wp_ajax_get_event_results", "getEventResults" );

function printDat( $toPrint ) {
  echo "<pre>";
  print_r( $toPrint );
  echo "</pre>";
}


function broadway_scripts(){
     //wp_enqueue_script('tb-js-script', get_template_directory_uri() . '/library/js/tb-scripts.js'); 
}


// we need to hook into a couple filters to make sure ACF taxonomy fields get saved into the Term Meta table
function acf_update_term_meta($value, $post_id, $field) {
  $term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
  if($term_id > 0)
    update_term_meta($term_id, $field['name'], $value);
  return $value;
}
add_filter('acf/update_value/name=dropdown_display', 'acf_update_term_meta', 10, 3);
add_filter('acf/update_value/name=include_filter', 'acf_update_term_meta', 10, 3);

function acf_load_term_meta($value, $post_id, $field) {
  $term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
  if($term_id > 0)
    $value = get_term_meta($term_id, $field['name'], true);
  return $value;
}
add_filter('acf/load_value/name=dropdown_display', 'acf_load_term_meta', 10, 3);
add_filter('acf/load_value/name=include_filter', 'acf_load_term_meta', 10, 3);
/* DON'T DELETE THIS CLOSING TAG */ ?>