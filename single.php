<?php
/**
 * Single post template.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="container">
	<section class="content-area">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-thumbnail">
						<?php the_post_thumbnail( 'large', array( 'class' => 'entry-thumbnail__img' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</section>
</div>
<?php
get_footer();
