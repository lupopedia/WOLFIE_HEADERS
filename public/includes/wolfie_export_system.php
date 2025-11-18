<?php
/**
 * WOLFIE Headers Export System - v2.2.2
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: Export functionality for log data (CSV and JSON formats)
 * WHERE: public/includes/wolfie_export_system.php
 * WHEN: 2025-11-18
 * WHY: Enable users to export filtered log data for analysis
 * HOW: Export to CSV or JSON format with proper formatting
 * 
 * Version: 2.2.2
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/system.php';

/**
 * Export file logs to CSV
 * 
 * @param array $logs Log entries to export
 * @param string $filename Output filename (optional)
 * @return void Outputs CSV file
 */
function exportFileLogsToCSV($logs, $filename = null) {
    if (!$filename) {
        $filename = 'wolfie_logs_export_' . date('Y-m-d_His') . '.csv';
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, ['Type', 'Channel', 'Agent', 'Filename', 'Path', 'Size', 'Last Modified']);
    
    foreach ($logs as $log) {
        $size = 0;
        $lastModified = '';
        
        if (isset($log['path']) && file_exists($log['path'])) {
            $size = filesize($log['path']);
            $lastModified = date('Y-m-d H:i:s', filemtime($log['path']));
        }
        
        fputcsv($output, [
            'File',
            $log['channel'] ?? '',
            $log['agent'] ?? '',
            $log['filename'] ?? '',
            $log['path'] ?? '',
            $size,
            $lastModified
        ]);
    }
    
    fclose($output);
    exit;
}

/**
 * Export database logs to CSV
 * 
 * @param array $logs Log entries to export
 * @param string $tableName Table name
 * @param string $filename Output filename (optional)
 * @return void Outputs CSV file
 */
function exportDatabaseLogsToCSV($logs, $tableName = '', $filename = null) {
    if (!$filename) {
        $filename = 'wolfie_db_logs_export_' . date('Y-m-d_His') . '.csv';
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    if (empty($logs)) {
        fputcsv($output, ['No data to export']);
        fclose($output);
        exit;
    }
    
    // Get column names from first entry
    $columns = array_keys($logs[0]);
    fputcsv($output, array_merge(['Table'], $columns));
    
    foreach ($logs as $log) {
        $row = [$tableName];
        foreach ($columns as $col) {
            $value = $log[$col] ?? '';
            // Handle JSON/arrays
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $row[] = $value;
        }
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

/**
 * Export logs to JSON
 * 
 * @param array $data Data to export (can include both files and database)
 * @param string $filename Output filename (optional)
 * @return void Outputs JSON file
 */
function exportLogsToJSON($data, $filename = null) {
    if (!$filename) {
        $filename = 'wolfie_logs_export_' . date('Y-m-d_His') . '.json';
    }
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $export = [
        'export_date' => date('Y-m-d H:i:s'),
        'version' => WOLFIE_HEADERS_VERSION,
        'data' => $data
    ];
    
    echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Export combined logs (files + database) to CSV
 * 
 * @param array $fileLogs File log entries
 * @param array $databaseLogs Database log entries
 * @param string $filename Output filename (optional)
 * @return void Outputs CSV file
 */
function exportCombinedLogsToCSV($fileLogs, $databaseLogs, $filename = null) {
    if (!$filename) {
        $filename = 'wolfie_combined_logs_export_' . date('Y-m-d_His') . '.csv';
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, ['Type', 'Source', 'Channel', 'Agent', 'ID', 'Timestamp', 'Data']);
    
    // Export file logs
    foreach ($fileLogs as $log) {
        fputcsv($output, [
            'File',
            'logs/' . ($log['filename'] ?? ''),
            $log['channel'] ?? '',
            $log['agent'] ?? '',
            '',
            '',
            json_encode($log)
        ]);
    }
    
    // Export database logs
    foreach ($databaseLogs as $log) {
        $data = $log['data'] ?? $log;
        fputcsv($output, [
            'Database',
            $log['table'] ?? '',
            $log['channel'] ?? '',
            $log['agent'] ?? '',
            $log['id'] ?? '',
            $log['timestamp'] ?? '',
            json_encode($data)
        ]);
    }
    
    fclose($output);
    exit;
}

