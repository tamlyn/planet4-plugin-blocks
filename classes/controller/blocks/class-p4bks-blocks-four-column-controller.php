<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_FourColumn_Controller' ) ) {

	class P4BKS_Blocks_FourColumn_Controller extends P4BKS_Blocks_Controller {

		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'four_column';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the four column shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
		 */
		public function prepare_fields() {

			// This block will have 4 different columns with same fields
			$fields = [];
			for ( $i = 1; $i < 5; $i ++ ) {
				$ordinal = $i . date( "S", mktime( 0, 0, 0, 0, $i, 0 ) );
				$field   = [
					[
						'label'       => esc_html__( "Select Image for $ordinal column", 'planet4-blocks' ),
						'attr'        => 'attachment_' . $i,
						'type'        => 'attachment',
						'libraryType' => array( 'image' ),
						'addButton'   => esc_html__( 'Select Image', 'shortcode-ui' ),
						'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui' ),
					],
					[
						'label' => __( 'Image link', 'planet4-blocks' ),
						'attr'  => 'image_link_' . $i,
						'type'  => 'url',
						'meta'  => [
							'placeholder' => esc_html__( "Enter $ordinal image link", 'planet4-blocks' ),
							'data-plugin' => 'planet4-blocks',
						],
					],
					[
						'label' => esc_html__( 'Title', 'planet4-blocks' ),
						'attr'  => 'title_' . $i,
						'type'  => 'text',
						'meta'  => [
							'placeholder' => esc_html__( "Enter title of $ordinal column", 'planet4-blocks' ),
							'data-plugin' => 'planet4-blocks',
						],
					],
					[
						'label' => esc_html__( 'Description', 'planet4-blocks' ),
						'attr'  => 'description_' . $i,
						'type'  => 'textarea',
						'meta'  => [
							'placeholder' => esc_html__( "Enter description of $ordinal column", 'planet4-blocks' ),
							'data-plugin' => 'planet4-blocks',
						],
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
				'label'         => esc_html__( 'Content Four Column', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => 'dashicons-grid-view',

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
		 * Callback for the four column shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array $attr
		 * @param string $content
		 * @param string $shortcode_tag
		 *
		 * @return string
		 */
		public function prepare_template( $attr, $content, $shortcode_tag ): string {

			$attrs_temp = [];
			for ( $i = 1; $i < 5; $i ++ ) {
				$temp       = [
					"title_$i"       => esc_html( $attr["title_$i"] ),
					"description_$i" => wpautop( esc_html( $attr["description_$i"] ) ),
					"attachment_$i"  => $attr["attachment_$i"],
					"image_link_$i" => esc_html( $attr["image_link_$i"] ),
				];
				$attrs_temp = array_merge( $attrs_temp, $temp );
			}
			$attr = shortcode_atts( $attrs_temp, $attr, $shortcode_tag );

			for ( $i = 1; $i < 5; $i ++ ) {
				$temp = wp_get_attachment_image_src( $attr["attachment_$i"] );
				if ( $temp !== false && ! empty( $temp ) ) {
					$attr["attachment_$i"] = $temp[0];
				}
			}

			$data = [
				'fields'              => $attr,
				'available_languages' => P4BKS_LANGUAGES,
				'domain'              => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
