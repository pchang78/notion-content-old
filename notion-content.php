<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://patrickchang.com
 * @since             1.0.0
 * @package           Notion_Content
 *
 * @wordpress-plugin
 * Plugin Name:       Notion Content
 * Plugin URI:        https://www.patrickchang.com/wp/notion
 * Description:       Plugin for displaying simple content from Notion using the Notion API.
 * Version:           1.0.0
 * Author:            Patrick Chang
 * Author URI:        https://patrickchang.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       notion-content
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NOTION_CONTENT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-notion-content-activator.php
 */
function activate_notion_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-notion-content-activator.php';
	Notion_Content_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-notion-content-deactivator.php
 */
function deactivate_notion_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-notion-content-deactivator.php';
	Notion_Content_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_notion_content' );
register_deactivation_hook( __FILE__, 'deactivate_notion_content' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-notion-content.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_notion_content() {

	$plugin = new Notion_Content();
	$plugin->run();

}
run_notion_content();
