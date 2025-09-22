
<?php
// Performance optimization enabled
// include 'performance-optimization.php';
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<head>
<!-- Google Analytics - moved to footer for better performance -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	
	<!-- SEO Meta Tags -->
	<meta name="description" content="<?php echo isset($meta_description) ? $meta_description : 'Discover Uzbekistan with Jahongir Travel. Expert-guided tours to Samarkand, Bukhara, Khiva. UNESCO heritage sites, Silk Road adventures, cultural experiences.'; ?>">
	<meta name="keywords" content="<?php echo isset($meta_keywords) ? $meta_keywords : 'Uzbekistan tours, Samarkand tours, Bukhara tours, Khiva tours, Silk Road tours, Central Asia travel, UNESCO heritage sites, cultural tours, adventure travel'; ?>">
	<meta name="author" content="Jahongir Travel">
	<meta name="robots" content="index, follow">
	<meta name="language" content="English">
	<meta name="revisit-after" content="7 days">
	
	<!-- Canonical URL -->
	<link rel="canonical" href="<?php echo isset($canonical_url) ? $canonical_url : 'https://jahongir-travel.uz' . $_SERVER['REQUEST_URI']; ?>">
	
	<!-- Open Graph Meta Tags -->
	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:description" content="<?php echo isset($meta_description) ? $meta_description : 'Discover Uzbekistan with Jahongir Travel. Expert-guided tours to Samarkand, Bukhara, Khiva.'; ?>">
	<meta property="og:type" content="<?php echo isset($og_type) ? $og_type : 'website'; ?>">
	<meta property="og:url" content="<?php echo 'https://jahongir-travel.uz' . $_SERVER['REQUEST_URI']; ?>">
	<meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'https://jahongir-travel.uz/images/logo_brown.png'; ?>">
	<meta property="og:site_name" content="Jahongir Travel">
	<meta property="og:locale" content="en_US">
	
	<!-- Twitter Card Meta Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	<meta name="twitter:description" content="<?php echo isset($meta_description) ? $meta_description : 'Discover Uzbekistan with Jahongir Travel. Expert-guided tours to Samarkand, Bukhara, Khiva.'; ?>">
	<meta name="twitter:image" content="<?php echo isset($og_image) ? $og_image : 'https://jahongir-travel.uz/images/logo_brown.png'; ?>">
	
	<!-- Additional SEO Meta Tags -->
	<meta name="geo.region" content="UZ">
	<meta name="geo.placename" content="Samarkand">
	<meta name="geo.position" content="39.650176;66.978082">
	<meta name="ICBM" content="39.650176, 66.978082">
	
	<!-- Critical CSS -->
	<?php // echo get_critical_css(); ?>
	
	<!-- Preload Critical Resources -->
	<link rel="preload" href="assets/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="assets/css/style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="assets/css/travel-setting.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="assets/js/jquery.min.js" as="script">
	<link rel="preload" href="assets/js/bootstrap.min.js" as="script">
	
	<!-- DNS Prefetch for External Resources -->
	<link rel="dns-prefetch" href="//fonts.googleapis.com">
	<link rel="dns-prefetch" href="//www.googletagmanager.com">
	<link rel="dns-prefetch" href="//www.google.com">
	
	<!-- Schema Markup -->
	<?php
	include 'schema-markup.php';
	include 'image-seo.php';
	
	// Organization Schema
	echo SchemaMarkup::outputSchema(SchemaMarkup::getOrganizationSchema());
	
	// Local Business Schema
	echo SchemaMarkup::outputSchema(SchemaMarkup::getLocalBusinessSchema());
	
	// Breadcrumb Schema
	$breadcrumbs = [
		['name' => 'Home', 'url' => 'https://jahongir-travel.uz/'],
		['name' => 'Tours', 'url' => 'https://jahongir-travel.uz/index']
	];
	echo SchemaMarkup::outputSchema(SchemaMarkup::getBreadcrumbSchema($breadcrumbs));
	
	// FAQ Schema
	echo SchemaMarkup::outputSchema(SchemaMarkup::getFAQSchema($commonFAQs));
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="xmlrpc.html">
	
	<!-- Menu Animation Fix -->
	<link rel="stylesheet" href="assets/css/menu-fix.css">
	
	<!-- JavaScript fix for menu wiggle and submenu flash -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Prevent submenu flash on click
		var menuItems = document.querySelectorAll('.navbar-nav > li.menu-item-has-children > a');
		menuItems.forEach(function(item) {
			item.addEventListener('click', function(e) {
				// Hide submenu immediately on click
				var submenu = this.nextElementSibling;
				if (submenu && submenu.classList.contains('sub-menu')) {
					submenu.style.visibility = 'hidden';
					submenu.style.opacity = '0';
					submenu.style.transform = 'translateY(-10px)';
					submenu.style.pointerEvents = 'none';
				}
			});
			
			// Prevent focus from showing submenu
			item.addEventListener('focus', function(e) {
				var submenu = this.nextElementSibling;
				if (submenu && submenu.classList.contains('sub-menu')) {
					submenu.style.visibility = 'hidden';
					submenu.style.opacity = '0';
					submenu.style.transform = 'translateY(-10px)';
					submenu.style.pointerEvents = 'none';
				}
			});
		});
		
		// Ensure smooth hover behavior
		var menuParents = document.querySelectorAll('.navbar-nav > li.menu-item-has-children');
		menuParents.forEach(function(parent) {
			parent.addEventListener('mouseenter', function() {
				var submenu = this.querySelector('.sub-menu');
				if (submenu) {
					submenu.style.visibility = 'visible';
					submenu.style.opacity = '1';
					submenu.style.transform = 'translateY(0)';
					submenu.style.pointerEvents = 'auto';
				}
			});
			
			parent.addEventListener('mouseleave', function() {
				var submenu = this.querySelector('.sub-menu');
				if (submenu) {
					submenu.style.visibility = 'hidden';
					submenu.style.opacity = '0';
					submenu.style.transform = 'translateY(-10px)';
					submenu.style.pointerEvents = 'none';
				}
			});
		});
		
		// CRITICAL: Prevent all visual effects on click
		var allMenuLinks = document.querySelectorAll('.navbar-nav > li > a');
		allMenuLinks.forEach(function(link) {
			link.addEventListener('mousedown', function(e) {
				// Disable all transitions during click
				this.style.transition = 'none';
				this.style.webkitTransition = 'none';
				this.style.mozTransition = 'none';
				this.style.msTransition = 'none';
				this.style.oTransition = 'none';
				
				// Ensure no transforms
				this.style.transform = 'none';
				this.style.webkitTransform = 'none';
				this.style.mozTransform = 'none';
				this.style.msTransform = 'none';
				this.style.oTransform = 'none';
				
				// Ensure no animations
				this.style.animation = 'none';
				this.style.webkitAnimation = 'none';
				this.style.mozAnimation = 'none';
				this.style.msAnimation = 'none';
				this.style.oAnimation = 'none';
			});
			
			link.addEventListener('mouseup', function(e) {
				// Re-enable only color transition after click
				this.style.transition = 'color 0.1s ease';
				this.style.webkitTransition = 'color 0.1s ease';
				this.style.mozTransition = 'color 0.1s ease';
				this.style.msTransition = 'color 0.1s ease';
				this.style.oTransition = 'color 0.1s ease';
			});
			
			link.addEventListener('click', function(e) {
				// Ensure no visual effects during navigation
				this.style.transition = 'none';
				this.style.transform = 'none';
				this.style.animation = 'none';
			});
		});
	});
	</script>
	
	<!-- CSS Loading -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/travel-setting.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/flaticon.css">
	<link rel="stylesheet" href="assets/css/font-linearicons.css">
	<link rel="stylesheet" href="assets/css/booking.css">
	<link rel="stylesheet" href="assets/css/swipebox.min.css">
	
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	
	<!-- Additional CSS for specific pages -->
	<link rel="preload" href="thumbnail-gallery.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

