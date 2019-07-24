<?php
/**
 * The template for displaying category pages
 *
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

get_header(); ?>

	<div <?php tie_content_column_attr(); ?>>

		<?php if ( have_posts() ) : ?>

			<header class="entry-header-outer container-wrapper">
				<?php

					do_action( 'TieLabs/before_archive_title' );

					the_archive_title( '<h1 class="page-title">', '</h1>' );

					if( tie_get_option( 'category_desc' )){

						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					}
				?>
			</header><!-- .entry-header-outer /-->

			<?php

			// Category layout
			$layout = tie_get_object_option( 'category_layout' ) ? tie_get_object_option( 'category_layout' ) : tie_get_option( 'category_layout' );
			$layout = ! empty( $layout ) ? $layout : 'excerpt';

			// Category Excerpt length
			$excerpt_length = tie_get_object_option( 'category_excerpt_length' ) ? tie_get_object_option( 'category_excerpt_length' ) : tie_get_option( 'category_excerpt_length' );

			// Category Media Overlay
			$media_overlay = tie_get_object_option( 'category_media_overlay' ) ? true : false;

			// Get the layout template part
			TIELABS_HELPER::get_template_part( 'templates/archives', '', array(
				'layout'          => $layout,
				'excerpt_length'  => $excerpt_length,
				'media_overlay'   => $media_overlay,
				'category_meta'   => apply_filters( 'TieLabs/Archive_Thumbnail/category_meta', false ),
			));


			// Page navigation
			$pagination = tie_get_object_option( 'category_pagination' ) ? tie_get_object_option( 'category_pagination' ) : tie_get_option( 'category_pagination' );

			TIELABS_PAGINATION::show( array( 'type' => $pagination ) );

		// If no content, include the "No posts found" template
		else :
			TIELABS_HELPER::get_template_part( 'templates/not-found' );

		endif;

		?>

	</div><!-- .main-content /-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
