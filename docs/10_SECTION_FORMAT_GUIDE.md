---
title: 10_SECTION_FORMAT_GUIDE.md
agent_username: wolfie
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
agent_id: 008
channel_number: 001
version: 2.0.0
tags: [SYSTEM, DOCUMENTATION, REFERENCE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS]
in_this_file_we_have: [OVERVIEW, SECTION_DEFINITIONS, USAGE_GUIDELINES, EXAMPLES, VALIDATION_RULES]
superpositionally: ["FILEID_10_SECTION_FORMAT_GUIDE"]
---

# 10-Section Format Guide (v2.0.0)

## OVERVIEW

WOLFIE Headers v2.0.0 introduces a standardized **10-section format** that maps directly to LUPOPEDIA_PLATFORM ontology and agent system requirements. This format replaces the flexible v1.4.2 format with a structured approach that ensures consistency across all documentation.

**Why 10 Sections?**
- Direct mapping to LUPOPEDIA ontology classes
- Agent system integration (each section can be processed by specialized agents)
- Consistent parsing and validation across the platform
- Support for action-oriented content (DO, HACK) and flexible categorization (OTHER, TAGS)

**Breaking Change**: v1.4.2 headers with fewer than 10 sections will need migration. See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for conversion instructions.

---

## SECTION_DEFINITIONS

### WHO
- **Meaning**: People, agents, teams, organizations, or entities involved with or responsible for the content.
- **Content**: Maintainer info, contact routes, roles/responsibilities, authorship, ownership.
- **Usage**: Use when documenting who created, maintains, or is affected by the content.
- **Example Sections**: `MAINTAINER`, `AUTHORS`, `CONTACT`, `TEAM`, `STAKEHOLDERS`

### WHAT
- **Meaning**: Description of the document, component, feature, or artifact itself.
- **Content**: Overview, feature summary, release scope, component description, purpose statement.
- **Usage**: Core description of what the document is about or what the component does.
- **Example Sections**: `OVERVIEW`, `FEATURES`, `SUMMARY`, `DESCRIPTION`, `SCOPE`

### WHERE
- **Meaning**: Physical or digital locations, paths, environments, or deployment targets.
- **Content**: File paths, directory structures, URLs, server info, deployment targets, repository locations.
- **Usage**: Document where files live, where to deploy, or where to find related resources.
- **Example Sections**: `FILE_LOCATION`, `DEPLOYMENT`, `REPOSITORY`, `ENVIRONMENT`, `PATHS`

### WHEN
- **Meaning**: Timelines, milestones, release dates, schedules, or temporal context.
- **Content**: Changelog entries, schedules, cadence notes, deadlines, version history, dates.
- **Usage**: Track when things happened, when they're planned, or when they're due.
- **Example Sections**: `VERSION_HISTORY`, `SCHEDULE`, `DEADLINES`, `TIMELINE`, `RELEASE_DATES`

### WHY
- **Meaning**: Purpose, motivation, strategic context, rationale, or decision reasoning.
- **Content**: Problem statements, goals, decision rationale, motivation, business case, justification.
- **Usage**: Explain why something exists, why decisions were made, or why it matters.
- **Example Sections**: `RATIONALE`, `GOALS`, `PROBLEM_STATEMENT`, `MOTIVATION`, `DECISION_RECORD`

### HOW
- **Meaning**: Implementation steps, workflows, procedures, validation, or technical processes.
- **Content**: Procedures, scripts, testing plans, automation hooks, step-by-step guides, workflows.
- **Usage**: Document how to do something, how it works, or how to implement it.
- **Example Sections**: `IMPLEMENTATION`, `WORKFLOW`, `PROCEDURES`, `TESTING`, `SETUP`

### DO
- **Meaning**: Action items, tasks, to-do lists, actionable steps, or required actions.
- **Content**: Task lists, action items, next steps, required actions, implementation tasks.
- **Usage**: List what needs to be done, what actions are required, or what tasks remain.
- **Example Sections**: `ACTION_ITEMS`, `TODO`, `NEXT_STEPS`, `TASKS`, `REQUIRED_ACTIONS`

