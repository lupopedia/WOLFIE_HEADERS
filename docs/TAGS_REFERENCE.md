---
title: TAGS_REFERENCE.md
agent_username: wolfie
date_created: 2025-11-09
last_modified: 2025-11-09
status: published
onchannel: 1
tags: [SYSTEM, REFERENCE]
collections: [WHAT, WHY, HOW]
in_this_file_we_have: [TAG_DIRECTORY, COLLECTION_DIRECTORY, USAGE_NOTES]
superpositionally: ["FILEID_WHS_TAGS"]
---

# Tag & Collection Reference (Channel 1)

## TAG_DIRECTORY

| Tag        | Meaning                                      | When to Use                                 |
|------------|----------------------------------------------|---------------------------------------------|
| SYSTEM     | Core platform documentation                   | Architecture, release notes, governance     |
| DOCUMENTATION | Guides, references, how-to content        | Tutorials, manuals, onboarding              |
| DATABASE   | Schema design, migrations, data tooling       | Table docs, standards, migrations           |
| SESSION    | Session logs, transcripts, retrospectives     | Meeting notes, daily summaries              |

## COLLECTION_DIRECTORY

| Collection | Meaning                         | Typical Sections                                      |
|------------|---------------------------------|-------------------------------------------------------|
| WHO        | People, agents, organizations    | Maintainer info, contact links, responsibilities      |
| WHAT       | Description of the artifact      | Overview, features, release summary                   |
| WHERE      | Paths, environments, hosting     | Directory maps, server info, deployment targets       |
| WHEN       | Timelines, release dates         | Changelogs, schedules, milestone targets              |
| WHY        | Rationale and strategy           | Problem statements, goals, decision records           |
| HOW        | Implementation details           | Setup, workflows, validation steps                    |
| HELP       | Support and contact              | FAQs, escalation paths, support channels              |

## USAGE_NOTES

- Tags are case-sensitive and should be pluralized only when defined in the directory.  
- Collections map directly to ontology classes; keep entries short (1–2 words).  
- Update this reference whenever new tags/collections are introduced so downstream tooling stays accurate.

---

Agent-specific overlays may redefine the meaning column; see `docs/channel_1/1_wolfie_rose/TAGS.md` once published.

