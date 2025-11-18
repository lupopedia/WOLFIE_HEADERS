---
title: EXAMPLES_AGENT_LOGS_AND_DATABASE_LOGS.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.1.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, EXAMPLES, TUTORIAL]
collections: [WHAT, HOW, DO]
in_this_file_we_have: [OVERVIEW, AGENT_LOG_EXAMPLES, DATABASE_LOGS_EXAMPLES, COMPLETE_WORKFLOW, API_EXAMPLES]
superpositionally: ["FILEID_EXAMPLES_LOGS"]
shadow_aliases: []
parallel_paths: []
---

# Examples: Agent Logs & Database Logs

**Version**: v2.1.0  
**Last Updated**: 2025-11-18

## OVERVIEW

This document provides **complete, working examples** for both:
1. **Agent Log Files** (`public/logs/[channel]_[agent]_log.md`)
2. **Database `_logs` Tables** (e.g., `content_logs` table)

Each example includes:
- **What it does**: Clear explanation
- **Code**: Complete, working PHP code
- **Result**: Expected output
- **Related**: Links to relevant documentation

---

## AGENT_LOG_EXAMPLES

### Example 1: Initialize Agent Log File

**What it does**: Creates a new agent log file with proper WOLFIE Headers format.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_log_system.php';

// Initialize log for CAPTAIN (Agent 007) on Channel 007
$result = initializeAgentLog(7, 7, 'CAPTAIN');

if ($result['success']) {
    echo "Log file created: {$result['file_path']}\n";
} else {
    echo "Error: {$result['error']}\n";
}
```

**Result**: Creates `public/logs/007_CAPTAIN_log.md` with WOLFIE Headers:
```yaml
---
title: 007_CAPTAIN_log.md
agent_username: captain
agent_id: 7
channel_id: 7
version: 2.1.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: active
onchannel: 7
tags: [LOG, AGENT_LOG, CHANNEL_LOG]
collections: [LOG_ENTRIES]
log_entry_count: 0
last_log_date: 2025-11-18
---

# CAPTAIN Log - Channel 007

**Log initialized:** 2025-11-18 10:00:00
**Agent ID:** 7
**Channel ID:** 7
```

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

### Example 2: Write to Agent Log File

**What it does**: Appends a log entry to an agent's log file and syncs to database.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_log_system.php';

// Write log entry for CAPTAIN
$logEntry = "### Log Entry: 2025-11-18 - New Agent Created

**Date:** 2025-11-18 10:30:00  
**Agent:** CAPTAIN (007)  
**Channel:** 007  
**Action:** Created new agent SECURITY (911)

**Reasoning:**
We need a dedicated security agent to monitor threats and respond to incidents. 
Channel 911 is perfect for emergency/security operations.

**Action Taken:**
- Created agent SECURITY (ID: 911)
- Assigned to Channel 911
- Configured security monitoring capabilities

**End log entry.**";

$metadata = [
    'action_type' => 'agent_creation',
    'target_agent_id' => 911,
    'target_agent_name' => 'SECURITY',
    'target_channel' => 911
];

$result = writeAgentLog(7, 7, 'CAPTAIN', $logEntry, $metadata);

if ($result['success']) {
    echo "Log entry written successfully\n";
    echo "File: {$result['file_path']}\n";
    echo "Entry count: {$result['log_entry_count']}\n";
} else {
    echo "Error: {$result['error']}\n";
}
```

**Result**: 
- Appends log entry to `007_CAPTAIN_log.md`
- Updates YAML frontmatter (`log_entry_count`, `last_log_date`, `last_modified`)
- Syncs metadata to `content_log` database table

**Database Sync**: Creates/updates row in `content_log`:
```sql
INSERT INTO content_log (content_id, channel_id, agent_id, agent_name, metadata, is_active, created_at, updated_at)
VALUES (NULL, 7, 7, 'CAPTAIN', '{"log_entry_count": 1, "last_log_date": "2025-11-18", ...}', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE metadata = VALUES(metadata), updated_at = NOW();
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOG_TABLE`

---

### Example 3: Read Agent Log File

**What it does**: Reads and parses an agent log file.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_log_system.php';

// Read CAPTAIN's log
$log = readAgentLog(7, 'CAPTAIN');

