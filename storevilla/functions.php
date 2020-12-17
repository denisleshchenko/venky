<?php
/**
 * StoreVilla functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Store_Villa
 */

if ( ! function_exists( 'storevilla_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function storevilla_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Store Villa, use a find and replace
	 * to change 'storevilla' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'storevilla', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('storevilla-cat-image', 275, 370, true);
	add_image_size('storevilla-blog-grid', 255, 160, true);
	add_image_size('storevilla-blog-image', 1170, 470, true);
	add_image_size('storevilla-slider-image', 760, 510, true);		

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'storevillatopmenu' => esc_html__( 'Top Menu', 'storevilla' ),
		'storevillaprimary' => esc_html__( 'Primary Menu', 'storevilla' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );
	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'storevilla_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Editor style.
	add_editor_style( 'assets/css/editor-style.css' );

	/*
	 * Enable support for custom logo.
	 */
	add_image_size( 'storevilla-logo', 350, 175 );
	add_theme_support( 'custom-logo', array( 'size' => 'storevilla-logo' ) );
}
endif;
add_action( 'after_setup_theme', 'storevilla_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function storevilla_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'storevilla_content_width', 640 );
}
add_action( 'after_setup_theme', 'storevilla_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function storevilla_widgets_init() {
	
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar Widget Area', 'storevilla' ),
		'id'            => 'storevillasidebarone',
		'description'   => esc_html__( 'Add widgets here.', 'storevilla' ),
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar Widget Area', 'storevilla' ),
		'id'            => 'storevillasidebartwo',
		'description'   => esc_html__( 'Add widgets here.', 'storevilla' ),
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	
	register_sidebar( array(
		'name'          => esc_html__( 'Top Right Header Widget Area', 'storevilla' ),
		'id'            => 'storevillaheaderone',
		'description'   => esc_html__( 'Add languages currency widgets here.', 'storevilla' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'SV : Main Block Widget Area', 'storevilla' ),
		'id'            => 'storevillamainwidget',
		'description'   => esc_html__( 'Add widgets here.', 'storevilla' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	$footer_widget_regions = apply_filters( 'storevilla_footer_widget_regions', 5 );
	
	for ( $i = 1; $i <= intval( $footer_widget_regions ); $i++ ) {
		
		register_sidebar( array(
			/* translators: %d : fooer id counter */
			'name' 				=> sprintf( __( 'Footer Widget Area %d', 'storevilla' ), $i ),
			'id' 				=> sprintf( 'storevillafooter-%d', $i ),
			/* translators: %d : footer id counter */
			'description' 		=> sprintf( __( ' Add Widgetized Footer Region %d.', 'storevilla' ), $i ),
			'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h3 class="widget-title">',
			'after_title' 		=> '</h3>',
		) );
	}
	

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Quick Information Area', 'storevilla' ),
		'id'            => 'storevillaquickinfo',
		'description'   => esc_html__( 'Add quick contact information widgets here.', 'storevilla' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'storevilla_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function storevilla_scripts() {

	/*----------------- Google Fonts --------------------------------------*/
	$storevilla_font_args = array(
        'family' => 'Open+Sans:400,300,400,600,600,700|Lato:400,300,300,400,700',
    );
    wp_enqueue_style('google-fonts', add_query_arg( $storevilla_font_args, "//fonts.googleapis.com/css" ) );

	/*------------------- CSS Style ---------------------------------*/
	
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/fontawesome/css/font-awesome.css');
	
	wp_enqueue_style( 'lightslider', get_template_directory_uri() . '/assets/css/lightslider.css');

    wp_enqueue_style( 'linearicons', get_template_directory_uri() . '/assets/linearicons/style.css');

	wp_enqueue_style( 'storevilla-style', get_stylesheet_uri() );

	wp_enqueue_style( 'storevilla-responsive', get_template_directory_uri() . '/assets/css/responsive.css');
	wp_enqueue_style( 'storevilla-keyboard', get_template_directory_uri() . '/assets/css/keyboard.css' );

	/*------------------- JavaScript ---------------------------------------*/
	$storevilla_theme = wp_get_theme();
    $theme_version = $storevilla_theme->get( 'Version' );

 	wp_enqueue_script( 'lightslider', get_template_directory_uri() . '/assets/js/lightslider.js', array(), NULL, true );

	wp_enqueue_script( 'storevilla-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), esc_attr( $theme_version ), true );

	wp_enqueue_script( 'storevilla-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), esc_attr( $theme_version ), true );
	
	wp_enqueue_script( 'retina', get_template_directory_uri() . '/assets/js/retina.js', array('jquery'), esc_attr( $theme_version ), true );

	wp_enqueue_script( 'storevilla-common', get_template_directory_uri() . '/assets/js/common.js', array('jquery'), esc_attr( $theme_version ), true );
	
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'storevilla_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 */
function storevilla_editor_styles() {
	$storevilla_font_args = array(
        'family' => 'Open+Sans:400,300,400,600,600,700|Lato:400,300,300,400,700',
    );
    wp_enqueue_style( 'storevilla-lite-google-fonts', add_query_arg( $storevilla_font_args, "//fonts.googleapis.com/css" ) );

    wp_enqueue_style( 'storevilla-lite-editor-style', get_template_directory_uri() . '/assets/css/editor-style.css' );
}
add_action( 'enqueue_block_editor_assets', 'storevilla_editor_styles' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load hooks file.
*/
require get_template_directory() . '/inc/hooks.php';


/**
 ** Load storevilla widget section.
*/
require ( get_template_directory() . '/inc/storevilla-widget.php' );

/**
 * Load fontawesome fonts value
*/
require ( get_template_directory() . '/inc/storevilla-fontawesome.php' );

/**
 * Dynamic Styles
*/
require(get_template_directory() . '/assets/css/style.php' );

/**
 * Load fontawesome fonts value
*/
require ( get_template_directory() . '/inc/welcome/welcome-config.php' );