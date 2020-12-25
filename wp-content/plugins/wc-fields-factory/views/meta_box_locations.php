<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$index = 0;

$locations = apply_filters( "wcff/location/context", array(
	array( "id" => "location_product_data", "title" => __( "Product Tabs", "wc-fields-factory" ) ),
	array( "id" => "location_product", "title" => __( "Product View", "wc-fields-factory" ) ),
	array( "id" => "location_product_cat", "title" => __( "Product Category View", "wc-fields-factory" ) )
));

$logics = apply_filters( "wcff/condition/logic", array( 
	array( "id" => "==", "title" => __( "is equal to", "wc-fields-factory" ) ),
	array( "id" => "!=", "title" => __( "is not equal to", "wc-fields-factory" ) )
));

$rule_group = wcff()->dao->load_location_rules($post->ID);
$rule_group = json_decode( $rule_group, true );

?>

<div class="wcff_location_logic_wrapper">
	<table class="wcff_table">
		<tbody>
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Rules', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Add rules to determines which products or product categories will have this custom fields group', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff_location_logic_groups">
					<?php if( is_array( $rule_group ) && count( $rule_group ) > 0 ) {					
						foreach ( $rule_group as $group ) { ?>
																			
							<div class="wcff_location_logic_group"> 
								<h4><?php echo ( $index == 0 ) ? __( 'Show this product fields group if', 'wc-fields-factory' ) : __( 'or', 'wc-fields-factory' ); ?></h4>
								<table class="wcff_table wcff_location_rules_table">
								<tbody>
									<?php foreach ( $group as $rule ) { ?>
									<tr>
										<td>
											<select class="wcff_location_param select">
												<?php foreach ( $locations as $location ) {
													$selected = ( $location["id"] == $rule["context"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $location["id"] .'" '. $selected .'>'. $location["title"] .'</option>';													
												} ?>																			
											</select>
										</td>
										<td>
											<select class="wcff_location_operator select">
												<?php foreach ( $logics as $logic ) {
													$selected = ( $logic["id"] == $rule["logic"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $logic["id"] .'" '. $selected .'>'. $logic["title"] .'</option>';													
												} ?>												
											</select>
										</td>
										<td class="location_value_td">
											<?php 												
											if( is_array( $rule["endpoint"] ) ) {	
												echo wcff()->builder->build_metabox_context_list("wcff_location_metabox_context_value", $rule["endpoint"]["context"]);
												echo wcff()->builder->build_metabox_priority_list("wcff_location_metabox_priorities_value", $rule["endpoint"]["priority"]);											
											} else {																		
												echo wcff()->builder->build_products_tabs_list("wcff_location_product_data_value", $rule["endpoint"]);												
											}											
											?>																				
										</td>
										<!--
										 <td class="add"><a href="#" class="location-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"><?php echo ( $index != 0 ) ? '<a href="#" class="condition-remove-rule wcff-button-remove"></a>' : ''; ?></td>
										 -->
									</tr>
									<?php $index++; } ?>
								</tbody>
							</table>
						</div>					
					
					<?php } } else { ?>					
						<div class="wcff_location_logic_group"> 
							<h4><?php _e( 'Place this admin fields group on the following locations', 'wc-fields-factory' ); ?></h4>
							<table class="wcff_table wcff_location_rules_table">
								<tbody>
									<tr>
										<td>
											<select class="wcff_location_param select">
												<?php foreach ( $locations as $location ) : ?>
													<option value="<?php echo $location["id"]; ?>"><?php echo $location["title"]; ?></option>
												<?php endforeach; ?>																																				
											</select>
										</td>
										<td>
											<select class="wcff_location_operator select">
												<option value="==" selected="selected"><?php _e( 'is equal to', 'wc-fields-factory' ); ?></option>												
											</select>
										</td>
										<td class="location_value_td">
											<?php echo wcff()->builder->build_products_tabs_list("wcff_location_product_data_value"); ?>											
										</td>
										<!-- 
										<td class="add"><a href="#" class="location-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"></td>
										 -->
									</tr>
								</tbody>
							</table>							
						</div>				
					<?php } ?>
						<!-- 
						<h4>or</h4>
						<a href="#" class="location-add-group button"><?php _e( 'Add location group', 'wc-fields-factory' ); ?></a>
						 -->	
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="wcff_location_rules" id="wcff_location_rules" value="Sample Rules"/>
</div>