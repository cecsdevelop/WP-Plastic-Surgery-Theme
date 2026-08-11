<?php
/**
 * Plain template helper functions (not part of the WPPlasticSurgery\ autoloaded namespace).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'truong_verify' ) ) {
	/**
	 * Renders an inline marker for copy pending clinical/legal sign-off.
	 *
	 * Mirrors the `[VERIFY: ...]` convention used in Architecture/02-briefs/.
	 */
	function truong_verify( string $detail = '' ): string {
		$label = '' !== $detail ? '[VERIFY: ' . esc_html( $detail ) . ']' : '[VERIFY]';

		return '<mark class="tg-verify" title="' . esc_attr__( 'Pending clinical verification', 'wp-plastic-surgery' ) . '">' . $label . '</mark>';
	}
}
