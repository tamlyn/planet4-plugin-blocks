<?php

require_once __DIR__ . '/../p4-unittestcase.php';

use P4BKS\Controllers\Blocks\Carousel_Controller as Carousel;
use P4BKS\Views\View as View;

if ( ! class_exists( 'P4_Carousel' ) ) {

	/**
	 * Class P4_Carousel
	 *
	 * @package Planet4_Plugin_Blocks
	 */
	class P4_Carousel extends P4_UnitTestCase {

		const ATTACHMENTS_COUNT = 3;

		/**
		 * Test that the block retrieves all the available Attachments with post_type image/jpg.
		 */
		public function test_attachments_count() {
			$dummy_attachments = $this->get_dummy_attachments();
			$attachment_ids    = $this->factory->attachment->create_many( self::ATTACHMENTS_COUNT, $dummy_attachments );

			$carousel = new Carousel( new View() );
			$fields = [
				'multiple_image' => implode( ',', $attachment_ids ),
			];
			$data = $carousel->prepare_data( $fields );

			try {
				$this->assertEquals( self::ATTACHMENTS_COUNT, count( $data['images'] ) );
			} catch ( \Exception $e ) {
				$this->fail( '->Did not find as many Attachments as expected.' );
			}
		}

		/**
		 * Get data which will be used to create dummy attachments for the test.
		 *
		 * @return array
		 */
		private function get_dummy_attachments() : array {
			return [
				'post_author'    => 1,
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image/jpg',
			];
		}
	}
}
