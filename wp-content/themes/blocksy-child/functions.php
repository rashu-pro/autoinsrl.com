<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css');

  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), '1.0.5');
});

function check_login_and_redirect() {
  // Check if the user is logged in
  if (is_user_logged_in()) {
    // Redirect to the specific page
    wp_redirect(home_url('/dashboard/'));
    exit;
  }
}
add_shortcode('custom_login_redirect', 'check_login_and_redirect');

/**
 * Add a hidden page to execute necessary functions
 * @return void
 */
function add_hidden_dashboard_page(){
  add_submenu_page(
    null,
    'Hidden Dashboard Page',
    'Hidden Dashboard Page',
    'manage_options',
    'hidden-dashboard-page',
    'hidden_dashboard_page_content'
  );
}
add_action('admin_menu', 'add_hidden_dashboard_page');

function hidden_dashboard_page_content(){
  ?>
  <div class="" style="padding-bottom:20px;">
    <h2>Insert Users From CSV</h2>
    <form method="post">
      <input type="submit" name="insert_users_from_csv" class="button button-primary"
             value="Insert Users From CSV File">
    </form>

    <hr />
  </div>

  <?php
}

if(isset($_POST['insert_users_from_csv'])){
  echo "<div style='margin-left: 350px; margin-top: 50px;'>";
  create_users_from_csv(get_stylesheet_directory_uri().'/user_list.csv');
  echo "</div>";
}

/**
 * Create users from csv list
 * @param $csv_file_path
 * @return void
 */
function create_users_from_csv($csv_file_path) {
  // Open the CSV file
  if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
    // Skip the first row if it contains headers
    $header = fgetcsv($handle, 1000, ",");

    // Loop through the CSV file
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $first_and_last_name = get_first_and_last_name_from_name($data[0]);
      $user_data = array(
        'user_login' => $data[0],
        'user_email' => $data[1],
        'first_name' => $first_and_last_name['first_name'],
        'last_name'  => $first_and_last_name['last_name'],
        'role'       => 'customer'
      );

      // Check if the user already exists
      if ($user_data['user_email'] && !username_exists($user_data['user_login']) && !email_exists($user_data['user_email'])) {
        // Insert the user into the database
        $user_id = wp_insert_user($user_data);

        // Check for errors
        if (is_wp_error($user_id)) {
          echo 'Failed to create user ' . $user_data['user_login'] . ': ' . $user_id->get_error_message() . '<br>';
        } else {
          echo 'User ' . $user_data['user_email'] . ' created successfully.<br>';
        }
      } else {
        echo 'User ' . $user_data['user_email'] . ' already exists.<br>';
      }
    }
    // Close the file after processing
    fclose($handle);
  } else {
    echo 'Unable to open the CSV file.';
  }
}

/**
 * Get first and last name from name field
 * @param $name
 * @return array
 */
function get_first_and_last_name_from_name($name) {
  // Trim any excess white spaces from the name
  $name = trim($name);

  // Explode the name into parts using space as the delimiter
  $name_parts = explode(' ', $name);

  // If there are fewer than 2 parts, treat it as invalid or a single name
  if (count($name_parts) < 2) {
    return array(
      'first_name' => $name_parts[0],
      'last_name'  => ''
    );
  }

  // Assume the first word is the first name and the rest is the last name
  $first_name = array_shift($name_parts);
  $last_name = implode(' ', $name_parts);

  return array(
    'first_name' => $first_name,
    'last_name'  => $last_name
  );
}
