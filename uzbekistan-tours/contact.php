<?php $title = "Contact Us - Uzbekistan Tours | Jahongir Travel"; ?>
<?php include 'includes/header.php';?>

<div class="wrapper-container">
<div class="site wrapper-content">
	<div class="top_site_main" style="background-image:url(../images/banner/top-heading.jpg);">
		<div class="banner-wrapper container article_heading">
			<div class="breadcrumbs-wrapper">
				<ul class="phys-breadcrumb">
					<li><a href="../index.php" class="home">Home</a></li>
					<li><a href="index.php">Uzbekistan Tours</a></li>
					<li>Contact Us</li>
				</ul>
			</div>
			<h1 class="heading_primary">Contact Us for Uzbekistan Tours</h1>
		</div>
	</div>
	
	<section class="content-area">
		<div class="container">
			<div class="row">
				<div class="site-main col-sm-8">
					<div class="main-content">
						<h2>Get in Touch for Your Uzbekistan Adventure</h2>
						<p>Ready to explore the ancient Silk Road cities of Uzbekistan? Contact Jahongir Travel for personalized tour planning and expert guidance.</p>
						
						<div class="contact-info-section" style="margin: 30px 0;">
							<h3>Contact Information</h3>
							<div class="contact-details" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
								<p><strong>Address:</strong> 4 Chirokchi str., Samarkand, Uzbekistan</p>
								<p><strong>Phone:</strong> +998 91 555 0808</p>
								<p><strong>Email:</strong> <a href="mailto:odilorg@gmail.com">odilorg@gmail.com</a></p>
								<p><strong>Working Hours:</strong> Mon – Fri 9:00 am – 5:30 pm, Sat 9:00 am – 1:00 pm</p>
							</div>
						</div>
						
						<div class="tour-inquiry-section" style="margin: 30px 0;">
							<h3>Tour Inquiry Form</h3>
							<form action="../mailer-tours.php" method="POST" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="name">Full Name *</label>
									<input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="email">Email Address *</label>
									<input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="phone">Phone Number</label>
									<input type="tel" id="phone" name="phone" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="tour">Tour of Interest</label>
									<select id="tour" name="tour" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
										<option value="">Select a tour</option>
										<option value="best-of-uzbekistan">Best of Uzbekistan in 10 Days</option>
										<option value="bike-tour">Uzbekistan Adventure Bike Tour</option>
										<option value="seven-mysterious-nights">7 Mysterious Nights in Uzbekistan</option>
										<option value="golden-journey">Golden Road to Samarkand</option>
										<option value="custom">Custom Tour</option>
									</select>
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="travel-date">Preferred Travel Date</label>
									<input type="date" id="travel-date" name="travel_date" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="group-size">Group Size</label>
									<select id="group-size" name="group_size" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
										<option value="">Select group size</option>
										<option value="1">1 person</option>
										<option value="2">2 people</option>
										<option value="3-5">3-5 people</option>
										<option value="6-10">6-10 people</option>
										<option value="10+">10+ people</option>
									</select>
								</div>
								
								<div class="form-group" style="margin-bottom: 15px;">
									<label for="message">Message *</label>
									<textarea id="message" name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Tell us about your travel preferences, special requirements, or any questions you have..."></textarea>
								</div>
								
								<button type="submit" style="background: #007cba; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Send Inquiry</button>
							</form>
						</div>
						
						<div class="why-choose-us" style="margin: 30px 0;">
							<h3>Why Choose Jahongir Travel?</h3>
							<ul style="list-style: none; padding: 0;">
								<li style="margin: 10px 0; padding-left: 20px; position: relative;">
									<span style="position: absolute; left: 0; color: #007cba;">✓</span>
									<strong>Local Expertise:</strong> Born and raised in Samarkand with deep knowledge of Uzbekistan's history and culture
								</li>
								<li style="margin: 10px 0; padding-left: 20px; position: relative;">
									<span style="position: absolute; left: 0; color: #007cba;">✓</span>
									<strong>Personalized Service:</strong> Customized tours tailored to your interests and preferences
								</li>
								<li style="margin: 10px 0; padding-left: 20px; position: relative;">
									<span style="position: absolute; left: 0; color: #007cba;">✓</span>
									<strong>Authentic Experiences:</strong> Connect with local families and experience true Uzbek hospitality
								</li>
								<li style="margin: 10px 0; padding-left: 20px; position: relative;">
									<span style="position: absolute; left: 0; color: #007cba;">✓</span>
									<strong>Competitive Prices:</strong> Best value for money with transparent pricing
								</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="sidebar col-sm-4">
					<aside class="widget-area">
						<div class="contact-widget">
							<h3>Quick Contact</h3>
							<p><strong>Jahongir Travel</strong><br>
							4 Chirokchi str., Samarkand<br>
							Uzbekistan</p>
							<p>Phone: +998 91 555 0808<br>
							Email: odilorg@gmail.com</p>
							<a href="mailto:odilorg@gmail.com" class="btn btn-primary">Send Email</a>
						</div>
						
						<div class="popular-tours-widget" style="margin-top: 30px;">
							<h3>Popular Uzbekistan Tours</h3>
							<ul style="list-style: none; padding: 0;">
								<li style="margin: 10px 0;"><a href="best-of-uzbekistan-in-10-days.php">Best of Uzbekistan in 10 Days</a></li>
								<li style="margin: 10px 0;"><a href="bike-tour-in-uzbekistan.php">Uzbekistan Adventure Bike Tour</a></li>
								<li style="margin: 10px 0;"><a href="seven-mysterious-nights-uzbekistan.php">7 Mysterious Nights in Uzbekistan</a></li>
								<li style="margin: 10px 0;"><a href="golden-journey-to-samarkand.php">Golden Road to Samarkand</a></li>
							</ul>
						</div>
					</aside>
				</div>
			</div>
		</div>
	</section>
</div>
</div>

<?php include '../includes/footer.php';?>
