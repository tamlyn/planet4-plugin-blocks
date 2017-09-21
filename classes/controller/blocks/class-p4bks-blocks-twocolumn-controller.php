<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_TwoColumn_Controller' ) ) {

	class P4BKS_Blocks_TwoColumn_Controller extends P4BKS_Blocks_Controller {

		/**
		 *
		 */
		public function load() {
			parent::load();
		}

		/**
		 * Register shortcodes
		 *
		 * This registration is done independently of any UI that might be associated with them, so it always happens, even if
		 * Shortcake is not active.
		 *
		 * @since 1.0.0
		 */
		public function shortcode_ui_register_shortcodes() {
			// Define the callback for the advanced shortcode.
			add_shortcode( 'shortcake_twocolumn', array( $this, 'prepare_two_columns' ) );
		}

		/**
		 * Shortcode UI setup for the twocolumn shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
		 */
		public function shortcode_ui_block_fields() {
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
				// First column fields.
				array(
					'label'  => esc_html__( 'Title', 'planet4-blocks' ),
					'attr'   => 'title_1',
					'type'   => 'text',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter title of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label'    => esc_html__( 'Description', 'planet4-blocks' ),
					'attr'     => 'description_1',
					'type'     => 'textarea',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter description of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label'    => __( 'Button text', 'planet4-blocks' ),
					'attr'     => 'button_text_1',
					'type'     => 'text',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter button text of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label'    => __( 'Button link', 'planet4-blocks' ),
					'attr'     => 'button_link_1',
					'type'     => 'url',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter button link of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),

				// Second column fields.
				array(
					'label'  => esc_html__( 'Title', 'planet4-blocks' ),
					'attr'   => 'title_2',
					'type'   => 'text',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter title of second column', 'planet4-blocks' ),
						'data-test'   => 1,
					),
				),
				array(
					'label'    => esc_html__( 'Description', 'planet4-blocks' ),
					'attr'     => 'description_2',
					'type'     => 'textarea',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter description of second column', 'planet4-blocks' ),
						'data-test'   => 1,
					),
				),
				array(
					'label'    => __( 'Button text', 'planet4-blocks' ),
					'attr'     => 'button_text_2',
					'type'     => 'text',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter button text of second column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label'    => __( 'Button link', 'planet4-blocks' ),
					'attr'     => 'button_link_2',
					'type'     => 'url',
					'meta'   => array(
						'placeholder' => esc_html__( 'Enter button link of second column', 'planet4-blocks' ),
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
				'label' => esc_html__( 'Two Column', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => 'dashicons-editor',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 *
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs' => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_twocolumn', $shortcode_ui_args );
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
		public function prepare_two_columns( $fields, $content, $shortcode_tag ) {

			$fields = shortcode_atts( array(
				'title_1'       => esc_html( $fields['title_1'] ),
				'description_1' => wpautop( esc_html( $fields['description_1'] ) ),
				'button_text_1' => esc_html( $fields['button_text_1'] ),
				'button_link_1' => esc_html( $fields['button_link_1'] ),

				'title_2'       => esc_html( $fields['title_2'] ),
				'description_2' => wpautop( esc_html( $fields['description_2'] ) ),
				'button_text_2' => esc_html( $fields['button_text_2'] ),
				'button_link_2' => esc_html( $fields['button_link_2'] ),
			), $fields, $shortcode_tag );

			$data = [
				'fields' => $fields,
				'available_languages' => P4BKS_LANGUAGES,
				'domain' => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->view_template( 'two_columns', $data );

			return ob_get_clean();
		}
	}
}
