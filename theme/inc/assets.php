<?php
/**
 * Stylesheets for parts of the site that theme.json can't style on its own.
 */

add_action( 'wp_enqueue_scripts', function () {
	// Front end.
	wp_enqueue_style( 'grandview-header', get_theme_file_uri( 'assets/css/header.css' ), array(), wp_get_theme()->get( 'Version' ) );
} );

add_action( 'after_setup_theme', function () {
	// Site editor, so the header looks the same while editing.
	add_editor_style( 'assets/css/header.css' );
} );
