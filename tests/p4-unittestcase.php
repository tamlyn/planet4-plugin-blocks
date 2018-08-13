<?php

/**
 * Class P4_UnitTestCase
 */
abstract class P4_UnitTestCase extends WP_UnitTestCase {

	/**
	 * Sets up a P4 Unit Test.
	 */
	public function setUp() {

		// Create custom taxonomy p4-page-type.
		register_taxonomy( 'p4-page-type', 'P4_CustomTaxonomyTest' );

		// Create p4-page-type terms.
		$this->factory->term->create( [
			'name'     => 'Story',
			'taxonomy' => 'p4-page-type',
			'slug'     => 'story',
		] );
		$this->factory->term->create( [
			'name'     => 'Publication',
			'taxonomy' => 'p4-page-type',
			'slug'     => 'publication',
		] );
		$term_id = $this->factory->term->create( [
			'name'     => 'Press Release',
			'taxonomy' => 'p4-page-type',
			'slug'     => 'press-release',
		] );
	}
}
