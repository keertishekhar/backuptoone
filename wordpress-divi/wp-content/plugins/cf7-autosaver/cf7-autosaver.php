<?php
/**
 * Plugin Name: CF7 Autosaver
 * Plugin URI: http://bishoy.me/wp-plugins/cf7-autosaver/
 * Description: Allows your Contact Form 7 to be auto-saved, also will have an option to auto-fill with Facebook!
 * Version: 1.0.0
 * Author: Bishoy A.
 * Author URI: http://bishoy.me
 * Requires at least: 2.0.0
 * Tested up to: 4.3.1
 *
 * Text Domain: cf7-autosaver
 * Domain Path: /languages/
 *
 * @package CF7_Autosaver
 * @category Core
 * @author Bishoy A.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of CF7_Autosaver to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object CF7_Autosaver
 */
function CF7_Autosaver() {
	return CF7_Autosaver::instance();
} // End CF7_Autosaver()

add_action( 'plugins_loaded', 'CF7_Autosaver' );

/**
 * Main CF7_Autosaver Class
 *
 * @class CF7_Autosaver
 * @version	1.0.0
 * @since 1.0.0
 * @package	CF7_Autosaver
 * @author Bishoy A.
 */
final class CF7_Autosaver {
	/**
	 * CF7_Autosaver The single instance of CF7_Autosaver.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	/**
	 * Plugin Base Name.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_basename;
	// Admin - End
	
	/**
	 * Whether or not there is a notice to be displayed
	 * @var boolean
	 * @access private
	 * @since  1.0.1
	 */
	private $_has_notice;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->token 			= 'cf7-autosaver';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->plugin_basename  = plugin_basename( __FILE__ );
		$this->version 			= '1.0.1';

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$this->set_notice( array( 'type' => 'error', 'msg' => 'Contact Form 7 plugin is not installed/activated.' ) );
		} else {

			// Admin - Start
			require_once( 'classes/class-cf7-autosaver-settings.php' );
				$this->settings = CF7_Autosaver_Settings::instance();

			if ( is_admin() ) {
				require_once( 'classes/class-cf7-autosaver-admin.php' );
				$this->admin = CF7_Autosaver_Admin::instance();
			}
			// Admin - End

			register_activation_hook( __FILE__, array( $this, 'install' ) );

			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueues' ) );

			if ( $this->_has_notice ) {
				add_action( 'admin_notices', array( $this, 'cf7_notice' ) );
			}
		}

	} // End __construct()

	/**
	 * Main CF7_Autosaver Instance
	 *
	 * Ensures only one instance of CF7_Autosaver is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see CF7_Autosaver()
	 * @return Main CF7_Autosaver instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'cf7-autosaver', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Sets a cache for notice
	 * @param array $notice
	 */
	public function set_notice( array $notice ) {
		wp_cache_set( 'cf7as_admin_notice_available', $notice );

		$this->_has_notice = true;
	} // End set_notice

	/**
	 * Cloning is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Styles and Scripts enqueues
	 * @access public
	 * @since  1.0.0
	 */
	public function enqueues() {
		$as_options = get_option( 'cf7-autosaver-autosaver' );
		$fb_options = get_option( 'cf7-autosaver-fill-with-facebook' );
		
		wp_register_script( 'cf7as_scripts', $this->plugin_url . 'assets/js/scripts.js', array( 'jquery', 'cf7as_jquery_sisyphus', 'contact-form-7' ), filemtime( $this->plugin_path . 'assets/js/scripts.js' ), true );

		wp_localize_script( 
			'cf7as_scripts', 
			'cf7as', 
			array(
				'forms'          => $as_options['cf7_autosaver_form_select'],
				'auto_release'   => ( ! empty( $as_options['cf7as_auto_release'] ) ? true : false ),
				'location_based' => ( ! empty( $as_options['cf7as_location_based'] ) ? true : false ),
				'fb_forms'       => $fb_options['cf7_fb_form_select'],
				'fb'             => $fb_options['cf7as_facebook_app'],
				'fb_text'        => $fb_options['cf7as_facebook_link_text'],
				'fb_above'       => ( ! empty( $fb_options['cf7as_facebook_link_above'] ) ? true : false ),
			)
		);

		wp_enqueue_script( 'cf7as_jquery_sisyphus', $this->plugin_url . 'assets/js/sisyphus.min.js', array( 'jquery', 'contact-form-7' ), filemtime( $this->plugin_path . 'assets/js/sisyphus.min.js' ), true );
		wp_enqueue_script( 'cf7as_scripts' );

		wp_enqueue_style( 'cf7as_styles', plugins_url( 'assets/css/style.css', __FILE__ ) );
	} // End enqueues

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()

	/**
	 * HTML for Notice if the Contact form 7 is inactive
	 * @access public
	 * @since  1.0.1
	 */
	public function cf7_notice() {

		$notice = wp_cache_get( 'cf7as_admin_notice_available' );

		if ( empty( $notice ) ) return false;
	    ?>
	    <div class="<?php echo $notice['type']; ?>">
	        <p><?php _e( '<strong>CF7 Autosaver:</strong> ' . $notice['msg'], 'cf7as' ); ?></p>
	    </div>
	    <?php
	}// End cf7_inactive
} // End Class
