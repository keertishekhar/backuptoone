<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_id
 * @var $el_class
 * @var $width
 * @var $css
 * @var $offset
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Column
 */
$el_class = $el_id = $width = $css = $offset = $css_animation = '';
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
switch ($width) {
	case "4/8" :
			$width = "momizat_vc_col vc_main_col";
		break;
	case "6/8" :
			$width = "momizat_vc_col vc_main_col one_side";
		break;
	case "2/8" :
			$width = "momizat_vc_col vc_sec_sidebar sidebar secondary-sidebar";
		break;
	case "3/9" :
			$width = "momizat_vc_col vc_sidebar sidebar main-sidebar";
		break;

	//left sidebar
	case "8/8" :
				$width = "momizat_vc_col vc_sidebar sidebar main-sidebar alignlefti";
		break;
	case "7/7" :
			$width = "momizat_vc_col vc_main_col one_side alignrighti";
			break;
	case "9/9" :
			$width = "momizat_vc_col vc_sec_sidebar sidebar secondary-sidebar alignlefti";
	break;

	default:
			$width = wpb_translateColumnWidthToSpan( $width );
	break;
}
$width = vc_column_offset_class_merge( $offset, $width );

$css_classes = array(
	$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
	'wpb_column',
	'vc_column_container',
	$width,
);

if ( vc_shortcode_custom_css_has_property( $css, array(
	'border',
	'background',
) ) ) {
	$css_classes[] = 'vc_col-has-fill';
}

$wrapper_attributes = array();

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= '<div class="vc_column-inner ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';
$output .= '<div class="wpb_wrapper">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo $output;
