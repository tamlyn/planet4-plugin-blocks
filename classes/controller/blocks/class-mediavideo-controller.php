<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'MediaVideo_Controller' ) ) {

	/**
	 * Class MediaVideo_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class MediaVideo_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'media_video';

		/**
		 * Shortcode UI setup for the Mediavideo shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			$fields = array(
				array(
					'label' => __( 'Video Title', 'planet4-blocks-backend' ),
					'attr'  => 'video_title',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => __( 'Enter video title', 'planet4-blocks-backend' ),
					),
				),
				array(
					'label' => __( 'Youtube ID', 'planet4-blocks-backend' ),
					'attr'  => 'youtube_id',
					'type'  => 'text',
					'meta'  => array(
						'placeholder' => __( 'Enter youtube video id', 'planet4-blocks-backend' ),
					),
				)
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Youtube Video', 'planet4-blocks-backend' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/media_video.jpg' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4BKS_ALLOWED_PAGETYPE,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Get all the data that will be needed to render the block correctly.
		 *
		 * @param array  $fields This contains array of article shortcake block field.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode block of article.
		 *
		 * @return array The data to be passed in the View.
		 */
		public function prepare_data( $fields, $content, $shortcode_tag ) : array {

			$data = [
				'fields' => $fields,
			];
			return $data;
		}
	}
}
