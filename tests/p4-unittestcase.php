<?php

use PHPUnit\Framework\TestCase as TestCase;

/**
 * Class P4_UnitTestCase
 */
abstract class P4_UnitTestCase extends WP_UnitTestCase {
	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}
}
