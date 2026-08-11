document.addEventListener( 'DOMContentLoaded', function () {
	var fields = document.querySelectorAll( '.surgeon-videos-field' );

	if ( ! fields.length ) {
		return;
	}

	function toggleRowFields( row ) {
		var type        = row.querySelector( '.surgeon-videos-field__type' ).value;
		var urlField    = row.querySelector( '.surgeon-videos-field__url-field' );
		var uploadField = row.querySelector( '.surgeon-videos-field__upload-field' );
		var isUpload    = 'upload' === type;

		urlField.style.display = isUpload ? 'none' : '';
		uploadField.style.display = isUpload ? '' : 'none';
	}

	fields.forEach( function ( field ) {
		var rows     = field.querySelector( '.surgeon-videos-field__rows' );
		var template = field.querySelector( '.surgeon-videos-field__template' );
		var addBtn   = field.querySelector( '.surgeon-videos-field__add' );
		var max      = parseInt( field.dataset.max, 10 ) || 9;
		var index    = rows.querySelectorAll( '.surgeon-videos-field__row' ).length;

		function updateAddButton() {
			var count = rows.querySelectorAll( '.surgeon-videos-field__row' ).length;
			addBtn.style.display = count >= max ? 'none' : '';
		}

		addBtn.addEventListener( 'click', function () {
			if ( rows.querySelectorAll( '.surgeon-videos-field__row' ).length >= max ) {
				return;
			}

			var html    = template.innerHTML.replace( /__INDEX__/g, String( index ) );
			var wrapper = document.createElement( 'div' );

			wrapper.innerHTML = html.trim();
			rows.appendChild( wrapper.firstElementChild );
			index++;

			updateAddButton();
		} );

		rows.addEventListener( 'click', function ( event ) {
			if ( event.target.classList.contains( 'surgeon-videos-field__remove' ) ) {
				event.preventDefault();
				event.target.closest( '.surgeon-videos-field__row' ).remove();
				updateAddButton();
			}
		} );

		rows.addEventListener( 'change', function ( event ) {
			if ( event.target.classList.contains( 'surgeon-videos-field__type' ) ) {
				toggleRowFields( event.target.closest( '.surgeon-videos-field__row' ) );
			}
		} );

		updateAddButton();
	} );
} );
