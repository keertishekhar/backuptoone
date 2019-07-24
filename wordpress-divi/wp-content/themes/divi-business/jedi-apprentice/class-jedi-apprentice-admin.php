<?php
/**
 * J.E.D.I. | Jerry's Easy Demo Import
 *
 * Class: JEDI_Apprentice_Admin
 * This class builds the WP Admin menu items and pages.
 *
 * @package		jedi-apprentice
 * @author		Jerry Simmons <jerry@montereypremier.com>
 * @copyright	2017 Jerry Simmons
 * @license		GPL-2.0+
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'JEDI_Apprentice_Admin' ) ) :
class JEDI_Apprentice_Admin {

	public $jedi_beacon;
	public $jedi_apprentice_menu_title;

	public $jedi_apprentice_menu_slug = 'jedi_apprentice_menu';
	public $jedi_import_section_id = 'jedi_apprentice_options';
	public $jedi_apprentice_settings_page = 'jedi_import_options_page';
	public $jedi_support_section_id = 'jedi_support_section_id';
	public $jedi_support_settings_page = 'jedi_support_settings_page';

	public $jedi_apprentice_settings = array();
	public $jedi_import_options = array();

	/**
	 * Initializes import settings
	 * Build Admin Menus
	 **/
	public function __construct() {
		$this->jedi_beacon = new JEDI_Beacon;

		$this->jedi_apprentice_settings = get_option('jedi_apprentice_settings');
		$this->jedi_import_options = get_option('jedi_import_options');
		$this->jedi_apprentice_menu_title = $this->jedi_apprentice_settings['installer_name'];

		add_action( 'admin_menu', array($this, 'admin_menu'), 8000 );
		add_action( 'admin_init', array($this, 'admin_menu_import_options'), 1 );
		add_action( 'admin_init', array($this, 'admin_menu_support_options'), 2 );
		add_action( 'admin_enqueue_scripts', array($this, 'load_jedi_apprentice_admin_style') );
	}


	/**
	 * Load Admin Menus CSS
	 **/
	function load_jedi_apprentice_admin_style() {
		if( $this->jedi_apprentice_settings['installer_style'] ) {
			wp_register_style(
				'jedi_apprentice_admin_css',
				get_stylesheet_directory_uri() . '/jedi-apprentice/jedi_apprentice_admin.css',
				false, rand() );
		} else {
			wp_register_style(
				'jedi_apprentice_admin_css',
				JEDI_APPRENTICE_URL . 'jedi_apprentice_admin.css',
				false, rand() );
		}
		wp_enqueue_style( 'jedi_apprentice_admin_css' );
	}


	/**
	 * Builds the WP Admin Page Menus
	 **/
	function admin_menu() {
		if ( 'Imported' != get_option('jedi_status') ) {
			$menu_page_position = '3.01';
		} else {
			$menu_page_position = '9999';
		}
		add_menu_page(
			$this->jedi_apprentice_menu_title,					// Title
			$this->jedi_apprentice_menu_title,					// Menu Title
			'manage_options',									// Capability
			$this->jedi_apprentice_menu_slug,					// Menu Slug
			array($this, 'jedi_apprentice_admin_menu_page'),	// Callable Function
			'dashicons-migrate',								// Icon
			$menu_page_position );								// Position
		add_submenu_page(
			$this->jedi_apprentice_menu_slug,					// Parent Slug
			'Easy Demo Import',									// Page Title,
			'Easy Demo Import', 								// Menu Title
			'manage_options', 									// Capability
			$this->jedi_apprentice_menu_slug, 					// Menu Slug
			array($this, 'jedi_apprentice_admin_menu_page' ) );	// Callable Function
/*		add_submenu_page(
			$this->jedi_apprentice_menu_slug,						// Parent Slug
			'Documentation',										// Page Title,
			'Documentation', 										// Menu Title
			'manage_options', 										// Capability
			$this->jedi_apprentice_menu_slug.'_documentation',		// Menu Slug
			array($this, 'jedi_apprentice_documentation_page' ) );	// Callable Function
*/
		add_submenu_page(
			$this->jedi_apprentice_menu_slug,					// Parent Slug
			'Support',											// Page Title,
			'Support', 											// Menu Title
			'manage_options', 									// Capability
			$this->jedi_apprentice_menu_slug.'_support', 		// Menu Slug
			array($this, 'jedi_apprentice_support_page' ) );	// Callable Function
	}


	/**
	 * Install Selected Repository Plugins
	 *
	 * @uses PclZip()
	 * @uses wp_filesystem
	 * @uses get_plugins()
	 * @uses wp_cache_get() and wp_cache_set()
	 **/
	private function jedi_install_repo_plugins() {
		$install_plugins = $this->jedi_import_options['install_plugins'];

		foreach( $install_plugins as $slug => $install_plugin ) {
			$install_plugin = trim($install_plugin);
			$temp_zip = WP_PLUGIN_DIR . '/' . basename($install_plugin);
			$safe_jedi = copy( $install_plugin, $temp_zip );

			/**
			 * Unzip Plugin File
			 **/
			$unzip_plugin = new PclZip( $temp_zip );
			if ($unzip_plugin->extract(PCLZIP_OPT_PATH, WP_PLUGIN_DIR) == 0) {
				die("Error : ".$unzip_plugin->errorInfo(true));
			}

			/**
			 * Remove Temporary Zip File
			 **/
			global $wp_filesystem;
			if (empty($wp_filesystem)) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				if( ! WP_Filesystem() ) {
					$this->jedi_beacon->order_66('Failed to initialize WP Filesystem.');
				}
			}
			$wp_filesystem->delete( $temp_zip );

			$plugin_data = get_plugins( '/' . $slug );
			$plugin_file = $slug . '/' . key( $plugin_data );

			// Update WP Plugin Data Cache
			$cache_plugins = wp_cache_get( 'plugins', 'plugins' );
			if ( !empty( $cache_plugins ) ) {
				$cache_plugins[''][$plugin_file] = $plugin_data;
				wp_cache_set( 'plugins', $cache_plugins, 'plugins' );
			}

			// Update JEDI Import Setting With Plugin File
			if( isset( $this->jedi_import_options['activate_plugins'][$slug] ) ) {
				$this->jedi_import_options['activate_plugins'][$slug] = $plugin_file;
			}

			$this->jedi_beacon->jedi_log( 'Plugin Installed', $plugin_file );
		}
	} // END jedi_install_repo_plugins()


	/**
	 * Install Selected Plugins Not Found In The Repository
	 *
	 * @uses wp_filesystem
	 * @uses get_plugins()
	 * @uses wp_cache_get() and wp_cache_set()
	 **/
	private function jedi_install_nonrepo_plugins() {
		$install_plugins = $this->jedi_import_options['install_nonrepo_plugins'];

		$plugin_import_path = JEDI_APPRENTICE_PATH . 'demo-data/anyplugin/';

		foreach( $install_plugins as $install_plugin ) {

			// Create Plugin Folder
			$plugin_install_path = WP_PLUGIN_DIR . '/' . $install_plugin . '/';
			if( ! wp_mkdir_p( $plugin_install_path ) ) {
				$this->jedi_beacon->order_66('Unable to create Plugin Folder: ' . $plugin_install_path );
			}

			// Duplicate The Plugin Folder
			$safe_jedi = copy_dir( $plugin_import_path.$install_plugin.'/', $plugin_install_path );
			if( is_wp_error($safe_jedi) ) {
				$this->jedi_beacon->order_66('Failed to copy Plugin folder', $safe_jedi);
			}

			$plugin_data = get_plugins( '/' . $install_plugin );
			$plugin_file = $install_plugin . '/' . key( $plugin_data );

			// Update WP Plugin Data Cache
			$cache_plugins = wp_cache_get( 'plugins', 'plugins' );
			if ( !empty( $cache_plugins ) ) {
				$cache_plugins[''][$plugin_file] = $plugin_data;
				wp_cache_set( 'plugins', $cache_plugins, 'plugins' );
			}

			// Update JEDI Import Setting With Plugin File
			if( isset( $this->jedi_import_options['activate_plugins'][$install_plugin] ) ) {
				$this->jedi_import_options['activate_plugins'][$install_plugin] = $plugin_file;
			}

			$this->jedi_beacon->jedi_log( 'Plugin Installed', $plugin_file );
		}
	} // END jedi_install_nonrepo_plugins()


	/**
	 * Activate Selected Plugins
	 *
	 * @uses activate_plugin()
	 **/
	private function jedi_activate_plugins() {
		$activate_plugins = $this->jedi_import_options['activate_plugins'];
		foreach( $activate_plugins as $activate_plugin ) {
			$safe_jedi = activate_plugin( $activate_plugin );
			$this->jedi_beacon->jedi_log( 'Plugin Activated: ' . $activate_plugin, $safe_jedi );
		}
	}


	/**
	 * Main Import Page for JEDI Apprentice
	 **/
	function jedi_apprentice_admin_menu_page() {

		// Update Database Values
		if( isset($_POST['jedi_import_button'] ) ) { // button name

			$this->jedi_import_options['include_posts'] = '';
			$this->jedi_import_options['include_media'] = '';
			$this->jedi_import_options['include_options'] = '';
			$this->jedi_import_options['include_css'] = '';
			$this->jedi_import_options['include_menus'] = '';
			$this->jedi_import_options['include_homepage'] = '';
			$this->jedi_import_options['include_widgets'] = '';

			if( isset( $_POST['jedi_import_posts_checkbox'] ) ) {
				$this->jedi_import_options['include_posts'] = $_POST['jedi_import_posts_checkbox'];
			}
			if( isset( $_POST['jedi_import_media_checkbox'] ) ) {
				$this->jedi_import_options['include_media'] = $_POST['jedi_import_media_checkbox'];
			}
			if( isset( $_POST['jedi_import_options_checkbox'] ) ) {
				$this->jedi_import_options['include_options'] = $_POST['jedi_import_options_checkbox'];
			}
			if( isset( $_POST['jedi_import_css_checkbox'] ) ) {
				$this->jedi_import_options['include_css'] = $_POST['jedi_import_css_checkbox'];
			}
			if( isset( $_POST['jedi_import_menus_checkbox'] ) ) {
				$this->jedi_import_options['include_menus'] = $_POST['jedi_import_menus_checkbox'];
			}
			if( isset( $_POST['jedi_import_homepage_checkbox'] ) ) {
				$this->jedi_import_options['include_homepage'] = $_POST['jedi_import_homepage_checkbox'];
			}
			if( isset( $_POST['jedi_import_widgets_checkbox'] ) ) {
				$this->jedi_import_options['include_widgets'] = $_POST['jedi_import_widgets_checkbox'];
			}

			/**
			 * Install & Activate Plugins
			 **/
			$install_plugins = array();
			$install_nonrepo_plugins = array();
			$activate_plugins = array();
			foreach( $_POST as $key => $post_data ) {
				if( 0 === strpos( $key, 'install_plugin' ) ) {
					if( 'nonrepo' == $post_data ) {
						$install_nonrepo_plugins[substr($key,15)] = substr($key,15);
					} else {
						$install_plugins[substr($key,15)] = $post_data;
					}
				}
				if( 0 === strpos( $key, 'activate_plugin' ) ) {
					$activate_plugins[substr($key,16)] = $post_data;
				}
			}

			$this->jedi_import_options['install_plugins'] = $install_plugins;
			$this->jedi_import_options['install_nonrepo_plugins'] = $install_nonrepo_plugins;
			$this->jedi_import_options['activate_plugins'] = $activate_plugins;

			if( 0 < count( $install_plugins ) ) {
				$this->jedi_install_repo_plugins();
			}
			if( 0 < count( $install_nonrepo_plugins ) ) {
				$this->jedi_install_nonrepo_plugins();
			}
			if( 0 < count( $activate_plugins ) ) {
				$this->jedi_activate_plugins();
			}

			update_option('jedi_import_options', $this->jedi_import_options);
			$this->jedi_beacon->jedi_log( 'Import Options Saved', $this->jedi_import_options );

			/**
			 * Run Import
			 **/
			$jedi_do_import = new JEDI_Apprentice_Import;
			unset($jedi_do_import);

		} else {

			// Display Options
			?>
			<div class="jedi_import_options_container">
				<form action="admin.php?page=jedi_apprentice_menu" method="POST">
					<input type="hidden" value="true" name="jedi_import_button" />
					<?php //settings_fields( $this->jedi_import_section_id );
					//do_settings_sections( $this->jedi_apprentice_settings_page );
					$this->list_import_options();
					$this->list_recommended_plugins();
					$this->list_recommended_nonrepo_plugins();
					submit_button('Import Demo Content'); ?>
				</form>
			</div>
			<?php
		}

	} // END jedi_apprentice_admin_menu_page()


	/**
	 * Builds all the fields for the main import page
	 **/
	function admin_menu_import_options() {

		// Import Options Section
		add_settings_section(
			$this->jedi_import_section_id,								// ID
			$this->jedi_apprentice_menu_title . ' - Import Options',	// Title
			array($this, 'jedi_import_options_callback'),				// Callback Function
			$this->jedi_apprentice_settings_page );						// Settings Page

		// Import Posts Checkbox
		if( $this->jedi_apprentice_settings['include_posts'] ) {
			add_settings_field(
				'jedi_import_posts_checkbox', 						// ID,
				__('Include Posts And Pages', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_posts_checkbox'), 		// Callback Function
				$this->jedi_apprentice_settings_page, 				// Settings Section Page
				$this->jedi_import_section_id ); 					// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_posts_checkbox');
		}

		// Import Media Checkbox
		if( $this->jedi_apprentice_settings['include_media'] ) {
			add_settings_field(
				'jedi_import_media_checkbox', 					// ID,
				__('Include Media Library', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_media_checkbox'), 	// Callback Function
				$this->jedi_apprentice_settings_page, 			// Settings Section Page
				$this->jedi_import_section_id ); 				// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_media_checkbox');
		}

		// Import Theme Options Checkbox
		if( $this->jedi_apprentice_settings['include_options'] ) {
			add_settings_field(
				'jedi_import_options_checkbox', 							// ID,
				__('Include Divi Theme Options & Divi Customizer Settings',
					'jedi-apprentice'),										// Title
				array($this, 'jedi_import_options_checkbox'), 				// Callback Function
				$this->jedi_apprentice_settings_page, 						// Settings Section Page
				$this->jedi_import_section_id ); 							// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_options_checkbox');
		}

		// Import CSS Checkbox
		if( $this->jedi_apprentice_settings['include_css'] ) {
			add_settings_field(
				'jedi_import_css_checkbox', 								// ID,
				__('Include Customizer Additional CSS', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_css_checkbox'), 					// Callback Function
				$this->jedi_apprentice_settings_page,						// Settings Section Page
				$this->jedi_import_section_id ); 							// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_css_checkbox');
		}

		// Import Menus Checkbox
		if( $this->jedi_apprentice_settings['include_menus'] ) {
			add_settings_field(
				'jedi_import_menus_checkbox', 						// ID,
				__('Include Menu Structure', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_menus_checkbox'), 		// Callback Function
				$this->jedi_apprentice_settings_page, 				// Settings Section Page
				$this->jedi_import_section_id ); 					// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_menus_checkbox');
		}

		// Import Widgets Checkbox
		if( $this->jedi_apprentice_settings['include_widgets'] ) {
			add_settings_field(
				'jedi_import_widgets_checkbox', 					// ID,
				__('Include Widget Settings', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_widgets_checkbox'), 		// Callback Function
				$this->jedi_apprentice_settings_page, 				// Settings Section Page
				$this->jedi_import_section_id ); 					// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_widgets_checkbox');
		}

		// Set Homepage Checkbox
		if( $this->jedi_apprentice_settings['include_homepage'] ) {
			add_settings_field(
				'jedi_import_homepage_checkbox', 						// ID,
				__('Automatically Set Homepage', 'jedi-apprentice'),	// Title
				array($this, 'jedi_import_homepage_checkbox'),			// Callback Function
				$this->jedi_apprentice_settings_page, 					// Settings Section Page
				$this->jedi_import_section_id ); 						// Settings Section ID
			register_setting($this->jedi_import_section_id, 'jedi_import_homepage_checkbox');
		}

	} // END admin_menu_import_options()


	/**
	 * List Recommended Plugins
	 * Add Checkboxes For Install & Activate Options
	 **/
	function list_recommended_plugins() {
		if( !isset( $this->jedi_apprentice_settings['selected_plugins'] ) ) { return; }
		$active_plugins = get_option( 'active_plugins' );
		$installed_plugins = get_plugins();

		echo '<div class="jedi_available_plugins">';
			echo '<table class="jedi_suggest_plugins_table">';
				echo '<td class="jedi_plugin_heading" colspan="3"><h3>Recommended Plugin(s) From The WordPress Plugin Repository:</h3></td>';

				foreach( $this->jedi_apprentice_settings['selected_plugins'] as $plugin_slug ) {

					$get_plugin_repo_data = array(
						'action' => 'plugin_information',
						'request' => serialize(
							(object)array(
								'slug' => $plugin_slug,
								'fields' => array('description' => true) ) )
					);
					$repo_data = wp_remote_post(
						'http://api.wordpress.org/plugins/info/1.0/',
						array( 'body' => $get_plugin_repo_data )
					);

					if( 'N;' != $repo_data['body'] ) {
						$plugin_repo_data = unserialize( $repo_data['body'] );

						$is_plugin_installed = false;
						foreach( $installed_plugins as $plugin_file => $installed_plugin ) {
							$plugin_path_info = pathinfo( WP_PLUGIN_DIR . '/' . $plugin_file );
							$active_plugin_slug = basename( $plugin_path_info['dirname'] );
							if( $active_plugin_slug == $plugin_slug ) {
								$is_plugin_installed = true;
								$installed_plugin_file = $plugin_file;
							}
						}
						$is_plugin_activated = false;
						foreach( $active_plugins as $active_plugin ) {
							$plugin_path_info = pathinfo( WP_PLUGIN_DIR . '/' . $active_plugin );
							$active_plugin_slug = basename( $plugin_path_info['dirname'] );
							if( $active_plugin_slug == $plugin_slug ) {
								$is_plugin_activated = true;
							}
						}
					} else { continue; }

					echo '<tr class="row1"><td class="jedi_plugin_name_td">';
						echo '<h4>' . $plugin_repo_data->name . '</h4>';

						if( $is_plugin_installed ) {
							echo "<input disabled type='checkbox' name='plugin_installed' value='1'"
								.checked(1, $is_plugin_installed, false)." /> Installed";
						} else {
							echo "<input type='checkbox' name='install_plugin_". $plugin_repo_data->slug ."' value='". $plugin_repo_data->download_link . "' "
								.checked(1, 1, false)." /> Install";
						}
						echo '<br>';

						if( $is_plugin_activated ) {
							echo "<input disabled type='checkbox' name='plugin_activated' value='1'"
								.checked(1, $is_plugin_installed, false)." /> Activated";
						} else {
							if( !$is_plugin_installed ) {
								echo "<input type='checkbox' name='activate_plugin_" . $plugin_repo_data->slug . "' value='" . $plugin_repo_data->slug . "' "
									.checked(1, 1, false)." /> Activate";
							} else {
								echo "<input type='checkbox' name='activate_plugin_" . $plugin_repo_data->slug . "' value='" . $installed_plugin_file . "' "
									.checked(1, 1, false)." /> Activate";
							}
						}

					echo '</td>';
					echo '<td class="jedi_plugin_description_td">';
						echo substr( strip_tags( $plugin_repo_data->description ), 0, 150 ) . '... <br>';
						echo '<a href="'.$plugin_repo_data->homepage.'" target="_blank">Visit Plugin Homepage For More Information'.''.'</a>';

					echo '</td>';

					echo '<td class="jedi_plugin_info_td">';
						echo 'Author: <a href="' . $plugin_repo_data->author_profile . '" target="_blank">' . strip_tags( $plugin_repo_data->author ) . '</a><br>';
						echo 'Downloads: ' . number_format( $plugin_repo_data->downloaded ) . '<br>';
						echo 'Plugin Rating: ' . $plugin_repo_data->rating . '% ('
							. $plugin_repo_data->num_ratings .' ratings)<br>';
						echo 'Support Threads: ' . $plugin_repo_data->support_threads
							. ' (' . $plugin_repo_data->support_threads_resolved . ' resolved)<br>';
						echo 'Current Version: ' . $plugin_repo_data->version
							. ' (Updated: ' . substr($plugin_repo_data->last_updated, 0, 10) . ')<br>';
					echo '</td>';
				echo '</tr>';
				} // END foreach selected_plugins
			echo '</table>';
		echo '</div>';

	} // END list_recommended_plugins()


	/**
	 * List Recommended Plugins not found in the WordPress Plugin Repository
	 * Add Checkboxes For Install & Activate Options
	 **/
	function list_recommended_nonrepo_plugins() {
		if( empty($this->jedi_apprentice_settings['jedi_exported_nonrepo_plugins']) ) { return; }

		$active_plugins = get_option( 'active_plugins' );
		$installed_plugins = get_plugins();

		echo '<div class="jedi_available_plugins">';
			echo '<table class="jedi_suggest_plugins_table">';
				echo '<td class="jedi_plugin_heading" colspan="3"><h3><br>Other Recommended Plugin(s):</h3></td>';

				foreach( $this->jedi_apprentice_settings['jedi_exported_nonrepo_plugins'] as $plugin_slug => $nonrepo_plugin ) {
					$is_plugin_installed = false;
					foreach( $installed_plugins as $plugin_file => $installed_plugin ) {
						$plugin_path_info = pathinfo( WP_PLUGIN_DIR . '/' . $plugin_file );
						$active_plugin_slug = basename( $plugin_path_info['dirname'] );
						if( $active_plugin_slug == $plugin_slug ) {
							$is_plugin_installed = true;
							$installed_plugin_file = $plugin_file;
						}
					}
					$is_plugin_activated = false;
					foreach( $active_plugins as $active_plugin ) {
						$plugin_path_info = pathinfo( WP_PLUGIN_DIR . '/' . $active_plugin );
						$active_plugin_slug = basename( $plugin_path_info['dirname'] );
						if( $active_plugin_slug == $plugin_slug ) {
							$is_plugin_activated = true;
						}
					}

					echo '<tr class="row1"><td class="jedi_plugin_name_td">';
						echo '<h4>' . $nonrepo_plugin['Name'] . '</h4>';

						if( $is_plugin_installed ) {
							echo "<input disabled type='checkbox' name='plugin_installed' value='1'"
								.checked(1, $is_plugin_installed, false)." /> Installed";
						} else {
							echo "<input type='checkbox' name='install_plugin_". $plugin_slug ."' value='nonrepo' "
								.checked(1, 1, false)." /> Install";
						}
						echo '<br>';

						if( $is_plugin_activated ) {
							echo "<input disabled type='checkbox' name='plugin_activated' value='1'"
								.checked(1, $is_plugin_installed, false)." /> Activated";
						} else {
							if( !$is_plugin_installed ) {
								echo "<input type='checkbox' name='activate_plugin_" . $plugin_slug . "' value='" . $plugin_slug . "' "
									.checked(1, 1, false)." /> Activate";
							} else {
								echo "<input type='checkbox' name='activate_plugin_" . $plugin_slug . "' value='" . $installed_plugin_file . "' "
									.checked(1, 1, false)." /> Activate";
							}
						}
					echo '</td>';

					echo '<td class="jedi_plugin_description_td">';
						echo $nonrepo_plugin['Description'];
					echo '</td>';

					echo '<td class="jedi_plugin_info_td">';
						echo 'Version: ' . $nonrepo_plugin['Version'] . '<br>';
						echo 'Plugin URL: <a href="' . $nonrepo_plugin['PluginURI'] . '" target="_blank">'
							.$nonrepo_plugin['PluginURI'] . '</a><br>';
					echo '</td>';
				echo '</tr>';
				} // END foreach selected_plugins
			echo '</table>';
		echo '</div>';

	} // END list_recommended_nonrepo_plugins()

	/**
	 * Import Page - Installer Message
	 **/
	function jedi_import_options_callback() {
		// Installer Message
		_e( nl2br($this->jedi_apprentice_settings['installer_message']), 'jedi-apprentice' );
	}


	/**
	 * Import Options Checkboxes
	 **/
	function list_import_options() {
		$jedi_documentation = $this->jedi_apprentice_settings['custom_documentation'];
		echo '<h2>' . $this->jedi_apprentice_menu_title . ' - Import Options</h2>';
		echo $jedi_documentation['intro']['text'];
		echo '<table class="jedi_import_options_table">';
			if( $this->jedi_apprentice_settings['include_posts'] ) {
				echo '<tr><th><h3>Include Posts</h3></th>';
					echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_posts_checkbox' value='1' "
						.checked(1, $this->jedi_import_options['include_posts'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_posts']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_media'] ) {
				echo '<tr><th><h3>Include Media</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_media_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_media'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_media']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_options'] ) {
				echo '<tr><th><h3>Include Options</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_options_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_options'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_options']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_css'] ) {
				echo '<tr><th><h3>Include CSS</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_css_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_css'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_css']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_menus'] ) {
				echo '<tr><th><h3>Include Menus</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_menus_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_menus'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_menus']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_homepage'] ) {
				echo '<tr><th><h3>Include Homepage</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_homepage_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_homepage'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_homepage']['text']);
					echo '</td>';
				echo '</tr>';
			}
			if( $this->jedi_apprentice_settings['include_widgets'] ) {
				echo '<tr><th><h3>Include Widgets</h3></th>';
				echo "<td class='jedi_import_checkbox'><input type='checkbox' name='jedi_import_widgets_checkbox' value='1' "
					.checked(1, $this->jedi_import_options['include_widgets'], false)." /></td>";
					echo '<td class="jedi_doc_text">' . strip_tags($jedi_documentation['include_widgets']['text']);
					echo '<span class="jedi_apprentice_checkbox_description"><br>(Notice: This will overwrite all current widget settings)</span>';
					echo '</td>';
				echo '</tr>';
			}
		echo '</table>';
	}


	function jedi_import_posts_checkbox() {
		echo "<input type='checkbox' name='jedi_import_posts_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_posts'], false)." />";
	}
	function jedi_import_media_checkbox() {
		echo "<input type='checkbox' name='jedi_import_media_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_media'], false)." />";
	}
	function jedi_import_options_checkbox() {
		echo "<input type='checkbox' name='jedi_import_options_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_options'], false)." />";
		echo '<span class="jedi_apprentice_checkbox_description">(Notice: This option will overwrite all the current Divi Theme Options and Divi Customizer Settings)</span>';
	}
	function jedi_import_css_checkbox() {
		echo "<input type='checkbox' name='jedi_import_css_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_css'], false)." />";
	}
	function jedi_import_menus_checkbox() {
		echo "<input type='checkbox' name='jedi_import_menus_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_menus'], false)." />";
	}
	function jedi_import_homepage_checkbox() {
		echo "<input type='checkbox' name='jedi_import_homepage_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_homepage'], false)." />";
	}
	function jedi_import_widgets_checkbox() {
		echo "<input type='checkbox' name='jedi_import_widgets_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['include_widgets'], false)." />";
		echo '<span class="jedi_apprentice_checkbox_description">(Notice: This will overwrite all current widget settings)</span>';
	}


	/**
	 * Documentation Page for JEDI Apprentice
	 **/
	function jedi_apprentice_documentation_page() {

		echo '<div class="jedi_documentation_page">';
	 	echo '<h2>'.$this->jedi_apprentice_settings['installer_name'].' - Documentation</h2>';

		$jedi_documentation = $this->jedi_apprentice_settings['custom_documentation'];

		foreach( $jedi_documentation as $doc_item ) {
			echo '<div class="jedi_documentation_item">';
			echo '<h4>'.$doc_item['heading'].'</h4>';
			echo stripslashes($doc_item['text']);
			echo '</div> <!-- END div.jedi_documentation_item -->';
		}

	 	echo '</div> <!-- END div.jedi_documentation_page -->';
	}



	/**
	 * Support Page for JEDI Apprentice
	 **/
	function jedi_apprentice_support_page() {

		if(isset($_POST['jedi_support_save'])) { // button name
			// Update Database Values
			$this->jedi_import_options['enable_logging'] = $_POST['jedi_enable_logging_checkbox'];
			update_option('jedi_import_options', $this->jedi_import_options);
		}

		if(isset($_POST['jedi_support_reset'])) { // button name
			if( 'Reset Import Settings' == $_POST['submit'] ) {
				delete_option('jedi_import_options');
				delete_option('jedi_apprentice_settings');

				$reset_jedi_apprentice = new JEDI_Apprentice_Admin;
				unset($reset_jedi_apprentice);
				echo '<div class="jedi_settings_reset">';
					echo '<p>Import Settings Have Been Reset To Defaults</p>';
				echo '</div>';

				$this->jedi_import_options = get_option('jedi_import_options');
			}
		}


		// Display Options Form
		$form_action = 'action="admin.php?page=' . $this->jedi_apprentice_menu_slug . '"';
?>
			<div class="jedi_support_options_container">
				<form <?php $form_action ?> method="POST">
					<input type="hidden" value="true" name="jedi_support_save" id="jedi_support_form"/>
					<hr>
					<?php settings_fields($this->jedi_support_section_id);
					do_settings_sections($this->jedi_support_settings_page);
					submit_button('Save Changes'); ?>
				</form>
			</div>

			<hr>
			<form <?php $form_action ?> method="POST">
				<input type="hidden" value="true" name="jedi_support_reset" id="jedi_support_form"/>
				<?php submit_button( 'Reset Import Settings' ); ?>
			</form>

<?php
	}


	/**
	 * Builds all the fields for the Support Page
	 **/
	function admin_menu_support_options() {

		// Need Some Help? Section
		add_settings_section(
			$this->jedi_support_section_id.'_info',			// ID
			__('Need Some Help?', 'jedi-master' ),			// Title
			array($this, 'jedi_support_info'),				// Callback Function
			$this->jedi_support_settings_page );			// Settings Page

		// Support Options Section
		add_settings_section(
			$this->jedi_support_section_id,					// ID
			__('Tracking Down Bugs?', 'jedi-apprentice' ),		// Title
			array($this, 'jedi_support_options_callback'),	// Callback Function
			$this->jedi_support_settings_page );			// Settings Page

		// Enable Logging Checkbox
		add_settings_field(
			'jedi_enable_logging_checkbox', 				// ID,
			__('Enable Logging', 'jedi-apprentice'),				// Title
			array($this, 'jedi_enable_logging_checkbox'),	// Callback Function
			$this->jedi_support_settings_page, 				// Settings Section Page
			$this->jedi_support_section_id ); 				// Settings Section ID
		register_setting($this->jedi_support_section_id, 'jedi_enable_logging_checkbox');

	}

	/**
	 * Support Information & Links
	 **/
	function jedi_support_info() {
		echo '<div class="jedi_admin_support_info">';
		echo stripslashes($this->jedi_apprentice_settings['support_message']);
		echo '</div><hr>';
	} // END jedi_support_info()

	/**
	 * Support Page - Helpful Text
	 **/
	function jedi_support_options_callback() {
		//_e( 'Jerry\'s Easy Demo Import', 'jedi-apprentice' );
	}

	/**
	 * Checkbox: Enable Logging
	 **/
	function jedi_enable_logging_checkbox() {
		echo "<input type='checkbox' name='jedi_enable_logging_checkbox' value='1' "
			.checked(1, $this->jedi_import_options['enable_logging'], false)." />";
		$this->jedi_beacon->display_log_link();

	}
} // END JEDI_Apprentice_Admin
endif;