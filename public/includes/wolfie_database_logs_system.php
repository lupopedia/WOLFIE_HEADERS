<?php
/**
 * WOLFIE Headers Database _logs Table System - v2.0.8
 * 
 * WHO: Captain WOLFIE (Agent 008) with MAAT's balance review (Agent 009)
 * WHAT: Functions for row-level change tracking using database _logs tables
 * WHERE: public/includes/wolfie_database_logs_system.php
 * WHEN: 2025-11-18
 * WHY: Enable row-level change tracking for database records (complement to directory-level markdown logs)
 * HOW: Auto-discover _logs tables using SHOW TABLES and DESCRIBE (shared hosting compatible)
 * 
 * Version: 2.0.8 - Shared hosting compatibility
 */

// Load configuration files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

// Cache for discovered _logs tables
$GLOBALS['wolfie_logs_tables_cache'] = null;

/**
 * Discover all _logs tables in the database
 * 
 * @param bool $forceRefresh Force cache refresh
 * @return array Discovered _logs tables with metadata
 */
function discoverLogsTables($forceRefresh = false) {
    global $wolfie_logs_tables_cache;
    
    if (!$forceRefresh && $wolfie_logs_tables_cache !== null) {
        return $wolfie_logs_tables_cache;
    }
    
    try {
        $db = getWOLFIEDatabaseConnection();
        $tables = [];
        
        // Find all tables ending with _logs using SHOW TABLES (shared hosting compatible)
        // SHOW TABLES returns a single column, column name varies by database
        $stmt = $db->query("SHOW TABLES");
        $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Filter tables ending with _logs
        $logsTables = array_filter($allTables, function($table) {
            return preg_match('/_logs$/', $table);
        });
        
        // Sort alphabetically
        sort($logsTables);
        
        foreach ($logsTables as $tableName) {
            // Extract parent table name (e.g., content_logs -> content)
            $parentTable = preg_replace('/_logs$/', '', $tableName);
            
            // Get table structure using DESCRIBE (shared hosting compatible)
            $stmt = $db->query("DESCRIBE `{$tableName}`");
            $describeColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Map DESCRIBE output to expected format
            $columns = [];
            foreach ($describeColumns as $col) {
                $columns[] = [
                    'COLUMN_NAME' => $col['Field'],
                    'COLUMN_TYPE' => $col['Type'],
                    'IS_NULLABLE' => $col['Null'] === 'YES' ? 'YES' : 'NO',
                    'COLUMN_DEFAULT' => $col['Default'],
                    'COLUMN_COMMENT' => '' // DESCRIBE doesn't include comments
                ];
            }
            
            // Find parent_id column (e.g., content_id, user_id)
            $parentIdColumn = null;
            foreach ($columns as $col) {
                $columnName = $col['COLUMN_NAME'];
                if (preg_match('/^' . preg_quote($parentTable, '/') . '_id$/', $columnName)) {
                    $parentIdColumn = $columnName;
                    break;
                }
            }
            
            // Get row count and last change
            $rowCount = 0;
            $lastChange = null;
            try {
                $stmt = $db->query("SELECT COUNT(*) as cnt, MAX(created_at) as last_change FROM `" . $tableName . "` WHERE is_active = 1 AND deleted_at IS NULL");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $rowCount = intval($result['cnt']);
                $lastChange = $result['last_change'];
            } catch (Exception $e) {
                // Table might not have data yet
            }
            
            $tables[$tableName] = [
                'table_name' => $tableName,
                'parent_table' => $parentTable,
                'parent_id_column' => $parentIdColumn,
                'columns' => $columns,
                'row_count' => $rowCount,
                'last_change' => $lastChange
            ];
        }
        
        $wolfie_logs_tables_cache = $tables;
        return $tables;
        
    } catch (Exception $e) {
        error_log("Error discovering _logs tables: " . $e->getMessage());
        return [];
    }
}

/**
 * Validate _logs table structure
 * 
 * @param string $tableName _logs table name
 * @return array Validation result with errors if any
 */
