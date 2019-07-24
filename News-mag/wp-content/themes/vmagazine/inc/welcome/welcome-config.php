<?php
	/**
	 * Welcome Page Initiation
	*/

	include get_template_directory() . '/inc/welcome/welcome.php';

	/** Plugins **/
	$plugins = array(
		// *** Companion Plugins
		'companion_plugins' => array(

			'vmagazine-companion' => array(
				'slug' 		=> 'vmagazine-companion',
				'name' 		=> esc_html__('Vmagazine Companion', 'vmagazine'),
				'filename' 	=> 'vmagazine-companion.php',
				'class' 	=> 'Vmagazine_Companion',
				'host_type' => 'bundled', // Use either bundled, remote, wordpress
				'location' 	=> get_template_directory().'/inc/welcome/plugins/vmagazine-companion.zip',
				'info' 		=> esc_html__('This plugin is required to make the theme work properly', 'vmagazine'),
			),

			//vmagazine elementor addons
			'vmagazine-elementor-addons' => array(
				'slug' 		=> 'vmagazine-elementor-addons',
				'name' 		=> esc_html__('Vmagazine Elementor Addons', 'vmagazine'),
				'filename' 	=> 'vmagazine-elementor-addons.php',
				'class' 	=> 'VMagazine_EA',
				'host_type' => 'bundled', // Use either bundled, remote, wordpress
				'location' 	=> get_template_directory().'/inc/welcome/plugins/vmagazine-elementor-addons.zip',
				'info' 		=> esc_html__('This plugin adds custom made elementor elements', 'vmagazine'),
			),

			
		),

		// *** Required Plugins
		'required_plugins' => array(
			'access-demo-importer' => array(
				'slug' 		=> 'access-demo-importer',
				'name' 		=> esc_html__('Access Demo Importer', 'vmagazine'),
				'filename' 	=>'access-demo-importer.php',
				'host_type' => 'bundled', // Use either bundled, remote, wordpress
				'class' 	=> 'PDI_Importer',
				'location' 	=> get_template_directory().'/inc/welcome/plugins/access-demo-importer.zip',
				'info' 		=> esc_html__('Access Demo Importer adds the feature to Import the Demo Conent with a single click.', 'vmagazine'),
			)
		),

		// *** Recommended Plugins
		'recommended_plugins' => array(
			// Free Plugins
			'free_plugins' => array(

				
				'siteorigin-panels' => array(
					'slug'      => 'siteorigin-panels',
					'filename' 	=> 'siteorigin-panels.php',
					'class' 	=> 'SiteOrigin_Panels'
				),


				'newsletter' 	=> array(
					'slug'      => 'newsletter',
					'filename' 	=> 'plugin.php',
					'class' 	=> 'Newsletter'
				),

				'regenerate-thumbnails' => array(
					'slug'      => 'regenerate-thumbnails',
					'filename' 	=> 'regenerate-thumbnails.php',
					'class' 	=> 'RegenerateThumbnails'	
				),

				

				'woocommerce' => array(
					'slug' => 'woocommerce',
					'filename' => 'woocommerce.php',
					'class' => 'WooCommerce'
				),

				

				

				
			),

			// Pro Plugins
			'pro_plugins' => array(

				'ultimate-form-builder' => array(
					'slug' 			=> 'ultimate-form-builder',
					'name' 			=> esc_html__('Ultimate Form Builder', 'vmagazine'),
					'version' 		=> esc_html__('1.3.3', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=>'ultimate-form-builder.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/ultimate-form-builder/ultimate-form-builder.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/ultimate-form-builder/screen.png',
					'class' 		=> 'UFB_Class'
				),


				'accesspress-social-pro' => array(
					'slug' 			=> 'accesspress-social-pro',
					'name'      	=> esc_html__('AccessPress Social Pro', 'vmagazine'),
					'version' 		=> esc_html__('1.3.5', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'accesspress-social-pro.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/accesspress-social-pro/accesspress-social-pro.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/accesspress-social-pro/social-pro-img.jpg',
					'class' 		=> 'APSS_Class'
				),

				'ultimate-author-box' => array(
					'slug' 			=> 'ultimate-author-box',
					'name'      	=> esc_html__('Ultimate Author Box', 'vmagazine'),
					'version' 		=> esc_html__('1.0.15', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'ultimate-author-box.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/ultimate-author-box/ultimate-author-box.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/ultimate-author-box/screen.jpg',
					'class' 		=> 'Ultimate_Author_Box'
				),

				'everest-coming-soon' => array(
					'slug' 			=> 'everest-coming-soon',
					'name'         	=> esc_html__('Everest Coming Soon', 'vmagazine'),
					'version' 		=> esc_html__('1.0.4', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'everest-coming-soon.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/everest-coming-soon/everest-coming-soon.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/everest-coming-soon/screen.png',
					'class' 		=> 'Everest_Coming_Soon'
				),

				'accesspress-instagram-feed-pro' => array(
					'slug' 			=> 'accesspress-instagram-feed-pro',
					'name'         	=> esc_html__('AccessPress Instagram Feed Pro', 'vmagazine'),
					'version' 		=> esc_html__('2.1.5', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'accesspress-instagram-feed-pro.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/accesspress-instagram-feed-pro/accesspress-instagram-feed-pro.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/accesspress-instagram-feed-pro/screen.jpg',
					'class' 		=> 'IF_Pro_Class'
				),
				
				'accesspress-anonymous-post-pro' => array(
					'slug' 			=> 'accesspress-anonymous-post-pro',
					'name'         	=> esc_html__('AccessPress Anonymous Post Pro', 'vmagazine'),
					'version' 		=> esc_html__('3.2.3', 'vmagazine'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'accesspress-anonymous-post-pro.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'location' 		=> 'https://accesspressthemes.com/plugin-repo/accesspress-anonymous-post-pro/accesspress-anonymous-post-pro.zip',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/accesspress-anonymous-post-pro/screen.png',
					'class' 		=> 'AP_Pro_Class'
				),


				'revslider' 	=> array(
					'slug' 		=> 'revslider',
					'name' 		=> esc_html__('Revolution Slider', 'vmagazine'),
					'version' 	=> esc_html__( '5.4.6', 'vmagazine' ),
					'author' 	=> 'ThemePunch',
					'filename' 	=>'revslider.php',
					'host_type' => 'remote', // Use either bundled, remote, wordpress
					'location' 	=> 'https://accesspressthemes.com/plugin-repo/revslider/revslider.zip',
					'screenshot' => 'https://accesspressthemes.com/plugin-repo/revslider/screen.png',
					'class' 	=> 'RevSliderFront'
				),

				

				'envato-market' 	=> array(
					'slug' 		=> 'envato-market',
					'name' 		=> esc_html__('Envato Market', 'vmagazine'),
					'version' 	=> esc_html__( '2.0.1', 'vmagazine' ),
					'author' 	=> 'Envato',
					'filename' 	=> 'envato-market.php',
					'host_type' => 'remote', // Use either bundled, remote, wordpress
					'location' 	=> 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
					'screenshot' => 'https://accesspressthemes.com/plugin-repo/wp-envato-market/screen.png',
					'function' 	=> 'envato_market',
				),
			)
		),
	);

	$strings = array(
		// Welcome Page General Texts
		'welcome_menu_text' => esc_html__( 'VMagazine Setup', 'vmagazine' ),
		'theme_short_description' => esc_html__( 'The VMagazine is full fledged Premium WordPress theme for companies. The theme comes with spectacular design and powerful features. It is a highly flexible theme that gives you full control to design and manage your dream website as per your wish.', 'vmagazine' ),

		// Plugin Action Texts
		'install_n_activate' 	=> esc_html__('Install and Activate', 'vmagazine'),
		'deactivate' 			=> esc_html__('Deactivate', 'vmagazine'),
		'activate' 				=> esc_html__('Activate', 'vmagazine'),

		// Getting Started Section
		'doc_heading' 		=> esc_html__('Step 1 - Documentation', 'vmagazine'),
		'doc_description' 	=> esc_html__('Read the Documentation and follow the instructions to manage the site , it helps you to set up the theme more easily and quickly. The Documentation is very easy with its pictorial  and well managed listed instructions. ', 'vmagazine'),
		'doc_read_now' 		=> esc_html__( 'Read Now', 'vmagazine' ),
		'cus_heading' 		=> esc_html__('Step 2 - Customizer Panel', 'vmagazine'),
		'cus_description' 	=> esc_html__('Using the VMagazine customizer panel you can easily customize every aspect of the theme.', 'vmagazine'),
		'cus_read_now' 		=> esc_html__( 'Go to Customizer Panels', 'vmagazine' ),

		// Recommended Plugins Section
		'pro_plugin_title' 			=> esc_html__( 'Pro Plugins', 'vmagazine' ),
		'pro_plugin_description' 	=> esc_html__( 'Take Advantage of some of our Premium Plugins.', 'vmagazine' ),
		'free_plugin_title' 		=> esc_html__( 'Free Plugins', 'vmagazine' ),
		'free_plugin_description' 	=> esc_html__( 'These Free Plugins might be handy for you.', 'vmagazine' ),

		

		// Demo Actions
		'activate_btn' 		=> esc_html__('Activate', 'vmagazine'),
		'installed_btn' 	=> esc_html__('Activated', 'vmagazine'),
		'demo_installing' 	=> esc_html__('Installing Demo', 'vmagazine'),
		'demo_installed' 	=> esc_html__('Demo Installed', 'vmagazine'),
		'demo_confirm' 		=> esc_html__('Are you sure to import demo content ?', 'vmagazine'),

		// Actions Required
		'req_plugins_installed' => esc_html__( 'All Recommended action has been successfully completed.', 'vmagazine' ),
		'customize_theme_btn' 	=> esc_html__( 'Customize Theme', 'vmagazine' ),
	);

	/**
	 * Initiating Welcome Page
	*/
	$my_theme_wc_page = new VMagazine_Welcome( $plugins, $strings );


	