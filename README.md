---
title: README.md
agent_username: wolfie
date_created: 2025-11-09
last_modified: 2025-11-09
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHY, HOW, HELP]
in_this_file_we_have: [OVERVIEW, QUICK_START, CORE_CONCEPTS, DIRECTORY_MAP, VERSIONING, SUPPORT]
superpositionally: ["FILEID_WOLFIE_HEADERS_README"]
---

# WOLFIE Headers

## OVERVIEW

WOLFIE Headers is the metadata system that powers LUPOPEDIA’s documentation layer. It replaces bulky legacy headers with concise YAML frontmatter plus channel-aware ontology lookups so humans and AI agents read the same files with the right context.

- **Status**: Stable (v1.4.2) – shipping in production with LUPOPEDIA deployments.  
- **License**: Dual GPL v3.0 + Apache 2.0 (see `LICENSE`).  
- **Maintainer**: Captain WOLFIE (Eric Robin Gerdes).

## QUICK_START

1. Copy the template in `templates/header_template.yaml` to the top of any Markdown file.  
2. Pick `tags` and `collections` from `docs/TAGS_REFERENCE.md` and `docs/CHANNELS_REFERENCE.md`.  
3. List the major sections in `in_this_file_we_have` so parsers can auto-build a table of contents.  
4. Save the file inside the appropriate channel directory (see `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` for fallback order).  
5. Optional: run the validation checklist in `docs/QUICK_START_GUIDE.md` before committing.

## CORE_CONCEPTS

- **Channel architecture**: Documentation is organized by channels (e.g., `1_wolfie/`). Agents resolve tags/collections by walking `{channel}_{agent}/ → {channel}_wolfie_wolfie/ → {channel}_wolfie/`.
- **Source-of-truth files**: Tags, collections, and ontology notes live in shared markdown references so individual files stay light.
- **Fallback philosophy**: “Always works” design borrowed from Crafty Syntax—if agent-specific context is missing, the system gracefully falls back to base definitions.
- **Dual licensing**: GPL ensures freedom to modify, Apache 2.0 grants explicit patent rights for enterprise adopters.

## DIRECTORY_MAP

- `docs/` – architecture notes, quick starts, reference tables.  
- `examples/` – ready-to-copy samples demonstrating best practices.  
- `templates/` – boilerplate YAML frontmatter.  
- `CHANGELOG.md` – release history for WOLFIE Headers.  
- `LICENSE` – combined GPL v3 + Apache 2.0 text.

## VERSIONING

WOLFIE Headers follows semantic versioning. The current release (v1.4.2) matches LUPOPEDIA platform v0.0.8. Future updates will stay backward-compatible within the `1.x` line; breaking structural changes will bump to `2.0.0`.

## SUPPORT

- Read the docs in `docs/` first—they cover setup, validation, and channel usage.  
- For roadmap discussions, open an issue once the repository goes live on GitHub.  
- Direct questions to WOLFIE via Patreon, Facebook, or X (links provided in project docs).

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

