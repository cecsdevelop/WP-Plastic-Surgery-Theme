document.addEventListener( 'DOMContentLoaded', function () {
	if ( ! document.querySelector( '.surgeon-image-field' ) && ! document.querySelector( '.surgeon-video-field' ) ) {
		return;
	}

	function openImagePicker( button ) {
		var field     = button.closest( '.surgeon-image-field' );
		var input     = field.querySelector( '.surgeon-image-field__input' );
		var preview   = field.querySelector( '.surgeon-image-field__preview' );
		var removeBtn = field.querySelector( '.surgeon-image-field__remove' );

		var frame = wp.media( {
			title: button.textContent,
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
	}

	function clearImage( button ) {
		var field   = button.closest( '.surgeon-image-field' );
		var input   = field.querySelector( '.surgeon-image-field__input' );
		var preview = field.querySelector( '.surgeon-image-field__preview' );

		input.value = '';
		preview.textContent = '';

		var placeholder = document.createElement( 'span' );
		placeholder.className = 'surgeon-image-field__placeholder';
		placeholder.textContent = preview.dataset.placeholder || '';
		preview.appendChild( placeholder );

		button.style.display = 'none';
	}

	function openVideoPicker( button ) {
		var field     = button.closest( '.surgeon-video-field' );
		var input     = field.querySelector( '.surgeon-video-field__input' );
		var filename  = field.querySelector( '.surgeon-video-field__filename' );
		var removeBtn = field.querySelector( '.surgeon-video-field__remove' );

		var frame = wp.media( {
			title: button.textContent,
			multiple: false,
			library: { type: 'video' },
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();

			input.value = attachment.id;
			filename.textContent = attachment.filename || attachment.title || '';

			removeBtn.style.display = '';
		} );

		frame.open();
	}

	function clearVideo( button ) {
		var field    = button.closest( '.surgeon-video-field' );
		var input    = field.querySelector( '.surgeon-video-field__input' );
		var filename = field.querySelector( '.surgeon-video-field__filename' );

		input.value = '';
		filename.textContent = filename.dataset.placeholder || '';

		button.style.display = 'none';
	}

	document.addEventListener( 'click', function ( event ) {
		if ( event.target.classList.contains( 'surgeon-image-field__select' ) ) {
			event.preventDefault();
			openImagePicker( event.target );
			return;
		}

		if ( event.target.classList.contains( 'surgeon-image-field__remove' ) ) {
			event.preventDefault();
			clearImage( event.target );
			return;
		}

		if ( event.target.classList.contains( 'surgeon-video-field__select' ) ) {
			event.preventDefault();
			openVideoPicker( event.target );
			return;
		}

		if ( event.target.classList.contains( 'surgeon-video-field__remove' ) ) {
			event.preventDefault();
			clearVideo( event.target );
		}
	} );
} );
