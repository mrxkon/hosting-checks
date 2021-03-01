<?php

/**
 * Set namespace.
 */
namespace Xkon\Plugin_Tpl;

/**
 * Import necessary classes.
 */
use \Analog\Analog;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Log.
 *
 * Simple wrapper for Analog.
 */
class Logger {
	/**
	 * Log file.
	 *
	 * @return string
	 */
	public static function log_file() {
		return wp_normalize_path( WP_CONTENT_DIR . '/' . 'plugin-tpl.log' );
	}

	/**
	 * Should we log?
	 *
	 * @return bool
	 */
	public static function should_log() {
		return defined( 'PLUGIN_TPL_DEBUG' ) && PLUGIN_TPL_DEBUG ? true : false;
	}

	/**
	 * Log.
	 *
	 * @param mixed $message
	 */
	public static function log( $message ) {
		if ( ! self::should_log() ) {
			return;
		}

		if ( ! is_string( $message ) || is_array( $message ) || is_object( $message ) ) {
			$message = print_r( $message, true );
		}

		// Set Analog.
		Analog::handler( self::log_file() );

		Analog::log( $message );
	}
}
