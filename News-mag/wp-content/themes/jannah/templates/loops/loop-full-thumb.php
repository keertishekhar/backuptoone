<?php
/**
 * Block / Archives Layout - Full Thumb
 *
 * This template can be overridden by copying it to your-child-theme/templates/loops/loop-full-thumb.php.
 *
 * HOWEVER, on occasion TieLabs will need to update template files and you
 * will need to copy the new files to your child theme to maintain compatibility.
 *
 * @author   TieLabs
 * @version  2.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

?>

<li <?php tie_post_class( 'post-item' ); ?>>

	<?php

		# Get the post thumbnail
		if ( has_post_thumbnail() && empty( $block['thumb_all'] ) ){

			$image_size = ! empty( $block['is_full'] ) ? TIELABS_THEME_SLUG.'-image-full' : TIELABS_THEME_SLUG.'-image-post';

			tie_post_thumbnail( $image_size, 'large', true );
		}

		# Get the Post Meta info
		if( ! empty( $block['post_meta'] )){

			tie_the_post_meta();
		}

	?>

	<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php tie_the_title( $block['title_length'] ); ?></a></h3>

	<?php
		if( ! empty( $block['excerpt'] )){ ?>
			<p class="post-excerpt"><?php tie_the_excerpt( $block['excerpt_length'] ) ?></p>
			<?php
		}

		if( ! empty( $block['read_more'] )){ ?>
			<a class="more-link button" href="<?php the_permalink() ?>"><?php esc_html_e( 'Read More &raquo;', TIELABS_TEXTDOMAIN ) ?></a>
			<?php
		}
	?>
</li>
