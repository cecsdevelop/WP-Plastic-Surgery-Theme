<?php
/**
 * Procedure Hero Gutenberg block: featured image + page title come from the
 * post; subtitle, excerpt, hero images, and up to 2 CTA buttons are block attributes.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Procedure;

use WPPlasticSurgery\Admin\BaseController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ProcedureHeroBlock extends BaseController {

	private const BLOCK_DIR = 'blocks/procedure-hero';

	public function register(): void {
		add_action( 'init', array( $this, 'register_block' ) );
		add_filter( 'block_categories_all', array( $this, 'register_category' ) );
	}

	public function register_block(): void {
		wp_register_script(
			'wp-plastic-surgery-procedure-hero-editor',
			$this->uri() . self::BLOCK_DIR . '/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			$this->asset_version( self::BLOCK_DIR . '/edit.js' ),
			true
		);

		wp_register_style(
			'wp-plastic-surgery-procedure-hero-editor',
			$this->uri() . 'assets/css/admin-procedure-hero-block.css',
			array(),
			$this->asset_version( 'assets/css/admin-procedure-hero-block.css' )
		);

		register_block_type(
			$this->path() . self::BLOCK_DIR,
			array(
				'editor_script'   => 'wp-plastic-surgery-procedure-hero-editor',
				'editor_style'    => 'wp-plastic-surgery-procedure-hero-editor',
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param array<int, array{slug: string, title: string}> $categories
	 * @return array<int, array{slug: string, title: string}>
	 */
	public function register_category( array $categories ): array {
		foreach ( $categories as $category ) {
			if ( 'wp-plastic-surgery' === $category['slug'] ) {
				return $categories;
			}
		}

		array_unshift(
			$categories,
			array(
				'slug'  => 'wp-plastic-surgery',
				'title' => __( 'Truong Group', 'wp-plastic-surgery' ),
			)
		);

		return $categories;
	}

	/**
	 * @param array{subtitle?: string, excerpt?: string, desktopImageId?: int, mobileImageId?: int, cta1Text?: string, cta1Url?: string, cta1BgColor?: string, cta1TextColor?: string, cta2Text?: string, cta2Url?: string, cta2BgColor?: string, cta2TextColor?: string} $attributes
	 */
	public function render( array $attributes ): string {
		$subtitle = isset( $attributes['subtitle'] ) ? (string) $attributes['subtitle'] : '';
		$excerpt  = isset( $attributes['excerpt'] ) ? (string) $attributes['excerpt'] : '';

		$desktop_image_id = ! empty( $attributes['desktopImageId'] )
			? (int) $attributes['desktopImageId']
			: get_post_thumbnail_id();

		$mobile_image_id = ! empty( $attributes['mobileImageId'] )
			? (int) $attributes['mobileImageId']
			: $desktop_image_id;

		$desktop_bg_url = $desktop_image_id ? (string) wp_get_attachment_image_url( $desktop_image_id, 'full' ) : '';
		$mobile_bg_url  = $mobile_image_id ? (string) wp_get_attachment_image_url( $mobile_image_id, 'large' ) : '';
		$has_mobile_bg  = '' !== $mobile_bg_url && $mobile_image_id !== $desktop_image_id;

		$ctas = array();
		foreach ( array( 1, 2 ) as $i ) {
			$text = isset( $attributes[ "cta{$i}Text" ] ) ? trim( (string) $attributes[ "cta{$i}Text" ] ) : '';
			$url  = isset( $attributes[ "cta{$i}Url" ] ) ? trim( (string) $attributes[ "cta{$i}Url" ] ) : '';

			if ( '' === $text || '' === $url ) {
				continue;
			}

			$ctas[] = array(
				'text'       => $text,
				'url'        => $url,
				'bg_color'   => isset( $attributes[ "cta{$i}BgColor" ] ) ? (string) $attributes[ "cta{$i}BgColor" ] : '',
				'text_color' => isset( $attributes[ "cta{$i}TextColor" ] ) ? (string) $attributes[ "cta{$i}TextColor" ] : '',
				'variant'    => 1 === $i ? 'btn-primary' : 'btn-secondary',
			);
		}

		ob_start();
		?>
		<?php if ( $has_mobile_bg || ! empty( $ctas ) ) : ?>
			<style>
				<?php if ( $has_mobile_bg ) : ?>
					@media ( max-width: 767px ) {
						.procedure-hero { background-image: url( '<?php echo esc_url( $mobile_bg_url ); ?>' ); }
					}
				<?php endif; ?>
				<?php if ( ! empty( $ctas ) ) : ?>
					.procedure-hero__cta:hover { opacity: 0.8; }
				<?php endif; ?>
			</style>
		<?php endif; ?>
		<section class="procedure-hero"<?php echo '' !== $desktop_bg_url ? ' style="background-image: url(\'' . esc_url( $desktop_bg_url ) . '\');"' : ''; ?>>
			<div class="container">
				<div class="procedure-hero__card">
					<h1 class="procedure-hero__title"><?php echo esc_html( get_the_title() ); ?></h1>

					<?php if ( '' !== $subtitle ) : ?>
						<h2 class="procedure-hero__subtitle"><?php echo esc_html( $subtitle ); ?></h2>
					<?php endif; ?>

					<?php if ( '' !== $excerpt ) : ?>
						<div class="procedure-hero__excerpt"><?php echo wp_kses_post( $excerpt ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $ctas ) ) : ?>
						<p class="procedure-hero__ctas">
							<?php foreach ( $ctas as $button ) : ?>
								<?php
								$btn_style = '';
								if ( '' !== $button['bg_color'] ) {
									$btn_style .= 'background-color:' . $button['bg_color'] . ';';
								}
								if ( '' !== $button['text_color'] ) {
									$btn_style .= 'color:' . $button['text_color'] . ';';
								}
								?>
								<a
									href="<?php echo esc_url( $button['url'] ); ?>"
									class="btn <?php echo esc_attr( $button['variant'] ); ?> procedure-hero__cta"
									<?php echo '' !== $btn_style ? 'style="' . esc_attr( $btn_style ) . '"' : ''; ?>
								>
									<?php echo esc_html( $button['text'] ); ?>
								</a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
		return (string) ob_get_clean();
	}
}
