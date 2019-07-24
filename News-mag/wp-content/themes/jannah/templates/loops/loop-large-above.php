<?php
/**
 * Block Layout - Large Above
 *
 * This template can be overridden by copying it to your-child-theme/templates/loops/loop-large-above.php.
 *
 * HOWEVER, on occasion TieLabs will need to update template files and you
 * will need to copy the new files to your child theme to maintain compatibility.
 *
 * @author   TieLabs
 * @version  3.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

?>


<li <?php tie_post_class( 'post-item' ); ?>>

	<?php

	if( $count == 1 ) :

		# Get the post thumbnail
		if ( has_post_thumbnail() ){

			$image_size = ! empty( $block['is_full'] ) ? TIELABS_THEME_SLUG.'-image-full' : TIELABS_THEME_SLUG.'-image-post';
			tie_post_thumbnail( $image_size, 'large' );
		}

		?>

		<div class="clearfix"></div>

		<div class="post-overlay">
			<div class="post-content">

				<?php tie_the_category(); ?>

				<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php tie_the_title( $block['title_length'] ); ?></a></h3>

				<?php

					# Get the Post Meta info
					if( ! empty( $block['post_meta'] ) ){

						tie_the_post_meta( '', '<div class="thumb-meta">', '</div><!-- .thumb-meta -->' );
					}

				?>

			</div><!-- .post-content -->
		</div><!-- .post-overlay -->

		<?php
	else:

		# Get the post thumbnail
		if ( has_post_thumbnail() ){
			tie_post_thumbnail( TIELABS_THEME_SLUG.'-image-large' );
		}

		?>

		<div class="clearfix"></div>

		<div class="post-overlay">
			<div class="post-content">

				<?php

					# Get the Post Meta info
					if( ! empty( $block['post_meta'] ) ){
						tie_the_post_meta( array( 'trending' => true, 'author' => false, 'views' => false ) );
					}

				?>

				<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php tie_the_title( $block['title_length'] ); ?></a></h3>

				<?php

					# Get the review score for the posts with stars
					if( ! empty( $block['post_meta'] )){
						echo '<div class="post-meta">'. tie_get_score( 'stars' ) .'</div>';
					}

				?>

			</div><!-- .post-content -->
		</div><!-- .post-overlay -->

	<?php endif; ?>

</li>
