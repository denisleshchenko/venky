<?php
/*
*  Meta box - Custom Product Fields
*  Template for creating or updating custom product fields
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

// conditional logic dummy data
$conditional_logic_rule = array(
	'field' => '',
	'operator' => '==',
	'value' => ''
);

$error_field_type = '<b>' . __( 'Error', 'wc-fields-factory' ) . '</b> ' . __( 'Field type does not exist', 'wc-fields-factory' );

?>

<!-- Hidden Fields -->
<div style="display:none;">
	<input type="hidden" name="wcff_nonce" value="<?php echo wp_create_nonce( 'field_group' ); ?>" />
</div>
<!-- / Hidden Fields -->

<!-- Fields Header -->
<div class="fields_header">
	<table class="wcff_table">
		<thead>
			<tr>
				<th class="field-order"></th>
				<th class="field-label"><?php _e( 'Field Label', 'wc-fields-factory' ); ?></th>
				<th class="field-name"><?php _e( 'Field Name', 'wc-fields-factory' ); ?></th>
				<th class="field-type"><?php _e( 'Field Type', 'wc-fields-factory' ); ?></th>		
				<th class="field-actions"><?php _e( 'Actions', 'wc-fields-factory' ); ?></th>			
			</tr>
		</thead>
	</table>
</div>
<!-- / Fields Header -->

<div class="fields">
	
	<div id="wcff-fields-set" class="sortable ui-sortable">
	<div id="wcff-add-field-placeholder" style="">
		<img src="<?php echo plugins_url("", __dir__); ?>/assets/img/add.png" alt="Add Field" />
		<span class="wcff-add-here-label">Drop here.!</span>
		<br>
		<strong style="vertical-align: top;"><?php _e( '--- Drog any field from the field type box and drop here. ---', 'wc-fields-factory' ); ?></strong>
	</div>	
		<?php

			wcff()->dao->set_current_post_type( $post->post_type );			
			$fields = wcff()->dao->load_fields( $post->ID );

			if( is_array( $fields ) ) {				
				echo wcff()->builder->build_custom_fields_list( $fields );				
			} else {
				$fields = array();	
			}			
			do_action( "wcff_after_load_field_list", $post );
		?>
		
	</div>
	
	<div id="wcff-empty-field-set" style="display:<?php echo count( $fields ) < 1 ? 'block' : 'none'; ?>">
		<?php _e( 'Zero product fields.! Use the', 'wc-fields-factory' ); ?> <strong><?php _e( 'Fields Factory', 'wc-fields-factory' ); ?></strong> <?php _e( 'form to create your custom product fields.!', 'wc-fields-factory' ); ?>
	</div>	
</div>
