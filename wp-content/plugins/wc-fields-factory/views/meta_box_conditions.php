<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;
$index = 0;

$contexts = apply_filters( "wcff/condition/context", array(
	array( "id" => "product", "title" => "Product" ),
	array( "id" => "product_cat", "title" => "Product Category" ),
	array( "id" => "product_tag", "title" => "Product Tag" ),
	array( "id" => "product_type", "title" => "Product Type" ),
	array( "id" => "product_variation", "title" => "Product Variation" )
));

$logics = apply_filters( "wcff/condition/logic", array( 
	array( "id"=>"==", "title"=>"is equal to" ), 
	array( "id"=>"!=", "title"=>"is not equal to" )
));

$rule_group = wcff()->dao->load_condition_rules( $post->ID );
$rule_group = json_decode( $rule_group, true );

$products = wcff()->dao->load_products();
array_unshift( $products , array( "id" => "-1", "title" => "All Products" ));

$pcats = wcff()->dao->load_product_cats();
array_unshift( $pcats , array( "id" => "-1", "title" => "All Categories" ));

?>

<div class="wcff_logic_wrapper">
	<table class="wcff_table">
		<tbody>
			<tr>
				<td class="summary">
					<label for="post_type"><?php _e( 'Rules', 'wc-fields-factory' ); ?></label>
					<p class="description"><?php _e( 'Add rules to determines which products or product categories will have this custom fields group', 'wc-fields-factory' ); ?></p>
				</td>
				<td>
					<div class="wcff_logic_groups">
					<?php if( is_array( $rule_group ) && count( $rule_group ) > 0 && !empty( $rule_group ) ) {					
						foreach ( $rule_group as $group ) { ?>
																			
							<div class="wcff_logic_group"> 
								<h4><?php echo ( $index == 0 ) ? __( 'Show this product fields group if', 'wc-fields-factory' ) : __( 'or', 'wc-fields-factory' ); ?></h4>
								<table class="wcff_table wcff_rules_table">
								<tbody>
									<?php foreach ( $group as $rule ) { ?>
									<tr>
										<td>
											<select class="wcff_condition_param select">
												<?php foreach ( $contexts as $context ) {
													$selected = ( $context["id"] == $rule["context"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $context["id"] .'" '. $selected .'>'. $context["title"] .'</option>';													
												} ?>																			
											</select>
										</td>
										<td>
											<select class="wcff_condition_operator select">
												<?php foreach ( $logics as $logic ) {
													$selected = ( $logic["id"] == $rule["logic"] ) ? 'selected="selected"' : '';
													echo '<option value="'. $logic["id"] .'" '. $selected .'>'. $logic["title"] .'</option>';													
												} ?>												
											</select>
										</td>
										<td class="condition_value_td">											
											<?php																							
												if( $rule["context"] == "product" ) {
												    echo wcff()->builder->build_products_list( 'wcff_condition_value select', $rule["endpoint"] );
												} elseif( $rule["context"] == "product_cat" ) {
													echo wcff()->builder->build_products_cat_list( 'wcff_condition_value select', $rule["endpoint"] );
												} elseif( $rule["context"] == "product_tag" ) {
													echo wcff()->builder->build_products_tag_list( 'wcff_condition_value select', $rule["endpoint"] );
												} elseif( $rule["context"] == "product_type" ) {
													echo wcff()->builder->build_products_type_list( 'wcff_condition_value select', $rule["endpoint"] );
												} elseif( $rule["context"] == "product_variation" ) {
													echo wcff()->builder->build_products_varions_list( 'wcff_condition_value select', $rule["endpoint"] );
												}
											?>											
										</td>
										<td class="add"><a href="#" class="condition-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"><?php echo ( $index != 0 ) ? '<a href="#" class="condition-remove-rule wcff-button-remove"></a>' : ''; ?></td>
									</tr>
									<?php $index++; } ?>
								</tbody>
							</table>
							</div>					
					
					<?php } } else { ?>					
						<div class="wcff_logic_group"> 
							<?php if( $post->post_type == "wccpf" ) : ?>
							<h4><?php _e( 'Show this product fields group if', 'wc-fields-factory' ); ?></h4>
							<?php elseif ( $post->post_type == "wccaf" ) : ?>
							<h4><?php _e( 'Show this admin fields group if', 'wc-fields-factory' ); ?></h4>
							<?php endif; ?>
							<table class="wcff_table wcff_rules_table">
								<tbody>
									<tr>
										<td>
											<select class="wcff_condition_param select">
												<option value="product" selected="selected"><?php _e( 'Product', 'wc-fields-factory' ); ?></option>
												<option value="product_cat"><?php _e( 'Product Category', 'wc-fields-factory' ); ?></option>
												<option value="product_tag"><?php _e( 'Product Tag', 'wc-fields-factory' ); ?></option>
												<option value="product_type"><?php _e( 'Product Type', 'wc-fields-factory' ); ?></option>
												<option value="product_variation"><?php _e( 'Product Variation', 'wc-fields-factory' ); ?></option>
											</select>
										</td>
										<td>
											<select class="wcff_condition_operator select">
												<option value="==" selected="selected"><?php _e( 'is equal to', 'wc-fields-factory' ); ?></option>
												<option value="!="><?php _e( 'is not equal to', 'wc-fields-factory' ); ?></option>
											</select>
										</td>
										<td class="condition_value_td">
											<?php echo wcff()->builder->build_products_list( "wcff_condition_value" ); ?>											
										</td>
										<td class="add"><a href="#" class="condition-add-rule button"><?php _e( 'and', 'wc-fields-factory' ); ?></a></td>
										<td class="remove"></td>
									</tr>
								</tbody>
							</table>							
						</div>				
					<?php } ?>
						<h4>or</h4>
						<a href="#" class="condition-add-group button"><?php _e( 'Add condition group', 'wc-fields-factory' ); ?></a>	
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="wcff_condition_rules" id="wcff_condition_rules" value="Sample Rules"/>
</div>
