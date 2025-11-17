---
title: COLLECTIONS.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-11-09
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, REFERENCE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER, TAGS]
in_this_file_we_have: [COLLECTION_DEFINITIONS, DEPRECATED_COLLECTIONS]
superpositionally: ["FILEID_CHANNEL1_BASE_COLLECTIONS"]
---

# Collection Definitions (Channel 1 Base Layer)

**Version**: v2.0.0 (10-Section Format)  
**Status**: Base fallback definitions for Channel 1  
**Note**: This is the base layer. For authoritative definitions, see `1_wolfie_wolfie/COLLECTIONS.md`.

This document defines the 10 standard collections used in WOLFIE Headers v2.0.0. These are base definitions that serve as fallback when agent-specific definitions are not available.

---

## WHO
- **Meaning**: People, agents, teams, or organizations.  
- **Content**: Maintainer info, contact routes, roles/responsibilities, authorship, ownership.  
- **Usage**: Use when documenting who created, maintains, or is affected by the content.

## WHAT
- **Meaning**: Description of the document or component itself.  
- **Content**: Overview, feature summary, release scope, component description, purpose statement.  
- **Usage**: Core description of what the document is about or what the component does.

## WHERE
- **Meaning**: Physical or digital locations, paths, environments, or deployment targets.  
- **Content**: File paths, directory structures, URLs, server info, deployment targets, repository locations.  
- **Usage**: Document where files live, where to deploy, or where to find related resources.

## WHEN
- **Meaning**: Timelines, milestones, release dates, schedules, or temporal context.  
- **Content**: Changelog entries, schedules, cadence notes, deadlines, version history, dates.  
- **Usage**: Track when things happened, when they're planned, or when they're due.

## WHY
- **Meaning**: Purpose, motivation, strategic context, rationale, or decision reasoning.  
- **Content**: Problem statements, goals, decision rationale, motivation, business case, justification.  
- **Usage**: Explain why something exists, why decisions were made, or why it matters.

## HOW
- **Meaning**: Implementation steps, workflows, procedures, validation, or technical processes.  
- **Content**: Procedures, scripts, testing plans, automation hooks, step-by-step guides, workflows.  
- **Usage**: Document how to do something, how it works, or how to implement it.

## DO
- **Meaning**: Action items, tasks, to-do lists, actionable steps, or required actions.  
- **Content**: Task lists, action items, next steps, required actions, implementation tasks.  
- **Usage**: List what needs to be done, what actions are required, or what tasks remain.  
- **New in v2.0.0**: Added to support action-oriented documentation.

## HACK
- **Meaning**: Workarounds, temporary solutions, quick fixes, non-standard approaches, or experimental methods.  
- **Content**: Workarounds, temporary fixes, experimental code, non-standard solutions, quick patches.  
- **Usage**: Document temporary solutions, workarounds, or non-standard approaches that work but aren't ideal.  
- **New in v2.0.0**: Added to support workaround documentation.

## OTHER
- **Meaning**: Miscellaneous content that doesn't fit into other categories, additional context, or supplementary information.  
- **Content**: Notes, additional context, related links, references, supplementary information, edge cases.  
- **Usage**: Catch-all for content that doesn't fit WHO/WHAT/WHERE/WHEN/WHY/HOW/DO/HACK but is still relevant.  
- **New in v2.0.0**: Added to support flexible categorization.

## TAGS
- **Meaning**: Categorization labels, metadata tags, or classification markers (both as a collection and as a YAML field).  
- **Content**: System tags (SYSTEM, DOCUMENTATION, DATABASE), domain tags, classification markers.  
- **Usage**: Categorize content for filtering, searching, or organization. Maps to `tags:` YAML field.  
- **New in v2.0.0**: Added as collection (also exists as YAML field).  
- **Note**: TAGS serves dual purpose:
  1. As a **collection** (content category): Use when documenting tag systems, tag references, or categorization schemes.
  2. As a **YAML field**: The `tags:` array in frontmatter contains the actual tags applied to the document.

---

## DEPRECATED_COLLECTIONS

**HELP** (v1.4.2):  
- **Status**: Deprecated in v2.0.0
- **Previous Meaning**: Support links or contact points
- **Migration**: Use `OTHER` for support/help content, or `WHO` for contact information
- **Rationale**: HELP was merged into OTHER for flexibility, or can be represented via WHO for contact info

---

## USAGE_GUIDELINES

- Use uppercase snake case for all collection names (WHO, WHAT, etc.)
- Include only collections relevant to your document (minimum: 1, recommended: 3-6)
- Collections should align with document content sections
- See `docs/10_SECTION_FORMAT_GUIDE.md` for detailed usage examples
- For authoritative definitions, see `1_wolfie_wolfie/COLLECTIONS.md`

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

