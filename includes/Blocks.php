<?php

namespace L4WP;

class Blocks {
	public array $blocks = [ 'signup-form' ];

	public function register_hooks() {
		add_action( 'init', [ $this, 'register' ] );
	}

	public function register() {
		foreach ( $this->blocks as $block ) {
			register_block_type( L4WP_BLOCKS_PATH . $block );
		}
	}
}
