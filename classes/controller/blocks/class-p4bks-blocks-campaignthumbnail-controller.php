<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_CampaignThumbnail_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_CampaignThumbnail_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class P4BKS_Blocks_CampaignThumbnail_Controller extends P4BKS_Blocks_Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'campaign_thumbnail';

		/**
		 * Shortcode UI setup for the CampaignThumbnail shortcode.
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
				)
			);

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = array(
				'label'         => __( 'Campaign Thumbnail', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/campaign_thumbnail.png' ) . '" />',
				'attrs'         => $fields,
			);

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields This contains array of all data added.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode block of campaign thumbnail.
		 *
		 * @since 0.1.0
		 *
		 * @return string All the data used for the html.
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			$category_data  = get_the_category();
			$category_id    = ( isset( $category_data[0]->cat_ID ) && ! empty( $category_data[0]->cat_ID ) ) ? $category_data[0]->cat_ID : '';

			if( isset( $category_data[0]->cat_ID ) && ! empty( $category_data[0]->cat_ID ) ) {
				$args         = array( 'numberposts' => '1', 'category' => $category_id );
			} else {
				$args         = array( 'numberposts' => '1' );
			}

			$recent_posts   = wp_get_recent_posts( $args );
			$recent_post_id = ( isset( $recent_posts[0]['ID'] ) && ! empty( $recent_posts[0]['ID'] ) ) ? $recent_posts[0]['ID'] : '';

			$post_tags      = array();
			if( ! empty( $recent_post_id ) ) {
				$post_tags    = get_the_tags( $recent_post_id );
				$post_tags    = array_slice($post_tags, 0, 3);
			}

			foreach( $post_tags as $tag ) {
				$tags           = array();
				$tags['name']   = $tag->name;
				$tags['slug']   = $tag->slug;
				$tags['href']   = get_tag_link( $tag );
				$attachment_id  = get_term_meta( $tag->term_id, 'tag_attachment_id', true );

				if( ! empty( $attachment_id ) ) {
					$tags['image'] = wp_get_attachment_image_src( $attachment_id, 'medium' );
				}

				$fields['tags'][] = $tags;
			}

			$data = [
				'fields' => $fields,
				'domain' => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
