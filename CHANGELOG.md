---
title: CHANGELOG.md
agent_username: wolfie
date_created: 2025-11-09
last_modified: 2025-11-17
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, VERSIONING]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [VERSION_HISTORY, V2.0.0_PLANNED, NOTES]
superpositionally: ["FILEID_WOLFIE_HEADERS_CHANGELOG"]
---

# WOLFIE Headers Changelog

All notable changes to this component are documented here. Dates use the LUPOPEDIA development timeline (Sioux Falls timezone).

## VERSION_HISTORY

### v1.4.2 — 2025-11-08
- Added ontology snapshot tables to all core docs for consistency with LUPOPEDIA public pages.
- Published channel fallback order guidance and clarified BASE + DELTA merging.
- Created packaging checklist for shared-host deployments (LUPOPEDIA v0.0.8).

### v1.4.1 — 2025-11-05
- Introduced centralized TAGS and COLLECTIONS references for channel 1.
- Implemented `in_this_file_we_have` as the machine-readable table-of-contents list.
- Documented invite-only rollout plan and dependency on LUPOPEDIA v0.0.7.

### v1.4.0 — 2025-11-03
- Launched YAML frontmatter format (replacing AGAPE contextual headers).
- Defined channel naming conventions (`{channel}_{agent}` folders) and fallback rules.
- Published quick start template and minimal header requirements.

## V2.0.0_PLANNED

### v2.0.0 — Planned (Breaking Changes)

**⚠️ BREAKING CHANGES**: This version introduces breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.

**Status**: Planning Phase (2025-11-17)

**Planned Changes**:
- **10-Section Format**: New standard format (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
- **Required Fields**: `agent_id` and `channel_number` (000-999) will be required in YAML frontmatter
- **Agent System Integration**: Enhanced integration with LUPOPEDIA agent system (1000 channels, agent routing)
- **Collection Updates**: New collections (DO, HACK, OTHER) added to standard set
- **Channel Architecture**: Support for 1000-channel architecture (000-999)
- **Validation**: Stricter validation rules for required fields

**Migration Required**: All v1.4.2 headers must be migrated to v2.0.0 format. See `TODO_2.0.0.md` for complete migration plan.

**Dependency**: LUPOPEDIA_PLATFORM 1.0.0 **REQUIRES** WOLFIE Headers 2.0.0. This version cannot be released until WOLFIE Headers 2.0.0 is complete.

**Timeline**: Target completion before LUPOPEDIA_PLATFORM 1.0.0 release.

## NOTES

- Pre-1.4 releases lived inside legacy AGAPE and Superpositionally header experiments; see LUPOPEDIA internal archives for historical context.
- Upcoming work (v1.5.x) was planned but superseded by v2.0.0 breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.
- **v2.0.0 Planning**: See `TODO_2.0.0.md` for complete migration plan and task breakdown.

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

