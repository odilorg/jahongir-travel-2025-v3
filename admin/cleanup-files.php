<?php
/**
 * File Cleanup Tool - Remove Unnecessary Files
 * Password: jahongir2025
 */

session_start();

$admin_password = 'jahongir2025';
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>File Cleanup - Admin Login</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; }
                .login-form { max-width: 300px; margin: 100px auto; background: white; padding: 20px; border-radius: 5px; }
                input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; }
                button { width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 3px; }
            </style>
        </head>
        <body>
            <div class="login-form">
                <h2>Admin Login</h2>
                <form method="post">
                    <input type="password" name="password" placeholder="Enter password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Files to potentially remove
$files_to_check = [
    '../test.php' => ['type' => 'test', 'safe_to_delete' => true],
    '../seo-test.php' => ['type' => 'test', 'safe_to_delete' => true],
    '../image-seo-test.php' => ['type' => 'test', 'safe_to_delete' => true],
    '../.htaccess.backup' => ['type' => 'backup', 'safe_to_delete' => false], // Keep as backup
    '../google-my-business-optimization-guide.md' => ['type' => 'documentation', 'safe_to_delete' => false], // Archive instead
    '../seo-monitoring-guide.md' => ['type' => 'documentation', 'safe_to_delete' => false], // Archive instead
];

$deleted_files = [];
$errors = [];

// Handle file deletion
if (isset($_POST['delete_files']) && isset($_POST['files'])) {
    foreach ($_POST['files'] as $file) {
        if (isset($files_to_check[$file])) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    $deleted_files[] = $file;
                } else {
                    $errors[] = "Failed to delete: $file";
                }
            } else {
                $errors[] = "File not found: $file";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>File Cleanup Tool - Jahongir Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; position: relative; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .file-item { padding: 15px; margin: 10px 0; background: #f8f9fa; border-left: 4px solid #3498db; border-radius: 3px; }
        .file-item.safe { border-left-color: #27ae60; }
        .file-item.warning { border-left-color: #f39c12; }
        .file-item.danger { border-left-color: #e74c3c; }
        .btn { background: #3498db; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .logout { position: absolute; top: 20px; right: 20px; }
        .logout a { color: white; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #f5c6cb; }
        .warning-box { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #ffeaa7; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        label { cursor: pointer; display: block; }
        input[type="checkbox"] { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🧹 File Cleanup Tool</h1>
        <p>Remove unnecessary test and temporary files</p>
        <div class="logout">
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Results -->
        <?php if (!empty($deleted_files)): ?>
        <div class="success">
            <strong>✅ Successfully deleted <?php echo count($deleted_files); ?> file(s):</strong>
            <ul style="margin-top: 10px; margin-left: 20px;">
                <?php foreach ($deleted_files as $file): ?>
                <li><?php echo htmlspecialchars($file); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="error">
            <strong>❌ Errors:</strong>
            <ul style="margin-top: 10px; margin-left: 20px;">
                <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Warning -->
        <div class="card">
            <h3>⚠️ Important Warning</h3>
            <div class="warning-box">
                <strong>Before deleting files:</strong>
                <ul style="margin-left: 20px; line-height: 1.6; margin-top: 10px;">
                    <li>Make sure you have a backup of your website</li>
                    <li>Only delete files marked as "Safe to Delete"</li>
                    <li>Documentation files should be archived, not deleted</li>
                    <li>Test files can be safely removed from production</li>
                </ul>
            </div>
        </div>

        <!-- Files to Check -->
        <div class="card">
            <h3>Files Found</h3>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">Select</th>
                            <th>File Path</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Recommendation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $file_count = 0;
                        foreach ($files_to_check as $file => $info):
                            $exists = file_exists($file);
                            $size = $exists ? filesize($file) : 0;
                            $file_count++;
                        ?>
                        <tr>
                            <td>
                                <?php if ($exists && $info['safe_to_delete']): ?>
                                <input type="checkbox" name="files[]" value="<?php echo htmlspecialchars($file); ?>" id="file_<?php echo $file_count; ?>">
                                <?php endif; ?>
                            </td>
                            <td>
                                <label for="file_<?php echo $file_count; ?>">
                                    <strong><?php echo htmlspecialchars($file); ?></strong>
                                </label>
                            </td>
                            <td><?php echo ucfirst($info['type']); ?></td>
                            <td><?php echo $exists ? round($size / 1024, 2) . ' KB' : '-'; ?></td>
                            <td>
                                <?php if ($exists): ?>
                                    <span style="color: #f39c12;">⚠️ Exists</span>
                                <?php else: ?>
                                    <span style="color: #27ae60;">✅ Already removed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($info['safe_to_delete']): ?>
                                    <span style="color: #27ae60;">✅ Safe to delete</span>
                                <?php else: ?>
                                    <span style="color: #f39c12;">⚠️ Archive instead</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    <button type="submit" name="delete_files" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the selected files? This action cannot be undone.')">
                        🗑️ Delete Selected Files
                    </button>
                </div>
            </form>
        </div>

        <!-- Recommendations -->
        <div class="card">
            <h3>Cleanup Recommendations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Files</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Delete (Production Only)</strong></td>
                        <td>test.php, seo-test.php, image-seo-test.php</td>
                        <td><span style="color: #27ae60;">✅ Safe</span></td>
                    </tr>
                    <tr>
                        <td><strong>Archive</strong></td>
                        <td>*.md documentation files</td>
                        <td><span style="color: #f39c12;">⚠️ Move to docs/</span></td>
                    </tr>
                    <tr>
                        <td><strong>Keep</strong></td>
                        <td>.htaccess.backup</td>
                        <td><span style="color: #3498db;">ℹ️ Backup file</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Next Steps -->
        <div class="card">
            <h3>Next Steps After Cleanup</h3>
            <ol style="margin-left: 20px; line-height: 1.6;">
                <li>Review the SECURITY-RECOMMENDATIONS.md file in the root directory</li>
                <li>Change all admin passwords from default "jahongir2025"</li>
                <li>Configure email settings in mailer.php and mailer-tours.php</li>
                <li>Test contact forms after email configuration</li>
                <li>Add IP restrictions to /admin/ directory</li>
                <li>Run security scan on production server</li>
            </ol>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        window.location.href = 'cleanup-files.php';
    </script>
    <?php endif; ?>
</body>
</html>
