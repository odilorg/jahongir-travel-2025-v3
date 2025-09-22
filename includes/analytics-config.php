<?php
// Advanced Analytics and Monitoring Configuration
// This file provides comprehensive tracking and monitoring setup

class AnalyticsManager {
    private $config;
    
    public function __construct() {
        $this->config = [
            'google_analytics_id' => 'UA-11149707-6',
            'google_tag_manager_id' => '', // Add if you have GTM
            'facebook_pixel_id' => '', // Add if you have Facebook Pixel
            'hotjar_id' => '', // Add if you have Hotjar
            'enable_performance_tracking' => true,
            'enable_error_tracking' => true,
            'enable_user_behavior_tracking' => true
        ];
    }
    
    public function getGoogleAnalyticsConfig() {
        return [
            'tracking_id' => $this->config['google_analytics_id'],
            'enhanced_ecommerce' => true,
            'custom_dimensions' => [
                'cd1' => 'Tour Category',
                'cd2' => 'Tour Duration',
                'cd3' => 'Customer Country',
                'cd4' => 'Booking Source'
            ],
            'custom_metrics' => [
                'cm1' => 'Tour Price',
                'cm2' => 'Page Load Time',
                'cm3' => 'User Engagement Score'
            ],
            'goals' => [
                'contact_form_submission' => 'Contact Form',
                'tour_inquiry' => 'Tour Inquiry',
                'booking_request' => 'Booking Request',
                'phone_call' => 'Phone Call'
            ]
        ];
    }
    
