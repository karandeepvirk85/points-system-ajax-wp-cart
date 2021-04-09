<!doctype html>
<html <?php language_attributes(); ?>>
<!--HEAD-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>	
</head>

<!--Site Header-->
<header>
	<!--Top Notification Bar-->	
	<div class="top-notification-bar">
		<p>Get Free Giftcard For online Shoping</p>
	</div>

	<!--Site Desktop Top Menu-->
	<div class="header-container-desktop">
		<div class="left-container">
			<div class="logo-container">
				<div class="site-logo">
					<a href="<?php echo home_url();?>">
						<img  alt="Site logo" src="<?php echo get_template_directory_uri()?>/images/site-logo.png">
					</a>
				</div>
			</div>
		</div>

		<div class="right-container">
			<div class="links-container">
				<?php 
				if(class_exists('Theme_Controller')){
					$arrMenu = Theme_Controller::getMainByMenu('main-menu-desktop');
					if(count($arrMenu)>0){
						foreach ($arrMenu as $arrMenuItems){	
							?>
							<a class="<?php  echo $arrMenuItems['page_id'] === $post->ID ? "active-menu" : "";?>" id="<?php echo $arrMenuItems['ID']?>" href="<?php echo $arrMenuItems['url']?>">
								<?php echo $arrMenuItems['title']?>
							</a>
						<?php }
					}
				}
				?>
			</div>
			<?php get_template_part( 'nextpage-templates/searchform' ); ?>
			<div class="user-meta-information">
				<div class="user-meta-spinner">
					<i class="fa-spin fa fa-cog" aria-hidden="true"></i></div>
				<div class="user-meta-name">
					
				</div>
				<div class="user-meta-points animate__animated animate__fadeInUp">
					<strong>Points:</strong> <span id="user-total-points"><span></strong> 
				</div>
			</div>
		</div>
	</div>

	<!--Site Mobile Top Menu-->
	<div class="header-container-mobile">
		<div class="top-mobile-container">
			<div class="left-logo-container">
				<a href="<?php echo home_url();?>">
					<img  alt="Site logo" src="<?php echo get_template_directory_uri()?>/images/site-logo.png">
				</a>
			</div>
			<div class="right-button-container" onclick="objTheme.MobileMenuOpenClose()">
				<i class="fa fa-bars" id="menu-fa-icon" aria-hidden="true"></i>
			</div>
		</div>

		<div class="animate__animated animate__fadeInLeft bottom-links-container" id="bottom-links">
			<?php 
				if(class_exists('Theme_Controller')){
					$arrMenu = Theme_Controller::getMainByMenu('main-menu-desktop');
					if(count($arrMenu)>0){
						foreach ($arrMenu as $arrMenuItems){	
							?>
							<a class="<?php  echo $arrMenuItems['page_id'] === $post->ID ? "active-menu" : "";?>" id="<?php echo $arrMenuItems['ID']?>" href="<?php echo $arrMenuItems['url']?>">
								<?php echo $arrMenuItems['title']?>
							</a>
						<?php }
					}
				}
			?>
		</div>
	</div>
</header>
<!--Body Start-->
<body>