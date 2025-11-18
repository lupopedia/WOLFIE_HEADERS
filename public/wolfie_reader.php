<?php
/**
 * WOLFIE Log Reader v2.2.0
 * Enhanced with database log integration
 * Reads log files from public/logs/ directory AND database tables ending with _logs or _log
 */

// Load configuration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/system.php';
require_once __DIR__ . '/includes/wolfie_database_logs_system.php';

// Configuration
$logsDir = __DIR__ . '/logs';
$view = isset($_GET['view']) ? $_GET['view'] : 'main';
$agent = isset($_GET['agent']) ? $_GET['agent'] : '';
$channel = isset($_GET['channel']) ? $_GET['channel'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : '';
$source = isset($_GET['source']) ? $_GET['source'] : 'all'; // 'files', 'database', 'all'

// Database connection (with graceful fallback)
$db = null;
$dbAvailable = false;
try {
    $db = getWOLFIEDatabaseConnection();
    $dbAvailable = true;
} catch (Exception $e) {
    // Database not available - continue with file-only mode
    $dbAvailable = false;
}

// Discover database log tables
function discoverDatabaseLogTables($db) {
    if (!$db) {
        return [];
    }
    
    try {
        $tables = [];
        
        // Find tables ending with _logs or _log
        $stmt = $db->query("SHOW TABLES");
        $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($allTables as $tableName) {
            if (preg_match('/_(logs|log)$/', $tableName)) {
                // Check if table has channel_id and agent_name columns
                try {
                    $stmt = $db->query("DESCRIBE `{$tableName}`");
                    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (in_array('channel_id', $columns) && in_array('agent_name', $columns)) {
                        // Get row count
                        $countStmt = $db->query("SELECT COUNT(*) as cnt FROM `{$tableName}`");
                        $count = $countStmt->fetch(PDO::FETCH_ASSOC)['cnt'];
                        
                        $tables[] = [
                            'name' => $tableName,
                            'count' => intval($count)
                        ];
                    }
                } catch (Exception $e) {
                    // Skip tables that can't be described
                    continue;
                }
            }
        }
        
        return $tables;
    } catch (Exception $e) {
        return [];
    }
}

// Get database logs
function getDatabaseLogs($db, $tableName, $channel = null, $agent = null) {
    if (!$db || !$tableName) {
        return [];
    }
    
    try {
        $query = "SELECT * FROM `{$tableName}` WHERE 1=1";
        $params = [];
        
        if ($channel !== null && $channel !== '') {
            $query .= " AND channel_id = :channel";
            $params[':channel'] = intval($channel);
        }
        
        if ($agent !== null && $agent !== '') {
            $query .= " AND agent_name = :agent";
            $params[':agent'] = $agent;
        }
        
        $query .= " ORDER BY created_at DESC LIMIT 1000";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

// Scan logs directory
function scanLogsDirectory($logsDir) {
    $logs = [];
    $agents = [];
    $channels = [];
    
    if (!is_dir($logsDir)) {
        return ['logs' => [], 'agents' => [], 'channels' => []];
    }
    
    $files = scandir($logsDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || !preg_match('/\.md$/', $file)) {
            continue;
        }
        
        $parsed = parseLogFilename($file);
        if ($parsed) {
            $logs[] = [
                'filename' => $file,
                'path' => $logsDir . '/' . $file,
                'channel' => $parsed['channel'],
                'agent' => $parsed['agent'],
                'channel_int' => intval($parsed['channel']),
                'source' => 'file'
            ];
            
            // Track unique agents
            if (!isset($agents[$parsed['agent']])) {
                $agents[$parsed['agent']] = [
                    'name' => $parsed['agent'],
                    'count' => 0,
                    'channels' => []
                ];
            }
            $agents[$parsed['agent']]['count']++;
            if (!in_array($parsed['channel'], $agents[$parsed['agent']]['channels'])) {
                $agents[$parsed['agent']]['channels'][] = $parsed['channel'];
            }
            
            // Track unique channels
            if (!isset($channels[$parsed['channel']])) {
                $channels[$parsed['channel']] = [
                    'number' => $parsed['channel'],
                    'count' => 0,
                    'agents' => []
                ];
            }
            $channels[$parsed['channel']]['count']++;
            if (!in_array($parsed['agent'], $channels[$parsed['channel']]['agents'])) {
                $channels[$parsed['channel']]['agents'][] = $parsed['agent'];
            }
        }
    }
    
    // Sort agents by name
    ksort($agents);
    
    // Sort channels by number
    ksort($channels);
    
    return [
        'logs' => $logs,
        'agents' => $agents,
        'channels' => $channels
    ];
}

// Parse log filename to extract channel and agent
function parseLogFilename($filename) {
    // Pattern 1: [channel]_[agent]_log.md (e.g., 007_CAPTAIN_log.md)
    if (preg_match('/^(\d{3})_([A-Za-z0-9_]+)_log\.md$/', $filename, $matches)) {
        return [
            'channel' => $matches[1],
            'agent' => $matches[2]
        ];
    }
    
    // Pattern 2: [channel]_[agent].md (e.g., 007_wolfie.md)
    if (preg_match('/^(\d{3})_([A-Za-z0-9_]+)\.md$/', $filename, $matches)) {
        return [
            'channel' => $matches[1],
            'agent' => $matches[2]
        ];
    }
    
    return null;
}

// Get log content
function getLogContent($filepath) {
    if (!file_exists($filepath)) {
        return null;
    }
    return file_get_contents($filepath);
}

// Basic markdown to HTML conversion
function markdownToHtml($text) {
    // Convert headers
    $text = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $text);
    $text = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $text);
    
    // Convert bold
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    
    // Convert italic
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    
    // Convert horizontal rules
    $text = preg_replace('/^---$/m', '<hr>', $text);
    
    // Convert code blocks
    $text = preg_replace('/```([^`]+)```/s', '<pre><code>$1</code></pre>', $text);
    
    // Convert inline code
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    
    // Convert unordered lists
    $text = preg_replace('/^-\s+(.*)$/m', '<li>$1</li>', $text);
    
    // Convert paragraphs
    $lines = explode("\n", $text);
    $result = [];
    $inList = false;
    $listItems = [];
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        
        if (empty($trimmed)) {
            if ($inList && !empty($listItems)) {
                $result[] = '<ul>' . implode('', $listItems) . '</ul>';
                $listItems = [];
                $inList = false;
            }
            $result[] = '';
            continue;
        }
        
        if (preg_match('/^<[^>]+>/', $trimmed)) {
            if ($inList && !empty($listItems)) {
                $result[] = '<ul>' . implode('', $listItems) . '</ul>';
                $listItems = [];
                $inList = false;
            }
            $result[] = $trimmed;
        } elseif (preg_match('/^<li>/', $trimmed)) {
            $inList = true;
            $listItems[] = $trimmed;
        } else {
            if ($inList && !empty($listItems)) {
                $result[] = '<ul>' . implode('', $listItems) . '</ul>';
                $listItems = [];
                $inList = false;
            }
            if (!preg_match('/^<[^>]+>/', $trimmed)) {
                $result[] = '<p>' . $trimmed . '</p>';
            } else {
                $result[] = $trimmed;
            }
        }
    }
    
    if ($inList && !empty($listItems)) {
        $result[] = '<ul>' . implode('', $listItems) . '</ul>';
    }
    
    return implode("\n", $result);
}

