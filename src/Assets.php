<?php
/**
 * Assets Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Assets.
 */
class Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize.
	 */
	private function init() {
		/**
		 * The 'enqueue_block_assets' hook includes styles and scripts both in editor and frontend,
		 * except when is_admin() is used to include them conditionally
		 */
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Enqueue Admin Scripts
	 */
	public function enqueue_styles() {
		/*
		$asset_config_file = sprintf( '%s/assets.php', SF_BUILD_PATH );

		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}

		$asset_config = include_once $asset_config_file;

		if ( empty( $asset_config['js/editor.js'] ) ) {
			return;
		}

		$editor_asset    = $asset_config['js/editor.js'];
		$js_dependencies = ( ! empty( $editor_asset['dependencies'] ) ) ? $editor_asset['dependencies'] : [];
		$version         = ( ! empty( $editor_asset['version'] ) ) ? $editor_asset['version'] : filemtime( $asset_config_file );
		*/

		// Admin CSS.
		if ( is_admin() ) {
			// wp_enqueue_script(
			// 	'af-blocks-js',
			// 	SF_ASSETS_URL . '/css/admin.css',
			// 	$js_dependencies,
			// 	$version,
			// 	true
			// );
			wp_enqueue_style(
				'smb-admin-css',
				SF_ASSETS_URL . '/css/admin.css',
				array(),
				filemtime( SF_ASSETS_PATH . '/css/admin.css' ),
				'all'
			);
		}

		// Theme Gutenberg blocks CSS.
		/*
		$css_dependencies = [
			'wp-block-library-theme',
			'wp-block-library',
		];

		wp_enqueue_style(
			'af-blocks-css',
			SF_BUILD_URL . '/css/editor.css',
			$css_dependencies,
			filemtime( SF_BUILD_PATH . '/css/editor.css' ),
			'all'
		);
		*/
	}
}

