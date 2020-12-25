<?php
/*
*  Meta box - Custom Product Fields
*  Template for creating or updating custom product fields
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;


$fields = array();


if( $post->post_type != "wccaf" ) {
	$fields = apply_filters( "wccpf/fields/factory/supported/fields", array(
			array( "id" => "text", "title" => __( 'Text', 'wc-fields-factory' ) ),
			array( "id" => "number", "title" => __( 'Number', 'wc-fields-factory' ) ),
			array( "id" => "email", "title" => __( 'Email', 'wc-fields-factory' ) ),
			array( "id" => "hidden", "title" => __( 'Hidden', 'wc-fields-factory' ) ),
			array( "id" => "label", "title" => __( 'Label', 'wc-fields-factory' ) ),
			array( "id" => "textarea", "title" => __( 'Text Area', 'wc-fields-factory' ) ),
			array( "id" => "checkbox", "title" => __( 'Check Box', 'wc-fields-factory' ) ),
			array( "id" => "radio", "title" => __( 'Radio Button', 'wc-fields-factory' ) ),
			array( "id" => "select", "title" => __( 'Select', 'wc-fields-factory' ) ),
			array( "id" => "datepicker", "title" => __( 'Date Picker', 'wc-fields-factory' ) ),
			array( "id" => "colorpicker", "title" => __( 'Color Picker', 'wc-fields-factory' ) ),
			array( "id" => "file", "title" => __( 'File', 'wc-fields-factory' ) )
	));
} else {
	$fields = apply_filters( "wccaf/fields/factory/supported/fields", array(
			array( "id" => "text", "title" => __( 'Text', 'wc-fields-factory' ) ),
			array( "id" => "number", "title" => __( 'Number', 'wc-fields-factory' ) ),
			array( "id" => "email", "title" => __( 'Email', 'wc-fields-factory' ) ),
			array( "id" => "textarea", "title" => __( 'Text Area', 'wc-fields-factory' ) ),
			array( "id" => "checkbox", "title" => __( 'Check Box', 'wc-fields-factory' ) ),
			array( "id" => "radio", "title" => __( 'Radio Button', 'wc-fields-factory' ) ),
			array( "id" => "select", "title" => __( 'Select', 'wc-fields-factory' ) ),
			array( "id" => "datepicker", "title" => __( 'Date Picker', 'wc-fields-factory' ) ),
			array( "id" => "colorpicker", "title" => __( 'Color Picker', 'wc-fields-factory' ) ),
			array( "id" => "image", "title" => __( 'Image', 'wc-fields-factory' ) ),
			array( "id" => "url", "title" => __( 'Url', 'wc-fields-factory' ) )
	));
}

//$fields = apply_filters($post->post_type ."/fields/factory/supported/fields", $fields);

$logics = apply_filters( "wcff/pricing/and/sub/fields/logic", array(
		array( "id" => "equal", "title" => __( "is equal to", 'wc-fields-factory' ) ),
		array( "id" => "not-equal", "title" => __( "is not equal to", 'wc-fields-factory' ) ),
		array( "id" => "less-than", "title" => __( "less than", 'wc-fields-factory' ) ),
		array( "id" => "less-than-equal", "title" => __( "less than or equal to", 'wc-fields-factory' ) ),
		array( "id" => "greater-than", "title" => __( "greater than", 'wc-fields-factory' ) ),
		array( "id" => "greater-than-equal", "title" => __( "greater than or equal to", 'wc-fields-factory' ) )
));

$wccpf_options = wcff()->option->get_options();
$is_multilingual = isset($wccpf_options["enable_multilingual"]) ? $wccpf_options["enable_multilingual"] : "no";
$supported_locale = isset($wccpf_options["supported_lang"]) ? $wccpf_options["supported_lang"] : array();

?>

<!-- Hidden Fields -->
<div style="display:none;">
	<input type="hidden" name="wcff_nonce" value="<?php echo wp_create_nonce( 'field_group' ); ?>" />
</div>
<!-- / Hidden Fields -->


<div class="fields_select">
	<div id="wcff-fields-select-container">
		<ul class="select">
			<?php foreach ( $fields as $field ) : ?>
			<li><a draggable="true" class="wcff-drag-fields" href="#" value="<?php echo $field["id"]; ?>"><span><img src="<?php echo plugins_url('../assets/img/'.$field["id"].'.png', __FILE__ ); ?>"></span><?php echo $field["title"]; ?></a></li>
			<?php endforeach;?>								
		</ul>
	</div>
</div>


