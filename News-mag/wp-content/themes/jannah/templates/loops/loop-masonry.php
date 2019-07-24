<?php
/**
 * Achive Layout - Masonry
 *
 * This template can be overridden by copying it to your-child-theme/templates/loops/loop-masonry.php.
 *
 * HOWEVER, on occasion TieLabs will need to update template files and you
 * will need to copy the new files to your child theme to maintain compatibility.
 *
 * @author   TieLabs
 * @version  3.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

?>

<div <?php tie_post_class( 'container-wrapper post-element' ); ?>>
	<div class="entry-archives-header">
		<div class="entry-header-inner">

			<?php

				# Get the Post Category
				if( $block['category_meta'] ){
					tie_the_category();
				}

			?>

			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title() ?></a></h3>

			<?php
				# Get the Post Meta info
				if( $block['post_meta'] ){
					tie_the_post_meta( array( 'author' => false ));
				}
			?>

		</div><!-- .entry-header-inner /-->
	</div><!-- .entry-header /-->

	<div class="clearfix"></div>

	<div class="featured-area">
		<?php
			# Get the post thumbnail
			if ( has_post_thumbnail() ){
				tie_post_thumbnail( $block['uncropped_image'], 'large' );
			}
		?>
	</div>

	<?php if( $block['excerpt'] ): ?>
		<div class="entry-content">
			<p><?php tie_the_excerpt( $block['excerpt_length'] ); ?></p>
			<a class="more-link button" href="<?php the_permalink() ?>"><?php esc_html_e( 'Read More &raquo;', TIELABS_TEXTDOMAIN ) ?></a>
		</div><!-- .entry-content /-->
	<?php endif; ?>

</div><!-- .container-wrapper :: single post /-->
