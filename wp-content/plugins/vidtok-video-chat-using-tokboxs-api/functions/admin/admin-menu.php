<?php


/*  ADMIN MENU
/*---------------------------*/
	
	function vidtok_admin_menu()
		{
			
			/*VARIABLES*/
				$icon_url	= VIDTOK_PLUGINFULLURL . 'vidtok-package/images/logo/vidtok-admin-logo.png'; 
				
			/*ADD SETTINGS GROUP*/
				add_menu_page('Vidtok Settings', 'Vidtok Settings', 'activate_plugins', 'vidtok_plugin_settings', 'vidtok_account', $icon_url);   
 			
			/*ADD CREATE VIDEO CHAT*/
				add_submenu_page('vidtok_plugin_settings', 'Video Management', 'Video Management', 'activate_plugins', 'vidtok_manage_video_chat', 'vidtok_manage');
			
		}



/*  VIDTOK MAIN PAGE
/*---------------------------*/

	function vidtok_account() {  
	
		/*OPTIONS*/
			$options = get_option('vidtok_options');
			
			if($options['vapi'] != NULL || $options['vapi'] != '' || $options['vapi'] != false || $options['vapi'] != 0){
		
				/*SUBSCRIPTION*/
				  $url 		= 'http://vidtok.co/api/activation/subscription/id/'.$options['vapi'];
				  $content 	= file_get_contents($url);
				  $json		= json_decode($content, true);
		
			}
	?>

        <div class="wrap" style="font-size:14px;">  
            
            <div id="icon-options-general" class="icon32"><br /></div><h2>Vidtok Settings</h2>
				
                <br/>
                
				<?php if ( isset( $_GET['message'])&& $_GET['message'] == '1'){ ?>
                	<div id='message' class='updated fade'><p><strong>Vidtok Settings Saved</strong></p></div>
                <?php } ?>

            
            <h2>Welcome to Vidtok</h2>
            <cite>-stream video to the world-</cite>  
            
            
            
       	  	<br/><br/>
                


            <hr/>
            <h2>Vidtok Account</h2>
            <p>If you haven't created an account on the Vidtok website please do so by visiting <a href="http://vidtok.co/pricing" target="_blank">Vidtok: Subscriptions</a>, select a subscription package and create a new account. Once inside your account dashboard, look for your <code>VIDTOK API KEY</code> and enter it below.</p>

            <form method="POST"  action="admin-post.php">  
                <table class="form-table">  
                    <tr valign="top">  
                        <th scope="row">  
                            <label for="vapi">VIDTOK API KEY</label>  
                        </th>  
                        <td>  
                            <input type="text" name="vapi" size="32" value="<?php echo esc_html($options['vapi']); ?>" />  
                        </td>
                        <td>&nbsp;</td>  
                    </tr>
                     <tr valign="top">  
                        <th scope="row">  
                            <label for="vsubscription">VIDTOK SUBSCRIPTION TYPE</label>  
                        </th>  
                        <td>  
                            <input type="text" name="vsubscription" size="32" disabled value="<?php echo esc_html($options['vsubscription']); ?>" />  
                        </td>
                        <td align="left"><?php if($options['vsubscription'] == ''){ ?><strong style="color:red;">VIDTOK ERROR:</strong> There is an error with your Vidtok API KEY make sure you entered the correct key for this domain.<?php } ?></td>  
                    </tr>
                    <?php if(isset($json['subscription_status'])){ ?> 
                    <tr valign="top">  
                        <th scope="row">  
                            <label for="vapi">VIDTOK SUBSCRIPTION STATUS</label>  
                        </th>  
                        <td><input type="text" name="status" size="32" disabled value="<?php echo $json['subscription_status']; ?>" /></td>
                        <td>&nbsp;</td>  
                    </tr>
                    <?php } ?>                                         
                </table>  
                
            
            <br/><br/>
                

            <hr/>
            <h2>Tokbox Account</h2>
            <p>To access the Vidtok Service you must create an account with Tokbox and get an <code>API KEY</code> and <code>API SECERT</code>. If you already have a Tokbox account please enter your <code>API KEY</code> and <code>API SECERT</code> below.<br/><br/>If you are new to Tokbox follow create an account with Tokbox via: <a href="https://dashboard.tokbox.com/signups/new" target="_blank">Tokbox Account Creation</a></p>

 
                <table width="378" class="form-table">  
                    <tr valign="top">  
                        <th scope="row">  
                            <label for="oapi">TOKBOX API KEY</label>  
                        </th>  
                        <td>  
                            <input type="text" name="oapi" size="32" value="<?php echo esc_html($options['oapi']); ?>" />  
                        </td>  
                    </tr> 

                    <tr valign="top">  
                        <th scope="row">  
                            <label for="osecert">TOKBOX API SECERT</label>  
                        </th>  
                        <td>  
                            <input type="text" name="osecert" size="32" value="<?php echo esc_html($options['osecert']); ?>" />  
                        </td>  
                    </tr>  
                </table>
                
                <input type="submit" value="Save Vidtok Settings" class="button-primary"/>
                
                <input type="hidden" name="action" value="save_vidtok_options" />
               	<?php wp_nonce_field('vidtok'); ?>                
                
                <br/><br/>
                
                <hr/>
                <h2>Vidtok Subscription Changes</h2>
                <p>If you have upgraded or downgraded your subscription on the Vidtok website, please click on the Update Vidtok Subscription button below. This will update your subscription with your Wordpress plugin.</p>
                 
                 <input type="submit" value="Update Vidtok Subscription" class="button-primary"/>
                  
            </form> 
            
             
		</div> 

	<?	}



