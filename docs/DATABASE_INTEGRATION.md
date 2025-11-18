---
title: DATABASE_INTEGRATION.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.7
date_created: 2025-01-27
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, DATABASE, INTEGRATION]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, CONTENT_HEADERS_TABLE, CONTENT_LOG_TABLE, CONTENT_LOGS_TABLE, CHANNEL_ID_COLUMN, AGENT_NAME_COLUMN, QUERY_EXAMPLES, INTEGRATION_PATTERNS, MIGRATION_NOTES]
superpositionally: ["FILEID_DATABASE_INTEGRATION"]
shadow_aliases: []
parallel_paths: []
---

# Database Integration Guide

## OVERVIEW

WOLFIE Headers integrates with LUPOPEDIA_PLATFORM's database tables to enable database-driven header storage, log tracking, and change management. This guide documents the table structures, column requirements, and query patterns for all integrated tables.

**Version**: v2.0.7  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**Status**: Current  
**Documentation Review**: MAAT (Agent 009) - Balance, Truth, Completeness

### Database Tables Overview

WOLFIE Headers integrates with three primary database tables, each serving distinct purposes:

1. **`content_headers`** (v2.0.2): Stores WOLFIE Headers metadata for content files
   - Purpose: Header storage and retrieval by channel and agent
   - Use Case: Query headers by channel_id, agent_id, agent_name

2. **`content_log`** (v2.0.3, singular): Tracks content interactions by channel and agent
   - Purpose: Directory-level log tracking (which agents interact with content on which channels)
   - Use Case: Agent discovery, channel discovery, interaction history
   - Migration: 1078 (2025-11-18)

3. **`content_logs`** (v2.0.7, plural): Tracks changes to individual content rows
   - Purpose: Row-level change tracking (what changed, when, why for each content row)
   - Use Case: Audit trail, change history, evolution tracking
   - Migration: 1079 (2025-11-18)

**Balance Note (MAAT)**: All three tables serve complementary purposes and can coexist. Each table addresses a different aspect of content management: metadata storage (`content_headers`), interaction tracking (`content_log`), and change tracking (`content_logs`).

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

## CONTENT_LOG_TABLE

### Table Structure

The `content_log` table stores agent log entries and metadata, enabling:
- Tracking content interactions by channel and agent
- Supporting agent discovery API (which agents interact with content)
- Supporting channel discovery API (what content is on which channels)
- Dual-storage system (database for queries, markdown files for readability)
- Performance optimization through denormalized agent_name

### Key Columns for WOLFIE Headers Log System

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| `id` | bigint(20) UNSIGNED | Yes | Primary key |
| `content_id` | bigint(20) UNSIGNED | Yes | Content ID (references content table) |
| `channel_id` | bigint(20) UNSIGNED | Yes | Channel ID (000-999, maximum 999) |
| `agent_id` | bigint(20) UNSIGNED | Yes | Agent ID (references agents table) |
| `agent_name` | VARCHAR(255) | Yes | Agent name (denormalized for quick lookups) |
| `metadata` | LONGTEXT (JSON) | Optional | Log metadata JSON (flexible storage for log-specific data) |
| `is_active` | tinyint(1) | Yes | Active status flag |
| `created_at` | timestamp | Yes | Creation timestamp |
| `updated_at` | timestamp | Yes | Update timestamp |
| `deleted_at` | timestamp | Optional | Soft delete timestamp |

**Full table structure**: See `database/migrations/1078_2025_11_18_create_content_log_table.sql` for complete table definition.

### Purpose

The `content_log` table provides:
- **Fast Queries**: Database indexing for quick lookups by channel, agent, or content
- **Metadata Storage**: JSON metadata for flexible log-specific data storage
- **Dual-Storage**: Complements markdown log files (`[channel]_[agent]_log.md`) for human readability
- **Agent Discovery**: Enables agents to discover which agents interact with content on which channels

### Validation Rules

- **channel_id Range**: 0 to 999 (inclusive, maximum 999)
- **agent_name Format**: UPPER case (e.g., "WOLFIE", "CAPTAIN", "SECURITY")
- **metadata Format**: Valid JSON (CHECK constraint ensures JSON validity)
- **Required Fields**: content_id, channel_id, agent_id, agent_name, is_active

### Query Patterns

