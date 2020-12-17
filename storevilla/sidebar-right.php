<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
*/

if ( is_active_sidebar( 'storevillasidebarone' ) ) : ?>
	<aside id="secondaryright" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'storevillasidebarone' ); ?>
	</aside><!-- #secondary -->
<?php endif;