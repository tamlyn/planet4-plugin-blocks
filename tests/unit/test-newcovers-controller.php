<?php

require_once __DIR__ . '/../p4-unittestcase.php';

use P4BKS\Controllers\Blocks\NewCovers_Controller as Covers;
use P4BKS\Views\View as View;

if ( ! class_exists( 'Newcovers_Controller_Test' ) ) {

	/**
	 * Class Newcovers_Controller_Test
	 *
	 * @package Planet4_Plugin_Blocks
	 * @coversDefaultClass \P4BKS\Controllers\Blocks\NewCovers_Controller
	 */
	class Newcovers_Controller_Test extends P4_UnitTestCase {

		/** @var $block Covers */
		protected $block;

		/**
		 * This method sets up the test.
		 */
		public function setUp() {
			parent::setUp();
			$this->block = new Covers( new View() );
		}

		/**
		 * Test that filter_posts_for_act_pages returns take action pages.
		 *
		 * @covers ::filter_posts_for_act_pages
		 */
		public function test_filter_act_pages() {
			$dummy_posts = $this->get_dummy_posts();

			// No action pages exist yet.
			$fields = [];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_act_pages', [ &$fields ] );
			$this->assertEquals( 0, count( $posts ) );

			// Create take action pages.
			$this->factory->post->create_many( 5, $dummy_posts['take_action_pages'] );
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_act_pages', [ &$fields ] );
			$this->assertEquals( 5, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );
		}

		/**
		 * Test that filter_posts_for_act_pages_by_ids returns take action pages provided by post ids.
		 *
		 * @covers ::filter_posts_by_ids
		 */
		public function test_filter_act_pages_by_ids() {
			$dummy_posts      = $this->get_dummy_posts();
			$action_pages_ids = $this->factory->post->create_many( 5, $dummy_posts['take_action_pages'] );

			// Passing post ids.
			$fields = [
				'cover_type' => '1',
				'posts' => implode( ',', $action_pages_ids ),
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_by_ids', [ &$fields ] );
			$this->assertEquals( 5, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );

			// Not passing any post ids, should return empty array.
			$fields = [
				'posts' => '',
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_by_ids', [ &$fields ] );
			$this->assertEquals( 0, count( $posts ) );
		}

		/**
		 * Test filtering posts for cfc block view using post ids.
		 *
		 * @covers ::filter_posts_by_ids
		 */
		public function test_filter_posts_by_ids() {
			$dummy_posts      = $this->get_dummy_posts();
			$action_pages_ids = $this->factory->post->create_many( 5, $dummy_posts['story'] );

			// Passing post ids.
			$fields = [
				'posts' => implode( ',', $action_pages_ids ),
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_by_ids', [ &$fields ] );
			$this->assertEquals( 5, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );

			// Not passing any post ids, should return empty array.
			$fields = [
				'posts' => '',
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_by_ids', [ &$fields ] );
			$this->assertEquals( 0, count( $posts ) );
		}

		/**
		 * Test filtering posts for cfc block view using tags and/or post_types.
		 *
		 * @covers ::filter_posts_for_cfc
		 */
		public function test_filter_posts_for_cfc() {
			// Get editor user.
			$user = get_user_by( 'login', 'p4_editor' );
			wp_set_current_user( $user->ID );

			$dummy_posts      = $this->get_dummy_posts();
			// Populate 5 posts with tag arcticsunrise, 15 posts with story term and 5 posts with both tag and post type.
			$this->factory->post->create_many( 5, $dummy_posts['story_with_tag'] );
			$this->factory->post->create_many( 15, $dummy_posts['story_with_term'] );
			$this->factory->post->create_many( 5, $dummy_posts['story_with_term_and_tag'] );
			$arctic_term_id = $this->get_tag_id( 'arcticsunrise' );
			$story_term_id  = $this->get_custom_term_id( 'story' );

			// Passing tag ids.
			$fields = [
				'tags' => $arctic_term_id,
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_cfc', [ &$fields ] );
			$this->assertEquals( 10, count( $posts ) );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );

			// Passing post type ids.
			$fields = [
				'post_types' => "$story_term_id",
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_cfc', [ &$fields ] );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );
			$this->assertEquals( 20, count( $posts ) );

			// Passing both tag ids and post type ids.
			$fields = [
				'tags' => $arctic_term_id,
				'post_types' => "$story_term_id",
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_cfc', [ &$fields ] );
			$this->assertContainsOnlyInstancesOf( \WP_Post::class, $posts );
			$this->assertEquals( 5, count( $posts ) );

			// Not passing any post ids, should return empty array.
			$fields = [
				'tags' => '',
				'post_types' => '',
			];
			$posts  = $this->invokeMethod( $this->block, 'filter_posts_for_cfc', [ &$fields ] );
			$this->assertEquals( 0, count( $posts ) );
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
				'story_with_tag'         => [
					'post_author' => 1,
					'post_title'  => 'this is a story',
					'post_status' => 'publish',
					'tags_input'  => [
						'arcticsunrise',
					],
				],
				'story_with_term'         => [
					'post_author' => 1,
					'post_title'  => 'this is a story',
					'post_status' => 'publish',
					'tax_input'   => [
						'p4-page-type' => [ 'story' ],
					],
				],
				'story_with_term_and_tag' => [
					'post_author' => 1,
					'post_title'  => 'this is a story',
					'post_status' => 'publish',
					'tags_input'  => [
						'arcticsunrise',
					],
					'tax_input'   => [
						'p4-page-type' => [ 'story' ],
					],
				],
			];
		}
	}
}
