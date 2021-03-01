<?php

/**
 * Set namespace.
 */
namespace Xkon\Hosting_Checks\Admin;

/**
 * Import necessary classes.
 */
use Xkon\Hosting_Checks\Logger;
use Xkon\Hosting_Checks\Tests;

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
			<table class="form-table" role="presentation">
				<tbody>
				<?php
				$results = Tests::run_tests();

				foreach ( $results as $result ) {
					echo '<tr>';
						echo '<th scope="row">' . $result['label'] . '</th>';
						echo '<td>' . $result['result'] . ' seconds</td>';
					echo '</tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
