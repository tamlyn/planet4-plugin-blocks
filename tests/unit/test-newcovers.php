<?php

require_once __DIR__ . '/../p4-unittestcase.php';

use P4BKS\Controllers\Blocks\Articles_Controller as Articles;
use P4BKS\Controllers\Blocks\NewCovers_Controller as Covers;
use P4BKS\Views\View as View;

if ( ! class_exists( 'P4_NewCoversTest' ) ) {

	/**
	 * Class P4_ArticlesTest
	 *
	 * @package Planet4_Plugin_Blocks
	 */
	class P4_NewCoversTest extends P4_UnitTestCase {

		/** @var $block Articles */
		protected $block;

		/**
		 * This method sets up the test.
		 */
		public function setUp() {
			parent::setUp();
			$this->block = new Covers( new View() );
		}

		/**
		 * Test that the block retrieves all the available Posts with 'press-release' as p4 page type.
		 */
		public function test_filter_act_pages() {
			$dummy_posts = $this->get_dummy_posts();
			$this->factory->post->create_many( 5, $dummy_posts['take_action_pages'] );

			$fields = [];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_act_pages', [ &$fields ] );
			$this->assertEquals( 5, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );
		}

		/**
		 * Test that the block retrieves all the available Posts with 'publication' as p4 page type.
		 */
		public function test_filter_act_pages_by_ids() {
			$dummy_posts      = $this->get_dummy_posts();
			$action_pages_ids = $this->factory->post->create_many( 5, $dummy_posts['take_action_pages'] );


			$fields = [
				'posts' => implode( ',', $action_pages_ids ),
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_act_pages_by_ids', [ &$fields ] );
			$this->assertEquals( 5, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );
		}

		/**
		 * Get data which will be used to create dummy posts for the test.
		 *
		 * @return array
		 */
		private function get_dummy_posts(): array {

			// Create Act & Explore pages
			// Accepts the same arguments as wp_insert_post.
			$act_page_id = $this->factory->post->create( [
				'post_type'  => 'page',
				'post_title' => 'ACT',
				'post_name'  => 'act',
			] );

			$explore_page_id = $this->factory->post->create( [
				'post_type'  => 'page',
				'post_title' => 'EXPLORE',
				'post_name'  => 'explore',
			] );

			// Define explore and act pages in planet4_options.
			update_option( 'planet4_options',
				[
					'act_page'     => $act_page_id,
					'explore_page' => $explore_page_id,
				]
			);

			return [
				'press-release'     => [
					'post_author' => 1,
					'post_title'  => 'this is a press release',
					'post_status' => 'publish',
				],
				'publication'       => [
					'post_author' => 1,
					'post_title'  => 'this is a publication',
					'post_status' => 'publish',
				],
				'story'             => [
					'post_author' => 1,
					'post_title'  => 'this is a story',
					'post_status' => 'publish',
				],
				'take_action_pages' => [
					'post_author' => 1,
					'post_title'  => 'this is a take action page',
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_parent' => $act_page_id,
				],
				'explore_pages'     => [
					'post_author' => 1,
					'post_title'  => 'this is an explore page',
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_parent' => $explore_page_id,
				],
			];
		}
	}
}
