<?php

/**
 * Set namespace.
 */
namespace Xkon\Plugin_Tpl\Admin;

/**
 * Import necessary classes.
 */
use Xkon\Plugin_Tpl\Logger;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Page.
 */
class Page {
	/**
	 * Create the Admin page.
	 */
	public static function load() {
		?>
		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
		</div>
		<?php

		// Logger tests.
		$one = array(
			'one',
			'two',
			'three',
		);
		$two = array(
			'one' => 'one',
			'two' => 'two',
			'three' => 123,
		);
		Logger::log( 'my string' );
		Logger::log( '123 string' );
		Logger::log( 123 );
		Logger::log( $one );
		Logger::log( $two );
	}
}
