<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
  Plugin name:AccessPress Social Share
  Plugin URI: https://accesspressthemes.com/wordpress-plugins/accesspress-social-share/
  Description: A plugin to add various social media shares to a site with dynamic configuration options.
  Version: 4.5.4
  Author: AccessPress Themes
  Author URI: http://accesspressthemes.com
  Text Domain: accesspress-social-share
  Domain Path: /languages/
  License: GPLv2 or later
 */

//Decleration of the necessary constants for plugin

  if ( ! defined( 'APSS_PATH' ) ) {
    define( 'APSS_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'APSS_IMAGE_DIR' ) ) {
    define( 'APSS_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images' );
}

if ( ! defined( 'APSS_JS_DIR' ) ) {
    define( 'APSS_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );
}

if ( ! defined( 'APSS_CSS_DIR' ) ) {
    define( 'APSS_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );
}

if ( ! defined( 'APSS_LANG_DIR' ) ) {
    define( 'APSS_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' );
}

if ( ! defined( 'APSS_VERSION' ) ) {
    define( 'APSS_VERSION', '4.5.4' );
}

if ( ! defined( 'APSS_TEXT_DOMAIN' ) ) {
    define( 'APSS_TEXT_DOMAIN', 'accesspress-social-share' );
}

if ( ! defined( 'APSS_SETTING_NAME' ) ) {
    define( 'APSS_SETTING_NAME', 'apss_share_settings' );
}

if ( ! defined( 'APSS_COUNT_TRANSIENTS' ) ) {
    define( 'APSS_COUNT_TRANSIENTS', 'apss_social_counts_transients' );
}

defined('APSS_LITE_PLUGIN_NAME') or define('APSS_LITE_PLUGIN_NAME', 'AccessPress Social Share');
defined('APSS_LITE_DEMO') or define('APSS_LITE_DEMO', 'http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-share');
defined('APSS_LITE_DOC') or define('APSS_LITE_DOC', 'https://accesspressthemes.com/documentation/accesspress-social-share/');
defined('APSS_LITE_DETAIL') or define('APSS_LITE_DETAIL', 'https://accesspressthemes.com/wordpress-plugins/accesspress-social-share/');
defined('APSS_LITE_RATING') or define('APSS_LITE_RATING', 'https://wordpress.org/support/plugin/accesspress-social-share/reviews/#new-post');

defined('APSS_PRO_PLUGIN_NAME') or define('APSS_PRO_PLUGIN_NAME', 'AccessPress Social Pro');
defined('APSS_PRO_LINK') or define('APSS_PRO_LINK','https://accesspressthemes.com/wordpress-plugins/accesspress-social-pro/');
defined('APSS_PRO_DEMO') or define('APSS_PRO_DEMO', 'http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-pro');
defined('APSS_PRO_DETAIL') or define('APSS_PRO_DETAIL', 'https://accesspressthemes.com/wordpress-plugins/accesspress-social-pro/');

//Decleration of the class for necessary configuration of a plugin
if ( ! class_exists( 'APSS_Class' ) ) {

    class APSS_Class{

        var $apss_settings;
        var $apss_social_counts_transients;

        function __construct(){
            $this -> apss_settings = get_option( APSS_SETTING_NAME ); //get the plugin variable contents from the options table.
            register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) ); //load the default setting for the plugin while activating
            add_action( 'init', array( $this, 'plugin_text_domain' ) ); //load the plugin text domain
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) ); //registers all the assets required for wp-admin
            add_filter( 'the_content', array( $this, 'apss_the_content_filter' ), 110 ); // add the filter function for display of social share icons in frontend //added 12 priority level at the end to make the plugin compactible with Visual Composer.

            if ( isset( $this -> apss_settings[ 'disable_frontend_assets' ] ) && $this -> apss_settings[ 'disable_frontend_assets' ] != '1' ) {
                add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) ); //registers all the assets required for the frontend
            } else if ( ! isset( $this -> apss_settings[ 'disable_frontend_assets' ] ) ) {
                add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) ); //registers all the assets required for the frontend
            }

            add_action( 'admin_menu', array( $this, 'add_apss_menu' ) ); //register the plugin menu in backend
            add_action( 'admin_post_apss_save_options', array( $this, 'apss_save_options' ) ); //save the options in the wordpress options table.
            add_action( 'admin_post_apss_restore_default_settings', array( $this, 'apss_restore_default_settings' ) ); //restores default settings.
            add_action( 'admin_post_apss_clear_cache', array( $this, 'apss_clear_cache' ) ); //clear the cache of the social share counter.
            add_shortcode( 'apss-share', array( $this, 'apss_shortcode' ) ); //adds a shortcode
            add_shortcode( 'apss_share', array( $this, 'apss_shortcode' ) ); //added a new shortcode to remove the shortcode with hyphen in future
            add_shortcode( 'apss-count', array( $this, 'apss_count_shortcode' ) ); //adds a share count shortcode
            add_shortcode( 'apss_count', array( $this, 'apss_count_shortcode' ) ); //added a new shortcode to remove the shortcode with hyphen in future
            add_action( 'add_meta_boxes', array( $this, 'social_meta_box' ) ); //for providing the option to disable the social share option in each frontend page
            add_action( 'save_post', array( $this, 'save_meta_values' ) ); //function to save the post meta values of a plugin.

            add_action( 'wp_ajax_nopriv_frontend_counter', array( $this, 'frontend_counter' ) ); //fetching of the social share count.
            add_action( 'wp_ajax_frontend_counter', array( $this, 'frontend_counter' ) ); // action for ajax counter.

            add_filter( 'plugin_row_meta', array( $this, 'apss_plugin_row_meta' ), 10, 2 );
            add_filter( 'admin_footer_text', array( $this, 'apss_admin_footer_text' ) );
            add_action( 'admin_init', array( $this, 'redirect_to_site' ), 1 );

        }

        function redirect_to_site(){

            if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'apss-documentation' ) {
                wp_redirect( APSS_LITE_DOC );
                exit();
            }

            if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'apss-premium' ) {
                wp_redirect( APSS_PRO_LINK );
                exit();
            }
        }


        function apss_plugin_row_meta( $links, $file ){
            if ( strpos( $file, 'accesspress-social-share.php' ) !== false ) {
                $new_links = array(
                    'demo' => '<a href="'.APSS_LITE_DEMO.'" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span>Live Demo</a>',
                    'doc' => '<a href="'.APSS_LITE_DOC.'" target="_blank"><span class="dashicons dashicons-media-document"></span>Documentation</a>',
                    'support' => '<a href="http://accesspressthemes.com/support" target="_blank"><span class="dashicons dashicons-admin-users"></span>Support</a>',
                    'pro' => '<a href="'.APSS_PRO_LINK.'" target="_blank"><span class="dashicons dashicons-cart"></span>Premium version</a>'
                );
                $links = array_merge( $links, $new_links );
            }
            return $links;
        }

        function apss_admin_footer_text( $text ){
            global $post;
            if ( isset($_GET['page']) && $_GET['page'] === 'accesspress-social-share') {

              $text = 'Enjoyed ' . APSS_LITE_PLUGIN_NAME . '? <a href="' . APSS_LITE_RATING . '" target="_blank">Please leave us a ★★★★★ rating</a> We really appreciate your support! | Try premium version <a href="' . APSS_PRO_LINK . '" target="_blank">' . APSS_PRO_PLUGIN_NAME . '</a> - more features, more power!';
              return $text;

          } else {

              return $text;

          }
      }


        //called when plugin is activated
      function plugin_activation(){

        global $wpdb;
        if ( is_multisite() ) {
            $current_blog = $wpdb -> blogid;
                // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb -> get_col( "SELECT blog_id FROM {$wpdb -> blogs}" );
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                if ( ! get_option( APSS_SETTING_NAME ) ) {
                    include( 'inc/backend/activation.php' );
                }
                if ( ! get_option( APSS_COUNT_TRANSIENTS ) ) {
                    $apss_social_counts_transients = array();
                    update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                }
            }
        } else {
            if ( ! get_option( APSS_SETTING_NAME ) ) {
                include( 'inc/backend/activation.php' );
            }
            if ( ! get_option( APSS_COUNT_TRANSIENTS ) ) {
                $apss_social_counts_transients = array();
                update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
            }
        }

    }

        //loads the text domain for translation
    function plugin_text_domain(){
        load_plugin_textdomain( 'accesspress-social-share', false, APSS_LANG_DIR );
    }

        //functions to register frontend styles and scripts
    function register_admin_assets(){
            /**
             * Backend CSS
             * */
            if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'accesspress-social-share' ) {
                wp_enqueue_style( 'aps-admin-css', APSS_CSS_DIR . '/backend.css', false, APSS_VERSION ); //registering plugin admin css
                
                wp_enqueue_style( 'fontawesome-css', APSS_CSS_DIR . '/font-awesome/font-awesome.min.css', false, APSS_VERSION );

                /**
                 * Backend JS
                 * */
                wp_enqueue_script( 'jquery-ui-sortable' );
                wp_enqueue_script( 'apss-admin-js', APSS_JS_DIR . '/backend.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), APSS_VERSION ); //registering plugin's admin js
            }
        }

        //function to return the content filter for the posts and pages
        function apss_the_content_filter( $content ){
            if ( in_array( 'get_the_excerpt', $GLOBALS[ 'wp_current_filter' ] ) )
                return $content;
            global $post;
            $post_content = $content;
            $title = str_replace( '+', '%20', urlencode( $post -> post_title ) );
            $content = trim( strip_shortcodes( strip_tags( $post -> post_content ) ) );
            if ( strlen( $content ) >= 100 ) {
                $excerpt = urlencode( substr( $content, 0, 100 ) . '...' );
            } else {
                $excerpt = urlencode( $content );
            }
            $options = $this -> apss_settings;
            ob_start();
            include('inc/frontend/content-filter.php');
            $html_content = ob_get_contents();
            ob_get_clean();
            if( isset( $options[ 'disable_frontend_assets' ] ) && $options[ 'disable_frontend_assets' ] == '0' ){
                if(isset($apss_share_settings['font_awesome'])){
                    $font_awesome_version=$apss_share_settings['font_awesome'];
                }
                else{
                    $font_awesome_version='';
                }
            }
            else{
                $font_awesome_version='';
            }
            $share_shows_in_options = $options[ 'share_options' ];
            $content_flag = get_post_meta( $post -> ID, 'apss_content_flag', true );

            $all = in_array( 'all', $options[ 'share_options' ] );
            $is_lists_authorized = (is_search() && $content_flag != '1' ) && $all ? true : false;

            $is_attachement_check = in_array( 'attachment', $options[ 'share_options' ] );
            $is_attachement = (is_attachment() && $is_attachement_check ) ? true : false;

            $front_page = in_array( 'front_page', $options[ 'share_options' ] );
            $is_front_page = (is_front_page() && $content_flag != '1' ) && $front_page ? true : false;

            $share_shows_in_options = $options[ 'share_options' ];
            $is_singular = is_singular( $share_shows_in_options ) && ! is_front_page() && $content_flag != '1' ? true : false;

            if ( ! empty( $share_shows_in_options ) ) {
                $is_tax = is_tax( $share_shows_in_options );
            } else {
                $is_tax = false;
            }

            $is_category = in_array( 'categories', $options[ 'share_options' ] );
            $default_category = ( is_category() ) && $is_category ? true : false;

            $is_default_archive = in_array( 'archives', $options[ 'share_options' ] );
            $default_archives = ( ( is_archive() && ! is_tax() ) && ! is_category() ) && $is_default_archive ? true : false;
            if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
                $show_icons = false;
            } else {
                $show_icons = true;
            }

            if ( empty( $options[ 'share_options' ] ) ) {
                return $post_content;
            } else if ( ($is_lists_authorized || $is_attachement || $is_singular || $is_tax || $is_front_page || $default_category || $default_archives) && $show_icons ) {
                if ( $options[ 'share_positions' ] == 'below_content' ) {
                    return $post_content . "<div class='apss-social-share apss-theme-$icon_set_value clearfix $font_awesome_version' >" . $html_content . "</div>";
                }

                if ( $options[ 'share_positions' ] == 'above_content' ) {
                    return "<div class='apss-social-share apss-theme-$icon_set_value clearfix $font_awesome_version'>$html_content</div>" . $post_content;
                }

                if ( $options[ 'share_positions' ] == 'on_both' ) {
                    return "<div class='apss-social-share apss-theme-$icon_set_value clearfix $font_awesome_version'>$html_content</div>" . $post_content . "<div class='apss-social-share apss-theme-$icon_set_value clearfix'>$html_content</div>";
                }
            } else {
                return $post_content;
            }
        }

        /**
         * Registers Frontend Assets
         * */
        function register_frontend_assets(){
            $apss_share_settings = get_option('apss_share_settings');
            if ( isset( $apss_share_settings['font_awesome'] ) && $apss_share_settings['font_awesome'] == 'apss_font_awesome_five' ) {
                wp_enqueue_style( 'apss-font-awesome-five', APSS_CSS_DIR . '/fontawesome-all.css', array(), APSS_VERSION );
            }
            else{
                wp_enqueue_style( 'apss-font-awesome-four', APSS_CSS_DIR . '/font-awesome.min.css', array(), APSS_VERSION );
            }
            wp_enqueue_style( 'apss-frontend-css', APSS_CSS_DIR . '/frontend.css', array(), APSS_VERSION );
            wp_enqueue_style( 'apss-font-opensans', '//fonts.googleapis.com/css?family=Open+Sans', array(), false );
            wp_enqueue_script( 'apss-frontend-mainjs', APSS_JS_DIR . '/frontend.js', array( 'jquery' ), APSS_VERSION, true );
            $ajax_nonce = wp_create_nonce( 'apss-ajax-nonce' );
            wp_localize_script( 'apss-frontend-mainjs', 'frontend_ajax_object', array( 'ajax_url' => admin_url() . 'admin-ajax.php', 'ajax_nonce' => $ajax_nonce ) );
        }

        //add plugins menu in backend
        function add_apss_menu(){
            add_menu_page( 'AccessPress Social Share', 'AccessPress Social Share', 'manage_options', 'accesspress-social-share', array( $this, 'main_page' ), APSS_IMAGE_DIR . '/apss-icon.png' );

            add_submenu_page('accesspress-social-share', __('Documentation', 'accesspress-social-share'), __('Documentation', 'accesspress-social-share'), 'manage_options', 'apss-documentation', '__return_false', null, 9);
            add_submenu_page('accesspress-social-share', __('Check Premium Version', 'accesspress-social-share'), __('Check Premium Version', 'accesspress-social-share'), 'manage_options', 'apss-premium', '__return_false', null, 9);
        }

        //for saving the plugin settings
        function apss_save_options(){
            if ( isset( $_POST[ 'apss_add_nonce_save_settings' ] ) && isset( $_POST[ 'apss_submit_settings' ] ) && wp_verify_nonce( $_POST[ 'apss_add_nonce_save_settings' ], 'apss_nonce_save_settings' ) ) {
                include( 'inc/backend/save-settings.php' );
            } else {
                die( 'No script kiddies please!' );
            }
        }

        //function to restore the default setting of a plugin
        function apss_restore_default_settings(){
            $nonce = $_REQUEST[ '_wpnonce' ];
            if ( ! empty( $_GET ) && wp_verify_nonce( $nonce, 'apss-restore-default-settings-nonce' ) ) {
                //restore the default plugin activation settings from the activation page.
                include( 'inc/backend/activation.php' );
                wp_redirect( admin_url() . 'admin.php?page=accesspress-social-share&message=3' );
                exit;
            } else {
                die( 'No script kiddies please!' );
            }
        }

        /**
         * Clears the social share counter cache
         */
        function apss_clear_cache(){
            if ( ! empty( $_GET ) && wp_verify_nonce( $_GET[ '_wpnonce' ], 'apss-clear-cache-nonce' ) ) {
                $apss_settings = $this -> apss_settings;
                $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
                foreach ( $apss_social_counts_transients as $transient ) {
                    delete_transient( $transient );
                }
                update_option( APSS_COUNT_TRANSIENTS, array() );
                $transient_array = array( 'apss_tweets_count', 'apss_linkedin_count', 'apss_fb_count', 'apss_pin_count' );
                foreach ( $transient_array as $transient ) {
                    delete_transient( $transient );
                }
                wp_redirect( admin_url() . 'admin.php?page=accesspress-social-share&message=4' );
            }
        }

        //function for adding shortcode of a plugin
        function apss_shortcode( $attr ){
            ob_start();
            include( 'inc/frontend/shortcode.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        //frontend counter
        function frontend_counter(){
            if ( ! empty( $_GET ) && wp_verify_nonce( $_GET[ '_wpnonce' ], 'apss-ajax-nonce' ) ) {
                $apss_settings = $this -> apss_settings;
                $new_detail_array = array();
                if ( isset( $_POST[ 'data' ] ) ) {
                    $details = sanitize_array($_POST[ 'data' ]);
                    foreach ( $details as $detail ) {
                        $new_detail_array[ $detail[ 'network' ] ] = $this -> get_count( $detail[ 'network' ], $detail[ 'url' ] );
                    }
                } else if ( isset( $_POST[ 'shortcode_data' ] ) ) {
                    $shortcode_data = sanitize_array($_POST[ 'shortcode_data' ]);
                    foreach ( $shortcode_data as $detail ) {
                        $detail_array = explode( '_', $detail );
                        $url = trim( $detail_array[ 0 ] );
                        $network = $detail_array[ 1 ];
                        $new_detail_array[] = $this -> get_count( $network, $url );
                    }
                }
                die( json_encode( $new_detail_array ) );
            }
        }

        function sanitize_array($array = array(), $sanitize_rule = array()) {
            if (!is_array($array) || count($array) == 0) {
                return array();
            }
            foreach ($array as $k => $v) {
                if (!is_array($v)) {
                    $default_sanitize_rule = (is_numeric($k)) ? 'text' : 'html';
                    $sanitize_type = isset($sanitize_rule[$k]) ? $sanitize_rule[$k] : $default_sanitize_rule;
                    $array[$k] = $this->sanitize_value($v, $sanitize_type);
                }
                if (is_array($v)) {
                    $array[$k] = $this->sanitize_array($v, $sanitize_rule);
                }
            }
            return $array;
        }

        function sanitize_value($value = '', $sanitize_type = 'text') {
            switch ($sanitize_type) {
                case 'html':
                $allowed_html = wp_kses_allowed_html('post');
                return wp_kses($value, $allowed_html);
                break;
                default:
                return sanitize_text_field($value);
                break;
            }
        }

        //frontend counter only Shortcode
        function apss_count_shortcode( $attr ){
            ob_start();
            include( 'inc/frontend/count_shortcode.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }

        ///////////////////////////for post meta options//////////////////////////////////
        /**
         * Adds a section in all the post and page section to disable the share options in frontend pages
         */
        function social_meta_box(){
            add_meta_box( 'ap-share-box', 'AccessPress social share options', array( $this, 'metabox_callback' ), '', 'side', 'core' );
        }

        function metabox_callback( $post ){
            wp_nonce_field( 'save_meta_values', 'ap_share_meta_nonce' );
            $content_flag = get_post_meta( $post -> ID, 'apss_content_flag', true );
            ?>
            <label><input type="checkbox" value="1" name="apss_content_flag" <?php checked( $content_flag, true ) ?>/><?php _e( 'Hide share icons in content', 'accesspress-social-share' ); ?></label><br>
            <?php
        }

        /**
         * Save Share Flags on post save
         */
        function save_meta_values( $post_id ){

            /*
             * We need to verify this came from our screen and with proper authorization,
             * because the save_post action can be triggered at other times.
             */

            // Check if our nonce is set.
            if ( ! isset( $_POST[ 'ap_share_meta_nonce' ] ) ) {
                return;
            }

            // Verify that the nonce is valid.
            if ( ! wp_verify_nonce( $_POST[ 'ap_share_meta_nonce' ], 'save_meta_values' ) ) {
                return;
            }

            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            // Check the user's permissions.
            if ( isset( $_POST[ 'post_type' ] ) && 'page' == $_POST[ 'post_type' ] ) {

                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                }
            } else {

                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }

            /* OK, it's safe for us to save the data now. */
            // Make sure that it is set.
            $content_flag = (isset( $_POST[ 'apss_content_flag' ] ) && $_POST[ 'apss_content_flag' ] == 1) ? 1 : 0;

            // Update the meta field in the database.
            update_post_meta( $post_id, 'apss_content_flag', $content_flag );
        }

        ////////////////////////////////////////////////////////////
        //plugins backend admin page
        function main_page(){
            include('inc/backend/main-page.php');
        }

        //returns the current page url
        function curPageURL(){
            $pageURL = 'http';
            if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) {
                $pageURL .= "s";
            }
            $pageURL .= "://";
            if ( $_SERVER[ "SERVER_PORT" ] != "80" ) {
                $pageURL .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ] . $_SERVER[ "REQUEST_URI" ];
            } else {
                $pageURL .= $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "REQUEST_URI" ];
            }
            return $pageURL;
        }

        /**
         * Funciton to print array in pre format
         * */
        function print_array( $array ){
            echo "<pre>";
            print_r( $array );
            echo "</pre>";
        }

        //////////////////////////////////// for count //////////////////////////////////////
        //for facebook url share count
        function get_fb( $url ){
            $apss_settings = $this -> apss_settings;
            if ( ! isset( $apss_settings[ 'enable_cache' ] ) || $apss_settings[ 'enable_cache' ] == '1' ) {
                $cache_period = $apss_settings[ 'cache_period' ];
                $fb_transient = 'fb_' . md5( $url );
                $fb_transient_count = get_transient( $fb_transient );
                $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
                if ( false === $fb_transient_count ) {
                    $json_string = $this -> get_json_values( 'https://graph.facebook.com/?id=' . $url );
                    $json = json_decode( $json_string, true );
                    $facebook_count = isset( $json[ 'share' ][ 'share_count' ] ) ? intval( $json[ 'share' ][ 'share_count' ] ) : 0;
                    set_transient( $fb_transient, $facebook_count, $cache_period * HOUR_IN_SECONDS );
                    if ( ! in_array( $fb_transient, $apss_social_counts_transients ) ) {
                        $apss_social_counts_transients[] = $fb_transient;
                        update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                    }
                } else {
                    $facebook_count = $fb_transient_count;
                }

            } else {
                $json_string = $this -> get_json_values( 'https://graph.facebook.com/?id=' . $url );
                $json = json_decode( $json_string, true );
                $facebook_count = isset( $json[ 'share' ][ 'share_count' ] ) ? intval( $json[ 'share' ][ 'share_count' ] ) : 0;
            }
            return $facebook_count;
        }

        /**
         * Get Facebook Access Token
         * */
        function get_fb_access_token(){
            $apss_settings = $this -> apss_settings;
            $app_id = $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_id' ];
            $app_secret = $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_secret' ];
            $api_url = 'https://graph.facebook.com/';
            $url = sprintf(
                    '%soauth/access_token?client_id=%s&client_secret=%s&grant_type=client_credentials', $api_url, $app_id, $app_secret
            );

            $get_token = "https://graph.facebook.com/oauth/access_token?client_id=$app_id&client_secret=$app_secret&grant_type=client_credentials&&redirect_uri=$url";

            $access_token = wp_remote_get( $get_token, array( 'timeout' => 60 ) );
            if ( is_wp_error( $access_token ) || ( isset( $access_token[ 'response' ][ 'code' ] ) && 200 != $access_token[ 'response' ][ 'code' ] ) ) {
                return '';
            } else {
                $json_decode = json_decode( $access_token[ 'body' ] );
                return sanitize_text_field( $json_decode -> access_token );
            }
        }

        function new_get_fb( $url ){
            $apss_settings = $this -> apss_settings;

            if ( isset( $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_id' ] ) && $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_id' ] != '' ) {
                $fb_app_id = $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_id' ];
            }

            if ( isset( $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_secret' ] ) && $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_secret' ] != '' ) {
                $fb_app_secret = $apss_settings[ 'api_configuration' ][ 'facebook' ][ 'app_id' ];
            }

            if ( ! isset( $fb_app_id ) || ! isset( $fb_app_secret ) ) {
                $facebook_count = self:: get_fb( $url );
                return $facebook_count;
            } else {
                $access_token = self:: get_fb_access_token();

                $api_url = 'https://graph.facebook.com/';
                $facebook_count = sprintf(
                    '%s?access_token=%s&id=%s', $api_url, $access_token, $url
                );
                //$facebook_count = 'https://graph.facebook.com/?fields=og_object%7Blikes.summary(true).limit(0)%7D,share&id=' . $url;

                $apss_settings = $this -> apss_settings;
                if ( isset( $apss_settings[ 'enable_cache' ] ) && $apss_settings[ 'enable_cache' ] == '1' ) {
                    $cache_period = $apss_settings[ 'cache_period' ];
                    $fb_transient = 'fb_' . md5( $url );
                    $fb_transient_count = get_transient( $fb_transient );

                    //for setting the counter transient in separate options value
                    $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
                    if ( false === $fb_transient_count ) {
                        $json_string = $this -> get_json_values( $facebook_count );
                        $json = json_decode( $json_string, true );
                        $facebook_count = isset( $json[ 'share' ][ 'share_count' ] ) ? intval( $json[ 'share' ][ 'share_count' ] ) : 0;
                        set_transient( $fb_transient, $facebook_count, $cache_period * HOUR_IN_SECONDS );
                        if ( ! in_array( $fb_transient, $apss_social_counts_transients ) ) {
                            $apss_social_counts_transients[] = $fb_transient;
                            update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                        }
                    } else {
                        $facebook_count = $fb_transient_count;
                    }
                } else {
                    $facebook_count = APSS_Class :: facebook_new_share_count_api( $url );
                }
                return $facebook_count;
                die();
            }
        }

        static function facebook_new_share_count_api( $url ){

         $access_token = self::get_fb_access_token( $url );
         $api_url = 'https://graph.facebook.com/';

               // Not Working --v
         $facebook_count = sprintf( '%s?access_token=%s&id=%s', $api_url, $access_token, $url );

               // Not Working --v
         $facebook_count = 'https://graph.facebook.com/?id=' . $url . '&access_token=' . sanitize_text_field( $access_token );

               // Not Working --v
         $facebook_count = 'https://graph.facebook.com/?id=' . $url . '&fields=og_object{engagement}';
         if ( $access_token != '' ) {
            $facebook_count .= '&access_token=' . sanitize_text_field( $access_token );
        }

               // Not Working -- depreciated for FB App v2.9 and higher
        $facebook_count = 'https://graph.facebook.com/?fields=og_object%7Blikes.summary(true).limit(0)%7D,share&id=' . $url;

               // Working
        $facebook_count = "https://graph.facebook.com/?id=$url&fields=engagement&access_token=$access_token";

        $json_string = self::get_json_values( $facebook_count );
        $json = json_decode( $json_string, true );
        if ( is_wp_error( $access_token ) || ( isset( $access_token[ 'response' ][ 'code' ] ) && 200 != $access_token[ 'response' ][ 'code' ] ) ) {
            return '0';
        } else {
            $facebook_count = isset( $json[ 'engagement' ][ 'share_count' ] ) ? intval( $json[ 'engagement' ][ 'share_count' ] ) : 0;
            return $facebook_count;
        }
    }

        //for twitter url share count
    function get_tweets( $url ){
        $apss_settings = $this -> apss_settings;
        if ( ! isset( $apss_settings[ 'enable_cache' ] ) || $apss_settings[ 'enable_cache' ] == '1' ) {
            $cache_period = $apss_settings[ 'cache_period' ];
            $twitter_transient = 'twitter_' . md5( $url );
            $twitter_transient_count = get_transient( $twitter_transient );
                //for setting the counter transient in separate options value
            $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
            if ( false === $twitter_transient_count ) {
                if ( isset( $apss_settings[ 'twitter_counter_api' ] ) ) {
                    $api_selection = $apss_settings[ 'twitter_counter_api' ];
                } else {
                    $api_selection = '1';
                }

                if ( $api_selection == '2' ) {
                    $json_string = $this -> get_json_values( 'http://public.newsharecounts.com/count.json?url=' . $url );
                } else if ( $api_selection == '3' ) {
                    $json_string = $this -> get_json_values( 'http://opensharecount.com/count.json?url=' . $url );
                } else {
                        // depriciated url share count. returns null
                    $json_string = $this -> get_json_values( 'http://urls.api.twitter.com/1/urls/count.json?url=' . $url );
                }

                $json = json_decode( $json_string, true );
                $tweet_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
                set_transient( $twitter_transient, $tweet_count, $cache_period * HOUR_IN_SECONDS );
                if ( ! in_array( $twitter_transient, $apss_social_counts_transients ) ) {
                    $apss_social_counts_transients[] = $twitter_transient;
                    update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                }
            } else {
                $tweet_count = $twitter_transient_count;
            }
        } else {
            if ( isset( $apss_settings[ 'twitter_counter_api' ] ) ) {
                $api_selection = $apss_settings[ 'twitter_counter_api' ];
            } else {
                $api_selection = '1';
            }

            if ( $api_selection == '2' ) {
                $json_string = $this -> get_json_values( 'http://public.newsharecounts.com/count.json?url=' . $url );
            } else if ( $api_selection == '3' ) {
                $json_string = $this -> get_json_values( 'http://opensharecount.com/count.json?url=' . $url );
            } else {
                    // depriciated url share count. returns null
                $json_string = $this -> get_json_values( 'http://urls.api.twitter.com/1/urls/count.json?url=' . $url );
            }

            $json = json_decode( $json_string, true );
            $tweet_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
        }
        return $tweet_count;
    }

        //for pinterest url share count
    function get_pinterest( $url ){
        $apss_settings = $this -> apss_settings;
        if ( ! isset( $apss_settings[ 'enable_cache' ] ) || $apss_settings[ 'enable_cache' ] == '1' ) {
            $cache_period = $apss_settings[ 'cache_period' ];
            $pinterest_transient = 'pinterest_' . md5( $url );
            $pinterest_transient_count = get_transient( $pinterest_transient );

                //for setting the counter transient in separate options value
            $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
            if ( false === $pinterest_transient_count ) {
                $json_string = $this -> get_json_values( 'http://api.pinterest.com/v1/urls/count.json?url=' . $url );
                $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );
                $json = json_decode( $json_string, true );
                $pinterest_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
                set_transient( $pinterest_transient, $pinterest_count, $cache_period * HOUR_IN_SECONDS );
                if ( ! in_array( $pinterest_transient, $apss_social_counts_transients ) ) {
                    $apss_social_counts_transients[] = $pinterest_transient;
                    update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                }
            } else {
                $pinterest_count = $pinterest_transient_count;
            }
        } else {
            $json_string = $this -> get_json_values( 'http://api.pinterest.com/v1/urls/count.json?url=' . $url );
            $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );
            $json = json_decode( $json_string, true );
            $pinterest_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
        }
        return $pinterest_count;
    }

        //for linkedin url share count
    function get_linkedin( $url ){
        $apss_settings = $this -> apss_settings;
        if ( ! isset( $apss_settings[ 'enable_cache' ] ) || $apss_settings[ 'enable_cache' ] == '1' ) {
            $cache_period = $apss_settings[ 'cache_period' ];
            $linkedin_transient = 'linkedin_' . md5( $url );
            $linkedin_transient_count = get_transient( $linkedin_transient );

                //for setting the counter transient in separate options value
            $apss_social_counts_transients = get_option( APSS_COUNT_TRANSIENTS );
            if ( false === $linkedin_transient_count ) {
                $json_string = $this -> get_json_values( "https://www.linkedin.com/countserv/count/share?url=$url&format=json" );
                $json = json_decode( $json_string, true );
                $linkedin_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;

                set_transient( $linkedin_transient, $linkedin_count, $cache_period * HOUR_IN_SECONDS );
                if ( ! in_array( $linkedin_transient, $apss_social_counts_transients ) ) {
                    $apss_social_counts_transients[] = $linkedin_transient;
                    update_option( APSS_COUNT_TRANSIENTS, $apss_social_counts_transients );
                }
            } else {
                $linkedin_count = $linkedin_transient_count;
            }
        } else {
            $json_string = $this -> get_json_values( "https://www.linkedin.com/countserv/count/share?url=$url&format=json" );
            $json = json_decode( $json_string, true );
            $linkedin_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
        }
        return $linkedin_count;
    }

        //function to return json values from social media urls
    private function get_json_values( $url ){
        $args = array( 'timeout' => 10 );
        $response = wp_remote_get( $url, $args );
        $json_response = wp_remote_retrieve_body( $response );
        return $json_response;
    }

        ////////////////////////////////////for count ends here/////////////////////////////////////////////

    function get_count( $profile_name, $url ){

        $url = apply_filters( 'apss_share_url', $url );

        switch ( $profile_name ) {
            case 'facebook':
            $count = $this -> new_get_fb( $url );
            break;

            case 'twitter':
            $count = $this -> get_tweets( $url );
            break;

            case 'linkedin':
            $count = $this -> get_linkedin( $url );
            break;

            case 'pinterest':
            $count = $this -> get_pinterest( $url );
            break;

            default:
            $count = 0;
            break;
        }
        return $count;
    }

    public static function get_http_url( $url ){
        return preg_replace( '/https:/i', 'http:', $url );
    }
}
$GLOBALS[ 'apss_object' ] = new APSS_Class();
}