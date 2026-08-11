<?php
/**
 * Template part for the site header: branding + primary nav.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$truong_header    = \WPPlasticSurgery\Admin\SettingsController::get_header_settings();
$truong_cta       = \WPPlasticSurgery\Admin\SettingsController::get_cta_settings();
$truong_cta_text  = '' !== $truong_cta['text'] ? $truong_cta['text'] : __( 'CTA', 'wp-plastic-surgery' );
$truong_cta_url   = '' !== $truong_cta['url'] ? $truong_cta['url'] : '#';
?>
<header class="site-header">
	<div class="container">
		<div class="row">
			<div class="col-lg-2 col-md-2 col-xs-4 header-inner flex jc-fs ai-c ">
				<div class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__logo-link">
						<?php if ( $truong_header['logo_id'] ) : ?>
							<?php echo wp_get_attachment_image( $truong_header['logo_id'], 'full', false, array( 'class' => 'site-header__logo site-header__logo--default' ) ); ?>
						<?php else : ?>
							<span class="site-header__logo-text"><?php bloginfo( 'name' ); ?></span>
						<?php endif; ?>
						<?php if ( $truong_header['sticky_logo_id'] ) : ?>
							<?php echo wp_get_attachment_image( $truong_header['sticky_logo_id'], 'full', false, array( 'class' => 'site-header__logo site-header__logo--sticky' ) ); ?>
						<?php endif; ?>
					</a>
				</div>
			</div>

			<button type="button" id="burger" class="site-header__burger" aria-label="<?php esc_attr_e( 'Toggle menu', 'wp-plastic-surgery' ); ?>" aria-expanded="false" aria-controls="drawer">
				<span></span><span></span><span></span>
			</button>

			<?php if ( $truong_header['nav_menu_id'] ) : ?>
				<nav id="drawer" class="site-header__nav col-lg-9 col-md-9 col-xs-4" aria-label="<?php esc_attr_e( 'Primary navigation', 'wp-plastic-surgery' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'menu'        => $truong_header['nav_menu_id'],
							'container'   => false,
							'menu_class'  => 'site-header__menu',
							'fallback_cb' => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div id="scrim" class="scrim"></div>

			<div class="header-cta d-desktop-only col-lg-1 col-md-1 col-xs-4">
				<a href="<?php echo esc_url( $truong_cta_url ); ?>" class="btn btn-primary btn-sm"><?php echo esc_html( $truong_cta_text ); ?></a>
			</div>
		</div><!-- end row-->
	</div><!-- end container-->
</header>



<!-- Header -->