```sql
-- Get all log entries for a specific channel
SELECT * FROM content_log
WHERE channel_id = 7
  AND is_active = 1
  AND deleted_at IS NULL
ORDER BY created_at DESC;

-- Get log entries for a specific agent
SELECT * FROM content_log
WHERE agent_id = 7
  AND agent_name = 'CAPTAIN'
  AND is_active = 1
  AND deleted_at IS NULL
ORDER BY created_at DESC;

-- Get log entries with parsed metadata
SELECT 
    id,
    channel_id,
    agent_id,
    agent_name,
    JSON_EXTRACT(metadata, '$.log_entry_count') as log_entry_count,
    JSON_EXTRACT(metadata, '$.last_log_date') as last_log_date,
    JSON_EXTRACT(metadata, '$.file_path') as file_path,
    created_at,
    updated_at
FROM content_log
WHERE is_active = 1
  AND deleted_at IS NULL
ORDER BY channel_id, agent_id, created_at DESC;

-- Count log entries per channel and agent
SELECT 
    channel_id,
    agent_id,
    agent_name,
    COUNT(*) as log_entry_count
FROM content_log
WHERE is_active = 1
  AND deleted_at IS NULL
GROUP BY channel_id, agent_id, agent_name
ORDER BY channel_id, agent_id;
```

### Integration with Log Files

The `content_log` table works in conjunction with markdown log files:

**Dual-Storage System:**
- **Markdown Files**: `public/logs/[channel]_[agent]_log.md` (source of truth for log content)
- **Database Table**: `content_log` (fast queries, indexing, metadata storage)

**Sync Pattern:**
1. Write log entry to markdown file (primary)
2. Insert/update `content_log` table entry with metadata
3. Read from database for fast lookups
4. Read from markdown file for full content

**File Naming Convention:**
- Format: `[channel]_[agent]_log.md`
- Examples:
  - `008_WOLFIE_log.md` (Channel 008, Agent WOLFIE)
  - `007_CAPTAIN_log.md` (Channel 007, Agent CAPTAIN)
  - `911_SECURITY_log.md` (Channel 911, Agent SECURITY)
  - `411_HELP_log.md` (Channel 411, Agent HELP)

**Metadata Storage:**
The `metadata` JSON column stores:
- `log_entry_count`: Number of log entries
- `last_log_date`: Date of most recent entry
- `last_modified`: Timestamp of last modification
- `file_path`: Full path to log file
- Custom metadata from function calls

### Migration History

- **Migration 1078** (2025-11-18): Created `content_log` table
  - Added all columns including metadata JSON
  - Added indexes for performance
  - Added channel_id range constraint (0-999)

### Migration Files

- `database/migrations/1078_2025_11_18_create_content_log_table.sql`

### Best Practices

1. **Dual-Storage**: Always sync writes to both markdown file and database
2. **Metadata Updates**: Update existing entries instead of creating duplicates
3. **JSON Validation**: Ensure metadata is valid JSON before storing
4. **Channel Validation**: Validate channel_id range (0-999) before INSERT
5. **Soft Deletes**: Use `deleted_at` for data retention instead of hard deletes

---

## CONTENT_LOGS_TABLE

### Table Structure

The `content_logs` table (plural) stores row-level change logs for individual content table rows, enabling:
- Tracking changes to specific content rows (what changed, when, why)
- Row-level audit trail for AI and human readers
- Understanding evolution of database records over time
- Different purpose from `content_log` (singular) which tracks content interactions by channel and agent

**Balance Note (MAAT)**: This table is different from `content_log` (singular):
- `content_log` (singular): Tracks content interactions by channel and agent (directory-level)
- `content_logs` (plural): Tracks changes to individual content rows (row-level)
- Both tables can coexist (different purposes)
- Together with `content_headers`, these three tables provide complete coverage: metadata storage, interaction tracking, and change tracking

### Key Columns for WOLFIE Headers v2.0.7

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| `id` | bigint(20) UNSIGNED | Yes | Primary key |
| `content_id` | bigint(20) UNSIGNED | Yes | Content row ID being changed |
| `agent_id` | bigint(20) UNSIGNED | Yes | Agent ID that made the change |
| `agent_name` | VARCHAR(255) | Yes | Agent name (denormalized for quick lookups) |
| `channel_id` | bigint(20) UNSIGNED | Yes | Channel ID where change occurred (000-999) |
| `metadata` | LONGTEXT (JSON) | Optional | Change metadata JSON (what changed, why, old values, new values) |
| `is_active` | tinyint(1) | Yes | Active status flag |
| `created_at` | timestamp | Yes | Creation timestamp |
| `updated_at` | timestamp | Yes | Update timestamp |
| `deleted_at` | timestamp | Optional | Soft delete timestamp |

