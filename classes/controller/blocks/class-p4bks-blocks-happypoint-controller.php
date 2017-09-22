<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_HappyPoint_Controller' ) ) {

	class P4BKS_Blocks_HappyPoint_Controller extends P4BKS_Blocks_Controller {

		/**
		 *
		 */
		public function load() {
			parent::load();
		}

		/**
		 * Register shortcodes
		 */
		public function shortcode_ui_register_shortcodes() {
			// Define the callback for the advanced shortcode.
			add_shortcode( 'shortcake_happypoint', array( $this, 'prepare_happy_point' ) );
		}

		/**
		 * Shortcode UI setup for the happypoint shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function shortcode_ui_block_fields() {
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

			shortcode_ui_register_for_shortcode( 'shortcake_happypoint', $shortcode_ui_args );
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
		public function prepare_happy_point( $fields, $content, $shortcode_tag ) {

			$fields = shortcode_atts( array(
				'background'     	=> '',
				'opacity' 		 	=> '70',
				'boxout_title'    	=> '',
				'boxout_descr'     	=> '',
				'boxout_link_text' 	=> '',
				'boxout_link_url'  	=> '',
			), $fields, $shortcode_tag );

			$data = $fields;

			$data['background'] 		= wp_get_attachment_image( $fields['background'] );
			$data['opacity'] 			= wp_kses_post( $fields['opacity']);
			$data['boxout_title'] 		= wp_kses_post( $fields['boxout_title']);
			$data['boxout_descr'] 		= wp_kses_post( $fields['boxout_descr']);
			$data['boxout_link_text'] 	= wp_kses_post( $fields['boxout_link_text']);
			$data['boxout_link_url'] 	= esc_html( $fields['boxout_link_url']);


			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->view_template( 'happy_point', $data );

			return ob_get_clean();
		}
	}
}
