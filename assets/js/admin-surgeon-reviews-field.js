document.addEventListener( 'DOMContentLoaded', function () {
	var field = document.querySelector( '.surgeon-reviews-field' );

	if ( ! field ) {
		return;
	}

	var rows     = field.querySelector( '.surgeon-reviews-field__rows' );
	var template = field.querySelector( '.surgeon-reviews-field__template' );
	var addBtn   = field.querySelector( '.surgeon-reviews-field__add' );
	var index    = rows.querySelectorAll( '.surgeon-reviews-field__row' ).length;

	addBtn.addEventListener( 'click', function () {
		var html    = template.innerHTML.replace( /__INDEX__/g, String( index ) );
		var wrapper = document.createElement( 'div' );

		wrapper.innerHTML = html.trim();
		rows.appendChild( wrapper.firstElementChild );
		index++;
	} );

	rows.addEventListener( 'click', function ( event ) {
		if ( event.target.classList.contains( 'surgeon-reviews-field__remove' ) ) {
			event.preventDefault();
			event.target.closest( '.surgeon-reviews-field__row' ).remove();
		}
	} );
} );
