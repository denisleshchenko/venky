<?php
/**
 * StoreVilla Theme Customizer.
 *
 * @package Store_Villa
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function storevilla_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

/**
 * General Settings
*/
 $wp_customize->add_panel('storevilla_general_settings', array(
  'capabitity' => 'edit_theme_options',
  'priority' => 2,
  'title' => __('General Settings', 'storevilla')
));

 $wp_customize->get_section('title_tagline')->panel = 'storevilla_general_settings';
 $wp_customize->get_section('colors')->panel = 'storevilla_general_settings';
 $wp_customize->get_section('colors')->title = __( 'Themes Colors', 'storevilla' ); 
 $wp_customize->get_section('background_image')->panel = 'storevilla_general_settings'; 
 $wp_customize->get_section('static_front_page')->panel = 'storevilla_general_settings';
 $wp_customize->get_section('header_image')->panel = 'storevilla_general_settings';

 /*------------------------------------------------------------------------------------*/
  /**
   * Upgrade to enlighten Pro
  */
  // Register custom section types.
  $wp_customize->register_section_type( 'Storevilla_Customize_Section_Pro' );

  // Register sections.
  $wp_customize->add_section(
      new Storevilla_Customize_Section_Pro(
          $wp_customize,
          'uncode-pro',
          array(
              'title1'    => esc_html__( 'Free vs Pro', 'storevilla' ),
              'pro_text1' => esc_html__( 'Compare','storevilla' ),
              'pro_url1'  => admin_url('themes.php?page=welcome-page#free_vs_pro'),
              'priority' => 1,
          )
      )
  );

  $wp_customize->add_setting( 'storevilla_tpl_color', array( 'default' => '#0091D5', 'sanitize_callback' => 'sanitize_hex_color' ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'storevilla_tpl_color', array(
      'label'      => esc_html__( 'Template Color', 'storevilla' ),
      'section'    => 'colors',
      'settings'   => 'storevilla_tpl_color',
    ) ) 
  );

    $wp_customize->add_section( 'storevilla_header_options', array(
		'title'           =>      __('Header Options', 'storevilla'),
		'priority'        =>      '111',
    ));

    $wp_customize->add_setting('storevilla_top_header', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_top_header', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Top Header', 'storevilla'),
		'section' => 'storevilla_header_options',
		'settings' => 'storevilla_top_header',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));


    $wp_customize->add_section( 'storevilla_web_page_layout', array(
        'title'           =>      __('Web Page Layout Options', 'storevilla'),
        'priority'        =>      '111',
    ));

    $wp_customize->add_setting('storevilla_web_page_layout_options', array(
        'default' => 'disable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
    ));

    $wp_customize->add_control('storevilla_web_page_layout_options', array(
        'type' => 'radio',
        'label' => __('Enable / Disable Top Header', 'storevilla'),
        'section' => 'storevilla_web_page_layout',
        'settings' => 'storevilla_web_page_layout_options',
        'choices' => array(
         'enable' => __('Box Layout', 'storevilla'),
         'disable' => __('Full Width Layout', 'storevilla')
        )
    ));
	
	
	$wp_customize->add_setting('storevilla_top_left_options',  array(
        'default' =>  'nav',
        'sanitize_callback' => 'storevilla_top_header_sanitize'
    ));
    
    $wp_customize->add_control('storevilla_top_left_options', array(
        'section'       => 'storevilla_header_options',
        'label'         =>  __('Top Header Options', 'storevilla'),
        'type'          =>  'radio',
        'choices' => array(        
             'nav' => __('Top Navigation', 'storevilla'),
             'quickinfo'     => __('Quick Info', 'storevilla'),
           )
    ));
    
    /**
     * Select Our Features Icon
    */
    $wp_customize->add_setting( 'storevilla_email_icon', array(
        'default' => 'fa fa-envelope',
        'sanitize_callback' => 'storevilla_text_sanitize',
    ) );
    $wp_customize->add_control( new Storevilla_Customize_Icons_Control( $wp_customize, 'storevilla_email_icon', array(
          'type'    => 'storevilla_icons',                  
          'label'   => esc_html__( 'Select Features Icon', 'storevilla' ),
          'section' => 'storevilla_header_options',
          'setting' => 'storevilla_email_icon',
          'active_callback' => 'storevilla_top_header_optons',
    ) ) );
	
	  $wp_customize->add_setting('storevilla_email_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_email_title',array(
        'type' => 'text',
        'label' => __('Email Address', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_email_title',
        'active_callback' => 'storevilla_top_header_optons',
    ));

    /**
     * Select Our Features Icon
    */
    $wp_customize->add_setting( 'storevilla_phone_icon', array(
        'default' => 'fa fa-phone',
        'sanitize_callback' => 'storevilla_text_sanitize',
    ) );
    $wp_customize->add_control( new Storevilla_Customize_Icons_Control( $wp_customize, 'storevilla_phone_icon', array(
          'type'    => 'storevilla_icons',                  
          'label'   => esc_html__( 'Select Features Icon', 'storevilla' ),
          'section' => 'storevilla_header_options',
          'setting' => 'storevilla_phone_icon',
          'active_callback' => 'storevilla_top_header_optons',
    ) ) );
	
	  $wp_customize->add_setting('storevilla_phone_number', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_phone_number',array(
        'type' => 'text',
        'label' => __('Phone Number', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_phone_number',
        'active_callback' => 'storevilla_top_header_optons',
    ));
    
    /**
     * Select Our Features Icon
    */
    $wp_customize->add_setting( 'storevilla_address_icon', array(
        'default' => 'fa fa-map-marker',
        'sanitize_callback' => 'storevilla_text_sanitize',
    ) );
    $wp_customize->add_control( new Storevilla_Customize_Icons_Control( $wp_customize, 'storevilla_address_icon', array(
          'type'    => 'storevilla_icons',                  
          'label'   => esc_html__( 'Select Features Icon', 'storevilla' ),
          'section' => 'storevilla_header_options',
          'setting' => 'storevilla_address_icon',
          'active_callback' => 'storevilla_top_header_optons',
    ) ) );
	
	  $wp_customize->add_setting('storevilla_map_address', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_map_address',array(
        'type' => 'text',
        'label' => __('Address', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_map_address',
        'active_callback' => 'storevilla_top_header_optons',
    ));

    /**
     * Select Our Features Icon
    */
    $wp_customize->add_setting( 'storevilla_shop_open_icon', array(
        'default' => 'fa fa-clock-o',
        'sanitize_callback' => 'storevilla_text_sanitize',
    ) );
    $wp_customize->add_control( new Storevilla_Customize_Icons_Control( $wp_customize, 'storevilla_shop_open_icon', array(
          'type'    => 'storevilla_icons',                  
          'label'   => esc_html__( 'Select Features Icon', 'storevilla' ),
          'section' => 'storevilla_header_options',
          'setting' => 'storevilla_shop_open_icon',
          'active_callback' => 'storevilla_top_header_optons',
    ) ) );
	
	  $wp_customize->add_setting('storevilla_shop_open_time', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_shop_open_time',array(
        'type' => 'text',
        'label' => __('Shop Opening Time', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_shop_open_time',
        'active_callback' => 'storevilla_top_header_optons',
    )); 
	
	
	$wp_customize->add_section( 'storevilla_main_banner_area', array(
		'title'           =>      __('Main Banner Section Area', 'storevilla'),
		'priority'        =>      '111',
  ));

  $wp_customize->add_setting('storevilla_main_banner_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_main_banner_settings', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Main Banner Area', 'storevilla'),
		'section' => 'storevilla_main_banner_area',
		'settings' => 'storevilla_main_banner_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));

      /* Main Slider Category */
      $wp_customize->add_setting( 'storevilla_slider_team_id', array(
            'default' => '0',
            'sanitize_callback' => 'absint'
      ) );

      $wp_customize->add_control( new StoreVilla_Customize_Category_Control( $wp_customize, 'storevilla_slider_team_id', array(
          'label' => __( 'Select Slide Category', 'storevilla' ),
          'section' => 'storevilla_main_banner_area',
      ) ) );
		
	$wp_customize->add_section( 'storevilla_main_header_promo_area', array(
		'title'           =>      __('Header Promo Section Area', 'storevilla'),
		'priority'        =>      '112',
    ));

    $wp_customize->add_setting('storevilla_main_header_promo_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_main_header_promo_settings', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Main Header Promo Area', 'storevilla'),
		'section' => 'storevilla_main_header_promo_area',
		'settings' => 'storevilla_main_header_promo_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));
	
	
	$wp_customize->add_setting( 'storevilla_promo_area_one_image', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'storevilla_promo_area_one_image', array(
        'section'       =>      'storevilla_main_header_promo_area',
        'label'         =>      __('Upload Promo One Image', 'storevilla'),
        'type'          =>      'image',
    )));
    
    $wp_customize->add_setting('storevilla_promo_area_one_link', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_promo_area_one_link',array(
        'type' => 'text',
        'label' => __('Promo One Link', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_one_link',
    ));
    
    $wp_customize->add_setting( 'storevilla_promo_area_two_image', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'storevilla_promo_area_two_image', array(
        'section'       =>      'storevilla_main_header_promo_area',
        'label'         =>      __('Upload Promo Two Image', 'storevilla'),
        'type'          =>      'image',
    )));    
    
    $wp_customize->add_setting('storevilla_promo_area_two_link', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_promo_area_two_link',array(
        'type' => 'text',
        'label' => __('Promo Two Link', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_two_link',
    ));
	
	$imagepath =  get_template_directory_uri() . '/assets/images/';

    // Start of the WooCommerce Design Options
    $wp_customize->add_panel('storevilla_woocommerce_design_options', array(
      'capabitity' => 'edit_theme_options',
      'description' => __('Mange products and singel product page settings', 'storevilla'),
      'priority' => 113,
      'title' => __('WooCommerce Products Area', 'storevilla')
    ));

     
    // site archive layout setting
    $wp_customize->add_section('storevilla_woocommerce_products_settings', array(
      'priority' => 2,
      'title' => __('Products Pages Settings', 'storevilla'),
      'panel' => 'storevilla_woocommerce_design_options'
    ));

    $wp_customize->add_setting('storevilla_woocommerce_products_page_layout', array(
      'default' => 'rightsidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout'  //done
    ));

    $wp_customize->add_control(new Storevilla_Image_Radio_Control($wp_customize, 'storevilla_woocommerce_products_page_layout', array(
      'type' => 'radio',
      'label' => __('Select Products pages Layout', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_products_page_layout',
      'choices' => array( 
              'leftsidebar' => $imagepath.'left-sidebar.png',  
              'rightsidebar' => $imagepath.'right-sidebar.png', 
            )
    )));

    $wp_customize->add_setting('storevilla_woocommerce_product_row', array(
      'default' => '3',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout_row'  //done
    ));

    $wp_customize->add_control('storevilla_woocommerce_product_row', array(
      'type' => 'select',
      'label' => __('Select Products Pages Row', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_product_row',
      'choices' => array( 
              '2' => '2',  
              '3' => '3', 
              '4' => '4',
    )));

    $wp_customize->add_setting('storevilla_woocommerce_display_product_number', array(
      'default' => 12,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_number_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_display_product_number', array(
      'type' => 'number',
      'label' => __('Enter Products Display Per Page', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_display_product_number'
    ));

    

    // WooCommerce Singel Product Page Settings
    $wp_customize->add_section('storevilla_woocommerce_single_products_page_settings', array(
      'priority' => 2,
      'title' => __('Single Products Page Settings', 'storevilla'),
      'panel' => 'storevilla_woocommerce_design_options'
    ));

    $wp_customize->add_setting('storevilla_woocommerce_single_products_page_layout', array(
      'default' => 'rightsidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout'  //done
    ));

    $wp_customize->add_control(new Storevilla_Image_Radio_Control($wp_customize, 'storevilla_woocommerce_single_products_page_layout', array(
      'type' => 'radio',
      'label' => __('Select Single Products Page Layout', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_single_products_page_layout',
      'choices' => array( 
              'leftsidebar' => $imagepath.'left-sidebar.png',  
              'rightsidebar' => $imagepath.'right-sidebar.png', 
            )
    )));
    
    $wp_customize->add_setting('storevilla_woocommerce_singel_product_page_upsell_title', array(
      'default' => 'Up Sell Products',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_text_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_singel_product_page_upsell_title', array(
      'type' => 'text',
      'label' => __('Enter Up Sell Title', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_singel_product_page_upsell_title'
    ));


    $wp_customize->add_setting('storevilla_woocommerce_product_page_related_title', array(
      'default' => 'Related Products',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_text_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_product_page_related_title', array(
      'type' => 'text',
      'label' => __('Enter Related Products Title', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_product_page_related_title'
    ));
    
    
    
    $wp_customize->add_section( 'storevilla_brands_logo_area', array(
		'title'           =>      __('Brands Logo Section Area', 'storevilla'),
		'priority'        =>      '114',
    ));
    
    $wp_customize->add_setting('storevilla_brands_area_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_brands_area_settings', array(
		'type' => 'radio',
		'label' => __('Options Enable/Disable Brands Loga Area', 'storevilla'),
		'section' => 'storevilla_brands_logo_area',
		'settings' => 'storevilla_brands_area_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));
    
    $wp_customize->add_setting('storevilla_brands_top_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_brands_top_title',array(
        'type' => 'text',
        'label' => __('Brands Top Title', 'storevilla'),
        'section' => 'storevilla_brands_logo_area',
        'setting' => 'storevilla_brands_top_title',
    ));
    
    $wp_customize->add_setting('storevilla_brands_main_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control('storevilla_brands_main_title',array(
        'type' => 'text',
        'label' => __('Brands Main Title', 'storevilla'),
        'section' => 'storevilla_brands_logo_area',
        'setting' => 'storevilla_brands_main_title',
    ));
	
    $wp_customize->add_setting('storevilla_brands_logo', array(
      'default'     => '',
      'sanitize_callback' => 'storevilla_sanitize_text'
    ) );

    $wp_customize->add_control( new StoreVilla_Customize_Multi_Image( $wp_customize, 'storevilla_brands_logo', array(
      'settings'    => 'storevilla_brands_logo',
      'section'   => 'storevilla_brands_logo_area',
      'label'     => __( 'Upload Brands Logos', 'storevilla' ),
    ) ) );	  
    
	
	$wp_customize->add_section( 'storevilla_copyright', array(
		'title'           =>      __('Copyright Message Section', 'storevilla'),
		'priority'        =>      '116',
    ));

    $wp_customize->add_setting('storevilla_footer_copyright', array(
         'default' => '',
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'esc_textarea'  //done
    ));

	$wp_customize->add_control('storevilla_footer_copyright', array(
	 'type' => 'textarea',
	 'label' => __('Copyright', 'storevilla'),
	 'section' => 'storevilla_copyright',
	 'settings' => 'storevilla_footer_copyright'
	));

	// Payment Logo Section    
    $wp_customize->add_section( 'paymentlogo_images', array(
		'title'           =>      __('Payment Logo Section', 'storevilla'),
		'priority'        =>      '117',
    ));
    
    $wp_customize->add_setting( 'paymentlogo_image_one', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_one', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_two', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_two', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_three', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_three', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_four', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'   // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_four', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_five', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'   // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_five', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_six', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));
   
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_six', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));


    function storevilla_checkbox_sanitize($input) {
      if ( $input == 1 ) {
         return 1;
      } else {
         return 0;
      }
    }

    function storevilla_radio_enable_disable_sanitize($input) {
       $valid_keys = array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }
    }
    
    function storevilla_top_header_sanitize($input) {
       $valid_keys = array(
         'nav' => __('Top Navigation', 'storevilla'),
         'quickinfo'     => __('Quick Info', 'storevilla'),
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }
    }
       

    function storevilla_text_sanitize( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    function storevilla_radio_sanitize_layout($input) {
        $imagepath =  get_template_directory_uri() . '/images/';
        $valid_keys = array(
         'leftsidebar' => $imagepath.'left-sidebar.png',  
         'rightsidebar' => $imagepath.'right-sidebar.png',
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
        } else {
         return '';
        }
    }

    function storevilla_radio_sanitize_layout_row($input) {
      $valid_keys = array(
          '2' => '2',  
          '3' => '3', 
          '4' => '4',
      );
      if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
      } else {
         return '';
      }
    }

    function storevilla_number_sanitize( $int ) {
        return absint( $int );
    }
    
    function storevilla_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }
    
    
    function storevilla_top_header_optons(){
     $header_optons = get_theme_mod('storevilla_top_left_options');
       if( $header_optons == 'quickinfo') {
          return true;
       }
     return false;
    }
    
}
add_action( 'customize_register', 'storevilla_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
**/
function storevilla_customize_preview_js() {
	wp_enqueue_script( 'storevilla_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'storevilla_customize_preview_js' );