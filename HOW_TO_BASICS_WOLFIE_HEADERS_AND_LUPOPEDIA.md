---
title: HOW_TO_BASICS_WOLFIE_HEADERS_AND_LUPOPEDIA.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.3
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, HOWTO, BASICS, QUICK_START]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW]
in_this_file_we_have: [OVERVIEW, WHAT_IS_WOLFIE_HEADERS, WHAT_IS_LUPOPEDIA, THE_LOG_SYSTEM, CHANNELS_AND_AGENTS, HOW_TO_USE, EXAMPLES, QUICK_REFERENCE]
---

# HOW TO BASICS: WOLFIE Headers & LUPOPEDIA

**Date:** 2025-11-18  
**Captain:** WOLFIE (Agent 008)  
**Status:** Building While Flying - Manual Written After Launch ☕  
**Ship:** LUPOPEDIA LLC (Launched Yesterday)

---

## OVERVIEW

**The Situation:** We launched LUPOPEDIA LLC yesterday with Crafty Syntax in the mothership's hull. WOLFIE Headers is being built while we fly. This is the only ship where the manual is written after we get it up in the air.

**What You Need to Know:**
1. **WOLFIE Headers** = Documentation system (YAML frontmatter for markdown files)
2. **LUPOPEDIA** = Knowledge platform (web application with AI agents, channels, logs)
3. **Log System** = Every agent on every channel has a log file (`[channel]_[agent]_log.md`)

**The Philosophy:** Building while flying. Manual written after launch. Coffee hot. Maximum 999.

---

## WHAT_IS_WOLFIE_HEADERS

### The Simple Answer

**WOLFIE Headers** is a documentation system that uses YAML frontmatter (metadata at the top of markdown files) to organize documentation.

### What It Does

- **Replaces bulky headers** with concise YAML frontmatter
- **Organizes by channels** (like Crafty Syntax channels)
- **Uses source-of-truth files** (no duplication)
- **Works for humans AND AI agents** (same files, same format)

### Example WOLFIE Header

```yaml
---
title: MY_FILE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.3
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, HOWTO]
collections: [WHO, WHAT, HOW]
in_this_file_we_have: [OVERVIEW, EXAMPLES, REFERENCE]
---

# My File Content Here
```

### Key Concepts

- **YAML Frontmatter**: Metadata between `---` at the top of markdown files
- **Channels**: Documentation organized by channel (like `1_wolfie/`)
- **Tags**: Categorize files (`[DOCUMENTATION, HOWTO]`)
- **Collections**: Organize by type (`[WHO, WHAT, HOW]`)
- **in_this_file_we_have**: Table of contents list for parsers

### Where It Lives

- **GitHub**: https://github.com/lupopedia/WOLFIE_HEADERS
- **Version**: 2.0.3 (Current) | 2.0.2 (Stable) | 2.0.1 (Stable) | 2.0.0 (Minimum)
- **Required By**: LUPOPEDIA_PLATFORM (must be installed separately)

---

## WHAT_IS_LUPOPEDIA

### The Simple Answer

**LUPOPEDIA** is the knowledge platform (web application) that organizes content using AI agents, channels, tags, and collections.

### What It Does

- **Organizes content** using tags, collections, and channels
- **AI Agent System** with 1000 channels (000-999)
- **Multi-agent broadcasting** (multiple agents on same channel)
- **Log system** for tracking agent activities
- **Built on Crafty Syntax** (22 years stable, 1.2M installations)

### Key Features

1. **AI Agent System**: 1000 channels (000-999), agents can create other agents
2. **Channel Architecture**: Radio network model (like Crafty Syntax)
3. **Log System**: Every agent on every channel has a log file
4. **WOLFIE Headers**: Documentation system (required dependency)
5. **Functional Commands**: System-wide command router (`lupopedia.php`)

### The Dependency Chain

```
Crafty Syntax Live Help (Foundation)
    ↓
    └─> WOLFIE Headers 2.0.3 (REQUIRED - separate package)
        ↓
        └─> LUPOPEDIA_PLATFORM (Layer 1)
            ↓
            └─> Agent System (Layer 2)
                Channels: 000-999 (maximum 999)
```

**Critical:** WOLFIE Headers is **NOT included** in LUPOPEDIA_PLATFORM. It must be installed separately.

---

## THE_LOG_SYSTEM

### The Discovery

We started with a "Captain's Log" but realized: **every agent on every channel needs a log file**.

### How It Works

**File Naming:** `[channel]_[agent]_log.md`

**Examples:**
- `007_CAPTAIN_log.md` - Channel 007, Agent CAPTAIN
- `008_WOLFIE_log.md` - Channel 008, Agent WOLFIE
- `911_SECURITY_log.md` - Channel 911, Agent SECURITY
- `411_HELP_log.md` - Channel 411, Agent HELP

**Location:** `public/logs/`

### The Structure

**Channels:** 000-999 (maximum 999 channels)

**Agents Per Channel:** Multiple agents can work on the same channel

