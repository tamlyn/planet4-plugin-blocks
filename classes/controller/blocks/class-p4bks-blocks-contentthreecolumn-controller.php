<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_ContentThreeColumn_Controller' ) ) {

	class P4BKS_Blocks_ContentThreeColumn_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'content_three_column';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the ThreeColumn shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Title', 'planet4-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => __( 'Enter title', 'planet4-blocks' ),
					),
				),
				array(
					'label' => __( 'Description', 'planet4-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
					'meta'  => array(
						'placeholder' => __( 'Enter description', 'planet4-blocks' ),
					),
				),
				array(
					'label'       => __( 'Image 1', 'planet4-blocks' ),
					'attr'        => 'image_1',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select First Image', 'planet4-blocks' ),
					'frameTitle'  => __( 'Select First Image', 'planet4-blocks' ),
				),
				array(
					'label'       => __( 'Image 2', 'planet4-blocks' ),
					'attr'        => 'image_2',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select Second Image', 'planet4-blocks' ),
					'frameTitle'  => __( 'Select Second Image', 'planet4-blocks' ),
				),
				array(
					'label'       => __( 'Image 3', 'planet4-blocks' ),
					'attr'        => 'image_3',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => __( 'Select Third Image', 'planet4-blocks' ),
					'frameTitle'  => __( 'Select Third Image', 'planet4-blocks' ),
				)
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Three Columns', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/three_columns.png' ) . '" />',
				'attrs'         => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields This contains array of all data added.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode block of three column.
		 *
		 * @since 0.1.0
		 *
		 * @return string All the data used for the html.
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			for ( $i = 1; $i < 4; $i++ ) {
				$img_array = wp_get_attachment_image_src( $fields[ "image_$i" ], 'full' );
				if ( false !== $img_array && ! empty( $img_array ) ) {
					$fields["alt_$i"]    = get_post_meta( $fields["image_$i"], '_wp_attachment_image_alt', true );
					$fields["image_$i"]  = $img_array[0];
				}
			}

			$data = [
				'fields'      => $fields,
				'domain'      => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
