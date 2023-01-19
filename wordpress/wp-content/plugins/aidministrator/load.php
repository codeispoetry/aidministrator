<?php

namespace Aidministrator;

Controllers\Plugin::init( __DIR__, __FILE__ );

add_action( 'admin_enqueue_scripts', 'Aidministrator\Controllers\Plugin::enqueue' );

add_action( 'admin_footer', 'Aidministrator\Controllers\Chat::init' );

add_action( 'wp_ajax_aidministrator', [ new Controllers\Action(), 'human' ] );

