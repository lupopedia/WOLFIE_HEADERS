<?php
/**
 * Example: Discover _logs Tables (v2.0.8)
 * 
 * WHO: Captain WOLFIE (Agent 008) with MAAT's balance review (Agent 009)
 * WHAT: Example demonstrating how to discover all _logs tables in the database
 * WHERE: public/examples/example_discover_logs_tables.php
 * WHEN: 2025-11-18
 * WHY: Demonstrate v2.0.8 auto-discovery functionality (shared hosting compatible - uses SHOW TABLES)
 * HOW: Use discoverLogsTables() function
 */

// Load configuration files first
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

// Load database logs system
require_once __DIR__ . '/../includes/wolfie_database_logs_system.php';

// Discover all _logs tables
$tables = discoverLogsTables();

echo "🔍 Discovered _logs Tables:\n\n";

if (empty($tables)) {
    echo "No _logs tables found in database.\n";
    echo "Run migration 1079 to create content_logs table.\n";
} else {
    foreach ($tables as $tableName => $tableInfo) {
        echo "📋 Table: {$tableInfo['table_name']}\n";
        echo "   Parent Table: {$tableInfo['parent_table']}\n";
        echo "   Parent ID Column: {$tableInfo['parent_id_column']}\n";
        echo "   Row Count: {$tableInfo['row_count']}\n";
        echo "   Last Change: " . ($tableInfo['last_change'] ?? 'N/A') . "\n";
        echo "\n";
    }
    
    echo "✅ Total Tables: " . count($tables) . "\n";
}

// Validate a specific table
if (!empty($tables)) {
    $firstTable = array_key_first($tables);
    echo "\n🔍 Validating table: {$firstTable}\n";
    
    $validation = validateLogsTable($firstTable);
    
    if ($validation['valid']) {
        echo "✅ Table structure is valid.\n";
    } else {
        echo "❌ Validation errors:\n";
        foreach ($validation['errors'] as $error) {
            echo "   - {$error}\n";
        }
    }
}

?>

