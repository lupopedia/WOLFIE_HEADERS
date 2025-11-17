<?php
/**
 * Agent File Validation Script
 * 
 * Validates agent files follow WOLFIE Headers v2.0.2 naming convention
 * and frontmatter requirements.
 * 
 * Usage: php scripts/validate_agent_files.php
 * 
 * Checks:
 * - File naming convention (who_is_agent_[channel_id]_[agent_name].php)
 * - Channel ID range (000-999)
 * - Agent name format (lowercase)
 * - File location (public/)
 * - Frontmatter requirements (if applicable)
 */

// Configuration
$publicDir = __DIR__ . '/../../public';
$pattern = '/^who_is_agent_(\d{3})_([a-z_]+)\.php$/';
$errors = [];
$warnings = [];
$valid = [];

// Find all agent files
$files = glob($publicDir . '/who_is_agent_*.php');

if (empty($files)) {
    echo "❌ No agent files found in {$publicDir}\n";
    exit(1);
}

echo "🔍 Validating " . count($files) . " agent files...\n\n";

foreach ($files as $file) {
    $filename = basename($file);
    $filepath = $file;
    
    // Check file naming convention
    if (!preg_match($pattern, $filename, $matches)) {
        $errors[] = "❌ {$filename}: Invalid naming convention (should be: who_is_agent_[000-999]_[agent_name].php)";
        continue;
    }
    
    $channelIdStr = $matches[1];
    $agentName = $matches[2];
    $channelId = (int)$channelIdStr;
    
    // Validate channel ID range
    if ($channelId < 0 || $channelId > 999) {
        $errors[] = "❌ {$filename}: Channel ID out of range ({$channelId}, must be 000-999)";
        continue;
    }
    
    // Validate agent name format
    if (!preg_match('/^[a-z_]+$/', $agentName)) {
        $errors[] = "❌ {$filename}: Agent name must be lowercase with underscores only";
        continue;
    }
    
    // Check file location
    $relativePath = str_replace(__DIR__ . '/../../', '', $filepath);
    if (strpos($relativePath, 'public/') !== 0) {
        $warnings[] = "⚠️  {$filename}: File not in public/ directory ({$relativePath})";
    }
    
    // Try to extract frontmatter (if present in comments)
    $content = file_get_contents($filepath);
    $frontmatterFound = false;
    
    // Check for YAML frontmatter in comments
    if (preg_match('/---\s*\n(.*?)\n---/s', $content, $fmMatches)) {
        $frontmatterFound = true;
        
        // Parse frontmatter
        $frontmatter = $fmMatches[1];
        
        // Check agent_username matches file name
        if (preg_match('/agent_username:\s*(\S+)/', $frontmatter, $usernameMatch)) {
            $frontmatterAgent = trim($usernameMatch[1]);
            if ($frontmatterAgent !== $agentName) {
                $errors[] = "❌ {$filename}: Frontmatter agent_username '{$frontmatterAgent}' doesn't match file name '{$agentName}'";
            }
        }
        
        // Check agent_id matches channel ID
        if (preg_match('/agent_id:\s*(\d+)/', $frontmatter, $agentIdMatch)) {
            $frontmatterAgentId = (int)trim($agentIdMatch[1]);
            if ($frontmatterAgentId !== $channelId) {
                $errors[] = "❌ {$filename}: Frontmatter agent_id '{$frontmatterAgentId}' doesn't match channel ID '{$channelId}'";
            }
        }
        
        // Check channel_number matches
        if (preg_match('/channel_number:\s*(\S+)/', $frontmatter, $channelMatch)) {
            $frontmatterChannel = trim($channelMatch[1]);
            if ($frontmatterChannel !== $channelIdStr) {
                $warnings[] = "⚠️  {$filename}: Frontmatter channel_number '{$frontmatterChannel}' doesn't match file name '{$channelIdStr}'";
            }
        }
        
        // Check version
        if (preg_match('/version:\s*(\S+)/', $frontmatter, $versionMatch)) {
            $version = trim($versionMatch[1]);
            if (version_compare($version, '2.0.2', '<')) {
                $warnings[] = "⚠️  {$filename}: Frontmatter version '{$version}' is less than 2.0.2";
            }
        } else {
            $warnings[] = "⚠️  {$filename}: No version field in frontmatter";
        }
    } else {
        $warnings[] = "⚠️  {$filename}: No YAML frontmatter found (should include WOLFIE Headers v2.0.2 metadata)";
    }
    
    // Check for required sections (WHO, WHAT, WHERE, etc.)
    $requiredSections = ['WHO', 'WHAT', 'WHERE', 'WHEN', 'WHY', 'HOW', 'DO', 'HACK', 'OTHER'];
    $missingSections = [];
    foreach ($requiredSections as $section) {
        if (stripos($content, "<h2>{$section}</h2>") === false && 
            stripos($content, "## {$section}") === false) {
            $missingSections[] = $section;
        }
    }
    
    if (!empty($missingSections)) {
        $warnings[] = "⚠️  {$filename}: Missing sections: " . implode(', ', $missingSections);
    }
    
    // File is valid
    $valid[] = [
        'file' => $filename,
        'channel_id' => $channelId,
        'agent_name' => $agentName,
        'has_frontmatter' => $frontmatterFound
    ];
}

// Print results
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "VALIDATION RESULTS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (!empty($valid)) {
    echo "✅ Valid Files (" . count($valid) . "):\n";
    foreach ($valid as $file) {
        echo "   ✓ {$file['file']} (Channel: {$file['channel_id']}, Agent: {$file['agent_name']})\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  Warnings (" . count($warnings) . "):\n";
    foreach ($warnings as $warning) {
        echo "   {$warning}\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ Errors (" . count($errors) . "):\n";
    foreach ($errors as $error) {
        echo "   {$error}\n";
    }
    echo "\n";
}

// Summary
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "SUMMARY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Total files: " . count($files) . "\n";
echo "Valid: " . count($valid) . "\n";
echo "Warnings: " . count($warnings) . "\n";
echo "Errors: " . count($errors) . "\n";

if (empty($errors)) {
    echo "\n✅ All files pass validation!\n";
    exit(0);
} else {
    echo "\n❌ Validation failed with " . count($errors) . " error(s)\n";
    exit(1);
}

