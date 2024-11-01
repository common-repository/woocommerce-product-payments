<?php

/**
* Plugin Name: Payment gateway per Product for WooCommerce
* Plugin URI: https://www.dreamfoxmedia.com/project/woocommerce-payment-gateway-per-product-premium/
* Description: Extend WooCommerce plugin to add different payments methods to a product
* Version: 3.5.5
 * WC tested up to: 7.8
 * WC tested up to: 9.2.3
* Author: Dreamfox
* Author URI: www.dreamfoxmedia.com
* Text Domain: dfm-payment-gateway-per-product-for-woocommerce
* Domain Path: /languages/
* @Developer : Hoang Xuan Hao / Marco van Loghum Slaterus ( Dreamfoxmedia )
*/
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
define( 'DFM_PGPPFW__FILE__', __FILE__ );
define( 'DFM_PGPPFW_PAYMENT_META_KEY', 'payments' );
require_once dirname( __FILE__ ) . '/inc/functions.php';
require_once dirname( __FILE__ ) . '/inc/settings.php';
if ( function_exists( 'dfm_pgppfw_fs' ) ) {
    dfm_pgppfw_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'dfm_pgppfw_fs' ) ) {
        // Create a helper function for easy SDK access.
        function dfm_pgppfw_fs() {
            global $dfm_pgppfw_fs;
            if ( !isset( $dfm_pgppfw_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_4167_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_4167_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $dfm_pgppfw_fs = fs_dynamic_init( array(
                    'id'              => '4167',
                    'slug'            => 'dfm-payment-gateway-per-product-for-woocommerce',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_5a51c11bf6bf5275ffda4baf7fbaa',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Premium',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'all',
                    'navigation'      => 'tabs',
                    'menu'            => array(
                        'slug'        => 'dfm-pgppfw',
                        'contact'     => true,
                        'support'     => false,
                        'affiliation' => false,
                        'parent'      => array(
                            'slug' => 'woocommerce',
                        ),
                    ),
                    'is_live'         => true,
                ) );
            }
            return $dfm_pgppfw_fs;
        }

        // Init Freemius.
        dfm_pgppfw_fs();
        // Signal that SDK was initiated.
        do_action( 'dfm_pgppfw_fs_loaded' );
    }
}
dfm_pgppfw_fs()->add_filter( 'hide_account_tabs', 'dfm_pgppfw_hide_account_tabs' );
function dfm_pgppfw_hide_account_tabs() {
    return true;
}

