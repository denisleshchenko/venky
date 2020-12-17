<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Store_Villa
 */

if ( ! function_exists( 'storevilla_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function storevilla_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s : posted date */
		esc_html_x( 'Posted on %s', 'post date', 'storevilla' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		/* translators: %s : author */
		esc_html_x( 'by %s', 'post author', 'storevilla' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'storevilla_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function storevilla_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'storevilla' ) );
		if ( $categories_list && storevilla_categorized_blog() ) {
			/* translators: %1$s : category lists */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'storevilla' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'storevilla' ) );
		if ( $tags_list ) {
			/* translators: %1$s : taglists */
			printf( '<span class="tags-links">' . wp_kses(__( 'Tagged %1$s', 'storevilla' ), array( 'a' => array( 'href' => array(), 'rel' => array() ) ) ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'storevilla' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'storevilla' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function storevilla_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'storevilla_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'storevilla_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so storevilla_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so storevilla_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in storevilla_categorized_blog.
 */
function storevilla_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'storevilla_categories' );
}
add_action( 'edit_category', 'storevilla_category_transient_flusher' );
add_action( 'save_post',     'storevilla_category_transient_flusher' );


/**
 * StoreVilla Custom Function Section.
 */
 
 
/**
* Header Section Function Area
*/

if ( ! function_exists( 'storevilla_skip_links' ) ) {
	/**
	 * Skip links
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_skip_links() {
		?>
			<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e( 'Skip to navigation', 'storevilla' ); ?></a>
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'storevilla' ); ?></a>
		<?php
	}
}

if ( ! function_exists( 'storevilla_top_header' ) ) {
	/**
	 * Display Top Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_top_header() {
		$top_header = get_theme_mod('storevilla_top_header','enable');
		$header_options = get_theme_mod('storevilla_top_left_options','nav');
		// Quick Info
			$emial_icon = esc_attr ( get_theme_mod('storevilla_email_icon') ) ;
			$email_address = sanitize_email( get_theme_mod('storevilla_email_title') );
			
			$phone_icon = esc_attr ( get_theme_mod('storevilla_phone_icon') );
			$phone_number = esc_attr ( get_theme_mod('storevilla_phone_number') );
			$phone_num = preg_replace("/[^0-9]/","",$phone_number);
			
			$map_address_iocn = esc_attr ( get_theme_mod('storevilla_address_icon') );
			$map_address = wp_kses_post( get_theme_mod('storevilla_map_address') );
			
			$shop_open_icon = esc_attr ( get_theme_mod('storevilla_shop_open_icon') );
			$shop_open_time = esc_attr ( get_theme_mod('storevilla_shop_open_time') );
			
		if( !empty( $top_header ) && $top_header == 'enable' ) {
			?>
				<div class="top-header">
					
					<div class="store-container clearfix">
						
						<?php  
							if( !empty( $header_options ) && $header_options == 'nav' ) { ?>
							<nav class="top-navigation" role="navigation"><?php  wp_nav_menu( array( 'theme_location'	=> 'storevillatopmenu', 'depth' => 1 ) ); ?> </nav>
							<?php }else{
						?>
							<ul class="store-quickinfo">
									
								<?php if(!empty( $email_address )) { ?>
									
				                    <li>
				                    	<span class="fa <?php if(!empty( $emial_icon )) { echo esc_attr($emial_icon); } ?>">&nbsp;</span>
				                    	<a href="mailto:<?php echo esc_html($email_address); ?>"><?php echo esc_html($email_address); ?></a>
				                    </li>
			                    <?php }  ?>
			                    
			                    <?php if(!empty( $phone_number )) { ?>
									
				                    <li>
				                    	<span class="fa <?php if(!empty( $phone_icon )) { echo esc_attr($phone_icon); } ?>">&nbsp;</span>
				                    	<a href="tel:<?php echo esc_html($phone_num); ?>"><?php echo esc_html($phone_number); ?></a>
				                    </li>
			                    <?php }  ?>
			                    
			                    <?php if(!empty( $map_address )) { ?>
									
				                    <li>
				                    	<span class="fa <?php if(!empty( $map_address_iocn )) { echo esc_attr($map_address_iocn); } ?>">&nbsp;</span>
				                    	<?php echo esc_html($map_address); ?>
				                    </li>
			                    <?php }  ?>
			                    
			                    <?php if(!empty( $shop_open_time )) { ?>
									
				                    <li>
				                    	<span class="fa <?php if(!empty( $shop_open_icon )) { echo esc_attr($shop_open_icon); } ?>">&nbsp;</span>
				                    	<?php echo esc_html($shop_open_time); ?>
				                    </li>
			                    <?php }  ?>
			                    
							</ul>
			                  
						<?php
							}
						?>
						
						<!-- Top-navigation -->
						
						<div class="top-header-regin">						
								
	                		<ul class="site-header-cart menu">

    							<?php if (is_user_logged_in()) { if ( storevilla_is_woocommerce_activated() ) { ?>	
    						        <li class="my_account_wrapper">
    									<a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" title="<?php esc_attr_e('My Account','storevilla');?>">
    										<?php esc_html_e('My Account','storevilla'); ?>
    									</a>
    								</li>
    							<?php } ?>
    								<li>
    				                    <a class="sv_logout" href="<?php echo esc_url(wp_logout_url( home_url() )); ?>">
    				                        <?php esc_html_e(' Logout', 'storevilla'); ?>
    				                    </a>
    			                    </li>    			
    			                <?php } else { if ( storevilla_is_woocommerce_activated() ) { ?>    			
    			                	<li>
    				                    <a class="sv_login" href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">
    				                        <?php esc_html_e('Login / Register', 'storevilla'); ?>
    				                    </a>
    			                    </li>
    			                <?php } }  ?>

	                			<?php
					                if (function_exists('YITH_WCWL')) {
					                $wishlist_url = YITH_WCWL()->get_wishlist_url();
				            	?>
				                    <li>
					                    <a class="quick-wishlist" href="<?php echo esc_url($wishlist_url); ?>" title="<?php esc_attr_e( 'Wishlist', 'storevilla' ); ?>">
					                        <?php esc_html_e('Wishlist','storevilla'); ?><?php echo "(" . absint(yith_wcwl_count_products()) . ")"; ?>
					                    </a>
				                    </li>

					            <?php } if ( storevilla_is_woocommerce_activated() ) { ?>
								
	                			<li>	                				
	                				<?php 
	                					storevilla_cart_link();
	                				 	the_widget( 'WC_Widget_Cart', 'title=' );
	                				?>
	                			</li>

	                			<?php }   if ( storevilla_is_woocommerce_activated() ) { 
					            	if ( is_active_sidebar( 'storevillaheaderone' ) ) { ?>
										<li>
											<div class="header-widget-region" role="complementary">
												<?php dynamic_sidebar( 'storevillaheaderone' ); ?>
											</div>
										</li>
								<?php } } ?>

	                		</ul>								
					          
						</div>
						
					</div>
					
				</div>
			<?php
		}
	}
}


if ( ! function_exists( 'storevilla_button_header' ) ) {
	/**
	 * Display Site Branding
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_button_header() { ?>	
		<div class="header-wrap clearfix">
			<div class="store-container">
				<div class="site-branding">
					<?php
						if ( function_exists( 'the_custom_logo' ) ) {
							the_custom_logo();
						}
					?>
					<div class="sv-logo-wrap">
						<div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
						<?php
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) : 
						?>
						<p class="site-description"><?php echo esc_html($description); ?></p>
						<?php endif; ?>
					</div>				
				</div><!-- .site-branding -->
				<div class="search-cart-wrap clearfix">
				<?php
				
				/**
				 * Display Product Search
				 * @since  1.0.0
				 * @uses  storevilla_is_woocommerce_activated() check if WooCommerce is activated
				 * @return void
				 */
					 
					if ( storevilla_is_woocommerce_activated() ) { ?>
						<div class="advance-search">
							<?php echo storevilla_product_search(); ?>
						</div>
					<?php } else{ ?>
						<div class="normal-search">
							<?php get_search_form(); ?>
						</div>
					<?php } ?>		
				
				</div>	
			</div>
		</div>
	
	<?php
	}
}


if ( ! function_exists( 'storevilla_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation">
			<div class="store-container clearfix">
				<div class="menu-toggle" aria-controls="primary-navigation" tabindex="0">
					<span></span>
				</div>
				<?php
					wp_nav_menu(
						array(
							'theme_location'	=> 'storevillaprimary',
							'menu_id' => 'primary-menu',
							'container_class'	=> 'primary-navigation',
						)
					);
				?>
			</div>
		</nav><!-- #site-navigation -->
		<?php
	}
}


/**
 * Footer Section Function Area
 */

if ( ! function_exists( 'storevilla_footer_widgets' ) ) {
	/**
	 * Display the theme quick info
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_footer_widgets() {
		
			if ( is_active_sidebar( 'storevillafooter-5' ) ) {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 5 );
			} elseif ( is_active_sidebar( 'storevillafooter-4' ) ) {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 4 );
			} elseif ( is_active_sidebar( 'storevillafooter-3' ) ) {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 3 );
			} elseif ( is_active_sidebar( 'storevillafooter-2' ) ) {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 2 );
			} elseif ( is_active_sidebar( 'storevillafooter-1' ) ) {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 1 );
			} else {
				$widget_columns = apply_filters( 'storevilla_footer_widget_regions', 0 );
			}
	
			if ( $widget_columns > 0 ) : ?>
	
				<section class="footer-widgets col-<?php echo intval( $widget_columns ); ?> clearfix">
					
					<div class="top-footer-wrap">

						<div class="store-container">

							<?php $i = 0; while ( $i < $widget_columns ) : $i++; ?>
			
								<?php if ( is_active_sidebar( 'storevillafooter-' . $i ) ) : ?>
			
									<section class="block footer-widget-<?php echo intval( $i ); ?>">
							        	<?php dynamic_sidebar( 'storevillafooter-' . intval( $i ) ); ?>
									</section>
			
						        <?php endif; ?>
			
							<?php endwhile; 

							 ?>

						</div>
						<?php 
						if ( is_active_sidebar( 'storevillaquickinfo' ) ) { ?>		
								<div class="footer-quick-info" role="complementary">				
									<?php dynamic_sidebar( 'storevillaquickinfo' ); ?>				
								</div>			
							<?php }?>

					</div>
	
				</section><!-- .footer-widgets  -->
	
		<?php endif;
	}
}

 
if ( ! function_exists( 'storevilla_credit' ) ) {
	/**
	 * Display the theme credit
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_credit() {
		?>
		
		<div class="bottom-footer-wrap clearfix">

			<div class="store-container">

				<div class="site-info">
					<?php $copyright = get_theme_mod( 'storevilla_footer_copyright' ); 
					if( !empty( $copyright ) ) { ?>
						<?php echo  wp_kses_post($copyright) ; ?>	
					<?php } else { ?>
						<?php echo ('&copy; ' . date_i18n( 'Y' ) ); ?>
					<?php }

					$designer_link = 'https://accesspressthemes.com/wordpress-themes/storevilla/';
					/* translators: %s : designer link */
					printf( wp_kses(__( ' WordPress Theme: %s ', 'storevilla' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), '<a href=" ' . esc_url( $designer_link ) . ' " target="_blank">StoreVilla</a>' ); ?>
				</div><!-- .site-info -->
		<?php
	}
}


if ( ! function_exists( 'storevilla_payment_logo_area' ) ) {
	/**
	 * Display the theme payment logo
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_payment_logo_area() {
		?>
				<div class="site-payment-logo">
					<?php storevilla_payment_logo(); ?>
				</div>

			</div>
			
		</div>
		<?php
	}
}

/**
 * Main HomePage Section Function Area
 */
 
if ( ! function_exists( 'storevilla_main_slider' ) ) {
	/**
	 * Display the banner slider
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_main_slider() {
		
			$slider_options = get_theme_mod( 'storevilla_main_banner_settings','enable' );
			
			if(!empty( $slider_options ) && $slider_options == 'enable' ){
		?>
			<div class="store-villa-banner clearfix">
				<div class="store-container">
					<div class="slider-wrapper">
						<ul id="store-gallery" class="store-gallery cS-hidden">
							<?php
							    $slider_cat_id = intval( get_theme_mod( 'storevilla_slider_team_id', '0' ));
							    if( !empty( $slider_cat_id ) ) {
							    $slider_args = array(
							        'post_type' => 'post',
							        'tax_query' => array(
							            array(
							                'taxonomy'  => 'category',
							                'field'     => 'id', 
							                'terms'     => $slider_cat_id                                                                 
							            )),
							        'posts_per_page' => 8
							    );

							    $slider_query = new WP_Query( $slider_args );
							    if( $slider_query->have_posts() ) { while( $slider_query->have_posts() ) { $slider_query->the_post();
							    $image_path = wp_get_attachment_image_src( get_post_thumbnail_id(), 'storevilla-slider-image', true );                           
							?>
							<li class="banner-slider">
								<img src="<?php echo esc_url($image_path[0]); ?>" alt="<?php the_title(); ?>"/>
								<div class="banner-slider-info">
									<h2 class="caption-title">										
										<?php
										    $slider_title = esc_attr( get_the_title() );
										    $slider_title = explode(" ", $slider_title);
										    $new1 = array_shift($slider_title);
										    $new2 = array_shift($slider_title);
										    $content = implode(' ', $slider_title);
										    echo '<span>'.esc_html($new1). ' '.  esc_html($new2).'</span>'. esc_html($content);
										?>
									</h2>
									<div class="caption-content">
										<?php echo esc_html(wp_trim_words( get_the_content(), 10)); ?>
									</div>
									<a class="slider-button" href="<?php the_permalink(); ?>">
										<?php esc_html_e('View More','storevilla'); ?>
									</a>								
								</div>
							</li>
							<?php  } } wp_reset_postdata();  } ?> 
						</ul>
					</div>
					<?php storevilla_promo_area(); ?>
				</div>
			</div>
		<?php
			}
	}
}


if ( ! function_exists( 'storevilla_main_widget' ) ) {
	/**
	 * Display all product and category widget
	 * @since  1.0.0
	 * @return void
	 */
	function storevilla_main_widget() {
		?>
			<div class="main-widget-wrap">
				<?php 
					if ( is_active_sidebar( 'storevillamainwidget' ) ) { 
						
						dynamic_sidebar( 'storevillamainwidget' ); 
						
					}
				?>
			</div>
		<?php
	}
}

if ( ! function_exists( 'storevilla_brand_logo' ) ) {
	function storevilla_brand_logo() {
			$brands_options = get_theme_mod ( 'storevilla_brands_area_settings','enable' );
			$brand_top_title = get_theme_mod( 'storevilla_brands_top_title' );
			$brand_main_title = get_theme_mod( 'storevilla_brands_main_title' );
			
			if(!empty( $brands_options ) && $brands_options == 'enable' ){
		?>
			<div class="brand-logo-wrap">
				<div class="store-container">
					<div class="block-title">
	                    <?php if( !empty( $brand_top_title ) ) { ?><span><?php echo esc_html( $brand_top_title ); ?></span> <?php } ?>
	                    <?php if( !empty( $brand_main_title ) ) { ?><h2><?php echo esc_html( $brand_main_title ); ?></h2> <?php } ?>
	                </div>
	                <ul id="brands-logo" class="brands-logo cS-hidden">
						<?php 
							$storevilla_brands_logo = get_theme_mod('storevilla_brands_logo');
							$storevilla_brands_logo = explode(',', $storevilla_brands_logo);
							foreach ($storevilla_brands_logo as $storevilla_brands_logo_single) {
								$image = wp_get_attachment_image_src( $storevilla_brands_logo_single, 'full');
						?>
							<li>
								<img src="<?php echo esc_url( $image[0] ); ?>" />
							</li>
						<?php } ?>						
					</ul>
				</div>				
			</div>			
		<?php
			}
	}
}