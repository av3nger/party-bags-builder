<?php
/**
 * Party Bag Builder
 *
 * Custom party bag builder for WooCommerce with multistep wizard.
 *
 * @package PartyBagBuilder
 *
 * @wordpress-plugin
 * Plugin Name:          Party Bag Builder
 * Plugin URI:           https://github.com/vanyukov/party-bag-builder
 * Description:          Custom party bag builder for WooCommerce with multistep wizard
 * Version:              1.1.1
 * Author:               vCore Digital
 * Author URI:           https://vcore.digital
 * Requires at least:    6.8
 * Requires PHP:         8.3
 * Requires Plugins:     woocommerce
 * WC requires at least: 10.0
 * WC tested up to:      10.2
 * Text Domain:          party-bag-builder
 * Domain Path:          /languages
 * License:              MIT
 * License URI:          https://opensource.org/licenses/MIT
 */

use PBB\Plugin;

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
const PBB_PLUGIN_FILE = __FILE__;
define( 'PBB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PBB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PBB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloader for plugin classes.
 *
 * @param string $class The fully-qualified class name.
 */
spl_autoload_register(
	function ( $class_name ) {
		$prefix   = 'PBB\\';
		$base_dir = PBB_PLUGIN_DIR . 'includes/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class_name, $len );
		$file           = $base_dir . 'class-' . str_replace( '\\', '/', strtolower( str_replace( '_', '-', $relative_class ) ) ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

// Include the main plugin class.
require_once PBB_PLUGIN_DIR . 'includes/class-plugin.php';

/**
 * Returns the main instance of the plugin.
 *
 * @return PBB\Plugin
 */
function party_bag_builder(): Plugin {
	return PBB\Plugin::instance();
}

add_action( 'plugins_loaded', 'party_bag_builder' );

register_activation_hook( __FILE__, array( 'PBB\\Setup', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PBB\\Setup', 'deactivate' ) );
