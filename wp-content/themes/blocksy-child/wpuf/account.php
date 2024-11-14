<?php
if (is_user_logged_in()) {
  $current_user = wp_get_current_user();
  if ( in_array( 'administrator', $current_user->roles ) || in_array('mechanic', $current_user->roles) ) {
    wp_redirect(home_url('/wp-admin/'));
  }
}
?>
<style>
  .customer-breadcrumb{
    background-color: #f8f8f8;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .d-flex{
    display: flex;
  }
  .align-center{
    align-items: center;
  }
  .justify-between{
    justify-content: space-between;
  }
  .m-0{
    margin-bottom: 0;
  }
  a.link-black{
    color: #000;
  }
  .customer-portal-title-wrapper{
    margin-top: 15px;
    margin-bottom: 20px;
  }
  .customer-portal-title{
    color: #000;
    font-size: 24px;
  }
  .customer-portal-title-wrapper .customer-portal-title{
    padding-right: 20px;
    display: inline-block;
    background-color: #fff;
    margin: 0;
  }
  .sec-heading-border{
    height: 1px;
    background-color: #E3E1E1;
  }
  .customer-portal-title-wrapper .sec-heading-border{
    width: 100%;
    position: absolute;
    top: 50%;
    z-index: -1;
    transform: translateY(-50%);
  }
  .p-relative{
    position: relative;
  }
  .maintenance-wrapper{
    border: 1px solid #DEDBDB;
    margin-bottom: 60px;
  }
  .maintenance-wrapper .maintenance-head{
    background-color: #F4F6F6;
    padding: 10px 30px;
  }

  .maintenance-wrapper .vehicle-name{

  }
  .vehicle-details-wrapper{
    padding: 10px 30px;
  }
  .brand-name{
    font-size: 18px;
  }
  .brand-title{

  }
  .brand-name{
    font-weight: 600;
  }
  .vehicle-details-wrapper .divider-wrapper{
    margin: 15px 0;
  }
  .vehicle-details-wrapper .divider-line{
    height: 1px;
    background-color: #E3E1E1;
  }
  .maintenance-history-wrapper{
    padding: 0 30px 30px;
  }
  .maintenance-history-wrapper ul{
    padding: 0 0 0 0;
  }
  .maintenance-history-wrapper ul li{
    margin-bottom: 35px;
    list-style: none;
    position: relative;
    color: #000;
  }
  .maintenance-history-wrapper ul li .date-maintenance{
    display: inline-block;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
  }
  .maintenance-history-wrapper ul li .date-maintenance img{
    display: inline-block;
    max-width: 13px;
    position: relative;
    top: -2px;
  }

  .title-maintenance{
    font-size: 20px;
    font-weight: 600;
    margin-top: -5px;
  }
  .date-maintenance{
    opacity: 0.8;
    font-weight: 500;
    text-transform: uppercase;
  }
  .empty-maintenance-block{
    margin-top: 20%;
  }
  .empty-maintenance-block .icon-holder img{
    max-width: 150px;
    margin: 0 auto;
  }
  .empty-maintenance-block .help-text{
    text-align: center;
    color: #3E3A3A;
    margin-top: 35px;
  }

  @media (min-width: 768px){
    .vehicle-brand{
      min-width: 250px;
    }
    .vehicle-model{
      min-width: 250px;
    }
    .customer-portal-title-wrapper{
      margin-top: 30px;
    }
    .empty-maintenance-block .icon-holder img{
      max-width: 232px;
    }
    .customer-portal-title{
      font-size: 28px;
    }
    .customer-portal-title-wrapper .customer-portal-title{
      padding-right: 50px;
    }
    .empty-maintenance-block{
      margin-top: 50px;
    }
  }

  @media (max-width: 992px){
    .customer-email-block{
      display: none;
    }
    .customer-portal-title-wrapper .sec-heading-border{
      display: none;
    }
  }
</style>

