<?php
/**
 * Internal Linking Strategy for Jahongir Travel
 * Optimizes internal linking for better SEO
 */

class InternalLinking {
    
    /**
     * Get related tours for internal linking
     */
    public static function getRelatedTours($currentTour = null) {
        $allTours = [
            'samarkand-city-tour' => [
                'title' => 'Samarkand City Tour',
                'url' => '../tours-from-samarkand/samarkand-city-tour.php',
                'description' => 'One-day comprehensive tour of Samarkand\'s UNESCO sites',
                'keywords' => ['samarkand', 'city tour', 'unesco', 'registan']
            ],
            'best-of-uzbekistan' => [
                'title' => 'Best of Uzbekistan in 10 Days',
                'url' => '../uzbekistan-tours/best-of-uzbekistan-in-10-days.php',
                'description' => 'Complete Uzbekistan tour covering all major cities',
                'keywords' => ['uzbekistan', 'complete tour', 'samarkand', 'bukhara', 'khiva']
            ],
            'bukhara-city-tour' => [
                'title' => 'Bukhara City Tour',
                'url' => '../tours-from-bukhara/bukhara-city-tour.php',
                'description' => 'Explore Bukhara\'s ancient Silk Road heritage',
                'keywords' => ['bukhara', 'city tour', 'silk road', 'ancient']
            ],
            'khiva-fortresses' => [
                'title' => 'Ancient Fortresses Tour from Khiva',
                'url' => '../tours-from-khiva/tour-from-khiva-ancient-fortresses.php',
                'description' => 'Discover ancient fortresses around Khiva',
                'keywords' => ['khiva', 'fortresses', 'ancient', 'desert']
            ],
            'nuratau-homestay' => [
                'title' => 'Nuratau Mountains Homestay',
                'url' => '../tours-from-samarkand/nuratau-homestay-3-days.php',
                'description' => 'Authentic homestay experience in Nuratau Mountains',
                'keywords' => ['nuratau', 'mountains', 'homestay', 'authentic']
            ],
            'yurt-camp-tour' => [
                'title' => 'Yurt Camp Aydarkul Lake Tour',
                'url' => '../tours-from-samarkand/yurt-camp-tour.php',
                'description' => 'Experience traditional yurt camping by Aydarkul Lake',
                'keywords' => ['yurt', 'camp', 'aydarkul', 'lake', 'traditional']
            ],
            'seven-lakes-tajikistan' => [
                'title' => 'Seven Lakes Tajikistan Tour',
                'url' => '../tajikistan-tours/seven-lakes-tajikistan-tour.php',
                'description' => 'Discover the beautiful Seven Lakes in Tajikistan',
                'keywords' => ['tajikistan', 'seven lakes', 'mountains', 'nature']
            ]
        ];
        
        // Remove current tour from related tours
        if ($currentTour && isset($allTours[$currentTour])) {
            unset($allTours[$currentTour]);
        }
        
        return $allTours;
    }
    
