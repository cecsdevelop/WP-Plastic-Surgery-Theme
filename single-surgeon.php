<?php
/**
 * Single template for the `surgeon` CPT.
 *
 * Structure loosely inspired by chicagoaesthetics.com/about/dr-sue-kafali/ —
 * a photo+name hero, then a narrative bio section, then a booking CTA —
 * rebuilt against our own data model rather than copied:
 * SurgeonController's doctor fields (name/description/image_id/
 * professional tags), credentials, and history gallery sections —
 * not that page's fixed fields.
 *
 * @package WPPlasticSurgery
 */

use WPPlasticSurgery\Modules\Surgeon\SurgeonController;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$truong_doctor = SurgeonController::get_doctor( get_the_ID() );
	$truong_tags   = SurgeonController::get_professional_tags( get_the_ID() );
	$truong_ctas   = SurgeonController::get_cta_buttons( get_the_ID() );

	$truong_name        = trim( $truong_doctor['name'] );
	$truong_description = trim( $truong_doctor['description'] );
	$truong_image_id    = $truong_doctor['image_id'];

	$truong_credentials = SurgeonController::get_credentials( get_the_ID() );

	$truong_credentials_title       = trim( $truong_credentials['title'] );
	$truong_credentials_subtitle    = trim( $truong_credentials['subtitle'] );
	$truong_credentials_description = trim( $truong_credentials['description'] );

	// Alphabetical by label, per section design.
	$truong_credentials_lists = array(
		array(
			'label' => __( 'Awards', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_awards( get_the_ID() ),
		),
		array(
			'label' => __( 'Board Certifications', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_board_certifications( get_the_ID() ),
		),
		array(
			'label' => __( 'Education', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_education( get_the_ID() ),
		),
		array(
			'label' => __( 'Fellowships and Honors', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_fellowships_and_honors( get_the_ID() ),
		),
		array(
			'label' => __( 'Memberships', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_memberships( get_the_ID() ),
		),
		array(
			'label' => __( 'Sub-certifications', 'wp-plastic-surgery' ),
			'items' => SurgeonController::get_sub_certifications( get_the_ID() ),
		),
	);

	$truong_has_credentials_lists = false;

	foreach ( $truong_credentials_lists as $truong_credentials_list ) {
		if ( ! empty( $truong_credentials_list['items'] ) ) {
			$truong_has_credentials_lists = true;
			break;
		}
	}

	$truong_history = SurgeonController::get_history( get_the_ID() );

	$truong_history_title       = trim( $truong_history['title'] );
	$truong_history_subtitle    = trim( $truong_history['subtitle'] );
	$truong_history_description = trim( $truong_history['description'] );
	$truong_history_gallery     = $truong_history['gallery'];

	$truong_procedure_ids = SurgeonController::get_procedure_ids( get_the_ID() );

	$truong_videos = SurgeonController::get_videos( get_the_ID() );

	$truong_results = SurgeonController::get_results( get_the_ID() );

	$truong_reviews         = SurgeonController::get_reviews( get_the_ID() );
	$truong_reviews_summary = SurgeonController::get_reviews_summary( get_the_ID() );
	$truong_review_labels   = SurgeonController::review_platform_labels();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'sbbl-surgeon' ); ?> itemscope itemtype="https://schema.org/Physician">
		<!--
		<section class="sbbl-section sbbl-surgeon-hero">
			<div class="container">
				<div class="row">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="col-lg-4 col-md-5 col-xs-12 sbbl-surgeon-hero__photo">
							<?php the_post_thumbnail( 'large', array( 'itemprop' => 'image', 'class' => 'sbbl-surgeon-hero__img' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="<?php echo has_post_thumbnail() ? 'col-lg-8 col-md-7 col-xs-12' : 'col-xs-12'; ?> sbbl-surgeon-hero__intro">
						<h1 itemprop="name"><?php the_title(); ?></h1>
					</div>
				</div>
			</div>
		</section>
		-->
		<?php if ( '' !== $truong_name || '' !== $truong_description ) : ?>
			<section class="sbbl-section sbbl-surgeon-block">
				<div class="container">
					<div class="row">
						<div class="<?php echo $truong_image_id ? 'col-lg-7 col-md-6 col-xs-12' : 'col-xs-12'; ?> sbbl-surgeon-block__text pl-1 pr-1">
							<?php if ( '' !== $truong_name ) : ?>
								<h2><?php echo esc_html( $truong_name ); ?></h2>
							<?php endif; ?>
							<?php if ( ! empty( $truong_tags ) ) : ?>
								<ul class="sbbl-surgeon-block__tags">
									<?php foreach ( $truong_tags as $truong_tag ) : ?>
										<li><?php echo esc_html( $truong_tag ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( '' !== $truong_description ) : ?>
								<div class="sbbl-surgeon-block__lead"><?php echo wp_kses_post( $truong_description ); ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $truong_ctas ) ) : ?>
								<div class="sbbl-surgeon-block__ctas">
									<?php foreach ( $truong_ctas as $truong_cta_index => $truong_cta ) : ?>
										<a href="<?php echo esc_url( $truong_cta['url'] ); ?>" class="btn <?php echo ( 0 === $truong_cta_index ) ? 'btn-primary' : 'btn-secondary'; ?>">
											<?php echo esc_html( $truong_cta['text'] ); ?>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( $truong_image_id ) : ?>
							<div class="col-lg-5 col-md-6 col-xs-12 sbbl-surgeon-block__image">
								<?php echo wp_get_attachment_image( $truong_image_id, 'large' ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$truong_show_credentials = '' !== $truong_credentials_title
			|| '' !== $truong_credentials_subtitle
			|| '' !== $truong_credentials_description
			|| $truong_has_credentials_lists;
		?>
		<?php if ( $truong_show_credentials ) : ?>
			<section class="sbbl-section sbbl-surgeon-credentials">
				<div class="container">
					<?php if ( '' !== $truong_credentials_title ) : ?>
						<h2><?php echo esc_html( $truong_credentials_title ); ?></h2>
					<?php endif; ?>
					<?php if ( '' !== $truong_credentials_subtitle ) : ?>
						<h3><?php echo esc_html( $truong_credentials_subtitle ); ?></h3>
					<?php endif; ?>
					<div class="row">
						<div class="col-lg-7 col-md-6 col-xs-12 sbbl-surgeon-credentials__text">
							<?php if ( '' !== $truong_credentials_description ) : ?>
								<div class="sbbl-surgeon-credentials__lead"><?php echo wp_kses_post( $truong_credentials_description ); ?></div>
							<?php endif; ?>
						</div>
						<?php if ( $truong_has_credentials_lists ) : ?>
							<div class="col-lg-5 col-md-6 col-xs-12 sbbl-surgeon-credentials__lists">
								<?php foreach ( $truong_credentials_lists as $truong_credentials_list ) : ?>
									<?php if ( ! empty( $truong_credentials_list['items'] ) ) : ?>
										<h4><?php echo esc_html( $truong_credentials_list['label'] ); ?></h4>
										<ul>
											<?php foreach ( $truong_credentials_list['items'] as $truong_credentials_item ) : ?>
												<li><?php echo esc_html( $truong_credentials_item ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$truong_show_history = '' !== $truong_history_title
			|| '' !== $truong_history_subtitle
			|| '' !== $truong_history_description
			|| ! empty( $truong_history_gallery );
		?>
		<?php if ( $truong_show_history ) : ?>
			<section class="sbbl-section sbbl-surgeon-history">
				<div class="container">
					<?php if ( '' !== $truong_history_title ) : ?>
						<h2><?php echo esc_html( $truong_history_title ); ?></h2>
					<?php endif; ?>
					<?php if ( '' !== $truong_history_subtitle ) : ?>
						<h3><?php echo esc_html( $truong_history_subtitle ); ?></h3>
					<?php endif; ?>
					<div class="row">
						<div class="<?php echo ! empty( $truong_history_gallery ) ? 'col-lg-7 col-md-6 col-xs-12' : 'col-xs-12'; ?> sbbl-surgeon-history__text">
							<?php if ( '' !== $truong_history_description ) : ?>
								<div class="sbbl-surgeon-history__lead"><?php echo wp_kses_post( $truong_history_description ); ?></div>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $truong_history_gallery ) ) : ?>
							<div class="col-lg-5 col-md-6 col-xs-12 sbbl-surgeon-history__gallery-col">
								<div class="sbbl-surgeon-history__gallery">
									<?php foreach ( $truong_history_gallery as $truong_gallery_index => $truong_gallery_image_id ) : ?>
										<?php
										$truong_gallery_thumb = wp_get_attachment_image_url( $truong_gallery_image_id, 'medium' );
										$truong_gallery_full  = wp_get_attachment_image_url( $truong_gallery_image_id, 'large' );

										if ( ! $truong_gallery_thumb || ! $truong_gallery_full ) {
											continue;
										}
										?>
										<button type="button" class="sbbl-surgeon-history__gallery-item" data-full="<?php echo esc_url( $truong_gallery_full ); ?>" data-index="<?php echo esc_attr( (string) $truong_gallery_index ); ?>">
											<img src="<?php echo esc_url( $truong_gallery_thumb ); ?>" alt="" loading="lazy" />
										</button>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
			<?php if ( ! empty( $truong_history_gallery ) ) : ?>
				<div class="sbbl-lightbox" data-lightbox hidden>
					<button type="button" class="sbbl-lightbox__close" aria-label="<?php esc_attr_e( 'Close', 'wp-plastic-surgery' ); ?>">&times;</button>
					<button type="button" class="sbbl-lightbox__prev" aria-label="<?php esc_attr_e( 'Previous image', 'wp-plastic-surgery' ); ?>">&#8249;</button>
					<img class="sbbl-lightbox__image" src="" alt="" />
					<button type="button" class="sbbl-lightbox__next" aria-label="<?php esc_attr_e( 'Next image', 'wp-plastic-surgery' ); ?>">&#8250;</button>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $truong_procedure_ids ) ) : ?>
			<section class="sbbl-section sbbl-surgeon-procedures">
				<div class="container">
					<h2>
						<?php
						printf(
							/* translators: %s: surgeon's name. */
							esc_html__( 'Procedures by %s', 'wp-plastic-surgery' ),
							esc_html( get_the_title() )
						);
						?>
					</h2>
					<div class="row sbbl-surgeon-procedures__grid">
						<?php foreach ( $truong_procedure_ids as $truong_procedure_id ) : ?>
							<div class="col-lg-3 col-md-6 col-xs-12 sbbl-surgeon-procedures__card">
								<h3><?php echo esc_html( get_the_title( $truong_procedure_id ) ); ?></h3>
								<?php $truong_procedure_excerpt = get_the_excerpt( $truong_procedure_id ); ?>
								<?php if ( '' !== $truong_procedure_excerpt ) : ?>
									<p><?php echo esc_html( $truong_procedure_excerpt ); ?></p>
								<?php endif; ?>
								<a href="<?php echo esc_url( get_permalink( $truong_procedure_id ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Learn More', 'wp-plastic-surgery' ); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $truong_videos ) ) : ?>
			<section class="sbbl-section sbbl-surgeon-videos">
				<div class="container">
					<h2>
						<?php
						printf(
							/* translators: %s: surgeon's name. */
							esc_html__( 'Videos by %s', 'wp-plastic-surgery' ),
							esc_html( get_the_title() )
						);
						?>
					</h2>
					<div class="sbbl-surgeon-videos__grid">
						<?php foreach ( $truong_videos as $truong_video ) : ?>
							<?php
							$truong_video_label = '' !== $truong_video['title']
								? sprintf(
									/* translators: %s: video title. */
									__( 'Play video: %s', 'wp-plastic-surgery' ),
									$truong_video['title']
								)
								: __( 'Play video', 'wp-plastic-surgery' );
							?>
							<button
								type="button"
								class="sbbl-surgeon-videos__item"
								data-type="<?php echo esc_attr( $truong_video['type'] ); ?>"
								<?php if ( 'upload' === $truong_video['type'] ) : ?>
									data-src="<?php echo esc_url( $truong_video['file_url'] ); ?>"
								<?php else : ?>
									data-video-id="<?php echo esc_attr( $truong_video['video_id'] ); ?>"
								<?php endif; ?>
								aria-label="<?php echo esc_attr( $truong_video_label ); ?>"
							>
								<img src="<?php echo esc_url( $truong_video['poster_url'] ); ?>" alt="" loading="lazy" />
								<span class="sbbl-surgeon-videos__play" aria-hidden="true">&#9658;</span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<div class="sbbl-video-modal" data-video-modal hidden>
				<button type="button" class="sbbl-video-modal__close" aria-label="<?php esc_attr_e( 'Close', 'wp-plastic-surgery' ); ?>">&times;</button>
				<div class="sbbl-video-modal__stage"></div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $truong_results['items'] ) ) : ?>
			<section class="sbbl-section sbbl-surgeon-results" data-surgeon-results data-post-id="<?php echo esc_attr( (string) get_the_ID() ); ?>" data-offset="<?php echo esc_attr( (string) count( $truong_results['items'] ) ); ?>">
				<div class="container">
					<h2>
						<?php
						printf(
							/* translators: %s: surgeon's name. */
							esc_html__( 'Results by %s', 'wp-plastic-surgery' ),
							esc_html( get_the_title() )
						);
						?>
					</h2>
					<div class="sbbl-surgeon-results__grid">
						<?php foreach ( $truong_results['items'] as $truong_result ) : ?>
							<?php SurgeonController::render_result_card( $truong_result ); ?>
						<?php endforeach; ?>
					</div>
					<?php if ( $truong_results['has_more'] ) : ?>
						<p class="sbbl-surgeon-results__load-more-wrap">
							<button type="button" class="btn btn-primary sbbl-surgeon-results__load-more"><?php esc_html_e( 'Load More', 'wp-plastic-surgery' ); ?></button>
						</p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $truong_reviews ) ) : ?>
			<section class="sbbl-section sbbl-surgeon-reviews">
				<div class="container">
					<h2>
						<?php
						printf(
							/* translators: %s: surgeon's name. */
							esc_html__( 'What Patients Are Saying About %s', 'wp-plastic-surgery' ),
							esc_html( get_the_title() )
						);
						?>
					</h2>
					<?php if ( $truong_reviews_summary['count'] > 0 ) : ?>
						<p class="sbbl-surgeon-reviews__summary" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
							<meta itemprop="ratingValue" content="<?php echo esc_attr( (string) $truong_reviews_summary['average'] ); ?>" />
							<meta itemprop="bestRating" content="5" />
							<meta itemprop="reviewCount" content="<?php echo esc_attr( (string) $truong_reviews_summary['count'] ); ?>" />
							<?php
							printf(
								/* translators: 1: average rating out of 5. 2: number of reviews. */
								esc_html__( '%1$s / 5 (%2$s reviews)', 'wp-plastic-surgery' ),
								esc_html( (string) $truong_reviews_summary['average'] ),
								esc_html( (string) $truong_reviews_summary['count'] )
							);
							?>
						</p>
					<?php endif; ?>
					<div class="sbbl-surgeon-reviews__grid">
						<?php foreach ( $truong_reviews as $truong_review ) : ?>
							<div class="sbbl-surgeon-reviews__card" itemprop="review" itemscope itemtype="https://schema.org/Review">
								<div class="sbbl-surgeon-reviews__stars" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
									<meta itemprop="ratingValue" content="<?php echo esc_attr( (string) $truong_review['rating'] ); ?>" />
									<meta itemprop="bestRating" content="5" />
									<?php echo esc_html( str_repeat( '★', $truong_review['rating'] ) . str_repeat( '☆', 5 - $truong_review['rating'] ) ); ?>
								</div>
								<p class="sbbl-surgeon-reviews__text" itemprop="reviewBody">&ldquo;<?php echo esc_html( $truong_review['review_text'] ); ?>&rdquo;</p>
								<p class="sbbl-surgeon-reviews__author" itemprop="author" itemscope itemtype="https://schema.org/Person">
									<span itemprop="name"><?php echo esc_html( $truong_review['reviewer_name'] ); ?></span>
									&mdash; <?php echo esc_html( $truong_review_labels[ $truong_review['platform'] ] ?? '' ); ?>
								</p>
								<?php if ( '' !== $truong_review['review_date'] ) : ?>
									<meta itemprop="datePublished" content="<?php echo esc_attr( $truong_review['review_date'] ); ?>" />
								<?php endif; ?>
								<?php if ( '' !== $truong_review['review_url'] ) : ?>
									<a href="<?php echo esc_url( $truong_review['review_url'] ); ?>" target="_blank" rel="nofollow noopener">
										<?php
										printf(
											/* translators: %s: review platform name, e.g. Google. */
											esc_html__( 'Read on %s', 'wp-plastic-surgery' ),
											esc_html( $truong_review_labels[ $truong_review['platform'] ] ?? '' )
										);
										?>
									</a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="sbbl-section sbbl-surgeon-cta bg-light">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<h2>
							<?php
							printf(
								/* translators: %s: surgeon's name. */
								esc_html__( 'Ready to talk to %s?', 'wp-plastic-surgery' ),
								esc_html( get_the_title() )
							);
							?>
						</h2>
						<?php get_template_part( 'template-parts/consult-cta' ); ?>
						<p class="sbbl-section__links">
							<a href="<?php echo esc_url( home_url( '/our-surgeons/' ) ); ?>"><?php esc_html_e( 'Meet the rest of the group', 'wp-plastic-surgery' ); ?></a>
						</p>
					</div>
				</div>
			</div>
		</section>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
