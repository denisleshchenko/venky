<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

?>
	<?php if( !( is_home() || is_front_page() ) ) { ?>
		</div>
	</div> <!-- Store Vill Container -->
	<?php } ?>

</div><!-- #content -->

	<?php do_action( 'storevilla_before_footer' ); ?>
	
		<footer id="colophon" class="site-footer" role="contentinfo">

				<?php
					/**
					 * @hooked storevilla_footer_widgets - 10
					 * @hooked storevilla_credit - 20
					 * @hooked storevilla_payment_logo - 40
					 */
					do_action( 'storevilla_footer' ); 
				?>		
			
		</footer><!-- #colophon -->
		
	<?php do_action( 'storevilla_before_footer' ); ?>

    <a href="#" class="scrollup"><i class="fa fa-angle-up" aria-hidden="true"></i> </a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
