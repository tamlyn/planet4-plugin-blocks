<?php

namespace P4BKS\Controllers\Blocks;

if ( ! class_exists( 'Articles_Controller' ) ) {

	/**
	 * Class Articles_Controller
	 *
	 * @package P4BKS\Controllers\Blocks
	 */
	class Articles_Controller extends Controller {

		/** @const string BLOCK_NAME */
		const BLOCK_NAME = 'articles';

		/**
		 * Shortcode UI setup for the article shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {
			$fields = [
				[
					'label' => __( 'Article Heading', 'planet4-blocks' ),
					'attr'  => 'article_heading',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter article heading', 'planet4-blocks' ),
					],
				],
				[
					'label' => __( 'Article Count', 'planet4-blocks' ),
					'attr'  => 'article_count',
					'type'  => 'number',
					'meta'  => [
						'placeholder' => __( 'Enter articles count', 'planet4-blocks' ),
					],
				],
				[
					'label' => __( 'Read More Text', 'planet4-blocks' ),
					'attr'  => 'read_more_text',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Add read more button text', 'planet4-blocks' ),
					],
				],
				[
					'label' => __( 'Read More Link', 'planet4-blocks' ),
					'attr'  => 'read_more_link',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Add read more button link', 'planet4-blocks' ),
					],
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Articles', 'planet4-blocks' ),
				'listItemImage' => '<img src="' . esc_url( plugins_url() . '/planet4-plugin-blocks/admin/images/home_news.jpg' ) . '" />',
				'attrs'         => $fields,
				'post_type'     => P4BKS_ALLOWED_PAGETYPE,
			];

			shortcode_ui_register_for_shortcode( 'shortcake_' . self::BLOCK_NAME, $shortcode_ui_args );
		}

		/**
		 * Callback for the shortcode.
		 * It renders the shortcode based on supplied attributes.
		 *
		 * @param array  $fields This contains array of article shortcake block field.
		 * @param string $content This is the post content.
		 * @param string $shortcode_tag The shortcode block of article.
		 *
		 * @since 0.1.0
		 *
		 * @return string All the data used for the html.
		 */
		public function prepare_template( $fields, $content, $shortcode_tag ) : string {

			// Read more button links to search results if no link is specified.
			$fields['article_count']  = ( ! empty( $fields['article_count'] ) ) ? $fields['article_count'] : 3;
			$fields['read_more_text'] = $fields['read_more_text'] ?? __( 'READ ALL THE NEWS', 'planet4-blocks' );
			$tag_id                   = $fields['tag_id'] ?? '';
			$tag_filter               = $tag_id ? '&f[tag][' . get_tag( $tag_id )->name . ']=' . $tag_id : '';
			$read_more_link           = ( ! empty( $fields['read_more_link'] ) ) ? $fields['read_more_link'] : get_site_url() . '/?s=&orderby=post_date&f[ctype][Post]=3' . $tag_filter;
			$exclude_post_id          = (int) $fields['exclude_post_id'] ?? '';

			//Get page categories
			$post_categories         = get_the_category();

			$category_id_array = [];
			foreach ( $post_categories as $category ) {
				$category_id_array[] = $category->term_id;
			}

			// Get page/post tags.
			$post_tags = get_the_tags();

			// On other than tag page, read more link should lead to search page-preselected with current page categories/tags.
			if ( '' == $tag_id ) {
				$read_more_filter = '';
				if ( $post_categories ) {
					$options = get_option( 'planet4_options' );
					foreach ( $post_categories as $category ) {
						// For issue page.
						if ( $category->parent === (int)$options['issues_parent_category'] ) {
							$read_more_filter .= '&f[cat][' . $category->name . ']=' . $category->term_id;
						}
					}
				}

				if ( '' === $read_more_filter ) {
					// For normal page and post.
					if ( $post_tags ) {
						foreach ( $post_tags as $tag ) {
							$read_more_filter .= '&f[tag][' . $tag->name . ']=' . $tag->term_id;
						}
					}
				}

				$read_more_link = $fields['read_more_link'] ?? $read_more_link . $read_more_filter;
			}
			$fields['read_more_link'] = $read_more_link;

			$category_ids = '';
			if ( $category_id_array ) {
				$category_ids = implode( ',', $category_id_array );
			}

			// Get all posts with arguments.
			$args = [
				'numberposts' => $fields['article_count'],
				'orderby'     => 'date',
				'category'    => '( ' . $category_ids . ' )',
				'post_status' => 'publish',
			];

			// For post page block so current main post will exclude.
			if( $exclude_post_id ) {
				$args['post__not_in'] = [$exclude_post_id];
			}

			if ( $tag_id ) {
				$args['tag_id'] = $tag_id;
			}

			// For post, display related article based on current post tags.
			if ( 'post' === get_post_type() ) {
				if ( $post_tags ) {
					$tag_id_array = [];
					foreach ( $post_tags as $tag ) {
						$tag_id_array[] = $tag->term_id;
					}
					$args['tag__in'] = $tag_id_array;
				}
			}

			$all_posts = wp_get_recent_posts( $args );

			if ( $all_posts ) {
				foreach ( $all_posts as $recent ) {
					$recent['alt_text']  = '';
					$recent['thumbnail'] = '';
					$author_override     = get_post_meta( $recent['ID'], 'p4_author_override', true );
					$recent['author']    = '' === $author_override ? get_the_author_meta( 'display_name', $recent['post_author'] ) : $author_override;

					if ( has_post_thumbnail( $recent['ID'] ) ) {
						$recent['thumbnail'] = get_the_post_thumbnail_url( $recent['ID'], 'medium' );
						$img_id              = get_post_thumbnail_id( $recent['ID'] );
						$recent['alt_text']  = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
						$recent['srcset']    = wp_calculate_image_srcset( [ '400', '267' ], wp_get_attachment_image_src( $img_id, 'medium' )[0], wp_get_attachment_metadata( $img_id ) );
					}

					$wp_tags = wp_get_post_tags( $recent['ID'] );

					$tags = [];

					if ( $wp_tags ) {
						foreach ( $wp_tags as $wp_tag ) {
							$tags_data['name'] = $wp_tag->name;
							$tags_data['slug'] = $wp_tag->slug;
							$tags_data['href'] = get_tag_link( $wp_tag );
							$tags[]            = $tags_data;
						}
					}

					$recent['tags'] = $tags;
					$page_type_data = get_the_terms( $recent['ID'], 'p4-page-type' );
					$page_type      = '';

					if ( $page_type_data ) {
						$page_type = $page_type_data[0]->name;
					}

					$recent['page_type'] = $page_type;
					$recent['permalink'] = get_permalink( $recent['ID'] );
					$recent_posts[]      = $recent;
				}
			}

			$data = [
				'fields'        => $fields,
				'recent_posts'  => $recent_posts,
				'domain'        => 'planet4-blocks',
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}
	}
}
