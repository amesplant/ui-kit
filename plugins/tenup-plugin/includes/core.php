<?php
/**
 * Core plugin functionality.
 *
 * @package TenUpPlugin
 */

namespace TenUpPlugin\Core;

use TenUpPlugin\ModuleInitialization;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	add_action( 'init', 'TenUpPlugin\Core\i18n' );
	add_action( 'init', 'TenUpPlugin\Core\register_blocks' );
	add_action( 'init', 'TenUpPlugin\Core\register_block_pattern_categories' );
	add_action( 'init', 'TenUpPlugin\Core\init', apply_filters( 'tenup_plugin_init_priority', 8 ) );

	do_action( 'tenup_plugin_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'tenup-plugin' );
	load_textdomain( 'tenup-plugin', WP_LANG_DIR . '/tenup-plugin/tenup-plugin-' . $locale . '.mo' );
	load_plugin_textdomain( 'tenup-plugin', false, plugin_basename( TENUP_PLUGIN_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'tenup_plugin_before_init' );

	// If the composer.json isn't found, trigger a warning.
	if ( ! file_exists( TENUP_PLUGIN_PATH . 'composer.json' ) ) {
		add_action(
			'admin_notices',
			function () {
				$class = 'notice notice-error';
				/* translators: %s: the path to the plugin */
				$message = sprintf( __( 'The composer.json file was not found within %s. No classes will be loaded.', 'tenup-plugin' ), TENUP_PLUGIN_PATH );

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}
		);
		return;
	}

	ModuleInitialization::instance()->init_classes();
	do_action( 'tenup_plugin_init' );
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {}

/**
 * Register block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function register_block_pattern_categories() {

	register_block_pattern_category(
		'tenup-plugin',
		[ 'label' => __( '10up Plugin', 'tenup-plugin' ) ]
	);
}

/**
 * Automatically registers all blocks that are located within the includes/blocks directory
 *
 * @return void
 */
function register_blocks() {
	if ( file_exists( TENUP_PLUGIN_BLOCKS_PATH ) ) {
		$block_json_files = glob( TENUP_PLUGIN_BLOCKS_PATH . '*/block.json' );

		foreach ( $block_json_files as $filename ) {
			$block_folder = dirname( $filename );
			$block        = register_block_type_from_metadata( $block_folder );

			add_filter(
				'allowed_block_types_all',
				function ( $allowed_blocks ) use ( $block ) {
					return array_merge( $allowed_blocks, [ $block->name ] );
				}
			);
		}
	}
}
