<?php

namespace L4WP;

class Assets {
	public function register_hooks() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_block_editor_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
	}

	private function get_asset_data( string $name ): array {
		$asset_filepath = L4WP_ASSETS_PATH . $name . '.asset.php';
		$asset_file     = file_exists( $asset_filepath ) ? include $asset_filepath : [
			'dependencies' => [],
			'version'      => L4WP_VERSION,
		];

		return $asset_file;
	}

	private function enqueue_style( string $name, array $dependencies = [] ) {
		$data = $this->get_asset_data( str_replace( 'style-', '', $name ) );

		wp_enqueue_style(
			"loops-for-wordpress-{$name}-style",
			L4WP_ASSETS_URL . $name . '.css',
			$dependencies,
			$data['version']
		);
	}

	private function enqueue_script( string $name, array $params = [], $dependencies = [] ) {
		$data = $this->get_asset_data( $name );

		wp_register_script(
			"loops-for-wordpress-{$name}-plugin-script",
			L4WP_ASSETS_URL . $name . '.js',
			array_merge( $data['dependencies'], $dependencies ),
			$data['version'],
			true
		);

		if ( ! empty( $params ) ) {
			wp_add_inline_script( "loops-for-wordpress-{$name}-plugin-script", 'const l4WP = ' . wp_json_encode( $params ), 'before' );
		}

		wp_enqueue_script( "loops-for-wordpress-{$name}-plugin-script" );
	}

	public function register_block_editor_assets() {
		$this->enqueue_script( 'editor' );
		$this->enqueue_style( 'editor' );
	}

	public function register_assets() {
		$this->enqueue_style( 'app' );
		$this->enqueue_script( 'app' );
	}
}
