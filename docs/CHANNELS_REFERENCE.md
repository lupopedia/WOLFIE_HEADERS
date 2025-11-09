---
title: CHANNELS_REFERENCE.md
agent_username: wolfie
date_created: 2025-11-09
last_modified: 2025-11-09
status: published
onchannel: 1
tags: [SYSTEM, REFERENCE]
collections: [WHAT, WHERE, HOW]
in_this_file_we_have: [ACTIVE_CHANNELS, NAMING_RULES, REQUIRED_FILES, FUTURE_PLANS]
superpositionally: ["FILEID_WHS_CHANNELS"]
---

# Channels Reference

## ACTIVE_CHANNELS

| Channel | Folder                  | Purpose                                 | Status      |
|---------|------------------------|-----------------------------------------|-------------|
| 1       | `channel_1/`           | Base WOLFIE context (system definitions)| ✅ Active    |
| 2       | `channel_2/` *(planned)| Database-specific overlays              | 🚧 Planned  |
| 238     | `channel_238/` *(planned)| Spiritual + interfaith content        | 🚧 Planned  |

## NAMING_RULES

- Folder pattern: `{channel_number}_{agent_username}`.  
- Base folders (no agent overlay) live at `channel_{n}/`.  
- Agent overlays inherit from the base via fallback chaining.

## REQUIRED_FILES

Every channel must provide:

1. `TAGS.md` – meaning/usage for each tag.  
2. `COLLECTIONS.md` – definition of each collection grouping.  
3. `README.md` *(optional but recommended)* – channel overview, agent notes, onboarding tips.

## FUTURE_PLANS

- Channel 2 will document database standards, migrations, and tooling.  
- Additional channels will ship as LUPOPEDIA expands to new domains (media, education, spiritual care).  
- Automation scripts will eventually scaffold channel folders with default references and validation tasks.

---

For examples of agent-specific definitions, see `docs/channel_1/1_wolfie_wolfie/`.

