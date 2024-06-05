<?php
/**
 * Plugin Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Plugin.
 */
class Plugin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	public function activate() {}
	public function deactivate() {}

	/**
	 * Initialize plugin
	 */
	private function init() {
		define( 'SF_PATH', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
		define( 'SF_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
		define( 'SF_ASSETS_PATH', SF_PATH . '/assets' );
		define( 'SF_ASSETS_URL', SF_URL . '/assets' );
		define( 'SF_VERSION', '1.0.0' );

		new Assets();

		$core = new Core();
		$core->init();
	}
}

