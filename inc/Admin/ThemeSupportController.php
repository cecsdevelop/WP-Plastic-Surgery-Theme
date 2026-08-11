<?php
/**
 * Core theme support declarations (nav menus, etc).
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ThemeSupportController extends BaseController {

	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
	}

	public function setup(): void {
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'wp-plastic-surgery' ),
				'footer'  => __( 'Footer Menu', 'wp-plastic-surgery' ),
			)
		);

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 675, true );

		add_post_type_support( 'post', 'page-attributes' );
	}
}
