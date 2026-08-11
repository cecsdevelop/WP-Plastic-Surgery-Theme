<?php
/**
 * Template part for the site footer: branding + nav + contact + copyright.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$truong_footer = \WPPlasticSurgery\Admin\SettingsController::get_footer_settings();
$truong_header = \WPPlasticSurgery\Admin\SettingsController::get_header_settings();
$truong_logo   = $truong_footer['logo_id'] ? $truong_footer['logo_id'] : $truong_header['logo_id'];
?>
<!-- Footer -->
	<div class="container">
		<div class="row">
			
			<div class="col-lg-4 col-md-4 col-xs-12 site-footer__branding site-footer__column">
				<?php if ( $truong_logo ) : ?>
					<?php echo wp_get_attachment_image( $truong_logo, 'full', false, array( 'class' => 'site-footer__logo' ) ); ?>
				<?php else : ?>
					<span class="site-footer__logo-text"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="col-lg-4 col-md-4 col-xs-6 site-footer__column">
				<h2 class="footer-column_title">Services</h2>
				<?php if ( $truong_footer['nav_menu_id'] ) : ?>
					<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'wp-plastic-surgery' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'menu'        => $truong_footer['nav_menu_id'],
								'container'   => false,
								'menu_class'  => 'site-footer__menu',
								'fallback_cb' => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>
			
			<div class="col-lg-4 col-md-4 col-xs-12 site-footer__column">
				<h2 class="footer-column_title">Get In Touch</h2>
				<?php if ( $truong_footer['phone'] || $truong_footer['email'] || $truong_footer['address'] ) : ?>
					<div class="site-footer__contact">
						<div class="site-footer__contact-item">
							<?php if ( $truong_footer['phone'] ) : ?>
								<span>Call us: </span>	<a class="site-footer__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $truong_footer['phone'] ) ); ?>"><?php echo esc_html( $truong_footer['phone'] ); ?></a>
							<?php endif; ?>
						</div>
						<div class="site-footer__contact-item">
							<?php if ( $truong_footer['email'] ) : ?>
								<span>Email: </span><a class="site-footer__email" href="mailto:<?php echo esc_attr( $truong_footer['email'] ); ?>"><?php echo esc_html( $truong_footer['email'] ); ?></a>
							<?php endif; ?>
						</div>
						<div class="site-footer__contact-item">
							<?php if ( $truong_footer['address'] ) : ?>
								<span>Address: </span><address class="site-footer__address"><?php echo nl2br( esc_html( $truong_footer['address'] ) ); ?></address>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="col-lg-12 col-md-12 col-xs-12 flex flex-dc jc-c ai-c fc-w">
				<?php get_template_part( 'template-parts/social-links' ); ?>
				<?php $truong_copyright = \WPPlasticSurgery\Admin\SettingsController::get_footer_copyright(); ?>
				<?php if ( $truong_copyright ) : ?>
					<p class="site-footer__copyright"><?php echo wp_kses_post( $truong_copyright ); ?></p>
				<?php endif; ?>
			</div>

		</div>
	</div>

