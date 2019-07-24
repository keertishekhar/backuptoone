<?php
/**
 * Taqyeem Class
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly



if( ! class_exists( 'TIELABS_TAQYEEM' )){

	class TIELABS_TAQYEEM{

		/**
		 * __construct
		 *
		 * Class constructor where we will call our filter and action hooks.
		 */
		function __construct(){

			// Disable if the Taqyeem plugin is not active
			if( ! TIELABS_TAQYEEM_IS_ACTIVE ) return;

			// Disable the Custom Styles and Typofraphy options
			add_filter( 'taqyeem_custom_styles', '__return_false' );

			// Disable the plugin Rich Snippets
			add_filter( 'tie_taqyeem_rich_snippets', '__return_false' );

			// Remove Shortcodes code and Keep the content
			add_filter( 'taqyeem_exclude_content', 'TIELABS_HELPER::strip_shortcodes' );

			// Enqueue Scripts and Styles
			add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

			// Change the reviews head box class
			add_filter( 'taqyeem_reviews_head_classes', array( $this, 'review_head_class' ) );

			// Alter the Queries
			add_filter( 'TieLabs/Query/args', array( $this, 'best_reviews_query' ), 10, 2 );

			// Alter the Related Posts Query
			add_filter( 'TieLabs/related_posts_query', array( $this, 'related_posts_query' ) );

			// Alter the Check Also box Query
			add_filter( 'TieLabs/checkalso_query', array( $this, 'checkalso_query' ) );

			// Default Widgets Posts Thumb Size
			add_filter( 'tie_taqyeem_widget_thumb_size', array( $this, 'reviews_thumb_size' ) );

			// Allow the Rview Rich Snippet on Pages
			add_filter( 'TieLabs/is_page_rich_snippet', array( $this, 'rich_snippet_for_page' ) );

			// Change the Review Rich Snippet
			add_filter( 'TieLabs/rich_snippet_schema', array( $this, 'review_rich_snippet' ) );

			// Add Best Reviews to the post order option menu.
			add_filter( 'TieLabs/Options/Related/post_order_args',   array( $this, 'posts_order_args' ) );
			add_filter( 'TieLabs/Options/Checkalso/post_order_args', array( $this, 'posts_order_args' ) );
			add_filter( 'TieLabs/Builder/Block/post_order_args',     array( $this, 'posts_order_args' ) );
			add_filter( 'TieLabs/Widget/Slider/post_order_args',     array( $this, 'posts_order_args' ) );
			add_filter( 'TieLabs/Widget/Posts/post_order_args',      array( $this, 'posts_order_args_2' ) );

			// Prevent Taqyeem from displaying the review box in the content block in the builder
			add_action( 'TieLabs/Builder/before', array( $this, 'remove_review_block' ) );

		}


		/*
		 * Allow the Rview Rich Snippet on Pages
		 */
		function rich_snippet_for_page(){

			if( tie_get_postdata( 'taq_review_position' ) ){
				return true;
			}
		}


		/*
		 * Change the Review Rich Snippet
		 */
		function review_rich_snippet( $schema ){

			// Check if the current post has review
			if( ! tie_get_postdata( 'taq_review_position' ) ){
				return $schema;
			}

			// Get he total score and convert it to 0 ~ 5
			$total_score = (int) get_post_meta( get_the_ID(), 'taq_review_score', true );

			if( ! empty( $total_score ) && $total_score > 0 ){
				$total_score = round( ($total_score*5)/100, 1 );
			}

			// Review type
			$schema['@type'] = 'review';

			// Set the post title as the item reviewd title
			$schema['itemReviewed'] = array(
				'@type' => 'Thing',
				'name'  => get_the_title(),
			);

			// Set the rating
			$schema['reviewRating'] = array(
				'@type'       => 'Rating',
				'worstRating' => 1,
				'bestRating'  => 5,
				'ratingValue' => $total_score,
				'description' => get_post_meta( get_the_ID(), 'taq_review_summary', true ),
			);

			// Set the post description as the review body text
			$schema['reviewBody'] = $schema['description'];

			// Set the post featured image as the item image if it exists
			if( ! empty( $schema['image']['url'] ) ){
				$schema['itemReviewed']['image'] = $schema['image']['url'];
			}

			// Unset some data
			unset( $schema['articleBody'] );
			unset( $schema['articleSection'] );

			// return the modefied data
			return $schema;
		}


		/*
		 * Change the reviews head box class
		 */
		function review_head_class( $class ){
			return tie_get_box_class( $class );
		}


		/*
		 * Alter the query for the best reviews
		 */
		function best_reviews_query( $args, $block ){

			if( ! empty( $block['order'] ) && $block['order'] == 'best' ){
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'taq_review_score';
			}

			return $args;
		}


		/*
		 * Alter the Related Posts Query
		 */
		function related_posts_query( $args ){

			if( tie_get_option( 'related_order' ) == 'best' ){
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'taq_review_score';
			}

			return $args;
		}


		/*
		 * Alter the Check Also Query
		 */
		function checkalso_query( $args ){

			if( tie_get_option( 'check_also_order' ) == 'best' ){
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'taq_review_score';
			}

			return $args;
		}


		/*
		 * Add Best Reviews to the post order option menu.
		 */
		function posts_order_args( $args ){

			$args['best'] = esc_html__( 'Best Reviews', TIELABS_TEXTDOMAIN );

			return $args;
		}


		/*
		 * Add Best Reviews to the post order option menu.
		 */
		function posts_order_args_2( $args ){

			$args['standard']['best'] = esc_html__( 'Best Reviews', TIELABS_TEXTDOMAIN );

			return $args;
		}


		/*
		 * Default Widgets Posts Thumb Size
		 */
		function reviews_thumb_size(){
			return TIELABS_THEME_SLUG.'-image-small';
		}


		/*
		 * Prevent Taqyeem from displaying the review box in the content block in the builder
		 */
		function remove_review_block(){
			remove_filter( 'the_content', 'taqyeem_insert_review' );
		}


		/*
		 * Enqueue Scripts and Styles
		 */
		function enqueue_scripts(){

			wp_dequeue_script( 'taqyeem-main' );
			wp_dequeue_style( 'taqyeem-style' );

			wp_enqueue_style( 'taqyeem-styles', TIELABS_TEMPLATE_URL.'/assets/css/taqyeem'. TIELABS_STYLES::is_minified() .'.css', array(), TIELABS_DB_VERSION, 'all' );

			if( ! is_admin() ){
				wp_dequeue_style( 'taqyeem-fontawesome' );
			}
		}

	}


	// Instantiate the class
	new TIELABS_TAQYEEM();
}
