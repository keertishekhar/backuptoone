<?php
/**
 * Theme functions and definitions
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*
 * Works with PHP 5.3 or Later
 */
if ( version_compare( phpversion(), '5.3', '<' ) ) {
	require get_template_directory() . '/framework/functions/php-disable.php';
	return;
}


/*
 * Define Constants
 */
define( 'TIELABS_DB_VERSION',            '3.2.0' );
define( 'TIELABS_THEME_SLUG',            'jannah' );
define( 'TIELABS_TEXTDOMAIN',            'jannah' );
define( 'TIELABS_THEME_ID',              '19659555' );
define( 'TIELABS_TEMPLATE_PATH',         get_template_directory() );
define( 'TIELABS_TEMPLATE_URL',          get_template_directory_uri() );
define( 'TIELABS_BBPRESS_IS_ACTIVE',     class_exists( 'bbPress' ));
define( 'TIELABS_JETPACK_IS_ACTIVE',     class_exists( 'Jetpack' ));
define( 'TIELABS_BWPMINIFY_IS_ACTIVE',   class_exists( 'BWP_MINIFY' ));
define( 'TIELABS_REVSLIDER_IS_ACTIVE',   class_exists( 'RevSlider' ));
define( 'TIELABS_CRYPTOALL_IS_ACTIVE',   class_exists( 'CPCommon' ));
define( 'TIELABS_BUDDYPRESS_IS_ACTIVE',  class_exists( 'BuddyPress' ));
define( 'TIELABS_LS_Sliders_IS_ACTIVE',  class_exists( 'LS_Sliders' ));
define( 'TIELABS_FB_INSTANT_IS_ACTIVE',  class_exists( 'Instant_Articles_Wizard' ));
define( 'TIELABS_WOOCOMMERCE_IS_ACTIVE', class_exists( 'WooCommerce' ));
define( 'TIELABS_MPTIMETABLE_IS_ACTIVE', class_exists( 'Mp_Time_Table' ));
define( 'TIELABS_AMP_IS_ACTIVE',         function_exists( 'amp_init' ));
define( 'TIELABS_WPUC_IS_ACTIVE',        function_exists( 'run_MABEL_WPUC' ));
define( 'TIELABS_ARQAM_IS_ACTIVE',       function_exists( 'arqam_init' ));
define( 'TIELABS_SENSEI_IS_ACTIVE',      function_exists( 'Sensei' ));
define( 'TIELABS_TAQYEEM_IS_ACTIVE',     function_exists( 'taqyeem_get_option' ));
define( 'TIELABS_EXTENSIONS_IS_ACTIVE',  function_exists( 'jannah_extensions_shortcodes_scripts' ));


/*
 * Theme Settings Option Field
 */
add_filter( 'TieLabs/theme_options', 'jannah_theme_options_name' );
function jannah_theme_options_name( $option ){
	return 'tie_jannah_options';
}


/*
 * Translatable Theme Name
 */
add_filter( 'TieLabs/theme_name', 'jannah_theme_name' );
function jannah_theme_name( $option ){
	return esc_html__( 'Jannah', TIELABS_TEXTDOMAIN );
}


/**
 * Default Theme Color
 */
add_filter( 'TieLabs/default_theme_color', 'jannah_theme_color' );
function jannah_theme_color(){
	return '#0088ff';
}


/*
 * Import Files
 *
 * With locate_template you can override these files with child theme
 */
locate_template( 'framework/framework-load.php', true, true );
locate_template( 'inc/theme-setup.php', true, true );
locate_template( 'inc/style.php',       true, true );
locate_template( 'inc/deprecated.php',  true, true );
locate_template( 'inc/widgets.php',     true, true );

if( is_admin() ){
	locate_template( 'inc/help-links.php',   true, true );
}

/**
 * Load the Sliders.js file in the Post Slideshow shortcode
 */
if( ! function_exists( 'jannah_enqueue_js_slideshow_sc' ) ){

	add_action( 'tie_extensions_sc_before_post_slideshow', 'jannah_enqueue_js_slideshow_sc' );
	function jannah_enqueue_js_slideshow_sc(){
		wp_enqueue_script( 'tie-js-sliders' );
	}
}


/*
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
add_action( 'template_redirect', 'jannah_content_width', 0 );
function jannah_content_width() {

	$content_width = ( TIELABS_HELPER::has_sidebar() ) ? 708 : 1220;

	/**
	 * Filter content width of the theme.
	 */
	$GLOBALS['content_width'] = apply_filters( 'TieLabs/content_width', $content_width );
}

