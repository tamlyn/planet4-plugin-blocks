<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Subheader_Controller' ) ) {

	class P4BKS_Blocks_Subheader_Controller extends P4BKS_Blocks_Controller {

		/**
		 * function to load the block and define its name
		 */
		public function load() {
			$this->block_name = 'subheader';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the subheader shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Title', 'planet4-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
				),
				array(
					'label' => __( 'Description', 'planet4-blocks' ),
					'attr'  => 'descr',
					'type'  => 'textarea',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Subheader', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/icons/subheader.png' ) . '" />',
				'attrs'         => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcake_twocolumn shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array $fields Array of fields that are to be used in the template.
		 * @param string $content The content of the post.
		 * @param string $shortcode_tag The shortcode tag (shortcake_blockname).
		 *
		 * @return string The complete html of the block
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ): string {

			$fields = shortcode_atts( array(
				'title' => '',
				'descr' => '',
			), $fields, $shortcode_tag );

			$data = [
				'fields' => $fields
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
