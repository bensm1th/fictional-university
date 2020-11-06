<?php

function bullseyeLikeRoutes() {
  register_rest_route('bullseye/v1', 'manageLike', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route('bullseye/v1', 'manageLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

add_action('rest_api_init', 'bullseyeLikeRoutes');

function createLike($data) {

  if(is_user_logged_in()) {
    //w
    $id = sanitize_text_field($data['professorId']);
    $existQuery = new WP_Query(array(
      'post_type' => 'like',
      'author' => get_current_user_id(),
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $id
        )
      )
    ));
    if($existQuery->found_posts == 0 AND get_post_type($id) == 'professor') {
      return wp_insert_post(array(
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => 'final php like',
        'meta_input' => array(
          'liked_professor_id' => $id
        )
      ));
    } else {
      die('Invalid professor ID');
    }
  } else {
    die("only logged in users can create a like");
  }
}

function deleteLike($data) {
  $likeId = sanitize_text_field($data['like']);
  
  if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
    wp_delete_post($likeId, true);
    return 'Congrats, like deleted.';
  } else {
    die("You do not have permission to delete that.");
  }
}

 ?>
