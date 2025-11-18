---
title: TODO_2.0.8.md
agent_username: wolfie
agent_id: 008
channel_number: 001
version: 2.0.8
date_created: 2025-11-18
last_modified: 2025-11-18
status: planning
onchannel: 1
tags: [SYSTEM, TODO, PLANNING, SHARED_HOSTING, COMPATIBILITY]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, DO, HACK, OTHER]
in_this_file_we_have: [OVERVIEW, SHARED_HOSTING_COMPATIBILITY, CONFIGURATION_SYSTEM, DATABASE_CONNECTION, TABLE_DISCOVERY, MIGRATION_PLAN, IMPLEMENTATION_CHECKLIST]
superpositionally: ["FILEID_TODO_2.0.8"]
shadow_aliases: []
parallel_paths: []
---

# WOLFIE Headers v2.0.8 - Shared Hosting Compatibility & Self-Contained Configuration

## OVERVIEW

**Version**: 2.0.8  
**Status**: Planning  
**Target Release**: TBD  
**Priority**: High (Shared Hosting Compatibility)

**Goal**: Make WOLFIE Headers fully self-contained and compatible with shared hosting environments where `information_schema` may not be accessible. Replace `information_schema` queries with `SHOW TABLES` and `DESCRIBE` commands, and centralize configuration in `public/config/`.

---

## WHY_THIS_VERSION

### Problem Statement

**Current Issue (v2.0.7)**:
- Uses `information_schema.TABLES` and `information_schema.COLUMNS` for table discovery
- Many shared hosting providers restrict access to `information_schema`
- Database connection location is inconsistent
- Version information is scattered across files
- No centralized system configuration

**Solution (v2.0.8)**:
- Replace `information_schema` queries with `SHOW TABLES` and `DESCRIBE` commands
- Centralize database connection in `public/config/database.php`
- Centralize system configuration in `public/config/system.php`
- Make entire system self-contained in `public/` folder
- Add platform detection (Windows/Linux) and development flags

---

## SHARED_HOSTING_COMPATIBILITY

### Current Implementation (v2.0.7)

**File**: `public/includes/wolfie_database_logs_system.php`

**Current Code** (Lines 40-50):
```php
$stmt = $db->query("
    SELECT TABLE_NAME
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME LIKE '%_logs'
    ORDER BY TABLE_NAME
");
```

**Problem**: `information_schema` may not be accessible on shared hosting.

### New Implementation (v2.0.8)

**Replace with**:
```php
// Use SHOW TABLES instead of information_schema
$stmt = $db->query("SHOW TABLES LIKE '%_logs'");
$logsTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
```

**For column discovery**:
```php
// Use DESCRIBE instead of information_schema.COLUMNS
$stmt = $db->query("DESCRIBE `{$tableName}`");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### Benefits

1. **Shared Hosting Compatible**: `SHOW TABLES` and `DESCRIBE` work on all MySQL/MariaDB installations
2. **No Privilege Issues**: Doesn't require `information_schema` access
3. **Simpler Queries**: More straightforward SQL commands
4. **Better Performance**: Direct table queries are often faster

---

## CONFIGURATION_SYSTEM

### New Configuration Files

#### 1. `public/config/database.php`

**Purpose**: Centralize database connection configuration

**Structure**:
```php
<?php
/**
 * WOLFIE Headers Database Configuration
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: Database connection configuration
 * WHERE: public/config/database.php
 * WHEN: 2025-11-18
 * WHY: Centralize database connection for shared hosting compatibility
 * HOW: PDO connection with error handling
 */

// Database configuration
define('WOLFIE_DB_HOST', 'localhost');
define('WOLFIE_DB_NAME', 'lupopedia');
define('WOLFIE_DB_USER', 'root');
define('WOLFIE_DB_PASS', '');
define('WOLFIE_DB_CHARSET', 'utf8mb4');

/**
 * Get database connection
 * 
 * @return PDO Database connection
 * @throws Exception If connection fails
 */
