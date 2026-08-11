<?php
/**
 * Surgeon List Gutenberg block: lists `surgeon` CPT posts as a list or a
 * 2/3/4-column card grid, with the option to exclude specific surgeons.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Surgeon;

use WPPlasticSurgery\Admin\BaseController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SurgeonListBlock extends BaseController {

	private const BLOCK_DIR = 'blocks/surgeon-list';

	public function register(): void {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	public function register_block(): void {
		wp_register_script(
			'wp-plastic-surgery-surgeon-list-editor',
			$this->uri() . self::BLOCK_DIR . '/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-server-side-render', 'wp-i18n' ),
			$this->asset_version( self::BLOCK_DIR . '/edit.js' ),
			true
		);

		register_block_type(
			$this->path() . self::BLOCK_DIR,
			array(
				'editor_script'   => 'wp-plastic-surgery-surgeon-list-editor',
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param array{layout?: string, columns?: int, excludedIds?: array<int, int>} $attributes
	 */
	public function render( array $attributes ): string {
		ob_start();
		get_template_part(
			'template-parts/surgeon-list',
			null,
			array(
				'layout'       => isset( $attributes['layout'] ) ? (string) $attributes['layout'] : 'list',
				'columns'      => isset( $attributes['columns'] ) ? (int) $attributes['columns'] : 3,
				'excluded_ids' => isset( $attributes['excludedIds'] ) && is_array( $attributes['excludedIds'] )
					? array_map( 'intval', $attributes['excludedIds'] )
					: array(),
			)
		);

		return (string) ob_get_clean();
	}
}
