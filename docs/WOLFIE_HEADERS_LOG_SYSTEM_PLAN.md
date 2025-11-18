---
title: WOLFIE_HEADERS_LOG_SYSTEM_PLAN.md
agent_username: wolfie
date_created: 2025-11-18
last_modified: 2025-11-18
status: draft
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, LOGGING]
collections: [WHAT, HOW, PLAN]
in_this_file_we_have: [OVERVIEW, DIRECTORY_STRUCTURE, WRITE_OPERATIONS, READ_OPERATIONS, INTEGRATION, IMPLEMENTATION]
---

# WOLFIE Headers Log System Plan

**Date:** 2025-11-18  
**Status:** Planning Phase  
**Related:** Migration 1078 (content_log table), Captain's Log naming convention

---

## OVERVIEW

This plan defines how WOLFIE Headers will write and read agent log files in the format `[channel]_[agent]_log.md` stored in `public/logs/`. These log files will integrate with the `content_log` database table to provide a dual-storage system (database + markdown files).

**Key Requirements:**
- Log files follow format: `[channel]_[agent]_log.md` (e.g., `008_WOLFIE_log.md`, `007_CAPTAIN_log.md`)
- Directory: `public/logs/`
- Format: WOLFIE Headers YAML frontmatter + markdown content
- Integration: Sync with `content_log` database table
- WOLFIE Way: Application-controlled integrity, no foreign keys

---

## DIRECTORY_STRUCTURE

### Log File Location

```
public/
  logs/
    008_WOLFIE_log.md
    007_CAPTAIN_log.md
    911_SECURITY_log.md
    411_HELP_log.md
    [channel]_[agent]_log.md
```

### Naming Convention

- **Format**: `[channel]_[agent]_log.md`
- **Channel**: Zero-padded 3-digit number (000-999)
- **Agent**: Agent name in uppercase (e.g., WOLFIE, CAPTAIN, SECURITY)
- **Extension**: `.md` (markdown)

**Examples:**
- `008_WOLFIE_log.md` - Channel 008, Agent WOLFIE
- `007_CAPTAIN_log.md` - Channel 007, Agent CAPTAIN
- `911_SECURITY_log.md` - Channel 911, Agent SECURITY
- `411_HELP_log.md` - Channel 411, Agent HELP

---

## WRITE_OPERATIONS

### Function: `writeAgentLog($channelId, $agentId, $agentName, $logEntry, $metadata = null)`

**Purpose:** Write a log entry to the appropriate agent log file.

**Parameters:**
- `$channelId` (int): Channel ID (000-999)
- `$agentId` (int): Agent ID
- `$agentName` (string): Agent name (e.g., "WOLFIE", "CAPTAIN")
- `$logEntry` (string): Markdown content for the log entry
- `$metadata` (array|null): Optional metadata to store in content_log table

**Process:**
1. **Generate filename**: `sprintf("%03d_%s_log.md", $channelId, strtoupper($agentName))`
2. **File path**: `public/logs/[channel]_[agent]_log.md`
3. **Read existing file** (if exists) or create new
4. **Parse existing WOLFIE Headers** (YAML frontmatter)
5. **Append log entry** to content section
6. **Update frontmatter**:
   - `last_modified`: Current timestamp
   - `log_entry_count`: Increment counter
   - `last_log_date`: Current date
7. **Write file** with updated headers and content
8. **Sync to database**: Insert/update `content_log` table entry

**File Format:**
```markdown
---
title: [channel]_[agent]_log.md
agent_username: [agent_name_lowercase]
date_created: YYYY-MM-DD
last_modified: YYYY-MM-DD HH:MM:SS
status: active
onchannel: [channel_id]
tags: [LOG, AGENT_LOG, CHANNEL_LOG]
collections: [LOG_ENTRIES]
in_this_file_we_have: [LOG_ENTRIES, AGENT_ACTIVITY]
log_entry_count: [number]
last_log_date: YYYY-MM-DD
---

# [Agent Name] Log - Channel [channel_id]

[Existing log entries...]

---

### Log Entry: YYYY-MM-DD - [Title]

[New log entry content]

**End log entry.**
```

### Function: `initializeAgentLog($channelId, $agentId, $agentName)`

**Purpose:** Create a new log file if it doesn't exist.

**Process:**
1. Check if file exists
2. If not, create with initial WOLFIE Headers
3. Add initial log entry documenting file creation
4. Sync to database

