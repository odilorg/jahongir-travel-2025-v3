<?php
/**
 * Image SEO Optimizer for Jahongir Travel
 * Generates optimized alt text, titles, and captions for images
 */

class ImageSEO {
    
    /**
     * Get optimized alt text for images based on filename and context
     */
    public static function getAltText($imagePath, $context = 'general') {
        $filename = basename($imagePath, '.jpg');
        $filename = basename($filename, '.png');
        $filename = basename($filename, '.webp');
        
        // Convert filename to readable text
        $altText = str_replace(['-', '_'], ' ', $filename);
        $altText = ucwords($altText);
        
        // Add context-specific keywords
        switch ($context) {
            case 'samarkand':
                $altText = str_replace(['Samarkand', 'Registan', 'Gur Emir', 'Shahi Zinda'], 
                    ['Samarkand UNESCO World Heritage', 'Registan Square Samarkand', 'Gur-e Amir Mausoleum Samarkand', 'Shahi Zinda Necropolis Samarkand'], 
                    $altText);
                break;
            case 'bukhara':
                $altText = str_replace(['Bukhara', 'Ark', 'Kalyan'], 
                    ['Bukhara UNESCO World Heritage', 'Ark Fortress Bukhara', 'Kalyan Minaret Bukhara'], 
                    $altText);
                break;
            case 'khiva':
                $altText = str_replace(['Khiva', 'Ichan Kala'], 
                    ['Khiva UNESCO World Heritage', 'Ichan Kala Khiva'], 
                    $altText);
                break;
            case 'uzbekistan':
                $altText = str_replace(['Uzbekistan', 'Silk Road'], 
                    ['Uzbekistan Travel', 'Silk Road Uzbekistan'], 
                    $altText);
                break;
        }
        
        // Add travel-related keywords
        if (strpos($altText, 'Tour') === false && strpos($altText, 'Travel') === false) {
            $altText .= ' Uzbekistan Travel';
        }
        
        return $altText;
    }
    
    /**
     * Get image title for better SEO
     */
    public static function getImageTitle($imagePath, $context = 'general') {
        $altText = self::getAltText($imagePath, $context);
        
        // Make title more descriptive
        $title = $altText;
        
        // Add location context
        if ($context === 'samarkand') {
            $title = str_replace('Uzbekistan Travel', 'Samarkand Uzbekistan Tour', $title);
        } elseif ($context === 'bukhara') {
            $title = str_replace('Uzbekistan Travel', 'Bukhara Uzbekistan Tour', $title);
        } elseif ($context === 'khiva') {
            $title = str_replace('Uzbekistan Travel', 'Khiva Uzbekistan Tour', $title);
        }
        
        return $title;
    }
    
    /**
     * Get image caption for better context
     */
    public static function getImageCaption($imagePath, $context = 'general') {
        $filename = basename($imagePath);
        
        // Generate contextual captions
        $captions = [
            'registan' => 'Registan Square in Samarkand - UNESCO World Heritage Site featuring three magnificent madrasahs',
            'gur-emir' => 'Gur-e Amir Mausoleum in Samarkand - Final resting place of Tamerlane and his descendants',
            'shahi-zinda' => 'Shahi Zinda Necropolis in Samarkand - Beautiful blue-tiled mausoleums and tombs',
            'bibi-khanum' => 'Bibi Khanum Mosque in Samarkand - One of Central Asia\'s largest mosques',
            'ark-fortress' => 'Ark Fortress in Bukhara - Ancient citadel dating back 1,500 years',
            'kalyan' => 'Kalyan Minaret in Bukhara - Famous tower that survived Genghis Khan\'s invasion',
            'samanid' => 'Samanid Mausoleum in Bukhara - One of the oldest Islamic monuments in Central Asia',
            'ichan-kala' => 'Ichan Kala in Khiva - Well-preserved medieval city within fortress walls',
            'yurt' => 'Traditional yurt camp experience in Uzbekistan - Authentic nomadic accommodation',
            'nuratau' => 'Nuratau Mountains in Uzbekistan - Beautiful mountain landscapes and hiking trails',
            'aydarkul' => 'Aydarkul Lake in Uzbekistan - Desert lake perfect for yurt camping and camel riding',
            'silk-road' => 'Silk Road heritage in Uzbekistan - Ancient trade route connecting East and West',
            'uzbekistan' => 'Beautiful Uzbekistan - Central Asian country rich in history and culture',
            'samarkand' => 'Samarkand Uzbekistan - Ancient city on the Silk Road with stunning architecture',
            'bukhara' => 'Bukhara Uzbekistan - Historic city known for its Islamic architecture',
            'khiva' => 'Khiva Uzbekistan - Preserved medieval city in the Khorezm region'
        ];
        
        // Find matching caption
        foreach ($captions as $keyword => $caption) {
            if (stripos($filename, $keyword) !== false) {
                return $caption;
            }
        }
        
        // Default caption
        return 'Uzbekistan travel destination - Discover the beauty of Central Asia with Jahongir Travel';
    }
    
