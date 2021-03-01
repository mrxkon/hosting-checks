<?php

/**
 * Set namespace.
 */
namespace Xkon\Hosting_Checks\Admin;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Menu.
 */
class Menu {
	/**
	 * Create the Admin menu.
	 */
	public static function create() {
		add_action(
			'admin_menu',
			array(
				'\\Xkon\\Hosting_Checks\\Admin\\Menu',
				'populate',
			)
		);
	}

	/**
	 * Populate the Admin menu.
	 */
	public static function populate() {
		add_menu_page(
			'Hosting Checks',
			'Hosting Checks',
			'manage_options',
			'hosting-checks',
			array( '\\Xkon\\Hosting_Checks\\Admin\\Page', 'load' ),
			'dashicons-yes-alt'
		);
	}
}
