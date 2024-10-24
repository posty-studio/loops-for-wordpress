<?php

namespace L4WP;

class Setup {
	private function set_constants() {
		define( 'L4WP_VERSION', '1.0.0' );
		define( 'L4WP_SLUG', 'loops-for-wordpress' );
		define( 'L4WP_PATH', plugin_dir_path( __DIR__ ) );
		define( 'L4WP_BLOCKS_PATH', L4WP_PATH . 'build/js/blocks/' );
		define( 'L4WP_ASSETS_PATH', L4WP_PATH . 'build/' );
		define( 'L4WP_ASSETS_URL', plugin_dir_url( __DIR__ ) . 'build/' );
	}

	public function init() {
		$this->set_constants();

		( new Assets() )->register_hooks();
		( new Blocks() )->register_hooks();

		add_action( 'rest_api_init', [ new Endpoints\REST_Subscribe_Controller(), 'register_routes' ] );
	}
}
