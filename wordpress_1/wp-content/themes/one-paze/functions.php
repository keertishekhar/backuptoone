<?php
/**
 * One Paze functions and definitions
 *
 * @package One Paze
 */
 
 if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'one_paze_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function one_paze_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on One Pager, use a find and replace
	 * to change 'one-paze' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'one-paze', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'one-paze' ),
        'footer-menu' => esc_html__( 'Footer Menu', 'one-paze' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	/** Declare Woocommerce Compatibility **/
	add_theme_support( 'woocommerce' );
}
endif; // one_paze_setup
add_action( 'after_setup_theme', 'one_paze_setup' );

/** Adding Editor Styles **/
function one_paze_add_editor_styles() {
    add_editor_style( get_template_directory_uri().'/css/custom-editor-style.css' );
}

add_action( 'admin_init', 'one_paze_add_editor_styles' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function one_paze_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'one_paze_content_width', 640 );
}
add_action( 'after_setup_theme', 'one_paze_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function one_paze_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'one-paze' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
    
    /** Header Text Section **/
    register_sidebar( array(
		'name'          => __( 'Header Text', 'one-paze' ),
		'id'            => 'header_text',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
    
    /** Footer Social Links Section **/
    register_sidebar( array(
		'name'          => __( 'Footer Social Links', 'one-paze' ),
		'id'            => 'footer_social_links',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
    
    /** Footer Social Links Section **/
    register_sidebar( array(
		'name'          => __( 'Google Map (Home Page)', 'one-paze' ),
		'id'            => 'contact_section_map',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'one_paze_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function one_paze_scripts() {
	wp_enqueue_style( 'one-paze-style', get_stylesheet_uri() );
    
    wp_enqueue_style( 'one-paze-sidetogglemenu-css', get_template_directory_uri() . '/css/sidetogglemenu.css');
    
    wp_enqueue_style( 'one-paze-wow-css', get_template_directory_uri() . '/css/animate.css');
    
    wp_enqueue_style( 'one-paze-fontawesome-css', get_template_directory_uri() . '/css/faw/css/font-awesome.css');
    
    wp_enqueue_style( 'one-paze-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,300,600,700');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    wp_enqueue_script( 'one-paze-sidetogglemenu-js', get_template_directory_uri(). '/js/sidetogglemenu.js', array('jquery') );
    
    wp_enqueue_script( 'one-paze-scrollto-js', get_template_directory_uri(). '/js/jquery.scrollTo.js', array('jquery') );
    
    wp_enqueue_script( 'one-paze-localscroll-js', get_template_directory_uri(). '/js/jquery.localScroll.js', array('jquery', 'one-paze-scrollto-js') );
    
    wp_enqueue_script( 'one-paze-parallax-js', get_template_directory_uri(). '/js/jquery.parallax.js', array('jquery') );

	wp_enqueue_script( 'one-paze-mixitup-js', get_template_directory_uri(). '/js/jquery.mixitup.js', array('jquery') );
    
    wp_enqueue_script( 'one-paze-jquery-nav', get_template_directory_uri(). '/js/jquery.nav.js', array('jquery') );
    
    wp_enqueue_script( 'one-paze-bxslider', get_template_directory_uri(). '/js/bxslider/jquery.bxslider.js', array('jquery'));
    
    wp_enqueue_script( 'one-paze-wow-js', get_template_directory_uri(). '/js/wow.js'); 
    
    wp_enqueue_script( 'one-paze-custom', get_template_directory_uri() . '/js/custom.js', array('jquery', 'one-paze-wow-js', 'one-paze-jquery-nav', 'one-paze-bxslider', 'one-paze-localscroll-js', 'one-paze-parallax-js', 'one-paze-mixitup-js', 'one-paze-sidetogglemenu-js'));

    $show_menu_on = esc_attr(get_theme_mod('menu_show_on_click', 'top'));
    $tgl_id = '';
    $tgl_id = ($show_menu_on == 'left') ? 'left' : 'right';

    wp_localize_script( 'one-paze-custom', 'OnepazeObj', array( 'down_arrow_src' => esc_url( get_template_directory_uri().'/images/toggledown.png' ), 'position' => esc_attr($tgl_id) ) );
}
add_action( 'wp_enqueue_scripts', 'one_paze_scripts' );

/** Add Image Sizes **/
add_image_size('one-paze-port-thumb',363,272,true);
add_image_size('one-paze-blog-thumb',270,127,true);
add_image_size('one-paze-team-thumb',175,175,true);
add_image_size('one-paze-testimonial-thumb',79,79,true);
add_image_size('one-paze-innerpage-thumb', 869, 342, true);
add_image_size('one-paze-blog-post-thumb', 600, 342, true);
add_image_size('one-paze-slider-image-size', 1349, 670, true);
add_image_size('one-paze-blog-large-image', 805, 355, true);
add_image_size('one-paze-blog-medium-image', 380, 252, true);

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load One Pager Custom Functions.
 */
require get_template_directory() . '/inc/one-paze-functions.php';

/**
 * Load Custom Customizer Functionality
 */

require get_template_directory() . '/inc/admin/one-paze-customizer.php';

/**
 * Load Dynamic Styles
 */
require get_template_directory() . '/css/dynamic-styles.php';

/**
 * Load Welcome Page
 */
require get_template_directory() . '/welcome/welcome.php';

// Add specific CSS class by filter
add_filter( 'body_class', 'one_paze_add_unique_class_names' );
function one_paze_add_unique_class_names( $classes ) {
	global $post;
	$port_cat = get_theme_mod('portfolio_section_category', 0);

	if(is_single()) {
		$cats = get_the_category($post);
		$cat_arr = array();

		foreach($cats as $cat) :
		    $cat_arr[] = $cat->term_id;
		endforeach;

		if($port_cat != 0 && in_array($port_cat, $cat_arr) ) {
			$classes[] = 'single-pfolio';
		}
	} elseif(is_archive()) {
		$cat = get_query_var('cat');
		if($port_cat != 0 && $cat == $port_cat ){
			$classes[] = 'arch-pfolio';
		}
	}
	// return the $classes array
	return $classes;
}