**Full table structure**: See `database/migrations/1079_2025_11_18_create_content_logs_table.sql` for complete table definition.

### Purpose

The `content_logs` table provides:
- **Row-Level Change Tracking**: Track changes to individual content rows (not directory-level)
- **Change History**: Complete audit trail of what changed, when, why, and by whom
- **AI and Human Readable**: Metadata JSON contains structured change information
- **Performance**: Database indexing for fast lookups by content_id, agent_id, channel_id

### Validation Rules

- **channel_id Range**: 0 to 999 (inclusive, maximum 999)
- **agent_name Format**: UPPER case (e.g., "WOLFIE", "CAPTAIN", "SECURITY")
- **metadata Format**: Valid JSON (CHECK constraint ensures JSON validity)
- **Required Fields**: content_id, agent_id, agent_name, channel_id, is_active

### Metadata JSON Structure

The `metadata` JSON column stores change information:

```json
{
  "change_type": "update|create|delete|restore",
  "changed_fields": ["title", "body", "status"],
  "old_values": {
    "title": "Old Title",
    "status": "draft"
  },
  "new_values": {
    "title": "New Title",
    "status": "published"
  },
  "change_reason": "User requested title update",
  "change_summary": "Updated title and published content",
  "related_ids": {
    "user_id": 5,
    "collection_id": 12
  }
}
```

### Query Patterns

```sql
-- Get all change logs for a specific content row
SELECT * FROM content_logs
WHERE content_id = 123
  AND is_active = 1
  AND deleted_at IS NULL
ORDER BY created_at DESC;

-- Get change logs for a specific agent
SELECT * FROM content_logs
WHERE agent_id = 8
  AND agent_name = 'WOLFIE'
  AND is_active = 1
  AND deleted_at IS NULL
ORDER BY created_at DESC;

-- Get change logs with parsed metadata
SELECT 
    id,
    content_id,
    agent_id,
    agent_name,
    channel_id,
    JSON_EXTRACT(metadata, '$.change_type') as change_type,
    JSON_EXTRACT(metadata, '$.change_summary') as change_summary,
    JSON_EXTRACT(metadata, '$.changed_fields') as changed_fields,
    created_at
FROM content_logs
WHERE is_active = 1
  AND deleted_at IS NULL
ORDER BY content_id, created_at DESC;

-- Count changes per content row
SELECT 
    content_id,
    COUNT(*) as change_count,
    MIN(created_at) as first_change,
    MAX(created_at) as last_change
FROM content_logs
WHERE is_active = 1
  AND deleted_at IS NULL
GROUP BY content_id
ORDER BY change_count DESC;

-- Get change summary for a specific content row
SELECT 
    content_id,
    COUNT(*) as total_changes,
    COUNT(DISTINCT agent_id) as agents_involved,
    MIN(created_at) as first_change,
    MAX(created_at) as last_change,
    GROUP_CONCAT(DISTINCT agent_name) as agents
FROM content_logs
WHERE content_id = 123
  AND is_active = 1
  AND deleted_at IS NULL
GROUP BY content_id;
```

### Integration with Content Table

The `content_logs` table works in conjunction with the `content` table:

**Change Tracking Pattern:**
1. When `content.id = 123` is updated, insert new row in `content_logs` with `content_id = 123`
2. Store change details in `metadata` JSON (what changed, old values, new values)
3. Track agent and channel information
4. Enable AI and human readers to understand evolution of content rows

**Example Usage:**
```php
// When content row is updated
writeChangeLog(
    'content',
    123,  // content_id
    8,    // agent_id
    'WOLFIE',  // agent_name
    7,    // channel_id
    [
        'change_type' => 'update',
        'changed_fields' => ['title', 'status'],
        'old_values' => ['title' => 'Old Title', 'status' => 'draft'],
        'new_values' => ['title' => 'New Title', 'status' => 'published']
    ],
    [
        'change_reason' => 'User requested title update',
        'change_summary' => 'Updated title and published content'
    ]
);
```

