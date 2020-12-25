<?php

if (!defined('ABSPATH')) { exit; }

/**
 * 
 * One of the core module, responsible for generating everything that related to Fields Factory UI<br>
 * Privides methods for generating Fields configuration related meta fields.<br>
 * Provides methods for generating Product Fields, Admin Field and other UI widgets.
 * 
 * @author : Saravana Kumar K
 * @copyright : Sarkware Pvt Ltd 
 *
 */
class Wcff_Builder {
    
	private $wccpf_options = array();
	private $is_multilingual = "no";
	private $supported_locale = array();
	
    public function __construct() {}
    
    /**
     * 
     * Generate Product List using Select tag ( id => Title )<br>
     * Mostly used in Product Rules widget.
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_products_list($_class, $_active = "") {
        $products = wcff()->dao->load_products();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        $html .= '<option value="-1">All Products</option>';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $selected = ($product["id"] == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($product["id"]) . '" ' . $selected . '>' . esc_html($product["title"]) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     *
     * Generate Product Category List using Select tag ( id => Title )<br>
     * Mostly used in Product Rules widget.
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_products_cat_list($_class, $_active = "") {
        $pcats = wcff()->dao->load_product_cats();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        $html .= '<option value="-1">All Categories</option>';
        if (count($pcats) > 0) {
            foreach ($pcats as $pcat) {
                $selected = ($pcat["id"] == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($pcat["id"]) . '" ' . $selected . '>' . esc_html($pcat["title"]) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * 
     * Generate Product Tag List using Select tag ( id => Title )<br>
     * Mostly used in Product Rules widget. 
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_products_tag_list($_class, $_active = "") {
        $ptags = wcff()->dao->load_product_tags();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        $html .= '<option value="-1">All Tags</option>';
        if (count($ptags) > 0) {
            foreach ($ptags as $ptag) {
                $selected = ($ptag["id"] == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($ptag["id"]) . '" ' . $selected . '>' . esc_html($ptag["title"]) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * 
     * Generate Product Type List using Select tag ( id => Title )<br>
     * Mostly used in Product Rules widget. 
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_products_type_list($_class, $_active = "") {
        $ptypes = wcff()->dao->load_product_types();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        $html .= '<option value="-1">All Types</option>';
        if (count($ptypes) > 0) {
            foreach ($ptypes as $ptype) {
                $selected = ($ptype["id"] == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($ptype["id"]) . '" ' . $selected . '>' . esc_html($ptype["title"]) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    
    /**
     *
     * Generate Product Type List using Select tag ( id => Title )<br>
     * Mostly used in Product Rules widget.
     *
     * @param string $_class
     * @param string $_active
     * @return string
     *
     */
    public function build_products_varions_list($_class, $_active = "", $_prod_id = 0) {
    	
    	$html = "";
    	// for variation product list
    	$ptypes = wcff()->dao->load_products_with_variation();
	    $html .= '<select class="variation_product_list">';
	    $html .= '<option value="0">All Products</option>';
	    if (count($ptypes) > 0) {
	    	foreach ($ptypes as $ptype) {
	    		$selected = ($ptype["id"] == $_prod_id) ? 'selected="selected"' : '';
	    		$html .= '<option value="' . esc_attr($ptype["id"]) . '" ' . $selected . '>' . esc_html($ptype["title"]) . '</option>';
	    	}
	    }
	    $html .= '</select>';
	    // for variation list
    	$ptypes = wcff()->dao->load_product_variations($_prod_id);
    	$html .= '<select class="' . esc_attr($_class) . ' select">';
    	$html .= '<option value="-1">All Variations</option>';
    	if (count($ptypes) > 0) {
    		foreach ($ptypes as $ptype) {
    			$selected = ($ptype["id"] == $_active) ? 'selected="selected"' : '';
    			$html .= '<option value="' . esc_attr($ptype["id"]) . '" ' . $selected . '>' . esc_html($ptype["title"]) . '</option>';
    		}
    	}
    	$html .= '</select>';
    	return $html;
    }
    
