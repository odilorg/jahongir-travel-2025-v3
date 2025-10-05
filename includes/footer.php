<div class="wrapper-footer wrapper-footer-newsletter">
		<div class="main-top-footer">
			<div class="container">
				<div class="row">
					<aside class="col-sm-6 widget_text"><h3 class="widget-title">CONTACT</h3>
						<div class="textwidget">
							<div class="footer-info">
								<p>Jahongir Travel is based in Samarkand – Uzbekistan. Opened as hotel/guest house in 2009 Jahongir b&b has become one of the top places to stay in Samarkand. As we gained the valuable experience operating the business in Uzbekistan, throughout these years the idea to serve a larger group of people eventually led to opening the separate department “the Tour Operator of Uzbekistan and the Central Asia”..
								</p>
								<ul class="contact-info">
									<li><i class="fa fa-map-marker fa-fw"></i> 4 Chirokchi str., Samarkand, Uzbekistan</li>
									<li><i class="fa fa-whatsapp fa-fw"></i> +998 91 555 0808</li>
									<li>
										<i class="fa fa-envelope fa-fw"></i><a href="mailto:odilorg@gmail.com">odilorg@gmail.com</a>
									</li>
								</ul>
							</div>
						</div>
					</aside>
					
					<aside class="col-sm-6 widget_text"><h3 class="widget-title">Our Menu</h3>
						<div class="textwidget">
							<ul class="menu list-arrow">
								<li><a href="aboutus.php">About us</a></li>
								<li><a href="index.php">Samarkand Tours</a></li>
								<li><a href="contact.php">Contact</a></li>
								<li><a href="https://jahongir-premium.uz/">Jahongir Premium Hotel</a></li>
								<li><a href="https://jahongirbandb.com/">Jahongir Guest House</a></li>
							</ul>
						</div>
					</aside>
					
				</div>
			</div>
		</div>
		<div class="container wrapper-copyright">
			<div class="row">
				<div class="col-sm-6">
					<div><p>Copyright © <?php echo date('Y'); ?> Jahongir Travel. All Rights Reserved.</p></div>
				</div>
				<div class="col-sm-6">
					<aside id="text-5" class="widget_text">
						<div class="textwidget">
							<ul class="footer_menu">
								
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-instagram"></i></a>
								</li>
							</ul>
						</div>
					</aside>
				</div>
			</div>
		</div>
	</div>
	
</div>

<!-- Optimized JavaScript Loading -->
<script>
// Load critical JavaScript immediately
(function() {
    // Lazy loading implementation
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
})();
</script>

<!-- Load non-critical JavaScript asynchronously -->
<script async src="assets/js/jquery.min.js"></script>
<script async src="assets/js/bootstrap.min.js"></script>
<script defer src="assets/js/vendors.js"></script>
<script defer src="assets/js/owl.carousel.min.js"></script>
<script defer src="assets/js/jquery.mb-comingsoon.min.js"></script>
<script defer src="assets/js/waypoints.min.js"></script>
<script defer src="assets/js/jquery.counterup.min.js"></script>
<script defer src="assets/js/theme.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>

<!-- Google Analytics - moved here for better performance -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-11149707-6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-11149707-6');
</script>

<!-- reCAPTCHA - load only when needed -->
<script>
function loadRecaptcha() {
    if (document.querySelector('.g-recaptcha')) {
        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
}
// Load reCAPTCHA after page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadRecaptcha);
} else {
    loadRecaptcha();
}
</script>

    <!-- Advanced Analytics and Performance Monitoring -->
    <?php
    include 'analytics-config.php';
    echo $analytics->getAllTrackingScripts();
    echo $seoMonitor->getSEOTrackingScript();
    ?>

</div> <!-- Close wrapper-container -->

</body>
</html>