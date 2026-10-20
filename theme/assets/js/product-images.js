// Images box on the product edit screen (see inc/product-images.php).
// "Add images" opens WordPress's media picker. The hidden input holds the
// image IDs in order ("12,15,9") and is saved with the product.

document.querySelectorAll( '.gvs-product-images' ).forEach( ( box ) => {
	const input = box.querySelector( 'input' );
	const list = box.querySelector( '.gvs-image-list' );
	let picker;

	// Rewrite the hidden input from the thumbnails' current order.
	const sync = () => {
		input.value = [ ...list.children ].map( ( li ) => li.dataset.id ).join( ',' );
	};

	box.querySelector( '.gvs-image-add' ).addEventListener( 'click', () => {
		if ( ! picker ) {
			picker = wp.media( { title: 'Product images', library: { type: 'image' }, multiple: 'add' } );
			picker.on( 'select', () => {
				picker.state().get( 'selection' ).each( ( attachment ) => {
					const image = attachment.toJSON();
					if ( list.querySelector( `[data-id="${ image.id }"]` ) ) {
						return; // Already added.
					}
					list.insertAdjacentHTML( 'beforeend',
						`<li data-id="${ image.id }"><img src="${ ( image.sizes.thumbnail || image.sizes.full ).url }" alt="">` +
						'<button type="button" class="gvs-image-remove" aria-label="Remove image">×</button></li>' );
				} );
				picker.state().get( 'selection' ).reset(); // Start empty next time.
				sync();
			} );
		}
		picker.open();
	} );

	list.addEventListener( 'click', ( event ) => {
		if ( event.target.classList.contains( 'gvs-image-remove' ) ) {
			event.target.closest( 'li' ).remove();
			sync();
		}
	} );

	// Drag to reorder, using the jQuery UI Sortable that ships with WordPress.
	jQuery( list ).sortable( { update: sync } );
} );
