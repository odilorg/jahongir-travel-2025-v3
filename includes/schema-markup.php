<?php
/**
 * Schema Markup Generator for Jahongir Travel
 * Generates structured data for better SEO
 */

class SchemaMarkup {
    
    /**
     * Generate Organization Schema
     */
    public static function getOrganizationSchema() {
        return [
            "@context" => "https://schema.org",
            "@type" => "TravelAgency",
            "name" => "Jahongir Travel",
            "description" => "Expert-guided tours to Uzbekistan and Central Asia. Discover Samarkand, Bukhara, Khiva with UNESCO heritage sites and Silk Road adventures.",
            "url" => "https://jahongir-travel.uz",
            "logo" => "https://jahongir-travel.uz/images/logo_brown.png",
            "image" => "https://jahongir-travel.uz/images/gur-emir.jpg",
            "telephone" => "+998 91 555 0808",
            "email" => "odilorg@gmail.com",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "4 Chirokchi str.",
                "addressLocality" => "Samarkand",
                "addressCountry" => "UZ"
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => "39.650176",
                "longitude" => "66.978082"
            ],
            "openingHours" => "Mo-Su 00:00-23:59",
            "priceRange" => "$$",
            "currenciesAccepted" => "USD, EUR, UZS",
            "paymentAccepted" => "Cash, Credit Card, Bank Transfer",
            "areaServed" => [
                "Uzbekistan",
                "Central Asia",
                "Samarkand",
                "Bukhara", 
                "Khiva",
                "Tashkent"
            ],
            "serviceType" => [
                "Cultural Tours",
                "Adventure Travel",
                "Heritage Tours",
                "Silk Road Tours",
                "UNESCO Site Tours"
            ],
            "sameAs" => [
                "https://jahongir-premium.uz/",
                "https://jahongirbandb.com/"
            ]
        ];
    }
    
    /**
     * Generate Tour Schema
     */
    public static function getTourSchema($tourData) {
        return [
            "@context" => "https://schema.org",
            "@type" => "TouristTrip",
            "name" => $tourData['name'],
            "description" => $tourData['description'],
            "url" => $tourData['url'],
            "image" => $tourData['image'],
            "offers" => [
                "@type" => "Offer",
                "price" => $tourData['price'],
                "priceCurrency" => "USD",
                "availability" => "https://schema.org/InStock",
                "validFrom" => date('Y-m-d'),
                "validThrough" => date('Y-m-d', strtotime('+1 year'))
            ],
            "duration" => $tourData['duration'],
            "touristType" => $tourData['tourist_type'],
            "includedInDataCatalog" => [
                "@type" => "DataCatalog",
                "name" => "Uzbekistan Tours",
                "url" => "https://jahongir-travel.uz/uzbekistan-tours/"
            ],
            "provider" => [
                "@type" => "TravelAgency",
                "name" => "Jahongir Travel",
                "url" => "https://jahongir-travel.uz"
            ],
            "itinerary" => $tourData['itinerary'] ?? [],
            "touristAttraction" => $tourData['attractions'] ?? []
        ];
    }
    
    /**
     * Generate FAQ Schema
     */
    public static function getFAQSchema($faqs) {
        $faqItems = [];
        foreach ($faqs as $faq) {
            $faqItems[] = [
                "@type" => "Question",
                "name" => $faq['question'],
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $faq['answer']
                ]
            ];
        }
        
        return [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => $faqItems
        ];
    }
    
    /**
     * Generate Breadcrumb Schema
     */
    public static function getBreadcrumbSchema($breadcrumbs) {
        $items = [];
        $position = 1;
        
        foreach ($breadcrumbs as $crumb) {
            $items[] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => $crumb['name'],
                "item" => $crumb['url']
            ];
            $position++;
        }
        
        return [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $items
        ];
    }
    
    /**
     * Generate Tourist Attraction Schema
     */
    public static function getTouristAttractionSchema($attractionData) {
        return [
            "@context" => "https://schema.org",
            "@type" => "TouristAttraction",
            "name" => $attractionData['name'],
            "description" => $attractionData['description'],
            "image" => $attractionData['image'],
            "url" => $attractionData['url'],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => $attractionData['latitude'],
                "longitude" => $attractionData['longitude']
            ],
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => $attractionData['city'],
                "addressCountry" => "UZ"
            ],
            "isAccessibleForFree" => $attractionData['free'] ?? false,
            "touristType" => $attractionData['tourist_type'] ?? "Cultural",
            "partOf" => [
                "@type" => "Place",
                "name" => "Uzbekistan"
            ]
        ];
    }
    
    /**
     * Generate Review Schema
     */
    public static function getReviewSchema($reviews) {
        $reviewItems = [];
        
        foreach ($reviews as $review) {
            $reviewItems[] = [
                "@type" => "Review",
                "author" => [
                    "@type" => "Person",
                    "name" => $review['author']
                ],
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => $review['rating'],
                    "bestRating" => "5"
                ],
                "reviewBody" => $review['text'],
                "datePublished" => $review['date']
            ];
        }
        
        return [
            "@context" => "https://schema.org",
            "@type" => "AggregateRating",
            "itemReviewed" => [
                "@type" => "TravelAgency",
                "name" => "Jahongir Travel"
            ],
            "ratingValue" => "4.8",
            "reviewCount" => count($reviews),
            "bestRating" => "5",
            "worstRating" => "1"
        ];
    }
    
    /**
     * Generate Local Business Schema
     */
    public static function getLocalBusinessSchema() {
        return [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "@id" => "https://jahongir-travel.uz/#organization",
            "name" => "Jahongir Travel",
            "image" => "https://jahongir-travel.uz/images/logo_brown.png",
            "telephone" => "+998 91 555 0808",
            "email" => "odilorg@gmail.com",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "4 Chirokchi str.",
                "addressLocality" => "Samarkand",
                "addressRegion" => "Samarkand Region",
                "postalCode" => "140100",
                "addressCountry" => "UZ"
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => "39.650176",
                "longitude" => "66.978082"
            ],
            "url" => "https://jahongir-travel.uz",
            "openingHoursSpecification" => [
                [
                    "@type" => "OpeningHoursSpecification",
                    "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                    "opens" => "00:00",
                    "closes" => "23:59"
                ]
            ],
            "priceRange" => "$$",
            "currenciesAccepted" => "USD, EUR, UZS",
            "paymentAccepted" => "Cash, Credit Card, Bank Transfer"
        ];
    }
    
    /**
     * Output schema as JSON-LD
     */
    public static function outputSchema($schema) {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
    }
}

