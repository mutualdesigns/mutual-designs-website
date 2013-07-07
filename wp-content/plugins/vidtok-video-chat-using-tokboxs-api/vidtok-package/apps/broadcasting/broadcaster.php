<!doctype html>
<html>
<head>
<meta charset="utf-8">
	
    <!--TITLE-->	
        <title>Vidtok: Broadcasting</title>

    <!--OPEN GRAPH META DATA-->
        <meta property="og:site_name" content="Vidtok Video Chat" />
        <meta property="og:title" content="Vidtok -stream video to the world-" />
        <meta property="og:url" content="http://vidtok.co/" />
		<meta property="og:type" content="website" />
        <meta property="og:locale" content="en_US" />       
        <meta property="og:image" content="http://vidtok.co/images/logos/vidtok-logo-v-large.png" /> 
        <meta property="og:description" content="Vidtok Video allows you to create 1:1 and multiuser video streams using Opentok's Tokbox API platform. Start streaming video to the world today!" />
        
    <!--MAIN CSS-->
    	<link href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/css/main.css" rel="stylesheet" />

    <!--BROADCASTER APP CSS-->
    	<link href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/css/broadcasting.css" rel="stylesheet" />
        
	<!--JQUERY-->
    	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
            
	<!--TOXBOX-->
    	<script src="http://static.opentok.com/v0.91/js/TB.min.js" ></script>        
        
	<!--PLUGINS-->
    	<script src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/js/plugins.js"></script>
	
    <!--VIDTOK-->
    	<script src="http://static.vidtok.co/v1.0/js/vidtok.min.js"></script> 
         	
    <!--GLOBAL-->
    	<script src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/js/globals.js"></script>
   	
    <!--VIDTOK OPTIONS-->
     	<?php $options = get_option('vidtok_options'); ?>
        <?php 
			
			/*WORDPRSS DATABASE*/
				global $wpdb;
			
			/*VARIABLES*/
				$vid = $_GET['vid'];  

			/*QUERY*/				
				$query = 'select * from ' . $wpdb->get_blog_prefix() . "vidtok_sessions WHERE vid = %s"; 
				
				$vidtok = $wpdb->get_row($wpdb->prepare($query, $vid)); 
			
			/*OPENTOK SDK*/
				require_once VIDTOK_PLUGINFULLDIR.'libs/opentok-sdk/OpenTokSDK.php';
			
			/*CREATE TOKEN*/
				$apiObj = new OpenTokSDK($options['oapi'], $options['osecert']);

				$token = $apiObj->generateToken($vidtok->opentok_session_id);

			
		?>


          
   	<!--VIDTOK SHARE-->
       	<script type="text/javascript">

			jQuery(document).ready(function(e) {


			   /*Authorize Vidtok*/ 
				  jQuery.vidtok.authorize({ vApi : '<?php echo $options['vapi']; ?>', vArchive : '', vType : 'broadcasting', vPlatform : 'wordpress', oApi : '<?php echo $options['oapi']; ?>', oSession : '<?php echo $vidtok->opentok_session_id; ?>', oToken : '<?php echo $token; ?>' }); 
				
			  /*Vidtok Video Share*/
				  jQuery('#vidtok-submit').click(function(){
						
					  /*Variables*/
							var email 	= jQuery('#vidtok-email').val();
							var url		= '<?php echo str_replace('broadcasting', 'viewer', $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>';
							
							
					  if(isValidEmailAddress(email) === true){
							
						  /*Send Email*/	
							  jQuery.ajax({
								  
								 type		: 'POST',
								 url		: '<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/email.php',
								 data		: { email : email, url : url },
								 success	: function(){
									 
												/*Clear Email*/
													jQuery('#vidtok-email').val('');
													
												/*Notification*/	
													$.gritter.add({
														
														title		: 'Invitation Sent',
														text		: 'You have successfully sent your invitation to join your Vidtok Video Chat.',
														image		: "<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo-notices.png",
														sticky	: false
														
													});	
													
								 }  
							  });
	
					  }else{
						  
						/*Notification*/	
							$.gritter.add({
								
								title		: 'Invalid Email Address',
								text		: 'There was an error with the entered email address, please check the email address and resend the invitation.',
								image		: "<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo-notices.png",
								sticky		: false
								
							});							   
	
					  }
	
				  });
					
				
			});
			
		</script>