/**
* For multi Network
*/
if ( !function_exists( 'is_plugin_active_for_network' ) || !function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
/**
* Check is free plugin is installed then we will deactivate free first
*/
//if ( is_plugin_active( 'woocommerce-product-payments/woocommerce-payment-gateway-per-product.php' ) ) {
//    deactivate_plugins( 'woocommerce-product-payments/woocommerce-payment-gateway-per-product.php' );
//}
/**
* Check if WooCommerce is active
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && !function_exists( 'softsdev_product_payments_settings' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    // Global variable to hold notice data
    global $softsdev_notice_data;
    /* ----------------------------------------------------- */
    if ( isset( $_GET['product-payment-ignore-notice'] ) && $_GET['product-payment-ignore-notice'] == 1 ) {
        update_option( 'product-payment-ignore-notice', '1' );
    }
    function product_payment_ignore_notice() {
        if ( isset( $_GET['product-payment-ignore-notice'] ) ) {
            update_option( 'product_payments_alert', 1 );
        }
    }

    add_action( 'admin_init', 'product_payment_ignore_notice' );
    // Submenu on woocommerce section
    add_action( 'admin_menu', 'softsdev_product_payments_submenu_page' );
    /* ----------------------------------------------------- */
    add_action( 'admin_enqueue_scripts', 'softsdev_product_payments_enqueue' );
    /* ----------------------------------------------------- */
    function softsdev_product_payments_submenu_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Product Payment', 'dfm-payment-gateway-per-product-for-woocommerce' ),
            __( 'Product Payment', 'dfm-payment-gateway-per-product-for-woocommerce' ),
            'manage_options',
            'dfm-pgppfw',
            'softsdev_product_payments_settings'
        );
    }

    function softsdev_product_payments_enqueue() {
        wp_enqueue_style( 'softsdev_product_payments_enqueue', plugin_dir_url( __FILE__ ) . '/css/style.css' );
        wp_register_script( 'dd_setting_script', plugins_url( '/js/setting.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'dd_setting_script' );
        $data_to_pass = array(
            'base_url' => get_bloginfo( 'url' ),
        );
        wp_localize_script( 'dd_setting_script', 'dd_settings_data', $data_to_pass );
    }

    /**
     *
     * @param string $text
     * @return string
     */
    function softsdev_product_payments_footer_text(  $text  ) {
        if ( isset( $_GET['page'] ) && strpos( plugin_basename( wp_unslash( $_GET['page'] ) ), 'dfm-pgppfw' ) === 0 ) {
            $text = '<a href="https://www.dreamfoxmedia.com" target="_blank">www.dreamfoxmedia.com</a>';
        }
        return $text;
    }

    /**
     *
     * @param string $text
     * @return string
     */
    function softsdev_product_payments_update_footer(  $text  ) {
        if ( isset( $_GET['page'] ) && strpos( plugin_basename( wp_unslash( $_GET['page'] ) ), 'dfm-pgppfw' ) === 0 ) {
            $text = 'Version 3.3.1';
        }
        return $text;
    }

    /**
     * Type: updated,error,update-nag
     */
    if ( !function_exists( 'softsdev_notice' ) ) {
        function softsdev_notice(  $message, $type  ) {
            ?>
            <div class="<?php 
            echo $type;
            ?> notice">
            <p><?php 
            echo $message;
            ?></p>
            </div>
            <?php 
        }

    }
    /**
     *
     */
    /**
     * Setting form of product payment
     */
    add_action( 'add_meta_boxes', 'wpp_meta_box_add' );
    /**
     *
     */
    function wpp_meta_box_add() {
        global $post;
        if ( isset( $post->ID ) && is_product_eligible( $post->ID ) ) {
            add_meta_box(
                'payments',
                'Choose payment gateway',
                'wpp_payments_form',
                'product',
                'side',
                'core'
            );
        }
    }

    /**
     * Get product payment method
     * 
     * @param int Product Id
     * @return array List of payment method ids
     */
    function wpp_get_product_payment_meta(  $product_id  ) {
        $payments = get_post_meta( $product_id, DFM_PGPPFW_PAYMENT_META_KEY, true );
        $payments = ( !is_array( $payments ) ? [] : $payments );
        return $payments;
    }

    /**
     *
     * @global type $post
     * @global WC_Payment_Gateways $woo
     */
    function wpp_payments_form() {
        global $woo, $post;
        $woo = new WC_Payment_Gateways();
        $postPayments = wpp_get_product_payment_meta( $post->ID );
        $payments = $woo->payment_gateways;
        foreach ( $payments as $pay ) {
            if ( apply_filters( 'softsdev_show_disabled_gateways', false ) || $pay->enabled === 'no' ) {
                continue;
            }
            $checked = '';
            if ( is_array( $postPayments ) && in_array( $pay->id, $postPayments ) ) {
                $checked = ' checked="yes" ';
            }
            ?>
        <input type="checkbox" <?php 
            echo $checked;
            ?> value="<?php 
            echo $pay->id;
            ?>" name="pays[]" id="payment_<?php 
            echo $pay->id;
            ?>" />
        <label for="payment_<?php 
            echo $pay->id;
            ?>"><?php 
            echo $pay->title;
            ?></label>
        <br />
        <?php 
        }
    }

    add_action(
        'save_post',
        'wpp_meta_box_save',
        10,
        2
    );
    /**
     *
     * @param type $post_id
     * @param type $post
     * @return type
     */
    function wpp_meta_box_save(  $post_id, $post  ) {
        // Restrict to save for autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || isset( $_REQUEST['action'] ) && sanitize_title( $_REQUEST['action'] ) != 'editpost' ) {
            return $post_id;
        }
        // Restrict to save for revisions
        if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
            return $post_id;
        }
        if ( get_post_type() === 'product' ) {
            delete_post_meta( $post_id, DFM_PGPPFW_PAYMENT_META_KEY );
            $productIds = get_option( 'woocommerce_product_apply', array() );
            if ( is_array( $productIds ) && !in_array( $post_id, $productIds ) ) {
                $productIds[] = $post_id;
                update_option( 'woocommerce_product_apply', $productIds );
            }
            $payments = array();
            if ( isset( $_POST['pays'] ) ) {
                $post_payments = array_filter( array_map( 'sanitize_title', $_POST['pays'] ) );
            } else {
                $post_payments = [];
            }
            if ( $post_payments ) {
                foreach ( $post_payments as $pay ) {
                    $payments[] = $pay;
                }
            }
            if ( count( $payments ) ) {
                update_post_meta( $post_id, DFM_PGPPFW_PAYMENT_META_KEY, $payments );
            } else {
                delete_post_meta( $post_id, DFM_PGPPFW_PAYMENT_META_KEY );
            }
        }
    }

    /**
     *
     *
     *
     * @global type $woocommerce
     * @param type $available_gateways
     * @return type
     */
    function wpppayment_gateway_disable_country(  $available_gateways  ) {
        global $woocommerce;
        $arrayKeys = array_keys( $available_gateways );
        /**
         * default setting
         */
        $softsdev_wpp_plugin_settings = get_option( 'sdwpp_plugin_settings', array(
            'softsdev_selected_cats' => '',
            'default_payment'        => '',
        ) );
        $default_payment = unserialize( $softsdev_wpp_plugin_settings['default_payment'] );
        $is_default_pay_needed = false;
        foreach ( $available_gateways as $gateway_id => $gateway ) {
            // check by categories
            if ( dfm_per_categories_enabled() ) {
                $included_cats = dfm_per_categories_include_get_option( $gateway_id );
                if ( dfm_per_categories_do_disable( $included_cats, true ) ) {
                    unset($available_gateways[$gateway_id]);
                    continue;
                }
                $excluded_cats = dfm_per_categories_exclude_get_option( $gateway_id );
                if ( dfm_per_categories_do_disable( $excluded_cats ) ) {
                    unset($available_gateways[$gateway_id]);
                    continue;
                }
            }
            // check by tags
            if ( dfm_per_tags_enabled() ) {
                $included_tags = dfm_per_tags_include_get_option( $gateway_id );
                if ( dfm_per_tags_do_disable( $included_tags, true ) ) {
                    unset($available_gateways[$gateway_id]);
                    continue;
                }
                $excluded_tags = dfm_per_tags_exclude_get_option( $gateway_id );
                if ( dfm_per_tags_do_disable( $excluded_tags ) ) {
                    unset($available_gateways[$gateway_id]);
                    continue;
                }
            }
        }
        /**
         * checking all cart products
         */
        if ( is_object( $woocommerce->cart ) ) {
            $items = $woocommerce->cart->cart_contents;
            $itemsPays = '';
            if ( is_array( $items ) ) {
                foreach ( $items as $item ) {
                    // check by products
                    if ( !is_product_eligible( $item['product_id'] ) ) {
                        continue;
                    }
                    $itemsPays = get_post_meta( $item['product_id'], DFM_PGPPFW_PAYMENT_META_KEY, true );
                    if ( is_array( $itemsPays ) && count( $itemsPays ) ) {
                        foreach ( $arrayKeys as $key ) {
                            if ( array_key_exists( $key, $available_gateways ) && !in_array( $available_gateways[$key]->id, $itemsPays ) ) {
                                if ( $default_payment == $key ) {
                                    $is_default_pay_needed = true;
                                    $default_payment_obj = $available_gateways[$key];
                                    unset($available_gateways[$key]);
                                } else {
                                    unset($available_gateways[$key]);
                                }
                            }
                        }
                    }
                }
            }
            /**
             * set default payment if there is none
             */
            if ( $is_default_pay_needed && count( $available_gateways ) == 0 ) {
                $available_gateways[$default_payment] = $default_payment_obj;
            }
        }
        return $available_gateways;
    }

    add_filter( 'woocommerce_available_payment_gateways', 'wpppayment_gateway_disable_country' );
    function softsdev_product_payments_settings() {
        wp_enqueue_style( 'softsdev_select2_css', plugins_url( '/vendor/select2/css/select2.min.css', __FILE__ ) );
        wp_enqueue_script( 'softsdev_select2_js', plugins_url( '/vendor/select2/js/select2.min.js', __FILE__ ) );
        $categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ) );
        $softsdev_wpp_plugin_settings = get_option( 'sdwpp_plugin_settings', array(
            'softsdev_selected_cats' => '',
            'default_payment'        => '',
        ) );
        if ( isset( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] ) && !empty( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] ) ) {
            $softsdev_selected_cats = unserialize( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] );
        } else {
            $softsdev_selected_cats = array();
        }
        ob_start();
        ?>

        <select class="js-softsdev_selected_cats" name="sdwpp_setting[softsdev_selected_cats][]" multiple="multiple" style="width: 100%;">
            <?php 
        foreach ( $categories as $category ) {
            ?>
                <option value="<?php 
            echo $category->term_id;
            ?>"<?php 
            echo ( in_array( $category->term_id, $softsdev_selected_cats ) ? ' selected="selected"' : '' );
            ?>><?php 
            echo $category->name;
            ?></option>
            <?php 
        }
        ?>
        </select>
        <p>You can select any 2 categories for this functionality due to free plugin.</p>

        <script>
            (function($) {
                var softsdev = {
                    select2: function() {
                        $('.js-softsdev_selected_cats').select2({
                            maximumSelectionLength: 2
                        });
                    },
                }

                $(document).ready(function() {
                    for (var func in softsdev) {
                        if (softsdev[func] instanceof Function) {
                            softsdev[func]();
                        }
                    }
                });
            })(jQuery);
        </script>
        <?php 
        $additional_html = ob_get_clean();
        softsdev_product_payments_settings_part( $additional_html );
    }

    add_action( 'init', 'softsdev_product_payments_save_settings' );
    function softsdev_product_payments_save_settings() {
        global $softsdev_notice_data;
        if ( isset( $_POST['sdwpp_setting'] ) ) {
            update_option( 'sdwpp_plugin_settings', array_filter( array_map( 'serialize', $_POST['sdwpp_setting'] ) ) );
            // Set the message and type in a global variable
            $softsdev_notice_data = array(
                'message' => 'Woocommerce Payment Gateway per Product setting is updated.',
                'type'    => 'updated',
            );
            add_action( 'admin_notices', 'softsdev_display_notice' );
        }
    }

    function softsdev_display_notice() {
        global $softsdev_notice_data;
        // Check if notice data is set before displaying
        if ( isset( $softsdev_notice_data['message'] ) && isset( $softsdev_notice_data['type'] ) ) {
            softsdev_notice( $softsdev_notice_data['message'], $softsdev_notice_data['type'] );
        }
    }

    /**
     *
     * @param type $product_id
     * @return boolean
     */
    function is_product_eligible(  $product_id  ) {
        // Product object
        $product_object = wc_get_product( $product_id );
        if ( !$product_object || $product_object->post_type != 'product' ) {
            return false;
        }
        $softsdev_wpp_plugin_settings = get_option( 'sdwpp_plugin_settings', array(
            'softsdev_selected_cats' => '',
            'default_payment'        => '',
        ) );
        if ( isset( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] ) && !empty( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] ) ) {
            $softsdev_selected_cats = unserialize( $softsdev_wpp_plugin_settings['softsdev_selected_cats'] );
        } else {
            $softsdev_selected_cats = array();
        }
        if ( $softsdev_selected_cats ) {
            $is_eligible = false;
            // Get visiblity
            $current_visibility = $product_object->get_catalog_visibility();
            // Get Category Ids
            $cat_ids = wp_get_post_terms( $product_id, 'product_cat', array(
                'fields' => 'ids',
            ) );
            // Convert saved array in to list
            $softsdev_selected_cats = ( is_array( $softsdev_selected_cats ) ? $softsdev_selected_cats : array($softsdev_selected_cats) );
            foreach ( $cat_ids as $cat_id ) {
                if ( in_array( $cat_id, $softsdev_selected_cats ) ) {
                    $is_eligible = true;
                    break;
                }
            }
            // check visiblity in array or now define
            if ( $is_eligible && in_array( $current_visibility, array('catalog', 'visible') ) ) {
                $is_eligible = true;
            } else {
                $is_eligible = false;
            }
            // return eligiblity
            return $is_eligible;
        }
        return false;
    }

    add_action( 'init', 'softsdev_product_payments_save_per_categories' );
    function softsdev_product_payments_save_per_categories() {
        if ( isset( $_POST['dfm_per_categories'] ) ) {
            $enabled = ( isset( $_POST['dfm_per_categories_enable'] ) ? 1 : 0 );
            update_option( 'dfm_per_categories_enable', $enabled );
            $available_gateways = WC()->payment_gateways->payment_gateways();
            foreach ( $available_gateways as $gateway_id => $gateway ) {
                $field_include = dfm_per_categories_include_field_name( $gateway_id );
                $field_exclude = dfm_per_categories_exclude_field_name( $gateway_id );
                update_option( $field_include, ( isset( $_POST[$field_include] ) ? $_POST[$field_include] : [] ) );
                update_option( $field_exclude, ( isset( $_POST[$field_exclude] ) ? $_POST[$field_exclude] : [] ) );
            }
            // Set the message and type in a global variable
            $softsdev_notice_data = array(
                'message' => 'Woocommerce Payment Gateway per categories is updated.',
                'type'    => 'updated',
            );
            add_action( 'admin_notices', 'softsdev_display_notice' );
        }
    }

    add_action( 'init', 'softsdev_product_payments_save_per_tags' );
    function softsdev_product_payments_save_per_tags() {
        if ( isset( $_POST['dfm_per_tags'] ) ) {
            $enabled = ( isset( $_POST['dfm_per_tags_enable'] ) ? 1 : 0 );
            update_option( 'dfm_per_tags_enable', $enabled );
            $available_gateways = WC()->payment_gateways->payment_gateways();
            foreach ( $available_gateways as $gateway_id => $gateway ) {
                $field_include = dfm_per_tags_include_field_name( $gateway_id );
                $field_exclude = dfm_per_tags_exclude_field_name( $gateway_id );
                update_option( $field_include, ( isset( $_POST[$field_include] ) ? $_POST[$field_include] : [] ) );
                update_option( $field_exclude, ( isset( $_POST[$field_exclude] ) ? $_POST[$field_exclude] : [] ) );
            }
            // Set the message and type in a global variable
            $softsdev_notice_data = array(
                'message' => 'Woocommerce Payment Gateway per tags is updated.',
                'type'    => 'updated',
            );
            add_action( 'admin_notices', 'softsdev_display_notice' );
        }
    }

}