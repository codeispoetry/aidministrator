<?php
/**
 * AIdministrator
 *
 * @package           AIdministrator
 * @author            Thomas Rose
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       AIdministrator
 * Description:       Provides a chatbot (chatgpt) to do administrative tasks.
 * Version:           0.1
 * Requires at least: 5.0
 * Requires PHP:      5.6
 * Author:            Thomas Rose
 * Text Domain:       aidministrator
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Aidministrator;

use Exception;

try {
	if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		throw new Exception( 'Please run "composer install" in the plugin directory.' );
	}
} catch ( Exception $e ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	wp_die( $e->getMessage() );
}
require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
