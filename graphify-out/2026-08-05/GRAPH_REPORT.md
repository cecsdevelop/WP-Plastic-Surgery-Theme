# Graph Report - .  (2026-08-05)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 357 nodes · 344 edges · 100 communities (86 shown, 14 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.77)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8933cb0c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Graphify Skill Pipeline (SKILL.md)
- FaqController
- SurgeonController
- scripts.js
- SettingsController
- BaseController
- SurgeonController
- Init
- sbbl-assessment.js
- BaseController
- What You Must Do When Invoked
- FaqController
- Token Economy
- graphify reference: extra exports and benchmark
- Rol
- graphify reference: query, path, explain
- Init
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- ModuleRegistrar
- graphify reference: GitHub clone and cross-repo merge
- graphify reference: transcribe video and audio
- CLAUDE.md
- extraction-spec.md
- WP_Post
- WP_Post

## God Nodes (most connected - your core abstractions)
1. `SettingsController` - 48 edges
2. `FaqController` - 18 edges
3. `FaqController` - 18 edges
4. `Graphify Skill Pipeline (SKILL.md)` - 14 edges
5. `BaseController` - 13 edges
6. `BaseController` - 13 edges
7. `What You Must Do When Invoked` - 12 edges
8. `SurgeonController` - 11 edges
9. `SurgeonController` - 11 edges
10. `/graphify` - 10 edges

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

## Communities (100 total, 14 thin omitted)

### Community 0 - "Graphify Skill Pipeline (SKILL.md)"
Cohesion: 0.09
Nodes (31): Graphify Trigger Directive, Add URL & Watch Folder Reference, Watch Debounce Mechanism, Exports & Benchmark Reference, MCP Stdio Server Export, Discrete Confidence Score Rubric (Avoids 0.5 Collapse), Extraction Subagent Prompt Spec, Node ID Must Match AST Extractor Format (+23 more)

### Community 5 - "scripts.js"
Cohesion: 0.47
Nodes (4): closeSubmenuItem(), initSubmenuToggles(), onScroll(), toggle()

### Community 18 - "BaseController"
Cohesion: 0.09
Nodes (4): BaseController, EnqueueController, ModuleRegistrar, ThemeSupportController

### Community 40 - "sbbl-assessment.js"
Cohesion: 0.60
Nodes (3): goNext(), showResult(), showStep()

### Community 84 - "BaseController"
Cohesion: 0.12
Nodes (3): EnqueueController, ThemeSupportController, BaseController

### Community 86 - "What You Must Do When Invoked"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 101 - "Token Economy"
Cohesion: 0.20
Nodes (9): AI Project Instructions, Code Output, Communication, Context and Reading, Default Role, graphify, Scope Discipline, Token Economy (+1 more)

### Community 105 - "graphify reference: extra exports and benchmark"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 110 - "Rol"
Cohesion: 0.33
Nodes (5): Directrices técnicas, Formato de respuesta, graphify, Reglas de salida (ahorro de tokens), Rol

### Community 111 - "graphify reference: query, path, explain"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 113 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 114 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 115 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

## Knowledge Gaps
- **59 isolated node(s):** `graphify`, `Usage`, `What graphify is for`, `Step 0 - GitHub repos and multi-path merge (only if a URL or several paths)`, `Step 1 - Ensure graphify is installed` (+54 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **14 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `SettingsController` connect `SettingsController` to `BaseController`, `BaseController`?**
  _High betweenness centrality (0.122) - this node is a cross-community bridge._
- **Why does `BaseController` connect `BaseController` to `FaqController`, `SettingsController`, `SurgeonController`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **Why does `BaseController` connect `BaseController` to `SurgeonController`, `SettingsController`, `FaqController`?**
  _High betweenness centrality (0.080) - this node is a cross-community bridge._
- **What connects `graphify`, `Usage`, `What graphify is for` to the rest of the system?**
  _59 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Graphify Skill Pipeline (SKILL.md)` be split into smaller, more focused modules?**
  _Cohesion score 0.09462365591397849 - nodes in this community are weakly interconnected._
- **Should `SettingsController` be split into smaller, more focused modules?**
  _Cohesion score 0.05735430157261795 - nodes in this community are weakly interconnected._
- **Should `BaseController` be split into smaller, more focused modules?**
  _Cohesion score 0.09420289855072464 - nodes in this community are weakly interconnected._