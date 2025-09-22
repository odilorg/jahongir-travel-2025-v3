<?php
/**
 * WebP Image Conversion and Optimization Script
 * Converts all images to WebP format for better performance
 * 
 * Usage: Run this script once to convert all existing images
 * Access: /admin/convert-to-webp.php
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simple authentication
$admin_password = 'jahongir2025'; // Same password as performance dashboard
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>WebP Conversion - Admin Login</title>
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

class WebPConverter {
    private $supportedFormats = ['jpg', 'jpeg', 'png'];
    private $conversionLog = [];
    private $totalImages = 0;
    private $convertedImages = 0;
    private $skippedImages = 0;
    private $errors = [];
    
    public function __construct() {
        // Check if WebP extension is available
        if (!extension_loaded('gd')) {
            throw new Exception('GD extension not available. Please install GD extension.');
        }
        
        if (!function_exists('imagewebp')) {
            throw new Exception('WebP support not available. Please install GD extension with WebP support.');
        }
    }
    
    /**
     * Convert all images in a directory to WebP
     */
    public function convertDirectory($directory, $recursive = true) {
        $this->log("Starting WebP conversion for directory: $directory");
        
        if (!is_dir($directory)) {
            $this->log("Directory not found: $directory");
            return [
                'total' => 0,
                'converted' => 0,
                'skipped' => 0,
                'errors' => ["Directory not found: $directory"],
                'log' => $this->conversionLog
            ];
        }
        
        $this->processDirectory($directory, $recursive);
        
        return [
            'total' => $this->totalImages,
            'converted' => $this->convertedImages,
            'skipped' => $this->skippedImages,
            'errors' => $this->errors,
            'log' => $this->conversionLog
        ];
    }
    
    /**
     * Process directory recursively
     */
    private function processDirectory($dir, $recursive = true) {
        try {
            $files = scandir($dir);
            
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $fullPath = $dir . '/' . $file;
                
                if (is_dir($fullPath) && $recursive) {
                    $this->processDirectory($fullPath, $recursive);
                } elseif (is_file($fullPath)) {
                    $this->processImage($fullPath);
                }
            }
        } catch (Exception $e) {
            $this->errors[] = "Error processing directory $dir: " . $e->getMessage();
        }
    }
    
    /**
     * Process individual image file
     */
    private function processImage($imagePath) {
        try {
            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            
            if (!in_array($extension, $this->supportedFormats)) {
                return;
            }
            
            $this->totalImages++;
            
            // Check if WebP version already exists
            $webpPath = $this->getWebPPath($imagePath);
            if (file_exists($webpPath)) {
                $this->skippedImages++;
                $this->log("Skipped (WebP exists): $imagePath");
                return;
            }
            
            // Skip files with suspicious names (likely corrupted)
            if (strpos($imagePath, '..') !== false || strpos($imagePath, '//') !== false) {
                $this->skippedImages++;
                $this->log("Skipped (suspicious path): $imagePath");
                return;
            }
            
            // Convert to WebP
            if ($this->convertToWebP($imagePath, $webpPath)) {
                $this->convertedImages++;
                $this->log("Converted: $imagePath -> $webpPath");
            } else {
                $this->skippedImages++;
                $this->log("Skipped (conversion failed): $imagePath");
            }
        } catch (Exception $e) {
            $this->errors[] = "Error processing image $imagePath: " . $e->getMessage();
        }
    }
    
    /**
     * Convert image to WebP format
     */
    private function convertToWebP($sourcePath, $webpPath) {
        try {
            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            
            // Validate file exists and is readable
            if (!file_exists($sourcePath) || !is_readable($sourcePath)) {
                $this->log("File not readable: $sourcePath");
                return false;
            }
            
            // Check file size (skip empty files)
            if (filesize($sourcePath) < 100) {
                $this->log("File too small (likely corrupted): $sourcePath");
                return false;
            }
            
            // Validate file header for JPEG files
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $handle = fopen($sourcePath, 'rb');
                if ($handle) {
                    $header = fread($handle, 4);
                    fclose($handle);
                    
                    // Check for JPEG magic bytes (FF D8 FF)
                    if (substr($header, 0, 2) !== "\xFF\xD8") {
                        $this->log("Invalid JPEG header: $sourcePath");
                        return false;
                    }
                }
            }
            
            // Validate file header for PNG files
            if ($extension === 'png') {
                $handle = fopen($sourcePath, 'rb');
                if ($handle) {
                    $header = fread($handle, 8);
                    fclose($handle);
                    
                    // Check for PNG magic bytes (89 50 4E 47 0D 0A 1A 0A)
                    if (substr($header, 0, 8) !== "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
                        $this->log("Invalid PNG header: $sourcePath");
                        return false;
                    }
                }
            }
            
            // Create image resource based on file type
            $image = null;
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'png':
                    $image = @imagecreatefrompng($sourcePath);
                    break;
                default:
                    $this->log("Unsupported file type: $sourcePath");
                    return false;
            }
            
            if (!$image) {
                $this->log("Failed to create image resource from: $sourcePath");
                return false;
            }
            
            // Convert palette images to true color for WebP compatibility
            if (imageistruecolor($image) === false) {
                imagepalettetotruecolor($image);
            }
            
            // Preserve transparency for PNG images
            if ($extension === 'png') {
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            
            // Create directory if it doesn't exist
            $webpDir = dirname($webpPath);
            if (!is_dir($webpDir)) {
                if (!mkdir($webpDir, 0755, true)) {
                    $this->log("Failed to create directory: $webpDir");
                    imagedestroy($image);
                    return false;
                }
            }
            
            // Convert to WebP with quality 85 (good balance of quality/size)
            $result = @imagewebp($image, $webpPath, 85);
            
            // Clean up
            imagedestroy($image);
            
            if (!$result) {
                $this->log("Failed to save WebP image: $webpPath");
                return false;
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Exception converting $sourcePath: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get WebP file path
     */
    private function getWebPPath($originalPath) {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    }
    
    /**
     * Log conversion activity
     */
    private function log($message) {
        $this->conversionLog[] = date('Y-m-d H:i:s') . ' - ' . $message;
    }
    
    /**
     * Get file size comparison
     */
    public function getFileSizeComparison($originalPath, $webpPath) {
        if (!file_exists($originalPath) || !file_exists($webpPath)) {
            return null;
        }
        
        $originalSize = filesize($originalPath);
        $webpSize = filesize($webpPath);
        $savings = $originalSize - $webpSize;
        $savingsPercent = round(($savings / $originalSize) * 100, 2);
        
        return [
            'original' => $this->formatBytes($originalSize),
            'webp' => $this->formatBytes($webpSize),
            'savings' => $this->formatBytes($savings),
            'savings_percent' => $savingsPercent
        ];
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Get conversion statistics
     */
    public function getStatistics() {
        return [
            'total_images' => $this->totalImages,
            'converted_images' => $this->convertedImages,
            'skipped_images' => $this->skippedImages,
            'error_count' => count($this->errors),
            'conversion_rate' => $this->totalImages > 0 ? round(($this->convertedImages / $this->totalImages) * 100, 2) : 0
        ];
    }
    
    /**
     * Get corrupted files report
     */
    public function getCorruptedFilesReport() {
        $corruptedFiles = [];
        $directories = [
            '../images',
            '../uzbekistan-tours/images',
            '../tours-from-samarkand/images',
            '../tours-from-bukhara/images',
            '../tours-from-khiva/images',
            '../tajikistan-tours/images'
        ];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;
                    
                    $fullPath = $dir . '/' . $file;
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    
                    if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        // Check file size
                        if (filesize($fullPath) < 100) {
                            $corruptedFiles[] = [
                                'file' => $fullPath,
                                'issue' => 'File too small (likely corrupted)',
                                'size' => filesize($fullPath) . ' bytes'
                            ];
                            continue;
                        }
                        
                        // Check file headers
                        $handle = fopen($fullPath, 'rb');
                        if ($handle) {
                            $header = fread($handle, 8);
                            fclose($handle);
                            
                            if ($extension === 'jpg' || $extension === 'jpeg') {
                                if (substr($header, 0, 2) !== "\xFF\xD8") {
                                    $corruptedFiles[] = [
                                        'file' => $fullPath,
                                        'issue' => 'Invalid JPEG header',
                                        'size' => filesize($fullPath) . ' bytes'
                                    ];
                                }
                            } elseif ($extension === 'png') {
                                if (substr($header, 0, 8) !== "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
                                    $corruptedFiles[] = [
                                        'file' => $fullPath,
                                        'issue' => 'Invalid PNG header',
                                        'size' => filesize($fullPath) . ' bytes'
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $corruptedFiles;
    }
}

// Handle conversion request
$conversionResult = null;
$statistics = null;

if (isset($_POST['convert_images'])) {
    try {
        $converter = new WebPConverter();
        
        // Define directories to convert (only existing ones)
        $directories = [
            '../images',
            '../uzbekistan-tours/images',
            '../tours-from-samarkand/images',
            '../tours-from-bukhara/images',
            '../tours-from-khiva/images',
            '../tajikistan-tours/images'
        ];
        
        $totalResult = [
            'total' => 0,
            'converted' => 0,
            'skipped' => 0,
            'errors' => [],
            'log' => []
        ];
        
        // Convert each directory
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $result = $converter->convertDirectory($dir, true);
                $totalResult['total'] += $result['total'];
                $totalResult['converted'] += $result['converted'];
                $totalResult['skipped'] += $result['skipped'];
                $totalResult['errors'] = array_merge($totalResult['errors'], $result['errors']);
                $totalResult['log'] = array_merge($totalResult['log'], $result['log']);
            } else {
                $totalResult['log'][] = "Directory not found: $dir";
            }
        }
        
        $conversionResult = $totalResult;
        $statistics = $converter->getStatistics();
        
        // Get corrupted files report
        $corruptedFiles = $converter->getCorruptedFilesReport();
        
    } catch (Exception $e) {
        $conversionResult = ['error' => $e->getMessage()];
    }
}

// Get sample file size comparison
$sampleComparison = null;
if (file_exists('../images/gur-emir.jpg') && file_exists('../images/gur-emir.webp')) {
    try {
        $converter = new WebPConverter();
        $sampleComparison = $converter->getFileSizeComparison('../images/gur-emir.jpg', '../images/gur-emir.webp');
    } catch (Exception $e) {
        // Ignore comparison errors
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WebP Image Conversion - Jahongir Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .btn { background: #3498db; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-item { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .stat-label { color: #7f8c8d; margin-top: 5px; }
        .log { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px; }
        .error { color: #e74c3c; }
        .success { color: #27ae60; }
        .warning { color: #f39c12; }
        .comparison { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 15px 0; }
        .comparison-item { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; }
        .logout { position: absolute; top: 20px; right: 20px; }
        .logout a { color: white; text-decoration: none; background: #e74c3c; padding: 10px 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>WebP Image Conversion Tool</h1>
        <p>Convert all images to WebP format for better performance</p>
        <div class="logout">
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- WebP Benefits -->
        <div class="card">
            <h3>Why Convert to WebP?</h3>
            <ul style="margin-left: 20px; line-height: 1.6;">
                <li><strong>25-35% smaller file sizes</strong> compared to JPEG</li>
                <li><strong>50-80% smaller</strong> compared to PNG</li>
                <li><strong>Faster page loading</strong> and better Core Web Vitals</li>
                <li><strong>Better SEO rankings</strong> due to improved page speed</li>
                <li><strong>Reduced bandwidth usage</strong> and hosting costs</li>
                <li><strong>Modern browser support</strong> (95%+ of users)</li>
            </ul>
        </div>

        <!-- Sample Comparison -->
        <?php if ($sampleComparison): ?>
        <div class="card">
            <h3>Sample File Size Comparison</h3>
            <p>Example: gur-emir.jpg vs gur-emir.webp</p>
            <div class="comparison">
                <div class="comparison-item">
                    <div class="stat-value"><?php echo $sampleComparison['original']; ?></div>
                    <div class="stat-label">Original JPEG</div>
                </div>
                <div class="comparison-item">
                    <div class="stat-value"><?php echo $sampleComparison['webp']; ?></div>
                    <div class="stat-label">WebP Version</div>
                </div>
                <div class="comparison-item">
                    <div class="stat-value success"><?php echo $sampleComparison['savings']; ?></div>
                    <div class="stat-label">Size Savings</div>
                </div>
                <div class="comparison-item">
                    <div class="stat-value success"><?php echo $sampleComparison['savings_percent']; ?>%</div>
                    <div class="stat-label">Savings %</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Conversion Form -->
        <div class="card">
            <h3>Convert Images to WebP</h3>
            <p>This will convert all JPG, JPEG, and PNG images in your website to WebP format. The conversion process will:</p>
            <ul style="margin-left: 20px; line-height: 1.6;">
                <li>Scan all image directories</li>
                <li>Convert images to WebP format (85% quality)</li>
                <li>Preserve original files</li>
                <li>Skip already converted images</li>
            </ul>
            
            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="convert_images" class="btn btn-success">
                    🚀 Start WebP Conversion
                </button>
            </form>
        </div>

        <!-- Conversion Results -->
        <?php if ($conversionResult): ?>
        <div class="card">
            <h3>Conversion Results</h3>
            
            <?php if (isset($conversionResult['error'])): ?>
                <div class="error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($conversionResult['error']); ?>
                </div>
            <?php else: ?>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $conversionResult['total']; ?></div>
                        <div class="stat-label">Total Images</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value success"><?php echo $conversionResult['converted']; ?></div>
                        <div class="stat-label">Converted</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value warning"><?php echo $conversionResult['skipped']; ?></div>
                        <div class="stat-label">Skipped</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value error"><?php echo count($conversionResult['errors']); ?></div>
                        <div class="stat-label">Errors</div>
                    </div>
                </div>
                
                <?php if (!empty($conversionResult['errors'])): ?>
                <div style="margin-top: 20px;">
                    <h4>Errors:</h4>
                    <div class="log">
                        <?php foreach ($conversionResult['errors'] as $error): ?>
                            <div class="error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($conversionResult['log'])): ?>
                <div style="margin-top: 20px;">
                    <h4>Conversion Log:</h4>
                    <div class="log">
                        <?php foreach ($conversionResult['log'] as $logEntry): ?>
                            <div><?php echo htmlspecialchars($logEntry); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Corrupted Files Report -->
        <?php if (isset($corruptedFiles) && !empty($corruptedFiles)): ?>
        <div class="card">
            <h3>⚠️ Corrupted Files Detected</h3>
            <p>The following files have issues and were skipped during conversion:</p>
            <table>
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Issue</th>
                        <th>Size</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($corruptedFiles as $file): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file['file']); ?></td>
                        <td><?php echo htmlspecialchars($file['issue']); ?></td>
                        <td><?php echo htmlspecialchars($file['size']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
                <strong>Recommendation:</strong> These files should be replaced with valid image files or removed from your website.
            </div>
        </div>
        <?php endif; ?>

        <!-- Next Steps -->
        <div class="card">
            <h3>Next Steps After Conversion</h3>
            <ol style="margin-left: 20px; line-height: 1.6;">
                <li><strong>Test the website</strong> to ensure all images load correctly</li>
                <li><strong>Check browser compatibility</strong> - WebP works in 95%+ of browsers</li>
                <li><strong>Monitor page speed</strong> improvements in Google PageSpeed Insights</li>
                <li><strong>Update image references</strong> if needed (our system handles this automatically)</li>
                <li><strong>Consider removing original files</strong> after confirming WebP works (optional)</li>
                <?php if (isset($corruptedFiles) && !empty($corruptedFiles)): ?>
                <li><strong>Replace corrupted files</strong> with valid image files</li>
                <?php endif; ?>
            </ol>
        </div>

        <!-- Browser Support -->
        <div class="card">
            <h3>WebP Browser Support</h3>
            <p>WebP is supported by:</p>
            <ul style="margin-left: 20px; line-height: 1.6;">
                <li>✅ Chrome (all versions)</li>
                <li>✅ Firefox (65+)</li>
                <li>✅ Safari (14+)</li>
                <li>✅ Edge (all versions)</li>
                <li>✅ Opera (all versions)</li>
                <li>❌ Internet Explorer (fallback to original images)</li>
            </ul>
            <p style="margin-top: 15px; color: #7f8c8d;">
                <strong>Note:</strong> Our implementation automatically falls back to original images for unsupported browsers.
            </p>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        window.location.href = 'convert-to-webp.php';
    </script>
    <?php endif; ?>
</body>
</html>
