<?php 

function wcff_enqueue_option_assets() {
	wp_register_style( 'wcff-style', plugin_dir_url( __FILE__ ) . '../assets/css/wcff-admin.css' );
	wp_enqueue_style('wcff-style');
}
add_action( 'admin_enqueue_scripts', 'wcff_enqueue_option_assets' );	

if( is_admin() ) {
    add_action( 'admin_init', 'wcff_register_options' );
}

function wcff_register_options() {
    register_setting( 'wcff_options', 'wcff_options' );
}

function wccpf_render_option_page() {
    $wccpf_options = get_option( 'wccpf_options' );
    $wccpf_options =  is_array( $wccpf_options ) ? $wccpf_options : array();
    $show_custom_data = isset( $wccpf_options["show_custom_data"] ) ? $wccpf_options["show_custom_data"] : "yes";
    $fields_location = isset( $wccpf_options["field_location"] ) ? $wccpf_options["field_location"] : "woocommerce_before_add_to_cart_button";
    
    $ptab_title = isset( $wccpf_options["product_tab_title"] ) ? $wccpf_options["product_tab_title"] : "";
    $ptab_priority = isset( $wccpf_options["product_tab_priority"] ) ? $wccpf_options["product_tab_priority"] : 30;
    $is_show_login_user_only = isset( $wccpf_options["show_login_user_only"] ) ? $wccpf_options["show_login_user_only"] : "no";
    $edit_field_value_cart_page =  isset( $wccpf_options["edit_field_value_cart_page"] ) ? $wccpf_options["edit_field_value_cart_page"] : "no";
    $fields_cloning = isset( $wccpf_options["fields_cloning"] ) ? $wccpf_options["fields_cloning"] : "no";
    $group_title =  isset( $wccpf_options["fields_group_title"] ) ? $wccpf_options["fields_group_title"] : "";
    $show_field_group_title =  isset( $wccpf_options["show_group_title"] ) ? $wccpf_options["show_group_title"] : "no";
    $group_meta_on_cart = isset( $wccpf_options["group_meta_on_cart"] ) ? $wccpf_options["group_meta_on_cart"] : "no";
    $group_fields_on_cart = isset( $wccpf_options["group_fields_on_cart"] ) ? $wccpf_options["group_fields_on_cart"] : "no";
    $client_side_validation = isset( $wccpf_options["client_side_validation"] ) ? $wccpf_options["client_side_validation"] : "no";
    $client_side_validation_type = isset( $wccpf_options["client_side_validation_type"] ) ? $wccpf_options["client_side_validation_type"] : "submit";
    $wcff_ajax_pricing_rules = isset( $wccpf_options["enable_ajax_pricing_rules"] ) ? $wccpf_options["enable_ajax_pricing_rules"] : "disable";
    $wcff_show_pricing_rules_title = isset( $wccpf_options["ajax_pricing_rules_title"] ) ? $wccpf_options["ajax_pricing_rules_title"] : "hide";
    $wcff_show_pricing_rules_title_header = isset( $wccpf_options["ajax_pricing_rules_title_header"] ) ? $wccpf_options["ajax_pricing_rules_title_header"] : "";
    $wcff_show_pricing_details_container = isset( $wccpf_options["pricing_rules_details"] ) ? $wccpf_options["pricing_rules_details"] : "show";
    $wcff_ajax_pricing_rules_price_container = isset( $wccpf_options["ajax_pricing_rules_price_container"] ) ? $wccpf_options["ajax_pricing_rules_price_container"] : "default";
  	$wcff_ajax_price_replace_container = isset( $wccpf_options["ajax_price_replace_container"] ) ? $wccpf_options["ajax_price_replace_container"] : "";
    $enable_multilingual = isset( $wccpf_options["enable_multilingual"] ) ? $wccpf_options["enable_multilingual"] : "no";
    $supported_locale = isset( $wccpf_options["supported_lang"] ) ? $wccpf_options["supported_lang"] : array(); 
    
    ?>
    
	<?php if( isset( $_GET["settings-updated"] ) ) :?>
	<div id="message" class="updated fade"><p><strong>Your settings have been saved.</strong></p></div>
	<?php endif; ?>

	<div class="wrap wcff-options-wrapper">		
		<h2><?php _e( 'WC Fields Factory Options', 'wc-fields-factory' ); ?></h2>
		<form action='options.php' method='post' class='wcff-options-form'>		
			<?php settings_fields('wccpf_options'); ?>
					
			<table class="wcff-option-field-row wcff_table">			
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Display on Cart & Checkout', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Display custom meta data on Cart & Checkout page.!', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[show_custom_data]" value="yes" <?php echo ( $show_custom_data == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[show_custom_data]" value="no" <?php echo ( $show_custom_data == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>			
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Fields Location', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Choose where the fields should be displayed on product page', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal wcff-field-location-on-product">
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_before_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_after_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_before_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_after_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_before_single_product_summary" <?php echo ( $fields_location == "woocommerce_before_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_after_single_product_summary" <?php echo ( $fields_location == "woocommerce_after_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'After Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_single_product_summary" <?php echo ( $fields_location == "woocommerce_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_single_product_tab" <?php echo ( $fields_location == "woocommerce_single_product_tab" ) ? "checked" : ""; ?>/> <?php _e( 'Product Tab', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_product_meta_start" <?php echo ( $fields_location == "woocommerce_product_meta_start" ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Meta', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="wccpf_options[field_location]" value="woocommerce_product_meta_end" <?php echo ( $fields_location == "woocommerce_product_meta_end" ) ? "checked" : ""; ?>/> <?php _e( 'After Product Meta', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr id="wcff-product-tab-config" style="display:<?php echo ( $fields_location == "woocommerce_single_product_tab" ) ? "table-row" : "none"; ?>">
					<td class="summary">
						<label for="post_type"><?php _e( 'Product Tab Config', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'New tab will be inserted on the Product Tab, and all the custom fields will be injected on it.<br/> Enter a title for that product tab and the priority ( 10,20 30... Enter 0 if you want this tab at first )', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">							
							<label>Tab Title</label>
							<input type="text" name="wccpf_options[product_tab_title]" placeholder="eg. Customize This Product" value="<?php echo esc_attr( $ptab_title ); ?>" />								
							<label>Tab Priority</label>
							<input type="number" name="wccpf_options[product_tab_priority]" value="<?php echo esc_attr( $ptab_priority ); ?>" />													
						</div>
					</td>
				</tr>			
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Fields Cloning', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Display custom fields per product count. Whenever user increases the product quantity, all custom fields will be cloned.!, the', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[fields_cloning]" value="yes" <?php echo ( $fields_cloning == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[fields_cloning]" value="no" <?php echo ( $fields_cloning == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Cloning Group Title', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'If "Fields Cloning" enabled, then you can assign a title for fields group.!', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<input type="text" name="wccpf_options[fields_group_title]" value="<?php echo esc_attr( $group_title ); ?>" style="<?php echo (($enable_multilingual == "yes") && (count($supported_locale) > 0)) ? 'width: 93%;' : ''; ?>" placeholder="eg. Addiotnal Options : "/>
							<?php 
							if (($enable_multilingual == "yes") && (count($supported_locale) > 0)) { ?>
								<button class="wcff-factory-multilingual-btn" title="Open Multilingual Panel"><img src="<?php echo get_home_url() .'/wp-content/plugins/wc-fields-factory/assets/img/translate.png'; ?>" /></button>
								<?php 
							}													
							if ($enable_multilingual == "yes") {
								include_once( dirname(dirname(__FILE__)). '/includes/wcff-locale.php');
								$l_manager = new Wcff_Locale();
								$locales = $l_manager->get_locales();
								if (count($supported_locale) > 0) {
									echo '<div class="wcff-locale-list-wrapper" style="display: none;">';
									foreach ($supported_locale as $code) { 									
										$grp_title = (isset($wccpf_options["fields_group_title_". $code])) ? $wccpf_options["fields_group_title_". $code] : ""; ?>			
									<label>Cloning Group Title for <?php echo $locales[$code]; ?></label>						
									<input type="text" name="wccpf_options[fields_group_title_<?php echo $code; ?>]" value="<?php echo $grp_title; ?>" />										
									<?php 
									}
									echo '<div>';
								}								
							}							
							?>																							
						</div>
					</td>
				</tr>			
				<tr style="display: none;">
					<td class="summary">
						<label for="post_type"><?php _e( 'Group Meta', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Custom meta data will be grouped and displayed in cart & checkout. won\'t work if group fields option choosed.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[group_meta_on_cart]" value="yes" <?php echo ( $group_meta_on_cart == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[group_meta_on_cart]" value="no" <?php echo ( $group_meta_on_cart == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>
				<tr style="display: none;">
					<td class="summary">
						<label for="post_type"><?php _e( 'Group Fields', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Custom fields will be grouped ( within each line item, per count ) and displayed in cart & checkout.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[group_fields_on_cart]" value="yes" <?php echo ( $group_fields_on_cart == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[group_fields_on_cart]" value="no" <?php echo ( $group_fields_on_cart == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Show Group Title', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Whether to show the group title for each fields group.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[show_group_title]" value="yes" <?php echo ( $show_field_group_title == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[show_group_title]" value="no" <?php echo ( $show_field_group_title == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>					
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Client Side Validation', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Whether the validation should be done on Client Side.?', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">							
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[client_side_validation]" value="yes" <?php echo ( $client_side_validation == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[client_side_validation]" value="no" <?php echo ( $client_side_validation == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>		
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Client Side Validation Type', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Choose whether the validation done on field level ( on blur ) or while form submit', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">							
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[client_side_validation_type]" value="submit" <?php echo ( $client_side_validation_type == "submit" ) ? "checked" : ""; ?>/> <?php _e( 'On Product Submit', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[client_side_validation_type]" value="blur" <?php echo ( $client_side_validation_type == "blur" ) ? "checked" : ""; ?>/> <?php _e( 'On Blur [ + Product Submit ]', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Show custom fields login user only', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Show all field only if user has logged-in', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">							
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[show_login_user_only]" value="yes" <?php echo ( $is_show_login_user_only == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[show_login_user_only]" value="no" <?php echo ( $is_show_login_user_only == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Editable', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Make all fields editable on cart', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">							
							<ul class="wcff-field-layout-horizontal">
								<li><label><input type="radio" name="wccpf_options[edit_field_value_cart_page]" value="yes" <?php echo ( $edit_field_value_cart_page == "yes" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" name="wccpf_options[edit_field_value_cart_page]" value="no" <?php echo ( $edit_field_value_cart_page == "no" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
					</td>
				</tr>
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Multilingual', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Enable multi language option for fields labels, options, placeholders and validation messages', 'wc-fields-factory' ); ?></p>
					</td>
					
					<td>
						<ul class="wcff-field-layout-horizontal">
							<li><label><input type="radio" name="wccpf_options[enable_multilingual]" value="yes" <?php echo ( $enable_multilingual == "yes" ) ? "checked" : ""; ?> class="wcff-multilingual-option-radio" /> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
							<li><label><input type="radio" name="wccpf_options[enable_multilingual]" value="no" <?php echo ( $enable_multilingual == "no" ) ? "checked" : ""; ?> class="wcff-multilingual-option-radio" /> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
						</ul>	
						<div id="wcff-multilingual-locale-list" style="<?php echo ($enable_multilingual == "yes") ? "display: block;" : "display: none;"; ?>">
							<label class="">Choose supported languages</label>
							<ul class="wcff-field-layout-horizontal wcff-multilingual-choser-ul">
							<?php 
								$locales = wcff()->locale->get_locales();
								foreach ($locales as $code => $title) {
							    	echo '<li><label><input type="checkbox" '. (in_array($code, $supported_locale) ? "checked" : "") .' name="wccpf_options[supported_lang][]" value="'. $code .'"/> '. $title .'</label></li>';
							    }
							?>							
							</ul>
						</div>						
					</td>
				</tr>	
				<tr>
					<td class="summary">
						<label for="post_type"><?php _e( 'Price Rules', 'wc-fields-factory' ); ?></label>
						<p class="description"><?php _e( 'Pricing rules setting.', 'wc-fields-factory' ); ?></p>
					</td>
					<td>
						<div class="wcff-field-types-meta">			
								<div class="wcff-factory-tab-container">
									<div class="wcff-factory-tab-left-panel">
										<ul>
											<li data-box="#wcff-ajax-pricing-rule-enable" class="selected"><label><?php _e( 'Update Price.?', 'wc-fields-factory' ); ?></label></li>
											<li data-box="#wcff-ajax-pricing-rule-title-enable"><label><?php _e( 'Product Price Selector', 'wc-fields-factory' ); ?></label></li>
											<li data-box="#wcff-ajax-pricing-rule-replace-container"><label><?php _e( 'Price Rules', 'wc-fields-factory' ); ?></label></li>
										</ul>
									</div>
									<div class="wcff-factory-tab-right-panel wcff-panel-tab-right-container">
										<div id="wcff-ajax-pricing-rule-enable" class="wcff-factory-tab-content">
											<ul class="wcff-field-layout-horizontal">
												<li><label><input type="radio" name="wccpf_options[enable_ajax_pricing_rules]" value="enable" <?php echo ( $wcff_ajax_pricing_rules == "enable" ) ? "checked" : ""; ?>/> <?php _e( 'Yes', 'wc-fields-factory' ); ?></label></li>
												<li><label><input type="radio" name="wccpf_options[enable_ajax_pricing_rules]" value="disable" <?php echo ( $wcff_ajax_pricing_rules == "disable" ) ? "checked" : ""; ?>/> <?php _e( 'No', 'wc-fields-factory' ); ?></label></li>
											</ul>
											<p class="description">Updating the product price at the real time in product page.</p>
										</div>
										<div id="wcff-ajax-pricing-rule-replace-container" class="wcff-factory-tab-content">
											<ul class="wcff-field-layout-horizontal">
												<li><label><input type="radio" class="wcff-pricing-rules-rules-price-container" name="wccpf_options[ajax_pricing_rules_price_container]" value="default" <?php echo ( $wcff_ajax_pricing_rules_price_container == "default" ) ? "checked" : ""; ?>/> <?php _e( 'Default', 'wc-fields-factory' ); ?></label></li>
												<li><label><input type="radio" class="wcff-pricing-rules-rules-price-container" name="wccpf_options[ajax_pricing_rules_price_container]" value="custom" <?php echo ( $wcff_ajax_pricing_rules_price_container == "custom" ) ? "checked" : ""; ?>/> <?php _e( 'Custom', 'wc-fields-factory' ); ?></label></li>
												<li><label><input type="radio" class="wcff-pricing-rules-rules-price-container" name="wccpf_options[ajax_pricing_rules_price_container]" value="both" <?php echo ( $wcff_ajax_pricing_rules_price_container == "both" ) ? "checked" : ""; ?>/> <?php _e( 'Both', 'wc-fields-factory' ); ?></label></li>
											</ul>
											<div style="<?php echo ( $wcff_ajax_pricing_rules_price_container == "default"  ) ? "display: none;" : ""; ?>" id="wcff-pricing-rules-rules-price-container">
												<label><input type="text" name="wccpf_options[ajax_price_replace_container]" placeholder="Add Price replacing html element" value="<?php echo $wcff_ajax_price_replace_container; ?>" /><p class="description"><?php _e( 'After pricing calculation replace price container class or id Ex:( .class_one, #id_one )', 'wc-fields-factory' ); ?></p> </label>
											</div>
											<p class="description">In single product page replace old price into negotiated price element.</p>
										</div>
										<div id="wcff-ajax-pricing-rule-title-enable" class="wcff-factory-tab-content">
											<div class="">
												<h4>Pricing rule details</h4>
    											<ul class="wcff-field-layout-horizontal">
    												<li><label><input type="radio" class="wcff-pricing-rules-container-option-radio" name="wccpf_options[pricing_rules_details]" value="show" <?php echo ( $wcff_show_pricing_details_container == "show" ) ? "checked" : ""; ?>/> <?php _e( 'Show', 'wc-fields-factory' ); ?></label></li>
    												<li><label><input type="radio" class="wcff-pricing-rules-title-option-radio" name="wccpf_options[pricing_rules_details]" value="hide" <?php echo ( $wcff_show_pricing_details_container == "hide" ) ? "checked" : ""; ?>/> <?php _e( 'Hide', 'wc-fields-factory' ); ?></label></li>
    											</ul>
												<p class="description">Pricing rule details want to show or not in product page and cart page to user.</p>
											</div>
											<div class="">
												<h4>Pricing Title</h4>
    											<ul class="wcff-field-layout-horizontal">
    												<li><label><input type="radio" class="wcff-pricing-rules-title-option-radio" name="wccpf_options[ajax_pricing_rules_title]" value="show" <?php echo ( $wcff_show_pricing_rules_title == "show" ) ? "checked" : ""; ?>/> <?php _e( 'Show', 'wc-fields-factory' ); ?></label></li>
    												<li><label><input type="radio" class="wcff-pricing-rules-title-option-radio" name="wccpf_options[ajax_pricing_rules_title]" value="hide" <?php echo ( $wcff_show_pricing_rules_title == "hide" ) ? "checked" : ""; ?>/> <?php _e( 'Hide', 'wc-fields-factory' ); ?></label></li>
    											</ul>
    											<div style="<?php echo ( $wcff_show_pricing_rules_title == "show" ) ? "" : "display: none;"; ?>" id="wcff-pricing-rules-title-option-field">
    												<label><input type="text" name="wccpf_options[ajax_pricing_rules_title_header]" placeholder="<?php _e( 'Pricing rules title header', 'wc-fields-factory' ); ?>" value="<?php echo $wcff_show_pricing_rules_title_header; ?>" /> </label>
    											</div>
    											<p class="description">Pricing rule overall title.</p>
											</div>
										</div>
									</div>
								</div>
						</div>
					</td>
				</tr>
										
			</table>			
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
	
	<script type="text/javascript">
		(function($){
			
			
			$( document ).ready(function(){
				$( ".wcff-fields-location-radio" ).on( "change", function() {
					if( $( this ).is(":checked") && $( this ).val() == "woocommerce_single_product_tab" ) {
						$( "#wcff-product-tab-config" ).fadeIn("normal");
					} else {
						$( "#wcff-product-tab-config" ).fadeOut("normal");
					}
				});
				$( ".wcff-multilingual-option-radio" ).on("change", function() {
					if( $(this).is(":checked") && $(this).val() === "yes" ) {
						$( "#wcff-multilingual-locale-list" ).fadeIn();
					} else {
						$( "#wcff-multilingual-locale-list" ).fadeOut();
					}
				});
				$(document).on( "click", "button.wcff-factory-multilingual-btn", function(e) {
					$(this).next().toggle("normal");
					e.preventDefault();
					e.stopPropagation();
				});
				/* Click hanlder tab headers */
				$(document).on( "click", "div.wcff-factory-tab-left-panel li", this, function(e) {					
					$(this).parent().parent().next().find(">div").hide()
					$(this).parent().find("> li").removeClass();
					$(this).addClass("selected");			
					$(this).parent().parent().next().find(">div:nth-child("+ ($(this).index() + 1) +")").show();
				});	
				$( ".wcff-pricing-rules-title-option-radio" ).on("change", function() {
					if( $(this).is(":checked") && $(this).val() === "show" ) {
						$( "#wcff-pricing-rules-title-option-field" ).fadeIn();
					} else {
						$( "#wcff-pricing-rules-title-option-field" ).fadeOut();
					}
				});
				$( ".wcff-pricing-rules-rules-price-container" ).on("change", function() {
					if( $(this).is(":checked") && ( $(this).val() === "custom"  || $(this).val() === "both" ) ) {
						$( "#wcff-pricing-rules-rules-price-container" ).fadeIn();
					} else {
						$( "#wcff-pricing-rules-rules-price-container" ).fadeOut();
					}
				});
			});
		})(jQuery);
	</script>
	
<?php 

}

?>