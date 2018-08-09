<?php

require_once __DIR__ . '/../p4-unittestcase.php';

use P4BKS\Controllers\Blocks\ContentFourColumn_Controller as ContentFourColumn;
use P4BKS\Views\View as View;

if ( ! class_exists( 'P4_ContentFourColumn' ) ) {

	/**
	 * Class P4_ContentFourColumn
	 *
	 * @package Planet4_Plugin_Blocks
	 */
	class P4_ContentFourColumn extends P4_UnitTestCase {

		const POSTS_COUNT = 3;

		/**
		 * Test that the block retrieves all the available Posts with a specific tag.
		 */
		public function test_posts_count() {
			$dummy_posts = $this->get_dummy_posts();
			$posts_ids   = $this->factory->post->create_many( self::POSTS_COUNT, $dummy_posts );

			$cfc    = new ContentFourColumn( new View() );
			$fields = [
				'select_tag' => 0,
			];
			$data = $cfc->prepare_data( $fields );

			try {
				$this->assertEquals( self::POSTS_COUNT, count( $data['posts'] ) );
			} catch ( \Exception $e ) {
				$this->fail( '->Did not find as many Posts as expected.' );
			}
		}

		/**
		 * Get data which will be used to create dummy attachments for the test.
		 *
		 * @return array
		 */
		private function get_dummy_posts() : array {
			return [
				'post_author'    => 1,
				'post_title'     => 'this is a post',
				'post_type'      => 'post',
				'post_status'    => 'publish',
			];
		}
	}
}