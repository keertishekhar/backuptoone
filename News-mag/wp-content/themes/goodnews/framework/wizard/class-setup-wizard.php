<?php
if(!class_exists('mom_setup_wizard')) {
  class mom_setup_wizard
  {
 		/** @var array Steps for the setup wizard */
		protected $steps = array();
		protected $step = '';
		/**
		 * Relative plugin path
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $page_slug;
		protected $page_url;

		protected $plugin_path = '';
		protected $theme_name = '';

		/**
		 * Relative plugin url for this plugin folder, used when enquing scripts
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_url = '';

				/**
		 * TGMPA instance storage
		 *
		 * @var object
		 */
		protected $tgmpa_instance;

		/**
		 * TGMPA Menu slug
		 *
		 * @var string
		 */
		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		/**
		 * TGMPA Menu url
		 *
		 * @var string
		 */
		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		protected $version = '1.0.0';
		
		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}


    public function __construct() {
			$this->init();
			$this->init_actions();
	  add_action( 'wp_ajax_mom_setup_plugins', array( $this, 'ajax_plugins' ) );
    }

		public function init() {
						$current_theme = wp_get_theme();
						$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
						$this->page_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_page_slug', $this->theme_name . '-setup' );
						//set relative plugin path url
						$this->plugin_path = trailingslashit( $this->cleanFilePath( dirname( __FILE__ ) ) );
						$relative_url      = str_replace( $this->cleanFilePath( get_template_directory() ), '', $this->plugin_path );
						$this->plugin_url  = trailingslashit( get_template_directory_uri() . $relative_url );
						$this->page_url = 'themes.php?page=' . $this->page_slug;
		}
		
		public function init_actions() {
			if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {
				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );

				if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
					add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
					add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
				}

				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
				add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action( 'admin_init', array( $this, 'wizard' ), 30 );
				add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
				add_action( 'wp_ajax_mom_setup_plugins', array( $this, 'ajax_plugins' ) );
				add_action( 'vc_activation_hook', array( $this, 'disable_vc_welcome' ), 99 );
				add_filter('woocommerce_enable_setup_wizard', array($this, 'disable_woo_wizard'), 10, 1);

			}
		}
		/**
		 * Helper function
		 * Take a path and return it clean
		 *
		 * @param string $path
		 *
		 * @since    1.1.2
		 */
		public static function cleanFilePath( $path ) {
			$path = str_replace( '', '', str_replace( array( '\\', '\\\\', '//' ), '/', $path ) );
			if ( $path[ strlen( $path ) - 1 ] === '/' ) {
				$path = rtrim( $path, '/' );
			}

			return $path;
		}

    public function admin_menu() {
      add_theme_page(__('Setup wizard', 'meza'), __('Setup wizard', 'meza'), 'edit_theme_options', $this->page_slug, array($this, 'setup_page'));
    }
    public function setup_page() {
      return;
		}
		public function switch_theme() {
			set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
		}

		public function admin_redirects() {
			if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
				return;
			}
			delete_transient( '_' . $this->theme_name . '_activation_redirect' );
			wp_safe_redirect( admin_url( $this->page_url ) );
			exit;
		}
		public function tgmpa_load( $status ) {
			return is_admin() || current_user_can( 'install_themes' );
		}
		/**
		 * Get configured TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		/**
		 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );

		}		
		
		private function _get_plugins() {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
			$plugins  = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					// No need to display plugins if they are installed, up-to-date and active.
					//continue;
					$plugins['all'][ $slug ] = $plugin;
					$plugins['installed'][ $slug ] = $plugin;

				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}

			return $plugins;
		}

		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'mom_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found' ) ) );
			}
			$json = array();
			// send back some json we use to hit up TGM
			$plugins = $this->_get_plugins();
			// what are we doing with this plugin?
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => - 1,
						'message'       => esc_html__( 'Activating Plugin' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => - 1,
						'message'       => esc_html__( 'Updating Plugin' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => - 1,
						'message'       => esc_html__( 'Installing Plugin' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success' ) ) );
			}
			exit;

		}
	public function init_wizard_steps() {
		$this->steps = array(
			'intro' => array(
			  'name'    => esc_html__( 'Introduction', 'meza' ),
			  'view'    => array( $this, 'intro' ),
			  'handler' => '',
			),
			  );
			  
			  $this->steps['plugins'] = array(
				  'name'    => esc_html__( 'Plugins', 'meza' ),
				  'view'    => array( $this, 'plugins' ),
				  'handler' => '',
			  );
			  $this->steps['demos'] = array(
				  'name'    => esc_html__( 'Demos', 'meza' ),
				  'view'    => array( $this, 'demos' ),
				  'handler' => '',
			  );
			  $this->steps['ready'] = array(
				  'name'    => esc_html__( 'Ready', 'meza' ),
				  'view'    => array( $this, 'ready' ),
				  'handler' => '',
			  );
  
  
		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );
			  
	}
	public function disable_woo_wizard( $bool) {
		if ( !empty( $_GET['page'] ) && 'tgmpa-install-plugins' == $_GET['page'] ) {
			return false;
		}
		return true;
	}
	public function disable_vc_welcome() {
		delete_transient( '_vc_page_welcome_redirect' );
	}
    /**
		 * Output the content for the current step
		*/
    public function wizard() {
      if (!isset($_GET['page']) || $_GET['page'] !== $this->page_slug) {
        return;
      }
			//ob_end_clean();
			//assets 
			if (isset($_GET['step']) && $_GET['step'] === 'demos') {
				if(class_exists('mom_core_demos')) {
					$demos = new mom_core_demos; 
					$demos->load_assets();
				}
			}

			wp_enqueue_style( 'mom-setup-wizard', $this->plugin_url . 'css/wizard.css', array(), $this->version );
			
			wp_enqueue_style( 'mom-setup-wizard', get_template_directory_uri() . '/framework/demos/css/demo.css', array(), $this->version );
			
			wp_register_script( 'mom-setup-wizard', $this->plugin_url . 'js/wizard.js', array('jquery'), $this->version );
			wp_localize_script( 'mom-setup-wizard', 'wizardAjax', array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce( 'tgmpa-update' ),
					'install' => wp_create_nonce( 'tgmpa-install' ),
				),
				'tgm_bulk_url'     => admin_url( $this->tgmpa_url ),
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'wpnonce'          => wp_create_nonce( 'mom_setup_nonce' ),
				'verify_text'      => esc_html__( 'verifying...', 'meza' ),
				'installing_text'      => esc_html__( 'Installing...', 'meza' ),
			) );
			
			//ob_start();
      //page 
      $this->setup_wizard_header();
      $this->setup_wizard_steps();
      $this->setup_wizard_content();
      $this->setup_wizard_footer();

	  exit;
    }

		public function get_step_link( $step ) {
			return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->page_slug ) );
		}

		public function get_next_step_link() {
			$keys = array_keys( $this->steps );

			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Setup Wizard Header
		 */
	public function setup_wizard_header() {
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php
			// avoid theme check issues.
			echo '<t';
			echo 'itle>' . esc_html__( 'Theme &rsaquo; Setup Wizard' ) . '</ti' . 'tle>'; ?>
			<?php wp_print_scripts( 'mom-setup-wizard' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="mom-setup-wizard wp-core-ui">
		<?php include dirname(__FILE__) . '/parts/header.php'; ?>
			<div class="mom-setup-wizard-wrap">
				<?php
	}

		/**
		 * Setup Wizard Footer
		 */
	public function setup_wizard_footer() {
			include dirname(__FILE__) . '/parts/footer.php';
		?>
		</div> <!-- end setup wizard wrap --> 
		</body>
		<?php
		@do_action( 'admin_footer' ); // this was spitting out some errors in some admin templates. quick @ fix until I have time to find out what's causing errors.
		do_action( 'admin_print_footer_scripts' );
		?>
		</html>
		<?php
	}

		/**
		 * Output the steps
		 */
		public function setup_wizard_steps() {
			$ouput_steps = $this->steps;
			array_shift( $ouput_steps );
			include dirname(__FILE__) . '/parts/steps.php';
		}

		/**
		 * Output the content for the current step
		 */
    public function setup_wizard_content() {
      if(array_key_exists( $this->step, $this->steps )) {
        isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
      }
    }
    public function intro() { 
		include dirname(__FILE__) . '/parts/intro.php';
	}
	public function plugins() {
		include dirname(__FILE__) . '/parts/plugins.php';
	}
	public function demos() { 
		include dirname(__FILE__) . '/parts/demos.php';
	}
	public function ready() {
		include dirname(__FILE__) . '/parts/ready.php';
	}

	public function svg($s) {
		$svgs = array();
		include dirname(__FILE__) . '/parts/svgs.php';
		return isset($svgs[$s]) ? $svgs[$s] : '';
	}
  
  }

  new mom_setup_wizard;
  
}