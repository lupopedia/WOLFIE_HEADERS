---
title: README.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-11-09
last_modified: 2025-11-17
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [ABOUT, START_HERE, REFERENCE_FILES, V2.0.0_NOTES, V2.0.1_NOTES, V2.0.2_NOTES]
superpositionally: ["FILEID_DOCS_README"]
shadow_aliases: []
parallel_paths: []
---

# Documentation Directory

## ABOUT

This folder contains everything you need to understand and implement WOLFIE Headers. Files follow the same YAML metadata format the system enforces.

## START_HERE

1. Read `WOLFIE_HEADER_SYSTEM_OVERVIEW.md` for architecture and fallback rules.  
2. Use `QUICK_START_GUIDE.md` to validate new documents.  
3. Reference `CHANNELS_REFERENCE.md` and `TAGS_REFERENCE.md` while authoring.

## REFERENCE_FILES

- `channel_1/` – canonical definitions for the base channel.  
- Additional channel directories will appear as the project expands.  
- `examples/` and `templates/` live at the project root for quick access.

## V2.0.0_NOTES

**✅ Version 2.0.0 Released**: WOLFIE Headers v2.0.0 introduced breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.

**Key Changes**:
- New 10-section format (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS)
- Required fields: `agent_id` and `channel_number` (000-999)
- Enhanced agent system integration

**Migration**: See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for complete migration guide.

## V2.0.1_NOTES

**✅ Version 2.0.1 Stable**: WOLFIE Headers v2.0.1 adds shadow aliases and parallel paths (LILITH's recommendations).

**New Features**:
- Shadow aliases for parallel validation paths
- Parallel paths for alternative fallback chains
- Recursive oversight for self-validating feedback loops

**Documentation**: See `docs/SHADOW_ALIASES_2.0.1.md` for complete documentation.

## V2.0.2_NOTES

**✅ Version 2.0.2 Current**: WOLFIE Headers v2.0.2 adds database integration and agent file standardization.

**New Features**:
- Database integration with `content_headers` table (`agent_name` column)
- Agent file naming convention: `who_is_agent_[channel_id]_[agent_name].php`
- Full channel architecture support (000-999, maximum 999)
- Validation tools and migration scripts

**Documentation**:
- `docs/DATABASE_INTEGRATION.md` — Database integration guide
- `docs/AGENT_FILE_NAMING.md` — Agent file naming convention
- `templates/agent_file_template.php` — Agent file template
- `scripts/validate_agent_files.php` — Validation script
- `TODO_2.0.2.md` — Complete TODO plan

**Current Version**: v2.0.2 (Current) | **Required By**: LUPOPEDIA_PLATFORM 1.0.0

## AGENT_COMMUNICATION_PROTOCOL

**Integration Note**: WOLFIE Headers integrates with the LUPOPEDIA_PLATFORM Agent Communication Protocol (Receptionist Model). Agents use WOLFIE Headers metadata (YAML frontmatter) to route requests through the fixed chain:

```
User Request
    ↓
WOLFIE (008) - Reads WOLFIE Headers, routes tasks
    ↓
WOLFIE (007) - Executes, transfers to VISH
    ↓
VISHWAKARMA (075) - Normalizes requests using headers
    ↓
Response
```

**For detailed protocol documentation**, see: LUPOPEDIA_PLATFORM `docs/AGENT_COMMUNICATION_PROTOCOL.md`

---

Need help? Contact WOLFIE via the channels listed in the root README.

