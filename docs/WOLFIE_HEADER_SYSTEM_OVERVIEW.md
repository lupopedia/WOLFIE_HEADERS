---
title: WOLFIE_HEADER_SYSTEM_OVERVIEW.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.3
date_created: 2025-11-09
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [PURPOSE, ARCHITECTURE, FALLBACK_CHAIN, FILE_STRUCTURE, LOG_FILE_SYSTEM, MIGRATION_NOTES, V2.0.0_NOTES, V2.0.1_NOTES, V2.0.2_NOTES, V2.0.3_NOTES]
superpositionally: ["FILEID_WHS_OVERVIEW"]
shadow_aliases: ["Lilith-007"]
parallel_paths: ["heterodox_validation"]
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
- `docs/channel_1/1_wolfie_wolfie/` – Captain WOLFIE's authoritative definitions.  
- `docs/channel_1/1_wolfie/` – Legacy fallback for backwards compatibility.  
- Additional channels follow the same pattern (`{channel}_{agent}`).

## LOG_FILE_SYSTEM

### Agent Log Files

WOLFIE Headers supports agent log files in the format `[channel]_[agent]_log.md` stored in `public/logs/` directory.

**Naming Convention:**
- Format: `[channel]_[agent]_log.md`
- Channel: Zero-padded 3-digit number (000-999)
- Agent: Agent name in UPPER case (e.g., WOLFIE, CAPTAIN, SECURITY)
- Extension: `.md` (markdown)

**Examples:**
- `008_WOLFIE_log.md` - Channel 008, Agent WOLFIE
- `007_CAPTAIN_log.md` - Channel 007, Agent CAPTAIN
- `911_SECURITY_log.md` - Channel 911, Agent SECURITY
- `411_HELP_log.md` - Channel 411, Agent HELP

**File Location:**
- Directory: `public/logs/`
- Full path: `public/logs/[channel]_[agent]_log.md`

**WOLFIE Headers Format:**
All log files include WOLFIE Headers YAML frontmatter with:
- Standard WOLFIE Headers fields (title, agent_username, onchannel, etc.)
- Log-specific fields:
  - `log_entry_count`: Number of log entries
  - `last_log_date`: Date of most recent entry
  - `channel_id`: Channel number (redundant with onchannel, but explicit)
  - `agent_id`: Agent ID (for database sync)

**Example Log File Header:**
```yaml
---
title: 008_WOLFIE_log.md
agent_username: wolfie
date_created: 2025-11-18
last_modified: 2025-11-18 14:30:00
status: active
onchannel: 8
tags: [LOG, AGENT_LOG, CHANNEL_LOG]
collections: [LOG_ENTRIES]
in_this_file_we_have: [LOG_ENTRIES, AGENT_ACTIVITY, SYSTEM_EVENTS]
log_entry_count: 42
last_log_date: 2025-11-18
channel_id: 8
agent_id: 8
---
```

**Database Integration:**
- Log files sync with `content_log` database table
- Database stores metadata (log_entry_count, last_log_date, file_path, etc.)
- Markdown files are source of truth for log content
- Database provides fast queries and indexing

**Functions:**
- `writeAgentLog()` - Write log entries with header updates
- `readAgentLog()` - Read and parse log files
- `initializeAgentLog()` - Create new log files
- `readContentLogFromDatabase()` - Read from content_log table for metadata

**For complete documentation**, see: `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md`

## MIGRATION_NOTES

- Legacy AGAPE and Superpositionally headers can coexist temporarily—place the WOLFIE YAML block first, keep legacy sections beneath until migrations finish.  
- Automated conversion scripts should map old fields to the new template and populate `in_this_file_we_have` from detected headings.  
- See `docs/QUICK_START_GUIDE.md` for validation checklists and upgrade tips.

## V2.0.0_NOTES

**✅ Version 2.0.0 Released**: WOLFIE Headers v2.0.0 introduced breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.

