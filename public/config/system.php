<?php
/**
 * WOLFIE Headers System Configuration
 * 
 * WHO: Captain WOLFIE (Agent 008)
 * WHAT: System configuration (version, platform, flags)
 * WHERE: public/config/system.php
 * WHEN: 2025-11-18
 * WHY: Centralize system configuration for consistency
 * HOW: Defines version, platform detection, development flags
 * 
 * Version: 2.1.0
 */

// Version Information
define('WOLFIE_HEADERS_VERSION', '2.1.0');
define('WOLFIE_HEADERS_VERSION_MAJOR', 2);
define('WOLFIE_HEADERS_VERSION_MINOR', 1);
define('WOLFIE_HEADERS_VERSION_PATCH', 0);

// Platform Detection
define('WOLFIE_IS_WINDOWS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
define('WOLFIE_IS_LINUX', !WOLFIE_IS_WINDOWS && strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX');
define('WOLFIE_IS_UNIX', !WOLFIE_IS_WINDOWS);

// Development Flags
define('WOLFIE_BORN_YESTERDAY', false); // Set to true for fresh installations
define('WOLFIE_DEBUG_MODE', false); // Set to true for debugging
define('WOLFIE_SHARED_HOSTING', true); // Set to true if on shared hosting

// Path Configuration
define('WOLFIE_PUBLIC_DIR', __DIR__ . '/..');
define('WOLFIE_LOGS_DIR', WOLFIE_PUBLIC_DIR . '/logs');
define('WOLFIE_INCLUDES_DIR', WOLFIE_PUBLIC_DIR . '/includes');
define('WOLFIE_CONFIG_DIR', __DIR__);

/**
 * Get WOLFIE Headers version
 * 
 * @return string Version string
 */
function getWOLFIEHeadersVersion() {
    return WOLFIE_HEADERS_VERSION;
}

/**
 * Check if running on Windows
 * 
 * @return bool True if Windows
 */
function isWOLFIEWindows() {
    return WOLFIE_IS_WINDOWS;
}

/**
 * Check if running on Linux/Unix
 * 
 * @return bool True if Linux/Unix
 */
function isWOLFIELinux() {
    return WOLFIE_IS_LINUX;
}

/**
 * Check if "born yesterday" (fresh installation)
 * 
 * @return bool True if fresh installation
 */
function isWOLFIEBornYesterday() {
    return WOLFIE_BORN_YESTERDAY;
}

/**
 * Check if debug mode enabled
 * 
 * @return bool True if debug mode
 */
function isWOLFIEDebugMode() {
    return WOLFIE_DEBUG_MODE;
}

/**
 * Check if on shared hosting
 * 
 * @return bool True if shared hosting
 */
function isWOLFIESharedHosting() {
    return WOLFIE_SHARED_HOSTING;
}

