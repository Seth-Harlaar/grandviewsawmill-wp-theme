<?php
/**
 * Custom blocks. Each folder in blocks/ has a block.json that tells
 * WordPress the block's name and which files it uses.
 */

add_action( 'init', function () {
	register_block_type( get_template_directory() . '/blocks/product-card' );
	register_block_type( get_template_directory() . '/blocks/product-meta' );
	register_block_type( get_template_directory() . '/blocks/category-card' ); // After product-card: reuses its CSS.
	register_block_type( get_template_directory() . '/blocks/back-link' );
} );