    /**
     * 
     * Generate Single Product Page's Tab List using Select tag ( Slug => Title )<br>
     * Mostly used in Location Rules widget. 
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_products_tabs_list($_class, $_active = "") {
        $ptabs = wcff()->dao->load_product_tabs();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        if (count($ptabs) > 0) {
            foreach ($ptabs as $pttitle => $ptvalue) {
                $selected = ($ptvalue == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($ptvalue) . '" ' . $selected . '>' . esc_html($pttitle) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * 
     * Generate Product Tab List using Select tag ( Slug => Title )<br>
     * Mostly used in Location Rules widget.  
     * 
     * @param string $_class
     * @param string $_active
     * @return string
     * 
     */
    public function build_metabox_context_list($_class, $_active = "") {
        $mcontexts = wcff()->dao->load_metabox_contexts();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        if (count($mcontexts) > 0) {
            foreach ($mcontexts as $mckey => $mcvalue) {
                $selected = ($mckey == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($mckey) . '" ' . $selected . '>' . esc_html($mcvalue) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     *
     * Generate Priority List using Select tag ( Slug => Title )<br>
     * Mostly used in Location Rules widget.
     *
     * @param string $_class
     * @param string $_active
     * @return string
     *
     */
    public function build_metabox_priority_list($_class, $_active = "") {
        $mpriorities = wcff()->dao->load_metabox_priorities();
        $html = '<select class="' . esc_attr($_class) . ' select">';
        if (count($mpriorities) > 0) {
            foreach ($mpriorities as $mpkey => $mpvalue) {
                $selected = ($mpkey == $_active) ? 'selected="selected"' : '';
                $html .= '<option value="' . esc_attr($mpkey) . '" ' . $selected . '>' . esc_html($mpvalue) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * 
     * Generate Fields List for given wcff post ( Post type could be 'wccpf' or 'wccaf' )
     * 
     * @param object $_fields
     * @return string
     * 
     */
    public function build_custom_fields_list($_fields) {
        global $post;
        $html = "";
        $it = 1;
        $wccpf_options = wcff()->option->get_options();
        $is_multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
        $supported_locale = isset($wccpf_options["supported_lang"]) ? $wccpf_options["supported_lang"] : array();
        foreach ($_fields as $key => $field) {
            $field_toggle = isset( $field["is_enable"] ) ? 'data-is_enable="'.( $field["is_enable"] ? "true" : "false"  ).'"' : '';
            $html .= '<div class="wcff-meta-row" data-key="' . esc_attr($key) . '" data-type="' . $field["type"] . '" data-unremovable="'.( isset( $field["is_unremovable"] ) && $field["is_unremovable"] ? "true" : "false"  ).'" '.$field_toggle.'>
						<table class="wcff_table">
							<tbody>
								<tr>
									<td class="field-order wcff-sortable">
										<span class="wcff-field-order-number wcff-field-order">'. $it .'</span>
									</td>
									<td class="field-label">
    										<label class="wcff-field-label" data-key="'. esc_attr ( $key ) . '">' . esc_html($field["label"]) . '</label>';
                                    if($is_multilingual == "yes" && count($supported_locale) > 0) {
                                        $html .=  '<button class="wcff-factory-multilingual-label-btn" title="Open Multilingual Panel"><img src="'. (esc_url(wcff()->info["dir"] ."assets/img/translate.png")) .'"/></button>';
                                        $html .=  '<div class="wcff-factory-locale-label-dialog">';
                                        $locales = wcff()->locale->get_locales();
                                        foreach ($supported_locale as $code) {
                                            $html .=  '<div class="wcff-locale-block" data-param="label">';
                                            $html .=  '<label>Label for '. $locales[$code] .'</label>';
                                            $html .=  '<input type="text"  name="wcff-field-type-meta-label-'. $code .'" class="wcff-field-type-meta-label-'. $code .'" value="" />';
                                            $html .=  '</div>';
                                        }
                                        $html .=  '</div>';
                                    }
									$html .= '</td>
									<td class="field-name">
										<label class="wcff-field-name">' . str_replace( array("wccpf_", "wccpf_"), array("", ""), $field["name"] ) . '</label>
									</td>
									<td class="field-type">
										<label class="wcff-field-type"><span style="background: url('.plugins_url('../assets/img/'.$field["type"].'.png', __FILE__ ).') no-repeat left;"></span>' . $field["type"] .'</label>
									</td>
									<td class="field-actions">
										<div class="wcff-meta-option">';
									if( isset( $field["is_unremovable"] ) && $field["is_unremovable"] ){
									    $checked = isset( $field["is_enable"] ) && $field["is_enable"] ? "checked" : "";
									    $html .= '<label class="wcff-switch" data-key="' . esc_attr ( $key ) . '"> <input class="wcff-toggle-check" type="checkbox" '.$checked.'> <span class="slider round"></span> </label>';
									} else  {
									    $html .= '<a href="#" data-key="' . esc_attr ( $key ) . '" class="wcff-field-delete button">x</a>';
									}
										$html .= '</div>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" name="' . esc_attr($key) . '_order" class="wcff-field-order-index" value="' . $field["order"] . '" />';
						$html .= wcff_field_config_wrapper( $post->post_type );
            $html .= '</div>';
            $it++;
        }
        return $html;
    }
    
    /**
     * 
     * Generate the fields configuration widget for a given Field Type<br>
     * Heavily relies on 'factory_meta_loop' method.
     * 
     * @param string $_ftype
     * @param string $_ptype
     * @return string|boolean
     * 
     */
    public function build_factory_fields($_ftype = "text", $_ptype = "wccpf") {
        /* Load the config meta */
        $fields_meta = wcff()->dao->get_fields_meta();
        $common_meta = apply_filters("before_render_common_meta", wcff()->dao->get_fields_common_meta(), $_ptype, $_ftype );
        $wccaf_common_meta = apply_filters("before_render_admin_common_meta", wcff()->dao->get_admin_fields_comman_meta(), $_ptype, $_ftype );
        /* Load the options */
        $this->wccpf_options = wcff()->option->get_options();
        $this->is_multilingual = isset($this->wccpf_options["enable_multilingual"]) ? $this->wccpf_options["enable_multilingual"] : "no";
        $this->supported_locale = isset($this->wccpf_options["supported_lang"]) ? $this->wccpf_options["supported_lang"] : array();
        /* Lets begin */
        if (isset($fields_meta[$_ftype])) {
            $fields_meta[$_ftype] = apply_filters("before_render_field_meta", $fields_meta[$_ftype], $_ptype, $_ftype );
            $html = '';
            /* Make sure whether this field is supported for the given Post Type */
            if (in_array($_ptype, $fields_meta[$_ftype]["support"])) {
                if (isset($fields_meta[$_ftype]["document"]) && ! empty($fields_meta[$_ftype]["document"])) {
                    /* Insert a config row for Documentation Link */
                    $html .= '<tr>';
                    /* Left container TD starts here */
                    $html .= '<td class="summary">';
                    $html .= '<label>Documentation</label>';
                    $html .= '<p class="description">Reference documentation for ' . $fields_meta[$_ftype]["title"] . '</p>';
                    $html .= '</td>';
                    /* Left container TD ends here */
                    /* Right container TD starts here */
                    $html .= '<td>';
                    $html .= '<a href="' . $fields_meta[$_ftype]["document"] . '" target="_blank" title="Click here for documentation">How to use this.?</a>';
                    $html .= '</td>';
                    /* Right container TD ends here */
                    $html .= '<tr>';
                }
                /* Field's specific metas */
                $html .= $this->factory_meta_loop($fields_meta[$_ftype]["meta"], $_ftype, $_ptype);
                /* Include common meta */
                $html .= $this->factory_meta_loop($common_meta, $_ftype, $_ptype);
                /* Include common meta specif to Admin Field */
                if ($_ptype == "wccaf") {
                    $html .= $this->factory_meta_loop($wccaf_common_meta, $_ftype, $_ptype);
                }
                return $html;
            }
        }
        return false;
    }
    
    /**
     * 
     * Iterate through field's config meta and generate the factory widget for a given fields type.
     * 
     * @param array $_metas
     * @param string $_ftype
     * @param string $_ptype
     * @return string
     * 
     */
    private function factory_meta_loop($_metas = array(), $_ftype = "text", $_ptype = "wccpf") {
		$html = '';
		/* Iterate over all the meta for and construct the HTML */
		foreach ( $_metas as $meta ) {
			/* Special property used only on Textarea method */
			$meta ["ftype"] = $_ftype;
			/* Make sure this attribute is available for this field type */
			if (isset ( $meta ["include_if_not"] ) && ! empty ( $meta ["include_if_not"] ) && in_array ( $_ftype, $meta ["include_if_not"] )) {
				continue;
			}
			if ($_ptype == "wccaf" && isset ( $meta ["param"] ) && $meta ["param"] == "visibility" && $_ftype != "image") {
				/*
				 * This meta has to be inserted above the visibility config
				 * Only for the Admin Fields, since the sequence is impossible with normal flow
				 * we are forced to hard code it here
				 */
				$html .= $this->build_factory_meta_wrapper ( array (
					"label" => __ ( 'Show on Product Page', 'wc-fields-factory' ),
					"desc" => __ ( 'Whether to show this custom admin field on front end product page.', 'wc-fields-factory' ),
					"type" => "radio",
					"param" => "show_on_product_page",
					"layout" => "vertical",
					"options" => array (
						array (
							"value" => "yes",
							"label" => __ ( 'Show in Product Page', 'wc-fields-factory' ),
							"selected" => false 
						),
						array (
							"value" => "no",
							"label" => __ ( 'Hide in Product Page', 'wc-fields-factory' ),
							"selected" => true 
						) 
					),
					"include_if_not" => array (
						"image" 
					),
					"at_startup" => "show",
					"translatable" => "no"
				), $_ptype );
			}
			/* Well time to wrap it */
			$html .= $this->build_factory_meta_wrapper ( $meta, $_ptype );
			/*
			 * Include the role list selector config
			 * after the "Logged in Users Only" config
			 */
			if (isset ( $meta ["param"] ) && $meta ["param"] == "login_user_field") {
				global $wp_roles;
				$role_list = array();
				foreach ( $wp_roles->roles as $handle => $role ) {					
					$role_list[] = array(
						"value" => $handle,
						"label" => $role["name"]
					);
				}			
				$html .= $this->build_factory_meta_wrapper ( array (
					"label" => __ ( 'Target Roles', 'wc-fields-factory' ),
					"desc" => __ ( 'Show this field if only the logged in user has the following roles.', 'wc-fields-factory' ),
					"type" => "checkbox",
					"param" => "show_for_roles",
					"layout" => "horizontal",
					"options" => $role_list,
					"include_if_not" => array (
						"image"
					),
					"at_startup" => "hide",
					"translatable" => "no"
				), $_ptype );
			}
			
			if (isset ( $meta ["param"] ) && $meta ["param"] == "timepicker") {
				$html .= $this->build_factory_meta_wrapper ( array (
					"label" => __ ( 'Allowed Hours & Minutes', 'wc-fields-factory' ),
					"desc" => __ ( 'Specify minimum and maximum hours & minutes that user can select from.', 'wc-fields-factory' ),
					"type" => "html",
					"param" => "min_max_hours_minutes",
					"html" => '<div class="wccpf-datepicker-min-max-wrapper"><input type="text" id="wccpf-datepicker-min-max-hours" placeholder="0:23" value=""/> <strong>:</strong> <input type="text" id="wccpf-datepicker-min-max-minutes" placeholder="0:59" value=""/></div>',
					"include_if_not" => array (),
					"at_startup" => "hide",
					"translatable" => "no"
				), $_ptype );
			}
		}
		
		return $html;
	}
    
    /**
     * 
     * @param object $_meta
     * @param string $_ptype
     * @return string
     * 
     */
    private function build_factory_meta_wrapper($_meta, $_ptype) {
        /* Meta row TR starts here */
    	$html = '<tr style="' . ((($_ptype == "wccaf" || $_meta["param"] == "show_for_roles" || $_meta["param"] == "min_max_hours_minutes") && isset($_meta["at_startup"]) && $_meta["at_startup"] == "hide") ? "display:none;" : "") . '">';
        
        /* Left container TD starts here */
        $html .= '<td class="summary">';
        $html .= '<label>' . $_meta["label"] . '</label>';
        $html .= '<p class="description">' . $_meta["desc"] . '</p>';
        $html .= '</td>';
        /* Left container TD ends here */
        
        /* Add a padding right for the translate button - if the field is translatable */
        
        $padding_right = ($this->is_multilingual == "yes" && $_meta["translatable"] == "yes") ? 'padding-right: 60px;' : '';
        /* Right container TD starts here */
        $html .= '<td style="' . $padding_right . '">';
        
        if ($this->is_multilingual == "yes" && $_meta["translatable"] == "yes") {
            $html .= '<button class="wcff-factory-multilingual-btn" title="Open Multilingual Panel"><img src="' . (esc_url(wcff()->info["dir"] . "assets/img/translate.png")) . '"/></button>';
        }
        
        if ($_meta["type"] != "tab") {
            /* Meta field's wrapper starts here */
            $html .= '<div class="wcff-field-types-meta" data-type="' . $_meta["type"] . '" data-param="' . $_meta["param"] . '">';
            $html .= $this->build_factory_meta_field($_meta, $_ptype);
            $html .= '</div>';
            /* Meta field's wrapper ends here */
            
            /* If this confog option is translatable then add those fields as well */
            if ($this->is_multilingual == "yes" && count($this->supported_locale) > 0 && $_meta["translatable"] == "yes") {
            	$locales = wcff()->locale->get_locales();
                $html .= '<div class="wcff-locale-list-wrapper" style="display: none;">';
                if ($_meta["param"] != "default_value") {
                    foreach ($this->supported_locale as $code) {                    	
                        $html .= '<div class="wcff-locale-block" data-param="' . $_meta["param"] . '">';
                        $html .= '<label>' . $_meta["label"] . ' for ' . $locales[$code] . '</label>';
                        if ($_meta["type"] == "text") {
                            $html .= '<input type="text" name="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" class="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" value="" />';
                        } else {
                            if ($_meta["ftype"] != "label") {
                                /* This must for the Choices option */
                                $html .= '<table class="wcff-choice-factory-container">';
                                $html .= '<tbody>';
                                $html .= '<tr>';
                                $html .= '<td class="field">';
                                $html .= '<div class="wcff-locale-block" data-param="' . $_meta["param"] . '">';
                                $html .= '<textarea name="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" data-locale="' . $code . '" class="wcff-choices-textarea"></textarea>';
                                $html .= '</div>';
                                $html .= '</td>';
                                $html .= '<td class="factory">';
                                $html .= '<input type="text" class="wcff-option-value-text" placeholder="Type the ' . $locales[$code] . ' Value">';
                                $html .= '<input type="text" class="wcff-option-label-text" placeholder="Type the ' . $locales[$code] . ' Label">';
                                $html .= '<button class="wcff-add-opt-btn" data-target="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" data-target-param="' . $_meta["param"] . '" data-ftype="' . $_meta["ftype"] . '">Add Option</button>';
                                $html .= '</td>';
                                $html .= '</tr>';
                                $html .= '</tbody>';
                                $html .= '</table>';
                            } else {
                                $html .= '<div class="wcff-locale-block" data-param="' . $_meta["param"] . '">';
                                $html .= '<textarea name="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" data-locale="' . $code . '" class="wcff-label-message-textarea"></textarea>';
                                $html .= '</div>';
                            }
                        }
                        $html .= '</div>';
                    }
                } else {
                	if ($_meta["ftype"] == "select" || $_meta["ftype"] == "radio" || $_meta["ftype"] == "checkbox") {
                		/*
                		 * Since we are using real time option creation for default_value param
                		 * We just need to put warpper and let the client side module handle the rest
                		 */
                		foreach ($this->supported_locale as $code) {                			
                			$html .= '<div>';
                			$html .= '<label>' . $_meta["label"] . ' for ' . $locales[$code] . '</label>';
                			$html .= '<div class="wcff-default-choice-wrapper wcff-default-option-holder-' . $code . '"></div>';
                			$html .= '</div>';
                		}
                	} else {      
                		foreach ($this->supported_locale as $code) {
                			$html .= '<div class="wcff-locale-block" data-param="' . $_meta["param"] . '">';
                			$html .= '<label>' . $_meta["label"] . ' for ' . $locales[$code] . '</label>';  
                			$html .= '<input type="text" name="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" class="wcff-field-type-meta-' . $_meta["param"] . '-' . $code . '" value="" />';
                			$html .= '</div>';
                		}
                	}                    
                }
                $html .= '</div>';
            }
            
            /* Some times there are two fields for the same meta attribute */
            if (isset($_meta["additonal"])) {
                /* Meta field's wrapper starts here */
                $html .= '<div class="wcff-field-types-meta" data-type="' . $_meta["additonal"]["type"] . '" data-param="' . $_meta["additonal"]["param"] . '">';
                $html .= $this->build_factory_meta_field($_meta["additonal"], $_ptype);
                $html .= '</div>';
                /* Meta field's wrapper ends here */
            }
        } else {
            $html .= $this->build_factory_meta_tab_widget($_meta, $_ptype);
        }
        
        $html .= '</td>';
        /* Right container TD ends here */
        
        $html .= '</tr>';
        /* Meta row TR ends here */
        
        return $html;
    }
    
    /**
     * 
     * Helper method which delegate the task to other method to generate fields for Factory Widget
     * 
     * @param object $_meta
     * @param string $_ptype
     * @return string|unknown
     * 
     */
    private function build_factory_meta_field($_meta, $_ptype) {
        $html = '';
        /* Meta field starts here */
        if ($_meta["type"] == "text" || $_meta["type"] == "email" || $_meta["type"] == "number" || $_meta["type"] == "password") {
            $html = $this->build_factory_meta_input_field($_meta, $_ptype);
        } else if ($_meta["type"] == "textarea") {
            $html = $this->build_factory_meta_textarea_field($_meta, $_ptype);
        } else if ($_meta["type"] == "radio" || $_meta["type"] == "checkbox") {
            $html = $this->build_factory_meta_option_field($_meta, $_ptype);
        } else if ($_meta["type"] == "select") {
            $html = $this->build_factory_meta_select_field($_meta, $_ptype);
        } else if ($_meta["type"] == "html") {
            $html = $_meta["html"];
        } else {
            /* Unlikely */
            $html = '';
        }
        /* Meta field ends here */
        return $html;
    }
    
    /**
     * 
     * Generate Input fields for Fcatory Widget
     * 
     * @param object $_meta
     * @param string $_ptype
     * @return string
     * 
     */
    private function build_factory_meta_input_field($_meta, $_ptype="wccpf") {
        return '<input type="'. $_meta["type"].'" name="wcff-field-type-meta-'. $_meta["param"] .'" class="wcff-field-type-meta-'. $_meta["param"] .'" placeholder="'. $_meta["placeholder"] .'" value="" />';
    }
    
    /**
     * 
     * Generate Textarea field for Factory Widget
     * 
     * @param object $_meta
     * @param string $_ptype
     * @return string
     * 
     */
    private function build_factory_meta_textarea_field($_meta, $_ptype = "wccpf") {
        $html = '';
        if ($_meta["param"] == "choices" && ($_meta["ftype"] == "radio" || $_meta["ftype"] == "checkbox" || $_meta["ftype"] == "select")) {
            $html = '<table class="wcff-choice-factory-container">';
            $html .= '<tr>';
            $html .= '<td class="field">';
            $html .= '<textarea name="wcff-field-type-meta-' . $_meta["param"] . '" class="wcff-field-type-meta-' . $_meta["param"] . '" class="wcff-choices-textarea" placeholder="' . $_meta["placeholder"] . '" rows="' . $_meta["rows"] . '"></textarea>';
            $html .= '</td>';
            $html .= '<td class="factory">';
            
            $html .= '<input type="text" class="wcff-option-value-text" placeholder="Type the Value" />';
            $html .= '<input type="text" class="wcff-option-label-text" placeholder="Type the Label" />';
            $html .= '<button class="wcff-add-opt-btn" data-target="wcff-field-type-meta-' . $_meta["param"] . '" data-target-param="' . $_meta["param"] . '" data-ftype="' . $_meta["ftype"] . '">Add Option</button>';
            
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        } else {
            $html = '<textarea name="wcff-field-type-meta-' . $_meta["param"] . '" class="wcff-field-type-meta-' . $_meta["param"] . '" placeholder="' . $_meta["placeholder"] . '" rows="' . $_meta["rows"] . '"></textarea>';
        }
        return $html;
    }
    
    /**
     *
     * Generate Check Box as well as Radio Button fields for Factory Widget
     *
     * @param object $_meta
     * @param string $_ptype
     * @return string
     *
     */
    private function build_factory_meta_option_field($_meta, $_ptype = "wccpf") {
        $html = '<ul class="wcff-field-layout-' . $_meta["layout"] . '">';
        foreach ($_meta["options"] as $option) {
            $checked = '';
            if (isset($option["selected"]) && $option["selected"]) {
                $checked = 'checked';
            }
            $html .= '<li><label><input type="' . $_meta["type"] . '" class="wcff-field-type-meta-' . $_meta["param"] . '" value="' . $option["value"] . '" ' . $checked . ' /> ' . $option["label"] . '</label></li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    /**
     *
     * Generate Select field for Factory Widget
     *
     * @param object $_meta
     * @param string $_ptype
     * @return string
     *
     */
    private function build_factory_meta_select_field($_meta, $_ptype = "wccpf") {
        $html = '<select name="wcff-field-type-meta-' . $_meta["param"] . '" class="wcff-field-type-meta-' . $_meta["param"] . '">';
        foreach ($_meta["options"] as $option) {
            $selected = '';
            if (isset($option["selected"]) && $option["selected"]) {
                $selected = 'selected';
            }
            $html .= '<option value="' . $option["value"] . '" ' . $selected . '>' . $option["label"] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     *
     * Generate Tab widget for Factory Widget<br>
     * Like the one used in the Date Field config
     *
     * @param object $_meta
     * @param string $_ptype
     * @return string
     *
     */
    private function build_factory_meta_tab_widget($_meta, $_ptype = "wccpf") {
        /* Accordian wrapper starts here */
        $html = '<div class="wcff-factory-tab-container">';
        
        /* Left side header panel starts here */
        $html .= '<div class="wcff-factory-tab-left-panel">';
        $html .= '<ul>';
        foreach ($_meta["tabs"] as $tab) {
            $html .= '<li data-box="' . $tab["header"]["target"] . '" class="' . $tab["header"]["css_class"] . '">' . $tab["header"]["title"] . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        /* Left side header anel ends here */
        
        /* Left side header panel starts here */
        $html .= '<div class="wcff-factory-tab-right-panel">';
        foreach ($_meta["tabs"] as $tab) {
            /* Tab content section starts here */
            $html .= '<div id="' . $tab["content"]["container"] . '" class="wcff-factory-tab-content">';
            
            foreach ($tab["content"]["fields"] as $field) {
                /* Meta field's wrapper starts here */
                $html .= '<div class="wcff-field-types-meta" data-type="' . $field["type"] . '" data-param="' . $field["param"] . '">';
                $html .= $this->build_factory_meta_field($field, $_ptype);
                $html .= '</div>';
                /* Meta field's wrapper ends here */
            }
            
            $html .= '</div>';
            /* Tab content section ends here */
        }
        $html .= '</div>';
        /* Left side header anel ends here */
        
        $html .= '</div>';
        /* Accordian wrapper ends here */
        return $html;
    }
    
    /**
     * 
     * Primary handler for generating Fields, which will be injected into Single Product Page<br>
     * It evoluate the field's meta and delegate the task to various helper methods the get the fields HTML<br>
     * Even the Admin fields which some times injected into Product Page also achived via this method<br>
     * If the fields not need to be wrapped with the default fields wrapper then call this method with @$_wrapper=false
     * 
     * @param object $_meta
     * @param string $_ptype
     * @param string $_wrapper
     * @return string|string|unknown
     * 
     */
    public function build_user_field($_meta, $_ptype = "wccpf", $_wrapper = true) {
        $html = '';
        /* Load the config option object */
        $wccpf_options = wcff()->option->get_options();
        /* */
        $fields_cloning = isset($wccpf_options["fields_cloning"]) ? $wccpf_options["fields_cloning"] : "no";
        /* Whether to add numeric index to the name attribute ( yes incase of fields cloning enabled ) */
        $name_index = $fields_cloning == "yes" ? "_1" : "";
        
        $readonly = isset($_meta["show_as_read_only"]) ? $_meta["show_as_read_only"] : "no";
        $readonly = ($readonly == "yes") ? "disabled" : "";
        $cloneable = isset($_meta["cloneable"]) ? $_meta["cloneable"] : "yes";
        $cloneable = ($cloneable == "yes") ? 'data-cloneable="yes"' : '';
        $show_as_value = isset($_meta["showin_value"]) ? $_meta["showin_value"] : "no";
        $is_private = isset($_meta["login_user_field"]) ? $_meta["login_user_field"] : "no";
        $field_class = isset($_meta["field_class"]) ? $_meta["field_class"] : "";
        /* check is pricing rules is availbe */
        $is_pricing_rule = isset( $_meta["pricing_rules"] ) && isset($wccpf_options["enable_ajax_pricing_rules"]) && $wccpf_options["enable_ajax_pricing_rules"] == "enable" ? "yes" : "no";
        if ($is_private == "yes" && ! is_user_logged_in()) {
            /* Well looks like this field is available only for logged in users */
            return "";
        }
        
        /* Check for roles */
        if ($is_private == "yes" && (isset($_meta["show_for_roles"]) && is_array($_meta["show_for_roles"]) && !empty($_meta["show_for_roles"]))) {
        	$can = false;
        	foreach ($_meta["show_for_roles"] as $role) {
        		if (current_user_can($role)) {
        			$can = true;
        		}
        	}
        	if (!$can) {
        		/* User not have the role */
        		return "";
        	}
        }
        
        /* Identify the field's type and start rendering */
        if ($_meta["type"] == "text" || $_meta["type"] == "email" || $_meta["type"] == "number" || $_meta["type"] == "datepicker" || $_meta["type"] == "colorpicker") {
            $html = $this->build_input_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "textarea") {
            $html = $this->build_textarea_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "radio") {
            $html = $this->build_radio_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "checkbox") {
            $html = $this->build_checkbox_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "select") {
            $html = $this->build_select_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "file") {
            $html = $this->build_file_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper, $is_pricing_rule);
        } else if ($_meta["type"] == "hidden") {
            $html = $this->build_input_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, false);
        } else if ($_meta["type"] == "url") {
            $html = $this->build_url_field($_meta, $_ptype, $field_class, $name_index, $show_as_value, $readonly, $cloneable, $_wrapper);
        } else if ($_meta["type"] == "label") {
            $html = $this->build_label_field($_meta, $_ptype, $field_class, $name_index, $cloneable);
        } else if ($_meta["type"] == "html") {
            $html = $_meta["html"];
        } else {
            /* Unlikely */
            $html = '';
        }
       
        /* Final html tag */
        return $html;
    }
    
    /**
     * 
     * Primary haandler for generating fields, which will be injected into the Product Admin Page, Product Variable Section and Product Category Page.<br>
     * It evoluate the field's meta and delegate the task to various helper methods the get the fields HTML
     * 
     * @param object $_meta
     * @return string|unknown
     * 
     */
    public function build_admin_field($_meta) {
        $html = '';
        
        $field_class = isset($_meta["field_class"]) ? $_meta["field_class"] : "";
        /* Add a special class if only for textarea field */
        $field_class .= ($_meta["type"] == "textarea") ? " short" : "";
        /* Add a special class if only for radio field */
        $field_class .= ($_meta["type"] == "radio") ? " select short" : "";
        
        /* Identify the field's type and start rendering */
        if ($_meta["type"] == "text" || $_meta["type"] == "email" || $_meta["type"] == "number" || $_meta["type"] == "datepicker" || $_meta["type"] == "colorpicker") {
            $html = $this->build_input_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "textarea") {
        	$html = $this->build_textarea_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "radio") {
        	$html = $this->build_radio_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "checkbox") {
        	$html = $this->build_checkbox_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "select") {
        	$html = $this->build_select_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "image") {
            $html = $this->build_image_field($_meta);
        } else if ($_meta["type"] == "url") {
        	$html = $this->build_url_field($_meta, "wccaf", $field_class, "", "no", "", "", true);
        } else if ($_meta["type"] == "html") {
            $html = $_meta["html"];
        } else {
            /* Unlikely */
            $html = '';
        }
        /* Final html tag */
        return $html;
    }
    
    /**
     * 
     * Helper method for generating Input Fields for both Product as well as Admin 
     * 
     * @param object $_meta			     : Field's meta
     * @param string $_ptype			     : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			     : Custom css class for this field
     * @param string $_index			     : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	     : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			 : Whether this field is read only
     * @param string $_cloneable          : Whether this field is cloneable
     * @param string $_wrapper			 : Whether this field has to wrapped up\
     * @param string $_is_pricing_rules	: Whether this field has pricing rules
     * @return string			
     * 
     */
    private function build_input_field($_meta, $_ptype = "wccpf", $_class = "", $_index = "", $_show_as_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rules = "no") {
        $html = '';
        $placeholder = '';
        $value = ($_ptype != "wccaf") ? (isset($_meta["default_value"]) ? esc_attr($_meta["default_value"]) : "") : ((isset($_meta['value'])) ? esc_attr($_meta['value']) : "");        
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        if (isset($_meta["placeholder"]) && (! empty($_meta["placeholder"]))) {
        	if (isset($_meta["required"]) && $_meta["required"] == "yes") {
        		$placeholder = 'placeholder="' . esc_attr($_meta["placeholder"]) . '"';
        	} else {
        		$placeholder = 'placeholder="' . esc_attr($_meta["placeholder"]) . '"';
        	}
        }
        
        $maxlength = (isset($_meta["maxlength"]) && ! empty($_meta["maxlength"])) ? ('maxlength="' . esc_attr($_meta["maxlength"]) . '"') : '';
        $min = (isset($_meta["min"]) && ! empty($_meta["min"])) ? ('min="' . esc_attr($_meta["min"]) . '"') : '';
        $max = (isset($_meta["max"]) && ! empty($_meta["max"])) ? ('max="' . esc_attr($_meta["max"]) . '"') : '';
        $step = (isset($_meta["step"]) && ! empty($_meta["step"])) ? ('step="' . esc_attr($_meta["step"]) . '"') : '';
        /* Some fields doesn't has required config option */
        $_meta["required"] = isset($_meta["required"]) ? $_meta["required"] : "no";
        
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        /* Construct the input field */
        if ($_show_as_value == "no") {
            if ($_meta["type"] == "text") {
                if ($_ptype != "wccaf") {
                    $value = esc_attr($_meta["default_value"]);
                } else {
                    $_meta['placeholder'] = isset($_meta['placeholder']) ? $_meta['placeholder'] : '';
                    $_meta['name'] = isset($_meta['name']) ? $_meta['name'] : $_meta['id'];
                    $_meta['value_type'] = isset($_meta['value_type']) ? $_meta['value_type'] : 'text';
                    $data_type = empty($_meta['data_type']) ? '' : $_meta['data_type'];
                    switch ($data_type) {
                        case 'price':
                            $_class .= ' wc_input_price';
                            $value = wc_format_localized_price($_meta['value']);
                            break;
                        case 'decimal':
                            $_class .= ' wc_input_decimal';
                            $value = wc_format_localized_decimal($_meta['value']);
                            break;
                        case 'stock':
                            $_class .= ' wc_input_stock';
                            $value = wc_stock_amount($_meta['value']);
                            break;
                        case 'url':
                            $_class .= ' wc_input_url';
                            $value = esc_url($_meta['value']);
                            break;
                        default:
                            break;
                    }
                }
                $html = '<input type="text" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" value="' . $value . '" ' . $placeholder . ' ' . $maxlength . ' ' . $_ptype . '-type="text" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' />';
            } else if ($_meta["type"] == "number") {
                $html = '<input type="number" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" value="' . $value . '" ' . $placeholder . ' ' . $min . ' ' . $max . ' ' . $step . ' ' . $_ptype . '-type="number" ' . $_ptype . '-pattern="number" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' />';
            } else if ($_meta["type"] == "email") {
            	$html = '<input type="email" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $placeholder . ' ' . $_ptype . '-type="email" ' . $_ptype . '-pattern="email" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' value="' . $value . '" />';
            } else if ($_meta["type"] == "datepicker") {
                $html = '<input type="text" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" name="' . esc_attr($_meta["name"] . $_index) . '" class="' . $_ptype . '-field ' . $_ptype . '-datepicker ' . $_ptype . '-datepicker-' . esc_attr( isset( $_meta["admin_class"] ) ? $_meta["admin_class"] : "" ) . ' ' . $_class . '" ' . $placeholder . ' value="' . $value . '" ' . (($_meta["readonly"] == "yes") ? "readonly" : "") . ' ' . $_ptype . '-type="text" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' />';
                $html .= $this->initialize_datepicker_field($_meta, $_ptype);
            } else if ($_meta["type"] == "colorpicker") {
            	$defaultcolor = ($value && $value != "") ? $value : "#000";                
            	$html = '<input type="text" name="' . esc_attr($_meta["name"] . $_index) . '" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_ptype . '-color ' . $_class . ' ' . $_ptype . '-color-' . esc_attr(isset( $_meta["admin_class"] ) ? $_meta["admin_class"] : "") . '" value="' . $defaultcolor . '" ' . $_ptype . '-type="text" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' />';
                if ($_ptype == "wccaf") {
                    $html .= $this->initialize_color_picker_field($_meta);
                }
            } else if ($_meta["type"] == "password") {
                $html = '<input type="password" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $placeholder . ' ' . $_ptype . '-type="password" ' . $_ptype . '-pattern="password" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' value="' . $value . '" />';
            } else if ($_meta["type"] == "hidden") {
            	$html = '<input type="hidden" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $_cloneable . ' value="' . (isset($_meta["placeholder"]) ? esc_attr($_meta["placeholder"]) : "") . '" />';
            } else {
                $html = '';
            }
        } else {
            /*
             * Show the raw value instead of as a field
             * Used for the Admin Field showing on product page
             * Its safe to assume that this option is not available for Admin Fields
             */
            if ($_meta["type"] != "colorpicker") {
                $html = '<p class="wcff-wccaf-value-para-tag">' . $_meta["default_value"] . '</p>';
            } else {
                $defaultcolor = isset($_meta["default_value"]) ? $_meta["default_value"] : "#000";
                $html = ($_meta["hex_color_show_in"] == "yes") ? '<span class="wcff-color-picker-color-show" color-code="' . $defaultcolor . '" style="padding: 0px 15px;background-color: ' . $defaultcolor . '"; ></span>' : $defaultcolor;
            }
        }
        
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     * 
     * Helper method for generating Textarea Field for both Product as well as Admin 
     * 
     * @param object $_meta			     : Field's meta
     * @param string $_ptype			     : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			     : Custom css class for this field
     * @param string $_index			 : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	     : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			 : Whether this field is read only
     * @param string $_cloneable          : Whether this field is cloneable
     * @param string $_wrapper			 : Whether this field has to wrapped up
     * @param string $_is_pricing_rules	: Whether this field has pricing rules
     * @return string			
     * 
     */
    private function build_textarea_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_as_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rules = "no") {
        $html = '';
        $rows = (isset($_meta["rows"]) && ! empty($_meta["rows"])) ? ('rows="' . esc_attr(trim($_meta["rows"])) . '"') : '';
        $maxlength = (isset($_meta["maxlength"]) && ! empty($_meta["maxlength"])) ? ('maxlength="' . esc_attr(trim($_meta["maxlength"])) . '"') : '';
        $placeholder = (isset($_meta["placeholder"]) && ! empty($_meta["placeholder"])) ? ('placeholder="' . esc_attr(trim($_meta["placeholder"])) . '"') : '';
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        if ($_show_as_value == "no") {
            $html = '<textarea data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $placeholder . ' ' . $rows . ' ' . $maxlength . ' ' . $_ptype . '-type="textarea" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' >' . (($_ptype != "wccaf") ? esc_html($_meta["default_value"]) : esc_html($_meta['value'])) . '</textarea>';
        } else {
            /*
             * Show the raw value instead of as a field
             * Used for the Admin Field showing on product page
             */
            $html = '<p class="wcff-wccaf-value-para-tag">' . $_meta["default_value"] . '</p>';
        }
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     * 
     * Helper method for generating Radio Buttons Field for both Product as well as Admin 
     * 
     * @param object $_meta			    : Field's meta
     * @param string $_ptype			    : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			    : Custom css class for this field
     * @param string $_index			    : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	    : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			: Whether this field is read only
     * @param string $_cloneable         : Whether this field is cloneable
     * @param string $_wrapper			: Whether this field has to wrapped up
     * @param string $_is_pricing_rules	: Whether this field has pricing rules
     * @return string		
     * 
     */
    private function build_radio_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_as_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rules = "no") {
        $html = '';
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        if ($_show_as_value == "no") {
            /* For admin field, we don't need <ul> wrapper */
            if ($_ptype != "wccaf") {
                $html = '<ul class="' . ((isset($_meta['layout']) && $_meta['layout'] == "horizontal") ? "wccpf-field-layout-horizontal" : "wccpf-field-layout-vertical") . '" ' . $_cloneable . '>';
            }
            $choices = explode(";", ((isset($_meta["choices"]) && ! empty($_meta["choices"])) ? $_meta["choices"] : ""));
            $_meta["default_value"] = (isset($_meta["default_value"]) && ! empty($_meta["default_value"])) ? trim($_meta["default_value"]) : "";
            foreach ($choices as $choice) {
                $attr = '';
                $key_val = explode("|", $choice);
                /* It has to be two items ( Value => Label ), otherwise don't proceed */
                if (count($key_val) == 2) {
                    if ($_ptype != "wccaf") {
                        /* Since version 2.0.0 - Default value will be absolute value not as key|val pair */
                        if (strpos($_meta["default_value"], "|") !== false) {
                            /* Compatibility for <= V 1.4.0 */
                            if ($choice == $_meta["default_value"]) {
                                $attr = 'checked="checked"';
                            }
                        } else {
                            /*
                             * For product fields from V 2.0.0
                             * For admin fields, which will be displyed as Product Fields
                             */
                            if ($key_val[0] == $_meta["default_value"]) {
                                $attr = 'checked="checked"';
                            }
                        }
                    } else {
                        if ($key_val[0] == $_meta["value"]) {
                            $attr = 'checked="checked"';
                        }
                    }
                    /* For admin field, we don't need <li></li> wrapper */
                    $html .= (($_ptype != "wccaf") ? '<li>' : '') . '<label class="wcff-option-wrapper-label"><input type="radio" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'"  class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" value="' . esc_attr(trim($key_val[0])) . '" ' . $attr . ' ' . $_ptype . '-type="radio" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_readonly . ' /> ' . esc_html(trim($key_val[1])) . '</label>' . (($_ptype != "wccaf") ? '</li>' : '');
                }
            }
            /* For admin field, we don't need <ul> wrapper */
            $html .= ($_ptype != "wccaf") ? '</ul>' : '';
        } else {
            /*
             * Show the raw value instead of as a field
             * Used for the Admin Field showing on product page
             */
            $html = '<p class="wcff-wccaf-value-para-tag">' . $_meta["default_value"] . '</p>';
        }
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     *
     * Helper method for generating Checkboxs Field for both Product as well as Admin
     *
     * @param object $_meta			     : Field's meta
     * @param string $_ptype			     : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			     : Custom css class for this field
     * @param string $_index			     : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	     : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			 : Whether this field is read only
     * @param string $_cloneable          : Whether this field is cloneable
     * @param string $_wrapper			 : Whether this field has to wrapped up
     * @param string $_is_pricing_rules	: Whether this field has pricing rules
     * @return string
     *
     */
    private function build_checkbox_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_as_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rules = "no") {
        $html = '';        
        $defaults = array();
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        if ($_show_as_value == "no") {
            /* For admin field, we don't need <ul> wrapper */
            if ($_ptype != "wccaf") {
                $html = '<ul class="' . (($_meta['layout'] == "horizontal") ? "wccpf-field-layout-horizontal" : "wccpf-field-layout-vertical") . '" ' . $_cloneable . '>';
            }
            $choices = explode(";", ((isset($_meta["choices"]) && ! empty($_meta["choices"])) ? $_meta["choices"] : ""));
            if ($_ptype != "wccaf") {
                /* Since version 2.0.0 - Default value will be absolute value not as key|val pair */
                if (is_array($_meta["default_value"])) {
                    $defaults = $_meta["default_value"];
                } else {
                    /* Compatibility mode for <= V 1.4.0 */
                    $temp_opts = ($_meta["default_value"] != "") ? explode(";", $_meta["default_value"]) : array();
                    foreach ($temp_opts as $opts) {
                        $opt = explode("|", $opts);
                        if (count($opt) == 2) {
                            $defaults[] = $opt[0];
                        }
                    }
                }
            } else {
                /* This is going to be always an Array ( Value only Array ) */
                $defaults = $_meta["value"];
            }
            foreach ($choices as $choice) {
                $attr = '';
                $key_val = explode("|", $choice);
                /* It has to be two items ( Value => Label ), otherwise don't proceed */
                if (count($key_val) == 2) {
                	if (in_array(trim($key_val[0]), $defaults)) {
                        $attr = 'checked';
                    }
                    /* For admin field, we don't need <li></li> wrapper */
                    $html .= (($_ptype != "wccaf") ? '<li>' : '') . '<label class="wcff-option-wrapper-label"><input type="checkbox" data-has_field_rules="'.$has_field_rules.'" data-is_pricing_rules="'.$_is_pricing_rules.'" class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '[]" value="' . esc_attr(trim($key_val[0])) . '" ' . $attr . ' ' . $_ptype . '-type="checkbox" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_readonly . ' /> ' . esc_attr(trim($key_val[1])) . '</label>' . (($_ptype != "wccaf") ? '</li>' : '');
                }
            }
            /* For admin field, we don't need <ul> wrapper */
            $html .= ($_ptype != "wccaf") ? '</ul>' : '';
        } else {
            /*
             * Show the raw value instead of as a field
             * Used for the Admin Field showing on product page
             */
            $html = '<p class="wcff-wccaf-value-para-tag">' . implode(", ", $_meta["default_value"]) . '</p>';
        }
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     *
     * Helper method for generating Select Field for both Product as well as Admin
     *
     * @param object $_meta			     : Field's meta
     * @param string $_ptype			     : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			     : Custom css class for this field
     * @param string $_index			     : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	     : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			 : Whether this field is read only
     * @param string $_cloneable          : Whether this field is cloneable
     * @param string $_wrapper			 : Whether this field has to wrapped up
     * @param string $_wrapper			 : Whether this field has to wrapped up
     * @param string $_is_pricing_rules	: Whether this field has pricing rules
     * @return string
     *
     */
    private function build_select_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_as_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rules = "no") {
        $html = '';
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        if ($_show_as_value == "no") {
            $html = '<select data-has_field_rules="'.$has_field_rules.'"  data-is_pricing_rules="'.$_is_pricing_rules.'"  class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $_ptype . '-type="select" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' >';
            
            $choices = explode(";", ((isset($_meta["choices"]) && ! empty($_meta["choices"])) ? $_meta["choices"] : ""));
            $_meta["default_value"] = (isset($_meta["default_value"]) && ! empty($_meta["default_value"])) ? trim($_meta["default_value"]) : "";
            
            /* Placeholder option */
            if (isset($_meta["placeholder"]) && !empty($_meta["placeholder"])) {
            	$html .= '<option value="wccpf_none">' . esc_html($_meta["placeholder"]) . '</option>';
            }
            $choices = apply_filters( "wcff_select_option_before_rendering", $choices, $_meta["name"] );
            foreach ($choices as $choice) {
            	$attr = '';
                $key_val = explode("|", $choice);
                /* It has to be two items ( Value => Label ), otherwise don't proceed */
                if (count($key_val) == 2) {
                    if ($_ptype != "wccaf") {
                        /* Since version 2.0.0 - Default value will be absolute value, not as key|val pair */
                        if (strpos($_meta["default_value"], "|") !== false) {
                            /* Compatibility for <= V 1.4.0 */
                            if ($choice == $_meta["default_value"]) {
                                $attr = 'selected';
                            }
                        } else {
                            /*
                             * For product fields from V 2.0.0
                             * For admin fields, which will be displyed as Product Fields
                             */
                            if (trim($key_val[0]) == $_meta["default_value"]) {
                                $attr = 'selected';
                            }
                        }
                    } else {
                        if ($key_val[0] == $_meta["value"]) {
                            $attr = 'selected';
                        }
                    }
                    $html .= '<option value="' . esc_attr(trim($key_val[0])) . '" ' . $attr . '>' . esc_html(trim($key_val[1])) . '</option>';
                }
            }
            $html .= '</select>';
        } else {
            /*
             * Show the raw value instead of as a field
             * Used for the Admin Field showing on product page
             */
            $html = '<p class="wcff-wccaf-value-para-tag">' . $_meta["default_value"] . '</p>';
        }
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     *
     * Helper method for generating File Input Field for both Product as well as Admin
     *
     * @param object $_meta			     : Field's meta
     * @param string $_ptype			     : Post type ( could be 'wccpf' or 'wccaf' )
     * @param string $_class			     : Custom css class for this field
     * @param string $_index			     : If cloning option enabled then this will the Name Index - which will be suffixed with fields name attribute
     * @param string $_show_as_value	     : If admin wants to display the value instead as field ( Only for Admin Fields )
     * @param string $_readonly			 : Whether this field is read only
     * @param string $_cloneable          : Whether this field is cloneable
     * @param string $_wrapper			 : Whether this field has to wrapped up
     * @return string
     *
     */
    private function build_file_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true, $_is_pricing_rule = "no") {
        /*
         * Show as value option not available for FILE field
         * since file field not supported for Admin Field  */
        
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        $_index .= (isset($_meta["multi_file"]) && $_meta["multi_file"] == "yes") ? "[]" : "";
        $accept = (isset($_meta["filetypes"]) && ! empty($_meta["filetypes"])) ? ('accept="' . esc_attr(trim($_meta["filetypes"])) . '"') : '';
        $multifile = (isset($_meta["multi_file"]) && $_meta["multi_file"] == "yes") ? 'multiple="multiple"' : '';
        $maxsize = (isset($_meta["max_file_size"]) && ! empty($_meta["max_file_size"])) ? ('max-size="' . esc_attr(trim($_meta["max_file_size"])) . '"') : '';
        $preview = (isset($_meta["img_is_prev"]) && $_meta["img_is_prev"] == "yes") ? "yes" : "no"; 
        $preview_width = (isset($_meta["img_is_prev_width"]) && $_meta["img_is_prev_width"] != "") ? $_meta["img_is_prev_width"] : "65px";
        $has_field_rules = isset( $_meta["field_rules"] ) && is_array( $_meta["field_rules"] ) && count( $_meta["field_rules"] ) != 0 ? "yes" : "no";
        /* Construct the field */
        $html = '<input type="file" data-has_field_rules="'.$has_field_rules.'"  data-is_pricing_rules="'.$_is_pricing_rule.'" ' . $maxsize . ' class="' . $_ptype . '-field ' . $_class . '" name="' . esc_attr($_meta["name"] . $_index) . '" ' . $accept . ' ' . $multifile . ' ' . $_ptype . '-type="file" ' . $_ptype . '-pattern="mandatory" ' . $_ptype . '-mandatory="' . $_meta["required"] . '" ' . $_cloneable . ' ' . $_readonly . ' data-preview="'. $preview .'" data-preview-width="'. $preview_width .'" />';
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     *
     * Helper method used to generate Label Widget
     *
     * @param object $_meta
     * @param string $_class
     * @return string
     *
     */
    private function build_label_field($_meta, $_ptype, $_class = "", $_index, $_cloneable = "" ) {
        $_meta["message"] = isset($_meta["message"]) ? $_meta["message"] : "";
        $_meta["message_type"] = isset($_meta["message_type"]) ? $_meta["message_type"] : "info";
        /* If the fields is not cloneable then reset the $_index parameter */
        $_index = ($_cloneable != "") ? $_index : "";
        /* Is init field show or hide */
        $onload_field = (isset($_meta["initial_show"]) && $_meta["initial_show"] == "no" ) ? "display: none;" : "";
        if ($_meta["message"] != "") {
            $html = '<div style="'.$onload_field.'" data-labelfield="'.$_meta["name"].'" class="wcff-label wccpf_fields_table' . $_class . ' wcff-label-' . $_meta["message_type"] . '" '.$_cloneable.'>' . html_entity_decode($_meta["message"]) . '<input type="hidden" name="' . esc_attr($_meta["name"] . $_index) . '"></div>';
            if($_ptype == "wcccf"){
                $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
            }
            return $html;
        }
        return "";
    }
    
    /**
     * 
     * Helper method used to build Image Uploader widget for Admin Fields
     * 
     * @param object $_meta
     * @return string
     * 
     */
    private function build_image_field($_meta) {
        global $content_width, $_wp_additional_image_sizes;
        $html = '';
        $has_image = false;
        $thumbnail_html = "";
        $content_width = 64;
        $old_content_width = $content_width;
        $image_wrapper_class = "wccaf-image-field-wrapper";
        
        $location_class = str_replace(".php", "", $_meta["location"]);
        
        $_meta["upload_btn_label"] = (isset($_meta["upload_btn_label"]) && ! empty($_meta["upload_btn_label"])) ? $_meta["upload_btn_label"] : "Upload";
        $_meta["media_browser_title"] = (isset($_meta["media_browser_title"]) && ! empty($_meta["media_browser_title"])) ? $_meta["media_browser_title"] : "Choose an Image";
        $_meta["upload_probe_text"] = (isset($_meta["upload_probe_text"]) && ! empty($_meta["upload_probe_text"])) ? $_meta["upload_probe_text"] : "You haven't set an image yet";
        
        if (isset($_meta["value"]) && ! empty($_meta["value"])) {
            if (! isset($_wp_additional_image_sizes['thumbnail'])) {
                $thumbnail_html = wp_get_attachment_image($_meta["value"], array(
                    $content_width,
                    $content_width
                ));
            } else {
                $thumbnail_html = wp_get_attachment_image($_meta["value"], 'thumbnail');
            }
            if (! empty($thumbnail_html)) {
                $has_image = true;
                $image_wrapper_class = "wccaf-image-field-wrapper has_image";
            }
            $content_width = $old_content_width;
        }
        
        if ($_meta["location"] != "product_cat_add_form_fields" && $_meta["location"] != "product_cat_edit_form_fields") {
            $html = '<div class="form-field ' . esc_attr($_meta['name']) . "_field " . $image_wrapper_class . ' ' . $location_class . '">';
            $html .= '<label>' . esc_html($_meta['label']) . '</label>';
        } else if ($_meta["location"] == "product_cat_add_form_fields") {
            $html .= '<div class="form-field ' . $location_class . ' ' . esc_attr($_meta['name']) . "_field " . $image_wrapper_class . '">';
            $html .= '<label class="wcff-admin-field-label" for="' . esc_attr($_meta['name']) . '">' . wp_kses_post($_meta['label']) . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label>';
        } else {
            $html .= '<tr class="form-field ' . esc_attr($_meta['name']) . "_field " . $image_wrapper_class . ' ' . $location_class . '">';
            $html .= '<th scope="row" valign="top"><label class="wcff-admin-field-label" for="' . esc_attr($_meta['name']) . '">' . wp_kses_post($_meta['label']) . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label></th>';
            $html .= '<td>';
        }
        
        /* Image preview section start */
        
        if (! empty($thumbnail_html)) {
            $html .= $thumbnail_html;
        } else {
            $html .= '<img src="" style="width:' . esc_attr($content_width) . 'px;height:auto;border:0;display:none;" />';
        }
        $html .= '<a href="#" class="wccaf-image-remove-btn"></a>';
        $html .= '<input type="hidden" id="' . esc_attr($_meta["name"]) . '" name="' . esc_attr($_meta["name"]) . '" value="" />';
        
        /* Image preview section end */
        /* Upload section start */
        
        $html .= '<p class="wccaf-img-field-btn-wrapper" style="display: ' . ($has_image ? "none" : "block") . '">';
        $html .= '<span>' . esc_html($_meta["upload_probe_text"]) . '</span>';
        $html .= '<input type="button" class="button wcff_upload_image_button" data-uploader_title="' . esc_attr($_meta["media_browser_title"]) . '" value="' . esc_attr($_meta["upload_btn_label"]) . '" />';
        $html .= '</p>';
        
        /* Upload section end */
        
        if ($_meta["location"] != "product_cat_add_form_fields" && $_meta["location"] != "product_cat_edit_form_fields") {
            $html .= '</div>';
        } else if ($_meta["location"] == "product_cat_add_form_fields") {
            $html .= '</div>';
        } else {
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    
    /**
     * 
     * Helper method used to generate URL field for both Product as well as Admin
     * 
     * @param object $_meta
     * @param string $_ptype
     * @param string $_class
     * @param object $_index
     * @param string $_show_value
     * @param string $_readonly
     * @param string $_cloneable
     * @param string $_wrapper
     * @return string
     * 
     */
    private function build_url_field($_meta, $_ptype = "wccpf", $_class = "", $_index, $_show_value = "no", $_readonly = "", $_cloneable = "", $_wrapper = true) {
        $html = '';
        if ($_ptype != "wccaf") {
            if (isset($_meta["default_value"]) && $_meta["default_value"] != "") {
                $visual_type = (isset($_meta["view_in"]) && ! empty($_meta["view_in"])) ? $_meta["view_in"] : "link";
                $open_tab = (isset($_meta["tab_open"]) && ! empty($_meta["tab_open"])) ? $_meta["tab_open"] : "_blank";
                if ($visual_type == "link") {
                    /* Admin wants this url to be displayed as LINK */
                    $html = '<a href="' . $_meta["default_value"] . '" class="' . $_class . '" target="' . $open_tab . '" title="' . $_meta["tool_tip"] . '" ' . $_cloneable . ' >' . $_meta["link_name"] . '</a>';
                } else {
                    /* Admin wants this url to be displayed as Button */
                    $html = '<button onclick="window.open(\'' . $_meta["default_value"] . '\', \'' . $open_tab . '\' )"  title="' . $_meta["tool_tip"] . '" class="' . $_class . '" ' . $_cloneable . ' >' . $_meta["link_name"] . '</button>';
                }
            } else {
                /* This means url value is empty so no need render the field */
                $_wrapper = false;
            }
        } else {
            $html .= '<input type="text" name="' . esc_attr($_meta['name']) . '" class="wccaf-field short" id="' . esc_attr($_meta['name']) . '" placeholder="http://example.com" wccaf-type="url" value="' . esc_attr($_meta['value']) . '" wccaf-pattern="mandatory" wccaf-mandatory="">';
        }
        /* Add wrapper around the field, based on the user options */
        if ($_wrapper) {
            $html = $this->built_field_wrapper($html, $_meta, $_ptype, $_index);
        }
        return $html;
    }
    
    /**
     * 
     * Helper method used to generate Wrapper around for both Product as well Admin<br>
     * It also decides the wrapper type, based on the Fields parent post type and Location  
     * 
     * @param object $_html
     * @param object $_meta
     * @param object $_ptype
     * @param object $_index
     * @return string
     * 
     */
    private function built_field_wrapper($_html, $_meta, $_ptype, $_index) {
        $html = '';
        if ($_ptype != "wccaf" && $_ptype != "wcccf" ) {
            /*
             * Add the validation message section
             * URL field doesn't need any validation message
             */
            if ($_meta["type"] != "url") {
            	$_html .= '<span class="wccpf-validation-message">' . (isset($_meta["message"]) ? $_meta["message"] : "") . '</span>';
            }
            /* CHeck for the custom wrapper action registered */
            if (has_action('wccpf_before_field_rendering') && has_action('wccpf_after_field_rendering')) {
                $before = do_filter('wccpf_before_field_rendering', $_meta);
                $after = do_filter('wccpf_after_field_rendering', $_meta);
                $html = $before . $_html . $after;
            } else {
                /* Special property for URL field alone */
                $show_label = isset($_meta["show_label"]) ? $_meta["show_label"] : "yes";
                /* Default field wrapper */
                $wrapper_class = (isset($_meta["field_class"]) && !empty($_meta["field_class"])) ? $_meta["field_class"] : $_meta["name"];
                /* Is init field show or hide */
                $onload_field = (isset($_meta["initial_show"]) && $_meta["initial_show"] == "no" ) ? "display: none;" : "";
                $html = '<table style="'.$onload_field.'" class="wccpf_fields_table ' . apply_filters('wccpf_fields_container_class', '') . ' '. $wrapper_class.'-wrapper">';
                $html .= '<tbody>';
                $html .= '<tr>';
                if ($_meta["type"] != "url" || $show_label == "yes") {
                    $html .= '<td class="wccpf_label"><label for="' . esc_attr($_meta["name"] . $_index) . '">' . esc_html($_meta["label"]) . '' . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label></td>';
                }
                $html .= '<td class="wccpf_value">' . $_html . '</td>';
                $html .= '</tr>';
                $html .= '</tbody>';
                $html .= '</table>';
            }
        } else if( $_ptype == "wcccf" ){
            $requere = isset( $_meta["required"] ) && $_meta["required"]  ? '<abbr class="required" title="required">*</abbr>' : ' <span class="optional">(optional)</span>';
            $label = $_meta["type"] != "label" ? '<label for="'. esc_attr( $_meta["name"] ) .'" class=""> '. esc_attr( $_meta["label"] ) ."&nbsp;" . $requere . '</label>' : "";
            $html .= '<div class="form-row form-row-wide wcff-checkout-field-container address-field" id="'. esc_attr( $_meta["name"] ).'" data-priority="' . esc_attr( $_meta["order"] ) . '">'.$label.$_html.'</div>';
        }else {
            if ($_meta["location"] != "product_cat_add_form_fields" && $_meta["location"] != "product_cat_edit_form_fields") {
                $html .= '<p class="form-field ' . esc_attr($_meta['name']) . '_field ' . $_meta["location"] . '">';
                $html .= '<label class="wcff-admin-field-label" for="' . esc_attr($_meta['name']) . '">' . wp_kses_post($_meta['label']) . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label>';
                /* Insert the actual field here */
                $html .= $_html;
                
                if (! empty($_meta['description'])) {
                    if (isset($_meta['desc_tip']) && "no" != $_meta['desc_tip']) {
                        $html .= '<img class="help_tip" data-tip="' . wp_kses_post($_meta['description']) . '" src="' . esc_url(wcff()->info["dir"]) . '/assets/img/help.png" height="16" width="16" />';
                    } else {
                        $html .= '<span class="description">' . wp_kses_post($_meta['description']) . '</span>';
                    }
                }
                $html .= '<span class="wccaf-validation-message">' . (isset($_meta["message"]) ? $_meta["message"] : "") . '</span>';
                $html .= '</p>';
            } else if ($_meta["location"] == "product_cat_add_form_fields") {
                $html .= '<div class="form-field ' . $_meta["location"] . '">';
                $html .= '<label class="wcff-admin-field-label" for="' . esc_attr($_meta['name']) . '">' . wp_kses_post($_meta['label']) . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label>';
                
                /* Insert the actual field here */
                $html .= $_html;
                
                if (! empty($_meta['description'])) {
                    $html .= '<p class="description">' . wp_kses_post($_meta['description']) . '</p>';
                }
                $html .= '<span class="wccaf-validation-message">' . (isset($_meta["message"]) ? $_meta["message"] : "") . '</span>';
                $html .= '</div>';
            } else {
                $html .= '<tr class="form-field">';
                $html .= '<th scope="row" valign="top"><label class="wcff-admin-field-label" for="' . esc_attr($_meta['name']) . '">' . wp_kses_post($_meta['label']) . ((isset($_meta["required"]) && $_meta["required"] == "yes") ? ' <span>*</span>' : '') . '</label></th>';
                $html .= '<td>';
                
                /* Insert the actual field here */
                $html .= $_html;
                
                if (! empty($_meta['description'])) {
                    $html .= '<p class="description">' . wp_kses_post($_meta['description']) . '</p>';
                }
                $html .= '<span class="wccaf-validation-message">' . (isset($_meta["message"]) ? $_meta["message"] : "") . '</span>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        return $html;
    }
   
    /**
     * 
     * Convert php dateformat into jQuery UI Date Picker compatible format
     * Taken from : https://stackoverflow.com/questions/16702398/convert-a-php-date-format-to-a-jqueryui-datepicker-date-format
     * 
     * @author Tristan Jahier
     * @param string $_php_format
     * @return string|unknown
     */
    private function convert_php_jquery_datepicker_format($_php_format) {
        $SYMBOLS = array(
            // Day
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'yy',
            'y' => 'y',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => ''
        );
        $jqueryui_format = "";
        $escaping = false;
        for($i = 0; $i < strlen($_php_format); $i++) {
            $char = $_php_format[$i];
            if($char === '\\') {
                $i++;
                if($escaping) { 
                    $jqueryui_format .= $_php_format[$i];
                } else { 
                    $jqueryui_format .= '\'' . $_php_format[$i];
                }
                $escaping = true;
            } else {
                if($escaping) { 
                    $jqueryui_format .= "'"; 
                    $escaping = false; 
                }
                if(isset($SYMBOLS[$char])) {
                    $jqueryui_format .= $SYMBOLS[$char];
                } else {
                    $jqueryui_format .= $char;
                }
            }
        }
        return $jqueryui_format;
    }
    
    /**
     * 
     * Datepicker initializer script for both Product as well as Admin Fields
     * 
     * @param object $_field
     * @param string $_post_type
     * @return string
     * 
     */
    private function initialize_datepicker_field($_field, $_post_type) {
        $localize = "none";
        $year_range = "-10:+10";  
        
        if ( isset( $_field["language"] ) && !empty( $_field["language"] ) && $_field["language"] != "default") {        	
        	$localize = esc_attr($_field["language"]);        	
        }       
        if (isset($_field["dropdown_year_range"]) && !empty($_field["dropdown_year_range"])) {
        	$year_range = esc_attr($_field["dropdown_year_range"]);
        }     
        
        /* Determine the current locale */
        $current_locale = wcff()->locale->detrmine_current_locale();
        /*If admin hadn't set locale, then try to determine */
        $localize = ($localize == "none") ? $current_locale : $localize;
        
        ob_start(); ?>
    	
		<script type="text/javascript">		
		(function($) {
			jQuery(document).ready(function() {
			<?php			
			if ($localize != "none" && $localize != "en") { ?>
				/* Datepicker User configured localization */	
				if( typeof jQuery != "undefined" && typeof jQuery.datepicker != "undefined" ){					
    				var options = jQuery.extend({}, jQuery.datepicker.regional["<?php echo $localize; ?>"]);
    				$.datepicker.setDefaults(options);
				}
			<?php 
			} else { ?>
				/* Datepicker default configuration */	
				if( typeof jQuery != "undefined" && typeof jQuery.datepicker != "undefined" ){								
    				var options = jQuery.extend({}, jQuery.datepicker.regional["en-GB"]);
    				$.datepicker.setDefaults(options);
				}
			<?php 
			}				
			?>
			
			jQuery("body").on("focus", ".<?php echo $_post_type; ?>-datepicker-<?php echo esc_attr($_field["admin_class"]); ?>", function() {
					var $ = jQuery;
				<?php if (isset($_field["timepicker"]) && $_field["timepicker"] == "yes") : ?>
				jQuery(this).datetimepicker({
						controlType: 'select',
						oneLine: true,
						timeFormat: 'hh:mm tt',
					<?php 
					if (isset($_field["min_max_hours_minutes"]) && !empty($_field["min_max_hours_minutes"])) {
						$hour_minute = explode("|", $_field["min_max_hours_minutes"]);
						if (is_array($hour_minute) && count($hour_minute) == 2) {
							if ($hour_minute[0] != "") {
								$min_max_hours = explode(":", $hour_minute[0]);
								if (is_array($min_max_hours) && count($min_max_hours) == 2) { ?>

								hourMin: <?php echo trim($min_max_hours[0]); ?>,
								hourMax: <?php echo trim($min_max_hours[1]); ?>,
								
								<?php
								$min_max_minutes = explode(":", $hour_minute[1]);
									if (is_array($min_max_minutes) && count($min_max_minutes) == 2) { ?>

									minuteMin: <?php echo trim($min_max_minutes[0]); ?>,
									minuteMax: <?php echo trim($min_max_minutes[1]); ?>,
											
									<?php 
									}								
								}								
							}							
						}
					}						
					?>						
				<?php else : ?>
				jQuery(this).datepicker({
				<?php endif; ?>											
				<?php			
				    if (isset($_field["date_format"]) && $_field["date_format"] != "") {
				    	echo "dateFormat:'". $this->convert_php_jquery_datepicker_format(esc_attr($_field["date_format"])) ."'";
					} else {
					    echo "dateFormat:'". $this->convert_php_jquery_datepicker_format("d-m-Y") ."'";
					}	
						
					if (isset($_field["display_in_dropdown"]) && !empty($_field["display_in_dropdown"])) {
						if ($_field["display_in_dropdown"] == "yes") {
							echo ",changeMonth: true";
							echo ",changeYear: true";
							echo ",yearRange:'". $year_range ."'";
						}
					}
					
					if (isset($_field["disable_date"]) && !empty($_field["disable_date"]) ) {
						if ("future" == $_field["disable_date"]) {
							echo ",maxDate: 0";
						}
						if ("past" == $_field["disable_date"]) {
							echo ",minDate: new Date()";
						}	
					}
					if (isset($_field["disable_next_x_day"]) && strlen($_field["disable_next_x_day"]) > 0){
					   echo ",minDate: '+".$_field["disable_next_x_day"]."d'";
					}
					if (isset($_field["allow_next_x_years"]) && !empty($_field["allow_next_x_years"]) ||
						isset($_field["allow_next_x_months"]) && !empty($_field["allow_next_x_months"]) ||
						isset($_field["allow_next_x_weeks"]) && !empty($_field["allow_next_x_weeks"]) ||
						isset($_field["allow_next_x_days"]) && !empty($_field["allow_next_x_days"]) ) {
						$allowed_dates = "";
						if (isset($_field["allow_next_x_years"]) && !empty($_field["allow_next_x_years"]) && is_numeric($_field["allow_next_x_years"])) {
							$allowed_dates .= "+". trim($_field["allow_next_x_years"]) ."y ";
						}
						if (isset($_field["allow_next_x_months"]) && !empty($_field["allow_next_x_months"]) && is_numeric($_field["allow_next_x_months"])) {
							$allowed_dates .= "+". trim($_field["allow_next_x_months"]) ."m ";
						}
						if (isset($_field["allow_next_x_weeks"]) && !empty($_field["allow_next_x_weeks"]) && is_numeric($_field["allow_next_x_weeks"])) {
							$allowed_dates .= "+". trim($_field["allow_next_x_weeks"]) ."w ";
						}
						if (isset($_field["allow_next_x_days"]) && !empty($_field["allow_next_x_days"]) && is_numeric($_field["allow_next_x_days"])) {
							$allowed_dates .= "+". trim($_field["allow_next_x_days"]) ."d";
						}
						echo ",minDate: 0";
						echo ",maxDate: \"". trim($allowed_dates) ."\"";
					}
					/* Hooks up a call back for 'beforeShowDay' */
					echo ",beforeShowDay: disableDates";		
				?>					
						,onSelect: function( dateText ) {	
							$( this ).trigger( "change" );						
						    $( this ).next().hide();
						}								 
					});
				});		

				function disableDates( date ) {	
					<?php if (is_array($_field["disable_days"]) && count($_field["disable_days"]) > 0) { ?>
							 var disableDays = <?php echo json_encode($_field["disable_days"]); ?>;
							 var day 	= date.getDay();
							 for (var i = 0; i < disableDays.length; i++) {
									 var test = disableDays[i]
								 		 test = test == "sunday" ? 0 : test == "monday" ? 1 : test == "tuesday" ? 2 : test == "wednesday" ? 3 : test == "thursday" ? 4 : test == "friday" ? 5 : test == "saturday" ? 6 : "";
							        if ( day == test ) {									        
							            return [false];
							        }
							 }						
					<?php } ?>	
					<?php if (isset($_field["specific_date_all_months"]) && !empty($_field["specific_date_all_months"])){ ?>
					 		var disableDateAll = <?php echo '"'.$_field["specific_date_all_months"].'"'; ?>;
					 			disableDateAll = disableDateAll.split(",");
					 		for (var i = 0; i < disableDateAll.length; i++) {
								if (parseInt(disableDateAll[i].trim()) == date.getDate()){
									return [false];
								}					
					 		}
					<?php } ?>						
					<?php if (isset($_field["specific_dates"]) && !empty($_field["specific_dates"])) { ?>
								var disableDates = <?php echo "'".$_field["specific_dates"]."'"; ?>;
									disableDates = disableDates.split(",");
									/* Sanitize the dates */
									for (var i = 0; i < disableDates.length; i++) {	
										disableDates[i] = disableDates[i].trim();
									}		
									/* Form the date string to compare */							
								var m = date.getMonth(),
									d = date.getDate(),
									y = date.getFullYear(),
									currentdate = ( m + 1 ) + '-' + d + '-' + y ;
								/* Make dicision */								
								if ( jQuery.inArray( currentdate, disableDates ) != -1 ) {
									return [false];
								}
								
					<?php } ?>	
					<?php if (isset($_field["disable_next_x_day"]) && strlen($_field["disable_next_x_day"]) > 0) {} ?>					
					<?php if (isset($_field["weekend_weekdays"]) && !empty($_field["display_in_dropdown"])) { ?>
							<?php if ($_field["weekend_weekdays"] == "weekdays"){ ?>
								//weekdays disable callback
								var weekenddate = jQuery.datepicker.noWeekends(date);
								var disableweek = [!weekenddate[0]]; 
								return disableweek;
							<?php } else if ($_field["weekend_weekdays"] == "weekends") { ?>
								//weekend disable callback
								var weekenddate = jQuery.datepicker.noWeekends(date);
								return weekenddate; 
							<?php } ?>							
					<?php }  ?>						
					return [true];
				}
							
			});
		})(jQuery);
		</script>
		
		<?php
		return ob_get_clean();
	}
	
	/**
	 * 
	 * Initializer script for colorpicker<br>
	 * This is applicable only for the Admin Field<br>
	 * 
	 * @param object $_field
	 * @return string
	 * 
	 */
	private function initialize_color_picker_field($_field) {
	    ob_start();  ?>
	    
	    <script type="text/javascript">
	    var $ = jQuery;
	    (function($) {
	    	jQuery( document ).ready(function() {        	        
        	        <?php
        	        $palettes = null;
        	        $colorformat = isset($_field["color_format"]) ? $_field["color_format"] : "hex";
        	        if (isset($_field["palettes"]) && $_field["palettes"] != "") {
        	            $palettes = explode(";", $_field["palettes"]);
        	        } ?>
        												
        	        jQuery( ".wccaf-color-<?php echo esc_attr( $_field["admin_class"] ); ?>").spectrum({
        				 preferredFormat: "<?php echo $colorformat; ?>",					
        				<?php 
        				if ($_field["show_palette_only"] != "yes" && isset( $_field["color_text_field"] ) && $_field["color_text_field"] == "yes"){
        				    echo "showInput: true,";
        				}
        				$comma = "";
        				$indexX = 0;
        				$indexY = 0;
        				if (is_array($palettes) && count($palettes) > 0) {
        				    if ($_field["show_palette_only"] == "yes") {
        						echo "showPaletteOnly: true,";
        				    }
        					echo "showPalette: true,";
        					echo "palette : [";						
        					foreach ($palettes as $palette) {		
        						$indexX = 0;								
        						$comma = ($indexY == 0) ? "" : ",";
        						echo $comma."[";
        						$colors = explode(",", $palette);
        					 	foreach ($colors as $color) {							 		
        					 		$comma = ($indexX == 0) ? "" : ","; 
        					 		echo $comma ."'". $color ."'";	
        					 		$indexX++;
        						}
        						echo "]";
        						$indexY++;
        					} 
        					echo "]";						
        				}
        				?>
        			});				
        				
        		});
	    })(jQuery);
		</script>
		
		<?php
		return ob_get_clean();
	}
}

?>