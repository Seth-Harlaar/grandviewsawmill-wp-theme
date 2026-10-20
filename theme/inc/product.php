<?php
/**
 * Products custom post type.
 * Title and description use WordPress's built-in title and editor.
 * Prices are stored as post meta (an extra field): a list of rows, each
 * with a title, description, price, unit and quantity.
 */

add_action( 'init', function () {
	// Adds a "Products" section to wp-admin.
	register_post_type( 'product', array(
		'label'       => 'Products',
		'public'      => true,
		'has_archive' => false, // No /product/ list page; products are listed per category.
		'menu_icon'   => 'dashicons-cart',
		'supports'    => array( 'title', 'editor' ),
	) );

	// Product categories, attached to products. Each one has a Name and
	// Description. Manage them under Products -> Product Categories;
	// tick them on each product's edit screen.
	register_taxonomy( 'product_category', 'product', array(
		'label'             => 'Product Categories',
		'hierarchical'      => true, // Checkboxes and parent categories, like post Categories.
		'show_admin_column' => true, // Adds a Categories column to the Products list.
		'rewrite'           => array( 'slug' => 'product-category' ),
	) );
} );

// "100" -> "$100", "8.5" -> "$8.50". Used by the product card and detail blocks.
function gvs_format_price( $price ) {
	$price = (float) $price;
	return '$' . number_format_i18n( $price, floor( $price ) == $price ? 0 : 2 );
}

// Cuts text to $max characters with "…". Used for card titles.
function gvs_truncate( $text, $max = 40 ) {
	return mb_strlen( $text ) > $max ? rtrim( mb_substr( $text, 0, $max - 1 ) ) . '…' : $text;
}

// A product's price rows, cheapest first. Each row is an array with
// title, desc, price, unit and quantity (all strings; blank = not set).
function gvs_product_prices( $post_id ) {
	$rows = get_post_meta( $post_id, 'prices', true );
	$rows = is_array( $rows ) ? $rows : array();
	usort( $rows, function ( $a, $b ) {
		return (float) $a['price'] <=> (float) $b['price'];
	} );
	return $rows;
}

// "12 in stock", "Sold out", or "" when the quantity is blank.
function gvs_format_stock( $quantity ) {
	if ( '' === $quantity ) {
		return '';
	}
	return (int) $quantity > 0 ? (int) $quantity . ' in stock' : 'Sold out';
}

// Units suggested in the Unit dropdown on the product edit screen.
function gvs_unit_suggestions() {
	return array( 'bd ft', 'linear ft', 'sq ft', 'each', 'bundle', 'cord' );
}

add_action( 'add_meta_boxes', function () {
	// Adds a "Prices" box to the product edit screen.
	add_meta_box( 'product_prices', 'Prices', 'gvs_product_prices_box', 'product' );
} );

function gvs_product_prices_box( $post ) {
	wp_nonce_field( 'gvs_save_product', 'gvs_product_nonce' );
	$rows = gvs_product_prices( $post->ID );
	?>
	<table class="widefat gvs-prices">
		<thead>
			<tr><th>Title</th><th>Description</th><th>Price</th><th>Unit (e.g. bd ft, each)</th><th>Quantity</th><th></th></tr>
		</thead>
		<tbody>
			<?php foreach ( $rows as $i => $row ) {
				gvs_price_row( $i, $row );
			} ?>
		</tbody>
	</table>
	<p><button type="button" class="button gvs-add-price">Add row</button></p>

	<!-- Suggestions for the Unit inputs (list="gvs-units"). Anything else can still be typed. -->
	<datalist id="gvs-units">
		<?php foreach ( gvs_unit_suggestions() as $unit ) : ?>
			<option value="<?php echo esc_attr( $unit ); ?>">
		<?php endforeach; ?>
	</datalist>

	<!-- Blank row copied by "Add row". __i__ is swapped for a new number. -->
	<template id="gvs-price-row"><?php gvs_price_row( '__i__' ); ?></template>

	<script>
		( function () {
			var tbody = document.querySelector( '.gvs-prices tbody' );
			var template = document.getElementById( 'gvs-price-row' ).innerHTML;
			var next = <?php echo (int) count( $rows ); ?>;

			document.querySelector( '.gvs-add-price' ).addEventListener( 'click', function () {
				tbody.insertAdjacentHTML( 'beforeend', template.replace( /__i__/g, next++ ) );
			} );
			tbody.addEventListener( 'click', function ( event ) {
				if ( event.target.classList.contains( 'gvs-remove-price' ) ) {
					event.target.closest( 'tr' ).remove();
				}
			} );
		} )();
	</script>
	<?php
}

// One editable row. Inputs are named prices[0][title], prices[0][price], ...
// so PHP receives them as a list of rows in $_POST['prices'].
function gvs_price_row( $i, $row = array() ) {
	$value = function ( $key ) use ( $row ) {
		return esc_attr( $row[ $key ] ?? '' );
	};
	?>
	<tr>
		<td><input type="text" name="prices[<?php echo $i; ?>][title]" value="<?php echo $value( 'title' ); ?>"></td>
		<td><input type="text" name="prices[<?php echo $i; ?>][desc]" value="<?php echo $value( 'desc' ); ?>"></td>
		<td><input type="number" step="0.01" name="prices[<?php echo $i; ?>][price]" value="<?php echo $value( 'price' ); ?>"></td>
		<td><input type="text" name="prices[<?php echo $i; ?>][unit]" list="gvs-units" value="<?php echo $value( 'unit' ); ?>"></td>
		<td><input type="number" min="0" name="prices[<?php echo $i; ?>][quantity]" value="<?php echo $value( 'quantity' ); ?>"></td>
		<td><button type="button" class="button-link gvs-remove-price" aria-label="Remove row">×</button></td>
	</tr>
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

	$rows = array();
	foreach ( (array) wp_unslash( $_POST['prices'] ?? array() ) as $row ) {
		$row = array(
			'title'    => sanitize_text_field( $row['title'] ?? '' ),
			'desc'     => sanitize_text_field( $row['desc'] ?? '' ),
			'price'    => sanitize_text_field( $row['price'] ?? '' ),
			'unit'     => sanitize_text_field( $row['unit'] ?? '' ),
			'quantity' => '' === ( $row['quantity'] ?? '' ) ? '' : (string) absint( $row['quantity'] ),
		);
		// Skip rows left completely empty.
		if ( implode( '', $row ) !== '' ) {
			$rows[] = $row;
		}
	}
	update_post_meta( $post_id, 'prices', $rows );

	// Old single-price fields, replaced by the rows above.
	delete_post_meta( $post_id, 'price' );
	delete_post_meta( $post_id, 'unit' );
	delete_post_meta( $post_id, 'quantity' );
} );
