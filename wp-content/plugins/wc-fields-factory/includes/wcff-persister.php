<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }
/**
 * 
 * One of the core module which is responsible for mining the $_REQUEST object for custom fields
 * and retrive the value and store it as the meta on Cart Line Item.
 * 
 * @author : Saravana Kumar K
 * @copyright : Sarkware Pvt Ltd
 *
 */
class Wcff_Persister {
	
    /* ID of the product that is being Added To Cart */
	private $product_id;
	/* Cart item custom data holder */
	private $cart_item_data;
	
	/* Fields cloning flaq */
	private $fields_cloning = "no";
	/* Holds product fields list (from all group) */
	private $product_fields = null;
	/* Holds admin fields list (from all group) */
	private $admin_fields = null;
	
	public function __construct() {}

	/**
	 * 
	 * This method will be called whenever an Add To Cart operation performed<br/> 
	 * It does the Mining & extracting user submitted custo fields data and store them as Cart Item Data. 
	 * 
	 * @param array $_cart_item_data
	 * @param integer $_product_id
	 * @return array| unknown
	 * 
	 */
    public function persist($_cart_item_data, $_product_id, $_variation_id = null) {
        $this->product_id = $_product_id;
        $this->cart_item_data = $_cart_item_data;
        /* Make sure it is an Array */
        if (! is_array($this->cart_item_data)) {
            $this->cart_item_data = array();
        }
        
        $wccpf_options = wcff()->option->get_options();
        $this->fields_cloning = isset($wccpf_options["fields_cloning"]) ? $wccpf_options["fields_cloning"] : "no";
       
        $this->product_fields = wcff()->dao->load_fields_for_product($this->product_id, 'wccpf');
        $this->admin_fields = wcff()->dao->load_fields_for_product($this->product_id, 'wccaf', 'any');
        if( isset( $_variation_id ) && $_variation_id != null  && $_variation_id != 0 ){
        	$this->product_fields = array_merge( $this->product_fields, wcff()->dao->load_fields_for_product($_variation_id, 'wccpf', 'cart-page') );
        	$this->admin_fields =  array_merge($this->admin_fields, wcff()->dao->load_fields_for_product($_variation_id, 'wccaf', 'cart-page'));
        }
       
        /* Persist Product Fields */
        $this->persist_product_fields();
        /* Persist Admin Fields that has been configured to show on Product Page */
        $this->persist_admin_fields();
        /* Return the prepared custom fields (key=>value) list */  
        
        
        return $this->cart_item_data;
    }
	
