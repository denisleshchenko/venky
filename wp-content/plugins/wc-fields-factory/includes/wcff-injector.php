<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Wcff_FieldsInjector {
	
	private $group_index = 1;
	
	private $fields_cloning = "no";
	private $multilingual = false;
	
	private $product_fields = null;
	private $admin_fields = null;
	private $product = null;
	private $color_picker_count = 0;
	private $wcff_option_printed = false;
	
	public function __construct() {}
	
	public function inject( $p_location ) {
	    Global $product;
	    $this->product = $product;
		$fields_group_title = "";
		$wccpf_options = wcff()->option->get_options();
		
		$this->fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$this->multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
		
		if (isset( $wccpf_options["fields_group_title"]) && $wccpf_options["fields_group_title"] != "") {
			$fields_group_title = $wccpf_options["fields_group_title"];
		} else {
			$fields_group_title = "Additional Options : ";
		}
		/* Translate cloning title - if multilingual option enabled */
		if ($this->multilingual == "yes") {
			$current_locale = wcff()->locale->detrmine_current_locale();
			if ($current_locale != "en" && isset($wccpf_options["fields_group_title_". $current_locale]) && ! empty($wccpf_options["fields_group_title_". $current_locale])) {
				$fields_group_title = $wccpf_options["fields_group_title_". $current_locale];
			}
		}
		
		/* Let other plugins change the Cloning Title */
		if (has_filter('wccpf_cloning_fields_group_title')) {
			$fields_group_title = apply_filters('wccpf_cloning_fields_group_title', $fields_group_title);
		}
		
		$this->product_fields = wcff()->dao->load_fields_for_product($this->get_product_id($this->product), 'wccpf', "product-page", $p_location, false);
		$this->admin_fields = wcff()->dao->load_fields_for_product($this->get_product_id($this->product), 'wccaf', 'any', $p_location, false);
		
		do_action('wccpf_before_fields_start');
		
		/* Inject label field - whichever comes at top */
		$this->handle_label_field("beginning");
		echo '<div class="wccpf-fields-container">';
		/* Fields wrapper container starts here ( only for cloning option, otherwise no wrapper added ) */
		if ($this->fields_cloning == "yes") {
			if (count($this->product_fields) > 0 || count($this->admin_fields) > 0) {
				
				if( wcff()->option->get_options()["field_location"] == $p_location ){
				    echo '<input type="hidden" id="wccpf_fields_clone_count" value="1" />';
				}
				echo '<div class="wccpf-fields-group">';
				echo '<h4>'. $fields_group_title .' <span class="wccpf-fields-group-title-index">1</span></h4>';
				if($this->product->is_type( 'variable' ) ){
				    echo '<div class="wcff-variation-field"  data-area="'.$p_location.'"></div>';
				}
			} else {
			    if($this->product->is_type( 'variable' ) ){
			         echo '<div class="wcff-variation-cloning-field-container"><div class="wccpf-fields-container"><div class="wccpf-fields-group"><h4>'. $fields_group_title .' <span class="wccpf-fields-group-title-index">1</span></h4><div class="wcff-variation-field"  data-area="'.$p_location.'"></div></div></div></div>';
			    }
			}
		}
		
		
		if( !$this->wcff_option_printed ):
		$this->wcff_option_printed = true;
		/**/
		?>
    	    
	    <?php
	    endif;
		
		/* Inject product fields */
		echo $this->render_product_fields();
		/* Reset group index */
		$this->group_index = 1;
		/* Inject admin fields */
		echo $this->render_admin_fields();
		
		/**/
		if( method_exists(WC()->session, "__unset") ){
		      WC()->session->__unset("wcff_validation_failed");
	    }
		if ($this->fields_cloning == "yes") {
			if (count($this->product_fields) > 0 || count($this->admin_fields) > 0) {
				echo '</div>';
			}
		}
		echo '</div>';
		/* Cloning wrapper ends */
		
		/* Inject label field - whichever comes at bottom */
		$this->handle_label_field("end");
		
		do_action( 'wccpf_after_fields_end' );
		
		/* if product has variation inject date and color picker scripts */
		if($this->product->is_type( 'variable' ) ){
			if( $this->fields_cloning != "yes" ){
			     echo '<div class="wcff-variation-field"  data-area="'.$p_location.'"></div>';
			}
		}
		// render color picker scripts
	    echo $this->enqueue_color_picker_script();
	}
	
	public function inject_variation_field( $_variation_id ){
		$variation_fields_html = array();
		$wccpf_options = wcff()->option->get_options();
		$this->fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
		$this->multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
		$variation_p_fields_group = wcff()->dao->load_fields_for_product($_variation_id, 'wccpf', "product-page", '',  true, true);
		$variation_a_fields_group = wcff()->dao->load_fields_for_product($_variation_id, 'wccaf', 'any', '', true, true);
		
		$all_product_fields = array();
		$all_admin_fields   = array();
		for( $i = 0; $i < count ( $variation_p_fields_group ); $i++ ){
		    $this->product_fields = array( $variation_p_fields_group[$i]["fields"] );
		    $all_product_fields[] = $variation_p_fields_group[$i]["fields"];
		    /* Reset group index */
		    $this->group_index = 1;
    		/* Inject product fields */
		    $htmls = !empty( $this->product_fields ) ? $this->render_product_fields() : "";
		    $variation_fields_html[] = array( "location" => $variation_p_fields_group[$i]["location"], "html" => $htmls );
		}
		
		for( $i = 0; $i < count ( $variation_a_fields_group ); $i++ ){
		    $this->admin_fields = array( $variation_a_fields_group[$i]["fields"] );
		    $all_admin_fields[] = $variation_a_fields_group[$i]["fields"];
		    /* Reset group index */
		    $this->group_index = 1;
    		/* Inject admin fields */
		    $htmls = !empty( $this->admin_fields ) ? $this->render_admin_fields($_variation_id) : "";
		    $variation_fields_html[] = array( "location" => $variation_a_fields_group[$i]["location"], "html" => $htmls );
		}
		$this->product_fields = $all_product_fields;
		$this->admin_fields   = $all_admin_fields;
		$variation_fields_html[] = array( "location" => "color_picker_scripts", "html" => $this->enqueue_color_picker_script($_variation_id, true) );
		return array( "data" => $variation_fields_html, "date_picker" => "" );
	}
	
	private function render_product_fields() {
		$pfieldhtml = "";
		if (count($this->product_fields) > 0) {
			foreach ($this->product_fields as $fields) {
				if (is_array($fields) && count($fields) > 0) {
					$pfieldhtml .= '<div class="wccpf-fields-group-'. $this->group_index++ .'">';
					
					/* Trigger 'wccpf_before_fields_group_start' action, can be used to display title for this group */
					do_action('wccpf_before_fields_group_start', $this->group_index);
					$field_rules_script = '<script> var $ = jQuery;
													jQuery(document).ready(function(){ 
														jQuery(document).on("change", "[data-has_field_rules=yes]", function(){
															';
					foreach ($fields as $key => $field) {
					    if(!isset( $field["type"] )){
					        continue;
					    }
					    if ( $field["type"] == "label" && $field["position"] != "normal") {
							continue;
						}	
						if ($this->multilingual == "yes") {
							/* Localize field */
							$field = wcff()->locale->localize_field($field);
						}  	
						/*
						 * This is not necessary here, but variation fields have some issues, so we have to do this in all places
						 * Since CSS class name connot contains special characters especially [ ] */
						if ($field["type"] == "datepicker" || $field["type"] == "colorpicker") {
							$field["admin_class"] = $field["name"];
						}
						
						if (WC()->session->__isset("wcff_validation_failed")) {
							/* Last add to cart operation failed
							 * Try to restore the fields old value */
							$index = "";
							if ($this->fields_cloning == "yes") {
								$index= "_1";
							}
							if (isset($_REQUEST[$field["name"] . $index])) {
								$field["default_value"] = $_REQUEST[$field["name"] . $index];
							}
						}
						
						/* generate html for wccpf fields */
						$html = wcff()->builder->build_user_field($field, "wccpf");
						
						/* Field rules script */
						if(isset($field["field_rules"]) && is_array($field["field_rules"]) && count($field["field_rules"]) != 0){
							$is_field_clone_enable = $this->fields_cloning == "yes" && $field["cloneable"] == "yes" ? "true" : "false";
							$field_rules_script .= 'var field_name = jQuery(this).attr( "name" ), clone_index = '.$is_field_clone_enable.' ? field_name.slice( field_name.lastIndexOf("_"),  field_name.length ) : "";if(false){}';
							if ($field["type"] != "checkbox" ) {
							    $field_rules_script .= 'else if(jQuery(this).attr("name") == "'.$field["name"].'"+clone_index+"" ){';
							} else if ($field["type"] == "checkbox") {
							    $field_rules_script .= 'else if(jQuery(this).attr("name") == "'.$field["name"].'"+clone_index+"[]" ){';
							} 
							for( $fi = 0; $fi < count($field["field_rules"]); $fi++ ){
								$field_rules_script .= wcff()->negotiator->field_rules_script_render( $field["field_rules"][$fi], $field["type"], $field["name"] );
							}
							$field_rules_script .= "$(this).closest(\".wccpf-fields-container\").find(\"table:not(.wcff_is_hidden_from_field_rule) [data-is_pricing_rules='yes']\").trigger( \"change\" ); }";
						}
						
						/* Allow third party apps logic to render wccpf fields with their own wish */
						if (has_filter('wccpf_before_fields_rendering')) {
							$html = apply_filters('wccpf_before_fields_rendering', $field, $html);
						}
						
						do_action('wccpf_before_field_start', $field);
						
						$pfieldhtml .= $html;
						
						do_action('wccpf_after_field_end', $field);
						
					}
					$field_rules_script .= "});
										});
									</script>";
					$pfieldhtml .= '</div>'.$field_rules_script;
				}
			}
		}
		return $pfieldhtml;
	}
	
	private function render_admin_fields( $variable_id = 0 ) {
		$afieldhtml = "";
		$prod_id = "";
		if($variable_id == 0){
		    $prod_id = $this->get_product_id($this->product);
		} else {
			$prod_id = $variable_id;
		}
	    if (count($this->admin_fields) > 0) {
			foreach ($this->admin_fields as $afields) {
			    if (count($afields) > 0) {
					$afieldhtml .= '<div class="wccpf-admin-fields-group-'. $this->group_index++ .'">';
					foreach ($afields as $key => $afield) {
					    if(!isset( $afield["type"] )){
					        continue;
					    }
					    
						$afield["show_on_product_page"] = isset($afield["show_on_product_page"]) ? $afield["show_on_product_page"] : "no";
						/* Url field is special case here
						 * As the reason for the field itself to display some reference link on front end product page */
						if ($afield["show_on_product_page"] == "yes" || $afield["type"] == "url") {							
							if ($this->multilingual == "yes") {
								/* Localize field */								
								$afield = wcff()->locale->localize_field($afield);
							}  		
						    /* Determine the fields value */
							$afield["default_value"] = $this->determine_field_value($afield, $prod_id);
							/* Set this property for helping builder
							 * Admin's select, check & radio fields value attribute is difference from the Product field */
							$afield["is_admin_field"] = true;
							
							if (WC()->session->__isset("wcff_validation_failed")) {
								/* Last add to cart operation failed
								 * Try to restore the fields old value */
								$index = "";
								if ($this->fields_cloning == "yes") {
									$index= "_1";
								}
								if (isset($_REQUEST[$afield["name"] . $index])) {
									$afield["default_value"] = $_REQUEST[$afield["name"] . $index];
								}
							}
							
							/*
							 * This is not necessary here, but variation fields have some issues, so we have to do this in all places
							 * Since CSS class name connot contains special characters especially [ ] */
							if ($afield["type"] == "datepicker" || $afield["type"] == "colorpicker") {
								$afield["admin_class"] = $afield["name"];
							}
							
							/* generate html for wccpf fields
							 * Eventhough it is an admin field we are rendering this as a product field ( hense 'wccpf' ) */
							$html = wcff()->builder->build_user_field($afield, "wccpf");
							
							/* Allow third party apps logic to render wccpf fields with their own wish */
							if (has_filter('wccpf_before_fields_rendering')) {
								$html = apply_filters('wccpf_before_fields_rendering', $afield, $html);
							}
							
							do_action('wccpf_before_field_start', $afield);
							
							$afieldhtml .= $html;
							
							do_action('wccpf_after_field_end', $afield);
							
						}
					}
					$afieldhtml .= '</div>';
			    }
			}
		}
		
		return $afieldhtml;
	}
	
	/**
	 * 
	 * Helper method for retrieving Admin Field's value
	 * If value no there then default value will be returned
	 * Except check box other fields value will be returned as it is,
	 * but for checkbox the value will be converted as Array and then returned
	 * 
	 * @param object $_meta
	 * @param number $_id
	 * @param string $_ptype
	 * @return boolean|array| unknown|mixed|string
	 * 
	 */
	private function determine_field_value($_meta, $_id = 0) {
	    $mval = false;
	    /**
	     * We are assuming that here the user will use whatever the Admin Fields that is placed for the product page
	     * not on the Product Taxonomies page or Admin Fields for variable sections. because it doesn't make any sense.
	     * and if they do then we have a problem here
	     */
        if (metadata_exists("post", $_id, "wccaf_". $_meta["name"])) {
            $mval = get_post_meta($_id, "wccaf_". $_meta["name"], true);
            /* Incase of checkbox - the values has to be deserialzed as Array */
            if ($_meta["type"] == "checkbox") {
                $mval = explode(',', $mval);
            }
        } else {
            /* This will make sure the following section fill with default value instead */
            $mval = false;
        }	    
	    /* We can trust this since we never use boolean value for any meta
	     * instead we use 'yes' or 'no' values */
	    if (!$mval) {
	        /* Value is not there - probably this field is not yet saved */
	        if ($_meta["type"] == "checkbox") {
	            $d_choices = array();
	            if ($_meta["default_value"] != "") {
	                $choices = explode(";", $_meta["default_value"]);
	                if (is_array($choices)) {
	                    foreach ($choices as $choice) {
	                    	$d_value = explode("|", $choice);
	                    	$d_choices[] = $d_value[0];
	                    }
	                }
	            }
	            $mval = $d_choices;
	        } else if ($_meta["type"] == "radio" || $_meta["type"] == "select") {
	            $mval = "";
	            if ($_meta["default_value"] != "") {
	            	$d_value = explode("|", $_meta["default_value"]);
	            	$mval = $d_value[0];
	            }
	        } else {
	            /* For rest of the fields - no problem */
	        	$mval = isset($_meta["default_value"]) ? $_meta["default_value"] : "";
	        }
	    }
	    return $mval;
	}
	
	private function handle_label_field($position = "beginning") {
		foreach ($this->product_fields as $fields) {
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $field) {
					if ($field["type"] == "label" && $field["position"] == $position) {
						/* generate html for wccpf fields */
						$html = wcff()->builder->build_user_field($field, "wccpf");
						/* Allow third party apps logic to render wccpf fields with their own wish */
						if (has_filter('wccpf_before_fields_rendering')) {
							$html = apply_filters('wccpf_before_fields_rendering', $field, $html);
						}
						
						do_action('wccpf_before_field_start', $field);
						
						echo $html;
						
						do_action('wccpf_after_field_end', $field);
					}
				}
			}
		}		
	}
	
	/**
	 *
	 * @param WC_Product $_product
	 * @return integer
	 *
	 * Wrapper method for getting Wc Product object's ID attribute
	 *
	 */
	private function get_product_id($_product){
	    return method_exists($_product, 'get_id') ? $_product->get_id() : $_product->id;
	}
	
	
	/**
	 * 
	 * Enqueue assets for Front end Product Page
	 * 
	 * @param boolean $isdate_css
	 * 
	 */
	public function enqueue_client_side_assets($isdate_css = false) { if ( is_product() || is_cart() || is_checkout() && is_archive() || is_shop() ) :
	       $wccpf_options = wcff()->option->get_options();
	       $field_glob_location = isset( $wccpf_options["field_location"] ) ? $wccpf_options["field_location"] : "woocommerce_before_add_to_cart_button";
	       ?>
	       
	       <script type="text/javascript">
	       
    	    var wccpf_opt = {
    	    	editable : "<?php echo isset( $wccpf_options["edit_field_value_cart_page"] ) ? $wccpf_options["edit_field_value_cart_page"] : "no" ?>",
    	        cloning : "<?php echo isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no"; ?>",
    	        location : "<?php echo $field_glob_location; ?>",
    	        validation : "<?php echo isset( $wccpf_options["client_side_validation"] ) ? $wccpf_options["client_side_validation"] : "no"; ?>",
    	        validation_type : "<?php echo isset( $wccpf_options["client_side_validation_type"] ) ? $wccpf_options["client_side_validation_type"] : "submit"; ?>",
    	        ajax_pricing_rules_title : "<?php echo isset( $wccpf_options["ajax_pricing_rules_title"] ) ? $wccpf_options["ajax_pricing_rules_title"] : "hide"; ?>",
    	        ajax_pricing_rules_title_header : "<?php echo isset( $wccpf_options["ajax_pricing_rules_title_header"] ) ? $wccpf_options["ajax_pricing_rules_title_header"] : ""; ?>",
    		    ajax_pricing_rules_price_container_is : "<?php echo isset( $wccpf_options["ajax_pricing_rules_price_container"] ) ? $wccpf_options["ajax_pricing_rules_price_container"] : "default"; ?>",
    	        ajax_price_replace_container : "<?php echo isset( $wccpf_options["ajax_price_replace_container"] ) ? $wccpf_options["ajax_price_replace_container"] : ""; ?>",
    	        price_details : "<?php echo isset( $wccpf_options["pricing_rules_details"] ) && $wccpf_options["pricing_rules_details"] == "show" ? true : false; ?>",
    	        color_picker_functions    : [],
    	        is_ajax_add_to_cart : "<?php echo get_option( 'woocommerce_enable_ajax_add_to_cart' ); ?>",
    	        is_page : "<?php echo ( is_shop() ? "archive" : "single" ); ?>"
    	    };
    	    </script>
	
	
			<?php
	        // Jquery init
			wp_enqueue_script( "jquery" ); 
			// Jquery ui and time picker style
			wp_enqueue_style("wcff-jquery-ui-style", wcff()->info['dir'].'assets/css/jquery-ui.css');
			wp_enqueue_style("wcff-timepicker-style", wcff()->info['dir'].'assets/css/jquery-ui-timepicker-addon.css');
			// jquery UI Core
			$jquery_ui_core = includes_url() . "/js/jquery/ui/core.min.js";									
			if(!file_exists(ABSPATH ."wp-includes/js/jquery/ui/core.min.js")) {
				$jquery_ui_core = includes_url() . "/js/jquery/ui/jquery.ui.core.min.js";											
			} 
			wp_enqueue_script( 'jquery-ui-core', $jquery_ui_core );
			// Jquery Date pciker
			$jquery_ui_datepicker = includes_url() . "/js/jquery/ui/datepicker.min.js";	
			if(!file_exists(ABSPATH ."wp-includes/js/jquery/ui/core.min.js")) {
				$jquery_ui_datepicker = includes_url() . "/js/jquery/ui/jquery.ui.datepicker.min.js";
			}
			wp_enqueue_script( 'jquery-ui-datepicker', $jquery_ui_datepicker );
			
			// Jquery Multi-Language 
			wp_enqueue_script( 'jquery-ui-i18n', wcff()->info['dir'].'assets/js/jquery-ui-i18n.min.js' );
			// Jquery Time Picker script
			wp_enqueue_script( 'jquery-ui-timepicker-addon', wcff()->info['dir'].'assets/js/jquery-ui-timepicker-addon.min.js' );
			// Color Picker css
			wp_enqueue_style("wcff-colorpicker-style", wcff()->info['dir'].'assets/css/spectrum.css');
			// Color Picker Script
			wp_enqueue_script( 'wcff-colorpicker-script', wcff()->info['dir'].'assets/js/spectrum.js' );
			// wcff Client css 
			wp_enqueue_style("wcff-client-style", wcff()->info['dir'].'assets/css/wcff-client.css');
			//wcff Client Script
			wp_enqueue_script( 'wcff-client-script', wcff()->info['dir'].'assets/js/wcff-client.js' );
			?>	
    		<?php if(is_shop()): ?>
    		
    		<script>
    			jQuery( document ).on( "click", ".wccpf_fields_table ", function(e){
        			var target = jQuery( e.target );
        			if( !target.is( ".wccpf_fields_table" ) && !target.is("input[type='checkbox']") && !target.is("input[type='radio']") && !( target.is( "label" ) && target.find("input[type='checkbox'],input[type='radio'],input[type='file']").length != 0 ) ){
						return false;
					}
            	});
    		</script>
    		<?php endif; ?>
		<?php endif; ?>
		
		<?php 
	}
	
	private function enqueue_color_picker_script($variable_id = 0, $_is_variation = false) {
	    $this->color_picker_count += 1;
		$live_prod_id = "";
		$is_color_prod_image = false;
		$color_method_name = "";
		$is_color_picker_available = false;
		if($variable_id == 0){
		    $live_prod_id = $this->get_product_id($this->product);
		} else {
			$live_prod_id = $variable_id;
		}
	    if( $_is_variation  ){
	        $color_method_name = "wccpf_init_color_pickers_variation_".$this->color_picker_count;
	    } else {
	        $color_method_name = "wccpf_init_color_pickers_".$this->color_picker_count;
	    }
		$color_picker_script = "";
		$color_picker_script .= '<script type="text/javascript">
				
				function '.$color_method_name.'() {';
		
		foreach ( $this->product_fields as $title => $fields ) {
			foreach ( $fields as $key => $field ) {
			    if( !isset( $field["type"] ) ){
			        continue;
			    }
				if( $field["type"] == "colorpicker" ) {
				    $is_color_picker_available = true;
					$palettes = null;
					$colorformat = isset( $field["color_format"] ) ? $field["color_format"] : "hex";
					$defaultcolor = isset( $field["default_value"] ) ? $field["default_value"] : "#000";
					
					if( isset( $field["palettes"] ) && $field["palettes"] != "" ) {
						$palettes = explode( ";", $field["palettes"] );
					}
											
				$color_picker_script .=	'var native_image = jQuery( "figure.woocommerce-product-gallery__wrapper" ).clone();jQuery( ".wccpf-color-'.esc_attr( $field["name"] ).'").spectrum({
						 color: "'.$defaultcolor.'", 
						 preferredFormat: "'.$colorformat.'",';					
				        if( $field["show_palette_only"] != "yes" ){
				            if( isset( $field["color_text_field"] ) && $field["color_text_field"] == "yes" ){
        				        $color_picker_script .= "showInput: true,";
				            }
				        } else {
        				    if( isset( $field["color_image"] ) && is_array( $field["color_image"] ) ){
        				        $color_picker_script .= "change: function(color) { if(false){} ";
        				        for( $r = 0; $r < count ( $field["color_image"] ); $r++ ){
        				            if( !$is_color_prod_image ){
        				                $is_color_prod_image = true;
        				            }
        				            $logig = $field["color_image"][$r]["logic"] == "equal" ? "==" : "!=";
        				            $color_picker_script .= "else if( color.toHexString() ".$logig." '".$field["color_image"][$r]["expected_value"]."' || ( '".$field["color_image"][$r]["expected_value"]."'.length == 4 && color.toHexString().slice( 0,4 ) ".$logig." '".$field["color_image"][$r]["expected_value"]."' ) ){";
        				            if( $field["color_image"][$r]["image_or_url"] == "image" ){
        				                $img_wrapper  = wc_get_gallery_image_html( $field["color_image"][$r]["url"], false );
        				                $img_html     = '<figure class="woocommerce-product-gallery__wrapper">';
        				                $img_html    .= apply_filters( 'wc_fields_factory_image_change', $img_wrapper, $field["color_image"][$r]["url"] );
        				                $img_html    .= '</figure>';
        				                $color_picker_script .= "jQuery( 'figure.woocommerce-product-gallery__wrapper' ).replaceWith( '".$img_html."' );";
        				            } else { 
        				                $color_picker_script .= 'window.location = "'.$field["color_image"][$r]["url"].'";';
        				            }
        				            $color_picker_script .= "}";
            				    }
            				        $color_picker_script .= " else {";
    				                 $color_picker_script .= 'jQuery( "figure.woocommerce-product-gallery__wrapper" ).replaceWith(  native_image );';
    				                $color_picker_script .= "}";
            				    $color_picker_script .= "},";
        				    }
        				}
						$comma = "";
						$indexX = 0;
						$indexY = 0;
						if( is_array( $palettes ) && count( $palettes ) > 0 ) {
							if( $field["show_palette_only"] == "yes" ) {
								$color_picker_script .= "showPaletteOnly: true,";
							} 
							$color_picker_script .= "showPalette: true,";
							$color_picker_script .= "palette : [";						
							foreach ( $palettes as $palette ) {		
								$indexX = 0;								
								$comma = ( $indexY == 0 ) ? "" : ",";
								$color_picker_script .= $comma."[";
								$colors = explode( ",", $palette );
							 	foreach ( $colors as $color ) {							 		
							 		$comma = ( $indexX == 0 ) ? "" : ","; 
							 		$color_picker_script .= $comma ."'". $color ."'";	
							 		$indexX++;
								}
								$color_picker_script .= "]";
								$indexY++;
							} 
							$color_picker_script .= "]";						
						}
				$color_picker_script .= '});';		
				}
			}
		}
			
		if (count($this->admin_fields) > 0) {
			foreach ($this->admin_fields as $title => $afields) {
				if (count($afields) > 0) {
					foreach ($afields as $key => $afield) {
						if($afield["type"] == "colorpicker") {
						    $is_color_picker_available = true;
							$palettes = null;
							$colorformat = isset($afield["color_format"]) ? $afield["color_format"] : "hex";
							$defaultcolor = isset($afield["default_value"]) ? $afield["default_value"] : "#000";
							
							$mval = get_post_meta($live_prod_id, "wccaf_". $afield["name"], true);
							if (!$mval || $mval == "") {								
								$mval = $defaultcolor;								
							}
								
							if (isset( $afield["palettes"]) && $afield["palettes"] != "") {
								$palettes = explode( ";", $afield["palettes"] );
							} 
							
							$color_picker_script .= 'jQuery(".wccpf-color-'.esc_attr($afield["name"]).'").spectrum({
								 color: "'.$mval.'", 
								 preferredFormat: "'.$colorformat.'",';			
    							if ($afield["show_palette_only"] != "yes" && isset( $afield["color_text_field"] ) && $afield["color_text_field"] == "yes" ) {
    							    $color_picker_script .= "showInput: true,";
    							}
								$comma = "";
								$indexX = 0;
								$indexY = 0;
								if (is_array($palettes) && count($palettes) > 0) {
									if ($afield["show_palette_only"] == "yes") {
										$color_picker_script .= "showPaletteOnly: true,";
									} 
									$color_picker_script .= "showPalette: true,";
									$color_picker_script .= "palette : [";						
									foreach ($palettes as $palette) {		
										$indexX = 0;								
										$comma = ($indexY == 0) ? "" : ",";
										$color_picker_script .= $comma."[";
										$colors = explode(",", $palette);
									 	foreach ($colors as $color) {							 		
									 		$comma = ($indexX == 0) ? "" : ","; 
									 		$color_picker_script .= $comma ."'". $color ."'";	
									 		$indexX++;
										}
										$color_picker_script .= "]";
										$indexY++;
									} 
									$color_picker_script .= "]";						
								}
							$color_picker_script .= '});';	
						}
					}	
				}				
			}
		}
			
		$color_picker_script .= '}';
		if( $_is_variation ){
	       $color_picker_script .= 'if( typeof wccpf_init_color_pickers_variation_'.$this->color_picker_count.' != "undefined" ){wccpf_opt.color_picker_functions.push( "wccpf_init_color_pickers_variation_'.$this->color_picker_count.'" );wccpf_init_color_pickers_variation_'.$this->color_picker_count.'();}';
		} else {
    		$color_picker_script .= 'jQuery(document).ready(function() { if( typeof jQuery != "undefined" && typeof jQuery.spectrum != "undefined" ){';
    		$color_picker_script .= 'if( typeof wccpf_init_color_pickers_'.$this->color_picker_count.' != "undefined" ){wccpf_opt.color_picker_functions.push( "wccpf_init_color_pickers_'.$this->color_picker_count.'" );wccpf_init_color_pickers_'.$this->color_picker_count.'();}';
    		if( $is_color_prod_image ){
    		    $color_picker_script .= 'jQuery( ".wccpf-color" ).trigger( "change" );';
    		}
    		$color_picker_script .= '} });';
		}
		
		$color_picker_script .= '</script>';
		if( !$is_color_picker_available ){
		    $color_picker_script = "";
		}
		return $color_picker_script;
	}
	
}





?>