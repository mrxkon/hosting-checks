<?php

/**
 * Set namespace.
 */
namespace Xkon\Plugin_Tpl\REST;

/**
 * Import necessary classes.
 */
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_Error;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Functions.
 */
class Functions {
	/**
	 * Hello!
	 *
	 * @param WP_REST_Request $request
	 */
	public static function hello( \WP_REST_Request $request ) {
		$user     = wp_get_current_user();
		$username = $user->user_login;

		return new \WP_REST_Response(
			array(
				'username' => $username,
			),
			200
		);
	}
}
