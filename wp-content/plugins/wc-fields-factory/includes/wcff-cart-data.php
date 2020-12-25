<?php

if (!defined('ABSPATH')) { exit; }
/**
 * 
 * Renders the custom fields values in the Cart Line Item<br>
 * It will mine the Caft Item Object for all the product, admin and custom pricing values<br>
 * and add those values as key value pairs into the Cart Data, which will be rendered by the WC Cart Template
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_CartDataRenderer {
    
    /* Holds the mined custom fields key val pairs */
    private $wccpf_items;
    /* Cart data object supplied by the WC */
    private $cart_data;
    /* Cart line item object supplied by the WC */
    private $cart_item = null;
    
    /* Multilingual flag */
    private $multilingual;
    /* Cloning option flag */
    private $fields_cloning;
    /* Visibility flag on Cart - ( Global Option ) */
    private $show_custom_data;
    /* Grouping option flag */
    private $group_meta_on_cart;
    
    /* Product fields list */
    private $product_fields = null;
    /* Admin fields list */
    private $admin_fields = null;
	
    public function __construct() {}
    
    /**
     * 
     * Handler for 'woocommerce_get_item_data' action<br>
     * It gather all the custom fields values by using other helper methods<br>
     * and returns the the key value pair array
     * 
     * @param object $_cart_data
     * @param object $_cart_item
     * @return array
     * 
     */
    public function render_fields_data($_cart_data, $_cart_item = null) {
        $this->cart_data = $_cart_data;
        $this->cart_item = $_cart_item;
        $this->wccpf_items = array();
        
        /* Woo 2.4.2 updates */
        if (! empty($this->cart_data)) {
            $this->wccpf_items = $this->cart_data;
        }
        
        $wccpf_options = wcff()->option->get_options();
        $this->show_custom_data = isset($wccpf_options["show_custom_data"]) ? $wccpf_options["show_custom_data"] : "yes";
        $this->fields_cloning = isset($wccpf_options["fields_cloning"]) ? $wccpf_options["fields_cloning"] : "no";
        $this->group_meta_on_cart = isset($wccpf_options["group_meta_on_cart"]) ? $wccpf_options["group_meta_on_cart"] : "no";
        $this->multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
        
        $this->product_fields = wcff()->dao->load_fields_for_product($this->cart_item['product_id'], 'wccpf');
        $this->admin_fields = wcff()->dao->load_fields_for_product($this->cart_item['product_id'], 'wccaf', 'any');
        if( isset( $this->cart_item['variation_id'] ) && $this->cart_item['variation_id'] != 0 && !empty( $this->cart_item['variation_id'] ) ){
        	$this->product_fields = array_merge( $this->product_fields, wcff()->dao->load_fields_for_product($this->cart_item['variation_id'], 'wccpf', 'cart-page') );
        	$this->admin_fields = array_merge( $this->admin_fields, wcff()->dao->load_fields_for_product($this->cart_item['variation_id'], 'wccaf', 'cart-page') );
        }
       
       
        /* Mining process for Product Fields */
        $this->render_product_fields_data();
        
        /* Mining procss for Custom Pricing */
        $this->render_pricing_rules_data();
        
        return $this->wccpf_items;
    }
    
    /**
     * 
     * Mine the Cart Line Item Object for Product Fields
     * 
     */
    private function render_product_fields_data() {    	    
        	/* Normal mining for product fields
        	 * Since we have field level cloning option
         * we need to mine normal flow even if cloning is enabled */
        foreach ($this->product_fields as $title => $fields) {
            	if (is_array($fields) && count($fields)) {
	            foreach ($fields as $field) {
	                $field["visibility"] = isset($field["visibility"]) ? $field["visibility"] : "yes";
	                if ($field["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $field["name"]])) {
	                    $this->render_data($field, $this->cart_item['wccpf_'. $field["name"]]);
	                }
	            }
           	}
        }
        
        if ($this->fields_cloning == "yes") {
            /* Mining with cloning option enabled */
            if (isset($this->cart_item["quantity"])) {
                $pcount = intval($this->cart_item["quantity"]);
                foreach ($this->product_fields as $title => $fields) {
                   	if (is_array($fields) && count($fields)) {
	                    if ($this->group_meta_on_cart == "yes") {		
	                        /*Group field - due to cloning, same fields can be submitted multiple times */
	                        foreach ($fields as $field) {
	                            for ($i = 1; $i <= $pcount; $i++) {
	                                $field["visibility"] = isset($field["visibility"]) ? $field["visibility"] : "yes";
	                                if ($field["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $field["name"] . "_" . $i])) {
	                      	            $this->render_data($field, $this->cart_item['wccpf_'. $field["name"] . "_" . $i], (" - " . $i));
	                                }
	                            }
	                        }
	                    } else {
	                        /* Normal grouping - based on the fields order from the backend */
	                      	for ($i = 1; $i <= $pcount; $i++) {
	                       		foreach ($fields as $field) {
	                       			$field["visibility"] = isset($field["visibility"]) ? $field["visibility"] : "yes";
	                       			if ($field["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $field["name"] . "_" . $i])) {
                       					$this->render_data($field, $this->cart_item['wccpf_'. $field["name"] . "_" . $i], (" - " . $i));
	                       			}
	                       		}
	                       	}
	                    }
                   	}
                }
                
            }
        }       
        
        
        /* Mining process for Admin Fields */
        $this->render_admin_fields_data();
        
    }
    
    /**
     *
     * Mine the Cart Line Item Object for Admin Fields
     *
     */
    private function render_admin_fields_data() {        	
    		/* Normal mining for admin fields
        	 * Since we have field level cloning option
         * we need to mine normal flow even if cloning is enabled */
    		foreach ($this->admin_fields as $title => $afields) {
    			if (is_array($afields) && count($afields) > 0) {
    				foreach ($afields as $key => $afield) {    					
    					$afield["visibility"] = isset($afield["visibility"]) ? $afield["visibility"] : "yes";
    					if ($afield["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $afield["name"]])) {    						
    						$this->render_data($afield, $this->cart_item['wccpf_'. $afield["name"]]);    							
    					}
    				}
    			}
    		}
        	if ($this->fields_cloning == "yes") {
        		if (isset($this->cart_item["quantity"])) {
        			$pcount = intval($this->cart_item["quantity"]);
        			foreach ($this->admin_fields as $title => $afields) {
        				if (is_array($afields) && count($afields)) {
        					if ($this->group_meta_on_cart == "yes") {	
        						foreach ($afields as $key => $afield) {        							
        							for($i = 1; $i <= $pcount; $i++) {
        								$afield["visibility"] = isset($afield["visibility"]) ? $afield["visibility"] : "yes";
        								if ($afield["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $afield["name"] . "_" . $i ])) {
        									$this->render_data($afield, $this->cart_item['wccpf_'. $afield["name"] . "_" . $i ], (" - " . $i));
        								}
        							}
        						}
        					} else {
        						for ($i = 1; $i <= $pcount; $i++) {
        							foreach ($afields as $key => $afield) {        								
        								$afield["visibility"] = isset($afield["visibility"]) ? $afield["visibility"] : "yes";
        								if ($afield["visibility"] == "yes" && isset($this->cart_item['wccpf_'. $afield["name"] . "_" . $i ])) {   										
        									$this->render_data($afield, $this->cart_item['wccpf_'. $afield["name"] . "_" . $i ], (" - " . $i));
        								}
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
     * Mine the Cart Line Item Object for Custom Pricing Rules
     *
     */
    private function render_pricing_rules_data() {    	
        	foreach ($this->cart_item as $ckey => $cval) {
        		if (strpos($ckey, "wccpf_pricing_applied_") !== false) {
        			$prules = $this->cart_item[$ckey];
        			if (isset($prules["title"]) && isset($prules["amount"])) {
        				$this->wccpf_items[] = array("name" => $prules["title"], "value" => $prules["amount"]);
        			}
        		}
        	}    	
    }
    
    /**
     * 
     * Insert custom fields values as Key Val pairs into wccpf_items 
     * 
     * @param object $_field
     * @param string|number|array $_val
     * @param string $_index
     * 
     */
    private function render_data($_field, $_val, $_index = "") {
    	$value = null;
        if ($this->multilingual == "yes") {
        	/* Localize field */
        	$_field = wcff()->locale->localize_field($_field);
        }     	
        $_val = (($_val && isset($_val["user_val"])) ? $_val["user_val"] : $_val);
        if ($_field["type"] != "file" && $_field["type"] != "checkbox") {
        	$value = esc_html(stripslashes($_val));        	
        } else if($_field["type"] == "checkbox") {
    	    /* Since checkbox value is array, we have to deal it seperately */
        	$value = (is_array($_val) ? implode(", ", $_val) : esc_html(stripslashes($_val)));    	   
        } else {
            $is_multi_file = isset($_field["multi_file"]) ? $_field["multi_file"] : "no";
            if ($is_multi_file == "yes") {
                $fnames = array();
                $images = "";
                $farray = json_decode($_val, true);
                foreach ($farray as $fobj) {
                    $path_parts = pathinfo($fobj['file']);
                    $fnames[] = $path_parts["basename"];
                    if (@getimagesize($fobj["url"])) {
                        $images .= "<img src='".$fobj["url"]."' style='width: ".$_field["img_is_prev_width"]."px' >";
                    }
                }
                if ($_field["img_is_prev"] == "yes" && @getimagesize($fobj["url"])) {
                	$value = $images;                    
                } else {
                	$value = implode(", ", $fnames);                    
                }
            } else {
                $fobj = json_decode($_val, true);
                $path_parts = pathinfo($fobj['file']);
                if ($_field["img_is_prev"] == "yes" && @getimagesize($fobj["url"])) {
                	$value = "<img src='".$fobj["url"]."' style='width: ".$_field["img_is_prev_width"]."px' />";                    
                } else{
                	$value = $path_parts["basename"];                    
                }
            }
        }
        $cif_data = array(
        	"field_key" => ($_field["label"]. $_index),
        	"field_val" => $value
        );
        /* Let other plugins override this value - if they wanted */
        if (has_filter("wcff_before_rendering_cart_data")) {
            $cif_data = apply_filters("wcff_before_rendering_cart_data", $cif_data, $_field );
        }  
        $this->wccpf_items[] = array("name" => $cif_data["field_key"], "value" => $cif_data["field_val"]);
    }
    
}

?>