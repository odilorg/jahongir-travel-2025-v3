<?php
/**
 * WebP Test Script - Simplified Version
 * Simple test to check if WebP conversion is working
 * Access: /admin/webp-test.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simple authentication
$admin_password = 'jahongir2025';
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>WebP Test - Admin Login</title>
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

// Test WebP functionality
$test_results = [];

// Test 1: Check GD extension
$test_results['gd_extension'] = extension_loaded('gd') ? '✅ Available' : '❌ Not Available';

// Test 2: Check WebP support
$test_results['webp_support'] = function_exists('imagewebp') ? '✅ Available' : '❌ Not Available';

// Test 3: Check if we can create a test image (simplified)
$test_image_created = false;
$test_image_path = '../images/test-webp.jpg';
$test_webp_path = '../images/test-webp.webp';

if (extension_loaded('gd') && function_exists('imagecreate')) {
    try {
        // Create a simple test image
        $test_image = imagecreate(100, 100);
        $bg_color = imagecolorallocate($test_image, 255, 255, 255);
        $text_color = imagecolorallocate($test_image, 0, 0, 0);
        imagestring($test_image, 5, 10, 40, 'TEST', $text_color);
        
        // Save as JPEG
        if (imagejpeg($test_image, $test_image_path, 90)) {
            $test_image_created = true;
            $test_results['test_image_creation'] = '✅ Success';
        } else {
            $test_results['test_image_creation'] = '❌ Failed';
        }
        
        // Convert to WebP if WebP support is available
        if (function_exists('imagewebp') && $test_image_created) {
            // Convert palette image to true color for WebP compatibility
            $truecolor_image = imagecreatetruecolor(100, 100);
            imagecopy($truecolor_image, $test_image, 0, 0, 0, 0, 100, 100);
            
            if (imagewebp($truecolor_image, $test_webp_path, 85)) {
                $test_results['webp_conversion'] = '✅ Success';
                
                // Get file sizes
                $original_size = filesize($test_image_path);
                $webp_size = filesize($test_webp_path);
                $savings = $original_size - $webp_size;
                $savings_percent = round(($savings / $original_size) * 100, 2);
                
                $test_results['file_sizes'] = [
                    'original' => $original_size . ' bytes',
                    'webp' => $webp_size . ' bytes',
                    'savings' => $savings . ' bytes (' . $savings_percent . '%)'
                ];
            } else {
                $test_results['webp_conversion'] = '❌ Failed';
            }
            
            // Clean up truecolor image
            imagedestroy($truecolor_image);
        } else {
            $test_results['webp_conversion'] = '❌ WebP not supported';
        }
        
        // Clean up
        imagedestroy($test_image);
        
    } catch (Exception $e) {
        $test_results['test_image_creation'] = '❌ Error: ' . $e->getMessage();
    }
} else {
    $test_results['test_image_creation'] = '❌ GD extension not available';
    $test_results['webp_conversion'] = '❌ Cannot test without GD';
}

// Test 4: Check existing images
$existing_images = [];
$image_dirs = ['../images', '../uzbekistan-tours/images', '../tours-from-samarkand/images'];

foreach ($image_dirs as $dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        $jpg_count = 0;
        $png_count = 0;
        $webp_count = 0;
        
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if ($ext === 'jpg' || $ext === 'jpeg') $jpg_count++;
            if ($ext === 'png') $png_count++;
            if ($ext === 'webp') $webp_count++;
        }
        
        $existing_images[$dir] = [
            'jpg' => $jpg_count,
            'png' => $png_count,
            'webp' => $webp_count
        ];
    }
}

// Test 5: Browser WebP support detection
$browser_webp_support = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
$test_results['browser_webp_support'] = $browser_webp_support ? '✅ Supported' : '❌ Not Supported';

// Test 6: Check PHP configuration
$test_results['php_version'] = 'PHP ' . phpversion();
$test_results['memory_limit'] = ini_get('memory_limit');
$test_results['max_execution_time'] = ini_get('max_execution_time');
$test_results['upload_max_filesize'] = ini_get('upload_max_filesize');
?>

<!DOCTYPE html>
<html>
<head>
    <title>WebP Test Results - Jahongir Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
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
        <h1>WebP Test Results</h1>
        <p>Testing WebP conversion capabilities</p>
        <div class="logout">
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- System Requirements -->
        <div class="card">
            <h3>System Requirements Check</h3>
            <div class="test-result <?php echo strpos($test_results['gd_extension'], '✅') !== false ? 'success' : 'error'; ?>">
                <strong>GD Extension:</strong> <?php echo $test_results['gd_extension']; ?>
            </div>
            <div class="test-result <?php echo strpos($test_results['webp_support'], '✅') !== false ? 'success' : 'error'; ?>">
                <strong>WebP Support:</strong> <?php echo $test_results['webp_support']; ?>
            </div>
            <div class="test-result <?php echo strpos($test_results['browser_webp_support'], '✅') !== false ? 'success' : 'info'; ?>">
                <strong>Browser WebP Support:</strong> <?php echo $test_results['browser_webp_support']; ?>
            </div>
        </div>

        <!-- PHP Configuration -->
        <div class="card">
            <h3>PHP Configuration</h3>
            <div class="test-result info">
                <strong>PHP Version:</strong> <?php echo $test_results['php_version']; ?>
            </div>
            <div class="test-result info">
                <strong>Memory Limit:</strong> <?php echo $test_results['memory_limit']; ?>
            </div>
            <div class="test-result info">
                <strong>Max Execution Time:</strong> <?php echo $test_results['max_execution_time']; ?> seconds
            </div>
            <div class="test-result info">
                <strong>Upload Max Filesize:</strong> <?php echo $test_results['upload_max_filesize']; ?>
            </div>
        </div>

        <!-- Test Image Creation -->
        <div class="card">
            <h3>Test Image Creation</h3>
            <div class="test-result <?php echo strpos($test_results['test_image_creation'], '✅') !== false ? 'success' : 'error'; ?>">
                <strong>Test Image Creation:</strong> <?php echo $test_results['test_image_creation']; ?>
            </div>
            <div class="test-result <?php echo strpos($test_results['webp_conversion'], '✅') !== false ? 'success' : 'error'; ?>">
                <strong>WebP Conversion:</strong> <?php echo $test_results['webp_conversion']; ?>
            </div>
            
            <?php if (isset($test_results['file_sizes'])): ?>
            <div style="margin-top: 15px;">
                <h4>File Size Comparison:</h4>
                <ul style="margin-left: 20px;">
                    <li><strong>Original JPEG:</strong> <?php echo $test_results['file_sizes']['original']; ?></li>
                    <li><strong>WebP Version:</strong> <?php echo $test_results['file_sizes']['webp']; ?></li>
                    <li><strong>Size Savings:</strong> <?php echo $test_results['file_sizes']['savings']; ?></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <!-- Existing Images -->
        <div class="card">
            <h3>Existing Images Inventory</h3>
            <table>
                <thead>
                    <tr>
                        <th>Directory</th>
                        <th>JPEG Images</th>
                        <th>PNG Images</th>
                        <th>WebP Images</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($existing_images as $dir => $counts): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dir); ?></td>
                        <td><?php echo $counts['jpg']; ?></td>
                        <td><?php echo $counts['png']; ?></td>
                        <td><?php echo $counts['webp']; ?></td>
                        <td><?php echo $counts['jpg'] + $counts['png'] + $counts['webp']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Recommendations -->
        <div class="card">
            <h3>Recommendations</h3>
            <?php if (strpos($test_results['webp_support'], '✅') !== false): ?>
                <div class="test-result success">
                    <strong>✅ WebP conversion is ready!</strong> You can proceed with converting your images to WebP format.
                </div>
                <div style="margin-top: 15px;">
                    <a href="convert-to-webp.php" class="btn">🚀 Start WebP Conversion</a>
                    <a href="performance-dashboard.php" class="btn">📊 Performance Dashboard</a>
                </div>
            <?php else: ?>
                <div class="test-result error">
                    <strong>❌ WebP support not available.</strong> You need to install or enable WebP support in your PHP installation.
                </div>
                <div style="margin-top: 15px;">
                    <h4>How to enable WebP support:</h4>
                    <ul style="margin-left: 20px; line-height: 1.6;">
                        <li><strong>XAMPP:</strong> WebP support should be included in recent versions</li>
                        <li><strong>Manual Installation:</strong> Recompile PHP with --with-webp flag</li>
                        <li><strong>Alternative:</strong> Use external tools like ImageMagick or cwebp</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Test Images -->
        <?php if ($test_image_created): ?>
        <div class="card">
            <h3>Test Images</h3>
            <p>Below are the test images created during this test:</p>
            <div style="display: flex; gap: 20px; margin-top: 15px;">
                <div>
                    <h4>Original JPEG</h4>
                    <img src="../images/test-webp.jpg" alt="Test JPEG" style="border: 1px solid #ddd; max-width: 200px;">
                </div>
                <?php if (file_exists('images/test-webp.webp')): ?>
                <div>
                    <h4>WebP Version</h4>
                    <picture>
                        <source srcset="../images/test-webp.webp" type="image/webp">
                        <img src="../images/test-webp.jpg" alt="Test WebP" style="border: 1px solid #ddd; max-width: 200px;">
                    </picture>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        window.location.href = 'webp-test.php';
    </script>
    <?php endif; ?>
</body>
</html>
