<?php

/*
 * Plugin Name: WPCheckin
 * Plugin URI: http://beyondisolation.com/wpcheckin
 * Description: Add geo checkin feature to WordPress.
 * Version: 1.0.1
 * Author: Adam Sommer
 * Author URI: http://beyondisolation.com
 * License: GPL2
*/

// Assign global variables.
$plugin_url = WP_PLUGIN_URL ."/wpcheckin";
$options = array();
$display_json = true;
$dev_mode = false;

// Add link to plugin in the Admin menu.
// Under Settings > Checkin

function wpcheckin_menu() {
  // Use the add_options_page function.
  // add_options_page($page_title, $menu_title, $capability, $menu-slug, $function)a
  add_options_page('Checkin', 'Checkin', 'manage_options', 'wpcheckin', 'wpcheckin_options_page');
}

add_action('admin_menu', 'wpcheckin_menu');

/*
 * 
 * Setup the Admin page for the plugin.
 *
*/
function wpcheckin_options_page() {
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permission to access this page.');
  }

  global $plugin_url;
  global $options;
  global $display_json;
  global $current_user;

  //error_log(print_r($_POST, true));
  $options = get_option('wpcheckin');
  if ($options != '' and isset($options['wpcheckin_key'])) {
    $wpcheckin_key = $options['wpcheckin_key'];
  } else {
    $wpcheckin_key = '';
  }

  get_currentuserinfo();
  if (isset($current_user)) {
    $options['user'] = $current_user->user_firstname ." ". $current_user->user_lastname;
    update_option('wpcheckin', $options);
  } else {
    $options['user'] = "Anonymous User";
  }

  // Handle the new Device form.
  if (isset($_POST['wpcheckin_form_submitted'])) {
    $hidden_field = esc_html($_POST['wpcheckin_form_submitted']);

    if ($hidden_field == 'Y') {
      $wpcheckin_key = esc_html($_POST['wpcheckin_key']);

      $options['wpcheckin_key'] = $wpcheckin_key;
      $options['last_updated'] = time();

      update_option('wpcheckin', $options);
    }
  }

  // Handle the location form.
  if (isset($_POST['wpcheckin_loc_submitted'])) {
    //error_log(print_r($_POST, true));
    if (esc_html($_POST['wpcheckin_loc_submitted']) == "Y") {
      $options['wpcheckin_lat'] = esc_html($_POST['wpcheckin_lat']);
      $options['wpcheckin_lon'] = esc_html($_POST['wpcheckin_lon']);
      $options['last_updated'] = time();
      update_option('wpcheckin', $options);
    }
  }

  if (isset($_POST['wpcheckin_places_submitted'])) { 
    $wpcheckin_places = wpcheckin_get_places(array($options['wpcheckin_lat'], $options['wpcheckin_lon']));
  }

  require('inc/options-page-wrapper.php');
}

function wpcheckin_shortcode($atts, $content = null) {
  global $post;
  global $current_user;

  get_currentuserinfo();
  if (isset($current_user)) {
    $options['user'] = $current_user->user_firstname ." ". $current_user->user_lastname;
    update_option('wpcheckin', $options);
  } else {
    $options['user'] = "Anonymous User";
  }
  //global $options;

  //extract(shortcode_atts(array('checkin' => ''), $atts));

  $options = get_option('wpcheckin');
  if (isset($_POST['wpcheckin_places_submitted'])) { 
    $wpcheckin_places = wpcheckin_get_places(array($options['wpcheckin_lat'], $options['wpcheckin_lon']));
  }

  // Set widget variables.
  $before_widget = '';
  $before_title = $title = $after_title = '';
  $after_widget = '';

  ob_start();

  require('inc/front-end.php');

  $content = ob_get_clean();

  return $content;
}

add_shortcode('wpcheckin', 'wpcheckin_shortcode');

