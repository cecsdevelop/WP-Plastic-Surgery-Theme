<?php
/**
 * Theme settings screen (Header, CTA, Tracking, Social, Scripts).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SettingsController extends BaseController {

	private const MENU_SLUG       = 'wp-plastic-surgery-settings';
	private const HEADER_GROUP    = 'truong_group_header_group';
	private const HEADER_OPTION   = 'truong_group_header_settings';
	private const CTA_GROUP       = 'truong_group_cta_group';
	private const CTA_OPTION      = 'truong_group_cta_settings';
	private const TRACKING_GROUP  = 'truong_group_tracking_group';
	private const TRACKING_OPTION = 'truong_group_tracking_settings';
	private const SOCIAL_GROUP    = 'truong_group_social_group';
	private const SOCIAL_OPTION   = 'truong_group_social_settings';
	private const SCRIPTS_GROUP   = 'truong_group_scripts_group';
	private const SCRIPTS_OPTION  = 'truong_group_custom_scripts';
	private const FOOTER_GROUP    = 'truong_group_footer_group';
	private const FOOTER_OPTION   = 'truong_group_footer_settings';

	/**
	 * Fixed set of social networks exposed as URL fields.
	 *
	 * @var array<string, string>
	 */
	private const SOCIAL_NETWORKS = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'x'         => 'X (Twitter)',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
	);

	/**
	 * Valid injection points for custom scripts.
	 */
	private const SCRIPT_LOCATIONS = array( 'head', 'body_open', 'footer' );

	/**
	 * Hook suffix of the settings page, used to scope asset loading.
	 */
	private string $page_hook = '';

	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		add_action( 'wp_head', array( $this, 'output_favicon' ), 1 );
		add_action( 'wp_head', array( $this, 'output_theme_colors' ), 1 );
		add_action( 'wp_head', array( $this, 'output_tracking_head' ), 5 );
		add_action( 'wp_head', array( $this, 'output_custom_scripts_head' ), 20 );
		add_action( 'wp_body_open', array( $this, 'output_tracking_body' ) );
		add_action( 'wp_body_open', array( $this, 'output_custom_scripts_body_open' ) );
		add_action( 'wp_footer', array( $this, 'output_custom_scripts_footer' ) );
	}

	/**
	 * Header settings, merged with defaults.
	 *
	 * @return array{logo_id: int, sticky_logo_id: int, favicon_id: int, nav_menu_id: int, bg_color: string, menu_font_color: string}
	 */
	public static function get_header_settings(): array {
		$defaults = array(
			'logo_id'         => 0,
			'sticky_logo_id'  => 0,
			'favicon_id'      => 0,
			'nav_menu_id'     => 0,
			'bg_color'        => '',
			'menu_font_color' => '',
		);

		return wp_parse_args( (array) get_option( self::HEADER_OPTION, array() ), $defaults );
	}

	/**
	 * CTA settings, merged with defaults.
	 *
	 * @return array{text: string, url: string}
	 */
	/**
	 * Footer settings, merged with defaults.
	 *
	 * @return array{logo_id: int, nav_menu_id: int, phone: string, email: string, address: string, copyright: string, bg_color: string, text_color: string}
	 */
	public static function get_footer_settings(): array {
		$defaults = array(
			'logo_id'    => 0,
			'nav_menu_id' => 0,
			'phone'      => '',
			'email'      => '',
			'address'    => '',
			'copyright'  => '',
			'bg_color'   => '',
			'text_color' => '',
		);

		return wp_parse_args( (array) get_option( self::FOOTER_OPTION, array() ), $defaults );
	}

	/**
	 * Footer copyright text with the {year} token resolved.
	 */
	public static function get_footer_copyright(): string {
		$settings = self::get_footer_settings();

		return str_replace( '{year}', gmdate( 'Y' ), $settings['copyright'] );
	}

	public static function get_cta_settings(): array {
		$defaults = array(
			'text'        => '',
			'url'         => '',
			'bg_color'    => '',
			'text_color'  => '',
			'gallery_url' => '',
		);

		return wp_parse_args( (array) get_option( self::CTA_OPTION, array() ), $defaults );
	}

	/**
	 * Tracking settings, merged with defaults.
	 *
	 * @return array{ga4_id: string, gtm_id: string}
	 */
	public static function get_tracking_settings(): array {
		$defaults = array(
			'ga4_id' => '',
			'gtm_id' => '',
		);

		return wp_parse_args( (array) get_option( self::TRACKING_OPTION, array() ), $defaults );
	}

	/**
	 * Social network URLs, merged with defaults.
	 *
	 * @return array<string, string>
	 */
	public static function get_social_settings(): array {
		$defaults = array_fill_keys( array_keys( self::SOCIAL_NETWORKS ), '' );

		return wp_parse_args( (array) get_option( self::SOCIAL_OPTION, array() ), $defaults );
	}

	/**
	 * Custom script rows.
	 *
	 * @return array<int, array{label: string, code: string, location: string, enabled: bool}>
	 */
	public static function get_custom_scripts(): array {
		$items = (array) get_option( self::SCRIPTS_OPTION, array() );

		return array_values( array_filter( $items, 'is_array' ) );
	}

	public function register_menu(): void {
		$this->page_hook = (string) add_menu_page(
			__( 'Theme Settings', 'wp-plastic-surgery' ),
			__( 'Theme Settings', 'wp-plastic-surgery' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render_page' ),
			'dashicons-admin-customizer',
			61
		);
	}

	/**
	 * Tabs available on the settings page, keyed by slug.
	 *
	 * @return array<string, string>
	 */
	private function tabs(): array {
		return array(
			'header'   => __( 'Header', 'wp-plastic-surgery' ),
			'footer'   => __( 'Footer', 'wp-plastic-surgery' ),
			'cta'      => __( 'CTA', 'wp-plastic-surgery' ),
			'tracking' => __( 'Tracking', 'wp-plastic-surgery' ),
			'social'   => __( 'Social Media', 'wp-plastic-surgery' ),
			'scripts'  => __( 'Scripts', 'wp-plastic-surgery' ),
		);
	}

	private function group_for_tab( string $tab ): string {
		$map = array(
			'header'   => self::HEADER_GROUP,
			'footer'   => self::FOOTER_GROUP,
			'cta'      => self::CTA_GROUP,
			'tracking' => self::TRACKING_GROUP,
			'social'   => self::SOCIAL_GROUP,
			'scripts'  => self::SCRIPTS_GROUP,
		);

		return $map[ $tab ] ?? self::HEADER_GROUP;
	}

	public function register_settings(): void {
		$this->register_header_settings();
		$this->register_footer_settings();
		$this->register_cta_settings();
		$this->register_tracking_settings();
		$this->register_social_settings();
		$this->register_scripts_settings();
	}

	private function register_header_settings(): void {
		register_setting(
			self::HEADER_GROUP,
			self::HEADER_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_header_settings' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_header_section', '', '__return_false', self::MENU_SLUG . '_header' );

		add_settings_field(
			'logo_id',
			__( 'Logo', 'wp-plastic-surgery' ),
			array( $this, 'render_media_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option' => self::HEADER_OPTION,
				'key'    => 'logo_id',
				'label'  => __( 'Select logo', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'sticky_logo_id',
			__( 'Sticky header logo', 'wp-plastic-surgery' ),
			array( $this, 'render_media_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option' => self::HEADER_OPTION,
				'key'    => 'sticky_logo_id',
				'label'  => __( 'Select sticky logo', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'favicon_id',
			__( 'Favicon', 'wp-plastic-surgery' ),
			array( $this, 'render_media_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option' => self::HEADER_OPTION,
				'key'    => 'favicon_id',
				'label'  => __( 'Select favicon', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'nav_menu_id',
			__( 'Navigation menu', 'wp-plastic-surgery' ),
			array( $this, 'render_nav_menu_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option' => self::HEADER_OPTION,
				'key'    => 'nav_menu_id',
			)
		);

		add_settings_field(
			'bg_color',
			__( 'Header background color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option'  => self::HEADER_OPTION,
				'key'     => 'bg_color',
				'default' => '#38465e',
			)
		);

		add_settings_field(
			'menu_font_color',
			__( 'Menu & submenu font color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_header',
			'truong_group_header_section',
			array(
				'option'      => self::HEADER_OPTION,
				'key'         => 'menu_font_color',
				'default'     => '#ffffff',
				'description' => __( 'Applies to the top-level menu and all nested sub-menu items.', 'wp-plastic-surgery' ),
			)
		);
	}

	private function register_footer_settings(): void {
		register_setting(
			self::FOOTER_GROUP,
			self::FOOTER_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_footer_settings' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_footer_section', '', '__return_false', self::MENU_SLUG . '_footer' );

		add_settings_field(
			'logo_id',
			__( 'Footer logo', 'wp-plastic-surgery' ),
			array( $this, 'render_media_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'      => self::FOOTER_OPTION,
				'key'         => 'logo_id',
				'label'       => __( 'Select footer logo', 'wp-plastic-surgery' ),
				'description' => __( 'Optional. Falls back to the Header logo when empty.', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'nav_menu_id',
			__( 'Footer menu', 'wp-plastic-surgery' ),
			array( $this, 'render_nav_menu_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option' => self::FOOTER_OPTION,
				'key'    => 'nav_menu_id',
			)
		);

		add_settings_field(
			'phone',
			__( 'Phone', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'      => self::FOOTER_OPTION,
				'key'         => 'phone',
				'type'        => 'tel',
				'placeholder' => '+1 (555) 555-5555',
			)
		);

		add_settings_field(
			'email',
			__( 'Email', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'      => self::FOOTER_OPTION,
				'key'         => 'email',
				'type'        => 'email',
				'placeholder' => 'info@example.com',
			)
		);

		add_settings_field(
			'address',
			__( 'Address', 'wp-plastic-surgery' ),
			array( $this, 'render_textarea_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option' => self::FOOTER_OPTION,
				'key'    => 'address',
				'rows'   => 3,
			)
		);

		add_settings_field(
			'copyright',
			__( 'Copyright text', 'wp-plastic-surgery' ),
			array( $this, 'render_textarea_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'      => self::FOOTER_OPTION,
				'key'         => 'copyright',
				'rows'        => 2,
				'description' => __( 'Use {year} to auto-insert the current year, e.g. "© {year} Truong Group".', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'bg_color',
			__( 'Footer background color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'  => self::FOOTER_OPTION,
				'key'     => 'bg_color',
				'default' => '#38465e',
			)
		);

		add_settings_field(
			'text_color',
			__( 'Footer text color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_footer',
			'truong_group_footer_section',
			array(
				'option'      => self::FOOTER_OPTION,
				'key'         => 'text_color',
				'default'     => '#ffffff',
				'description' => __( 'Forces the color of every text element in the footer: branding, menu, sub-menus, contact info and copyright.', 'wp-plastic-surgery' ),
			)
		);
	}

	private function register_cta_settings(): void {
		register_setting(
			self::CTA_GROUP,
			self::CTA_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_cta_settings' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_cta_section', '', '__return_false', self::MENU_SLUG . '_cta' );

		add_settings_field(
			'text',
			__( 'Button text', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_cta',
			'truong_group_cta_section',
			array(
				'option' => self::CTA_OPTION,
				'key'    => 'text',
				'type'   => 'text',
			)
		);

		add_settings_field(
			'url',
			__( 'Button URL', 'wp-plastic-surgery' ),
			array( $this, 'render_page_url_field' ),
			self::MENU_SLUG . '_cta',
			'truong_group_cta_section',
			array(
				'option' => self::CTA_OPTION,
				'key'    => 'url',
			)
		);

		add_settings_field(
			'gallery_url',
			__( 'Patient gallery URL', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_cta',
			'truong_group_cta_section',
			array(
				'option'      => self::CTA_OPTION,
				'key'         => 'gallery_url',
				'type'        => 'url',
				'placeholder' => 'https://',
				'description' => __( 'Secondary button shown in procedure page heroes. Leave empty to hide it.', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'bg_color',
			__( 'Button background color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_cta',
			'truong_group_cta_section',
			array(
				'option'  => self::CTA_OPTION,
				'key'     => 'bg_color',
				'default' => '#38465e',
			)
		);

		add_settings_field(
			'text_color',
			__( 'Button text color', 'wp-plastic-surgery' ),
			array( $this, 'render_color_field' ),
			self::MENU_SLUG . '_cta',
			'truong_group_cta_section',
			array(
				'option'  => self::CTA_OPTION,
				'key'     => 'text_color',
				'default' => '#ffffff',
			)
		);
	}

	private function register_tracking_settings(): void {
		register_setting(
			self::TRACKING_GROUP,
			self::TRACKING_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_tracking_settings' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_tracking_section', '', '__return_false', self::MENU_SLUG . '_tracking' );

		add_settings_field(
			'ga4_id',
			__( 'GA4 Measurement ID', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_tracking',
			'truong_group_tracking_section',
			array(
				'option'      => self::TRACKING_OPTION,
				'key'         => 'ga4_id',
				'type'        => 'text',
				'placeholder' => 'G-XXXXXXXXXX',
				'description' => __( 'Automatically injects the standard gtag.js snippet into the <head>.', 'wp-plastic-surgery' ),
			)
		);

		add_settings_field(
			'gtm_id',
			__( 'GTM Container ID', 'wp-plastic-surgery' ),
			array( $this, 'render_text_field' ),
			self::MENU_SLUG . '_tracking',
			'truong_group_tracking_section',
			array(
				'option'      => self::TRACKING_OPTION,
				'key'         => 'gtm_id',
				'type'        => 'text',
				'placeholder' => 'GTM-XXXXXXX',
				'description' => __( 'Injects the standard Google Tag Manager snippet (head + noscript in body).', 'wp-plastic-surgery' ),
			)
		);
	}

	private function register_social_settings(): void {
		register_setting(
			self::SOCIAL_GROUP,
			self::SOCIAL_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_social_settings' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_social_section', '', '__return_false', self::MENU_SLUG . '_social' );

		foreach ( self::SOCIAL_NETWORKS as $key => $label ) {
			add_settings_field(
				'social_' . $key,
				$label,
				array( $this, 'render_text_field' ),
				self::MENU_SLUG . '_social',
				'truong_group_social_section',
				array(
					'option'      => self::SOCIAL_OPTION,
					'key'         => $key,
					'type'        => 'url',
					'placeholder' => 'https://',
				)
			);
		}
	}

	private function register_scripts_settings(): void {
		register_setting(
			self::SCRIPTS_GROUP,
			self::SCRIPTS_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_custom_scripts' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'truong_group_scripts_section', '', array( $this, 'render_scripts_section_intro' ), self::MENU_SLUG . '_scripts' );

		add_settings_field(
			'scripts',
			__( 'Custom scripts', 'wp-plastic-surgery' ),
			array( $this, 'render_scripts_field' ),
			self::MENU_SLUG . '_scripts',
			'truong_group_scripts_section'
		);
	}

	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$tabs   = $this->tabs();
		$active = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'header';

		if ( ! array_key_exists( $active, $tabs ) ) {
			$active = 'header';
		}

		?>
		<div class="wrap wp-plastic-surgery-settings">
			<h1><?php esc_html_e( 'Theme Settings', 'wp-plastic-surgery' ); ?></h1>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $slug => $label ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array( 'page' => self::MENU_SLUG, 'tab' => $slug ), admin_url( 'admin.php' ) ) ); ?>"
						class="nav-tab <?php echo $active === $slug ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $label ); ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( $this->group_for_tab( $active ) );
				do_settings_sections( self::MENU_SLUG . '_' . $active );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Renders a media-library backed field (attachment id storage).
	 *
	 * @param array{option: string, key: string, label: string} $args
	 */
	public function render_media_field( array $args ): void {
		$settings    = (array) get_option( $args['option'], array() );
		$id          = isset( $settings[ $args['key'] ] ) ? absint( $settings[ $args['key'] ] ) : 0;
		$field_id    = $args['option'] . '_' . $args['key'];
		$field_name  = $args['option'] . '[' . $args['key'] . ']';
		$placeholder = __( 'No image selected', 'wp-plastic-surgery' );
		?>
		<div class="truong-media-field">
			<div class="truong-media-field__preview" data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<?php if ( $id && wp_attachment_is_image( $id ) ) : ?>
					<img src="<?php echo esc_url( (string) wp_get_attachment_image_url( $id, 'medium' ) ); ?>" alt="" />
				<?php else : ?>
					<span class="truong-media-field__placeholder"><?php echo esc_html( $placeholder ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" class="truong-media-field__input" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( (string) $id ); ?>" />
			<p>
				<button type="button" class="button truong-media-field__select"><?php echo esc_html( $args['label'] ); ?></button>
				<button type="button" class="button-link-delete truong-media-field__remove"<?php echo $id ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
			</p>
		</div>
		<?php
	}

	/**
	 * Renders a nav menu select field backed by an option array.
	 *
	 * @param array{option: string, key: string} $args
	 */
	public function render_nav_menu_field( array $args ): void {
		$settings = (array) get_option( $args['option'], array() );
		$selected = isset( $settings[ $args['key'] ] ) ? absint( $settings[ $args['key'] ] ) : 0;
		$menus    = wp_get_nav_menus();
		?>
		<select name="<?php echo esc_attr( $args['option'] ); ?>[<?php echo esc_attr( $args['key'] ); ?>]">
			<option value="0"><?php esc_html_e( '— Select menu —', 'wp-plastic-surgery' ); ?></option>
			<?php foreach ( $menus as $menu ) : ?>
				<option value="<?php echo esc_attr( (string) $menu->term_id ); ?>" <?php selected( $selected, $menu->term_id ); ?>>
					<?php echo esc_html( $menu->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php if ( empty( $menus ) ) : ?>
			<p class="description"><?php esc_html_e( 'No menus created yet. Go to Appearance > Menus.', 'wp-plastic-surgery' ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Renders a color-picker field backed by an option array.
	 *
	 * @param array{option: string, key: string, default?: string, description?: string} $args
	 */
	public function render_color_field( array $args ): void {
		$settings = (array) get_option( $args['option'], array() );
		$value    = isset( $settings[ $args['key'] ] ) ? (string) $settings[ $args['key'] ] : '';
		?>
		<input
			type="text"
			class="truong-color-field"
			name="<?php echo esc_attr( $args['option'] ); ?>[<?php echo esc_attr( $args['key'] ); ?>]"
			value="<?php echo esc_attr( $value ); ?>"
			data-default-color="<?php echo esc_attr( $args['default'] ?? '' ); ?>"
		/>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Renders a generic single-value text/url field backed by an option array.
	 *
	 * @param array{option: string, key: string, type?: string, placeholder?: string, description?: string} $args
	 */
	public function render_text_field( array $args ): void {
		$settings = (array) get_option( $args['option'], array() );
		$value    = isset( $settings[ $args['key'] ] ) ? (string) $settings[ $args['key'] ] : '';
		$type     = $args['type'] ?? 'text';
		?>
		<input
			type="<?php echo esc_attr( $type ); ?>"
			class="regular-text"
			name="<?php echo esc_attr( $args['option'] ); ?>[<?php echo esc_attr( $args['key'] ); ?>]"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo ! empty( $args['placeholder'] ) ? 'placeholder="' . esc_attr( $args['placeholder'] ) . '"' : ''; ?>
		/>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Renders a URL field with a page picker: choose an existing page from a
	 * dropdown (its permalink becomes the value) or "Custom URL" to type one
	 * manually — e.g. an anchor or an external link. Backed by the same
	 * single-string option value as render_text_field().
	 *
	 * @param array{option: string, key: string} $args
	 */
	public function render_page_url_field( array $args ): void {
		$settings   = (array) get_option( $args['option'], array() );
		$value      = isset( $settings[ $args['key'] ] ) ? (string) $settings[ $args['key'] ] : '';
		$field_id   = $args['option'] . '_' . $args['key'];
		$field_name = $args['option'] . '[' . $args['key'] . ']';

		$pages      = get_pages( array( 'sort_column' => 'post_title' ) );
		$permalinks = array_map( 'get_permalink', $pages );
		$is_custom  = '' !== $value && ! in_array( $value, $permalinks, true );
		?>
		<div class="truong-page-url-field">
			<select class="truong-page-url-field__select">
				<option value=""><?php esc_html_e( '— Select a page —', 'wp-plastic-surgery' ); ?></option>
				<?php foreach ( $pages as $page ) : ?>
					<?php $permalink = get_permalink( $page ); ?>
					<option value="<?php echo esc_url( $permalink ); ?>" <?php selected( $value, $permalink ); ?>>
						<?php echo esc_html( $page->post_title ); ?>
					</option>
				<?php endforeach; ?>
				<option value="__custom__" <?php selected( $is_custom, true ); ?>><?php esc_html_e( '— Custom URL —', 'wp-plastic-surgery' ); ?></option>
			</select>
			<input
				type="url"
				class="regular-text truong-page-url-field__input"
				id="<?php echo esc_attr( $field_id ); ?>"
				name="<?php echo esc_attr( $field_name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				<?php echo ( $is_custom || '' === $value ) ? '' : ' style="display:none;"'; ?>
			/>
		</div>
		<?php
	}

	/**
	 * Renders a generic multi-line text field backed by an option array.
	 *
	 * @param array{option: string, key: string, rows?: int, description?: string} $args
	 */
	public function render_textarea_field( array $args ): void {
		$settings = (array) get_option( $args['option'], array() );
		$value    = isset( $settings[ $args['key'] ] ) ? (string) $settings[ $args['key'] ] : '';
		$rows     = $args['rows'] ?? 3;
		?>
		<textarea
			class="large-text"
			rows="<?php echo esc_attr( (string) $rows ); ?>"
			name="<?php echo esc_attr( $args['option'] ); ?>[<?php echo esc_attr( $args['key'] ); ?>]"
		><?php echo esc_textarea( $value ); ?></textarea>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function render_scripts_section_intro(): void {
		?>
		<p class="description">
			<?php esc_html_e( 'Each script is injected as-is (raw HTML/JS), unfiltered. Only users with administrator permissions can edit this section.', 'wp-plastic-surgery' ); ?>
		</p>
		<?php
	}

	public function render_scripts_field(): void {
		$items = self::get_custom_scripts();
		?>
		<div id="truong-scripts-repeater" class="truong-scripts-repeater" data-index="<?php echo esc_attr( (string) count( $items ) ); ?>">
			<div class="truong-scripts-repeater__rows">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php $this->render_script_row( (string) $index, $item ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button truong-scripts-repeater__add"><?php esc_html_e( '+ Add script', 'wp-plastic-surgery' ); ?></button></p>
			<template id="truong-scripts-repeater-template">
				<?php $this->render_script_row( '__INDEX__', array() ); ?>
			</template>
		</div>
		<?php
	}

	/**
	 * @param array{label?: string, code?: string, location?: string, enabled?: bool} $item
	 */
	private function render_script_row( string $index, array $item ): void {
		$label    = $item['label'] ?? '';
		$code     = $item['code'] ?? '';
		$location = in_array( $item['location'] ?? '', self::SCRIPT_LOCATIONS, true ) ? $item['location'] : 'head';
		$enabled  = ! empty( $item['enabled'] );
		$name     = self::SCRIPTS_OPTION . '[' . $index . ']';
		?>
		<div class="truong-scripts-repeater__row">
			<p>
				<label>
					<?php esc_html_e( 'Name', 'wp-plastic-surgery' ); ?><br />
					<input type="text" class="widefat" name="<?php echo esc_attr( $name ); ?>[label]" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php esc_attr_e( 'E.g. Ahrefs Analytics', 'wp-plastic-surgery' ); ?>" />
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[enabled]" value="1" <?php checked( $enabled ); ?> />
					<?php esc_html_e( 'Active', 'wp-plastic-surgery' ); ?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Location', 'wp-plastic-surgery' ); ?><br />
					<select name="<?php echo esc_attr( $name ); ?>[location]">
						<option value="head" <?php selected( $location, 'head' ); ?>>&lt;head&gt;</option>
						<option value="body_open" <?php selected( $location, 'body_open' ); ?>><?php esc_html_e( 'Right after <body>', 'wp-plastic-surgery' ); ?></option>
						<option value="footer" <?php selected( $location, 'footer' ); ?>><?php esc_html_e( 'Before </body>', 'wp-plastic-surgery' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Code', 'wp-plastic-surgery' ); ?><br />
					<textarea class="widefat code" rows="5" name="<?php echo esc_attr( $name ); ?>[code]"><?php echo esc_textarea( $code ); ?></textarea>
				</label>
			</p>
			<button type="button" class="button-link-delete truong-scripts-repeater__remove"><?php esc_html_e( 'Remove script', 'wp-plastic-surgery' ); ?></button>
		</div>
		<?php
	}

	/**
	 * @param mixed $input
	 * @return array{logo_id: int, favicon_id: int, nav_menu_id: int, bg_color: string, menu_font_color: string}
	 */
	public function sanitize_header_settings( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();

		return array(
			'logo_id'         => isset( $input['logo_id'] ) ? absint( $input['logo_id'] ) : 0,
			'sticky_logo_id'  => isset( $input['sticky_logo_id'] ) ? absint( $input['sticky_logo_id'] ) : 0,
			'favicon_id'      => isset( $input['favicon_id'] ) ? absint( $input['favicon_id'] ) : 0,
			'nav_menu_id'     => isset( $input['nav_menu_id'] ) ? absint( $input['nav_menu_id'] ) : 0,
			'bg_color'        => isset( $input['bg_color'] ) ? $this->sanitize_color( $input['bg_color'] ) : '',
			'menu_font_color' => isset( $input['menu_font_color'] ) ? $this->sanitize_color( $input['menu_font_color'] ) : '',
		);
	}

	/**
	 * Sanitizes a hex color, allowing an empty value (falls back to the CSS default).
	 */
	private function sanitize_color( string $value ): string {
		$value = trim( $value );

		if ( '' === $value ) {
			return '';
		}

		return (string) ( sanitize_hex_color( $value ) ?? '' );
	}

	/**
	 * @param mixed $input
	 * @return array{text: string, url: string, bg_color: string, text_color: string, gallery_url: string}
	 */
	public function sanitize_cta_settings( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();

		return array(
			'text'        => isset( $input['text'] ) ? sanitize_text_field( $input['text'] ) : '',
			'url'         => isset( $input['url'] ) ? esc_url_raw( $input['url'] ) : '',
			'bg_color'    => isset( $input['bg_color'] ) ? $this->sanitize_color( $input['bg_color'] ) : '',
			'text_color'  => isset( $input['text_color'] ) ? $this->sanitize_color( $input['text_color'] ) : '',
			'gallery_url' => isset( $input['gallery_url'] ) ? esc_url_raw( $input['gallery_url'] ) : '',
		);
	}

	/**
	 * @param mixed $input
	 * @return array{logo_id: int, nav_menu_id: int, phone: string, email: string, address: string, copyright: string, bg_color: string, text_color: string}
	 */
	public function sanitize_footer_settings( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();

		return array(
			'logo_id'     => isset( $input['logo_id'] ) ? absint( $input['logo_id'] ) : 0,
			'nav_menu_id' => isset( $input['nav_menu_id'] ) ? absint( $input['nav_menu_id'] ) : 0,
			'phone'       => isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '',
			'email'       => isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '',
			'address'     => isset( $input['address'] ) ? sanitize_textarea_field( $input['address'] ) : '',
			'copyright'   => isset( $input['copyright'] ) ? wp_kses_post( $input['copyright'] ) : '',
			'bg_color'    => isset( $input['bg_color'] ) ? $this->sanitize_color( $input['bg_color'] ) : '',
			'text_color'  => isset( $input['text_color'] ) ? $this->sanitize_color( $input['text_color'] ) : '',
		);
	}

	/**
	 * @param mixed $input
	 * @return array{ga4_id: string, gtm_id: string}
	 */
	public function sanitize_tracking_settings( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();

		return array(
			'ga4_id' => isset( $input['ga4_id'] ) ? $this->sanitize_tracking_id( $input['ga4_id'] ) : '',
			'gtm_id' => isset( $input['gtm_id'] ) ? $this->sanitize_tracking_id( $input['gtm_id'] ) : '',
		);
	}

	private function sanitize_tracking_id( string $value ): string {
		return (string) preg_replace( '/[^A-Za-z0-9-]/', '', $value );
	}

	/**
	 * @param mixed $input
	 * @return array<string, string>
	 */
	public function sanitize_social_settings( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();
		$clean = array();

		foreach ( array_keys( self::SOCIAL_NETWORKS ) as $key ) {
			$clean[ $key ] = ! empty( $input[ $key ] ) ? esc_url_raw( $input[ $key ] ) : '';
		}

		return $clean;
	}

	/**
	 * @param mixed $input
	 * @return array<int, array{label: string, code: string, location: string, enabled: bool}>
	 */
	public function sanitize_custom_scripts( $input ): array {
		$input = is_array( $input ) ? wp_unslash( $input ) : array();
		$clean = array();

		foreach ( $input as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$label    = sanitize_text_field( $item['label'] ?? '' );
			$code     = isset( $item['code'] ) ? (string) $item['code'] : '';
			$location = in_array( $item['location'] ?? '', self::SCRIPT_LOCATIONS, true ) ? $item['location'] : 'head';
			$enabled  = ! empty( $item['enabled'] );

			if ( '' === $label && '' === trim( $code ) ) {
				continue;
			}

			$clean[] = array(
				'label'    => $label,
				'code'     => $code,
				'location' => $location,
				'enabled'  => $enabled,
			);
		}

		return $clean;
	}

	public function enqueue_admin_assets( string $hook ): void {
		if ( '' === $this->page_hook || $hook !== $this->page_hook ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script(
			'wp-plastic-surgery-settings-color-picker',
			$this->uri() . 'assets/js/admin-settings-color-picker.js',
			array( 'wp-color-picker' ),
			$this->asset_version( 'assets/js/admin-settings-color-picker.js' ),
			true
		);

		wp_enqueue_style(
			'wp-plastic-surgery-settings',
			$this->uri() . 'assets/css/admin-settings.css',
			array(),
			$this->asset_version( 'assets/css/admin-settings.css' )
		);

		wp_enqueue_script(
			'wp-plastic-surgery-settings',
			$this->uri() . 'assets/js/admin-settings-media.js',
			array(),
			$this->asset_version( 'assets/js/admin-settings-media.js' ),
			true
		);

		wp_enqueue_script(
			'wp-plastic-surgery-settings-scripts-repeater',
			$this->uri() . 'assets/js/admin-settings-scripts-repeater.js',
			array(),
			$this->asset_version( 'assets/js/admin-settings-scripts-repeater.js' ),
			true
		);

		wp_enqueue_script(
			'wp-plastic-surgery-settings-page-url',
			$this->uri() . 'assets/js/admin-settings-page-url.js',
			array(),
			$this->asset_version( 'assets/js/admin-settings-page-url.js' ),
			true
		);
	}

	/**
	 * Outputs the favicon link tag on the front end, if configured.
	 */
	public function output_favicon(): void {
		$settings = self::get_header_settings();

		if ( ! $settings['favicon_id'] ) {
			return;
		}

		$url = wp_get_attachment_image_url( $settings['favicon_id'], 'full' );

		if ( ! $url ) {
			return;
		}

		printf( '<link rel="icon" href="%s" />' . "\n", esc_url( $url ) );
	}

	/**
	 * Outputs header, footer and CTA color overrides as CSS custom
	 * properties, if configured. Each falls back to the theme's default
	 * colors in main.css when left empty.
	 */
	public function output_theme_colors(): void {
		$header = self::get_header_settings();
		$footer = self::get_footer_settings();
		$cta    = self::get_cta_settings();

		$map = array(
			'--header-bg-color'   => $header['bg_color'],
			'--header-menu-color' => $header['menu_font_color'],
			'--footer-bg-color'   => $footer['bg_color'],
			'--footer-text-color' => $footer['text_color'],
			'--cta-bg-color'      => $cta['bg_color'],
			'--cta-text-color'    => $cta['text_color'],
		);

		$declarations = array();

		foreach ( $map as $property => $value ) {
			if ( $value ) {
				$declarations[] = $property . ':' . $value;
			}
		}

		if ( empty( $declarations ) ) {
			return;
		}

		printf( '<style id="wp-plastic-surgery-theme-colors">:root{%s;}</style>' . "\n", esc_html( implode( ';', $declarations ) ) );
	}

	/**
	 * Outputs the GA4 / GTM head snippets, if configured.
	 */
	public function output_tracking_head(): void {
		$tracking = self::get_tracking_settings();

		if ( $tracking['ga4_id'] ) {
			$src = esc_url( 'https://www.googletagmanager.com/gtag/js?id=' . $tracking['ga4_id'] );
			$id  = esc_js( $tracking['ga4_id'] );

			echo "<script async src=\"{$src}\"></script>\n";
			echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$id}');</script>\n";
		}

		if ( $tracking['gtm_id'] ) {
			$id = esc_js( $tracking['gtm_id'] );

			echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$id}');</script>\n";
		}
	}

	/**
	 * Outputs the GTM <noscript> fallback, if configured. Must run on wp_body_open.
	 */
	public function output_tracking_body(): void {
		$tracking = self::get_tracking_settings();

		if ( ! $tracking['gtm_id'] ) {
			return;
		}

		$src = esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $tracking['gtm_id'] );

		echo "<noscript><iframe src=\"{$src}\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>\n";
	}

	public function output_custom_scripts_head(): void {
		$this->print_scripts_for_location( 'head' );
	}

	public function output_custom_scripts_body_open(): void {
		$this->print_scripts_for_location( 'body_open' );
	}

	public function output_custom_scripts_footer(): void {
		$this->print_scripts_for_location( 'footer' );
	}

	private function print_scripts_for_location( string $location ): void {
		foreach ( self::get_custom_scripts() as $item ) {
			if ( empty( $item['enabled'] ) || ( $item['location'] ?? 'head' ) !== $location ) {
				continue;
			}

			$code = trim( $item['code'] ?? '' );

			if ( '' === $code ) {
				continue;
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- intentional raw output, admin-only trusted input gated by manage_options.
			echo "\n" . $code . "\n";
		}
	}
}
