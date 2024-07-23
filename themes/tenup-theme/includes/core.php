<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package TenUpTheme
 */

namespace TenUpTheme\Core;

use function TenUpTheme\Utility\get_asset_info;

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	add_action( 'init', 'TenUpTheme\Core\register_block_pattern_categories' );
	add_action( 'after_setup_theme', 'TenUpTheme\Core\i18n' );
	add_action( 'after_setup_theme', 'TenUpTheme\Core\theme_setup', 11 );
	add_action( 'wp_enqueue_scripts', 'TenUpTheme\Core\scripts' );
	add_action( 'wp_enqueue_scripts', 'TenUpTheme\Core\styles' );
	add_action( 'enqueue_block_editor_assets', 'TenUpTheme\Core\enqueue_block_editor_assets' );
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "tenup-theme", change the
 * filename of '/languages/TenUpTheme.pot' to the name of your project.
 *
 * @return void
 */
function i18n() {
	load_theme_textdomain( 'tenup-theme', TENUP_THEME_PATH . '/languages' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'dist/css/frontend.css' );

	remove_theme_support( 'core-block-patterns' );
}

/**
 * Register block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function register_block_pattern_categories() {

	register_block_pattern_category(
		'10up-theme',
		[ 'label' => __( '10up Theme', 'tenup-theme' ) ]
	);
}


/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	/**
	 * Enqueuing frontend.js is required to get css hot reloading working in the frontend
	 * If you're not shipping any front-end js wrap this enqueue in a SCRIPT_DEBUG check.
	 */
	wp_enqueue_script(
		'frontend',
		TENUP_THEME_DIST_URL . 'js/frontend.js',
		get_asset_info( 'frontend', 'dependencies' ),
		get_asset_info( 'frontend', 'version' ),
		[
			'strategy' => 'defer',
		]
	);
}


/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {
	wp_enqueue_style(
		'styles',
		TENUP_THEME_DIST_URL . 'css/frontend.css',
		[ 'ui-kit-theme-styles' ],
		get_asset_info( 'frontend', 'version' )
	);
}

/**
 * Enqueue scripts for block editor.
 *
 * @return void
 */
function enqueue_block_editor_assets() {
	wp_enqueue_script(
		'block-editor',
		TENUP_THEME_DIST_URL . 'js/block-editor.js',
		get_asset_info( 'block-editor', 'dependencies' ),
		get_asset_info( 'block-editor', 'version' ),
		[
			'strategy' => 'defer',
		]
	);
}
