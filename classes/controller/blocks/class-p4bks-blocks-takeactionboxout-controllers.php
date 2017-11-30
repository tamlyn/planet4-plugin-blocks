<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_TakeActionBoxout_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_TakeActionBoxout_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class P4BKS_Blocks_TakeActionBoxout_Controller extends P4BKS_Blocks_Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'take_action_boxout';

		/**
		 * Shortcode UI setup for the shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {

			// Get the id of the ACT page. We need this to get the children posts/pages of the ACT Page.
			$parent_act_id = planet4_get_option( 'select_act_page' );
			$arguments     = [];
			if( 0 !== absint( $parent_act_id ) ) {
				$act_page = get_post( $parent_act_id );
				$arguments = [
					'post_type'     => 'page',
					'post_name__in' => [ $act_page->post_name ],
				];
			}

			// Initialize variable.
			$take_action_pages_args = [];
			$query                  = new \WP_Query( $arguments );

			// If ACT Page is found construct arguments array for the select box to be used below.
			if ( $query->have_posts() ) {
				$posts                  = $query->get_posts();
				$post                   = $posts[0];
				$take_action_pages_args = [
					'post_type'   => 'page',
					'post_parent' => $post->ID,
				];
			} else {
				$take_action_pages_args = [
					'post_type' => 'page',
				];
			}

			$fields = [
				[
					'attr'        => 'take_action_page',
					'label'       => __( 'Select a Take Action Page', 'planet4-blocks' ),
					'description' => __( 'Associate this block with a Take Action page', 'planet4-blocks' ),
					'type'        => 'post_select',
					'query'       => $take_action_pages_args,  // Filter select options only with ACT page children.
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Take Action Boxout', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/take_action_boxout.png' ) . '" />',
				'attrs'         => $fields,
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
			$page_id = $fields['take_action_page'];

			$args = [
				'p'         => intval( $page_id ), // ID of a page, post.
				'post_type' => 'any',
			];

			// Try to find the page that the user selected.
			$query = new \WP_Query( $args );
			$page  = null;
			$tag   = null;

			// If page is found populate the necessary fields for the block.
			if ( $query->have_posts() ) {
				$posts   = $query->get_posts();
				$page    = $posts[0];
				$wp_tags = wp_get_post_tags( $page->ID );
				if ( is_array( $wp_tags ) && $wp_tags ) {
					$tag = $wp_tags[0];
				}
			}

			// Populate variables.
			$block = [
				'first_tag'      => null === $tag ? '' : $tag->slug,
				'first_tag_link' => null === $tag ? '' : get_tag_link( $tag ),
				'title'          => null === $page ? '' : $page->post_title,
				'excerpt'        => null === $page ? '' : $page->post_excerpt,
				'link'           => null === $page ? '' : get_permalink( $page ),
				'image'          => null === $page ? '' : get_the_post_thumbnail_url( $page, 'large' ),
			];

			$data = [
				'page' => $block,
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
