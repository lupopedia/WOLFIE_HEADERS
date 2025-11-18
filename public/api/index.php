<?php
/**
 * WOLFIE Headers API Router
 * 
 * Routes API requests to appropriate endpoints
 */

require_once __DIR__ . '/../includes/wolfie_api_core.php';

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];

// Support both PATH_INFO and query parameter routing
$path = '';
if (isset($_SERVER['PATH_INFO'])) {
    $path = trim($_SERVER['PATH_INFO'], '/');
} elseif (isset($_GET['path'])) {
    $path = trim($_GET['path'], '/');
} elseif (isset($_GET['endpoint'])) {
    $path = trim($_GET['endpoint'], '/');
}

$pathParts = $path ? explode('/', $path) : [];

// Route to appropriate endpoint
if (empty($pathParts)) {
    // API root - show available endpoints
    sendJsonResponse([
        'endpoints' => [
            'GET /api/agents' => 'List all agents',
            'GET /api/agents/{agent_name}' => 'Get specific agent details',
            'GET /api/channels' => 'List all channels',
            'GET /api/channels/{channel_id}' => 'Get specific channel details',
            'GET /api/logs' => 'List all log files',
            'GET /api/logs/agent/{agent_name}' => 'Get logs by agent',
            'GET /api/logs/channel/{channel_id}' => 'Get logs by channel',
            'GET /api/logs/{channel_id}/{agent_name}' => 'Get specific log file',
            'POST /api/search' => 'Search log content',
            'POST /api/validate/log/{filename}' => 'Validate log file',
            'POST /api/validate/directory' => 'Validate entire directory'
        ],
        'version' => WOLFIE_API_VERSION
    ]);
}

