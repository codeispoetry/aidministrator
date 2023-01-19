<?php

namespace Aidministrator\Controllers;

/**
 * The Controller class for the whole plugin.
 */
class Plugin {
	/**
	 * Inits the plugin.
	 *
	 * @param string $dir Direcotry of the plugin.
	 * @param string $file Main file of the plugin.
	 */
	public static function init( $dir, $file ) {
		define( __NAMESPACE__ . '\\PLUGIN_DIR', $dir );
		define( __NAMESPACE__ . '\\PLUGIN_FILE', $file );

		self::load_textdomain();
	}

	/**
	 * Inits the plugin translation.
	 */
	public static function load_textdomain() {
		$plugin_rel_path = substr( PLUGIN_DIR, strlen( WP_PLUGIN_DIR ) + 1 );

		load_plugin_textdomain(
			'aidministrator',
			false,
			$plugin_rel_path . '/languages'
		);
	}

	/**
	 * Enqueues the scripts and styles.
	 */
	public static function enqueue() {
		wp_enqueue_script( 'aidministrator', plugin_dir_url( PLUGIN_FILE ) . 'dist/js/index.js', [], '1.0', true );
		wp_enqueue_style( 'aidministrator', plugin_dir_url( PLUGIN_FILE ) . 'dist/css/style.css', [], '1.0', 'all' );

		$vars = [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'aidministrator' ),
			'messages' => [
				'error' => __( 'An error occurred.', 'aidministrator' ),
			],
		];
		wp_localize_script( 'aidministrator', 'aidministrator', $vars );
	}

	/**
	 * Gets the config.
	 *
	 * @throws \Exception If the config file is missing.
	 */
	public static function get_config() {
		try {
			$config = parse_ini_file( PLUGIN_DIR . '/config.ini' );

			if ( empty( $config ) ) {
				throw new \Exception( 'Please provide Open AI Key in config.file' );
			}
		} catch ( \Exception $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return [ 'error' => $e->getMessage() ];
		}

		return $config;
	}
}