    /**
     * Generate optimized image HTML with SEO attributes
     */
    public static function generateOptimizedImage($imagePath, $context = 'general', $width = null, $height = null, $class = '') {
        // Get WebP version if available
        $webpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $imagePath);
        $hasWebp = file_exists($webpPath);
        
        // Generate SEO attributes
        $altText = self::getAltText($imagePath, $context);
        $title = self::getImageTitle($imagePath, $context);
        $caption = self::getImageCaption($imagePath, $context);
        
        // Build attributes
        $widthAttr = $width ? ' width="' . $width . '"' : '';
        $heightAttr = $height ? ' height="' . $height . '"' : '';
        $classAttr = $class ? ' class="' . $class . '"' : '';
        
        $html = '';
        
        if ($hasWebp) {
            // Serve WebP with fallback
            $html .= '<picture>';
            $html .= '<source srcset="' . $webpPath . '" type="image/webp">';
            $html .= '<img src="' . $imagePath . '" alt="' . htmlspecialchars($altText) . '" title="' . htmlspecialchars($title) . '"' . $widthAttr . $heightAttr . $classAttr . ' loading="lazy">';
            $html .= '</picture>';
        } else {
            // Serve original image
            $html .= '<img src="' . $imagePath . '" alt="' . htmlspecialchars($altText) . '" title="' . htmlspecialchars($title) . '"' . $widthAttr . $heightAttr . $classAttr . ' loading="lazy">';
        }
        
        // Add caption if needed
        if ($caption) {
            $html .= '<figcaption style="font-size: 0.9em; color: #666; margin-top: 5px; text-align: center;">' . htmlspecialchars($caption) . '</figcaption>';
        }
        
        return $html;
    }
    
    /**
     * Generate image with figure wrapper for better semantic HTML
     */
    public static function generateFigureImage($imagePath, $context = 'general', $width = null, $height = null, $class = '') {
        $html = '<figure style="margin: 0; text-align: center;">';
        $html .= self::generateOptimizedImage($imagePath, $context, $width, $height, $class);
        $html .= '</figure>';
        
        return $html;
    }
    
    /**
     * Get image schema markup for rich snippets
     */
    public static function getImageSchema($imagePath, $context = 'general') {
        $altText = self::getAltText($imagePath, $context);
        $caption = self::getImageCaption($imagePath, $context);
        
        return [
            "@type" => "ImageObject",
            "url" => "https://jahongir-travel.uz/" . $imagePath,
            "name" => $altText,
            "description" => $caption,
            "contentUrl" => "https://jahongir-travel.uz/" . $imagePath,
            "encodingFormat" => "image/jpeg"
        ];
    }
    
    /**
     * Generate gallery with optimized images
     */
    public static function generateImageGallery($images, $context = 'general', $columns = 3) {
        $html = '<div class="image-gallery" style="display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: 20px; margin: 30px 0;">';
        
        foreach ($images as $image) {
            $html .= '<div class="gallery-item">';
            $html .= self::generateFigureImage($image['path'], $context, $image['width'] ?? null, $image['height'] ?? null, 'gallery-image');
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get common image paths for different contexts
     */
    public static function getContextImages($context) {
        $images = [
            'samarkand' => [
                ['path' => 'tours-from-samarkand/images/samarkand-city-tour/registan-ensemble-samarkand.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-samarkand/images/samarkand-city-tour/gur-emir-mausoleum.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-samarkand/images/samarkand-city-tour/shahi-zinda-entrance-portal.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-samarkand/images/samarkand-city-tour/bibi-khanum-mosque.jpg', 'width' => 800, 'height' => 600]
            ],
            'bukhara' => [
                ['path' => 'uzbekistan-tours/images/ark-bukhara.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/poi-kalon-bukhara.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/mausoleum-samanids-bukhara.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/bukhara-walls.jpg', 'width' => 800, 'height' => 600]
            ],
            'khiva' => [
                ['path' => 'uzbekistan-tours/images/khiva-ichan-kala-walls.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-khiva/images/ayaz-qala-1.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-khiva/images/qizil-qala-khiva-khorezm.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'tours-from-khiva/images/toprak-qala.jpg', 'width' => 800, 'height' => 600]
            ],
            'uzbekistan' => [
                ['path' => 'images/gur-emir.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/registan-square-samarkand.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/best-of-uzbekistan-in-10-days.jpg', 'width' => 800, 'height' => 600],
                ['path' => 'uzbekistan-tours/images/golden-journey-to-samarkand.jpg', 'width' => 800, 'height' => 600]
            ]
        ];
        
        return $images[$context] ?? [];
    }
}

// Common image optimization functions
function get_seo_optimized_image($imagePath, $context = 'general', $width = null, $height = null, $class = '') {
    return ImageSEO::generateOptimizedImage($imagePath, $context, $width, $height, $class);
}

function get_figure_image($imagePath, $context = 'general', $width = null, $height = null, $class = '') {
    return ImageSEO::generateFigureImage($imagePath, $context, $width, $height, $class);
}

function get_image_gallery($images, $context = 'general', $columns = 3) {
    return ImageSEO::generateImageGallery($images, $context, $columns);
}
?>
