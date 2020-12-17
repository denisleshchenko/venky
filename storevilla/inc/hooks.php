<?php

/**
 * Header action Area
 */

/**
 * Header
 * @see  storevilla_skip_links()
 * @see  storevilla_top_navigation()
 * @see  storevilla_header_widget_region()
 * @see  storevilla_site_branding()
 * @see  storevilla_advance_product_search()
 * @see  storevilla_header_cart()
 * @see  storevilla_primary_navigation()
 */
add_action( 'storevilla_header', 'storevilla_skip_links', 	  0 );
add_action( 'storevilla_header', 'storevilla_top_header', 10 );
add_action( 'storevilla_header', 'storevilla_button_header', 20 );
add_action( 'storevilla_header', 'storevilla_primary_navigation', 60 );


/**
 * Footer action Area
 */
 
/**
* Header
* @see  storevilla_footer_widgets()
* @see  storevilla_credit()
* @see  storevilla_payment_logo()
*/
 
add_action( 'storevilla_footer', 'storevilla_footer_widgets', 10 );
add_action( 'storevilla_footer', 'storevilla_credit', 20 );
add_action( 'storevilla_footer', 'storevilla_payment_logo', 40 );



/**
 * Main HomePage Section Function Area
**/
 
/**
* Header
* @see  storevilla_main_slider()
* @see  storevilla_main_widget()
* @see  storevilla_brand_logo()
*/
 
add_action( 'storevilla_homepage', 'storevilla_main_slider', 10 );
add_action( 'storevilla_homepage', 'storevilla_main_widget', 20 );
add_action( 'storevilla_homepage', 'storevilla_brand_logo', 30 );