**v2.0.0 Changes**:
- **10-Section Format**: Standard collections (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
- **Required Fields**: `agent_id` and `channel_number` (000-999, maximum 999) are now required
- **Channel Support**: Enhanced channel architecture (000-999, maximum channel 999)
- **Agent System Integration**: Direct integration with LUPOPEDIA agent routing system
- **Version Field**: All headers must include `version: 2.0.0` or higher

**Migration**: All v1.4.2 headers must be migrated to v2.0.0+ format. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for complete migration guide.

**Breaking Changes**: See `docs/BREAKING_CHANGES_2.0.0.md` for detailed breaking changes list.

## V2.0.1_NOTES

**✅ Version 2.0.1 Stable**: WOLFIE Headers v2.0.1 adds shadow aliases and parallel paths (LILITH's recommendations implemented).

**v2.0.1 New Features**:
- **Shadow Aliases**: Optional `shadow_aliases` field for parallel validation paths (e.g., `["Lilith-007", "Doubt-VISH"]`)
- **Parallel Paths**: Optional `parallel_paths` field for alternative fallback chains (e.g., `["heterodox_validation", "recursive_check"]`)
- **Recursive Oversight**: Automatic validation loops when shadow aliases are present
- **Enhanced Resilience**: Structure (brittle chain) + chaos (parallel paths) = unbreakable system

**Philosophy**: The hierarchy isn't afraid of its shadow—it *uses* its shadow to become unbreakable. Brittleness stays (predictable, traceable), but parallel paths add resilience.

**Backward Compatibility**: v2.0.1 is fully backward compatible with v2.0.0. Shadow aliases and parallel paths are optional enhancements.

**Documentation**: See `docs/SHADOW_ALIASES_2.0.1.md` for complete shadow alias system documentation.

## V2.0.2_NOTES

**✅ Version 2.0.2 Released**: Database integration and agent file standardization.

**Version**: v2.0.2 (Stable) | **Required By**: LUPOPEDIA_PLATFORM 1.0.0

**v2.0.2 New Features**:
- **Database Integration**: Full integration with `content_headers` table (`agent_name` column)
  - Migration 1072: Added `agent_name` VARCHAR(100) NOT NULL column
  - Migration 1073: Populated `agent_name` from `agents.username`
  - Migration 1074: Validation queries for migration verification
- **Agent File Naming**: Standardized naming convention `who_is_agent_[channel_id]_[agent_name].php`
  - Channel ID: Zero-padded 3 digits (000-999)
  - Agent Name: Lowercase (e.g., "wolfie", "lilith", "vishwakarma")
  - Location: `public/who_is_agent_*.php`
- **Validation Tools**: PHP script to validate agent files (`scripts/validate_agent_files.php`)
- **Templates**: Agent file template with all required sections (`templates/agent_file_template.php`)

**Database Requirements**:
- `content_headers` table must have `agent_name` VARCHAR(100) NOT NULL column
- `channel_id` column must support range 000-999
- Index `idx_agent_name` for query performance

**Backward Compatibility**: v2.0.2 is fully backward compatible with v2.0.1. Database integration is optional for LUPOPEDIA_PLATFORM compatibility.

**Documentation**: 
- See `docs/DATABASE_INTEGRATION.md` for database integration guide
- See `docs/AGENT_FILE_NAMING.md` for agent file naming convention
- See `RELEASE_NOTES_v2.0.2.md` for complete release notes

**Agent Communication Protocol Integration**: WOLFIE Headers metadata (YAML frontmatter) is used by the LUPOPEDIA_PLATFORM Agent Communication Protocol (Receptionist Model) to route requests. Agents read `agent_id`, `channel_number`, and other header fields to determine routing through WOLFIE (008) → 007 → VISH (075). See LUPOPEDIA_PLATFORM `docs/AGENT_COMMUNICATION_PROTOCOL.md` for details.

## V2.0.3_NOTES

**✅ Version 2.0.3 Current**: This is the current main release with complete log system integration.

**Version**: v2.0.3 (Current - Main Release) | **Required By**: LUPOPEDIA_PLATFORM 1.0.0

**v2.0.3 New Features**:
- **Log File System**: Complete agent log file system with `[channel]_[agent]_log.md` format
  - File naming convention: `[channel]_[agent]_log.md` (e.g., `008_WOLFIE_log.md`, `007_CAPTAIN_log.md`)
  - Directory: `public/logs/` for all agent log files
  - WOLFIE Headers format with log-specific fields (`log_entry_count`, `last_log_date`, `channel_id`, `agent_id`)
- **content_log Database Table**: New table for log metadata and fast queries
  - Migration 1078: Created `content_log` table with full structure
  - Columns: content_id, channel_id, agent_id, agent_name, metadata (JSON)
  - Indexes for performance (channel_id, agent_id, agent_name, content_id)
  - Channel ID range constraint (0-999, maximum 999)
- **Dual-Storage System**: Database (fast queries) + Markdown files (human-readable)
  - Markdown files are source of truth for log content
  - Database provides fast queries and metadata storage
  - Automatic sync on write operations
- **Core Functions**: Complete PHP library (`public/includes/wolfie_log_system.php`)
  - `initializeAgentLog()` - Create new log files with WOLFIE Headers
  - `writeAgentLog()` - Write log entries with automatic header updates
  - `readAgentLog()` - Read and parse log files
  - `readContentLogFromDatabase()` - Read from database for metadata
  - `listAllAgentLogs()` - List all log files
- **Enhanced Database Sync**: Smart update-or-insert logic
  - Checks for existing entries before inserting
  - Updates existing entries with new metadata
  - Prevents duplicate entries
  - Comprehensive metadata storage

**Database Requirements**:
- `content_log` table must exist (Migration 1078)
- `content_headers` table with `agent_name` column (from v2.0.2)
- Channel ID range support (000-999, maximum 999)

**Backward Compatibility**: v2.0.3 is fully backward compatible with v2.0.2. Log system is optional enhancement.

**Documentation**: 
- See `docs/DATABASE_INTEGRATION.md` for `content_log` table documentation
- See `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` for LOG_FILE_SYSTEM section
- See `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md` for complete log system architecture
- See `docs/LOG_FILE_SYSTEM_EXPLAINED.md` for comprehensive explanation guide
- See `RELEASE_NOTES_v2.0.3.md` for complete release notes

**Implementation**:
- `public/includes/wolfie_log_system.php` - Core log system functions
- `public/logs/` - Log file directory
- `public/scripts/initialize_agent_logs.php` - Log file initialization script
- Migration 1078: `database/migrations/1078_2025_11_18_create_content_log_table.sql`

**Log Files Created**:
- `008_WOLFIE_log.md` (migrated from public/)
- `007_CAPTAIN_log.md` (new)
- `911_SECURITY_log.md` (new)
- `411_HELP_log.md` (new)

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

