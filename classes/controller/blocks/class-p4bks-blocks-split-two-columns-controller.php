<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Split_Two_Columns_Controller' ) ) {

	class P4BKS_Blocks_Split_Two_Columns_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'split_two_columns';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			$issues = get_posts( [
				'post_type'     => 'page',
				'category_name' => 'issues',
			] );

			$options = [];
			if ( $issues ) {
				foreach ( $issues as $issue ) {
					array_push( $options, [
						'value' => (string) $issue->ID,
						'label' => get_the_title( $issue->ID ),
					] );
				}
			}

			$fields = [
				[
					'attr'        => 'select_issue',
					'label'       => __( 'Select an Issue', 'planet4-blocks' ),
					'description' => 'Associate this block to the Issue that it will talk about',
					'type'        => 'select',
					'options'     => $options,
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
				'listItemImage' => '<img src="' . esc_url( plugins_url() . "/planet4-plugin-blocks/admin/images/$this->block_name.png" ) . '" />',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 * See above, to where the the assignment to the $fields variable was made.
				 */
				'attrs' => $fields,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
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
			$page_meta_data = get_post_meta( $issue_id );

			$data = [
				'issue' => [
					'title'       => null === $page_meta_data['p4_title'][0] ? get_the_title( $issue_id ) : $page_meta_data['p4_title'][0],
					'description' => $page_meta_data['p4_description'][0],
					'link_text'   => __( 'Learn more about this issue', 'planet4-blocks' ),
					'link_url'    => get_post_permalink( $issue_id ),
				],
			];
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