/*  CREATE VIDEO CHATS 
/*---------------------------*/
	
	function vidtok_manage()
		{ 
			
			/*OPTIONS*/
				$options = get_option('vidtok_options');
		
		?>
			
			<div class="wrap" style="font-size:14px;">  
				
				<div id="icon-options-general" class="icon32"><br /></div><h2>Vidtok Video Management</h2>
	
				<br/>
                
				<h2>Welcome to Vidtok</h2>
				<cite>-stream video to the world-</cite>  
				
				<br/><br/>
	
				<hr/>
                <h2>Create & Manage Vidtok Video Chats</h2>
  				<p>Using the icons below you can create new video chats, once you enter into the video chat session you will be able to share the link via email, Facebook and Twitter. Once you send your invites just sit back and wait for the show.</p> 
				
                <br/><br/>
                
            <!---->
                <table border="0">
                    <tr>
                      <td width="200" align="center"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=individual" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/user.png" alt="" width="128" height="128"/></a></td>
                      <td width="60" align="center">&nbsp;</td> 
                      <td width="200" align="center"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=broadcast" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/broadcasting.png" alt="" width="128" height="128"/></a></td>
                      <td width="60" align="center">&nbsp;</td>
                      <?php if($options['vsubscription'] != 'free'){ ?><td width="200" align="center"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=group" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/group.png" alt="" width="128" height="128"/></a> <?php } ?></td>
                    </tr>
                    <tr>
                      <td align="center">CREATE 1:1 VIDEO CHAT</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">CREATE BOARDCAST</td>
                      <td align="center">&nbsp;</td>
                      <?php if($options['vsubscription'] != 'free'){ ?><td align="center">CREATE GROUP VIDEO CHAT</td><?php } ?>
                    </tr>
                    <tr>
                      <td colspan="5" height="60" align="center">&nbsp;</td>
                    </tr>
                    <?php if($options['vsubscription'] == 'advanced' || $options['vsubscription'] == 'premium'){ ?>
                    <tr>
                      <td align="center"><a href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/create.php?type=archive" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/record.png" alt="" width="128" height="128"/></a></td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center"><a href="<?php echo site_url(); ?>/vidtok/management" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-128/archive-management.png" alt="" width="128" height="128"/></a></td>  
                    </tr>
                    <tr>
                      <td align="center">CREATE ARCHIVE</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">&nbsp;</td>
                      <td align="center">ARCHIVE MANAGMENT</td>
                    </tr>
                    <?php } ?>
                  </table>
                 
            
                             
              </div> 
			
		<?php }