// Format database log entry
function formatDatabaseLogEntry($entry) {
    $html = '<div class="db-log-entry">';
    $html .= '<div class="db-log-header">';
    $html .= '<strong>ID:</strong> ' . htmlspecialchars($entry['id'] ?? 'N/A') . ' | ';
    $html .= '<strong>Channel:</strong> ' . htmlspecialchars($entry['channel_id'] ?? 'N/A') . ' | ';
    $html .= '<strong>Agent:</strong> ' . htmlspecialchars($entry['agent_name'] ?? 'N/A');
    if (isset($entry['created_at'])) {
        $html .= ' | <strong>Date:</strong> ' . htmlspecialchars($entry['created_at']);
    }
    $html .= '</div>';
    
    if (isset($entry['metadata'])) {
        $metadata = json_decode($entry['metadata'], true);
        if ($metadata) {
            $html .= '<div class="db-log-metadata"><strong>Metadata:</strong><pre>' . htmlspecialchars(json_encode($metadata, JSON_PRETTY_PRINT)) . '</pre></div>';
        }
    }
    
    $html .= '</div>';
    return $html;
}

// Scan directory and database
$data = scanLogsDirectory($logsDir);
$logs = $data['logs'];
$agents = $data['agents'];
$channels = $data['channels'];

// Get database tables
$dbTables = [];
if ($dbAvailable) {
    $dbTables = discoverDatabaseLogTables($db);
}