function getWOLFIEDatabaseConnection() {
    static $connection = null;
    
    if ($connection === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                WOLFIE_DB_HOST,
                WOLFIE_DB_NAME,
                WOLFIE_DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $connection = new PDO($dsn, WOLFIE_DB_USER, WOLFIE_DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("WOLFIE Headers Database Connection Failed: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $connection;
}
```

#### 2. `public/config/system.php`

**Purpose**: Centralize system configuration (version, platform, flags)

**Structure**:
```php
<?php
/**
 * WOLFIE Headers System Configuration
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: System configuration (version, platform, flags)
 * WHERE: public/config/system.php
 * WHEN: 2025-11-18
 * WHY: Centralize system configuration for consistency
 * HOW: Defines version, platform detection, development flags
 */

// Version Information
define('WOLFIE_HEADERS_VERSION', '2.0.8');
define('WOLFIE_HEADERS_VERSION_MAJOR', 2);
define('WOLFIE_HEADERS_VERSION_MINOR', 0);
define('WOLFIE_HEADERS_VERSION_PATCH', 8);

// Platform Detection
define('WOLFIE_IS_WINDOWS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
define('WOLFIE_IS_LINUX', !WOLFIE_IS_WINDOWS && strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX');
define('WOLFIE_IS_UNIX', !WOLFIE_IS_WINDOWS);

// Development Flags
define('WOLFIE_BORN_YESTERDAY', false); // Set to true for fresh installations
define('WOLFIE_DEBUG_MODE', false); // Set to true for debugging
define('WOLFIE_SHARED_HOSTING', true); // Set to true if on shared hosting

// Path Configuration
define('WOLFIE_PUBLIC_DIR', __DIR__ . '/..');
define('WOLFIE_LOGS_DIR', WOLFIE_PUBLIC_DIR . '/logs');
define('WOLFIE_INCLUDES_DIR', WOLFIE_PUBLIC_DIR . '/includes');
define('WOLFIE_CONFIG_DIR', __DIR__);

/**
 * Get WOLFIE Headers version
 * 
 * @return string Version string
 */
function getWOLFIEHeadersVersion() {
    return WOLFIE_HEADERS_VERSION;
}

/**
 * Check if running on Windows
 * 
 * @return bool True if Windows
 */
function isWOLFIEWindows() {
    return WOLFIE_IS_WINDOWS;
}

/**
 * Check if running on Linux/Unix
 * 
 * @return bool True if Linux/Unix
 */
function isWOLFIELinux() {
    return WOLFIE_IS_LINUX;
}

/**
 * Check if "born yesterday" (fresh installation)
 * 
 * @return bool True if fresh installation
 */
function isWOLFIEBornYesterday() {
    return WOLFIE_BORN_YESTERDAY;
}

/**
 * Check if debug mode enabled
 * 
 * @return bool True if debug mode
 */
function isWOLFIEDebugMode() {
    return WOLFIE_DEBUG_MODE;
}

/**
 * Check if on shared hosting
 * 
 * @return bool True if shared hosting
 */
function isWOLFIESharedHosting() {
    return WOLFIE_SHARED_HOSTING;
}
```

---

## DATABASE_CONNECTION

### Migration from Current System

**Current**: Various files use different database connection methods
- `public/includes/wolfie_log_system.php` uses `getDatabaseConnection()`
- `public/includes/wolfie_database_logs_system.php` uses `getDatabaseConnection()`
- `public/includes/wolfie_api_core.php` may use different methods

**New**: All files use `getWOLFIEDatabaseConnection()` from `public/config/database.php`

### Update Required Files

1. **`public/includes/wolfie_database_logs_system.php`**
   - Replace `getDatabaseConnection()` with `getWOLFIEDatabaseConnection()`
   - Add `require_once __DIR__ . '/../config/database.php';`
   - Replace `information_schema` queries with `SHOW TABLES` and `DESCRIBE`

2. **`public/includes/wolfie_log_system.php`**
   - Replace `getDatabaseConnection()` with `getWOLFIEDatabaseConnection()`
   - Add `require_once __DIR__ . '/../config/database.php';`

3. **`public/includes/wolfie_api_core.php`**
   - Replace any database connection calls with `getWOLFIEDatabaseConnection()`
   - Add `require_once __DIR__ . '/../config/database.php';`

4. **`public/api/index.php`**
   - Ensure `public/config/database.php` is loaded
   - Ensure `public/config/system.php` is loaded

---

## TABLE_DISCOVERY

### Replace `information_schema` Queries

#### 1. Discover `_logs` Tables

**Current (v2.0.7)**:
```php
$stmt = $db->query("
    SELECT TABLE_NAME
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME LIKE '%_logs'
    ORDER BY TABLE_NAME
");
$logsTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
```

**New (v2.0.8)**:
```php
// Use SHOW TABLES instead
$stmt = $db->query("SHOW TABLES LIKE '%_logs'");
$logsTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

// If using PDO with default fetch mode, may need to adjust:
// For MySQL, SHOW TABLES returns single column, column name varies by database name
// Better approach:
$stmt = $db->query("SHOW TABLES");
$allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$logsTables = array_filter($allTables, function($table) {
    return preg_match('/_logs$/', $table);
});
```

#### 2. Get Table Columns

**Current (v2.0.7)**:
```php
$stmt = $db->query("
    SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_COMMENT
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = " . $db->quote($tableName) . "
    ORDER BY ORDINAL_POSITION
");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

**New (v2.0.8)**:
```php
// Use DESCRIBE instead
$stmt = $db->query("DESCRIBE `{$tableName}`");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DESCRIBE returns: Field, Type, Null, Key, Default, Extra
// Map to expected format:
$columnInfo = [];
foreach ($columns as $col) {
    $columnInfo[] = [
        'COLUMN_NAME' => $col['Field'],
        'COLUMN_TYPE' => $col['Type'],
        'IS_NULLABLE' => $col['Null'] === 'YES' ? 'YES' : 'NO',
        'COLUMN_DEFAULT' => $col['Default'],
        'COLUMN_COMMENT' => '' // DESCRIBE doesn't include comments, use SHOW FULL COLUMNS if needed
    ];
}
```

#### 3. Get Table Existence Check

**Current (v2.0.7)**:
```php
$stmt = $db->query("
    SELECT COUNT(*) as cnt
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = " . $db->quote($tableName) . "
");
```

**New (v2.0.8)**:
```php
// Use SHOW TABLES with specific table name
try {
    $stmt = $db->query("SHOW TABLES LIKE " . $db->quote($tableName));
    $exists = $stmt->rowCount() > 0;
} catch (PDOException $e) {
    $exists = false;
}
```

---

## IMPLEMENTATION_CHECKLIST

### Phase 1: Configuration Files

- [ ] Create `public/config/database.php`
  - [ ] Define database connection constants
  - [ ] Implement `getWOLFIEDatabaseConnection()` function
  - [ ] Add error handling and logging
  - [ ] Add connection pooling (static variable)

- [ ] Create `public/config/system.php`
  - [ ] Define version constants (2.0.8)
  - [ ] Implement platform detection (Windows/Linux)
  - [ ] Add `WOLFIE_BORN_YESTERDAY` flag
  - [ ] Add `WOLFIE_DEBUG_MODE` flag
  - [ ] Add `WOLFIE_SHARED_HOSTING` flag
  - [ ] Define path constants
  - [ ] Implement helper functions (getVersion, isWindows, isLinux, isBornYesterday, etc.)

### Phase 2: Database Connection Migration

- [ ] Update `public/includes/wolfie_database_logs_system.php`
  - [ ] Replace `getDatabaseConnection()` with `getWOLFIEDatabaseConnection()`
  - [ ] Add `require_once __DIR__ . '/../config/database.php';`
  - [ ] Test database connection

- [ ] Update `public/includes/wolfie_log_system.php`
  - [ ] Replace `getDatabaseConnection()` with `getWOLFIEDatabaseConnection()`
  - [ ] Add `require_once __DIR__ . '/../config/database.php';`
  - [ ] Test database connection

- [ ] Update `public/includes/wolfie_api_core.php`
  - [ ] Replace any database connection calls
  - [ ] Add `require_once __DIR__ . '/../config/database.php';`
  - [ ] Test database connection

- [ ] Update `public/api/index.php`
  - [ ] Ensure config files are loaded
  - [ ] Test API endpoints

### Phase 3: Replace `information_schema` Queries

- [ ] Update `discoverLogsTables()` function
  - [ ] Replace `information_schema.TABLES` with `SHOW TABLES`
  - [ ] Filter tables ending with `_logs`
  - [ ] Test table discovery

- [ ] Update column discovery
  - [ ] Replace `information_schema.COLUMNS` with `DESCRIBE`
  - [ ] Map DESCRIBE output to expected format
  - [ ] Handle column comments (use `SHOW FULL COLUMNS` if needed)
  - [ ] Test column discovery

- [ ] Update table existence checks
  - [ ] Replace `information_schema.TABLES` with `SHOW TABLES LIKE`
  - [ ] Test table existence checks

- [ ] Update `validateLogsTable()` function
  - [ ] Replace all `information_schema` queries
  - [ ] Use `DESCRIBE` for column validation
  - [ ] Test validation

### Phase 4: System Configuration Integration

- [ ] Update all files to use `public/config/system.php`
  - [ ] Replace hardcoded version strings with `WOLFIE_HEADERS_VERSION`
  - [ ] Use platform detection functions where needed
  - [ ] Use `WOLFIE_BORN_YESTERDAY` flag for fresh installation checks
  - [ ] Use `WOLFIE_DEBUG_MODE` for debug logging

- [ ] Update API responses
  - [ ] Include version from `system.php`
  - [ ] Include platform information if needed
  - [ ] Test API responses

### Phase 5: Testing & Validation

- [ ] Test on Windows environment
  - [ ] Database connection works
  - [ ] Table discovery works
  - [ ] Column discovery works
  - [ ] All functions work correctly

- [ ] Test on Linux/Unix environment
  - [ ] Database connection works
  - [ ] Table discovery works
  - [ ] Column discovery works
  - [ ] All functions work correctly

- [ ] Test on shared hosting environment
  - [ ] Verify `information_schema` is not used
  - [ ] Verify `SHOW TABLES` works
  - [ ] Verify `DESCRIBE` works
  - [ ] Verify all functionality works

- [ ] Test backward compatibility
  - [ ] Verify existing code still works
  - [ ] Verify API endpoints still work
  - [ ] Verify log system still works

### Phase 6: Documentation

- [ ] Update `README.md`
  - [ ] Add v2.0.8 release notes
  - [ ] Document shared hosting compatibility
  - [ ] Document configuration system
  - [ ] Document platform detection

- [ ] Update `CHANGELOG.md`
  - [ ] Add v2.0.8 entry
  - [ ] Document breaking changes (if any)
  - [ ] Document new features

- [ ] Update `docs/DATABASE_INTEGRATION.md`
  - [ ] Document new database connection method
  - [ ] Document `SHOW TABLES` and `DESCRIBE` usage
  - [ ] Document shared hosting compatibility

- [ ] Create `docs/SHARED_HOSTING_COMPATIBILITY.md`
  - [ ] Document shared hosting requirements
  - [ ] Document configuration setup
  - [ ] Document troubleshooting guide

- [ ] Update example files
  - [ ] Update examples to use new configuration
  - [ ] Add shared hosting examples

---

## MIGRATION_PLAN

### For Existing Installations

1. **Backup Current Installation**
   - Backup database
   - Backup `public/` folder

2. **Update Files**
   - Copy new `public/config/` files
   - Update existing files with new database connection
   - Update existing files to remove `information_schema` queries

3. **Test**
   - Test database connection
   - Test table discovery
   - Test all functionality

4. **Deploy**
   - Deploy to production
   - Monitor for errors

### For Fresh Installations

1. **Copy Files**
   - Copy entire `public/` folder structure
   - Configure `public/config/database.php`
   - Configure `public/config/system.php`

2. **Set Flags**
   - Set `WOLFIE_BORN_YESTERDAY = true` for fresh installations
   - Set `WOLFIE_SHARED_HOSTING = true` if on shared hosting

3. **Test**
   - Test all functionality
   - Verify shared hosting compatibility

---

## BREAKING_CHANGES

### Potential Breaking Changes

1. **Database Connection Function**
   - Old: `getDatabaseConnection()`
   - New: `getWOLFIEDatabaseConnection()`
   - **Impact**: Any external code using old function name will break
   - **Mitigation**: Provide backward compatibility wrapper if needed

2. **Configuration File Locations**
   - Old: Various locations
   - New: `public/config/database.php` and `public/config/system.php`
   - **Impact**: External code expecting old locations will break
   - **Mitigation**: Document new locations clearly

3. **Table Discovery Method**
   - Old: `information_schema` queries
   - New: `SHOW TABLES` and `DESCRIBE`
   - **Impact**: None (internal change only)
   - **Mitigation**: N/A

---

## BACKWARD_COMPATIBILITY

### Compatibility Strategy

1. **Database Connection**
   - Option 1: Keep old function name as alias
   - Option 2: Document migration path
   - **Recommendation**: Option 2 (cleaner, forces proper migration)

2. **Configuration**
   - Option 1: Support both old and new locations
   - Option 2: Require migration to new locations
   - **Recommendation**: Option 2 (cleaner, forces proper setup)

---

## TESTING_STRATEGY

### Test Environments

1. **Windows (XAMPP)**
   - Test database connection
   - Test table discovery
   - Test all functions

2. **Linux (LAMP)**
   - Test database connection
   - Test table discovery
   - Test all functions

3. **Shared Hosting (cPanel)**
   - Test without `information_schema` access
   - Test `SHOW TABLES` and `DESCRIBE`
   - Test all functionality

4. **Cloud Hosting (AWS, DigitalOcean)**
   - Test database connection
   - Test table discovery
   - Test all functions

---

## DOCUMENTATION_REQUIREMENTS

### Required Documentation

1. **Configuration Guide**
   - How to set up `database.php`
   - How to set up `system.php`
   - Platform detection explanation
   - Flag explanations

2. **Shared Hosting Guide**
   - Requirements
   - Setup instructions
   - Troubleshooting

3. **Migration Guide**
   - From v2.0.7 to v2.0.8
   - Breaking changes
   - Compatibility notes

4. **API Documentation**
   - Updated endpoints
   - Configuration requirements
   - Platform-specific notes

---

## SUCCESS_CRITERIA

### Definition of Done

- [ ] All `information_schema` queries replaced with `SHOW TABLES` and `DESCRIBE`
- [ ] Database connection centralized in `public/config/database.php`
- [ ] System configuration centralized in `public/config/system.php`
- [ ] Platform detection working (Windows/Linux)
- [ ] `WOLFIE_BORN_YESTERDAY` flag implemented
- [ ] All functions tested on Windows
- [ ] All functions tested on Linux
- [ ] All functions tested on shared hosting
- [ ] Documentation complete
- [ ] Examples updated
- [ ] Backward compatibility verified (if applicable)
- [ ] Version updated to 2.0.8
- [ ] CHANGELOG updated
- [ ] README updated

---

## NOTES

### Design Decisions

1. **Why `SHOW TABLES` instead of `information_schema`?**
   - Shared hosting compatibility
   - Simpler queries
   - Better performance
   - No privilege issues

2. **Why centralize configuration?**
   - Easier maintenance
   - Consistent configuration
   - Better organization
   - Self-contained system

3. **Why platform detection?**
   - Path handling differences (Windows vs Linux)
   - File permission differences
   - Development environment detection

4. **Why `WOLFIE_BORN_YESTERDAY` flag?**
   - Fresh installation detection
   - Setup wizard support
   - Migration detection
   - Development workflow

---

**Last Updated**: 2025-11-18  
**Status**: Planning  
**Next Steps**: Begin Phase 1 (Configuration Files)

---

© 2025 Eric Robin Gerdes / LUPOPEDIA LLC — Dual licensed under GPL v3.0 + Apache 2.0.

