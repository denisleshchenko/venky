<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * Cart Line Item price calculator.<br/>
 * Alter the existing line item price based on user values.<br/>
 * Also adds custom fee to the cart if configured so.
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_Negotiator {
	
    public function __construct() {
    }
    
    /**
     * 
     * Determine the line item price based on User submitted values ( while adding product to cart )<br/>
     * Loop through all the line item and calculate the product price based on Pricing Rules of each fields (if the criteria is matched) 
     * 
     * @param object $citem, string $cart_item_key
     * 
     */
 
    public function handle_custom_pricing( $citem, $cart_item_key ) {
    		$orgPrice = method_exists ( $citem ["data"], "get_price" ) ? floatval ( $citem['data']->get_price() ) : floatval ( $citem['data']->price );
    		$basePrice = $orgPrice;
    		$percentage_price = 0;
    		$customPrice = $orgPrice;
			foreach ( $citem as $ckey => $cval ) {
				if (strpos ( $ckey, "wccpf_" ) !== false && isset ( $citem [$ckey] ["pricing_rules"] ) && $citem [$ckey] ["user_val"]) {
					$fname   = $citem [$ckey] ["fname"];
					$ftype   = $citem [$ckey] ["ftype"];
					$dformat = $citem [$ckey] ["format"];
					$uvalue  = $citem [$ckey] ["user_val"];
					$p_rules = $citem [$ckey] ["pricing_rules"];
					/* Iterate through the rules and update the price */
					
					foreach ( $p_rules as $prule ) {
						if ($this->check_rules ( $prule, $uvalue, $ftype, $dformat )) {
							$is_amount = isset( $prule ["tprice"] ) && $prule ["tprice"] == "cost" ? true : false;
							/* Determine the price */
							if( $is_amount ){
								if ($prule ["ptype"] == "add") {
									$customPrice = $customPrice + floatval ( $prule ["amount"] );
								} else {
									$percentage_price = 0;
									$customPrice = floatval( $prule ["amount"] );
								}
							} else {
								if ($prule ["ptype"] == "add") {
									$percentage_price = $percentage_price + ( ( floatval ( $prule ["amount"] ) / 100 ) * $basePrice);
								} else {
									$customPrice = 0;
									$percentage_price = (floatval ($prule ["amount"] ) / 100) * $basePrice;
								}
							}
							/* Add pricing rules label - for user notification */
							$citem ["wccpf_pricing_applied_" . (strtolower ( str_replace ( " ", "_", $prule ["title"] ) ))] = array ( "title" => $prule ["title"], "amount" => get_woocommerce_currency_symbol () . ( $is_amount ? $prule ["amount"] : ((floatval ($prule ["amount"] ) / 100) * $basePrice) )  );
					   }
					}
					
					$orgPrice = apply_filters( "wcff_negotiate_price_after_calculation", $percentage_price + $customPrice );
				}
			}
			/* Update the price */
			if (method_exists ( $citem ["data"], "set_price" )) {
				/* Woocommerce 3.0.6 + */
				$citem ["data"]->set_price( $orgPrice );
			} else {
				/* Woocommerece before 3.0.6 */
				$citem ["data"]->price = $orgPrice;
			}
    	return $citem;
    }
    
    
    /**
     *
     * Determine the line item price based on User selected/entered values ( while entered or selected values )<br/>
     * Loop through all the line item and calculate the product price based on Pricing Rules of each fields (if the criteria is matched)
     *
     * @param object $citem, string $cart_item_key
     *
     */
    public function ajax_get_negotiated_price( $_payload ){
    	if( isset( $_payload[ "_product_id" ] ) && $_payload[ "_fields_data" ] ){
    		$product_id = $_payload[ "_product_id" ];
    		$product_variation = isset( $_payload[ "_variation_id" ] ) && !empty( $_payload[ "_variation_id" ] ) ? $_payload[ "_variation_id" ] : null;
    		$price_prod_id = $product_variation == null ? $product_id : $product_variation;
    		$product_rules_fiels = $_payload[ "_fields_data" ];
    		$product_object = wc_get_product( $price_prod_id );
    		$product_price = 0;
    		if(method_exists($product_object, "get_price")){
    		    $product_price = $product_object->get_price("view");
    		} else {
    			$product_price = empty( $product_object->sale_price ) ? $product_object->regular_price : $product_object->sale_price;
    		}
    		$basePrice = $product_price;
    		$percentage_price = 0;
    		$customPrice = $product_price;
    		$product_fields = wcff()->dao->load_fields_for_product( $product_id );
    		if( $product_variation != null && !empty( $product_variation ) && $product_variation != 0 ){
    			$product_fields = array_merge($product_fields, wcff()->dao->load_fields_for_product( $product_variation, 'wccpf', 'cart-page' ));
    			$product_fields = array_merge($product_fields, wcff()->dao->load_fields_for_product( $product_variation, 'wccaf', 'cart-page' ));
    		}
    		$data = array();
    		$data_price_titles = array();
    		// Pricing rules fields loop - from frontend
    		for( $j = 0; $j < sizeof( $product_rules_fiels ); $j++ ){
    			// Get field meta key  check is clonable or not.
    			$field_meta_key = isset($product_rules_fiels[$j]["is_clonable"]) && $product_rules_fiels[$j]["is_clonable"] == "yes" ? substr( $product_rules_fiels[$j]["name"], 0, strrpos( $product_rules_fiels[$j]["name"], "_" ) ) : $product_rules_fiels[$j]["name"];
    			// Product fields loop
    			for( $i = 0; $i < sizeof( $product_fields ); $i++ ){
    				/* if user changed label after field create */
    				$is_valid_obj_flg = false;
    				$is_valid_obj_meta_key = "";
    				foreach( $product_fields[$i] as $fckey => $fcvalue ){
    					if( $fcvalue["name"] == $field_meta_key ){
    						$is_valid_obj_flg = true;
    						$is_valid_obj_meta_key = $fckey;
    					}
    				}
    				
    				if( isset( $product_fields[$i]["wccpf_".$field_meta_key] ) || $is_valid_obj_flg ){
    					$field_meta = $product_fields[$i][$is_valid_obj_meta_key];
	    				$fname   = $field_meta["name"];
	    				$ftype   = $field_meta["type"];
	    				$dformat = isset( $field_meta["date_format"] ) ? $field_meta["date_format"] : "";
	    				$uvalue  = $product_rules_fiels[$j]["value"];
	    				$p_rules = $field_meta["pricing_rules"];
    					foreach ( $p_rules as $prule ) {
    						if ($this->check_rules ( $prule, $uvalue, $ftype, $dformat )) {
    							$is_amount = isset( $prule ["tprice"] ) && $prule ["tprice"] == "cost" ? true : false;
    							/* Determine the price */
    							if( $is_amount ){
    								if ($prule ["ptype"] == "add") {
    									$customPrice = $customPrice + floatval ( $prule ["amount"] );
    								} else {
    									$percentage_price = 0;
    									$customPrice = floatval( $prule ["amount"] );
    								}
    							} else {
    								if ($prule ["ptype"] == "add") {
    									$percentage_price = $percentage_price + ( ( floatval ( $prule ["amount"] ) / 100 ) * $basePrice);
    								} else {
    									$customPrice = 0;
    									$percentage_price = (floatval ($prule ["amount"] ) / 100) * $basePrice;
    								}
    							}
    							$data_price_titles[]= array ( "title" => $prule ["title"], "amount" => get_woocommerce_currency_symbol () . ( $is_amount ? $prule ["amount"] : ((floatval ($prule ["amount"] ) / 100) * $basePrice)   )  );
    						}
    					}
	    			}
    			}
    		}
    		
    		$product_price = apply_filters( "wcff_realtime_negotiate_price_after_calculation", $percentage_price + $customPrice, $product_id );
    		$data['currency'] = get_woocommerce_currency();
    		$data['currency_symbol'] = get_woocommerce_currency_symbol();
    		$data['amount'] = wc_price( $product_price );
    		$data['data_title'] = $data_price_titles;
    		return array( "status" => true, "data" => $data );
    	}
    	return array( "status" => false, "data" => "Something went wrong." );
    }
    
    
    public function field_rules_script_render($_rule_meta, $_field_type, $_field_name){
    	$rule_script	= '';
    	$exp_val = $_rule_meta["expected_value"];
    	if ($_field_type != "checkbox" && $_field_type != "datepicker") {
    		$field_logic_symbol = "";
    		if ($_rule_meta["logic"] == "equal") {
    			$field_logic_symbol = "==";
    		} else if ($_rule_meta["logic"] == "not-equal") {
    			$field_logic_symbol = "!=";
    		} else if ($_rule_meta["logic"] == "greater-than" && is_numeric($exp_val)) {
    			$field_logic_symbol = ">";
    		} else if ($_rule_meta["logic"] == "less-than" && is_numeric($exp_val)) {
    			$field_logic_symbol = "<";
    		} else if ($_rule_meta["logic"] == "greater-than-equal" && is_numeric($exp_val) ) {
    			$field_logic_symbol = ">=";
    		} else if ($_rule_meta["logic"] == "less-than-equal" && is_numeric($exp_val)) {
    		  $field_logic_symbol = "<=";
    		} else if( $_rule_meta["logic"] == "not-null" ){
    			$field_logic_symbol = "!=";
    			$exp_val = "";
    		}
    		
    		$field_logic_symbol = $field_logic_symbol == "" ? "==" : $field_logic_symbol;
    		$rule_script .= 'if( jQuery(this).val() '.$field_logic_symbol.' "'.$exp_val.'" ){';
    		$rule_script .= $this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type);
    		$rule_script .= '} else {';
    		$rule_script .= $this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true);
    		$rule_script .= '}';
    	} else if ($_field_type == "checkbox") {
    		$rule_script .= '
    				var listofcheckedfields = jQuery("[name=\''.$_field_name.'[]\']:checked"),
    						arrlist = [];
    						for( var i = 0; i < listofcheckedfields.length; i++ ){ 
    							arrlist[i] = jQuery(listofcheckedfields[i]).val(); 
    						}
    						var flg_count = 0;
    						for(var j = 0; j < arrlist.length; j++){
    							if( '.json_encode($exp_val).'.indexOf(arrlist[j]) != -1 ){ flg_count++; }
            				};';
            /* This must be a check box field */
            if ($_rule_meta["logic"] == "is-only") { 
             	/* User chosen option (or options) has to be exact match */
                /* In that case both end has to be same quantity */
            	$rule_script .= 'if(flg_count == 1){'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'}';
            	$rule_script .= 'else{'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
			} else if ($_rule_meta["logic"] == "is-also") {
                 /* User chosen option should contains expected option
                 * There can be other options also chosen (but expected option has to be one of them) */
                $rule_script .= 'if(flg_count == '.json_encode($exp_val).'.length){ '.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'}';
                $rule_script .= 'else{'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
            } else if ($_rule_meta["logic"] == "any-one-of") {
                 /* Well there can be more then one expected options, but any one of them are present 
                 * with the user submitted options then rules are met */
                $rule_script .= 'if(flg_count != 0){'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'}';
                $rule_script .= 'else{'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
            }
       } else if ($_field_type == "datepicker") {
       			$rule_script .= 'var daysName = ["sunday","monday","tuesday","wednesday","thursday","friday","saturday"],
       							frmttdDateObj = jQuery(this).datepicker( "getDate" );';
                if ($_rule_meta["expected_value"]["dtype"] == "days") {
                     /* If user chosed any specific day like "sunday", "monday" ... */
                	$rule_script .= 'if( '.json_encode( $_rule_meta["expected_value"]["value"] ).'.indexOf( daysName[frmttdDateObj.getDay()] ) != -1 ){'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type);
                	$rule_script .= '} else {'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
                } 
                if ($_rule_meta["expected_value"]["dtype"] == "specific-dates") {             
                     /* Logic for any specific date matches ( Exact date ) */
                     $sdates = explode(",", (($_rule_meta["expected_value"]["value"]) ? $_rule_meta["expected_value"]["value"] : ""));
                     if (is_array($sdates)) {
                     	$rule_script .= 'if(false){}';
                    	foreach ($sdates as $sdate) {
                    		$split_date = explode( "-", $sdate );
                    		$rule_script .= 'else if( parseInt( '.$split_date[0].' ) == (frmttdDateObj.getMonth()+1) && parseInt( '.$split_date[1].' ) == frmttdDateObj.getDate() && parseInt( '.$split_date[2].' ) == frmttdDateObj.getFullYear() ){
                    		'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'	
                    		}';
                    	}
                    	$rule_script .= 'else{'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
                    }                        
                } 
                if ($_rule_meta["expected_value"]["dtype"] == "weekends-weekdays") {
                    /* Logic for the weekends */
                    if ($_rule_meta["expected_value"]["value"] == "weekends") {
                    	$rule_script .= 'if( frmttdDateObj.getDay() == 6 || frmttdDateObj.getDay() == 0 ){'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'} else {'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
                    } else {
                    	$rule_script .= 'if( frmttdDateObj.getDay() != 6 && frmttdDateObj.getDay() != 0 ){'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'} else {'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
                    }
                } 
                if ($_rule_meta["expected_value"]["dtype"] == "specific-dates-each-month") {
                    /* Logic for the exact date of each month */
                    $sdates = explode(",", (($_rule_meta["expected_value"]["value"]) ? $_rule_meta["expected_value"]["value"] : ""));
                    $rule_script .= 'if(false){}';
                    foreach ($sdates as $sdate) {
                    	$rule_script .= 'else if( parseInt( '.$sdate.' ) == frmttdDateObj.getDate() ){
                    		'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type).'
                    	}';
               		}
               		$rule_script .= 'else{'.$this->field_rules_script_looper($_rule_meta["field_rules"], $_field_type, true).'}';
               }
            }
    	return $rule_script;
    }
    
    
    /*
     * Add script for field validation 
     * $_rule_arr : each field is show, hide or null, field key with value
     * $_field_type: field type
     * $rev : is reverse functionality
     */
    private function field_rules_script_looper($_rule_arr, $_field_type, $rev = false){
    	$looper_script = "";
    	foreach( $_rule_arr as $f_name => $rule ){
    		$is_check_sqr = strpos( $f_name, "[]" ) == FALSE ? "" : "[]";
    		$f_name = str_replace("[]", "", $f_name);
    		if( $rule == "hide" ){
    			$type = $rev == true ? "show" : "hide";
    			$classToggle = $type == "show" ? "removeClass" : "addClass";
    			$looper_script .= 'jQuery(this).closest(".wccpf-fields-container").find( "[name=\''.$f_name.'"+clone_index+"'.$is_check_sqr.'\']" ).closest(".wccpf_fields_table").'.$type.'().'.$classToggle.'("wcff_is_hidden_from_field_rule");';
    		} else if( $rule == "show" ){
    			$type = $rev == true ? "hide" : "show";
    			$classToggle = $type == "show" ? "removeClass" : "addClass";
    			$looper_script .= 'jQuery(this).closest(".wccpf-fields-container").find( "[name=\''.$f_name.'"+clone_index+"'.$is_check_sqr.'\']" ).closest(".wccpf_fields_table").'.$type.'().'.$classToggle.'("wcff_is_hidden_from_field_rule");';
    		}
    	}
    	return $looper_script;
    }
    

    /**
     * 
     * Add custom fee to Cart, based on user submitted values (while adding product to cart).
     * Loop through all the line item and add the custom fee, based on Fee Rules of each fields (if the criteria is matched) 
     * 
     * @param object $_cart
     * 
     */
    
    public function handle_custom_fee($_cart = null) {
        if ($_cart) {
        	$cart = WC()->cart->get_cart();
        	$cart_total = WC()->cart->cart_contents_total;
        	foreach ($cart as $key => $citem) {
                foreach ($citem as $ckey => $cval) {
                    if (strpos($ckey, "wccpf_") !== false && isset($citem[$ckey]["fee_rules"]) && $citem[$ckey]["user_val"]) {
                        $ftype = $citem[$ckey]["ftype"];
                        $dformat = $citem[$ckey]["format"];
                        $uvalue = $citem[$ckey]["user_val"];
                        $f_rules = $citem[$ckey]["fee_rules"];
                        /* Iterate through the rules and update the price */
                        foreach ($f_rules as $frule) {
                            if ($this->check_rules($frule, $uvalue, $ftype, $dformat)) {
                                $is_tax  = isset( $frule["is_tx"] ) && $frule["is_tx"] == "non_tax" ? false : true;
                            	$fee_amount = isset( $frule["tprice"] ) &&  $frule["tprice"] == "cost" ? $frule["amount"] : ( floatval ( $frule["amount"] ) / 100 ) * $cart_total;
                            	WC()->cart->add_fee($frule["title"], $fee_amount, $is_tax, "");
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * 
     * Evoluate the rules (Pricing or Fee) of the given field against the submitted user value
     * 
     * @param array $_rules
     * @param mixed $_value
     * @return boolean
     * 
     */
    public function check_rules($_rule, $_value, $_ftype, $_dformat) {
        if (($_rule && isset($_rule["expected_value"]) && isset($_rule["logic"]) && ! empty($_value)) || $_ftype == "datepicker") {
            if ($_ftype != "checkbox" && $_ftype != "datepicker") {
                if ($_rule["logic"] == "equal") {
                    return ($_rule["expected_value"] == $_value);
                } else if ($_rule["logic"] == "not-equal") {
                    return ($_rule["expected_value"] != $_value);
                } else if ($_rule["logic"] == "greater-than" && is_numeric($_rule["expected_value"]) && is_numeric($_value)) {
                    return ($_value > $_rule["expected_value"]);
                } else if ($_rule["logic"] == "less-than" && is_numeric($_rule["expected_value"]) && is_numeric($_value)) {
                    return ($_value < $_rule["expected_value"]);
                } else if ($_rule["logic"] == "greater-than-equal" && is_numeric($_rule["expected_value"]) && is_numeric($_value)) {
                    return ($_value >= $_rule["expected_value"]);
                } else if ($_rule["logic"] == "less-than-equal" && is_numeric($_rule["expected_value"]) && is_numeric($_value)) {
                    return ($_value <= $_rule["expected_value"]);
                } else if( $_rule["logic"] == "not-null" ){
                    $trimmed_value = trim( $_value );
                    if( !empty( $trimmed_value ) ){
                	    return true;
                	} else {
                	    return false;
                	}
                }
            } else if ($_ftype == "checkbox") {
                /* This must be a check box field */
                if (is_array($_rule["expected_value"]) && is_array($_value)) {
                    if ($_rule["logic"] == "is-only") { 
                        /* User chosen option (or options) has to be exact match */
                        /* In that case both end has to be same quantity */
                        if (count($_rule["expected_value"]) == count($_value)) {
                            /* Now check for the individual options are equals */
                            foreach ($_rule["expected_value"] as $e_val) {
                                if (! in_array($e_val, $_value)) {
                                    /* Well has exact quantity on both side but one or more different values */
                                    return false;
                                }
                            }
                            /* Has equal options, and all are matching with expected values */
                            return true;
                        }
                    } else if ($_rule["logic"] == "is-also") {
                        /* User chosen option should contains expected option
                         * There can be other options also chosen (but expected option has to be one of them) */
                        if (count($_value) >= count($_rule["expected_value"])) {
                            foreach ($_rule["expected_value"] as $e_val) {
                                if (! in_array($e_val, $_value)) {
                                    return false;
                                }
                            }
                            /* Well expected option(s) is chosen by the User */
                            return true;
                        }
                    } else if ($_rule["logic"] == "any-one-of") {
                        /* Well there can be more then one expected options, but any one of them are present 
                         * with the user submitted options then rules are met */
                        $res = false;
                        foreach ($_rule["expected_value"] as $e_val) {
                            if (in_array($e_val, $_value)) {
                                $res = true;
                            }
                        }
                        return $res;
                    }
                }
            } else if ($_ftype == "datepicker") {
            	
            	$user_date = DateTime::createFromFormat($_dformat, $_value);  
                if ($user_date && isset($_rule["expected_value"]["dtype"]) && isset($_rule["expected_value"]["value"])) { 
                    if ($_rule["expected_value"]["dtype"] == "days") {
                        /* If user chosed any specific day like "sunday", "monday" ... */
                    	$day = $user_date->format('l');                  	
                        if (is_array($_rule["expected_value"]["value"]) && in_array(strtolower($day), $_rule["expected_value"]["value"])) {
                            return true;
                        }
                    } 
                    if ($_rule["expected_value"]["dtype"] == "specific-dates") {             
                        /* Logic for any specific date matches ( Exact date ) */
                        $sdates = explode(",", (($_rule["expected_value"]["value"]) ? $_rule["expected_value"]["value"] : ""));
                    	if (is_array($sdates)) {
                    		foreach ($sdates as $sdate) {
                    			$sdate = DateTime::createFromFormat("m-d-Y", trim($sdate)); 
                    			 if ($user_date->format("Y-m-d") == $sdate->format("Y-d-m")) {
                    				return true;
                    			} 
                    		}
                    	}                        
                    } 
                    if ($_rule["expected_value"]["dtype"] == "weekends-weekdays") {
                        /* Logic for the weekends */
                    	if ($_rule["expected_value"]["value"] == "weekends") {
                    		if (strtolower($user_date->format('l')) == "saturday" || strtolower($user_date->format('l')) == "sunday") {
                    			return true;
                    		}
                    	} else {
                    		if (strtolower($user_date->format('l')) != "saturday" && strtolower($user_date->format('l')) != "sunday") {
                    			return true;
                    		}
                    	}
                        
                    } 
                    if ($_rule["expected_value"]["dtype"] == "specific-dates-each-month") {
                        /* Logic for the exact date of each month */
                        $sdates = explode(",", (($_rule["expected_value"]["value"]) ? $_rule["expected_value"]["value"] : ""));
                       
                        foreach ($sdates as $sdate) {
                            if (trim($sdate) == $user_date->format("j")) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    
}

?>