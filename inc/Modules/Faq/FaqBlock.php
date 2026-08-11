<?php
/**
 * FAQ List Gutenberg block: picks one `faq` CPT post and renders it via the
 * existing template-parts/faq.php (schema.org markup included).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Faq;

use WPPlasticSurgery\Admin\BaseController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FaqBlock extends BaseController {

	private const BLOCK_DIR = 'blocks/faq-list';

	public function register(): void {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	public function register_block(): void {
		wp_register_script(
			'wp-plastic-surgery-faq-list-editor',
			$this->uri() . self::BLOCK_DIR . '/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-server-side-render', 'wp-i18n' ),
			$this->asset_version( self::BLOCK_DIR . '/edit.js' ),
			true
		);

		register_block_type(
			$this->path() . self::BLOCK_DIR,
			array(
				'editor_script'   => 'wp-plastic-surgery-faq-list-editor',
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param array{faqId?: int} $attributes
	 */
	public function render( array $attributes ): string {
		$faq_id = ! empty( $attributes['faqId'] ) ? (int) $attributes['faqId'] : 0;

		if ( ! $faq_id || FaqController::post_type() !== get_post_type( $faq_id ) ) {
			return '';
		}

		ob_start();
		get_template_part( 'template-parts/faq', null, array( 'faq_id' => $faq_id ) );

		return (string) ob_get_clean();
	}
}
