<?php
/**
 * WOLFIE Headers API Core Functions
 * 
 * WHO: Captain WOLFIE (Agent 008) with LILITH's review (Agent 010)
 * WHAT: Core functions for API endpoints (agent discovery, channel discovery, log access)
 * WHERE: public/includes/wolfie_api_core.php
 * WHEN: 2025-11-18
 * WHY: Enable programmatic access to log system for agents (as suggested by LILITH)
 * HOW: RESTful API functions returning JSON responses
 */

// Load configuration files
require_once __DIR__ . '/../config/system.php';

// Configuration
define('WOLFIE_API_VERSION', WOLFIE_HEADERS_VERSION);
define('WOLFIE_LOGS_DIR', __DIR__ . '/../logs');
define('WOLFIE_CACHE_DIR', __DIR__ . '/../logs/.cache');
define('WOLFIE_CACHE_TTL', 300); // 5 minutes

/**
 * Send JSON response
 * 
 * @param mixed $data Response data
 * @param int $statusCode HTTP status code
 * @param array $metadata Additional metadata
 */
function sendJsonResponse($data, $statusCode = 200, $metadata = []) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    
    $response = [
        'status' => $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error',
        'data' => $data,
        'metadata' => array_merge([
            'api_version' => WOLFIE_API_VERSION,
            'generated_at' => gmdate('Y-m-d\TH:i:s\Z')
        ], $metadata)
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Send error response
 * 
 * @param string $code Error code
 * @param string $message Error message
 * @param array $details Additional error details
 * @param int $statusCode HTTP status code
 */
function sendErrorResponse($code, $message, $details = [], $statusCode = 400) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    
    $response = [
        'status' => 'error',
        'error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details
        ],
        'metadata' => [
            'api_version' => WOLFIE_API_VERSION,
            'generated_at' => gmdate('Y-m-d\TH:i:s\Z')
        ]
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Get cache file path
 * 
 * @param string $key Cache key
 * @return string Cache file path
 */
function getCachePath($key) {
    if (!is_dir(WOLFIE_CACHE_DIR)) {
        mkdir(WOLFIE_CACHE_DIR, 0755, true);
    }
    return WOLFIE_CACHE_DIR . '/' . md5($key) . '.json';
}

/**
 * Get cached data
 * 
 * @param string $key Cache key
 * @return mixed|null Cached data or null if expired/missing
 */
function getCache($key) {
    $cacheFile = getCachePath($key);
    
    if (!file_exists($cacheFile)) {
        return null;
    }
    
    $cacheData = json_decode(file_get_contents($cacheFile), true);
    
    if (!$cacheData || !isset($cacheData['expires_at'])) {
        return null;
    }
    
    if (time() > $cacheData['expires_at']) {
        unlink($cacheFile);
        return null;
    }
    
    return $cacheData['data'];
}

/**
 * Set cache data
 * 
 * @param string $key Cache key
 * @param mixed $data Data to cache
 * @param int $ttl Time to live in seconds
 */
function setCache($key, $data, $ttl = WOLFIE_CACHE_TTL) {
    $cacheFile = getCachePath($key);
    
    $cacheData = [
        'data' => $data,
        'created_at' => time(),
        'expires_at' => time() + $ttl
    ];
    
    file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT));
}

/**
 * Invalidate cache
 * 
 * @param string $key Cache key (optional, if null invalidates all)
 */
