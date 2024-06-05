<?php
/**
 * Splash Fields Plugin
 *
 * @package splash-fields
 * @author  Greg Slonina
 *
 * @wordpress-plugin
 * Plugin Name:       Splash Fields
 * Plugin URI:        https://splashfields.com/
 * Description:       Create custom fields and add them to a metabox, options page or sidepanel.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Greg Slonina
 * Author URI:        https://github.com/theMugician/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       splash-fields
 * Domain Path:       /languages
 */

/**
 * Bootstrap the plugin.
 */
require_once 'vendor/autoload.php';
// require_once untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/inc/custom-functions.php';

use Splash_Fields\Plugin;

if ( class_exists( 'Splash_Fields\Plugin' ) ) {
	$the_plugin = new Plugin();
}

// register_activation_hook( __FILE__, [ $the_plugin, 'activate' ] );
// register_deactivation_hook( __FILE__, [ $the_plugin, 'deactivate' ] );
