<?php
// WOLFIE Log Reader
// Standalone version for WOLFIE Headers repository
// Reads log files from public/logs/ directory

// Configuration
$logsDir = __DIR__ . '/logs';
$view = isset($_GET['view']) ? $_GET['view'] : 'main';
$agent = isset($_GET['agent']) ? $_GET['agent'] : '';
$channel = isset($_GET['channel']) ? $_GET['channel'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';

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
                'channel_int' => intval($parsed['channel'])
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

// Scan directory
$data = scanLogsDirectory($logsDir);
$logs = $data['logs'];
$agents = $data['agents'];
$channels = $data['channels'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WOLFIE Log Reader</title>
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
    </style>
</head>
<body>
<div class="reader-container">
    <div class="reader-header">
        <h1>📋 WOLFIE Log Reader</h1>
        <p>Browse and view agent log files across all channels</p>
    </div>

    <?php if ($view === 'main'): ?>
        <!-- Main View: Statistics and Lists -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Log Files</h3>
                <div class="number"><?php echo count($logs); ?></div>
            </div>
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

    <?php elseif ($view === 'agent' && !empty($agent)): ?>
        <!-- Agent View: All logs by agent -->
        <div class="log-viewer">
            <h2>🤖 Agent: <?php echo htmlspecialchars($agent); ?></h2>
            <?php
            $agentLogs = array_filter($logs, function($log) use ($agent) {
                return $log['agent'] === $agent;
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
                return $log['channel'] === $channel;
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

