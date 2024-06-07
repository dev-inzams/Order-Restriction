<?php
/**
 * @package   Order Restriction
 * @author    Tech Nuxt <technuxt@gmail.com>
 * @license   GPL-2.0+
 * @link       https://technuxt.com  
 * @copyright 2014 Tech Nuxt
 * 
 * @wordpress-plugin
 * Plugin Name:       Order Restriction
 * Plugin URI:        https://wordpress.org/plugins/order-restriction/
 * Description:       Restrict order to specific amount and create notice for this amount.hello
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Tested up to:      5.2
 * Author:            Tech Nuxt
 * Author URI:        https://technuxt.com
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://wordpress.org/plugins/order-restriction/
 * Text Domain:       ortn
 * Domain Path:       /languages
 * Requires Plugin:   woocommerce
 */

 //  include css and js for user
  function ortn_enqueue_style() {
    // enqueue css
    wp_enqueue_style( 'ortn-user-style', plugin_dir_url(__FILE__) . '/public/css/ortn-style.css', array(), '1.0.0', 'all' );
    // enqueue font awesome
    wp_enqueue_style( 'ortn-user-font-awesome', plugin_dir_url( __FILE__ ) . '/public/css/fontawesome.css', array(), '6.5.2', 'all' );
    wp_enqueue_style( 'ortn-user-font-awesome-all', plugin_dir_url( __FILE__ ) . '/public/css/all.css', array(), '6.5.2', 'all' );
    
    // script
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'ortn-user-script', plugin_dir_url(__FILE__) . '/public/js/ortn-main.js' , array(), '1.0.0', 'all' );
  
  }
  add_action( 'wp_enqueue_scripts', 'ortn_enqueue_style' );

  //  include css and js for admin
  function ortn_enqueue_style_admin($hook) {
    // get current page url
    $current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
    // enqueue css
    if ( $current_page != 'ortn-order-restriction') {
      return;
    }
    wp_enqueue_style( 'ortn-admin-bootstrap', plugin_dir_url( __FILE__ ) . '/admin/css/ortn-bootstrap.css', array(), '5.3.3', 'all' );
    wp_enqueue_style( 'ortn-admin-font-awesome-all', plugin_dir_url(__FILE__) . '/admin/css/all.css', array(), '6.5.2', 'all' );
    wp_enqueue_style( 'ortn-admin-font-awesome', plugin_dir_url(__FILE__) . '/admin/css/fontawesome.css', array(), '6.5.2', 'all' );
    wp_enqueue_style( 'ortn-admin-style', plugin_dir_url(__FILE__) . '/admin/css/ortn-style.css' );

    // script
    if(!wp_enqueue_script( 'jquery' )){
      wp_enqueue_script( 'ortn-admin-jquery-js', 'https://code.jquery.com/jquery-3.5.1.slim.min.js' );
    }

    wp_enqueue_script( 'ortn-admin-script', plugin_dir_url(__FILE__) . '/admin/js/ortn-main.js' , array(), '1.0.0', 'all' ); 
    wp_enqueue_script( 'ortn-admin-bootstrap-js', plugin_dir_url( __FILE__ ) . '/admin/js/ortn-bootstrap.js', array(), '5.3.3', 'all' );
    wp_enqueue_script( 'ortn-admin-bootstrap-js-bundle', plugin_dir_url( __FILE__ ) . '/admin/js/ortn-bootstrap.bundle.js', array(), '5.3.3', 'all' );  
  }
  add_action( 'admin_enqueue_scripts', 'ortn_enqueue_style_admin' );




  // Disable checkout page for orders under $150
  include 'includes/vat/vat-restriction.php';


  // include custom notice
  include 'includes/notice/notice.php';


  

// plugin customization setting
add_action( 'admin_menu', 'ortn_admin_menu' );
function ortn_admin_menu() {
    add_menu_page( 'Order Restriction', 'Order Restriction', 'manage_options', 'ortn-order-restriction', 'ortn_admin_page', 'dashicons-clipboard', 110 );
}