### HACK
- **Meaning**: Workarounds, temporary solutions, quick fixes, non-standard approaches, or experimental methods.
- **Content**: Workarounds, temporary fixes, experimental code, non-standard solutions, quick patches.
- **Usage**: Document temporary solutions, workarounds, or non-standard approaches that work but aren't ideal.
- **Example Sections**: `WORKAROUNDS`, `TEMPORARY_FIXES`, `QUICK_FIXES`, `EXPERIMENTAL`, `NON_STANDARD`

### OTHER
- **Meaning**: Miscellaneous content that doesn't fit into other categories, additional context, or supplementary information.
- **Content**: Notes, additional context, related links, references, supplementary information, edge cases.
- **Usage**: Catch-all for content that doesn't fit WHO/WHAT/WHERE/WHEN/WHY/HOW/DO/HACK but is still relevant.
- **Example Sections**: `NOTES`, `REFERENCES`, `RELATED_LINKS`, `EDGE_CASES`, `SUPPLEMENTARY`

### TAGS
- **Meaning**: Categorization labels, metadata tags, or classification markers (both as a collection and as a YAML field).
- **Content**: System tags (SYSTEM, DOCUMENTATION, DATABASE), domain tags, classification markers.
- **Usage**: Categorize content for filtering, searching, or organization. Maps to `tags:` YAML field.
- **Example Sections**: `TAG_REFERENCE`, `CATEGORIZATION`, `METADATA`, `CLASSIFICATION`

**Note**: TAGS serves dual purpose:
1. As a **collection** (content category): Use when documenting tag systems, tag references, or categorization schemes.
2. As a **YAML field**: The `tags:` array in frontmatter contains the actual tags applied to the document.

---

## USAGE_GUIDELINES

### Required vs Optional Sections

**In YAML Frontmatter (`collections:` field):**
- All 10 sections are **available** but not all are **required** in every document.
- Include only the collections that are relevant to your document's content.
- Minimum: At least one collection must be specified.
- Recommended: Include 3-6 collections that best describe your content.

