<?php
/**
 * Surgeon custom post type + doctor meta box (name/image/description/tags).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Surgeon;

use WPPlasticSurgery\Admin\BaseController;
use WPPlasticSurgery\Modules\Procedure\ProcedureController;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SurgeonController extends BaseController {

	private const POST_TYPE = 'surgeon';
	private const META_KEY  = '_surgeon_blocks';
	private const NONCE     = 'surgeon_blocks_nonce';

	private const TAGS_META_KEY = '_surgeon_professional_tags';
	private const CTA_META_KEY  = '_surgeon_ctas';

	private const CREDENTIALS_NONCE = 'surgeon_credentials_nonce';

	private const CREDENTIALS_TITLE_META_KEY       = '_surgeon_credentials_title';
	private const CREDENTIALS_SUBTITLE_META_KEY    = '_surgeon_credentials_subtitle';
	private const CREDENTIALS_DESCRIPTION_META_KEY = '_surgeon_credentials_description';

	private const BOARD_CERT_META_KEY  = '_surgeon_board_certifications';
	private const SUB_CERT_META_KEY    = '_surgeon_sub_certifications';
	private const AWARDS_META_KEY      = '_surgeon_awards';
	private const EDUCATION_META_KEY   = '_surgeon_education';
	private const FELLOWSHIPS_META_KEY = '_surgeon_fellowships_honors';
	private const MEMBERSHIPS_META_KEY = '_surgeon_memberships';

	private const HISTORY_NONCE = 'surgeon_history_nonce';

	private const HISTORY_TITLE_META_KEY       = '_surgeon_history_title';
	private const HISTORY_SUBTITLE_META_KEY    = '_surgeon_history_subtitle';
	private const HISTORY_DESCRIPTION_META_KEY = '_surgeon_history_description';
	private const HISTORY_GALLERY_META_KEY     = '_surgeon_history_gallery';

	private const HISTORY_GALLERY_MAX = 9;

	private const PROCEDURES_NONCE    = 'surgeon_procedures_nonce';
	private const PROCEDURES_META_KEY = '_surgeon_procedures';

	private const VIDEOS_NONCE    = 'surgeon_videos_nonce';
	private const VIDEOS_META_KEY = '_surgeon_videos';
	private const VIDEOS_MAX      = 9;
	private const VIDEO_TYPES     = array( 'youtube', 'vimeo', 'upload' );

	private const REVIEWS_NONCE    = 'surgeon_reviews_nonce';
	private const REVIEWS_META_KEY = '_surgeon_reviews';
	private const REVIEW_PLATFORMS = array( 'google', 'realself', 'yelp', 'facebook' );

	private const RESULTS_NONCE          = 'surgeon_results_nonce';
	private const RESULTS_TERM_META_KEY  = '_surgeon_patient_gallery_term_id';
	private const RESULTS_TAXONOMY       = 'patient_surgeon';
	private const RESULTS_POST_TYPE      = 'patient_gallery';
	private const RESULTS_IMAGE_META_KEY = '_patient_images';
	private const RESULTS_PER_PAGE       = 12;

	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_credentials_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_history_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_procedures_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_videos_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_reviews_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_results_meta_box' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_meta' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_credentials' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_history' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_procedures' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_videos' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_reviews' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_results' ) );
		add_action( 'wp_ajax_surgeon_load_more_results', array( $this, 'ajax_load_more_results' ) );
		add_action( 'wp_ajax_nopriv_surgeon_load_more_results', array( $this, 'ajax_load_more_results' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * @return array<string, string>
	 */
	public static function review_platform_labels(): array {
		return array(
			'google'   => __( 'Google', 'wp-plastic-surgery' ),
			'realself' => __( 'RealSelf', 'wp-plastic-surgery' ),
			'yelp'     => __( 'Yelp', 'wp-plastic-surgery' ),
			'facebook' => __( 'Facebook', 'wp-plastic-surgery' ),
		);
	}

	/**
	 * Doctor fields for a given Surgeon post, ready for front-end render.
	 *
	 * @return array{name: string, description: string, image_id: int}
	 */
	public static function get_doctor( int $post_id ): array {
		$empty = array(
			'name'        => '',
			'description' => '',
			'image_id'    => 0,
		);

		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return $empty;
		}

		$block = self::normalize_stored_doctor( get_post_meta( $post_id, self::META_KEY, true ) );

		return array(
			'name'        => (string) ( $block['name'] ?? '' ),
			'description' => (string) ( $block['description'] ?? '' ),
			'image_id'    => absint( $block['image_id'] ?? 0 ),
		);
	}

	/**
	 * Accepts both the current flat storage shape and the legacy
	 * "array of blocks" shape (`[0 => ['name' => ..., ...]]`) from when
	 * this section was multipliable, so existing post meta keeps working
	 * until it's next saved and normalized to the flat shape.
	 */
	private static function normalize_stored_doctor( $stored ): array {
		if ( ! is_array( $stored ) ) {
			return array();
		}

		if ( isset( $stored[0] ) && is_array( $stored[0] ) ) {
			return $stored[0];
		}

		return $stored;
	}

	/**
	 * Professional tags for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_professional_tags( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$items = get_post_meta( $post_id, self::TAGS_META_KEY, true );

		return is_array( $items ) ? $items : array();
	}

	/**
	 * CTA buttons for a given Surgeon post, ready for front-end render.
	 * Only returns buttons that have both text and a valid URL — a button
	 * missing either is dropped, so the front end can show one, the
	 * other, both, or neither without extra checks.
	 *
	 * @return array<int, array{text: string, url: string}>
	 */
	public static function get_cta_buttons( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$stored = get_post_meta( $post_id, self::CTA_META_KEY, true );
		$stored = is_array( $stored ) ? $stored : array();
		$buttons = array();

		foreach ( $stored as $cta ) {
			$text = trim( (string) ( $cta['text'] ?? '' ) );
			$url  = trim( (string) ( $cta['url'] ?? '' ) );

			if ( '' === $text || '' === $url || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
				continue;
			}

			$buttons[] = array(
				'text' => $text,
				'url'  => $url,
			);
		}

		return $buttons;
	}

	/**
	 * Credentials section header fields for a given Surgeon post, ready for front-end render.
	 *
	 * @return array{title: string, subtitle: string, description: string}
	 */
	public static function get_credentials( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array(
				'title'       => '',
				'subtitle'    => '',
				'description' => '',
			);
		}

		return array(
			'title'       => (string) get_post_meta( $post_id, self::CREDENTIALS_TITLE_META_KEY, true ),
			'subtitle'    => (string) get_post_meta( $post_id, self::CREDENTIALS_SUBTITLE_META_KEY, true ),
			'description' => (string) get_post_meta( $post_id, self::CREDENTIALS_DESCRIPTION_META_KEY, true ),
		);
	}

	/**
	 * Board certifications for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_board_certifications( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::BOARD_CERT_META_KEY );
	}

	/**
	 * Sub-certifications for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_sub_certifications( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::SUB_CERT_META_KEY );
	}

	/**
	 * Awards for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_awards( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::AWARDS_META_KEY );
	}

	/**
	 * Education entries for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_education( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::EDUCATION_META_KEY );
	}

	/**
	 * Fellowships and honors entries for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_fellowships_and_honors( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::FELLOWSHIPS_META_KEY );
	}

	/**
	 * Memberships for a given Surgeon post, ready for front-end render.
	 *
	 * @return array<int, string>
	 */
	public static function get_memberships( int $post_id ): array {
		return self::get_credentials_list( $post_id, self::MEMBERSHIPS_META_KEY );
	}

	/**
	 * @return array<int, string>
	 */
	private static function get_credentials_list( int $post_id, string $meta_key ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$items = get_post_meta( $post_id, $meta_key, true );

		return is_array( $items ) ? $items : array();
	}

	/**
	 * History section fields for a given Surgeon post, ready for front-end render.
	 *
	 * @return array{title: string, subtitle: string, description: string, gallery: array<int, int>}
	 */
	public static function get_history( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array(
				'title'       => '',
				'subtitle'    => '',
				'description' => '',
				'gallery'     => array(),
			);
		}

		$gallery = get_post_meta( $post_id, self::HISTORY_GALLERY_META_KEY, true );

		return array(
			'title'       => (string) get_post_meta( $post_id, self::HISTORY_TITLE_META_KEY, true ),
			'subtitle'    => (string) get_post_meta( $post_id, self::HISTORY_SUBTITLE_META_KEY, true ),
			'description' => (string) get_post_meta( $post_id, self::HISTORY_DESCRIPTION_META_KEY, true ),
			'gallery'     => is_array( $gallery ) ? array_map( 'absint', $gallery ) : array(),
		);
	}

	/**
	 * Selected Procedure post IDs for a given Surgeon post, ready for front-end
	 * render — filtered to published Procedure posts only, in stored display order.
	 *
	 * @return array<int, int>
	 */
	public static function get_procedure_ids( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$ids = get_post_meta( $post_id, self::PROCEDURES_META_KEY, true );
		$ids = is_array( $ids ) ? array_map( 'absint', $ids ) : array();

		return array_values(
			array_filter(
				$ids,
				static function ( $procedure_id ) {
					return ProcedureController::post_type() === get_post_type( $procedure_id )
						&& 'publish' === get_post_status( $procedure_id );
				}
			)
		);
	}

	/**
	 * Videos for a given Surgeon post, ready for front-end render. Each item
	 * always has a poster (enforced at save time), plus either a `video_id`
	 * (youtube/vimeo) or a `file_url` (upload).
	 *
	 * @return array<int, array{type: string, poster_url: string, title: string, video_id?: string, file_url?: string}>
	 */
	public static function get_videos( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$stored = get_post_meta( $post_id, self::VIDEOS_META_KEY, true );
		$stored = is_array( $stored ) ? $stored : array();
		$videos = array();

		foreach ( $stored as $item ) {
			$type = (string) ( $item['type'] ?? '' );

			if ( ! in_array( $type, self::VIDEO_TYPES, true ) ) {
				continue;
			}

			$poster_id  = absint( $item['poster_id'] ?? 0 );
			$poster_url = $poster_id ? wp_get_attachment_image_url( $poster_id, 'medium' ) : '';

			if ( ! $poster_url ) {
				continue;
			}

			$title = (string) ( $item['title'] ?? '' );

			if ( 'upload' === $type ) {
				$attachment_id = absint( $item['attachment_id'] ?? 0 );
				$file_url      = $attachment_id ? wp_get_attachment_url( $attachment_id ) : '';

				if ( ! $file_url ) {
					continue;
				}

				$videos[] = array(
					'type'       => 'upload',
					'file_url'   => $file_url,
					'poster_url' => $poster_url,
					'title'      => $title,
				);

				continue;
			}

			$video_id = (string) ( $item['video_id'] ?? '' );

			if ( '' === $video_id ) {
				continue;
			}

			$videos[] = array(
				'type'       => $type,
				'video_id'   => $video_id,
				'poster_url' => $poster_url,
				'title'      => $title,
			);
		}

		return $videos;
	}

	/**
	 * Reviews for a given Surgeon post, ready for front-end render. Already
	 * validated/sanitized at save time (see save_reviews()).
	 *
	 * @return array<int, array{platform: string, reviewer_name: string, rating: int, review_text: string, review_date: string, review_url: string}>
	 */
	public static function get_reviews( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$reviews = get_post_meta( $post_id, self::REVIEWS_META_KEY, true );

		return is_array( $reviews ) ? $reviews : array();
	}

	/**
	 * @return array{average: float, count: int}
	 */
	public static function get_reviews_summary( int $post_id ): array {
		$reviews = self::get_reviews( $post_id );
		$count   = count( $reviews );

		if ( 0 === $count ) {
			return array(
				'average' => 0.0,
				'count'   => 0,
			);
		}

		$total = 0;

		foreach ( $reviews as $review ) {
			$total += (int) ( $review['rating'] ?? 0 );
		}

		return array(
			'average' => round( $total / $count, 1 ),
			'count'   => $count,
		);
	}

	/**
	 * The `patient_surgeon` taxonomy term (from the Patient Gallery plugin)
	 * this Surgeon post is mapped to, for the Results section. 0 if unmapped.
	 */
	public static function get_results_term_id( int $post_id ): int {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return 0;
		}

		return absint( get_post_meta( $post_id, self::RESULTS_TERM_META_KEY, true ) );
	}

	/**
	 * A page of before/after Results for a given Surgeon post, ready for
	 * front-end render, plus whether a further page exists. `has_more` is
	 * computed from the raw tax_query match count (WP_Query::$found_posts),
	 * not the filtered item count, so a page containing a case with no
	 * resolvable image never causes "Load More" to disappear early.
	 *
	 * @return array{items: array<int, array{id: int, title: string, thumbnail_url: string, permalink: string}>, has_more: bool}
	 */
	public static function get_results( int $post_id, int $limit = self::RESULTS_PER_PAGE, int $offset = 0 ): array {
		$term_id = self::get_results_term_id( $post_id );

		if ( 0 === $term_id || ! taxonomy_exists( self::RESULTS_TAXONOMY ) ) {
			return array(
				'items'    => array(),
				'has_more' => false,
			);
		}

		$query = new \WP_Query(
			array(
				'post_type'      => self::RESULTS_POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'offset'         => $offset,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => self::RESULTS_TAXONOMY,
						'field'    => 'term_id',
						'terms'    => $term_id,
					),
				),
			)
		);

		$items = array();

		foreach ( $query->posts as $result_post ) {
			$card = self::build_result_card_data( $result_post );

			if ( null !== $card ) {
				$items[] = $card;
			}
		}

		return array(
			'items'    => $items,
			'has_more' => ( $offset + $limit ) < $query->found_posts,
		);
	}

	/**
	 * @return array{id: int, title: string, thumbnail_url: string, permalink: string}|null
	 */
	private static function build_result_card_data( \WP_Post $post ): ?array {
		$raw   = get_post_meta( $post->ID, self::RESULTS_IMAGE_META_KEY, true );
		$pairs = $raw ? json_decode( (string) $raw, true ) : array();

		$thumbnail_url = '';

		if ( is_array( $pairs ) ) {
			foreach ( $pairs as $pair ) {
				if ( ! empty( $pair['after'] ) && is_string( $pair['after'] ) ) {
					$thumbnail_url = $pair['after'];
					break;
				}

				if ( '' === $thumbnail_url && ! empty( $pair['before'] ) && is_string( $pair['before'] ) ) {
					$thumbnail_url = $pair['before'];
				}
			}
		}

		if ( '' === $thumbnail_url ) {
			return null;
		}

		return array(
			'id'            => $post->ID,
			'title'         => get_the_title( $post ),
			'thumbnail_url' => $thumbnail_url,
			'permalink'     => (string) get_permalink( $post ),
		);
	}

	/**
	 * @param array{id: int, title: string, thumbnail_url: string, permalink: string} $item
	 */
	public static function render_result_card( array $item ): void {
		?>
		<a href="<?php echo esc_url( $item['permalink'] ); ?>" class="sbbl-surgeon-results__card">
			<img src="<?php echo esc_url( $item['thumbnail_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" />
		</a>
		<?php
	}

	public static function post_type(): string {
		return self::POST_TYPE;
	}

	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => __( 'Surgeons', 'wp-plastic-surgery' ),
					'singular_name' => __( 'Surgeon', 'wp-plastic-surgery' ),
					'add_new_item'  => __( 'Add Surgeon', 'wp-plastic-surgery' ),
					'edit_item'     => __( 'Edit Surgeon', 'wp-plastic-surgery' ),
					'all_items'     => __( 'Surgeons', 'wp-plastic-surgery' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => false,
				'menu_icon'    => 'dashicons-businessman',
				'supports'     => array( 'title', 'thumbnail', 'excerpt' ),
				'rewrite'      => array( 'slug' => 'our-surgeons' ),
			)
		);
	}

	public function register_meta_box(): void {
		add_meta_box(
			'surgeon_blocks_box',
			__( 'About Doctor', 'wp-plastic-surgery' ),
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	public function render_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$doctor = self::get_doctor( $post->ID );

		$stored_ctas = get_post_meta( $post->ID, self::CTA_META_KEY, true );
		$stored_ctas = is_array( $stored_ctas ) ? $stored_ctas : array();

		$this->render_doctor_block_fields(
			$doctor['name'],
			$doctor['description'],
			$doctor['image_id'],
			$this->get_meta_list( $post->ID, self::TAGS_META_KEY ),
			$stored_ctas
		);
	}

	/**
	 * @param array<int, string> $tags
	 * @param array<int, array{text?: string, url?: string}> $ctas
	 */
	private function render_doctor_block_fields( string $name, string $description, int $image_id, array $tags, array $ctas ): void {
		?>
		<div class="surgeon-doctor-block">
			<p>
				<label><?php esc_html_e( 'Doctor Name', 'wp-plastic-surgery' ); ?></label>
				<input type="text" class="widefat" name="surgeon_name" value="<?php echo esc_attr( $name ); ?>" />
			</p>

			<div class="surgeon-doctor-block__tags">
				<label><?php esc_html_e( 'Professional Tags', 'wp-plastic-surgery' ); ?></label>
				<?php
				$this->render_text_list_meta_box(
					'surgeon_professional_tags',
					$tags,
					__( 'e.g. Board-Certified Plastic Surgeon', 'wp-plastic-surgery' )
				);
				?>
			</div>

			<div class="surgeon-doctor-block__columns">
				<div class="surgeon-doctor-block__col surgeon-doctor-block__col--image">
					<label><?php esc_html_e( 'Image', 'wp-plastic-surgery' ); ?></label>
					<?php $this->render_image_field( 'surgeon_image_id', $image_id ); ?>
				</div>
				<div class="surgeon-doctor-block__col surgeon-doctor-block__col--content">
					<p>
						<label><?php esc_html_e( 'Description (text or HTML)', 'wp-plastic-surgery' ); ?></label>
						<textarea class="widefat" rows="3" name="surgeon_description"><?php echo esc_textarea( $description ); ?></textarea>
					</p>
				</div>
			</div>

			<div class="surgeon-doctor-block__ctas">
				<?php foreach ( array( 1, 2 ) as $cta_number ) : ?>
					<?php
					$cta      = $ctas[ $cta_number - 1 ] ?? array();
					$cta_text = (string) ( $cta['text'] ?? '' );
					$cta_url  = (string) ( $cta['url'] ?? '' );
					?>
					<div class="surgeon-doctor-block__cta">
						<p>
							<label>
								<?php
								printf(
									/* translators: %d: CTA button number (1 or 2). */
									esc_html__( 'CTA Button %d Text', 'wp-plastic-surgery' ),
									(int) $cta_number
								);
								?>
							</label>
							<input type="text" class="widefat" name="surgeon_cta_<?php echo esc_attr( (string) $cta_number ); ?>_text" value="<?php echo esc_attr( $cta_text ); ?>" />
						</p>
						<p>
							<label>
								<?php
								printf(
									/* translators: %d: CTA button number (1 or 2). */
									esc_html__( 'CTA Button %d URL', 'wp-plastic-surgery' ),
									(int) $cta_number
								);
								?>
							</label>
							<input type="url" class="widefat" name="surgeon_cta_<?php echo esc_attr( (string) $cta_number ); ?>_url" value="<?php echo esc_attr( $cta_url ); ?>" placeholder="https://" />
						</p>
					</div>
				<?php endforeach; ?>
				<p class="description"><?php esc_html_e( 'Each button only shows on the front end once it has both a text and a valid URL.', 'wp-plastic-surgery' ); ?></p>
			</div>
		</div>
		<?php
	}

	public function register_credentials_meta_box(): void {
		add_meta_box(
			'surgeon_credentials_box',
			__( 'Credentials', 'wp-plastic-surgery' ),
			array( $this, 'render_credentials_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_credentials_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::CREDENTIALS_NONCE, self::CREDENTIALS_NONCE );

		$credentials = self::get_credentials( $post->ID );
		?>
		<p>
			<label><?php esc_html_e( 'Title', 'wp-plastic-surgery' ); ?></label>
			<input type="text" class="widefat" name="surgeon_credentials_title" value="<?php echo esc_attr( $credentials['title'] ); ?>" />
		</p>
		<p>
			<label><?php esc_html_e( 'Subtitle', 'wp-plastic-surgery' ); ?></label>
			<input type="text" class="widefat" name="surgeon_credentials_subtitle" value="<?php echo esc_attr( $credentials['subtitle'] ); ?>" />
		</p>
		<p>
			<label><?php esc_html_e( 'Description', 'wp-plastic-surgery' ); ?></label>
			<textarea class="widefat" rows="3" name="surgeon_credentials_description"><?php echo esc_textarea( $credentials['description'] ); ?></textarea>
		</p>

		<h3><?php esc_html_e( 'Board Certifications', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_board_certifications',
			$this->get_meta_list( $post->ID, self::BOARD_CERT_META_KEY ),
			__( 'e.g. American Board of Plastic Surgery', 'wp-plastic-surgery' )
		);
		?>
		<h3><?php esc_html_e( 'Sub-certifications', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_sub_certifications',
			$this->get_meta_list( $post->ID, self::SUB_CERT_META_KEY ),
			__( 'e.g. Subspecialty Certification in Hand Surgery', 'wp-plastic-surgery' )
		);
		?>
		<h3><?php esc_html_e( 'Awards', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_awards',
			$this->get_meta_list( $post->ID, self::AWARDS_META_KEY ),
			__( 'e.g. Top Doctor, Castle Connolly', 'wp-plastic-surgery' )
		);
		?>
		<h3><?php esc_html_e( 'Education', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_education',
			$this->get_meta_list( $post->ID, self::EDUCATION_META_KEY ),
			__( 'e.g. Northwestern University Feinberg School of Medicine, M.D.', 'wp-plastic-surgery' )
		);
		?>
		<h3><?php esc_html_e( 'Fellowships and Honors', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_fellowships_honors',
			$this->get_meta_list( $post->ID, self::FELLOWSHIPS_META_KEY ),
			__( 'e.g. Aesthetic Surgery Fellowship, American Society of Plastic Surgeons', 'wp-plastic-surgery' )
		);
		?>
		<h3><?php esc_html_e( 'Memberships', 'wp-plastic-surgery' ); ?></h3>
		<?php
		$this->render_text_list_meta_box(
			'surgeon_memberships',
			$this->get_meta_list( $post->ID, self::MEMBERSHIPS_META_KEY ),
			__( 'e.g. American Society of Plastic Surgeons (ASPS)', 'wp-plastic-surgery' )
		);
	}

	public function save_credentials( int $post_id ): void {
		if ( ! isset( $_POST[ self::CREDENTIALS_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::CREDENTIALS_NONCE ] ), self::CREDENTIALS_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$title       = isset( $_POST['surgeon_credentials_title'] ) ? sanitize_text_field( wp_unslash( $_POST['surgeon_credentials_title'] ) ) : '';
		$subtitle    = isset( $_POST['surgeon_credentials_subtitle'] ) ? sanitize_text_field( wp_unslash( $_POST['surgeon_credentials_subtitle'] ) ) : '';
		$description = isset( $_POST['surgeon_credentials_description'] ) ? wp_kses_post( wp_unslash( $_POST['surgeon_credentials_description'] ) ) : '';

		update_post_meta( $post_id, self::CREDENTIALS_TITLE_META_KEY, $title );
		update_post_meta( $post_id, self::CREDENTIALS_SUBTITLE_META_KEY, $subtitle );
		update_post_meta( $post_id, self::CREDENTIALS_DESCRIPTION_META_KEY, $description );

		$this->save_text_list_value( 'surgeon_board_certifications', self::BOARD_CERT_META_KEY, $post_id );
		$this->save_text_list_value( 'surgeon_sub_certifications', self::SUB_CERT_META_KEY, $post_id );
		$this->save_text_list_value( 'surgeon_awards', self::AWARDS_META_KEY, $post_id );
		$this->save_text_list_value( 'surgeon_education', self::EDUCATION_META_KEY, $post_id );
		$this->save_text_list_value( 'surgeon_fellowships_honors', self::FELLOWSHIPS_META_KEY, $post_id );
		$this->save_text_list_value( 'surgeon_memberships', self::MEMBERSHIPS_META_KEY, $post_id );
	}

	public function register_history_meta_box(): void {
		add_meta_box(
			'surgeon_history_box',
			__( 'History', 'wp-plastic-surgery' ),
			array( $this, 'render_history_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_history_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::HISTORY_NONCE, self::HISTORY_NONCE );

		$history = self::get_history( $post->ID );
		?>
		<p>
			<label><?php esc_html_e( 'Title', 'wp-plastic-surgery' ); ?></label>
			<input type="text" class="widefat" name="surgeon_history_title" value="<?php echo esc_attr( $history['title'] ); ?>" />
		</p>
		<p>
			<label><?php esc_html_e( 'Subtitle', 'wp-plastic-surgery' ); ?></label>
			<input type="text" class="widefat" name="surgeon_history_subtitle" value="<?php echo esc_attr( $history['subtitle'] ); ?>" />
		</p>
		<p>
			<label><?php esc_html_e( 'Description', 'wp-plastic-surgery' ); ?></label>
			<?php
			wp_editor(
				$history['description'],
				'surgeon_history_description',
				array(
					'textarea_name' => 'surgeon_history_description',
					'textarea_rows' => 8,
					'media_buttons' => false,
					'teeny'         => true,
				)
			);
			?>
		</p>
		<p>
			<?php
			printf(
				/* translators: %d: maximum number of gallery images. */
				esc_html__( 'Gallery (up to %d images)', 'wp-plastic-surgery' ),
				(int) self::HISTORY_GALLERY_MAX
			);
			?>
		</p>
		<?php $this->render_gallery_field( 'surgeon_history_gallery', $history['gallery'] ); ?>
		<?php
	}

	public function save_history( int $post_id ): void {
		if ( ! isset( $_POST[ self::HISTORY_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::HISTORY_NONCE ] ), self::HISTORY_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$title       = isset( $_POST['surgeon_history_title'] ) ? sanitize_text_field( wp_unslash( $_POST['surgeon_history_title'] ) ) : '';
		$subtitle    = isset( $_POST['surgeon_history_subtitle'] ) ? sanitize_text_field( wp_unslash( $_POST['surgeon_history_subtitle'] ) ) : '';
		$description = isset( $_POST['surgeon_history_description'] ) ? wp_kses_post( wp_unslash( $_POST['surgeon_history_description'] ) ) : '';

		$gallery_raw = isset( $_POST['surgeon_history_gallery'] ) && is_array( $_POST['surgeon_history_gallery'] ) ? wp_unslash( $_POST['surgeon_history_gallery'] ) : array();
		$gallery     = array();

		foreach ( $gallery_raw as $value ) {
			$image_id = absint( $value );

			if ( 0 === $image_id || in_array( $image_id, $gallery, true ) ) {
				continue;
			}

			$gallery[] = $image_id;

			if ( count( $gallery ) >= self::HISTORY_GALLERY_MAX ) {
				break;
			}
		}

		update_post_meta( $post_id, self::HISTORY_TITLE_META_KEY, $title );
		update_post_meta( $post_id, self::HISTORY_SUBTITLE_META_KEY, $subtitle );
		update_post_meta( $post_id, self::HISTORY_DESCRIPTION_META_KEY, $description );
		update_post_meta( $post_id, self::HISTORY_GALLERY_META_KEY, $gallery );
	}

	public function register_procedures_meta_box(): void {
		add_meta_box(
			'surgeon_procedures_box',
			__( 'Procedures', 'wp-plastic-surgery' ),
			array( $this, 'render_procedures_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_procedures_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::PROCEDURES_NONCE, self::PROCEDURES_NONCE );

		$selected = self::get_procedure_ids( $post->ID );
		$groups   = $this->get_procedure_groups();

		if ( empty( $groups ) ) {
			?>
			<p><?php esc_html_e( 'No procedures found. Add some under Procedures first.', 'wp-plastic-surgery' ); ?></p>
			<?php
			return;
		}
		?>
		<div class="surgeon-procedures-checklist">
			<?php foreach ( $groups as $group ) : ?>
				<div class="surgeon-procedures-checklist__group" style="margin-left: <?php echo esc_attr( (string) ( $group['depth'] * 20 ) ); ?>px;">
					<p class="surgeon-procedures-checklist__label"><strong><?php echo esc_html( $group['label'] ); ?></strong></p>
					<ul>
						<?php foreach ( $group['procedure_ids'] as $procedure_id ) : ?>
							<li>
								<label>
									<input type="checkbox" name="surgeon_procedures[]" value="<?php echo esc_attr( (string) $procedure_id ); ?>" <?php checked( in_array( $procedure_id, $selected, true ) ); ?> />
									<?php echo esc_html( get_the_title( $procedure_id ) ); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function save_procedures( int $post_id ): void {
		if ( ! isset( $_POST[ self::PROCEDURES_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::PROCEDURES_NONCE ] ), self::PROCEDURES_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$raw   = isset( $_POST['surgeon_procedures'] ) && is_array( $_POST['surgeon_procedures'] ) ? wp_unslash( $_POST['surgeon_procedures'] ) : array();
		$clean = array();

		foreach ( $raw as $value ) {
			$procedure_id = absint( $value );

			if ( ProcedureController::post_type() !== get_post_type( $procedure_id ) ) {
				continue;
			}

			if ( ! in_array( $procedure_id, $clean, true ) ) {
				$clean[] = $procedure_id;
			}
		}

		update_post_meta( $post_id, self::PROCEDURES_META_KEY, $clean );
	}

	/**
	 * Procedures grouped by Procedure Category, ordered alphabetically within
	 * each hierarchy level (parent categories before their children), plus a
	 * trailing "Uncategorized" group for procedures with no assigned category.
	 * Checkbox submission order in render_procedures_meta_box() follows this
	 * same order, so the stored selection is already display-ready.
	 *
	 * @return array<int, array{label: string, depth: int, procedure_ids: array<int, int>}>
	 */
	private function get_procedure_groups(): array {
		$terms = get_terms(
			array(
				'taxonomy'   => ProcedureController::taxonomy(),
				'hide_empty' => false,
			)
		);

		$ordered_terms = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$by_parent = array();

			foreach ( $terms as $term ) {
				$by_parent[ $term->parent ][] = $term;
			}

			foreach ( $by_parent as &$siblings ) {
				usort(
					$siblings,
					static function ( $a, $b ) {
						return strcasecmp( $a->name, $b->name );
					}
				);
			}
			unset( $siblings );

			$this->flatten_procedure_terms( 0, 0, $by_parent, $ordered_terms );
		}

		$groups       = array();
		$assigned_ids = array();

		foreach ( $ordered_terms as $entry ) {
			$procedure_ids = get_posts(
				array(
					'post_type'      => ProcedureController::post_type(),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'fields'         => 'ids',
					'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy'         => ProcedureController::taxonomy(),
							'field'            => 'term_id',
							'terms'            => $entry['term']->term_id,
							'include_children' => false,
						),
					),
				)
			);

			if ( empty( $procedure_ids ) ) {
				continue;
			}

			$assigned_ids = array_merge( $assigned_ids, $procedure_ids );

			$groups[] = array(
				'label'         => $entry['term']->name,
				'depth'         => $entry['depth'],
				'procedure_ids' => $procedure_ids,
			);
		}

		$uncategorized_ids = get_posts(
			array(
				'post_type'      => ProcedureController::post_type(),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'post__not_in'   => ! empty( $assigned_ids ) ? $assigned_ids : array( 0 ),
			)
		);

		if ( ! empty( $uncategorized_ids ) ) {
			$groups[] = array(
				'label'         => __( 'Uncategorized', 'wp-plastic-surgery' ),
				'depth'         => 0,
				'procedure_ids' => $uncategorized_ids,
			);
		}

		return $groups;
	}

	/**
	 * @param array<int, array<int, \WP_Term>> $by_parent
	 * @param array<int, array{term: \WP_Term, depth: int}> $ordered
	 */
	private function flatten_procedure_terms( int $parent_id, int $depth, array $by_parent, array &$ordered ): void {
		if ( empty( $by_parent[ $parent_id ] ) ) {
			return;
		}

		foreach ( $by_parent[ $parent_id ] as $term ) {
			$ordered[] = array(
				'term'  => $term,
				'depth' => $depth,
			);

			$this->flatten_procedure_terms( $term->term_id, $depth + 1, $by_parent, $ordered );
		}
	}

	public function register_videos_meta_box(): void {
		add_meta_box(
			'surgeon_videos_box',
			__( 'Videos', 'wp-plastic-surgery' ),
			array( $this, 'render_videos_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_videos_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::VIDEOS_NONCE, self::VIDEOS_NONCE );

		$videos = get_post_meta( $post->ID, self::VIDEOS_META_KEY, true );
		$videos = is_array( $videos ) && ! empty( $videos ) ? $videos : array( $this->empty_video_item() );
		?>
		<div class="surgeon-videos-field" data-max="<?php echo esc_attr( (string) self::VIDEOS_MAX ); ?>">
			<div class="surgeon-videos-field__rows">
				<?php foreach ( $videos as $index => $video ) : ?>
					<?php $this->render_video_row( (string) $index, $video ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button surgeon-videos-field__add"><?php esc_html_e( 'Add video', 'wp-plastic-surgery' ); ?></button></p>
			<template class="surgeon-videos-field__template">
				<?php $this->render_video_row( '__INDEX__', $this->empty_video_item() ); ?>
			</template>
			<p class="description"><?php esc_html_e( 'Each video needs a poster image before it will show on the front end. Up to 9.', 'wp-plastic-surgery' ); ?></p>
		</div>
		<?php
	}

	/**
	 * @return array{type: string, url: string, attachment_id: int, poster_id: int, title: string}
	 */
	private function empty_video_item(): array {
		return array(
			'type'          => 'youtube',
			'url'           => '',
			'attachment_id' => 0,
			'poster_id'     => 0,
			'title'         => '',
		);
	}

	/**
	 * @param array{type?: string, url?: string, attachment_id?: int, poster_id?: int, title?: string} $video
	 */
	private function render_video_row( string $index, array $video ): void {
		$type          = in_array( $video['type'] ?? '', self::VIDEO_TYPES, true ) ? $video['type'] : 'youtube';
		$url           = (string) ( $video['url'] ?? '' );
		$attachment_id = absint( $video['attachment_id'] ?? 0 );
		$poster_id     = absint( $video['poster_id'] ?? 0 );
		$title         = (string) ( $video['title'] ?? '' );
		$field         = "surgeon_videos[{$index}]";
		?>
		<div class="surgeon-videos-field__row">
			<button type="button" class="button-link-delete surgeon-videos-field__remove"><?php esc_html_e( 'Remove video', 'wp-plastic-surgery' ); ?></button>
			<div class="surgeon-videos-field__col">
				<p>
					<label><?php esc_html_e( 'Source', 'wp-plastic-surgery' ); ?></label>
					<select class="surgeon-videos-field__type" name="<?php echo esc_attr( $field ); ?>[type]">
						<option value="youtube" <?php selected( $type, 'youtube' ); ?>><?php esc_html_e( 'YouTube', 'wp-plastic-surgery' ); ?></option>
						<option value="vimeo" <?php selected( $type, 'vimeo' ); ?>><?php esc_html_e( 'Vimeo', 'wp-plastic-surgery' ); ?></option>
						<option value="upload" <?php selected( $type, 'upload' ); ?>><?php esc_html_e( 'Uploaded file', 'wp-plastic-surgery' ); ?></option>
					</select>
				</p>
				<p class="surgeon-videos-field__url-field"<?php echo ( 'upload' === $type ) ? ' style="display:none;"' : ''; ?>>
					<label><?php esc_html_e( 'Video URL', 'wp-plastic-surgery' ); ?></label>
					<input type="url" class="widefat" name="<?php echo esc_attr( $field ); ?>[url]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://www.youtube.com/watch?v=..." />
				</p>
				<div class="surgeon-videos-field__upload-field"<?php echo ( 'upload' !== $type ) ? ' style="display:none;"' : ''; ?>>
					<label><?php esc_html_e( 'Video file', 'wp-plastic-surgery' ); ?></label>
					<?php $this->render_video_file_field( "{$field}[attachment_id]", $attachment_id ); ?>
				</div>
				<p>
					<label><?php esc_html_e( 'Title (optional)', 'wp-plastic-surgery' ); ?></label>
					<input type="text" class="widefat" name="<?php echo esc_attr( $field ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" />
				</p>
			</div>
			<div class="surgeon-videos-field__col">
				<label><?php esc_html_e( 'Poster / thumbnail (required)', 'wp-plastic-surgery' ); ?></label>
				<?php $this->render_image_field( "{$field}[poster_id]", $poster_id ); ?>
			</div>
		</div>
		<?php
	}

	private function render_video_file_field( string $field_name, int $attachment_id ): void {
		$filename    = $attachment_id ? wp_basename( (string) get_attached_file( $attachment_id ) ) : '';
		$placeholder = __( 'No file selected', 'wp-plastic-surgery' );
		?>
		<div class="surgeon-video-field">
			<span class="surgeon-video-field__filename" data-placeholder="<?php echo esc_attr( $placeholder ); ?>"><?php echo $filename ? esc_html( $filename ) : esc_html( $placeholder ); ?></span>
			<input type="hidden" class="surgeon-video-field__input" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( (string) $attachment_id ); ?>" />
			<p>
				<button type="button" class="button surgeon-video-field__select"><?php esc_html_e( 'Select video', 'wp-plastic-surgery' ); ?></button>
				<button type="button" class="button-link-delete surgeon-video-field__remove"<?php echo $attachment_id ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
			</p>
		</div>
		<?php
	}

	public function save_videos( int $post_id ): void {
		if ( ! isset( $_POST[ self::VIDEOS_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::VIDEOS_NONCE ] ), self::VIDEOS_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$raw   = isset( $_POST['surgeon_videos'] ) && is_array( $_POST['surgeon_videos'] ) ? wp_unslash( $_POST['surgeon_videos'] ) : array();
		$clean = array();

		foreach ( $raw as $item ) {
			if ( count( $clean ) >= self::VIDEOS_MAX || ! is_array( $item ) ) {
				break;
			}

			$type = isset( $item['type'] ) && in_array( $item['type'], self::VIDEO_TYPES, true ) ? $item['type'] : '';

			if ( '' === $type ) {
				continue;
			}

			$poster_id = isset( $item['poster_id'] ) ? absint( $item['poster_id'] ) : 0;

			// Poster is required — an item without one never renders, so don't store it.
			if ( 0 === $poster_id ) {
				continue;
			}

			$title = isset( $item['title'] ) ? sanitize_text_field( $item['title'] ) : '';

			if ( 'upload' === $type ) {
				$attachment_id = isset( $item['attachment_id'] ) ? absint( $item['attachment_id'] ) : 0;

				if ( 0 === $attachment_id || ! wp_attachment_is( 'video', $attachment_id ) ) {
					continue;
				}

				$clean[] = array(
					'type'          => 'upload',
					'attachment_id' => $attachment_id,
					'poster_id'     => $poster_id,
					'title'         => $title,
				);

				continue;
			}

			$url      = isset( $item['url'] ) ? esc_url_raw( $item['url'] ) : '';
			$video_id = 'youtube' === $type ? self::extract_youtube_id( $url ) : self::extract_vimeo_id( $url );

			if ( '' === $video_id ) {
				continue;
			}

			$clean[] = array(
				'type'      => $type,
				'url'       => $url,
				'video_id'  => $video_id,
				'poster_id' => $poster_id,
				'title'     => $title,
			);
		}

		update_post_meta( $post_id, self::VIDEOS_META_KEY, $clean );
	}

	private static function extract_youtube_id( string $url ): string {
		if ( preg_match( '#(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})#', $url, $matches ) ) {
			return $matches[1];
		}

		return '';
	}

	private static function extract_vimeo_id( string $url ): string {
		if ( preg_match( '#vimeo\.com/(?:.*/)?(\d+)#', $url, $matches ) ) {
			return $matches[1];
		}

		return '';
	}

	public function register_reviews_meta_box(): void {
		add_meta_box(
			'surgeon_reviews_box',
			__( 'Reviews', 'wp-plastic-surgery' ),
			array( $this, 'render_reviews_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_reviews_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::REVIEWS_NONCE, self::REVIEWS_NONCE );

		$reviews = get_post_meta( $post->ID, self::REVIEWS_META_KEY, true );
		$reviews = is_array( $reviews ) && ! empty( $reviews ) ? $reviews : array( $this->empty_review_item() );
		?>
		<div class="surgeon-reviews-field">
			<div class="surgeon-reviews-field__rows">
				<?php foreach ( $reviews as $index => $review ) : ?>
					<?php $this->render_review_row( (string) $index, $review ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button surgeon-reviews-field__add"><?php esc_html_e( 'Add review', 'wp-plastic-surgery' ); ?></button></p>
			<template class="surgeon-reviews-field__template">
				<?php $this->render_review_row( '__INDEX__', $this->empty_review_item() ); ?>
			</template>
			<p class="description"><?php esc_html_e( 'Copy the reviewer name, rating, and text from the original review (Google, RealSelf, Yelp, or Facebook). A link back to the original review is optional but recommended.', 'wp-plastic-surgery' ); ?></p>
		</div>
		<?php
	}

	/**
	 * @return array{platform: string, reviewer_name: string, rating: int, review_text: string, review_date: string, review_url: string}
	 */
	private function empty_review_item(): array {
		return array(
			'platform'      => 'google',
			'reviewer_name' => '',
			'rating'        => 5,
			'review_text'   => '',
			'review_date'   => '',
			'review_url'    => '',
		);
	}

	/**
	 * @param array{platform?: string, reviewer_name?: string, rating?: int, review_text?: string, review_date?: string, review_url?: string} $review
	 */
	private function render_review_row( string $index, array $review ): void {
		$platform = in_array( $review['platform'] ?? '', self::REVIEW_PLATFORMS, true ) ? $review['platform'] : 'google';
		$name     = (string) ( $review['reviewer_name'] ?? '' );
		$rating   = absint( $review['rating'] ?? 5 );
		$rating   = ( $rating >= 1 && $rating <= 5 ) ? $rating : 5;
		$text     = (string) ( $review['review_text'] ?? '' );
		$date     = (string) ( $review['review_date'] ?? '' );
		$url      = (string) ( $review['review_url'] ?? '' );
		$field    = "surgeon_reviews[{$index}]";
		?>
		<div class="surgeon-reviews-field__row">
			<button type="button" class="button-link-delete surgeon-reviews-field__remove"><?php esc_html_e( 'Remove review', 'wp-plastic-surgery' ); ?></button>
			<div class="surgeon-reviews-field__grid">
				<p>
					<label><?php esc_html_e( 'Platform', 'wp-plastic-surgery' ); ?></label>
					<select name="<?php echo esc_attr( $field ); ?>[platform]">
						<?php foreach ( self::review_platform_labels() as $slug => $label ) : ?>
							<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $platform, $slug ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p>
					<label><?php esc_html_e( 'Reviewer Name', 'wp-plastic-surgery' ); ?></label>
					<input type="text" class="widefat" name="<?php echo esc_attr( $field ); ?>[reviewer_name]" value="<?php echo esc_attr( $name ); ?>" />
				</p>
				<p>
					<label><?php esc_html_e( 'Rating', 'wp-plastic-surgery' ); ?></label>
					<select name="<?php echo esc_attr( $field ); ?>[rating]">
						<?php foreach ( range( 1, 5 ) as $stars ) : ?>
							<option value="<?php echo esc_attr( (string) $stars ); ?>" <?php selected( $rating, $stars ); ?>>
								<?php echo esc_html( str_repeat( '★', $stars ) . str_repeat( '☆', 5 - $stars ) ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>
				<p>
					<label><?php esc_html_e( 'Date (optional)', 'wp-plastic-surgery' ); ?></label>
					<input type="date" name="<?php echo esc_attr( $field ); ?>[review_date]" value="<?php echo esc_attr( $date ); ?>" />
				</p>
				<p>
					<label><?php esc_html_e( 'Source URL (optional)', 'wp-plastic-surgery' ); ?></label>
					<input type="url" class="widefat" name="<?php echo esc_attr( $field ); ?>[review_url]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://..." />
				</p>
			</div>
			<p>
				<label><?php esc_html_e( 'Review Text', 'wp-plastic-surgery' ); ?></label>
				<textarea class="widefat" rows="3" name="<?php echo esc_attr( $field ); ?>[review_text]"><?php echo esc_textarea( $text ); ?></textarea>
			</p>
		</div>
		<?php
	}

	public function save_reviews( int $post_id ): void {
		if ( ! isset( $_POST[ self::REVIEWS_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::REVIEWS_NONCE ] ), self::REVIEWS_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$raw   = isset( $_POST['surgeon_reviews'] ) && is_array( $_POST['surgeon_reviews'] ) ? wp_unslash( $_POST['surgeon_reviews'] ) : array();
		$clean = array();

		foreach ( $raw as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$platform = isset( $item['platform'] ) && in_array( $item['platform'], self::REVIEW_PLATFORMS, true ) ? $item['platform'] : '';
			$name     = isset( $item['reviewer_name'] ) ? sanitize_text_field( $item['reviewer_name'] ) : '';
			$text     = isset( $item['review_text'] ) ? sanitize_textarea_field( $item['review_text'] ) : '';
			$rating   = isset( $item['rating'] ) ? absint( $item['rating'] ) : 0;

			// Platform, reviewer name, review text, and a 1-5 rating are all required — a review missing any of these isn't useful/renderable.
			if ( '' === $platform || '' === $name || '' === $text || $rating < 1 || $rating > 5 ) {
				continue;
			}

			$date = '';

			if ( ! empty( $item['review_date'] ) ) {
				$timestamp = strtotime( (string) $item['review_date'] );
				$date      = $timestamp ? gmdate( 'Y-m-d', $timestamp ) : '';
			}

			$url = ! empty( $item['review_url'] ) ? esc_url_raw( $item['review_url'] ) : '';

			$clean[] = array(
				'platform'      => $platform,
				'reviewer_name' => $name,
				'rating'        => $rating,
				'review_text'   => $text,
				'review_date'   => $date,
				'review_url'    => $url,
			);
		}

		update_post_meta( $post_id, self::REVIEWS_META_KEY, $clean );
	}

	public function register_results_meta_box(): void {
		if ( ! taxonomy_exists( self::RESULTS_TAXONOMY ) ) {
			return;
		}

		add_meta_box(
			'surgeon_results_box',
			__( 'Results', 'wp-plastic-surgery' ),
			array( $this, 'render_results_meta_box' ),
			self::POST_TYPE,
			'normal',
			'default'
		);
	}

	public function render_results_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::RESULTS_NONCE, self::RESULTS_NONCE );

		$selected = self::get_results_term_id( $post->ID );
		$terms    = get_terms(
			array(
				'taxonomy'   => self::RESULTS_TAXONOMY,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			?>
			<p><?php esc_html_e( 'No surgeon terms found in the Patient Gallery plugin.', 'wp-plastic-surgery' ); ?></p>
			<?php
			return;
		}
		?>
		<p class="description"><?php esc_html_e( 'Pick which Patient Gallery "Surgeon" term this doctor corresponds to, to show their before/after results on the front end.', 'wp-plastic-surgery' ); ?></p>
		<ul class="surgeon-results-terms">
			<li>
				<label>
					<input type="radio" name="surgeon_results_term_id" value="0" <?php checked( 0, $selected ); ?> />
					<?php esc_html_e( 'None', 'wp-plastic-surgery' ); ?>
				</label>
			</li>
			<?php foreach ( $terms as $term ) : ?>
				<li>
					<label>
						<input type="radio" name="surgeon_results_term_id" value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php checked( $term->term_id, $selected ); ?> />
						<?php echo esc_html( $term->name ); ?>
						<span class="description">
							<?php
							printf(
								/* translators: %d: number of cases tagged with this surgeon term. */
								esc_html( _n( '(%d case)', '(%d cases)', $term->count, 'wp-plastic-surgery' ) ),
								(int) $term->count
							);
							?>
						</span>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	public function save_results( int $post_id ): void {
		if ( ! isset( $_POST[ self::RESULTS_NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::RESULTS_NONCE ] ), self::RESULTS_NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$term_id = isset( $_POST['surgeon_results_term_id'] ) ? absint( $_POST['surgeon_results_term_id'] ) : 0;

		if ( 0 !== $term_id && ( ! taxonomy_exists( self::RESULTS_TAXONOMY ) || ! term_exists( $term_id, self::RESULTS_TAXONOMY ) ) ) {
			$term_id = 0;
		}

		update_post_meta( $post_id, self::RESULTS_TERM_META_KEY, $term_id );
	}

	/**
	 * AJAX "Load More" for the Results grid. Read-only (no capability check
	 * needed beyond the nonce) — mirrors the same query/render path as the
	 * server-rendered first page, so there's exactly one place that decides
	 * what a Results card looks like.
	 */
	public function ajax_load_more_results(): void {
		check_ajax_referer( 'surgeon_load_more_results', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$offset  = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;

		if ( 0 === $post_id ) {
			wp_die();
		}

		$result = self::get_results( $post_id, self::RESULTS_PER_PAGE, $offset );

		foreach ( $result['items'] as $item ) {
			self::render_result_card( $item );
		}

		printf( '<span data-has-more="%s"></span>', $result['has_more'] ? '1' : '0' );

		wp_die();
	}

	/**
	 * @param array<int, int> $image_ids
	 */
	private function render_gallery_field( string $field_name, array $image_ids ): void {
		?>
		<div class="surgeon-gallery-field" data-field="<?php echo esc_attr( $field_name ); ?>" data-max="<?php echo esc_attr( (string) self::HISTORY_GALLERY_MAX ); ?>" data-remove-label="<?php echo esc_attr__( 'Remove', 'wp-plastic-surgery' ); ?>">
			<div class="surgeon-gallery-field__grid">
				<?php foreach ( $image_ids as $image_id ) : ?>
					<?php $this->render_gallery_field_item( $field_name, $image_id ); ?>
				<?php endforeach; ?>
			</div>
			<p>
				<button type="button" class="button surgeon-gallery-field__add"><?php esc_html_e( 'Add images', 'wp-plastic-surgery' ); ?></button>
			</p>
		</div>
		<?php
	}

	private function render_gallery_field_item( string $field_name, int $image_id ): void {
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
		?>
		<div class="surgeon-gallery-field__item">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="" />
			<input type="hidden" name="<?php echo esc_attr( $field_name ); ?>[]" value="<?php echo esc_attr( (string) $image_id ); ?>" />
			<button type="button" class="button-link-delete surgeon-gallery-field__remove"><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
		</div>
		<?php
	}

	/**
	 * Renders a media-picker image field (preview + hidden ID input + select/remove
	 * buttons), shared by the About Doctor and History image fields.
	 */
	private function render_image_field( string $field_name, int $image_id ): void {
		$image_url   = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
		$placeholder = __( 'No image selected', 'wp-plastic-surgery' );
		?>
		<div class="surgeon-image-field">
			<div class="surgeon-image-field__preview" data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="" />
				<?php else : ?>
					<span class="surgeon-image-field__placeholder"><?php echo esc_html( $placeholder ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" class="surgeon-image-field__input" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( (string) $image_id ); ?>" />
			<p>
				<button type="button" class="button surgeon-image-field__select"><?php esc_html_e( 'Select image', 'wp-plastic-surgery' ); ?></button>
				<button type="button" class="button-link-delete surgeon-image-field__remove"<?php echo $image_id ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
			</p>
		</div>
		<?php
	}

	private function get_meta_list( int $post_id, string $meta_key ): array {
		$items = get_post_meta( $post_id, $meta_key, true );

		return is_array( $items ) ? $items : array();
	}

	/**
	 * Renders a "list of text inputs" repeater (add/remove rows), used by
	 * the Professional Tags field and each Credentials sub-section.
	 *
	 * @param array<int, string> $items
	 */
	private function render_text_list_meta_box( string $field_name, array $items, string $placeholder ): void {
		if ( empty( $items ) ) {
			$items = array( '' );
		}
		?>
		<div class="simple-text-repeater" data-index="<?php echo esc_attr( (string) count( $items ) ); ?>">
			<div class="simple-text-repeater__rows">
				<?php foreach ( $items as $index => $value ) : ?>
					<?php $this->render_text_list_row( $field_name, (string) $index, (string) $value, $placeholder ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button simple-text-repeater__add"><?php esc_html_e( '+ Add item', 'wp-plastic-surgery' ); ?></button></p>
			<template class="simple-text-repeater__template">
				<?php $this->render_text_list_row( $field_name, '__INDEX__', '', $placeholder ); ?>
			</template>
		</div>
		<?php
	}

	private function render_text_list_row( string $field_name, string $index, string $value, string $placeholder ): void {
		?>
		<div class="simple-text-repeater__row">
			<input type="text" class="widefat" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="<?php echo esc_attr( $field_name ); ?>[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
			<button type="button" class="button-link-delete simple-text-repeater__remove"><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
		</div>
		<?php
	}

	/**
	 * Sanitizes and saves one text-list field. Assumes the caller already
	 * verified the nonce/capability/autosave guards for the whole meta box.
	 */
	private function save_text_list_value( string $field_name, string $meta_key, int $post_id ): void {
		$raw   = isset( $_POST[ $field_name ] ) && is_array( $_POST[ $field_name ] ) ? wp_unslash( $_POST[ $field_name ] ) : array();
		$clean = array();

		foreach ( $raw as $value ) {
			$value = sanitize_text_field( $value );

			if ( '' === $value ) {
				continue;
			}

			$clean[] = $value;
		}

		update_post_meta( $post_id, $meta_key, $clean );
	}

	public function save_meta( int $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE ] ), self::NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$name        = isset( $_POST['surgeon_name'] ) ? sanitize_text_field( wp_unslash( $_POST['surgeon_name'] ) ) : '';
		$description = isset( $_POST['surgeon_description'] ) ? wp_kses_post( wp_unslash( $_POST['surgeon_description'] ) ) : '';
		$image_id    = isset( $_POST['surgeon_image_id'] ) ? absint( $_POST['surgeon_image_id'] ) : 0;

		$is_empty = '' === $name
			&& 0 === $image_id
			&& '' === trim( wp_strip_all_tags( $description ) );

		update_post_meta(
			$post_id,
			self::META_KEY,
			$is_empty ? array() : array(
				'name'        => $name,
				'description' => $description,
				'image_id'    => $image_id,
			)
		);

		$this->save_text_list_value( 'surgeon_professional_tags', self::TAGS_META_KEY, $post_id );

		$ctas = array();

		foreach ( array( 1, 2 ) as $cta_number ) {
			$text_field = "surgeon_cta_{$cta_number}_text";
			$url_field  = "surgeon_cta_{$cta_number}_url";

			$ctas[] = array(
				'text' => isset( $_POST[ $text_field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $text_field ] ) ) : '',
				'url'  => isset( $_POST[ $url_field ] ) ? esc_url_raw( wp_unslash( $_POST[ $url_field ] ) ) : '',
			);
		}

		update_post_meta( $post_id, self::CTA_META_KEY, $ctas );
	}

	public function enqueue_admin_assets( string $hook ): void {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen || self::POST_TYPE !== $screen->post_type ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style(
			'surgeon-doctor-block',
			$this->uri() . 'assets/css/admin-surgeon-doctor-block.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-doctor-block.css' )
		);

		wp_enqueue_script(
			'surgeon-doctor-block',
			$this->uri() . 'assets/js/admin-surgeon-doctor-block.js',
			array(),
			$this->asset_version( 'assets/js/admin-surgeon-doctor-block.js' ),
			true
		);

		wp_enqueue_style(
			'simple-text-repeater',
			$this->uri() . 'assets/css/admin-simple-text-repeater.css',
			array(),
			$this->asset_version( 'assets/css/admin-simple-text-repeater.css' )
		);

		wp_enqueue_script(
			'simple-text-repeater',
			$this->uri() . 'assets/js/admin-simple-text-repeater.js',
			array(),
			$this->asset_version( 'assets/js/admin-simple-text-repeater.js' ),
			true
		);

		wp_enqueue_style(
			'surgeon-gallery-field',
			$this->uri() . 'assets/css/admin-surgeon-gallery-field.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-gallery-field.css' )
		);

		wp_enqueue_script(
			'surgeon-gallery-field',
			$this->uri() . 'assets/js/admin-surgeon-gallery-field.js',
			array(),
			$this->asset_version( 'assets/js/admin-surgeon-gallery-field.js' ),
			true
		);

		wp_enqueue_style(
			'surgeon-procedures',
			$this->uri() . 'assets/css/admin-surgeon-procedures.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-procedures.css' )
		);

		wp_enqueue_style(
			'surgeon-videos-field',
			$this->uri() . 'assets/css/admin-surgeon-videos-field.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-videos-field.css' )
		);

		wp_enqueue_script(
			'surgeon-videos-field',
			$this->uri() . 'assets/js/admin-surgeon-videos-field.js',
			array(),
			$this->asset_version( 'assets/js/admin-surgeon-videos-field.js' ),
			true
		);

		wp_enqueue_style(
			'surgeon-reviews-field',
			$this->uri() . 'assets/css/admin-surgeon-reviews-field.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-reviews-field.css' )
		);

		wp_enqueue_script(
			'surgeon-reviews-field',
			$this->uri() . 'assets/js/admin-surgeon-reviews-field.js',
			array(),
			$this->asset_version( 'assets/js/admin-surgeon-reviews-field.js' ),
			true
		);

		wp_enqueue_style(
			'surgeon-results',
			$this->uri() . 'assets/css/admin-surgeon-results.css',
			array(),
			$this->asset_version( 'assets/css/admin-surgeon-results.css' )
		);
	}
}
