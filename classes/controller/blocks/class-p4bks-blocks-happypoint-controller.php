<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_HappyPoint_Controller' ) ) {

	class P4BKS_Blocks_HappyPoint_Controller extends P4BKS_Blocks_Controller {

		/**
		 *
		 */
		public function load() {
			// --- Set here the name of your block ---
			$this->block_name = 'happy_point';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the happypoint shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label'       => esc_html__( 'Background', 'planet4-blocks' ),
					'attr'        => 'background',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => esc_html__( 'Select Background Image', 'planet4-blocks' ),
					'frameTitle'  => esc_html__( 'Select Background Image', 'planet4-blocks' ),
				),
				array(
					'label'  => esc_html__( 'Opacity % . Number between 1 and 100. If you leave it empty 70 will be used', 'planet4-blocks' ),
					'attr'   => 'opacity',
					'type'   => 'text',
					'meta'   => array(
						'data-test'   => 70,
					),
				),
				array(
					'label'  => esc_html__( 'Boxout Title', 'planet4-blocks' ),
					'attr'   => 'boxout_title',
					'type'   => 'text',
				),
				array(
					'label'  => esc_html__( 'Boxout Description', 'planet4-blocks' ),
					'attr'   => 'boxout_descr',
					'type'   => 'text',
				),
				array(
					'label'  => esc_html__( 'Boxout Link Text', 'planet4-blocks' ),
					'attr'   => 'boxout_link_text',
					'type'   => 'text',
				),
				array(
					'label'  => esc_html__( 'Boxout Link Url', 'planet4-blocks' ),
					'attr'   => 'boxout_link_url',
					'type'   => 'text',
				),
			);

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = array(
				'label' => esc_html__( 'Happy Point', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/icons/happy_point.png' ) . '" />',
				'attrs' => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcake_twocolumn shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array $fields
		 * @param string $content
		 * @param string $shortcode_tag
		 *
		 * @return string
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$fields = shortcode_atts( array(
				'background'     	=> '',
				'opacity' 		 	=> '70',
				'boxout_title'    	=> '',
				'boxout_descr'     	=> '',
				'boxout_link_text' 	=> '',
				'boxout_link_url'  	=> '',
			), $fields, $shortcode_tag );

			if (!is_numeric($fields['opacity'])) {
				$fields['opacity'] = 70;
			}
			$opacity_reverse = number_format(((100-$fields['opacity'])/100), 1);

			$fields['background_html'] 		= wp_get_attachment_image( $fields['background'] );
			$fields['background_src'] 		= wp_get_attachment_image_src( $fields['background'] );
			$fields['opacity'] 				= $opacity_reverse;
			$fields['boxout_title'] 		= wp_kses_post( $fields['boxout_title']);
			$fields['boxout_descr'] 		= wp_kses_post( $fields['boxout_descr']);
			$fields['boxout_link_text'] 	= wp_kses_post( $fields['boxout_link_text']);
			$fields['boxout_link_url'] 		= esc_html( $fields['boxout_link_url']);


			$data = ['fields' => $fields];



			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );
			return ob_get_clean();
		}
	}
}
