<?php
/**
 * Template Name: Thank You
 * Template Post Type: page
 *
 * Post-submission confirmation page —
 * Architecture/01-tecnico/02-formulario-spec.md, "Página /thank-you/".
 * This is the GA4 conversion URL (see template-parts/thank-you.php for the
 * generate_lead event) and must stay out of search results regardless of
 * the site-wide "discourage search engines" setting, so it forces
 * noindex,nofollow via the wp_robots filter rather than relying on that
 * toggle or on Rank Math's per-page setting being remembered.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'wp_robots',
	function ( array $robots ): array {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;

		return $robots;
	}
);

get_header();

get_template_part( 'template-parts/thank-you' );

get_footer();
