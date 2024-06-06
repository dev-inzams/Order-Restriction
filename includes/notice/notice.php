<?php

function custom_cart_notice() {
    // Minimum order amount get from settings
    $minimum_amount = get_option( 'ortn_minimum_amount', 150 );
    
    // Calculate total cart amount
    $cart_total = WC()->cart->get_cart_contents_total();
    if ( $cart_total < $minimum_amount ) {
      ?>
      <div class="<?php echo esc_attr(get_option('ortn_select_norice_type', 'woocommerce-message')); ?> ">
      <i class="fas <?php echo esc_attr(get_option('ortn_icon_class', 'fa-shopping-cart')); ?>"></i>
      <?php echo esc_attr(get_option('ortn_alert_message', 'Minimum order amount is 150.')); ?>
      </div>
      <?php
    }    
  }
  add_action('woocommerce_before_cart', 'custom_cart_notice', 10 );

?>