<div class="my-account-wrapper">
  <?php
  if (is_user_logged_in()) {
    ?>
    <div class="customer-portal-wrapper">
      <!-- Breadcrumb -->
      <div class="customer-breadcrumb">
        <div class="ct-container">
          <div class="d-flex align-center justify-between">
            <div class="">
              <h4 class="m-0"><a href="#" class="link-black">Dashboard</a> </h4>
            </div>

            <div class="customer-email-block">
              <a href="#" class="link-black"><?php echo $current_user->user_email ?></a>
            </div>
          </div>
        </div>
      </div>

      <div class="customer-portal-body">
        <!-- Customer portal content title -->
        <div class="customer-portal-title-wrapper">
          <div class="ct-container">
            <div class="p-relative">
              <h3 class="customer-portal-title"> Maintenance History</h3>

              <div class="sec-heading-border"></div>
            </div>
          </div>
        </div>

        <!-- Show maintenance history -->
        <?php
        $current_user_id = get_current_user_id();

        // Query to get vehicles linked to the logged-in user
        $args = array(
          'post_type' => 'vehicle',
          'post_status' => 'publish',
          'posts_per_page' => -1,
          'meta_query' => array(
            array(
              'key' => 'customer_name',
              'value' => $current_user_id,
              'compare' => '='
            )
          )
        );

        $vehicle_query = new WP_Query($args);
        ?>

        <?php if ($vehicle_query->have_posts()) : ?>
          <?php while ($vehicle_query->have_posts()) : $vehicle_query->the_post(); ?>
            <div class="ct-container">
              <div class="maintenance-wrapper">
                <div class="maintenance-head">
                  <h4 class="m-0 vehicle-name"><?php the_title() ?></h4>
                </div>

                <!-- Vehicle Details -->
                <div class="vehicle-details-wrapper">
                  <div class="d-flex">
                    <div class="vehicle-brand">
                      <p class="m-0 brand-title">BRAND</p>
                      <p class="m-0 brand-name"> <?php the_field('brand') ?> </p>
                    </div>

                    <div class="vehicle-model">
                      <p class="m-0 brand-title">Model</p>
                      <p class="m-0 brand-name"> <?php the_field('model') ?> </p>
                    </div>

                    <div class="vehicle-license">
                      <p class="m-0 brand-title">LICENSE</p>
                      <p class="m-0 brand-name"> <?php the_field('license_plate') ?> </p>
                    </div>
                  </div>
                  <div class="divider-wrapper">
                    <div class="divider-line"></div>
                  </div>
                </div>

                <!-- Maintenance history -->
                <div class="maintenance-history-wrapper">
                  <?php
                  $args = array(
                    'post_type' => 'maintenance_record',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                      array(
                        'key' => 'vehicle',
                        'value' => get_the_ID(),
                        'compare' => '='
                      )
                    ),
                    'orderby' => 'date_of_maintenance',
                    'order' => 'ASC'
                  );

                  $maintenance_query = new WP_Query($args);
                  ?>

                  <?php if ($maintenance_query->have_posts()) : ?>
                    <ul>
                      <?php $counter = 1; ?>
                      <?php while ($maintenance_query->have_posts()) : $maintenance_query->the_post(); ?>
                        <?php
                        $mechanic_details = get_field('mechanic_name');
                        ?>
                        <li>
                          <p class="m-0 date-maintenance">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/calendar.png" alt="date">
                            <?php the_field('date_of_maintenance') ?>
                          </p>
                          <p class="title-maintenance m-0"> <?php echo $counter ?>. <?php the_title() ?> </p>
                          <?php if(get_field('km')): ?>
                            <div style="margin-top: 10px">
                              <strong>KM:</strong> <?php the_field('km'); ?>
                            </div>
                          <?php endif; ?>
                          <p class="m-0"> <?php the_field('descriptionnotes') ?> </p>
                        </li>
                        <?php $counter++ ?>
                      <?php endwhile; ?>
                    </ul>
                    <?php wp_reset_postdata(); ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
        <div class="empty-maintenance">
          <div class="empty-maintenance-block">
            <div class="icon-holder">
              <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/empty-folder.png" alt="no maintenance" />
            </div>
            <p class="help-text">No maintenance record found!</p>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  <?php
  }
  ?>
</div>
