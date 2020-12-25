<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * This moduke is responsible for inserting product field values, admin field values and custom pricing label as order meta. 
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_OrderHandler {
	
    /* Order line item ID */
    private $item_id;
    /* Order Line item Object */
    private $item_obj;
    /* Cart item key that represent this Order Line Item */
    private $cart_item_key;
    
    /* Fields cloning flag */
    private $fields_cloning;
    /* Multilingual flag */
    private $multilingual;
    /* Holds Product fields list */
    private $product_fields = null;
    /* Holds Admin fields list */
    private $admin_fields = null;
    
    public function __construct() {}
    
    /**
     * 
     * Handle 'woocommerce_new_order_item' action ( 'woocommerce_add_order_item_meta' for WC < 3.0.6 )<br>
     * Just delegates the task to other helper method for inserting product, admi and pricing values as order line item meta 
     * 
     * @param integer $_item_id
     * @param object $_values
     * @param string $_cart_item_key
     * 
     */
    public function insert($_item_id, $_values, $_cart_item_key) {
        $this->item_id = $_item_id;
        $this->cart_item_key = $_cart_item_key;
        
        $wccpf_options = wcff()->option->get_options();
        $this->fields_cloning = isset($wccpf_options["fields_cloning"]) ? $wccpf_options["fields_cloning"] : "no";
        $this->multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
        
        /* WC 3+ & Older versions - compatible */
        $this->item_obj = version_compare(WC()->version, '3.0.0', '<') ? $_values : isset($_values->legacy_values) ?  $_values->legacy_values : $_values;
        if (isset($this->item_obj["product_id"])) {           
            $this->product_fields = wcff()->dao->load_fields_for_product($this->item_obj['product_id'], 'wccpf');
            $this->admin_fields = wcff()->dao->load_fields_for_product($this->item_obj['product_id'], 'wccaf', 'any');  
            if( isset( $this->item_obj["variation_id"] ) && !empty( $this->item_obj["variation_id"] ) && $this->item_obj["variation_id"] != 0 ){
            	$this->product_fields = array_merge( $this->product_fields, wcff()->dao->load_fields_for_product($this->item_obj['variation_id'], 'wccpf', 'cart-page'));
            	$this->admin_fields = array_merge($this->admin_fields, wcff()->dao->load_fields_for_product($this->item_obj['variation_id'], 'wccaf', 'cart-page'));
            }
            
            /**/
            $this->insert_product_fields_meta();
            /**/
            $this->insert_admin_fields_meta();
            /**/
            $this->insert_pricing_rules_meta();
        }        
    }
    
    /**
     * 
     * Responsible for inserting Product Fields value as Order Line Item Meta<br>
     * It will mine the Order Item Object for Product Fields, once found the entry it will insert as Order Line Item Meta. 
     * 
     */
    private function insert_product_fields_meta() { 
        /* Normal mining for product fields
         * Since we have field level cloning option
         * we need to mine normal flow even if cloning is enabled */
        foreach ($this->product_fields as $title => $fields) {
            if (is_array($fields) && count($fields)) {
                foreach ($fields as $field) {
                    $add_as_meta = isset($field["order_meta"]) ? $field["order_meta"] : "yes";
                    if ($add_as_meta == "yes" && isset($this->item_obj['wccpf_' . $field["name"]])) {
                        $this->insert_fields_meta($field, $this->item_obj['wccpf_' . $field["name"]]);
                    }
                }
            }                
        }
        if ($this->fields_cloning == "yes") {
            if (isset($this->item_obj["quantity"])) {
                $pcount = intval($this->item_obj["quantity"]);
                foreach ($this->product_fields as $title => $fields) {
                    if (is_array($fields) && count($fields)) {
                        for ($i = 1; $i <= $pcount; $i++) {
                            foreach ($fields as $field) {
                                $add_as_meta = isset($field["order_meta"]) ? $field["order_meta"] : "yes";
                                if ($add_as_meta == "yes" && isset($this->item_obj['wccpf_' . $field["name"] . "_" . $i])) {
                                    $this->insert_fields_meta($field, $this->item_obj['wccpf_' . $field["name"] . "_" . $i], (" - " . $i));
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
     * Responsible for inserting Admin Fields value as Order Line Item Meta<br>
     * It will mine the Order Item Object for Admin Fields, once found the entry it will insert as Order Line Item Meta.
     *
     */
    private function insert_admin_fields_meta() {
        /* Normal mining for admin fields
         * Since we have field level cloning option
         * we need to mine normal flow even if cloning is enabled */
        foreach ($this->admin_fields as $title => $afields) {
            if (is_array($afields) && count($afields) > 0) {
                foreach ($afields as $key => $afield) {
                    $add_as_meta = isset($afield["order_meta"]) ? $afield["order_meta"] : "no";
                    if ($add_as_meta == "yes" && isset($this->item_obj['wccpf_'. $afield["name"]])) {
                        $this->insert_fields_meta($afield, $this->item_obj['wccpf_'. $afield["name"]]);                            
                    }
                }
            }
        }
        if ($this->fields_cloning == "yes") {
            if (isset($this->item_obj["quantity"])) {
                $pcount = intval($this->item_obj["quantity"]);
                foreach ($this->admin_fields as $title => $afields) {
                    if (is_array($afields) && count($afields) > 0) {
                        for ($i = 1; $i <= $pcount; $i++) {
                            foreach ($afields as $key => $afield) {
                                $add_as_meta = isset($afield["order_meta"]) ? $afield["order_meta"] : "no";
                                if ($add_as_meta == "yes" && isset($this->item_obj['wccpf_'. $afield["name"] . "_" . $i])) {                                
                                    $this->insert_fields_meta($afield, $this->item_obj['wccpf_'. $afield["name"] . "_" . $i], (" - " . $i));
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
     * Responsible for inserting Custom Pricing value as Order Line Item Meta<br>
     * It will mine the Order Item Object for Pricing Rules, once found the entry it will insert as Order Line Item Meta.
     *
     */
    private function insert_pricing_rules_meta() {
        	foreach ($this->item_obj as $ckey => $cval) {
        		if (strpos($ckey, "wccpf_pricing_applied_") !== false) {
        			$prules = $this->item_obj[$ckey];
        			if (isset($prules["title"]) && isset($prules["amount"])) {
        				$wcff_price_meta = array(
        					"prule_title" => $prules["title"],
        					"prule_amount" => $prules["amount"]
        				);
        				/* Let other plugins override this value - if they wanted */
        				if(has_filter("wcff_before_inserting_pricing_order_meta")) {
        					$wcff_price_meta = apply_filters("wcff_before_inserting_pricing_order_meta", $this->item_id, $prules, $wcff_price_meta);
        				}
        				wc_add_order_item_meta($this->item_id, $wcff_price_meta["prule_title"], $wcff_price_meta["prule_amount"]);
        			}
        		}
        	}
    }
    
    /**
     *
     * Helper method which actually does the Order Line Item Meta Inserting Task
     * 
     * @param object $_field
     * @param array|string|number $_val
     * @param string $_index
     * 
     */
    private function insert_fields_meta($_field, $_val, $_index = "") {
    	$value = null;
    	if ($this->multilingual == "yes") {
    		/* Localize field */
    		$_field= wcff()->locale->localize_field($_field);
    	}
    	
        $_val = (($_val && isset($_val["user_val"])) ? $_val["user_val"] : $_val); 
        if ($_field["type"] != "file" && $_field["type"] != "checkbox") {        	 
        	$value = stripslashes($_val);
        } else if($_field["type"] == "checkbox") {        	 
        	$value = (is_array($_val) ? implode(", ", $_val) : stripslashes($_val));
        } else {
            if ($_field["multi_file"] == "yes") {
                $furls = array();
                $farray = json_decode($_val, true);
                foreach ($farray as $fobj) {
                    $furls[] = $fobj["url"];
                }
                $value = implode(", ", $furls);                           
            } else {
                $fobj = json_decode($_val, true);
                $value = $fobj["url"];                
            }
        }
        $wcff_order_item_meta = array(
        	"field_key" => $_field["label"] . $_index,
        	"field_val" => $value
        ); 
        /* Let other plugins override this value - if they wanted */
        if(has_filter("wcff_before_inserting_order_item_meta")) {
            $wcff_order_item_meta= apply_filters("wcff_before_inserting_order_item_meta", $wcff_order_item_meta, $this->item_id, $_field);
        }
        wc_add_order_item_meta($this->item_id, $wcff_order_item_meta["field_key"], $wcff_order_item_meta["field_val"]);
    }    
    
}

?>