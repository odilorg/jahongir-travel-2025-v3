<?php
/**
 * Admin Panel Configuration
 */

// Admin credentials (change these!)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', password_hash('jahongir2025', PASSWORD_BCRYPT)); // CHANGE THIS PASSWORD!

// Session configuration
define('SESSION_NAME', 'jahongir_admin_session');
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Paths
define('ADMIN_DIR', __DIR__);
define('ROOT_DIR', dirname(__DIR__));
define('TOURS_DIR', ROOT_DIR . '/uzbekistan-tours');
define('SAMARKAND_TOURS_DIR', ROOT_DIR . '/tours-from-samarkand');
define('BUKHARA_TOURS_DIR', ROOT_DIR . '/tours-from-bukhara');
define('KHIVA_TOURS_DIR', ROOT_DIR . '/tours-from-khiva');
define('BLOG_DIR', ROOT_DIR . '/blog');
define('IMAGES_DIR', ROOT_DIR . '/images');

// Site settings - Auto-detect localhost or use production URL
if (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)) {
    // Localhost - construct base URL
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptPath = dirname(dirname($_SERVER['SCRIPT_NAME'])); // Remove /admin from path
    define('SITE_URL', $protocol . '://' . $host . $scriptPath);
} else {
    // Production
    define('SITE_URL', 'https://jahongir-travel.uz');
}
define('SITE_NAME', 'Jahongir Travel');

// File patterns
define('TOUR_FILE_PATTERN', '*.php');
define('BLOG_FILE_PATTERN', '*.php');

// Excluded files (not editable tours/posts)
define('EXCLUDED_FILES', [
    'index.php',
    'contact.php',
    'includes',
    'mailer.php',
    'mailer-tours.php'
]);

// Backup directory
define('BACKUP_DIR', ADMIN_DIR . '/backups');
if (!file_exists(BACKUP_DIR)) {
    mkdir(BACKUP_DIR, 0755, true);
}

// CSRF token configuration
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRE', 3600);

// Upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('UPLOAD_DIR', ROOT_DIR . '/images/uploads');

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Timezone
date_default_timezone_set('Asia/Samarkand');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
