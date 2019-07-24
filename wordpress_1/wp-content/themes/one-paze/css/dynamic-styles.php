<?php
    function one_paze_dynamic_styles() {
        $slider_overlay_direction = esc_attr(get_theme_mod('slider_overlay_direction'));
        $rotate = ($slider_overlay_direction == 'top') ? '0deg' : '180deg';

        $enabled_sections = one_paze_get_parallax_sections();

        $custom_css = '';

        $tpl_color = get_theme_mod( 'one_paze_tpl_color', '#4295ce' );
        $tpl_rgb = one_paze_lite_hex2rgb($tpl_color);

        /** Background Color **/
        $custom_css .= "
            .main-nav-scrolled,
            .cta_read,
            .port-view-all, .blog-view-all,
            .nav-previous a:before, .nav-next a:before,
            .form-submit input,
            .site-header{
                background: {$tpl_color};
            }";
            
        /** Border Color **/
        $custom_css .= "
            .cta_read,
            .port-view-all, .blog-view-all,
            .portfolio-post-filter .filter.active,
            .form-submit input:hover,
            .form-submit input{
                border-color: {$tpl_color};
            }";
            
        /** Color **/
        $custom_css .= "
            .cta_read:hover,
            .port-view-all:hover, .blog-view-all:hover,
            .blog_read,
            .testinomial .bx-controls-direction a,
            .contact-info>div a, .contact-info>div,
            .site-info a,
            .ap-container span.current,
            .portfolio-post-filter .filter:hover,
            .portfolio-post-filter .filter.active,
            .footer-top a:hover,
            .blog-post-wrap a:hover h3,
            .form-submit input:hover,
            .blog-collection .post-meta-infos>span>span.readmore a,
            .blog-collection .post-meta-infos>span>span:hover,
            .blog-collection .post-meta-infos>span>span:hover a,
            .inner .blog-post-content>a,
            .search-results h1.entry-title a:hover{
                color: {$tpl_color};
            }";
            
        /** Box Shadow 0.4 **/
        $custom_css .= "
            .main-nav-scrolled{
                box-shadow: 0 2px 3px rgba({$tpl_rgb[0]}, {$tpl_rgb[1]}, {$tpl_rgb[2]}, 0.4);
            }";
            
        /** Border Bottom **/
            $custom_css .= "
                .main-nav-scrolled{
                    border-bottom: 1px solid rgba({$tpl_rgb[0]}, {$tpl_rgb[1]}, {$tpl_rgb[2]}, 0.5);
                }";

        /** For Slider **/
        $custom_css .= ".slider-overlay1{
                -webkit-transform: rotate({$rotate});
                -moz-transform: rotate({$rotate});
            }";

        /** For Section Background **/
        foreach ($enabled_sections as $enabled_section) :
            $section = $enabled_section['section'];
            $section_bg_color = esc_attr(get_theme_mod($section . '_section_bg_color'));
            $section_bg_image = esc_url(get_theme_mod($section . '_section_bg_image'));
            $section_bg_repeat = esc_attr(get_theme_mod($section . '_section_bg_repeat', 'no-repeat'));
            $section_bg_position = esc_attr(get_theme_mod($section . '_section_bg_position', 'left'));
            $section_bg_attachment = esc_attr(get_theme_mod($section . '_section_bg_attachment', 'scroll'));
            $section_bg_size = esc_attr(get_theme_mod($section . '_section_bg_size', 'auto'));

            if( $section_bg_color ) {
                $custom_css .= "#plx_{$section}_section{
                    background-color: {$section_bg_color};
                }";
            }

            if( $section_bg_image ) {
                $custom_css .= "#plx_{$section}_section{
                    background-image: url('{$section_bg_image}');
                }";
            }

            if( $section_bg_repeat ) {
                $custom_css .= "#plx_{$section}_section{
                    background-repeat: {$section_bg_repeat};
                }";
            }

            if( $section_bg_position ) {
                $custom_css .= "#plx_{$section}_section{
                    background-position: {$section_bg_position};
                }";
            }

            if( $section_bg_attachment ) {
                $custom_css .= "#plx_{$section}_section{
                    background-attachment: {$section_bg_attachment};
                }";
            }

            if( $section_bg_size ) {
                $custom_css .= "#plx_{$section}_section{
                    background-size: {$section_bg_size};
                }";
            }

        endforeach;
            
        /** For Section Title **/
        $sec_title_clr_arr = array(
            'about' => '#plx_about_section .about h2',
            'portfolio' => '#plx_portfolio_section h2',
            'services' => '#plx_services_section h2',
            'blog' => '#plx_blog_section h2',
            'testimonial' => '.testinomial h2',
            'team' => '.team h2' 
        );

        foreach( $sec_title_clr_arr as $sec_id => $sec_identifier ) {
            $title_color = get_theme_mod( $sec_id . '_section_title_color', '' );
            if( $title_color ){
                $custom_css .= "{$sec_identifier} {
                    color: {$title_color};
                }";
            }
        }

        $sec_content_clr_arr = array(
            'about' => '#plx_about_section .about-contents *',
            'services' => '#plx_services_section .service-descr, #plx_services_section .service-post-wrap h3, #plx_services_section .services-excerpt',
            'cta' => '#plx_cta_section .mid-content',
            'testimonial' => '.client-testimonial',
            'team' => '#plx_team_section .member-says, #plx_team_section .team-member h3',
        );

        foreach ( $sec_content_clr_arr as $s_id => $s_identifier ) {
            $content_color = get_theme_mod( $s_id . '_section_content_color', '' );

            if( $content_color ){
                $custom_css .= "{$s_identifier} {
                    color: {$content_color};
                }";
            }
        }
            
        wp_add_inline_style( 'one-paze-style', $custom_css );
    }
    
    add_action( 'wp_enqueue_scripts', 'one_paze_dynamic_styles' );

    function one_paze_lite_hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    function one_paze_colourBrightness($hex, $percent) {
        // Work out if hash given
        $hash = '';
        if (stristr($hex, '#')) {
            $hex = str_replace('#', '', $hex);
            $hash = '#';
        }
        /// HEX TO RGB
        $rgb = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
        //// CALCULATE 
        for ($i = 0; $i < 3; $i++) {
            // See if brighter or darker
            if ($percent > 0) {
                // Lighter
                $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
            } else {
                // Darker
                $positivePercent = $percent - ($percent * 2);
                $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
            }
            // In case rounding up causes us to go to 256
            if ($rgb[$i] > 255) {
                $rgb[$i] = 255;
            }
        }
        //// RBG to Hex
        $hex = '';
        for ($i = 0; $i < 3; $i++) {
            // Convert the decimal digit to hex
            $hexDigit = dechex($rgb[$i]);
            // Add a leading zero if necessary
            if (strlen($hexDigit) == 1) {
                $hexDigit = "0" . $hexDigit;
            }
            // Append to the hex string
            $hex .= $hexDigit;
        }
        return $hash . $hex;
    }