/*
 *
 * Get Places information from Google.
 *
*/
function wpcheckin_get_places($location) {
  global $dev_mode;
  $options = get_option('wpcheckin');

  $base_url = "https://maps.googleapis.com/maps/api/place/radarsearch/json?";
  //"wpcheckin_lat: "36.2181086", "wpcheckin_lon": "-81.6635562"
  $location = "location=". $location[0] .",". $location[1];
  //$location = "location=36.2181086,-81.6635562";

  if (isset($options['wpcheckin_key'])) {
    $key = "&key=". $options['wpcheckin_key'];
  } else {
    $key = '';
  }

  if (isset($dev_mode) or isset($display_json)) {
    $radius = "&radius=50";
  } else {
    $radius = "&radius=10";
  }
  $params = "&types=establishment|gym|health|school|plumber|park|movie_theater|store|grocery_or_supermarket|department_store|casino|art_gallery|atm|airport|food|restaurant|university|liquor_store|night_club|bar|subway_station&sensor=true";

  $places_url = $base_url . $location . $radius . $params . $key;

  $args = array("timeout" => 120);

  // Use data in options table if in dev mode.
  if ($dev_mode and isset($options['wpcheckin_places_feed'])) {
    $places_feed = $options['wpcheckin_places_feed'];
    //echo gettype($places_feed[0]);
    $places_feed[0]->status = 'OK';
    $places_status = "OK";
  } else {
    error_log('Getting feed from Google Places...');
    $places_feed = wp_remote_get($places_url, $args);

    //var_dump($places_feed);
    //echo "<br/><br/>";
    $places_feed = json_decode($places_feed['body']);
    $places_status = $places_feed->status;
  }

  // If error from Google setup an empty $places_feed object.
  if ($places_status == 'REQUEST_DENIED' or $places_status == 'OVER_QUERY_LIMIT' or $places_status == 'ZERO_RESULTS') {
    $places_feed = json_decode(json_encode(array(
      "id" => "",
      "formatted_address" => "",
      "formatted_phone_number" => "",
      "website" => "",
      "status" => "error",
    )));
  }

  //var_dump($places_feed);

  // If no Google error get the details of each place.
  if ($places_status != 'REQUEST_DENIED' and $places_status != 'OVER_QUERY_LIMIT' or $places_status == 'ZERO_RESULTS') {
    if (!$dev_mode) {

      // Get deets from Google.
      $deets = get_feed_details($places_feed);
      $deets[0]->status = "OK";
    } else if ($dev_mode and !isset($options['wpcheckin_places_feed'])) {
      // Add the place details into the options table if it's not set and dev mode is on.
      $deets = get_feed_details($places_feed);
      
      error_log('Saving places into options table...');
      $options['wpcheckin_places_feed'] = $deets;
      update_option('wpcheckin', $options);
    } else {  
      // Dev mode deets.
      error_log('Dev mode places feed...');
      $deets = $places_feed;
    }
  } else {
    // Google error deets.
    $deets = array($places_feed);
  }
  //error_log(print_r($deets, true));
  return $deets;
}

function get_feed_details($feed) {
  $refs = array();

  for ($i = 0; $i < 10; $i++) {
    if (isset($feed->results[$i]->reference)) {
      array_push($refs, $feed->results[$i]->reference);
    } 
    $i++;
  }

  $args = array("timeout" => 120);
  
  $deets = array();
  foreach ($refs as $ref) {
  
    $deets_url =  "https://maps.googleapis.com/maps/api/place/details/json?reference=". $ref .
                  "&sensor=true&key=AIzaSyA3tcWGZTNkYV4BrPXpug_idUjnWfc6Ymk";
    $deets_feed = wp_remote_get($deets_url, $args);
    array_push($deets, json_decode($deets_feed['body'])->result);
  }

  return $deets;

}

function wpcheckin_get_location() {
  wp_enqueue_script('get_location.js', plugins_url('wpcheckin/js/get_location.js'), array('jquery'), '.1', false);
}

add_action('admin_menu', 'wpcheckin_get_location');

function wpcheckin_refresh() {
  $options = get_option('wpcheckin');

  //error_log(print_r($_POST, true));

  $options['wpcheckin_lat'] = esc_html($_POST['wpcheckin_lat']);
  $options['wpcheckin_lon'] = esc_html($_POST['wpcheckin_lon']);
  $options['last_updated'] = time();

  if (isset($_POST['wpcheckin_places_submitted'])) { 
    $wpcheckin_places = wpcheckin_get_places(array($options['wpcheckin_lat'], $options['wpcheckin_lon']));
    require('inc/places-list.php');
  }
  update_option('wpcheckin', $options);

  die();
}

