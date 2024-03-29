<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://aguspri.com
 * @since             1.0.0
 * @package           Smpi
 *
 * @wordpress-plugin
 * Plugin Name:       SocialMedia Photo Importer
 * Plugin URI:        https://aguspri.com/smpi
 * Description:       Plugin to fetch one or more images that stored on your social media like Instagram, Facebook,etc. Note: Currently this plugin only available for Instagram account.
 * Version:           1.0.0
 * Author:            Agus Priyanto
 * Author URI:        https://aguspri.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smpi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants
 */
define( 'SMPI_VERSION', '1.0.0' );
define( 'SMPI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMPI_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'SMPI_LIB_DIR', SMPI_PLUGIN_DIR . 'lib/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smpi-activator.php
 */
function activate_smpi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smpi-activator.php';
	Smpi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smpi-deactivator.php
 */
function deactivate_smpi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smpi-deactivator.php';
	Smpi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smpi' );
register_deactivation_hook( __FILE__, 'deactivate_smpi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smpi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smpi() {

	$plugin = new Smpi();
	$plugin->run();

}
run_smpi();