// Get database logs if filtering
$dbLogs = [];
if ($dbAvailable && $table && ($source === 'database' || $source === 'all')) {
    $dbLogs = getDatabaseLogs($db, $table, $channel ?: null, $agent ?: null);
}

// Calculate statistics
$totalFileLogs = count($logs);
$totalDbLogs = 0;
foreach ($dbTables as $t) {
    $totalDbLogs += $t['count'];
}
$totalLogs = $totalFileLogs + $totalDbLogs;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WOLFIE Log Reader v2.2.0</title>
    <style>
.reader-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.reader-header {
    background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(29, 78, 216, 0.3);
}

.reader-header h1 {
    margin: 0 0 10px 0;
    font-size: 2.5rem;
}

.reader-header p {
    margin: 0;
    font-size: 1.1rem;
    opacity: 0.9;
}

.filter-panel {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.filter-panel h3 {
    margin: 0 0 20px 0;
    color: #1d4ed8;
    font-size: 1.3rem;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #334155;
}

.filter-row select,
.filter-row input {
    width: 100%;
    padding: 10px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
}

.filter-row select:focus,
.filter-row input:focus {
    outline: none;
    border-color: #2563eb;
}

.btn-filter {
    padding: 12px 24px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-filter:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-left: 4px solid #2563eb;
}

.stat-card.file-source {
    border-left-color: #10b981;
}

.stat-card.db-source {
    border-left-color: #f59e0b;
}

.stat-card h3 {
    margin: 0 0 10px 0;
    color: #1d4ed8;
    font-size: 1.1rem;
}

.stat-card .number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2563eb;
}

.stat-card .source-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    margin-top: 5px;
}

.source-badge.file {
    background: #d1fae5;
    color: #065f46;
}

