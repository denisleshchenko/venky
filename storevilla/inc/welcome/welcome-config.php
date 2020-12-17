<?php
	/**
	 * Welcome Page Initiation
	*/

	get_template_part('/inc/welcome/welcome');

	/** Plugins **/
	$th_plugins = array(
		// *** Companion Plugins
		'companion_plugins' => array(

		),

		//Displays on Required Plugins tab
		'req_plugins' => array(

			// Free Plugins
			'free_plug' => array(

				'woocommerce' => array(
					'slug' => 'woocommerce',
					'filename' =>'woocommerce.php',
					'class' => 'WooCommerce',
				),

				'accesspress-social-icons' => array(
					'slug' => 'accesspress-social-icons',
					'filename' => 'accesspress-social-icons.php',
					'class' => 'APS_Class'
				),

				'accesspress-social-share' => array(
					'slug' => 'accesspress-social-share',
					'filename' => 'accesspress-social-share.php',
					'class' => 'APSS_Class'
				),

				'ap-custom-testimonial' => array(
					'slug' => 'ap-custom-testimonial',
					'filename' => 'ap-custom-testimonial.php',
					'class' => 'APCT_free'
				),

				'ap-mega-menu' => array(
					'slug' => 'ap-mega-menu',
					'filename' => 'ap-mega-menu.php',
					'class' => 'APMM_Class'
				),

			),
			'pro_plug' => array(

			),
		),

		// *** Displays on Import Demo section
		'required_plugins' => array(
			'access-demo-importer' => array(
					'slug' 		=> 'access-demo-importer',
					'name' 		=> esc_html__('Access Demo Importer', 'storevilla'),
					'filename' 	=>'access-demo-importer.php',
					'host_type' => 'wordpress', // Use either bundled, remote, wordpress
					'class' 	=> 'Access_Demo_Importer',
					'info' 		=> esc_html__('Access Demo Importer adds the feature to Import the Demo Conent with a single click.', 'storevilla'),
			),
		

		),

		// *** Recommended Plugins
		'recommended_plugins' => array(
			// Free Plugins
			'free_plugins' => array(

			),

			// Pro Plugins
			'pro_plugins' => array(

			)
		),
	);

	$strings = array(
		// Welcome Page General Texts
		'welcome_menu_text' => esc_html__( 'StoreVilla Welcome', 'storevilla' ),
		'theme_short_description' => esc_html__( 'StoreVilla is an elegantly designed free eCommerce WordPress theme. It is powered by the wonderful WooCommerce plugin and its extension to let you build a successful online store with an ease. It is a complete eCommerce package with all required features and functionalities. Its alluring and flexible design looks great on any kind of online stores such as fashion, jewelry, cosmetics, etc. The theme is inbuilt with cool customizer tool - you can easily configure the design, settings and other elements as per your wish. It features unlimited slider options, background configuration, advanced product search, multiple category display layout, 2 page layout, well organized homepage sections and much more. It\'s a perfect theme to start your online shop of any kind. Moreover, the theme comes with 10+ custom widgets - enhance the functionality of your eCommerce site and give your customers the best online shopping experience. All the WooCommerce settings are enabled in the theme - you can easily add any products in the store and feature it anywhere in any way. It is fully responsive, translation ready and SEO friendly theme. With StoreVilla - you can effortlessly create an online store of your dream, Demo: http://accesspressthemes.com/theme-demos/?theme=storevilla Support forum: https://accesspressthemes.com/support/forum/themes/free-themes/storevilla/', 'storevilla' ),

		// Plugin Action Texts
		'install_n_activate' 	=> esc_html__('Install and Activate', 'storevilla'),
		'deactivate' 			=> esc_html__('Deactivate', 'storevilla'),
		'activate' 				=> esc_html__('Activate', 'storevilla'),

		// Getting Started Section
		'doc_heading' 		=> esc_html__('Step 1 - Documentation', 'storevilla'),
		'doc_description' 	=> esc_html__('Read the Documentation and follow the instructions to manage the site , it helps you to set up the theme more easily and quickly. The Documentation is very easy with its pictorial  and well managed listed instructions. ', 'storevilla'),
		'doc_link'			=> 'https://doc.accesspressthemes.com/storevilla/',
		'doc_read_now' 		=> esc_html__( 'Read Now', 'storevilla' ),
		'cus_heading' 		=> esc_html__('Step 2 - Customizer Panel', 'storevilla'),
		'cus_read_now' 		=> esc_html__( 'Go to Customizer Panels', 'storevilla' ),

		// Recommended Plugins Section
		'pro_plugin_title' 			=> esc_html__( 'Premium Plugins', 'storevilla' ),
		'free_plugin_title' 		=> esc_html__( 'Free Plugins', 'storevilla' ),

		// Demo Actions
		'activate_btn' 		=> esc_html__('Activate', 'storevilla'),
		'installed_btn' 	=> esc_html__('Activated', 'storevilla'),
		'demo_installing' 	=> esc_html__('Installing Demo', 'storevilla'),
		'demo_installed' 	=> esc_html__('Demo Installed', 'storevilla'),
		'demo_confirm' 		=> esc_html__('Are you sure to import demo content ?', 'storevilla'),

		// Actions Required
		'req_plugin_info' => esc_html__('All these required plugins will be installed and activated while importing demo. Or you can choose to install and activate them manually. If you\'re not importing any of the demos, you must install and activate these plugins manually.', 'storevilla' ),
		'req_plugins_installed' => esc_html__( 'All Recommended action has been successfully completed.', 'storevilla' ),
		'customize_theme_btn' 	=> esc_html__( 'Customize Theme', 'storevilla' ),
		'pro_plugin_title' 			=> esc_html__( 'Premium Plugins', 'storevilla' ),
		'free_plugin_title' 		=> esc_html__( 'Free Plugins', 'storevilla' ),
	);

	/**
	 * Initiating Welcome Page
	*/
	$my_theme_wc_page = new Storevilla_Welcome( $th_plugins, $strings );