function invalidateCache($key = null) {
    if ($key === null) {
        // Invalidate all cache
        if (is_dir(WOLFIE_CACHE_DIR)) {
            $files = glob(WOLFIE_CACHE_DIR . '/*.json');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    } else {
        $cacheFile = getCachePath($key);
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}

/**
 * Scan logs directory with caching
 * 
 * @param bool $forceRefresh Force cache refresh
 * @return array Logs data (logs, agents, channels)
 */
function scanLogsDirectoryCached($forceRefresh = false) {
    $cacheKey = 'logs_directory_scan';
    
    if (!$forceRefresh) {
        $cached = getCache($cacheKey);
        if ($cached !== null) {
            return $cached;
        }
    }
    
    $logs = [];
    $agents = [];
    $channels = [];
    
    if (!is_dir(WOLFIE_LOGS_DIR)) {
        $result = ['logs' => [], 'agents' => [], 'channels' => []];
        setCache($cacheKey, $result);
        return $result;
    }
    
    $files = scandir(WOLFIE_LOGS_DIR);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || !preg_match('/\.md$/', $file)) {
            continue;
        }
        
        $parsed = parseLogFilename($file);
        if ($parsed) {
            $filepath = WOLFIE_LOGS_DIR . '/' . $file;
            $fileInfo = [
                'filename' => $file,
                'path' => $filepath,
                'channel' => $parsed['channel'],
                'agent' => $parsed['agent'],
                'channel_int' => intval($parsed['channel']),
                'file_size' => filesize($filepath),
                'last_modified' => date('Y-m-d\TH:i:s\Z', filemtime($filepath))
            ];
            
            // Try to extract metadata from YAML frontmatter
            $content = file_get_contents($filepath);
            $headers = parseWolfieHeaders($content);
            if ($headers['has_wolfie_header']) {
                $fileInfo['metadata'] = [
                    'agent_id' => isset($headers['agent_id']) ? intval($headers['agent_id']) : null,
                    'channel_id' => isset($headers['channel_id']) ? intval($headers['channel_id']) : null,
                    'version' => isset($headers['version']) ? $headers['version'] : null,
                    'log_entry_count' => isset($headers['log_entry_count']) ? intval($headers['log_entry_count']) : 0,
                    'last_log_date' => isset($headers['last_log_date']) ? $headers['last_log_date'] : null
                ];
            }
            
            $logs[] = $fileInfo;
            
            // Track unique agents
            if (!isset($agents[$parsed['agent']])) {
                $agents[$parsed['agent']] = [
                    'name' => $parsed['agent'],
                    'count' => 0,
                    'channels' => [],
                    'agent_id' => null,
                    'total_entries' => 0,
                    'first_log_date' => null,
                    'last_log_date' => null
                ];
            }
            $agents[$parsed['agent']]['count']++;
            if (!in_array($parsed['channel'], $agents[$parsed['agent']]['channels'])) {
                $agents[$parsed['agent']]['channels'][] = $parsed['channel'];
            }
            
            // Update agent metadata if available
            if (isset($fileInfo['metadata'])) {
                if ($agents[$parsed['agent']]['agent_id'] === null && $fileInfo['metadata']['agent_id'] !== null) {
                    $agents[$parsed['agent']]['agent_id'] = $fileInfo['metadata']['agent_id'];
                }
                if ($fileInfo['metadata']['log_entry_count'] > 0) {
                    $agents[$parsed['agent']]['total_entries'] += $fileInfo['metadata']['log_entry_count'];
                }
                if ($fileInfo['metadata']['last_log_date']) {
                    if ($agents[$parsed['agent']]['last_log_date'] === null || 
                        $fileInfo['metadata']['last_log_date'] > $agents[$parsed['agent']]['last_log_date']) {
                        $agents[$parsed['agent']]['last_log_date'] = $fileInfo['metadata']['last_log_date'];
                    }
                    if ($agents[$parsed['agent']]['first_log_date'] === null || 
                        $fileInfo['metadata']['last_log_date'] < $agents[$parsed['agent']]['first_log_date']) {
                        $agents[$parsed['agent']]['first_log_date'] = $fileInfo['metadata']['last_log_date'];
                    }
                }
            }
            
            // Track unique channels
            if (!isset($channels[$parsed['channel']])) {
                $channels[$parsed['channel']] = [
                    'number' => $parsed['channel'],
                    'count' => 0,
                    'agents' => [],
                    'total_entries' => 0,
                    'first_log_date' => null,
                    'last_log_date' => null
                ];
            }
            $channels[$parsed['channel']]['count']++;
            if (!in_array($parsed['agent'], $channels[$parsed['channel']]['agents'])) {
                $channels[$parsed['channel']]['agents'][] = $parsed['agent'];
            }
            
            // Update channel metadata if available
            if (isset($fileInfo['metadata'])) {
                if ($fileInfo['metadata']['log_entry_count'] > 0) {
                    $channels[$parsed['channel']]['total_entries'] += $fileInfo['metadata']['log_entry_count'];
                }
                if ($fileInfo['metadata']['last_log_date']) {
                    if ($channels[$parsed['channel']]['last_log_date'] === null || 
                        $fileInfo['metadata']['last_log_date'] > $channels[$parsed['channel']]['last_log_date']) {
                        $channels[$parsed['channel']]['last_log_date'] = $fileInfo['metadata']['last_log_date'];
                    }
                    if ($channels[$parsed['channel']]['first_log_date'] === null || 
                        $fileInfo['metadata']['last_log_date'] < $channels[$parsed['channel']]['first_log_date']) {
                        $channels[$parsed['channel']]['first_log_date'] = $fileInfo['metadata']['last_log_date'];
                    }
                }
            }
        }
    }
    
    // Sort agents by name
    ksort($agents);
    
    // Sort channels by number
    ksort($channels);
    
    $result = [
        'logs' => $logs,
        'agents' => $agents,
        'channels' => $channels
    ];
    
    setCache($cacheKey, $result);
    return $result;
}

/**
 * Parse log filename to extract channel and agent
 * 
 * @param string $filename Log filename
 * @return array|null Parsed data or null if invalid
 */
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

/**
 * Parse WOLFIE headers from markdown file content
 * 
 * @param string $content Raw markdown content
 * @return array Parsed header data
 */