</head>
<body class="single-product travel_tour-page travel_tour">
<style>
        /* WhatsApp button styling */
        .whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 9999; /* Ensures the button stays above other elements */
        }

        .whatsapp-button:hover {
            background-color: #1ebc53;
        }

        .whatsapp-button img {
            width: 35px;
            height: 35px;
        }
    </style>
    
     <a href="https://wa.me/998940771303" target="_blank" class="whatsapp-button">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    </a>

<header id="masthead" class="site-header sticky_header affix-top">
		<div class="header_top_bar">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<aside id="text-15" class="widget_text">
							<div class="textwidget">
								<ul class="top_bar_info clearfix">
									<li><i class="fa fa-clock-o"></i> Mon - Sun 08.00 - 18.00</li>
									<li><i class="fa fa-envelope-o"></i>info@jahongir-travel.uz</li>
								</ul>

							</div>
						</aside>
					</div>
					<div class="col-sm-8 topbar-right">
						<aside id="text-7" class="widget widget_text">
							<div class="textwidget">
								<ul class="top_bar_info clearfix">
									<li><i class="fa fa-whatsapp"></i> +998 91 555 08 08</li>
									<li class="hidden-info">
										<i class="fa fa-map-marker"></i> 4 Chirokchi str., Samarkand, Uzbekistan
									</li>
								</ul>
							</div>
						</aside>
							 
					</div>

				</div>
			</div>
		</div>
		<div class="navigation-menu">
			<div class="container">
				<div class="menu-mobile-effect navbar-toggle button-collapse" data-activates="mobile-demo">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				<div class="width-logo sm-logo">
					<a href="index.php" title="Jahongir Travel" rel="home">
						<img src="images/logo_brown.png" alt="Logo" width="474" height="130" class="logo_transparent_static">
						<img src="images/logo_sticky.png" alt="Sticky logo" width="474" height="130" class="logo_sticky">
					</a>
				</div>
				<nav class="width-navigation">
					<ul class="nav navbar-nav menu-main-menu side-nav" id="mobile-demo">
						<li >
							<a href="aboutus.php">About Us</a>
						</li>
						<li class="menu-item-has-children current-menu-parent">
							<a href="index.php">Tours</a>
							<ul class="sub-menu">
								<li><a href="uzbekistan-tours/index.php">Uzbekistan Tours</a></li>
								<li><a href="tours-from-samarkand/index.php">Samarkand Tours</a></li>
								<li><a href="tours-from-bukhara/index.php">Bukhara Tours</a></li>
								<li><a href="tours-from-khiva/tour-from-khiva-ancient-fortresses.php">Khiva Tours</a></li>
								<li><a href="tajikistan-tours/seven-lakes-tajikistan-tour.php">Tajikistan Tours</a></li>
							</ul>
						</li>
						<li>
							<a href="uzbekistan-travel-guide.php">Travel Guide</a>
						</li>
						<li>
							<a href="blog/index.php">Travel Blog</a>
						</li>
						

						
						<li><a href="contact.php">Contact US</a></li>
						<li class="menu-item-has-children current-menu-parent">
							<a href="index.php">Our Hotels</a>
							<ul class="sub-menu">
								<li><a href="https://jahongir-premium.uz/">Jahongir Premium Hotel</a></li>
								<li><a href="https://jahongirbandb.com/">Jahongir Guest House</a></li>
							
								
							</ul>
						</li>
						
						
						
					
						
					</ul>
				</nav>
			</div>
		</div>
	</header>