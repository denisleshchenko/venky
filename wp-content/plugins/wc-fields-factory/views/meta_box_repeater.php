<?php 

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$logics = apply_filters( "wcff/pricing/and/sub/fields/logic", array(
	array( "id" => "equal", "title" => __( "is equal to", 'wc-fields-factory' ) ),
	array( "id" => "not-equal", "title" => __( "is not equal to", 'wc-fields-factory' ) ),
	array( "id" => "less-than", "title" => __( "less than", 'wc-fields-factory' ) ),
	array( "id" => "less-than-equal", "title" => __( "less than or equal to", 'wc-fields-factory' ) ),
	array( "id" => "greater-than", "title" => __( "greater than", 'wc-fields-factory' ) ),
	array( "id" => "greater-than-equal", "title" => __( "greater than or equal to", 'wc-fields-factory' ) )
));

?>

<div id="wcff_fields_factory" action="POST">

	<table class="wcff_table wcff_repeater_factory_header">
		<tr>		
			<td><input type="text" name="wcff-field-type-meta-label" class="wcff-field-type-meta-label" value="" placeholder="Label"/></td>
			<td><input type="text" name="wcff-field-type-meta-name" class="wcff-field-type-meta-name" value="" placeholder="Name" readonly/></td>
			<td><a href="#" class="wcff-add-new-repeater button button-primary">+ <?php _e( 'Add Repeater', 'wc-fields-factory' ); ?></a></td>
		</tr>
	</table>	

	<div class="wcff-repeater-meta-container">
		<table class="wcff_table">
			<tbody id="wcff-field-types-meta-body">		
				<?php  echo apply_filters( 'wcff/render/setup/fields/type=repeater', "wccpf" ); ?>						
			</tbody>
			<!--
			<tfoot id="wcff-field-factory-footer" style="display:none">
				<tr>
					<td></td>
					<td style="text-align: right;">
						<a href="#" class="wcff-cancel-update-field-btn button"><?php _e( 'Cancel', 'wc-fields-factory' ); ?></a>
						<a href="#" data-key="" class="button wcff-meta-option-delete-btn"><?php _e( 'Delete', 'wc-fields-factory' ); ?></a>						
					</td>
				</tr>
			</tfoot>
			!-->
		</table>
	</div>	
		
</div>