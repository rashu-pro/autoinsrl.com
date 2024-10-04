<?php
/*
Template Name: My Account
*/

get_header(); // Include the custom header

// Start the WordPress loop
if ( have_posts() ) :
  while ( have_posts() ) : the_post();
    // Add your custom content here
    ?>
    <div class="my-account-page">
      <!-- Insert the WP User Frontend dashboard shortcode -->
      <?php echo do_shortcode('[wpuf_account]'); ?>
    </div>
  <?php
  endwhile;
endif;

get_footer(); // Include the footer