// Common tour data for Uzbekistan
$uzbekistanTours = [
    'best-of-uzbekistan' => [
        'name' => 'Best of Uzbekistan in 10 Days',
        'description' => 'Complete Uzbekistan tour covering Tashkent, Samarkand, Bukhara, and Khiva. Experience the best of Silk Road heritage.',
        'url' => 'https://jahongir-travel.uz/uzbekistan-tours/best-of-uzbekistan-in-10-days.php',
        'image' => 'https://jahongir-travel.uz/uzbekistan-tours/images/best-of-uzbekistan-in-10-days.jpg',
        'price' => '1200',
        'duration' => 'P10D',
        'tourist_type' => 'Cultural',
        'itinerary' => [
            'Tashkent arrival and city tour',
            'Samarkand - Registan Square, Gur-e Amir',
            'Bukhara - Old city and Ark Fortress',
            'Khiva - Ichan Kala UNESCO site'
        ],
        'attractions' => [
            'Registan Square',
            'Gur-e Amir Mausoleum',
            'Shahi Zinda',
            'Bukhara Old City',
            'Ichan Kala'
        ]
    ],
    'samarkand-city-tour' => [
        'name' => 'Samarkand City Tour',
        'description' => 'One-day comprehensive tour of Samarkand\'s UNESCO World Heritage sites.',
        'url' => 'https://jahongir-travel.uz/tours-from-samarkand/samarkand-city-tour.php',
        'image' => 'https://jahongir-travel.uz/tours-from-samarkand/images/samarkand-city-tour/registan-ensemble-samarkand.jpg',
        'price' => '80',
        'duration' => 'P1D',
        'tourist_type' => 'Cultural',
        'itinerary' => [
            'Registan Square',
            'Gur-e Amir Mausoleum',
            'Shahi Zinda Necropolis',
            'Bibi Khanum Mosque'
        ],
        'attractions' => [
            'Registan Square',
            'Gur-e Amir Mausoleum',
            'Shahi Zinda',
            'Bibi Khanum Mosque'
        ]
    ]
];

// Common FAQs
$commonFAQs = [
    [
        'question' => 'What is the best time to visit Uzbekistan?',
        'answer' => 'The best time to visit Uzbekistan is during spring (April-May) and autumn (September-October) when the weather is mild and pleasant. Summer can be very hot, especially in the desert areas.'
    ],
    [
        'question' => 'Do I need a visa to visit Uzbekistan?',
        'answer' => 'Visa requirements depend on your nationality. Citizens of many countries can now visit Uzbekistan visa-free for up to 30 days. Please check with your local Uzbek embassy for current requirements.'
    ],
    [
        'question' => 'What currency is used in Uzbekistan?',
        'answer' => 'The official currency is the Uzbek Som (UZS). US Dollars and Euros are also widely accepted, especially in tourist areas. Credit cards are accepted in major hotels and restaurants.'
    ],
    [
        'question' => 'Is Uzbekistan safe for tourists?',
        'answer' => 'Yes, Uzbekistan is generally very safe for tourists. The country has a low crime rate and the people are known for their hospitality. However, as with any travel, it\'s important to take normal precautions.'
    ],
    [
        'question' => 'What should I wear when visiting mosques and religious sites?',
        'answer' => 'When visiting mosques and religious sites, dress modestly. Women should cover their heads, shoulders, and knees. Men should avoid shorts and sleeveless shirts. Remove shoes before entering.'
    ]
];
?>

