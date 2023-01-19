<?php

namespace Aidministrator\Controllers;

/**
 * The Controller class for the chat
 */
class Chat {


	/**
	 * Inits the chat.
	 */
	public static function init() {
		load_template(
			PLUGIN_DIR . '/inc/views/page.php',
			true,
			[
				'foo' => 'bar',
			]
		);
	}
}
