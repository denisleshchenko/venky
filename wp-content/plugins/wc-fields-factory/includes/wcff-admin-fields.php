<?php 

if (!defined('ABSPATH')) { exit; }

/**
 * 
 * 
 * 
 * @author Saravana Kumar K
 * @copyright Sarkware Pvt Ltd
 *
 */
class Wcff_AdminFields {
	
	var $location;
	
	var $is_datepicker_there = false;
	var $is_colorpicker_there = false;
	var $is_image_field_there = false;
	
	public function __construct() {
		$wccaf_locations = apply_filters('wccaf/locations', array (
			"woocommerce_product_options_general_product_data" => array($this, 'inject_wccaf_on_product_options_general_product_data'),
			"woocommerce_product_options_inventory_product_data" => array($this, 'inject_wccaf_on_product_options_inventory_product_data'),
			"woocommerce_product_options_shipping" => array($this, 'inject_wccaf_on_product_options_shipping'),
			"woocommerce_product_options_attributes" => array($this, 'inject_wccaf_on_product_options_attributes'),
			"woocommerce_product_options_related" => array($this, 'inject_wccaf_on_product_options_related'),
			"woocommerce_product_options_advanced" => array($this, 'inject_wccaf_on_product_options_advanced')
		));
		
		foreach ($wccaf_locations as $location => $callback) {
			if (is_callable($callback)) {
				add_action($location, $callback);
			}
		}
		
		/* Better to enqueue script here itself
		 * even if no fields on product view, since variable product fields
		 * will be injected through ajax, we have no way to enqueue scripts on ajax response  */
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_assets') );	
		
		add_action('admin_head-post.php', array($this, 'inject_wccaf_on_product_page'));
		
		add_action('product_cat_add_form_fields', array($this, 'inject_wccaf_on_product_cat_page_add'));
		add_action('product_cat_edit_form_fields', array($this, 'inject_wccaf_on_product_cat_page_edit'));
		
		add_action('save_post', array($this, 'save_wccaf_product_fields'), 1, 3);
		
		add_action('edited_product_cat', array($this, 'save_wccaf_product_cat_fields'));
		add_action('create_product_cat', array($this, 'save_wccaf_product_cat_fields'));
		
		add_action('woocommerce_product_after_variable_attributes', array($this, 'inject_wccaf_on_product_variable_section'), 10, 3);
		add_action('woocommerce_save_product_variation', array($this, 'save_wccaf_product_variable_fields'), 99, 2);
		
	}
	
	public function inject_wccaf_on_product_page() {
		global $post;
		if ($post->post_type == "product") {
			$this->location = "admin_head-post.php";
			$this->inject_wccaf();			
		}
	}
	
	public function inject_wccaf_on_product_cat_page_add() {
		$this->location = "product_cat_add_form_fields";
		$this->inject_wccaf();		
		/* Form clearance script */
		$this->wccaf_product_cat_form_clear();
	}
	
	public function inject_wccaf_on_product_cat_page_edit($_term) {
		$this->location = "product_cat_edit_form_fields";
		$this->inject_wccaf($_term);	
	}
	
	public function inject_wccaf_on_product_options_general_product_data() {
		$this->location = "woocommerce_product_options_general_product_data";
		$this->inject_wccaf();
	}
	
	public function inject_wccaf_on_product_options_inventory_product_data() {
		$this->location = "woocommerce_product_options_inventory_product_data";
		$this->inject_wccaf();
	}
	
	public function inject_wccaf_on_product_options_shipping() {
		$this->location = "woocommerce_product_options_shipping";
		$this->inject_wccaf();
	}
	
	public function inject_wccaf_on_product_options_attributes() {
		$this->location = "woocommerce_product_options_attributes";
		$this->inject_wccaf();
	}
	
	public function inject_wccaf_on_product_options_related() {
		$this->location = "woocommerce_product_options_related";
		$this->inject_wccaf();
	}
	
	public function inject_wccaf_on_product_options_advanced() {
		$this->location = "woocommerce_product_options_advanced";
		$this->inject_wccaf();
	}
	
