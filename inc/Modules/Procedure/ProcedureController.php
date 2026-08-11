<?php
/**
 * Procedure custom post type + hierarchical Procedure Category taxonomy
 * (with per-term image + content fields).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Modules\Procedure;

use WPPlasticSurgery\Admin\BaseController;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ProcedureController extends BaseController {

	private const POST_TYPE = 'procedure';
	private const TAXONOMY  = 'procedure_category';
	private const NONCE     = 'procedure_category_term_meta_nonce';

	private const IMAGE_META_KEY   = '_procedure_category_image_id';
	private const CONTENT_META_KEY = '_procedure_category_content';

	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );

		add_action( self::TAXONOMY . '_add_form_fields', array( $this, 'render_add_term_fields' ) );
		add_action( self::TAXONOMY . '_edit_form_fields', array( $this, 'render_edit_term_fields' ) );
		add_action( 'created_' . self::TAXONOMY, array( $this, 'save_term_meta' ) );
		add_action( 'edited_' . self::TAXONOMY, array( $this, 'save_term_meta' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	public static function post_type(): string {
		return self::POST_TYPE;
	}

	public static function taxonomy(): string {
		return self::TAXONOMY;
	}

	/**
	 * Category image attachment id, ready for front-end render.
	 */
	public static function get_category_image_id( int $term_id ): int {
		return absint( get_term_meta( $term_id, self::IMAGE_META_KEY, true ) );
	}

	/**
	 * Category rich content (text/HTML), ready for front-end render.
	 */
	public static function get_category_content( int $term_id ): string {
		$content = get_term_meta( $term_id, self::CONTENT_META_KEY, true );

		return is_string( $content ) ? $content : '';
	}

	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => __( 'Procedures', 'wp-plastic-surgery' ),
					'singular_name' => __( 'Procedure', 'wp-plastic-surgery' ),
					'add_new_item'  => __( 'Add Procedure', 'wp-plastic-surgery' ),
					'edit_item'     => __( 'Edit Procedure', 'wp-plastic-surgery' ),
					'all_items'     => __( 'Procedures', 'wp-plastic-surgery' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => false,
				'menu_icon'    => 'dashicons-clipboard',
				'supports'     => array( 'title', 'thumbnail', 'excerpt' ),
				'rewrite'      => array( 'slug' => 'procedures' ),
			)
		);
	}

	public function register_taxonomy(): void {
		register_taxonomy(
			self::TAXONOMY,
			array( self::POST_TYPE ),
			array(
				'labels'            => array(
					'name'          => __( 'Procedure Categories', 'wp-plastic-surgery' ),
					'singular_name' => __( 'Procedure Category', 'wp-plastic-surgery' ),
					'add_new_item'  => __( 'Add Procedure Category', 'wp-plastic-surgery' ),
					'edit_item'     => __( 'Edit Procedure Category', 'wp-plastic-surgery' ),
					'all_items'     => __( 'All Procedure Categories', 'wp-plastic-surgery' ),
				),
				'public'            => true,
				'show_in_rest'      => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'procedure-category' ),
			)
		);
	}

	/**
	 * Image + content fields on the "Add new term" screen.
	 */
	public function render_add_term_fields(): void {
		wp_nonce_field( self::NONCE, self::NONCE );
		?>
		<div class="form-field">
			<label><?php esc_html_e( 'Category Image', 'wp-plastic-surgery' ); ?></label>
			<?php $this->render_image_field( 0 ); ?>
		</div>
		<div class="form-field">
			<label for="procedure_category_content"><?php esc_html_e( 'Content', 'wp-plastic-surgery' ); ?></label>
			<?php $this->render_content_field( '' ); ?>
		</div>
		<?php
	}

	/**
	 * Image + content fields on the "Edit term" screen.
	 */
	public function render_edit_term_fields( WP_Term $term ): void {
		wp_nonce_field( self::NONCE, self::NONCE );
		$image_id = self::get_category_image_id( $term->term_id );
		$content  = self::get_category_content( $term->term_id );
		?>
		<tr class="form-field">
			<th scope="row"><label><?php esc_html_e( 'Category Image', 'wp-plastic-surgery' ); ?></label></th>
			<td><?php $this->render_image_field( $image_id ); ?></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="procedure_category_content"><?php esc_html_e( 'Content', 'wp-plastic-surgery' ); ?></label></th>
			<td><?php $this->render_content_field( $content ); ?></td>
		</tr>
		<?php
	}

	private function render_image_field( int $image_id ): void {
		$placeholder = __( 'No image selected', 'wp-plastic-surgery' );
		?>
		<div class="truong-media-field">
			<div class="truong-media-field__preview" data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
				<?php if ( $image_id && wp_attachment_is_image( $image_id ) ) : ?>
					<img src="<?php echo esc_url( (string) wp_get_attachment_image_url( $image_id, 'medium' ) ); ?>" alt="" />
				<?php else : ?>
					<span class="truong-media-field__placeholder"><?php echo esc_html( $placeholder ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" class="truong-media-field__input" id="procedure_category_image_id" name="procedure_category_image_id" value="<?php echo esc_attr( (string) $image_id ); ?>" />
			<p>
				<button type="button" class="button truong-media-field__select"><?php esc_html_e( 'Select image', 'wp-plastic-surgery' ); ?></button>
				<button type="button" class="button-link-delete truong-media-field__remove"<?php echo $image_id ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'wp-plastic-surgery' ); ?></button>
			</p>
		</div>
		<?php
	}

	private function render_content_field( string $content ): void {
		?>
		<textarea class="widefat" rows="8" id="procedure_category_content" name="procedure_category_content"><?php echo esc_textarea( $content ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Text or HTML.', 'wp-plastic-surgery' ); ?></p>
		<?php
	}

	public function save_term_meta( int $term_id ): void {
		if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE ] ), self::NONCE ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_categories' ) ) {
			return;
		}

		$image_id = isset( $_POST['procedure_category_image_id'] ) ? absint( $_POST['procedure_category_image_id'] ) : 0;
		$content  = isset( $_POST['procedure_category_content'] ) ? wp_kses_post( wp_unslash( $_POST['procedure_category_content'] ) ) : '';

		if ( $image_id ) {
			update_term_meta( $term_id, self::IMAGE_META_KEY, $image_id );
		} else {
			delete_term_meta( $term_id, self::IMAGE_META_KEY );
		}

		if ( '' !== $content ) {
			update_term_meta( $term_id, self::CONTENT_META_KEY, $content );
		} else {
			delete_term_meta( $term_id, self::CONTENT_META_KEY );
		}
	}

	public function enqueue_admin_assets( string $hook ): void {
		if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen || self::TAXONOMY !== $screen->taxonomy ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style(
			'wp-plastic-surgery-procedure-category',
			$this->uri() . 'assets/css/admin-settings.css',
			array(),
			$this->asset_version( 'assets/css/admin-settings.css' )
		);

		wp_enqueue_script(
			'wp-plastic-surgery-procedure-category-media',
			$this->uri() . 'assets/js/admin-settings-media.js',
			array(),
			$this->asset_version( 'assets/js/admin-settings-media.js' ),
			true
		);
	}
}
