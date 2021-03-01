<?php

/**
 * Set namespace.
 */
namespace Xkon\Hosting_Checks;

/**
 * Import necessary classes.
 */
use Xkon\Hosting_Checks\Logger;

/**
 * Check that the file is not accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, but you can not directly access this file.' );
}

/**
 * Class Tests.
 */
class Tests {
	/**
	 * Results.
	 *
	 * @var array
	 */
	private static $results;

	/**
	 * Information.
	 *
	 * @var array
	 */
	private static $info;

	/**
	 * PHP function iterations.
	 *
	 * @var int
	 */
	private static $func_iterations = 150000;

	/**
	 * PHP loop iterations
	 *
	 * @var int
	 */
	private static $loop_iterations = 1000000;

	/**
	 * WordPress iterations.
	 *
	 * @var int
	 */
	private static $wp_iterations = 300;

	/**
	 * PHP math functions.
	 */
	private static $math_functions = array(
		'abs',
		'acos',
		'asin',
		'atan',
		'floor',
		'exp',
		'sin',
		'tan',
		'pi',
		'is_finite',
		'is_nan',
		'sqrt',
	);

	/**
	 * PHP string functions.
	 *
	 * @var array
	 */
	private static $string_functions = array(
		'addslashes',
		'chunk_split',
		'metaphone',
		'strip_tags',
		'md5',
		'sha1',
		'strtoupper',
		'strtolower',
		'strrev',
		'strlen',
		'soundex',
		'ord',
	);

	/**
	 * Run all tests.
	 *
	 * @return array
	 */
	public static function run_tests() {
		$start_time = microtime( true );

		self::math();
		self::string();
		self::loop();
		self::ifelse();

		$count_math   = self::$func_iterations * count( self::$math_functions );
		$count_string = self::$func_iterations * count( self::$string_functions );
		$count_loop   = self::$loop_iterations * 2;

		self::$results['math']['label']   = 'PHP Math<br/>(' . $count_math . ' iterations)';
		self::$results['string']['label'] = 'PHP String Manipulation<br/>(' . $count_string . ' iterations)';
		self::$results['loop']['label']   = 'PHP Loops<br/>(' . $count_loop . ' iterations)';
		self::$results['ifelse']['label'] = 'PHP If-Elseif-Elseif<br/>(' . self::$loop_iterations . ' iterations)';

		self::mysql();
		self::wordpress();

		self::$results['wpdb_insert']['label'] = 'WordPress $wpdb Insert<br/>(' . self::$wp_iterations . ' iterations)';
		self::$results['wpdb_select']['label'] = 'WordPress $wpdb Select<br/>(' . self::$wp_iterations . ' iterations)';
		self::$results['wpdb_update']['label'] = 'WordPress $wpdb Update<br/>(' . self::$wp_iterations . ' iterations)';
		self::$results['wpdb_delete']['label'] = 'WordPress $wpdb Delete<br/>(' . self::$wp_iterations . ' iterations)';

		self::$results['total']['label']  = 'Total time';
		self::$results['total']['result'] = self::calculate_time( $start_time );

		return self::$results;
	}

	/**
	 * Calculate time.
	 */
	private static function calculate_time( $start_time ) {
		return number_format( microtime( true ) - $start_time, 3 );
	}

	/**
	 * PHP math test.
	 */
	private static function math() {
		$start_time = microtime( true );

		for ( $i = 0; $i < self::$func_iterations; $i++ ) {
			foreach ( self::$math_functions as $function ) {
				$run = call_user_func_array( $function, array( $i ) );
			}
		}

		self::$results['math']['result'] = self::calculate_time( $start_time );
	}

	/**
	 * PHP string test.
	 */
	private static function string() {
		$start_time = microtime( true );

		$string = 'the quick brown fox jumps over the lazy dog';

		for ( $i = 0; $i < self::$func_iterations; $i++ ) {
			foreach ( self::$string_functions as $function ) {
				$run = call_user_func_array( $function, array( $string ) );
			}
		}

		self::$results['string']['result'] = self::calculate_time( $start_time );
	}

	/**
	 * PHP loop test.
	 */
	private static function loop() {
		$start_time = microtime( true );

		for ( $i = 0; $i < self::$loop_iterations; ++$i ) {
			// silence.
		}

		$i = 0;

		while ( $i < self::$loop_iterations ) {
			++$i;
		}

		self::$results['loop']['result'] = self::calculate_time( $start_time );
	}

	/**
	 * PHP if-elseif-elseif test.
	 */
	private static function ifelse() {
		$start_time = microtime( true );

		for ( $i = 0; $i < self::$loop_iterations; $i++ ) {
			if ( -1 === $i ) {
				// silence.
			} elseif ( -2 === $i ) {
				// silence.
			} elseif ( -3 === $i ) {
				// silence.
			}
		}

		self::$results['ifelse']['result'] = self::calculate_time( $start_time );
	}

	/**
	 * MySQL test.
	 */
	private static function mysql() {
		$start_time = microtime( true );

		$host = explode( ':', DB_HOST );

		// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_connect
		$db = mysqli_connect( $host[0], DB_USER, DB_PASSWORD, DB_NAME, $host[1] );

		self::$results['mysql_connect']['label']  = 'MySQL Connect';
		self::$results['mysql_connect']['result'] = self::calculate_time( $start_time );

		$query = "SELECT BENCHMARK( 1000000, ENCODE( 'Hello & Goodbye!', RAND() ) );";

		// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_query
		$result = mysqli_query( $db, $query );

		self::$results['mysql_query']['label']  = 'MySQL Select Benchmark<br/>(1000000 iterations)';
		self::$results['mysql_query']['result'] = self::calculate_time( $start_time );

		// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_close
		mysqli_close( $db );
	}

	/**
	 * WordPress test.
	 */
	private static function wordpress() {
		global $wpdb;

		$table           = $wpdb->prefix . 'options';
		$option_name     = 'hosting_checks_';
		$coffee_text     = 'Crema, macchiato, cup whipped robusta decaffeinated wings iced extra est aromatic.';
		$coffee_new_text = 'Iced, organic, frappuccino single shot white cultivar cappuccino blue mountain chicory organic instant flavour.';

		$start_time = microtime( true );

		for ( $i = 0; $i < self::$wp_iterations; $i++ ) {
			$query_result = $wpdb->insert(
				$table,
				array(
					'option_name'  => $option_name . $i,
					'option_value' => $coffee_text,
				)
			);
		}

		self::$results['wpdb_insert']['result'] = self::calculate_time( $start_time );

		$start_time = microtime( true );

		for ( $i = 0; $i < self::$wp_iterations; $i++ ) {
			$query_result = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s",
					$option_name . $i
				)
			);
		}

		self::$results['wpdb_select']['result'] = self::calculate_time( $start_time );

		$start_time = microtime( true );

		for ( $i = 0; $i < self::$wp_iterations; $i++ ) {
			$query_result = $wpdb->update(
				$table,
				array(
					'option_value' => $coffee_new_text,
				),
				array(
					'option_name' => $option_name . $i,
				)
			);
		}

		self::$results['wpdb_update']['result'] = self::calculate_time( $start_time );

		$start_time = microtime( true );

		for ( $i = 0; $i < self::$wp_iterations; $i++ ) {
			$query_result = $wpdb->delete(
				$table,
				array(
					'option_name' => $option_name . $i,
				)
			);
		}

		self::$results['wpdb_delete']['result'] = self::calculate_time( $start_time );
	}
}