.source-badge.database {
    background: #fef3c7;
    color: #92400e;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

@media (max-width: 968px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.section-card h2 {
    margin: 0 0 20px 0;
    color: #1d4ed8;
    font-size: 1.8rem;
    border-bottom: 3px solid #2563eb;
    padding-bottom: 10px;
}

.item-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.item-list li {
    padding: 12px;
    margin-bottom: 8px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
    transition: all 0.2s;
}

.item-list li:hover {
    background: #eff6ff;
    transform: translateX(5px);
}

.item-list a {
    text-decoration: none;
    color: #1e40af;
    font-weight: 600;
    display: block;
}

.item-list .count {
    color: #64748b;
    font-size: 0.9rem;
    margin-left: 10px;
}

.log-viewer {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.log-viewer h2 {
    margin: 0 0 20px 0;
    color: #1d4ed8;
    font-size: 1.8rem;
}

.log-content {
    background: #f8fafc;
    border-radius: 8px;
    padding: 25px;
    border-left: 4px solid #2563eb;
    line-height: 1.8;
}

.log-content h1, .log-content h2, .log-content h3 {
    color: #1d4ed8;
}

.log-content code {
    background: #e2e8f0;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.log-content pre {
    background: #1e293b;
    color: #dbeafe;
    padding: 15px;
    border-radius: 8px;
    overflow-x: auto;
}

.db-log-entry {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
}

.db-log-header {
    margin-bottom: 10px;
    color: #92400e;
}

.db-log-metadata {
    margin-top: 10px;
}

.db-log-metadata pre {
    background: #1e293b;
    color: #dbeafe;
    padding: 10px;
    border-radius: 4px;
    overflow-x: auto;
    font-size: 0.9rem;
}

.nav-buttons {
    display: flex;
    gap: 15px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-block;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.btn-secondary {
    background: white;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.btn-secondary:hover {
    background: #eff6ff;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #64748b;
}

.empty-state p {
    font-size: 1.1rem;
    margin: 10px 0;
}

.source-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.source-tab {
    padding: 10px 20px;
    background: #f1f5f9;
    border: 2px solid transparent;
    border-radius: 8px;
    text-decoration: none;
    color: #475569;
    font-weight: 600;
    transition: all 0.2s;
}

.source-tab.active {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.source-tab:hover {
    background: #e2e8f0;
}

.source-tab.active:hover {
    background: #1d4ed8;
}
    </style>
</head>
<body>
<div class="reader-container">
    <div class="reader-header">
        <h1>📋 WOLFIE Log Reader v2.2.0</h1>
        <p>Browse and view agent log files and database logs across all channels</p>
    </div>

    <?php if ($view === 'main'): ?>
        <!-- Filter Panel -->
        <div class="filter-panel">
            <h3>🔍 Filter Logs</h3>
            <form method="GET" action="">
                <input type="hidden" name="view" value="filtered">
                <div class="filter-row">
                    <div>
                        <label>Source</label>
                        <select name="source">
                            <option value="all" <?php echo $source === 'all' ? 'selected' : ''; ?>>All Sources</option>
                            <option value="files" <?php echo $source === 'files' ? 'selected' : ''; ?>>File Logs Only</option>
                            <?php if ($dbAvailable): ?>
                            <option value="database" <?php echo $source === 'database' ? 'selected' : ''; ?>>Database Logs Only</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label>Channel</label>
                        <input type="number" name="channel" min="0" max="999" placeholder="000-999" value="<?php echo htmlspecialchars($channel); ?>">
                    </div>
                    <div>
                        <label>Agent Name</label>
                        <input type="text" name="agent" placeholder="Agent name" value="<?php echo htmlspecialchars($agent); ?>">
                    </div>
                    <?php if ($dbAvailable && !empty($dbTables)): ?>
                    <div>
                        <label>Database Table</label>
                        <select name="table">
                            <option value="">All Tables</option>
                            <?php foreach ($dbTables as $t): ?>
                            <option value="<?php echo htmlspecialchars($t['name']); ?>" <?php echo $table === $t['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t['name']); ?> (<?php echo $t['count']; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-filter">Apply Filters</button>
                <a href="?" class="btn btn-secondary" style="margin-left: 10px;">Clear Filters</a>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Logs</h3>
                <div class="number"><?php echo $totalLogs; ?></div>
            </div>
            <div class="stat-card file-source">
                <h3>File Logs</h3>
                <div class="number"><?php echo $totalFileLogs; ?></div>
                <span class="source-badge file">Files</span>
            </div>
            <?php if ($dbAvailable): ?>
            <div class="stat-card db-source">
                <h3>Database Logs</h3>
                <div class="number"><?php echo $totalDbLogs; ?></div>
                <span class="source-badge database">Database</span>
            </div>
            <div class="stat-card">
                <h3>Database Tables</h3>
                <div class="number"><?php echo count($dbTables); ?></div>
            </div>
            <?php endif; ?>
            <div class="stat-card">
                <h3>Unique Agents</h3>
                <div class="number"><?php echo count($agents); ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Channels</h3>
                <div class="number"><?php echo count($channels); ?></div>
            </div>
        </div>

        <div class="content-grid">
            <div class="section-card">
                <h2>🤖 Agents</h2>
                <?php if (empty($agents)): ?>
                    <div class="empty-state">
                        <p>No agents found in logs directory.</p>
                    </div>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($agents as $agentName => $agentData): ?>
                            <li>
                                <a href="?view=agent&agent=<?php echo urlencode($agentName); ?>">
                                    <?php echo htmlspecialchars($agentName); ?>
                                    <span class="count">(<?php echo $agentData['count']; ?> log<?php echo $agentData['count'] !== 1 ? 's' : ''; ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="section-card">
                <h2>📡 Channels</h2>
                <?php if (empty($channels)): ?>
                    <div class="empty-state">
                        <p>No channels found in logs directory.</p>
                    </div>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($channels as $channelNum => $channelData): ?>
                            <li>
                                <a href="?view=channel&channel=<?php echo urlencode($channelNum); ?>">
                                    Channel <?php echo htmlspecialchars($channelNum); ?>
                                    <span class="count">(<?php echo $channelData['count']; ?> log<?php echo $channelData['count'] !== 1 ? 's' : ''; ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($dbAvailable && !empty($dbTables)): ?>
        <div class="section-card">
            <h2>🗄️ Database Log Tables</h2>
            <ul class="item-list">
                <?php foreach ($dbTables as $t): ?>
                    <li>
                        <a href="?view=table&table=<?php echo urlencode($t['name']); ?>">
                            <?php echo htmlspecialchars($t['name']); ?>
                            <span class="count">(<?php echo $t['count']; ?> entries)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="section-card">
            <h2>📄 All Log Files</h2>
            <?php if (empty($logs)): ?>
                <div class="empty-state">
                    <p>No log files found in logs directory.</p>
                </div>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($logs as $log): ?>
                        <li>
                            <a href="?view=log&file=<?php echo urlencode($log['filename']); ?>">
                                <?php echo htmlspecialchars($log['filename']); ?>
                                <span class="count">(Channel <?php echo $log['channel']; ?>, Agent <?php echo htmlspecialchars($log['agent']); ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    <?php elseif ($view === 'filtered'): ?>
        <!-- Filtered View -->
        <div class="log-viewer">
            <h2>🔍 Filtered Results</h2>
            
            <div class="source-tabs">
                <a href="?view=filtered&source=all&channel=<?php echo urlencode($channel); ?>&agent=<?php echo urlencode($agent); ?>&table=<?php echo urlencode($table); ?>" 
                   class="source-tab <?php echo $source === 'all' ? 'active' : ''; ?>">All</a>
                <a href="?view=filtered&source=files&channel=<?php echo urlencode($channel); ?>&agent=<?php echo urlencode($agent); ?>&table=<?php echo urlencode($table); ?>" 
                   class="source-tab <?php echo $source === 'files' ? 'active' : ''; ?>">Files</a>
                <?php if ($dbAvailable): ?>
                <a href="?view=filtered&source=database&channel=<?php echo urlencode($channel); ?>&agent=<?php echo urlencode($agent); ?>&table=<?php echo urlencode($table); ?>" 
                   class="source-tab <?php echo $source === 'database' ? 'active' : ''; ?>">Database</a>
                <?php endif; ?>
            </div>

            <?php
            // Filter file logs
            $filteredFileLogs = $logs;
            if ($channel) {
                $filteredFileLogs = array_filter($filteredFileLogs, function($log) use ($channel) {
                    return $log['channel'] === str_pad($channel, 3, '0', STR_PAD_LEFT);
                });
            }
            if ($agent) {
                $filteredFileLogs = array_filter($filteredFileLogs, function($log) use ($agent) {
                    return strtolower($log['agent']) === strtolower($agent);
                });
            }
            ?>

            <?php if ($source === 'files' || $source === 'all'): ?>
            <h3 style="margin-top: 30px; color: #10b981;">📁 File Logs (<?php echo count($filteredFileLogs); ?>)</h3>
            <?php if (empty($filteredFileLogs)): ?>
                <div class="empty-state">
                    <p>No file logs match the filter criteria.</p>
                </div>
            <?php else: ?>
                <ul class="item-list">
                    <?php foreach ($filteredFileLogs as $log): ?>
                        <li>
                            <a href="?view=log&file=<?php echo urlencode($log['filename']); ?>">
                                <?php echo htmlspecialchars($log['filename']); ?>
                                <span class="count">(Channel <?php echo $log['channel']; ?>, Agent <?php echo htmlspecialchars($log['agent']); ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($dbAvailable && ($source === 'database' || $source === 'all')): ?>
            <h3 style="margin-top: 30px; color: #f59e0b;">🗄️ Database Logs</h3>
            <?php if (empty($dbTables)): ?>
                <div class="empty-state">
                    <p>No database log tables found.</p>
                </div>
            <?php else: ?>
                <?php
                $tablesToShow = $table ? [$table] : array_column($dbTables, 'name');
                $hasResults = false;
                foreach ($tablesToShow as $tableName):
                    $tableLogs = getDatabaseLogs($db, $tableName, $channel ?: null, $agent ?: null);
                    if (!empty($tableLogs)):
                        $hasResults = true;
                ?>
                    <h4 style="margin-top: 20px;">Table: <?php echo htmlspecialchars($tableName); ?> (<?php echo count($tableLogs); ?> entries)</h4>
                    <div class="log-content">
                        <?php foreach ($tableLogs as $entry): ?>
                            <?php echo formatDatabaseLogEntry($entry); ?>
                        <?php endforeach; ?>
                    </div>
                <?php
                    endif;
                endforeach;
                if (!$hasResults):
                ?>
                    <div class="empty-state">
                        <p>No database logs match the filter criteria.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>

            <div class="nav-buttons">
                <a href="?" class="btn btn-primary">← Back to Main View</a>
            </div>
        </div>

    <?php elseif ($view === 'agent' && !empty($agent)): ?>
        <!-- Agent View: All logs by agent -->
        <div class="log-viewer">
            <h2>🤖 Agent: <?php echo htmlspecialchars($agent); ?></h2>
            <?php
            $agentLogs = array_filter($logs, function($log) use ($agent) {
                return strtolower($log['agent']) === strtolower($agent);
            });
            ?>
            <p><strong><?php echo count($agentLogs); ?></strong> log file<?php echo count($agentLogs) !== 1 ? 's' : ''; ?> found for this agent.</p>
            
            <ul class="item-list" style="margin-top: 20px;">
                <?php foreach ($agentLogs as $log): ?>
                    <li>
                        <a href="?view=log&file=<?php echo urlencode($log['filename']); ?>">
                            <?php echo htmlspecialchars($log['filename']); ?>
                            <span class="count">(Channel <?php echo $log['channel']; ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="nav-buttons">
                <a href="?" class="btn btn-primary">← Back to Main View</a>
            </div>
        </div>

    <?php elseif ($view === 'channel' && !empty($channel)): ?>
        <!-- Channel View: All logs on channel -->
        <div class="log-viewer">
            <h2>📡 Channel: <?php echo htmlspecialchars($channel); ?></h2>
            <?php
            $channelLogs = array_filter($logs, function($log) use ($channel) {
                return $log['channel'] === str_pad($channel, 3, '0', STR_PAD_LEFT);
            });
            ?>
            <p><strong><?php echo count($channelLogs); ?></strong> log file<?php echo count($channelLogs) !== 1 ? 's' : ''; ?> found on this channel.</p>
            
            <ul class="item-list" style="margin-top: 20px;">
                <?php foreach ($channelLogs as $log): ?>
                    <li>
                        <a href="?view=log&file=<?php echo urlencode($log['filename']); ?>">
                            <?php echo htmlspecialchars($log['filename']); ?>
                            <span class="count">(Agent <?php echo htmlspecialchars($log['agent']); ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="nav-buttons">
                <a href="?" class="btn btn-primary">← Back to Main View</a>
            </div>
        </div>

    <?php elseif ($view === 'table' && !empty($table) && $dbAvailable): ?>
        <!-- Database Table View -->
        <div class="log-viewer">
            <h2>🗄️ Database Table: <?php echo htmlspecialchars($table); ?></h2>
            <?php
            $tableLogs = getDatabaseLogs($db, $table);
            ?>
            <p><strong><?php echo count($tableLogs); ?></strong> log entries found in this table.</p>
            
            <div class="log-content" style="margin-top: 20px;">
                <?php foreach ($tableLogs as $entry): ?>
                    <?php echo formatDatabaseLogEntry($entry); ?>
                <?php endforeach; ?>
            </div>
            
            <div class="nav-buttons">
                <a href="?" class="btn btn-primary">← Back to Main View</a>
            </div>
        </div>

    <?php elseif ($view === 'log' && !empty($file)): ?>
        <!-- Log View: Single log file -->
        <?php
        $filepath = $logsDir . '/' . $file;
        $content = getLogContent($filepath);
        $parsed = parseLogFilename($file);
        ?>
        <div class="log-viewer">
            <h2>📄 <?php echo htmlspecialchars($file); ?></h2>
            <?php if ($parsed): ?>
                <p>
                    <strong>Channel:</strong> <?php echo htmlspecialchars($parsed['channel']); ?> | 
                    <strong>Agent:</strong> <?php echo htmlspecialchars($parsed['agent']); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($content): ?>
                <div class="log-content">
                    <?php echo markdownToHtml($content); ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Log file not found or could not be read.</p>
                </div>
            <?php endif; ?>
            
            <div class="nav-buttons">
                <a href="?" class="btn btn-primary">← Back to Main View</a>
                <?php if ($parsed): ?>
                    <a href="?view=agent&agent=<?php echo urlencode($parsed['agent']); ?>" class="btn btn-secondary">View All Logs by <?php echo htmlspecialchars($parsed['agent']); ?></a>
                    <a href="?view=channel&channel=<?php echo urlencode($parsed['channel']); ?>" class="btn btn-secondary">View All Logs on Channel <?php echo htmlspecialchars($parsed['channel']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
