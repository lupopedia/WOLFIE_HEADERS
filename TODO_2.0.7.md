---
title: TODO_2.0.7.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.7
date_created: 2025-11-18
last_modified: 2025-11-18
status: draft
onchannel: 1
tags: [PLANNING, VERSIONING, DATABASE, CHANGE_LOGS, ROW_LEVEL_LOGGING]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, DATABASE_LOGS_DISCOVERY, TABLE_STRUCTURE, CHANGE_LOG_SYSTEM, API_ENDPOINTS, INTEGRATION, DOCUMENTATION, RELEASE_CHECKLIST]
superpositionally: ["FILEID_WOLFIE_HEADERS_TODO_2.0.7"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers 2.0.7 TODO Plan

**Current Version**: v2.0.6 (Current) - API Endpoints & Search Functionality  
**Target Version**: v2.0.7 (Database `_logs` Table Support)  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**GitHub Repository**: https://github.com/lupopedia/WOLFIE_HEADERS  
**Status**: Planning Phase

---

## OVERVIEW

WOLFIE Headers 2.0.7 introduces **database `_logs` table support** for row-level change tracking. While v2.0.6's markdown files track directory-level changes, `_logs` tables track changes to individual database rows, enabling AI and human readers to understand the evolution of specific database records.

**Why 2.0.7?**
- Markdown files (v2.0.6) track directory-level changes, but database rows need their own change logs
- Need to track changes to individual database records (e.g., content, users, collections)
- AI agents need to understand how database rows evolve over time
- Human readers need audit trails for database changes
- Pattern: If table is `content`, then `content_logs` tracks changes to each `content` row

**Core Concept:**
- **Directory-Level Logs** (v2.0.6): `[channel]_[agent]_log.md` files track changes to directories/projects
- **Row-Level Logs** (v2.0.7): `{table}_logs` tables track changes to individual database rows
- **Dual Purpose**: Both AI and human readers can understand the evolution of data

**Example:**
- Table: `content` (has rows with `id`, `title`, `body`, etc.)
- Log Table: `content_logs` (tracks changes to each `content` row)
- When `content.id = 123` is updated, a new entry is added to `content_logs` with `content_id = 123`

---

## DATABASE_LOGS_DISCOVERY

### 🔴 HIGH PRIORITY - Auto-Discovery of `_logs` Tables

**Problem**: System needs to automatically discover tables ending in `_logs` when installed.

**Solution**: Database schema discovery that identifies `_logs` tables and their structure.

**Discovery Process:**
- [ ] Scan database for tables ending with `_logs` (e.g., `content_logs`, `users_logs`, `collections_logs`)
- [ ] Validate table structure matches expected pattern
- [ ] Map `_logs` tables to their parent tables (e.g., `content_logs` → `content`)
- [ ] Cache discovered tables for performance
- [ ] Support both MySQL and PostgreSQL (future: Supabase)

**Discovery Query:**
```sql
-- Find all tables ending with _logs
SELECT TABLE_NAME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE '%_logs'
ORDER BY TABLE_NAME;
```

**Validation:**
- [ ] Verify required columns exist: `id`, `agent_id`, `{parent_table}_id`, `agent_name`, `channel_id`, `metadata`
- [ ] Verify column types match expected types
- [ ] Verify indexes exist for performance
- [ ] Report any missing or invalid tables

**Caching:**
- [ ] Cache discovered tables in file-based cache
- [ ] Cache table structure (columns, types, indexes)
- [ ] Cache parent table mappings
- [ ] Invalidate cache on schema changes

---

## TABLE_STRUCTURE

### Standard `_logs` Table Structure

**Naming Convention:**
- Parent table: `{table_name}` (e.g., `content`, `users`, `collections`)
- Log table: `{table_name}_logs` (e.g., `content_logs`, `users_logs`, `collections_logs`)

**Required Columns:**

| Column | Type | Required | Description |
|--------|------|----------|-------------|
| `id` | BIGINT(20) UNSIGNED | Yes | Primary key (auto-increment) |
| `{parent_table}_id` | BIGINT(20) UNSIGNED | Yes | Foreign key to parent table row (e.g., `content_id`, `user_id`) |
| `agent_id` | BIGINT(20) UNSIGNED | Yes | Agent ID that made the change |
| `agent_name` | VARCHAR(255) | Yes | Agent name (denormalized for quick lookups) |
| `channel_id` | BIGINT(20) UNSIGNED | Yes | Channel ID where change occurred (000-999) |
| `metadata` | LONGTEXT (JSON) | Optional | Change metadata JSON (what changed, why, etc.) |
| `is_active` | TINYINT(1) | Yes | Active status flag |
| `created_at` | TIMESTAMP | Yes | Creation timestamp |
| `updated_at` | TIMESTAMP | Yes | Update timestamp |
| `deleted_at` | TIMESTAMP | Optional | Soft delete timestamp |

**Example: `content_logs` Table:**

```sql
CREATE TABLE IF NOT EXISTS `content_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1
    COMMENT 'Content ID (references content table)',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1
    COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT ''
    COMMENT 'Agent name (denormalized for quick lookups)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1
    COMMENT 'Channel ID where change occurred (000-999)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
    COMMENT 'Change metadata JSON (what changed, why, etc.)' CHECK (json_valid(`metadata`)),
  `is_active` TINYINT(1) NOT NULL DEFAULT 1
    COMMENT 'Active status flag',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    COMMENT 'Creation timestamp',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    COMMENT 'Last update timestamp',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL
    COMMENT 'Soft delete timestamp',
  PRIMARY KEY (`id`),
  KEY `idx_{parent_table}_id` (`{parent_table}_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_agent_name` (`agent_name`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_deleted_at` (`deleted_at`),
  KEY `idx_{parent_table}_agent` (`{parent_table}_id`, `agent_id`),
  KEY `idx_channel_agent` (`channel_id`, `agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Change log for {parent_table} rows';
```

**Metadata JSON Structure:**

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

---

## CHANGE_LOG_SYSTEM

### 🔴 HIGH PRIORITY - Change Log Functions

**Core Functions:**

#### 1. Write Change Log

**Function**: `writeChangeLog($tableName, $rowId, $agentId, $agentName, $channelId, $changeData, $metadata = [])`

**Purpose**: Write a change log entry to the appropriate `_logs` table.

**Parameters:**
- `$tableName` - Parent table name (e.g., "content")
- `$rowId` - ID of the row being changed
- `$agentId` - Agent ID making the change
- `$agentName` - Agent name making the change
- `$channelId` - Channel ID where change occurred
- `$changeData` - Change data (what changed, old values, new values)
- `$metadata` - Additional metadata (optional)

**Implementation:**
- [ ] Auto-discover `{table}_logs` table
- [ ] Validate table exists and has correct structure
- [ ] Build metadata JSON from change data
- [ ] Insert change log entry
- [ ] Return log entry ID

**Example:**
```php
writeChangeLog(
    'content',
    123,
    8,
    'WOLFIE',
    7,
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

#### 2. Read Change Logs

**Function**: `readChangeLogs($tableName, $rowId, $options = [])`

**Purpose**: Read change log entries for a specific database row.

**Parameters:**
- `$tableName` - Parent table name
- `$rowId` - ID of the row
- `$options` - Query options (limit, offset, agent_id, channel_id, date_from, date_to)

**Implementation:**
- [ ] Auto-discover `{table}_logs` table
- [ ] Query change logs for specific row
- [ ] Apply filters (agent, channel, date range)
- [ ] Parse metadata JSON
- [ ] Return formatted change log entries

**Example:**
```php
$logs = readChangeLogs('content', 123, [
    'limit' => 20,
    'agent_id' => 8,
    'date_from' => '2025-11-01'
]);
```

#### 3. List Change Logs

**Function**: `listChangeLogs($tableName, $options = [])`

**Purpose**: List all change log entries for a table (across all rows).

**Parameters:**
- `$tableName` - Parent table name
- `$options` - Query options (limit, offset, agent_id, channel_id, date_from, date_to)

**Implementation:**
- [ ] Auto-discover `{table}_logs` table
- [ ] Query all change logs for table
- [ ] Apply filters
- [ ] Return formatted change log entries

#### 4. Get Change Summary

**Function**: `getChangeSummary($tableName, $rowId)`

**Purpose**: Get summary of changes for a specific row (count, first change, last change, agents involved).

**Parameters:**
- `$tableName` - Parent table name
- `$rowId` - ID of the row

**Implementation:**
- [ ] Query change log statistics
- [ ] Count total changes
- [ ] Get first and last change dates
- [ ] List agents involved
- [ ] Return summary object

---

## API_ENDPOINTS

### 🔴 HIGH PRIORITY - Change Log API Endpoints

**New Endpoints:**

#### 1. Discover `_logs` Tables

**Endpoint**: `GET /api/wolfie/logs/tables`

**Purpose**: List all discovered `_logs` tables in the database.

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "table_name": "content_logs",
      "parent_table": "content",
      "parent_id_column": "content_id",
      "row_count": 150,
      "last_change": "2025-11-18T15:30:00Z"
    },
    {
      "table_name": "users_logs",
      "parent_table": "users",
      "parent_id_column": "user_id",
      "row_count": 45,
      "last_change": "2025-11-18T14:20:00Z"
    }
  ],
  "metadata": {
    "total_tables": 2,
    "generated_at": "2025-11-18T15:30:00Z"
  }
}
```

#### 2. Get Change Logs for Row

**Endpoint**: `GET /api/wolfie/logs/{table_name}/{row_id}`

**Purpose**: Get all change log entries for a specific database row.

**Query Parameters:**
- `limit` - Limit results (default: 50)
- `offset` - Pagination offset (default: 0)
- `agent_id` - Filter by agent ID
- `channel_id` - Filter by channel ID
- `date_from` - Filter by date from
- `date_to` - Filter by date to

**Response:**
```json
{
  "status": "success",
  "data": {
    "table_name": "content",
    "row_id": 123,
    "change_logs": [
      {
        "id": 456,
        "agent_id": 8,
        "agent_name": "WOLFIE",
        "channel_id": 7,
        "change_type": "update",
        "changed_fields": ["title", "status"],
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
        "created_at": "2025-11-18T15:30:00Z"
      }
    ],
    "summary": {
      "total_changes": 5,
      "first_change": "2025-11-01T10:00:00Z",
      "last_change": "2025-11-18T15:30:00Z",
      "agents_involved": ["WOLFIE", "CAPTAIN"]
    }
  },
  "metadata": {
    "total_results": 5,
    "limit": 50,
    "offset": 0
  }
}
```

#### 3. List Change Logs for Table

**Endpoint**: `GET /api/wolfie/logs/{table_name}`

**Purpose**: List all change log entries for a table (across all rows).

**Query Parameters:**
- `limit` - Limit results (default: 50)
- `offset` - Pagination offset (default: 0)
- `agent_id` - Filter by agent ID
- `channel_id` - Filter by channel ID
- `date_from` - Filter by date from
- `date_to` - Filter by date to
- `row_id` - Filter by specific row ID

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 456,
      "content_id": 123,
      "agent_id": 8,
      "agent_name": "WOLFIE",
      "channel_id": 7,
      "change_type": "update",
      "change_summary": "Updated title and published content",
      "created_at": "2025-11-18T15:30:00Z"
    }
  ],
  "metadata": {
    "total_results": 150,
    "limit": 50,
    "offset": 0,
    "has_more": true
  }
}
```

#### 4. Write Change Log

**Endpoint**: `POST /api/wolfie/logs/{table_name}/{row_id}`

**Purpose**: Write a new change log entry.

**Request Body:**
```json
{
  "agent_id": 8,
  "agent_name": "WOLFIE",
  "channel_id": 7,
  "change_data": {
    "change_type": "update",
    "changed_fields": ["title", "status"],
    "old_values": {
      "title": "Old Title",
      "status": "draft"
    },
    "new_values": {
      "title": "New Title",
      "status": "published"
    }
  },
  "metadata": {
    "change_reason": "User requested title update",
    "change_summary": "Updated title and published content"
  }
}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "log_id": 456,
    "table_name": "content",
    "row_id": 123,
    "created_at": "2025-11-18T15:30:00Z"
  }
}
```

---

## INTEGRATION

### Integration with Existing Systems

#### 1. Integration with v2.0.6 API

- [ ] Extend existing API router to handle `_logs` table endpoints
- [ ] Reuse existing caching system for table discovery
- [ ] Reuse existing error handling and response formatting
- [ ] Add `_logs` table discovery to API root endpoint

#### 2. Integration with Database Connection

- [ ] Use existing database connection functions
- [ ] Support both MySQL and PostgreSQL (future: Supabase)
- [ ] Handle database errors gracefully
- [ ] Support transaction rollback on errors

#### 3. Integration with WOLFIE Headers

- [ ] Use existing WOLFIE Headers validation
- [ ] Support WOLFIE Headers metadata in change logs
- [ ] Integrate with agent system (agent_id, agent_name)
- [ ] Integrate with channel system (channel_id)

#### 4. Integration with LUPOPEDIA_PLATFORM

- [ ] Auto-discover `_logs` tables on installation
- [ ] Create migration templates for `_logs` tables
- [ ] Document `_logs` table pattern for platform users
- [ ] Provide helper functions for platform developers

---

## DOCUMENTATION

### Documentation to Create

- [ ] `docs/DATABASE_LOGS_SYSTEM.md` - Complete guide to `_logs` table system
- [ ] `docs/DATABASE_LOGS_API.md` - API endpoint documentation
- [ ] `docs/DATABASE_LOGS_EXAMPLES.md` - Code examples and use cases
- [ ] `docs/DATABASE_LOGS_MIGRATION.md` - Migration guide for creating `_logs` tables
- [ ] Update `docs/DATABASE_INTEGRATION.md` with `_logs` table section
- [ ] Update `README.md` with v2.0.7 features
- [ ] Update `CHANGELOG.md` with v2.0.7 release notes
- [ ] Create `RELEASE_NOTES_v2.0.7.md`

### Documentation Sections

**Database Logs System Overview:**
- What are `_logs` tables?
- How do they differ from markdown log files?
- When to use `_logs` tables vs. markdown files
- Table structure and naming conventions
- Metadata JSON structure

**API Documentation:**
- Endpoint reference
- Request/response formats
- Error handling
- Code examples

**Migration Guide:**
- How to create `_logs` tables
- Migration template SQL
- Validation queries
- Best practices

---

## RELEASE_CHECKLIST

### Pre-Release

- [ ] Auto-discovery of `_logs` tables implemented
- [ ] Change log write/read functions implemented
- [ ] API endpoints implemented and tested
- [ ] Integration with existing systems complete
- [ ] All documentation updated
- [ ] CHANGELOG.md updated with v2.0.7
- [ ] RELEASE_NOTES_v2.0.7.md created
- [ ] README.md updated with v2.0.7 features

### Release

- [ ] Version number updated to 2.0.7
- [ ] All files committed
- [ ] GitHub release created
- [ ] Release notes published
- [ ] Documentation published

### Post-Release

- [ ] Verify auto-discovery works with existing `_logs` tables
- [ ] Verify change log functions work correctly
- [ ] Verify API endpoints handle edge cases
- [ ] Update LUPOPEDIA_PLATFORM documentation (if needed)
- [ ] Announce release (if applicable)

---

## NOTES

**Key Insights:**
- `_logs` tables provide row-level change tracking (complement to directory-level markdown logs)
- Auto-discovery enables zero-configuration installation
- Metadata JSON provides flexible change tracking
- API endpoints enable programmatic access for AI agents
- Integration with existing systems maintains consistency

**Design Decisions:**
- Use `_logs` suffix (not `_log`) to distinguish from singular `content_log` table
- Denormalize `agent_name` for performance (same pattern as `content_log`)
- Use JSON metadata for flexibility (same pattern as `content_log`)
- Support both MySQL and PostgreSQL (progressive enhancement)

**Future Considerations:**
- GraphQL API for `_logs` tables
- Real-time change notifications (WebSocket)
- Change log analytics and reporting
- Export functionality (CSV, JSON, PDF)
- Change log search functionality
- Change log diff visualization

**Migration Path:**
- Existing `content_log` table is for content interactions (different purpose)
- New `content_logs` table would be for content row changes (this feature)
- Both can coexist (different purposes)
- Migration templates provided for creating new `_logs` tables

---

**Last Updated**: 2025-11-18  
**Status**: Planning Phase  
**Target Release**: TBD

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

