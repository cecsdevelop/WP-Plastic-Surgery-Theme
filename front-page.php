<?php
/**
 * Front page template.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="entry-content">
	<?php the_content(); ?>
</div>
<?php
get_footer();
