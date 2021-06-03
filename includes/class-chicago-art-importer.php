<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 * 
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 * 
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 * @since 		  1.0.0
 * @package 	  Chicago_Art_Importer
 * @subpackage 	Chicago_Art_Importer/includes
 * @author 		  Gaby Costales <gabycostales@gmail.com>
 */

class Chicago_Art_Importer {
  protected $loader;
  protected $version;
  protected $plugin_name;

  public function __construct() {
    $this->plugin_name = 'chicago-art-importer';
    $this->version = '1.0.0';

    $this->load_dependencies();
    $this->define_admin_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Chicago_Art_Importer_Loader. Orchestrates the hooks of the plugin.
   * - Chicago_Art_Importer_Admin. Defines all hooks for the dashboard.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   */
  private function load_dependencies() {
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-chicago-art-importer-loader.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-chicago-art-importer-admin.php';

    $this->loader = new Chicago_Art_Importer_Loader();
  }

  /**
   * Register all of the hooks related to the dashboard functionality
   * of the plugin.
   */
  private function define_admin_hooks() {
    $plugin_admin = new Chicago_Art_Importer_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('init', $plugin_admin, 'new_cpt_artwork');
    $this->loader->add_action('init', $plugin_admin, 'new_taxonomy_artwork_type');
    $this->loader->add_action('init', $plugin_admin, 'add_artworks_custom_fields');
    $this->loader->add_action('init', $plugin_admin, 'setup_artwork_types');
    $this->loader->add_action('admin_menu', $plugin_admin, 'add_menu_page');
    $this->loader->add_action('admin_notices', $plugin_admin, 'display_admin_notices');
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @return Chicago_Art_Importer_Loader
   */
  public function get_loader() {
    return $this->loader;
  }
}