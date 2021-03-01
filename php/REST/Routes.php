<?php

/**
 * Set namespace.
 */
namespace Xkon\Plugin_Tpl\REST;

/**
 * Import necessary classes.
 */
use \WP_REST_Server;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Routes.
 */
class Routes {
	/**
	 * Register routes.
	 */
	public static function register() {
		register_rest_route(
			'plugin-tpl/v1',
			'/hello',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( '\\Xkon\\Plugin_Tpl\\REST\\Functions', 'hello' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}
}
