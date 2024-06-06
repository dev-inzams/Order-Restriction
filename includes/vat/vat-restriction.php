<?php

function disable_checkout_under_minimum() {
    // Minimum order amount get from settings
    $minimum_amount = get_option( 'ortn_minimum_amount', 150 );
    
    // Calculate total cart amount
    $cart_total = WC()->cart->get_cart_contents_total();
  
    // If cart total is less than minimum amount, redirect to custom page
    if ( $cart_total < $minimum_amount && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
        echo "<script>alert('Minimum order amount is $minimum_amount. Your current cart total is $cart_total.'); history.go(-1);</script>";
        exit;
    }
}
add_action( 'template_redirect', 'disable_checkout_under_minimum' );


?>