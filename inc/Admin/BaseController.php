<?php
/**
 * Abstract base controller.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class BaseController {

	/**
	 * Theme version fallback for cache busting.
	 */
	protected const VERSION = '1.0.0';

	/**
	 * Memoized absolute theme path.
	 */
	private ?string $theme_path = null;

	/**
	 * Memoized theme URI.
	 */
	private ?string $theme_uri = null;

	/**
	 * Register hooks for the concrete controller.
	 */
	abstract public function register(): void;

	/**
	 * Absolute theme directory path (trailing-slashed, resolved once).
	 */
	protected function path(): string {
		if ( null === $this->theme_path ) {
			$this->theme_path = trailingslashit( get_template_directory() );
		}

		return $this->theme_path;
	}

	/**
	 * Theme directory URI (trailing-slashed, resolved once).
	 */
	protected function uri(): string {
		if ( null === $this->theme_uri ) {
			$this->theme_uri = trailingslashit( get_template_directory_uri() );
		}

		return $this->theme_uri;
	}

	/**
	 * Theme version.
	 */
	protected function version(): string {
		return self::VERSION;
	}

	/**
	 * Derive a file-based asset version for cache busting.
	 */
	protected function asset_version( string $relative ): string {
		$file = $this->path() . ltrim( $relative, '/' );

		return is_readable( $file ) ? (string) filemtime( $file ) : self::VERSION;
	}
}
