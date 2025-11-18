<?php
/**
 * WOLFIE Headers Database Configuration
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: Database connection configuration
 * WHERE: public/config/database.php
 * WHEN: 2025-11-18
 * WHY: Centralize database connection for shared hosting compatibility
 * HOW: PDO connection with error handling
 * 
 * Version: 2.0.8
 */

// Database configuration
// Update these values for your environment
define('WOLFIE_DB_HOST', 'localhost');
define('WOLFIE_DB_NAME', 'lupopedia');
define('WOLFIE_DB_USER', 'root');
define('WOLFIE_DB_PASS', '');
define('WOLFIE_DB_CHARSET', 'utf8mb4');

/**
 * Get database connection
 * 
 * @return PDO Database connection
 * @throws Exception If connection fails
 */
function getWOLFIEDatabaseConnection() {
    static $connection = null;
    
    if ($connection === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                WOLFIE_DB_HOST,
                WOLFIE_DB_NAME,
                WOLFIE_DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $connection = new PDO($dsn, WOLFIE_DB_USER, WOLFIE_DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("WOLFIE Headers Database Connection Failed: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $connection;
}

/**
 * Backward compatibility wrapper
 * For code that still uses getDatabaseConnection()
 * 
 * @return PDO Database connection
 * @deprecated Use getWOLFIEDatabaseConnection() instead
 */
function getDatabaseConnection() {
    return getWOLFIEDatabaseConnection();
}

