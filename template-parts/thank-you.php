<?php
/**
 * Body content for /thank-you/ —
 * Architecture/01-tecnico/02-formulario-spec.md, "Página /thank-you/".
 *
 * Fires the `generate_lead` GA4 event on load, per
 * "Medición en GA4" in that same spec — this page IS the conversion,
 * regardless of which form eventually redirects here.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="sbbl-section sbbl-thank-you">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1><?php esc_html_e( "Thanks — we've got your request.", 'wp-plastic-surgery' ); ?></h1>

				<p><strong><?php esc_html_e( "We'll reach out within one business day.", 'wp-plastic-surgery' ); ?></strong></p>

				<h2><?php esc_html_e( "What Happens Now", 'wp-plastic-surgery' ); ?></h2>
				<ul>
					<li><?php echo wp_kses_post( sprintf( /* translators: %s: VERIFY marker for who calls. */ __( '<strong>Who calls you</strong> — %s', 'wp-plastic-surgery' ), truong_verify( 'name/role of who follows up, e.g. patient coordinator' ) ) ); ?></li>
					<li><?php echo wp_kses_post( sprintf( /* translators: %s: VERIFY marker for the caller ID number. */ __( '<strong>From this number</strong> — %s, so you recognize the call', 'wp-plastic-surgery' ), truong_verify( 'phone number that will show up on caller ID' ) ) ); ?></li>
					<li><?php echo wp_kses_post( sprintf( /* translators: %s: VERIFY marker for business hours. */ __( '<strong>During these hours</strong> — %s', 'wp-plastic-surgery' ), truong_verify( 'business hours' ) ) ); ?></li>
				</ul>

				<p><?php esc_html_e( "If you don't hear from us in that window, it's worth checking your spam folder or giving the office a call directly — it usually means a typo in the contact details, not a missed request.", 'wp-plastic-surgery' ); ?></p>

				<h2><?php esc_html_e( 'While You Wait', 'wp-plastic-surgery' ); ?></h2>
				<ul class="sbbl-thank-you__links">
					<li><a href="<?php echo esc_url( home_url( '/am-i-too-skinny-for-a-bbl/' ) ); ?>"><?php esc_html_e( 'Am I Too Skinny for a BBL?', 'wp-plastic-surgery' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/skinny-bbl-cost/' ) ); ?>"><?php esc_html_e( 'Skinny BBL Cost: Full Price Breakdown', 'wp-plastic-surgery' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/skinny-bbl-recovery/' ) ); ?>"><?php esc_html_e( 'Skinny BBL Recovery: Week by Week', 'wp-plastic-surgery' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</section>
<script>
if ( typeof gtag === 'function' ) {
	gtag( 'event', 'generate_lead' );
}
</script>