</head>
<body>
	
    
    <!--HEADER-->
    	<div class="vidtok-header">
        	<div class="vidtok-logo"><a href="http://vidtok.co" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo.png" alt="Vidtok Video Chat" /></a></div>
            <div class="vidtok-connections"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/connections.png" alt="Connections" /><p>Viewers: <span id="vidtok-connections">0</span></p></div>
        </div>
    
    
    <!--VIDTOK VIDEO-->
    	<div class="vidtok-w">
        
            <!--VIDTOK PUBLISHER-->
                <div class="vidtok-broadcasting-video-h" id="vidtok-pub">
                	<div class="vidtok-publisher-loading" id="vidtok-publisher-loader"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/large-loader-cyan.gif" alt="loading" /><p>Connecting to Vidtok Video...</p></div>	
                    <div id="vidtok-publisher"></div>
                </div>

        
        </div>


    <!--VIDTOK SHARE LINK-->
		<div class="vidtok-share-w" id="vidtok-share-panel">
            
        	<form>
                <label>Send to</label>
                <input type="text" id="vidtok-email" name="vidtok-email" value="" size="20" />
                <input type="button" id="vidtok-submit" name="vidtok-submit" class="vidtok-button" value="Send Invite" />
            </form>
            
            <div class="vidtok-share-divide">|</div>
            
            <div class="vidtok-share-panel-handle"><p>Share Panel</p><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/share.png" alt="Vidtok Share Panel" /></div>
        
        </div>

	
    
    <!--VIDTOK TOOL PANEL-->
        <div id="vidtok-tool-panel">
            <div class="vidtok-tool-panel-w">
               
               <!--VIDTOK TOOLS-->
                    <div class="vidtok-tool-panel-handle"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/tools.png" alt="Vidtok Tool Panel" /><p>Tool Panel</p></div>
                    <div class="vidtok-tool-panel-h">
                        <div class="vidtok-tool-panel-c" id="vidtok-tools-refresh"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/refresh.png" alt="Refresh Vidtok Video"/><p>Refresh</p></div>
                        <div class="vidtok-tool-panel-c" id="vidtok-tools-network"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/network.png" alt="Vidtok Network Connection"/><p>Network Connection</p></div>
                        <div class="vidtok-tool-panel-c" id="vidtok-tools-settings"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/settings.png" alt="Vidtok Settings"/><p>Settings</p></div>
                    </div>
                
                
                <!--VIDTOK DIALOG--> 
                    <div class="vidtok-tool-panel-dialog">
                    
                        <!--NETWORK-->
                            <div class="vidtok-tool-panel-dialog-c" id="vidtok-dialog-network">
                                <div class="vidtok-dialog-loading" id="vidtok-loading-network"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/large-loader-cyan.gif" alt="" /><p>Loading Network Data...<br/>Data will load when you click the allow button...</p></div>
                                <div class="vidtok-show-network">              
                                    <h2>Connection Quality</h2>
                                    <p><span id="vidtok-connection-alert"></span><br/><br/><a href="http://www.tokbox.com/user-diagnostic/" style="text-decoration:none; color:#098CC2; text-transform:uppercase;" target="_blank">Diagnostic Tool</a></p>	
                                </div>
                            </div>
    
    
                        <!--SETTINGS-->
                            <div class="vidtok-tool-panel-dialog-c" id="vidtok-dialog-settings">
                            	<div class="vidtok-dialog-loading" id="vidtok-loading-settings"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/large-loader-cyan.gif" alt="" /><p>Loading Connected Devices...</p></div>
    							<div class="vidtok-show-settings">
                                	<div class="vidtok-settings-button"><button class="vidtok-button" id="vidtok-change-device" onClick="$.vidtok.panel();">Change Device</button></div>
                                	<h2>Connected Devices</h2>
                                    <p>Below you will see the current connected devices to your video and audio, you can select different devices by clicking on the change devices button.</p>
                                	<div class="vidtok-tool-panel-device">
                                    	<img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-72/webcam.png" alt="Vidtok Video Chat" />
                                        <p id="vidtok-selected-camera"></p>
                                    </div>
                                    <div class="vidtok-tool-panel-device">
                                    	<img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-72/mic.png" alt="Vidtok Video Chat" />
                                        <p id="vidtok-selected-mic"></p>
                                    </div>
                                </div>
                            </div>             
                    
                    </div>	

            </div>
        </div>  
        
        
	<!--VIDTOK FOOTER-->
    	<div class="vidtok-footer">
        	<!--<p>Powered by <a href="http://vidtok.co" target="_blank">Vidtok</a> a <a href="http://blaccspot.com" target="_blank">Blacc Spot Media, Inc.</a> Company</p>-->
        </div>
            
        

</body>
</html>