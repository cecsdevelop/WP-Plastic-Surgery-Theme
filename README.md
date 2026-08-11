# WP Plastic Surgery

Tema WordPress a medida, orientado a objetos, para un sitio de cirugía plástica (procedimientos, cirujanos, FAQs). Base de producción con estándares de código estrictos y valores por defecto seguros.

- **Versión:** 1.0.0
- **Requiere WordPress:** 6.6+
- **Requiere PHP:** 8.1+
- **Text Domain:** `wp-plastic-surgery`
- **Licencia:** GPL v2 o posterior

## Arquitectura

El tema arranca desde `functions.php`, que delega todo en `inc/Init.php` (singleton). `Init` registra un autoloader PSR-4 propio bajo el namespace `WPPlasticSurgery\` (mapea `WPPlasticSurgery\Foo\Bar` → `inc/Foo/Bar.php`) y luego invoca `Admin\ModuleRegistrar::register_all()`, que instancia y registra, en orden, cada módulo:

```
ThemeSupportController
EnqueueController
SettingsController
FaqController      + FaqBlock
SurgeonController   + SurgeonListBlock
ProcedureController + ProcedureHeroBlock
```

Cada módulo expone un método `register()` que se engancha a los hooks de WordPress correspondientes (CPTs, taxonomías, meta boxes, bloques, assets, etc.).

### Estructura de carpetas

```
inc/
  Init.php               Bootstrapper + autoloader
  template-tags.php      Helpers de plantilla
  Admin/
    BaseController.php       Clase base (paths, versión de assets)
    EnqueueController.php    Registro de scripts/estilos del front
    ModuleRegistrar.php      Orquesta el registro de módulos
    SettingsController.php   Página de opciones del tema
    ThemeSupportController.php  add_theme_support(), menús, etc.
  Modules/
    Faq/        FaqController (CPT + meta boxes), FaqBlock
    Procedure/  ProcedureController (taxonomía de procedimientos), ProcedureHeroBlock
    Surgeon/    SurgeonController (CPT de cirujanos), SurgeonListBlock

blocks/                   Bloques Gutenberg nativos (block.json + edit.js)
template-parts/           Partials reutilizables (hero, faq, surgeons, footer, header...)
templates/                Plantillas de página (Site Editor / block templates)
assets/
  css/                    Estilos de front y del admin (por campo/bloque)
  js/                     Scripts de front y del admin (repeaters, media, galerías)
  fonts/, images/
```

## Módulos

| Módulo | Responsabilidad |
|---|---|
| `ThemeSupportController` | `add_theme_support`, menús de navegación, tamaños de imagen |
| `EnqueueController` | Encolado condicional de CSS/JS del front |
| `SettingsController` | Página de ajustes del tema (color picker, media, repeaters) |
| `FaqController` / `FaqBlock` | CPT de FAQs, meta boxes, seeds de contenido, bloque "FAQ List" |
| `ProcedureController` / `ProcedureHeroBlock` | Taxonomía/agrupación de procedimientos, bloque "Procedure Hero" |
| `SurgeonController` / `SurgeonListBlock` | CPT de cirujanos (credenciales, videos, reviews, galería), bloque "Surgeon List" |

## Bloques Gutenberg

Namespace de bloques: `wp-plastic-surgery`.

- **`wp-plastic-surgery/procedure-hero`** — Hero de páginas de procedimiento: imagen y título vienen de la página; subtítulo, extracto y dos CTAs se editan en el bloque (imágenes desktop/mobile independientes).
- **`wp-plastic-surgery/surgeon-list`** — Lista cirujanos como lista o grid de 2/3/4 columnas, con opción de excluir cirujanos concretos.
- **`wp-plastic-surgery/faq-list`** — Inserta un set de FAQs (pregunta/respuesta) publicado previamente.

## Convenciones de código

Ver [AGENTS.md](AGENTS.md) — fuente única de verdad para rol, estándares y flujo de trabajo de este tema (aplica a cualquier asistente de IA, no solo Claude). En resumen:

- PHP 8.1+, `declare(strict_types=1)`, OOP, WPCS + PSR-12.
- Sanitización en la entrada, escapado tardío en la salida (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`), nonces + capability checks, prepared statements con `$wpdb`.
- JS moderno (ES6+); React para bloques de Gutenberg.

## Knowledge graph (graphify)

Este repo mantiene un grafo de conocimiento en `graphify-out/` (nodos, comunidades, relaciones cruzadas entre archivos). Para preguntas sobre la arquitectura, usar antes que grep/lectura manual:

```
graphify query "<pregunta>"
graphify path "<A>" "<B>"
graphify explain "<concepto>"
graphify update .   # tras cambios de código
```
