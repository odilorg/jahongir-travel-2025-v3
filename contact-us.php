<?php include 'mailer.php' ?>

    
     <?php include 'Includes/header.php';?>
    
    <!-- SubHeader =============================================== -->
    <section class="parallax-window" id="short" data-parallax="scroll" data-image-src="img/sub_header_short_2.jpg" data-natural-width="1400" data-natural-height="350">
        <div id="subheader">
            <h1>Contact Us</h1>
    </div><!-- End subheader -->
    </section><!-- End section -->
    <!-- End SubHeader ============================================ -->
	
        
    <div class="container margin_60_35">
    <?php echo $alert; ?>
    <h2 class="main_title"><meta itemprop="name" content="Jahongir Hotel"><em></em>Welcome to Jahongir <span>Hotel Samarkand</span></h2>
    
        <div class="row add_top_20">
        
        <div class="col-md-4">
            	<div class="box_style_1">
                <div class="box_contact">
                    <i class="icon_set_1_icon-41"></i>
                    <h4>Address</h4>
                   <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <span itemprop="streetAddress">Chirokchi 4</span>, 
                        <span itemprop="addressLocality">Samarkand</span><br/>
                        <span itemprop="addressCountry">Uzbekistan</span> 
                        <span itemprop="postalCode">140100</span></span><br/><a href="tel://998915550808">
                        <span itemprop="telephone">+998 91 555 08 08</span></a></p>
                        <span itemprop="Map">Click the <a href="img/map-big.jpg" target="_new">map</a> for directions<br><a href="https://www.google.com/maps/place/Jahongir+B%26B/@39.649977,66.9757983,17z/data=!4m12!1m6!3m5!1s0x3f4d18b454a40ec5:0x975a59209af77710!2sJahongir+B%26B!8m2!3d39.649977!4d66.977987!3m4!1s0x3f4d18b454a40ec5:0x975a59209af77710!8m2!3d39.649977!4d66.977987">GPS coordinates 39.650176, 66.978082</a></span></a></p>
                    </div>
                    <div class="box_contact">
            	
			</form>
            </div>
            </div>
            </div>   
            
        	<div class="col-md-7 col-md-offset-1">
            
            <div id="message-contact"></div>
				 <form action="" method="post">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" required class="form-control" id="name_contact" required name="name" placeholder="Enter Name">
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" required class="form-control" id="lastname_contact" required name="last_name" placeholder="Enter Last Name">
							</div>
						</div>
					</div>
					<!-- End row -->
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label>Email</label>
								<input type="email" required id="email_contact" name="email" class="form-control" placeholder="Enter Email" required>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label>Phone</label>
								<input type="text" required id="phone_contact" name="phone" class="form-control" placeholder="Enter Phone number" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Message</label>
								<textarea id="message_contact" required name="message" class="form-control" required placeholder="Write your message" style="height:150px;"></textarea>
							</div>
						</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-md-6">
                        	
                        
							<input type="submit" value="Submit" name="submit" class="btn_1" id="submit-contact">
                           
						</div>
					</div>
				</form>               
            </div><!-- End col-md-8 -->    
            
             
        </div><!-- End row -->
    </div></span><!-- End Container -->
      
        <?php include 'Includes/footer.php';?>

       

<div id="toTop"></div><!-- Back to top button -->
   
<!-- COMMON SCRIPTS -->
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/common_scripts_min.js"></script>
<script src="assets/validate.js"></script>
<script src="js/functions.js"></script>

<!-- Specifi scripts -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/mapmarker.jquery.js"></script>
<script type="text/javascript" src="js/mapmarker_func.jquery.js"></script>
<script type="text/javascript">
	if(window.history.replaceState){
		window.history.replaceState(null, null, window.location.href);
	}
	</script>

</body>
</html>



