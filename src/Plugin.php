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
		define( 'SPF_PATH', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
		define( 'SPF_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
		define( 'SPF_ASSETS_PATH', SPF_PATH . '/assets' );
		define( 'SPF_ASSETS_URL', SPF_URL . '/assets' );
		define( 'SPF_VERSION', '1.0.0' );

		new Assets();

		$core = new Core();
		$core->init();
	}
}

