<?php

/**
 * Plugin Name:       Hosting Checks
 * Plugin URI:        https://github.com/mrxkon/hosting-checks
 * Description:       PHP, MySQL & $wpdb tests for Hosting.
 * Version:           1.0.0
 * Required at least: 5.0
 * Requires PHP:      7.0
 * Author:            Konstantinos Xenos
 * Author URI:        https://xkon.dev
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hosting-checks
 *
 * Copyright (C) 2021-Present
 * Konstantinos Xenos (https://xkon.dev).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/.
 */

/**
 * Set namespace.
 */
namespace Xkon;

/**
 * Import necessary classes.
 */
use Xkon\Hosting_Checks\Admin\Menu;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Hosting_Checks.
 */
class Hosting_Checks {
	/**
	 * The plugin version.
	 *
	 * @var string $version
	 */
	public static $version;

	/**
	 * The plugin directory.
	 *
	 * @var string $dir
	 */
	public static $dir;

	/**
	 * The plugin url.
	 *
	 * @var string $url
	 */
	public static $url;

	/**
	 * The plugin instanace.
	 *
	 * @var null|Hosting_Checks $instance
	 */
	private static $instance = null;

	/**
	 * Gets the plugin instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// Composer autoload.
		require_once __DIR__ . '/vendor/autoload.php';

		// Set the plugin version.
		self::$version = '1.0.0';

		// Set the plugin directory.
		self::$dir = wp_normalize_path( plugin_dir_path( __FILE__ ) );

		// Set the plugin url.
		self::$url = plugin_dir_url( __FILE__ );

		// Create the admin menus.
		Menu::create();
	}

	/**
	 * Runs on plugin activation.
	 * Runs on plugin deactivation.
	 * Runs on plugin uninstall.
	 */
	public static function cleanup() {
		global $wpdb;

		$table       = $wpdb->prefix . 'options';
		$option_name = 'hostingchecks_';

		for ( $i = 0; $i < 300; $i++ ) {
			$query_result = $wpdb->delete(
				$table,
				array(
					'option_name' => $option_name . $i,
				)
			);
		}
	}
}

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'cleanup' ) );

/**
 * Dectivation Hook.
 */
register_deactivation_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'cleanup' ) );

/**
 * Uninstall Hook.
 */
register_uninstall_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'cleanup' ) );

/**
 * Load plugin.
 */
add_action( 'plugins_loaded', array( '\\Xkon\\Hosting_Checks', 'get_instance' ) );