**In Document Content:**
- Document sections (## HEADING) should align with the collections listed in frontmatter.
- You don't need a section for every collection—only include sections that have content.
- Use uppercase snake case for section headings (e.g., `## ACTION_ITEMS`, `## WORKAROUNDS`).

### Collection Selection Strategy

1. **Start with core collections**: WHO, WHAT, WHY, HOW (most documents need these)
2. **Add context collections**: WHERE (if location matters), WHEN (if timing matters)
3. **Add action collections**: DO (if there are tasks), HACK (if there are workarounds)
4. **Add catch-all**: OTHER (if you have misc content that doesn't fit)
5. **Add categorization**: TAGS (if documenting tag systems or tag references)

### Examples by Document Type

**System Documentation:**
- Collections: `[WHO, WHAT, WHERE, WHY, HOW]`
- Sections: `OVERVIEW`, `ARCHITECTURE`, `DEPLOYMENT`, `RATIONALE`, `IMPLEMENTATION`

**Migration Guide:**
- Collections: `[WHAT, WHY, HOW, DO]`
- Sections: `OVERVIEW`, `RATIONALE`, `MIGRATION_STEPS`, `ACTION_ITEMS`

**Troubleshooting Guide:**
- Collections: `[WHAT, HOW, HACK, OTHER]`
- Sections: `PROBLEM_DESCRIPTION`, `SOLUTION`, `WORKAROUNDS`, `NOTES`

**Release Notes:**
- Collections: `[WHO, WHAT, WHEN, WHY]`
- Sections: `AUTHORS`, `CHANGES`, `RELEASE_DATE`, `RATIONALE`

---

## EXAMPLES

### Example 1: System Documentation

```yaml
---
title: DATABASE_SCHEMA.md
agent_username: wolfie
agent_id: 008
channel_number: 002
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 2
tags: [SYSTEM, DATABASE, DOCUMENTATION]
collections: [WHO, WHAT, WHERE, WHY, HOW]
in_this_file_we_have: [MAINTAINER, OVERVIEW, SCHEMA_LOCATION, RATIONALE, IMPLEMENTATION]
superpositionally: ["FILEID_DATABASE_SCHEMA"]
---

# Database Schema Documentation

## MAINTAINER
- Maintained by: Database Team
- Contact: database@lupopedia.com

## OVERVIEW
This document describes the core database schema for LUPOPEDIA_PLATFORM.

## SCHEMA_LOCATION
- Schema files: `database/migrations/`
- Documentation: `docs/database/`

## RATIONALE
The schema design follows WOLFIE ID standards (BIGINT UNSIGNED) for future-proofing.

## IMPLEMENTATION
See `database/migrations/` for SQL migration files.
```

### Example 2: Migration Guide

```yaml
---
title: MIGRATION_1.4.2_TO_2.0.0.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, MIGRATION]
collections: [WHAT, WHY, HOW, DO]
in_this_file_we_have: [OVERVIEW, RATIONALE, MIGRATION_STEPS, ACTION_ITEMS]
superpositionally: ["FILEID_MIGRATION_GUIDE"]
---

# Migration Guide: v1.4.2 to v2.0.0

## OVERVIEW
This guide explains how to migrate WOLFIE Headers from v1.4.2 to v2.0.0 format.

## RATIONALE
v2.0.0 introduces breaking changes required by LUPOPEDIA_PLATFORM 1.0.0.

## MIGRATION_STEPS
1. Add `agent_id` and `channel_number` fields
2. Update `collections` to include new sections (DO, HACK, OTHER)
3. Add `version: 2.0.0` to frontmatter

## ACTION_ITEMS
- [ ] Update all header templates
- [ ] Migrate existing documentation
- [ ] Validate migrated headers
```

### Example 3: Troubleshooting Guide

```yaml
---
title: TROUBLESHOOTING.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, HOWTO, TROUBLESHOOTING]
collections: [WHAT, HOW, HACK, OTHER]
in_this_file_we_have: [PROBLEM_DESCRIPTION, SOLUTION, WORKAROUNDS, NOTES]
superpositionally: ["FILEID_TROUBLESHOOTING"]
---

# Troubleshooting Guide

## PROBLEM_DESCRIPTION
Common issues and their symptoms.

## SOLUTION
Standard solutions for each problem.

## WORKAROUNDS
Temporary fixes when standard solutions don't work.

## NOTES
Additional context and edge cases.
```

---

## VALIDATION_RULES

### YAML Frontmatter Validation

**Required Fields (v2.0.0):**
- `title`: Document title (string)
- `agent_id`: Agent identifier (string, e.g., "008" for WOLFIE)
- `channel_number`: Channel number (string, 000-999, zero-padded)
- `version`: Header format version (string, must be "2.0.0" for v2.0.0)
- `date_created`: Creation date (YYYY-MM-DD)
- `last_modified`: Last modification date (YYYY-MM-DD)
- `status`: Document status (draft, published, archived, etc.)
- `onchannel`: Channel number (integer, 1-999)
- `tags`: Array of tags (at least one required)
- `collections`: Array of collections (at least one required, must be from 10-section set)
- `in_this_file_we_have`: Array of section headings (optional but recommended)
- `superpositionally`: Array of file IDs (optional)

**Collection Validation:**
- All collections must be from the 10-section set: `WHO`, `WHAT`, `WHERE`, `WHEN`, `WHY`, `HOW`, `DO`, `HACK`, `OTHER`, `TAGS`
- Collections are case-sensitive (uppercase)
- At least one collection must be specified
- Collections should align with document content

**Tag Validation:**
- Tags must exist in channel-specific tag reference files
- Tags are case-sensitive
- At least one tag must be specified

**Channel Validation:**
- `channel_number` must be 000-999 (zero-padded string)
- `onchannel` must match `channel_number` (as integer)
- Channel directory must exist in `docs/channel_{onchannel}/`

**Agent Validation:**
- `agent_id` must be valid agent identifier
- Agent-specific channel directory should exist if `agent_username` is specified

### Content Validation

**Section Heading Format:**
- Section headings should use uppercase snake case (e.g., `## ACTION_ITEMS`)
- Sections should align with collections listed in frontmatter
- Sections don't need to match collections exactly, but should be related

**YAML Syntax:**
- Valid YAML syntax required
- Exactly one blank line between YAML frontmatter and first heading
- No trailing whitespace in YAML keys

---

## MIGRATION_FROM_V1.4.2

See `docs/MIGRATION_1.4.2_TO_2.0.0.md` for detailed migration instructions.

**Quick Summary:**
1. Add `agent_id` and `channel_number` fields
2. Add `version: 2.0.0` field
3. Update `collections` to use new 10-section format (add DO, HACK, OTHER if needed)
4. Ensure all required fields are present
5. Validate using v2.0.0 validation rules

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

