<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'P4BKS_Blocks_Articles_Controller' ) ) {

	class P4BKS_Blocks_Articles_Controller extends P4BKS_Blocks_Controller {


		/**
		 * Override this method in order to give your block its own name.
		 */
		public function load() {
			// --- Set here the name of your block ---
			$this->block_name = 'articles';
			parent::load();
		}

		/**
		 * Shortcode UI setup for the twocolumn shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			/*
			 * Define the UI for attributes of the shortcode. Optional.
			 *
			 * If no UI is registered for an attribute, then the attribute will
			 * not be editable through Shortcake's UI. However, the value of any
			 * unregistered attributes will be preserved when editing.
			 *
			 * Each array must include 'attr', 'type', and 'label'.
			 * * 'attr' should be the name of the attribute.
			 * * 'type' options include: text, checkbox, textarea, radio, select, email,
			 *     url, number, and date, post_select, attachment, color.
			 * * 'label' is the label text associated with that input field.
			 *
			 * Use 'meta' to add arbitrary attributes to the HTML of the field.
			 *
			 * Use 'encode' to encode attribute data. Requires customization in shortcode callback to decode.
			 *
			 * Depending on 'type', additional arguments may be available.
			 */
				$fields = array(

					array(
						'label' => esc_html__( 'Article Heading', 'planet4-blocks' ),
						'attr'  => 'article_heading',
						'type'  => 'text',
						'meta'  => array(
							'placeholder' => esc_html__( 'Enter article heading', 'planet4-blocks' ),
						),
					),

					array(
						'label' => esc_html__( 'Article Count', 'planet4-blocks' ),
						'attr'  => 'article_count',
						'type'  => 'text',
						'meta'  => array(
							'placeholder' => esc_html__( 'Enter articles count', 'planet4-blocks' ),
						),
					),
				);

			/*
			 * Define the Shortcode UI arguments.
			 */
			$shortcode_ui_args = array(
				/*
				 * How the shortcode should be labeled in the UI. Required argument.
				 */
				'label' => esc_html__( 'Articles', 'planet4-blocks' ),

				/*
				 * Include an icon with your shortcode. Optional.
				 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
				 */
				'listItemImage' => 'dashicons-editor-table',

				/*
				 * Define the UI for attributes of the shortcode. Optional.
				 * See above, to where the the assignment to the $fields variable was made.
				 */
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

			// Get all posts with arguments.
			$args = array(
				'numberposts' => $fields['article_count'],
				'order' => 'ASC',
				'orderby' => 'title',
			);
			$all_posts = wp_get_recent_posts( $args );

			foreach ( $all_posts as $recent ) {
				if ( has_post_thumbnail( $recent['ID'] ) ) {
						$thumbnail_image = get_the_post_thumbnail_url( $recent['ID'],'single-post-thumbnail' );
						$recent['thumbnail'] = $thumbnail_image;
				}
				$recent['tags'] = wp_get_post_tags( $recent['ID'] );
				$recent['category'] = get_the_category( $recent['ID'] );
				$recent_posts[] = $recent;
			}

			$data = [
				'fields' => array_map( 'wp_kses_post', $fields ),
				'recent_posts'  => $recent_posts,
			];
			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( $this->block_name, $data );

			return ob_get_clean();
		}
	}
}
