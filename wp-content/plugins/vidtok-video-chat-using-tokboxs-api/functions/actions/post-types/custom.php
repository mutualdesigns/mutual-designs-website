<?php


/*  CREATE CUSTOM POST TYPES
/*---------------------------*/

	function vidtok_create_post_types()
		{
			
					
			/*PAGE SLUGS*/
				$archive		= 'vidtok-archive';
				$broadcasting	= 'vidtok-broadcasting';
				$group			= 'vidtok-group';
				$individual 	= 'vidtok-individual';
				$management		= 'vidtok-archive-managment';
				$share			= 'vidtok-share';
				$viewer			= 'vidtok-viewer';
				
				$slugs			= array($archive, $broadcasting, $group, $individual, $management, $share, $viewer);
			
			/*CREATE MAIN POST TYPE*/
					register_post_type('vidtok',
						array(
							'labels' => array(
								'name' 					=> 'Vidtok',
								'singular_name' 		=> 'Vidtok',
								'rewrite' 				=> array('slug' => 'vidtok'),
								'add_new' 				=> '',
								'add_new_item' 			=> '',
								'edit' 					=> '',
								'edit_item' 			=> '',
								'new_item' 				=> '',
								'view' 					=> '',
								'view_item' 			=> '',
								'search_items' 			=> '',
								'not_found' 			=> '',
								'not_found_in_trash'	=> '',
								'parent' 				=> ''
							),
							'public' => false,
							'menu_position' => 20,
							'supports' =>
							array('title', 'editor', 'comments',
								'thumbnail', 'custom-fields'),
								'taxonomies' => array( '' ),
								'menu_icon' =>
									plugins_url('', __FILE__ ),
							'has_archive' => true
						)
					);				
			
			
			/*CREATE SUB POST TYPES*/
				foreach($slugs as $slug){
					register_post_type(ucwords(str_replace('-', ' ' ,$slug)),
						array(
							'labels' => array(
								'name' 					=> ucwords(str_replace('-', '_' ,$slug)),
								'singular_name' 		=> ucwords(str_replace('-', '_' ,$slug)),
								'rewrite' 				=> array( 'slug' => $slug ),
								'add_new' 				=> '',
								'add_new_item' 			=> '',
								'edit' 					=> '',
								'edit_item' 			=> '',
								'new_item' 				=> '',
								'view' 					=> '',
								'view_item' 			=> '',
								'search_items' 			=> '',
								'not_found' 			=> '',
								'not_found_in_trash'	=> '',
								'parent' 				=> 'vidtok'
							),
							'public' => false,
							'menu_position' => 20,
							'supports' =>
							array('title', 'editor', 'comments',
								'thumbnail', 'custom-fields'),
								'taxonomies' => array( '' ),
								'menu_icon' =>
									plugins_url('', __FILE__ ),
							'has_archive' => true
						)
					);			
				}
				
				
			/*FLUSH REWRITE RULES*/	
				/*IMPORTANT - MOVE TO SETTINGS AFTER REGISTRATION*/
					global $wp_rewrite;
					$wp_rewrite->flush_rules();	

		}

