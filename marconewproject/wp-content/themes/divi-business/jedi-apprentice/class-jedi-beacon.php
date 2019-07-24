<?php
/**
 * J.E.D.I. | Jerry's Easy Demo Import
 *
 * Class: JEDI_Beacon
 * This class handles the communications needs of the plugin.
 * (Displaying Information, Logging Details, Error Handling)
 *
 * @package		jedi-master
 * @author		Jerry Simmons <jerry@montereypremier.com>
 * @copyright	2017 Jerry Simmons
 * @license		GPL-2.0+
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( 'JEDI_Beacon' ) ) :
class JEDI_Beacon {

	public $log_file;
	public $logging_enabled;

	/**
	 *
	 **/
	public function __construct() {
		if( class_exists( 'JEDI_Master_Admin' ) ) {

			/**
			 * Check If Logging Is Enabled
			 **/
			$jedi_master_settings = get_option('jedi_master_settings');
			if( $jedi_master_settings['enable_logging'] ) {
				$this->logging_enabled = true;
			}

			/**
			 * Set JEDI Master Log File
			 **/
			$this->log_file = plugin_dir_path(__FILE__) . 'jedi-master.log';

		} else {

			/**
			 * Check If Logging Is Enabled
			 **/
			$jedi_import_options = get_option('jedi_import_options');
			if( $jedi_import_options['enable_logging'] ) {
				$this->logging_enabled = true;
			}

			/**
			 * Set JEDI Apprentice Log File
			 **/
			$this->log_file = plugin_dir_path(__FILE__) . 'jedi-apprentice.log';
		}
	}


	/**
	 * Displays information for the user to view
	 *
	 * @param string $label Provides a label for the data to be displayed
	 * @param mixed $value Optional. Adds additional data to the label.
	 * @param bool $autostyle Optional. Provide style automatically or not.
	 **/
	public function jedi_report( $label, $value='', $autostyle=true ) {
		if( is_object($value) || is_array($value) ) {
			$value = serialize( $value );
		}
		ob_flush(); flush();
		if( $autostyle ) {
			$html_style = '<p style="font-size: 110%; padding-left: 25px">';
			$html_label = '<span class="jedi_processing_label" style="">'.$label.'</span>';
			$html_value = $value ? ': <span class="jedi_processing_value" style="font-weight: bold;">'.$value.'</span>' : '';
			echo $html_style.$html_label.$html_value.'</p>';
		} else {
			echo $label.$value;
		}
		$this->jedi_log($label, $value);
		ob_flush(); flush();
	} // END jedi_report()


	/**
	 * Writes logging information to file
	 *
	 * @param string $log_trigger Provides information about what calls the function.
	 * @param mixed $log_data Optional. Provides extra data about the logged event.
	 **/
	public function jedi_log( $log_trigger, $log_data='' ) {

		if( $this->logging_enabled ) {
			if( is_wp_error( $log_data ) ) {
				$log_data = $log_data->get_error_message();
			} elseif( is_array( $log_data ) ) {
				$log_data = serialize( $log_data );
			}

			if( '' == $log_data ) {
				$jedi_log = date('Y-m-d H:i:s').' | '.$log_trigger.PHP_EOL.PHP_EOL;
			} else {
				$jedi_log = date('Y-m-d H:i:s').' | '.$log_trigger.PHP_EOL.$log_data.PHP_EOL.PHP_EOL;
			}
			file_put_contents( $this->log_file, $jedi_log, FILE_APPEND );
		}
	}

	/**
	 * Check if the log file exists
	 * If it does, then display a link to it
	 **/
	public function display_log_link() {
		if( file_exists( $this->log_file ) ) {
			echo '<p>Log File: <a href="' . plugin_dir_url( $this->log_file ) . basename( $this->log_file )
				. '" target="_blank">' . basename( $this->log_file ) . '</a></p>';
		}
	}


	/**
	 * Log Errors That Cause A JEDI Process To Die
	 * Then display error message to user
	 *
	 * @param string $error_message Optional. The error message.
	 * @param mixed $error_data Optional. Provides extra data about the error.
	 **/
	public function order_66( $error_message='', $error_data='' ) {
		$this->jedi_log( 'order_66', $error_message );

		if( is_wp_error($error_data) ) {
			$this->jedi_log( 'Error Codes', $error_data->errors );
			$this->jedi_log( 'Error Data', $error_data->error_data );
		} else {
			$this->jedi_log( 'Error Data: ', $error_data );
		}

		wp_die( $error_message );
	}

	public function get_system_info() {
		$system_info = array();

		// OS Info
		$system_info['php_os'] = PHP_OS;
		$system_info['php_version'] = PHP_VERSION;

		// INI Settings
		$system_info['allow_url_fopen'] = ini_get('allow_url_fopen');
		$system_info['max_execution_time'] = ini_get('max_execution_time');
		$system_info['max_file_uploads'] = ini_get('max_file_uploads');
		$system_info['memory_limit'] = ini_get('memory_limit');

		// WordPress Info
		$system_info['wp_version'] = get_bloginfo('version');
		$system_info['wp_url'] = get_bloginfo('url');

		// Theme Info
		$wp_theme = wp_get_theme();
		if( is_child_theme() ) {
			$system_info['wp_theme_name'] = $wp_theme->parent()->get( 'Name' );
			$system_info['wp_theme_version'] = $wp_theme->parent()->get( 'Version' );
			$system_info['wp_childtheme_name'] = $wp_theme->get( 'Name' );
			$system_info['wp_childtheme_version'] = $wp_theme->get( 'Version' );
		} else {
			$system_info['wp_theme_name'] = $wp_theme->get( 'Name' );
			$system_info['wp_theme_version'] = $wp_theme->get( 'Version' );
		}

		return $system_info;
	} // END get_system_info()

	/**
	 * Clean up list of Post ID's provided by user input
	 * Called by classes JEDI_Master_Admin and JEDI_Master_Export_Free
	 **/
	public function jedi_process_post_id_list( $excluded_id_list = '', $return_array = false ) {
		if( empty( $excluded_id_list ) ) { return ''; }

		// Remove any whitespace
		$excluded_id_list = str_replace( ' ', '', $excluded_id_list );

		// Remove trailing comma(s)
		if ( substr( $excluded_id_list, -1 ) === ',' ) {
			$excluded_id_list = rtrim( $excluded_id_list, ',' );
		}

		$validate_posts = array();
		$validate_posts = explode( ',', $excluded_id_list );

		foreach( $validate_posts as $key => $validate_post ) {
			if( false === get_post_status( $validate_post ) ) {
				unset( $validate_posts[$key] );
			}
		}

		if( !empty( $validate_posts ) ) {
			if( true == $return_array ) {
				$excluded_id_list = $validate_posts;
			} else {
				$excluded_id_list = implode( ',', $validate_posts );
			}
		} else {
			$excluded_id_list = '';
		}

		return $excluded_id_list;
	} // END jedi_process_post_id_list()

} // END JEDI_Beacon
endif;