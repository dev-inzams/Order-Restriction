<?php
/**
 * Plugin Name: Order Restriction
 * Plugin URI: https://wordpress.org/plugins/order-restriction/
 * Description: Restrict order to specific amount.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Tested up to: 5.2
 * Stability: Stable
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 5.6
 * Author: Tech Nuxt
 * Author URI: https://technuxt.com
 * Text Domain: ortn
 * Update URI: https://wordpress.org/plugins/order-restriction/
 * Domain Path: /languages
 */

 //  include css
  function ortn_enqueue_style() {
    wp_enqueue_style( 'ortn-user-style', plugin_dir_url(__FILE__) . 'css/user-panel/ortn-user-style.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'ortn-admin-style', plugin_dir_url(__FILE__) . 'css/admin-panel/ortn-admin-style.css' );
  }
  add_action( 'wp_enqueue_scripts', 'ortn_enqueue_style' );

  //  include css for admin
  function ortn_enqueue_style_admin() {
    wp_enqueue_style( 'ortn-admin-style', plugin_dir_url(__FILE__) . 'css/admin-panel/ortn-admin-style.css' );
  }
  add_action( 'admin_enqueue_scripts', 'ortn_enqueue_style_admin' );

  // include js
  function ortn_enqueue_script() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'ortn-user-script', plugin_dir_url(__FILE__) . 'js/user-panel/ortn-user-main.js' , array(), '1.0.0', true );
    wp_enqueue_script( 'ortn-admin-script', plugin_dir_url(__FILE__) . 'js/admin-panel/ortn-admin-main.js' , array(), '1.0.0', true );

  }
  add_action( 'wp_enqueue_scripts', 'ortn_enqueue_script' );


  // Disable checkout page for orders under $150
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





  function custom_cart_notice() {
    // Minimum order amount get from settings
    $minimum_amount = get_option( 'ortn_minimum_amount', 150 );
    
    // Calculate total cart amount
    $cart_total = WC()->cart->get_cart_contents_total();
    if ( $cart_total < $minimum_amount ) {
      echo '<div class="woocommerce-message">This is a custom notice on the cart page.</div>';
    }    
  }
  add_action('woocommerce_before_cart', 'custom_cart_notice', 10 );







// plugin customization setting
add_action( 'admin_menu', 'ortn_admin_menu' );
function ortn_admin_menu() {
    add_menu_page( 'Order Restriction', 'Order Restriction', 'manage_options', 'ortn', 'ortn_admin_page', 'dashicons-clipboard', 110 );
}

function ortn_admin_page() {
    // include 'admin-panel/ortn-admin-page.php';
    ?>
  <section class="ortn-section">
    <div class="left-side">
        <h1>Order Restriction</h1>
        <p>Restrict order to specific amount.</p>


        <form action="options.php" method="post">
        <?php wp_nonce_field( 'update-options' ); ?>
          <div class="input-group">
              <label for="name">Alert Message</label>
              <input type="text" id="name" name="ortn_alert_message">
              <input type="hidden" id="ortn_alert_message" name="ortn_alert_message" value="<?php echo get_option('ortn_alert_message'); ?>">
          </div>
          <div class="input-group">
              <label for="ortn_minimum_amount">Minimum order amount</label>
              <input type="number" id="ortn_minimum_amount" name="ortn_minimum_amount" value="<?php echo get_option( 'ortn_minimum_amount', 150 ) ?>">
          </div>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options" value="ortn_minimum_amount">
            <input type="submit" value="Save" class="button button-primary">
        </form>
    </div>


    <!-- right side -->
    <div class="right-side">
        <div class="image-container">
            <img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/technuxt-logo.png'; ?>" alt="Placeholder Image">
        </div>
        <p>🌟 Welcome to Technuxt! 🌟 At Technuxt, we're more than just a software company - we're your partners in digital innovation. 💻📱 Our mission? To craft cutting-edge web and mobile solutions that empower businesses to thrive in the digital age. With a passion for innovation and a commitment to excellence, we blend creativity with technology to bring your ideas to life. From elegant mobile apps to robust web platforms, we specialize in creating bespoke software solutions tailored to your unique needs. Join us on this exciting journey as we push the boundaries of possibility and redefine the future of technology together. Let's innovate, inspire, and transform the digital landscape, one line of code at a time. Welcome to Technuxt - where innovation knows no bounds! 🚀✨</p>
        <div class="buttons">
            <button class="btn-primary">Primary Button</button>
            <button class="btn-secondary">Secondary Button</button>
        </div>
    </div>
  </section>
  <?php
    
}


?>