<?php
/**
 * Template Name: Surgeons
 * Template Post Type: post, page
 *
 * Surgeons page template.
 *
 * @package WPPlasticSurgery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/**
 * Home sections, in the order defined by
 * Architecture/02-briefs/05-our-surgeons.md.
 */
get_template_part( 'template-parts/surgeons' );

get_footer();
