<?php
/**
 * Core class that initializes the project.
 *
 * @package primary-category
 */

namespace TenUpPrimaryCategory\Core;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
	add_action( 'admin_init', $n( 'admin_init' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );

	do_action( 'tenup_primary_category_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'tenup-primary-category' );
	load_textdomain( 'tenup-primary-category', WP_LANG_DIR . '/tenup-primary-category/tenup-primary-category-' . $locale . '.mo' );
	load_plugin_textdomain( 'tenup-primary-category', false, plugin_basename( TENUP_PRIMARY_CATEGORY_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'tenup_primary_category_init' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into at admin.
 *
 * @return void
 */
function admin_init() {
	// Initialize the PostMetabox Instance.
	$post_metabox = new PostMetabox();
	$post_metabox->hooks();

	do_action( 'tenup_primary_category_admin_init' );
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension).
 * @param string $context Context for the script ('admin', 'frontend', or 'shared').
 *
 * @return string|WP_Error URL
 */
function script_url( $script, $context ) {

	if ( ! in_array( $context, [ 'admin', 'frontend', 'shared' ], true ) ) {
		error_log( 'Invalid $context specfied in TenUpPrimaryCategory script loader.' );
		return '';
	}

	return TENUP_PRIMARY_CATEGORY_URL . "dist/js/${context}.bundle.js" ;
}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts() {

	wp_enqueue_script(
		'tenup_primary_category_admin',
		script_url( 'admin', 'admin' ),
		[ 'jquery' ],
		TENUP_PRIMARY_CATEGORY_VERSION,
		true
	);

}
