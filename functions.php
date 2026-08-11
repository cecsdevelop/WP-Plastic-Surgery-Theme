<?php
/**
 * Theme bootstrap.
 *
 * Only responsibility of this file is to boot the OOP system.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/Init.php';
require_once get_template_directory() . '/inc/template-tags.php';

\WPPlasticSurgery\Init::instance()->run();
