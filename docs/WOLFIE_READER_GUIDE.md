---
title: WOLFIE_READER_GUIDE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.2.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, USER_GUIDE]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW]
in_this_file_we_have: [OVERVIEW, QUICK_START, VIEWING_OPTIONS, FILTERING, EXAMPLES, TROUBLESHOOTING]
superpositionally: ["FILEID_WOLFIE_READER_GUIDE"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Log Reader Guide

**Version**: 2.2.0  
**Status**: Published  
**Purpose**: User guide for the enhanced WOLFIE Log Reader

## OVERVIEW

The WOLFIE Log Reader (`public/wolfie_reader.php`) provides a unified interface for viewing logs from both file-based and database-based log systems. You can view logs by channel, by agent name, or by both, across both file logs and database tables.

**Key Features**:
- View file logs from `public/logs/` directory
- View database logs from tables ending with `_logs` or `_log`
- Filter by channel (000-999)
- Filter by agent name
- Filter by channel AND agent name
- Unified interface showing both sources

---

## QUICK_START

### Access the Reader

Navigate to: `public/wolfie_reader.php`

### Main View

The main view shows:
- **Statistics**: Total logs, file logs, database logs, agents, channels
- **Agents List**: All agents with log counts
- **Channels List**: All channels with log counts
- **Database Tables**: All discovered `*_logs` and `*_log` tables
- **All Log Files**: Complete list of log files

---

## VIEWING_OPTIONS

### View by Channel

**Purpose**: See all logs for a specific channel (000-999).

**Steps**:
1. Click on a channel number in the "Channels" section
2. Or use the filter panel: Enter channel number and click "Apply Filters"

**Result**: Shows all file logs and database logs for that channel.

### View by Agent Name

**Purpose**: See all logs for a specific agent.

**Steps**:
1. Click on an agent name in the "Agents" section
2. Or use the filter panel: Enter agent name and click "Apply Filters"

**Result**: Shows all file logs and database logs for that agent across all channels.

### View by Channel AND Agent Name

**Purpose**: See logs for a specific agent on a specific channel.

**Steps**:
1. Use the filter panel
2. Enter both channel number and agent name
3. Click "Apply Filters"

**Result**: Shows file logs and database logs matching both criteria.

### View Database Table

**Purpose**: See all logs from a specific database table.

**Steps**:
1. Click on a database table name in the "Database Log Tables" section
2. Or use the filter panel: Select a table from the dropdown

**Result**: Shows all log entries from that table.

---

## FILTERING

### Filter Panel

The filter panel provides multiple filtering options:

1. **Source**: Choose "All Sources", "File Logs Only", or "Database Logs Only"
2. **Channel**: Enter channel number (000-999)
3. **Agent Name**: Enter agent name
4. **Database Table**: Select a specific table (if database available)

### Source Tabs

When viewing filtered results, use source tabs to switch between:
- **All**: Shows both file logs and database logs
- **Files**: Shows only file logs
- **Database**: Shows only database logs

---

## EXAMPLES

### Example 1: View All Logs for Channel 007

**URL**: `wolfie_reader.php?view=filtered&channel=007&source=all`

**What You'll See**:
- File logs: `logs/007_*_log.md` files
- Database logs: All entries where `channel_id = 7`

### Example 2: View All Logs for Agent WOLFIE

**URL**: `wolfie_reader.php?view=filtered&agent=WOLFIE&source=all`

**What You'll See**:
- File logs: `logs/*_WOLFIE_log.md` files
- Database logs: All entries where `agent_name = 'WOLFIE'`

### Example 3: View Logs for Channel 007 AND Agent CAPTAIN

**URL**: `wolfie_reader.php?view=filtered&channel=007&agent=CAPTAIN&source=all`

**What You'll See**:
- File logs: `logs/007_CAPTAIN_log.md`
- Database logs: All entries where `channel_id = 7 AND agent_name = 'CAPTAIN'`

### Example 4: View Database Logs Only

**URL**: `wolfie_reader.php?view=filtered&source=database&table=content_logs`

**What You'll See**:
- Only database logs from `content_logs` table
- No file logs displayed

---

## TROUBLESHOOTING

### Database Not Available

**Issue**: Database logs section not showing.

**Solution**: 
- Check database configuration in `public/config/database.php`
- Ensure database connection is working
- Reader will continue with file-only mode if database unavailable

### No Logs Found

**Issue**: Filter returns no results.

**Solutions**:
- Check channel number format (should be 000-999)
- Check agent name spelling (case-sensitive)
- Verify log files exist in `public/logs/` directory
- Verify database tables have `channel_id` and `agent_name` columns

### Database Tables Not Discovered

**Issue**: Database tables not showing in list.

**Solutions**:
- Ensure tables end with `_logs` or `_log`
- Verify tables have `channel_id` and `agent_name` columns
- Check database connection is working
- Refresh the page to reload table discovery

---

## ADVANCED_USAGE

### URL Parameters

You can construct URLs directly with parameters:

- `view=main` - Main view (default)
- `view=filtered` - Filtered results view
- `view=agent&agent=NAME` - View by agent
- `view=channel&channel=XXX` - View by channel
- `view=table&table=NAME` - View database table
- `view=log&file=FILENAME` - View specific log file
- `source=all|files|database` - Filter by source
- `channel=XXX` - Filter by channel
- `agent=NAME` - Filter by agent
- `table=NAME` - Filter by database table

### Combining Filters

You can combine multiple filters:
- Channel + Agent: `?view=filtered&channel=007&agent=CAPTAIN`
- Source + Table: `?view=filtered&source=database&table=content_logs`
- All filters: `?view=filtered&source=all&channel=007&agent=CAPTAIN&table=content_logs`

---

## RELATED_DOCUMENTATION

- **README.md** - Main WOLFIE Headers documentation
- **RELEASE_NOTES_v2.2.0.md** - v2.2.0 release notes
- **TODO_2.2.0.md** - Implementation plan
- **DATABASE_INTEGRATION.md** - Database log table structure

---

**Guide Version**: 2.2.0  
**Last Updated**: 2025-11-18

