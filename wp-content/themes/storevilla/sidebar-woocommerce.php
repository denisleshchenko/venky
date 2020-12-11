<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

if(is_singular()){
$post_sidebar =  get_theme_mod( 'storevilla_woocommerce_single_products_page_layout','rightsidebar' );
}else{
$post_sidebar =  get_theme_mod( 'storevilla_woocommerce_products_page_layout','rightsidebar' );
}

if( $post_sidebar == 'rightsidebar' && is_active_sidebar('storevillasidebarone')){
	?>
		<aside id="secondaryright" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'storevillasidebarone' ); ?>
		</aside><!-- #secondaryright -->
	<?php
}

if( $post_sidebar == 'leftsidebar' && is_active_sidebar('storevillasidebartwo')){
	?>
		<aside id="secondaryleft" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'storevillasidebartwo' ); ?>
		</aside><!-- #secondaryleft -->
	<?php
}

