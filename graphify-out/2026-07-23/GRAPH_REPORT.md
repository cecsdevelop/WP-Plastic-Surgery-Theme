# Graph Report - .  (2026-07-23)

## Corpus Check
- Corpus is ~13,579 words - fits in a single context window. You may not need a graph.

## Summary
- 70 nodes · 63 edges · 25 communities (23 shown, 2 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 6 edges (avg confidence: 0.82)
- Token cost: 0 input · 92,851 output

## Community Hubs (Navigation)
- Graphify Skill Reference Docs
- Project AI Instructions & Graphify Wiring
- Enqueue/Base Controller (Assets)
- Theme Bootstrap (Init)
- Graphify Query Mechanics

## God Nodes (most connected - your core abstractions)
1. `Graphify Skill Pipeline (SKILL.md)` - 14 edges
2. `BaseController` - 7 edges
3. `Init` - 5 edges
4. `Query / Path / Explain Reference` - 5 edges
5. `Incremental Update & Cluster-Only Reference` - 5 edges
6. `AGENTS.md Graphify Usage Rules` - 5 edges
7. `CLAUDE.md Graphify Usage Rules` - 5 edges
8. `EnqueueController` - 4 edges
9. `Extraction Subagent Prompt Spec` - 4 edges
10. `graphify claude install Command` - 4 edges

## Surprising Connections (you probably didn't know these)
- `AGENTS.md Graphify Usage Rules` --semantically_similar_to--> `CLAUDE.md Graphify Usage Rules`  [INFERRED] [semantically similar]
  AGENTS.md → CLAUDE.md
- `graphify claude install Command` --references--> `AGENTS.md Graphify Usage Rules`  [INFERRED]
  .claude/skills/graphify/references/hooks.md → AGENTS.md
- `graphify claude install Command` --references--> `CLAUDE.md Graphify Usage Rules`  [INFERRED]
  .claude/skills/graphify/references/hooks.md → CLAUDE.md
- `AGENTS.md Graphify Usage Rules` --references--> `Query / Path / Explain Reference`  [EXTRACTED]
  AGENTS.md → .claude/skills/graphify/references/query.md
- `CLAUDE.md Graphify Usage Rules` --references--> `Query / Path / Explain Reference`  [EXTRACTED]
  CLAUDE.md → .claude/skills/graphify/references/query.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **SKILL.md Hub Referencing All Reference Docs** — _claude_skills_graphify_skill_graphify_pipeline, _claude_skills_graphify_references_add_watch_add_watch, _claude_skills_graphify_references_exports_exports_benchmark, _claude_skills_graphify_references_extraction_spec_extraction_subagent_spec, _claude_skills_graphify_references_github_and_merge_github_clone_merge, _claude_skills_graphify_references_hooks_hooks_integration, _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_transcribe_transcribe, _claude_skills_graphify_references_update_update_cluster_only [EXTRACTED 1.00]
- **Project Config Files That Trigger the Graphify Skill** — _claude_claude_trigger, agents_agents_md, claude_claude_md, _claude_skills_graphify_skill_graphify_pipeline [INFERRED 0.85]
- **Query Vocab-Expansion + Work-Memory Feedback Loop** — _claude_skills_graphify_references_query_query_path_explain, _claude_skills_graphify_references_query_vocab_expansion_mechanism, _claude_skills_graphify_references_query_work_memory_feedback_loop [EXTRACTED 1.00]

## Communities (25 total, 2 thin omitted)

### Community 0 - "Graphify Skill Reference Docs"
Cohesion: 0.16
Nodes (15): Graphify Trigger Directive, Add URL & Watch Folder Reference, Watch Debounce Mechanism, Exports & Benchmark Reference, MCP Stdio Server Export, Discrete Confidence Score Rubric (Avoids 0.5 Collapse), Extraction Subagent Prompt Spec, Node ID Must Match AST Extractor Format (+7 more)

### Community 1 - "Project AI Instructions & Graphify Wiring"
Cohesion: 0.24
Nodes (11): graphify claude install Command, Commit Hook & CLAUDE.md Integration Reference, Post-Commit Auto-Rebuild Hook, Incremental Update & Cluster-Only Reference, Graph Health Check (Step 4.5), AGENTS.md Project Instructions, AGENTS.md Graphify Usage Rules, Token Economy Communication Principle (+3 more)

### Community 4 - "Graphify Query Mechanics"
Cohesion: 0.50
Nodes (5): Query / Path / Explain Reference, Vocab Expansion Before Query Traversal, Work Memory Self-Improving Loop (save-result / reflect), build_merge Replace-on-Re-extract, Semantic Extraction Cache (Step B0)

## Knowledge Gaps
- **6 isolated node(s):** `Graphify Trigger Directive`, `Community Labeling (Step 5)`, `Watch Debounce Mechanism`, `MCP Stdio Server Export`, `Cross-Repo Graph Merge` (+1 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **2 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Graphify Skill Pipeline (SKILL.md)` connect `Graphify Skill Reference Docs` to `Project AI Instructions & Graphify Wiring`, `Graphify Query Mechanics`?**
  _High betweenness centrality (0.132) - this node is a cross-community bridge._
- **Why does `Query / Path / Explain Reference` connect `Graphify Query Mechanics` to `Graphify Skill Reference Docs`, `Project AI Instructions & Graphify Wiring`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Why does `AGENTS.md Graphify Usage Rules` connect `Project AI Instructions & Graphify Wiring` to `Graphify Query Mechanics`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **What connects `Graphify Trigger Directive`, `Community Labeling (Step 5)`, `Watch Debounce Mechanism` to the rest of the system?**
  _6 weakly-connected nodes found - possible documentation gaps or missing edges._