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
            <?php //if(get_theme_mod($section['section'].'_section_enable_overlay') == 1 && get_theme_mod($section['section'].'_section_bg_image') != '') : ?>
                <div class="section-overlay <?php echo esc_attr($section['section']) . '-section-overlay'; ?>" style="<?php if($aplyoverlay) : ?>background: rgba(54, 151, 216, 0.34);<?php endif; ?>">
            <?php //endif; ?>
            <div class="mid-content">
                <?php get_template_part('template-parts/section', $section['section']); ?>
            </div>
            <?php //if(get_theme_mod($section['section'].'_section_enable_overlay') == 1 && get_theme_mod($section['section'].'_section_bg_image') != '') : ?>
                </div>
            <?php //endif; ?>
        </section>
    <?php 
    endforeach;   
?>