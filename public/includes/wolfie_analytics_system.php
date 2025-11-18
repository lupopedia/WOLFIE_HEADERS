<?php
/**
 * WOLFIE Headers Analytics System - v2.2.2
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: Analytics and insights for log data
 * WHERE: public/includes/wolfie_analytics_system.php
 * WHEN: 2025-11-18
 * WHY: Provide insights into log patterns, activity trends, and system usage
 * HOW: Aggregate queries and calculations for analytics
 * 
 * Version: 2.2.2
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';
require_once __DIR__ . '/wolfie_database_logs_system.php';

/**
 * Get most active agents from file logs
 * 
 * @param string $logsDir Logs directory path
 * @param int $limit Number of results
 * @return array Most active agents with counts
 */
function getMostActiveAgentsFromFiles($logsDir = null, $limit = 10) {
    if (!$logsDir) {
        $logsDir = __DIR__ . '/../logs';
    }
    
    $agents = [];
    
    if (!is_dir($logsDir)) {
        return $agents;
    }
    
    $files = glob($logsDir . '/*_*_log.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        if (preg_match('/^\d+_([^_]+)_log\.md$/', $filename, $matches)) {
            $agent = $matches[1];
            if (!isset($agents[$agent])) {
                $agents[$agent] = [
                    'name' => $agent,
                    'file_count' => 0,
                    'total_size' => 0
                ];
            }
            $agents[$agent]['file_count']++;
            if (file_exists($file)) {
                $agents[$agent]['total_size'] += filesize($file);
            }
        }
    }
    
    // Sort by file count
    usort($agents, function($a, $b) {
        return $b['file_count'] - $a['file_count'];
    });
    
    return array_slice($agents, 0, $limit);
}

/**
 * Get most active channels from file logs
 * 
 * @param string $logsDir Logs directory path
 * @param int $limit Number of results
 * @return array Most active channels with counts
 */
function getMostActiveChannelsFromFiles($logsDir = null, $limit = 10) {
    if (!$logsDir) {
        $logsDir = __DIR__ . '/../logs';
    }
    
    $channels = [];
    
    if (!is_dir($logsDir)) {
        return $channels;
    }
    
    $files = glob($logsDir . '/*_*_log.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        if (preg_match('/^(\d+)_[^_]+_log\.md$/', $filename, $matches)) {
            $channel = $matches[1];
            if (!isset($channels[$channel])) {
                $channels[$channel] = [
                    'channel' => $channel,
                    'file_count' => 0,
                    'total_size' => 0
                ];
            }
            $channels[$channel]['file_count']++;
            if (file_exists($file)) {
                $channels[$channel]['total_size'] += filesize($file);
            }
        }
    }
    
    // Sort by file count
    usort($channels, function($a, $b) {
        return $b['file_count'] - $a['file_count'];
    });
    
    return array_slice($channels, 0, $limit);
}

/**
 * Get most active agents from database logs
 * 
 * @param PDO $db Database connection
 * @param array $tableNames Array of table names
 * @param int $limit Number of results
 * @return array Most active agents with counts
 */
function getMostActiveAgentsFromDatabase($db, $tableNames, $limit = 10) {
    $agents = [];
    
    if (!$db || empty($tableNames)) {
        return $agents;
    }
    
    try {
        foreach ($tableNames as $tableName) {
            $query = "SELECT agent_name, COUNT(*) as count 
                      FROM `{$tableName}` 
                      WHERE agent_name IS NOT NULL AND agent_name != ''
                      GROUP BY agent_name 
                      ORDER BY count DESC 
                      LIMIT {$limit}";
            
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $agentName = $row['agent_name'];
                if (!isset($agents[$agentName])) {
                    $agents[$agentName] = [
                        'name' => $agentName,
                        'count' => 0,
                        'tables' => []
                    ];
                }
                $agents[$agentName]['count'] += intval($row['count']);
                $agents[$agentName]['tables'][] = $tableName;
            }
        }
        
        // Sort by count
        usort($agents, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return array_slice($agents, 0, $limit);
    } catch (Exception $e) {
        error_log("Database analytics failed: " . $e->getMessage());
        return [];
    }
}

/**
 * Get most active channels from database logs
 * 
 * @param PDO $db Database connection
 * @param array $tableNames Array of table names
 * @param int $limit Number of results
 * @return array Most active channels with counts
 */
function getMostActiveChannelsFromDatabase($db, $tableNames, $limit = 10) {
    $channels = [];
    
    if (!$db || empty($tableNames)) {
        return $channels;
    }
    
    try {
        foreach ($tableNames as $tableName) {
            $query = "SELECT channel_id, COUNT(*) as count 
                      FROM `{$tableName}` 
                      WHERE channel_id IS NOT NULL
                      GROUP BY channel_id 
                      ORDER BY count DESC 
                      LIMIT {$limit}";
            
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $channelId = $row['channel_id'];
                if (!isset($channels[$channelId])) {
                    $channels[$channelId] = [
                        'channel' => $channelId,
                        'count' => 0,
                        'tables' => []
                    ];
                }
                $channels[$channelId]['count'] += intval($row['count']);
                $channels[$channelId]['tables'][] = $tableName;
            }
        }
        
        // Sort by count
        usort($channels, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return array_slice($channels, 0, $limit);
    } catch (Exception $e) {
        error_log("Database analytics failed: " . $e->getMessage());
        return [];
    }
}

