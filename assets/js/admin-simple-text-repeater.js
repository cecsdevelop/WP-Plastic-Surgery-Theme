document.addEventListener( 'DOMContentLoaded', function () {
	var roots = document.querySelectorAll( '.simple-text-repeater' );

	roots.forEach( function ( root ) {
		var rows     = root.querySelector( '.simple-text-repeater__rows' );
		var template = root.querySelector( '.simple-text-repeater__template' );
		var addBtn   = root.querySelector( '.simple-text-repeater__add' );
		var index    = parseInt( root.dataset.index, 10 ) || 0;

		addBtn.addEventListener( 'click', function () {
			var html    = template.innerHTML.replace( /__INDEX__/g, String( index ) );
			var wrapper = document.createElement( 'div' );

			wrapper.innerHTML = html.trim();
			rows.appendChild( wrapper.firstElementChild );
			index++;
		} );

		rows.addEventListener( 'click', function ( event ) {
			if ( event.target.classList.contains( 'simple-text-repeater__remove' ) ) {
				event.target.closest( '.simple-text-repeater__row' ).remove();
			}
		} );
	} );
} );