    /**
     * Get contextual internal links based on content
     */
    public static function getContextualLinks($content, $excludeUrl = null) {
        $contextualLinks = [];
        
        // Keywords to tour mapping
        $keywordMap = [
            'samarkand' => [
                'url' => '../tours-from-samarkand/samarkand-city-tour.php',
                'text' => 'Samarkand City Tour',
                'anchor' => 'samarkand-city-tour'
            ],
            'bukhara' => [
                'url' => '../tours-from-bukhara/bukhara-city-tour.php',
                'text' => 'Bukhara City Tour',
                'anchor' => 'bukhara-city-tour'
            ],
            'khiva' => [
                'url' => '../tours-from-khiva/tour-from-khiva-ancient-fortresses.php',
                'text' => 'Khiva Ancient Fortresses Tour',
                'anchor' => 'khiva-fortresses'
            ],
            'registan' => [
                'url' => '../tours-from-samarkand/samarkand-city-tour.php',
                'text' => 'Registan Square Tour',
                'anchor' => 'registan-square'
            ],
            'unesco' => [
                'url' => '../uzbekistan-tours/best-of-uzbekistan-in-10-days.php',
                'text' => 'UNESCO Heritage Sites Tour',
                'anchor' => 'unesco-tour'
            ],
            'silk road' => [
                'url' => '../uzbekistan-tours/golden-journey-to-samarkand.php',
                'text' => 'Silk Road Journey',
                'anchor' => 'silk-road'
            ],
            'mountains' => [
                'url' => '../tours-from-samarkand/nuratau-homestay-3-days.php',
                'text' => 'Nuratau Mountains Tour',
                'anchor' => 'nuratau-mountains'
            ],
            'yurt' => [
                'url' => '../tours-from-samarkand/yurt-camp-tour.php',
                'text' => 'Yurt Camp Experience',
                'anchor' => 'yurt-camp'
            ]
        ];
        
        // Find keywords in content and create links
        foreach ($keywordMap as $keyword => $linkData) {
            if (stripos($content, $keyword) !== false && $linkData['url'] !== $excludeUrl) {
                $contextualLinks[] = $linkData;
            }
        }
        
        return $contextualLinks;
    }
    
