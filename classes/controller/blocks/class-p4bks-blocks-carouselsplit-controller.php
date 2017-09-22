<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_CarouselSplit_Controller' ) ) {

	class P4BKS_Blocks_CarouselSplit_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			// --- Set here the name of your block ---
			$this->block_name = 'carousel_split';
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

			$fields = array(
				// First column fields.
				array(
					'label' => esc_html__( 'Title', 'planet4-blocks' ),
					'attr'  => 'title_1',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => esc_html__( 'Enter title of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label' => esc_html__( 'Description', 'planet4-blocks' ),
					'attr'  => 'description_1',
					'type'  => 'textarea',
					'meta'  => array(
						'placeholder' => esc_html__( 'Enter description of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label' => __( 'Button text', 'planet4-blocks' ),
					'attr'  => 'button_text_1',
					'type'  => 'text',
					'value' => __( 'Find a way to make change', 'planet4-blocks'),
					'meta'  => array(
						'data-plugin' => 'planet4-blocks',
					),
				),
				array(
					'label' => __( 'Button link', 'planet4-blocks' ),
					'attr'  => 'button_link_1',
					'type'  => 'url',
					'meta'  => array(
						'placeholder' => esc_html__( 'Enter button link of first column', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					),
				),
			);


// This block will have 4 different columns with same fields
			$fields = [];
			for ( $i = 1; $i < 5; $i ++ ) {
				$ordinal = $i . date( "S", mktime( 0, 0, 0, 0, $i, 0 ) );
				$field   = [
					[
						'label' => esc_html__( 'Title', 'planet4-blocks' ),
						'attr'  => 'title_' . $i,
						'type'  => 'text',
						'meta'  => [
							'placeholder' => esc_html__( "Enter title of $ordinal image", 'planet4-blocks' ),
							'data-plugin' => 'planet4-blocks',
						],
					],
					[
						'label' => esc_html__( 'Caption', 'planet4-blocks' ),
						'attr'  => 'caption_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => esc_html__( "Enter caption of $ordinal image", 'planet4-blocks' ),
							'data-plugin' => 'planet4-blocks',
						],
					],
					[
						'label'       => esc_html__( 'Attachment', 'shortcode-ui' ),
						'attr'        => 'attachment_' . $i,
						'type'        => 'attachment',
						/*
						 * These arguments are passed to the instantiation of the media library:
						 * 'libraryType' - Type of media to make available.
						 * 'addButton'   - Text for the button to open media library.
						 * 'frameTitle'  - Title for the modal UI once the library is open.
						 */
						'libraryType' => array( 'image' ),
						'addButton'   => esc_html__( 'Select Image', 'shortcode-ui' ),
						'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui' ),
					],
				];
				$fields  = array_merge( $fields, $field );
			}
			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = array(
				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label'         => esc_html__( 'Carousel Split', 'planet4-blocks' ),
				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => 'dashicons-screenoptions',
				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 *
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs'         => $fields,
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
