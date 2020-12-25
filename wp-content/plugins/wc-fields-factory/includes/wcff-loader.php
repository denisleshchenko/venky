<?php 

if (!defined('ABSPATH')) {exit;}
/**
 * 
 * @author 	    : Saravana Kumar K
 * @copyright   : Sarkware Pvt Ltd
 *
 * This module is responsible for loading and initializing various components of WC Fields Factory
 * 
 */
class Wcff_Loader {
    
    private $wcff;
    
    public function __construct($_wcff) {
        $this->wcff = $_wcff;
    }
    
    /**
     * This has two primary responsible
     * 1. Initialize all the custom post types that WC Fields Factory needed
     * 2. Initialize menu and submenu on wp-admin page 
     */
    public function prepare_environment() {
        include_once( 'wcff-setup.php' );
    }
    
    /**
     * This method is responsible for loading all the classes of WC Fields Factory
     */
    public function load_environment() {
        include_once('wcff-request.php');
        include_once('wcff-response.php');
        include_once('wcff-dao.php');
        include_once('wcff-builder.php');
        include_once('wcff-validator.php');
        include_once('wcff-options.php');
        include_once('wcff-listener.php');        
        include_once('wcff-injector.php');
        include_once('wcff-cart-data.php');
        include_once('wcff-cart-editor.php');
        include_once('wcff-negotiator.php');
        include_once('wcff-persister.php');
        include_once('wcff-order-handler.php');
        include_once('wcff-product-fields.php');
        include_once('wcff-locale.php');
        if ( version_compare( WC()->version, '3.2.0', '>' ) ) {
            include_once('wcff-checkout-fields.php');
        } 
        
        
        if (is_admin()) {
            include_once('wcff-post-handler.php');
            include_once('wcff-admin-fields.php');
            include_once(plugin_dir_path( __FILE__). '../views/meta_box_option.php');
            include_once(plugin_dir_path( __FILE__). '../views/meta_box_field_config_wrapper.php');
        }
        
        /* Instanciate Data Access Object */
        $this->wcff->dao = new Wcff_Dao();
        /* Instanciate UI builder object */
        $this->wcff->builder = new Wcff_Builder();
        /* Instanciate WCFF options */
        $this->wcff->option = new Wcff_Options();
        /* Instanciate Fields Validator */
        $this->wcff->validator = new Wcff_Validator();
        /* Instanciate Fields Injector object */
        $this->wcff->injector = new Wcff_FieldsInjector();
        /* Instanciate Fields Persister object */
        $this->wcff->persister = new Wcff_Persister();
        /* Instanciate Cart & CheckOut Data Render object */
        $this->wcff->renderer = new Wcff_CartDataRenderer();
        /* Instanciate Cart Fields Editor Object */
        $this->wcff->editor = new Wcff_CartEditor();
        /* Instanciate Order Handler object */
        $this->wcff->order = new Wcff_OrderHandler();
        /* Instanciate Pricing & Fee handler object */
        $this->wcff->negotiator = new Wcff_Negotiator();
        /* Instanciate Multilingual object */
        $this->wcff->locale = new Wcff_Locale();
        if ( version_compare( WC()->version, '3.2.0', '>' ) ) {
            /* Instanciate CheckoutFields object */
            $this->wcff->checkout = new Wcff_CheckoutFields();
        }
    }    
	
}

?>