function validateLogsTable($tableName) {
    $errors = [];
    
    try {
        $db = getWOLFIEDatabaseConnection();
        
        // Check if table exists using SHOW TABLES (shared hosting compatible)
        try {
            $stmt = $db->query("SHOW TABLES LIKE " . $db->quote($tableName));
            $exists = $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $exists = false;
        }
        
        if (!$exists) {
            $errors[] = "Table '{$tableName}' does not exist";
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Get required columns using DESCRIBE (shared hosting compatible)
        $requiredColumns = ['id', 'agent_id', 'agent_name', 'channel_id', 'metadata', 'is_active', 'created_at', 'updated_at'];
        $stmt = $db->query("DESCRIBE `{$tableName}`");
        $describeColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $existingColumns = array_column($describeColumns, 'Field');
        
        foreach ($requiredColumns as $required) {
            if (!in_array($required, $existingColumns)) {
                $errors[] = "Required column '{$required}' is missing";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'columns' => $existingColumns
        ];
        
    } catch (Exception $e) {
        $errors[] = "Validation error: " . $e->getMessage();
        return ['valid' => false, 'errors' => $errors];
    }
}

/**
 * Write change log entry to _logs table
 * 
 * @param string $tableName Parent table name (e.g., "content")
 * @param int $rowId Row ID being changed
 * @param int $agentId Agent ID making the change
 * @param string $agentName Agent name making the change
 * @param int $channelId Channel ID where change occurred
 * @param array $changeData Change data (change_type, changed_fields, old_values, new_values)
 * @param array $metadata Additional metadata (change_reason, change_summary, related_ids)
 * @return int|false Log entry ID or false on error
 */
function writeChangeLog($tableName, $rowId, $agentId, $agentName, $channelId, $changeData, $metadata = []) {
    try {
        $db = getWOLFIEDatabaseConnection();
        
        // Discover _logs table
        $logsTableName = $tableName . '_logs';
        $discovered = discoverLogsTables();
        
        if (!isset($discovered[$logsTableName])) {
            error_log("_logs table '{$logsTableName}' not found. Run migration 1079 or create table first.");
            return false;
        }
        
        $tableInfo = $discovered[$logsTableName];
        $parentIdColumn = $tableInfo['parent_id_column'];
        
        if (!$parentIdColumn) {
            error_log("Could not determine parent ID column for table '{$logsTableName}'");
            return false;
        }
        
        // Validate channel_id range
        $channelId = intval($channelId);
        if ($channelId < 0 || $channelId > 999) {
            error_log("Invalid channel_id: {$channelId}. Must be 0-999.");
            return false;
        }
        
        // Build metadata JSON
        $metadataJson = array_merge([
            'change_type' => isset($changeData['change_type']) ? $changeData['change_type'] : 'update',
            'changed_fields' => isset($changeData['changed_fields']) ? $changeData['changed_fields'] : [],
            'old_values' => isset($changeData['old_values']) ? $changeData['old_values'] : [],
            'new_values' => isset($changeData['new_values']) ? $changeData['new_values'] : []
        ], $metadata);
        
        $metadataJsonString = json_encode($metadataJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        // Validate JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Invalid JSON metadata: " . json_last_error_msg());
            return false;
        }
        
        // Insert change log entry
        $sql = "INSERT INTO `{$logsTableName}` (
            `{$parentIdColumn}`,
            `agent_id`,
            `agent_name`,
            `channel_id`,
            `metadata`,
            `is_active`,
            `created_at`,
            `updated_at`
        ) VALUES (
            :row_id,
            :agent_id,
            :agent_name,
            :channel_id,
            :metadata,
            1,
            NOW(),
            NOW()
        )";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':row_id' => intval($rowId),
            ':agent_id' => intval($agentId),
            ':agent_name' => strtoupper(trim($agentName)),
            ':channel_id' => $channelId,
            ':metadata' => $metadataJsonString
        ]);
        
        // Invalidate cache
        global $wolfie_logs_tables_cache;
        $wolfie_logs_tables_cache = null;
        
        return $db->lastInsertId();
        
    } catch (Exception $e) {
        error_log("Error writing change log: " . $e->getMessage());
        return false;
    }
}

/**
 * Read change logs for a specific database row
 * 
 * @param string $tableName Parent table name
 * @param int $rowId Row ID
 * @param array $options Query options (limit, offset, agent_id, channel_id, date_from, date_to)
 * @return array Change log entries
 */
function readChangeLogs($tableName, $rowId, $options = []) {
    try {
        $db = getWOLFIEDatabaseConnection();
        
        // Discover _logs table
        $logsTableName = $tableName . '_logs';
        $discovered = discoverLogsTables();
        
        if (!isset($discovered[$logsTableName])) {
            return [];
        }
        
        $tableInfo = $discovered[$logsTableName];
        $parentIdColumn = $tableInfo['parent_id_column'];
        
        if (!$parentIdColumn) {
            return [];
        }
        
        // Build query
        $where = ["`{$parentIdColumn}` = :row_id", "`is_active` = 1", "`deleted_at` IS NULL"];
        $params = [':row_id' => intval($rowId)];
        
        // Apply filters
        if (isset($options['agent_id'])) {
            $where[] = "`agent_id` = :agent_id";
            $params[':agent_id'] = intval($options['agent_id']);
        }
        
        if (isset($options['channel_id'])) {
            $where[] = "`channel_id` = :channel_id";
            $params[':channel_id'] = intval($options['channel_id']);
        }
        
        if (isset($options['date_from'])) {
            $where[] = "`created_at` >= :date_from";
            $params[':date_from'] = $options['date_from'];
        }
        
        if (isset($options['date_to'])) {
            $where[] = "`created_at` <= :date_to";
            $params[':date_to'] = $options['date_to'];
        }
        
        $limit = isset($options['limit']) ? intval($options['limit']) : 50;
        $offset = isset($options['offset']) ? intval($options['offset']) : 0;
        
        $sql = "SELECT * FROM `{$logsTableName}`
                WHERE " . implode(' AND ', $where) . "
                ORDER BY `created_at` DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse metadata JSON
        foreach ($logs as &$log) {
            if (!empty($log['metadata'])) {
                $log['metadata_parsed'] = json_decode($log['metadata'], true);
            }
        }
        
        return $logs;
        
    } catch (Exception $e) {
        error_log("Error reading change logs: " . $e->getMessage());
        return [];
    }
}

