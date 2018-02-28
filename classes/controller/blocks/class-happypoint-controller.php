<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'HappyPoint_Controller' ) ) {

	/**
	 * Class HappyPoint_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class HappyPoint_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'happy_point';

		/**
		 * Shortcode UI setup for the happypoint shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label'       => __( 'Background', 'planet4-blocks' ),
					'attr'        => 'background',
					'type'        => 'attachment',
					'libraryType' => [ 'image' ],
					'addButton'   => __( 'Select Background Image', 'planet4-blocks' ),
					'frameTitle'  => __( 'Select Background Image', 'planet4-blocks' ),
				],
				[
					// translators: placeholder needs to represent the ordinal of the image, eg. 1st, 2nd etc.
					'label'       => sprintf( __( 'Select focus point for background image', 'planet4-blocks' ),  $i ) . '<img src="' . esc_url( plugins_url( '/planet4-plugin-blocks/admin/images/grid_9.png') ) . '" />',
					'attr'        => 'focus_image',
					'type'        => 'select',
					'options'     => [
						[ 'value' => 'left top',      'label' => __( '1 - Top Left', 'planet4-blocks' )],
						[ 'value' => 'center top',    'label' => __( '2 - Top Center', 'planet4-blocks' )],
						[ 'value' => 'right top',     'label' => __( '3 - Top Right', 'planet4-blocks' )],
						[ 'value' => 'left center',   'label' => __( '4 - Middle Left', 'planet4-blocks' )],
						[ 'value' => 'center center', 'label' => __( '5 - Middle Center', 'planet4-blocks' )],
						[ 'value' => 'right center',  'label' => __( '6 - Middle Right', 'planet4-blocks' )],
						[ 'value' => 'left bottom',   'label' => __( '7 - Bottom Left', 'planet4-blocks' )],
						[ 'value' => 'center bottom', 'label' => __( '8 - Bottom Center', 'planet4-blocks' )],
						[ 'value' => 'right bottom',  'label' => __( '9 - Bottom Right', 'planet4-blocks' )],
					],
				],
				[
					'label' => __( '<i>We use an overlay to fade the image back. Use a number between 1 and 100,<br /> the higher the number, the more faded the image will look. If you leave this <br/> empty, the default of 30 will be used.</i>', 'planet4-blocks' ),
					'attr'  => 'opacity',
					'type'  => 'number',
					'meta'  => [ 'data-test' => 30 ],
				],
				[
					'label' => __( 'Use mailing list iframe', 'planet4-blocks' ),
					'attr'  => 'mailing_list_iframe',
					'type'  => 'checkbox',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Happy Point', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/icon_happy_point.png' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4BKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
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

			$shortcode_atts_pairs =[
				'background'          => '',
				'opacity'             => 30,
				"focus_image" => $attributes[ "focus_image" ] ?? 'center center',
				'mailing_list_iframe' => '',
			];

			$fields = shortcode_atts( $shortcode_atts_pairs, $fields, $shortcode_tag );

			if ( ! is_numeric( $fields['opacity'] ) ) {
				$fields['opacity'] = 30;
			}

			$opacity = number_format( ( $fields['opacity'] / 100 ), 1 );

			$fields['background_html']     = wp_get_attachment_image( $fields['background'] );
			$fields['background_src']      = wp_get_attachment_image_src( $fields['background'], 'full' );
			$options                       = get_option( 'planet4_options' );
			$fields['engaging_network_id'] = $options['engaging_network_form_id'] ?? '';
			$fields['opacity']             = $opacity;

			$data = [
				'fields' => $fields
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