/**
 * Get log activity trends (entries over time)
 * 
 * @param PDO $db Database connection
 * @param array $tableNames Array of table names
 * @param int $days Number of days to analyze
 * @return array Activity trends by date
 */
function getLogActivityTrends($db, $tableNames, $days = 30) {
    $trends = [];
    
    if (!$db || empty($tableNames)) {
        return $trends;
    }
    
    try {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        foreach ($tableNames as $tableName) {
            // Check if table has created_at column
            $stmt = $db->query("DESCRIBE `{$tableName}`");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (!in_array('created_at', $columns)) {
                continue;
            }
            
            $query = "SELECT DATE(created_at) as date, COUNT(*) as count 
                      FROM `{$tableName}` 
                      WHERE created_at >= :start_date
                      GROUP BY DATE(created_at) 
                      ORDER BY date ASC";
            
            $stmt = $db->prepare($query);
            $stmt->execute([':start_date' => $startDate]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $date = $row['date'];
                if (!isset($trends[$date])) {
                    $trends[$date] = [
                        'date' => $date,
                        'count' => 0
                    ];
                }
                $trends[$date]['count'] += intval($row['count']);
            }
        }
        
        // Sort by date
        ksort($trends);
        
        return array_values($trends);
    } catch (Exception $e) {
        error_log("Activity trends failed: " . $e->getMessage());
        return [];
    }
}

/**
 * Get comprehensive analytics
 * 
 * @param string $logsDir Logs directory path
 * @param PDO $db Database connection (optional)
 * @param array $tableNames Database table names (optional)
 * @return array Comprehensive analytics data
 */
function getComprehensiveAnalytics($logsDir = null, $db = null, $tableNames = []) {
    $analytics = [
        'file_logs' => [
            'total_files' => 0,
            'total_size' => 0,
            'most_active_agents' => [],
            'most_active_channels' => []
        ],
        'database_logs' => [
            'total_entries' => 0,
            'most_active_agents' => [],
            'most_active_channels' => [],
            'activity_trends' => []
        ],
        'combined' => [
            'total_entries' => 0,
            'most_active_agents' => [],
            'most_active_channels' => []
        ]
    ];
    
    // File logs analytics
    if ($logsDir && is_dir($logsDir)) {
        $files = glob($logsDir . '/*_*_log.md');
        $analytics['file_logs']['total_files'] = count($files);
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                $analytics['file_logs']['total_size'] += filesize($file);
            }
        }
        
        $analytics['file_logs']['most_active_agents'] = getMostActiveAgentsFromFiles($logsDir, 10);
        $analytics['file_logs']['most_active_channels'] = getMostActiveChannelsFromFiles($logsDir, 10);
    }
    
    // Database logs analytics
    if ($db && !empty($tableNames)) {
        try {
            // Get total entries
            foreach ($tableNames as $tableName) {
                $stmt = $db->query("SELECT COUNT(*) as cnt FROM `{$tableName}`");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
                $analytics['database_logs']['total_entries'] += intval($count);
            }
            
            $analytics['database_logs']['most_active_agents'] = getMostActiveAgentsFromDatabase($db, $tableNames, 10);
            $analytics['database_logs']['most_active_channels'] = getMostActiveChannelsFromDatabase($db, $tableNames, 10);
            $analytics['database_logs']['activity_trends'] = getLogActivityTrends($db, $tableNames, 30);
        } catch (Exception $e) {
            error_log("Database analytics failed: " . $e->getMessage());
        }
    }
    
    // Combined analytics
    $analytics['combined']['total_entries'] = $analytics['file_logs']['total_files'] + $analytics['database_logs']['total_entries'];
    
    // Merge most active agents
    $combinedAgents = [];
    foreach ($analytics['file_logs']['most_active_agents'] as $agent) {
        $name = $agent['name'];
        if (!isset($combinedAgents[$name])) {
            $combinedAgents[$name] = ['name' => $name, 'file_count' => 0, 'db_count' => 0];
        }
        $combinedAgents[$name]['file_count'] = $agent['file_count'];
    }
    foreach ($analytics['database_logs']['most_active_agents'] as $agent) {
        $name = $agent['name'];
        if (!isset($combinedAgents[$name])) {
            $combinedAgents[$name] = ['name' => $name, 'file_count' => 0, 'db_count' => 0];
        }
        $combinedAgents[$name]['db_count'] = $agent['count'];
    }
    usort($combinedAgents, function($a, $b) {
        return ($b['file_count'] + $b['db_count']) - ($a['file_count'] + $a['db_count']);
    });
    $analytics['combined']['most_active_agents'] = array_slice($combinedAgents, 0, 10);
    
    return $analytics;
}

