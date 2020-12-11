<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storevilla_html_tag_schema(); ?> >
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <div id="page" class="hfeed site">

            <?php //do_action( 'storevilla_before_header' );  ?>

            <header id="masthead" class="site-header" <?php //if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . '); background-size:cover;"'; }   ?>>


                <div class="top-header">					
                    <div class="store-container clearfix">						
                        <nav id="site-navigation" class="main-navigation">
                            <div class="store-container clearfix">
                                <div class="menu-toggle" aria-controls="primary-navigation">
                                    <span></span>
                                </div>
                                <?php
                                wp_nav_menu(
                                        array(
                                            'theme_location' => 'storevillaprimary',
                                            'menu_id' => 'primary-menu',
                                            'container_class' => 'primary-navigation',
                                        )
                                );
                                ?>



                            </div>
                        </nav>
                        <div class="top-header-regin">						

                            <ul class="site-header-cart menu">

                                <?php if (is_user_logged_in()) {
                                    if (storevilla_is_woocommerce_activated()) {
                                        ?>	
                                        <li class="my_account_wrapper">
                                            <a href="<?php //echo get_permalink(get_option('woocommerce_myaccount_page_id'));  ?>" title="<?php _e('My Account', 'storevilla'); ?>">
        <?php //_e('My Account','storevilla');   ?>
                                            </a>
                                        </li>
    <?php } ?>
                                    <li>
                                        <a class="sv_logout" href="<?php echo wp_logout_url(home_url()); ?>">
    <?php //_e(' Logout', 'storevilla');   ?>
                                        </a>
                                    </li>    			
                                <?php } else {
                                    if (storevilla_is_woocommerce_activated()) {
                                        ?>    			
                                        <li>
                                            <a class="sv_login" href="<?php //echo get_permalink(get_option('woocommerce_myaccount_page_id'));   ?>">
                                        <?php //_e('Login / Register', 'storevilla');  ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                                ?>

                                    <?php if (storevilla_is_woocommerce_activated()) { ?>

                                    <li>	                				
                                        <?php
                                        storevilla_cart_link();
                                        the_widget('WC_Widget_Cart', 'title=');
                                        ?>
                                    </li>

<?php
} if (storevilla_is_woocommerce_activated()) {
    if (is_active_sidebar('storevillaheaderone')) {
        ?>
                                        <li>
                                            <div class="header-widget-region" role="complementary">
                                        <?php dynamic_sidebar('storevillaheaderone'); ?>
                                            </div>
                                        </li>
    <?php }
}
?>

                            </ul>								

                        </div>
                        <!-- Top-navigation -->



                    </div>

                </div>



                <?php
                /**
                 * @see  storevilla_skip_links() - 0
                 * @see  storevilla_top_header() - 10
                 * *@see storevilla_top_nav (filter for top header navigation)
                 * @see  storevilla_button_header() - 20
                 * @see  storevilla_primary_navigation() - 30
                 */
                //do_action( 'storevilla_header' ); 
                ?>
            </header><!-- #masthead -->

                        <?php do_action('storevilla_after_header'); ?>

            <div id="content" class="site-content">
<?php if (!( is_home() || is_front_page() )) { ?>
                    <div class="store-container clearfix">
                        <div class="store-container-inner clearfix">
    <?php
} 