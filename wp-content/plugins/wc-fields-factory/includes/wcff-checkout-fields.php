<?php 
    /*
     * Added from version 3.0.0
     * For Cutomize, add, disable checkout fields
     */

    class Wcff_CheckoutFields {
        private $billing_fields = array();
        private $shipping_fields = array();
        private $checkout_fields = array( "shipping-fields", "billing-fields", "custom-fields" );
        private $is_datepicker_there = false;
        private $is_colorpicker_there = false;
        private $defaults = array( "Postcode / ZIP", "State / County", "Town / City", "Street address", "Country", "Company name", "Last name", "First name" );
        
        function __construct(){
            if( is_admin() ){
                add_action( 'admin_init', array( $this, "wcff_predefined_posts_for_checkout" ) );
                add_action( 'edit_form_after_editor', array( $this, "wcff_checkout_meta_view" ), 99, 1 );
                add_filter( 'before_render_common_meta', array( $this, "wcccf_filter_field_meta" ), 9, 3 );
                ////
                
                add_filter( 'woocommerce_admin_billing_fields', array( $this, "wcccf_add_custom_fields_into_billing" ), 20, 1 );
                add_filter( 'woocommerce_admin_shipping_fields', array( $this, "wcccf_add_custom_fields_into_shipping" ), 20, 1 );
                // remove file field from chgeckout field
                add_filter( 'wccpf/fields/factory/supported/fields', array( $this, "remove_file_field_from_wcccf" ), 10, 1 );
                // Custom fields data to show on admin
                add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, "custom_fields_data_to_show_admin" ), 10, 1 );
                
            } else {
                
                add_filter( 'woocommerce_checkout_fields', array( $this, "wcccf_filter_checkout_fields" ), 9, 1 );
                
                add_filter( 'woocommerce_form_field_args', array( $this, "wcccf_checkout_form_field" ), 9, 3 );
                
                /* Custom field show on checkout page */
                add_action( "woocommerce_checkout_shipping", array( $this, "wcccf_custom_checkout_fields" ), 99 );
                /* Custom Field Validation */
                add_action( "woocommerce_after_checkout_validation", array( $this, "wcccf_custom_checkout_fields_validation" ), 99, 2 );
                /* Custom Field value show to user */
                add_action( "woocommerce_order_details_after_customer_details", array(  $this, "custom_fields_data_to_show_client" ), 9, 1 );
                /////
               add_filter( 'woocommerce_form_field_checkbox', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_datepicker', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_colorpicker', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_radio', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_email', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_label', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_number', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_hidden', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               add_filter( 'woocommerce_form_field_select', array( $this, "wcccf_field_render_on_checkout" ), 9, 4 );
               // init load color and date picker script and css
              // add_action( "woocommerce_after_checkout_form", array( $this, "enqueue_client_side_assets" ) );
               // Save to Order Meta
               add_action( 'woocommerce_checkout_update_order_meta', array( $this, "wcccf_save_order_address" ), 9, 1 );
               
               //Show to admin checkout values
               add_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'wcccf_show_billing_details' ), 10, 2 );
               add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'wcccf_show_shipping_details' ), 10, 2 );
               
               
               add_filter( 'woocommerce_formatted_address_replacements', array( $this, 'wcccf_retirive_formatted_address' ), 10, 2 );
               
            }
        }
        
        // remove file field from checkout field
        public function remove_file_field_from_wcccf( $_field_list ){
            global $post;
            if ( $post->post_type == "wcccf" ){
                foreach( $_field_list as $index => $field ){
                    if( $field["id"] == "file" ){
                        unset($_field_list[$index]);
                    }
                }
                $_field_list = array_values($_field_list);
            }
            return $_field_list;
        }
        
        // To add billing and shipping woocomerce fields
        public function wcff_predefined_posts_for_checkout(){
            $checkout_args = array(
                'post_type' => 'wcccf',
                'name' =>'billing-fields'
            );
            $all_post = get_posts( $checkout_args );
            $check_empty = empty( $all_post );
            if( $check_empty ){
                for( $i = 0; $i < count($this->checkout_fields); $i++ ){
                   $id = wp_insert_post(array (
                        'post_type' => 'wcccf',
                        'post_title' =>  $this->checkout_fields[$i],
                        'post_status' => 'publish',
                        'comment_status' => 'closed', 
                        'ping_status' => 'closed', 
                    ));
                   if( $this->checkout_fields[$i] == "shipping-fields" || $this->checkout_fields[$i] == "billing-fields" ){
                       $metas = $this->checkout_fields[$i] == "shipping-fields" ? $this->shipping_fields() : $this->billing_fields();
                       foreach( $metas as $m_key => $meta ){
                           $field_meta = json_decode( '{"type":"","label":"", "is_unremovable" : true, "is_enable" : true, "name":"","order":"0","placeholder":"","default_value":"","maxlength":"", "required":"no","message":"","visibility":"yes","order_meta":"yes","login_user_field":"no","show_for_roles":[],"cart_editable":"no","cloneable":"no","field_class":"","initial_show":"yes","locale":[],"key":"", "use_this_label" : false}', true );
                           $field_meta["name"] = $m_key;
                           $field_meta["key"]  = "wcccf_". $m_key;
                           $field_meta["type"]  = isset( $meta["type"] ) && ( $meta["type"] == "country" || $meta["type"] == "state" ) ? "select" : "text";
                           $field_meta["order"] = $meta["priority"];
                           $field_meta["priority"] = isset( $meta["order"] ) ? $meta["order"] : 110;
                           $field_meta["label"] =  isset( $meta["label"] ) ? $meta["label"] : "Address 2 (Blank)";
                           $field_meta["placeholder"] = isset( $meta["placeholder"] ) ? $meta["placeholder"] : "";
                           $field_meta["required"] = isset( $meta["required"] ) && $meta["required"] ? "yes" : "no";
                           $field_meta["use_this_label"] = isset( $meta["use_this_label"] ) && $meta["use_this_label"] ? true : false;
                           if ( isset( $meta["type"] ) && $meta["type"] == "country" ){
                               //$field_meta["choices"] = $this->get_select_option( $this->woo_countries() );
                               $field_meta["choices"] = "Don't modify option|Don't modify option";
                           } else if( isset( $meta["type"] ) && $meta["type"] == "state" ){
                               $field_meta["choices"] = "Don't modify option|Don't modify option";
                           }
                           add_post_meta($id, $field_meta["key"], wp_slash( json_encode( $field_meta ) ) );
                       }
                   }
                }
            }
            if( isset( $_REQUEST["post_type"] ) && $_REQUEST["post_type"] == "wcccf" ){
                $url = get_edit_post_link( get_posts( $checkout_args )[0]->ID );
                echo '<script>window.location = "'.urldecode ( $url ).'".replace(/&amp;/g, "&");</script>';
            }
        }
        
        private function get_select_option( $op_obj ){
            $select_choice = "";
            foreach ( $op_obj as $key => $val ) {
                $select_choice .= $key."|".$val."\n";
            }
            return $select_choice;
        }
        
        public function wcff_checkout_meta_view( $post ){
            if( $post->post_type == "wcccf" ):
            $billing_url  = get_edit_post_link( get_posts( array( 'post_type' => 'wcccf', 'name' =>'billing-fields' ) )[0]->ID );
            $shipping_url = get_edit_post_link( get_posts( array( 'post_type' => 'wcccf', 'name' =>'shipping-fields' ) )[0]->ID );
            $custom_url   = get_edit_post_link( get_posts( array( 'post_type' => 'wcccf', 'name' =>'custom-fields' ) )[0]->ID );
            ?>            
        	<ul id="wcccf-config-tab-header" class="wcccf-config-tab-header">
        		<li><a href="<?php echo  $billing_url; ?>" title="Billing Fiedls" class="<?php echo $this->active_page( "billing-fields" ) ? "selected" : "" ; ?>">Billing Fields</a></li>
        		<li><a href="<?php echo $shipping_url; ?>" title="Shipping Fields" class="<?php echo $this->active_page( "shipping-fields" ) ? "selected" : "" ; ?>">Shipping Fields</a></li>
        		<li><a href="<?php echo $custom_url; ?>" title="Other Fields" class="<?php echo $this->active_page( "custom-fields" ) ? "selected" : "" ; ?>">Other Fields</a></li>
        	</ul>
			<?php
			endif;
        }
        
        private function active_page( $_post_name ){
            global $post;
            return $post->post_name == $_post_name;
        }
        
        public function billing_fields(){
            return  WC()->countries->get_address_fields(
                'billing_country',
                'billing_'
                );
        }
        
        public function shipping_fields(){
            return WC()->countries->get_address_fields(
                'shipping_country',
                'shipping_'
                );
        }
        
        private function woo_countries(){
           return WC()->countries->get_countries();
        }
        
        private function woo_states(){
            return WC()->countries->get_states();
        }
        
        private function get_fields_meta( $_type ){
            $res = get_post_meta( get_posts( array( 'post_type' => 'wcccf', 'name' => $_type ) )[0]->ID );
            $return = !empty( $res ) && is_array( $res ) ? $res : array();
            return $return;
        }
        
        public function wcccf_filter_checkout_fields( $fields ){
            $billing_metas  = $this->get_fields_meta( 'billing-fields' );
            $shipping_metas = $this->get_fields_meta( 'shipping-fields' );
            foreach( $fields as $fypes => $field_meta ){
                if( $fypes == "billing" ){
                    // Remodify user defined billing field
                    foreach( $field_meta as $key => $meta ){
                        if( isset( $billing_metas["wcccf_".$key] ) ){
                            $billing_meta = json_decode( $billing_metas["wcccf_".$key][0], true );
                            if( $billing_meta["is_enable"] && $this->check_login_user( $billing_meta ) ){
                                $fields[$fypes][$key] = $this->wcccf_checkout_modify( $fields[$fypes][$key], $billing_meta );
                            } else {
                                unset($fields[$fypes][$key]);
                            }
                        }
                    }
                    // Add meta for extra billing fields
                    foreach( $billing_metas as $key => $val ){
                        $billing_meta = json_decode( $billing_metas[$key][0], true );
                        if( !isset( $fields[$fypes][$billing_meta["name"]] ) && is_array( $billing_meta ) && !$billing_meta["is_unremovable"] ){
                           $billing_meta["name"] = "wcccf_billing_".$billing_meta["name"];
                           $fields[$fypes][$billing_meta["name"]] = $this->wcccf_checkout_modify( null, $billing_meta );
                        }
                    }
                } else if( $fypes == "shipping" ) {
                    // Remodify user defined shipping field
                    foreach( $field_meta as $key => $meta ){
                        $shipping_meta = json_decode( $shipping_metas["wcccf_".$key][0], true );
                        if( $shipping_meta["is_enable"] && $this->check_login_user( $shipping_meta ) ){
                            $fields[$fypes][$key] = $this->wcccf_checkout_modify( $fields[$fypes][$key], $shipping_meta );
                        } else {
                            unset($fields[$fypes][$key]);
                        }
                    }
                    // Add meta for extra shipping fields
                    foreach( $shipping_metas as $key => $val ){
                        $shipping_meta = json_decode( $shipping_metas[$key][0], true );
                        if( !isset( $fields[$fypes][$shipping_meta["name"]] )  && is_array( $shipping_meta ) && !$shipping_meta["is_unremovable"] ){
                            $shipping_meta["name"] = "wcccf_shipping_".$shipping_meta["name"];
                            $fields[$fypes][$shipping_meta["name"]] = $this->wcccf_checkout_modify( null, $shipping_meta );
                        }
                    }
                }
            }
            return $fields;
        }
        
        private function wcccf_checkout_modify( $_orginal, $_modify ){
            $returnArr = array();
            if( !isset( $_orginal ) || $_orginal == null ){
               $returnArr = $_modify;
               $returnArr["required"] = isset( $_modify["required"] ) && $_modify["required"] == "yes" ? true : false;
            } else {
                $returnArr = $_orginal;
                $returnArr["priority"]    = $_modify["order"];
                $returnArr["placeholder"] = isset( $_modify["placeholder"] ) ? $_modify["placeholder"] : "";
                if( !( isset( $returnArr["type"] ) && $returnArr["type"] == "state" ) ){
                    $returnArr["required"]    = isset( $_modify["required"] ) && $_modify["required"] == "yes" ? true : false;
                }
                /* temprory removed for user review */
                //$returnArr["label"]    = $_modify["label"];
                
                $check_not_empty = isset( $_modify["default_value"] ) && !empty( $_modify["default_value"] );
                if( $check_not_empty ){
                    $returnArr["default"]  = $_modify["default_value"];
                }
            }
            $returnArr["cloneable"] = "no";
            return $returnArr;
        }
      
        public function wcccf_filter_field_meta( $_metas, $_ftype, $field ){
            $remove_items = array( "cloneable", "order_meta", "cart_editable", "initial_show", "field_class" );
            if( $_ftype == "wcccf" ){
                foreach( $_metas as $key => $meta ){
                    if( in_array( $meta["param"], $remove_items ) ){
                        unset($_metas[$key]);
                    }
                    if( $meta["param"] == "visibility" ){
                        $_metas[$key]["label"] = __('Show To User', 'wc-fields-factory');
                        $_metas[$key]["desc"] = __('Show Field data to user on order details.', 'wc-fields-factory');
                        $_metas[$key]["layout"] = "horizontal";
                        $_metas[$key]["options"] = array(
                            array(
                                "value" => "yes",
                                "label" => __('Yes', 'wc-fields-factory'),
                                "selected" => true
                            ),
                            array(
                                "value" => "no",
                                "label" => __('No', 'wc-fields-factory'),
                                "selected" => false
                            )
                        );
                    }
                }
            }
            $_metas = array_values($_metas);
            return $_metas;
        }
        
        private function check_login_user( $_meta ){
            if( isset( $_meta["login_user_field"] ) && $_meta["login_user_field"] == "yes" && !is_user_logged_in() ){
                return false;
            }
            $flg = (isset($_meta["show_for_roles"]) && is_array($_meta["show_for_roles"]) && !empty($_meta["show_for_roles"]));
            if ( $flg ) {
                $can = false;
                foreach ($_meta["show_for_roles"] as $role) {
                    if (current_user_can($role) && !$can ) {
                        $can = true;
                    }
                }
                if (!$can) {
                    /* User not have the role */
                    return false;
                }
            } 
            return true;
        }
        
        // render Checkout address fields
        public function wcccf_field_render_on_checkout($field, $key, $args, $value){
            if( strpos($key, 'wcccf_') !== false ){
                if( $args["type"] == "checkbox" ||
                    $args["type"] == "datepicker" || 
                    $args["type"] == "colorpicker" || 
                    $args["type"] == "label" ||
                    $args["type"] == "email" ||
                    $args["type"] == "radio" ||
                    $args["type"] == "select" ||
                    $args["type"] == "number" ||
                    $args["type"] == "hidden"){
                    if($args["type"] =="datepicker"){
                        $this->is_datepicker_there = true;
                    } else if($args["type"] =="colorpicker"){
                        $this->is_colorpicker_there = true;
                    }
                    
                    
                    if( $this->check_login_user( $args ) ){
                        $field = wcff()->builder->build_user_field( $args, "wcccf", true );
                    } else {
                        $field = "";
                    }
                }
            }
            return $field;
        }
        
        public function wcccf_checkout_form_field($args, $key, $value){
            if( $args["type"] == "colorpicker" || $args["type"] == "datepicker" ){
                $args["admin_class"] = $args["name"];
            } else if( $args["type"] == "text" ){
                $args["label"]       = $args["label"] == "Address 2 (Blank)" ? "" : $args["label"];
            }
            return $args;
        }
        
        public function enqueue_client_side_assets($isdate_css = false) { ?>
    		<?php if ( is_checkout() ) : ?>
    			<?php if($this->is_datepicker_there) : ?>
    				<link id="wcff-jquery-ui-style" rel="stylesheet" type="text/css" href="<?php echo wcff()->info['dir']; ?>assets/css/jquery-ui.css">
    				<link id="wcff-timepicker-style" rel="stylesheet" type="text/css" href="<?php echo wcff()->info['dir']; ?>assets/css/jquery-ui-timepicker-addon.css">
    				<?php if(!wp_script_is("jquery-ui-core")) : ?>
    					<?php 
    						$jquery_ui_core = includes_url() . "/js/jquery/ui/core.min.js";									
    						if(!file_exists(ABSPATH ."wp-includes/js/jquery/ui/core.min.js")) {
    							$jquery_ui_core = includes_url() . "/js/jquery/ui/jquery.ui.core.min.js";											
    						}
    					?>
    					<script type="text/javascript" src="<?php echo $jquery_ui_core; ?>"></script>
    				<?php endif; ?>
    			
    				<?php if(!wp_script_is("jquery-ui-datepicker")) : ?>
    					<?php 
    						$jquery_ui_datepicker = includes_url() . "/js/jquery/ui/datepicker.min.js";	
    						if(!file_exists(ABSPATH ."wp-includes/js/jquery/ui/core.min.js")) {
    							$jquery_ui_datepicker = includes_url() . "/js/jquery/ui/jquery.ui.datepicker.min.js";	
    						}
    					?>
    					<script id="wcff-datepicker-script" type="text/javascript" src="<?php echo $jquery_ui_datepicker; ?>"></script>					
    				<?php endif; ?>		
    			
    				<script type="text/javascript" src="<?php echo wcff()->info['dir']; ?>assets/js/jquery-ui-i18n.min.js"></script>
    				<script type="text/javascript" src="<?php echo wcff()->info['dir']; ?>assets/js/jquery-ui-timepicker-addon.min.js"></script>
    			
    			<?php endif; ?>
    			
    			<?php if($this->is_colorpicker_there) : ?>			
    				<link id="wcff-colorpicker-style" rel="stylesheet" type="text/css" href="<?php echo wcff()->info['dir']; ?>assets/css/spectrum.css">
    				<script id="wcff-colorpicker-script" type="text/javascript" src="<?php echo wcff()->info['dir']; ?>assets/js/spectrum.js"></script>	
    						
    			<?php 
    			
    				echo $this->enqueue_color_picker_script();
    				endif; ?>
    		
    		<link rel="stylesheet" type="text/css" href="<?php echo wcff()->info['dir']; ?>assets/css/wcff-client.css">
    		<script type="text/javascript" src="<?php echo wcff()->info['dir']; ?>assets/js/wcff-client.js"></script>
    		<?php endif; ?>
    		<?php 
	   }
	
        
       public function wcccf_save_order_address( $_ord_id ){
            $all_fields["billing"]  = $this->get_fields_meta( 'billing-fields' );
            $all_fields["shipping"] = $this->get_fields_meta( 'shipping-fields' );
            $all_fields["custom"] = $this->get_fields_meta( 'custom-fields' );
            foreach( $all_fields as $groupkey => $gourpval ){
                $checkout_fields = array();
                foreach( $gourpval as $name => $val ){
                    $checkout_fields[$name] = json_decode( $val[0], true );
                }
                foreach ($checkout_fields as $key => $field) {
                    if( isset( $_REQUEST['wcccf_' . $groupkey . '_' .esc_attr($field["name"]) ] ) ){
                        $vals = "";
                        $value = $_REQUEST['wcccf_' . $groupkey . '_' .esc_attr($field["name"]) ];
                        if( $field["type"] == "checkbox" ){
                            $vals = (is_array($value) ? implode(", ", $value) : esc_html(stripslashes($value)));
                        } else {
                            $vals = $value;
                        }
                        update_post_meta( $_ord_id, '_' . $groupkey . "_" .esc_attr($field["name"]), $vals );
                    }
                }
            }
        }
        
        public function wcccf_show_billing_details($details, $_context){
            $_b_fields = $this->add_into_address_client( $details, $_context, "billing" );
            return $_b_fields;
        }
        
        
        public function wcccf_show_shipping_details($details, $_context){
            $_s_fields = $this->add_into_address_client( $details, $_context, "shipping" );
            return $_s_fields;
        }
        
        
        public function wcccf_add_custom_fields_into_billing( $_b_fields ){
            $_b_fields = $this->add_into_address_admin( "billing", $_b_fields );
            return $_b_fields;
        }
        
        public function wcccf_add_custom_fields_into_shipping( $_s_fields ){
            $_s_fields = $this->add_into_address_admin( "shipping", $_s_fields );
            return $_s_fields;
        }
        
        public function custom_fields_data_to_show_admin( $_order ){
           $list_of_custom_data = $this->add_into_address_admin( "custom", array(), $_order->get_id() );
           $html = '<div class="wcff-checkout-custom-fields">';
           if( is_admin() ){
               $html .= '<h3>Custom Fields Data :</h3>';
           }
           foreach( $list_of_custom_data as $key => $val ){
               $html .= '<p><strong>'.$val["label"].': </strong> '.$val["value"].'</p>';
           }
           $html .= '</div>';
           $empty_check = !empty( $list_of_custom_data );
           if( $empty_check ){
               echo $html;
           }
        }
        
        public function custom_fields_data_to_show_client( $_order ){
            $list_of_custom_data = $this->add_into_address_client( array(), $_order, "custom", "custom" );
            $html = '<div class="wcff-checkout-custom-fields">';
            foreach( $list_of_custom_data as $key => $val ){
                $html .= '<p><strong>'.$val["label"].': </strong> '.$val["value"].'</p>';
            }
            $html .= '</div>';
            $check_empty = !empty( $list_of_custom_data );
            if( $check_empty ){
                echo $html;
            }
        }
        
        private function add_into_address_client( $details, $_context, $_type, $_check = "address" ){
            $selected_fields  = $this->get_fields_meta( $_type.'-fields' );
            $checkout_fields = array();
            foreach( $selected_fields as $name => $val ){
                $checkout_fields[$name] = json_decode( $val[0], true );
            }
            
            wc()->countries->address_formats = array();
            $_adress_formats = wc()->countries->get_address_formats();
            foreach ($checkout_fields as $key => $field) {
                $meta_value = get_post_meta( $_context->get_id(), '_'.$_type.'_'.esc_attr($field["name"]), true );
                $flg_em = !empty( $meta_value ) && is_array( $field );
                if( $flg_em ){
                    $show = isset( $field["visibility"] ) && $field["visibility"] == "yes"  ? true : false;
                    if( $show && $_check == "custom" ){
                        $details[$field["name"]] = array(
                            'label' => __( $field["label"], 'woocommerce' ),
                            'value' => $meta_value
                        );
                    } 
                    if( $_check == "address" ){
                        $details[$field["name"]] = $meta_value;
                    }
                    
                    if( $show && $_check == "address" ){
                        foreach( $_adress_formats as $adr_key => $adr_val ){
                            $_adress_formats[$adr_key] .= "\n{".$field["name"]."}";
                        }
                        
                    }
                }
            }
            wc()->countries->address_formats = $_adress_formats;
            return $details;
        }
        
        
        
        private function add_into_address_admin ( $_type, $_fields, $_id = 0 ){
            global $post;
            $id = $_id == 0 ? $post->ID : $_id;
            $selected_fields  = $this->get_fields_meta( $_type."-fields" );
            $checkout_fields = array();
            foreach( $selected_fields as $name => $val ){
                $checkout_fields[$name] = json_decode( $val[0], true );
            }
           
            foreach ($checkout_fields as $key => $field) {
                if( is_array( $field ) ){
                    $valid = isset( $field["is_unremovable"] ) && $field["is_unremovable"]  ? false : true;
                    $meta_value = get_post_meta( $id, '_'.$_type.'_'.esc_attr($field["name"]), true );
                    $valid_flg = $valid && !empty( $meta_value );
                    if( $valid_flg ){
                        $_fields[$field["name"]] = array(
                            'label' => __( $field["label"], 'woocommerce' ),
                            'value' => $meta_value
                        );
                    }
                }
            }
            return $_fields;
        }
        
        /* show to user address */
        public function wcccf_retirive_formatted_address( $_address, $_arg ){
            foreach( $_arg as $key => $val ){
                if( !isset( $_address["{".$key."}"] ) ){
                    $_address["{".$key."}"] = $val;
                    $_address["{".$key."_upper}"] = strtoupper( $val );
                }
            }
            return $_address;
        }
        
        /* To render custom checkout field */
        public function wcccf_custom_checkout_fields(){
            $checkout_custom_meta = $this->get_fields_meta( 'custom-fields' );
            $checkout_custom_fields = array();
            foreach( $checkout_custom_meta as $name => $val ){
                $checkout_custom_fields[$name] = json_decode( $val[0], true );
            }
            
            foreach ($checkout_custom_fields as $key => $field) {
                if( is_array( $field ) && $this->check_login_user( $field ) ){
                    $field["name"] = "wcccf_custom_".$field["name"];
                    $field["required"] = isset( $field["required"] ) && $field["required"] == "yes" ? true : false;
                    $field["cloneable"] = "no";
                    echo wcff()->builder->build_user_field( $field, "wcccf", true );
                }
            }
        }
        
        /* checkout Custom Field Validation */
        public function wcccf_custom_checkout_fields_validation( $data, $errors ){
            $checkout_custom_meta = $this->get_fields_meta( 'custom-fields' );
            $checkout_custom_fields = array();
            foreach( $checkout_custom_meta as $name => $val ){
                $checkout_custom_fields[$name] = json_decode( $val[0], true );
            }
            foreach ($checkout_custom_fields as $key => $field) {
                if( is_array( $field ) && $this->check_login_user( $field ) ){
                    $field["name"] = "wcccf_custom_".$field["name"];
                    $flg_one = !isset( $_REQUEST[$field["name"]] ) || ( isset( $_REQUEST[$field["name"]] ) && empty( $_REQUEST[$field["name"]] ) );
                    if( $flg_one  ){
                        if( isset( $field["required"] ) && $field["required"] == "yes" ){
                            $msg = isset( $field["message"] ) && !empty( $field["message"] ) ? esc_html( $field["message"] ) :  sprintf( __( '%s is a required field.', 'wc-fields-factory' ), '<strong>' . esc_html( $field["label"] ) . '</strong>' );
                            $errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', $msg, $field["label"] ) );
                        }
                    } 
                    $flg_two = isset( $_REQUEST[$field["name"]] ) && !empty( $_REQUEST[$field["name"]] ) ;
                    if ( $flg_two )  {
                        if( $field["type"] == "email" ){
                            if( filter_var($_REQUEST[$field["name"]], FILTER_VALIDATE_EMAIL) === false ) {
                                $msg = isset( $field["message"] ) && !empty( $field["message"] ) ? esc_html( $field["message"] ) :  sprintf( __( '%s invalid Email Addess.', 'wc-fields-factory' ), '<strong>' . esc_html( $field["label"] ) . '</strong>' );
                                $errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', $msg, $field["label"] ) );
                            }
                        } else if( $field["type"] == "number" ){
                            if( !is_numeric($_REQUEST[$field["name"]]) ){
                                $msg = isset( $field["message"] ) && !empty( $field["message"] ) ? esc_html( $field["message"] ) :  sprintf( __( '%s invalid Number.', 'wc-fields-factory' ), '<strong>' . esc_html( $field["label"] ) . '</strong>' );
                                $errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', $msg, $field["label"] ) );
                            }
                        }
                    }
                }
            }
        }
        
        /* Genarate Color Script for checkout field */
        private function enqueue_color_picker_script() {
            $all_fields = array();
            $all_fields["billing"]  = $this->get_fields_meta( 'billing-fields' );
            $all_fields["shipping"] = $this->get_fields_meta( 'shipping-fields' );
            $all_fields["custom"] = $this->get_fields_meta( 'custom-fields' );
            $checkout_fields = array();
            
            
            $color_picker_script = "";
            $color_picker_script .= '<script type="text/javascript">
    				var $ = jQuery;
    				function wccpf_init_color_pickers() {';
            foreach( $all_fields as $groupkey => $gourpval ){
                $checkout_fields = array();
                foreach( $gourpval as $name => $val ){
                    $checkout_fields[$name] = json_decode( $val[0], true );
                }
                foreach ($checkout_fields as $key => $field) {
                    if ($field["type"] == "colorpicker") {
                        $palettes = null;
                        $colorformat = isset($field["color_format"]) ? $field["color_format"] : "hex";
                        $defaultcolor = isset($field["default_value"]) ? $field["default_value"] : "#000";
                        
                        if (isset($field["palettes"]) && $field["palettes"] != "") {
                            $palettes = explode(";", $field["palettes"]);
                        }
                        $color_picker_script .= 'jQuery( ".wcccf-color-wcccf_' . $groupkey . "_" . esc_attr($field["name"]) . '").spectrum({
        						 color: "' . $defaultcolor . '",
        						 preferredFormat: "' . $colorformat . '",';
                        if ($field["show_palette_only"] != "yes") {
                            if (isset($field["color_text_field"]) && $field["color_text_field"] == "yes") {
                                $color_picker_script .= "showInput: true,";
                            }
                        }
                        $comma = "";
                        $indexX = 0;
                        $indexY = 0;
                        if (is_array($palettes) && count($palettes) > 0) {
                            if ($field["show_palette_only"] == "yes") {
                                $color_picker_script .= "showPaletteOnly: true,";
                            }
                            $color_picker_script .= "showPalette: true,";
                            $color_picker_script .= "palette : [";
                            foreach ($palettes as $palette) {
                                $indexX = 0;
                                $comma = ($indexY == 0) ? "" : ",";
                                $color_picker_script .= $comma . "[";
                                $colors = explode(",", $palette);
                                foreach ($colors as $color) {
                                    $comma = ($indexX == 0) ? "" : ",";
                                    $color_picker_script .= $comma . "'" . $color . "'";
                                    $indexX ++;
                                }
                                $color_picker_script .= "]";
                                $indexY ++;
                            }
                            $color_picker_script .= "]";
                        }
                        $color_picker_script .= '});';
                    }
                }
            }
            $color_picker_script .= '} jQuery(document).ready(function() { wccpf_init_color_pickers(); });</script>';
            return $color_picker_script;
        }
        
    }

?>