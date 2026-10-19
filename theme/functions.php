<?php
/**
 * functions.php runs on every page load. Use it to register features,
 * menus, and load CSS/JS.
 */

add_action( 'after_setup_theme', function () {
	// Let WordPress manage the <title> tag.
	add_theme_support( 'title-tag' );

	// Register a menu location. Assign a menu to it in Appearance -> Menus.
	register_nav_menus( array(
		'primary' => 'Primary Menu',
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	// Load style.css on the front end.
	wp_enqueue_style( 'gvs-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
} );
