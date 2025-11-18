---
title: DATABASE_INTEGRATION.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.2
date_created: 2025-01-27
last_modified: 2025-01-27
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, DATABASE, INTEGRATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, CONTENT_HEADERS_TABLE, CHANNEL_ID_COLUMN, AGENT_NAME_COLUMN, QUERY_EXAMPLES, INTEGRATION_PATTERNS, MIGRATION_NOTES]
superpositionally: ["FILEID_DATABASE_INTEGRATION"]
shadow_aliases: []
parallel_paths: []
---

# Database Integration Guide

## OVERVIEW

WOLFIE Headers v2.0.2 integrates with LUPOPEDIA_PLATFORM's `content_headers` table to enable database-driven header storage and retrieval. This guide documents the table structure, column requirements, and query patterns.

**Version**: v2.0.2  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**Status**: Current

---

## CONTENT_HEADERS_TABLE

### Table Structure

The `content_headers` table stores WOLFIE Headers metadata in a database format, enabling:
- Channel-based header organization (channels 000-999, maximum 999)
- Agent-based header attribution
- Query performance with indexes
- Integration with LUPOPEDIA_PLATFORM agent system

### Key Columns for WOLFIE Headers v2.0.2

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| `id` | bigint(20) UNSIGNED | Yes | Primary key |
| `channel_id` | bigint(20) UNSIGNED | Yes | Channel number (000-999, maximum 999) |
| `agent_id` | bigint(20) UNSIGNED | Yes | Agent ID reference |
| `agent_name` | VARCHAR(100) | Yes | Agent name (e.g., WOLFIE, LILITH) |
| `content_id` | bigint(20) UNSIGNED | Yes | Content reference |
| `value` | text | Yes | Header value |
| `sot_type` | enum | Optional | Source of truth type (who, what, where, etc.) |
| `sot_id` | bigint(20) UNSIGNED | Optional | Source of truth ID |
| `created_at` | timestamp | Yes | Creation timestamp |
| `updated_at` | timestamp | Yes | Update timestamp |
| `is_active` | tinyint(1) | Yes | Active flag |

**Full table structure**: See `database/schema/lupopedia_11_13_2025.sql` for complete table definition.

---

## CHANNEL_ID_COLUMN

### Purpose

The `channel_id` column stores the channel number (000-999) where the header belongs. This enables:
- Channel-based header organization
- Multi-channel header routing
- Agent file naming: `who_is_agent_[channel_id]_[agent_name].php`

### Validation Rules

- **Range**: 0 to 999 (inclusive)
- **Format**: Integer in database, zero-padded string in file names (e.g., "008", "010", "075")
- **Required**: Yes (NOT NULL)
- **Default**: 1 (Channel 1)

### Examples

```sql
-- Valid channel_id values
channel_id = 0    -- Channel 000
channel_id = 8    -- Channel 008
channel_id = 10   -- Channel 010
channel_id = 75   -- Channel 075
channel_id = 999  -- Channel 999

-- Invalid channel_id values
channel_id = 1000  -- OUT OF RANGE
channel_id = -1    -- OUT OF RANGE
```

### Query Patterns

```sql
-- Get all headers for a specific channel
SELECT * FROM content_headers
WHERE channel_id = 8
  AND is_active = 1;

-- Get headers for multiple channels
SELECT * FROM content_headers
WHERE channel_id IN (1, 8, 10, 75)
  AND is_active = 1;

-- Count headers per channel
SELECT channel_id, COUNT(*) as header_count
FROM content_headers
WHERE is_active = 1
GROUP BY channel_id
ORDER BY channel_id;
```

---

## AGENT_NAME_COLUMN

### Purpose

The `agent_name` column stores the agent name (e.g., WOLFIE, LILITH, VISHWAKARMA) for direct lookup without joining to the `agents` table. This enables:
- Direct agent name queries
- Agent file naming: `who_is_agent_[channel_id]_[agent_name].php`
- Performance optimization (no JOIN required)

### Validation Rules

