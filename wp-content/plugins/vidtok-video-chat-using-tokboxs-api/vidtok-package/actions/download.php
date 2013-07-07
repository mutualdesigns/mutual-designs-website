<?php

	/*FIX WP LOAD ISSUE*/		
		require_once('../../../../../wp-load.php'); 


	/*WORDPRESS DATABAE*/
		global $wpdb;

	/*VARIABLES*/		
		$options 			= get_option('vidtok_options');
		$archive_id 		= $_POST['archive_id'];
		
		if($_POST['archive_title']){
			
			$archive_title	= $_POST['archive_title'];
			
		}else{
			
			$archive_title	= 'Vidtok Archive: ' . date('Y-m-d H:i:s');	
			
		}
		
		$update 			= array();	
		$update['deleted'] 	= 'yes';
				
	
	/*OPENTOK CONSTANTS*/	
		$oApi	 		= $options['oapi'];
		$oApiSerect 	= $options['osecert'];
		
		
	/*BASE URL*/			
		$wpdir 			= wp_upload_dir();		
		$uploads_dir 	= $wpdir['basedir'];
		$uploads_url	= $wpdir['baseurl'];
		
		$base_dir 		= $uploads_dir . '/vidtok/';
		$base_url		= $uploads_url . '/vidtok/';
	
		
	/*OPENTOK SDK*/	
		require_once VIDTOK_PLUGINFULLDIR.'libs/opentok-sdk/OpenTokSDK.php';
	
	
	/*OPENTOK SESSION*/	
		$apiObj = new OpenTokSDK($oApi, $oApiSerect);
		
		$session = $apiObj->createSession( $_SERVER["REMOTE_ADDR"] );
		
		$sessionId = $session->getSessionId();
	
	
	/*OPENTOK TOKEN*/	
		$token = $apiObj->generateToken($sessionId, RoleConstants::MODERATOR, time() + (30*24*60*60));
	
	
	/*OPENTOK MANIFEST*/		
		$archive = $apiObj->getArchiveManifest($archive_id, $token);
		
		$resources = $archive->getResources();
		
		$vid = $resources[0]->getId();
		
		$url = $archive->downloadArchiveURL($vid, $token);
	
	
	/*CREATE FOLDER*/	
		if(!is_dir($base_dir)){
			
			mkdir($base_dir, 0777);	 
			
		}
	
	
	
	/*OPENTOK DOWNLOAD VIDEO WORDPRESS HTTP_API*/		
		$path 	= $base_dir.$archive_id.'.flv';
	
		$ch = wp_remote_get( 
			$url, 
			array( 
				'stream'   => true, 
				'filename' => $path  
			) 
		);

	
	/*INSERT INTO DATABASE*/		
		$wp_filetype = wp_check_filetype(basename($archive_id.'.flv'), null);
		
		$wp_upload_dir = $base_url.'/vidtok/'.$archive_id.'.flv'; 

		$attachment = array(
						   'guid' 			=> $wp_upload_dir, 
						   'post_mime_type' => $wp_filetype['type'],
						   'post_title' 	=> preg_replace('/\.[^.]+$/', '', basename($archive_title)),
						   'post_content' 	=> '',
						   'post_status' 	=> 'inherit'
					  );
		
		wp_insert_attachment($attachment, 'vidtok/'.$archive_id.'.flv');
		
			
	/*RESPONSE*/			
		if($ch){
			
			echo json_encode(array('status' => 'successful'));
			
		}else{
			
			echo json_encode(array('status' => 'failed'));
			
		}
	



