<?php
/**
 * Home section 7: "Your Surgeons" — queries the `surgeon` CPT.
 * Architecture/02-briefs/00-homepage.md, section 7 (non-negotiable: real
 * photo, full name, verifiable board certification — never fabricated here).
 *
 * @package WPPlasticSurgery
 */

use WPPlasticSurgery\Modules\Surgeon\SurgeonController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$truong_surgeons = new WP_Query(
	array(
		'post_type'      => SurgeonController::post_type(),
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);


?>
<section class="sbbl-section sbbl-surgeons bg-light">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2><?php esc_html_e( 'Our Surgeons', 'wp-plastic-surgery' ); ?></h2>
				<h3><?php esc_html_e( 'Real photos, full names, and verifiable board certification — every surgeon in the group, no exceptions.', 'wp-plastic-surgery' ); ?></h3>
			</div>
		</div>

		<div class="row sbbl-surgeons__grid">
			<?php
			while ( $truong_surgeons->have_posts() ) :
				$truong_surgeons->the_post();
				?>
				<div class="col-lg-3 col-md-6 col-xs-12 sbbl-surgeons__card" itemscope itemtype="https://schema.org/Physician">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'full', array( 'itemprop' => 'image' ) ); ?>
						<?php endif; ?>
						<h3 itemprop="name"><?php the_title(); ?></h3>
					</a>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<?php if(!is_page('our-surgeons')){ ?>
			<div class="row">
				<div class="col-xs-12">
					<p class="sbbl-section__links">
						<a href="<?php echo esc_url( home_url( '/our-surgeons/' ) ); ?>"><?php esc_html_e( 'Meet the full group', 'wp-plastic-surgery' ); ?></a>
					</p>
				</div>
			</div>
		<?php } ?>
	</div>
</section>
