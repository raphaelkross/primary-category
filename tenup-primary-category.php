<?php
/**
 * Primary Category
 *
 * @package     primary-category
 * @author      Rafael Angeline
 * @copyright   2017 Rafael Angeline
 * @license     GPL-2.0+
 *
 * Plugin Name: TenUp Primary Category
 * Plugin URI:  https://github.com/raphaelkross/primary-category
 * Description: Allows editors to set a primary category to posts.
 * Version:     1.0.0
 * Author:      Rafael Angeline
 * Author URI:  https://rafaelangeline.com
 * Text Domain: tenup-primary-category
 * Domain Path: /languages
 */

// Useful global constants.
define( 'TENUP_PRIMARY_CATEGORY_VERSION', '1.0.0' );
define( 'TENUP_PRIMARY_CATEGORY_URL',     plugin_dir_url( __FILE__ ) );
define( 'TENUP_PRIMARY_CATEGORY_PATH',    dirname( __FILE__ ) . '/' );
define( 'TENUP_PRIMARY_CATEGORY_INC',     TENUP_PRIMARY_CATEGORY_PATH . 'includes/' );

// Include files.
require_once TENUP_PRIMARY_CATEGORY_INC . 'functions/core.php';
require_once TENUP_PRIMARY_CATEGORY_INC . 'classes/post-metabox.php';

// Bootstrap.
TenUpPrimaryCategory\Core\setup();
