// Image field on the product category screens (see inc/category-image.php).
// "Select image" opens WordPress's media picker; the chosen image's ID goes
// into the hidden input, which is saved with the category.

document.querySelectorAll( '.gvs-term-image' ).forEach( ( field ) => {
	const input = field.querySelector( 'input' );
	const preview = field.querySelector( '.gvs-term-image-preview' );
	const remove = field.querySelector( '.gvs-term-image-remove' );
	let picker;

	field.querySelector( '.gvs-term-image-select' ).addEventListener( 'click', () => {
		if ( ! picker ) {
			picker = wp.media( { title: 'Category image', library: { type: 'image' }, multiple: false } );
			picker.on( 'select', () => {
				const image = picker.state().get( 'selection' ).first().toJSON();
				input.value = image.id;
				preview.innerHTML = '<img src="' + ( image.sizes.medium || image.sizes.full ).url + '" alt="">';
				remove.hidden = false;
			} );
		}
		picker.open();
	} );

	remove.addEventListener( 'click', () => {
		input.value = '';
		preview.innerHTML = '';
		remove.hidden = true;
	} );
} );
