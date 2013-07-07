<?php
/*
Plugin Name: 	Vidtok Video Chat using Tokbox's OpenTok API
Plugin URI: 	http://vidtok.co
Description: 	Vidtok was created to bridge the gap between the technical challenges to that everyday users face when wanting to integrate live video onto their website or blog. Vidtok has created a Wordpress plugin that allows users to add streaming video to their website in a few simple steps. We offer several <a href="http://vidtok.co/pricing" target="_blank">subscription packages</a> based on what your needs. 
Version: 		1.0
Author: 		the Blacc Spot Media team
Author URI: 	http://blaccspot.com
License: 		GPLv3 http://www.gnu.org/licenses/gpl.html
*/



/*  DEFINE CONSTANTS
/*---------------------------*/	
	
	/*VERSION*/
		define("VIDTOK_VERSION", "1.0.1");
	
	/*PLUGIN PATH*/
		/* /vidtok/ */
			define("VIDTOK_PLUGINPATH", "/" . plugin_basename(dirname(__FILE__)) . "/");
	
	/*PLUGIN FULL URL*/
		/* http://vidtok.co/blog/wp-content/plugins/vidtok/ */
			define("VIDTOK_PLUGINFULLURL", trailingslashit(plugins_url(null, __FILE__ )));
	
	/*PLUGIN FULL DIRECTORY*/
		/* /var/www/vhosts/vidtok.co/httpdocs/blog/wp-content/plugins/vidtok/ */
			define("VIDTOK_PLUGINFULLDIR", WP_PLUGIN_DIR . VIDTOK_PLUGINPATH);
			


/* ACTIVATION
/*---------------------------*/

	/*INSTALLATION*/
		register_activation_hook(__FILE__,'vidtok_install');

	/*PLUGIN ACTIVATION IMPLEMENATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/installation/install.php');
	
	/*ACTIVATION NOTICE*/
		add_action('admin_notices', 'vidtok_settings_notice');
		
	/*PLUGIN ACTIVATION IMPLEMENATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/installation/notices.php');



/*  UNINSTALL
/*---------------------------*/
	
	/*PLUGIN REMOVAL*/
		register_deactivation_hook( __FILE__, 'vidtok_uninstall' );
	
	/*PLUGIN REMOVAL IMPLEMENATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/installation/uninstall.php');



/*  ADMIN MENUS
/*---------------------------*/
	
	/*ADD VIDTOK ADMIN MENU*/
		add_action('admin_menu', 'vidtok_admin_menu');  

	/*VIDTOK ADMIN MENU IMPLEMENTAION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/admin/admin-menu.php');
		


/*  CREATE CUSTOM POST TYPES
/*---------------------------*/
			
	/*ADD POST TYPES*/
		add_action('init', 'vidtok_create_post_types');

	/*POST TYPES IMPLEMENTATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/actions/post-types/custom.php');


/*  CREATE CUSTOM TEMPALTE PAGES
/*---------------------------*/
	
	/*ADD TEMPLATE PAGES*/
		add_filter( 'template_include', 'vidtok_custom_pages', 1);

	/*TEMPLATE PAGES IMPLEMENTATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/actions/post-types/templates.php');




/* 	ADMIN SETTINGS
/*---------------------------*/

	/*SAVE SETTINGS*/
		add_action('admin_init', 'vidtok_admin_options');
	
	/*SAVE SETTING IMPLEMENTATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/admin/options.php');



/*  WIDGET
/*---------------------------*/

	/*ADD VIDTOK WIDGET*/
		add_action('widgets_init', 'vidtok_widget');
		 
	/*WIDGET IMPLEMENATION*/
		include_once(VIDTOK_PLUGINFULLDIR.'functions/admin/widgets.php'); 





