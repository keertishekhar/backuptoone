<?php
/**
 * AMP
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly



if( ! class_exists( 'TIELABS_AMP' )){

	class TIELABS_AMP{

		/**
		 * __construct
		 *
		 * Class constructor where we will call our filter and action hooks.
		 */
		function __construct(){

			// Disable if the AMP plugin is not active or the theme option is disabled
			if( ! TIELABS_AMP_IS_ACTIVE || ! tie_get_option( 'amp_active' ) ){
				return;
			}

			// Disable the AMP Customizer menu, Control styles from the theme options page.
			add_filter( 'amp_customizer_is_enabled', '__return_false' );

			// Actions
			add_action( 'pre_amp_render_post',    array( $this, 'content_filters' ) );
			add_action( 'amp_post_template_head', array( $this, 'remove_google_fonts' ), 2 );

			// Filters
			add_filter( 'amp_content_max_width',        array( $this, 'content_width' ) );
			add_filter( 'amp_post_template_file',       array( $this, 'templates_path' ), 10, 3 );
			add_filter( 'amp_site_icon_url',            array( $this, 'logo_path' ) );
			add_filter( 'amp_post_article_footer_meta', array( $this, 'meta_taxonomy' ) );
		}


		/**
		 * content_filters
		 *
		 * Add related posts, ads, formats and share buttons to the post content
		 */
		function content_filters(){

			add_filter( 'the_content', array( $this, 'strip_shortcodes' ));
			add_filter( 'the_content', array( $this, 'ads' ));
			add_filter( 'the_content', array( $this, 'share_buttons' ));
			add_filter( 'the_content', array( $this, 'post_formats'  ));
			add_filter( 'the_content', array( $this, 'related_posts' ));
		}


		/**
		 * post_formats
		 */
		function post_formats( $content ){

			$post_format = tie_get_postdata( 'tie_post_head' ) ? tie_get_postdata( 'tie_post_head' ) : 'standard';

			ob_start();

			if( $post_format ){

				// Get the post video
				if( $post_format == 'video' ){

					tie_video();
				}

				// Get post audio
				elseif( $post_format == 'audio' ){

					tie_audio();
				}

				// Get post map
				elseif( $post_format == 'map' ){
					echo tie_google_maps( tie_get_postdata( 'tie_googlemap_url' ));
				}

				// Get post slider
				elseif( $post_format == 'slider' ){

					// Custom slider
					if( tie_get_postdata( 'tie_post_slider' )){
						$slider     = tie_get_postdata( 'tie_post_slider' );
						$get_slider = get_post_custom( $slider );

						if( ! empty( $get_slider['custom_slider'][0] ) ){
							$images = maybe_unserialize( $get_slider['custom_slider'][0] );
						}
					}

					// Uploaded images
					elseif( tie_get_postdata( 'tie_post_gallery' )){
						$images = maybe_unserialize( tie_get_postdata( 'tie_post_gallery' ));
					}

					$ids = array();
					if( ! empty( $images ) && is_array( $images ) ){
						foreach( $images as $single_image ){
							$ids[] = $single_image['id'];
						}
					}

					echo( '[gallery ids="'. implode( ',', $ids ) .'"]');
				}

				// Featured Image
				elseif( has_post_thumbnail() && ( $post_format == 'thumb' ||
		          ( $post_format == 'standard' && ( tie_get_object_option( 'post_featured', 'cat_post_featured', 'tie_post_featured' ) && tie_get_object_option( 'post_featured', 'cat_post_featured', 'tie_post_featured' ) != 'no' )))){

					the_post_thumbnail();
				}
			}

			$output = ob_get_clean();

			if( ! empty( $output ) ){
				$output = '<div class="amp-featured">'. $output .'</div>';
				$content = $output . $content;
			}

			return $content;
		}


		/**
		 * related_posts
		 *
		 * Add related posts below the post content
		 */
		function related_posts( $content ){

			if( tie_get_option( 'amp_related_posts' ) ){

				$args = array(
					'posts_per_page' => tie_get_option( 'amp_related_posts_number', 4 ),
					'post_status'    => 'publish',
				);

				$recent_posts = new WP_Query( $args );

				if( $recent_posts->have_posts() ){

					$output = '
						<div class="amp-related-posts">
							<span>'. esc_html__( 'Related Articles', TIELABS_TEXTDOMAIN ) .'</span>
							<ul>
							';

							while ( $recent_posts->have_posts() ){
								$recent_posts->the_post();
								$output .= '
									<li>
										<a href="' . amp_get_permalink( get_the_ID() ) . '">'. get_the_post_thumbnail( null, TIELABS_THEME_SLUG.'-image-large' ) . get_the_title() .'</a>
									</li>';
							}

							$output .= '
							</ul>
						</div>
					';

					$content = $content . $output;
				}
			}

			return $content;
		}


		/**
		 * share_buttons
		 *
		 * Add the share buttons
		 */
		function share_buttons( $content ){

			if( tie_get_option( 'amp_share_buttons' ) ){

				$share_buttons = '
					<div class="social">
						<amp-social-share type="facebook"
							width="60"
							height="44"
							data-param-app_id='. tie_get_option( 'amp_facebook_app_id' ) .'></amp-social-share>

						<amp-social-share type="twitter"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="gplus"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="pinterest"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="linkedin"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="whatsapp"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="tumblr"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="sms"
							width="60"
							height="44"></amp-social-share>

						<amp-social-share type="email"
							width="60"
							height="44"></amp-social-share>

					</div>
				';

				$content = $content . $share_buttons;
			}

			return $content;
		}


		/**
		 * strip_shortcodes
		 *
		 */
		function strip_shortcodes( $content ){

			$content = preg_replace( '/(\[(padding)\s?.*?\])/', '', $content );
			$content = str_replace( '[/padding]', '', $content );

			return $content;
		}


		/**
		 * ads
		 *
		 */
		function ads( $content ){

			if( tie_get_option( 'amp_ad_above' ) ){
				$content = tie_get_option( 'amp_ad_above' ) . $content;
			}

			if( tie_get_option( 'amp_ad_below' ) ){
				$content = $content . tie_get_option( 'amp_ad_below' );
			}

			return $content;
		}


		/**
		 * content_width
		 *
		 */
		function content_width( $content_max_width ){

			return 700;
		}


		/**
		 * remove_google_fonts
		 *
		 * Do not load Merriweather Google fonts on AMP pages
		 */
		function remove_google_fonts(){

		  remove_action( 'amp_post_template_head', 'amp_post_template_add_fonts' );
		}


		/**
		 * templates_path
		 *
		 * Set custom template path
		 */
		function templates_path( $file, $type, $post ){

			if ( 'header-bar' === $type || 'featured-image' === $type || 'footer' === $type || 'style' === $type ) {

				$file = locate_template( 'framework/plugins/amp-templates/'. $type .'.php' );
			}

			return $file;
		}


		/**
		 * meta_taxonomy
		 *
		 * Show/Hide the categories and tags below the post
		 */
		function meta_taxonomy(){

			$meta = array( 'meta-comments-link' );

			if( tie_get_option( 'amp_taxonomy') ){
				$meta[] = 'meta-taxonomy';
			}

			return $meta;
		}


		/**
		 * logo_path
		 *
		 * Add the custom logo to the AMP structure data
		 */
		function logo_path(){

			return tie_get_option( 'amp_logo' );
		}

	}

	# Instantiate the class
	new TIELABS_AMP();

}
