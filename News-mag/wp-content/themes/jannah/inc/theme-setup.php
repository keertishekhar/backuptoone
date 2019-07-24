<?php
/**
 * Theme Custom Styles
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


/*
 * Setup Theme
 */
add_action( 'after_setup_theme', 'jannah_theme_setup' );
function jannah_theme_setup(){

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Switch default core markup for comment form, and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption'
	) );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( TIELABS_THEME_SLUG.'-image-small', 220, 150, true );
	add_image_size( TIELABS_THEME_SLUG.'-image-large', 390, 220, true );
	add_image_size( TIELABS_THEME_SLUG.'-image-post',  780, 405, true );
	add_image_size( TIELABS_THEME_SLUG.'-image-grid',  780, 500, true );
	add_image_size( TIELABS_THEME_SLUG.'-image-full',  1170, 610,true );

	// Add Support for the Arqam Lite plugin.
	add_theme_support( 'Arqam_Lite' );

	// Gutenberg
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
	 */
	if( ! tie_get_option( 'disable_editor_styles' ) ){
		add_editor_style( 'assets/css/editor-style.css' );
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( TIELABS_TEXTDOMAIN, TIELABS_TEMPLATE_PATH . '/languages' );

	// The theme uses wp_nav_menu() in multiple locations.
	register_nav_menus( array(
		'top-menu'    => esc_html__( 'Secondry Nav Menu', TIELABS_TEXTDOMAIN ),
		'primary'     => esc_html__( 'Main Nav Menu',     TIELABS_TEXTDOMAIN ),
		'404-menu'    => esc_html__( '404 Page menu',     TIELABS_TEXTDOMAIN ),
		'footer-menu' => esc_html__( 'Footer Navigation', TIELABS_TEXTDOMAIN ),
	));
}



/*
 * Enqueue IE Scripts and Styles
 */
add_action( 'wp_enqueue_scripts', 'jannah_enqueue_IE_assets' );
function jannah_enqueue_IE_assets(){

	global $is_IE;

	if( ! $is_IE ){
		return;
	}

	preg_match( '/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches );

	if( empty( $matches ) ){
		return;
	}

	if( is_array( $matches ) && count( $matches ) < 2 ){
		preg_match( '/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches );
	}

	$version = $matches[1];

	// IE 10 && IE 11
	if( $version <= 11 ){
		wp_enqueue_style( 'tie-css-ie-11', TIELABS_TEMPLATE_URL .'/assets/css/ie-lte-11.css', array(), TIELABS_DB_VERSION );
		wp_enqueue_script( 'tie-js-ie',    TIELABS_TEMPLATE_URL . '/assets/js/ie.js', array( 'jquery' ), TIELABS_DB_VERSION, true );
	}

	// IE 10
	if ( $version == 10 ) {
		wp_enqueue_style( 'tie-css-ie-10-only', TIELABS_TEMPLATE_URL.'/assets/css/ie-10.css', array(), TIELABS_DB_VERSION );
	}
	// < IE 10
	elseif ( $version < 10 ) {
		wp_enqueue_style( 'tie-css-ie-10', TIELABS_TEMPLATE_URL.'/assets/css/ie-lt-10.css', array(), TIELABS_DB_VERSION );
	}
}



/*
 * Register Main Scripts and Styles
 */
add_action( 'wp_enqueue_scripts', 'jannah_enqueue_scripts', 20 );
function jannah_enqueue_scripts(){

	$min = TIELABS_STYLES::is_minified();

	// Main Scripts file
	wp_enqueue_script( 'tie-scripts', TIELABS_TEMPLATE_URL . '/assets/js/scripts'. $min .'.js', array( 'jquery' ), TIELABS_DB_VERSION, true );

	// Sliders
	wp_register_script( 'tie-js-sliders', TIELABS_TEMPLATE_URL . '/assets/js/sliders'. $min .'.js', array( 'jquery' ), TIELABS_DB_VERSION, true );

	// Parallax
	wp_register_script( 'tie-js-parallax', TIELABS_TEMPLATE_URL . '/assets/js/parallax.js', array( 'jquery', 'imagesloaded' ), TIELABS_DB_VERSION, true );

	// Main style.css file
	wp_enqueue_style( 'tie-css-styles',  TIELABS_TEMPLATE_URL.'/assets/css/style'. $min .'.css', array(), TIELABS_DB_VERSION );

	// iLightBox css file
	wp_enqueue_style( 'tie-css-ilightbox', TIELABS_TEMPLATE_URL . '/assets/css/ilightbox/'. tie_get_option( 'lightbox_skin', 'dark' ) .'-skin/skin.css', array(), TIELABS_DB_VERSION );

	// Mp-Timetable css file
	if ( TIELABS_MPTIMETABLE_IS_ACTIVE ){
		wp_enqueue_style( 'tie-css-mptt', TIELABS_TEMPLATE_URL.'/assets/css/mptt'. $min .'.css', array(), TIELABS_DB_VERSION );
	}

  // Reading Position Indicator
  if( is_singular() && tie_get_option( 'reading_indicator' ) ){
  	wp_enqueue_script( 'imagesloaded' );
  }

	// Queue Comments reply js
	if ( is_singular() && comments_open() && get_option('thread_comments') ){
		wp_enqueue_script( 'comment-reply' );
	}

	// Prevent TieLabs shortcodes plugin from loading its js and Css files
	add_filter( 'tie_plugin_shortcodes_enqueue_assets', '__return_false' );
}
