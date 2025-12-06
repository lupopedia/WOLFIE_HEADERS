<?php
/**
 * WOLFIE Headers 2.9.2 - PHP Compatibility Bridge
 * 
 * Basic PHP wrapper to run JS tracker via exec().
 * Allows WOLFITH to analyze PHP-based Crafty Syntax installs.
 * 
 * Usage: php php-compat.php /path/to/folder
 * 
 * Full PHP-native migration tools in v2.9.3+
 */

$folder = isset($argv[1]) ? escapeshellarg($argv[1]) : escapeshellarg(__DIR__);
$output = [];
$returnVar = 0;

// Run JS tracker
exec("node " . __DIR__ . "/index.js scan " . $folder . " 2>&1", $output, $returnVar);

if ($returnVar !== 0) {
    echo "ERROR: Failed to run tracker\n";
    echo implode("\n", $output);
    exit(1);
}

// Parse JSON output for migration analysis
$jsonOutput = implode("\n", $output);
$data = json_decode($jsonOutput, true);

if ($data) {
    echo "✅ Tracker completed successfully\n";
    echo "   - Files tracked: " . count($data['files']) . "\n";
    echo "   - Collections: " . count($data['collections']) . "\n";
    echo "   - Tags: " . count($data['tags']) . "\n";
    echo "   - Channels: " . count($data['channels']) . "\n";
    echo "   - Files with Counting in Light: " . count($data['lightCounts']) . "\n";
    echo "   - Invalid files: " . count($data['invalidFiles']) . "\n";
    
    // Stub: Detect Crafty Syntax customizations by scanning MD files
    // Expand in v2.9.3 for full migration analysis
    if (count($data['invalidFiles']) > 0) {
        echo "\n⚠️  Invalid files found (may need migration):\n";
        foreach ($data['invalidFiles'] as $invalid) {
            echo "   - " . $invalid['file'] . "\n";
        }
    }
} else {
    echo "ERROR: Failed to parse tracker output\n";
    exit(1);
}
?>

