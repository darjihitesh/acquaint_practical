<?php
/*
Plugin Name: AGC Payment Gateway
Description: AGC payment gateway plugin
Author: Hitesh Darji
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* Check whether WooCommerce plugin activated */

if (! class_exists( 'woocommerce' ) ) { 
    function wpb_admin_notice_warn() {
    echo '<div class="notice notice-warning is-dismissible">
            <p>Important: WooCommerce plugin should be activated to run AGC Payment Plugin.</p>
          </div>'; 
    return false; 
    add_action( 'admin_notices', 'wpb_admin_notice_warn' );
    }
}
 

/**
 * AFC Payment Gateway.
 *
 * Provides a AGC Payment Gateway, mainly for testing purposes.
 */
add_action('plugins_loaded', 'init_custom_gateway_class');
function init_custom_gateway_class(){

    class AGC_Gateway extends WC_Payment_Gateway {

        public $domain;

        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->domain = 'agc_payment';

            $this->id                 = 'agc';
            $this->icon               = apply_filters('woocommerce_custom_gateway_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'AGC', $this->domain );
            $this->method_description = __( 'Allows payments with AGC gateway.', $this->domain );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title        = $this->get_option( 'title' );
            $this->description  = $this->get_option( 'description' );
            $this->instructions = $this->get_option( 'instructions', $this->description );
            $this->order_status = $this->get_option( 'order_status', 'completed' );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
        }

        /**
         * Initialise Gateway Settings Form Fields.
         */
        public function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __( 'Enable/Disable', $this->domain ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable AGC Payment', $this->domain ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title'       => __( 'Title', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', $this->domain ),
                    'default'     => __( 'AGC Payment', $this->domain ),
                    'desc_tip'    => true,
                ),
                'order_status' => array(
                    'title'       => __( 'Order Status', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether status you wish after checkout.', $this->domain ),
                    'default'     => 'wc-on-hold',
                    'desc_tip'    => true,
                    'options'     => wc_get_order_statuses()
                ),
                'description' => array(
                    'title'       => __( 'Description', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', $this->domain ),
                    'default'     => __('Payment Information', $this->domain),
                    'desc_tip'    => true,
                ),
                'instructions' => array(
                    'title'       => __( 'Instructions', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', $this->domain ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            );
        }

        /**
         * Output for the order received page.
         */
        public function thankyou_page() {
            if ( $this->instructions )
                echo wpautop( wptexturize( $this->instructions ) );
        }

        /**
         * Add content to the WC emails.
         *
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            if ( $this->instructions && ! $sent_to_admin && 'agc' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
            }
        }

        public function payment_fields(){

            if ( $description = $this->get_description() ) {
                echo wpautop( wptexturize( $description ) );
            }

            ?>
            <div id="custom_input">
                <p class="form-row form-row-wide">
                    <label for="mobile" class=""><?php _e('Mobile Number', $this->domain); ?></label>
                    <input type="text" class="" name="mobile" id="mobile" placeholder="" value="">
                </p>
                <p class="form-row form-row-wide">
                    <label for="email" class=""><?php _e('Email Address', $this->domain); ?></label>
                    <input type="email" class="" name="email" id="email" placeholder="" value="">
                </p>
            </div>
            <?php
        }

        /**
         * Process the payment and return the result.
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

            $order = wc_get_order( $order_id );

            $status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;

            // Set order status
            $order->update_status( $status, __( 'AGC Payment', $this->domain ) );
            $order->add_order_note('AGC awaiting cheque payment');

            // Reduce stock levels if any
            $order->reduce_order_stock();

            // Remove cart
            WC()->cart->empty_cart();

            // Return thankyou redirect
            return array(
                'result'    => 'success',
                'redirect'  => $this->get_return_url( $order )
            );
        }
    }
}

add_filter( 'woocommerce_payment_gateways', 'add_agc_gateway_class' );
function add_agc_gateway_class( $methods ) {
    $methods[] = 'AGC_Gateway'; 
    return $methods;
}

add_action('woocommerce_checkout_process', 'process_custom_payment');
function process_custom_payment(){

    if($_POST['payment_method'] != 'agc')
        return;

    if( !isset($_POST['mobile']) || empty($_POST['mobile']) )
        wc_add_notice( __( 'Please add your mobile number', 'WP Practical' ), 'error' );


    if( !isset($_POST['email']) || empty($_POST['email']) )
        wc_add_notice( __( 'Please add your email address', 'WP Practical' ), 'error' );

}

/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'custom_payment_update_order_meta' );
function custom_payment_update_order_meta( $order_id ) {

    if($_POST['payment_method'] != 'agc')
        return;

    update_post_meta( $order_id, 'mobile', $_POST['mobile'] );
    update_post_meta( $order_id, 'email', $_POST['email'] );
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'custom_checkout_field_display_admin_order_meta', 10, 1 );
function custom_checkout_field_display_admin_order_meta($order){
    $method = get_post_meta( $order->id, '_payment_method', true );
    if($method != 'agc')
        return;

    $mobile = get_post_meta( $order->id, 'mobile', true );
    $email = get_post_meta( $order->id, 'email', true );

    echo '<p><strong>'.__( 'Mobile Number' ).':</strong> ' . $mobile . '</p>';
    echo '<p><strong>'.__( 'Email Address').':</strong> ' . $email . '</p>';
}

/* Add note if status to completed */
add_action( 'woocommerce_order_status_completed', 'action_on_order_status_completed', 20, 2 );
function action_on_order_status_completed( $order_id, $order ){
 
  if($order->get_payment_method() == 'agc'){
        // Note for AGC gateway
        $note = __("Cheque payment completed");
        $order->add_order_note( $note );
  }
}