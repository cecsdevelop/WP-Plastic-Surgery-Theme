document.addEventListener( 'DOMContentLoaded', function () {
	var fields = document.querySelectorAll( '.surgeon-gallery-field' );

	if ( ! fields.length ) {
		return;
	}

	function createItem( fieldName, removeLabel, attachment ) {
		var item   = document.createElement( 'div' );
		var img    = document.createElement( 'img' );
		var input  = document.createElement( 'input' );
		var remove = document.createElement( 'button' );

		item.className = 'surgeon-gallery-field__item';

		img.src = ( attachment.sizes && attachment.sizes.thumbnail ) ? attachment.sizes.thumbnail.url : attachment.url;
		img.alt = '';

		input.type = 'hidden';
		input.name = fieldName + '[]';
		input.value = attachment.id;

		remove.type = 'button';
		remove.className = 'button-link-delete surgeon-gallery-field__remove';
		remove.textContent = removeLabel;

		item.appendChild( img );
		item.appendChild( input );
		item.appendChild( remove );

		return item;
	}

	fields.forEach( function ( field ) {
		var grid        = field.querySelector( '.surgeon-gallery-field__grid' );
		var addBtn      = field.querySelector( '.surgeon-gallery-field__add' );
		var fieldName   = field.dataset.field;
		var removeLabel = field.dataset.removeLabel || 'Remove';
		var max         = parseInt( field.dataset.max, 10 ) || 9;

		function updateAddButton() {
			var count = grid.querySelectorAll( '.surgeon-gallery-field__item' ).length;
			addBtn.style.display = count >= max ? 'none' : '';
		}

		addBtn.addEventListener( 'click', function () {
			var count     = grid.querySelectorAll( '.surgeon-gallery-field__item' ).length;
			var remaining = max - count;

			if ( remaining <= 0 ) {
				return;
			}

			var frame = wp.media( {
				title: addBtn.textContent,
				multiple: true,
				library: { type: 'image' },
			} );

			frame.on( 'select', function () {
				var selection = frame.state().get( 'selection' ).toJSON();

				selection.slice( 0, remaining ).forEach( function ( attachment ) {
					grid.appendChild( createItem( fieldName, removeLabel, attachment ) );
				} );

				updateAddButton();
			} );

			frame.open();
		} );

		grid.addEventListener( 'click', function ( event ) {
			if ( event.target.classList.contains( 'surgeon-gallery-field__remove' ) ) {
				event.preventDefault();
				event.target.closest( '.surgeon-gallery-field__item' ).remove();
				updateAddButton();
			}
		} );

		updateAddButton();
	} );
} );
