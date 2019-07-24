<?php
$test_section_title = esc_attr(get_theme_mod('testimonial_section_title'));
$test_category = esc_attr(get_theme_mod('testimonial_section_category'));
$test_query = new WP_Query(array('post_type' => 'post', 'cat' => $test_category, 'posts_per_page' => 6));
?>
<div class="container">
    <div class="testinomial clearfix">
        <h2><?php echo esc_html($test_section_title); ?></h2>
        <?php if ($test_query->have_posts() && $test_category) : ?>
            <div class="testimonial-slider clearfix">
                <?php while ($test_query->have_posts()) : $test_query->the_post(); ?>
                    <div class="testimonial-post-wrap wow fadeInUp" data-wow-duration="2s">
                        <div class="client-testimonial">
                            <?php the_content(); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="author_main">
                            <div class="author">
                                <figure>
                                    <?php
                                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'one-paze-testimonial-thumb');
                                    $img_src = $img[0];
                                    ?>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo esc_url($img_src); ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" />
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/no-testimonial-thumbnail.png' ); ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>" />
                                    <?php endif; ?>
                                </figure>
                                <div class="clearfix"></div>
                                <h3 class="client-name">
                                    <?php the_title(); ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>