<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function bullseye_custom_rest() {
  register_rest_field('post', 'authorName', array(
    'get_callback' => function() { return get_the_author();}
  ));
}

add_action('rest_api_init', 'bullseye_custom_rest');

  function pageBanner($args = NULL) {

    if(!$args['title']) {
      $args['title'] = get_the_title();
    }

    if(!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']) {
      if(get_field('page_banner_background_image')) {
      //if(get_field('page_banner_background_image' AND !is_archive() AND !is_home())) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">

        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>

<?php  }

  function bullseye_files() {
    wp_enqueue_style('custom-google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyDNkDvRVg74tlOajMOV_FJzlRHAsJUGAlc', NULL, '1.0', true);

    if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
      wp_enqueue_script('main-bullseye-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
      wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
      wp_enqueue_script('main-bullseye-js', get_theme_file_uri('/bundled-assets/scripts.f4f0107eb7f7cd8abbb5.js'), NULL, '1.0', true);
      wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.3e5342f4dcd325ce82ae.css'));
    }

    wp_localize_script('main-bullseye-js', 'bullseyeData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
  }

  add_action('wp_enqueue_scripts', 'bullseye_files');

  function bullsseye_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    //arguments are: a nickname for the image size, pixel width, pixel height, cropped (default false)
    //for last value, to crop better, pass in an array
    //since images aren't the same, not a good feature, just get centered images
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
  }

  add_action('after_setup_theme', 'bullsseye_features');

  function bullseye_adjust_queries($query) {

    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
      $query->set('posts_per_page', -1);
    }


    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);
    }

    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
      $today = date('Ymd');

      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        )
      ));

    }
  }

  add_action('pre_get_posts', 'bullseye_adjust_queries');

function bullseyeMapKey($api) {
  $api['key'] = 'AIzaSyDNkDvRVg74tlOajMOV_FJzlRHAsJUGAlc';
  return $api;
}

  add_filter('acf/fields/google_map/api', 'bullseyeMapKey');

  //redirect subscriber accounts out of admin and onto homepage

  function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }
  add_action('admin_init', 'redirectSubsToFrontend');

  function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      show_admin_bar(false);
    }
  }
  add_action('wp_loaded', 'noSubsAdminBar');

function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

  add_filter('login_headerurl', 'ourHeaderUrl');

function ourLoginCSS() {
  wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.3e5342f4dcd325ce82ae.css'));
  wp_enqueue_style('custom-google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

//f4f0107eb7f7cd8abbb5
//wp-content/themes/bullseyetest/bundled-assets/styles.3e5342f4dcd325ce82ae.css
}

  add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginTitle() {
  return get_bloginfo('name');
}

add_filter('login_headertitle', 'ourLoginTitle');

// force note posts to be openssl_get_privatekey

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if ($data['post_type'] == 'note') {
    if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
      die("You have reached your note limit.");
    }

    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_text_field($data['post_title']);
  }

  if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
    $data['post_status'] = "private";
  }

  return $data;
}

?>