function ortn_admin_page() {
?>
  <section class="ortn-section mt-5">
    <div class="container">
      <div class="row">
            <!-- left side -->
          <div class="col-8">
            <h1>Order Restriction</h1>
            <p>Restrict order to specific amount.</p>


            <form action="options.php" method="post">
                <?php wp_nonce_field('update-options'); ?>

                <div class="form-group bg-light p-3 mb-3">
                    <label for="name">Alert Message</label>
                    <input type="text" id="ortn_alert_message" name="ortn_alert_message" value="<?php echo esc_attr(get_option('ortn_alert_message', 'Minimum order amount is 150.')); ?>">
                </div>        

                <!-- notice icon -->
                <div class="form-group orth-icon bg-light p-3 mb-3"> 
                  <div class="row">
                      <div class="col-6">
                          <span>Select icon</span>
                            <i id="iconClassshow" class="fas <?php echo esc_attr(get_option('ortn_icon_class', 'fa-shopping-cart')); ?>"></i>
                            <input value="<?php echo esc_attr(get_option('ortn_icon_class', 'fa-shopping-cart')); ?>" type="hidden" class="form-control" id="iconClassInput" name="ortn_icon_class">
                      </div>
                      <div class="col-6 text-end">
                            <button type="button" class="button button-primary" id="showIconsBtn">Show Icons</button>
                        </div>
                  </div>
                </div>
                <!-- icons notice end -->

                <!-- The Modal -->
                <div class="modal fade" id="iconsModal" tabindex="-1" aria-labelledby="iconsModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="iconsModalLabel">Font Awesome Icons</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                              <label for="iconClassInput">Selected Icon</label>
                          </div>
                          <div class="row" id="iconsContainer">
                                            <!-- Icons will be loaded here dynamically -->
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- modal end -->

                <div class="form-group bg-light p-3 mb-3">
                  <div class="row">
                      <div class="col-6">
                          <label for="ortn_select_norice_type">Notice Type</label>
                        </div>
                      <div class="col-6 text-right">
                        <select id="ortn_select_norice_type" name="ortn_select_norice_type">
                            <option value="ortn-message-warning" <?php selected(get_option('ortn_select_norice_type'), 'ortn-message-warning'); ?>>Warning</option>
                            <option value="ortn-message-danger" <?php selected(get_option('ortn_select_norice_type'), 'ortn-message-danger'); ?>>Danger</option>
                            <option value="orth-default-message" <?php selected(get_option('ortn_select_norice_type'), 'orth-default-message'); ?>>WooCommerce</option>
                        </select>
                      </div>
                  </div>
                </div>

                <div class="form-group bg-light p-3 mb-3">
                    <label for="ortn_minimum_amount">Minimum order amount</label>
                    <input type="number" id="ortn_minimum_amount" name="ortn_minimum_amount" value="<?php echo esc_attr(get_option('ortn_minimum_amount', 150)); ?>">
                </div>

                        <!-- update SQL function area -->
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="page_options" value="ortn_minimum_amount,ortn_alert_message,ortn_select_norice_type,ortn_icon_class">
                        <input type="submit" value="Save" class="button button-primary mt-2">
            </form>
          </div>

                <!-- right side -->
            <div class="col-4">
                  <div class="image-container mb-3">
                      <img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/technuxt-logo.png'; ?>" alt="Placeholder Image">
                  </div>
                  <p class="description">🌟 Welcome to Technuxt! 🌟 At Technuxt, we're more than just a software company - we're your partners in digital innovation. 💻📱 Our mission? To craft cutting-edge web and mobile solutions that empower businesses to thrive in the digital age. With a passion for innovation and a commitment to excellence, we blend creativity with technology to bring your ideas to life. From elegant mobile apps to robust web platforms, we specialize in creating bespoke software solutions tailored to your unique needs. Join us on this exciting journey as we push the boundaries of possibility and redefine the future of technology together. Let's innovate, inspire, and transform the digital landscape, one line of code at a time. Welcome to Technuxt - where innovation knows no bounds! 🚀✨</p>
                  <div class="buttons">
                      <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="inzams" data-color="#FFDD00" data-emoji=""  data-font="Cookie" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff" ></script>
                  </div>
            </div> 
            
            
      </div>
    </div>  
  </section>

<?php   
} //ortn_admin_page


 // plugin redirect
 register_activation_hook( __FILE__, 'ortn_plugin_activation' );
 function ortn_plugin_activation(){
   add_option('orth_plugin_do_active', true);
 }

 add_action( 'admin_init', 'orth_plugin_redirect');
 function orth_plugin_redirect(){
   if(get_option('orth_plugin_do_active', false)){
     delete_option( 'orth_plugin_do_active' );
     if(!isset($_GET['active-multi'])){
       wp_safe_redirect( admin_url('admin.php?page=ortn-order-restriction') );
       exit;
     }
   }
 }


?>