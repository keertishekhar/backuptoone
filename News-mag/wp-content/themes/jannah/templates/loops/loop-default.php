<?php
/**
 * Block / Archives Layout - Default
 *
 * This template can be overridden by copying it to your-child-theme/templates/loops/loop-default.php.
 *
 * HOWEVER, on occasion TieLabs will need to update template files and you
 * will need to copy the new files to your child theme to maintain compatibility.
 *
 * @author   TieLabs
 * @version  3.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


// Set custom class for the post without thumb
$no_thumb = ( ! has_post_thumbnail() || ! empty( $block['thumb_all'] ) ) ? 'no-small-thumbs' : '';

?>

<li <?php tie_post_class( 'post-item '.$no_thumb, false, true ); ?>>

	<?php

		// Get the post thumbnail
		if ( has_post_thumbnail() && empty( $block['thumb_all'] ) ){

			tie_post_thumbnail( TIELABS_THEME_SLUG.'-image-large', 'large', true );
		}

	?>

	<div class="post-details">

		<?php

			// Get the Post Meta info
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

	</div>
</li>
