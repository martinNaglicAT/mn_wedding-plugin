<?php 

if (!defined('ABSPATH')) {
    exit;
}

if(!isset($lang)){
	$lang = "eng";
}

$cache_ver = get_cache_version();
//$site = get_custom_site_url();

$site = get_site_url();

$user_id = get_current_user_id();

?>



<!doctype html>
<html lang="<?php echo $lang; ?>">

	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Our wedding page">
		<meta name="keywords" content="template">


		<!--  FAVICON -->
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/icons/rings-gold.ico" />
		<!--<link rel="shortcut icon" type="image/x-icon" href="assets/icons/rings-green.ico" />-->

		<!--  FONTS  -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,400;1,500;1,700;1,900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

		<!-- WP head start -->

		<?php wp_head(); ?>

		<!-- WP head end -->
		
	</head>

	<body>
		<header>
			<div class="header-inner">
				<div class="logo">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/vectors/logo/logo-full-blk.svg" class="header-logo">
				</div>
				<div class="nav" id="nav">
					<ul>
						<li><a href="<?php echo home_url(); ?><?php echo pll__('/admin/'); ?>">Overview</a></li>
						<li class="vertical-divider"></li>
						<li><a href="<?php echo home_url(); ?><?php echo pll__('/users/'); ?>">Users</a></li>
						<li class="vertical-divider"></li>
						<li><a href="<?php echo home_url(); ?><?php echo pll__('/home/'); ?>"><?php echo pll_e('Home'); ?></a></li>
						
					</ul>
				</div>
				<div class="h-right-side">
					<div class="dark-mode">
					</div>
					<div class="lang">

						<!--placeholder-->
						EN
						
					</div>
					<div class="search">
						
					</div>
					<div class="menu">
						<a href="#" id="menu-inner" class="open-menu">
								<div class="menu-inner1">
								</div>
								<div class="menu-inner2">
								</div>
								<div class="menu-inner3">
								</div>
						</a>						
					</div>
				</div>

			</div>
			
		</header>

		<div class="page_wrap">

			<div class="flowers flowers-top">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/vectors/other/Flowers-1.svg">
			</div>

			<div class="page_content">


