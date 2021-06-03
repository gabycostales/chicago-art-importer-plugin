<?php
/**
 * @author 			gabycostales
 * @link 				http://gabycostales.com
 * @since 			1.0.0
 * @package 		Chicago Art Importer
 * 
 * @wordpress-plugin
 * Plugin Name: Art Institute of Chicago Importer Plugin
 * Plugin URI: http://gabycostales.com
 * Description: Client Plugin to import public data from the Art Institute of Chicago
 * Version: 1.0.0
 * Author: Gaby Costales - gabycostales@gmail.com
 * Author URI: http://gabycostales.com
 * License: GPL2
 */

// If this file is called directly, abort.
if (!defined('WPINC')) die;

// Used for referring to the plugin file or basename
if (!defined( 'CHICAGO_ART_IMPORTER_FILE')) {
  define('CHICAGO_ART_IMPORTER_FILE', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chicago-art-importer-activator.php
 */
function activate_Chicago_Art_Importer() {
  require_once plugin_dir_path(__FILE__) . 'includes/class-chicago-art-importer-activator.php';
  Chicago_Art_Importer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chicago-art-importer-deactivator.php
 */
function deactivate_Chicago_Art_Importer() {
  require_once plugin_dir_path(__FILE__).'includes/class-chicago-art-importer-deactivator.php';
  Chicago_Art_Importer_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_Chicago_Art_Importer');
register_deactivation_hook(__FILE__, 'deactivate_Chicago_Art_Importer');

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-chicago-art-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 		1.0.0
 */
function run_Chicago_Art_Importer() {
  $plugin = new Chicago_Art_Importer();
  $plugin->run();
}
run_Chicago_Art_Importer();