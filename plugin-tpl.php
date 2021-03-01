<?php

/**
 * Plugin Name:       Plugin - Template
 * Plugin URI:        https://github.com/mrxkon/plugin-tpl
 * Description:       Plugin - Template
 * Version:           1.0.0
 * Required at least: 5.0
 * Requires PHP:      7.0
 * Author:            Konstantinos Xenos
 * Author URI:        https://xkon.dev
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       plugin-tpl
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
use Xkon\Plugin_Tpl\Admin\Menu;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Plugin_Tpl.
 */
class Plugin_Tpl {
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
	 * @var null|Plugin_Tpl $instance
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
		add_action( 'rest_api_init', array( '\\Xkon\\Plugin_Tpl\\REST\\Routes', 'register' ) );
	}

	/**
	 * Runs on plugin activation.
	 */
	public static function on_plugin_activation() {
		// Use this functionality to require other plugins to be installed as well.

		/*
		$active_plugins   = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$required_plugins = array(
			'some-plugin/some-plugin.php',
			'woocommerce/woocommerce.php',
		);

		foreach ( $required_plugins as $required_plugin ) {
			preg_match( '#^(.*)(?:\/.*)#', $required_plugin, $matches );
			if ( ! in_array( $required_plugin, $active_plugins, true ) ) {
				wp_die( 'Sorry, but this plugin requires ' . $matches[1] . '. <a href="' . admin_url( 'plugins.php' ) . '">Return to Plugins.</a>' );
			}
		}
		*/
	}

	/**
	 * Styles and scripts.
	 *
	 * @param string $hook_suffix
	 */
	public function styles_and_scripts( $hook_suffix ) {
		wp_enqueue_style(
			'plugin-tpl',
			self::$url . 'css/styles.css',
			array(),
			self::$version
		);

		wp_enqueue_script(
			'plugin-tpl',
			self::$url . 'js/scripts.js',
			array( 'jquery' ),
			self::$version,
			true
		);

		wp_localize_script(
			'plugin-tpl',
			'plugin_tpl_globals',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'plugin-tpl' ),
			)
		);
	}

	/**
	 * Runs on plugin deactivation.
	 */
	public static function on_plugin_deactivation() {
		Logger::log( 'Deactivating plugin.' );
	}

	/**
	 * Runs on plugin uninstall.
	 */
	public static function on_plugin_uninstall() {}
}

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, array( '\\Xkon\\Plugin_Tpl', 'on_plugin_activation' ) );

/**
 * Dectivation Hook.
 */
register_deactivation_hook( __FILE__, array( '\\Xkon\\Plugin_Tpl', 'on_plugin_deactivation' ) );

/**
 * Uninstall Hook.
 */
register_uninstall_hook( __FILE__, array( '\\Xkon\\Plugin_Tpl', 'on_plugin_uninstall' ) );

/**
 * Load plugin.
 */
add_action( 'plugins_loaded', array( '\\Xkon\\Plugin_Tpl', 'get_instance' ) );
