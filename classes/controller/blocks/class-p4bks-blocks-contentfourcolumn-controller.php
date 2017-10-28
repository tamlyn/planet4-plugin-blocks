<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_ContentFourColumn_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_ContentFourColumn_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class P4BKS_Blocks_ContentFourColumn_Controller extends P4BKS_Blocks_Controller {

		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'content_four_column';
			parent::load();
		}

		/**
		 * Shortcode UI setup for content four column shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
		 */
		public function prepare_fields() {

			$fields = [
				[
					'attr'        => 'select_tag',
					'label'       => __( 'Select a Tag', 'planet4-blocks' ),
					'description' => __( 'Associate this block with Posts that have a specific Tag', 'planet4-blocks' ),
					'type'        => 'term_select',
					'taxonomy'    => 'post_tag',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Content Four Column', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/content_four_column.png' ) . '" />',
				'attrs'         => $fields,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . $this->block_name, $shortcode_ui_args );
		}

		/**
		 * Callback for content four column shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $attributes    Defined attributes array for this shortcode.
		 * @param string $content       Content.
		 * @param string $shortcode_tag Shortcode tag name.
		 *
		 * @return string Returns the compiled template.
		 */
		public function prepare_template( $attributes, $content, $shortcode_tag ) : string {

			$tag_id = absint( $attributes['select_tag'] );

			// Get all posts with a specific tag.
			// Construct the arguments array for the query.
			$args  = array(
				'tag_id'  => $tag_id,
				'order'   => 'DESC',
				'orderby' => 'date',
			);
			$query = new \WP_Query( $args );

			$posts_array = [];
			if ( $query->have_posts() ) {

				$posts = $query->get_posts();

				foreach ( $posts as $post ) {

					$post->alt_text  = '';
					$post->thumbnail = '';

					if ( has_post_thumbnail( $post ) ) {
						$post->thumbnail = get_the_post_thumbnail_url( $post, 'single-post-thumbnail' );
						$img_id          = get_post_thumbnail_id( $post );
						$post->alt_text  = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
					}

					$post->permalink = get_permalink( $post );
					$posts_array[]   = $post;
				}
			}

			$block_data = [
				'posts'  => $posts_array,
				'domain' => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $block_data );

			return ob_get_clean();
		}
	}
}