/**
 * List all change logs for a table (across all rows)
 * 
 * @param string $tableName Parent table name
 * @param array $options Query options (limit, offset, agent_id, channel_id, date_from, date_to, row_id)
 * @return array Change log entries
 */
function listChangeLogs($tableName, $options = []) {
    try {
        $db = getWOLFIEDatabaseConnection();
        
        // Discover _logs table
        $logsTableName = $tableName . '_logs';
        $discovered = discoverLogsTables();
        
        if (!isset($discovered[$logsTableName])) {
            return [];
        }
        
        $tableInfo = $discovered[$logsTableName];
        $parentIdColumn = $tableInfo['parent_id_column'];
        
        if (!$parentIdColumn) {
            return [];
        }
        
        // Build query
        $where = ["`is_active` = 1", "`deleted_at` IS NULL"];
        $params = [];
        
        // Apply filters
        if (isset($options['row_id'])) {
            $where[] = "`{$parentIdColumn}` = :row_id";
            $params[':row_id'] = intval($options['row_id']);
        }
        
        if (isset($options['agent_id'])) {
            $where[] = "`agent_id` = :agent_id";
            $params[':agent_id'] = intval($options['agent_id']);
        }
        
        if (isset($options['channel_id'])) {
            $where[] = "`channel_id` = :channel_id";
            $params[':channel_id'] = intval($options['channel_id']);
        }
        
        if (isset($options['date_from'])) {
            $where[] = "`created_at` >= :date_from";
            $params[':date_from'] = $options['date_from'];
        }
        
        if (isset($options['date_to'])) {
            $where[] = "`created_at` <= :date_to";
            $params[':date_to'] = $options['date_to'];
        }
        
        $limit = isset($options['limit']) ? intval($options['limit']) : 50;
        $offset = isset($options['offset']) ? intval($options['offset']) : 0;
        
        $sql = "SELECT * FROM `{$logsTableName}`
                WHERE " . implode(' AND ', $where) . "
                ORDER BY `created_at` DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse metadata JSON
        foreach ($logs as &$log) {
            if (!empty($log['metadata'])) {
                $log['metadata_parsed'] = json_decode($log['metadata'], true);
            }
        }
        
        return $logs;
        
    } catch (Exception $e) {
        error_log("Error listing change logs: " . $e->getMessage());
        return [];
    }
}

/**
 * Get change summary for a specific row
 * 
 * @param string $tableName Parent table name
 * @param int $rowId Row ID
 * @return array Change summary statistics
 */
function getChangeSummary($tableName, $rowId) {
    try {
        $db = getWOLFIEDatabaseConnection();
        
        // Discover _logs table
        $logsTableName = $tableName . '_logs';
        $discovered = discoverLogsTables();
        
        if (!isset($discovered[$logsTableName])) {
            return [
                'total_changes' => 0,
                'first_change' => null,
                'last_change' => null,
                'agents_involved' => [],
                'change_types' => []
            ];
        }
        
        $tableInfo = $discovered[$logsTableName];
        $parentIdColumn = $tableInfo['parent_id_column'];
        
        if (!$parentIdColumn) {
            return [
                'total_changes' => 0,
                'first_change' => null,
                'last_change' => null,
                'agents_involved' => [],
                'change_types' => []
            ];
        }
        
        // Get summary statistics
        $sql = "SELECT 
                    COUNT(*) as total_changes,
                    MIN(created_at) as first_change,
                    MAX(created_at) as last_change,
                    GROUP_CONCAT(DISTINCT agent_name) as agents,
                    GROUP_CONCAT(DISTINCT JSON_EXTRACT(metadata, '$.change_type')) as change_types
                FROM `{$logsTableName}`
                WHERE `{$parentIdColumn}` = :row_id
                  AND `is_active` = 1
                  AND `deleted_at` IS NULL";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':row_id' => intval($rowId)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $agents = !empty($result['agents']) ? explode(',', $result['agents']) : [];
        $changeTypes = !empty($result['change_types']) ? json_decode('[' . $result['change_types'] . ']', true) : [];
        
        return [
            'total_changes' => intval($result['total_changes']),
            'first_change' => $result['first_change'],
            'last_change' => $result['last_change'],
            'agents_involved' => array_map('trim', $agents),
            'change_types' => array_filter($changeTypes)
        ];
        
    } catch (Exception $e) {
        error_log("Error getting change summary: " . $e->getMessage());
        return [
            'total_changes' => 0,
            'first_change' => null,
            'last_change' => null,
            'agents_involved' => [],
            'change_types' => []
        ];
    }
}