function parseWolfieHeaders($content) {
    $headers = [
        'agent_username' => 'wolfie',
        'onchannel' => null,
        'tags' => [],
        'collections' => [],
        'in_this_file_we_have' => [],
        'has_wolfie_header' => false,
        'agent_id' => null,
        'channel_id' => null,
        'channel_number' => null,
        'version' => null,
        'log_entry_count' => 0,
        'last_log_date' => null
    ];
    
    // Extract YAML frontmatter
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
        $yamlContent = $matches[1];
        $parsed = parseSimpleYaml($yamlContent);
        
        $headers['has_wolfie_header'] = true;
        $headers = array_merge($headers, $parsed);
        
        // Convert string values to appropriate types
        if (isset($headers['agent_id'])) {
            $headers['agent_id'] = intval($headers['agent_id']);
        }
        if (isset($headers['channel_id'])) {
            $headers['channel_id'] = intval($headers['channel_id']);
        }
        if (isset($headers['onchannel'])) {
            $headers['onchannel'] = intval($headers['onchannel']);
        }
        if (isset($headers['log_entry_count'])) {
            $headers['log_entry_count'] = intval($headers['log_entry_count']);
        }
    }
    
    return $headers;
}

/**
 * Simple YAML parser for frontmatter
 * 
 * @param string $yaml YAML content
 * @return array Parsed data
 */
function parseSimpleYaml($yaml) {
    $data = [];
    $lines = explode("\n", $yaml);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Parse key: value or key: [array]
        if (preg_match('/^(\w+):\s*(.+)$/', $line, $matches)) {
            $key = $matches[1];
            $value = trim($matches[2]);
            
            // Check if it's an array [item1, item2, item3]
            if (preg_match('/^\[(.*)\]$/', $value, $arrayMatches)) {
                $items = explode(',', $arrayMatches[1]);
                $data[$key] = array_map(function($item) {
                    return trim($item, ' "\'');
                }, $items);
            } else {
                // Simple value
                $data[$key] = trim($value, '"\'');
            }
        }
    }
    
    return $data;
}

/**
 * Search log content
 * 
 * @param string $query Search query
 * @param array $filters Search filters (agent, channel, date_from, date_to)
 * @param array $options Search options (highlight, limit, offset)
 * @return array Search results
 */
function searchLogs($query, $filters = [], $options = []) {
    $data = scanLogsDirectoryCached();
    $logs = $data['logs'];
    
    $results = [];
    $queryLower = strtolower($query);
    
    foreach ($logs as $log) {
        // Apply filters
        if (isset($filters['agent']) && $log['agent'] !== $filters['agent']) {
            continue;
        }
        if (isset($filters['channel']) && $log['channel'] !== $filters['channel']) {
            continue;
        }
        if (isset($filters['date_from']) || isset($filters['date_to'])) {
            $logDate = isset($log['metadata']['last_log_date']) ? $log['metadata']['last_log_date'] : null;
            if ($logDate) {
                if (isset($filters['date_from']) && $logDate < $filters['date_from']) {
                    continue;
                }
                if (isset($filters['date_to']) && $logDate > $filters['date_to']) {
                    continue;
                }
            }
        }
        
        // Read file content
        $content = file_get_contents($log['path']);
        
        // Search in content
        $contentLower = strtolower($content);
        $matches = [];
        $relevanceScore = 0;
        
        // Count occurrences
        $occurrences = substr_count($contentLower, $queryLower);
        if ($occurrences > 0) {
            $relevanceScore = min(1.0, $occurrences / 10.0); // Cap at 1.0
            
            // Extract snippets
            $lines = explode("\n", $content);
            $snippets = [];
            foreach ($lines as $lineNum => $line) {
                if (stripos($line, $query) !== false) {
                    $snippet = trim($line);
                    if (strlen($snippet) > 200) {
                        $snippet = substr($snippet, 0, 200) . '...';
                    }
                    $snippets[] = [
                        'line' => $lineNum + 1,
                        'snippet' => $snippet,
                        'highlight' => isset($options['highlight']) && $options['highlight'] 
                            ? preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $snippet)
                            : $snippet
                    ];
                    if (count($snippets) >= 3) break; // Limit snippets
                }
            }
            
            $matches[] = [
                'field' => 'content',
                'snippets' => $snippets,
                'occurrences' => $occurrences
            ];
        }
        
        // Search in YAML frontmatter
        $headers = parseWolfieHeaders($content);
        if ($headers['has_wolfie_header']) {
            $headerText = json_encode($headers);
            if (stripos($headerText, $query) !== false) {
                $relevanceScore += 0.2; // Boost for metadata matches
                $matches[] = [
                    'field' => 'metadata',
                    'snippets' => []
                ];
            }
        }
        
        if (!empty($matches)) {
            $results[] = [
                'filename' => $log['filename'],
                'channel' => $log['channel'],
                'agent' => $log['agent'],
                'path' => $log['path'],
                'matches' => $matches,
                'relevance_score' => min(1.0, $relevanceScore),
                'metadata' => isset($log['metadata']) ? $log['metadata'] : null
            ];
        }
    }
    
    // Sort by relevance
    usort($results, function($a, $b) {
        return $b['relevance_score'] <=> $a['relevance_score'];
    });
    
    // Apply limit and offset
    $limit = isset($options['limit']) ? intval($options['limit']) : 20;
    $offset = isset($options['offset']) ? intval($options['offset']) : 0;
    
    return [
        'results' => array_slice($results, $offset, $limit),
        'total_results' => count($results),
        'has_more' => ($offset + $limit) < count($results)
    ];
}

