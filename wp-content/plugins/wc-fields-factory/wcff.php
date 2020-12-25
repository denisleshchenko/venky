<?php
/**
 * 
 * Plugin Name: WC Fields Factory
 * Plugin URI: http://sarkware.com/wc-fields-factory-a-wordpress-plugin-to-add-custom-fields-to-woocommerce-product-page/
 * Description: It allows you to add custom fields to your woocommerce product page. You can add custom fields and validations without tweaking any of your theme's code & templates, It also allows you to group the fields and add them to particular products or for particular product categories. Supported field types are text, numbers, email, textarea, checkbox, radio and select.
 * Version: 3.0.3
 * Author: Saravana Kumar K
 * Author URI: http://www.iamsark.com/
 * License: GPL
 * Copyright: sarkware
 * WC tested up to: 3.4.4
 */
if (!defined( 'ABSPATH' )) { exit; }

/**
 * 
 * WC Fields Factory's Main Class
 * 
 * @author 		Saravana Kumar K
 * @copyright 	Sarkware Pvt Ltd
 *
 */
class Wcff {
	
	var 
	   	/* Version number and root path details - could be accessed by "wcff()->info" */
	   	$info,
	   	/* Data Access Object reference - could be accessed by "wcff()->dao" */
	   	$dao,
	   	/* Fields interface - could be accessed by "wcff()->field" */
	   	$field,
		/* Fields injector instance - could be accessed by "wcff()->injector" */
		$injector,
		/* Fields Persister instance (which mine the REQUEST object and store the custom fields as Cart Item Data) - could be accessed by "wcff()->persister" */
	    $persister,
	    /* Fields Data Renderer instance - on Cart & Checkout - could be accessed by "wcff()->renderer" */
		$renderer,
		/* Fields Editor instance - on Cart & Checkout (though editing option won't works on Checkout) - could be accessed by "wcff()->editor" */
		$editor,
		/* Pricing & Fee handler instance - could be accessed by "wcff()->negotiator" */
		$negotiator,
		/* Order handler instance - could be accessed by "wcff()->order" */
		$order,
	   	/* Option object - could be accessed by "wcff()->option" */
	   	$option,
	   	/* Html builder object reference - could be accessed by "wcff()->builder" */
	   	$builder,
	   	/* Fields Validator instance - could be accessed by "wcff()->validator" */
	   	$validator,
	   	/* Fields Translator instance - could be accessed by "wcff()->locale" */
		$locale,
	   	/* Holds the Ajax request object comes from WC Fields Factory Admin Interfce - could be accessed by "wcff()->request" */
	   	$request,
	   	/* Holds the Ajax response object which will be sent back to Client - could be accessed by "wcff()->response" */
	   	$response;
	
	public function __construct() {		
	    /* Put some most wanted values on info property */
		$this->info = array(
			'path'				=> plugin_dir_path( __FILE__ ),
			'dir'				=> plugin_dir_url( __FILE__ ),
		    'basename'          => plugin_basename(__FILE__),
			'version'			=> '3.0.3'
		);		
		/* Make sure woocommerce installed and activated */
		if (!function_exists('WC')) {
		    add_action('admin_notices', 'wcff_woocommerce_not_found_notice');
		} else {
    		/* Well time to load the Bootstrap Script */
    		include_once('includes/wcff-loader.php');
    		$loader = new Wcff_Loader($this);		
    		/* Load the necessary fiels to prepare the Env */
    		$loader->load_environment();
    		/* Hook up with 'init' for setting up the Environment */
    		add_action('init', array($loader, 'prepare_environment'), 1);	
		}
	}	
	
}

/**
 * 
 * Returns the Main instance of WC Fields Factory
 * 
 * Helper function for accessing Fields Factory Globally
 * Using this function other plugins & themes can access the WC Fields Factory. thus no need of Global Variable.
 * 
 */
function wcff() {
	/* Expose WC Fields Factory to Global Space */
	global $wcff;
	/* Singleton instance of WC Fields Factory */
	if (!isset($wcff)) {
		$wcff = new Wcff();
	}
	return $wcff;
} 

/* Well use 'plugins_loaded' hook to start WC Fields Factory */
add_action('plugins_loaded', 'wcff', 11);

/* Woocommerce missing notice */
if (!function_exists('wcff_woocommerce_not_found_notice')) {
	function wcff_woocommerce_not_found_notice() {
	?>
        <div class="error">
            <p><?php _e('WC Fields Factory requires WooCommerce, Please make sure it is installed and activated.', 'wc-fields-factory'); ?></p>
        </div>
    <?php
    }
}


?>