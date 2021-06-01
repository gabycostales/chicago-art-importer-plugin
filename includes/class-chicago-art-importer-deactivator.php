<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since 		  1.0.0
 * @package 	  Chicago_Art_Importer
 * @subpackage 	Chicago_Art_Importer/includes
 * @author 		  Gaby Costales <gabycostales@gmail.com>
 */
class Chicago_Art_Importer_Deactivator {
	public static function deactivate() {
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-chicago-art-importer-admin.php';

		Chicago_Art_Importer_Admin::remove_admin_notices();
    Chicago_Art_Importer_Admin::delete_all_artwork_types();
	}
}