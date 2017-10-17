<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Covers_Controller' ) ) {

	class P4BKS_Blocks_Covers_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			$this->block_name = 'covers';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the shortcode.
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Title', 'planet4-blocks' ),
					'attr'  => 'title',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter title', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					],
				],
				[
					'label' => __( 'Description', 'planet4-blocks' ),
					'attr'  => 'description',
					'type'  => 'textarea',
					'meta'  => [
						'placeholder' => __( 'Enter description', 'planet4-blocks' ),
						'data-plugin' => 'planet4-blocks',
					],
				],
			];

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = [
				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label' => __( 'Take Action Covers', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/covers.png' ) . '" />',

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
			$actions = wp_get_recent_posts( [
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'order_by'      => 'date',
				'order'         => 'DESC',
				'numberposts'   => P4BKS_COVERS_NUM,
			], 'OBJECT' );

			if ( $actions ) {
				$site_url          = get_site_url();
				$fields['covers']  = [];
				$cover_button_text = __( 'Take Action', 'planet4-blocks' );

				foreach ( $actions as $action ) {
					$tags    = [];
					$wp_tags = wp_get_post_tags( $action->ID );

					if ( is_array( $wp_tags ) ) {
						foreach ( $wp_tags as $wp_tag ) {
							array_push( $tags, [
								'slug' => $wp_tag->slug,
								'href' => "$site_url/tag/$wp_tag->slug",
							]);
						}
					}
					array_push( $fields['covers'], [
						'tags'        => $tags,
						'title'       => get_the_title( $action->ID ),
						'excerpt'     => get_the_excerpt( $action->ID ),	// Note: WordPress removes shortcodes from auto-generated excerpts.
						'image'       => get_the_post_thumbnail_url( $action->ID ),
						'button_text' => $cover_button_text,
						'button_link' => get_post_permalink( $action->ID ),
					] );
				}
				$fields['button_text'] = __( 'Load More ...', 'planet4-blocks' );
				$fields['button_link'] = '.';
			}

			$data = [
				'fields' => $fields,
			];
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
