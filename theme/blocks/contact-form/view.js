// Sends the contact form in the background and swaps in the success box,
// so the page doesn't reload. Without JavaScript the form still posts normally.

document.querySelectorAll( '.contact-form' ).forEach( ( form ) => {
	form.addEventListener( 'submit', async ( event ) => {
		event.preventDefault();

		const button = form.querySelector( '.contact-submit' );
		button.disabled = true;

		const data = new FormData( form );
		data.append( 'ajax', '1' );

		let ok = false;
		try {
			// getAttribute, because form.action returns the hidden input named "action".
			const response = await fetch( form.getAttribute( 'action' ), { method: 'POST', body: data } );
			ok = ( await response.json() ).success;
		} catch ( e ) {}

		if ( ok ) {
			const success = document.createElement( 'div' );
			success.className = 'form-success';
			success.innerHTML = '<div class="label">Message received</div>Thanks, we\'ll get back to you as soon as we can.';
			form.replaceWith( success );
			return;
		}

		let error = form.querySelector( '.form-err' );
		if ( ! error ) {
			error = document.createElement( 'div' );
			error.className = 'form-err';
			form.prepend( error );
		}
		error.textContent = 'Something went wrong. Please check your name, email and details, then try again.';
		button.disabled = false;
	} );
} );
