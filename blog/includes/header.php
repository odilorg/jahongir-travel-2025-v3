<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Uzbekistan Travel Blog - Expert Travel Tips & Local Insights'; ?></title>
    <meta name="description" content="<?php echo isset($meta_description) ? $meta_description : 'Expert travel tips, local insights, and everything you need to know about visiting Uzbekistan. From locals born and raised in Samarkand.'; ?>">
    <meta name="keywords" content="<?php echo isset($meta_keywords) ? $meta_keywords : 'Uzbekistan travel blog, travel tips Uzbekistan, Samarkand travel guide, Bukhara travel, Khiva travel, Central Asia travel'; ?>">
	<meta name="author" content="Jahongir Travel">
	<meta name="robots" content="index, follow">
	<meta name="language" content="en">
	<meta name="revisit-after" content="7 days">
	
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($title) ? $title : 'Uzbekistan Travel Blog - Expert Travel Tips & Local Insights'; ?>">
    <meta property="og:description" content="<?php echo isset($meta_description) ? $meta_description : 'Expert travel tips, local insights, and everything you need to know about visiting Uzbekistan. From locals born and raised in Samarkand.'; ?>">
    <meta property="og:type" content="<?php echo isset($og_type) ? $og_type : 'website'; ?>">
    <meta property="og:url" content="<?php echo isset($canonical_url) ? $canonical_url : ('https://jahongir-travel.uz' . $_SERVER['REQUEST_URI']); ?>">
    <meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'https://jahongir-travel.uz/images/logo_brown.png'; ?>">
	<meta property="og:site_name" content="Jahongir Travel">
	<meta property="og:locale" content="en_US">
	
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($title) ? $title : 'Uzbekistan Travel Blog - Expert Travel Tips & Local Insights'; ?>">
    <meta name="twitter:description" content="<?php echo isset($meta_description) ? $meta_description : 'Expert travel tips, local insights, and everything you need to know about visiting Uzbekistan. From locals born and raised in Samarkand.'; ?>">
    <meta name="twitter:image" content="<?php echo isset($og_image) ? $og_image : 'https://jahongir-travel.uz/images/logo_brown.png'; ?>">
	
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo isset($canonical_url) ? $canonical_url : ('https://jahongir-travel.uz' . $_SERVER['REQUEST_URI']); ?>">
	
	<!-- Favicon -->
	<link rel="icon" type="image/png" href="../images/favicon.png">
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/css/flaticon.css">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/menu-fix.css">
	
	<!-- Modern Blog Styling -->
	<link rel="stylesheet" href="../assets/css/blog-modern.css?v=<?php echo time(); ?>">
	
	<!-- Inline CSS for immediate styling -->
	<style>
	/* Modern Blog Styling */
	body {
		background-color: #f8f9fa !important;
	}

	/* Match main site logo sizing in header */
	.navigation-menu .width-logo img,
	.width-logo.sm-logo img,
	.logo_transparent_static,
	.logo_sticky {
		max-height: 70px !important;
		height: auto !important;
		width: auto !important;
	}

	.sticky_header.affix .navigation-menu .width-logo img,
	.sticky_header.affix .logo_sticky {
		max-height: 60px !important;
		height: auto !important;
	}
	
	.blog-intro {
		background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
		border-left: 4px solid #007cba !important;
		padding: 40px 30px !important;
		border-radius: 15px !important;
		margin-bottom: 50px !important;
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
	}
	
	.blog-intro h2 {
		color: #2c3e50 !important;
		font-size: 2em !important;
		font-weight: 700 !important;
		margin-bottom: 25px !important;
		letter-spacing: -0.5px !important;
	}
	
	.blog-intro p {
		color: #555 !important;
		font-size: 1.1em !important;
		line-height: 1.7 !important;
		margin-bottom: 20px !important;
	}
	
	.blog-intro a {
		color: #007cba !important;
		text-decoration: none !important;
		font-weight: 600 !important;
		transition: all 0.3s ease !important;
	}
	
	.blog-intro a:hover {
		color: #005a87 !important;
		text-decoration: underline !important;
	}
	
	/* Section Heading */
	.section-heading {
		color: #2c3e50 !important;
		font-size: 2em !important;
		font-weight: 600 !important;
		margin: 50px 0 30px !important;
		padding-bottom: 15px !important;
		border-bottom: 3px solid #007cba !important;
		position: relative !important;
	}
	
	.section-heading::after {
		content: '' !important;
		position: absolute !important;
		bottom: -3px !important;
		left: 0 !important;
		width: 60px !important;
		height: 3px !important;
		background: linear-gradient(135deg, #007cba, #005a87) !important;
	}
	
	/* Blog Cards */
	.tours.products.wrapper-tours-slider {
		display: flex !important;
		flex-wrap: wrap !important;
		margin: 0 -15px !important;
	}
	
	.tours.products.wrapper-tours-slider .item-tour.product {
		margin-bottom: 30px !important;
		border-radius: 15px !important;
		overflow: hidden !important;
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
		background: #fff !important;
		width: calc(50% - 30px) !important;
		margin-left: 15px !important;
		margin-right: 15px !important;
		float: none !important;
		display: block !important;
	}
	
	/* Ensure proper 2-column layout */
	@media (max-width: 768px) {
		.tours.products.wrapper-tours-slider .item-tour.product {
			width: calc(100% - 30px) !important;
		}
	}
	
	.tours.products.wrapper-tours-slider .item-tour.product:hover {
		transform: translateY(-8px) !important;
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
	}
	
	.tours.products.wrapper-tours-slider .item_border {
		border: none !important;
		border-radius: 15px !important;
		background: #fff !important;
	}
	
	.tours.products.wrapper-tours-slider .post_images img {
		transition: all 0.4s ease !important;
		border-radius: 15px 15px 0 0 !important;
	}
	
	.tours.products.wrapper-tours-slider .item-tour.product:hover .post_images img {
		transform: scale(1.05) !important;
	}
	
	.tours.products.wrapper-tours-slider .price {
		background: linear-gradient(135deg, #007cba, #005a87) !important;
		color: white !important;
		padding: 8px 15px !important;
		border-radius: 20px !important;
		font-weight: 600 !important;
		font-size: 0.9em !important;
		position: absolute !important;
		top: 15px !important;
		left: 15px !important;
		z-index: 2 !important;
		box-shadow: 0 4px 15px rgba(0, 124, 186, 0.3) !important;
	}
	
	.tours.products.wrapper-tours-slider .wrapper_content {
		padding: 25px !important;
	}
	
	.tours.products.wrapper-tours-slider .post_title h4 {
		margin: 0 0 15px 0 !important;
		font-size: 1.3em !important;
		font-weight: 700 !important;
		line-height: 1.4 !important;
	}
	
	.tours.products.wrapper-tours-slider .post_title h4 a {
		color: #2c3e50 !important;
		text-decoration: none !important;
		transition: all 0.3s ease !important;
	}
	
	.tours.products.wrapper-tours-slider .post_title h4 a:hover {
		color: #007cba !important;
	}
	
	.tours.products.wrapper-tours-slider .post_date {
		color: #6c757d !important;
		font-size: 0.9em !important;
		font-weight: 500 !important;
		margin-bottom: 15px !important;
		display: block !important;
	}
	
	.tours.products.wrapper-tours-slider .description p {
		color: #555 !important;
		line-height: 1.6 !important;
		margin-bottom: 20px !important;
	}
	
	.tours.products.wrapper-tours-slider .read_more {
		display: flex !important;
		justify-content: space-between !important;
		align-items: center !important;
		margin-top: 20px !important;
	}
	
	.tours.products.wrapper-tours-slider .item_rating {
		color: #f39c12 !important;
	}
	
	.tours.products.wrapper-tours-slider .button {
		background: linear-gradient(135deg, #007cba, #005a87) !important;
		color: white !important;
		border: none !important;
		padding: 12px 25px !important;
		border-radius: 25px !important;
		font-weight: 600 !important;
		text-decoration: none !important;
		transition: all 0.3s ease !important;
		box-shadow: 0 4px 15px rgba(0, 124, 186, 0.3) !important;
	}
	
	.tours.products.wrapper-tours-slider .button:hover {
		background: linear-gradient(135deg, #005a87, #004066) !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 6px 20px rgba(0, 124, 186, 0.4) !important;
		color: white !important;
	}
	
	/* Sidebar Styling */
	.col-sm-4 .item-tour.product {
		margin-bottom: 30px !important;
		border-radius: 15px !important;
		overflow: hidden !important;
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
		transition: all 0.3s ease !important;
		background: #fff !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.col-sm-4 .item-tour.product:hover {
		transform: translateY(-5px) !important;
		box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
	}
	
	.col-sm-4 .item_border {
		border: none !important;
		border-radius: 15px !important;
		background: #fff !important;
	}
	
	.col-sm-4 .wrapper_content {
		padding: 25px !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.col-sm-4 .post_title h3 {
		color: #2c3e50 !important;
		font-size: 1.3em !important;
		font-weight: 700 !important;
		margin-bottom: 20px !important;
		padding-bottom: 10px !important;
		border-bottom: 2px solid #e9ecef !important;
		position: relative !important;
		width: 100% !important;
	}
	
	.col-sm-4 .post_title h3::after {
		content: '' !important;
		position: absolute !important;
		bottom: -2px !important;
		left: 0 !important;
		width: 40px !important;
		height: 2px !important;
		background: linear-gradient(135deg, #007cba, #005a87) !important;
	}
	
	.col-sm-4 .description {
		color: #555 !important;
		line-height: 1.6 !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.col-sm-4 .description p {
		margin-bottom: 15px !important;
		font-size: 1em !important;
		line-height: 1.6 !important;
		width: 100% !important;
	}
	
	.col-sm-4 .description ul {
		list-style: none !important;
		padding: 0 !important;
		margin: 0 !important;
		width: 100% !important;
		max-width: 100% !important;
	}
	
	.col-sm-4 .description ul li {
		margin-bottom: 12px !important;
		padding: 8px 0 !important;
		border-bottom: 1px solid #f8f9fa !important;
		transition: all 0.3s ease !important;
		width: 100% !important;
		display: block !important;
	}
	
	.col-sm-4 .description ul li:last-child {
		border-bottom: none !important;
	}
	
	.col-sm-4 .description ul li:hover {
		padding-left: 10px !important;
		background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
		border-radius: 5px !important;
		margin: 0 -10px !important;
		padding-right: 10px !important;
		width: calc(100% + 20px) !important;
	}
	
	.col-sm-4 .description ul li a {
		color: #2c3e50 !important;
		text-decoration: none !important;
		font-weight: 500 !important;
		transition: all 0.3s ease !important;
		display: block !important;
		width: 100% !important;
	}
	
	.col-sm-4 .description ul li a:hover {
		color: #007cba !important;
	}
	
    /* Ensure sidebar stretches to full width (scope to content only) */
    .content-area .col-sm-4 {
		width: 100% !important;
		max-width: 100% !important;
		padding-left: 0 !important;
		padding-right: 0 !important;
	}
	
    .content-area .col-sm-4 .widget-area {
		width: 100% !important;
		max-width: 100% !important;
	}
	</style>
	
	<!-- JavaScript fix for menu wiggle and submenu flash -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Prevent submenu flash on click
		const menuItems = document.querySelectorAll('.navbar-nav > li');
		menuItems.forEach(function(item) {
			item.addEventListener('click', function(e) {
				// Hide all submenus immediately
				const allSubmenus = document.querySelectorAll('.sub-menu');
				allSubmenus.forEach(function(submenu) {
					submenu.style.display = 'none';
				});
			});
			
			item.addEventListener('mouseenter', function() {
				// Show submenu on hover
				const submenu = item.querySelector('.sub-menu');
				if (submenu) {
					submenu.style.display = 'block';
				}
			});
			
			item.addEventListener('mouseleave', function() {
				// Hide submenu on leave
				const submenu = item.querySelector('.sub-menu');
				if (submenu) {
					submenu.style.display = 'none';
				}
			});
		});
	});
	</script>
</head>
<body class="home page-template-default page page-id-2 woocommerce-js">
	<!-- Preloader -->
	<div id="preloader">
		<div class="preloader">
			<span></span>
			<span></span>
		</div>
	</div>
	
    <!-- Header (matching main site structure) -->
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
                    <a href="../index.php" title="Jahongir Travel" rel="home">
                        <img src="../images/logo_brown.png" alt="Logo" width="474" height="130" class="logo_transparent_static">
                        <img src="../images/logo_sticky.png" alt="Sticky logo" width="474" height="130" class="logo_sticky">
                    </a>
                </div>
                <nav class="width-navigation">
                    <ul class="nav navbar-nav menu-main-menu side-nav" id="mobile-demo">
                        <li >
                            <a href="../aboutus.php">About Us</a>
                        </li>
                        <li class="menu-item-has-children current-menu-parent">
                            <a href="../index.php">Tours</a>
                            <ul class="sub-menu">
                                <li><a href="../uzbekistan-tours/index.php">Uzbekistan Tours</a></li>
                                <li><a href="../tours-from-samarkand/index.php">Samarkand Tours</a></li>
                                <li><a href="../tours-from-bukhara/index.php">Bukhara Tours</a></li>
                                <li><a href="../tours-from-khiva/tour-from-khiva-ancient-fortresses.php">Khiva Tours</a></li>
                                <li><a href="../tajikistan-tours/seven-lakes-tajikistan-tour.php">Tajikistan Tours</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="../uzbekistan-travel-guide.php">Travel Guide</a>
                        </li>
                        <li>
                            <a href="index.php">Travel Blog</a>
                        </li>
                        <li><a href="../contact.php">Contact US</a></li>
                        <li class="menu-item-has-children current-menu-parent">
                            <a href="../index.php">Our Hotels</a>
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