	private function inject_wccaf($_term = null) {		
		global $post;
		$is_colorpicker_there = false;
		$is_image_field_there = false;		
		$all_fields = wcff()->dao->load_fields_for_product((($post) ? $post->ID : 0), 'wccaf', $this->location);
			
		$this->is_datepicker_there = false;
		$this->is_colorpicker_there = false;
		$this->is_image_field_there = false;
				
		if ($this->location != "admin_head-post.php") {
			
			do_action('wccaf_before_fields_start');
			
			foreach ($all_fields as $title => $fields) {
				if (is_array($fields) && count($fields) > 0) {
					foreach ($fields as $key => $field) {
					    $field["location"] = $this->location;
					    
					    /* 
					     * This is not necessary here, but variation fields have some issues, so we have to do this in all places
					     * Since CSS class name connot contains special characters especially [ ] */
					    if ($field["type"] == "datepicker" || $field["type"] == "colorpicker") {
					    	$field["admin_class"] = $field["name"];
					    }
					    
					    /* Retrive the value for this field */
					    $field["value"] = $this->determine_field_value($field, (($_term != null && isset($_term->term_id)) ? $_term->term_id : (($post) ? $post->ID : 0)));		
												
						do_action('wccaf_before_field_start', $field);
						
						/* generate html for wccaf fields */
						echo wcff()->builder->build_admin_field($field);
						
						do_action('wccaf_after_field_end', $field);
						
						if ($field["type"] == "datepicker") {
						    $this->is_datepicker_there = true;
						}
						if ($field["type"] == "colorpicker") {
							$this->is_colorpicker_there = true;
						}						
						if ($field["type"] == "image") {
							$this->is_image_field_there = true;
						}						
					}
				}
			}
			
			do_action('wccaf_after_fields_end');	
			
			/* If Date, Color or Image field is there then enqueue the appropriate script resources */
			if ($this->is_datepicker_there || $this->is_colorpicker_there || $this->is_image_field_there) {
				$this->wccaf_back_end_enqueue_scripts();
			}
			/* Enqueue validation script for Admin Fields
			 * Since we have no server side validation for Admin Fields */
			$this->wccaf_fields_validation();
		} else {
			$added = false;
			$location_group = wcff()->dao->load_all_location_rules();
			foreach ($location_group as $lrules) {
				foreach ($lrules as $lrule) {
					if ($lrule["context"] == "location_product" || $lrule["context"] == "location_product_cat") {
					    add_meta_box('wccaf_meta_box', "Additional Options", array($this, "inject_wccaf_meta_box"), get_current_screen() -> id, $lrule["endpoint"]["context"], $lrule["endpoint"]["priority"], array('fields' => $all_fields, 'location' => $this->location, "term" => $_term));
						$added = true;
						break;
					}
				}
				if ($added) {
					break;
				}
			}
		}			
		
	}
	
