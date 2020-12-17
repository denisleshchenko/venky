<?php

/**
 * Header action Area
 */

/**
 * Header
 * @see  storevilla_skip_links()
 * @see  storevilla_top_navigation()
 * @see  storevilla_header_widget_region()
 * @see  storevilla_site_branding()
 * @see  storevilla_advance_product_search()
 * @see  storevilla_header_cart()
 * @see  storevilla_primary_navigation()
 */
add_action( 'storevilla_header', 'storevilla_skip_links', 	  0 );
add_action( 'storevilla_header', 'storevilla_top_header', 10 );
add_action( 'storevilla_header', 'storevilla_button_header', 20 );
add_action( 'storevilla_header', 'storevilla_primary_navigation', 60 );


/**
 * Footer action Area
 */
 
/**
* Header
* @see  storevilla_footer_widgets()
* @see  storevilla_credit()
* @see  storevilla_payment_logo()
*/
 
add_action( 'storevilla_footer', 'storevilla_footer_widgets', 10 );
add_action( 'storevilla_footer', 'storevilla_credit', 20 );
add_action( 'storevilla_footer', 'storevilla_payment_logo', 40 );



/**
 * Main HomePage Section Function Area
**/
 
/**
* Header
* @see  storevilla_main_slider()
* @see  storevilla_main_widget()
* @see  storevilla_brand_logo()
*/
 
add_action( 'storevilla_homepage', 'storevilla_main_slider', 10 );
add_action( 'storevilla_homepage', 'storevilla_main_widget', 20 );
add_action( 'storevilla_homepage', 'storevilla_brand_logo', 30 );

/**
 * Themes required Plugins Install Section
*/
if ( ! function_exists( 'storevilla_root_register_required_plugins' ) ) :
	
	function storevilla_root_register_required_plugins() {

	    $plugins = array(
	        array(
	            'name' => 'WooCommerce',
	            'slug' => 'woocommerce',
	            'required' => false,
	        ),
	        array(
	            'name' => 'YITH WooCommerce Quick View',
	            'slug' => 'yith-woocommerce-quick-view',
	            'required' => false,
	        ),
	         array(
	            'name' => 'YITH WooCommerce Compare',
	            'slug' => 'yith-woocommerce-compare',
	            'required' => false,
	        ),
	        array(
	            'name' => 'YITH WooCommerce Wishlist',
	            'slug' => 'yith-woocommerce-wishlist',
	            'required' => false,
	        ),	        
	        array(
	            'name' => 'AccessPress Social Share',
	            'slug' => 'accesspress-social-share',
	            'required' => false,
	        ),
	    );

	    $config = array(
	        'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
	        'default_path' => '', // Default absolute path to pre-packaged plugins.
	        'menu' => 'tgmpa-install-plugins', // Menu slug.
	        'parent_slug' => 'themes.php', // Parent menu slug.
	        'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
	        'has_notices' => true, // Show admin notices or not.
	        'dismissable' => true, // If false, a user cannot dismiss the nag message.
	        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
	        'is_automatic' => true, // Automatically activate plugins after installation or not.
	        'message' => '', // Message to output right before the plugins table.
	        'strings' => array(
	            'page_title' => __('Install Required Plugins', 'storevilla'),
	            'menu_title' => __('Install Plugins', 'storevilla'),
	            'installing' => __('Installing Plugin: %s', 'storevilla'), // %s = plugin name.
	            'oops' => __('Something went wrong with the plugin API.', 'storevilla'),
	            'notice_can_install_required' => _n_noop(
	                    'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_can_install_recommended' => _n_noop(
	                    'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_cannot_install' => _n_noop(
	                    'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_ask_to_update' => _n_noop(
	                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_ask_to_update_maybe' => _n_noop(
	                    'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_cannot_update' => _n_noop(
	                    'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_can_activate_required' => _n_noop(
	                    'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_can_activate_recommended' => _n_noop(
	                    'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'notice_cannot_activate' => _n_noop(
	                    'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'storevilla'
	            ), // %1$s = plugin name(s).
	            'install_link' => _n_noop(
	                    'Begin installing plugin', 'Begin installing plugins', 'storevilla'
	            ),
	            'update_link' => _n_noop(
	                    'Begin updating plugin', 'Begin updating plugins', 'storevilla'
	            ),
	            'activate_link' => _n_noop(
	                    'Begin activating plugin', 'Begin activating plugins', 'storevilla'
	            ),
	            'return' => __('Return to Required Plugins Installer', 'storevilla'),
	            'plugin_activated' => __('Plugin activated successfully.', 'storevilla'),
	            'activated_successfully' => __('The following plugin was activated successfully:', 'storevilla'),
	            'plugin_already_active' => __('No action taken. Plugin %1$s was already active.', 'storevilla'), // %1$s = plugin name(s).
	            'plugin_needs_higher_version' => __('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'storevilla'), // %1$s = plugin name(s).
	            'complete' => __('All plugins installed and activated successfully. %1$s', 'storevilla'), // %s = dashboard link.
	            'contact_admin' => __('Please contact the administrator of this site for help.', 'storevilla'),
	            'nag_type' => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
	        )
	    );
	    tgmpa($plugins, $config);
	}

add_action('tgmpa_register', 'storevilla_root_register_required_plugins');

endif;