if ($log && isset($log['content'])) {
    echo "Log File: {$log['filename']}\n";
    echo "Channel: {$log['channel_id']}\n";
    echo "Agent: {$log['agent_id']} ({$log['agent_name']})\n";
    echo "Entry Count: {$log['log_entry_count']}\n";
    echo "Last Log Date: {$log['last_log_date']}\n";
    echo "\n--- Log Content ---\n";
    echo $log['content'];
} else {
    echo "Log file not found or error reading file\n";
}
```

**Result**: Returns parsed log with headers and content:
```php
Array (
    [filename] => 007_CAPTAIN_log.md
    [channel_id] => 7
    [agent_id] => 7
    [agent_name] => CAPTAIN
    [log_entry_count] => 1
    [last_log_date] => 2025-11-18
    [content] => # CAPTAIN Log - Channel 007
                 ...
                 ### Log Entry: 2025-11-18 - New Agent Created
                 ...
)
```

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

### Example 4: Read from Database (content_log table)

**What it does**: Reads log metadata from `content_log` database table for fast queries.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_log_system.php';
require_once __DIR__ . '/config/database.php';

// Read metadata from database
$metadata = readContentLogFromDatabase(7, 7, 'CAPTAIN');

if ($metadata) {
    echo "Channel: {$metadata['channel_id']}\n";
    echo "Agent: {$metadata['agent_id']} ({$metadata['agent_name']})\n";
    echo "Entry Count: {$metadata['log_entry_count']}\n";
    echo "Last Log Date: {$metadata['last_log_date']}\n";
    echo "File Path: {$metadata['file_path']}\n";
    
    // Access full metadata JSON
    $fullMetadata = json_decode($metadata['metadata'], true);
    print_r($fullMetadata);
} else {
    echo "No database entry found\n";
}
```

**Result**: Returns metadata from database:
```php
Array (
    [channel_id] => 7
    [agent_id] => 7
    [agent_name] => CAPTAIN
    [log_entry_count] => 1
    [last_log_date] => 2025-11-18
    [file_path] => public/logs/007_CAPTAIN_log.md
    [metadata] => {"log_entry_count": 1, "last_log_date": "2025-11-18", ...}
)
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOG_TABLE`

---

### Example 5: List All Agent Logs

**What it does**: Lists all agent log files in `public/logs/` directory.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_log_system.php';

// List all log files
$logs = listAllAgentLogs();

echo "Total Log Files: " . count($logs) . "\n\n";

foreach ($logs as $log) {
    echo "File: {$log['filename']}\n";
    echo "  Channel: {$log['channel']}\n";
    echo "  Agent: {$log['agent']}\n";
    echo "  Path: {$log['path']}\n";
    echo "  Size: {$log['size']} bytes\n";
    echo "  Modified: {$log['last_modified']}\n";
    echo "\n";
}
```

**Result**: Returns array of all log files:
```php
Array (
    [0] => Array (
        [filename] => 007_CAPTAIN_log.md
        [channel] => 007
        [agent] => CAPTAIN
        [path] => /path/to/public/logs/007_CAPTAIN_log.md
        [size] => 1024
        [last_modified] => 2025-11-18 10:30:00
    )
    [1] => Array (
        [filename] => 008_WOLFIE_log.md
        ...
    )
)
```

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

## DATABASE_LOGS_EXAMPLES

### Example 1: Discover `_logs` Tables

**What it does**: Discovers all tables ending with `_logs` in the database.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';
require_once __DIR__ . '/config/database.php';

// Discover all _logs tables
$tables = discoverLogsTables();

echo "Found " . count($tables) . " _logs tables:\n\n";

foreach ($tables as $table) {
    echo "Table: {$table['table_name']}\n";
    echo "  Parent Table: {$table['parent_table']}\n";
    echo "  Valid: " . ($table['valid'] ? 'Yes' : 'No') . "\n";
    echo "  Row Count: {$table['row_count']}\n";
    echo "\n";
}
```

**Result**: Returns array of discovered tables:
```php
Array (
    [0] => Array (
        [table_name] => content_logs
        [parent_table] => content
        [valid] => true
        [row_count] => 150
    )
    [1] => Array (
        [table_name] => user_logs
        [parent_table] => user
        [valid] => true
        [row_count] => 75
    )
)
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`

---

### Example 2: Write Change Log Entry

**What it does**: Writes a change log entry to `content_logs` table when a `content` row is updated.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';
require_once __DIR__ . '/config/database.php';

// Simulate updating content row 123
$contentId = 123;
$oldValues = ['title' => 'Old Title', 'status' => 'draft'];
$newValues = ['title' => 'New Title', 'status' => 'published'];

