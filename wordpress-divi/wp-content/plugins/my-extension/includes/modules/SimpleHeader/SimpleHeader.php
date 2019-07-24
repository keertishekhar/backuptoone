<?php

class SIMP_SimpleHeader extends ET_Builder_Module {

	public $slug       = 'simp_simple_header';
	public $vb_support = 'on';

	public function init() {
		$this->name = esc_html__( 'Simple Header', 'simp-simple-extension' );
	}

	public function get_fields() {
		return array(
			'heading'     => array(
				'label'           => esc_html__( 'Heading', 'simp-simple-extension' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired heading here.', 'simp-simple-extension' ),
				'toggle_slug'     => 'main_content',
			),
			'content'     => array(
				'label'           => esc_html__( 'Content', 'simp-simple-extension' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear below the heading text.', 'simp-simple-extension' ),
				'toggle_slug'     => 'main_content',
			),
			'content-1'     => array(
				'label'           => esc_html__( 'Content', 'simp-simple-extension' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear below the heading text.', 'simp-simple-extension' ),
				'toggle_slug'     => 'main_content',
			),
			'src' => array(
				'label'              => esc_html__( 'Image', 'simp-simple-extension' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'simp-simple-extension' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'simp-simple-extension' ),
				'update_text'        => esc_attr__( 'Set As Image', 'simp-simple-extension' ),
				'affects'            => array(
					'alt',
					'title_text',
				),
				'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'simp-simple-extension' ),
				'toggle_slug'        => 'main_content',
				'dynamic_content'    => 'image',
			),
		);
	}

	public function render( $unprocessed_props, $content = null, $render_slug ) {
		return sprintf(
			'<h1 class="simp-simple-header-heading">%1$s</h1>
			<p>%2$s %3$s</p>
			<p><img class="img" src="%4$s"></p>
			<style>
			.simp-simple-header-heading {
				margin-bottom: 20px;
				text-align:center;
			}
			.img{
				width:1080px;
			}
			</style>',
			esc_html( $this->props['heading'] ),
			$this->props['content'],$this->props['content-1'],$this->props['src']
		);
	}
}

new SIMP_SimpleHeader;