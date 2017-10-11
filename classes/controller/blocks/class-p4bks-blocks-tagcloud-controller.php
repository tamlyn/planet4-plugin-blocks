<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Tagcloud_Controller' ) ) {

	class P4BKS_Blocks_Tagcloud_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'tagcloud';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the tagscloud shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Tag Cloud Heading', 'planet4-blocks' ),
					'attr'  => 'tagcloud_heading',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => __( 'Enter tag cloud heading', 'planet4-blocks' ),
					),
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Tag Cloud', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/tag_cloud.png' ) . '" />',
				'attrs'         => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields This contains array of all tags for particular category.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode block of tag cloud.
		 *
		 * @since 0.1.0
		 *
		 * @return string All the data used for the html.
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$categories = get_the_category();

			$category   = ( isset( $categories[0]->cat_name ) && ! empty( $categories[0]->cat_name ) ) ? $categories[0]->cat_name : '';

			$data = [
				'fields' => $fields,
				'domain' => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
