<?php

/**
 * Plugin Name:       Hosting Checks
 * Plugin URI:        https://github.com/mrxkon/hosting-checks
 * Description:       PHP & MySQL tests for Hosting.
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

		// Scripts & Styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'styles_and_scripts' ) );

		// Add Rest API endpoints.
		add_action( 'rest_api_init', array( '\\Xkon\\Hosting_Checks\\REST\\Routes', 'register' ) );
	}

	/**
	 * Runs on plugin activation.
	 */
	public static function on_plugin_activation() {}

	/**
	 * Styles and scripts.
	 *
	 * @param string $hook_suffix
	 */
	public function styles_and_scripts( $hook_suffix ) {
		wp_enqueue_style(
			'hosting-checks',
			self::$url . 'css/styles.css',
			array(),
			self::$version
		);

		wp_enqueue_script(
			'hosting-checks',
			self::$url . 'js/scripts.js',
			array( 'jquery' ),
			self::$version,
			true
		);

		wp_localize_script(
			'hosting-checks',
			'plugin_tpl_globals',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'hosting-checks' ),
			)
		);
	}

	/**
	 * Runs on plugin deactivation.
	 */
	public static function on_plugin_deactivation() {}

	/**
	 * Runs on plugin uninstall.
	 */
	public static function on_plugin_uninstall() {}
}

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'on_plugin_activation' ) );

/**
 * Dectivation Hook.
 */
register_deactivation_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'on_plugin_deactivation' ) );

/**
 * Uninstall Hook.
 */
register_uninstall_hook( __FILE__, array( '\\Xkon\\Hosting_Checks', 'on_plugin_uninstall' ) );

/**
 * Load plugin.
 */
add_action( 'plugins_loaded', array( '\\Xkon\\Hosting_Checks', 'get_instance' ) );
