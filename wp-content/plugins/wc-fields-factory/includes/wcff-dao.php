<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * Data access layer for WC Fields Factory
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_Dao {
	
	/* Namespace for WCFF related post meta
     * "wccpf_" for Custom product page Fields ( Front end product page )
     * "wccaf_" for Custom admin page fields ( for Admin Products )
     * "wccsf_" for Custom admin page fields ( for Sub group Fields )
     * "wccrf_" for Custom admin page fields ( for Repeater Fields )
     * "wcccf_" for Custom admin page fields ( for Checkout Fields )
     *  */
	private $wcff_key_prefix = "wccpf_";
	
	/* Holds all the supported field's specific configuration meta */
	private $fields_meta = array();
	
	/* Holds all the configuration meta that are common to all fields ( both Product as well as Admin ) */
	private $common_meta = array();
	
	/* Holds all the configuration meta that are common to Admin Fields */
	private $wccaf_common_meta = array();
	
	public function __construct() {
	    /* Wordpress's Save Post action hook
	     * This is where we would save all the rules for the Fields Group ( post ) that is being saved */
	    add_action( 'save_post', array( $this, 'on_save_post' ), 1, 3 );
	}
	
	/**
	 * 
	 * Set the current post type properties,<br/>
	 * based on this only all the subsequent fields related operation will happen<br/>
	 * this option could be either 'wccpf' for product fields or 'wccaf' for admin fields. 
	 * 
	 * @param string $_type
	 * 
	 */
	public function set_current_post_type( $_type = "wccpf" ) {		
		$this->wcff_key_prefix = $_type . "_";		
	}
	
	/**
	 * 
	 * Return the Fields config meta for Factory View<br/>
	 * Contains entire (specific to each fields) config meta list for each field type.
	 * 
	 * @return array
	 * 
	 */
	public function get_fields_meta() {
		/* Make sure the meta is loaded */
		$this->load_core_meta();
		return $this->fields_meta;
	}
	
	/**
	 * 
	 * Return the Fields config common meta for Factory View<br/>
	 * Contains entire (common for all fields) config meta list for each field type.
	 * 
	 * @return array
	 * 
	 */
	public function get_fields_common_meta() {
		/* Make sure the meta is loaded */
		$this->load_core_meta();
		return $this->common_meta;
	}
	
	/**
	 *
	 * Return the Admin Fields config common meta for Factory View<br/>
	 * Contains entire (common for all admin fields) config meta list for each field type.
	 *
	 * @return array
	 *
	 */
	public function get_admin_fields_comman_meta() {
		/* Make sure the meta is loaded */
		$this->load_core_meta();
		return $this->wccaf_common_meta;
	}
	
	/**
	 *
	 * Loads Fields configuration meta from the file system<br>
	 * Fields specific configuration meta from 'meta/wcff-meta.php'<br>
	 * Common configuration meta from 'meta/wcff-common-meta.php'<br>
	 * Common admin configuration meta from 'meta/wcff-common-wccaf-meta.php'
	 *
	 */
	private function load_core_meta() {
		/* Load core fields config meta */
		if ( ! is_array( $this->fields_meta ) || empty( $this->fields_meta ) ) {
			$this->fields_meta = include( 'meta/wcff-meta.php' );
		}
		/* Load common config meta for all fields */
		if ( ! is_array( $this->common_meta ) || empty( $this->common_meta ) ) {
			$this->common_meta = include( 'meta/wcff-common-meta.php' );
		}
		/* Load common config meta for admin fields */
		if ( ! is_array( $this->wccaf_common_meta ) || empty( $this->wccaf_common_meta ) ) {
			$this->wccaf_common_meta = include( 'meta/wcff-common-wccaf-meta.php' );
		}
	}
	
	/**
	 *
	 * Called whenever user 'Update' or 'Save' post from wp-admin single post view<br/>
	 * This is where the various (Product, Cat, Location ... ) rules for the fields group will be stored in their respective post meta.
	 *
	 * @param integer $_pid
	 * @param WP_Post $_post
	 * @param boolean $_update
	 * @return void|boolean
	 *
	 */
	public function on_save_post( $_pid = 0, $_post, $_update ) {			
		/* Maje sure the post types are valid */
		if ( ! $_pid || ! $_post || ( $_post->post_type != "wccpf" && $_post->post_type != "wccaf" ) ) {
			return false;
		}
		
		$_pid = absint( $_pid );
		
		/* Prepare the post type prefix for meta key */
		$this->wcff_key_prefix = $_post->post_type . "_";
		
		/* Conditional rules - determine which fields group belongs to which products */
		if ( isset( $_REQUEST[ "wcff_condition_rules" ] ) ) {
			delete_post_meta( $_pid, $this->wcff_key_prefix.'condition_rules' );
			add_post_meta( $_pid, $this->wcff_key_prefix.'condition_rules', $_REQUEST[ "wcff_condition_rules" ] );
		}
		
		/* Location rules - specific to Admin Fields */
		if ( isset( $_REQUEST[ "wcff_location_rules" ] ) ) {
			delete_post_meta( $_pid, $this->wcff_key_prefix.'location_rules' );
			add_post_meta( $_pid, $this->wcff_key_prefix.'location_rules', $_REQUEST[ "wcff_location_rules" ] );
		}
		
		/* Field location for each field */
		if ( isset( $_REQUEST[ "field_location_on_product" ] ) ) {
		    $group_location = $_REQUEST[ "field_location_on_product" ];
		    delete_post_meta( $_pid, $this->wcff_key_prefix.'field_location_on_product' );
		    add_post_meta( $_pid, $this->wcff_key_prefix.'field_location_on_product', $group_location );
		}
		
		/* Field location for archive page */
		if ( isset( $_REQUEST[ "field_location_on_archive" ] ) ) {
		    $group_archive_location = $_REQUEST[ "field_location_on_archive" ];
		    delete_post_meta( $_pid, $this->wcff_key_prefix.'field_location_on_archive' );
		    add_post_meta( $_pid, $this->wcff_key_prefix.'field_location_on_archive', $group_archive_location );
		}
		
		/* Update the fields order */
		$this->update_fields_order( $_pid );
		
		return true;		
	}
	
	/**
	 * 
	 * Update the fields sequence order properties for all fields on a given group (represented by $_pid)<br/>
	 * Called when Fields Group got saved or updated.
	 * 
	 * @param integer $_pid
	 * @return boolean
	 * 
	 */
	public function update_fields_order( $_pid = 0 ) {		
	    $fields = $this->load_fields( $_pid, false );
	    /* Update each fields order property */
		foreach ( $fields as $key => $field ) {
			if (isset($_REQUEST[ $key."_order" ])) {
				$field[ "order" ] = $_REQUEST[ $key."_order" ];
				update_post_meta( $_pid, $key, wp_slash( json_encode( $field ) ) );
			}			
		}
		
		return true;		
	}
	
	/**
	 * 
	 * Load conditional rules for given Fields Group Post
	 * 
	 * @param integer $_pid
	 * @return mixed
	 * 
	 */
	public function load_condition_rules( $_pid = 0 ) {		
		$_pid = absint( $_pid );
		/* Since we have renamed 'group_rules' meta as 'condition_rules' we need to make sure it is upto date
		 * and we remove the old 'group_rules' meta as well
		 **/
		$rules = get_post_meta( $_pid, $this->wcff_key_prefix.'group_rules', true );
		if ( $rules && $rules != "" ) {
			delete_post_meta( $_pid, $this->wcff_key_prefix.'group_rules' );
			update_post_meta( $_pid, $this->wcff_key_prefix.'condition_rules', $rules );
		}		
		$condition = get_post_meta( $_pid, $this->wcff_key_prefix.'condition_rules', true );
		
		return apply_filters( 'wcff_condition_rules', $condition, $_pid );		
	}
	
	/**
	 * 
	 * Load locational rules for given Admin Fields Group Post 
	 * 
	 * @param integer $_pid
	 * @return mixed
	 * 
	 */
	public function load_location_rules( $_pid = 0 ) {
		$_pid = absint( $_pid );
		$location = get_post_meta( $_pid, $this->wcff_key_prefix.'location_rules', true );		
		return apply_filters( 'wcff_location_rules', $location, $_pid );		
	}
	
	/**
	 * 
	 * Load locational rules for entire admin fields posts
	 * 
	 * @return mixed
	 * 
	 */
	public function load_all_location_rules() {		
		$location_rules = array();
		$wcffs = get_posts( array (
			'post_type' => "wccaf",
			'posts_per_page' => -1,
			'order' => 'ASC' )
		);
		if ( count( $wcffs ) > 0 ) {
			foreach ( $wcffs as $wcff ) {
				$temp_rules = get_post_meta( $wcff->ID, 'wccaf_location_rules', true );
				$temp_rules = json_decode( $temp_rules, true );
				$location_rules = array_merge( $location_rules, $temp_rules );
			}
		}
		
		return apply_filters( 'wcff_all_location_rules', $location_rules );		
	}
	
	/**
	 * 
	 * Used to load all woocommerce products<br/>
	 * Used in "Conditions" Widget
	 * 
	 * @return 	ARRAY of products ( ids & titles )
	 * 
	 */
	public function load_products() {		
		$productsList = array();
		$products = get_posts( array (
			'post_type' => 'product',
			'posts_per_page' => -1,
			'order' => 'ASC')
		);
		
		if ( count( $products ) > 0 ) {
			foreach ( $products as $product ) {
				$productsList[] = array( "id" => $product->ID, "title" => $product->post_title );
			}
		}
		
		return apply_filters( 'wcff_products', $productsList );		
	}
	
	/**
	 *
	 * Used to load all woocommerce products<br/>
	 * Used in "Conditions" Widget
	 *
	 * @return 	ARRAY of products ( ids & titles )
	 *
	 */
	public function load_products_with_variation() {
		$productsList = array();
		$products = get_posts( array (
				'post_type' => 'product',
				'posts_per_page' => -1,
				'order' => 'ASC')
				);
	
		if ( count( $products ) > 0 ) {
			foreach ( $products as $product ) {
				$product_ob = array();
				if ( version_compare( WC()->version, '2.2.0', '<' ) ) {
					$product_ob    = get_product( $product->ID );
				} else {
					$product_ob    = wc_get_product( $product->ID );
				}
				if( $product_ob->is_type( 'variable' ) ){
					$productsList[] = array( "id" => $product->ID, "title" => $product->post_title );
				}
			}
		}
	
		return apply_filters( 'wcff_products_with_variation', $productsList );
	}
	
	/**
	 * 
	 * Used to load all woocommerce product category<br/>
	 * Used in "Conditions" Widget
	 * 
	 * @return 	ARRAY of product categories ( ids & titles )
	 * 
	 */
	public function load_product_cats() {		
		$product_cats = array();
		$pcat_terms = get_terms( 'product_cat', 'orderby=count&hide_empty=0' );
		
		foreach( $pcat_terms as $pterm ) {
			$product_cats[] = array( "id" => $pterm->slug, "title" => $pterm->name );
		}
		
		return apply_filters( 'wcff_product_categories', $product_cats );		
	}
	
	/**
	 * 
	 * Used to load all woocommerce product tags<br/>
	 * Used in "Conditions" Widget
	 * 
	 * @return 	ARRAY of product tags ( ids & titles )
	 * 
	 */
	public function load_product_tags() {		
		$product_tags = array();
		$ptag_terms = get_terms( 'product_tag', 'orderby=count&hide_empty=0' );
		
		foreach( $ptag_terms as $pterm ) {
			$product_tags[] = array( "id" => $pterm->slug, "title" => $pterm->name );
		}
		
		return apply_filters( 'wcff_product_tags', $product_tags );		
	}
	
	/**
	 * 
	 * Used to load all woocommerce product types<br/>
	 * Used in "Conditions" Widget
	 * 
	 * @return 	ARRAY of product types ( slugs & titles )
	 * 
	 */
	public function load_product_types() {		
		$product_types = array();
		$all_types = array (
			'simple'   => __( 'Simple product', 'woocommerce' ),
			'grouped'  => __( 'Grouped product', 'woocommerce' ),
			'external' => __( 'External/Affiliate product', 'woocommerce' ),
			'variable' => __( 'Variable product', 'woocommerce' )
		);
		
		foreach ( $all_types as $key => $value ) {
			$product_types[] = array( "id" => $key, "title" => $value );
		}
		
		return apply_filters( 'wcff_product_types', $product_types );		
	}
	
	/**
	 *
	 * Used to load all woocommerce product types<br/>
	 * Used in "Conditions" Widget
	 *
	 * @return 	ARRAY of product types ( slugs & titles )
	 *
	 */
	public function load_product_variations( $parent = 0 ) {
		$products_variation_list = array();
		$variations = array();
		$arg = array (
				'post_type' => 'product_variation',
				'posts_per_page' => -1,
				'order' => 'ASC' );
		if( $parent != 0 ){
			$arg['post_parent']  = $parent;
		}
		$variations = get_posts( $arg); 
		foreach ( $variations as $product ) {
			$products_variation_list[] = array( "id" => $product->ID, "title" => $product->post_title );
		}
		return apply_filters( 'wcff_product_variation', $products_variation_list );		
	}
	
	/**
	 * 
	 * Used to load all woocommerce product tabs<br/>
	 * Used in "Location" Widget
	 * 
	 * @return 	ARRAY of product tabs ( titles & tab slugs )
	 * 
	 */
	public function load_product_tabs() {		
		$tabs = array (
			"General Tab" => "woocommerce_product_options_general_product_data",
			"Inventory Tab" => "woocommerce_product_options_inventory_product_data",
			"Shipping Tab" => "woocommerce_product_options_shipping",
			"Attributes Tab" => "woocommerce_product_options_attributes",
			"Related Tab" => "woocommerce_product_options_related",
			"Advanced Tab" => "woocommerce_product_options_advanced",
			"Variable Tab" => "woocommerce_product_after_variable_attributes"
		);
		
		return apply_filters( 'wcff_product_tabs', $tabs );		
	}
	
	/**
	 * 
	 * Used to load all wp context used for meta box<br/>
	 * Used for laying Admin Fields
	 * 
	 * @return 	ARRAY of meta contexts ( slugs & titles )
	 * 	
	 */
	public function load_metabox_contexts() {		
		$contexts = array (
			"normal" => "Normal",
			"advanced" => "Advanced",
			"side" => "Side"
		);
		
		return apply_filters( 'wcff_metabox_contexts', $contexts );		
	}
	
	/**
	 * 
	 * Used to load all wp priorities used for meta box<br/>
	 * Used for laying Admin Fields
	 * 
	 * @return 	ARRAY of meta priorities ( slugs & titles )
	 * 
	 */
	public function load_metabox_priorities() {		
		$priorities = array (
			"low" => "Low",
			"high" => "High",
			"core" => "Core",
			"default" => "Default"
		);
		
		return apply_filters( 'wcff_metabox_priorities', $priorities );		
	}
	
	/**
	 * 
	 * Used to load all woocommerce form fields validation types, to built Checkout Fields
	 * 
	 * @return ARRAY of validation types
	 * 
	 */
	public function load_wcccf_validation_types() {
		return apply_filters( 'wcccf_validation_types', array (
			"required" => "Required",
			"phone" => "Phone",
			"email" => "Email",
			"postcode" => "Post Code"
		) );
	}
	
	
	/**
	 *
	 * This function is used to load all wcff fields (actualy post meta) for a single WCFF post<br/>
	 * Mostly used in editing wccpf fields in admin screen
	 *
	 * @param 	integer	$pid	- WCFF Post Id
	 * @param   boolean	$sort   - Whether returning fields should be sorted
	 * @param   string $type   - Type of fields ( wccpf, wccaf ... )
	 * @return 	array
	 *
	 */
	public function load_fields( $_pid = 0, $_sort = true ) {		
		$fields = array();
		$_pid = absint( $_pid );
		$meta = get_post_meta( $_pid);
		foreach ( $meta as $key => $val ) {
			if ( preg_match( '/'. $this->wcff_key_prefix . '/', $key ) ) {
				if ( $key != $this->wcff_key_prefix . 'condition_rules' &&
					$key != $this->wcff_key_prefix . 'location_rules' &&
					$key != $this->wcff_key_prefix . 'group_rules' &&
					$key != $this->wcff_key_prefix . 'pricing_rules' &&
					$key != $this->wcff_key_prefix . 'fee_rules' &&
					$key != $this->wcff_key_prefix . 'field_rules' &&
					$key != $this->wcff_key_prefix . 'sub_fields_group_rules' &&
				    $key != $this->wcff_key_prefix . 'field_location_on_product' &&
				    $key != $this->wcff_key_prefix . 'field_location_on_archive' ) {
					$fields[ $key ] = json_decode( $val[0], true );
				}
			}
		}
		
		if ( $_sort ) {
			$this->usort_by_column( $fields, "order" );
		}
		
		return apply_filters( 'wcff_fields', $fields, $_pid, $_sort );		
	}
	
	/**
	 * 
	 * Loads all fields of the given Fields Group Post
	 * 
	 * @param number $_pid
	 * @param string $_mkey
	 * @return mixed
	 * 
	 */
	public function load_field( $_pid = 0, $_mkey = "" ) {	
		$_pid = absint( $_pid );
		$field = get_post_meta( $_pid, $_mkey, true );
		return apply_filters( 'wcff_field', $field, $_pid, $_mkey );		
	}
	
	/**
	 * 
	 * Save the given field's config meta as the post meta on a given Fields Group Post.  
	 * 
	 * @param number $_pid
	 * @param object $_payload
	 * @return number|false
	 * 
	 */
	public function save_field( $_pid = 0, $_payload ) {	
		$_pid = absint( $_pid );
		$_payload= apply_filters( 'wcff_before_save_field', $_payload, $_pid );		
		if ( ! isset( $_payload[ "name" ] ) || $_payload[ "name" ] == "_" || $_payload[ "name" ] != "" ) {
		    $_payload[ "key" ] = $this->wcff_key_prefix . $this->url_slug( $_payload[ "name" ], array( 'delimiter' => '_' ) );
		}
		$flg = add_post_meta( $_pid,  $_payload[ "key" ], wp_slash( json_encode( $_payload ) ) ) == false ? false : true;
		return $flg;	
	}
	
	/**
	 * 
	 * Update the given field's config meta as the post meta on a given Fields Group Post.
	 * 
	 * @param number $_pid
	 * @param object $_payload
	 * @return number|boolean
	 * 
	 */
	public function update_field($_pid = 0, $_payload) {
	    $_pid = absint( $_pid );
	    $res  = true;
	    $msg = "";
	    $field_meta_key = "";
	    $field_unopen = isset( $_payload["wcff_unopen_details"] ) ? $_payload["wcff_unopen_details"] : array();
	    $_payload = isset( $_payload["wcff_field_metas"] ) ? $_payload["wcff_field_metas"] : array();
	    for( $i = 0; $i < count( $_payload ); $i++ ){
	        $payload = $_payload[$i];
	        if ( isset( $payload[ "key" ] ) && $payload[ "key" ] != "" ) {
	            $field_meta_key = $payload[ "key" ];
	        } else {
	            $field_meta_key = "";
	        }
    		if( $res ){
    		    $post_meta = get_post_meta( $_pid, $field_meta_key, true );
    		    $check_not_empty = !empty( $field_meta_key ) && !empty( $post_meta );
    		    if( $check_not_empty ){
    		        $payload = apply_filters( 'wcff_before_update_field', $_payload[$i], $_pid );
    		        delete_post_meta( $_pid, $field_meta_key );
    		        if( add_post_meta( $_pid,  $field_meta_key, wp_slash( json_encode( $payload ) ) ) == false ) {
    		            $res = false;
    		            $msg = "Failed to update the custom field";
    		        }
        		} else {
        		    $res = $this->save_field( $_pid, $_payload[$i] );
        		    if( !$res ){
        		        $msg = "Failed to create custom field";
        		    }
        		}
    		}
	    }
	    
	    foreach( $field_unopen as $key => $data ){
	       $field_meta = get_post_meta( $_pid,  $key, true );
	       $check_empty = !empty( $field_meta );
	       if( $check_empty ){
	           $field_meta_json = json_decode( $field_meta, true );
	           foreach( $data as $meta_key => $meta_val ){
	               $field_meta_json[$meta_key] = $meta_val;
	           }
	           delete_post_meta( $_pid, $key );
	           if( add_post_meta( $_pid,  $key, wp_slash( json_encode( $field_meta_json ) ) ) == false ) {
	               $res = false;
	               $msg = "Failed to update the custom field";
	           }
	       }
	    }
	    return array( "res" => $res, "msg" => $msg );
	}
	
	/**
	 * 
	 * Remove the given field from Fields Group Post
	 * 
	 * @param number $_pid
	 * @param string $_mkey
	 * @return boolean
	 * 
	 */
	public function remove_field( $_pid = 0, $_mkey ) {
		$_pid = absint( $_pid );
		$mkey = apply_filters( 'wcff_before_remove_field', $_mkey, $_pid );
		return delete_post_meta( $_pid, $_mkey );		
	}
	
	/**
	 *
	 * This function is used to Load all WCCPF groups. which is used by "wccpf_product_form" module<br/>
	 * to render actual wccpf fields on the Product Page.
	 *
	 * @param 	integer	$pid	- Product Id
	 * @param   string $type   - Type of fields ( wccpf, wccaf ... )
	 * @return 	array ( Two Dimentional )
	 * 
	 */
	public function load_fields_for_product( $_pid, $_type = "wccpf", $_location = "product-page", $p_location = "", $_dont_follow = true, $_is_variation = false ) {		
		$fields = array();
		$all_fields = array();
		$this->wcff_key_prefix = $_type . "_";
		
		$wcffs = get_posts( array (
			'post_type' => $_type,
			'posts_per_page' => -1,
			'order' => 'ASC' )
		);
		
		$_pid = absint( $_pid );
		
		if ( count( $wcffs ) > 0 ) {
			foreach ( $wcffs as $wcff ) {
			    $field_group_location = get_post_meta(  $wcff->ID, $this->wcff_key_prefix."field_location_on_product", true );
			    $field_group_location = empty( $field_group_location ) ? "use_global_setting" : $field_group_location;
			    // archive page location
			    $field_group_location_archive = get_post_meta(  $wcff->ID, $this->wcff_key_prefix."field_location_on_archive", true );
			    $field_group_location_archive = empty( $field_group_location_archive ) ? "none" : $field_group_location_archive;
			    $flg  = false;
			    $field_glob_location = isset(wcff()->option->get_options()["field_location"]) ? wcff()->option->get_options()["field_location"] : "woocommerce_before_add_to_cart_button";
			    if($_dont_follow){
			        $flg = true;
			    }
			    if( !$flg && ( (  $field_group_location == "use_global_setting" && $field_glob_location == $p_location )  || $field_group_location == $p_location || ( $field_group_location_archive == $p_location && $field_group_location_archive != "none"  ) ) ){
			        $flg = true;
			    }
			   
			    if( $flg ){
    				$fields = array();
    				$crules_applicable = false;
    				$lrules_applicable = true;
    				$meta = get_post_meta( $wcff->ID );
    				$condition_rules = $this->load_condition_rules( $wcff->ID );
    				$condition_rules = json_decode( $condition_rules, true );
    				
    				if ( is_array( $condition_rules ) ) {
    					$crules_applicable = $this->check_for_product( $_pid, $condition_rules, $_location );
    				} else {
    					$crules_applicable = true;
    				}
    				if ( $_type == "wccaf" ) {
    					$old_location = $_location == "cart-page" ? "any" :  $_location;
    					$location_rules = get_post_meta( $wcff->ID, $this->wcff_key_prefix . 'location_rules', true );
    					$location_rules = json_decode( $location_rules, true );
    					if ( is_array( $location_rules ) && $old_location != "any" ) {
    						$lrules_applicable = $this->check_for_location( $_pid, $location_rules, $old_location );
    					} else {
    						$lrules_applicable = true;
    					}
    				}
    				
    				if ( $crules_applicable && $lrules_applicable ) {
    				   
    					foreach ( $meta as $key => $val ) {
    						if ( preg_match( '/' . $this->wcff_key_prefix . '/', $key ) ) {
    							if ( $key != $this->wcff_key_prefix . 'condition_rules' &&
    								$key != $this->wcff_key_prefix . 'location_rules' &&
    								$key != $this->wcff_key_prefix . 'group_rules' &&
    								$key != $this->wcff_key_prefix . 'pricing_rules' &&
    								$key != $this->wcff_key_prefix . 'fee_rules' &&
    								$key != $this->wcff_key_prefix . 'field_rules' &&
    								$key != $this->wcff_key_prefix . 'sub_fields_group_rules' &&
    							    $key != $this->wcff_key_prefix . 'field_location_on_product'&&
    							    $key != $this->wcff_key_prefix . 'field_location'&&
    							    $key != $this->wcff_key_prefix . 'field_location_on_archive') {
    								$fields[ $key ] = json_decode( $val[0], true );
    							}
    						}
    					}
    					$this->usort_by_column( $fields, "order" );
    					if( $_is_variation ){
    					    $all_fields[] = array( "location" => ( $field_group_location == "use_global_setting" ? $field_glob_location : $field_group_location ) , "fields" => $fields );
    					} else {
    					    $all_fields[] = $fields;
    					}
    				}
    			}
			}
		}
		if($_is_variation){
		    return apply_filters( 'wcff_fields_for_product_variation', $all_fields, $_pid, $_type, $_location );	
		} else {
		    return apply_filters( 'wcff_fields_for_product', $all_fields, $_pid, $_type, $_location );	
		}
	}
	
	/**
	 * 
	 * WCFF Condition Rules Engine, This is function used to determine whether or not to include<br/>
	 * a particular wccpf group fields to a particular Product
	 * 
	 * @param 	integer		$_pid	- Product Id
	 * @param 	array 		$_groups
	 * @return 	boolean
	 * 
	 */
	public function check_for_product( $_pid, $_groups, $location = "product-page" ) {
		$matches = array();
		$final_matches = array();
		foreach ( $_groups as $rules ) {
			$ands = array();
			foreach ( $rules as $rule ) {
				if(  $rule[ "context" ] != "product_variation" && ( wcff()->request["context"] == "wcff_variation_fields" || $location == "cart-page" )  ){
					return false;
				}
				if ( $rule[ "context" ] == "product" ) {
					if ( $rule[ "endpoint" ] == -1 ) {
						$ands[] = ( $rule[ "logic" ] == "==" );
					} else {
						if ( $rule[ "logic" ] == "==" ) {
							$ands[] = ( $_pid == $rule[ "endpoint" ] );
						} else {
							$ands[] = ( $_pid != $rule[ "endpoint" ] );
						}
					}
				} else if($rule[ "context" ] == "product_variation"){
					if ( $rule[ "endpoint" ] == -1 ) {
						if( get_post_type( $_pid ) == "product_variation" ){
							$ands[] = ( $rule[ "logic" ] == "==" );
						} else {
							$ands[] = false;
						}
					} else {
						if ( $rule[ "logic" ] == "==" ) {
							if( get_post_type( $_pid ) == "product_variation" ){
								$ands[] = ( $_pid == $rule[ "endpoint" ] );
							} else {
								$ands[] = false;
							}
						} else {
							if( get_post_type( $_pid ) == "product_variation" ){
								$ands[] = ( $_pid != $rule[ "endpoint" ] );
							} else {
								$ands[] = false;
							}
						}
					}
				} else if ( $rule[ "context" ] == "product_cat" ) {
					if ( $rule[ "endpoint" ] == -1 ) {
						$ands[] = ( $rule[ "logic" ] == "==" );
					} else {
						if ( $rule[ "logic" ] == "==" ) {
							$ands[] = has_term( $rule[ "endpoint" ], 'product_cat', $_pid );
						} else {
							$ands[] = !has_term( $rule[ "endpoint" ], 'product_cat', $_pid );
						}
					}
				}  else if ( $rule[ "context" ] == "product_tag" ) {
					if ( $rule[ "endpoint" ] == -1 ) {
						$ands[] = ( $rule[ "logic" ] == "==" );
					} else {
						if ( $rule[ "logic" ] == "==" ) {
							$ands[] = has_term( $rule[ "endpoint" ], 'product_tag', $_pid );
						} else {
							$ands[] = !has_term( $rule[ "endpoint" ], 'product_tag', $_pid );
						}
					}
				}  else if ( $rule[ "context" ] == "product_type" ) {
					if ( $rule[ "endpoint" ] == -1 ) {
						$ands[] = ( $rule[ "logic" ] == "==" );
					} else {
						$ptype = wp_get_object_terms( $_pid, 'product_type' );
						$ands[] = ( $ptype[0]->slug == $rule[ "endpoint" ] );
					}
				} 
			}
			$matches[] = $ands;
		}
		
		foreach ( $matches as $match ) {
			$final_matches[] = ! in_array( false, $match );
		}
		
		return in_array( true, $final_matches );
	}
	
	/**
	 * 
	 * WCFF Location Rules Engine, This is function used to determine where does the  particular wccaf fields group<br/>
	 * to be placed. in the product view, product cat view or one of any product data sections ( Tabs )<br/>
	 * applicable only for wccaf post_type.
	 * 
	 * @param integer $_pid
	 * @param array	$_groups
	 * @param string $_location
	 *
	 */
	public function check_for_location( $_pid, $_groups, $_location, $product_cart_page = "product-page" ) {
		foreach ($_groups as $rules) {
			foreach ($rules as $rule) {
				
				if ($rule["context"] == "location_product_data") {
					if ($rule["endpoint"] == $_location && $rule["logic"] == "==") {
						return true;
					}
				}
				if ($rule["context"] == "location_product" && $_location == "admin_head-post.php") {
					return true;
				}
				if ($rule["context"] == "location_product_cat" && ($_location == "product_cat_add_form_fields" || $_location == "product_cat_edit_form_fields"))  {
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * 
	 * Order the array for the given property.
	 * 
	 * @param array $_arr
	 * @param string $_col
	 * @param string $_dir
	 * 
	 */
	public function usort_by_column(&$_arr, $_col, $_dir = SORT_ASC) {		
		$sort_col = array();
		foreach ($_arr as $key=> $row) {
			$sort_col[$key] = $row[$_col];
		}
		array_multisort( $sort_col, $_dir, $_arr);		
	}
	
	/**
	 * 
	 * Create a web friendly URL slug from a string.
	 *
	 * @author Sean Murphy <sean@iamseanmurphy.com>
	 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
	 * @license http://creativecommons.org/publicdomain/zero/1.0/
	 *
	 * @param string $str
	 * @param array $options
	 * @return string
	 * 
	 */
	function url_slug( $_str, $_options = array() ) {
		
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$_str = mb_convert_encoding( ( string ) $_str, 'UTF-8', mb_list_encodings() );
		
		$defaults = array (
			'delimiter' => '-',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => false,
		);
		
		// Merge options
		$_options = array_merge( $defaults, $_options );
		
		$char_map = array (
			// Latin
			'ÃƒÆ’Ã¢â€šÂ¬' => 'A', 'ÃƒÆ’Ã¯Â¿Â½' => 'A', 'ÃƒÆ’Ã¢â‚¬Å¡' => 'A', 'ÃƒÆ’Ã†â€™' => 'A', 'ÃƒÆ’Ã¢â‚¬Å¾' => 'A', 'ÃƒÆ’Ã¢â‚¬Â¦' => 'A', 'ÃƒÆ’Ã¢â‚¬Â ' => 'AE', 'ÃƒÆ’Ã¢â‚¬Â¡' => 'C',
			'ÃƒÆ’Ã‹â€ ' => 'E', 'ÃƒÆ’Ã¢â‚¬Â°' => 'E', 'ÃƒÆ’Ã…Â ' => 'E', 'ÃƒÆ’Ã¢â‚¬Â¹' => 'E', 'ÃƒÆ’Ã…â€™' => 'I', 'ÃƒÆ’Ã¯Â¿Â½' => 'I', 'ÃƒÆ’Ã…Â½' => 'I', 'ÃƒÆ’Ã¯Â¿Â½' => 'I',
			'ÃƒÆ’Ã¯Â¿Â½' => 'D', 'ÃƒÆ’Ã¢â‚¬Ëœ' => 'N', 'ÃƒÆ’Ã¢â‚¬â„¢' => 'O', 'ÃƒÆ’Ã¢â‚¬Å“' => 'O', 'ÃƒÆ’Ã¢â‚¬ï¿½' => 'O', 'ÃƒÆ’Ã¢â‚¬Â¢' => 'O', 'ÃƒÆ’Ã¢â‚¬â€œ' => 'O', 'Ãƒâ€¦Ã¯Â¿Â½' => 'O',
			'ÃƒÆ’Ã‹Å“' => 'O', 'ÃƒÆ’Ã¢â€žÂ¢' => 'U', 'ÃƒÆ’Ã…Â¡' => 'U', 'ÃƒÆ’Ã¢â‚¬Âº' => 'U', 'ÃƒÆ’Ã…â€œ' => 'U', 'Ãƒâ€¦Ã‚Â°' => 'U', 'ÃƒÆ’Ã¯Â¿Â½' => 'Y', 'ÃƒÆ’Ã…Â¾' => 'TH',
			'ÃƒÆ’Ã…Â¸' => 'ss',
			'ÃƒÆ’Ã‚Â ' => 'a', 'ÃƒÆ’Ã‚Â¡' => 'a', 'ÃƒÆ’Ã‚Â¢' => 'a', 'ÃƒÆ’Ã‚Â£' => 'a', 'ÃƒÆ’Ã‚Â¤' => 'a', 'ÃƒÆ’Ã‚Â¥' => 'a', 'ÃƒÆ’Ã‚Â¦' => 'ae', 'ÃƒÆ’Ã‚Â§' => 'c',
			'ÃƒÆ’Ã‚Â¨' => 'e', 'ÃƒÆ’Ã‚Â©' => 'e', 'ÃƒÆ’Ã‚Âª' => 'e', 'ÃƒÆ’Ã‚Â«' => 'e', 'ÃƒÆ’Ã‚Â¬' => 'i', 'ÃƒÆ’Ã‚Â­' => 'i', 'ÃƒÆ’Ã‚Â®' => 'i', 'ÃƒÆ’Ã‚Â¯' => 'i',
			'ÃƒÆ’Ã‚Â°' => 'd', 'ÃƒÆ’Ã‚Â±' => 'n', 'ÃƒÆ’Ã‚Â²' => 'o', 'ÃƒÆ’Ã‚Â³' => 'o', 'ÃƒÆ’Ã‚Â´' => 'o', 'ÃƒÆ’Ã‚Âµ' => 'o', 'ÃƒÆ’Ã‚Â¶' => 'o', 'Ãƒâ€¦Ã¢â‚¬Ëœ' => 'o',
			'ÃƒÆ’Ã‚Â¸' => 'o', 'ÃƒÆ’Ã‚Â¹' => 'u', 'ÃƒÆ’Ã‚Âº' => 'u', 'ÃƒÆ’Ã‚Â»' => 'u', 'ÃƒÆ’Ã‚Â¼' => 'u', 'Ãƒâ€¦Ã‚Â±' => 'u', 'ÃƒÆ’Ã‚Â½' => 'y', 'ÃƒÆ’Ã‚Â¾' => 'th',
			'ÃƒÆ’Ã‚Â¿' => 'y',
			// Latin symbols
			'Ãƒâ€šÃ‚Â©' => '(c)',
			// Greek
			'ÃƒÅ½Ã¢â‚¬Ëœ' => 'A', 'ÃƒÅ½Ã¢â‚¬â„¢' => 'B', 'ÃƒÅ½Ã¢â‚¬Å“' => 'G', 'ÃƒÅ½Ã¢â‚¬ï¿½' => 'D', 'ÃƒÅ½Ã¢â‚¬Â¢' => 'E', 'ÃƒÅ½Ã¢â‚¬â€œ' => 'Z', 'ÃƒÅ½Ã¢â‚¬â€�' => 'H', 'ÃƒÅ½Ã‹Å“' => '8',
			'ÃƒÅ½Ã¢â€žÂ¢' => 'I', 'ÃƒÅ½Ã…Â¡' => 'K', 'ÃƒÅ½Ã¢â‚¬Âº' => 'L', 'ÃƒÅ½Ã…â€œ' => 'M', 'ÃƒÅ½Ã¯Â¿Â½' => 'N', 'ÃƒÅ½Ã…Â¾' => '3', 'ÃƒÅ½Ã…Â¸' => 'O', 'ÃƒÅ½Ã‚Â ' => 'P',
			'ÃƒÅ½Ã‚Â¡' => 'R', 'ÃƒÅ½Ã‚Â£' => 'S', 'ÃƒÅ½Ã‚Â¤' => 'T', 'ÃƒÅ½Ã‚Â¥' => 'Y', 'ÃƒÅ½Ã‚Â¦' => 'F', 'ÃƒÅ½Ã‚Â§' => 'X', 'ÃƒÅ½Ã‚Â¨' => 'PS', 'ÃƒÅ½Ã‚Â©' => 'W',
			'ÃƒÅ½Ã¢â‚¬Â ' => 'A', 'ÃƒÅ½Ã‹â€ ' => 'E', 'ÃƒÅ½Ã…Â ' => 'I', 'ÃƒÅ½Ã…â€™' => 'O', 'ÃƒÅ½Ã…Â½' => 'Y', 'ÃƒÅ½Ã¢â‚¬Â°' => 'H', 'ÃƒÅ½Ã¯Â¿Â½' => 'W', 'ÃƒÅ½Ã‚Âª' => 'I',
			'ÃƒÅ½Ã‚Â«' => 'Y',
			'ÃƒÅ½Ã‚Â±' => 'a', 'ÃƒÅ½Ã‚Â²' => 'b', 'ÃƒÅ½Ã‚Â³' => 'g', 'ÃƒÅ½Ã‚Â´' => 'd', 'ÃƒÅ½Ã‚Âµ' => 'e', 'ÃƒÅ½Ã‚Â¶' => 'z', 'ÃƒÅ½Ã‚Â·' => 'h', 'ÃƒÅ½Ã‚Â¸' => '8',
			'ÃƒÅ½Ã‚Â¹' => 'i', 'ÃƒÅ½Ã‚Âº' => 'k', 'ÃƒÅ½Ã‚Â»' => 'l', 'ÃƒÅ½Ã‚Â¼' => 'm', 'ÃƒÅ½Ã‚Â½' => 'n', 'ÃƒÅ½Ã‚Â¾' => '3', 'ÃƒÅ½Ã‚Â¿' => 'o', 'Ãƒï¿½Ã¢â€šÂ¬' => 'p',
			'Ãƒï¿½Ã¯Â¿Â½' => 'r', 'Ãƒï¿½Ã†â€™' => 's', 'Ãƒï¿½Ã¢â‚¬Å¾' => 't', 'Ãƒï¿½Ã¢â‚¬Â¦' => 'y', 'Ãƒï¿½Ã¢â‚¬Â ' => 'f', 'Ãƒï¿½Ã¢â‚¬Â¡' => 'x', 'Ãƒï¿½Ã‹â€ ' => 'ps', 'Ãƒï¿½Ã¢â‚¬Â°' => 'w',
			'ÃƒÅ½Ã‚Â¬' => 'a', 'ÃƒÅ½Ã‚Â­' => 'e', 'ÃƒÅ½Ã‚Â¯' => 'i', 'Ãƒï¿½Ã…â€™' => 'o', 'Ãƒï¿½Ã¯Â¿Â½' => 'y', 'ÃƒÅ½Ã‚Â®' => 'h', 'Ãƒï¿½Ã…Â½' => 'w', 'Ãƒï¿½Ã¢â‚¬Å¡' => 's',
			'Ãƒï¿½Ã…Â ' => 'i', 'ÃƒÅ½Ã‚Â°' => 'y', 'Ãƒï¿½Ã¢â‚¬Â¹' => 'y', 'ÃƒÅ½Ã¯Â¿Â½' => 'i',
			// Turkish
			'Ãƒâ€¦Ã…Â¾' => 'S', 'Ãƒâ€žÃ‚Â°' => 'I', 'ÃƒÆ’Ã¢â‚¬Â¡' => 'C', 'ÃƒÆ’Ã…â€œ' => 'U', 'ÃƒÆ’Ã¢â‚¬â€œ' => 'O', 'Ãƒâ€žÃ…Â¾' => 'G',
			'Ãƒâ€¦Ã…Â¸' => 's', 'Ãƒâ€žÃ‚Â±' => 'i', 'ÃƒÆ’Ã‚Â§' => 'c', 'ÃƒÆ’Ã‚Â¼' => 'u', 'ÃƒÆ’Ã‚Â¶' => 'o', 'Ãƒâ€žÃ…Â¸' => 'g',
			// Russian
			'Ãƒï¿½Ã¯Â¿Â½' => 'A', 'Ãƒï¿½Ã¢â‚¬Ëœ' => 'B', 'Ãƒï¿½Ã¢â‚¬â„¢' => 'V', 'Ãƒï¿½Ã¢â‚¬Å“' => 'G', 'Ãƒï¿½Ã¢â‚¬ï¿½' => 'D', 'Ãƒï¿½Ã¢â‚¬Â¢' => 'E', 'Ãƒï¿½Ã¯Â¿Â½' => 'Yo', 'Ãƒï¿½Ã¢â‚¬â€œ' => 'Zh',
			'Ãƒï¿½Ã¢â‚¬â€�' => 'Z', 'Ãƒï¿½Ã‹Å“' => 'I', 'Ãƒï¿½Ã¢â€žÂ¢' => 'J', 'Ãƒï¿½Ã…Â¡' => 'K', 'Ãƒï¿½Ã¢â‚¬Âº' => 'L', 'Ãƒï¿½Ã…â€œ' => 'M', 'Ãƒï¿½Ã¯Â¿Â½' => 'N', 'Ãƒï¿½Ã…Â¾' => 'O',
			'Ãƒï¿½Ã…Â¸' => 'P', 'Ãƒï¿½Ã‚Â ' => 'R', 'Ãƒï¿½Ã‚Â¡' => 'S', 'Ãƒï¿½Ã‚Â¢' => 'T', 'Ãƒï¿½Ã‚Â£' => 'U', 'Ãƒï¿½Ã‚Â¤' => 'F', 'Ãƒï¿½Ã‚Â¥' => 'H', 'Ãƒï¿½Ã‚Â¦' => 'C',
			'Ãƒï¿½Ã‚Â§' => 'Ch', 'Ãƒï¿½Ã‚Â¨' => 'Sh', 'Ãƒï¿½Ã‚Â©' => 'Sh', 'Ãƒï¿½Ã‚Âª' => '', 'Ãƒï¿½Ã‚Â«' => 'Y', 'Ãƒï¿½Ã‚Â¬' => '', 'Ãƒï¿½Ã‚Â­' => 'E', 'Ãƒï¿½Ã‚Â®' => 'Yu',
			'Ãƒï¿½Ã‚Â¯' => 'Ya',
			'Ãƒï¿½Ã‚Â°' => 'a', 'Ãƒï¿½Ã‚Â±' => 'b', 'Ãƒï¿½Ã‚Â²' => 'v', 'Ãƒï¿½Ã‚Â³' => 'g', 'Ãƒï¿½Ã‚Â´' => 'd', 'Ãƒï¿½Ã‚Âµ' => 'e', 'Ãƒâ€˜Ã¢â‚¬Ëœ' => 'yo', 'Ãƒï¿½Ã‚Â¶' => 'zh',
			'Ãƒï¿½Ã‚Â·' => 'z', 'Ãƒï¿½Ã‚Â¸' => 'i', 'Ãƒï¿½Ã‚Â¹' => 'j', 'Ãƒï¿½Ã‚Âº' => 'k', 'Ãƒï¿½Ã‚Â»' => 'l', 'Ãƒï¿½Ã‚Â¼' => 'm', 'Ãƒï¿½Ã‚Â½' => 'n', 'Ãƒï¿½Ã‚Â¾' => 'o',
			'Ãƒï¿½Ã‚Â¿' => 'p', 'Ãƒâ€˜Ã¢â€šÂ¬' => 'r', 'Ãƒâ€˜Ã¯Â¿Â½' => 's', 'Ãƒâ€˜Ã¢â‚¬Å¡' => 't', 'Ãƒâ€˜Ã†â€™' => 'u', 'Ãƒâ€˜Ã¢â‚¬Å¾' => 'f', 'Ãƒâ€˜Ã¢â‚¬Â¦' => 'h', 'Ãƒâ€˜Ã¢â‚¬Â ' => 'c',
			'Ãƒâ€˜Ã¢â‚¬Â¡' => 'ch', 'Ãƒâ€˜Ã‹â€ ' => 'sh', 'Ãƒâ€˜Ã¢â‚¬Â°' => 'sh', 'Ãƒâ€˜Ã…Â ' => '', 'Ãƒâ€˜Ã¢â‚¬Â¹' => 'y', 'Ãƒâ€˜Ã…â€™' => '', 'Ãƒâ€˜Ã¯Â¿Â½' => 'e', 'Ãƒâ€˜Ã…Â½' => 'yu',
			'Ãƒâ€˜Ã¯Â¿Â½' => 'ya',
			// Ukrainian
			'Ãƒï¿½Ã¢â‚¬Å¾' => 'Ye', 'Ãƒï¿½Ã¢â‚¬Â ' => 'I', 'Ãƒï¿½Ã¢â‚¬Â¡' => 'Yi', 'Ãƒâ€™Ã¯Â¿Â½' => 'G',
			'Ãƒâ€˜Ã¢â‚¬ï¿½' => 'ye', 'Ãƒâ€˜Ã¢â‚¬â€œ' => 'i', 'Ãƒâ€˜Ã¢â‚¬â€�' => 'yi', 'Ãƒâ€™Ã¢â‚¬Ëœ' => 'g',
			// Czech
			'Ãƒâ€žÃ…â€™' => 'C', 'Ãƒâ€žÃ…Â½' => 'D', 'Ãƒâ€žÃ…Â¡' => 'E', 'Ãƒâ€¦Ã¢â‚¬Â¡' => 'N', 'Ãƒâ€¦Ã‹Å“' => 'R', 'Ãƒâ€¦Ã‚Â ' => 'S', 'Ãƒâ€¦Ã‚Â¤' => 'T', 'Ãƒâ€¦Ã‚Â®' => 'U',
			'Ãƒâ€¦Ã‚Â½' => 'Z',
			'Ãƒâ€žÃ¯Â¿Â½' => 'c', 'Ãƒâ€žÃ¯Â¿Â½' => 'd', 'Ãƒâ€žÃ¢â‚¬Âº' => 'e', 'Ãƒâ€¦Ã‹â€ ' => 'n', 'Ãƒâ€¦Ã¢â€žÂ¢' => 'r', 'Ãƒâ€¦Ã‚Â¡' => 's', 'Ãƒâ€¦Ã‚Â¥' => 't', 'Ãƒâ€¦Ã‚Â¯' => 'u',
			'Ãƒâ€¦Ã‚Â¾' => 'z',
			// Polish
			'Ãƒâ€žÃ¢â‚¬Å¾' => 'A', 'Ãƒâ€žÃ¢â‚¬Â ' => 'C', 'Ãƒâ€žÃ‹Å“' => 'e', 'Ãƒâ€¦Ã¯Â¿Â½' => 'L', 'Ãƒâ€¦Ã†â€™' => 'N', 'ÃƒÆ’Ã¢â‚¬Å“' => 'o', 'Ãƒâ€¦Ã…Â¡' => 'S', 'Ãƒâ€¦Ã‚Â¹' => 'Z',
			'Ãƒâ€¦Ã‚Â»' => 'Z',
			'Ãƒâ€žÃ¢â‚¬Â¦' => 'a', 'Ãƒâ€žÃ¢â‚¬Â¡' => 'c', 'Ãƒâ€žÃ¢â€žÂ¢' => 'e', 'Ãƒâ€¦Ã¢â‚¬Å¡' => 'l', 'Ãƒâ€¦Ã¢â‚¬Å¾' => 'n', 'ÃƒÆ’Ã‚Â³' => 'o', 'Ãƒâ€¦Ã¢â‚¬Âº' => 's', 'Ãƒâ€¦Ã‚Âº' => 'z',
			'Ãƒâ€¦Ã‚Â¼' => 'z',
			// Latvian
			'Ãƒâ€žÃ¢â€šÂ¬' => 'A', 'Ãƒâ€žÃ…â€™' => 'C', 'Ãƒâ€žÃ¢â‚¬â„¢' => 'E', 'Ãƒâ€žÃ‚Â¢' => 'G', 'Ãƒâ€žÃ‚Âª' => 'i', 'Ãƒâ€žÃ‚Â¶' => 'k', 'Ãƒâ€žÃ‚Â»' => 'L', 'Ãƒâ€¦Ã¢â‚¬Â¦' => 'N',
			'Ãƒâ€¦Ã‚Â ' => 'S', 'Ãƒâ€¦Ã‚Âª' => 'u', 'Ãƒâ€¦Ã‚Â½' => 'Z',
			'Ãƒâ€žÃ¯Â¿Â½' => 'a', 'Ãƒâ€žÃ¯Â¿Â½' => 'c', 'Ãƒâ€žÃ¢â‚¬Å“' => 'e', 'Ãƒâ€žÃ‚Â£' => 'g', 'Ãƒâ€žÃ‚Â«' => 'i', 'Ãƒâ€žÃ‚Â·' => 'k', 'Ãƒâ€žÃ‚Â¼' => 'l', 'Ãƒâ€¦Ã¢â‚¬Â ' => 'n',
			'Ãƒâ€¦Ã‚Â¡' => 's', 'Ãƒâ€¦Ã‚Â«' => 'u', 'Ãƒâ€¦Ã‚Â¾' => 'z'
		);
		
		// Make custom replacements
		$_str = preg_replace( array_keys( $_options[ 'replacements' ] ), $_options[ 'replacements' ], $_str );
		
		// Transliterate characters to ASCII
		if ( $_options[ 'transliterate' ] ) {
			$_str = str_replace( array_keys( $char_map ), $char_map, $_str );
		}
		
		// Replace non-alphanumeric characters with our delimiter
		$_str = preg_replace( '/[^\p{L}\p{Nd}]+/u', $_options[ 'delimiter' ], $_str );
		
		// Remove duplicate delimiters
		$_str = preg_replace( '/(' . preg_quote( $_options[ 'delimiter' ], '/' ) . '){2,}/', '$1', $_str );
		
		// Truncate slug to max. characters
		$_str= mb_substr( $_str, 0, ( $_options[ 'limit' ] ? $_options[ 'limit' ] : mb_strlen( $_str, 'UTF-8' ) ), 'UTF-8' );
		
		// Remove delimiter from ends
		$_str = trim( $_str, $_options[ 'delimiter' ] );
		
		return $_options[ 'lowercase' ] ? mb_strtolower( $_str, 'UTF-8' ) : $_str;
		
	}
}

?>