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
class PostMetaboxTests extends Base\TestCase {

	/**
	 * Files to be tested.
	 *
	 * @var array
	 */
	protected $test_files = [
		'classes/post-metabox.php'
	];

	/**
	 * Create a instance.
	 */
	public function test_new_instance() {
		$this->assertInstanceOf( PostMetabox::class, new PostMetabox() );
	}

	/**
	 * Register the metabox.
	 */
	public function test_add_metabox() {
		// Get the instance.
		$instance = new PostMetabox();

		// Expected args to add_meta_box.
		$args = array(
			'tenup-primary-category',
			esc_html__( 'Primary Category', 'tenup-primary-category' ),
			array( $instance, 'content' ),
			'post',
			'side',
			'core',
		);

		// Expect add_meta_box to be called with proper args.
		\WP_Mock::userFunction( 'add_meta_box', array(
			'args'  => $args,
			'times' => 1,
		) );

		$instance->add_metabox();

		$this->assertConditionsMet();
	}

	/**
	 * Save post's meta data.
	 */
	public function test_save_data() {
		// Get the instance.
		$instance = new PostMetabox();

		// Sample post ID.
		$post_id = 20;

		// Sample $_POST.
		$_POST['tenup_primary_category_the_category'] = '40';
		$_POST['tenup_primary_category_nonce'] = 'nonce';

		// Sanitizers.
		\WP_Mock::passthruFunction( 'sanitize_key', array(
			'return_arg' => 0,
		) );

		\WP_Mock::passthruFunction( 'sanitize_text_field', array(
			'return_arg' => 0,
		) );

		\WP_Mock::passthruFunction( 'wp_unslash', array(
			'return_arg' => 0,
		) );

		// Force the nonce to pass.
		\WP_Mock::passthruFunction( 'wp_verify_nonce', array(
			'return' => true,
		) );

		// Ensure the user can edit the post.
		\WP_Mock::passthruFunction( 'current_user_can', array(
			'return' => true,
		) );

		// Expect update_post_meta with thoses parameters.
		\WP_Mock::userFunction( 'update_post_meta', array(
			'args' 	=> array(
				$post_id,
				'tenup_primary_category_the_category',
				'40',
			),
			'times' => 1,
		) );

		// Call it!
		$instance->save( $post_id );

		$this->assertConditionsMet();
	}

	/**
	 * Ensure content method is callable (by metabox callback).
	 */
	public function test_content_is_callable() {
		$instance = new PostMetabox();

		$this->assertTrue( method_exists( $instance, 'content' ) );
	}

	/**
	 * Test if hooks are all set.
	 */
	public function test_hooks() {
		$instance = new PostMetabox();

		\WP_Mock::expectActionAdded( 'save_post', array( $instance, 'save' ) );
		\WP_Mock::expectActionAdded( 'add_meta_boxes', array( $instance, 'add_metabox' ) );

		// Call it.
		$instance->hooks();
		$this->assertConditionsMet();
	}

	/**
	 * Output categories as <option> tags.
	 */
	public function test_output_categories_as_option_tags() {
		$instance = new PostMetabox();

		$categories = array(
			(Object) [ 'name' => 'Category 1', 'term_id' => 20 ],
			(Object) [ 'name' => 'Category 2', 'term_id' => 30 ],
		);

		\WP_Mock::userFunction( 'get_the_ID', array(
			'return' => 10,
		) );

		\WP_Mock::userFunction( 'get_the_category', array(
			'return' => $categories,
		) );

		\WP_Mock::userFunction( 'get_post_meta', array(
			'return' => '20',
		) );

		// Call it!
		$markup = $instance->get_options();

		// The expected result from the above function call.
		$expected_result =
			'<option selected="selected" value="20">Category 1</option><option value="30">Category 2</option>';

		$this->assertEquals( $expected_result, $markup );
	}
}
