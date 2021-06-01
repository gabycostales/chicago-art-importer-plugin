<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 		  1.0.0
 * @package 	  Chicago_Art_Importer
 * @subpackage 	Chicago_Art_Importer/includes
 * @author 		  Gaby Costales <gabycostales@gmail.com>
 */
class Chicago_Art_Importer_Activator {
  public static function activate() {
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-chicago-art-importer-admin.php';

		Chicago_Art_Importer_Admin::new_cpt_artwork();
		Chicago_Art_Importer_Admin::new_taxonomy_artwork_type();
    Chicago_Art_Importer_Admin::add_artworks_custom_fields();

		flush_rewrite_rules();

		Chicago_Art_Importer_Admin::add_admin_notices();
    Chicago_Art_Importer_Admin::admin_notices_init();
  }
}