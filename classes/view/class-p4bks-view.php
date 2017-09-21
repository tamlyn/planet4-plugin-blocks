<?php

namespace P4BKS\Views;

use Timber\Timber;

if ( ! class_exists( 'P4BKS_View' ) ) {

	/**
	 * Class P4BKS_View
	 */
	class P4BKS_View {

		/** @var string $template_dir The path to the template files. */
		private $template_dir = P4BKS_INCLUDES_DIR;


		/**
		 * Creates the plugin's View object.
		 */
		public function __construct() {}

		/**
		 * Compile and return a template file.
		 *
		 * @param array|string $template_name The file name of the template to render.
		 * @param array        $data The data to pass to the template.
		 * @param string       $sub_dir The path to a subdirectory where the template is located (relative to $template_dir).
		 *
		 * @return bool|string The returned output
		 */
		private function get_template( $template_name, $data, $sub_dir = '' ) {
			Timber::$locations = $this->template_dir;
			return Timber::compile( [ $sub_dir . $template_name . '.twig' ], $data );
		}

		/**
		 * Uses the appropriate templating engine to render a template file.
		 *
		 * @param array|string $template_name The file name of the template to render.
		 * @param array        $data The data to pass to the template.
		 * @param string       $sub_dir The path to a subdirectory where the template is located (relative to $template_dir).
		 */
		private function view_template( $template_name, $data, $sub_dir = '' ) {
			Timber::$locations = $this->template_dir;
			Timber::render( [ $sub_dir . $template_name . '.twig' ], $data );
		}

		/**
		 * Render the settings page of the plugin.
		 *
		 * @param array $data All the data needed to render the template.
		 */
		public function two_columns( $data ) {
			$this->view_template( __FUNCTION__, $data, 'blocks/' );
		}

		/**
		 * Render the settings page of the plugin.
		 *
		 * @param array $data All the data needed to render the template.
		 */
		public function settings( $data ) {
			$this->view_template( __FUNCTION__, $data );
		}
	}
}