**Example:**
- Channel 007: CAPTAIN (Agent 007)
- Channel 008: WOLFIE (Agent 008)
- Channel 911: SECURITY (Agent 911)
- Channel 411: HELP (Agent 411)

### Log File Format

Every log file has:
1. **WOLFIE Headers** (YAML frontmatter) with log-specific fields
2. **Log Entries** (markdown content with timestamps)
3. **Database Sync** (`content_log` table for fast queries)

### Example Log File

```markdown
---
title: 007_CAPTAIN_log.md
agent_username: captain
date_created: 2025-11-18
last_modified: 2025-11-18 14:30:00
status: active
onchannel: 7
tags: [LOG, AGENT_LOG, CHANNEL_LOG]
collections: [LOG_ENTRIES]
in_this_file_we_have: [LOG_ENTRIES, AGENT_ACTIVITY]
log_entry_count: 5
last_log_date: 2025-11-18
channel_id: 7
agent_id: 7
---

# CAPTAIN Log - Channel 007

**Log initialized:** 2025-11-18 09:25:00
**Agent ID:** 7
**Channel ID:** 7

---

### Log Entry: 2025-11-18 - First Entry

This is the first log entry for CAPTAIN on Channel 007.

**End log entry.**
```

### Dual-Storage System

**Markdown Files** (Source of Truth):
- Human-readable
- Version control friendly
- Full log history

**Database Table** (`content_log`):
- Fast queries
- Metadata storage
- Indexed for performance

**Sync:** Every write operation syncs to both storage systems.

---

## CHANNELS_AND_AGENTS

### Channels (000-999)

**Total Channels:** 1000 channels (000-999, maximum 999)

**Direct Mapping:** Agent ID = Channel Number
- Agent 007 → Channel 007
- Agent 008 → Channel 008
- Agent 911 → Channel 911

**Special Channels:**
- 007: CAPTAIN (Commanding Officer)
- 008: WOLFIE (System Architect)
- 411: HELP (Information/Help)
- 911: SECURITY (Emergency/Security)
- 777: LILITH (Luck/Chaos)

### Agents Per Channel

**Key Discovery:** Multiple agents can work on the same channel.

**Example:**
- Channel 007: CAPTAIN (primary), other agents can join
- Channel 008: WOLFIE (primary), other agents can join
- Channel 777: LILITH (primary), other agents can join

**How It Works:**
- Each agent has an ID (000-999)
- Agent ID usually matches channel number
- Multiple agents can broadcast/listen on same channel
- Each agent has its own log file

### Agent Communication

**The Receptionist Model:**
```
User Request
    ↓
WOLFIE (008) - Reads headers, routes tasks
    ↓
CAPTAIN (007) - Tactical operator, transfers to VISH
    ↓
VISHWAKARMA (075) - Normalizes requests, tracks changes
    ↓
Response
```

**Philosophy:** CAPTAIN doesn't know what he's doing, but knows who to transfer to. The system works anyway. Brittleness is a feature.

---

## HOW_TO_USE

### 1. Creating a Log File

**For a New Agent:**
```php
require_once 'public/includes/wolfie_log_system.php';

// Create log file for CAPTAIN (Channel 007, Agent ID 7)
$result = initializeAgentLog(7, 7, 'CAPTAIN');

if ($result['success']) {
    echo "Log file created: " . $result['file_path'];
    // Output: Log file created: public/logs/007_CAPTAIN_log.md
}
```

### 2. Writing a Log Entry

**Write to Agent's Log:**
```php
require_once 'public/includes/wolfie_log_system.php';

// Write log entry for WOLFIE
$logEntry = "Completed migration 1078. Created content_log table successfully.";
$metadata = [
    'migration_id' => 1078,
    'action' => 'table_creation',
    'status' => 'success'
];

$result = writeAgentLog(8, 8, 'WOLFIE', $logEntry, $metadata);

if ($result['success']) {
    echo "Log entry written. Total entries: " . $result['log_entry_count'];
}
```

### 3. Reading a Log File

**Read Agent's Log:**
```php
require_once 'public/includes/wolfie_log_system.php';

// Read WOLFIE's log file
$log = readAgentLog(8, 'WOLFIE');

if ($log['file_exists']) {
    echo "Log Entry Count: " . $log['headers']['log_entry_count'];
    echo "Last Log Date: " . $log['headers']['last_log_date'];
    echo "Content: " . $log['content'];
}
```

### 4. Listing All Log Files

**List All Agent Logs:**
```php
require_once 'public/includes/wolfie_log_system.php';

// List all agent log files
$allLogs = listAllAgentLogs();

foreach ($allLogs as $log) {
    echo sprintf(
        "Channel %03d - %s: %s (%s)\n",
        $log['channel_id'],
        $log['agent_name'],
        $log['filename'],
        $log['readable_size']
    );
}
```

### 5. Using WOLFIE Headers

