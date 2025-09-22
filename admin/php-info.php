<?php
// PHP Info and Extension Check
// Access: /admin/php-info.php
// Password: jahongir2025

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
            <title>PHP Info - Admin Login</title>
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

// Check extensions
$extensions = [
    'gd' => extension_loaded('gd'),
    'imagick' => extension_loaded('imagick'),
    'exif' => extension_loaded('exif'),
    'fileinfo' => extension_loaded('fileinfo'),
    'curl' => extension_loaded('curl'),
    'openssl' => extension_loaded('openssl'),
    'mbstring' => extension_loaded('mbstring'),
    'zip' => extension_loaded('zip')
];

// Check GD functions
$gd_functions = [
    'imagecreate' => function_exists('imagecreate'),
    'imagecreatefromjpeg' => function_exists('imagecreatefromjpeg'),
    'imagecreatefrompng' => function_exists('imagecreatefrompng'),
    'imagewebp' => function_exists('imagewebp'),
    'imagejpeg' => function_exists('imagejpeg'),
    'imagepng' => function_exists('imagepng'),
    'imagedestroy' => function_exists('imagedestroy')
];

// PHP configuration
$php_config = [
    'PHP Version' => phpversion(),
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time'),
    'Upload Max Filesize' => ini_get('upload_max_filesize'),
    'Post Max Size' => ini_get('post_max_size'),
    'Max Input Vars' => ini_get('max_input_vars'),
    'Display Errors' => ini_get('display_errors'),
    'Error Reporting' => ini_get('error_reporting')
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Info - Jahongir Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .test-result { padding: 10px; margin: 5px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .logout { position: absolute; top: 20px; right: 20px; }
        .logout a { color: white; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 5px; }
        .btn { background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; text-decoration: none; display: inline-block; margin: 10px 5px; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PHP Information & Extensions</h1>
        <p>Diagnostic information for Jahongir Travel</p>
        <div class="logout">
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- PHP Configuration -->
        <div class="card">
            <h3>PHP Configuration</h3>
            <table>
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($php_config as $setting => $value): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($setting); ?></td>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Extensions -->
        <div class="card">
            <h3>PHP Extensions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Extension</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $descriptions = [
                        'gd' => 'Image processing and manipulation',
                        'imagick' => 'Advanced image processing (alternative to GD)',
                        'exif' => 'Exchangeable image file format support',
                        'fileinfo' => 'File type detection',
                        'curl' => 'HTTP client library',
                        'openssl' => 'SSL/TLS support',
                        'mbstring' => 'Multibyte string handling',
                        'zip' => 'ZIP archive support'
                    ];
                    
                    foreach ($extensions as $ext => $loaded): ?>
                    <tr>
                        <td><?php echo strtoupper($ext); ?></td>
                        <td>
                            <span class="test-result <?php echo $loaded ? 'success' : 'error'; ?>">
                                <?php echo $loaded ? '✅ Loaded' : '❌ Not Loaded'; ?>
                            </span>
                        </td>
                        <td><?php echo isset($descriptions[$ext]) ? $descriptions[$ext] : ''; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- GD Functions -->
        <div class="card">
            <h3>GD Functions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Function</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $function_descriptions = [
                        'imagecreate' => 'Create a new image',
                        'imagecreatefromjpeg' => 'Create image from JPEG file',
                        'imagecreatefrompng' => 'Create image from PNG file',
                        'imagewebp' => 'Output WebP image (WebP support)',
                        'imagejpeg' => 'Output JPEG image',
                        'imagepng' => 'Output PNG image',
                        'imagedestroy' => 'Destroy image resource'
                    ];
                    
                    foreach ($gd_functions as $func => $exists): ?>
                    <tr>
                        <td><?php echo $func; ?></td>
                        <td>
                            <span class="test-result <?php echo $exists ? 'success' : 'error'; ?>">
                                <?php echo $exists ? '✅ Available' : '❌ Not Available'; ?>
                            </span>
                        </td>
                        <td><?php echo isset($function_descriptions[$func]) ? $function_descriptions[$func] : ''; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Recommendations -->
        <div class="card">
            <h3>Recommendations</h3>
            <?php if (!$extensions['gd']): ?>
                <div class="test-result error">
                    <strong>❌ GD Extension Missing:</strong> You need to enable the GD extension in PHP to use image processing features.
                </div>
                <div style="margin-top: 15px;">
                    <h4>How to enable GD extension in XAMPP:</h4>
                    <ol style="margin-left: 20px; line-height: 1.6;">
                        <li>Open <strong>php.ini</strong> file (usually in C:\xampp\php\php.ini)</li>
                        <li>Find the line: <code>;extension=gd</code></li>
                        <li>Remove the semicolon: <code>extension=gd</code></li>
                        <li>Save the file and restart Apache</li>
                    </ol>
                </div>
            <?php elseif (!$gd_functions['imagewebp']): ?>
                <div class="test-result error">
                    <strong>❌ WebP Support Missing:</strong> GD extension is loaded but WebP support is not available.
                </div>
                <div style="margin-top: 15px;">
                    <h4>How to enable WebP support:</h4>
                    <ol style="margin-left: 20px; line-height: 1.6;">
                        <li>Update to a newer version of XAMPP with WebP support</li>
                        <li>Or compile PHP with WebP support</li>
                        <li>Or use ImageMagick extension as alternative</li>
                    </ol>
                </div>
            <?php else: ?>
                <div class="test-result success">
                    <strong>✅ WebP Support Available:</strong> You can proceed with WebP image conversion!
                </div>
                <div style="margin-top: 15px;">
                    <a href="webp-test.php" class="btn">🧪 Run WebP Test</a>
                    <a href="convert-to-webp.php" class="btn">🚀 Convert Images</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Alternative Solutions -->
        <div class="card">
            <h3>Alternative Solutions</h3>
            <div class="test-result info">
                <strong>If WebP conversion is not available, you can:</strong>
            </div>
            <ul style="margin-left: 20px; line-height: 1.6;">
                <li><strong>Use online tools:</strong> Convert images manually using online WebP converters</li>
                <li><strong>Use desktop software:</strong> GIMP, Photoshop, or other image editors</li>
                <li><strong>Use command line tools:</strong> cwebp command line tool</li>
                <li><strong>Focus on other optimizations:</strong> The website will still benefit from other SEO improvements</li>
            </ul>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        window.location.href = 'php-info.php';
    </script>
    <?php endif; ?>
</body>
</html>

