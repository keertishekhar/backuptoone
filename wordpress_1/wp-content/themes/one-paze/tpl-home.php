<?php
/**
 * The template for displaying all pages.
 * Template Name: Home
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package One Pager
 */

get_header(); ?>

<?php
    /**
     * Parallax Page
     */
    $enabled_sections = one_paze_get_parallax_sections();
    foreach($enabled_sections as $section) :
    ?>
        <section id="<?php echo esc_attr($section['id']); ?>" class="plx-sections">
            <?php
                $aplyoverlay = false; 
                if(esc_attr(get_theme_mod($section['section'].'_section_enable_overlay')) == 1 && esc_attr(get_theme_mod($section['section'].'_section_bg_image')) != ''){
                    $aplyoverlay = true;
                } 
            ?>

            <div class="section-overlay <?php echo esc_attr($section['section']) . '-section-overlay'; ?>" style="<?php if($aplyoverlay) : ?>background: rgba(54, 151, 216, 0.34);<?php endif; ?>">

                <div class="mid-content">
                    <?php get_template_part('template-parts/section', $section['section']); ?>
                </div>
            
            </div>
            
        </section>
    <?php 
    endforeach;   
?>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
