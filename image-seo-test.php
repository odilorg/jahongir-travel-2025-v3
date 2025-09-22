<?php
/**
 * Image SEO Test Page - Jahongir Travel
 * Demonstrates optimized image SEO with alt text, titles, and captions
 */

$title = "Image SEO Test - Jahongir Travel";
$meta_description = "Test page demonstrating optimized image SEO with alt text, titles, captions, and WebP support for better search engine visibility.";
$meta_keywords = "Image SEO, alt text, WebP images, Uzbekistan travel photos, Samarkand images, Bukhara photos";
$canonical_url = "https://jahongir-travel.uz/image-seo-test";
$og_type = "website";
$og_image = "https://jahongir-travel.uz/images/logo_brown.png";
?>
<?php include 'includes/header.php';?>

<div class="site wrapper-content">
    <div class="container" style="padding: 40px 0;">
        <h1 style="text-align: center; color: #007cba; margin-bottom: 30px;">🖼️ Image SEO Test - Optimized Images</h1>
        
        <div style="background: #e8f4fd; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h2 style="color: #007cba;">✅ Image SEO Features Implemented:</h2>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>✅ <strong>Alt Text</strong> - Descriptive alternative text for all images</li>
                <li>✅ <strong>Image Titles</strong> - SEO-optimized title attributes</li>
                <li>✅ <strong>Captions</strong> - Contextual captions for better understanding</li>
                <li>✅ <strong>WebP Support</strong> - Modern image format with fallback</li>
                <li>✅ <strong>Lazy Loading</strong> - Improved page speed</li>
                <li>✅ <strong>Semantic HTML</strong> - Figure and figcaption elements</li>
                <li>✅ <strong>Schema Markup</strong> - Rich snippets for images</li>
                <li>✅ <strong>Context-Aware</strong> - Different SEO for different page contexts</li>
            </ul>
        </div>
        
        <!-- Samarkand Images -->
        <section style="margin: 40px 0;">
            <h2 style="color: #333; text-align: center;">🏛️ Samarkand UNESCO Heritage Images</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('tours-from-samarkand/images/samarkand-city-tour/registan-ensemble-samarkand.jpg', 'samarkand', 400, 300, 'samarkand-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('tours-from-samarkand/images/samarkand-city-tour/gur-emir-mausoleum.jpg', 'samarkand', 400, 300, 'samarkand-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('tours-from-samarkand/images/samarkand-city-tour/shahi-zinda-entrance-portal.jpg', 'samarkand', 400, 300, 'samarkand-image'); ?>
                </div>
                
            </div>
        </section>
        
        <!-- Bukhara Images -->
        <section style="margin: 40px 0;">
            <h2 style="color: #333; text-align: center;">🏰 Bukhara Ancient City Images</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('uzbekistan-tours/images/ark-bukhara.jpg', 'bukhara', 400, 300, 'bukhara-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('uzbekistan-tours/images/poi-kalon-bukhara.jpg', 'bukhara', 400, 300, 'bukhara-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('uzbekistan-tours/images/mausoleum-samanids-bukhara.jpg', 'bukhara', 400, 300, 'bukhara-image'); ?>
                </div>
                
            </div>
        </section>
        
        <!-- Uzbekistan General Images -->
        <section style="margin: 40px 0;">
            <h2 style="color: #333; text-align: center;">🇺🇿 Uzbekistan Travel Images</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('images/gur-emir.jpg', 'uzbekistan', 400, 300, 'uzbekistan-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('uzbekistan-tours/images/best-of-uzbekistan-in-10-days.jpg', 'uzbekistan', 400, 300, 'uzbekistan-image'); ?>
                </div>
                
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php echo get_figure_image('uzbekistan-tours/images/golden-journey-to-samarkand.jpg', 'uzbekistan', 400, 300, 'uzbekistan-image'); ?>
                </div>
                
            </div>
        </section>
        
        <!-- Image SEO Benefits -->
        <div style="background: #d4edda; padding: 20px; border-radius: 8px; margin: 40px 0;">
            <h3 style="color: #155724; margin: 0 0 15px 0;">🎯 Image SEO Benefits:</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <h4 style="color: #155724; margin: 0 0 10px 0;">🔍 Search Engine Visibility</h4>
                    <p style="margin: 0; color: #155724;">Images can now rank in Google Images search results</p>
                </div>
                <div>
                    <h4 style="color: #155724; margin: 0 0 10px 0;">♿ Accessibility</h4>
                    <p style="margin: 0; color: #155724;">Screen readers can describe images to visually impaired users</p>
                </div>
                <div>
                    <h4 style="color: #155724; margin: 0 0 10px 0;">⚡ Performance</h4>
                    <p style="margin: 0; color: #155724;">WebP format reduces file sizes by 25-50%</p>
                </div>
                <div>
                    <h4 style="color: #155724; margin: 0 0 10px 0;">📱 User Experience</h4>
                    <p style="margin: 0; color: #155724;">Lazy loading improves page speed and user experience</p>
                </div>
            </div>
        </div>
        
        <!-- Technical Details -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 40px 0;">
            <h3 style="color: #333; margin: 0 0 15px 0;">🔧 Technical Implementation:</h3>
            <ul style="margin: 0; padding-left: 20px; color: #555;">
                <li><strong>Alt Text:</strong> Automatically generated from filename with context-aware keywords</li>
                <li><strong>Title Attribute:</strong> SEO-optimized titles for better search visibility</li>
                <li><strong>Captions:</strong> Descriptive captions explaining the image content</li>
                <li><strong>WebP Support:</strong> Modern format with JPEG/PNG fallback for compatibility</li>
                <li><strong>Lazy Loading:</strong> Images load only when needed for better performance</li>
                <li><strong>Schema Markup:</strong> Rich snippets for better search engine understanding</li>
                <li><strong>Semantic HTML:</strong> Figure and figcaption elements for proper structure</li>
            </ul>
        </div>
        
        <div style="background: #d1ecf1; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="color: #0c5460; margin: 0;">🎉 Image SEO Successfully Implemented!</h3>
            <p style="margin: 10px 0 0 0; color: #0c5460;">All images now have optimized SEO attributes for better search engine visibility!</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php';?>