// Route: /api/agents
if ($pathParts[0] === 'agents') {
    if (count($pathParts) === 1) {
        // GET /api/agents - List all agents
        if ($method === 'GET') {
            $data = scanLogsDirectoryCached();
            $agents = [];
            
            foreach ($data['agents'] as $agentName => $agentData) {
                $agents[] = [
                    'name' => $agentData['name'],
                    'agent_id' => $agentData['agent_id'],
                    'log_count' => $agentData['count'],
                    'channels' => $agentData['channels'],
                    'last_log_date' => $agentData['last_log_date'],
                    'total_entries' => $agentData['total_entries']
                ];
            }
            
            sendJsonResponse($agents, 200, [
                'total_agents' => count($agents),
                'total_logs' => count($data['logs'])
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif (count($pathParts) === 2) {
        // GET /api/agents/{agent_name} - Get specific agent
        if ($method === 'GET') {
            $agentName = $pathParts[1];
            $data = scanLogsDirectoryCached();
            
            if (!isset($data['agents'][$agentName])) {
                sendErrorResponse('AGENT_NOT_FOUND', "Agent '{$agentName}' not found in logs directory", [], 404);
            }
            
            $agentData = $data['agents'][$agentName];
            $agentLogs = array_filter($data['logs'], function($log) use ($agentName) {
                return $log['agent'] === $agentName;
            });
            
            $logs = [];
            foreach ($agentLogs as $log) {
                $logs[] = [
                    'filename' => $log['filename'],
                    'channel' => $log['channel'],
                    'path' => $log['path'],
                    'entry_count' => isset($log['metadata']['log_entry_count']) ? $log['metadata']['log_entry_count'] : 0,
                    'last_modified' => $log['last_modified']
                ];
            }
            
            sendJsonResponse([
                'name' => $agentData['name'],
                'agent_id' => $agentData['agent_id'],
                'logs' => $logs,
                'channels' => $agentData['channels'],
                'statistics' => [
                    'total_logs' => $agentData['count'],
                    'total_entries' => $agentData['total_entries'],
                    'first_log_date' => $agentData['first_log_date'],
                    'last_log_date' => $agentData['last_log_date']
                ]
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } else {
        sendErrorResponse('INVALID_PATH', 'Invalid API path', [], 404);
    }
}

// Route: /api/channels
elseif ($pathParts[0] === 'channels') {
    if (count($pathParts) === 1) {
        // GET /api/channels - List all channels
        if ($method === 'GET') {
            $data = scanLogsDirectoryCached();
            $channels = [];
            
            foreach ($data['channels'] as $channelNum => $channelData) {
                $channels[] = [
                    'channel_id' => $channelData['number'],
                    'channel_number' => intval($channelData['number']),
                    'log_count' => $channelData['count'],
                    'agents' => $channelData['agents'],
                    'last_log_date' => $channelData['last_log_date']
                ];
            }
            
            sendJsonResponse($channels, 200, [
                'total_channels' => count($channels),
                'total_logs' => count($data['logs'])
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif (count($pathParts) === 2) {
        // GET /api/channels/{channel_id} - Get specific channel
        if ($method === 'GET') {
            $channelId = $pathParts[1];
            $data = scanLogsDirectoryCached();
            
            if (!isset($data['channels'][$channelId])) {
                sendErrorResponse('CHANNEL_NOT_FOUND', "Channel '{$channelId}' not found in logs directory", [], 404);
            }
            
            $channelData = $data['channels'][$channelId];
            $channelLogs = array_filter($data['logs'], function($log) use ($channelId) {
                return $log['channel'] === $channelId;
            });
            
            $logs = [];
            foreach ($channelLogs as $log) {
                $logs[] = [
                    'filename' => $log['filename'],
                    'agent' => $log['agent'],
                    'path' => $log['path'],
                    'entry_count' => isset($log['metadata']['log_entry_count']) ? $log['metadata']['log_entry_count'] : 0,
                    'last_modified' => $log['last_modified']
                ];
            }
            
            sendJsonResponse([
                'channel_id' => $channelData['number'],
                'channel_number' => intval($channelData['number']),
                'logs' => $logs,
                'agents' => $channelData['agents'],
                'statistics' => [
                    'total_logs' => $channelData['count'],
                    'total_entries' => $channelData['total_entries'],
                    'first_log_date' => $channelData['first_log_date'],
                    'last_log_date' => $channelData['last_log_date']
                ]
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } else {
        sendErrorResponse('INVALID_PATH', 'Invalid API path', [], 404);
    }
}

// Route: /api/logs
elseif ($pathParts[0] === 'logs') {
    if (count($pathParts) === 1) {
        // GET /api/logs - List all logs
        if ($method === 'GET') {
            $data = scanLogsDirectoryCached();
            
            // Apply filters
            $filteredLogs = $data['logs'];
            if (isset($_GET['agent'])) {
                $filteredLogs = array_filter($filteredLogs, function($log) {
                    return $log['agent'] === $_GET['agent'];
                });
            }
            if (isset($_GET['channel'])) {
                $filteredLogs = array_filter($filteredLogs, function($log) {
                    return $log['channel'] === $_GET['channel'];
                });
            }
            
            // Apply sorting
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'filename';
            usort($filteredLogs, function($a, $b) use ($sort) {
                return $a[$sort] <=> $b[$sort];
            });
            
            // Apply pagination
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
            $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
            $total = count($filteredLogs);
            $paginatedLogs = array_slice($filteredLogs, $offset, $limit);
            
            $logs = [];
            foreach ($paginatedLogs as $log) {
                $logs[] = [
                    'filename' => $log['filename'],
                    'channel' => $log['channel'],
                    'agent' => $log['agent'],
                    'path' => $log['path'],
                    'entry_count' => isset($log['metadata']['log_entry_count']) ? $log['metadata']['log_entry_count'] : 0,
                    'file_size' => $log['file_size'],
                    'last_modified' => $log['last_modified'],
                    'metadata' => isset($log['metadata']) ? $log['metadata'] : null
                ];
            }
            
            sendJsonResponse($logs, 200, [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif ($pathParts[1] === 'agent' && count($pathParts) === 3) {
        // GET /api/logs/agent/{agent_name}
        if ($method === 'GET') {
            $agentName = $pathParts[2];
            $data = scanLogsDirectoryCached();
            
            $agentLogs = array_filter($data['logs'], function($log) use ($agentName) {
                return $log['agent'] === $agentName;
            });
            
            if (empty($agentLogs)) {
                sendErrorResponse('AGENT_NOT_FOUND', "No logs found for agent '{$agentName}'", [], 404);
            }
            
            $logs = [];
            foreach ($agentLogs as $log) {
                $logs[] = [
                    'filename' => $log['filename'],
                    'channel' => $log['channel'],
                    'path' => $log['path'],
                    'entry_count' => isset($log['metadata']['log_entry_count']) ? $log['metadata']['log_entry_count'] : 0,
                    'last_modified' => $log['last_modified']
                ];
            }
            
            sendJsonResponse($logs, 200, [
                'agent' => $agentName,
                'total_logs' => count($logs)
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif ($pathParts[1] === 'channel' && count($pathParts) === 3) {
        // GET /api/logs/channel/{channel_id}
        if ($method === 'GET') {
            $channelId = $pathParts[2];
            $data = scanLogsDirectoryCached();
            
            $channelLogs = array_filter($data['logs'], function($log) use ($channelId) {
                return $log['channel'] === $channelId;
            });
            
            if (empty($channelLogs)) {
                sendErrorResponse('CHANNEL_NOT_FOUND', "No logs found for channel '{$channelId}'", [], 404);
            }
            
            $logs = [];
            foreach ($channelLogs as $log) {
                $logs[] = [
                    'filename' => $log['filename'],
                    'agent' => $log['agent'],
                    'path' => $log['path'],
                    'entry_count' => isset($log['metadata']['log_entry_count']) ? $log['metadata']['log_entry_count'] : 0,
                    'last_modified' => $log['last_modified']
                ];
            }
            
            sendJsonResponse($logs, 200, [
                'channel' => $channelId,
                'total_logs' => count($logs)
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif (count($pathParts) === 3) {
        // GET /api/logs/{channel_id}/{agent_name}
        if ($method === 'GET') {
            $channelId = $pathParts[1];
            $agentName = $pathParts[2];
            
            // Try both filename patterns
            $filename1 = sprintf("%03d_%s_log.md", intval($channelId), $agentName);
            $filename2 = sprintf("%03d_%s.md", intval($channelId), $agentName);
            
            $filepath1 = WOLFIE_LOGS_DIR . '/' . $filename1;
            $filepath2 = WOLFIE_LOGS_DIR . '/' . $filename2;
            
            $filepath = null;
            $filename = null;
            
            if (file_exists($filepath1)) {
                $filepath = $filepath1;
                $filename = $filename1;
            } elseif (file_exists($filepath2)) {
                $filepath = $filepath2;
                $filename = $filename2;
            } else {
                sendErrorResponse('LOG_NOT_FOUND', "Log file not found for channel '{$channelId}' and agent '{$agentName}'", [], 404);
            }
            
            $content = file_get_contents($filepath);
            $headers = parseWolfieHeaders($content);
            
            // Remove YAML frontmatter from content for display
            $bodyContent = $content;
            if (preg_match('/^---\s*\n.*?\n---\s*\n/s', $bodyContent)) {
                $bodyContent = preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $bodyContent);
                $bodyContent = trim($bodyContent);
            }
            
            sendJsonResponse([
                'filename' => $filename,
                'channel' => $channelId,
                'agent' => $agentName,
                'content' => $bodyContent,
                'metadata' => [
                    'agent_id' => $headers['agent_id'],
                    'channel_id' => $headers['channel_id'],
                    'version' => $headers['version'],
                    'entry_count' => $headers['log_entry_count'],
                    'last_log_date' => $headers['last_log_date']
                ],
                'yaml_frontmatter' => $headers['has_wolfie_header'] ? $headers : null,
                'file_info' => [
                    'size' => filesize($filepath),
                    'last_modified' => date('Y-m-d\TH:i:s\Z', filemtime($filepath)),
                    'created_at' => date('Y-m-d\TH:i:s\Z', filectime($filepath))
                ]
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } else {
        sendErrorResponse('INVALID_PATH', 'Invalid API path', [], 404);
    }
}

// Route: /api/search
elseif ($pathParts[0] === 'search') {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['query'])) {
            sendErrorResponse('INVALID_REQUEST', 'Missing required parameter: query', [], 400);
        }
        
        $query = $input['query'];
        $filters = isset($input['filters']) ? $input['filters'] : [];
        $options = isset($input['options']) ? $input['options'] : [];
        
        $startTime = microtime(true);
        $searchResults = searchLogs($query, $filters, $options);
        $queryTime = round((microtime(true) - $startTime) * 1000, 2);
        
        sendJsonResponse($searchResults['results'], 200, [
            'query' => $query,
            'filters_applied' => $filters,
            'total_results' => $searchResults['total_results'],
            'has_more' => $searchResults['has_more'],
            'query_time_ms' => $queryTime
        ]);
    } else {
        sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed. Use POST for search.', [], 405);
    }
}

// Route: /api/validate
elseif ($pathParts[0] === 'validate') {
    if ($pathParts[1] === 'log' && count($pathParts) === 3) {
        // POST /api/validate/log/{filename}
        if ($method === 'POST') {
            $filename = $pathParts[2];
            $filepath = WOLFIE_LOGS_DIR . '/' . $filename;
            
            if (!file_exists($filepath)) {
                sendErrorResponse('FILE_NOT_FOUND', "Log file '{$filename}' not found", [], 404);
            }
            
            $errors = validateLogFile($filepath, $filename);
            
            sendJsonResponse([
                'filename' => $filename,
                'valid' => empty($errors),
                'errors' => $errors
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } elseif ($pathParts[1] === 'directory' && count($pathParts) === 2) {
        // POST /api/validate/directory
        if ($method === 'POST') {
            $data = scanLogsDirectoryCached(true); // Force refresh
            $allErrors = [];
            $validCount = 0;
            
            foreach ($data['logs'] as $log) {
                $errors = validateLogFile($log['path'], $log['filename']);
                if (empty($errors)) {
                    $validCount++;
                } else {
                    $allErrors[] = [
                        'filename' => $log['filename'],
                        'errors' => $errors
                    ];
                }
            }
            
            sendJsonResponse([
                'total_files' => count($data['logs']),
                'valid_files' => $validCount,
                'errors' => count($allErrors),
                'validation_errors' => $allErrors
            ]);
        } else {
            sendErrorResponse('METHOD_NOT_ALLOWED', 'Method not allowed', [], 405);
        }
    } else {
        sendErrorResponse('INVALID_PATH', 'Invalid API path', [], 404);
    }
}

// Unknown route
else {
    sendErrorResponse('NOT_FOUND', 'API endpoint not found', [
        'path' => $path,
        'available_endpoints' => [
            '/api/agents',
            '/api/channels',
            '/api/logs',
            '/api/search',
            '/api/validate'
        ]
    ], 404);
}

/**
 * Validate log file
 * 
 * @param string $filepath Full file path
 * @param string $filename Filename for error reporting
 * @return array Validation errors
 */
function validateLogFile($filepath, $filename) {
    $errors = [];
    $content = file_get_contents($filepath);
    $headers = parseWolfieHeaders($content);
    $parsed = parseLogFilename($filename);
    
    // Validate YAML frontmatter exists
    if (!$headers['has_wolfie_header']) {
        $errors[] = [
            'error_type' => 'MISSING_YAML_FRONTMATTER',
            'message' => 'File does not contain YAML frontmatter',
            'suggestion' => 'Add YAML frontmatter with --- delimiters',
            'severity' => 'error'
        ];
    } else {
        // Validate required fields
        if (!isset($headers['agent_id']) || $headers['agent_id'] === null) {
            $errors[] = [
                'error_type' => 'MISSING_REQUIRED_FIELD',
                'field' => 'agent_id',
                'message' => "Required field 'agent_id' is missing in YAML frontmatter",
                'suggestion' => "Add 'agent_id: {number}' to YAML frontmatter",
                'severity' => 'error'
            ];
        }
        
        if (!isset($headers['channel_number']) && !isset($headers['channel_id'])) {
            $errors[] = [
                'error_type' => 'MISSING_REQUIRED_FIELD',
                'field' => 'channel_number',
                'message' => "Required field 'channel_number' or 'channel_id' is missing in YAML frontmatter",
                'suggestion' => "Add 'channel_number: {number}' to YAML frontmatter",
                'severity' => 'error'
            ];
        }
        
        if (!isset($headers['version'])) {
            $errors[] = [
                'error_type' => 'MISSING_REQUIRED_FIELD',
                'field' => 'version',
                'message' => "Required field 'version' is missing in YAML frontmatter",
                'suggestion' => "Add 'version: 2.0.6' to YAML frontmatter",
                'severity' => 'error'
            ];
        }
        
        // Validate channel number range
        $channelNum = isset($headers['channel_number']) ? $headers['channel_number'] : (isset($headers['channel_id']) ? $headers['channel_id'] : null);
        if ($channelNum !== null) {
            $channelInt = intval($channelNum);
            if ($channelInt < 0 || $channelInt > 999) {
                $errors[] = [
                    'error_type' => 'INVALID_CHANNEL_NUMBER',
                    'field' => 'channel_number',
                    'message' => "Channel number '{$channelNum}' is out of range (must be 000-999)",
                    'suggestion' => 'Channel number must be between 000 and 999',
                    'severity' => 'error'
                ];
            }
        }
    }
    
    // Validate filename matches content
    if ($parsed && $headers['has_wolfie_header']) {
        $filenameChannel = $parsed['channel'];
        $filenameAgent = $parsed['agent'];
        
        $contentChannel = isset($headers['channel_number']) ? sprintf("%03d", intval($headers['channel_number'])) : (isset($headers['channel_id']) ? sprintf("%03d", intval($headers['channel_id'])) : null);
        $contentAgent = isset($headers['agent_username']) ? strtoupper($headers['agent_username']) : null;
        
        if ($contentChannel && $filenameChannel !== $contentChannel) {
            $errors[] = [
                'error_type' => 'FILENAME_MISMATCH',
                'message' => "Filename indicates channel '{$filenameChannel}', but YAML frontmatter has channel_number: '{$contentChannel}'",
                'suggestion' => 'Either fix filename or fix YAML frontmatter to match',
                'severity' => 'warning'
            ];
        }
        
        if ($contentAgent && strtoupper($filenameAgent) !== $contentAgent) {
            $errors[] = [
                'error_type' => 'FILENAME_MISMATCH',
                'message' => "Filename indicates agent '{$filenameAgent}', but YAML frontmatter has agent_username: '{$contentAgent}'",
                'suggestion' => 'Either fix filename or fix YAML frontmatter to match',
                'severity' => 'warning'
            ];
        }
    }
    
    return $errors;
}

