<?php
/**
 * WOLFIE Headers v2.1.0 - Standard Error Handler
 * 
 * Provides standardized error response format across all API endpoints
 * 
 * @package WOLFIE_Headers
 * @version 2.1.0
 * @author Captain WOLFIE (Agent 008, Eric Robin Gerdes)
 * @date 2025-11-18
 */

/**
 * Standard error response format
 * 
 * @param string $code Error code (e.g., 'VALIDATION_ERROR', 'NOT_FOUND', 'SERVER_ERROR')
 * @param string $message Human-readable error message
 * @param array $details Additional error details (optional)
 * @param string|null $suggestion Helpful suggestion for resolving the error (optional)
 * @param int $httpStatus HTTP status code (default: 400)
 * @return array Standardized error response array
 */
function wolfieErrorResponse($code, $message, $details = [], $suggestion = null, $httpStatus = 400) {
    $response = [
        'success' => false,
        'error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details,
            'timestamp' => date('c') // ISO 8601 format
        ]
    ];
    
    if ($suggestion !== null) {
        $response['error']['suggestion'] = $suggestion;
    }
    
    // Set HTTP status code
    http_response_code($httpStatus);
    
    return $response;
}

/**
 * Send JSON error response and exit
 * 
 * @param string $code Error code
 * @param string $message Error message
 * @param array $details Error details
 * @param string|null $suggestion Helpful suggestion
 * @param int $httpStatus HTTP status code
 * @return void Exits after sending response
 */
function wolfieSendError($code, $message, $details = [], $suggestion = null, $httpStatus = 400) {
    header('Content-Type: application/json');
    echo json_encode(wolfieErrorResponse($code, $message, $details, $suggestion, $httpStatus), JSON_PRETTY_PRINT);
    exit;
}

/**
 * Standard success response format
 * 
 * @param mixed $data Response data
 * @param string|null $message Success message (optional)
 * @param array $meta Additional metadata (optional)
 * @param int $httpStatus HTTP status code (default: 200)
 * @return array Standardized success response array
 */
function wolfieSuccessResponse($data, $message = null, $meta = [], $httpStatus = 200) {
    $response = [
        'success' => true,
        'data' => $data,
        'timestamp' => date('c')
    ];
    
    if ($message !== null) {
        $response['message'] = $message;
    }
    
    if (!empty($meta)) {
        $response['meta'] = $meta;
    }
    
    // Set HTTP status code
    http_response_code($httpStatus);
    
    return $response;
}

/**
 * Send JSON success response
 * 
 * @param mixed $data Response data
 * @param string|null $message Success message
 * @param array $meta Additional metadata
 * @param int $httpStatus HTTP status code
 * @return void
 */
function wolfieSendSuccess($data, $message = null, $meta = [], $httpStatus = 200) {
    header('Content-Type: application/json');
    echo json_encode(wolfieSuccessResponse($data, $message, $meta, $httpStatus), JSON_PRETTY_PRINT);
}

/**
 * Validate channel ID
 * 
 * @param mixed $channelId Channel ID to validate
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validateChannelId($channelId) {
    if (!is_numeric($channelId)) {
        return [
            'valid' => false,
            'error' => 'Channel ID must be numeric'
        ];
    }
    
    $channelId = (int)$channelId;
    
    if ($channelId < 0 || $channelId > 999) {
        return [
            'valid' => false,
            'error' => 'Channel ID must be between 0 and 999'
        ];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Validate agent ID
 * 
 * @param mixed $agentId Agent ID to validate
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validateAgentId($agentId) {
    if (!is_numeric($agentId)) {
        return [
            'valid' => false,
            'error' => 'Agent ID must be numeric'
        ];
    }
    
    $agentId = (int)$agentId;
    
    if ($agentId < 0 || $agentId > 999) {
        return [
            'valid' => false,
            'error' => 'Agent ID must be between 0 and 999'
        ];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Validate agent name
 * 
 * @param mixed $agentName Agent name to validate
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validateAgentName($agentName) {
    if (empty($agentName) || !is_string($agentName)) {
        return [
            'valid' => false,
            'error' => 'Agent name must be a non-empty string'
        ];
    }
    
    // Sanitize agent name (alphanumeric, underscore, hyphen only)
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $agentName)) {
        return [
            'valid' => false,
            'error' => 'Agent name contains invalid characters (alphanumeric, underscore, hyphen only)'
        ];
    }
    
    if (strlen($agentName) > 100) {
        return [
            'valid' => false,
            'error' => 'Agent name must be 100 characters or less'
        ];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Validate table name
 * 
 * @param mixed $tableName Table name to validate
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validateTableName($tableName) {
    if (empty($tableName) || !is_string($tableName)) {
        return [
            'valid' => false,
            'error' => 'Table name must be a non-empty string'
        ];
    }
    
    // Sanitize table name (alphanumeric, underscore only)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        return [
            'valid' => false,
            'error' => 'Table name contains invalid characters (alphanumeric, underscore only)'
        ];
    }
    
    if (strlen($tableName) > 64) {
        return [
            'valid' => false,
            'error' => 'Table name must be 64 characters or less'
        ];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Validate row ID
 * 
 * @param mixed $rowId Row ID to validate
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validateRowId($rowId) {
    if (!is_numeric($rowId)) {
        return [
            'valid' => false,
            'error' => 'Row ID must be numeric'
        ];
    }
    
    $rowId = (int)$rowId;
    
    if ($rowId < 1) {
        return [
            'valid' => false,
            'error' => 'Row ID must be greater than 0'
        ];
    }
    
    return ['valid' => true, 'error' => null];
}

/**
 * Sanitize string input
 * 
 * @param mixed $input Input to sanitize
 * @param int $maxLength Maximum length (default: 255)
 * @return string Sanitized string
 */
function sanitizeStringInput($input, $maxLength = 255) {
    if (!is_string($input)) {
        $input = (string)$input;
    }
    
    $input = trim($input);
    $input = substr($input, 0, $maxLength);
    
    return $input;
}

/**
 * Common error codes
 */
define('WOLFIE_ERROR_VALIDATION', 'VALIDATION_ERROR');
define('WOLFIE_ERROR_NOT_FOUND', 'NOT_FOUND');
define('WOLFIE_ERROR_UNAUTHORIZED', 'UNAUTHORIZED');
define('WOLFIE_ERROR_FORBIDDEN', 'FORBIDDEN');
define('WOLFIE_ERROR_SERVER_ERROR', 'SERVER_ERROR');
define('WOLFIE_ERROR_BAD_REQUEST', 'BAD_REQUEST');
define('WOLFIE_ERROR_METHOD_NOT_ALLOWED', 'METHOD_NOT_ALLOWED');

