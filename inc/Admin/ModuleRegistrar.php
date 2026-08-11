<?php
/**
 * Admin module registrar.
 *
 * @package WPPlasticSurgery
 */

declare(strict_types=1);

namespace WPPlasticSurgery\Admin;

use WPPlasticSurgery\Modules\Faq\FaqBlock;
use WPPlasticSurgery\Modules\Faq\FaqController;
use WPPlasticSurgery\Modules\Procedure\ProcedureController;
use WPPlasticSurgery\Modules\Procedure\ProcedureHeroBlock;
use WPPlasticSurgery\Modules\Surgeon\SurgeonController;
use WPPlasticSurgery\Modules\Surgeon\SurgeonListBlock;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ModuleRegistrar {

	/**
	 * Modules to register, in order.
	 *
	 * @var array<int, class-string>
	 */
	private array $modules = array(
		ThemeSupportController::class,
		EnqueueController::class,
		SettingsController::class,
		FaqController::class,
		FaqBlock::class,
		SurgeonController::class,
		SurgeonListBlock::class,
		ProcedureController::class,
		ProcedureHeroBlock::class,
	);

	/**
	 * Instantiate each module and call its register() method.
	 */
	public function register_all(): void {
		foreach ( $this->modules as $module ) {
			$instance = new $module();

			if ( method_exists( $instance, 'register' ) ) {
				$instance->register();
			}
		}
	}
}
