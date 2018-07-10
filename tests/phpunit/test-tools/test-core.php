<?php
/**
 * Test Core functionalities.
 *
 * @package primary-category
 */

namespace TenUpPrimaryCategory\Core;

use TenUpPrimaryCategory as Base;

/**
 * Core Tests.
 */
class CoreTests extends Base\TestCase {

	/**
	 * Files to be tested.
	 *
	 * @var array
	 */
	protected $test_files = [
		'classes/post-metabox.php',
		'functions/core.php',
	];

	/**
	 * Test load method.
	 */
	public function test_setup() {
		// Setup.
		\WP_Mock::expectActionAdded( 'init', 'TenUpPrimaryCategory\Core\i18n' );
		\WP_Mock::expectActionAdded( 'init', 'TenUpPrimaryCategory\Core\init' );
		\WP_Mock::expectActionAdded( 'admin_init', 'TenUpPrimaryCategory\Core\admin_init' );
		\WP_Mock::expectAction( 'tenup_primary_category_loaded' );

		// Act.
		setup();

		// Verify.
		$this->assertConditionsMet();
	}

	/**
	 * Test internationalization integration.
	 */
	public function test_i18n() {
		// Setup.
		\WP_Mock::userFunction( 'get_locale', array(
			'times' => 1,
			'args' => array(),
			'return' => 'en_US',
		) );
		\WP_Mock::onFilter( 'plugin_locale' )->with( 'en_US', 'tenup-primary-category' )->reply( 'en_US' );
		\WP_Mock::userFunction( 'load_textdomain', array(
			'times' => 1,
			'args' => array( 'tenup-primary-category', 'lang_dir/tenup-primary-category/tenup-primary-category-en_US.mo' ),
		) );
		\WP_Mock::userFunction( 'plugin_basename', array(
			'times' => 1,
			'args' => array( 'path' ),
			'return' => 'path',
		) );
		\WP_Mock::userFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'tenup-primary-category', false, 'path/languages/' ),
		) );

		// Act.
		i18n();

		// Verify.
		$this->assertConditionsMet();
	}

	/**
	 * Test initialization method.
	 */
	public function test_init() {
		// Setup.
		\WP_Mock::expectAction( 'tenup_primary_category_init' );

		// Act.
		init();

		// Verify.
		$this->assertConditionsMet();
	}

	/**
	 * Test initialization method at admin.
	 */
	public function test_admin_init() {
		// Setup.
		\WP_Mock::expectAction( 'tenup_primary_category_admin_init' );

		// Act.
		admin_init();

		// Verify.
		$this->assertConditionsMet();
	}
}
