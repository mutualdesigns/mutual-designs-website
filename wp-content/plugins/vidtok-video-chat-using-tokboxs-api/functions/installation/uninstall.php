<?php



/*  VIDTOK UNINSTALL
/*---------------------------*/
	
	function vidtok_uninstall()
		{
			
			/*WORDPRSS DATABASE*/	
				global $wpdb;			
			
			/*VIDTOK DROP TABLE*/
				vidtok_drop_tables($wpdb->prefix); 
				
			/*DELETE OPTIONS*/
				$existing_options = get_option('vidtok_options');
				
				delete_option('vidtok_options');

			
		}
	
		
/*  VIDTOK DROP TABLES
/*---------------------------*/

	function vidtok_drop_tables($prefix)
		{
			
			/*WORDPRSS DATABASE*/	
				global $wpdb;					
			
			/*DROP VIDTOK ACCOUNT*/
				$wpdb->query( $wpdb->prepare( 'DROP TABLE ' . $prefix . 'vidtok_sessions' ));
			
			/*DROP VIDTOK ARCHIVE*/
				$wpdb->query( $wpdb->prepare( 'DROP TABLE ' . $prefix . 'vidtok_archive' )); 
				
			
		}




		