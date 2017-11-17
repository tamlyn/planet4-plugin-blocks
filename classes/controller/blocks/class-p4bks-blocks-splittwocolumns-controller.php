<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_SplitTwoColumns_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_SplitTwoColumns_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class P4BKS_Blocks_SplitTwoColumns_Controller extends P4BKS_Blocks_Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'split_two_columns';

		/**
		 * Shortcode UI setup for the shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$categories = get_categories( [
				'parent'  => get_cat_ID( 'Issues' ),            // Issue categories needs to be children of category Issues.
				'orderby' => 'name',
				'order'   => 'ASC',
			] );

			$options = [];
			if ( $categories ) {
				foreach ( $categories as $category ) {
					$issue = get_page_by_title( $category->name );      // Category and Issue need to have the same name.
					$options[] = [
						'value' => (string) $issue->ID,
						'label' => get_the_title( $issue->ID ),
					];
				}
			}

			$fields = [
				[
					'attr'        => 'select_issue',
					'label'       => __( 'Select an Issue', 'planet4-blocks' ),
					'description' => __( 'Associate this block to the Issue that it will talk about', 'planet4-blocks' ),
					'type'        => 'select',
					'options'     => $options,
				],
				[
					'attr'        => 'select_tag',
					'label'       => __( 'Select a Tag', 'planet4-blocks' ),
					'description' => __( 'Associate the selected Issue with a Tag', 'planet4-blocks' ),
					'type'        => 'term_select',
					'taxonomy'    => 'post_tag',
				],
				[
					'label' => __( 'Description', 'planet4-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Enter description', 'planet4-blocks' ),
					],
					'description' => __( '(Optional)', 'planet4-blocks' ),
				],
				[
					'label' => __( 'Button text', 'planet4-blocks' ),
					'attr'  => 'button_text',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter button text', 'planet4-blocks' ),
					],
					'description' => __( '(Optional)', 'planet4-blocks' ),
				],
				[
					'label' => __( 'Button link', 'planet4-blocks' ),
					'attr'  => 'button_link',
					'type'  => 'url',
					'meta'  => [
						'placeholder' => __( 'Enter button link', 'planet4-blocks' ),
					],
					'description' => __( '(Optional)', 'planet4-blocks' ),
				],

			];

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = [

				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label' => __( 'Split Two Columns', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/' . self::BLOCK_NAME . '.png' ) . '" />',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs' => $fields,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields Array of fields that are to be used in the template.
		 * @param string $content The content of the post.
		 * @param string $shortcode_tag The shortcode tag.
		 *
		 * @return string
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {
			$issue_id = absint( $fields['select_issue'] );
			$issue_meta_data = get_post_meta( $issue_id );

			$tag_id        = absint( $fields['select_tag'] );
			$tag           = get_term( $tag_id );
			$attachment_id = get_term_meta( $tag_id, 'tag_attachment_id', true );

			$data = $issue_meta_data ? [
				'issue' => [
					'title'       => $issue_meta_data['p4_title'][0] ?? get_the_title( $issue_id ),
					'description' => $issue_meta_data['p4_description'][0] ?? '',
					'image'       => get_the_post_thumbnail_url( $issue_id ),
					'link_text'   => __( 'Learn more about this issue', 'planet4-blocks' ),
					'link_url'    => get_permalink( $issue_id ),
				],
				'campaign' => [
					'image'       => wp_get_attachment_url( $attachment_id ),
					'name'        => $tag->name,
					'link'        => get_tag_link( $tag ),
					'description' => $fields['description'] ?? $tag->description,
					'button_text' => $fields['button_text'] ?? __( 'Support this campaign', 'planet4-blocks' ),
					'button_link' => $fields['button_link'] ?? get_tag_link( $tag ),
				],
			] : [];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
