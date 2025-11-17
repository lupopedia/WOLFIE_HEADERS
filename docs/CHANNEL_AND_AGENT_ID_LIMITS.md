---
title: CHANNEL_AND_AGENT_ID_LIMITS.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, LIMITS, VALIDATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, CHANNEL_LIMITS, AGENT_ID_LIMITS, VALIDATION_RULES, EXAMPLES]
superpositionally: ["FILEID_CHANNEL_AGENT_LIMITS"]
shadow_aliases: []
parallel_paths: []
---

# Channel and Agent ID Limits

## OVERVIEW

**CRITICAL CONSTRAINT**: Channels and Agent IDs are strictly limited to the range **000-999** (inclusive).

- **Maximum Channel Number**: **999** (not 1000)
- **Maximum Agent ID**: **999** (not 1000)
- **Range**: 000-999 (inclusive)
- **Total Count**: 1000 channels/agents (000-999 inclusive)

**This is a hard limit** - there cannot be a channel or agent ID greater than 999.

---

## CHANNEL_LIMITS

### Channel Number Range

- **Minimum**: 000
- **Maximum**: **999**
- **Format**: Zero-padded 3-digit string (e.g., "000", "001", "010", "999")
- **Total Channels**: 1000 (000-999 inclusive)

### Examples

✅ **Valid Channel Numbers**:
- `channel_number: "000"` - Channel 0 (minimum)
- `channel_number: "001"` - Channel 1
- `channel_number: "010"` - Channel 10
- `channel_number: "999"` - Channel 999 (maximum)

❌ **Invalid Channel Numbers**:
- `channel_number: "1000"` - **OUT OF RANGE** (maximum is 999)
- `channel_number: "1001"` - **OUT OF RANGE** (maximum is 999)
- `channel_number: "-1"` - **OUT OF RANGE** (minimum is 000)

### Database Column

The `channel_id` column in `content_headers` table:
- **Type**: `bigint(20) UNSIGNED`
- **Range**: 0-999 (maximum 999)
- **Validation**: Must be between 0 and 999 (inclusive)

---

## AGENT_ID_LIMITS

### Agent ID Range

- **Minimum**: 000
- **Maximum**: **999**
- **Format**: Zero-padded 3-digit string (e.g., "000", "001", "010", "999")
- **Total Agent IDs**: 1000 (000-999 inclusive)

### Examples

✅ **Valid Agent IDs**:
- `agent_id: "000"` - Agent 0 (minimum)
- `agent_id: "001"` - Agent 1
- `agent_id: "008"` - Agent 8 (WOLFIE)
- `agent_id: "010"` - Agent 10 (LILITH)
- `agent_id: "999"` - Agent 999 (maximum)

❌ **Invalid Agent IDs**:
- `agent_id: "1000"` - **OUT OF RANGE** (maximum is 999)
- `agent_id: "1001"` - **OUT OF RANGE** (maximum is 999)
- `agent_id: "-1"` - **OUT OF RANGE** (minimum is 000)

### Database Column

The `agent_id` column in `content_headers` table:
- **Type**: `bigint(20) UNSIGNED`
- **Range**: 0-999 (maximum 999)
- **Validation**: Must be between 0 and 999 (inclusive)

---

## VALIDATION_RULES

### YAML Frontmatter Validation

Both `agent_id` and `channel_number` must:
1. Be zero-padded 3-digit strings (e.g., "000", "001", "999")
2. Be within range 000-999 (inclusive)
3. Not exceed 999

### Validation Errors

**Error Messages**:
- `"agent_id out of range (must be 000-999, maximum 999)"`
- `"channel_number out of range (must be 000-999, maximum 999)"`
- `"Channel ID out of range (must be 000-999, maximum 999)"`

### Agent File Naming

Agent file naming convention: `who_is_agent_[channel_id]_[agent_name].php`

- **Channel ID**: Must be 000-999 (maximum 999)
- **Validation**: File names with channel IDs > 999 will be rejected

**Example Validation**:
- ✅ `who_is_agent_008_wolfie.php` - Valid (channel 008)
- ✅ `who_is_agent_999_unknown.php` - Valid (channel 999, maximum)
- ❌ `who_is_agent_1000_wolfie.php` - **INVALID** (exceeds maximum 999)

---

## EXAMPLES

### Valid Configuration

```yaml
---
agent_id: "008"
channel_number: "001"
onchannel: 1
---
```

### Invalid Configuration (Out of Range)

```yaml
---
agent_id: "1000"        # ❌ OUT OF RANGE (maximum 999)
channel_number: "1000"  # ❌ OUT OF RANGE (maximum 999)
onchannel: 1000         # ❌ OUT OF RANGE (maximum 999)
---
```

### Database Query Examples

```sql
-- Valid: Query channel 999 (maximum)
SELECT * FROM content_headers WHERE channel_id = 999;

-- Invalid: Query channel 1000 (exceeds maximum)
SELECT * FROM content_headers WHERE channel_id = 1000;  -- Will return no results

-- Valid: Query agent 999 (maximum)
SELECT * FROM content_headers WHERE agent_id = 999;

-- Invalid: Query agent 1000 (exceeds maximum)
SELECT * FROM content_headers WHERE agent_id = 1000;  -- Will return no results
```

---

## IMPORTANT_NOTES

1. **Hard Limit**: 999 is the absolute maximum for both channels and agent IDs. There is no channel 1000 or agent ID 1000.

2. **Zero-Padding**: All channel numbers and agent IDs must be zero-padded to 3 digits (e.g., "001", not "1").

3. **Total Count**: While there are 1000 channels/agents total (000-999 inclusive), the highest number is 999.

4. **Validation**: All validation scripts and database constraints enforce this limit.

5. **Future Expansion**: If expansion beyond 999 is needed, this would require a major version change (v3.0.0) and architectural redesign.

---

**Version**: v2.0.2  
**Last Updated**: 2025-01-27  
**Status**: Current

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

