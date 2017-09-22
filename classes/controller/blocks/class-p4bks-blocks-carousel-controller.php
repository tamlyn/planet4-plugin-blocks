<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Carousel_Controller' ) ) {

	class P4BKS_Blocks_Carousel_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'carousel';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the twocolumn shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			/*
			 * Define the UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will
			 * not be editable through Shortcake's UI. However, the value of any
			 * unregistered attributes will be preserved when editing.
			 *
			 * Each array must include 'attr', 'type', and 'label'.
			 * * 'attr' should be the name of the attribute.
			 * * 'type' options include: text, checkbox, textarea, radio, select, email,
			 *     url, number, and date, post_select, attachment, color.
			 * * 'label' is the label text associated with that input field.
			 *
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 *
			 * Use 'encode' to encode attribute data. Requires customization in shortcode callback to decode.
			 *
			 * Depending on 'type', additional arguments may be available.
			 */
			$fields = array(
				// Multiple Image Field
				array(
					'label' => esc_html__( 'Select Multiple Images', 'planet4-blocks' ),
					'attr'  => 'multiple_image',
					'type'  => 'attachment',
					'multiple'  => true,
					'meta'  => array(
						'placeholder' => esc_html__( 'Please select the images', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
			);

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = array(
				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label' => esc_html__( 'Carousel', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => 'dashicons-editor-table',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs' => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields
		 * @param string $content
		 * @param string $shortcode_tag
		 *
		 * @since 0.1.0
		 *
		 * @return string
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$data = [
				'fields' => array_map( 'wp_kses_post', $fields),
			];
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
