---
title: README.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.6
date_created: 2025-11-09
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS]
in_this_file_we_have: [OVERVIEW, QUICK_START, CORE_CONCEPTS, DIRECTORY_MAP, VERSIONING, DEPENDENCY_CHAIN, V2.0.0_RELEASE, V2.0.1_RELEASE, V2.0.2_RELEASE, V2.0.3_RELEASE, V2.0.4_RELEASE, V2.0.5_RELEASE, SUPPORT]
superpositionally: ["FILEID_WOLFIE_HEADERS_README"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers

## OVERVIEW

WOLFIE Headers is the metadata system that powers LUPOPEDIA's documentation layer. It replaces bulky legacy headers with concise YAML frontmatter plus channel-aware ontology lookups so humans and AI agents read the same files with the right context.

- **Current Version**: v2.0.6 (Current) – **REQUIRED** by LUPOPEDIA_PLATFORM 1.0.0  
- **Previous Version**: v2.0.5 (Stable) – backward compatible, v2.0.6 adds API endpoints and search  
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
- `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md` – **v2.0.3 log system architecture**.  
- `TODO_2.0.4.md` – **v2.0.4 Agent 007 CAPTAIN integration plan**.  
- `TODO_2.0.5.md` – **v2.0.5 Log Reader System plan**.  
- `TODO_2.0.6.md` – **v2.0.6 API Endpoints & Search plan** (NEW).  
- `public/wolfie_reader.php` – **v2.0.5 Log Reader web interface**.  
- `public/api/wolfie/index.php` – **v2.0.6 API endpoints** (NEW).  
- `public/includes/wolfie_api_core.php` – **v2.0.6 API core functions** (NEW).  
- `public/logs/` – **Log files directory**.  
- `LICENSE` – combined GPL v3 + Apache 2.0 text.

## VERSIONING

WOLFIE Headers follows semantic versioning. The current release is **v2.0.6**, which is required by LUPOPEDIA_PLATFORM 1.0.0.

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
  - Migration 1078: Created `content_log` table (tracks content interactions by channel/agent)
  - Dual-storage: Database (fast queries) + Markdown files (human-readable)
  - Enhanced sync: Smart update-or-insert logic prevents duplicates
- **content_logs Database Table**: Row-level change tracking (Migration 1079)
  - Migration 1079: Created `content_logs` table (tracks changes to individual content rows)
  - Different from `content_log` (singular): `content_logs` tracks row changes, `content_log` tracks interactions
  - Enables AI and human readers to understand evolution of database records
  - See `TODO_2.0.7.md` for complete implementation plan
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

**NEW FEATURES** (v2.0.4):
- **Agent 007 CAPTAIN Integration**: Official integration of Commanding Officer
  - Agent ID: 007, Channel: 007
  - GitHub: https://github.com/lupopedia/007_captain
  - Role: Commanding Officer & Strategic Coordinator
- **Agent 001 UNKNOWN Integration**: First Agent & Template
  - Agent ID: 001, Channel: 001
  - GitHub: https://github.com/lupopedia/001_unknown
  - Role: Template Agent & First Agent
- **Agent 999 UNKNOWN Integration**: Last Agent & Template
  - Agent ID: 999, Channel: 999 (Maximum 999)
  - GitHub: https://github.com/lupopedia/999_unknown
  - Role: Template Agent & Last Agent
- **Agent Repository Structure**: Standardized GitHub repository structure
  - README.md with WOLFIE Headers format
  - CHANGELOG.md for version history
  - LICENSE (dual GPL v3.0 + Apache 2.0)
  - docs/ directory for agent-specific documentation
- **Agent Integration Patterns**: Documentation for agent repositories
  - Agent repository naming convention
  - Agent repository structure standards
  - Agent integration with WOLFIE Headers

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
        Current: v2.0.5 (v2.0.4 stable, v2.0.3 stable, v2.0.2 stable, v2.0.1 stable, v2.0.0 minimum)
        ↓
        └─> LUPOPEDIA_PLATFORM 1.0.0 (Layer 1)
            GitHub: https://github.com/lupopedia/LUPOPEDIA_PLATFORM
            Requires: WOLFIE Headers 2.0.0+ (v2.0.5 recommended, v2.0.4 stable)
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

## V2.0.3_RELEASE

**Status**: Released (2025-11-18) - **Current Version**

**✅ Version 2.0.3 is now the current version** (Log System Integration).

**New Features** (Backward Compatible with v2.0.2):
1. **Log File System**: Complete agent log file system with `[channel]_[agent]_log.md` format
   - File naming: `[channel]_[agent]_log.md` (e.g., `008_WOLFIE_log.md`, `007_CAPTAIN_log.md`)
   - Directory: `public/logs/` for all agent log files
   - WOLFIE Headers format with log-specific fields
2. **content_log Database Table**: New table for log metadata and fast queries
   - Migration 1078: Created `content_log` table
   - Dual-storage: Database (fast queries) + Markdown files (human-readable)
   - Enhanced sync: Smart update-or-insert logic prevents duplicates
3. **Core Functions**: Complete PHP library (`public/includes/wolfie_log_system.php`)
   - `initializeAgentLog()` - Create new log files
   - `writeAgentLog()` - Write entries with automatic header updates
   - `readAgentLog()` - Read and parse log files
   - `readContentLogFromDatabase()` - Read from database for metadata
   - `listAllAgentLogs()` - List all log files
4. **Documentation**: Complete log system documentation
   - Database integration guide updated
   - System overview updated
   - Comprehensive explanation guide

**Database Requirements**:
- `content_log` table must exist (Migration 1078)
- `content_headers` table with `agent_name` column (from v2.0.2)
- Channel ID range support (000-999, maximum 999)

**Migration**: No migration required from v2.0.2. v2.0.3 is fully backward compatible. Log system is optional enhancement.

**Documentation**:
- **Release Notes**: `RELEASE_NOTES_v2.0.3.md`
- **Log System Plan**: `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md`
- **Database Integration**: `docs/DATABASE_INTEGRATION.md` (updated with content_log)
- **System Overview**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` (updated with LOG_FILE_SYSTEM)
- **Explanation Guide**: `docs/LOG_FILE_SYSTEM_EXPLAINED.md`

## V2.0.6_RELEASE

**Status**: Released (2025-11-18), Current Version  
**Backward Compatible**: Yes — fully compatible with v2.0.5

**✅ Version 2.0.6** introduces **API Endpoints and Search Functionality** for programmatic access to the log system (as suggested by LILITH).

**New Features** (API Endpoints & Search):
1. **RESTful API Endpoints** (`public/api/wolfie/index.php`)
   - `GET /api/wolfie/agents` - List all agents with metadata
   - `GET /api/wolfie/agents/{agent_name}` - Get specific agent details
   - `GET /api/wolfie/channels` - List all channels with metadata
   - `GET /api/wolfie/channels/{channel_id}` - Get specific channel details
   - `GET /api/wolfie/logs` - List all log files (with filtering and pagination)
   - `GET /api/wolfie/logs/agent/{agent_name}` - Get logs by agent
   - `GET /api/wolfie/logs/channel/{channel_id}` - Get logs by channel
   - `GET /api/wolfie/logs/{channel_id}/{agent_name}` - Get specific log file
   - `POST /api/wolfie/search` - Full-text search in log content
   - `POST /api/wolfie/validate/log/{filename}` - Validate log file
   - `POST /api/wolfie/validate/directory` - Validate entire directory

2. **Search Functionality**
   - Full-text search in log content (markdown body)
   - Search in YAML frontmatter (tags, collections, metadata)
   - Search by date range, agent, channel
   - Result highlighting and relevance scoring
   - Search result pagination

3. **Caching System**
   - File-based caching for directory scans
   - Cache TTL: 5 minutes (configurable)
   - Cache invalidation on file modification
   - Performance optimization for large log directories

4. **Validation API**
   - Validate individual log files
   - Validate entire logs directory
   - Comprehensive error reporting with suggestions
   - YAML frontmatter validation
   - Filename/content consistency checks

**Files Added**:
- `public/api/wolfie/index.php` - API router and endpoints
- `public/includes/wolfie_api_core.php` - API core functions
- `TODO_2.0.6.md` - Complete v2.0.6 implementation plan (LILITH's review)

**Documentation**:
- **Release Notes**: `RELEASE_NOTES_v2.0.6.md`
- **TODO Plan**: `TODO_2.0.6.md` (LILITH's critical analysis)
- **API Reference**: See `TODO_2.0.6.md` for complete API documentation

**Migration**: No migration required from v2.0.5. v2.0.6 is fully backward compatible. API endpoints are optional enhancement.

## V2.0.5_RELEASE

**Status**: Released (2025-11-18), Superseded by v2.0.6  
**Backward Compatible**: Yes — fully compatible with v2.0.4

**✅ Version 2.0.5** introduced the **Log Reader System** for browsing and viewing agent log files.

**New Features** (Log Reader System):
1. **Log Reader Web Interface** (`public/wolfie_reader.php`)
   - Browse all log files in `public/logs/` directory
   - Discover agents and channels from log files
   - View logs by agent, by channel, or specific log files
   - Statistics dashboard (total logs, unique agents, active channels)
   - Markdown rendering for log content
   - Responsive design for desktop and mobile

2. **Agent Discovery**
   - Automatically scans `public/logs/` directory
   - Extracts agent names from log filenames
   - Lists all unique agents with log counts
   - Shows channels each agent operates on
   - Links to view all logs by specific agent

3. **Channel Discovery**
   - Extracts channel numbers from log filenames
   - Lists all unique channels with log counts
   - Shows agents active on each channel
   - Links to view all logs on specific channel

4. **Log Viewing Options**
   - View specific log file (channel + agent combination)
   - View all logs on a specific channel
   - View all logs by a specific agent
   - View all logs (complete directory listing)
   - Navigation between related views

5. **Filename Parsing**
   - Supports pattern: `[channel]_[agent]_log.md` (e.g., `007_CAPTAIN_log.md`)
   - Supports pattern: `[channel]_[agent].md` (e.g., `007_wolfie.md`)
   - Handles case variations (uppercase/lowercase)
   - Validates channel numbers (000-999)

**Files Added**:
- `public/wolfie_reader.php` - Log reader web interface
- `public/logs/007_unknown.md` - Example log file for UNKNOWN agent on Channel 007
- `TODO_2.0.5.md` - Complete v2.0.5 implementation plan

**Documentation**:
- **Release Notes**: `RELEASE_NOTES_v2.0.5.md`
- **TODO Plan**: `TODO_2.0.5.md`
- **Log Reader**: `public/wolfie_reader.php` (standalone web interface)

**Migration**: No migration required from v2.0.4. v2.0.5 is fully backward compatible. Log reader is optional enhancement.

## SUPPORT

- Read the docs in `docs/` first—they cover setup, validation, and channel usage.  
- For roadmap discussions, open an issue once the repository goes live on GitHub.  
- Direct questions to WOLFIE via Patreon, Facebook, or X (links provided in project docs).

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