    /**
     * Generate related tours section HTML
     */
    public static function generateRelatedToursHTML($currentTour = null, $limit = 4) {
        $relatedTours = self::getRelatedTours($currentTour);
        $relatedTours = array_slice($relatedTours, 0, $limit);
        
        if (empty($relatedTours)) {
            return '';
        }
        
        $html = '<div class="related-tours-section" style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px;">';
        $html .= '<h3 style="color: #333; margin-bottom: 20px; font-size: 1.5em;">Related Tours You Might Enjoy</h3>';
        $html .= '<div class="related-tours-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">';
        
        foreach ($relatedTours as $tour) {
            $html .= '<div class="related-tour-item" style="background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">';
            $html .= '<h4 style="margin: 0 0 10px 0; color: #007cba;"><a href="' . $tour['url'] . '" style="text-decoration: none; color: inherit;">' . $tour['title'] . '</a></h4>';
            $html .= '<p style="margin: 0; color: #666; font-size: 0.9em;">' . $tour['description'] . '</p>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Generate contextual links HTML
     */
    public static function generateContextualLinksHTML($content, $excludeUrl = null) {
        $contextualLinks = self::getContextualLinks($content, $excludeUrl);
        
        if (empty($contextualLinks)) {
            return '';
        }
        
        $html = '<div class="contextual-links" style="margin: 20px 0; padding: 15px; background: #e8f4fd; border-left: 4px solid #007cba; border-radius: 4px;">';
        $html .= '<h4 style="margin: 0 0 10px 0; color: #007cba;">Explore More:</h4>';
        $html .= '<ul style="margin: 0; padding-left: 20px;">';
        
        foreach ($contextualLinks as $link) {
            $html .= '<li style="margin: 5px 0;"><a href="' . $link['url'] . '" style="color: #007cba; text-decoration: none;">' . $link['text'] . '</a></li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Generate breadcrumb navigation
     */
    public static function generateBreadcrumbHTML($breadcrumbs) {
        $html = '<nav class="breadcrumb" style="margin: 10px 0; font-size: 0.9em;">';
        $html .= '<ol style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap;">';
        
        $lastIndex = count($breadcrumbs) - 1;
        foreach ($breadcrumbs as $index => $crumb) {
            $html .= '<li style="display: flex; align-items: center;">';
            
            if ($index === $lastIndex) {
                // Last item - no link
                $html .= '<span style="color: #666;">' . $crumb['name'] . '</span>';
            } else {
                // Link item
                $html .= '<a href="' . $crumb['url'] . '" style="color: #007cba; text-decoration: none;">' . $crumb['name'] . '</a>';
                $html .= '<span style="margin: 0 8px; color: #999;">›</span>';
            }
            
            $html .= '</li>';
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * Generate footer links section
     */
    public static function generateFooterLinksHTML() {
        $footerLinks = [
            'Popular Tours' => [
                'Samarkand City Tour' => '../tours-from-samarkand/samarkand-city-tour.php',
                'Best of Uzbekistan' => '../uzbekistan-tours/best-of-uzbekistan-in-10-days.php',
                'Bukhara City Tour' => '../tours-from-bukhara/bukhara-city-tour.php',
                'Khiva Fortresses' => '../tours-from-khiva/tour-from-khiva-ancient-fortresses.php'
            ],
            'Destinations' => [
                'Samarkand Tours' => '../tours-from-samarkand/index.php',
                'Bukhara Tours' => '../tours-from-bukhara/index.php',
                'Khiva Tours' => '../tours-from-khiva/index.php',
                'Tajikistan Tours' => '../tajikistan-tours/index.php'
            ],
            'Tour Types' => [
                'Cultural Tours' => '../uzbekistan-tours/index.php',
                'Adventure Tours' => '../tours-from-samarkand/hiking-amankutan.php',
                'Homestay Tours' => '../tours-from-samarkand/nuratau-homestay-3-days.php',
                'Yurt Camp Tours' => '../tours-from-samarkand/yurt-camp-tour.php'
            ]
        ];
        
        $html = '<div class="footer-links" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin: 30px 0;">';
        
        foreach ($footerLinks as $category => $links) {
            $html .= '<div class="footer-link-category">';
            $html .= '<h4 style="color: #333; margin-bottom: 15px; font-size: 1.1em;">' . $category . '</h4>';
            $html .= '<ul style="list-style: none; padding: 0; margin: 0;">';
            
            foreach ($links as $text => $url) {
                $html .= '<li style="margin: 8px 0;"><a href="' . $url . '" style="color: #666; text-decoration: none; font-size: 0.9em;">' . $text . '</a></li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Generate call-to-action sections
     */
    public static function generateCTASection($type = 'general') {
        $ctas = [
            'general' => [
                'title' => 'Ready to Explore Uzbekistan?',
                'description' => 'Book your authentic Uzbekistan tour with Jahongir Travel and discover the beauty of Central Asia.',
                'button_text' => 'View All Tours',
                'button_url' => '../uzbekistan-tours/index.php',
                'secondary_text' => 'Contact us for custom itineraries',
                'secondary_url' => '../contact.php'
            ],
            'samarkand' => [
                'title' => 'Discover Samarkand\'s Heritage',
                'description' => 'Explore the magnificent Registan Square and UNESCO World Heritage sites of Samarkand.',
                'button_text' => 'Samarkand Tours',
                'button_url' => '../tours-from-samarkand/index.php',
                'secondary_text' => 'City Tour Details',
                'secondary_url' => '../tours-from-samarkand/samarkand-city-tour.php'
            ],
            'bukhara' => [
                'title' => 'Step into Bukhara\'s History',
                'description' => 'Walk through ancient streets and discover the rich Silk Road heritage of Bukhara.',
                'button_text' => 'Bukhara Tours',
                'button_url' => '../tours-from-bukhara/index.php',
                'secondary_text' => 'City Tour Details',
                'secondary_url' => '../tours-from-bukhara/bukhara-city-tour.php'
            ]
        ];
        
        $cta = $ctas[$type] ?? $ctas['general'];
        
        $html = '<div class="cta-section" style="margin: 40px 0; padding: 30px; background: linear-gradient(135deg, #007cba, #005a87); color: white; border-radius: 10px; text-align: center;">';
        $html .= '<h3 style="margin: 0 0 15px 0; font-size: 1.8em;">' . $cta['title'] . '</h3>';
        $html .= '<p style="margin: 0 0 25px 0; font-size: 1.1em; opacity: 0.9;">' . $cta['description'] . '</p>';
        $html .= '<div class="cta-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">';
        $html .= '<a href="' . $cta['button_url'] . '" style="background: white; color: #007cba; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block;">' . $cta['button_text'] . '</a>';
        $html .= '<a href="' . $cta['secondary_url'] . '" style="border: 2px solid white; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; display: inline-block;">' . $cta['secondary_text'] . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
?>
