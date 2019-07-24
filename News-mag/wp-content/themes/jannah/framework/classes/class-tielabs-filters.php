<?php
/**
 * Filters
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


class TIELABS_FILTERS {

	/**
	 * Runs on class initialization. Adds filters and actions.
	 */
	function __construct() {

		// Add Support for Shortcodes in the terms descriptions
		add_filter( 'term_description', 'do_shortcode' );

		add_action( 'init',                  array( $this, 'redirect_random_post' ) );
		add_action( 'wp_footer',             array( $this, 'footer_inline_scripts' ), 999 );
		add_action( 'wp_footer',             array( $this, 'footer_misc'  ) );
		add_action( 'wp_footer',             array( $this, 'popup_module' ) );
		add_filter( 'gettext',               array( $this, 'theme_translation' ), 10, 3 );
		add_action( 'enqueue_embed_scripts', array( $this, 'lazyload_embed_iframe' ) );

		add_filter( 'pre_get_posts',         array( $this, 'search_filters' ) );
		add_filter( 'get_avatar',            array( $this, 'lazyload_avatar' ) );
		add_filter( 'TieLabs/cache_key',     array( $this, 'cache_key' ) );
		add_filter( 'widget_tag_cloud_args', array( $this, 'tag_widget_limit' ) );
		add_filter( 'widget_title',          array( $this, 'tagcloud_widget_title' ), 10, 3 );
		add_filter( 'login_headerurl',       array( $this, 'dashboard_login_logo_url' ) );
		add_filter( 'login_head',            array( $this, 'dashboard_login_logo' ) );
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ), 15 );
		add_filter( 'wp_link_pages_args',    array( $this, 'pages_next_and_number' ) );
		add_filter( 'excerpt_more',          array( $this, 'excerpt_more' ) );
		add_filter( 'the_content_more_link', array( $this, 'read_more_button' ) );
		add_filter( 'the_content',           array( $this, 'shortcodes_notice' ) );
		add_filter( 'get_the_excerpt',       array( $this, 'post_excerpt' ), 9 );
		add_filter( 'upload_mimes',          array( $this, 'mimes_types' ), 1, 1 );
		add_filter( 'wp_kses_allowed_html',  array( $this, 'lazy_load_allow_attrs' ), 10, 2 );

		add_filter( 'TieLabs/exclude_content',            'TIELABS_HELPER::strip_shortcodes' );
		add_filter( 'wp_get_attachment_image_src',        array( $this, 'gif_image' ), 10, 4 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'lazyload_image_attributes' ), 8, 3 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'small_thumb_image_class' ), 10, 3 );

	}


	/**
	 * Add notice for the shortcodes plugin
	 */
	function shortcodes_notice( $content ){

		// Don't show the message if this is excerpt OR backend OR AMP
		if(
				is_admin() ||
				( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ||
				in_array( 'parse_request', $GLOBALS['wp_current_filter'] ) ||
				in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ){
			return $content;
		}

		$message = '';

		// Timetable and Event Schedule Plugin
		if( ! TIELABS_MPTIMETABLE_IS_ACTIVE ){

			if( strpos( $content, '[mp-timetable' ) !== false ){

				$message .= TIELABS_HELPER::notice_message( sprintf(
					esc_html__( 'This section contains some shortcodes that requries the %2$s%1$s%3$s Plugin.', TIELABS_TEXTDOMAIN ),
					'<strong>Timetable and Event Schedule</strong>',
					'<a href="https://wordpress.org/plugins/mp-timetable/" target="_blank" rel="noopener">',
					'</a>'
				), false );
			}
		}

		// Contact Form 7 Plugin
		if( ! class_exists( 'WPCF7' ) ){

			if( strpos( $content, '[contact-form-7' ) !== false ){

				$message .= TIELABS_HELPER::notice_message( sprintf(
					esc_html__( 'This section contains some shortcodes that requries the %2$s%1$s%3$s Plugin.', TIELABS_TEXTDOMAIN ),
					'<strong>Contact Form 7</strong>',
					'<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank" rel="noopener">',
					'</a>'
				), false) ;
			}
		}

		// Return the content with message
		return apply_filters( 'TieLabs/shortcodes_check', $message, $content ) . $content;
	}


	/*
	 * Theme translations
	 */
	function theme_translation( $translation, $text, $domain ){

		if( $domain == TIELABS_TEXTDOMAIN ){

			$theme_options = get_option( apply_filters( 'TieLabs/theme_options', '' ) );
			$sanitize_text = sanitize_title( htmlspecialchars( $text ) );

			if( ! empty( $theme_options['translations'][ $sanitize_text ] ) ){
				return htmlspecialchars_decode( $theme_options['translations'][ $sanitize_text ] );
			}
		}

		return $translation;
	}


	/*
	 * Add the inline scripts to the Footer
	 */
	function footer_inline_scripts(){

		// Check if we are in an AMP page
		if( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ){
			return;
		}

		// Print the inline scripts if the BWP is active
		if( ! empty( $GLOBALS['tie_inline_scripts'] ) && TIELABS_HELPER::is_js_minified() ){
			echo '<script type="text/javascript">'. $GLOBALS['tie_inline_scripts'] .'</script>';
		}
	}


	/*
	 * Add Misc Footer Conetnt
	 */
	function footer_misc(){

		// Check if we are in an AMP page
		if( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ){
			return;
		}

		// Custom Footer Code
		if ( tie_get_option( 'footer_code' ) ){
			echo do_shortcode( tie_get_option( 'footer_code' ) );
		}

		// Reading Position Indicator
		if ( tie_get_option( 'reading_indicator' ) && is_single() ){
			echo '<div id="reading-position-indicator"></div>';
		}

		// Facebook buttons
		echo '<div id="fb-root"></div>';
	}


	/*
	 * Add the Popup module to the footer
	 */
	function popup_module(){

		// Check if we are in an AMP page
		if( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ){
			return;
		}

		TIELABS_HELPER::get_template_part( 'templates/popup' );
	}


	/**
	 * Exclude post types and categories From Search results
	 */
	function search_filters( $query ){

		if( is_admin() || isset($_GET['post_type']) ) return $query;

		if( is_search() && $query->is_main_query() ){

			// Exclude Post types from search
			if ( ($exclude_post_types = tie_get_option( 'search_exclude_post_types' )) && is_array( $exclude_post_types ) ){

				$args = array(
					'public' => true,
					'exclude_from_search' => false,
				);

				$post_types = get_post_types( $args );

				foreach ( $exclude_post_types as $post_type ){
					unset( $post_types[ $post_type ] );
				}

				$query->set( 'post_type', $post_types );
			}

			// Exclude specific categoies from search
			if ( tie_get_option( 'search_cats' ) ){
				$query->set( 'cat', tie_get_option( 'search_cats' ) );
			}
		}

		return $query;
	}


	/**
	 * Random Article
	 */
	function redirect_random_post(){

		if( isset( $_GET['random-post'] ) && ( tie_get_option( 'top-nav-components_random' ) || tie_get_option( 'main-nav-components_random' ) )){

			$args = array(
				'posts_per_page'      => 1,
				'orderby'             => 'rand',
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			$random_post = new WP_Query ( $args );

			while ( $random_post->have_posts () ){
			  $random_post->the_post();
			  wp_redirect( get_permalink() );
			  exit;
			}
		}
	}


	/**
	 * Widgets Small Image Class
	 *
	 * By default WordPress uses 2 classes attachment-{size} size-{size}
	 * we need to add a general class doesn't linked with the thumb name
	 */
	function small_thumb_image_class( $attr, $attachment, $size = false ) {

		if( ! empty( $size ) && $size == TIELABS_THEME_SLUG.'-image-small' ){
			$attr['class'] .= ' tie-small-image';
		}

		return $attr;
	}


	/**
	 * Lazyload images
	 */
	function lazyload_image_attributes( $attr, $attachment, $size = false ) {

		/*
		 * Check lazyLoad option, Admin page, Feed Page, AMP page
		 * Check if the JetPack Plugin is active & the Photon option is enabled & Current images displayed in the post content
		 */
		if( ! tie_get_option( 'lazy_load' ) || is_admin() || is_feed() ||
			  ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ||
			  ( TIELABS_JETPACK_IS_ACTIVE && in_array( 'photon', Jetpack::get_active_modules() ) && in_array( 'the_content', $GLOBALS['wp_current_filter'] ) ) ){

			return $attr;
		}

		$blank_size  = ( $size == TIELABS_THEME_SLUG.'-image-small' ) ? '-small' : '';
		$blank_image = TIELABS_TEMPLATE_URL.'/assets/images/tie-empty'. $blank_size .'.png';

		$attr['class']   .= ' lazy-img';
		$attr['data-src'] = $attr['src'];
		$attr['src']      = $blank_image;

		unset( $attr['srcset'] );
		unset( $attr['sizes'] );

		return $attr;
	}


	/**
	 * Allow the data-src in the wp_kses function
	 * WooCommerce uses the wp_kses to output the products thumbs.
	 */
	function lazy_load_allow_attrs( $allowedtags, $context ){

		if( tie_get_option( 'lazy_load' ) ){
			$allowedtags['img']['data-src'] = true;
		}

		return $allowedtags;
	}


	/**
	 * Run the lazy load on the embed iframe
	 */
	function lazyload_embed_iframe(){

		if( ! tie_get_option( 'lazy_load' ) ){
			return;
		}

		echo '
			<script>
				document.addEventListener("DOMContentLoaded", function(){
					var x = document.getElementsByClassName("lazy-img"), i;
					for (i = 0; i < x.length; i++) {
						x[i].setAttribute("src", x[i].getAttribute("data-src"));
					}
				});
			</script>
		';
	}


	/**
	 * Avatar Lazyload
	 */
	function lazyload_avatar( $avatar ){

		if( tie_get_option( 'lazy_load' ) && ! is_admin() && ! is_feed() && ! in_array( 'the_content', $GLOBALS['wp_current_filter'] ) && ! in_array( 'woocommerce_review_before', $GLOBALS['wp_current_filter'] ) ){

			// Check if the data-src is added before
			if( strpos( $avatar, 'data-src' ) === false ){
				$avatar = str_replace( '"', "'", $avatar );
				$avatar = str_replace( 'srcset=', 'data-2x=', $avatar );
				$avatar = str_replace( "src='", "src='". TIELABS_TEMPLATE_URL."/assets/images/tie-empty-square.png' data-src='", $avatar );
				$avatar = str_replace( "class='", "class='lazy-img ", $avatar );
			}
		}

		return $avatar;
	}


	/**
	 * Gif images
	 */
	function gif_image( $image, $attachment_id, $size, $icon ){

		if( ! tie_get_option( 'disable_featured_gif' ) ){

			$file_type = wp_check_filetype( $image[0] );

			if( ! empty( $file_type ) && $file_type['ext'] == 'gif' && $size != 'full' ){

				$full_image = wp_get_attachment_image_src( $attachment_id, $size = 'full', $icon );

				# For the avatars we need to keep the original width and height
				if( ! empty( $full_image ) && in_array( 'get_avatar', $GLOBALS['wp_current_filter'] ) ){
					$full_image[1] = $image[1];
					$full_image[2] = $image[2];
				}

				return $full_image;
			}
		}

		return $image;
	}


	/**
	 * Modify Excerpts
	 */
	function post_excerpt( $text = '' ){

		$raw_excerpt = $text;

		if ( '' == $text ){
			$text = get_the_content( '' );
			$text = apply_filters( 'TieLabs/exclude_content', $text );
			$text = strip_shortcodes( $text );
			$text = apply_filters( 'the_content', $text );
			$text = str_replace( ']]>', ']]>', $text );

			$excerpt_length = apply_filters( 'excerpt_length', 55 );
			$excerpt_more   = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
			$text           = wp_trim_words( $text, $excerpt_length, $excerpt_more );
		}

		return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
	}


	/**
	 * Global Cache Key
	 */
	function cache_key( $key ){
		return 'tie-cache-'. TIELABS_HELPER::get_locale() . $key;
	}


	/*
	 * Change the number of tags in the cloud tags
	 */
	function tag_widget_limit( $args ){

		if( isset($args['taxonomy']) && $args['taxonomy'] == 'post_tag' ){
			$args['number'] = 18;
		}

		return $args;
	}


	/**
	 * Remove the default Tag CLoud titles if the title field is empty
	 */
	function tagcloud_widget_title( $title = false, $instance = false, $id_base = false ){

		if( $id_base == 'tag_cloud' && empty( $instance['title'] ) ){
			return false;
		}

		return $title;
	}


	/**
	 * Custom Dashboard login URL
	 */
	function dashboard_login_logo_url(){

		if( tie_get_option( 'dashboard_logo_url' ) ){
			return tie_get_option( 'dashboard_logo_url' );
		}
	}


	/*
	 * Custom Dashboard login page logo
	 */

	function dashboard_login_logo(){

		if( tie_get_option( 'dashboard_logo' ) ){
			echo '<style type="text/css"> .login h1 a {  background-image:url('.tie_get_option( 'dashboard_logo' ).')  !important; background-size: contain; width: 320px; height: 85px; } </style>';
		}
	}



	/*
	 * Remove anything that looks like an archive title prefix ("Archive:", "Foo:", "Bar:").
	 */
	function archive_title( $title ){

		if ( is_category() ) {
			return single_cat_title( '', false );
		}
		elseif ( is_tag() ) {
			return single_tag_title( '', false );
		}
		elseif ( is_author() ) {
			return '<span class="vcard">' . get_the_author() . '</span>';
		}
		elseif ( is_post_type_archive() ) {
			return post_type_archive_title( '', false );
		}
		elseif ( is_tax() ) {
			return single_term_title( '', false );
		}

		return $title;
	}


	/**
	 * Add Number and Next / prev multiple post pages
	 */
	function pages_next_and_number( $args ){

		if( $args['next_or_number'] == 'next_and_number' ){

			global $page, $numpages, $multipage, $more;
			$args['next_or_number'] = 'number';
			$prev = '';
			$next = '';

			if ( $multipage && $more ){
				$i = $page - 1;
				if ( $i && $more ){
					$prev .= _wp_link_page($i);
					$prev .= $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
				}

				$i = $page + 1;
				if ( $i <= $numpages && $more ){
					$next .= _wp_link_page($i);
					$next .= $args['link_before']. $args['nextpagelink'] . $args['link_after'] . '</a>';
				}
			}

			$args['before'] = $args['before'].$prev;
			$args['after'] = $next.$args['after'];
		}
		return $args;
	}


	/**
	 * Excerpt More
	 */
	function excerpt_more( $more ){
		return ' &hellip;';
	}


	/**
	 * Modify the Read More button text and classes
	 */
	function read_more_button() {
		return '<a class="more-link button" href="' . get_permalink() . '">'. esc_html__( 'Read More &raquo;', TIELABS_TEXTDOMAIN ) .'</a>';
	}


	/**
	 * Add Extra Mimes Types
	 */
	function mimes_types( $mime_types ) {

		// Allow this for Administrators and in the backend only.
		if( ! is_admin() || ! current_user_can( 'manage_options' ) ){
			return $mime_types;
		}

		$mime_types['svg']   = 'image/svg+xml';
		$mime_types['eot']   = 'application/vnd.ms-fontobject';
		$mime_types['ttf']   = 'application/x-font-ttf';
		$mime_types['woff']  = 'application/x-font-woff';
		$mime_types['woff2'] = 'application/x-font-woff2';

		return $mime_types;
	}

}

// Single instance.
$TIELABS_FILTERS = new TIELABS_FILTERS();
