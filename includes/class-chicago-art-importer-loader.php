<?php

/**
 * Register all actions and filters for the plugin.
 * 
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @since 		  1.0.0
 * @package 	  Chicago_Art_Importer
 * @subpackage 	Chicago_Art_Importer/includes
 * @author 		  Gaby Costales <gabycostales@gmail.com>
 */


class Chicago_Art_Importer_Loader {
  protected $actions;

  /**
	 * Initialize the collections used to maintain the actions.
	 */
	public function __construct() {
		$this->actions = array();
	}

  /**
	 * Add a new action to the collection to be registered with WordPress.
	 */
	public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
		$this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
	}

  /**
	 * A utility function that is used to register the actions and hooks into a single collection.
   */
  private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
		$hooks[$this->hook_index($hook, $component, $callback)] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;
	}

  /**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		if (is_null(self::$instance)) {
			self::$instance = new Chicago_Art_Importer_Loader();
		}

		return self::$instance;
	}

  /**
	 * Utility function for indexing $this->hooks
	 */
  protected function hook_index($hook, $component, $callback) {
		return md5($hook . get_class($component) . $callback);
	}

  /**
	 * Register the actions with WordPress.
	 */
  public function run() {
		foreach ($this->actions as $hook) {
			add_action($hook['hook'], array($hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args']);
		}
	}
}