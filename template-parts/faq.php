<?php
/**
 * FAQ block render — reusable across templates.
 *
 * Uso:
 *   get_template_part( 'template-parts/faq', null, array( 'faq_id' => 123 ) );
 *   get_template_part( 'template-parts/faq', null, array( 'faq_slug' => 'envios' ) );
 *   get_template_part( 'template-parts/faq' ); // usa el post actual si es CPT `faq`
 *
 * @package WPPlasticSurgery
 *
 * @var array{faq_id?: int, faq_slug?: string} $args
 */

use WPPlasticSurgery\Modules\Faq\FaqController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_id = 0;

if ( ! empty( $args['faq_slug'] ) ) {
	$found = get_page_by_path( $args['faq_slug'], OBJECT, FaqController::post_type() );
	$faq_id = $found ? $found->ID : 0;
} elseif ( ! empty( $args['faq_id'] ) ) {
	$faq_id = absint( $args['faq_id'] );
} else {
	$faq_id = get_the_ID();
}

$items = $faq_id ? FaqController::get_items( $faq_id ) : array();

if ( empty( $items ) ) {
	return;
}
?>
<section class="faq-block" itemscope itemtype="https://schema.org/FAQPage">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2>Faq</h2>
			</div>
		</div>
		<div class="row">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$question = trim( (string) ( $item['question'] ?? '' ) );
				$answer   = trim( (string) ( $item['answer'] ?? '' ) );

				if ( '' === $question && '' === $answer ) {
					continue;
				}
				?>
				<details class="faq-block__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
					<summary class="faq-block__question" itemprop="name"><?php echo esc_html( $question ); ?></summary>
					<div class="faq-block__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<div itemprop="text"><?php echo wp_kses_post( $answer ); ?></div>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
