# Graph Report - Truong-Group  (2026-08-07)

## Corpus Check
- 68 files · ~35,346 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 384 nodes · 461 edges · 50 communities (42 shown, 8 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 9 edges (avg confidence: 0.74)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `a6fa63a6`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Theme Settings Controller
- Procedure Hero CTA Attributes
- Graphify Advanced References
- Surgeon Module Registration
- Surgeon List Block Schema
- Surgeon Credentials Controller
- FAQ Controller
- Procedure Category Controller
- FAQ List Block Schema
- Gutenberg Block Manifests
- AGENTS.md Engineering Rules
- surgeon-videos-gallery.js
- Header Submenu Scripts
- Theme Autoload Bootstrap
- Assessment Quiz Script
- WP_Post Type Reference
- Theme README

## God Nodes (most connected - your core abstractions)
1. `SurgeonController` - 68 edges
2. `SettingsController` - 47 edges
3. `BaseController` - 21 edges
4. `FaqController` - 19 edges
5. `ProcedureController` - 18 edges
6. `attributes` - 15 edges
7. `Graphify Skill Pipeline (SKILL.md)` - 14 edges
8. `ProcedureHeroBlock` - 6 edges
9. `AI Project Instructions (AGENTS.md)` - 6 edges
10. `EnqueueController` - 5 edges

## Surprising Connections (you probably didn't know these)
- `CLAUDE.md — AGENTS.md Pointer` --references--> `AI Project Instructions (AGENTS.md)`  [EXTRACTED]
  CLAUDE.md → AGENTS.md
- `Work Memory Self-Improving Loop (save-result / reflect)` --semantically_similar_to--> `Semantic Extraction Cache (Step B0)`  [INFERRED] [semantically similar]
  .claude/skills/graphify/references/query.md → .claude/skills/graphify/SKILL.md
- `Semantic Extraction Cache (Step B0)` --semantically_similar_to--> `build_merge Replace-on-Re-extract`  [INFERRED] [semantically similar]
  .claude/skills/graphify/SKILL.md → .claude/skills/graphify/references/update.md
- `FaqController` --inherits--> `BaseController`  [EXTRACTED]
  inc/Modules/Faq/FaqController.php → inc/Admin/BaseController.php
- `SettingsController` --inherits--> `BaseController`  [EXTRACTED]
  inc/Admin/SettingsController.php → inc/Admin/BaseController.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Token Economy governs Scope Discipline, Error Handling, and Verification/Security exceptions** — agents_token_economy, agents_scope_discipline, agents_error_handling, agents_verification_security [EXTRACTED 1.00]
- **SKILL.md Hub Referencing All Reference Docs** — _claude_skills_graphify_skill_graphify_pipeline, _claude_skills_graphify_references_add_watch_add_watch, _claude_skills_graphify_references_exports_exports_benchmark, _claude_skills_graphify_references_extraction_spec_extraction_subagent_spec, _claude_skills_graphify_references_github_and_merge_github_clone_merge, _claude_skills_graphify_references_hooks_hooks_integration, _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_transcribe_transcribe, _claude_skills_graphify_references_update_update_cluster_only [EXTRACTED 1.00]
- **Project Config Files That Trigger the Graphify Skill** — _claude_claude_trigger, _claude_skills_graphify_skill_graphify_pipeline [INFERRED 0.85]
- **Query Vocab-Expansion + Work-Memory Feedback Loop** — _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_query_vocab_expansion_mechanism, _claude_skills_graphify_references_query_work_memory_feedback_loop [EXTRACTED 1.00]

## Communities (50 total, 8 thin omitted)

### Community 1 - "Procedure Hero CTA Attributes"
Cohesion: 0.05
Nodes (43): attributes, cta1BgColor, cta1Text, cta1TextColor, cta1Url, cta2BgColor, cta2Text, cta2TextColor (+35 more)

### Community 2 - "Graphify Advanced References"
Cohesion: 0.11
Nodes (25): Graphify Trigger Directive, Add URL & Watch Folder Reference, Watch Debounce Mechanism, Exports & Benchmark Reference, MCP Stdio Server Export, Discrete Confidence Score Rubric (Avoids 0.5 Collapse), Extraction Subagent Prompt Spec, Node ID Must Match AST Extractor Format (+17 more)

### Community 4 - "Surgeon Module Registration"
Cohesion: 0.06
Nodes (8): ThemeSupportController, BaseController, EnqueueController, ModuleRegistrar, FaqBlock, ProcedureHeroBlock, SurgeonListBlock, WP_Term

### Community 5 - "Surgeon List Block Schema"
Cohesion: 0.09
Nodes (22): apiVersion, attributes, columns, excludedIds, layout, category, default, type (+14 more)

### Community 9 - "FAQ List Block Schema"
Cohesion: 0.13
Nodes (14): apiVersion, attributes, faqId, category, description, default, type, icon (+6 more)

### Community 10 - "Gutenberg Block Manifests"
Cohesion: 0.17
Nodes (11): apiVersion, category, description, icon, name, $schema, supports, html (+3 more)

### Community 12 - "AGENTS.md Engineering Rules"
Cohesion: 0.25
Nodes (9): AI Project Instructions (AGENTS.md), Default Role: Senior WordPress Engineer, Error Handling, graphify Knowledge Graph Integration, Scope Discipline, Technical Domain: Performance/WPO & Technical SEO, Token Economy Principle, Verification and Security (+1 more)

### Community 16 - "Header Submenu Scripts"
Cohesion: 0.47
Nodes (4): closeSubmenuItem(), initSubmenuToggles(), onScroll(), toggle()

### Community 19 - "Assessment Quiz Script"
Cohesion: 0.60
Nodes (3): goNext(), showResult(), showStep()

## Knowledge Gaps
- **77 isolated node(s):** `$schema`, `apiVersion`, `name`, `title`, `category` (+72 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **8 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `BaseController` connect `Surgeon Module Registration` to `Theme Settings Controller`, `Procedure Category Controller`, `Surgeon Credentials Controller`, `FAQ Controller`?**
  _High betweenness centrality (0.159) - this node is a cross-community bridge._
- **Why does `SurgeonController` connect `Surgeon Credentials Controller` to `Procedure Category Controller`, `Surgeon Module Registration`?**
  _High betweenness centrality (0.128) - this node is a cross-community bridge._
- **Why does `SettingsController` connect `Theme Settings Controller` to `Surgeon Module Registration`?**
  _High betweenness centrality (0.102) - this node is a cross-community bridge._
- **Are the 3 inferred relationships involving `ProcedureController` (e.g. with `.get_procedure_groups()` and `.get_procedure_ids()`) actually correct?**
  _`ProcedureController` has 3 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `apiVersion`, `name` to the rest of the system?**
  _77 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Theme Settings Controller` be split into smaller, more focused modules?**
  _Cohesion score 0.05735430157261795 - nodes in this community are weakly interconnected._
- **Should `Procedure Hero CTA Attributes` be split into smaller, more focused modules?**
  _Cohesion score 0.046511627906976744 - nodes in this community are weakly interconnected._