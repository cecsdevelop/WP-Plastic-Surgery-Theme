document.addEventListener( 'DOMContentLoaded', function () {
	var modal = document.querySelector( '[data-video-modal]' );
	var items = document.querySelectorAll( '.sbbl-surgeon-videos__item' );

	if ( ! modal || ! items.length ) {
		return;
	}

	var stage    = modal.querySelector( '.sbbl-video-modal__stage' );
	var closeBtn = modal.querySelector( '.sbbl-video-modal__close' );

	function buildEmbed( item ) {
		var type = item.dataset.type;

		if ( 'youtube' === type ) {
			var iframe = document.createElement( 'iframe' );
			iframe.src = 'https://www.youtube-nocookie.com/embed/' + item.dataset.videoId + '?autoplay=1&rel=0';
			iframe.setAttribute( 'allow', 'autoplay; encrypted-media; picture-in-picture' );
			iframe.setAttribute( 'allowfullscreen', '' );
			iframe.setAttribute( 'frameborder', '0' );
			return iframe;
		}

		if ( 'vimeo' === type ) {
			var vimeoFrame = document.createElement( 'iframe' );
			vimeoFrame.src = 'https://player.vimeo.com/video/' + item.dataset.videoId + '?autoplay=1';
			vimeoFrame.setAttribute( 'allow', 'autoplay; fullscreen; picture-in-picture' );
			vimeoFrame.setAttribute( 'allowfullscreen', '' );
			vimeoFrame.setAttribute( 'frameborder', '0' );
			return vimeoFrame;
		}

		var video = document.createElement( 'video' );
		video.src = item.dataset.src;
		video.controls = true;
		video.autoplay = true;
		return video;
	}

	function open( item ) {
		stage.innerHTML = '';
		stage.appendChild( buildEmbed( item ) );
		modal.hidden = false;
	}

	function close() {
		modal.hidden = true;
		stage.innerHTML = '';
	}

	items.forEach( function ( item ) {
		item.addEventListener( 'click', function () {
			open( item );
		} );
	} );

	closeBtn.addEventListener( 'click', close );

	modal.addEventListener( 'click', function ( event ) {
		if ( event.target === modal ) {
			close();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( ! modal.hidden && 'Escape' === event.key ) {
			close();
		}
	} );
} );
