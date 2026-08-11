document.addEventListener( 'DOMContentLoaded', function () {
	var root = document.getElementById( 'faq-repeater' );

	if ( ! root ) {
		return;
	}

	var rows     = root.querySelector( '.faq-repeater__rows' );
	var template = document.getElementById( 'faq-repeater-template' );
	var addBtn   = root.querySelector( '.faq-repeater__add' );
	var index    = parseInt( root.dataset.index, 10 ) || 0;

	addBtn.addEventListener( 'click', function () {
		var html    = template.innerHTML.replace( /__INDEX__/g, String( index ) );
		var wrapper = document.createElement( 'div' );

		wrapper.innerHTML = html.trim();
		rows.appendChild( wrapper.firstElementChild );
		index++;
	} );

	rows.addEventListener( 'click', function ( event ) {
		if ( event.target.classList.contains( 'faq-repeater__remove' ) ) {
			event.target.closest( '.faq-repeater__row' ).remove();
		}
	} );
} );
