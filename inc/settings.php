<?php

function softsdev_product_payments_settings_part(  $additional_html = ''  ) {
    wp_enqueue_style( 'softsdev_select2_css', plugins_url( '/vendor/select2/css/select2.min.css', DFM_PGPPFW__FILE__ ) );
    wp_enqueue_script( 'softsdev_select2_js', plugins_url( '/vendor/select2/js/select2.min.js', DFM_PGPPFW__FILE__ ) );
    wp_register_script( 'dd_horztab_script', plugins_url( '/js/dd_horizontal_tabs.js', DFM_PGPPFW__FILE__ ) );
    wp_enqueue_script( 'dd_horztab_script' );
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap fs-section fs-full-size-wrapper wrap-mc-paid" id="dd-wc-product-payments"><div id="icon-tools" class="icon32"></div>';
    $setting_url = get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=dfm-pgppfw';
    ?>
  <h2 class="nav-tab-wrapper" id="settings">
    <a href="<?php 
    echo $setting_url;
    ?>" class="nav-tab fs-tab nav-tab-active home">Settings</a>
  </h2>
  <h2 class="title"><?php 
    echo __( 'Woocommerce Product Payments', 'dfm-payment-gateway-per-product-for-woocommerce' );
    ?></h2>

  <div class="left-dd-paid ">
    <div class="left_box_container">
      <ul class="horz_tabs">
        <li <?php 
    if ( !isset( $_GET['tab'] ) ) {
        ?> class="active" <?php 
    }
    ?> id="payment_information">
          <a href="javascript:;">Information</a>
        </li>
        <li id="payment_settings" <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_settings' ) {
        ?>class="active" <?php 
    }
    ?>>
          <a href="javascript:;">Settings</a>
        </li>
        <li id="payment_per_categories" <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_per_categories' ) {
        ?>class="active" <?php 
    }
    ?>>
          <a href="javascript:;">Per Categories</a>
        </li>
        <li id="payment_per_tags" <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_per_tags' ) {
        ?>class="active" <?php 
    }
    ?>>
          <a href="javascript:;">Per Tags</a>
        </li>
        <li id="payment_newsletter" <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_newsletter' ) {
        ?>class="active" <?php 
    }
    ?>>
          <a href="javascript:;">Newsletter</a>
        </li>
        <li id="payment_faq">
          <a href="javascript:;">FAQ</a>
        </li>
        <li id="payment_support" <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_support' ) {
        ?>class="active" <?php 
    }
    ?>>
          <a href="<?php 
    echo admin_url( 'admin.php?page=dfm-pgppfw-contact' );
    ?>">Support</a>
        </li>
        <li id="payment_dfmplugins">
          <a href="javascript:;">DFM Plugins</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="right-dd-paid ">
    <div id="tab_payment_information" class="postbox <?php 
    if ( !isset( $_GET['tab'] ) ) {
        ?>active<?php 
    }
    ?>" style="padding: 10px; margin: 10px 0px;">
      <?php 
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap wrap-mc-paid"><div id="icon-tools" class="icon32"></div></div>';
    echo '<h2 class="title">' . __( 'Woocommerce Product Payments - Information', 'dfm-payment-gateway-per-product-for-woocommerce' ) . '</h2>';
    ?>
      <img src="<?php 
    echo plugins_url( 'img/attention.png', DFM_PGPPFW__FILE__ );
    ?>"><br>
      IMPORTANT: We are using a new license system. If you have trouble with your license then see this link:<br>
      <a href="https://support.dreamfoxmedia.com/kb/article/5/transferring-our-licenses-from-dreamfoxmedia-to-freemius" target="_blank">Click here to see the complete article</a>

      <p>This plugin for WooCommerce Payment Gateway per Product, by tag or per category and lets you select the available payment method for each (individual) product.<br>
        This plugin will allow the admin to select the available payment gateway for each individual product. This is done by <a href="edit.php?post_type=product">products</a><br>
      <p><img src="<?php 
    echo plugins_url( 'img/pgpp1.png', DFM_PGPPFW__FILE__ );
    ?>">&nbsp;&nbsp;&nbsp;<img src="<?php 
    echo plugins_url( 'img/pgpp2.png', DFM_PGPPFW__FILE__ );
    ?>"></p>
      For TAG and CATEGORIES you can set these by clicking the menu items on the left.<br>
      Admin can select for each (individual) product the payment gateway that will be used by checkout. If no selection is made, then the default payment gateways are displayed.<br>
      If you for example only select paypal then only paypal will available for that product by checking out.</p>

    </div>

    <div id="tab_payment_settings" class="postbox <?php 
    if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'payment_settings' ) {
        ?>active<?php 
    }
    ?>" style="padding: 10px; margin: 10px 0px;">
      <?php 
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap wrap-mc-paid"><div id="icon-tools" class="icon32"></div></div>';
    echo '<h2 class="title">' . __( 'Woocommerce Product Payments - Settings', 'dfm-payment-gateway-per-product-for-woocommerce' ) . '</h2>';
    ?>


      <?php 
    /**
     * Settings default
     */
    $softsdev_wpp_plugin_settings = get_option( 'sdwpp_plugin_settings', array(
        'softsdev_selected_cats' => '',
        'default_payment'        => '',
    ) );
    $default_payment = unserialize( $softsdev_wpp_plugin_settings['default_payment'] );
    ?>
      <form id="woo_sdwpp" action="<?php 
    echo get_admin_url( null, 'admin.php' ) . '?page=dfm-pgppfw&tab=payment_settings';
    ?>" method="post">
        <div style="padding: 10px 0; margin: 10px 0px;">
          <?php 
    echo $additional_html;
    ?>



          <h3 class="hndle"><?php 
    echo __( 'Default Payment option( If not match any.)', 'dfm-payment-gateway-per-product-for-woocommerce' );
    ?></h3>
          <?php 
    $woo = new WC_Payment_Gateways();
    $payments = $woo->payment_gateways;
    ?>
          <select id="sdwpp_default_payment" name="sdwpp_setting[default_payment]">
            <option value="none" <?php 
    selected( $default_payment, 'none' );
    ?>>None</option>
            <?php 
    foreach ( $payments as $pay ) {
        /**
         *  skip if payment in disbled from admin
         */
        if ( $pay->enabled === 'no' ) {
            continue;
        }
        echo "<option value = '" . $pay->id . "' " . selected( $default_payment, $pay->id ) . ">" . $pay->title . "</option>";
    }
    ?>
          </select>
          <br />
          <small><?php 
    echo __( 'If in some case payment option not show then this will default one set', 'dfm-payment-gateway-per-product-for-woocommerce' );
    ?></small>
        </div>
        <input class="button-large button-primary" type="submit" value="Save changes" />
      </form>
    </div>
    <?php 
    ?>
      <p>This option is a premium feature</p>
    <?php 
    ?>
    <div id="tab_payment_newsletter" class="postbox" style="padding: 10px; margin: 10px 0px;">
      <?php 
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap wrap-mc-paid"><div id="icon-tools" class="icon32"></div></div>';
    echo '<h2 class="title">' . __( 'Woocommerce Product Payments - Newsletter', 'dfm-payment-gateway-per-product-for-woocommerce' ) . '</h2>';
    ?>
      <!-- Begin Sendinblue Form -->
      <iframe width="540" height="505" src="https://322fdba5.sibforms.com/serve/MUIEADPSqc91xZQAhD93GZEuPI0STBa6IDtiRPRy1s2sWDXpIahq0YCn_hTynzANungZ-IBXlkdiqtxS5LWTX2PnNO4HXf3zdrDPhYfqPMOU5dTl_slePr-U4hKHdS0HY622pFWMdMMfj40dLxrwCm1gCkrwuC5SLHSNKOfjzFKVX5WkfG6W2aOhHybGkbdXqxCZmXoHswZbB_uJ" frameborder="0" scrolling="auto" allowfullscreen style="display: block;margin-left: auto;margin-right: auto;max-width: 100%;"></iframe>
      <!--  END - Sendinblue form -->
    </div>

    <div id="tab_payment_faq" class="postbox" style="padding: 10px; margin: 10px 0px;">
      <?php 
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap wrap-mc-paid"><div id="icon-tools" class="icon32"></div></div>';
    echo '<h2 class="title">' . __( 'Woocommerce Product Payments - FAQ', 'dfm-payment-gateway-per-product-for-woocommerce' ) . '</h2>';
    ?>
      <h4 class="mc4wp-title"><?php 
    echo __( 'Looking for help?', 'Woocommerce Payment Gateway Per Product' );
    ?></h4>
      <p>Below you see the link to the complete FAQ available at: <a href="https://dreamfoxmedia.com?utm_source=wp-plugin&utm_medium=wcpgpp-p&utm_campaign=faqall" target="_blank">dreamfoxmedia.com</a></p>
      <ul class="ul-square">
        <li><a href="https://support.dreamfoxmedia.com/kb/section/4" target="_blank">Click here to see the complete FAQ section</a></li>
      </ul>

      <p>Or see this link to the most read FAQs for the payment plugin available at: <a href="https://support.dreamfoxmedia.com/kb/section/4" target="_blank">Dreamfoxmedia.com</a></p>


      <p><?php 
    echo sprintf( __( 'If your answer can not be found in the resources listed above, please use our supportsystem <a href="%s">here</a>.' ), 'https://support.dreamfoxmedia.com' );
    ?></p>
      <p>Found a bug? Please open an issue <a href="https://support.dreamfoxmedia.com/support/tickets/create" target="_blank">here.</a></p>
    </div>

    <div id="tab_payment_dfmplugins" class="postbox" style="padding: 10px; margin: 10px 0px;">
      <?php 
    add_filter( 'admin_footer_text', 'softsdev_product_payments_footer_text' );
    add_filter( 'update_footer', 'softsdev_product_payments_update_footer' );
    echo '<div class="wrap wrap-mc-paid"><div id="icon-tools" class="icon32"></div></div>';
    echo '<h2 class="title">' . __( 'Woocommerce Product Payments - Dreamfox Media Plugins', 'dfm-payment-gateway-per-product-for-woocommerce' ) . '</h2>';
    ?>
      <?php 
    $url = 'https://raw.githubusercontent.com/dreamfoxmedia/dreamfoxmedia/gh-pages/plugins/dfmplugins.json';
    $response = wp_remote_get( $url, array() );
    $response_code = wp_remote_retrieve_response_code( $response );
    $response_body = wp_remote_retrieve_body( $response );
    if ( $response_code != 200 || is_wp_error( $response ) ) {
        echo '<div class="error below-h2"><p>There was an error retrieving the list from the server.</p></div>';
        switch ( $response_code ) {
            case '403':
                echo '<div class="error below-h2"><p>Seems your host is blocking <strong>' . dirname( $url ) . '</strong>. Please request to white list this domain </p></div>';
                break;
        }
        wp_die();
    }
    $addons = json_decode( $response_body );
    ?>
      <div class="wrap">
        <h3>Here you see our great Free and Premium Plugins of Dreamfox Media</h3>
        <link href="<?php 
    echo plugins_url( '/css/addons-style.min.css', DFM_PGPPFW__FILE__ );
    ?>" rel="stylesheet" type="text/css">

        <ul class="addons-wrap">
          <?php 
    foreach ( $addons as $addon ) {
        if ( !empty( $addon->hidden ) ) {
            continue;
        }
        $addon->link = ( isset( $addon->link ) ? add_query_arg( array(
            'utm_source'   => 'Dreamfox Media Plugin Page',
            'utm_medium'   => 'link',
            'utm_campaign' => 'Dreamfox Plugins Add Ons',
        ), $addon->link ) : '' );
        ?>
            <li class="mymail-addon <?php 
        if ( !empty( $addon->is_free ) ) {
            echo ' is-free';
        }
        if ( !empty( $addon->is_feature ) ) {
            echo ' is-feature';
        }
        if ( isset( $addon->image ) ) {
            $image = str_replace( 'http//', '//', $addon->image );
        } elseif ( isset( $addon->image_ ) ) {
            $image = str_replace( 'http//', '//', $addon->image_ );
        }
        ?>">
              <div class="bgimage" style="min-height: 500px; background-repeat: no-repeat; background-image:url(<?php 
        echo $image;
        ?>)">
                <?php 
        if ( isset( $addon->wpslug ) ) {
            ?>
                  <a href="plugin-install.php?tab=plugin-information&plugin=<?php 
            echo dirname( $addon->wpslug );
            ?>&from=import&TB_iframe=true&width=745&height=745" class="thickbox">&nbsp;</a>
                <?php 
        } else {
            ?>
                  <a href="<?php 
            echo $addon->link;
            ?>">&nbsp;</a>
                <?php 
        }
        ?>
              </div>
              <h4><?php 
        echo $addon->name;
        ?></h4>
              <p class="author">by
                <?php 
        if ( $addon->author_url ) {
            echo '<a href="' . $addon->author_url . '">' . $addon->author . '</a>';
        } else {
            echo $addon->author;
        }
        ?>
              </p>
              <p class="description"><?php 
        echo $addon->description;
        ?></p>
              <div class="action-links">
                <?php 
        if ( !empty( $addon->wpslug ) ) {
            ?>
                  <?php 
            if ( is_dir( dirname( WP_PLUGIN_DIR . '/' . $addon->wpslug ) ) ) {
                ?>
                    <?php 
                if ( is_plugin_active( $addon->wpslug ) ) {
                    ?>
                      <a class="button" href="<?php 
                    echo wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . $addon->wpslug, 'deactivate-plugin_' . $addon->wpslug );
                    ?>"><?php 
                    _e( 'Deactivate', 'mymail' );
                    ?></a>
                    <?php 
                } elseif ( is_plugin_inactive( $addon->wpslug ) ) {
                    ?>
                      <a class="button" href="<?php 
                    echo wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $addon->wpslug, 'activate-plugin_' . $addon->wpslug );
                    ?>"><?php 
                    _e( 'Activate', 'mymail' );
                    ?></a>
                    <?php 
                }
                ?>
                  <?php 
            } else {
                ?>
                    <?php 
                if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
                    ?>
                      <a class="button button-primary" href="<?php 
                    echo wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . dirname( $addon->wpslug ) . '&mymail-addon' ), 'install-plugin_' . dirname( $addon->wpslug ) );
                    ?>"><?php 
                    _e( 'Install', 'mymail' );
                    ?></a>
                    <?php 
                }
                ?>
                  <?php 
            }
            ?>
                <?php 
        } else {
            ?>
                  <a class="button button-primary" href="<?php 
            echo $addon->link;
            ?>"><?php 
            _e( 'Purchase', 'mymail' );
            ?></a>
                <?php 
        }
        ?>
              </div>
            </li>
          <?php 
    }
    ?>
        </ul>
      </div>
    </div>
  </div>

<?php 
}
