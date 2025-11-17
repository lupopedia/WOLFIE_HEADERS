---
title: TAGS_REFERENCE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-11-09
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, REFERENCE]
collections: [WHAT, WHY, HOW]
in_this_file_we_have: [TAG_DIRECTORY, COLLECTION_DIRECTORY, USAGE_NOTES, V2.0.0_UPDATES]
superpositionally: ["FILEID_WHS_TAGS"]
---

# Tag & Collection Reference (Channel 1)

**Version**: v2.0.0  
**Status**: Updated for 10-section format

## TAG_DIRECTORY

| Tag        | Meaning                                      | When to Use                                 |
|------------|----------------------------------------------|---------------------------------------------|
| SYSTEM     | Core platform documentation                   | Architecture, release notes, governance     |
| DOCUMENTATION | Guides, references, how-to content        | Tutorials, manuals, onboarding              |
| DATABASE   | Schema design, migrations, data tooling       | Table docs, standards, migrations           |
| SESSION    | Session logs, transcripts, retrospectives     | Meeting notes, daily summaries              |
| MIGRATION  | Migration guides, version upgrades            | Migration docs, upgrade guides               |
| REFERENCE  | Reference documentation                       | API references, tag/collection references    |

## COLLECTION_DIRECTORY

**v2.0.0 10-Section Format** - All collections must be from this set:

| Collection | Meaning                         | Typical Sections                                      |
|------------|---------------------------------|-------------------------------------------------------|
| WHO        | People, agents, organizations    | Maintainer info, contact links, responsibilities      |
| WHAT       | Description of the artifact      | Overview, features, release summary                   |
| WHERE      | Paths, environments, hosting     | Directory maps, server info, deployment targets       |
| WHEN       | Timelines, release dates         | Changelogs, schedules, milestone targets              |
| WHY        | Rationale and strategy           | Problem statements, goals, decision records           |
| HOW        | Implementation details           | Setup, workflows, validation steps                    |
| DO         | Action items, tasks              | TODO lists, action items, next steps (NEW in v2.0.0) |
| HACK       | Workarounds, temporary fixes     | Workarounds, quick fixes, non-standard solutions (NEW in v2.0.0) |
| OTHER      | Miscellaneous content            | Notes, references, supplementary info (NEW in v2.0.0) |
| TAGS       | Categorization labels            | Tag systems, tag references, metadata (NEW in v2.0.0) |

**Deprecated Collections (v1.4.2):**
- `HELP` - Use `OTHER` for support/help content, or `WHO` for contact information

## USAGE_NOTES

- Tags are case-sensitive and should be pluralized only when defined in the directory.  
- Collections map directly to ontology classes; keep entries short (1–2 words).  
- **v2.0.0**: All collections must be from the 10-section set (WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS).
- Update this reference whenever new tags/collections are introduced so downstream tooling stays accurate.

## V2.0.0_UPDATES

**New Collections:**
- `DO`: Added for action-oriented documentation (tasks, action items)
- `HACK`: Added for workaround documentation (temporary fixes, non-standard solutions)
- `OTHER`: Added for miscellaneous content (catch-all category)
- `TAGS`: Added as collection (also exists as YAML field)

**Deprecated:**
- `HELP`: Deprecated in v2.0.0. Use `OTHER` or `WHO` instead.

**See Also:**
- `docs/10_SECTION_FORMAT_GUIDE.md` - Detailed 10-section format guide
- `docs/channel_1/1_wolfie_wolfie/COLLECTIONS.md` - Authoritative collection definitions

---

Agent-specific overlays may redefine the meaning column; see `docs/channel_1/1_wolfie_rose/TAGS.md` once published.

