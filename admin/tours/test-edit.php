<?php
/**
 * Debug Edit Page
 */

require_once '../config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/file-parser.php';

startAdminSession();

// Mock login for testing
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';
$_SESSION['last_activity'] = time();

echo "<h1>Edit Tour Debug</h1>";

$filename = $_GET['file'] ?? '';
$directory = $_GET['dir'] ?? '';

echo "<p><strong>Filename:</strong> " . htmlspecialchars($filename) . "</p>";
echo "<p><strong>Directory:</strong> " . htmlspecialchars($directory) . "</p>";

if (empty($filename) || empty($directory)) {
    echo "<p style='color:red;'>ERROR: Missing file or directory parameter</p>";
    exit;
}

$filepath = $directory . DIRECTORY_SEPARATOR . $filename;
echo "<p><strong>Full Path:</strong> " . htmlspecialchars($filepath) . "</p>";

if (!file_exists($filepath)) {
    echo "<p style='color:red;'>ERROR: File does not exist!</p>";
    exit;
}

echo "<p style='color:green;'>✓ File exists!</p>";

// Try to parse
$tourData = parsePhpFile($filepath);

if (!$tourData) {
    echo "<p style='color:red;'>ERROR: Failed to parse file</p>";
    exit;
}

echo "<p style='color:green;'>✓ File parsed successfully!</p>";

echo "<h2>Parsed Data:</h2>";
echo "<pre>";
print_r($tourData);
echo "</pre>";

echo "<h2>Test Update</h2>";
echo "<p>If you see this, the edit page should work!</p>";
echo "<p><a href='edit.php?file=" . urlencode($filename) . "&dir=" . urlencode($directory) . "'>Go to actual edit page</a></p>";
?>
