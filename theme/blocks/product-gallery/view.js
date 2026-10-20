// Product image carousel (see render.php). The track scrolls sideways and
// snaps to each image (CSS); this keeps the arrows and dots in sync with it.

document.querySelectorAll( '.product-gallery' ).forEach( ( gallery ) => {
	const track = gallery.querySelector( '.pg-track' );
	const prev = gallery.querySelector( '.pg-prev' );
	const next = gallery.querySelector( '.pg-next' );
	const dots = [ ...gallery.querySelectorAll( '.pg-dot' ) ];

	if ( ! prev ) {
		return; // Only one image.
	}

	const current = () => Math.round( track.scrollLeft / track.clientWidth );
	const goTo = ( index ) => track.scrollTo( { left: index * track.clientWidth, behavior: 'smooth' } );

	prev.addEventListener( 'click', () => goTo( current() - 1 ) );
	next.addEventListener( 'click', () => goTo( current() + 1 ) );
	dots.forEach( ( dot, index ) => dot.addEventListener( 'click', () => goTo( index ) ) );

	// Runs while scrolling (arrows, dots or swiping): update the dots and
	// grey out the arrow at either end.
	track.addEventListener( 'scroll', () => {
		const index = current();
		dots.forEach( ( dot, i ) => dot.classList.toggle( 'is-active', i === index ) );
		prev.disabled = index === 0;
		next.disabled = index === dots.length - 1;
	} );
} );
