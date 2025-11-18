<?php
/**
 * WOLFIE Headers Search System - v2.2.2
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: Full-text search functionality for log files and database logs
 * WHERE: public/includes/wolfie_search_system.php
 * WHEN: 2025-11-18
 * WHY: Enable keyword search across log content, YAML frontmatter, and metadata
 * HOW: Search file logs and database logs with combined filters
 * 
 * Version: 2.2.2
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

/**
 * Search file logs by keyword
 * 
 * @param string $keyword Search keyword
 * @param string $channel Channel filter (optional)
 * @param string $agent Agent filter (optional)
 * @param string $logsDir Logs directory path
 * @return array Matching log entries
 */
function searchFileLogs($keyword, $channel = null, $agent = null, $logsDir = null) {
    if (!$logsDir) {
        $logsDir = __DIR__ . '/../logs';
    }
    
    $results = [];
    $keyword = strtolower(trim($keyword));
    
    if (empty($keyword)) {
        return $results;
    }
    
    if (!is_dir($logsDir)) {
        return $results;
    }
    
    $files = glob($logsDir . '/*_*_log.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Parse filename: [channel]_[agent]_log.md
        if (preg_match('/^(\d+)_([^_]+)_log\.md$/', $filename, $matches)) {
            $fileChannel = $matches[1];
            $fileAgent = $matches[2];
            
            // Apply filters
            if ($channel !== null && $channel !== '' && $fileChannel != $channel) {
                continue;
            }
            if ($agent !== null && $agent !== '' && strtolower($fileAgent) !== strtolower($agent)) {
                continue;
            }
            
            // Read file content
            $content = @file_get_contents($file);
            if ($content === false) {
                continue;
            }
            
            // Search in content
            if (stripos($content, $keyword) !== false) {
                // Extract YAML frontmatter
                $yaml = '';
                if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $yamlMatches)) {
                    $yaml = $yamlMatches[1];
                }
                
                // Count matches
                $matchCount = substr_count(strtolower($content), $keyword);
                
                // Extract context (lines around match)
                $lines = explode("\n", $content);
                $contextLines = [];
                foreach ($lines as $lineNum => $line) {
                    if (stripos($line, $keyword) !== false) {
                        $start = max(0, $lineNum - 2);
                        $end = min(count($lines) - 1, $lineNum + 2);
                        $contextLines[] = [
                            'line' => $lineNum + 1,
                            'context' => implode("\n", array_slice($lines, $start, $end - $start + 1))
                        ];
                    }
                }
                
                $results[] = [
                    'type' => 'file',
                    'filename' => $filename,
                    'path' => $file,
                    'channel' => $fileChannel,
                    'agent' => $fileAgent,
                    'match_count' => $matchCount,
                    'yaml' => $yaml,
                    'context' => array_slice($contextLines, 0, 5) // Limit to 5 matches
                ];
            }
        }
    }
    
    return $results;
}

/**
 * Search database logs by keyword
 * 
 * @param PDO $db Database connection
 * @param string $tableName Table name
 * @param string $keyword Search keyword
 * @param string $channel Channel filter (optional)
 * @param string $agent Agent filter (optional)
 * @return array Matching log entries
 */
function searchDatabaseLogs($db, $tableName, $keyword, $channel = null, $agent = null) {
    $results = [];
    
    if (!$db || !$tableName) {
        return $results;
    }
    
    $keyword = trim($keyword);
    if (empty($keyword)) {
        return $results;
    }
    
    try {
        $query = "SELECT * FROM `{$tableName}` WHERE 1=1";
        $params = [];
        
        // Add filters
        if ($channel !== null && $channel !== '') {
            $query .= " AND channel_id = :channel";
            $params[':channel'] = intval($channel);
        }
        if ($agent !== null && $agent !== '') {
            $query .= " AND agent_name = :agent";
            $params[':agent'] = $agent;
        }
        
        // Search in metadata JSON or all columns
        // For MySQL, use LIKE for text search
        $query .= " AND (";
        $query .= " metadata LIKE :keyword";
        $query .= " OR agent_name LIKE :keyword2";
        $query .= " OR CAST(id AS CHAR) LIKE :keyword3";
        $query .= ")";
        
        $params[':keyword'] = '%' . $keyword . '%';
        $params[':keyword2'] = '%' . $keyword . '%';
        $params[':keyword3'] = '%' . $keyword . '%';
        
        $query .= " ORDER BY created_at DESC LIMIT 1000";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($entries as $entry) {
            // Count matches in metadata
            $metadataJson = json_encode($entry);
            $matchCount = substr_count(strtolower($metadataJson), strtolower($keyword));
            
            $results[] = [
                'type' => 'database',
                'table' => $tableName,
                'id' => $entry['id'] ?? null,
                'channel' => $entry['channel_id'] ?? null,
                'agent' => $entry['agent_name'] ?? null,
                'timestamp' => $entry['created_at'] ?? null,
                'match_count' => $matchCount,
                'data' => $entry
            ];
        }
    } catch (Exception $e) {
        error_log("Database search failed: " . $e->getMessage());
    }
    
    return $results;
}

/**
 * Combined search across file logs and database logs
 * 
 * @param string $keyword Search keyword
 * @param string $channel Channel filter (optional)
 * @param string $agent Agent filter (optional)
 * @param array $databaseTables Array of table names to search (optional)
 * @param string $logsDir Logs directory path (optional)
 * @return array Combined search results
 */
function searchAllLogs($keyword, $channel = null, $agent = null, $databaseTables = [], $logsDir = null) {
    $results = [
        'files' => [],
        'database' => [],
        'total_matches' => 0
    ];
    
    // Search file logs
    $fileResults = searchFileLogs($keyword, $channel, $agent, $logsDir);
    $results['files'] = $fileResults;
    $results['total_matches'] += count($fileResults);
    
    // Search database logs
    if (!empty($databaseTables)) {
        try {
            $db = getWOLFIEDatabaseConnection();
            
            foreach ($databaseTables as $tableName) {
                $dbResults = searchDatabaseLogs($db, $tableName, $keyword, $channel, $agent);
                $results['database'] = array_merge($results['database'], $dbResults);
                $results['total_matches'] += count($dbResults);
            }
        } catch (Exception $e) {
            // Database not available
            error_log("Database search unavailable: " . $e->getMessage());
        }
    }
    
    return $results;
}

