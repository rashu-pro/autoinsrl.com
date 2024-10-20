<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css');

  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), '1.0.2');
});

function custom_login_redirect( $redirect_to, $request, $user ) {
  // Check if the user is logged in and has a valid user object
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    if ( in_array( 'administrator', $user->roles ) || in_array('mechanic', $user->roles) ) {
      return admin_url();
    } else {
      return home_url( '/dashboard/' );
    }
  }

  return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );

