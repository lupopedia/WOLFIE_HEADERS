---
title: README.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.3
date_created: 2025-11-09
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS]
in_this_file_we_have: [OVERVIEW, QUICK_START, CORE_CONCEPTS, DIRECTORY_MAP, VERSIONING, DEPENDENCY_CHAIN, V2.0.0_RELEASE, V2.0.1_RELEASE, V2.0.2_RELEASE, V2.0.3_RELEASE, SUPPORT]
superpositionally: ["FILEID_WOLFIE_HEADERS_README"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers

## OVERVIEW

WOLFIE Headers is the metadata system that powers LUPOPEDIA's documentation layer. It replaces bulky legacy headers with concise YAML frontmatter plus channel-aware ontology lookups so humans and AI agents read the same files with the right context.

- **Current Version**: v2.0.3 (Current) – **REQUIRED** by LUPOPEDIA_PLATFORM 1.0.0  
- **Previous Version**: v2.0.2 (Stable) – backward compatible, v2.0.3 adds log system integration  
- **Legacy Version**: v1.4.2 (Legacy) – compatible with LUPOPEDIA_PLATFORM v0.0.8 and earlier  
- **License**: Dual GPL v3.0 + Apache 2.0 (see `LICENSE`).  
- **Maintainer**: Captain WOLFIE (Eric Robin Gerdes).  
- **GitHub**: https://github.com/lupopedia/WOLFIE_HEADERS

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
- `templates/` – boilerplate YAML frontmatter and agent file templates.  
- `scripts/` – validation scripts for agent files and migrations.  
- `CHANGELOG.md` – release history for WOLFIE Headers.  
- `TODO_2.0.0.md` – **v2.0.0 migration plan and task breakdown**.  
- `TODO_2.0.2.md` – **v2.0.2 database integration plan**.  
- `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md` – **v2.0.3 log system architecture** (NEW).  
- `LICENSE` – combined GPL v3 + Apache 2.0 text.

## VERSIONING

WOLFIE Headers follows semantic versioning. The current release is **v2.0.3**, which is required by LUPOPEDIA_PLATFORM 1.0.0.

**⚠️ BREAKING CHANGES** (v2.0.0 from v1.4.2):
- New 10-section format (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
- Required fields: `agent_id`, `channel_number` (000-999), `version: 2.0.0`
- Enhanced agent system integration
- Channel architecture improvements (channels 000-999, maximum 999)
- Stricter validation rules

**NEW FEATURES** (v2.0.1):
- **Shadow Aliases**: Parallel validation paths (e.g., `["Lilith-007", "Doubt-VISH"]`)
- **Parallel Paths**: Alternative fallback chains for resilience
- **Recursive Oversight**: Self-validating feedback loops

**NEW FEATURES** (v2.0.2):
- **Database Integration**: `content_headers` table with `agent_name` column
- **Agent File Naming**: Standardized naming convention (`who_is_agent_[channel_id]_[agent_name].php`)
- **Channel Support**: Full channel architecture (000-999, maximum 999)

**NEW FEATURES** (v2.0.3):
- **Log File System**: Complete agent log file system with `[channel]_[agent]_log.md` format
  - File naming: `[channel]_[agent]_log.md` (e.g., `008_WOLFIE_log.md`, `007_CAPTAIN_log.md`)
  - Directory: `public/logs/` for all agent log files
  - WOLFIE Headers format with log-specific fields
- **content_log Database Table**: New table for log metadata and fast queries
  - Migration 1078: Created `content_log` table
  - Dual-storage: Database (fast queries) + Markdown files (human-readable)
  - Enhanced sync: Smart update-or-insert logic prevents duplicates
- **Core Functions**: Complete PHP library (`public/includes/wolfie_log_system.php`)
  - `initializeAgentLog()` - Create new log files
  - `writeAgentLog()` - Write entries with automatic header updates
  - `readAgentLog()` - Read and parse log files
  - `readContentLogFromDatabase()` - Read from database for metadata
  - `listAllAgentLogs()` - List all log files
- **Documentation**: Complete log system documentation
  - Database integration guide updated
  - System overview updated
  - Comprehensive explanation guide

**Migration Required**: All v1.4.2 headers must be migrated to v2.0.0+ format. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for complete migration guide.

**Breaking Changes**: See `docs/BREAKING_CHANGES_2.0.0.md` for detailed breaking changes list.

**Compatibility**: See `docs/COMPATIBILITY_MATRIX.md` for version compatibility information.

## DEPENDENCY_CHAIN

**WOLFIE Headers is a required dependency for LUPOPEDIA_PLATFORM:**

```
Crafty Syntax Live Help 3.8.0 (Foundation)
    ↓
    └─> WOLFIE Headers 2.0.0+ (REQUIRED - separate package)
        GitHub: https://github.com/lupopedia/WOLFIE_HEADERS
        Current: v2.0.3 (v2.0.2 stable, v2.0.1 stable, v2.0.0 minimum)
        ↓
        └─> LUPOPEDIA_PLATFORM 1.0.0 (Layer 1)
            GitHub: https://github.com/lupopedia/LUPOPEDIA_PLATFORM
            Requires: WOLFIE Headers 2.0.0+ (v2.0.3 recommended, v2.0.2 stable)
            ↓
            └─> Agent System (Layer 2)
                Channels: 000-999 (maximum 999)
```

**Why This Matters**: LUPOPEDIA_PLATFORM 1.0.0 **REQUIRES** WOLFIE Headers 2.0.0 or higher (v2.0.2 recommended, v2.0.1 stable). WOLFIE Headers is a **separate package** and must be installed independently.

## V2.0.0_RELEASE

**Status**: Released (2025-01-27), superseded by v2.0.1

**✅ Version 2.0.0 introduced breaking changes from v1.4.2.**

**Breaking Changes**:
1. **10-Section Format**: New standard format (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
2. **Required Fields**: `agent_id` and `channel_number` (000-999) are now required
3. **Version Field**: `version: 2.0.0` is now required
4. **Agent System Integration**: Enhanced integration with LUPOPEDIA agent system
5. **Collection Updates**: New collections (DO, HACK, OTHER, TAGS) added to standard set
6. **Deprecated**: `HELP` collection deprecated (use `OTHER` or `WHO` instead)

**Migration Required**: All v1.4.2 headers must be migrated to v2.0.0 format. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for complete migration guide.

**Documentation**:
- **Breaking Changes**: `docs/BREAKING_CHANGES_2.0.0.md`
- **Migration Guide**: `docs/MIGRATION_1.4.2_TO_2.0.0.md`
- **Compatibility Matrix**: `docs/COMPATIBILITY_MATRIX.md`
- **Validation Rules**: `docs/VALIDATION_RULES_2.0.0.md`
- **10-Section Format**: `docs/10_SECTION_FORMAT_GUIDE.md`

## V2.0.1_RELEASE

**Status**: Released (2025-01-27)

**✅ Version 2.0.1** (LILITH's recommendations implemented, superseded by v2.0.2).

**New Features** (Backward Compatible with v2.0.0):
1. **Shadow Aliases**: Optional `shadow_aliases` field for parallel validation paths (e.g., `["Lilith-007", "Doubt-VISH"]`)
2. **Parallel Paths**: Optional `parallel_paths` field for alternative fallback chains (e.g., `["heterodox_validation", "recursive_check"]`)
3. **Recursive Oversight**: Automatic validation loops when shadow aliases are present
4. **Enhanced Resilience**: Structure (brittle chain) + chaos (parallel paths) = unbreakable system

**Philosophy**: The hierarchy isn't afraid of its shadow—it *uses* its shadow to become unbreakable. Brittleness stays (predictable, traceable), but parallel paths add resilience.

**Migration**: No migration required from v2.0.0. v2.0.1 is fully backward compatible. Shadow aliases and parallel paths are optional enhancements.

**Documentation**:
- **Shadow Aliases Guide**: `docs/SHADOW_ALIASES_2.0.1.md`
- **Updated Template**: `templates/header_template.yaml` (now includes v2.0.1 fields)
- **Compatibility**: See `docs/COMPATIBILITY_MATRIX.md` for version compatibility

## V2.0.2_RELEASE

**Status**: Released (2025-01-27)

**✅ Version 2.0.2 is now the current version** (Database Integration & Agent File Standardization).

**New Features** (Backward Compatible with v2.0.1):
1. **Database Integration**: `content_headers` table integration with `agent_name` column
   - Migration 1072: Added `agent_name` VARCHAR(100) NOT NULL column
   - Migration 1073: Populated `agent_name` from `agents.username`
   - Migration 1074: Validation queries for migration verification
2. **Agent File Naming**: Standardized naming convention `who_is_agent_[channel_id]_[agent_name].php`
   - Channel ID: Zero-padded 3 digits (000-999)
   - Agent Name: Lowercase (e.g., "wolfie", "lilith", "vishwakarma")
   - Location: `public/who_is_agent_*.php`
3. **Documentation**: Complete guides for database integration and agent file naming
4. **Templates**: Agent file template with all required sections
5. **Validation**: PHP script to validate agent files

**Database Requirements**:
- `content_headers` table must have `agent_name` VARCHAR(100) NOT NULL column
- `channel_id` column must support range 000-999
- Index `idx_agent_name` for query performance

**Migration**: No migration required from v2.0.1\. v2.0.2 is fully backward compatible. Database integration is optional for LUPOPEDIA_PLATFORM compatibility.

**Documentation**:
- **Database Integration Guide**: `docs/DATABASE_INTEGRATION.md`
- **Agent File Naming Guide**: `docs/AGENT_FILE_NAMING.md`
- **Agent File Template**: `templates/agent_file_template.php`
- **Validation Script**: `scripts/validate_agent_files.php`
- **TODO Plan**: `TODO_2.0.2.md`

**Agent Communication Protocol**: WOLFIE Headers integrates with the LUPOPEDIA_PLATFORM Agent Communication Protocol (Receptionist Model). See LUPOPEDIA_PLATFORM documentation (`docs/AGENT_COMMUNICATION_PROTOCOL.md`) for details on how agents route requests through WOLFIE (008) → 007 → VISH (075) using WOLFIE Headers metadata.

## SUPPORT

- Read the docs in `docs/` first—they cover setup, validation, and channel usage.  
- For roadmap discussions, open an issue once the repository goes live on GitHub.  
- Direct questions to WOLFIE via Patreon, Facebook, or X (links provided in project docs).

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

