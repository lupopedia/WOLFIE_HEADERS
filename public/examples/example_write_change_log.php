<?php
/**
 * Example: Write Change Log Entry (v2.0.8)
 * 
 * WHO: Captain WOLFIE (Agent 008) with MAAT's balance review (Agent 009)
 * WHAT: Example demonstrating how to write a change log entry to content_logs table
 * WHERE: public/examples/example_write_change_log.php
 * WHEN: 2025-11-18
 * WHY: Demonstrate v2.0.8 database _logs table functionality (shared hosting compatible)
 * HOW: Use writeChangeLog() function to track row-level changes
 */

// Load configuration files first
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

// Load database logs system
require_once __DIR__ . '/../includes/wolfie_database_logs_system.php';

// Example: Track a change to content row 123
$logId = writeChangeLog(
    'content',                    // Parent table name
    123,                          // Row ID being changed
    8,                            // Agent ID (WOLFIE)
    'WOLFIE',                     // Agent name
    7,                            // Channel ID (007 - CAPTAIN's channel)
    [
        'change_type' => 'update',
        'changed_fields' => ['title', 'status'],
        'old_values' => [
            'title' => 'Old Title',
            'status' => 'draft'
        ],
        'new_values' => [
            'title' => 'New Title',
            'status' => 'published'
        ]
    ],
    [
        'change_reason' => 'User requested title update and publication',
        'change_summary' => 'Updated title and published content',
        'related_ids' => [456, 789]  // Optional: related content IDs
    ]
);

if ($logId !== false) {
    echo "✅ Change log entry created successfully!\n";
    echo "Log ID: {$logId}\n";
    echo "Table: content_logs\n";
    echo "Row ID: 123\n";
    echo "Agent: WOLFIE (8)\n";
    echo "Channel: 7\n";
} else {
    echo "❌ Failed to create change log entry.\n";
    echo "Check error logs for details.\n";
}

?>

