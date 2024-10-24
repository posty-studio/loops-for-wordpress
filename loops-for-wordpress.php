<?php
/**
 * Plugin Name: Loops for WordPress
 * Description: Add Loops support to your WordPress site.
 * Author: Daniel Post
 * Author URI: https://posty.studio
 * License: GPL-3.0
 * Version: 1.0.0
 * Text Domain: loops-for-wordpress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

( new L4WP\Setup() )->init();