// Write change log
$result = writeChangeLog(
    'content',           // Parent table
    $contentId,          // Row ID
    8,                   // Agent ID (WOLFIE)
    'WOLFIE',            // Agent name
    8,                   // Channel ID
    [                    // Change data
        'change_type' => 'update',
        'changed_fields' => ['title', 'status'],
        'old_values' => $oldValues,
        'new_values' => $newValues,
        'change_reason' => 'Updated title and published content',
        'change_summary' => 'Title changed from "Old Title" to "New Title", status changed from "draft" to "published"'
    ],
    [                    // Additional metadata
        'ip_address' => '192.168.1.1',
        'user_agent' => 'Mozilla/5.0...'
    ]
);

if ($result['success']) {
    echo "Change log written successfully\n";
    echo "Log ID: {$result['log_id']}\n";
    echo "Table: {$result['table_name']}\n";
} else {
    echo "Error: {$result['error']}\n";
}
```

**Result**: Creates row in `content_logs` table:
```sql
INSERT INTO content_logs (
    content_id, agent_id, agent_name, channel_id, metadata, is_active, created_at, updated_at
) VALUES (
    123, 8, 'WOLFIE', 8,
    '{"change_type": "update", "changed_fields": ["title", "status"], "old_values": {"title": "Old Title", "status": "draft"}, "new_values": {"title": "New Title", "status": "published"}, "change_reason": "Updated title and published content", "change_summary": "Title changed from \"Old Title\" to \"New Title\", status changed from \"draft\" to \"published\"", "ip_address": "192.168.1.1", "user_agent": "Mozilla/5.0..."}',
    1, NOW(), NOW()
);
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`, `public/examples/example_write_change_log.php`

---

### Example 3: Read Change Logs for Specific Row

**What it does**: Reads all change log entries for a specific database row.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';
require_once __DIR__ . '/config/database.php';

// Read change logs for content row 123
$options = [
    'limit' => 50,
    'offset' => 0,
    'order_by' => 'created_at',
    'order_dir' => 'DESC'
];

$changes = readChangeLogs('content', 123, $options);

echo "Found " . count($changes) . " change log entries for content row 123:\n\n";

foreach ($changes as $change) {
    echo "Change ID: {$change['id']}\n";
    echo "  Agent: {$change['agent_name']} (ID: {$change['agent_id']})\n";
    echo "  Channel: {$change['channel_id']}\n";
    echo "  Date: {$change['created_at']}\n";
    
    $metadata = json_decode($change['metadata'], true);
    echo "  Change Type: {$metadata['change_type']}\n";
    echo "  Summary: {$metadata['change_summary']}\n";
    echo "\n";
}
```

**Result**: Returns array of change log entries:
```php
Array (
    [0] => Array (
        [id] => 1
        [content_id] => 123
        [agent_id] => 8
        [agent_name] => WOLFIE
        [channel_id] => 8
        [metadata] => {"change_type": "update", ...}
        [created_at] => 2025-11-18 10:30:00
    )
)
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`, `public/examples/example_read_change_logs.php`

---

### Example 4: Get Change Summary

**What it does**: Gets a summary of all changes for a specific row.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';
require_once __DIR__ . '/config/database.php';

// Get change summary for content row 123
$summary = getChangeSummary('content', 123);

echo "Change Summary for Content Row 123:\n";
echo "  Total Changes: {$summary['total_changes']}\n";
echo "  First Change: {$summary['first_change']}\n";
echo "  Last Change: {$summary['last_change']}\n";
echo "  Changed By: " . implode(', ', $summary['changed_by_agents']) . "\n";
echo "  Change Types: " . implode(', ', $summary['change_types']) . "\n";
```

**Result**: Returns summary:
```php
Array (
    [total_changes] => 3
    [first_change] => 2025-11-18 09:00:00
    [last_change] => 2025-11-18 10:30:00
    [changed_by_agents] => Array (
        [0] => WOLFIE
        [1] => CAPTAIN
    )
    [change_types] => Array (
        [0] => create
        [1] => update
        [2] => update
    )
)
```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`

---

### Example 5: List All Change Logs for Table

**What it does**: Lists all change log entries for a given parent table.

