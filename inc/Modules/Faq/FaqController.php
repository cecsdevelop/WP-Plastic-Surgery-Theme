<?php
/**
 * FAQ custom post type + repeater meta box.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Faq;

use WPPlasticSurgery\Admin\BaseController;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class FaqController extends BaseController {

	private const POST_TYPE = 'faq';
	private const META_KEY  = '_faq_items';
	private const NONCE     = 'faq_items_nonce';

	/**
	 * Slug of the seeded FAQ post used by the homepage FAQ section.
	 */
	public const HOME_FAQ_SLUG = 'skinny-bbl-home-faq';

	/**
	 * Slug of the seeded FAQ post used by /country-club-bbl/.
	 */
	public const COUNTRY_CLUB_BBL_FAQ_SLUG = 'country-club-bbl-faq';

	/**
	 * Slug of the seeded FAQ post used by /am-i-too-skinny-for-a-bbl/.
	 */
	public const TOO_SKINNY_FAQ_SLUG = 'am-i-too-skinny-for-a-bbl-faq';

	/**
	 * Slug of the seeded FAQ post used by /skinny-bbl-vs-bbl/.
	 */
	public const VS_BBL_FAQ_SLUG = 'skinny-bbl-vs-bbl-faq';

	/**
	 * Slug of the seeded FAQ post used by /skinny-bbl-cost/.
	 */
	public const COST_FAQ_SLUG = 'skinny-bbl-cost-faq';

	/**
	 * Slug of the seeded FAQ post used by /skinny-bbl-recovery/.
	 */
	public const RECOVERY_FAQ_SLUG = 'skinny-bbl-recovery-faq';

	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'save_meta' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( $this, 'seed_home_faq' ) );
		add_action( 'admin_init', array( $this, 'seed_country_club_bbl_faq' ) );
		add_action( 'admin_init', array( $this, 'seed_too_skinny_faq' ) );
		add_action( 'admin_init', array( $this, 'seed_vs_bbl_faq' ) );
		add_action( 'admin_init', array( $this, 'seed_cost_faq' ) );
		add_action( 'admin_init', array( $this, 'seed_recovery_faq' ) );
	}

	/**
	 * One-time seed of the 10 homepage FAQ questions from
	 * Architecture/02-briefs/00-homepage.md, section 8. Answers are left as
	 * an explicit pending marker — the brief only drafted the questions.
	 * No-ops once the post exists.
	 */
	public function seed_home_faq(): void {
		$this->seed_faq(
			self::HOME_FAQ_SLUG,
			__( 'Skinny BBL — Homepage FAQ', 'wp-plastic-surgery' ),
			array(
				"Can you get a BBL if you're skinny?",
				"What's the minimum BMI for a skinny BBL?",
				'Is a skinny BBL safe?',
				'How much fat do you need for a BBL?',
				'Will I need a second session?',
				'How long do skinny BBL results last?',
				'Can I sit after a skinny BBL?',
				'How much does a skinny BBL cost?',
				'Is a skinny BBL the same as a Sculptra BBL?',
				'Do I need to gain weight before a skinny BBL?',
			)
		);
	}

	/**
	 * One-time seed of the 6 FAQ questions from
	 * Architecture/02-briefs/04-country-club-bbl.md, section 6. Answers are
	 * left as an explicit pending marker — the brief only drafted the
	 * questions. No-ops once the post exists.
	 */
	public function seed_country_club_bbl_faq(): void {
		$this->seed_faq(
			self::COUNTRY_CLUB_BBL_FAQ_SLUG,
			__( 'Country Club BBL — FAQ', 'wp-plastic-surgery' ),
			array(
				'What is a country club BBL?',
				'Is a country club BBL the same as a natural BBL?',
				'How much fat is transferred in a country club BBL?',
				'Will people be able to tell?',
				"Can you get a country club BBL if you're slim?",
				'How long do the results last?',
			)
		);
	}

	/**
	 * One-time seed of the 8 FAQ questions from
	 * Architecture/02-briefs/03-am-i-too-skinny.md, section 7. Answers are
	 * left as an explicit pending marker — the brief only drafted the
	 * questions. No-ops once the post exists.
	 */
	public function seed_too_skinny_faq(): void {
		$this->seed_faq(
			self::TOO_SKINNY_FAQ_SLUG,
			__( 'Am I Too Skinny for a BBL? — FAQ', 'wp-plastic-surgery' ),
			array(
				'What is the minimum BMI for a BBL?',
				"Can you get a BBL if you're skinny?",
				'How much fat do you need for a BBL?',
				'Do I need to gain weight before a BBL?',
				'Can you get a BBL with no fat at all?',
				'Is a BBL riskier for thin patients?',
				'Will I need a second session?',
				'What if only one donor area has enough fat?',
			)
		);
	}

	/**
	 * One-time seed of the 7 FAQ questions from
	 * Architecture/02-briefs/02-skinny-bbl-vs-bbl.md, section 8. Answers are
	 * left as an explicit pending marker — the brief only drafted the
	 * questions. No-ops once the post exists.
	 */
	public function seed_vs_bbl_faq(): void {
		$this->seed_faq(
			self::VS_BBL_FAQ_SLUG,
			__( 'Skinny BBL vs Traditional BBL — FAQ', 'wp-plastic-surgery' ),
			array(
				'Is a skinny BBL the same as a regular BBL?',
				'Which one lasts longer?',
				'Is a skinny BBL more expensive?',
				"Can I have a traditional BBL if I'm slim?",
				"What's the difference between a skinny BBL and a natural BBL?",
				'Does a skinny BBL hurt more?',
				'Will I need a second session?',
			)
		);
	}

	/**
	 * One-time seed of the 8 FAQ questions from
	 * Architecture/02-briefs/01-skinny-bbl-cost.md, section 8. Answers are
	 * left as an explicit pending marker — the brief only drafted the
	 * questions. No-ops once the post exists.
	 */
	public function seed_cost_faq(): void {
		$this->seed_faq(
			self::COST_FAQ_SLUG,
			__( 'Skinny BBL Cost — FAQ', 'wp-plastic-surgery' ),
			array(
				'How much does a skinny BBL cost in 2026?',
				'Why is a skinny BBL more expensive than a regular BBL?',
				'Does insurance cover a skinny BBL?',
				'Can I finance a skinny BBL?',
				'How much is a skinny BBL in Chicago?',
				'What\'s the cheapest a skinny BBL should cost?',
				'Does the price include a second session if needed?',
				'Are consultations free?',
			)
		);
	}

	/**
	 * One-time seed of the 10 FAQ questions from
	 * Architecture/02-briefs/06-skinny-bbl-recovery.md, section 11. Answers
	 * are left as an explicit pending marker — the brief only drafted the
	 * questions. No-ops once the post exists.
	 */
	public function seed_recovery_faq(): void {
		$this->seed_faq(
			self::RECOVERY_FAQ_SLUG,
			__( 'Skinny BBL Recovery — FAQ', 'wp-plastic-surgery' ),
			array(
				'How long is skinny BBL recovery?',
				'When can I sit after a skinny BBL?',
				'When can I go back to work?',
				'When can I exercise again?',
				'How long do I wear compression garments?',
				'How long do I wear foams and boards?',
				'Why do my donor areas hurt more than my buttocks?',
				'When will I see my final result?',
				'Is swelling normal after 4 weeks?',
				'Can I sleep on my back after a BBL?',
			)
		);
	}

	/**
	 * Creates a `faq` post with pending-answer placeholders for each question,
	 * unless a post with this slug already exists.
	 *
	 * @param array<int, string> $questions
	 */
	private function seed_faq( string $slug, string $title, array $questions ): void {
		if ( get_page_by_path( $slug, OBJECT, self::POST_TYPE ) ) {
			return;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => self::POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return;
		}

		$pending = __( '[VERIFY] Pending clinical write-up.', 'wp-plastic-surgery' );
		$items   = array();

		foreach ( $questions as $question ) {
			$items[] = array(
				'question' => $question,
				'answer'   => $pending,
			);
		}

		update_post_meta( $post_id, self::META_KEY, $items );
	}

	/**
	 * Question/answer pairs for a given FAQ post, ready for front-end render.
	 *
	 * @return array<int, array{question: string, answer: string}>
	 */
	public static function get_items( int $post_id ): array {
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$items = get_post_meta( $post_id, self::META_KEY, true );

		return is_array( $items ) ? $items : array();
	}

	public static function post_type(): string {
		return self::POST_TYPE;
	}

	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => __( 'FAQs', 'wp-plastic-surgery' ),
					'singular_name' => __( 'FAQ', 'wp-plastic-surgery' ),
					'add_new_item'  => __( 'Add FAQ', 'wp-plastic-surgery' ),
					'edit_item'     => __( 'Edit FAQ', 'wp-plastic-surgery' ),
					'all_items'     => __( 'FAQs', 'wp-plastic-surgery' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => false,
				'menu_icon'    => 'dashicons-editor-help',
				'supports'     => array( 'title' ),
				'rewrite'      => array( 'slug' => 'faq' ),
			)
		);
	}

	public function register_meta_box(): void {
		add_meta_box(
			'faq_items_box',
			__( 'Questions and answers', 'wp-plastic-surgery' ),
			array( $this, 'render_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high'
		);
	}

	public function render_meta_box( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		$items = get_post_meta( $post->ID, self::META_KEY, true );

		if ( ! is_array( $items ) || empty( $items ) ) {
			$items = array( array( 'question' => '', 'answer' => '' ) );
		}

		echo '<div id="faq-repeater" class="faq-repeater" data-index="' . esc_attr( (string) count( $items ) ) . '">';
		echo '<div class="faq-repeater__rows">';

		foreach ( $items as $index => $item ) {
			$this->render_row( (string) $index, $item['question'] ?? '', $item['answer'] ?? '' );
		}

		echo '</div>';
		echo '<p><button type="button" class="button faq-repeater__add">' . esc_html__( '+ Add question', 'wp-plastic-surgery' ) . '</button></p>';

		echo '<template id="faq-repeater-template">';
		$this->render_row( '__INDEX__', '', '' );
		echo '</template>';
		echo '</div>';
	}

	private function render_row( string $index, string $question, string $answer ): void {
		?>
		<div class="faq-repeater__row">
			<p>
				<label><?php esc_html_e( 'Question', 'wp-plastic-surgery' ); ?></label>
				<input type="text" class="widefat" name="faq_items[<?php echo esc_attr( $index ); ?>][question]" value="<?php echo esc_attr( $question ); ?>" />
			</p>
			<p>
				<label><?php esc_html_e( 'Answer (text or HTML)', 'wp-plastic-surgery' ); ?></label>
				<textarea class="widefat" rows="4" name="faq_items[<?php echo esc_attr( $index ); ?>][answer]"><?php echo esc_textarea( $answer ); ?></textarea>
			</p>
			<button type="button" class="button-link-delete faq-repeater__remove"><?php esc_html_e( 'Remove question', 'wp-plastic-surgery' ); ?></button>
		</div>
		<?php
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

		$raw   = isset( $_POST['faq_items'] ) && is_array( $_POST['faq_items'] ) ? wp_unslash( $_POST['faq_items'] ) : array();
		$clean = array();

		foreach ( $raw as $item ) {
			$question = sanitize_text_field( $item['question'] ?? '' );
			$answer   = wp_kses_post( $item['answer'] ?? '' );

			if ( '' === $question && '' === $answer ) {
				continue;
			}

			$clean[] = array(
				'question' => $question,
				'answer'   => $answer,
			);
		}

		update_post_meta( $post_id, self::META_KEY, $clean );
	}

	public function enqueue_admin_assets( string $hook ): void {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen || self::POST_TYPE !== $screen->post_type ) {
			return;
		}

		wp_enqueue_style(
			'faq-repeater',
			$this->uri() . 'assets/css/admin-faq-repeater.css',
			array(),
			$this->asset_version( 'assets/css/admin-faq-repeater.css' )
		);

		wp_enqueue_script(
			'faq-repeater',
			$this->uri() . 'assets/js/admin-faq-repeater.js',
			array(),
			$this->asset_version( 'assets/js/admin-faq-repeater.js' ),
			true
		);
	}
}
