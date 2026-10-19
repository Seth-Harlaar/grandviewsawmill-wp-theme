<?php
/**
 * Stylesheets for parts of the site that theme.json can't style on its own.
 * Each file in assets/css/ listed here is loaded on the site and in the editor.
 */

function gvs_stylesheets() {
	return array( 'base', 'header', 'hero', 'about', 'services', 'footer', 'products', 'product-detail', 'inventory', 'contact' );
}

add_action( 'wp_enqueue_scripts', function () {
	// Front end.
	foreach ( gvs_stylesheets() as $name ) {
		// Version = file's last-modified time, so browsers fetch it again after every edit.
		wp_enqueue_style( "grandview-$name", get_theme_file_uri( "assets/css/$name.css" ), array(), filemtime( get_theme_file_path( "assets/css/$name.css" ) ) );
	}
} );

add_action( 'after_setup_theme', function () {
	// Site editor, so things look the same while editing.
	foreach ( gvs_stylesheets() as $name ) {
		add_editor_style( "assets/css/$name.css" );
	}
} );
