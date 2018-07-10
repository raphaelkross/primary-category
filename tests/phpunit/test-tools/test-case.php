<?php
/**
 * Sample Test Case.
 *
 * @package primary-category
 */

namespace TenUpPrimaryCategory;

use PHPUnit_Framework_TestResult;
use Text_Template;
use WP_Mock;
use WP_Mock\Tools\TestCase as BaseTestCase;

/**
 * Test Case.
 */
class TestCase extends BaseTestCase {

	/**
	 * Overwrite run method.
	 *
	 * @param \PHPUnit\Framework\TestResult $result TestResult instance.
	 * @return \PHPUnit\Framework\TestResult
	 */
	public function run( \PHPUnit\Framework\TestResult $result = null ) {
		$this->setPreserveGlobalState( false );
		return parent::run( $result );
	}

	/**
	 * Files to be tested.
	 *
	 * @var array
	 */
	protected $test_files = array();

	/**
	 * Overwrite default setUp.
	 *
	 * @return void
	 */
	public function setUp() {
		if ( ! empty( $this->test_files ) ) {
			foreach ( $this->test_files as $file ) {
				if ( file_exists( PROJECT . $file ) ) {
					require_once( PROJECT . $file );
				}
			}
		}

		parent::setUp();
	}

	/**
	 * Assert if actions were called as expected.
	 *
	 * @return void
	 */
	public function assertActionsCalled() {
		$actions_not_added = $expected_actions = 0;
		try {
			WP_Mock::assertActionsCalled();
		} catch ( \Exception $e ) {
			$actions_not_added = 1;
			$expected_actions  = $e->getMessage();
		}
		$this->assertEmpty( $actions_not_added, $expected_actions );
	}

	/**
	 * Get namespace.
	 *
	 * @param string $function The function name.
	 * @return string
	 */
	public function ns( $function ) {
		if ( ! is_string( $function ) || false !== strpos( $function, '\\' ) ) {
			return $function;
		}

		$this_classname = trim( get_class( $this ), '\\' );

		if ( ! strpos( $this_classname, '\\' ) ) {
			return $function;
		}

		// $thisNamespace is constructed by exploding the current class name on
		// namespace separators, running array_slice on that array starting at 0
		// and ending one element from the end (chops the class name off) and
		// imploding that using namespace separators as the glue.
		$this_namespace = implode( '\\', array_slice( explode( '\\', $this_classname ), 0, - 1 ) );

		return "$this_namespace\\$function";
	}

	/**
	 * Define constants after requires/includes
	 *
	 * See http://kpayne.me/2012/07/02/phpunit-process-isolation-and-constant-already-defined/
	 * for more details
	 *
	 * @param \Text_Template $template Template text.
	 */
	public function prepareTemplate( \Text_Template $template ) {
		$template->setVar( [
			'globals' => '$GLOBALS[\'__PHPUNIT_BOOTSTRAP\'] = \'' . $GLOBALS['__PHPUNIT_BOOTSTRAP'] . '\';',
		] );
		parent::prepareTemplate( $template );
	}
}
