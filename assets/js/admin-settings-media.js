( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var fields = document.querySelectorAll( '.truong-media-field' );

		fields.forEach( function ( field ) {
			var selectBtn = field.querySelector( '.truong-media-field__select' );
			var removeBtn = field.querySelector( '.truong-media-field__remove' );
			var input     = field.querySelector( '.truong-media-field__input' );
			var preview   = field.querySelector( '.truong-media-field__preview' );
			var frame;

			selectBtn.addEventListener( 'click', function ( event ) {
				event.preventDefault();

				if ( frame ) {
					frame.open();
					return;
				}

				frame = wp.media( {
					title: selectBtn.textContent,
					multiple: false,
					library: { type: 'image' },
				} );

				frame.on( 'select', function () {
					var attachment = frame.state().get( 'selection' ).first().toJSON();
					var url        = ( attachment.sizes && attachment.sizes.medium ) ? attachment.sizes.medium.url : attachment.url;

					input.value = attachment.id;
					preview.textContent = '';

					var img = document.createElement( 'img' );
					img.src = url;
					img.alt = '';
					preview.appendChild( img );

					removeBtn.style.display = '';
				} );

				frame.open();
			} );

			removeBtn.addEventListener( 'click', function ( event ) {
				event.preventDefault();

				input.value = '';
				preview.textContent = '';

				var placeholder = document.createElement( 'span' );
				placeholder.className = 'truong-media-field__placeholder';
				placeholder.textContent = preview.dataset.placeholder || '';
				preview.appendChild( placeholder );

				removeBtn.style.display = 'none';
			} );
		} );
	} );
} )();
