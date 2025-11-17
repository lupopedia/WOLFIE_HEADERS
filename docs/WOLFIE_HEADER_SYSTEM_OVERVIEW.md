---
title: WOLFIE_HEADER_SYSTEM_OVERVIEW.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-11-09
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [PURPOSE, ARCHITECTURE, FALLBACK_CHAIN, FILE_STRUCTURE, MIGRATION_NOTES, V2.0.0_NOTES]
superpositionally: ["FILEID_WHS_OVERVIEW"]
---

# WOLFIE Header System Overview

## PURPOSE

WOLFIE Headers deliver lightweight, consistent metadata for every Markdown document. The design solves three historic pain points:

1. **Duplication** – Replace 20+ line legacy headers with 5–7 lines of YAML.  
2. **Context drift** – Centralize tag/collection definitions so updates happen once.  
3. **Multi-agent reading** – Allow different AI agents to interpret the same file through channel-aware fallbacks.

## ARCHITECTURE

- **YAML Frontmatter** lives at the top of each document (see `templates/header_template.yaml`).  
- **Channels** partition documentation by perspective (`1_wolfie`, `2_database`, etc.).  
- **Agent overlays** (e.g., `1_wolfie_rose`) provide persona-specific vocab without copying files.  
- **Source-of-truth references** for tags and collections reside in channel directories inside `docs/`.

## FALLBACK_CHAIN

When an agent loads `tags: [SYSTEM]` on channel 1 with `agent_username: rose`, resolution happens in this order:

1. `docs/channel_1/1_wolfie_rose/TAGS.md`  
2. `docs/channel_1/1_wolfie_wolfie/TAGS.md`  
3. `docs/channel_1/1_wolfie/TAGS.md`

If a definition is missing at all levels, validation flags the header before release.

## FILE_STRUCTURE

- `docs/channel_1/` – Channel 1 references (base WOLFIE context).  
- `docs/channel_1/1_wolfie_wolfie/` – Captain WOLFIE’s authoritative definitions.  
- `docs/channel_1/1_wolfie/` – Legacy fallback for backwards compatibility.  
- Additional channels follow the same pattern (`{channel}_{agent}`).

## MIGRATION_NOTES

- Legacy AGAPE and Superpositionally headers can coexist temporarily—place the WOLFIE YAML block first, keep legacy sections beneath until migrations finish.  
- Automated conversion scripts should map old fields to the new template and populate `in_this_file_we_have` from detected headings.  
- See `docs/QUICK_START_GUIDE.md` for validation checklists and upgrade tips.

## V2.0.0_NOTES

**✅ Version 2.0.0 Released**: WOLFIE Headers v2.0.0 is now the current version with breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.

**Current Changes**:
- **10-Section Format**: Standard collections (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
- **Required Fields**: `agent_id` and `channel_number` (000-999) are now required
- **1000-Channel Support**: Enhanced channel architecture for 1000 channels (000-999)
- **Agent System Integration**: Direct integration with LUPOPEDIA agent routing system
- **Version Field**: All headers must include `version: 2.0.0`

**Migration**: All v1.4.2 headers must be migrated to v2.0.0 format. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for complete migration guide.

**Current Status**: v2.0.0 (Current) | **Required By**: LUPOPEDIA_PLATFORM 1.0.0

**Breaking Changes**: See `docs/BREAKING_CHANGES_2.0.0.md` for detailed breaking changes list.

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

