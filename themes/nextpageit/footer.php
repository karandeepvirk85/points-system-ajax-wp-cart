<?php 
/**
 * Footer
 */
wp_footer(); ?>
<Footer>
	<div class="footer-main-container">
		<div class="footer-container">
			<div class="footer-logo-container">	
				<img alt="Site logo" src="<?php echo get_template_directory_uri()?>/images/site-logo.png">
			</div>

			<div class="footer-text-container">
				<p>
					Lorem ipsum dolor sit amet, consectetur elit, sed do eiusmod tempor ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra lacus vel facilisis.
				</p>
			</div>
		</div>

		<div class="footer-navbar">
			<div class="footer-links">
				<?php 
				if(class_exists('Theme_Controller')){
					$arrMenu = Theme_Controller::getMainByMenu('footer-menu');
					if(count($arrMenu)>0){
						foreach ($arrMenu as $arrMenuItems){
							?>
							<a id="<?php echo $arrMenuItems['page_id']?>" href="<?php echo $arrMenuItems['url']?>">
								<?php echo $arrMenuItems['title']?>
							</a>
						<?php }
					}
				}	
				?>
			</div>

			<div class="footer-social-links">
				<a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
				<a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
				<a href=""><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<div class="footer-bottom-bar">
		<div class="footer-left-container">
			<p>2021 All Rights Reserved by Paid Survey App</p>
		</div>
		<div class="footer-right-container">
			<p>Privacy Policy </p>
			<span> | </span> 
			<p>Terms & Conditions</p>
		</div>
	</div>
</Footer>
</body>
</html>
