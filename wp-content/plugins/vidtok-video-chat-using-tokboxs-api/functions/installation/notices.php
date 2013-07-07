<?php




/*  VIDTOK ACTIVATION SETTINGS NOTICE
/*---------------------------*/
	
	function vidtok_settings_notice()
		{
			
			/*OPTIONS*/
				$options = get_option('vidtok_options');
			
			/*MESSAGES*/
				if($options['vapi'] == '' && $options['oapi'] != '' && $options['osecert'] != ''){
					
					/*VIDTOK API KEY NEEDED*/
						echo '<div class="error"><p><strong>VIDTOK PLUGIN INSTALLATION:</strong> Your Vidtok API KEY is missing, you will need to <a href="options-general.php?page=vidtok_plugin_settings">save your account api keys</a> before the plugin will work properly. If you are new to Vidtok you will need to create an account and retrieve your API KEY from within your account dashborad on the Vidtok website. Please visit <a href="http://vidtok.co/pricing" target="_blank">Vidtok: Subscriptions</a>, select a subscription package and create a new account. </p></div>';
					
				}else if(($options['oapi'] == '' && $options['osecert'] == '' && $options['vapi'] != '') || ($options['oapi'] != '' && $options['osecert'] == '' && $options['vapi'] != '') || ($options['oapi'] == '' && $options['osecert'] != '' && $options['vapi'] != '')){
					
					/*OPENTOK API KEY*/
						echo '<div class="error"><p><strong>VIDTOK PLUGIN INSTALLATION:</strong> You your Tokbox API KEY & API SECERT is missing, you will need to <a href="options-general.php?page=vidtok_plugin_settings">save your Tokbox API KEY & API SECERT</a> before the plugin will work properly. If you are new to Tokbox you will need to create an account and retrieve your API KEY from within your account dashborad on the Tokbox website. Please visit <a href="https://dashboard.tokbox.com/signups/new" target="_blank">Tokbox Account Creation</a>.</p></div>';
					
				}else if($options['oapi'] == '' && $options['osecert'] == '' && $options['vapi'] == ''){
					
					/*NEW INSTALL*/
						echo '<div class="error"><p><strong>VIDTOK PLUGIN INSTALLATION:</strong> You will need to update your <a href="options-general.php?page=vidtok_plugin_settings">Vidtok Settings</a> before the plugin will work properly. </p></div>';
						
				}
				
				
			
			
		}
	























