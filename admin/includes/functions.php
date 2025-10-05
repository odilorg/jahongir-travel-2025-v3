<?php
/**
 * Helper Functions for Admin Panel
 */

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Show success message
 */
function setSuccessMessage($message) {
    startAdminSession();
    $_SESSION['success_message'] = $message;
}

/**
 * Show error message
 */
function setErrorMessage($message) {
    startAdminSession();
    $_SESSION['error_message'] = $message;
}

/**
 * Get and clear success message
 */
function getSuccessMessage() {
    startAdminSession();
    if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        return $message;
    }
    return null;
}

/**
 * Get and clear error message
 */
function getErrorMessage() {
    startAdminSession();
    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        return $message;
    }
    return null;
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'success') {
    if ($type === 'success') {
        setSuccessMessage($message);
    } else {
        setErrorMessage($message);
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Time ago format
 */
function timeAgo($timestamp) {
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('M j, Y', $timestamp);
    }
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Truncate string
 */
function truncate($string, $length = 100, $append = '...') {
    if (strlen($string) <= $length) {
        return $string;
    }
    return substr($string, 0, $length) . $append;
}

/**
 * Check if request is POST
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Get POST data
 */
function getPost($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Get GET data
 */
function getQuery($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Validate file upload
 */
function validateImageUpload($file) {
    $errors = [];

    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $errors[] = 'No file uploaded';
        return $errors;
    }

    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $errors[] = 'File size exceeds maximum allowed size (' . formatFileSize(MAX_UPLOAD_SIZE) . ')';
    }

    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        $errors[] = 'Invalid file type. Allowed types: JPG, PNG, GIF, WebP';
    }

    // Check for valid image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        $errors[] = 'File is not a valid image';
    }

    return $errors;
}

/**
 * Generate safe filename
 */
function generateSafeFilename($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $basename = preg_replace('/[^a-zA-Z0-9-_]/', '-', $basename);
    $basename = preg_replace('/-+/', '-', $basename);
    $basename = trim($basename, '-');

    return $basename . '.' . $extension;
}

/**
 * Get all PHP files from directory
 */
function getPhpFiles($directory, $excludeFiles = []) {
    $files = [];

    if (!is_dir($directory)) {
        return $files;
    }

    $iterator = new DirectoryIterator($directory);

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filename = $file->getFilename();

            // Skip excluded files
            if (in_array($filename, $excludeFiles)) {
                continue;
            }

            $files[] = [
                'filename' => $filename,
                'path' => $file->getPathname(),
                'modified' => $file->getMTime(),
                'size' => $file->getSize()
            ];
        }
    }

    // Sort by modified time (newest first)
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    return $files;
}

/**
 * Backup file before editing
 */
function backupFile($filepath) {
    if (!file_exists($filepath)) {
        return false;
    }

    $backupPath = BACKUP_DIR . '/' . basename($filepath) . '.' . time() . '.backup';
    return copy($filepath, $backupPath);
}

/**
 * Clean old backups (keep last 10 per file)
 */
function cleanOldBackups($filename) {
    $backups = glob(BACKUP_DIR . '/' . $filename . '.*.backup');

    if (count($backups) > 10) {
        // Sort by modification time
        usort($backups, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Keep first 10, delete rest
        $toDelete = array_slice($backups, 10);
        foreach ($toDelete as $backup) {
            unlink($backup);
        }
    }
}
?>