    /**
     * Mining the $_REQUEST object for Product Fields
     */
	private function persist_product_fields() {
        /*
         * Normal mining process on $_REQUEST object
         * Since we have field level cloning option we have to mine
         * even if cloning option is enabled
         */
        foreach ($this->product_fields as $title => $fields) {
            if (is_array($fields) && count($fields)) {
                foreach ($fields as $field) {
                    if (isset($_REQUEST[$field["name"]]) || isset($_FILES[$field["name"]])) {
                        $this->persist_field($field, ($field["type"] != "file") ? $_REQUEST[$field["name"]] : $_FILES[$field["name"]]);
                    }
                }
            }
        }
        if ($this->fields_cloning == "yes") {
            /* Mining process on $_REQUEST object with cloning option */
            if (isset($_REQUEST["quantity"])) {
                $pcount = intval($_REQUEST["quantity"]);
                foreach ($this->product_fields as $title => $fields) {
                    if (is_array($fields) && count($fields)) {
                        foreach ($fields as $field) {
                            for ($i = 1; $i <= $pcount; $i ++) {
                                if (isset($_REQUEST[$field["name"] . "_" . $i]) || isset($_REQUEST[$field["name"] . "_" . $i . "[]"]) || isset($_FILES[$field["name"] . "_" . $i])) {
                                    $this->persist_field($field, ($field["type"] != "file") ? $_REQUEST[$field["name"] . "_" . $i] : $_FILES[$field["name"] . "_" . $i], ("_" . $i));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
	
    /**
     * Mining $_REQUEST object for admin fields, which has been configured to show on Product Page 
     */
	private function persist_admin_fields() {	    
	    /* Normal mining process on $_REQUEST object
	     * Since we have field level cloning option we have to mine
	     * even if cloning option is enabled */
	    	foreach ($this->admin_fields as $title => $afields) {
	        if (is_array($afields) && count($afields) > 0) {
	            foreach ($afields as $key => $afield) {
	               $afield["show_on_product_page"] = isset($afield["show_on_product_page"]) ? $afield["show_on_product_page"] : "no";
	               if ($afield["show_on_product_page"] == "yes" && isset($_REQUEST[$afield["name"]])) {		
    	            	      $this->persist_field($afield, $_REQUEST[$afield["name"]]);	
    	               }
	            }
	        }
	    }	        
	    if ($this->fields_cloning == "yes") {	 
	        /* Mining process on $_REQUEST object with cloning option */
	        if (isset($_REQUEST["quantity"])) {
	            $pcount = intval($_REQUEST["quantity"]);	      
                foreach ($this->admin_fields as $title => $afields) {
                	if (is_array($afields) && count($afields) > 0) {
                        foreach ($afields as $key => $afield) {
                            $afield["show_on_product_page"] = isset($afield["show_on_product_page"]) ? $afield["show_on_product_page"] : "no";
                            for ($i = 1; $i <= $pcount; $i++) {
                                if ($afield["show_on_product_page"] == "yes" && isset($_REQUEST[$afield["name"] . "_" . $i])) {
                                    $this->persist_field($afield, $_REQUEST[$afield["name"] . "_" . $i], ("_" . $i));
                                }
                            }
                        }
                    }
                }	            
	        }
	    }
	}
	
	/**
	 * 
	 * Does the extraction of custom fields data from $_REQUEST object<br/>
	 * and store them as Cart Item Data
	 * 
	 * @param object $_field
	 * @param mixed $_val
	 * @param string $_index
	 * 
	 */
	private function persist_field($_field, $_val, $_index = "") {
        $f_rules = isset($_field["fee_rules"]) ? $_field["fee_rules"] : array();
        $p_rules = isset($_field["pricing_rules"]) ? $_field["pricing_rules"] : array();
        if ($_field["type"] != "file") {
            $res = "";
            /* This option is used for select field, in that case we will store the Option's Label instead Value */
            $option_label = isset($_field["show_selected_val_lab"]) ? ($_field["show_selected_val_lab"] == "yes" ? true : false) : false;
            if ($_field["type"] == "select" && $option_label) {
                $get_option = explode(";", $_field["choices"]);
                for ($j = 0; $j < count($get_option); $j ++) {
                    $sin_option = explode("|", $get_option[$j]);
                    if ($sin_option[0] == $_val) {
                        $res = $sin_option[1];
                    }
                }
            } else {
                /* Other fields can be directly stored */
                $res = $_val;
            }
            /* Make sure the select field placeholder not there */
            if ($_field["type"] == "select" && $res == "wccpf_none") {
            	return;
            }
            /* Make sure the value is valid (not empty) */
            if (is_array($res) || trim($res)) {            	
            	$cif_data = array(
            		"field_key" => "wccpf_" . $_field["name"] . $_index,
            		"field_val" => array(
            			"fname" => $_field["name"] . $_index,
            			"ftype" => $_field["type"],
            			"user_val" => $res,
            			"fee_rules" => $f_rules,
            			"pricing_rules" => $p_rules,
            			/* Applicable only for Date field */
            			"format" => ($_field["type"] == "datepicker") ? ($_field["date_format"] != "" ? $_field["date_format"] : "d-m-Y") : ""
            		)
            	);
            	/* Let other plugins override this value - if they wanted */
            	if (has_filter("wcff_before_inserting_cart_data")) {
            		$cif_data = apply_filters("wcff_before_inserting_cart_data", $_field, $cif_data);
            	}            	
            	/* Well insert into cart data */
            	$this->cart_item_data[$cif_data["field_key"]] = $cif_data["field_val"];
            }
        } else {
            /* Process file upload */
            $this->persist_file_field($_field, $_val, $_index);
        }
    }
	
	/**
	 * 
	 * Upload the submitted file via custom File Field and store the meta in cart line item
	 * 
	 * @param object $_field
	 * @param object $_val ( $_FILE )
	 * @param number $_index
	 * 
	 */
	private function persist_file_field($_field, $_val, $_index = "") {
		$f_rules = isset($_field["fee_rules"]) ? $_field["fee_rules"] : array();
		$p_rules = isset($_field["pricing_rules"]) ? $_field["pricing_rules"] : array();
        // upload directory
        if (isset($_field["upload_url"])) {
            if ($_field["upload_url"] != "") {
                Global $copy_field_upload_dir;
                $copy_field_upload_dir = $_field["upload_url"];
                add_filter('upload_dir', array(
                    $this,
                    'custom_upload_dir'
                ));
            }
        }
        $res = array();
        $is_multi_file = isset($_field["multi_file"]) ? $_field["multi_file"] : "no";
        /* Handle the file upload */
        if ($is_multi_file == "yes") {
            /* fiels makes more sense then val */
            $files = $_val;
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    
                    $temp_res = $this->process_file_upload($file);
                    if (isset($temp_res['error'])) {
                        $res = $temp_res;
                        break;
                    } else {
                        $res[] = $temp_res;
                    }
                }
            }
        } else {
            $res = $this->process_file_upload($_val);
        }
        if (! isset($res['error'])) {
        	/* File field doesn't support pricing and fee rules */
        	$cif_data = array(
        		"field_key" => "wccpf_" . $_field["name"] . $_index,
        		"field_val" => array(
        			"fname" => $_field["name"],
        			"ftype" => $_field["type"],
        			"user_val" => json_encode($res),
        			"fee_rules" => $f_rules,
        			"pricing_rules" => $p_rules,
        			/* Applicable only for Date field */
        			"format" => ($_field["type"] == "datepicker") ? ($_field["date_format"] != "" ? $_field["date_format"] : "dd-mm-yy") : ""
        		)
        	);
        	/* Let other plugins override this value - if they wanted */
        	if (has_filter("wcff_before_inserting_cart_data")) {
        		$cif_data = apply_filters("wcff_before_inserting_cart_data", $_field, $cif_data);
        	}        	
        	/* Well insert iinto cart data */
        	$this->cart_item_data[$cif_data["field_key"]] = $cif_data["field_val"];
            do_action('wccpf_file_uploaded', $res);
        } else {
            wc_add_wp_error_notices($_field["message"], 'error');
        }
    }
	
    /**
     * 
     * Helping method which does the actual uploading process<br/>
     * Using Wordpress's 'wp_handle_upload' method.
     * 
     * @param $_FILE $_uploadedfile
     * @return array
     * 
     */
	private function process_file_upload($_uploadedfile) {
        if (! function_exists('wp_handle_upload')) {
            require_once (ABSPATH . 'wp-admin/includes/file.php');
        }
        $movefile = wp_handle_upload($_uploadedfile, array(
            'test_form' => false
        ));
        return $movefile;
    }
	
	/**
	 * 
	 * Handler for 'upload_dir' filter, where you can specify custom upload directory for your file upload
	 * 
	 * @param  string $_urls
	 * @return string
	 * 
	 */
	function custom_upload_dir($_urls) {
        Global $copy_field_upload_dir;
        $_urls['path'] = WP_CONTENT_DIR . '/' . $copy_field_upload_dir;
        $_urls['url'] = WP_CONTENT_URL . '/' . $copy_field_upload_dir;
        return $_urls;
    }
	
}