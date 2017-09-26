<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_CarouselSplit_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_CarouselSplit_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class P4BKS_Blocks_CarouselSplit_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'carousel_split';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the carousel split shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {

			// This block will have 4 different columns with same fields.
			$fields = [];
			for ( $i = 1; $i < 5; $i ++ ) {
				$ordinal = $i . date( 'S', mktime( 0, 0, 0, 0, $i, 0 ) );
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
						'libraryType' => [ 'image' ],
						'addButton'   => esc_html__( 'Select Image', 'shortcode-ui' ),
						'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui' ),
					],
				];
				$fields  = array_merge( $fields, $field );
			}
			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => esc_html__( 'Carousel Split', 'planet4-blocks' ),
				'listItemImage' => 'dashicons-grid-view',
				'attrs'         => $fields,
			);
			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $attrs Fields array defined for this shortcode.
		 * @param string $content
		 * @param string $shortcode_tag
		 *
		 * @since 0.1.0
		 *
		 * @return string
		 */
		public function prepare_template( $attrs, $content, $shortcode_tag ) : string {

			$attrs_temp = [];
			for ( $i = 1; $i < 5; $i ++ ) {
				$temp_array       = [
					"title_$i"       => $attrs[ "title_$i" ],
					"caption_$i"     => wpautop( esc_html( $attrs[ "caption_$i" ] ) ),
					"attachment_$i"  => $attrs[ "attachment_$i" ],
				];
				$attrs_temp = array_merge( $attrs_temp, $temp_array );
			}
			$attrs = shortcode_atts( $attrs_temp, $attrs, $shortcode_tag );
			for ( $i = 1; $i < 5; $i ++ ) {
				$temp_array = wp_get_attachment_image_src( $attrs[ "attachment_$i" ] );
				if ( false !== $temp_array && ! empty( $temp_array ) ) {
					$attrs[ "attachment_$i" ] = $temp_array[0];
				}
			}
			$block_data = [
				'fields'              => $attrs,
				'available_languages' => P4BKS_LANGUAGES,
				'domain'              => 'planet4-blocks',
			];
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $block_data );

			return ob_get_clean();
		}
	}
}
