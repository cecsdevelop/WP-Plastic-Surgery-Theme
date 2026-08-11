<?php
/**
 * Frontend asset enqueue controller.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Admin;

use WPPlasticSurgery\Modules\Surgeon\SurgeonController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class EnqueueController extends BaseController {

	/**
	 * Register the frontend enqueue hook.
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_head', array( $this, 'preload_fonts' ), 1 );
	}

	/**
	 * Enqueue theme stylesheet and script.
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style(
			'wp-plastic-surgery-main',
			$this->uri() . 'assets/css/main.css',
			array(),
			$this->asset_version( 'assets/css/main.css' )
		);

		wp_enqueue_script(
			'wp-plastic-surgery-scripts',
			$this->uri() . 'assets/js/scripts.js',
			array(),
			$this->asset_version( 'assets/js/scripts.js' ),
			true
		);

		if ( is_singular( SurgeonController::post_type() ) ) {
			wp_enqueue_script(
				'surgeon-history-gallery',
				$this->uri() . 'assets/js/surgeon-history-gallery.js',
				array(),
				$this->asset_version( 'assets/js/surgeon-history-gallery.js' ),
				true
			);

			wp_enqueue_script(
				'surgeon-videos-gallery',
				$this->uri() . 'assets/js/surgeon-videos-gallery.js',
				array(),
				$this->asset_version( 'assets/js/surgeon-videos-gallery.js' ),
				true
			);

			wp_enqueue_script(
				'surgeon-results',
				$this->uri() . 'assets/js/surgeon-results.js',
				array(),
				$this->asset_version( 'assets/js/surgeon-results.js' ),
				true
			);

			wp_localize_script(
				'surgeon-results',
				'surgeonResults',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'surgeon_load_more_results' ),
				)
			);
		}
	}

	/**
	 * Preloads the primary heading typeface to avoid a render-blocking delay on the LCP element.
	 */
	public function preload_fonts(): void {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin />' . "\n",
			esc_url( $this->uri() . 'assets/fonts/dist/Montserrat-Variable.woff2' )
		);
	}
}
