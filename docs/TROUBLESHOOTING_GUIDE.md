---
title: TROUBLESHOOTING_GUIDE.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.1.0
date_created: 2025-11-18
last_modified: 2025-11-18
status: published
onchannel: 1
tags: [DOCUMENTATION, TROUBLESHOOTING, HELP]
collections: [WHAT, HOW, DO]
in_this_file_we_have: [OVERVIEW, COMMON_ISSUES, AGENT_LOGS_ISSUES, DATABASE_LOGS_ISSUES, API_ISSUES, CONFIGURATION_ISSUES, SOLUTIONS]
superpositionally: ["FILEID_TROUBLESHOOTING"]
shadow_aliases: []
parallel_paths: []
---

# Troubleshooting Guide

**Version**: v2.1.0  
**Last Updated**: 2025-11-18

## OVERVIEW

This guide helps you solve common problems with WOLFIE Headers. Each issue includes:
- **Problem**: What's wrong
- **Cause**: Why it's happening
- **Solution**: How to fix it
- **Related**: Links to relevant documentation

---

## COMMON_ISSUES

### Issue: "Cannot connect to database"

**Problem**: Database connection errors when using WOLFIE Headers functions.

**Cause**: Invalid database credentials or missing configuration file.

**Solution**:
1. Check `public/config/database.php` exists
2. Verify database credentials are correct:
   ```php
   define('WOLFIE_DB_HOST', 'localhost');
   define('WOLFIE_DB_NAME', 'your_database');
   define('WOLFIE_DB_USER', 'your_username');
   define('WOLFIE_DB_PASS', 'your_password');
   ```
3. Test connection manually:
   ```php
   require_once 'config/database.php';
   $conn = getWOLFIEDatabaseConnection();
   if ($conn) {
       echo "Connected!";
   }
   ```

**Related**: `docs/INSTALLATION.md`, `public/config/database.php`

---

### Issue: "Log file not found"

**Problem**: `readAgentLog()` returns "file not found" error.

**Cause**: Log file doesn't exist or wrong path.

**Solution**:
1. Check log file exists: `public/logs/[channel]_[agent]_log.md`
2. Verify naming convention: `007_CAPTAIN_log.md` (not `007_captain_log.md`)
3. Initialize log file if missing:
   ```php
   require_once 'includes/wolfie_log_system.php';
   initializeAgentLog(7, 7, 'CAPTAIN');
   ```

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

### Issue: "Invalid channel ID" error

**Problem**: API returns "Channel ID must be between 0 and 999".

**Cause**: Channel ID out of valid range (0-999).

**Solution**:
- Use channel ID between 0 and 999
- Zero-pad channel IDs: `007` not `7` (for filenames)
- API accepts both: `7` or `007` (both valid)

**Related**: `docs/API_REFERENCE.md`

---

### Issue: "API endpoint not found"

**Problem**: API returns 404 "endpoint not found".

**Cause**: Incorrect API path or routing issue.

**Solution**:
1. Check base URL: `/api/wolfie/` (not `/api/`)
2. Verify endpoint exists: See `docs/API_REFERENCE.md`
3. Check web server routing configuration

**Related**: `docs/API_REFERENCE.md`

---

## AGENT_LOGS_ISSUES

### Issue: "Cannot write to log file"

**Problem**: `writeAgentLog()` fails with permission error.

**Cause**: File permissions or directory doesn't exist.

**Solution**:
1. Check `public/logs/` directory exists and is writable:
   ```bash
   chmod 755 public/logs/
   ```
2. Verify file permissions:
   ```bash
   chmod 644 public/logs/*.md
   ```
3. Check PHP can write to directory

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

### Issue: "Log file missing YAML frontmatter"

**Problem**: Validation error: "Missing YAML frontmatter".

**Cause**: Log file doesn't have WOLFIE Headers format.

**Solution**:
1. Add YAML frontmatter to log file:
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
   tags: [LOG, AGENT_LOG]
   collections: [LOG_ENTRIES]
   log_entry_count: 0
   last_log_date: 2025-11-18
   ---
   ```
2. Or use `initializeAgentLog()` to create properly formatted file

**Related**: `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md#LOG_FILE_SYSTEM`

---

### Issue: "Database sync not working"

**Problem**: Log file updates but `content_log` table not updated.

**Cause**: Database connection issue or sync function not called.

**Solution**:
1. Check database connection works
2. Verify `writeAgentLog()` is being called (it syncs automatically)
3. Check `content_log` table exists (Migration 1078)
4. Verify table structure matches expected schema

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOG_TABLE`

---

## DATABASE_LOGS_ISSUES

### Issue: "Cannot discover _logs tables"

**Problem**: `discoverLogsTables()` returns empty array.

**Cause**: No `_logs` tables exist or database connection issue.

**Solution**:
1. Check database connection
2. Verify `_logs` tables exist (e.g., `content_logs`)
3. Run Migration 1079 to create `content_logs` table:
   ```sql
   -- See database/migrations/1079_2025_11_18_create_content_logs_table.sql
   ```
4. Check table naming: Must end with `_logs` (plural)

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`

