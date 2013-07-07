<?php

	
/*  VIDTOK INSTALLATION
/*---------------------------*/

	function vidtok_install()
		{
			
			/*WORDPRESS DATABASE*/
				global $wpdb;
			
			/*CRATE DATABASE TABLES*/
				vidtok_create_tables($wpdb->get_blog_prefix());


		}




/*  VIDTOK CREATE DATABASE TABLES
/*---------------------------*/

	function vidtok_create_tables($prefix)
		{
			
			/*WORDPRESS DATABASE*/
				global $wpdb;
			
			/*VIDTOK SESSIONS*/
				$sessions	= "CREATE TABLE IF NOT EXISTS `" . $prefix . "vidtok_sessions` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `vid` varchar(18) NOT NULL,
								  `opentok_session_id` varchar(255) NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			
			
			/*VIDTOK SESSIONS*/
				$wpdb->query($sessions);
			
			
			/*ARCHIVE SQL*/	
				$archive	= "CREATE TABLE IF NOT EXISTS `" . $prefix . "vidtok_archive` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `archive_id` varchar(128) NOT NULL,
								  `archive_title` varchar(128) NULL,
								  `oSession` varchar(255) NOT NULL,
								  `date` datetime NOT NULL,
								  `deleted` enum('yes','no') NOT NULL DEFAULT 'no',
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			
			
			/*VIDTOK ARCHIVES*/
				$wpdb->query($archive);
				

			/*OPTIONS*/	
				$new_options['version']			= VIDTOK_VERSION;
				$new_options['vapi'] 			= '';
				$new_options['oapi'] 			= '';
				$new_options['osecert'] 		= '';	
				$new_options['vsubscription']	= 'free';		
			
			
			/*SET DEFAULT OPTIONS*/
				if(get_option('vidtok_options') === false){ 

					/*ADD OPTIONS*/
						add_option('vidtok_options', $new_options);
						
				}else{
					
					/*DELETE OPTIONS*/
						$existing_options = get_option('vidtok_options');
						
						delete_option('vidtok_options');
						
					/*ADD OPTIONS*/
						add_option('vidtok_options', $new_options);
					
					
				}
			
			
	
		}




















