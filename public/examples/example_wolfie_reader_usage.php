<?php
/**
 * Example: WOLFIE Log Reader Usage
 * 
 * This file demonstrates how to use the enhanced WOLFIE Log Reader v2.2.0
 * with database integration and filtering capabilities.
 * 
 * Version: 2.2.0
 */

// Example 1: Direct URL access patterns
$examples = [
    // View all logs for channel 007
    'channel_007' => 'wolfie_reader.php?view=filtered&channel=007&source=all',
    
    // View all logs for agent WOLFIE
    'agent_wolfie' => 'wolfie_reader.php?view=filtered&agent=WOLFIE&source=all',
    
    // View logs for channel 007 AND agent CAPTAIN
    'channel_and_agent' => 'wolfie_reader.php?view=filtered&channel=007&agent=CAPTAIN&source=all',
    
    // View only file logs
    'files_only' => 'wolfie_reader.php?view=filtered&source=files',
    
    // View only database logs
    'database_only' => 'wolfie_reader.php?view=filtered&source=database',
    
    // View specific database table
    'table_content_logs' => 'wolfie_reader.php?view=table&table=content_logs',
    
    // View specific log file
    'log_file' => 'wolfie_reader.php?view=log&file=007_CAPTAIN_log.md',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WOLFIE Log Reader - Usage Examples</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #1d4ed8;
        }
        .example {
            background: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .example h3 {
            margin-top: 0;
            color: #1d4ed8;
        }
        .example code {
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        .example a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .example a:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <h1>WOLFIE Log Reader - Usage Examples</h1>
    <p>This page demonstrates various ways to use the enhanced WOLFIE Log Reader v2.2.0.</p>
    
    <?php foreach ($examples as $name => $url): ?>
        <div class="example">
            <h3><?php echo ucfirst(str_replace('_', ' ', $name)); ?></h3>
            <p><strong>URL:</strong> <code><?php echo htmlspecialchars($url); ?></code></p>
            <a href="<?php echo htmlspecialchars($url); ?>">Try This Example</a>
        </div>
    <?php endforeach; ?>
    
    <h2>Programmatic Usage</h2>
    <div class="example">
        <h3>PHP Integration</h3>
        <pre><code><?php echo htmlspecialchars('<?php
// Redirect to filtered view
header("Location: wolfie_reader.php?view=filtered&channel=007&agent=CAPTAIN&source=all");
exit;
?>'); ?></code></pre>
    </div>
    
    <div class="example">
        <h3>JavaScript Integration</h3>
        <pre><code><?php echo htmlspecialchars('// Navigate to filtered view
window.location.href = "wolfie_reader.php?view=filtered&channel=007&source=all";'); ?></code></pre>
    </div>
    
    <h2>Filter Combinations</h2>
    <div class="example">
        <h3>All Filters Combined</h3>
        <p><strong>URL:</strong> <code>wolfie_reader.php?view=filtered&source=all&channel=007&agent=CAPTAIN&table=content_logs</code></p>
        <p>This shows logs matching all criteria: channel 007, agent CAPTAIN, from content_logs table.</p>
    </div>
</body>
</html>

