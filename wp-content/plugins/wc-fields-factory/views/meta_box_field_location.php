<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$fields_location = get_post_meta( $post->ID, $post->post_type ."_field_location_on_product", true );
$fields_location_archive = get_post_meta( $post->ID, $post->post_type ."_field_location_on_archive", true );
?>

<div class="wcff_logic_wrapper">
	<table class="wcff_table">
		<tbody>
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Rules', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Select location for product archive page and sigle page. Note: (On product page if you want use global setting to check "Use global setting location" and archive page don\'t want to show anywhere to check "none", <strong>Please don\'t use file field on archive page</strong>)', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff-field-types-meta">
							<h3>Single Product Page</h3>
							<ul class="wcff-field-layout-horizontal wcff-field-location-on-product">
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="use_global_setting" <?php echo ( $fields_location == "use_global_setting" || $fields_location == "" ) ? "checked" : ""; ?>/> <?php _e( 'Use global setting location', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_before_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_after_add_to_cart_button" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_button" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Button', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_before_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_before_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'Before Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_after_add_to_cart_form" <?php echo ( $fields_location == "woocommerce_after_add_to_cart_form" ) ? "checked" : ""; ?>/> <?php _e( 'After Add To Cart Form', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_before_single_product_summary" <?php echo ( $fields_location == "woocommerce_before_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_after_single_product_summary" <?php echo ( $fields_location == "woocommerce_after_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'After Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_single_product_summary" <?php echo ( $fields_location == "woocommerce_single_product_summary" ) ? "checked" : ""; ?>/> <?php _e( 'Product Summary', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_product_meta_start" <?php echo ( $fields_location == "woocommerce_product_meta_start" ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Meta', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_product" value="woocommerce_product_meta_end" <?php echo ( $fields_location == "woocommerce_product_meta_end" ) ? "checked" : ""; ?>/> <?php _e( 'After Product Meta', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
						<div class="wcff-field-types-meta">
							<h3>Archive Product Page</h3>
							<ul class="wcff-field-layout-horizontal wcff-field-location-on-product">
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="none" <?php echo ( $fields_location_archive == "none" || $fields_location_archive == "" ) ? "checked" : ""; ?>/> <?php _e( 'None', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="woocommerce_before_shop_loop_item" <?php echo ( $fields_location_archive == "woocommerce_before_shop_loop_item"  ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Content', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="woocommerce_before_shop_loop_item_title" <?php echo ( $fields_location_archive == "woocommerce_before_shop_loop_item_title"  ) ? "checked" : ""; ?>/> <?php _e( 'Before Product Title', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="woocommerce_shop_loop_item_title" <?php echo ( $fields_location_archive == "woocommerce_shop_loop_item_title"  ) ? "checked" : ""; ?>/> <?php _e( 'After Product Title', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="woocommerce_after_shop_loop_item_title" <?php echo ( $fields_location_archive == "woocommerce_after_shop_loop_item_title"  ) ? "checked" : ""; ?>/> <?php _e( 'After Product Price', 'wc-fields-factory' ); ?></label></li>
								<li><label><input type="radio" class="wcff-fields-location-radio" name="field_location_on_archive" value="woocommerce_after_shop_loop_item" <?php echo ( $fields_location_archive == "woocommerce_after_shop_loop_item"  ) ? "checked" : ""; ?>/> <?php _e( 'After Product Content', 'wc-fields-factory' ); ?></label></li>
							</ul>						
						</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
