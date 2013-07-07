<?php


/*  CUSTOM TEMPALTES
/*---------------------------*/
	
	function vidtok_custom_pages($template_path)
		{
			
			/*VARIABLES*/
				$current_url 			= $_SERVER['REQUEST_URI'];
				$segments				= explode('/', $current_url);
				$vidtok					= array_search('vidtok', $segments); 						
				$id						= (int)$vidtok + 1;	
				if(!substr($segments[$id], 0, strrpos($segments[$id], '?'))){
					
					$slug				= $segments[$id];
					
				}else{
							
					$slug				= substr($segments[$id], 0, strrpos($segments[$id], '?'));
				
				}
				
			/*TEMPLATE LOCATION*/
				$archive_template		= 'vidtok-package/apps/archive/archive.php';
				$broadcasting_template	= 'vidtok-package/apps/broadcasting/broadcaster.php';
				$group_template			= 'vidtok-package/apps/group/group.php';
				$individual_template 	= 'vidtok-package/apps/individual/individual.php';
				$management_template	= 'vidtok-package/apps/archive-management/management.php';
				$share_template			= 'vidtok-package/apps/archive/share.php';
				$viewer_template		= 'vidtok-package/apps/broadcasting/viewer.php'; 

			
			/*CREATE TEMPLATES*/
				/*ARCHVIE*/	
					if ($slug == 'archive') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $archive_template;
	
						
					}
					
				/*BROADCASTING*/
					if ($slug == 'broadcaster') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $broadcasting_template;
	
						
					}				
				
				/*GROUP*/
					if ($slug == 'group') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $group_template;
	
						
					}				
				
				/*INDIVIDUAL*/
					if ($slug == 'individual') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $individual_template;
	
						
					}				
				
				/*MANAGEMENT*/
					if ($slug == 'management') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $management_template;
	
						
					}				
				
				/*SHARE*/
					if ($slug == 'share') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $share_template;
	
						
					}				
				
				/*VIEWER*/		
					if ($slug == 'viewer') {
						
						/*TEMPLATE*/
							$template_path = VIDTOK_PLUGINFULLDIR . $viewer_template;
	
						
					}							
			
			/*IMPORTANT - INTERNET EXPLORER FIX*/			
				header('HTTP/1.1 200 OK'); 
											
			/*TEMPLATE PATH*/
				return $template_path;	
				
				
		}













