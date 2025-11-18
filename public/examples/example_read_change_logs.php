<?php
/**
 * Example: Read Change Logs (v2.0.8)
 * 
 * WHO: Captain WOLFIE (Agent 008) with MAAT's balance review (Agent 009)
 * WHAT: Example demonstrating how to read change logs for a database row
 * WHERE: public/examples/example_read_change_logs.php
 * WHEN: 2025-11-18
 * WHY: Demonstrate v2.0.8 database _logs table functionality (shared hosting compatible)
 * HOW: Use readChangeLogs() and getChangeSummary() functions
 */

// Load configuration files first
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

// Load database logs system
require_once __DIR__ . '/../includes/wolfie_database_logs_system.php';

// Example: Read change logs for content row 123
$logs = readChangeLogs('content', 123, [
    'limit' => 20,
    'agent_id' => 8,              // Filter by agent (optional)
    'channel_id' => 7,             // Filter by channel (optional)
    'date_from' => '2025-11-01'    // Filter by date (optional)
]);

echo "📋 Change Logs for content row 123:\n\n";

if (empty($logs)) {
    echo "No change logs found.\n";
} else {
    foreach ($logs as $log) {
        echo "---\n";
        echo "Log ID: {$log['id']}\n";
        echo "Agent: {$log['agent_name']} (ID: {$log['agent_id']})\n";
        echo "Channel: {$log['channel_id']}\n";
        echo "Created: {$log['created_at']}\n";
        
        if (isset($log['metadata_parsed'])) {
            $meta = $log['metadata_parsed'];
            echo "Change Type: " . ($meta['change_type'] ?? 'unknown') . "\n";
            
            if (isset($meta['changed_fields'])) {
                echo "Changed Fields: " . implode(', ', $meta['changed_fields']) . "\n";
            }
            
            if (isset($meta['change_reason'])) {
                echo "Reason: {$meta['change_reason']}\n";
            }
        }
        echo "\n";
    }
}

// Get change summary
$summary = getChangeSummary('content', 123);

echo "\n📊 Change Summary:\n";
echo "Total Changes: {$summary['total_changes']}\n";
echo "First Change: " . ($summary['first_change'] ?? 'N/A') . "\n";
echo "Last Change: " . ($summary['last_change'] ?? 'N/A') . "\n";
echo "Agents Involved: " . implode(', ', $summary['agents_involved']) . "\n";
echo "Change Types: " . implode(', ', $summary['change_types']) . "\n";

?>

