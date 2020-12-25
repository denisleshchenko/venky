<?php

function prefix_after_cart_item_name( $cart_item, $cart_item_key ) {
 $notes = isset( $cart_item['notes'] ) ? $cart_item['notes'] : '';
 printf(
 '<div><textarea class="%s" id="cart_notes_%s" data-cart-id="%s" placeholder="Примечание. Например: 5шт красных, 3 сиреневых и 2 бордовых">%s</textarea></div>',
 'prefix-cart-notes',
 $cart_item_key,
 $cart_item_key,
 $notes
 );
}
add_action( 'woocommerce_after_cart_item_name', 'prefix_after_cart_item_name', 10, 2 );

/**
 * Enqueue our JS file
 */
function prefix_enqueue_scripts() {
 //wp_register_script( 'prefix-script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'update-cart-item-ajax.js', array( 'jquery-blockui' ), time(), true );
 wp_localize_script(
 'myscript',
 'prefix_vars',
 array(
 'ajaxurl' => admin_url( 'admin-ajax.php' )
 )
 );
 wp_enqueue_script( 'myscript' );
}
add_action( 'wp_enqueue_scripts', 'prefix_enqueue_scripts' );


function prefix_update_cart_notes() {
 // Do a nonce check
 if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'woocommerce-cart' ) ) {
 wp_send_json( array( 'nonce_fail' => 1 ) );
 exit;
 }
 // Save the notes to the cart meta
 $cart = WC()->cart->cart_contents;
 $cart_id = $_POST['cart_id'];
 $notes = $_POST['notes'];
 $cart_item = $cart[$cart_id];
 $cart_item['notes'] = $notes;
 WC()->cart->cart_contents[$cart_id] = $cart_item;
 WC()->cart->set_session();
 wp_send_json( array( 'success' => 1 ) );
 exit;
}
add_action( 'wp_ajax_prefix_update_cart_notes', 'prefix_update_cart_notes' );

function prefix_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
 foreach( $item as $cart_item_key=>$cart_item ) {
 if( isset( $cart_item['notes'] ) ) {
 $item->add_meta_data( 'notes', $cart_item['notes'], true );
 }
 }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'prefix_checkout_create_order_line_item', 10, 4 );

