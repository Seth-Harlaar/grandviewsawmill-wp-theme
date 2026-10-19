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

/**
 * Products custom post type.
 * Title and description use WordPress's built-in title and editor.
 * Price, quantity and unit are stored as post meta (extra fields).
 */

add_action( 'init', function () {
	// Adds a "Products" section to wp-admin.
	register_post_type( 'product', array(
		'label'       => 'Products',
		'public'      => true,
		'has_archive' => true,
		'menu_icon'   => 'dashicons-cart',
		'supports'    => array( 'title', 'editor' ),
	) );
} );

add_action( 'add_meta_boxes', function () {
	// Adds a "Product Details" box to the product edit screen.
	add_meta_box( 'product_details', 'Product Details', 'gvs_product_details_box', 'product' );
} );

function gvs_product_details_box( $post ) {
	wp_nonce_field( 'gvs_save_product', 'gvs_product_nonce' );

	$price    = get_post_meta( $post->ID, 'price', true );
	$quantity = get_post_meta( $post->ID, 'quantity', true );
	$unit     = get_post_meta( $post->ID, 'unit', true );
	?>
	<p>
		<label>Price<br>
		<input type="number" step="0.01" name="price" value="<?php echo esc_attr( $price ); ?>"></label>
	</p>
	<p>
		<label>Quantity<br>
		<input type="number" name="quantity" value="<?php echo esc_attr( $quantity ); ?>"></label>
	</p>
	<p>
		<label>Unit (e.g. board foot, each)<br>
		<input type="text" name="unit" value="<?php echo esc_attr( $unit ); ?>"></label>
	</p>
	<?php
}

add_action( 'save_post_product', function ( $post_id ) {
	// Only save when the form was submitted by someone allowed to edit.
	if ( ! isset( $_POST['gvs_product_nonce'] ) || ! wp_verify_nonce( $_POST['gvs_product_nonce'], 'gvs_save_product' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, 'price', sanitize_text_field( $_POST['price'] ?? '' ) );
	update_post_meta( $post_id, 'quantity', absint( $_POST['quantity'] ?? 0 ) );
	update_post_meta( $post_id, 'unit', sanitize_text_field( $_POST['unit'] ?? '' ) );
} );
