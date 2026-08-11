( function( blocks, element, blockEditor, components, i18n ) {
	var el = element.createElement;
	var Fragment = element.Fragment;
	var RichText = blockEditor.RichText;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var PanelColorSettings = blockEditor.PanelColorSettings;
	var PanelBody = components.PanelBody;
	var Button = components.Button;
	var TextControl = components.TextControl;
	var __ = i18n.__;

	function imagePicker( args ) {
		var label = args.label;
		var imageId = args.imageId;
		var imageUrl = args.imageUrl;
		var onSelect = args.onSelect;
		var onRemove = args.onRemove;

		return el(
			'div',
			{ className: 'truong-procedure-hero-editor__image-picker' },
			el( 'p', {}, label ),
			imageUrl ? el( 'img', {
				src: imageUrl,
				className: 'truong-procedure-hero-editor__image-preview'
			} ) : null,
			el(
				MediaUploadCheck,
				{},
				el( MediaUpload, {
					onSelect: onSelect,
					allowedTypes: [ 'image' ],
					value: imageId,
					render: function( obj ) {
						return el(
							Button,
							{ variant: 'secondary', onClick: obj.open },
							imageId ? __( 'Replace image', 'truong-group' ) : __( 'Select image', 'truong-group' )
						);
					}
				} )
			),
			imageId ? el(
				Button,
				{ variant: 'link', isDestructive: true, onClick: onRemove },
				__( 'Remove', 'truong-group' )
			) : null
		);
	}

	function ctaControls( args ) {
		var label = args.label;
		var textValue = args.textValue;
		var urlValue = args.urlValue;
		var bgColor = args.bgColor;
		var textColor = args.textColor;
		var onChangeText = args.onChangeText;
		var onChangeUrl = args.onChangeUrl;
		var onChangeBgColor = args.onChangeBgColor;
		var onChangeTextColor = args.onChangeTextColor;

		return el(
			'div',
			{ className: 'truong-procedure-hero-editor__cta' },
			el( 'p', { className: 'truong-procedure-hero-editor__cta-label' }, label ),
			el( TextControl, {
				label: __( 'Button text', 'truong-group' ),
				value: textValue,
				onChange: onChangeText
			} ),
			el( TextControl, {
				label: __( 'Button URL', 'truong-group' ),
				type: 'url',
				value: urlValue,
				onChange: onChangeUrl
			} ),
			el( PanelColorSettings, {
				title: __( 'Button colors', 'truong-group' ),
				initialOpen: false,
				colorSettings: [
					{
						value: bgColor,
						onChange: onChangeBgColor,
						label: __( 'Background color', 'truong-group' )
					},
					{
						value: textColor,
						onChange: onChangeTextColor,
						label: __( 'Text color', 'truong-group' )
					}
				]
			} )
		);
	}

	blocks.registerBlockType( 'truong-group/procedure-hero', {
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps( { className: 'truong-procedure-hero-editor' } );

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Hero Images', 'truong-group' ), initialOpen: true },
						el( 'p', { className: 'truong-procedure-hero-editor__notice' },
							__( 'Optional: override the featured image for performance. If no mobile image is set, the desktop image is used on mobile too.', 'truong-group' )
						),
						imagePicker( {
							label: __( 'Desktop image', 'truong-group' ),
							imageId: attributes.desktopImageId,
							imageUrl: attributes.desktopImageUrl,
							onSelect: function( media ) {
								setAttributes( { desktopImageId: media.id, desktopImageUrl: media.url } );
							},
							onRemove: function() {
								setAttributes( { desktopImageId: 0, desktopImageUrl: '' } );
							}
						} ),
						imagePicker( {
							label: __( 'Mobile image (optional)', 'truong-group' ),
							imageId: attributes.mobileImageId,
							imageUrl: attributes.mobileImageUrl,
							onSelect: function( media ) {
								setAttributes( { mobileImageId: media.id, mobileImageUrl: media.url } );
							},
							onRemove: function() {
								setAttributes( { mobileImageId: 0, mobileImageUrl: '' } );
							}
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Call to Action Buttons', 'truong-group' ), initialOpen: false },
						el( 'p', { className: 'truong-procedure-hero-editor__notice' },
							__( 'Optional, up to 2 buttons. A button only shows once it has both text and a URL. Hover defaults to 80% opacity.', 'truong-group' )
						),
						ctaControls( {
							label: __( 'CTA 1', 'truong-group' ),
							textValue: attributes.cta1Text,
							urlValue: attributes.cta1Url,
							bgColor: attributes.cta1BgColor,
							textColor: attributes.cta1TextColor,
							onChangeText: function( value ) {
								setAttributes( { cta1Text: value } );
							},
							onChangeUrl: function( value ) {
								setAttributes( { cta1Url: value } );
							},
							onChangeBgColor: function( value ) {
								setAttributes( { cta1BgColor: value || '' } );
							},
							onChangeTextColor: function( value ) {
								setAttributes( { cta1TextColor: value || '' } );
							}
						} ),
						ctaControls( {
							label: __( 'CTA 2', 'truong-group' ),
							textValue: attributes.cta2Text,
							urlValue: attributes.cta2Url,
							bgColor: attributes.cta2BgColor,
							textColor: attributes.cta2TextColor,
							onChangeText: function( value ) {
								setAttributes( { cta2Text: value } );
							},
							onChangeUrl: function( value ) {
								setAttributes( { cta2Url: value } );
							},
							onChangeBgColor: function( value ) {
								setAttributes( { cta2BgColor: value || '' } );
							},
							onChangeTextColor: function( value ) {
								setAttributes( { cta2TextColor: value || '' } );
							}
						} )
					)
				),
				el(
				'div',
				blockProps,
					el(
						'p',
						{ className: 'truong-procedure-hero-editor__notice' },
						__( 'By default the page title and featured image are used. Fill in the subtitle and excerpt below; set custom hero images in the sidebar if needed.', 'truong-group' )
					),
					el( 'input', {
						type: 'text',
						className: 'truong-procedure-hero-editor__subtitle',
						placeholder: __( 'Subtitle', 'truong-group' ),
						value: attributes.subtitle,
						onChange: function( event ) {
							setAttributes( { subtitle: event.target.value } );
						}
					} ),
					el( RichText, {
						tagName: 'div',
						className: 'truong-procedure-hero-editor__excerpt',
						placeholder: __( 'Excerpt…', 'truong-group' ),
						value: attributes.excerpt,
						onChange: function( value ) {
							setAttributes( { excerpt: value } );
						},
						allowedFormats: [ 'core/bold', 'core/italic', 'core/link' ]
					} )
				)
			);
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
