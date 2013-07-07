<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php 	/* 	 * Print the <title> tag based on what is being viewed. 	 * We filter the output of wp_title() a bit -- see 	 * twentyten_filter_wp_title() in functions.php. 	 */ 	wp_title( '|', true, 'right' );  	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>
<body>

		<div id="blackbar">
        	<div id="main-nav" role="navigation">
            	<?php /*?>
				<span class="login borderless">
					Client <?php wp_loginout(); ?>
                </span>
				<?php */?>
                
                
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'depth' => '1' ) ); ?>
                
            </div><!-- #main-nav -->
        </div><!--blackbar-->
            
<div id="wrapper">
	<div id="header">
     
     		<a href="<?php bloginfo( 'url' ); ?>" class="logo"><h1>Mutual Designs</h1><img src="<?php bloginfo('template_url'); ?>/images/homepage/mutual-designs.gif" style="vertical-align:middle;"/></a><h4>Work With Us to Create a Simple Web Solution</h4>
            
	</div><!-- #header -->

	<div id="main">
