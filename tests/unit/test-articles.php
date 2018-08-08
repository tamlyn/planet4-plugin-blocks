<?php

require_once __DIR__ . '/../p4-unittestcase.php';

use P4BKS\Controllers\Blocks\Articles_Controller as Articles;

if ( ! class_exists( 'P4_ArticlesTest' ) ) {

	/**
	 * Class P4_ArticlesTest
	 *
	 * @package Planet4_Plugin_Blocks
	 */
	class P4_ArticlesTest extends P4_UnitTestCase {

		/**
		 *
		 */
		public function setUp() {
			parent::setUp();
			$mock_posts = $this->get_mock_posts();
			$this->factory->post->create_many( 2, $mock_posts['press-release'] );
			$this->factory->post->create_many( 1, $mock_posts['publication'] );
			$this->factory->post->create_many( 3, $mock_posts['story'] );

			$this->commit_transaction();
		}

		/**
		 *
		 */
		public function test_articles_number() {
			$test_number = 3;

//			$args = [
//				'numberposts'      => $test_number,
//				'post_status'      => 'publish',
//				'post_type'        => 'post',
//				'suppress_filters' => false,
//				'tax_query'        => [
//					'taxonomy' => 'p4-page-type',
//					'field'    => 'slug',
//					'terms'    => 'story',
//				],
//			];
//
//			$all_posts = wp_get_recent_posts( $args );
//			fwrite( STDERR, print_r( $all_posts, TRUE ) );

			$view     = new \P4BKS\Views\View();
			$articles = new Articles( $view );

			$fields = [
				'article_count'      => $test_number,
				'p4_page_type_story' => true,
			];
			$articles->prepare_template( $fields, '', 'shortcake_' . Articles::BLOCK_NAME );
			//fwrite( STDERR, print_r( $articles->data, TRUE ) );

			try {
				$this->assertEquals( $test_number, count( $articles->data['recent_posts'] ) );
			} catch ( \Exception $e ) {
				$this->fail( '->Did not find as many posts as expected.' );
			}
		}

		/**
		 * @return array
		 */
		private function get_mock_posts() : array {
			return [
				'press-release' => [
					'post_author' => 1,
					'post_title' => 'this is a press release',
					'post_status' => 'publish',
				],
				'publication' => [
					'post_author' => 1,
					'post_title' => 'this is a publication',
					'post_status' => 'publish',
				],
				'story' => [
					'post_author' => 1,
					'post_title' => 'this is a story',
					'post_status' => 'publish',
				],
			];
		}
	}
}
