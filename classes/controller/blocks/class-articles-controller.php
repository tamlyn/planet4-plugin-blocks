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
		 * Hooks all the needed functions to load the block.
		 */
		public function load() {
			parent::load();
			add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_assets' ] );
		}


		/**
		 * Load assets only on the admin pages of the plugin.
		 *
		 * @param string $hook The slug name of the current admin page.
		 */
		public function load_admin_assets( $hook ) {

			if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
				return;
			}

			add_action( 'enqueue_shortcode_ui', function () {
				wp_enqueue_script( 'articles', P4BKS_ADMIN_DIR . 'js/articles.js', [ 'shortcode-ui' ] );
			} );
		}

		/**
		 * Shortcode UI setup for the article shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * @since 0.1.0
		 */
		public function prepare_fields() {

			$checkboxes                 = [];
			$planet4_article_type_terms = get_terms(
				[
					'hide_empty' => false,
					'orderby'    => 'name',
					'taxonomy'   => 'p4-page-type',
				]
			);

			// Construct a checkbox for each p4-page-type.
			if ( ! empty( $planet4_article_type_terms ) ) {
				$checkboxes[] = [
					'attr'        => 'ignore_categories',
					'label'       => 'Ignore Categories',
					'description' => 'Ignore categories when filtering posts to populate the content of this block',
					'type'        => 'checkbox',
					'value'       => 'false',
				];

				foreach ( $planet4_article_type_terms as $term ) {
					$checkboxes[] = [
						'attr'        => 'p4_page_type_' . str_replace( '-', '_', $term->slug ),
						'label'       => $term->name . ' Posts',
						'description' => 'Use Posts that belong to ' . $term->name . ' type to populate the content of this block',
						'type'        => 'checkbox',
						'value'       => 'false',
					];
				}
			}

			$fields = [
				[
					'label' => __( 'Article Heading', 'planet4-blocks-backend' ),
					'attr'  => 'article_heading',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Enter article heading', 'planet4-blocks-backend' ),
					],
				],
				[
					'label' => __( 'Article Count', 'planet4-blocks-backend' ),
					'attr'  => 'article_count',
					'type'  => 'number',
					'meta'  => [
						'placeholder' => __( 'Enter articles count', 'planet4-blocks-backend' ),
					],
				],
				[
					'label' => __( 'Read More Text', 'planet4-blocks-backend' ),
					'attr'  => 'read_more_text',
					'type'  => 'text',
					'meta'  => [
						'placeholder' => __( 'Add read more button text', 'planet4-blocks-backend' ),
					],
				],
				[
					'label'       => __( 'Read More Link', 'planet4-blocks-backend' ),
					'attr'        => 'read_more_link',
					'type'        => 'text',
					'description' => ! empty( $checkboxes ) ? '<br><p><h2>' . __( 'There are 2 options to select posts for the block', 'planet4-blocks-backend' ) . '</h2></p><hr>' .
					                                          '<h3>' . __( '1st option', 'planet4-blocks-backend' ) . '</h3>' .
					                                          __( 'Select posts based on post types.', 'planet4-blocks-backend' ) : '',
					'meta'        => [
						'placeholder' => __( 'Add read more button link', 'planet4-blocks-backend' ),
					],
				],
			];

			if ( ! empty( $checkboxes ) ) {
				$fields = array_merge( $fields, $checkboxes );
			}

			$fields[] = [
				'label'    => '<br><hr><h3>' . __( '2nd option', 'planet4-blocks-backend' ) . '</h3><br>' .
				              __( 'Select specific Posts', 'planet4-blocks-backend' ),
				'attr'     => 'posts',
				'type'     => 'post_select',
				'multiple' => true,
				'query'    => [
					'post_type' => 'post',
				],
			];

			// Define the Shortcode UI arguments.
			$shortcode_ui_args = [
				'label'         => __( 'Articles', 'planet4-blocks-backend' ),
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
		public function prepare_template( $fields, $content, $shortcode_tag ): string {

			// Read more button links to search results if no link is specified.
			$tag_id            = $fields['tag_id'] ?? '';

			// Article block default text setting.
			$options              = get_option( 'planet4_options' );
			$article_title        = $options['articles_block_title'] ?? __( 'Related Articles', 'planet4-blocks' );
			$article_button_title = $options['articles_block_button_title'] ?? __( 'READ ALL THE NEWS', 'planet4-blocks' );
			$article_count        = $options['articles_count'] ?? 3;

			$fields['article_heading'] = $fields['article_heading'] ?? $article_title;
			$fields['read_more_text']  = $fields['read_more_text'] ?? $article_button_title;
			$fields['article_count']   = $fields['article_count'] ?? $article_count;


			// Filter p4_page_type keys from fields attributes array.
			$post_types_temp = $this->filter_post_types( $fields );

			// If any of the p4 page types are selected or we are in a tag page then use the old behavior to populate posts.
			// Otherwise use the specific posts that were selected (new behavior).
			$all_posts = false;
			if ( ! empty( $post_types_temp ) || '' !== $tag_id ) {
				$all_posts = $this->filter_posts( $fields );
			} elseif ( isset( $fields['posts'] ) && '' !== $fields['posts'] ) {
				$all_posts = $this->filter_posts_by_ids( $fields );
			}

			// Populate posts array for frontend template if results have been returned.
			if ( false !== $all_posts ) {
				$recent_posts = $this->populate_post_items( $all_posts );
			} else {
				$recent_posts = [];
			}

			$data = [
				'fields'       => $fields,
				'recent_posts' => $recent_posts,
			];

			// Shortcode callbacks must return content, hence, output buffering here.
			ob_start();
			$this->view->block( self::BLOCK_NAME, $data );

			return ob_get_clean();
		}

		/**
		 * Populate selected posts for frontend template.
		 *
		 * @param array $posts Selected posts.
		 *
		 * @return array
		 */
		private function populate_post_items( $posts ) {
			$recent_posts = [];

			if ( $posts ) {
				foreach ( $posts as $recent ) {
					$recent['alt_text']  = '';
					$recent['thumbnail'] = '';
					$author_override     = get_post_meta( $recent['ID'], 'p4_author_override', true );
					$recent['author']    = '' === $author_override ? get_the_author_meta( 'display_name', $recent['post_author'] ) : $author_override;

					if ( has_post_thumbnail( $recent['ID'] ) ) {
						$recent['thumbnail']       = get_the_post_thumbnail_url( $recent['ID'], 'articles-medium-large' );
						$img_id                    = get_post_thumbnail_id( $recent['ID'] );
						$dimensions                = wp_get_attachment_metadata( $img_id );
						$recent['thumbnail_ratio'] = ( isset( $dimensions['height'] ) && $dimensions['height'] > 0 ) ? $dimensions['width'] / $dimensions['height'] : 1;
						$recent['alt_text']        = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
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
						$page_type    = $page_type_data[0]->name;
						$page_type_id = $page_type_data[0]->term_id;
					}

					$recent['page_type'] = $page_type;
					$recent['permalink'] = get_permalink( $recent['ID'] );

					$recent['filter_url'] = add_query_arg(
						[
							's'                            => ' ',
							'orderby'                      => 'relevant',
							'f[ptype][' . $page_type . ']' => $page_type_id,
						],
						get_home_url()
					);

					$recent_posts[] = $recent;
				}
			}

			return $recent_posts;
		}

		/**
		 * Filter posts based on post ids.
		 *
		 * @param array $fields Block fields values.
		 *
		 * @return array|false
		 */
		private function filter_posts_by_ids( $fields ) {

			$post_ids = $fields['posts'] ?? '';

			if ( empty( $post_ids ) || ! preg_split( '/^\d+(,\d+)*$/', $post_ids ) ) {
				$post_ids = [];
			} else {
				$post_ids = explode( ',', $post_ids );
			}

			if ( empty( $post_ids ) ) {
				return false;
			} else {

				// Get all posts with arguments.
				$args = [
					'orderby'          => 'post__in',
					'post_status'      => 'publish',
					'post__in'         => $post_ids,
					'suppress_filters' => false,
				];

				return wp_get_recent_posts( $args );
			}
		}

		/**
		 * Filter posts based on post types (p4_page_type terms).
		 *
		 * @param array $fields Block fields values.
		 *
		 * @return array|false
		 */
		private function filter_posts( &$fields ) {

			$tag_id         = $fields['tag_id'] ?? '';
			$tag_filter     = $tag_id ? '&f[tag][' . get_tag( $tag_id )->name . ']=' . $tag_id : '';
			$read_more_link = ( ! empty( $fields['read_more_link'] ) ) ? $fields['read_more_link'] : get_home_url() . '/?s=&orderby=post_date&f[ctype][Post]=3' . $tag_filter;

			$exclude_post_id   = (int) ( $fields['exclude_post_id'] ?? '' );
			$ignore_categories = $fields['ignore_categories'] ?? 'false';
			$options           = get_option( 'planet4_options' );

			// Get page categories.
			$post_categories   = get_the_category();
			$category_id_array = [];
			foreach ( $post_categories as $category ) {
				$category_id_array[] = $category->term_id;
			}

			// If any p4_page_type was selected extract the term's slug to be used in the wp query below.
			$post_types = $this->filter_post_types( $fields );

			// Get page/post tags.
			$post_tags = get_the_tags();

			// On other than tag page, read more link should lead to search page-preselected with current page categories/tags.
			if ( '' === $tag_id ) {
				$read_more_filter = '';
				if ( 'true' !== $ignore_categories ) {
					if ( $post_categories ) {
						foreach ( $post_categories as $category ) {
							// For issue page.
							if ( $category->parent === (int) $options['issues_parent_category'] ) {
								$read_more_filter .= '&f[cat][' . $category->name . ']=' . $category->term_id;
							}
						}
					}
				}

				if ( ! empty( $post_types ) ) {
					// We cannot filter search for more than one pagetype, so use the last one.
					$read_more_post_type = end( $post_types );
					$page_type_data      = get_term_by( 'slug', wp_unslash( $read_more_post_type ), 'p4-page-type' );
					$read_more_filter    .= '&f[ptype][' . $page_type_data->slug . ']=' . $page_type_data->term_id;
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


			// Get all posts with arguments.
			$args = [
				'numberposts'      => $fields['article_count'],
				'orderby'          => 'date',
				'post_status'      => 'publish',
				'suppress_filters' => false,
			];

			if ( 'true' !== $ignore_categories ) {
				if ( $category_id_array ) {
					$category_ids     = implode( ',', $category_id_array );
					$args['category'] = '( ' . $category_ids . ' )';
				}
			}

			// For post page block so current main post will exclude.
			if ( $exclude_post_id ) {
				$args['post__not_in'] = [ $exclude_post_id ];
			}

			if ( $tag_id ) {
				$args['tag_id'] = $tag_id;
			}

			if ( ! empty( $post_types ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'p4-page-type',
						'field'    => 'slug',
						'terms'    => $post_types,
					],
				];
			}

			// For posts and pages, display related articles based on current post/page tags.
			$current_post_type = get_post_type();
			if ( 'post' === $current_post_type || ( 'page' === $current_post_type && '' === $tag_id ) ) {
				if ( $post_tags ) {
					$tag_id_array = [];
					foreach ( $post_tags as $tag ) {
						$tag_id_array[] = $tag->term_id;
					}
					$args['tag__in'] = $tag_id_array;
				}
			}

			return wp_get_recent_posts( $args );
		}

		/**
		 * Extract p4 page type terms from block's attributes.
		 *
		 * @param array $fields Block fields values.
		 *
		 * @return array
		 */
		private function filter_post_types( $fields ) {
			// Filter p4_page_type keys from attributes array.
			$post_types_temp = array_filter( (array) $fields, function ( $key ) {
				return strpos( $key, 'p4_page_type' ) === 0;
			}, ARRAY_FILTER_USE_KEY );

			$post_types = [];
			// If any p4_page_type was selected extract the term's slug to be used in the wp query.
			if ( ! empty( $post_types_temp ) ) {
				foreach ( $post_types_temp as $type => $value ) {
					if ( 'true' === $value ) {
						$post_type    = str_replace( '_', '-',
							str_replace( 'p4_page_type_', '', $type )
						);
						$post_types[] = $post_type;
					}
				}
			}

			return $post_types;
		}
	}

}
