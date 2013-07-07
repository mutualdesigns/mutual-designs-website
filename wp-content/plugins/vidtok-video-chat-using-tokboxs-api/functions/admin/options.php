<?php


/*  ADMIN INIT 
/*---------------------------*/

	function vidtok_admin_options(){
		
		/*ADD ACTION*/	
			add_action('admin_post_save_vidtok_options', 'procss_vidtok_options');
		
	}



/*  VIDTOK SETTINGS
/*---------------------------*/
	
	function procss_vidtok_options()
		{
			
			/*GET OPTIONS*/
				$options = get_option('vidtok_options');
			
			/*GET SUBSCRIPTION TYPE*/           	
				$url 		= 'http://vidtok.co/api/activation/subscription/id/'.$_POST['vapi'];
				$content 	= file_get_contents($url);
				$json		= json_decode($content, true);

			/*OPTIONS*/	
				$version		= VIDTOK_VERSION;
				
				
				if(isset($json['api_key'])){
				
					$vsubscription 	= $json['subscription_type'];
					
				}else{
				
					$vsubscription 	= '';
					
				}
				
				$vapi 				= $_POST['vapi'];
				$oapi 				= $_POST['oapi'];
				$osecert 			= $_POST['osecert'];
				
				
			
			/*STORE VALUES IN OPTION ARRAY*/		
				$update = array('version' => $version, 'vapi' => $vapi, 'vsubscription' => $vsubscription, 'oapi' => $oapi, 'osecert' => $osecert);  
									
			/*UPDATE OPTIONS*/				
				update_option('vidtok_options', $update);  
			
			/*REDIRECT*/	
				wp_redirect( add_query_arg(array('page' => 'vidtok_plugin_settings', 'message' => '1'), admin_url('options-general.php')));  
				
			/*EXIT FUNCTION*/	
				exit;					
					
					
		}

