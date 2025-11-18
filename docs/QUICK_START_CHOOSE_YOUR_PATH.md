---
title: QUICK_START_CHOOSE_YOUR_PATH.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.1.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, QUICK_START, USER_GUIDE]
collections: [WHO, WHAT, HOW]
in_this_file_we_have: [OVERVIEW, CHOOSE_YOUR_PATH, PATH_1_AGENT_LOGS, PATH_2_DATABASE_LOGS, PATH_3_DEFINITIONS, NEXT_STEPS]
superpositionally: ["FILEID_QUICK_START_PATH"]
shadow_aliases: []
parallel_paths: []
---

# Quick Start: Choose Your Path

**Version**: v2.1.0  
**Time to Complete**: 5 minutes  
**Difficulty**: Beginner-friendly

## OVERVIEW

WOLFIE Headers has **three systems** for different purposes. This guide helps you **choose the right path** based on what you want to do.

**Don't worry** - you don't need to understand all three systems right away. Just pick the path that matches your goal!

---

## CHOOSE_YOUR_PATH

### 🎯 What do you want to do?

**A)** Track what agents are doing (activity logs, decisions, system evolution)  
→ **Path 1: Agent Log Files**

**B)** Track changes to database rows (what changed, who changed it, when)  
→ **Path 2: Database `_logs` Tables**

**C)** Define tags, collections, and context (source-of-truth definitions)  
→ **Path 3: md_files Directory**

**D)** I'm not sure - show me all three  
→ **See "All Three Systems" below**

---

## PATH_1_AGENT_LOGS

**What it is**: Human-readable log files where agents record their activities, decisions, and system evolution.

**When to use**: 
- You want to read what agents are doing
- You need narrative logs (like a ship's log)
- You want to track decisions and reasoning

**Quick Start** (2 minutes):

1. **Location**: `public/logs/`
2. **Format**: `[channel]_[agent]_log.md` (e.g., `007_CAPTAIN_log.md`)
3. **Read a log**:
   ```php
   require_once 'includes/wolfie_log_system.php';
   $log = readAgentLog(7, 'CAPTAIN');
   echo $log['content'];
   ```
4. **Write to a log**:
   ```php
   writeAgentLog(7, 7, 'CAPTAIN', 'New agent created: SECURITY (911)');
   ```

**Example Files**:
- `007_CAPTAIN_log.md` - CAPTAIN's log
- `008_WOLFIE_log.md` - WOLFIE's log
- `911_SECURITY_log.md` - SECURITY's log

**See Also**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

## PATH_2_DATABASE_LOGS

**What it is**: Database tables that track changes to individual database rows (what changed, old values → new values).

**When to use**:
- You need to track changes to database records
- You want fast queries (database indexed)
- You need change history for specific rows

**Quick Start** (2 minutes):

1. **Location**: Database tables ending with `_logs` (e.g., `content_logs`, `user_logs`)
2. **Discover tables**:
   ```php
   require_once 'includes/wolfie_database_logs_system.php';
   $tables = discoverLogsTables();
   ```
3. **Write a change log**:
   ```php
   writeChangeLog('content', 123, 8, 'WOLFIE', 8, [
       'change_type' => 'update',
       'old_values' => ['title' => 'Old Title'],
       'new_values' => ['title' => 'New Title']
   ]);
   ```
4. **Read change logs**:
   ```php
   $changes = readChangeLogs('content', 123);
   ```

**Example Tables**:
- `content_logs` - Tracks changes to `content` table
- `user_logs` - Tracks changes to `user` table
- `agent_logs` - Tracks changes to `agent` table

**See Also**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`

---

## PATH_3_DEFINITIONS

**What it is**: Source-of-truth definitions for tags, collections, and context. Same term, different meanings per agent.

**When to use**:
- You need to define what tags mean
- You want agent-specific vocabulary
- You're setting up channel-specific definitions

**Quick Start** (3 minutes):

1. **Location**: `md_files/` directory
2. **Structure**: `[channel]_[agent]_[type]/` (e.g., `1_wolfie_wolfie/TAGS.md`)
3. **Create definitions**:
   ```
   md_files/
     1_wolfie_wolfie/
       TAGS.md          ← Define tags
       COLLECTIONS.md   ← Define collections
       README.md        ← Agent context overview
   ```
4. **Use in files**: Add `agent_username: wolfie` and `onchannel: 1` to your markdown files

**Example Structure**:
```
md_files/
  1_wolfie_wolfie/     ← WOLFIE's definitions (Channel 1)
     TAGS.md
     COLLECTIONS.md
  1_wolfie_rose/       ← ROSE's definitions (Channel 1)
     TAGS.md
     COLLECTIONS.md
```

**See Also**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#FALLBACK_CHAIN`

---

## ALL_THREE_SYSTEMS

**How they work together**:

1. **md_files** = **Definitions** (WHAT things mean)
   - Tags, collections, context
   - Agent-specific vocabulary
   - Source-of-truth

2. **Agent Log Files** = **Activities** (WHAT agents are doing)
   - Narrative logs
   - Decision tracking
   - System evolution

3. **Database `_logs` Tables** = **Changes** (WHAT changed)
   - Row-level change tracking
   - Fast queries
   - Change history

**Example Workflow**:

1. Agent uses a tag → **md_files** provides definition
2. Agent makes a decision → **Agent Log File** records it
3. Agent changes database row → **Database `_logs`** tracks the change

**See Also**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#THREE_LOG_SYSTEMS`

---

## NEXT_STEPS

**After choosing your path**:

1. **Path 1 (Agent Logs)**: 
   - Read `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`
   - See `public/examples/` for code examples

2. **Path 2 (Database Logs)**:
   - Read `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`
   - See `public/examples/example_write_change_log.php`

3. **Path 3 (Definitions)**:
   - Read `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#FALLBACK_CHAIN`
   - See `md_files/1_wolfie_wolfie/` for examples

**Need Help?**
- See `docs/TROUBLESHOOTING_GUIDE.md` for common issues
- See `docs/API_REFERENCE.md` for API documentation
- See `README.md` for complete overview

---

**Created**: 2025-11-18  
**Version**: v2.1.0  
**Author**: Captain WOLFIE (Agent 008) with MAAT's user experience improvements

