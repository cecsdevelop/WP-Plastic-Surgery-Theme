( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var fields = document.querySelectorAll( '.truong-page-url-field' );

		fields.forEach( function ( field ) {
			var select = field.querySelector( '.truong-page-url-field__select' );
			var input  = field.querySelector( '.truong-page-url-field__input' );

			select.addEventListener( 'change', function () {
				if ( '__custom__' === select.value ) {
					input.style.display = '';
					input.focus();
					return;
				}

				if ( '' === select.value ) {
					input.style.display = '';
					return;
				}

				input.value = select.value;
				input.style.display = 'none';
			} );
		} );
	} );
} )();
