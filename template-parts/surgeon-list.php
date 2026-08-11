<?php
/**
 * Surgeon list/grid render — used by the wp-plastic-surgery/surgeon-list block.
 *
 * Uso:
 *   get_template_part( 'template-parts/surgeon-list', null, array(
 *       'layout'       => 'list', // or 'card'
 *       'columns'      => 3,      // 2, 3 or 4 — only used when layout is 'card'
 *       'excluded_ids' => array( 12, 34 ),
 *   ) );
 *
 * @package WPPlasticSurgery
 *
 * @var array{layout?: string, columns?: int, excluded_ids?: array<int, int>} $args
 */

use WPPlasticSurgery\Modules\Surgeon\SurgeonController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout       = isset( $args['layout'] ) && 'card' === $args['layout'] ? 'card' : 'list';
$columns      = isset( $args['columns'] ) ? (int) $args['columns'] : 3;
$columns      = in_array( $columns, array( 2, 3, 4 ), true ) ? $columns : 3;
$excluded_ids = isset( $args['excluded_ids'] ) && is_array( $args['excluded_ids'] ) ? array_map( 'intval', $args['excluded_ids'] ) : array();

$query_args = array(
	'post_type'      => SurgeonController::post_type(),
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order title',
	'order'          => 'ASC',
);

if ( ! empty( $excluded_ids ) ) {
	$query_args['post__not_in'] = $excluded_ids;
}

$truong_surgeon_list = new WP_Query( $query_args );

if ( ! $truong_surgeon_list->have_posts() ) {
	return;
}

$card_columns = array(
	2 => 'col-lg-6 col-md-6 col-xs-12',
	3 => 'col-lg-4 col-md-6 col-xs-12',
	4 => 'col-lg-3 col-md-6 col-xs-12',
);
?>
<div class="truong-surgeon-list truong-surgeon-list--<?php echo esc_attr( $layout ); ?><?php echo 'card' === $layout ? ' row' : ''; ?>">
	<?php
	while ( $truong_surgeon_list->have_posts() ) :
		$truong_surgeon_list->the_post();
		?>
		<?php if ( 'card' === $layout ) : ?>
			<div class="<?php echo esc_attr( $card_columns[ $columns ] ); ?> truong-surgeon-list__card" itemscope itemtype="https://schema.org/Physician">
				<div class="truong-surgeon-list__card-inner">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="truong-surgeon-list__image">
							<?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="truong-surgeon-list__content">
						<h3 class="truong-surgeon-list__name" itemprop="name"><?php the_title(); ?></h3>
						<?php if ( has_excerpt() ) : ?>
							<p class="truong-surgeon-list__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" class="btn btn-primary truong-surgeon-list__cta">
							<?php esc_html_e( 'View Profile', 'wp-plastic-surgery' ); ?>
						</a>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="row truong-surgeon-list__item" itemscope itemtype="https://schema.org/Physician">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="col-md-4 col-xs-12 truong-surgeon-list__image">
						<?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="<?php echo has_post_thumbnail() ? 'col-md-8' : 'col-md-12'; ?> col-xs-12 truong-surgeon-list__content">
					<h3 class="truong-surgeon-list__name" itemprop="name"><?php the_title(); ?></h3>
					<?php if ( has_excerpt() ) : ?>
						<p class="truong-surgeon-list__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="btn btn-primary truong-surgeon-list__cta">
						<?php esc_html_e( 'View Profile', 'wp-plastic-surgery' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	<?php endwhile; ?>
</div>
<?php
wp_reset_postdata();