	public function inject_wccaf_on_product_variable_section($_loop, $variation_data, $_variation) {
		global $post;
		$this->location = "woocommerce_product_after_variable_attributes";
		$is_colorpicker_there = false;
		$all_fields = wcff()->dao->load_fields_for_product($post->ID, 'wccaf', $this->location);
		
		$this->is_datepicker_there = false;
		$this->is_colorpicker_there = false;
		$this->is_image_field_there = false;
		
		do_action('wccaf_before_fields_start');
		
		foreach ($all_fields as $title => $fields) {
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $key => $field) {
				    $field["location"] = $this->location;				    
				    
				    /* Since CSS class name connot contains special characters especially [ ] */
				    if ($field["type"] == "datepicker" || $field["type"] == "colorpicker") {
				        	$field["admin_class"] = $field["name"] ."_". $_loop;
				    }
				    
				    /* Retrive the value for this field */
				    $field["value"] = $this->determine_field_value($field, $_variation->ID);				    
				    
				    /* Prepare the name property */
				    $field["name"] = $field["name"] ."[". $_loop ."]";				    
				   	
					do_action('wccaf_before_field_start', $field);
					
					/* generate html for wccaf fields */
					echo wcff()->builder->build_admin_field($field);					
					
					do_action('wccaf_after_field_end', $field);
					
					if ($field["type"] == "datepicker") {
					    $this->is_datepicker_there = true;
					}
					if ($field["type"] == "colorpicker") {
					    $this->is_colorpicker_there = true;
					}
					if ($field["type"] == "image") {
					    $this->is_image_field_there = true;
					}					
				}
			}		
		}
		
		do_action('wccaf_after_fields_end');
		
		/* If Date, Color or Image field is there then enqueue the appropriate script resources */
		if ($this->is_datepicker_there || $this->is_colorpicker_there || $this->is_image_field_there) {
		    $this->wccaf_back_end_enqueue_scripts();
		}
		/* Enqueue validation script for Admin Fields
		 * Since we have no server side validation for Admin Fields */
		$this->wccaf_fields_validation();			
	}
	
	public function inject_wccaf_meta_box($_post, $_margs) {
	    $this->is_datepicker_there = false;
	    $this->is_colorpicker_there = false;
	    $this->is_image_field_there = false;
	    
		if (isset($_margs["args"]["fields"])) {
			
			do_action('wccaf_before_fields_start');
			
			foreach ($_margs["args"]["fields"] as $title => $fields) {
				if (is_array($fields) && count($fields) > 0) {
					foreach ($fields as $key => $field) {					
						$field["location"] = $_margs["args"]["location"];	
						
						/*
						 * This is not necessary here, but variation fields have some issues, so we have to do this in all places
						 * Since CSS class name connot contains special characters especially [ ] */
						if ($field["type"] == "datepicker" || $field["type"] == "colorpicker") {
							$field["admin_class"] = $field["name"];
						}
						
						/* Retrive the value for this field */
						if ($_margs["args"]["location"] != "product_cat_edit_form_fields") {
						    $field["value"] = $this->determine_field_value($field, $_post->ID);
						} else {
						    if (isset($_margs["args"]["term"]) && $_margs["args"]["term"]->term_id) {
						        $field["value"] = $this->determine_field_value($field, $_margs["args"]["term"]->term_id);
						    } else {
						        $field["value"] = ($field["type"] != "checkbox") ? "" : array();						        
						    }
						}				
						
						do_action('wccaf_before_field_start', $field);
						
						/* generate html for wccaf fields */
						echo wcff()->builder->build_admin_field($field);
						
						do_action('wccaf_after_field_end', $field);
						
						if ($field["type"] == "datepicker") {
							$this->is_datepicker_there = true;
						}
						if ($field["type"] == "colorpicker") {
							$this->is_colorpicker_there = true;
						}
						if ($field["type"] == "image") {
							$this->is_image_field_there = true;
						}	
					}
				}
			}
			
			do_action('wccaf_after_fields_end');
			
		}
		
		/* If Date, Color or Image field is there then enqueue the appropriate script resources */
		if ($this->is_datepicker_there || $this->is_colorpicker_there || $this->is_image_field_there) {
			$this->wccaf_back_end_enqueue_scripts();
		}
		/* Enqueue validation script for Admin Fields
		 * Since we have no server side validation for Admin Fields */
		$this->wccaf_fields_validation();
	}
	
	public function save_wccaf_product_fields($_post_id, $_post, $update) {
		$all_fields = wcff()->dao->load_fields_for_product($_post_id, 'wccaf', "any");		
		foreach ($all_fields as $title => $fields) {
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $key => $field) {
					/* If all checkbox is unchecked then the fields itself won;t be presented in the REQUEST object
					 * But we need to clear the existing meta for checkbox field */
					if (isset($_REQUEST[$field["name"]])) {
						$this->persist($_post_id, $field, $_REQUEST[$field["name"]], "product");	
					} else if (!isset($_REQUEST[$field["name"]]) && $field["type"] == "checkbox") {
					    $this->persist($_post_id, $field, array(), "product");													
					}
				}
			}
		}		
	}
	
	public function save_wccaf_product_cat_fields($_term_id) {
		$this->location = "product_cat_edit_form_fields";
		$all_fields = wcff()->dao->load_fields_for_product($_term_id, 'wccaf', $this->location);		
		foreach ($all_fields as $title => $fields) {
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $key => $field) {
					/* If all checkbox is unchecked then the fields itself won;t be presented in the REQUEST object
					 * But we need to clear the existing meta for checkbox field */
				    if (isset($_REQUEST[$field["name"]])) {
				    	$this->persist($_term_id, $field, $_REQUEST[$field["name"]], "cat");
				    } else if (!isset($_REQUEST[$field["name"]]) && $field["type"] == "checkbox") {
				    	$this->persist($_term_id, $field, array(), "cat");
				    }
				}
			}
		}		
	}
	
	public function save_wccaf_product_variable_fields($_variant_id, $_i) {
		global $post;
		$parent_post_id = -1;
		if (!$post) {
			$parent_post_id = wp_get_post_parent_id($_variant_id);
		} else {
			$parent_post_id = $post->ID;
		}		
		$this->location = "woocommerce_product_after_variable_attributes";
		$all_fields = wcff()->dao->load_fields_for_product($parent_post_id, 'wccaf', $this->location);
		
		foreach ($all_fields as $title => $fields) {
			if (is_array($fields) && count($fields) > 0) {
				foreach ($fields as $key => $field) {
					/* If all checkbox is unchecked then the fields itself won;t be presented in the REQUEST object
					 * But we need to clear the existing meta for checkbox field */					
					if (isset($_REQUEST[$field["name"]][$_i])) {
						$this->persist($_variant_id, $field, $_REQUEST[$field["name"]][$_i], "variable");
					} else if (!isset($_REQUEST[$field["name"]]) && $field["type"] == "checkbox") {
						$this->persist($_variant_id, $field, array(), "variable");
					}
				}
			}
		}		
	}
	
	private function persist($_id, $_meta, $_val, $_type) {	    
	    $_val = is_array($_val) ? implode(",", $_val) : $_val;
	    if ($_type != "cat") {    	
	        update_post_meta($_id, "wccaf_" . $_meta["name"], $_val);
	    } else {
	        update_option("taxonomy_product_cat_". $_id."_wccaf_". $_meta["name"], $_val);
	    }
	}
	
	private function determine_field_value($_meta, $_id = 0) {	    
	    $mval = false;
	    $meta_exist = false; 
	    if ($_meta["location"] != "product_cat_edit_form_fields") {
	    	if (metadata_exists("post", $_id, "wccaf_". $_meta["name"])) {
	    	   	$meta_exist = true;
	    		/* Well get the value */
	            $mval = get_post_meta($_id, "wccaf_". $_meta["name"], true);	            
	            /* Incase of checkbox - the values has to be deserialzed as Array */
	            if ($_meta["type"] == "checkbox" && is_string($mval)) {
	                $mval = explode(',', $mval);
	            }
	    	} else {	            
	            /* This will make sure the following section fill with default value instead */
	            $mval = false;	            
	        }
	    } else {
	        $mval = get_option("taxonomy_product_cat_". $_id . "_wccaf_" . $_meta["name"]);
	        /* Incase of checkbox - the values has to be deserialzed as Array */
	        if ($_meta["type"] == "checkbox" && is_string($mval)) {
	            $mval = explode(',', $mval);
	        }
	    }
	    /* We can trust this since we never use boolean value for any meta
	     * instead we use 'yes' or 'no' values */	    
	    if ( $meta_exist == false && $mval == false ) {
	        /* Value is not there - probably this field is not yet saved */
	        if ($_meta["type"] == "checkbox") {
	            $d_choices = array();
	            if (is_array($_meta["default_value"])) {
	                $d_choices = $_meta["default_value"];
	            } else {
	                if ($_meta["default_value"] != "") {
	                    $choices = explode(";", $_meta["default_value"]);
	                    foreach ($choices as $choice) {
	                    	$d_value = explode("|", $choice);
	                    	$d_choices[] = $d_value[0];
	                    }
	                }
	            }	            	            
	            $mval = $d_choices;
	        } else if ($_meta["type"] == "radio" || $_meta["type"] == "select") {
	            $mval = "";
	            if (isset($_meta["default_value"]) && $_meta["default_value"] != "") {
	            	$d_value = explode("|", $_meta["default_value"]);
	            	$mval = $d_value[0];
	            }
	        } else {
	            /* For rest of the fields - no problem */
	        	$mval = isset($_meta["default_value"]) ? $_meta["default_value"] : "";
	        }
	    }
	    
	    if ( $meta_exist && ( $mval == false || $mval == null || $mval == "") && $_meta["type"] == "checkbox" ) {
	    	$mval = array();
	    }	    
	   
	    return $mval;
	}
	
	private function wcff_check_screen( $scr_id ) {
		if( $scr_id == "wccpf-options" ) {
			return ( ( get_current_screen() -> id == "wccpf" ) || ( get_current_screen() -> id == "wccaf" ) || ( get_current_screen() -> id == "wccsf" ) || ( get_current_screen() -> id == "wccrf" ) || get_current_screen() -> id == "wccpf-options" );
		}
		return get_current_screen() -> id == $scr_id;
	}
	
	public function enqueue_admin_assets() {
		if( $this->wcff_check_screen( "product" ) || $this->wcff_check_screen( "edit-product_cat" ) ) {
			wp_register_style( 'wccaf-spectrum-css', wcff()->info['dir'] . 'assets/css/spectrum.css' );
			wp_register_style( 'wccaf-timepicker-css', wcff()->info['dir'] . 'assets/css/jquery-ui-timepicker-addon.css' );
			wp_enqueue_style( 'wccaf-spectrum-css' );
			wp_enqueue_style( 'wccaf-timepicker-css' );
			wp_register_script( 'wccaf-color-picker', wcff()->info['dir'] . 'assets/js/spectrum.js' );
			wp_enqueue_script( 'wccaf-color-picker' );
			/* Wordpress by default won't enqueue datepicker script on Taxonomy pages */
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );			
			wp_register_script( 'wccaf-datepicker-i18n', wcff()->info['dir'] . 'assets/js/jquery-ui-i18n.min.js' );
			wp_register_script( 'wccaf-datetime-picker', wcff()->info['dir'] . 'assets/js/jquery-ui-timepicker-addon.min.js' );
			wp_enqueue_script( 'wccaf-datetime-picker' );
			wp_enqueue_script( 'wccaf-datepicker-i18n' );
		}	
	}
	
	private function wccaf_back_end_enqueue_scripts() {		
		
		if ($this->is_image_field_there) { ?>
		
		<script type="text/javascript">

			/* Used to holds wordpress media browser's instance */
			var wcff_media_uploader = null;

			(function($) {

				$( document ).on( "click", ".wcff_upload_image_button", function() {
					
					var btn = $( this );
					var ifield = btn.parent().prev().prev().prev();
					var ufield = btn.parent().prev();
					var pfield = btn.closest(".wccaf-image-field-wrapper");
					
					if ( wcff_media_uploader ) {
						wcff_media_uploader.open();
					  	return;
					}

					wcff_media_uploader = wp.media.frames.file_frame = wp.media({
					  	title: btn.data( 'uploader_title' ),					  	
					  	multiple: false
					});

					wcff_media_uploader.on( 'select', function() {
						var attachment = wcff_media_uploader.state().get('selection').first().toJSON();						
						ufield.val( attachment.id );
						if( attachment.sizes["thumbnail"].url != "" ) {
							ifield.attr( 'src',attachment.sizes["thumbnail"].url );
						} else {
							ifield.attr( 'src',attachment.url );
						}
						btn.parent().hide();						
						ifield.show();						
						pfield.removeClass( "has_image" ).addClass( "has_image" );
					});

					wcff_media_uploader.open();					
					
				});

				$( document ).on( 'click', 'a.wccaf-image-remove-btn', function(e) {
					
					$( this ).next().val( '' );
					$( this ).prev().attr( 'src', '' );
					$( this ).prev().hide();
					$( this ).next().next().show();
					$( this ).closest(".wccaf-image-field-wrapper").removeClass( "has_image" );					

					e.preventDefault();
				});
				
			})(jQuery);

		</script>
			
		<?php 
		}
	}
	
	private function wccaf_fields_validation() { ?>
		<script type="text/javascript">

			/* Validation flag */
			var wccaf_is_valid = true;
			
			(function($) {
				
				$( document ).on( "blur", ".wccaf-field", function(e) {
					var me = $(this);	
					setTimeout(function() {
						doValidate( me );
						$("input[name=save]").removeClass("disabled");
						$("input[name=save]").parent().find(".spinner").hide();
					}, 500);														
				});	
				
				$(document).on("submit", "#post", function(){			 
					wccaf_is_valid = true;
					$( ".wccaf-field" ).each(function(){
						/**
						 * If the fields are shown in General Tab, and user tries to add an variable product
						 * in which case the General Tab itself in hidden, so for those knids of reason
						 * its better to check the vivibility of the field before applying validation rules */
						if ($(this).is(":visible")) {
							doValidate( $(this) );
						}						
					});				

					/**
					 * Incase if validation failed then 
					 * Remove the disabled class of the wordpress publish button.
					 * Also hide the spinner icon as well.
					 */
					if (!wccaf_is_valid) {
						$("#publishing-action").find("#publish").removeClass("disabled");
						$("#publishing-action").find("span.spinner").removeClass("is-active");
					}
					/* Return 'true' or 'false' */			
					return wccaf_is_valid;				
				});

				function doValidate( field ) {
					if( field.attr("wccaf-type") != "radio" && field.attr("wccaf-type") != "checkbox" ) {					
						if( field.attr("wccaf-mandatory") == "yes" ) {						
							if( doPatterns( field.attr("wccaf-pattern"), field.val() ) ) {
								field.parent().find("span.wccaf-validation-message").hide();
							} else {		
								wccaf_is_valid = false;
								field.parent().find("span.wccaf-validation-message").css("display", "block");
								/* Scroll down to this field so that admin can aware that field value is missing */
								$('html,body').animate(
									{ scrollTop: field.parent().offset().top - 50  },
									'slow'
								);
							}
						}
					} else {
						if( field.attr("wccaf-mandatory") == "yes" ) {	
							if( $("input[name="+ field.attr("name") +"]").is(':checked') ) {
								field.parent().find("span.wccaf-validation-message").css("display", "block");
							} else {
								wccaf_is_valid = false;
								field.parent().find("span.wccaf-validation-message").hide();
								/* Scroll down to this field so that admin can aware that field value is missing */
								$('html,body').animate(
									{ scrollTop: field.parent().offset().top - 50  },
									'slow'
								);
							}	 
						}
					}
				}				
				
				function doPatterns( patt, val ) {
					var pattern = {
						mandatory	: /\S/, 
						number		: /^\d*$/,
						email		: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,	      	
					};			    
				    return pattern[ patt ].test(val);	
				}
				
			})(jQuery);
		</script>		
	<?php 
	}
	
	/**
	 * 
	 * Since wordpress term creat form uses Ajax to submit fields
	 * We need to clear our custom fields manualy once the term is submited 
	 * 
	 */
	private function wccaf_product_cat_form_clear() { ?>
	    
	    <script type="text/javascript">
	    (function($) {
	    		$( document ).ajaxComplete( function( event, request, options ) {
				if ( request && 4 === request.readyState && 200 === request.status
					&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

					var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
					if ( ! res || res.errors ) {
						return;
					}
					// Clear wccaf fields
					$(".wccaf-field").each(function() {
						if ($(this).attr("wccaf-type") === "text" ||
								$(this).attr("wccaf-type") === "number" ||
								$(this).attr("wccaf-type") === "email" ||
								$(this).attr("wccaf-type") === "hidden" ||
								$(this).attr("wccaf-type") === "textarea" ||
								$(this).attr("wccaf-type") === "select" ||
								$(this).attr("wccaf-type") === "url") {
							$(this).val("");
						} else if($(this).attr("wccaf-type") === "radio" ||
								$(this).attr("wccaf-type") === "checkbox") {
							$(this).prop("checked", false);
						} else if($(this).attr("wccaf-type") === "image") {
							
						}
					});

					$("div.wccaf-image-field-wrapper.has_image").find("input[type=hidden]").val("");
					$("div.wccaf-image-field-wrapper.has_image").find("img").hide();
					$("div.wccaf-image-field-wrapper.has_image").find(".wccaf-img-field-btn-wrapper").show();
					$("div.wccaf-image-field-wrapper.has_image").removeClass("has_image");
					
					return;
				}
			} );
		})(jQuery);	    
	    </script>
	    
	    <?php 
	}
}

new Wcff_AdminFields();

?>