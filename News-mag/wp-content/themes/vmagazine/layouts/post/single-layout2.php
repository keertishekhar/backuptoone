<?php
/**
 * Template part for displaying single post layout 2
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AccessPress Themes
 * @subpackage Vmagazine
 * @since 1.0.0
 */
$post_id = get_the_ID();
?>

<div id="primary" class="content-area post-single-layout2 vmagazine-content">
	<main id="main" class="site-main" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<?php vmagazine_post_cat_lists(); ?>

			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-meta clearfix">
				<?php vmagazine_icon_meta(); ?>
			</div><!-- .entry-meta -->
			<?php
				$vmagazine_post_ftr_images = get_theme_mod('vmagazine_post_ftr_images','show');
				$get_post_format = get_post_format();
				
				$post_audio_url = get_post_meta( $post_id, 'post_embed_audio_url', true );
				$post_video_url = get_post_meta( $post_id, 'post_embed_video_url', true );
				$post_images_url = get_post_meta( $post_id, 'post_images', true );
				$audio_embed_code = wp_oembed_get( esc_url($post_audio_url) );

				if( $get_post_format == 'audio' && !empty( $post_audio_url ) ) {
					
					echo '<div class="post-audio">'.$audio_embed_code.'</div>';
				} elseif( $get_post_format == 'video' && !empty( $post_video_url ) ) {
					$embed_args = array(
		                            'width'=>826
		                            
		                            );
		            $embed_code = wp_oembed_get( $post_video_url, $embed_args );
		            echo '<div class="single-videothumb">'. $embed_code .'</div>';
				} elseif( $get_post_format == 'gallery' && !empty( $post_images_url ) ) {
			?>
					<div class="post-gallery-wrapper">
						<ul class="gallery-items">
							<?php 
								foreach ( $post_images_url as $key => $value ) {
									$image_id = vmagazine_get_attachment_id_from_url( $value );
									$image_path = wp_get_attachment_image_src( $image_id, 'vmagazine-single-large', true );
							?>
									<li>
										<a href="<?php echo esc_url( $image_path[0] );?>">
											<img src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php the_title_attribute()?>"/>
										</a>
									</li>
							<?php
								}
							?>
						</ul>
					</div><!-- .post-gallery-wrapper -->
			<?php
				} elseif( $vmagazine_post_ftr_images == 'show' ) {
					vmagazine_single_post_featured_image();
				}
			?>

			<div class="entry-content clearfix">
				<?php
					the_content( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'vmagazine' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vmagazine' ),
						'after'  => '</div>',
					) ); ?>
				</div>
				<div class="entry-content clearfix">	
                    <?php if( has_tag() ) : ?>
                    <div class="post-tag">
                    	<span class="tag-title"><?php echo esc_html__('Related tags : ','vmagazine');?></span>
                    	 <?php vmagazine_single_post_tags_list();?>
                    </div>
                    <?php endif; ?>
					
			        
			        <?php do_action('vmagazine_single_social_share'); ?>

			    	
					<?php
					/** Post ADS **/
					$ads_url = get_post_meta( get_the_ID(), 'vmagazine_ads_url', true ); 
					$ads_img = get_post_meta( get_the_ID(), 'vmagazine_ads_img', true );
					if( $ads_img ){
					?>
					<div class="post-ads">
						<a href="<?php echo esc_url($ads_url);?>" target="_blank">
							<img src="<?php echo esc_url($ads_img);?>" alt="<?php the_title_attribute()?>" />
						</a>
					</div>
					<?php
					}

					/**
					 * Post author info
					 */
					$post_author_info_option = get_theme_mod( 'vmagazine_author_info_option', 'hide' );
					if( $post_author_info_option != 'hide' ) {
						do_action( 'vmagazine_author_info' );
					}
					/**
					 * Post navigation
					 */
					
					do_action('vmagazine_single_post_nav');
				?>
			</div><!-- .entry-content -->
            
			<?php
			/**
			* Post Review
			*/
			$post_review_option = get_theme_mod( 'vmagazine_post_review_option', 'show' );
			if( $post_review_option != 'hide' ) {
				do_action( 'vmagazine_single_post_review' );
			}

             /**
			 * Related posts
			 */
			do_action( 'vmagazine_related_posts' );
								
			// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			
				comment_form(array(
					'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title"><span class="title-bg">',
					'title_reply' => esc_html__('Comment here','vmagazine'),
					'title_reply_after' => '</span></h4>',
					'comment_notes_before' => '',
					'label_submit'=> esc_html__('Comment','vmagazine'),
					));

				

			?>

			<?php vmagazine_entry_footer(); ?>
			
		</article><!-- #post-## -->
		<?php
			
		?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php		
	/**
	 * Post sidebar
	 */
	vmagazine_get_sidebar();
?>