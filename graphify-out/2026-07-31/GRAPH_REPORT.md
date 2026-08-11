# Graph Report - Truong-Group  (2026-07-31)

## Corpus Check
- 103 files · ~60,780 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 495 nodes · 471 edges · 84 communities (78 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 6 edges (avg confidence: 0.82)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `64d9928c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Graphify Skill Reference Docs
- Project AI Instructions & Graphify Wiring
- Enqueue/Base Controller (Assets)
- Theme Bootstrap (Init)
- Graphify Query Mechanics
- Frontend Interaction Script
- Theme functions.php
- Template: header.php (root)
- Template: page.php
- Template: single.php
- Section: about.php
- Section: comparison-table.php
- Section: consult-cta.php
- Section: gallery.php
- Section: header.php
- Section: hero.php
- Section: solutions-tabs.php
- Section: spotlight.php
- Section: trust-strip.php
- ModuleRegistrar
- Índice
- Revisión · Mockup v4 + `theme.json`
- Fase 0 — Reset técnico
- skinnybbls.com — Reglas del proyecto
- Init
- Checklist de despliegue del tema
- Prompt — Plantilla de páginas internas
- 08-prompt-homepage-v4.md
- 13-prompt-paginas-internas-v2.md
- page.php
- SurgeonController

## God Nodes (most connected - your core abstractions)
1. `SettingsController` - 43 edges
2. `Brief · `/skinny-bbl-recovery/`` - 22 edges
3. `Brief · Homepage (`/`)` - 19 edges
4. `FaqController` - 18 edges
5. `Brief · `/skinny-bbl-cost/`` - 18 edges
6. `Brief · `/skinny-bbl-vs-bbl/`` - 17 edges
7. `Brief · `/country-club-bbl/`` - 17 edges
8. `Brief · `/am-i-too-skinny-for-a-bbl/`` - 16 edges
9. `Brief · `/our-surgeons/`` - 15 edges
10. `Graphify Skill Pipeline (SKILL.md)` - 14 edges

## Surprising Connections (you probably didn't know these)
- `AGENTS.md Graphify Usage Rules` --semantically_similar_to--> `CLAUDE.md Graphify Usage Rules`  [INFERRED] [semantically similar]
  AGENTS.md → CLAUDE.md
- `graphify claude install Command` --references--> `AGENTS.md Graphify Usage Rules`  [INFERRED]
  .claude/skills/graphify/references/hooks.md → AGENTS.md
- `graphify claude install Command` --references--> `CLAUDE.md Graphify Usage Rules`  [INFERRED]
  .claude/skills/graphify/references/hooks.md → CLAUDE.md
- `Work Memory Self-Improving Loop (save-result / reflect)` --semantically_similar_to--> `Semantic Extraction Cache (Step B0)`  [INFERRED] [semantically similar]
  .claude/skills/graphify/references/query.md → .claude/skills/graphify/SKILL.md
- `Semantic Extraction Cache (Step B0)` --semantically_similar_to--> `build_merge Replace-on-Re-extract`  [INFERRED] [semantically similar]
  .claude/skills/graphify/SKILL.md → .claude/skills/graphify/references/update.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **SKILL.md Hub Referencing All Reference Docs** — _claude_skills_graphify_skill_graphify_pipeline, _claude_skills_graphify_references_add_watch_add_watch, _claude_skills_graphify_references_exports_exports_benchmark, _claude_skills_graphify_references_extraction_spec_extraction_subagent_spec, _claude_skills_graphify_references_github_and_merge_github_clone_merge, _claude_skills_graphify_references_hooks_hooks_integration, _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_transcribe_transcribe, _claude_skills_graphify_references_update_update_cluster_only [EXTRACTED 1.00]
- **Project Config Files That Trigger the Graphify Skill** — _claude_claude_trigger, agents_agents_md, claude_claude_md, _claude_skills_graphify_skill_graphify_pipeline [INFERRED 0.85]
- **Query Vocab-Expansion + Work-Memory Feedback Loop** — _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_query_vocab_expansion_mechanism, _claude_skills_graphify_references_query_work_memory_feedback_loop [EXTRACTED 1.00]

## Communities (84 total, 6 thin omitted)

### Community 0 - "Graphify Skill Reference Docs"
Cohesion: 0.09
Nodes (31): Graphify Trigger Directive, Add URL & Watch Folder Reference, Watch Debounce Mechanism, Exports & Benchmark Reference, MCP Stdio Server Export, Discrete Confidence Score Rubric (Avoids 0.5 Collapse), Extraction Subagent Prompt Spec, Node ID Must Match AST Extractor Format (+23 more)

### Community 1 - "Project AI Instructions & Graphify Wiring"
Cohesion: 0.07
Nodes (26): 10 · Protecting Your Results Long Term, 11 · FAQ, 1 · The Short Version, 2 · Why the Donor Sites Are the Harder Part, 3 · Week-by-Week Timeline, 4 · Sitting: The Rules, 5 · Sleeping and Positioning, 6 · What to Have Ready Before Surgery (+18 more)

### Community 2 - "Enqueue/Base Controller (Assets)"
Cohesion: 0.10
Nodes (19): 1 · What Is a Skinny BBL?, 2 · Skinny BBL vs Traditional BBL, 3 · Are You a Candidate?, 4 · What a Skinny BBL Costs, 5 · The Procedure, Step by Step, 6 · Recovery at a Glance, 7 · Your Surgeons, 8 · FAQ (+11 more)

### Community 3 - "Theme Bootstrap (Init)"
Cohesion: 0.10
Nodes (19): 1 · The Short Answer, 2 · What's Included in the Price, 3 · Why a Skinny BBL Often Costs More Than a Standard BBL, 4 · Skinny BBL Cost by Region, 5 · What the Price Doesn't Include, 6 · Why an Unusually Low Price Is a Warning Sign, 7 · Paying for a Skinny BBL, 8 · FAQ (+11 more)

### Community 4 - "Graphify Query Mechanics"
Cohesion: 0.11
Nodes (18): 1 · The Short Version, 2 · What "Skinny" Actually Means Here, 3 · What Changes in the Operating Room, 4 · What Changes in the Result, 5 · Which One Are You?, 6 · Cost and Recovery Differences, 7 · Other Comparisons, 8 · FAQ (+10 more)

### Community 8 - "Theme functions.php"
Cohesion: 0.11
Nodes (17): 1 · The Short Answer, 2 · What Surgeons Actually Assess, 3 · Where the Fat Usually Is (Even When You Don't Think You Have Any), 4 · If You Genuinely Don't Have Enough Fat, 5 · Should You Gain Weight First?, 6 · Fat Survival on Slim Patients, 7 · FAQ, → AUTOEVALUACIÓN AQUÍ (+9 more)

### Community 9 - "Template: header.php (root)"
Cohesion: 0.11
Nodes (17): 1 · What the Term Means, 2 · What Defines the Shape, 3 · Country Club BBL vs Other BBL Shapes, 4 · Who It Suits, 5 · How It's Achieved, 6 · FAQ, 7 · CTA, Brief · `/country-club-bbl/` (+9 more)

### Community 11 - "Template: page.php"
Cohesion: 0.11
Nodes (17): 45 · Per surgeon, A1 · Candidacy and BMI, A2 · Donor sites and volume, A3 · Procedure and recovery, A4 · Position statements, How to use this, Practice administrator, Practice counsel (+9 more)

### Community 13 - "Section: about.php"
Cohesion: 0.12
Nodes (15): Avisos legales — afectan al diseño de campos, Campos ocultos — la atribución, Checklist de aceptación, Consentimiento (sin premarcar), Especificación del formulario de captación, Estructura: 2 pasos, Implementación en Fluent Forms, La autoevaluación NO envía sus respuestas (+7 more)

### Community 14 - "Section: comparison-table.php"
Cohesion: 0.12
Nodes (15): 1 · Encabezado, 2 · Ficha por cirujano, 3 · Where We Practice, 4 · About the Group, 5 · CTA, Brief · `/our-surgeons/`, Checklist de publicación, Contenido requerido — pedir al cliente (+7 more)

### Community 15 - "Section: consult-cta.php"
Cohesion: 0.13
Nodes (14): Arquitectura del tema — skinnybbls.com, Caveat honesto sobre `theme.json` en tema clásico, Correcciones pendientes del layout interno, CPTs, Decisiones abiertas, Divergencia respecto a la base del grupo, ⚠️ Esta carpeta no debe vivir aquí, Home (`front-page.php`) (+6 more)

### Community 16 - "Section: gallery.php"
Cohesion: 0.14
Nodes (13): Canibalización interna en CBB, Clusters, cscchicago.com — tiene tesis, Datos de CBB (28 días a 2026-07-28), Diagnóstico, El CRM: GoHighLevel, El mejor dato de la cartera, Historial de skinnybbls.com (+5 more)

### Community 17 - "Section: header.php"
Cohesion: 0.14
Nodes (13): B1 · "Maximum Volume" ✅, B2 · Campos del formulario ✅, B3 · Separación de datos de salud ✅, Bloqueantes resueltos, Causa 1 · `1fr` no es `minmax(0, 1fr)`, Causa 2 · El arreglo de M3 causó el desbordamiento, Decisión pendiente de layout, Estado (+5 more)

### Community 18 - "Section: hero.php"
Cohesion: 0.12
Nodes (3): ModuleRegistrar, FaqController, WP_Post

### Community 21 - "Section: solutions-tabs.php"
Cohesion: 0.18
Nodes (10): FASE 0 · Reset técnico — semana del 28 jul, Lo que NO hacemos, MES 1 · Agosto — Cimientos, MES 2 · Septiembre — Profundidad, MES 3 · Octubre — Conversión y autoridad, MESES 4–6 · Nov 2026 – Ene 2027 — Tracción, MESES 7–9 · Feb – Abr 2027 — Consolidación, Riesgos a nombrar ante el cliente (+2 more)

### Community 22 - "Section: spotlight.php"
Cohesion: 0.18
Nodes (10): Brief · [URL], Checklist de publicación, Copy, Enlazado interno, Estructura, Justificación (datos), Lo que NO va, Metadatos (+2 more)

### Community 24 - "Section: trust-strip.php"
Cohesion: 0.20
Nodes (9): B1 · Volvió "Maximum Volume", B2 · El formulario de resultado perdió campos obligatorios, B3 · El BMI no puede viajar con el lead — y esto es un error mío, BLOQUEANTES, Estado, Lo que funciona, Menores, Revisión · Plantilla de páginas internas v1 (+1 more)

### Community 25 - "ModuleRegistrar"
Cohesion: 0.14
Nodes (3): BaseController, EnqueueController, ThemeSupportController

### Community 26 - "Índice"
Cohesion: 0.20
Nodes (9): 00-estrategia, 01-tecnico, 02-briefs, 03-handoff, 04-datos, 05-diseno, Arranque rápido, skinnybbls.com — Documentación del proyecto (+1 more)

### Community 27 - "Revisión · Mockup v4 + `theme.json`"
Cohesion: 0.22
Nodes (8): Arreglos verificados, BUG en `theme.json` · La propiedad `color` está mal anidada, Estado del diseño de la home, Faltan las protecciones de traspaso, Longitud de línea — resuelto en lo esencial, Menores, Revisión · Mockup v4 + `theme.json`, Veredicto

### Community 28 - "Fase 0 — Reset técnico"
Cohesion: 0.25
Nodes (7): Candidatas a 301 (si tienen backlinks), Checklist WordPress, El problema, Estado de ejecución (2026-07-28), Fase 0 — Reset técnico, Sobre 410 vs 404, Stack actual

### Community 29 - "skinnybbls.com — Reglas del proyecto"
Cohesion: 0.25
Nodes (7): Contexto en una frase, Convenciones, Estado actual (2026-07-28), Estilo de comunicación, Reglas duras — no negociables, skinnybbls.com — Reglas del proyecto, Ubicación de esta documentación

### Community 31 - "Checklist de despliegue del tema"
Cohesion: 0.33
Nodes (5): Checklist de despliegue del tema, Guardas, en orden de fiabilidad, Por qué importa más de lo que parece, Qué NO se exporta, Resto del checklist de despliegue

### Community 32 - "Prompt — Plantilla de páginas internas"
Cohesion: 0.33
Nodes (5): Checklist de revisión para la v2 de esta plantilla, Decisión de alcance, Lo que se añade y no existía en la home, Prompt, Prompt — Plantilla de páginas internas

### Community 40 - "page.php"
Cohesion: 0.60
Nodes (3): goNext(), showResult(), showStep()

## Knowledge Gaps
- **242 isolated node(s):** `La cartera`, `Sobre la segmentación de los principales`, `Por qué se descartó el modelo de "tiers de enlaces"`, `Clusters`, `Canibalización interna en CBB` (+237 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `BaseController` connect `ModuleRegistrar` to `Section: hero.php`, `SurgeonController`, `Template: single.php`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `SettingsController` connect `Template: single.php` to `ModuleRegistrar`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `FaqController` connect `Section: hero.php` to `ModuleRegistrar`?**
  _High betweenness centrality (0.011) - this node is a cross-community bridge._
- **What connects `La cartera`, `Sobre la segmentación de los principales`, `Por qué se descartó el modelo de "tiers de enlaces"` to the rest of the system?**
  _242 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Graphify Skill Reference Docs` be split into smaller, more focused modules?**
  _Cohesion score 0.09462365591397849 - nodes in this community are weakly interconnected._
- **Should `Project AI Instructions & Graphify Wiring` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `Enqueue/Base Controller (Assets)` be split into smaller, more focused modules?**
  _Cohesion score 0.1 - nodes in this community are weakly interconnected._