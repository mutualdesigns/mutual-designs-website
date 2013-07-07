<?php

	/*FIX WP LOAD ISSUE*/		
		require_once('../../../../../wp-load.php'); 

	/*WORDPRESS DATABAE*/
		global $wpdb;
		
	/*VARIABLES*/	
		$type		= $_GET['type'];
		$options 	= get_option('vidtok_options');
		$vid		= 'vidtok-'.rand(100,999).'-'.rand(1001,9999);
	
	
	/*OPENTOK LIBRARY*/
		require_once VIDTOK_PLUGINFULLDIR.'libs/opentok-sdk/OpenTokSDK.php';
					
	
	/*CREATE SESSION*/
		$apiObj = new OpenTokSDK($options['oapi'], $options['osecert']);
		
		$session = $apiObj->createSession($_SERVER["REMOTE_ADDR"]);
		
		$sessionId = $session->getSessionId();
		
	/*DATABASE VARIABLES*/
		$insert = array();	
		
		$insert['vid'] 					= $vid;
		$insert['opentok_session_id'] 	= $sessionId; 

	/*INSERT INTO DATABASE*/
		$wpdb->insert($wpdb->get_blog_prefix() . 'vidtok_sessions', $insert); 
		
	
	/*REDIRECT*/
		switch($type){
	
			case 'archive' :
				
				wp_redirect(site_url()."/vidtok/archive?vid=$vid");
	
				break;	
	
			case 'broadcast' :
				
				wp_redirect(site_url()."/vidtok/broadcaster?vid=$vid");
	
				break;						
	
			case 'group' :
				
				wp_redirect(site_url()."/vidtok/group?vid=$vid");
	
				break;	
				
			case 'individual' :
				
				wp_redirect(site_url()."/vidtok/individual?vid=$vid");
	
				break;		
										
	
									
		}

