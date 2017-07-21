<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="SemiColonWeb" />

	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

	<!-- styles that come with canvas -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" type="text/css" />

	<?php wp_head(); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->


	<title>Canvas | The Multi-Purpose HTML5 Template</title>

</head>

<body class="stretched">

	<?php get_template_part( 'top-nav-bar' ); ?>

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header
		============================================= -->
		<header id="header" class="full-header">

			<div id="header-wrap">

				<div class="container clearfix">

					<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

					<!-- Logo
					============================================= -->
					<div id="logo">
						<a href="<?php bloginfo('url');?>" class="standard-logo" data-dark-logo="<?php bloginfo('template_directory');?>/logo/kaseya-logo.png"><img src="<?php bloginfo('template_directory');?>/logo/kaseya-logo.png" alt="Kaseya Logo"></a>
						<a href="<?php bloginfo('url');?>" class="retina-logo" data-dark-logo="<?php bloginfo('template_directory');?>/logo/kaseya-logo@2x.png"><img src="<?php bloginfo('template_directory');?>/logo/kaseya-logo@2x.png" alt="Kaseya Logo"></a>
					</div><!-- #logo end -->

						 <?php /*
							wp_nav_menu(array(
								 // Whatever you used to register the nav menu with
								 'theme_location' => 'primary',
								 // Instantiate our class & pass it as an argument
								 'walker' => new Microdot_Walker_Nav_Menu(),
								 'container' => false,
								 'items_wrap' => '<nav id="primary-menu" class="sub-title"><ul>%3$s</ul></nav>'
							));
					 	 	*/ ?>

							<?php get_template_part( 'nav' ); ?>

		</header><!-- #header end -->
