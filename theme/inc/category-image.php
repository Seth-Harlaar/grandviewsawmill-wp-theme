<?php
/**
 * An image for each product category. Categories have no built-in image,
 * so the chosen Media Library image's ID is stored as term meta
 * ("image_id"), the category version of post meta.
 */

// The field on Products -> Product Categories -> "Add New Category" form.
add_action( 'product_category_add_form_fields', function () {
	?>
	<div class="form-field">
		<label>Image</label>
		<?php gvs_category_image_field( 0 ); ?>
	</div>
	<?php
} );

// The field on a category's Edit screen (a table row there).
add_action( 'product_category_edit_form_fields', function ( $term ) {
	?>
	<tr class="form-field">
		<th scope="row"><label>Image</label></th>
		<td><?php gvs_category_image_field( (int) get_term_meta( $term->term_id, 'image_id', true ) ); ?></td>
	</tr>
	<?php
} );

// Preview + buttons. category-image.js opens the media picker and fills
// in the hidden input with the chosen image's ID.
function gvs_category_image_field( $image_id ) {
	wp_nonce_field( 'gvs_category_image', 'gvs_category_image_nonce' );
	?>
	<div class="gvs-term-image">
		<input type="hidden" name="gvs_image_id" value="<?php echo $image_id ?: ''; ?>">
		<div class="gvs-term-image-preview"><?php echo $image_id ? wp_get_attachment_image( $image_id, 'medium' ) : ''; ?></div>
		<p>
			<button type="button" class="button gvs-term-image-select">Select image</button>
			<button type="button" class="button-link gvs-term-image-remove"<?php echo $image_id ? '' : ' hidden'; ?>>Remove</button>
		</p>
	</div>
	<?php
}

// Save when a category is added or edited.
add_action( 'created_product_category', 'gvs_save_category_image' );
add_action( 'edited_product_category', 'gvs_save_category_image' );

function gvs_save_category_image( $term_id ) {
	if ( ! isset( $_POST['gvs_category_image_nonce'] ) || ! wp_verify_nonce( $_POST['gvs_category_image_nonce'], 'gvs_category_image' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	$image_id = absint( $_POST['gvs_image_id'] ?? 0 );
	if ( $image_id ) {
		update_term_meta( $term_id, 'image_id', $image_id );
	} else {
		delete_term_meta( $term_id, 'image_id' );
	}
}

// Load the media picker and our script, only on the product category screens.
add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();
	if ( ! $screen || 'product_category' !== $screen->taxonomy ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'grandview-category-image', get_theme_file_uri( 'assets/js/category-image.js' ), array(), filemtime( get_theme_file_path( 'assets/js/category-image.js' ) ), true );
} );