    public function getPerformanceTrackingScript() {
        if (!$this->config['enable_performance_tracking']) {
            return '';
        }
        
        return "
        <script>
        // Core Web Vitals Tracking
        function sendToAnalytics(data) {
            if (typeof gtag !== 'undefined') {
                gtag('event', data.name, {
                    event_category: 'Web Vitals',
                    event_label: data.id,
                    value: Math.round(data.value),
                    non_interaction: true
                });
            }
        }
        
        // Track Largest Contentful Paint
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                sendToAnalytics({
                    name: 'LCP',
                    value: entry.startTime,
                    id: entry.id
                });
            }
        }).observe({entryTypes: ['largest-contentful-paint']});
        
        // Track First Input Delay
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                sendToAnalytics({
                    name: 'FID',
                    value: entry.processingStart - entry.startTime,
                    id: entry.id
                });
            }
        }).observe({entryTypes: ['first-input']});
        
        // Track Cumulative Layout Shift
        let clsValue = 0;
        new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            }
            sendToAnalytics({
                name: 'CLS',
                value: clsValue,
                id: 'cls-' + Date.now()
            });
        }).observe({entryTypes: ['layout-shift']});
        </script>";
    }
    
    public function getErrorTrackingScript() {
        if (!$this->config['enable_error_tracking']) {
            return '';
        }
        
        return "
        <script>
        // JavaScript Error Tracking
        window.addEventListener('error', function(e) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'exception', {
                    description: e.message,
                    fatal: false,
                    file: e.filename,
                    line: e.lineno
                });
            }
        });
        
        // Unhandled Promise Rejection Tracking
        window.addEventListener('unhandledrejection', function(e) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'exception', {
                    description: e.reason.toString(),
                    fatal: false,
                    type: 'unhandled_promise_rejection'
                });
            }
        });
        </script>";
    }
    
    public function getUserBehaviorTrackingScript() {
        if (!$this->config['enable_user_behavior_tracking']) {
            return '';
        }
        
        return "
        <script>
        // Track scroll depth
        let maxScroll = 0;
        window.addEventListener('scroll', function() {
            const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
            if (scrollPercent > maxScroll) {
                maxScroll = scrollPercent;
                if (scrollPercent % 25 === 0) { // Track at 25%, 50%, 75%, 100%
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            event_category: 'Engagement',
                            event_label: scrollPercent + '%',
                            value: scrollPercent
                        });
                    }
                }
            }
        });
        
        // Track time on page
        let startTime = Date.now();
        window.addEventListener('beforeunload', function() {
            const timeOnPage = Math.round((Date.now() - startTime) / 1000);
            if (typeof gtag !== 'undefined') {
                gtag('event', 'time_on_page', {
                    event_category: 'Engagement',
                    value: timeOnPage
                });
            }
        });
        
        // Track form interactions
        document.addEventListener('submit', function(e) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'form_submit', {
                    event_category: 'Form',
                    event_label: e.target.id || e.target.className
                });
            }
        });
        
        // Track external link clicks
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.hostname !== window.location.hostname) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'external_link_click', {
                        event_category: 'Outbound',
                        event_label: e.target.href
                    });
                }
            }
        });
        </script>";
    }
    
    public function getEcommerceTrackingScript() {
        return "
        <script>
        // Enhanced Ecommerce for Tour Bookings
        function trackTourView(tourData) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'view_item', {
                    currency: 'USD',
                    value: tourData.price,
                    items: [{
                        item_id: tourData.id,
                        item_name: tourData.name,
                        category: tourData.category,
                        price: tourData.price,
                        quantity: 1
                    }]
                });
            }
        }
        
        function trackTourInquiry(tourData) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'begin_checkout', {
                    currency: 'USD',
                    value: tourData.price,
                    items: [{
                        item_id: tourData.id,
                        item_name: tourData.name,
                        category: tourData.category,
                        price: tourData.price,
                        quantity: 1
                    }]
                });
            }
        }
        
        function trackBookingRequest(tourData) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'purchase', {
                    transaction_id: 'booking_' + Date.now(),
                    currency: 'USD',
                    value: tourData.price,
                    items: [{
                        item_id: tourData.id,
                        item_name: tourData.name,
                        category: tourData.category,
                        price: tourData.price,
                        quantity: 1
                    }]
                });
            }
        }
        </script>";
    }
    
    public function getCustomDimensionsScript() {
        return "
        <script>
        // Set custom dimensions
        function setCustomDimensions() {
            if (typeof gtag !== 'undefined') {
                // Set user's country
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => {
                        gtag('config', '" . $this->config['google_analytics_id'] . "', {
                            custom_map: {
                                'cd3': data.country_name
                            }
                        });
                    });
                
                // Set page type
                gtag('config', '" . $this->config['google_analytics_id'] . "', {
                    custom_map: {
                        'cd4': window.location.pathname.includes('tour') ? 'Tour Page' : 'Other'
                    }
                });
            }
        }
        
        // Call when page loads
        document.addEventListener('DOMContentLoaded', setCustomDimensions);
        </script>";
    }
    
    public function getAllTrackingScripts() {
        return $this->getPerformanceTrackingScript() . 
               $this->getErrorTrackingScript() . 
               $this->getUserBehaviorTrackingScript() . 
               $this->getEcommerceTrackingScript() . 
               $this->getCustomDimensionsScript();
    }
}

// SEO Performance Monitoring
class SEOMonitor {
    public function getSEOTrackingScript() {
        return "
        <script>
        // Track SEO-related metrics
        function trackSEOMetrics() {
            // Track page load performance
            window.addEventListener('load', function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                if (perfData && typeof gtag !== 'undefined') {
                    gtag('event', 'page_load_time', {
                        event_category: 'Performance',
                        value: Math.round(perfData.loadEventEnd - perfData.loadEventStart)
                    });
                }
            });
            
            // Track mobile usability
            if (window.innerWidth < 768) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'mobile_visit', {
                        event_category: 'Device',
                        event_label: 'Mobile'
                    });
                }
            }
            
            // Track search engine referrals
            const referrer = document.referrer;
            if (referrer.includes('google.') || referrer.includes('bing.') || referrer.includes('yahoo.')) {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'search_engine_referral', {
                        event_category: 'Traffic Source',
                        event_label: referrer
                    });
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', trackSEOMetrics);
        </script>";
    }
}

// Initialize analytics
$analytics = new AnalyticsManager();
$seoMonitor = new SEOMonitor();
?>