**Add to Any Markdown File:**
```yaml
---
title: MY_FILE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.3
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION]
collections: [WHO, WHAT, HOW]
in_this_file_we_have: [OVERVIEW, EXAMPLES]
---

# My File Content Here
```

---

## EXAMPLES

### Example 1: CAPTAIN's Log Entry

**File:** `public/logs/007_CAPTAIN_log.md`

**Entry:**
```markdown
---

### Log Entry: 2025-11-18 - Ship Launch

**Date:** 2025-11-18 10:00:00  
**Agent:** CAPTAIN (007)  
**Channel:** 007

We launched LUPOPEDIA LLC yesterday with Crafty Syntax in the mothership's hull. WOLFIE Headers is being built while we fly. This is the only ship where the manual is written after we get it up in the air.

**Status:** Operational  
**Coffee:** Hot ☕

**End log entry.**
```

### Example 2: WOLFIE's Log Entry

**File:** `public/logs/008_WOLFIE_log.md`

**Entry:**
```markdown
---

### Log Entry: 2025-11-18 - Log System Created

**Date:** 2025-11-18 14:30:00  
**Agent:** WOLFIE (008)  
**Channel:** 008

Created the log file system. Every agent on every channel now has a log file in format `[channel]_[agent]_log.md`. Channels 000-999, multiple agents per channel.

**Files Created:**
- `007_CAPTAIN_log.md`
- `008_WOLFIE_log.md`
- `911_SECURITY_log.md`
- `411_HELP_log.md`

**Database:** `content_log` table created (Migration 1078)

**End log entry.**
```

### Example 3: Multiple Agents on Channel 007

**Scenario:** CAPTAIN (007) and another agent both work on Channel 007

**Log Files:**
- `007_CAPTAIN_log.md` - CAPTAIN's activities
- `007_OTHERAGENT_log.md` - Other agent's activities

**Both agents:**
- Broadcast on Channel 007
- Listen on Channel 007
- Have separate log files
- Can see each other's activities

---

## QUICK_REFERENCE

### File Locations

- **WOLFIE Headers:** `GITHUB_LUPOPEDIA/WOLFIE_HEADERS/`
- **LUPOPEDIA Platform:** `GITHUB_LUPOPEDIA/LUPOPEDIA_PLATFORM/`
- **Log Files:** `public/logs/[channel]_[agent]_log.md`
- **Log System Functions:** `public/includes/wolfie_log_system.php`

### Key Functions

- `initializeAgentLog($channelId, $agentId, $agentName)` - Create log file
- `writeAgentLog($channelId, $agentId, $agentName, $logEntry, $metadata)` - Write entry
- `readAgentLog($channelId, $agentName)` - Read log file
- `readContentLogFromDatabase($channelId, $agentId, $agentName)` - Read from database
- `listAllAgentLogs()` - List all log files

### Channel Numbers

- **000-999:** All available channels (maximum 999)
- **007:** CAPTAIN (Commanding Officer)
- **008:** WOLFIE (System Architect)
- **411:** HELP (Information/Help)
- **911:** SECURITY (Emergency/Security)
- **777:** LILITH (Luck/Chaos)

### Naming Conventions

- **Log Files:** `[channel]_[agent]_log.md` (e.g., `007_CAPTAIN_log.md`)
- **Agent Files:** `who_is_agent_[channel_id]_[agent_name].php` (e.g., `who_is_agent_007_captain.php`)
- **Channel Directories:** `[channel]_[agent]/` (e.g., `1_wolfie/`)

### Database Tables

- **content_log:** Log metadata (Migration 1078)
- **content_headers:** WOLFIE Headers metadata
- **agents:** Agent information
- **channels:** Channel information

---

## SUMMARY

**WOLFIE Headers:**
- Documentation system with YAML frontmatter
- Organizes by channels
- Required by LUPOPEDIA_PLATFORM
- Version 2.0.3 (Current)

**LUPOPEDIA:**
- Knowledge platform with AI agents
- 1000 channels (000-999)
- Built on Crafty Syntax
- Log system for all agents

**Log System:**
- Every agent on every channel has a log file
- Format: `[channel]_[agent]_log.md`
- Location: `public/logs/`
- Dual-storage: Markdown files + Database

**The Philosophy:**
- Building while flying
- Manual written after launch
- Coffee hot ☕
- Maximum 999

---

**For More Information:**
- **WOLFIE Headers:** https://github.com/lupopedia/WOLFIE_HEADERS
- **LUPOPEDIA Platform:** https://github.com/lupopedia/LUPOPEDIA_PLATFORM
- **Log System Explained:** `docs/LOG_FILE_SYSTEM_EXPLAINED.md`
- **WOLFIE Headers README:** `GITHUB_LUPOPEDIA/WOLFIE_HEADERS/README.md`
- **LUPOPEDIA README:** `GITHUB_LUPOPEDIA/LUPOPEDIA_PLATFORM/README.md`

---

*Captain WOLFIE, signing off. Coffee hot. Ship operational. Manual written after launch. Maximum 999.* ☕✨

