<?php
/**
 * PHP File Parser - Extract and Update Variables
 */

/**
 * Parse PHP file and extract variables
 */
function parsePhpFile($filepath) {
    if (!file_exists($filepath)) {
        return false;
    }

    $content = file_get_contents($filepath);
    $data = [];

    // Extract PHP variables from the top of the file
    $patterns = [
        'title' => '/\$title\s*=\s*["\'](.+?)["\']\s*;/s',
        'meta_description' => '/\$meta_description\s*=\s*["\'](.+?)["\']\s*;/s',
        'meta_keywords' => '/\$meta_keywords\s*=\s*["\'](.+?)["\']\s*;/s',
        'canonical_url' => '/\$canonical_url\s*=\s*["\'](.+?)["\']\s*;/s',
        'og_type' => '/\$og_type\s*=\s*["\'](.+?)["\']\s*;/s',
        'og_image' => '/\$og_image\s*=\s*["\'](.+?)["\']\s*;/s',
    ];

    foreach ($patterns as $key => $pattern) {
        if (preg_match($pattern, $content, $matches)) {
            $data[$key] = $matches[1];
        } else {
            $data[$key] = '';
        }
    }

    // Extract H1 heading (main title)
    if (preg_match('/<h1[^>]*class="heading_primary"[^>]*>(.+?)<\/h1>/s', $content, $matches)) {
        $data['heading'] = trim(strip_tags($matches[1]));
    } else {
        $data['heading'] = '';
    }

    // Extract price if it's a tour
    if (preg_match('/<span class="price">From \$([0-9,.]+)<\/span>/i', $content, $matches)) {
        $data['price'] = $matches[1];
    } else {
        $data['price'] = '';
    }

    // Extract duration
    if (preg_match('/<span[^>]*>([0-9]+\s+DAYS?\s+[0-9]*\s*NIGHTS?|DAY\s+TOUR)<\/span>/i', $content, $matches)) {
        $data['duration'] = $matches[1];
    } else {
        $data['duration'] = '';
    }

    // Extract tour code
    if (preg_match('/<strong>Code:\s*<\/strong>([A-Z0-9]+)/i', $content, $matches)) {
        $data['tour_code'] = $matches[1];
    } else {
        $data['tour_code'] = '';
    }

    // Store full content for editing
    $data['full_content'] = $content;
    $data['filepath'] = $filepath;
    $data['filename'] = basename($filepath);

    return $data;
}

/**
 * Update PHP file variables
 */
function updatePhpFile($filepath, $data) {
    if (!file_exists($filepath)) {
        return false;
    }

    // Backup original file
    backupFile($filepath);

    $content = file_get_contents($filepath);

    // Update each variable
    $updates = [
        'title' => '$title',
        'meta_description' => '$meta_description',
        'meta_keywords' => '$meta_keywords',
        'canonical_url' => '$canonical_url',
        'og_type' => '$og_type',
        'og_image' => '$og_image',
    ];

    foreach ($updates as $key => $varName) {
        if (isset($data[$key])) {
            $value = addslashes($data[$key]);
            $pattern = '/' . preg_quote($varName, '/') . '\s*=\s*["\'].+?["\']\s*;/s';
            $replacement = $varName . ' = "' . $value . '";';
            $content = preg_replace($pattern, $replacement, $content, 1);
        }
    }

    // Update H1 heading
    if (isset($data['heading'])) {
        $pattern = '/(<h1[^>]*class="heading_primary"[^>]*>)(.+?)(<\/h1>)/s';
        $replacement = '$1' . htmlspecialchars($data['heading']) . '$3';
        $content = preg_replace($pattern, $replacement, $content, 1);
    }

    // Update price
    if (isset($data['price']) && !empty($data['price'])) {
        $pattern = '/(<span class="price">From \$)([0-9,.]+)(<\/span>)/i';
        $replacement = '$1' . htmlspecialchars($data['price']) . '$3';
        $content = preg_replace($pattern, $replacement, $content);
    }

    // Update duration
    if (isset($data['duration']) && !empty($data['duration'])) {
        $pattern = '/(<span[^>]*>)([0-9]+\s+DAYS?\s+[0-9]*\s*NIGHTS?|DAY\s+TOUR)(<\/span>)/i';
        $replacement = '$1' . htmlspecialchars($data['duration']) . '$3';
        $content = preg_replace($pattern, $replacement, $content, 1);
    }

    // Update tour code
    if (isset($data['tour_code']) && !empty($data['tour_code'])) {
        $pattern = '/(<strong>Code:\s*<\/strong>)([A-Z0-9]+)/i';
        $replacement = '$1' . htmlspecialchars($data['tour_code']);
        $content = preg_replace($pattern, $replacement, $content, 1);
    }

    // Validate PHP syntax before writing
    if (!validatePhpSyntax($content)) {
        return false;
    }

    // Write updated content
    $result = file_put_contents($filepath, $content);

    if ($result !== false) {
        // Clean old backups
        cleanOldBackups(basename($filepath));
        return true;
    }

    return false;
}

