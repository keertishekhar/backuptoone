<?php
/**
 * One Paze Theme Customizer
 *
 * @package One Paze
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function one_paze_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/*------------------------------------------------------------------------------------*/
	/**
	 * Upgrade to One Paze Pro
	*/
	// Register custom section types.
	$wp_customize->register_section_type( 'One_Paze_Customize_Section_Pro' );

	// Register sections.
	$wp_customize->add_section(
	    new One_Paze_Customize_Section_Pro(
	        $wp_customize,
	        'onepaze-pro',
	        array(
	            'title'    => esc_html__( 'Free Vs Pro', 'one-paze' ),
	            'pro_text' => esc_html__( 'Compare','one-paze' ),
	            'pro_url'  => admin_url( 'themes.php?page=onepaze-welcome&section=free_vs_pro'),
	            'priority' => 1,
	        )
	    )
	);
	$wp_customize->add_setting(
		'onepaze_pro_upbuton',
		array(
			'section' => 'onepaze-pro',
			'sanitize_callback' => 'esc_attr',
		)
	);

	$wp_customize->add_control(
		'onepaze_pro_upbuton',
		array(
			'section' => 'onepaze-pro'
		)
	);

	$wp_customize->add_setting( 'one_paze_tpl_color', array( 'default' => '#4295ce', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'one_paze_tpl_color', 
		array(
			'label'      => esc_html__( 'Template Color', 'one-paze' ),
			'section'    => 'colors',
			'settings'   => 'one_paze_tpl_color',
		) ) 
	);
}
add_action( 'customize_register', 'one_paze_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function one_paze_customize_preview_js() {
	wp_enqueue_script( 'one_paze_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'one_paze_customize_preview_js' );
