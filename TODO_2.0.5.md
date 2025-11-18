---
title: TODO_2.0.5.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.5
date_created: 2025-11-18
last_modified: 2025-11-18
status: draft
onchannel: 1
tags: [PLANNING, VERSIONING, LOG_READER, BROWSER]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, LOG_READER_SYSTEM, FEATURES, IMPLEMENTATION, DOCUMENTATION, RELEASE_CHECKLIST]
superpositionally: ["FILEID_WOLFIE_HEADERS_TODO_2.0.5"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers 2.0.5 TODO Plan

**Current Version**: v2.0.4 (Current) - Agent Integration  
**Target Version**: v2.0.5 (Log Reader System)  
**Required By**: LUPOPEDIA_PLATFORM 1.0.0  
**GitHub Repository**: https://github.com/lupopedia/WOLFIE_HEADERS  
**Status**: Planning Phase

---

## OVERVIEW

WOLFIE Headers 2.0.5 introduces a **Log Reader System** (`wolfie_reader.php`) that provides a web interface for browsing, searching, and viewing agent log files in the `public/logs/` directory. This system enables users to discover agents, channels, and view log entries across the entire LUPOPEDIA platform.

**Why 2.0.5?**
- Log files are accumulating in `public/logs/` directory
- Need a way to browse and discover agents and channels
- Need ability to view logs by agent, by channel, or specific combinations
- Log system needs a user-friendly interface for exploration

**Log Reader System:**
- **File**: `public/wolfie_reader.php`
- **Location**: Inside `public/logs/` directory (or accessible from public directory)
- **Purpose**: Browse, search, and view agent log files
- **Status**: To be implemented

---

## LOG_READER_SYSTEM

### 🔴 HIGH PRIORITY - Log Reader Implementation

#### 1. **Core Functionality**

**Agent Discovery:**
- [ ] Scan `public/logs/` directory for all log files
- [ ] Extract agent names from log filenames (pattern: `[channel]_[agent]_log.md` or `[channel]_[agent].md`)
- [ ] Display list of all agents found in logs directory
- [ ] Show agent count and statistics

**Channel Discovery:**
- [ ] Extract channel numbers from log filenames
- [ ] Display list of all channels found in logs directory
- [ ] Show channel count and statistics
- [ ] Display which agents are active on each channel

**Log Viewing Options:**
- [ ] View specific log (channel + agent combination)
- [ ] View all logs on a specific channel
- [ ] View all logs by a specific agent
- [ ] View all logs (complete directory listing)

#### 2. **File Parsing**

**Filename Patterns:**
- Pattern 1: `[channel]_[agent]_log.md` (e.g., `007_CAPTAIN_log.md`)
- Pattern 2: `[channel]_[agent].md` (e.g., `007_wolfie.md`)
- Pattern 3: Legacy formats (if any)

**Parsing Requirements:**
- [ ] Extract channel number from filename
- [ ] Extract agent name from filename
- [ ] Handle case variations (uppercase/lowercase)
- [ ] Handle missing `_log` suffix
- [ ] Validate channel numbers (000-999)
- [ ] Handle special characters in agent names

#### 3. **User Interface**

**Main View:**
- [ ] Display agent list with counts
- [ ] Display channel list with counts
- [ ] Show statistics (total logs, total agents, total channels)
- [ ] Search/filter functionality

**Log View:**
- [ ] Display log content with markdown rendering
- [ ] Show log metadata (from YAML frontmatter if available)
- [ ] Navigation back to main view
- [ ] Links to related logs (same channel, same agent)

**Filtering Options:**
- [ ] Filter by agent
- [ ] Filter by channel
- [ ] Filter by date (if available in metadata)
- [ ] Search by content (if implemented)

---

## FEATURES

### 1. **Agent Discovery**

**Agent List:**
- [ ] List all unique agents found in log files
- [ ] Show count of log files per agent
- [ ] Show channels each agent operates on
- [ ] Link to view all logs by agent

**Agent Details:**
- [ ] Agent name
- [ ] Number of log files
- [ ] Channels agent operates on
- [ ] Last log entry date (if available)

### 2. **Channel Discovery**

**Channel List:**
- [ ] List all unique channels found in log files
- [ ] Show count of log files per channel
- [ ] Show agents active on each channel
- [ ] Link to view all logs on channel

**Channel Details:**
- [ ] Channel number (000-999)
- [ ] Number of log files
- [ ] Agents active on channel
- [ ] Last log entry date (if available)

### 3. **Log Viewing**

**Single Log View:**
- [ ] Display full log content
- [ ] Parse and display YAML frontmatter (if present)
- [ ] Render markdown to HTML
- [ ] Show log metadata (date, agent, channel)

**Channel View:**
- [ ] Display all logs on a specific channel
- [ ] Group by agent (if multiple agents on channel)
- [ ] Show chronological order (if dates available)
- [ ] Link to individual log views

**Agent View:**
- [ ] Display all logs by a specific agent
- [ ] Group by channel (if agent operates on multiple channels)
- [ ] Show chronological order (if dates available)
- [ ] Link to individual log views

**All Logs View:**
- [ ] Display complete directory listing
- [ ] Group by channel or agent (user choice)
- [ ] Show file sizes and dates
- [ ] Link to individual log views

---

## IMPLEMENTATION

### 1. **File Structure**

**Main File:**
- `public/wolfie_reader.php` - Main log reader interface

**Dependencies:**
- `public/logs/` directory (must exist)
- Log files following naming convention: `[channel]_[agent]_log.md` or `[channel]_[agent].md`
- Markdown rendering capability (PHP or library)

### 2. **Core Functions**

**Directory Scanning:**
```php
function scanLogsDirectory($logsDir) {
    // Scan directory for log files
    // Extract channel and agent from filenames
    // Return structured data (agents, channels, files)
}
```

**Filename Parsing:**
```php
function parseLogFilename($filename) {
    // Parse filename to extract channel and agent
    // Handle multiple patterns
    // Return array with channel, agent, full path
}
```

**Agent Discovery:**
```php
function getAgentsFromLogs($logsData) {
    // Extract unique agents
    // Count logs per agent
    // Return agent list with statistics
}
```

**Channel Discovery:**
```php
function getChannelsFromLogs($logsData) {
    // Extract unique channels
    // Count logs per channel
    // Return channel list with statistics
}
```

**Log Filtering:**
```php
function filterLogsByAgent($logsData, $agentName) {
    // Filter logs by agent name
    // Return matching log files
}

function filterLogsByChannel($logsData, $channelId) {
    // Filter logs by channel number
    // Return matching log files
}
```

### 3. **User Interface**

**Main Page:**
- Agent list section
- Channel list section
- Statistics section
- Search/filter controls

**Log View Page:**
- Log content display
- Metadata display
- Navigation controls
- Related logs links

**Styling:**
- Consistent with WOLFIE Headers theme
- Responsive design
- Clear navigation
- Readable log display

---

## DOCUMENTATION

### 1. **User Guide**

**Documentation to Create:**
- [ ] `docs/LOG_READER_GUIDE.md` - User guide for log reader
- [ ] How to use agent discovery
- [ ] How to use channel discovery
- [ ] How to view logs
- [ ] How to filter logs

### 2. **Technical Documentation**

**Documentation to Create:**
- [ ] `docs/LOG_READER_IMPLEMENTATION.md` - Technical implementation details
- [ ] Filename parsing patterns
- [ ] Directory scanning algorithm
- [ ] Filtering logic
- [ ] Markdown rendering approach

### 3. **Integration Documentation**

**Documentation Updates:**
- [ ] Update `README.md` with log reader feature
- [ ] Update `CHANGELOG.md` with v2.0.5 release notes
- [ ] Update `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` with log reader section
- [ ] Update `docs/LOG_FILE_SYSTEM_EXPLAINED.md` with reader interface

---

## RELEASE_CHECKLIST

### Pre-Release

- [ ] `wolfie_reader.php` implemented and tested
- [ ] Agent discovery working correctly
- [ ] Channel discovery working correctly
- [ ] Log viewing working correctly
- [ ] Filtering working correctly
- [ ] All documentation updated
- [ ] CHANGELOG.md updated with v2.0.5
- [ ] RELEASE_NOTES_v2.0.5.md created
- [ ] README.md updated with log reader feature

### Release

- [ ] Version number updated to 2.0.5
- [ ] All files committed
- [ ] GitHub release created
- [ ] Release notes published
- [ ] Documentation published

### Post-Release

- [ ] Verify log reader works with existing log files
- [ ] Verify log reader handles edge cases (missing files, invalid names, etc.)
- [ ] Verify documentation is accessible
- [ ] Update LUPOPEDIA_PLATFORM documentation (if needed)
- [ ] Announce release (if applicable)

---

## NOTES

**Log Reader System:**
- File: `public/wolfie_reader.php`
- Location: Inside `public/logs/` directory or accessible from public directory
- Purpose: Browse, search, and view agent log files
- Status: To be implemented

**Filename Patterns:**
- Primary: `[channel]_[agent]_log.md` (e.g., `007_CAPTAIN_log.md`)
- Alternative: `[channel]_[agent].md` (e.g., `007_wolfie.md`)
- Channel format: 000-999 (zero-padded)
- Agent format: Alphanumeric, case-sensitive

**Integration Points:**
- WOLFIE Headers log system
- LUPOPEDIA_PLATFORM agent system
- Log file naming convention
- Markdown rendering system

**Future Considerations:**
- Search functionality (full-text search in log content)
- Date-based filtering
- Export functionality
- API endpoints for programmatic access
- Real-time log updates (if implemented)

---

**Last Updated**: 2025-11-18  
**Status**: Planning Phase  
**Target Release**: TBD

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Licensed under GPL v3.0 + Apache 2.0.

