<?php 
/**
 * @author		: Saravana Kumar K
 * @author url  : iamsark.com
 * @copyright	: sarkware.com
 * Wcff option page renderer
 */
function wcff_style() {
    //wp_register_style( 'wcff-style', plugin_dir_url( __FILE__ ) . '../assets/css/wcff.css' );
    //wp_enqueue_style('wcff-style');
}
add_action( 'wp_enqueue_scripts', 'wcff_style' );
if( is_admin() ) {
    add_action( 'admin_init', 'wccpf_register_options' );
}

function wccpf_register_options() {
    register_setting( 'wccpf_options', 'wccpf_options' );
}

/* Wrapper class for getting wcff options */
class Wcff_Options {

	public function __construct() {}

	public function get_options() {
		$options = get_option( 'wccpf_options' );
		$options =  is_array( $options ) ? $options : array();
		return apply_filters( 'wcff_options', $options );

	}
}

?>