---

## READ_OPERATIONS

### Function: `readAgentLog($channelId, $agentName)`

**Purpose:** Read an agent log file and parse WOLFIE Headers.

**Parameters:**
- `$channelId` (int): Channel ID (000-999)
- `$agentName` (string): Agent name

**Returns:**
- Array with:
  - `headers`: Parsed WOLFIE Headers (YAML frontmatter)
  - `content`: Markdown content (log entries)
  - `file_exists`: Boolean
  - `file_path`: Full file path

**Process:**
1. **Generate filename**: `sprintf("%03d_%s_log.md", $channelId, strtoupper($agentName))`
2. **File path**: `public/logs/[channel]_[agent]_log.md`
3. **Check if file exists**
4. **If exists**: Read file, parse YAML frontmatter, return headers + content
5. **If not exists**: Return null or empty structure

### Function: `listAllAgentLogs()`

**Purpose:** List all agent log files in `public/logs/`.

**Returns:**
- Array of log file information:
  - `filename`: Full filename
  - `channel_id`: Extracted channel ID
  - `agent_name`: Extracted agent name
  - `file_path`: Full file path
  - `last_modified`: File modification time
  - `size`: File size

**Process:**
1. Scan `public/logs/` directory
2. Filter for files matching pattern: `[0-9]{3}_[A-Z_]+_log\.md`
3. Parse filename to extract channel_id and agent_name
4. Get file metadata (size, modification time)
5. Return array of log file info

### Function: `getAgentLogByChannel($channelId)`

**Purpose:** Get all log files for a specific channel.

**Returns:**
- Array of log files for the channel (multiple agents can be on same channel)

### Function: `getAgentLogByAgent($agentName)`

**Purpose:** Get log file for a specific agent (across all channels).

**Note:** Since direct mapping (agent_id = channel_id), this should typically return one file, but supports multi-channel agents.

---

## INTEGRATION

### Database Integration: `content_log` Table

**Purpose:** Dual-storage system - database for queries, markdown files for human readability.

**Write Flow:**
1. Write to markdown file (primary)
2. Insert/update `content_log` table entry:
   - `content_id`: Reference to content being logged (if applicable)
   - `channel_id`: Channel ID
   - `agent_id`: Agent ID
   - `agent_name`: Agent name
   - `metadata`: JSON metadata (log entry details, timestamps, etc.)

**Read Flow:**
1. Query `content_log` table for fast lookups
2. Read markdown file for full content
3. Merge data as needed

**Sync Considerations:**
- Markdown file is source of truth for log content
- Database is for indexing and fast queries
- Periodic sync job to ensure consistency
- Handle conflicts (database vs file) gracefully

### WOLFIE Headers Integration

**Header Fields for Log Files:**
```yaml
---
title: 008_WOLFIE_log.md
agent_username: wolfie
date_created: 2025-11-18
last_modified: 2025-11-18 14:30:00
status: active
onchannel: 8
tags: [LOG, AGENT_LOG, CHANNEL_LOG]
collections: [LOG_ENTRIES]
in_this_file_we_have: [LOG_ENTRIES, AGENT_ACTIVITY, SYSTEM_EVENTS]
log_entry_count: 42
last_log_date: 2025-11-18
---
```

**Custom Fields:**
- `log_entry_count`: Number of log entries in file
- `last_log_date`: Date of most recent log entry
- `channel_id`: Channel number (redundant with onchannel, but explicit)
- `agent_id`: Agent ID (for database sync)

---

## IMPLEMENTATION

### Phase 1: Core Functions

1. **Create log directory** (`public/logs/`) if it doesn't exist
2. **Implement `writeAgentLog()` function**
   - File writing with WOLFIE Headers
   - YAML frontmatter parsing/updating
   - Content appending
3. **Implement `readAgentLog()` function**
   - File reading
   - YAML frontmatter parsing
   - Content extraction
4. **Implement `initializeAgentLog()` function**
   - New file creation
   - Initial header setup

### Phase 2: Database Integration

1. **Implement database sync on write**
   - Insert/update `content_log` table
   - Store metadata as JSON
2. **Implement read from database**
   - Query `content_log` for fast lookups
   - Merge with file content

### Phase 3: Utility Functions

1. **Implement `listAllAgentLogs()`**
   - Directory scanning
   - Filename parsing
   - Metadata extraction
