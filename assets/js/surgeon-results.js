document.addEventListener( 'DOMContentLoaded', function () {
	var section = document.querySelector( '[data-surgeon-results]' );

	if ( ! section || 'undefined' === typeof window.surgeonResults ) {
		return;
	}

	var grid    = section.querySelector( '.sbbl-surgeon-results__grid' );
	var loadBtn = section.querySelector( '.sbbl-surgeon-results__load-more' );

	if ( ! grid || ! loadBtn ) {
		return;
	}

	loadBtn.addEventListener( 'click', function () {
		loadBtn.disabled = true;

		var body = new URLSearchParams();
		body.set( 'action', 'surgeon_load_more_results' );
		body.set( 'nonce', window.surgeonResults.nonce );
		body.set( 'post_id', section.dataset.postId );
		body.set( 'offset', section.dataset.offset );

		fetch( window.surgeonResults.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString(),
		} )
			.then( function ( response ) {
				return response.text();
			} )
			.then( function ( html ) {
				var wrapper = document.createElement( 'div' );
				wrapper.innerHTML = html;

				var marker  = wrapper.querySelector( '[data-has-more]' );
				var hasMore = !! marker && '1' === marker.dataset.hasMore;

				if ( marker ) {
					marker.remove();
				}

				while ( wrapper.firstChild ) {
					grid.appendChild( wrapper.firstChild );
				}

				section.dataset.offset = String( parseInt( section.dataset.offset, 10 ) + 12 );
				loadBtn.disabled = false;

				if ( ! hasMore ) {
					loadBtn.remove();
				}
			} )
			.catch( function () {
				loadBtn.disabled = false;
			} );
	} );
} );
