document.addEventListener( 'DOMContentLoaded', function () {
	var lightbox = document.querySelector( '[data-lightbox]' );
	var items    = document.querySelectorAll( '.sbbl-surgeon-history__gallery-item' );

	if ( ! lightbox || ! items.length ) {
		return;
	}

	var image    = lightbox.querySelector( '.sbbl-lightbox__image' );
	var closeBtn = lightbox.querySelector( '.sbbl-lightbox__close' );
	var prevBtn  = lightbox.querySelector( '.sbbl-lightbox__prev' );
	var nextBtn  = lightbox.querySelector( '.sbbl-lightbox__next' );
	var current  = 0;

	function show( index ) {
		if ( index < 0 ) {
			index = items.length - 1;
		} else if ( index >= items.length ) {
			index = 0;
		}

		current = index;
		image.src = items[ current ].dataset.full;
		lightbox.hidden = false;
	}

	function hide() {
		lightbox.hidden = true;
		image.src = '';
	}

	items.forEach( function ( item, index ) {
		item.addEventListener( 'click', function () {
			show( index );
		} );
	} );

	closeBtn.addEventListener( 'click', hide );
	prevBtn.addEventListener( 'click', function () {
		show( current - 1 );
	} );
	nextBtn.addEventListener( 'click', function () {
		show( current + 1 );
	} );

	lightbox.addEventListener( 'click', function ( event ) {
		if ( event.target === lightbox ) {
			hide();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( lightbox.hidden ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			hide();
		} else if ( 'ArrowLeft' === event.key ) {
			show( current - 1 );
		} else if ( 'ArrowRight' === event.key ) {
			show( current + 1 );
		}
	} );
} );
