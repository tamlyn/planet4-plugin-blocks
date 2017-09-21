<?php

namespace P4BKS\Controllers\Blocks;

use P4BKS\Views\P4BKS_View;

if ( ! class_exists( 'P4BKS_Blocks_Controller' ) ) {

	/**
	 * Class P4BKS_Blocks_Controller
	 */
	abstract class P4BKS_Blocks_Controller {

		/** @var P4BKS_View $view */
		protected $view;


		/**
		 * Creates the plugin's controller object.
		 * Avoid putting hooks inside the constructor, to make testing easier.
		 *
		 * @param P4BKS_View $view The view object.
		 */
		public function __construct( P4BKS_View $view ) {
			$this->view = $view;
		}

		/**
		 *
		 */
		protected function load() {
			// Check to see if Shortcake is running, with an admin notice if not.
			add_action( 'init', array( $this, 'shortcode_ui_detection' ) );
			// Register the shortcodes.
			add_action( 'init', array( $this, 'shortcode_ui_register_shortcodes' ) );
			// Add Two Column element in UI
			add_action( 'register_shortcode_ui', array( $this, 'shortcode_ui_block_fields' ) );
		}

		/**
		 * If Shortcake isn't active, then add an administration notice.
		 *
		 * This check is optional. The addition of the shortcode UI is via an action hook that is only called in Shortcake.
		 * So if Shortcake isn't active, you won't be presented with errors.
		 *
		 * Here, we choose to tell users that Shortcake isn't active, but equally you could let it be silent.
		 *
		 * Why not just self-deactivate this plugin? Because then the shortcodes would not be registered either.
		 *
		 * @since 1.0.0
		 */
		public function shortcode_ui_detection() {
			if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
				add_action( 'admin_notices', array( $this, 'shortcode_ui_notices' ) );
			}
		}

		/**
		 * Display an administration notice if the user can activate plugins.
		 *
		 * If the user can't activate plugins, then it's poor UX to show a notice they can't do anything to fix.
		 *
		 * @since 1.0.0
		 */
		public function shortcode_ui_notices() {
			if ( current_user_can( 'activate_plugins' ) ) {
				?>
				<div class="error message">
					<p><?php esc_html_e( 'Shortcode UI plugin must be active for Planet4 - Blocks plugin to function.', 'planet4-blocks' ); ?></p>
				</div>
				<?php
			}
		}

		/**
		 * Register shortcodes
		 *
		 * This registration is done independently of any UI that might be associated with them, so it always happens, even if
		 * Shortcake is not active.
		 *
		 * @since 1.0.0
		 */
		abstract public function shortcode_ui_register_shortcodes();

		/**
		 * Shortcode UI setup for the twocolumn shortcode.
		 *
		 * It is called when the Shortcake action hook `register_shortcode_ui` is called.
		 *
		 * This example shortcode has many editable attributes, and more complex UI.
		 *
		 * @since 1.0.0
		 */
		abstract public function shortcode_ui_block_fields();
	}
}
