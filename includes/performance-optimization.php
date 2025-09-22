<?php
// Performance Optimization Configuration
// This file should be included at the top of header.php

// Only enable GZIP compression if headers haven't been sent
if (extension_loaded('zlib') && !ob_get_level() && !headers_sent()) {
    ob_start('ob_gzhandler');
}

// Set cache headers for static assets (only if headers not sent)
if (!headers_sent()) {
    $cache_time = 31536000; // 1 year for static assets
    $short_cache = 3600; // 1 hour for dynamic content
    
    // Function to add cache headers
    function add_cache_headers($seconds) {
        if (!headers_sent()) {
            header("Cache-Control: public, max-age=" . $seconds);
            header("Expires: " . gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        }
    }
    
    // Add cache headers for CSS and JS files
    if (isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '.css') !== false || strpos($_SERVER['REQUEST_URI'], '.js') !== false)) {
        add_cache_headers($cache_time);
    }
    
    // Add cache headers for images
    if (isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '.jpg') !== false || 
        strpos($_SERVER['REQUEST_URI'], '.jpeg') !== false || 
        strpos($_SERVER['REQUEST_URI'], '.png') !== false || 
        strpos($_SERVER['REQUEST_URI'], '.gif') !== false || 
        strpos($_SERVER['REQUEST_URI'], '.webp') !== false)) {
        add_cache_headers($cache_time);
    }
    
    // Add security headers
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

// Preload critical resources
function preload_critical_resources() {
    $preload_resources = [
        'assets/css/bootstrap.min.css',
        'assets/css/style.css',
        'assets/css/travel-setting.css',
        'assets/js/jquery.min.js',
        'assets/js/bootstrap.min.js'
    ];
    
    foreach ($preload_resources as $resource) {
        echo '<link rel="preload" href="' . $resource . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    }
}

// Image optimization function
function get_optimized_image($image_path, $width = null, $height = null, $quality = 85) {
    // Check if WebP is supported
    $webp_supported = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    
    if ($webp_supported) {
        $webp_path = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $image_path);
        if (file_exists($webp_path)) {
            return $webp_path;
        }
    }
    
    return $image_path;
}

// Enhanced image serving function
function serve_optimized_image($image_path, $alt_text = '', $width = null, $height = null, $class = '') {
    // Check if WebP is supported by browser
    $webp_supported = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    
    // Get WebP version if available
    $webp_path = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $image_path);
    $has_webp = file_exists($webp_path);
    
    // Build attributes
    $width_attr = $width ? ' width="' . $width . '"' : '';
    $height_attr = $height ? ' height="' . $height . '"' : '';
    $class_attr = $class ? ' class="' . $class . '"' : '';
    
    if ($webp_supported && $has_webp) {
        // Serve WebP with fallback
        return '<picture>
            <source srcset="' . $webp_path . '" type="image/webp">
            <img src="' . $image_path . '" alt="' . htmlspecialchars($alt_text) . '"' . $width_attr . $height_attr . $class_attr . ' loading="lazy">
        </picture>';
    } else {
        // Serve original image
        return '<img src="' . $image_path . '" alt="' . htmlspecialchars($alt_text) . '"' . $width_attr . $height_attr . $class_attr . ' loading="lazy">';
    }
}

// Lazy loading implementation
function lazy_load_image($src, $alt, $width = null, $height = null, $class = '') {
    $width_attr = $width ? ' width="' . $width . '"' : '';
    $height_attr = $height ? ' height="' . $height . '"' : '';
    $class_attr = $class ? ' class="' . $class . '"' : '';
    
    return '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 ' . ($width ?: '300') . ' ' . ($height ?: '200') . '\'%3E%3C/svg%3E" data-src="' . $src . '" alt="' . $alt . '"' . $width_attr . $height_attr . $class_attr . ' loading="lazy">';
}

// Critical CSS inlining
function get_critical_css() {
    return '
    <style>
    /* Critical CSS for above-the-fold content */
    .wrapper-container { max-width: 100%; }
    .site-header { position: relative; z-index: 1000; }
    .carousel { position: relative; }
    .carousel-inner { position: relative; width: 100%; overflow: hidden; }
    .carousel-inner > .item { position: relative; display: none; transition: 0.6s ease-in-out left; }
    .carousel-inner > .item.active { display: block; }
    .carousel-inner > .item > img { display: block; width: 100%; height: auto; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
    .row { margin-left: -15px; margin-right: -15px; }
    .col-sm-12 { width: 100%; padding: 0 15px; }
    .heading_primary { font-size: 2.5rem; font-weight: bold; margin: 20px 0; }
    .breadcrumbs-wrapper { margin: 10px 0; }
    .phys-breadcrumb { list-style: none; padding: 0; margin: 0; }
    .phys-breadcrumb li { display: inline-block; margin-right: 10px; }
    .phys-breadcrumb li:after { content: " > "; margin-left: 10px; }
    .phys-breadcrumb li:last-child:after { display: none; }
    </style>';
}

// Performance monitoring
function track_page_performance() {
    if (isset($_GET['debug_performance'])) {
        $start_time = microtime(true);
        $memory_usage = memory_get_usage(true);
        
        register_shutdown_function(function() use ($start_time, $memory_usage) {
            $end_time = microtime(true);
            $execution_time = $end_time - $start_time;
            $peak_memory = memory_get_peak_usage(true);
            
            echo "<!-- Performance Debug Info:
            Execution Time: " . round($execution_time, 4) . " seconds
            Memory Usage: " . round($memory_usage / 1024 / 1024, 2) . " MB
            Peak Memory: " . round($peak_memory / 1024 / 1024, 2) . " MB
            -->";
        });
    }
}

// Initialize performance tracking
track_page_performance();
?>
