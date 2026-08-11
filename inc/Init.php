<?php
/**
 * Service bootstrapper and autoloader.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Init {

	/**
	 * Singleton instance.
	 */
	private static ?Init $instance = null;

	/**
	 * Retrieve the singleton instance.
	 */
	public static function instance(): Init {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register the namespace autoloader.
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * PSR-4 style autoloader scoped to the theme namespace.
	 */
	public function autoload( string $class ): void {
		$prefix = __NAMESPACE__ . '\\';

		if ( ! str_starts_with( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$path     = get_template_directory() . '/inc/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}

	/**
	 * Delegate module registration to the module registrar.
	 */
	public function run(): void {
		( new Admin\ModuleRegistrar() )->register_all();
	}
}
