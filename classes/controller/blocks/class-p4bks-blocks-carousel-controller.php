<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Carousel_Controller' ) ) {

	class P4BKS_Blocks_Carousel_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			add_filter( 'attachment_fields_to_edit', array( $this, 'add_image_attachment_fields_to_edit' ), null, 2 );
			add_filter( 'attachment_fields_to_save', array( $this, 'add_image_attachment_fields_to_save' ), null , 2 );
			$this->block_name = 'carousel';
			parent::load();
		}

		/**
		 * Add custom media metadata fields
		 *
		 * Be sure to sanitize your data before saving it
		 * http://codex.wordpress.org/Data_Validation
		 *
		 * @param $form_fields An array of fields included in the attachment form
		 * @param $post The attachment record in the database
		 * @return $form_fields The final array of form fields to use
		 */
		function add_image_attachment_fields_to_edit( $form_fields, $post ) {

			// Add a Credit field
			$form_fields['credit_text'] = array(
				'label' => __( 'Credit' ),
				'input' => 'text', // this is default if "input" is omitted
				'value' => esc_attr( get_post_meta( $post->ID, '_credit_text', true ) ),
				'helps' => __( 'The owner of the image.' ),
			);

			return $form_fields;
		}

		/**
		 * Save custom media metadata fields
		 *
		 * Be sure to validate your data before saving it
		 * http://codex.wordpress.org/Data_Validation
		 *
		 * @param $post The $post data for the attachment
		 * @param $attachment The $attachment part of the form $_POST ($_POST[attachments][postID])
		 * @return $post
		 */
		function add_image_attachment_fields_to_save( $post, $attachment ) {
			if ( isset( $attachment['credit_text'] ) ) {
				update_post_meta( $post['ID'], '_credit_text', esc_attr( $attachment['credit_text'] ) );
			}

			return $post;
		}

		/**
		 * Shortcode UI setup for the carousel shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			$fields = array(
				// Multiple Image Field.
				array(
					'label' => __( 'Select Carousel Images', 'planet4-blocks' ),
					'attr'  => 'multiple_image',
					'type'  => 'attachment',
					'libraryType' => array( 'image' ),
					'multiple'    => true,
					'addButton'   => 'Select Carousel Images',
					'frameTitle'  => 'Select Carousel Images',
				),
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label' => __( 'Carousel', 'planet4-blocks' ),
				'listItemImage' => 'dashicons-editor-table',
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

			$explodeMultipleImageArray = explode( ',',$fields['multiple_image'] );
			$images_data = array();

			foreach ( $explodeMultipleImageArray as $imageID ) {

				$image_data_array = wp_get_attachment_image_src( $imageID, 'cta' );

				$images_data['image_src'] = $image_data_array[0];
				$image_metadata = get_post( $imageID );
				$attachment_fields = get_post_custom( $imageID );

				$images_data['credits'] = ( isset( $attachment_fields['_credit_text'][0] ) && ! empty( $attachment_fields['_credit_text'][0] ) ) ? esc_attr( $attachment_fields['_credit_text'][0] ) : '';

				$images_data['caption'] = $image_metadata->post_excerpt;

				$images[] = $images_data;
			}

			$data = [
				'images' => array_map( 'wp_kses_post', $images ),
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