### Migration History

- **Migration 1079** (2025-11-18): Created `content_logs` table
  - Added all columns including metadata JSON
  - Added indexes for performance (content_id, agent_id, channel_id, composite indexes)
  - Added channel_id range constraint (0-999)
  - Added JSON validation constraint for metadata

### Migration Files

- `database/migrations/1079_2025_11_18_create_content_logs_table.sql`

### Best Practices

1. **Row-Level Tracking**: Use `content_logs` for tracking changes to individual rows
2. **Directory-Level Tracking**: Use `content_log` (singular) for tracking content interactions by channel/agent
3. **Metadata Structure**: Follow standard metadata JSON structure for consistency
4. **Change Types**: Use standard change types (update, create, delete, restore)
5. **JSON Validation**: Ensure metadata is valid JSON before storing
6. **Channel Validation**: Validate channel_id range (0-999) before INSERT
7. **Soft Deletes**: Use `deleted_at` for data retention instead of hard deletes

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

## TABLE_COMPARISON

### Balanced Overview (MAAT's Perspective)

To ensure clarity and prevent confusion, here is a balanced comparison of all three database tables:

| Aspect | `content_headers` | `content_log` (singular) | `content_logs` (plural) |
|--------|-------------------|-------------------------|------------------------|
| **Purpose** | Store WOLFIE Headers metadata | Track content interactions | Track row-level changes |
| **Level** | File-level metadata | Directory-level interactions | Row-level changes |
| **Use Case** | Query headers by channel/agent | Agent discovery, channel discovery | Audit trail, change history |
| **Version** | v2.0.2 | v2.0.3 | v2.0.7 |
| **Migration** | 1072, 1073, 1074 | 1078 | 1079 |
| **Key Columns** | channel_id, agent_id, agent_name, value | content_id, channel_id, agent_id, agent_name | content_id, agent_id, agent_name, channel_id |
| **Metadata** | Header values, SOT types | Log entry counts, file paths | Change types, old/new values |
| **Relationship** | Links to content files | Links to log markdown files | Links to content table rows |
| **Query Pattern** | "What headers exist?" | "Which agents interact with content?" | "What changed in this row?" |

### When to Use Which Table

**Use `content_headers` when:**
- You need to query WOLFIE Headers metadata
- You need to find headers by channel or agent
- You need to construct agent file paths
- You need header values for content files

**Use `content_log` (singular) when:**
- You need to discover which agents interact with content
- You need to find content on specific channels
- You need directory-level log metadata
- You need to sync with markdown log files

**Use `content_logs` (plural) when:**
- You need to track changes to a specific content row
- You need an audit trail of what changed, when, why
- You need to understand evolution of database records
- You need row-level change history

### Balance and Harmony

**MAAT's Assessment:**
All three tables are in balance. Each serves a distinct purpose:
- `content_headers`: Metadata storage (foundation)
- `content_log`: Interaction tracking (directory-level)
- `content_logs`: Change tracking (row-level)

Together, they provide complete coverage: metadata, interactions, and changes. No table duplicates another's purpose. The system is harmonious and complete.

---

## RELATED_DOCUMENTATION

- `docs/AGENT_FILE_NAMING.md` - Agent file naming convention
- `docs/MIGRATION_2.0.1_TO_2.0.2.md` - Migration guide
- `TODO_2.0.2.md` - Complete TODO plan for v2.0.2
- `TODO_2.0.7.md` - Complete TODO plan for v2.0.7 (database `_logs` table support)
- `database/migrations/` - Migration scripts
  - `1072_2025_01_27_add_agent_name_to_content_headers.sql` - Added agent_name column
  - `1073_2025_01_27_populate_agent_name_in_content_headers.sql` - Populated agent_name
  - `1074_2025_01_27_validate_agent_name_migration.sql` - Validated migration
  - `1078_2025_11_18_create_content_log_table.sql` - Created content_log table
  - `1079_2025_11_18_create_content_logs_table.sql` - Created content_logs table
- `docs/WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md` - Log system architecture and implementation plan
- `docs/DATABASE_INTEGRATION.md` - This file (complete database integration guide)

---

**Last Updated**: 2025-11-18  
**Version**: 2.0.7  
**Status**: Current  
**Documentation Review**: MAAT (Agent 009) - Balance verified, completeness confirmed, truth maintained

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

