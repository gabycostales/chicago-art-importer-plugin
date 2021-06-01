<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @since 		  1.0.0
 * @package 	  Chicago_Art_Importer
 * @subpackage 	Chicago_Art_Importer/admin
 * @author 		  Gaby Costales <gabycostales@gmail.com>
 */

 class Chicago_Art_Importer_Admin {
  private $options;
  private $plugin_name;
  private $version;
  private $importer;

  public function __construct($plugin_name, $version) {
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-chicago-art-api-handler.php';

    $this->importer = new ChicagoArtApiHandler();
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_options();
	}

  /**
	 * Sets the class variable $options
	 */
	private function set_options() {
		$this->options = get_option($this->plugin_name.'-options');
	}


  /**
	 * Creates a new custom post type
	 */
  public static function new_cpt_artwork() {
    $cap_type = 'post';
    $single = 'Artwork';
    $plural = 'Artworks';
    $cpt_name = 'artwork';

    $labels = array(
      'add_new' => esc_html__("Add New {$single}", 'chicago-art-imporer'),
      'add_new_item' => esc_html__("Add New {$single}", 'chicago-art-imporer'),
      'all_items' => esc_html__($plural, 'chicago-art-imporer'),
      'edit_item' => esc_html__("Edit {$single}", 'chicago-art-imporer'),
      'menu_name' => esc_html__($plural, 'chicago-art-imporer'),
      'name' => esc_html__($plural, 'chicago-art-imporer'),
      'name_admin_bar' => esc_html__($single, 'chicago-art-imporer'),
      'new_item' => esc_html__("New {$single}", 'chicago-art-imporer'),
      'not_found' => esc_html__("No {$plural} Found", 'chicago-art-imporer'),
      'not_found_in_trash' => esc_html__("No {$plural} Found in Trash", 'chicago-art-imporer'),
      'parent_item_colon' => esc_html__("Parent {$plural} :", 'chicago-art-imporer'),
      'search_items' => esc_html__("Search {$plural}", 'chicago-art-imporer'),
      'singular_name' => esc_html__($single, 'chicago-art-imporer'),
      'view_item' => esc_html__("View {$single}", 'chicago-art-imporer'),
    );

    $opts = array(
      'can_export' => TRUE,
      'capability_type' => $cap_type,
      'description' => '',
      'exclude_from_search' => TRUE,
      'has_archive' => FALSE,
      'menu_icon' => 'dashicons-art',
      'menu_position' => 25,
      'public' => TRUE,
      'publicly_querable' => TRUE,
      'query_var' => TRUE,
      'rewrite' => FALSE,
      'show_in_admin_bar' => TRUE,
      'show_in_menu' => TRUE,
      'show_in_nav_menu' => TRUE,
      'show_ui' => TRUE,
      'supports' => array('title'),
      'taxonomies' => array(),
      'labels' => $labels,
    );

    $opts = apply_filters('chicago-art-importer-cpt-options', $opts);

		register_post_type(strtolower($cpt_name), $opts);
  }

  /**
	 * Adds ACF plugin fields for artwork custom post type
	 */
  public static function add_artworks_custom_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group(array(
      'key' => 'artwork_group',
      'title' => 'Artwork Fields',
      'menu_order' => 0,
      'label_placement' => 'top',
      'active' => 1,
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'artwork',
          ),
        ),
      ),
      'fields' => array(
        array(
          'key' => 'artwork_id',
          'label' => 'Artwork ID',
          'name' => 'artwork_id',
          'type' => 'text',
          'readonly' => TRUE,
          'disabled' => TRUE,
        ),
        array(
          'key' => 'artwork_is_public_domain',
          'label' => 'Is Public Domain',
          'name' => 'is_public_domain',
          'type' => 'true_false',
          'ui' => 1,
        ),
        array(
          'key' => 'artwork_artist_display',
          'label' => 'Artist Name',
          'name' => 'artist_display',
          'type' => 'text',
        ),
        array(
          'key' => 'artwork_thumbnail_url',
          'label' => 'Thumbnail URL',
          'name' => 'artwork_thumbnail_url',
          'type' => 'link',
          'return_format' => 'url',
        ),
        array(
          'key' => 'artwork_date_display',
          'label' => 'Display Date',
          'name' => 'date_display',
          'type' => 'text',
        ),
        array(
          'key' => 'artwork_place_of_origin',
          'label' => 'Place of Origin',
          'name' => 'place_of_origin',
          'type' => 'text',
        ),
        array(
          'key' => 'artwork_medium_display',
          'label' => 'Medium Display',
          'name' => 'medium_display',
          'type' => 'text',
        ),
        array(
          'key' => 'artwork_inscriptions',
          'label' => 'Inscriptions',
          'name' => 'inscriptions',
          'type' => 'text',
        ),
      ),
    ));
  }

  /**
	 * Creates a new taxonomy for a custom post type
	 */
	public static function new_taxonomy_artwork_type() {
    $plural = 'Types';
		$single = 'Type';
    $taxonomy_name = 'artwork_type';

    $labels = array(
      'add_new_item' => esc_html__("Add New {$single}", 'chicago-art-imporer'),
      'add_or_remove_items' => esc_html__("Add or remove {$plural}", 'chicago-art-imporer'),
      'all_items' => esc_html__($plural, 'chicago-art-imporer'),
      'choose_from_most_used' => esc_html__("Choose from most used {$plural}", 'chicago-art-importer'),
      'edit_item' => esc_html__("Edit {$single}", 'chicago-art-imporer'),
      'menu_name' => esc_html__($plural, 'chicago-art-imporer'),
      'name' => esc_html__($plural, 'chicago-art-imporer'),
      'new_item_name' => esc_html__("New {$single} Name", 'chicago-art-importer'),
      'not_found' => esc_html__("No {$plural} Found", 'chicago-art-imporer'),
      'parent_item' => esc_html__("Parent {$single}", 'chicago-art-importer'),
      'parent_item_colon' => esc_html__("Parent {$single} :", 'chicago-art-imporer'),
      'popular_items' => esc_html__("Popular {$plural}", 'chicago-art-importer'),
      'search_items' => esc_html__("Search {$plural}", 'chicago-art-importer'),
      'separate_items_with_commas' => esc_html__("Separate {$plural} with commas", 'chicago-art-importer'),
      'singular_name' => esc_html__($single, 'chicago-art-importer'),
		  'update_item' => esc_html__("Update {$single}", 'chicago-art-importer'),
		  'view_item' => esc_html__("View {$single}", 'chicago-art-importer'),
    );

    $opts = array(
      'hierarchical' => TRUE,
      'public' => TRUE,
      'query_var' => $taxonomy_name,
      'show_admin_column' => FALSE,
      'show_in_nav_menus' => TRUE,
      'show_tag_cloud' => TRUE,
      'show_ui' => TRUE,
      'sort' => '',
      'labels' =>  $labels,
    );

    $opts = apply_filters('chicago-art-importer-taxonomy-options', $opts);

		register_taxonomy($taxonomy_name, 'artwork', $opts);
  }

  /**
   * Adds notices for the admin to display.
   * Saves them in a temporary plugin option.
   * This method is called on plugin activation, so its needs to be static.
   */
  public static function add_admin_notices() {
    $notices = get_option('chicago_art_importer_admin_notices', array());
    update_option('chicago_art_importer_admin_notices', $notices);
  }

  /**
	 * Displays admin notices
	 */
  public function display_admin_notices() {
		$notices = get_option('chicago_art_importer_admin_notices', array());

		if (empty($notices)) return;

		foreach ($notices as $notice) {
			echo '<div class="' . esc_attr($notice['class']) . '"><p>' . $notice['notice'] . '</p></div>';
		}
  }

   /**
	 * Clear admin notices
	 */
  public function remove_admin_notices(){
    delete_option('chicago_art_importer_admin_notices');
  }

  /**
   * Verify that ACF is installed before using plugin
   */
  public function admin_notices_init() {
    $class = 'notice notice-error';
    $message = 'Missing required dependency in order to use the <b>Chicago Art Importer</b> plugin. Please install and activate the <strong><a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">ACF</a></strong> plugin, then re-activate the Chicago Art Importer.';

    if (!function_exists('acf_add_local_field_group')) {
      $notices = get_option('chicago_art_importer_admin_notices', array());
      $notices[] = array(
        'notice' => $message,
        'class' => $class
      );
      update_option('chicago_art_importer_admin_notices', $notices);
    }
  }

  /**
	 * Adds a settings page link to a menu
	 */
  public function add_menu_page() {
    add_submenu_page(
			'edit.php?post_type=artwork',
			apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Import Artwork', 'chicago-art-importer')),
      apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Import Artwork', 'chicago-art-importer')),
			'manage_options',
			$this->plugin_name . '-settings',
			array($this, 'page_options')
		);
  }

  /**
	 * Creates the options page
	 */
	public function page_options() {
		include(plugin_dir_path(__FILE__) . 'chicago-art-importer-page.php');
	}

  /**
	 * Setup artwork types
	 */
  public function setup_artwork_types() {
    $taxonomy_name = 'artwork_type';
    
    $terms = get_terms(array(
      'taxonomy' => $taxonomy_name,
      'hide_empty' => false
    ));

    if (!empty($terms)) return;

    $this->delete_all_artwork_types();

    $types = $this->importer->get_artwork_types();

    foreach ($types as $type) {
      wp_insert_term($type['title'], $taxonomy_name, array(
        'slug' => (string)$type['id'],
      ));
    }
  }

  /**
   * Delete all artwork types
   */
  public function delete_all_artwork_types() {
    $taxonomy_name = 'artwork_type';
    $terms = get_terms(array(
      'taxonomy' => $taxonomy_name,
      'hide_empty' => false
    ));
    foreach ($terms as $term) {
      wp_delete_term($term->term_id, $taxonomy_name); 
    }  
  }
 }