- **Type**: VARCHAR(100) NOT NULL
- **Format**: UPPER case (e.g., "WOLFIE", "LILITH", "VISHWAKARMA")
- **Source**: Populated from `agents.username` (UPPER case)
- **Required**: Yes (NOT NULL)
- **Indexed**: Yes (`idx_agent_name`)

### Examples

```sql
-- Valid agent_name values
agent_name = 'WOLFIE'
agent_name = 'LILITH'
agent_name = 'VISHWAKARMA'
agent_name = 'MAAT'
agent_name = 'THEMIS'

-- Invalid agent_name values
agent_name = ''           -- EMPTY (NOT NULL constraint)
agent_name = 'wolfie'     -- Should be UPPER case
agent_name = 'WOLFIE '    -- Trailing space
```

### Query Patterns

```sql
-- Get all headers for a specific agent
SELECT * FROM content_headers
WHERE agent_name = 'WOLFIE'
  AND is_active = 1;

-- Get headers for multiple agents
SELECT * FROM content_headers
WHERE agent_name IN ('WOLFIE', 'LILITH', 'VISHWAKARMA')
  AND is_active = 1;

-- Count headers per agent
SELECT agent_name, COUNT(*) as header_count
FROM content_headers
WHERE is_active = 1
GROUP BY agent_name
ORDER BY agent_name;
```

---

## QUERY_EXAMPLES

### Basic Queries

```sql
-- Get headers by channel_id
SELECT * FROM content_headers
WHERE channel_id = 8
  AND is_active = 1
ORDER BY created_at DESC;

-- Get headers by agent_name
SELECT * FROM content_headers
WHERE agent_name = 'WOLFIE'
  AND is_active = 1
ORDER BY created_at DESC;

-- Get headers by both channel_id and agent_name
SELECT * FROM content_headers
WHERE channel_id = 8
  AND agent_name = 'WOLFIE'
  AND is_active = 1
ORDER BY created_at DESC;
```

### Advanced Queries

```sql
-- Get headers with agent and channel info
SELECT 
    ch.*,
    a.username as agent_username,
    a.display_name as agent_display_name,
    c.name as channel_name
FROM content_headers ch
INNER JOIN agents a ON ch.agent_id = a.id
LEFT JOIN channels c ON ch.channel_id = c.id
WHERE ch.is_active = 1
ORDER BY ch.channel_id, ch.agent_name, ch.created_at DESC;

-- Get header distribution by channel and agent
SELECT 
    channel_id,
    agent_name,
    COUNT(*) as header_count,
    MIN(created_at) as first_header,
    MAX(created_at) as last_header
FROM content_headers
WHERE is_active = 1
GROUP BY channel_id, agent_name
ORDER BY channel_id, agent_name;

-- Get headers for agent file lookup
-- (Matches pattern: who_is_agent_[channel_id]_[agent_name].php)
SELECT 
    ch.*,
    CONCAT('who_is_agent_', LPAD(ch.channel_id, 3, '0'), '_', LOWER(ch.agent_name), '.php') as agent_file_name
FROM content_headers ch
WHERE ch.is_active = 1
  AND ch.channel_id BETWEEN 0 AND 999
  AND ch.agent_name != ''
ORDER BY ch.channel_id, ch.agent_name;
```

---

## INTEGRATION_PATTERNS

### Agent File Naming Integration

The `channel_id` and `agent_name` columns directly support the agent file naming convention:

**Pattern**: `who_is_agent_[channel_id]_[agent_name].php`

**Example Mappings**:
- `channel_id = 8, agent_name = 'WOLFIE'` → `who_is_agent_008_wolfie.php`
- `channel_id = 10, agent_name = 'LILITH'` → `who_is_agent_010_lilith.php`
- `channel_id = 75, agent_name = 'VISHWAKARMA'` → `who_is_agent_075_vishwakarma.php`