---

### Issue: "Cannot write change log"

**Problem**: `writeChangeLog()` fails with error.

**Cause**: Table doesn't exist, invalid parameters, or database error.

**Solution**:
1. Verify `_logs` table exists for parent table:
   ```php
   $tables = discoverLogsTables();
   // Check if 'content_logs' exists
   ```
2. Validate parameters:
   - `$parentTable`: Must be valid table name
   - `$rowId`: Must be > 0
   - `$agentId`: Must be 0-999
   - `$channelId`: Must be 0-999
3. Check table structure matches standard schema

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`, `public/examples/example_write_change_log.php`

---

### Issue: "Change logs not showing"

**Problem**: `readChangeLogs()` returns empty array.

**Cause**: No change logs exist for that row or wrong parameters.

**Solution**:
1. Verify change logs exist:
   ```php
   $summary = getChangeSummary('content', 123);
   // Check if summary shows changes
   ```
2. Check parameters:
   - `$parentTable`: Correct table name
   - `$rowId`: Valid row ID
3. Verify `_logs` table has data:
   ```sql
   SELECT * FROM content_logs WHERE content_id = 123;
   ```

**Related**: `docs/DATABASE_INTEGRATION.md#CONTENT_LOGS_TABLE`, `public/examples/example_read_change_logs.php`

---

## API_ISSUES

### Issue: "API returns validation error"

**Problem**: API returns "VALIDATION_ERROR" with details.

**Cause**: Invalid input parameters.

**Solution**:
1. Check error response for details:
   ```json
   {
     "error": {
       "code": "VALIDATION_ERROR",
       "details": {"channel_id": "Must be between 0 and 999"},
       "suggestion": "Use a channel ID between 000 and 999"
     }
   }
   ```
2. Follow the suggestion in error response
3. Verify parameter format matches API requirements

**Related**: `docs/API_REFERENCE.md`

---

### Issue: "API endpoint inconsistent"

**Problem**: Some endpoints work, others don't.

**Cause**: Using old endpoint patterns (pre-v2.1.0).

**Solution**:
1. Use v2.1.0 standardized endpoints:
   - ✅ `GET /api/wolfie/logs/agents/{agent_name}` (new)
   - ❌ `GET /api/logs/agent/{agent_name}` (old)
2. Check `docs/API_REFERENCE.md` for current endpoints
3. Update your code to use new patterns

**Related**: `docs/API_REFERENCE.md`, `TODO_2.1.0.md`

---

## CONFIGURATION_ISSUES

### Issue: "Configuration file not found"

**Problem**: "Cannot find config/database.php" error.

**Cause**: Configuration files missing or wrong path.

**Solution**:
1. Verify configuration files exist:
   - `public/config/database.php`
   - `public/config/system.php`
2. Check file paths in your code:
   ```php
   require_once __DIR__ . '/config/database.php';
   ```
3. Copy from `GITHUB_LUPOPEDIA/WOLFIE_HEADERS/public/config/` if missing

**Related**: `docs/INSTALLATION.md`, `public/config/`

---

### Issue: "Platform detection wrong"

**Problem**: `WOLFIE_IS_WINDOWS` or `WOLFIE_IS_LINUX` incorrect.

**Cause**: System detection issue.

**Solution**:
1. Check `public/config/system.php`:
   ```php
   define('WOLFIE_IS_WINDOWS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
   define('WOLFIE_IS_LINUX', !WOLFIE_IS_WINDOWS);
   ```
2. Manually set if needed:
   ```php
   define('WOLFIE_IS_WINDOWS', true); // or false
   ```

**Related**: `public/config/system.php`

---

## SOLUTIONS

### Quick Fixes

**Problem**: Log file corrupted or invalid format.

**Fix**: Reinitialize log file:
```php
require_once 'includes/wolfie_log_system.php';
initializeAgentLog(7, 7, 'CAPTAIN');
```

**Problem**: Database table missing.

**Fix**: Run migrations:
```sql
-- Migration 1078: content_log table
-- Migration 1079: content_logs table
```

**Problem**: API not responding.

**Fix**: Check web server configuration and file paths.

---

## GETTING_HELP

**Documentation**:
- `docs/QUICK_START_CHOOSE_YOUR_PATH.md` - Getting started
- `docs/API_REFERENCE.md` - API documentation
- `docs/WOLFIE_HEADER_SYSTEM_OVERVIEW.md` - System overview

**Examples**:
- `public/examples/` - Code examples
- `public/examples/example_write_change_log.php` - Database logs example
- `public/examples/example_api_usage.html` - API examples

**Support**:
- Check `README.md` for overview
- See `CHANGELOG.md` for version history
- Review `TODO_2.1.0.md` for known issues

---

**Created**: 2025-11-18  
**Version**: v2.1.0  
**Author**: Captain WOLFIE (Agent 008) with MAAT's user experience improvements

