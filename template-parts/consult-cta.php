<?php
/**
 * Template part for the CTA block (text + URL from theme settings).
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$truong_cta = \WPPlasticSurgery\Admin\SettingsController::get_cta_settings();

if ( '' === $truong_cta['text'] || '' === $truong_cta['url'] ) {
	return;
}
?>
<section class="consult-cta">
	<a href="<?php echo esc_url( $truong_cta['url'] ); ?>" class="consult-cta__button btn btn-primary">
		<?php echo esc_html( $truong_cta['text'] ); ?>
	</a>
</section>
