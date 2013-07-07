<?php

	/*FIX WP LOAD ISSUE*/		
		require_once('../../../../../wp-load.php'); 

	/*WORDPRESS DATABAE*/
		global $wpdb;

	/*VARIABLES*/		
		$options 			= get_option('vidtok_options');
		$archive_id 		= $_POST['archive_id'];
		
		$update 			= array();	
		$update['deleted'] 	= 'yes';	
			
	
	/*OPENTOK CONSTANTS*/			
		$oApi	 		= $options['oapi'];
		$oApiSerect 	= $options['osecert'];
	
	
		
	/*OPENTOK SDK*/			
		require_once VIDTOK_PLUGINFULLDIR.'libs/opentok-sdk/OpenTokSDK.php';
	
	
	/*OPENTOK SESSION*/	
		$apiObj = new OpenTokSDK($oApi, $oApiSerect);
		
		$session = $apiObj->createSession( $_SERVER["REMOTE_ADDR"] );
		
		$sessionId = $session->getSessionId(); 
	
	
	/*OPENTOK TOKEN*/		
		$token = $apiObj->generateToken($sessionId, RoleConstants::MODERATOR, time() + (30*24*60*60));
	
	
	/*POST HEADER*/			
		$header			= "x-tb-token-auth:$token";
		
		
	/*DELETE ARCHIVE*/										
		$delete_url		= "https://api.opentok.com/hl/archive/delete/$archive_id";
		$delete 		= do_post_request($delete_url, "", $header);
	

	/*UPDATE DATABASE DATABASE*/			
		$wpdb->update($wpdb->get_blog_prefix() . 'vidtok_archive', $update, array('archive_id' => $archive_id), array('%s'));   	


	/*RESPONSE*/			
		if($delete){
			
			echo json_encode(array('status' => 'failed'));
			
		}else{
			
			echo json_encode(array('status' => 'successful')); 
			
		}


























/*  FUNCTIONS
/*---------------------------*/

	/*Convert Object String to Array*/
	
		function object2array($object) 
			{ 
				return @json_decode(@json_encode($object),1); 
			} 

	/*Post to Opentok Servers*/
	
		  function do_post_request($url, $data, $optional_headers = null)
			  {
				$params = array('http' => array(
													'method' 	=> 'POST',
													'content' 	=> $data
						));
						
				if ($optional_headers !== null) {
					$params['http']['header'] = $optional_headers;
				}
				
				$ctx = stream_context_create($params);
				
				$fp = @fopen($url, 'rb', false, $ctx);
				
				if (!$fp) {
				  throw new Exception("Problem with $url, $php_errormsg");
				}
				
				$response = @stream_get_contents($fp);
				
				if ($response === false) {
				  throw new Exception("Problem reading data from $url, $php_errormsg");
				}
				
				return $response;
				
			  }