/**
 * Validate PHP syntax without executing
 */
function validatePhpSyntax($code) {
    // Create temporary file
    $tempFile = tempnam(sys_get_temp_dir(), 'php_syntax_check_');
    file_put_contents($tempFile, $code);

    // Run PHP syntax check
    $output = [];
    $return = 0;
    exec('php -l ' . escapeshellarg($tempFile) . ' 2>&1', $output, $return);

    // Clean up
    unlink($tempFile);

    // Return true if no syntax errors
    return $return === 0;
}

/**
 * Get tour category from file path
 */
function getTourCategory($filepath) {
    if (strpos($filepath, 'uzbekistan-tours') !== false) {
        return 'Uzbekistan Tours';
    } elseif (strpos($filepath, 'tours-from-samarkand') !== false) {
        return 'Tours from Samarkand';
    } elseif (strpos($filepath, 'tours-from-bukhara') !== false) {
        return 'Tours from Bukhara';
    } elseif (strpos($filepath, 'tours-from-khiva') !== false) {
        return 'Tours from Khiva';
    } elseif (strpos($filepath, 'tajikistan-tours') !== false) {
        return 'Tajikistan Tours';
    } elseif (strpos($filepath, 'blog') !== false) {
        return 'Blog';
    }
    return 'Other';
}

/**
 * Get all tours from all directories
 */
function getAllTours() {
    $tours = [];

    $directories = [
        TOURS_DIR,
        SAMARKAND_TOURS_DIR,
        BUKHARA_TOURS_DIR,
        KHIVA_TOURS_DIR,
    ];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            continue;
        }

        $files = getPhpFiles($dir, EXCLUDED_FILES);

        foreach ($files as $file) {
            $tourData = parsePhpFile($file['path']);

            if ($tourData) {
                $tours[] = [
                    'filename' => $file['filename'],
                    'path' => $file['path'],
                    'title' => $tourData['title'],
                    'category' => getTourCategory($file['path']),
                    'price' => $tourData['price'],
                    'duration' => $tourData['duration'],
                    'modified' => $file['modified'],
                ];
            }
        }
    }

    return $tours;
}

/**
 * Get all blog posts
 */
function getAllBlogPosts() {
    $posts = [];

    if (!is_dir(BLOG_DIR)) {
        return $posts;
    }

    $files = getPhpFiles(BLOG_DIR, array_merge(EXCLUDED_FILES, ['includes']));

    foreach ($files as $file) {
        $postData = parsePhpFile($file['path']);

        if ($postData) {
            $posts[] = [
                'filename' => $file['filename'],
                'path' => $file['path'],
                'title' => $postData['title'],
                'meta_description' => $postData['meta_description'],
                'modified' => $file['modified'],
            ];
        }
    }

    return $posts;
}
?>
