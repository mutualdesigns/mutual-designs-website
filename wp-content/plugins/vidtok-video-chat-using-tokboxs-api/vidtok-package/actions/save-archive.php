<?php
	
	/*FIX WPDB LOAD ISSUE*/
		require_once('../../../../../wp-load.php'); 
	
	/*WORDPRESS DATABAE*/
		global $wpdb;

	/*VARIABLES*/
		$archive_id 	= $_POST['archive_id'];
		$oSession 		= $_POST['oSession'];
		$archive_title	= $_POST['archive_title'];
	
	/*DATABASE VARIABLES*/
		$insert = array();	
		
		$insert['archive_id'] 		= $archive_id;
		
		if($archive_title != ''){
			$insert['archive_title'] 	= ucwords($archive_title);  
		}else{
			$insert['archive_title'] 	= 'Vidtok Archive: ' . date('Y-m-d H:i:s'); 
		}
		
		$insert['oSession'] 		= $oSession;
		$insert['date'] 			= date('Y-m-d H:i:s');
		
		
	/*INSERT INTO DATABASE*/			
		$wpdb->insert($wpdb->get_blog_prefix() . 'vidtok_archive', $insert); 	
	


		