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
	 * The single instance of the class.
	 *
	 * @var Plugin
	 */
	protected static $instance = null;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Ensure only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin - The single instance of the class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
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

		$core = Core::get_instance();
		$core->init();

		// Public functions.
		require_once SPF_PATH . '/functions.php';
	}
}

