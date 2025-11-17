---
title: QUICK_START_GUIDE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-11-09
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, HOWTO]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [BEFORE_YOU_START, CREATE_HEADER, VALIDATE, PUBLISH, AUTOMATION_CHECKLIST, V2.0.0_NOTES, V2.0.1_NOTES, V2.0.2_NOTES]
superpositionally: ["FILEID_WHS_QUICK_START"]
shadow_aliases: []
parallel_paths: []
---

# Quick Start Guide

## BEFORE_YOU_START

1. Pick the right channel (`1_wolfie` unless another context is required).  
2. Review available tags/collections in `docs/TAGS_REFERENCE.md` and `docs/CHANNELS_REFERENCE.md`.  
3. Outline your document sections so `in_this_file_we_have` is accurate.

## CREATE_HEADER

1. Copy `templates/header_template.yaml` to the top of your Markdown file.  
2. Update `title`, `date_created`, and `last_modified`.  
3. **v2.0.0+ Required**: Add `agent_id` (e.g., "008" for WOLFIE), `channel_number` (zero-padded string like "001"), and `version: 2.0.2` (or `2.0.1`, or `2.0.0`).  
4. Fill `tags` and `collections` from the reference tables (collections must be from 10-section set).  
5. **v2.0.1 Optional**: Add `shadow_aliases: []` and `parallel_paths: []` if you want parallel validation (see `docs/SHADOW_ALIASES_2.0.1.md`).  
6. **v2.0.2 Optional**: Database integration features available (see `docs/DATABASE_INTEGRATION.md`).  
7. List 3–6 items in `in_this_file_we_have` using uppercase snake case (e.g., `PROJECT_STATUS`).  
8. Save the file within the correct channel directory (`docs/channel_1/...`).

## VALIDATE

- **v2.0.0+ Required Fields**: Verify `agent_id`, `channel_number` (zero-padded), and `version: 2.0.2` (or `2.0.1`, or `2.0.0`) are present.  
- Ensure `channel_number` matches `onchannel` (as integer: "001" = 1).  
- Ensure there is exactly one blank line between the YAML and the first heading.  
- Confirm every tag/collection exists in the reference files.  
- Verify collections are from 10-section set: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS.  
- **v2.0.1 Optional**: If using `shadow_aliases` or `parallel_paths`, verify they are arrays of strings.  
- Run spellcheck on tags (case sensitive).  
- If `agent_username` is specified, verify the agent-specific folder exists.

## PUBLISH

1. Update `CHANGELOG.md` if the release includes structural changes or new references.  
2. Regenerate CSV/JSON indexes if your tooling requires them.  
3. Announce the update in the LUPOPEDIA changelog when syncing with the main platform.

## AUTOMATION_CHECKLIST

- [ ] Validate YAML syntax (CI script or `yamllint`).  
- [ ] Verify fallback resolution by simulating at least one agent context.  
- [ ] Run link checker on intra-doc references.  
- [ ] Archive legacy header blocks once the migration script completes.

## V2.0.0_NOTES

**✅ Version 2.0.0 Released**: This guide applies to v2.0.0+ format (current version: v2.0.2).

**v2.0.0 Requirements**:
- **Required Fields**: `agent_id` and `channel_number` (000-999) are mandatory
- **Version Field**: `version: 2.0.2` (or `2.0.1`, or `2.0.0`) must be present
- **10-Section Format**: Collections must be from: WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS
- **Validation**: Stricter validation rules are enforced (see `docs/VALIDATION_RULES_2.0.0.md`)

**Migration**: If migrating from v1.4.2, see `docs/MIGRATION_1.4.2_TO_2.0.0.md` for step-by-step guide.

**Breaking Changes**: See `docs/BREAKING_CHANGES_2.0.0.md` for detailed list

## V2.0.1_NOTES

**✅ Version 2.0.1 Stable**: This version adds shadow aliases and parallel paths (LILITH's recommendations implemented).

**v2.0.1 Optional Features**:
- **Shadow Aliases**: `shadow_aliases: []` — Parallel validation paths (e.g., `["Lilith-007", "Doubt-VISH"]`)
- **Parallel Paths**: `parallel_paths: []` — Alternative fallback chains (e.g., `["heterodox_validation", "recursive_check"]`)

**Backward Compatibility**: v2.0.1 is fully backward compatible with v2.0.0. Shadow aliases and parallel paths are optional.

**Documentation**: See `docs/SHADOW_ALIASES_2.0.1.md` for complete shadow alias system documentation.

## V2.0.2_NOTES

**✅ Version 2.0.2 Current**: This is the current main release with database integration and agent file standardization.

**v2.0.2 New Features**:
- **Database Integration**: Full integration with `content_headers` table (`agent_name` column)
- **Agent File Naming**: Standardized naming convention (`who_is_agent_[channel_id]_[agent_name].php`)
- **Validation Scripts**: PHP script to validate agent files (`scripts/validate_agent_files.php`)
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

**Current Version**: v2.0.2 (Current - Main Release) | **Required By**: LUPOPEDIA_PLATFORM 1.0.0

---

Need a reference implementation? Check `examples/sample_header.md` for a fully commented template.

