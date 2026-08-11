( function( blocks, element, blockEditor, components, data, ServerSideRender, i18n ) {
	var el = element.createElement;
	var useBlockProps = blockEditor.useBlockProps;
	var SelectControl = components.SelectControl;
	var Spinner = components.Spinner;
	var useSelect = data.useSelect;
	var __ = i18n.__;

	blocks.registerBlockType( 'wp-plastic-surgery/faq-list', {
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'truong-faq-list-editor' } );

			var faqPosts = useSelect( function( select ) {
				return select( 'core' ).getEntityRecords( 'postType', 'faq', {
					per_page: -1,
					status: 'publish',
					orderby: 'title',
					order: 'asc'
				} );
			}, [] );

			var options = [ { label: __( '— Select a FAQ set —', 'wp-plastic-surgery' ), value: 0 } ];

			if ( faqPosts ) {
				faqPosts.forEach( function( post ) {
					options.push( {
						label: post.title && post.title.rendered ? post.title.rendered : __( '(no title)', 'wp-plastic-surgery' ),
						value: post.id
					} );
				} );
			}

			return el(
				'div',
				blockProps,
				el( SelectControl, {
					label: __( 'FAQ set', 'wp-plastic-surgery' ),
					value: attributes.faqId,
					options: options,
					onChange: function( value ) {
						setAttributes( { faqId: parseInt( value, 10 ) || 0 } );
					}
				} ),
				undefined === faqPosts ? el( Spinner ) : null,
				attributes.faqId ? el( ServerSideRender, {
					block: 'wp-plastic-surgery/faq-list',
					attributes: { faqId: attributes.faqId }
				} ) : el(
					'p',
					{ className: 'truong-faq-list-editor__notice' },
					__( 'Choose a FAQ set above to preview it here.', 'wp-plastic-surgery' )
				)
			);
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.data, window.wp.serverSideRender, window.wp.i18n );
