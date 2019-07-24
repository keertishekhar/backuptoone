<?php
	if(!class_exists('One_Paze_Welcome')) :

		class One_Paze_Welcome {

			public $tab_sections = array();

			public $theme_name = ''; // For storing Theme Name
			public $theme_version = ''; // For Storing Theme Current Version Information
			public $free_plugins = array(); // For Storing the list of the Recommended Free Plugins
			public $pro_plugins = array(); // For Storing the list of the Recommended Pro Plugins
			public $req_plugins = array(); // For Storing the list of the Required Plugins
			public $companion_plugins = array(); // For Storing the list of the Companion Plugins

			/**
			 * Constructor for the Welcome Screen
			 */
			public function __construct() {
				
				/** Useful Variables **/
				$theme = wp_get_theme();
				$this->theme_name = $theme->Name;
				$this->theme_version = $theme->Version;

				$this->companion_plugins = array();

				/** List of required Plugins **/
				$this->req_plugins = array(

					'instant-demo-importer' => array(
						'slug' => 'instant-demo-importer',
						'name' => __('Instant Demo Importer', 'one-paze'),
						'filename' =>'instant-demo-importer.php',
						'github_repo' => true,
						'bundled' => true,
						'location' => 'https://github.com/WPaccesskeys/instant-demo-importer/archive/master.zip',
						'info' => __('Instant Demo Importer Plugin adds the feature to Import the Demo Conent with a single click.', 'one-paze'),
					),

				);

				/** Define Tabs Sections **/
				$this->tab_sections = array(
					'getting_started' => __('Getting Started', 'one-paze'),
					'recommended_plugins' => __('Recommended Plugins', 'one-paze'),
					'support' => __('Support', 'one-paze'),
					'free_vs_pro' => __('Free vs Pro', 'one-paze'),
					'more_wp_stuff' => __('More WordPress Stuff', 'one-paze'),
				);

				/** List of Recommended Free Plugins **/
				$this->free_plugins = array(

					'accesspress-social-icons' => array(
						'slug' => 'accesspress-social-icons',
						'filename' => 'accesspress-social-icons.php',
					),

					'accesspress-instagram-feed' => array(
						'slug' => 'accesspress-instagram-feed',
						'filename' => 'accesspress-instagram-feed.php',
					),

					'ap-custom-testimonial' => array(
						'slug' => 'ap-custom-testimonial',
						'filename' => 'ap-custom-testimonial.php',
					),

					'accesspress-twitter-feed' => array(
						'slug' => 'accesspress-twitter-feed',
						'filename' => 'accesspress-twitter-feed.php',
					),
				);

				/** List of Recommended Pro Plugins **/
				$this->pro_plugins = array();

				/* Theme Activation Notice */
				add_action( 'load-themes.php', array( $this, 'onepaze_activation_admin_notice' ) );

				/* Create a Welcome Page */
				add_action( 'admin_menu', array( $this, 'onepaze_welcome_register_menu' ) );

				/* Enqueue Styles & Scripts for Welcome Page */
				add_action( 'admin_enqueue_scripts', array( $this, 'onepaze_welcome_styles_and_scripts' ) );

				/** Plugin Installation Ajax **/
				add_action( 'wp_ajax_onepaze_plugin_installer', array( $this, 'onepaze_plugin_installer_callback' ) );

				/** Plugin Installation Ajax **/
				add_action( 'wp_ajax_onepaze_plugin_offline_installer', array( $this, 'onepaze_plugin_offline_installer_callback' ) );

				/** Plugin Activation Ajax **/
				add_action( 'wp_ajax_onepaze_plugin_activation', array( $this, 'onepaze_plugin_activation_callback' ) );

				/** Plugin Activation Ajax (Offline) **/
				add_action( 'wp_ajax_onepaze_plugin_offline_activation', array( $this, 'onepaze_plugin_offline_activation_callback' ) );

				add_action( 'init', array( $this, 'get_required_plugin_notification' ));

			}

			public function get_required_plugin_notification() {
				
				$req_plugins = $this->companion_plugins;
				$notif_counter = count($this->req_plugins);
				$onepaze_plugin_installed_notif = get_option('onepaze_plugin_installed_notif');

				foreach($req_plugins as $plugin) {
					$folder_name = $plugin['slug'];
					$file_name = $plugin['filename'];
					$path = WP_PLUGIN_DIR.'/'.esc_attr($folder_name).'/'.esc_attr($file_name);
					if(file_exists( $path )) {
						if(is_plugin_active($folder_name.'/'.$file_name)) {
							$notif_counter--;
						}
					}
				}
				update_option('onepaze_plugin_installed_notif', $notif_counter);
				return $notif_counter;
			}

			/** Welcome Message Notification on Theme Activation **/
			public function onepaze_activation_admin_notice() {
				global $pagenow;

				if( is_admin() && ('themes.php' == $pagenow) && (isset($_GET['activated'])) ) {
					?>
					<div class="notice notice-success is-dismissible">
						<p><?php /* translators: %1$s : theme name, %2$s : welcome page link */ printf( wp_kses( 'Welcome! Thank you for choosing %1$s! Please make sure you visit our <a href="%2$s">Welcome page</a> to get started with %1$s.', array( 'a' => array( 'href' => array() ) ) ), esc_html($this->theme_name), esc_url(admin_url( 'themes.php?page=onepaze-welcome' ))  ); ?></p>
						<p><a class="button" href="<?php echo esc_url(admin_url( 'themes.php?page=onepaze-welcome' )); ?>"><?php esc_html_e( 'Lets Get Started', 'one-paze' ); ?></a></p>
					</div>
					<?php
				}
			}

			/** Register Menu for Welcome Page **/
			public function onepaze_welcome_register_menu() {
				$action_count = get_option('onepaze_plugin_installed_notif');
				$title        = $action_count > 0 ? esc_html__( 'Welcome', 'one-paze' ) : esc_html__( 'Welcome', 'one-paze' );
				add_theme_page( 'Welcome', $title , 'edit_theme_options', 'onepaze-welcome', array( $this, 'onepaze_welcome_screen' ));
			}

			/** Welcome Page **/
			public function onepaze_welcome_screen() {
				$tabs = $this->tab_sections;

				$current_section = isset($_GET['section']) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'getting_started';
				$section_inline_style = '';
				?>
				<div class="wrap about-wrap access-wrap">
					<h1><?php /* translators: %1$s : theme name, %2$s : theme version */ printf( esc_html__( 'Welcome to %1$s - Version %2$s', 'one-paze' ), esc_html($this->theme_name), esc_html($this->theme_version) ); ?></h1>
					<div class="about-text"><?php printf( esc_html__( 'The %s is a Beautifully designed modern WordPress theme. It is one page Parallax layout with ability to configure inner pages as well. It loads super fast, built on customizer and allows you to live preview your changes as you go. Highly configurable and high level of compatibility with major WP plugins.', 'one-paze' ), esc_html($this->theme_name) ); ?></div>

					<a target="_blank" href="http://www.accesspressthemes.com" class="accesspress-badge wp-badge"><span><?php echo esc_html('AccessPressThemes'); ?></span></a>

				<div class="nav-tab-wrapper clearfix">
					<?php foreach($tabs as $id => $label) : ?>
						<?php
							$section = isset($_REQUEST['section']) ? sanitize_text_field( wp_unslash( $_REQUEST['section'] ) ) : 'getting_started';
							$nav_class = 'nav-tab';
							if($id == $section) {
								$nav_class .= ' nav-tab-active';
							}
						?>
						<a href="<?php echo esc_url(admin_url('themes.php?page=onepaze-welcome&section='.$id)); ?>" class="<?php echo esc_attr($nav_class); ?>" >
							<?php echo esc_html( $label ); ?>
							<?php if($id == 'demo_import') : $not = $this->get_required_plugin_notification(); ?>
								<?php if($not) : ?>
							   		<span class="pending-tasks">
						   				<?php echo esc_html($not); ?>
						   			</span>
				   				<?php endif; ?>
						   	<?php endif; ?>
					   	</a>
					<?php endforeach; ?>
			   	</div>

		   		<div class="welcome-section-wrapper">
	   				<?php $section = isset($_REQUEST['section']) ? sanitize_text_field( wp_unslash( $_REQUEST['section'] ) ) : 'getting_started'; ?>
   					
   					<div class="welcome-section <?php echo esc_attr($section); ?> clearfix">
   						<?php require_once get_template_directory() . '/welcome/sections/'.esc_html($section).'.php'; ?>
					</div>
			   	</div>
			   	</div>
				<?php
			}

			/** Enqueue Necessary Styles and Scripts for the Welcome Page **/
			public function onepaze_welcome_styles_and_scripts() {
				wp_enqueue_style( 'one-paze-welcome-screen', get_template_directory_uri() . '/welcome/css/welcome.css' );
				wp_enqueue_script( 'one-paze-welcome-screen', get_template_directory_uri() . '/welcome/js/welcome.js', array( 'jquery' ) );

				wp_localize_script( 'one-paze-welcome-screen', 'onepazeWelcomeObject', array(
					'admin_nonce'	=> wp_create_nonce('onepaze_plugin_installer_nonce'),
					'activate_nonce'	=> wp_create_nonce('onepaze_plugin_activate_nonce'),
					'ajaxurl'		=> esc_url( admin_url( 'admin-ajax.php' ) ),
					'activate_btn' => __('Activate', 'one-paze'),
					'installed_btn' => __('Activated', 'one-paze'),
					'demo_installing' => __('Installing Demo', 'one-paze'),
					'demo_installed' => __('Demo Installed', 'one-paze'),
					'demo_confirm' => __('Are you sure to import demo content ?', 'one-paze'),
				) );
			}

			/** Plugin API **/
			public function onepaze_call_plugin_api( $plugin ) {
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$call_api = plugins_api( 'plugin_information', array(
					'slug'   => $plugin,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true
					)
				) );

				return $call_api;
			}

			/** Check For Icon **/
			public function onepaze_check_for_icon( $arr ) {
				if ( ! empty( $arr['svg'] ) ) {
					$plugin_icon_url = $arr['svg'];
				} elseif ( ! empty( $arr['2x'] ) ) {
					$plugin_icon_url = $arr['2x'];
				} elseif ( ! empty( $arr['1x'] ) ) {
					$plugin_icon_url = $arr['1x'];
				} else {
					$plugin_icon_url = $arr['default'];
				}

				return $plugin_icon_url;
			}

			/** Check if Plugin is active or not **/
			public function onepaze_plugin_active($plugin) {
				$folder_name = $plugin['slug'];
				$file_name = $plugin['filename'];
				$status = 'install';

				$path = WP_PLUGIN_DIR.'/'.esc_attr($folder_name).'/'.esc_attr($file_name);

				if(file_exists( $path )) {
					$status = is_plugin_active(esc_attr($folder_name).'/'.esc_attr($file_name)) ? 'inactive' : 'active';
				}

				return $status;
			}

			/** Generate Url for the Plugin Button **/
			public function onepaze_plugin_generate_url($status, $plugin) {
				$folder_name = $plugin['slug'];
				$file_name = $plugin['filename'];

				switch ( $status ) {
					case 'install':
						return wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'install-plugin',
									'plugin' => esc_attr($folder_name)
								),
								network_admin_url( 'update.php' )
							),
							'install-plugin_' . esc_attr($folder_name)
						);
						break;

					case 'inactive':
						return add_query_arg( array(
							                      'action'        => 'deactivate',
							                      'plugin'        => rawurlencode( esc_attr($folder_name) . '/' . esc_attr($file_name) . '.php' ),
							                      'plugin_status' => 'all',
							                      'paged'         => '1',
							                      '_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . esc_attr($folder_name) . '/' . esc_attr($file_name) . '.php' ),
						                      ), network_admin_url( 'plugins.php' ) );
						break;

					case 'active':
						return add_query_arg( array(
							                      'action'        => 'activate',
							                      'plugin'        => rawurlencode( esc_attr($folder_name) . '/' . esc_attr($file_name) . '.php' ),
							                      'plugin_status' => 'all',
							                      'paged'         => '1',
							                      '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . esc_attr($folder_name) . '/' . esc_attr($file_name) . '.php' ),
						                      ), network_admin_url( 'plugins.php' ) );
						break;
				}
			}

			/* ========== Plugin Installation Ajax =========== */
			public function onepaze_plugin_installer_callback(){

				if ( ! current_user_can('install_plugins') )
					wp_die( esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'one-paze' ) );

				$nonce = isset( $_POST["nonce"] ) ? sanitize_text_field( wp_unslash( $_POST["nonce"] ) ) : '';
				$plugin = isset( $_POST["plugin"] ) ? sanitize_text_field( wp_unslash( $_POST["plugin"] ) ) : '';
				$plugin_file = isset( $_POST["plugin_file"] ) ? sanitize_text_field( wp_unslash( $_POST["plugin_file"] ) ) : '';

				// Check our nonce, if they don't match then bounce!
				if (! wp_verify_nonce( $nonce, 'onepaze_plugin_installer_nonce' ))
					wp_die( esc_html__( 'Error - unable to verify nonce, please try again.', 'one-paze') );


         		// Include required libs for installation
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
				require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

				// Get Plugin Info
				$api = $this->onepaze_call_plugin_api($plugin);

				$skin     = new WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader( $skin );
				$upgrader->install($api->download_link);

				$plugin_file = ABSPATH . 'wp-content/plugins/'.esc_html($plugin).'/'.esc_html($plugin_file);

				if($api->name) {
					$main_plugin_file = $this->get_plugin_file($plugin);
					if($main_plugin_file){
						activate_plugin($main_plugin_file);
						echo "success";
						die();
					}
				}
				echo "fail";

				die();
			}

			/** Plugin Offline Installation Ajax **/
			public function onepaze_plugin_offline_installer_callback() {

				
				$file_location = isset( $_POST['file_location'] ) ? sanitize_text_field( wp_unslash( $_POST['file_location'] ) ) : '';
				$file = isset( $_POST['file'] ) ? sanitize_text_field( wp_unslash( $_POST['file'] ) ) : '';
				$github = isset( $_POST['github'] ) ? sanitize_text_field( wp_unslash( $_POST['github'] ) ) : '';
				$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
				$plugin_directory = ABSPATH . 'wp-content/plugins/';

				$zip = new ZipArchive;
				if ($zip->open(esc_html($file_location)) === TRUE) {

				    $zip->extractTo($plugin_directory);
				    $zip->close();

				    if($github) {
				    	rename(realpath($plugin_directory).'/'.$slug.'-master', realpath($plugin_directory).'/'.$slug);
				    }
				    
				    activate_plugin($file);
					echo "success";
					die();
				} else {
				    echo 'failed';
				}

				die();
			}

			/** Plugin Offline Activation Ajax **/
			public function onepaze_plugin_offline_activation_callback() {

				$plugin = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';
				$plugin_file = ABSPATH . 'wp-content/plugins/'.esc_html($plugin).'/'.esc_html($plugin).'.php';

				if(file_exists($plugin_file)) {
					activate_plugin($plugin_file);
				} else {
					echo "Plugin Doesn't Exists";
				}

				die();
				
			}

			/** Plugin Activation Ajax **/
			public function onepaze_plugin_activation_callback(){

				if ( ! current_user_can('install_plugins') )
					wp_die( esc_html__( 'Sorry, you are not allowed to activate plugins on this site.', 'one-paze' ) );

				$nonce = isset( $_POST["nonce"] ) ? sanitize_text_field( wp_unslash( $_POST["nonce"] ) ) : '';
				$plugin = isset( $_POST["plugin"] ) ? sanitize_text_field( wp_unslash( $_POST["plugin"] ) ) : '';

				// Check our nonce, if they don't match then bounce!
				if (! wp_verify_nonce( $nonce, 'onepaze_plugin_activate_nonce' ))
					die( esc_html__( 'Error - unable to verify nonce, please try again.', 'one-paze' ) );


	         	// Include required libs for activation
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';


				// Get Plugin Info
				$api = $this->onepaze_call_plugin_api(esc_attr($plugin));


				if($api->name){
					$main_plugin_file = $this->get_plugin_file(esc_attr($plugin));
					$status = 'success';
					if($main_plugin_file){
						activate_plugin($main_plugin_file);
						$msg = $api->name .' successfully activated.';
					}
				} else {
					$status = 'failed';
					$msg = esc_html__('There was an error activating $api->name', 'one-paze');
				}

				$json = array(
					'status' => $status,
					'msg' => $msg,
				);

				wp_send_json($json);

			}

			public function all_required_plugins_installed() {

		      	$companion_plugins = $this->companion_plugins;
				$show_success_notice = false;

				foreach($companion_plugins as $plugin) {

					$path = WP_PLUGIN_DIR.'/'.esc_attr($plugin['slug']).'/'.esc_attr($plugin['filename']);

					if(file_exists($path)) {
						if(is_plugin_active(esc_attr($plugin['slug']).'/'.esc_attr($plugin['filename']))) {
							$show_success_notice = true;
						} else {
							$show_success_notice = false;
							break;
						}
					} else {
						$show_success_notice = false;
						break;
					}
				}

				return $show_success_notice;
	      	}

			public static function get_plugin_file( $plugin_slug ) {
		         require_once ABSPATH . '/wp-admin/includes/plugin.php'; // Load plugin lib
		         $plugins = get_plugins();

		         foreach( $plugins as $plugin_file => $plugin_info ) {

			         // Get the basename of the plugin e.g. [askismet]/askismet.php
			         $slug = dirname( plugin_basename( $plugin_file ) );

			         if($slug){
			            if ( $slug == $plugin_slug ) {
			               return $plugin_file; // If $slug = $plugin_name
			            }
		            }
		         }
		         return null;
	      	}

	      	public function get_local_dir_path($plugin) {

	      		$url = wp_nonce_url(admin_url('themes.php?page=onepaze-welcome&section=import_demo'),'onepaze-file-installation');
				if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
					return; // stop processing here
				}

	      		if ( ! WP_Filesystem($creds) ) {
					request_filesystem_credentials($url, '', true, false, null);
					return;
				}

				global $wp_filesystem;
				$file = $wp_filesystem->get_contents( $plugin['location'] );

				$file_location = get_template_directory().'/welcome/plugins/'.$plugin['slug'].'.zip';

				$wp_filesystem->put_contents( $file_location, $file, FS_CHMOD_FILE );

				return $file_location;
	      	}

		}

		new One_Paze_Welcome();

	endif;

	/** Initializing Demo Importer if exists **/
	if(class_exists('Instant_Demo_Importer')) :
		$demoimporter = new Instant_Demo_Importer();

		$demoimporter->demos = array(
			'one-paze' => array(
				'title' => __('One Paze Demo', 'one-paze'),
				'name' => 'one-paze',
				'screenshot' => get_template_directory_uri().'/welcome/demos/one-paze/screen.png',
				'home_page' => 'home',
				'menus' => array(
					'Primary menu' => 'primary'
				)
			),
		);

		$demoimporter->demo_dir = get_template_directory().'/welcome/demos/'; // Path to the directory containing demo files
		$demoimporter->options_replace_url = ''; // Set the url to be replaced with current siteurl
		$demoimporter->option_name = ''; // Set the the name of the option if the theme is based on theme option
		
	endif;
?>