---
title: CHANNELS_REFERENCE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.0
date_created: 2025-11-09
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, REFERENCE]
collections: [WHAT, WHERE, HOW]
in_this_file_we_have: [ACTIVE_CHANNELS, NAMING_RULES, REQUIRED_FILES, CHANNEL_NUMBER_FIELD, FUTURE_PLANS]
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

## CHANNEL_NUMBER_FIELD

**v2.0.0 Requirement**: All headers must include `channel_number` field.

- **Format**: Zero-padded string (000-999)
- **Examples**: "001" for channel 1, "002" for channel 2, "010" for channel 10, "999" for channel 999
- **Must Match**: `channel_number` (string) must match `onchannel` (integer)
  - `channel_number: "001"` matches `onchannel: 1`
  - `channel_number: "002"` matches `onchannel: 2`
- **Purpose**: Supports channel architecture (000-999, maximum 999) for LUPOPEDIA_PLATFORM agent system

**Validation**: See `docs/VALIDATION_RULES_2.0.0.md` for channel number validation rules.

## FUTURE_PLANS

- Channel 2 will document database standards, migrations, and tooling.  
- Additional channels will ship as LUPOPEDIA expands to new domains (media, education, spiritual care).  
- Automation scripts will eventually scaffold channel folders with default references and validation tasks.

---

For examples of agent-specific definitions, see `docs/channel_1/1_wolfie_wolfie/`.

