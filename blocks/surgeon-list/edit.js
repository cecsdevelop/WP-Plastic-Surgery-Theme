( function( blocks, element, blockEditor, components, data, ServerSideRender, i18n ) {
	var el = element.createElement;
	var Fragment = element.Fragment;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var RadioControl = components.RadioControl;
	var SelectControl = components.SelectControl;
	var CheckboxControl = components.CheckboxControl;
	var Spinner = components.Spinner;
	var useSelect = data.useSelect;
	var __ = i18n.__;

	blocks.registerBlockType( 'truong-group/surgeon-list', {
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'truong-surgeon-list-editor' } );
			var excludedIds = attributes.excludedIds || [];

			var surgeons = useSelect( function( select ) {
				return select( 'core' ).getEntityRecords( 'postType', 'surgeon', {
					per_page: -1,
					status: 'publish',
					orderby: 'title',
					order: 'asc'
				} );
			}, [] );

			function setExcluded( id, isExcluded ) {
				if ( isExcluded ) {
					setAttributes( { excludedIds: excludedIds.concat( [ id ] ) } );
				} else {
					setAttributes( { excludedIds: excludedIds.filter( function( existing ) { return existing !== id; } ) } );
				}
			}

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Layout', 'truong-group' ), initialOpen: true },
						el( RadioControl, {
							label: __( 'Display as', 'truong-group' ),
							selected: attributes.layout,
							options: [
								{ label: __( 'List', 'truong-group' ), value: 'list' },
								{ label: __( 'Card Grid', 'truong-group' ), value: 'card' }
							],
							onChange: function( value ) {
								setAttributes( { layout: value } );
							}
						} ),
						'card' === attributes.layout ? el( SelectControl, {
							label: __( 'Columns', 'truong-group' ),
							value: String( attributes.columns ),
							options: [
								{ label: '2', value: '2' },
								{ label: '3', value: '3' },
								{ label: '4', value: '4' }
							],
							onChange: function( value ) {
								setAttributes( { columns: parseInt( value, 10 ) || 3 } );
							}
						} ) : null
					),
					el(
						PanelBody,
						{ title: __( 'Exclude Surgeons', 'truong-group' ), initialOpen: false },
						el( 'p', { className: 'truong-surgeon-list-editor__notice' },
							__( 'Check a surgeon to leave them out of this list.', 'truong-group' )
						),
						undefined === surgeons ? el( Spinner ) : null,
						surgeons ? surgeons.map( function( post ) {
							return el( CheckboxControl, {
								key: post.id,
								label: post.title && post.title.rendered ? post.title.rendered : __( '(no title)', 'truong-group' ),
								checked: excludedIds.indexOf( post.id ) !== -1,
								onChange: function( checked ) {
									setExcluded( post.id, checked );
								}
							} );
						} ) : null
					)
				),
				el(
					'div',
					blockProps,
					el( ServerSideRender, {
						block: 'truong-group/surgeon-list',
						attributes: attributes
					} )
				)
			);
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.data, window.wp.serverSideRender, window.wp.i18n );
