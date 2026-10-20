<?php
/**
 * Images for each product. Stored as post meta ("images"): a list of
 * Media Library image IDs, in display order. The first one is used on cards.
 */

// A product's image IDs, in order.
function gvs_product_images( $post_id ) {
	$ids = get_post_meta( $post_id, 'images', true );
	return is_array( $ids ) ? array_map( 'intval', $ids ) : array();
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'product_images', 'Images', 'gvs_product_images_box', 'product' );
} );

// Thumbnails + buttons. product-images.js opens the media picker and keeps
// the hidden input up to date with the image IDs, e.g. "12,15,9".
function gvs_product_images_box( $post ) {
	$ids = gvs_product_images( $post->ID );
	?>
	<div class="gvs-product-images">
		<input type="hidden" name="gvs_images" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
		<ul class="gvs-image-list">
			<?php foreach ( $ids as $id ) : ?>
				<li data-id="<?php echo $id; ?>">
					<?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>
					<button type="button" class="gvs-image-remove" aria-label="Remove image">×</button>
				</li>
			<?php endforeach; ?>
		</ul>
		<p>
			<button type="button" class="button gvs-image-add">Add images</button>
			<span class="description">The first image is shown on product cards. Drag to reorder.</span>
		</p>
	</div>
	<style>
		.gvs-image-list { display: flex; flex-wrap: wrap; gap: 8px; margin: 0; }
		.gvs-image-list li { position: relative; margin: 0; cursor: move; }
		.gvs-image-list img { display: block; width: 100px; height: 100px; object-fit: cover; }
		.gvs-image-list li:first-child img { outline: 3px solid #2271b1; }
		.gvs-image-remove { position: absolute; top: 2px; right: 2px; border: 0; background: #000a; color: #fff; cursor: pointer; }
	</style>
	<?php
}

// Saved along with the rest of the product (the nonce comes from the Prices box).
add_action( 'save_post_product', function ( $post_id ) {
	if ( ! isset( $_POST['gvs_product_nonce'] ) || ! wp_verify_nonce( $_POST['gvs_product_nonce'], 'gvs_save_product' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$ids = array_filter( array_map( 'absint', explode( ',', $_POST['gvs_images'] ?? '' ) ) );
	update_post_meta( $post_id, 'images', array_values( $ids ) );
} );

// Load the media picker, drag-to-reorder and our script on the product edit screen only.
add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base || 'product' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'grandview-product-images', get_theme_file_uri( 'assets/js/product-images.js' ), array( 'jquery-ui-sortable' ), filemtime( get_theme_file_path( 'assets/js/product-images.js' ) ), true );
} );
