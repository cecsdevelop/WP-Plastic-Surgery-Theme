document.addEventListener( 'DOMContentLoaded', function () {
	var root = document.getElementById( 'truong-scripts-repeater' );

	if ( ! root ) {
		return;
	}

	var rows     = root.querySelector( '.truong-scripts-repeater__rows' );
	var template = document.getElementById( 'truong-scripts-repeater-template' );
	var addBtn   = root.querySelector( '.truong-scripts-repeater__add' );
	var index    = parseInt( root.dataset.index, 10 ) || 0;

	addBtn.addEventListener( 'click', function () {
		var html    = template.innerHTML.replace( /__INDEX__/g, String( index ) );
		var wrapper = document.createElement( 'div' );

		wrapper.innerHTML = html.trim();
		rows.appendChild( wrapper.firstElementChild );
		index++;
	} );

	rows.addEventListener( 'click', function ( event ) {
		if ( event.target.classList.contains( 'truong-scripts-repeater__remove' ) ) {
			event.target.closest( '.truong-scripts-repeater__row' ).remove();
		}
	} );
} );
