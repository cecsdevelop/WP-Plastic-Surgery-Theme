<?php
/**
 * Home hero: H1 + direct-answer paragraph + CTA, with an optional featured
 * image rendered as a real <img fetchpriority="high"> (not a CSS
 * background) so it stays discoverable/preloadable when it is the LCP
 * element (Architecture/02-briefs/00-homepage.md).
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$truong_cta      = \WPPlasticSurgery\Admin\SettingsController::get_cta_settings();
$truong_cta_text = '' !== $truong_cta['text'] ? $truong_cta['text'] : __( "Find out if you're a candidate", 'wp-plastic-surgery' );
$truong_cta_url  = '' !== $truong_cta['url'] ? $truong_cta['url'] : '#consultation-form';
$feature_image_id = get_post_thumbnail_id();
?>
<section class="sbbl-hero">
	<div class="container">
		<div class="row movile-columm-reverse">
			<div class="col-lg-6 col-md-6 col-xs-12 hero-column">
				<h1 class="sbbl-hero__title"><?php esc_html_e( 'Skinny BBL: The Complete Guide for Slim Patients', 'wp-plastic-surgery' ); ?></h1>
				<p class="sbbl-hero__answer">
					<?php esc_html_e( "A skinny BBL is a Brazilian butt lift performed on patients who don't have large fat reserves to work with. The principle is the same — fat is removed by liposuction, purified, and grafted into the buttocks — but on a slim frame the surgeon has to harvest from more areas to collect enough usable fat. That changes the planning, the cost, and what's realistically achievable.", 'wp-plastic-surgery' ); ?>
				</p>
			</div>
			<div class="col-lg-6 col-md-6 col-xs-12 hero-column hero-column-image">
				<?php if ( $feature_image_id ) : ?>
					<?php
					echo wp_get_attachment_image(
						$feature_image_id,
						'post-thumbnail',
						false,
						array(
							'class'         => 'hero-column-image__img',
							'alt'           => get_the_title(),
							'fetchpriority' => 'high',
							'loading'       => 'eager',
							'decoding'      => 'async',
						)
					);
					?>
				<?php endif; ?>
				<p class="sbbl-hero__cta">
					<a href="<?php echo esc_url( $truong_cta_url ); ?>" class="btn btn-primary"><?php echo esc_html( $truong_cta_text ); ?></a>
					<span class="sbbl-hero__cta-note"><?php esc_html_e( 'Takes about 60 seconds. No obligation.', 'wp-plastic-surgery' ); ?></span>
				</p>
			</div>
		</div>
	</div>
</section>