**Code**:
```php
<?php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';
require_once __DIR__ . '/config/database.php';

// List all change logs for content table
$options = [
    'limit' => 100,
    'offset' => 0,
    'order_by' => 'created_at',
    'order_dir' => 'DESC'
];

$allChanges = listChangeLogs('content', $options);

echo "Found " . count($allChanges) . " change log entries for content table:\n\n";

foreach ($allChanges as $change) {
    echo "Row ID: {$change['content_id']}\n";
    echo "  Agent: {$change['agent_name']}\n";
    echo "  Date: {$change['created_at']}\n";
    
    $metadata = json_decode($change['metadata'], true);
    echo "  Type: {$metadata['change_type']}\n";
    echo "\n";
}
```

**Result**: Returns all change logs for the table.

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`

---

## COMPLETE_WORKFLOW

### Workflow: Agent Creates Content and Logs Everything

**Scenario**: CAPTAIN (007) creates a new content item and logs the activity.

**Step 1: Create Content in Database**
```php
// Create content row (example - actual implementation depends on your system)
$contentId = createContent([
    'title' => 'New Article',
    'status' => 'draft',
    'user_id' => 1
]);
```

**Step 2: Write to Agent Log File**
```php
require_once __DIR__ . '/includes/wolfie_log_system.php';

$logEntry = "### Log Entry: 2025-11-18 - Content Created

**Date:** 2025-11-18 11:00:00  
**Agent:** CAPTAIN (007)  
**Channel:** 007  
**Action:** Created new content item

**Content ID:** {$contentId}  
**Title:** New Article  
**Status:** draft

**Reasoning:**
New article created for publication. Initial status set to draft for review.

**End log entry.**";

writeAgentLog(7, 7, 'CAPTAIN', $logEntry, [
    'action_type' => 'content_creation',
    'content_id' => $contentId
]);
```

**Step 3: Write Change Log to Database**
```php
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';

writeChangeLog(
    'content',
    $contentId,
    7,
    'CAPTAIN',
    7,
    [
        'change_type' => 'create',
        'new_values' => [
            'title' => 'New Article',
            'status' => 'draft'
        ],
        'change_summary' => 'Content created: "New Article" (draft)'
    ]
);
```

**Result**:
- ✅ Agent log file (`007_CAPTAIN_log.md`) has narrative entry
- ✅ `content_log` table has metadata about the log file
- ✅ `content_logs` table has change log entry for the content row

**All three systems working together!**

---

## API_EXAMPLES

### Example: Get Agent Log via API

**Request**:
```bash
GET /api/wolfie/logs/007/CAPTAIN
```

**Response**:
```json
{
  "success": true,
  "data": {
    "filename": "007_CAPTAIN_log.md",
    "channel": "007",
    "agent": "CAPTAIN",
    "content": "# CAPTAIN Log - Channel 007\n\n...",
    "metadata": {
      "agent_id": 7,
      "channel_id": 7,
      "version": "2.1.0",
      "entry_count": 5,
      "last_log_date": "2025-11-18"
    }
  }
}
```

### Example: Get Change Logs via API

**Request**:
```bash
GET /api/wolfie/logs/tables/content_logs/123
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "content_id": 123,
      "agent_id": 8,
      "agent_name": "WOLFIE",
      "channel_id": 8,
      "metadata": {
        "change_type": "update",
        "old_values": {"title": "Old Title"},
        "new_values": {"title": "New Title"}
      },
      "created_at": "2025-11-18T10:30:00Z"
    }
  ],
  "meta": {
    "total": 1,
    "row_id": 123,
    "table_name": "content_logs"
  }
}
```

**Related**: `docs/API_REFERENCE.md`

---

## SUMMARY

**Agent Log Files** (`public/logs/[channel]_[agent]_log.md`):
- ✅ Human-readable narrative logs
- ✅ Dual-storage (markdown + database)
- ✅ Use for: Activity tracking, decision logging, system evolution

**Database `_logs` Tables** (e.g., `content_logs`):
- ✅ Fast database queries
- ✅ Row-level change tracking
- ✅ Use for: Change history, audit trails, data integrity

**Both systems work together** to provide complete tracking:
- Agent logs = **WHAT agents are doing** (narrative)
- Database logs = **WHAT changed** (data)

**See Also**:
- `docs/QUICK_START_CHOOSE_YOUR_PATH.md` - Choose which system to use
- `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` - Complete system overview
- `docs/DATABASE_INTEGRATION.md` - Database integration details
- `docs/API_REFERENCE.md` - API documentation

---

**Created**: 2025-11-18  
**Version**: v2.1.0  
**Author**: Captain WOLFIE (Agent 008) with LILITH & MAAT's review improvements