**SQL Query for Agent File Lookup**:
```sql
SELECT 
    ch.*,
    CONCAT('who_is_agent_', LPAD(ch.channel_id, 3, '0'), '_', LOWER(ch.agent_name), '.php') as agent_file_path
FROM content_headers ch
WHERE ch.channel_id = 8
  AND ch.agent_name = 'WOLFIE'
  AND ch.is_active = 1;
```

### WOLFIE Headers Frontmatter Integration

The database columns map to WOLFIE Headers YAML frontmatter:

| Database Column | WOLFIE Header Field | Notes |
|----------------|---------------------|-------|
| `channel_id` | `channel_number` | Zero-padded string in frontmatter (e.g., "008") |
| `agent_name` | `agent_username` | UPPER case in DB, lowercase in file names |
| `agent_id` | `agent_id` | Direct mapping |
| `sot_type` | `collections` | Maps to collection type |
| `value` | Content | Header value/content |

---

## MIGRATION_NOTES

### Migration History

- **Migration 1072** (2025-01-27): Added `agent_name` column
- **Migration 1073** (2025-01-27): Populated `agent_name` from `agents.username`
- **Migration 1074** (2025-01-27): Validation queries

### Migration Files

- `database/migrations/1072_2025_01_27_add_agent_name_to_content_headers.sql`
- `database/migrations/1073_2025_01_27_populate_agent_name_in_content_headers.sql`
- `database/migrations/1074_2025_01_27_validate_agent_name_migration.sql`

### Validation

Run migration 1074 validation queries to verify:
- Column structure is correct
- Index exists and is functional
- Data was populated correctly
- Data matches source (agents.username)
- Channel IDs are in valid range (000-999)

---

## BEST_PRACTICES

### Query Performance

1. **Use indexes**: Always use `idx_agent_name` and `idx_channel_id` in WHERE clauses
2. **Filter by is_active**: Always include `is_active = 1` in queries
3. **Limit results**: Use `LIMIT` for large result sets
4. **Order by indexed columns**: Use `channel_id` and `agent_name` in ORDER BY

### Data Integrity

1. **Always populate agent_name**: Never leave empty strings
2. **Validate channel_id range**: Ensure 0-999 before INSERT
3. **Use UPPER case**: Store agent_name in UPPER case for consistency
4. **Sync with agents table**: Keep agent_name in sync with agents.username

### Integration Patterns

1. **Agent file lookup**: Use channel_id and agent_name to construct file paths
2. **Header retrieval**: Query by channel_id and/or agent_name for filtering
3. **Multi-channel support**: Use channel_id IN (...) for cross-channel queries
4. **Agent attribution**: Use agent_name for agent-specific header filtering

---

## RELATED_DOCUMENTATION

- `docs/AGENT_FILE_NAMING.md` - Agent file naming convention
- `docs/MIGRATION_2.0.1_TO_2.0.2.md` - Migration guide
- `TODO_2.0.2.md` - Complete TODO plan
- `database/migrations/` - Migration scripts

---

**Last Updated**: 2025-11-17  
**Version**: 2.0.2  
**Status**: Current

---

## AGENT_COMMUNICATION_PROTOCOL_INTEGRATION

**How WOLFIE Headers Database Integration Works with Agent Communication Protocol:**

The `content_headers` table with `agent_name` column enables the Agent Communication Protocol (Receptionist Model) to:

1. **Route Requests**: WOLFIE (008) reads `agent_id` and `channel_number` from headers to route tasks
2. **Agent Identification**: `agent_name` column provides human-readable agent identification for routing
3. **Channel Mapping**: Direct mapping (Agent ID = Channel Number) uses `channel_id` from `content_headers`
4. **Normalization**: VISH (075) uses `agent_name` to normalize requests and track changes

**Protocol Flow:**
```
User Request → WOLFIE (008) reads headers → Routes to 007 → VISH (075) normalizes using agent_name
```

**For detailed protocol documentation**, see: LUPOPEDIA_PLATFORM `docs/AGENT_COMMUNICATION_PROTOCOL.md`

---

*Captain WOLFIE, signing off. Coffee hot. Ship flying. Database integrated. Maximum 999.* ☕✨