add_action('wp_ajax_wpcheckin_refresh', 'wpcheckin_refresh');

function wpcheckin_post_checkin() {
  $options = get_option('wpcheckin');
 
  //error_log(print_r($_POST, true));

  // Setup the check post content.
  $content = "<strong>". $options['user'] ."</strong> Checkedin from: <br/>". esc_html($_POST['name']);
  $content .= "<br/>". esc_html($_POST['address']) ."<br/>". esc_html($_POST['phone']) ."<br/>";
  if (isset($_POST['website'])) {
    $content .= "<a target='_blank' href='". esc_html($_POST['website']) ."'>". esc_html($_POST['website']) ."</a><br/>";
  }

  date_default_timezone_set('America/New_York');

  // Create checkin post object.
  $new_checkin = array(
    'post_title'    => 'Checkin '. date('m-d-Y H:i', time()),
    'post_content'  => $content,
    'post_status'   => 'publish',
    'post_category' => array(0),
    'post_type' => 'wpcheckin',
  );

  //error_log($content);

  // Insert the post into the database
  $new_post_id = wp_insert_post($new_checkin);

  echo $content;

  //error_log($new_post_id);
  die();
}

add_action('wp_ajax_wpcheckin_post_checkin', 'wpcheckin_post_checkin');

function wpcheckin_enable_frontend_ajax() {
?>

  <script>
    var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
  </script>

<?php
}

add_action('wp_head', 'wpcheckin_enable_frontend_ajax');

function wpcheckin_backend_styles() {
  wp_enqueue_style('wpcheckin_backend_css', plugins_url('wpcheckin/wpcheckin.css'));
}

add_action('admin_head', 'wpcheckin_backend_styles');

function wpcheckin_frontend_styles() {
  wp_enqueue_style('wpcheckin_frontend_css', plugins_url('wpcheckin/wpcheckin.css'));
  wp_enqueue_script('wpcheckin_frontend_js', plugins_url('wpcheckin/js/wpcheckin.js'), array('jquery'), '', false);
}

add_action('wp_enqueue_scripts', 'wpcheckin_frontend_styles');

/*
 *
 * Setup the Widget. 
 *
*/
class WPCheckin_Widget extends WP_Widget {

  function wpcheckin_widget() {
    // Instantiate the parent object
    //parent::__construct( false, 'WPCheckin' );
    parent::__construct(
      'wpcheckin_widget', // Base ID
      __('WPCheckin', 'text_domain'), // Name
      array( 'description' => __( 'Checkin your location using Google Places and HTML5.', 'text_domain' ), ) // Args
    );
  }

  function widget( $args, $instance ) {
    // Widget output
    extract($args);
    $title = apply_filters('widget_title_hook', $instance['title']);

    $options = get_option('wpcheckin');

    global $current_user;

    get_currentuserinfo();
    if (isset($current_user)) {
      $options['user'] = $current_user->user_firstname ." ". $current_user->user_lastname;
      update_option('wpcheckin', $options);
    } else {
      $options['user'] = "Anonymous User";
    }

    if (isset($_POST['wpcheckin_places_submitted'])) { 
      $wpcheckin_places = wpcheckin_get_places(array($options['wpcheckin_lat'], $options['wpcheckin_lon']));
    }

    //var_dump($wpcheckin_places);

    require('inc/front-end.php');
  }

  function update( $new_instance, $old_instance ) {
    // Save widget options
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);

    return $instance;
  }

  function form( $instance ) {
    // Output admin widget options form
    if (isset($instance['title'])) {
      $title = esc_attr($instance['title']);
    } else {
      $title = "WPCheckin:";
    }

    require('inc/widget-fields.php');
  }
}

function wpcheckin_register_widgets() {
  register_widget( 'WPCheckin_Widget' );
}

add_action( 'widgets_init', 'wpcheckin_register_widgets' );

/*
 *
 * Create the checkin Post type.
 *
*/
function wpcheckin_post_type() {
  register_post_type( 'wpcheckin',
    array(
      'labels' => array(
        'name' => __( 'Checkins' ),
        'singular_name' => __( 'Checkin' )
      ),
      'public' => true,
      'has_archive' => false,
    )
  );
}

add_action( 'init', 'wpcheckin_post_type' );

?>