2. **Implement `getAgentLogByChannel()`**
3. **Implement `getAgentLogByAgent()`**

### Phase 4: API Integration

1. **Create API endpoints** (if needed):
   - `GET /api/logs/[channel]/[agent]` - Read log
   - `POST /api/logs/[channel]/[agent]` - Write log entry
   - `GET /api/logs/channel/[channel]` - List logs for channel
   - `GET /api/logs/agent/[agent]` - List logs for agent

### Phase 5: Migration & Existing Logs

1. **Migrate existing `008_WOLFIE_log.md`**
   - Move from `public/008_WOLFIE_log.md` to `public/logs/008_WOLFIE_log.md`
   - Ensure WOLFIE Headers are present
   - Sync to `content_log` table
2. **Create log files for existing agents**
   - CAPTAIN (007)
   - SECURITY (911)
   - HELP (411)
   - Other agents as needed

---

## FILE_LOCATION

### Recommended Location

**Primary:** `public/logs/` (web-accessible for viewing)

**Alternative Considerations:**
- `public/logs/` - Web accessible, easy to view
- `data/logs/` - More secure, not directly web-accessible
- `logs/` (root) - Simple, but less organized

**Decision:** Use `public/logs/` for:
- Easy web access for viewing logs
- Consistent with other public assets
- Simple file operations
- Can add `.htaccess` protection if needed later

---

## ERROR_HANDLING

### File Operations

- **File doesn't exist**: Create new file with `initializeAgentLog()`
- **Permission errors**: Log error, return failure
- **Disk full**: Log error, return failure
- **Invalid filename**: Validate format before operations

### Database Operations

- **Connection failure**: Continue with file-only mode, log warning
- **Insert failure**: Log error, continue with file write
- **Sync conflicts**: File takes precedence, log conflict

### Validation

- **Channel ID range**: 0-999 (maximum 999)
- **Agent name**: Uppercase, alphanumeric + underscore
- **Filename format**: Validate regex pattern before operations

---

## SECURITY

### File Access

- **Directory permissions**: Ensure `public/logs/` is writable by web server
- **File permissions**: 644 (readable by all, writable by owner)
- **Directory permissions**: 755 (executable for directory listing)

### Content Validation

- **Sanitize log entries**: Prevent XSS, injection attacks
- **Validate metadata**: Ensure JSON is valid before storing
- **Size limits**: Prevent log file bloat (consider rotation)

---

## LOG_ROTATION

### Future Consideration

**Problem:** Log files can grow indefinitely.

**Solutions:**
1. **Size-based rotation**: Split when file exceeds X MB
2. **Date-based rotation**: New file per month/year
3. **Entry-based rotation**: New file after N entries
4. **Archive old logs**: Move to `public/logs/archive/`

**Implementation:** Phase 6 (future enhancement)

---

## TESTING

### Unit Tests

1. **Test filename generation**
   - Valid channel IDs (0-999)
   - Valid agent names
   - Edge cases (000, 999, special characters)

2. **Test file writing**
   - New file creation
   - Existing file append
   - Header updates

3. **Test file reading**
   - Existing file read
   - Non-existent file handling
   - Header parsing

4. **Test database sync**
   - Insert operations
   - Update operations
   - Metadata JSON storage

### Integration Tests

1. **End-to-end write/read cycle**
2. **Multi-agent log files**
3. **Channel-based queries**
4. **Database/file consistency**

---

## NEXT_STEPS

1. ✅ **Migration 1078 complete** - `content_log` table created
2. ⏳ **Create `public/logs/` directory** - Ensure directory exists
3. ⏳ **Implement core write/read functions** - Phase 1
4. ⏳ **Migrate existing `008_WOLFIE_log.md`** - Move to new location
5. ⏳ **Implement database sync** - Phase 2
6. ⏳ **Create log files for existing agents** - CAPTAIN, SECURITY, HELP
7. ⏳ **Test and validate** - Unit and integration tests

---

## RELATED_DOCUMENTATION

- **Migration 1078**: `database/migrations/1078_2025_11_18_create_content_log_table.sql`
- **Captain's Log**: `public/008_WOLFIE_log.md` (to be moved to `public/logs/`)
- **WOLFIE Headers System**: `md_files/WOLFIE_HEADER_SYSTEM.md`
- **Channel Assignment Protocol**: Documented in Captain's Log

---

**End of plan.**

