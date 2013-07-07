<!doctype html>
<html>
<head>
<meta charset="utf-8">
	
    <!--TITLE-->	
        <title>Vidtok View Broadcast</title>

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

    <!--VIEWER CSS-->
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
				  jQuery.vidtok.authorize({ vApi : '<?php echo $options['vapi']; ?>', vArchive : '', vType : 'viewer', vPlatform : 'wordpress', oApi : '<?php echo $options['oapi']; ?>', oSession : '<?php echo $vidtok->opentok_session_id; ?>', oToken : '<?php echo $token; ?>' }); 
				
				
			  /*Vidtok Video Share*/
				  jQuery('#vidtok-submit').click(function(){
						
					  /*Variables*/
							var email 	= jQuery('#vidtok-email').val();
							var url		= window.location.href;
							
							
					  if(isValidEmailAddress(email) === true){
							
						  /*Send Email*/	
							  jQuery.ajax({
								  
								 type		: 'POST',
								 url		: '<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/actions/email.php',
								 data		: { email : email, url : url },
								 dataType	: 'json',
								 success	: function(data){
									 
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
								sticky	: false
								
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
        </div>
    
    <!--VIDTOK VIDEO-->
    	<div class="vidtok-w">

            <!--VIDTOK SUBSCRIBER-->
                <div class="vidtok-broadcasting-video-h" id="vidtok-sub">
                	<div class="vidtok-subscriber-loading" id="vidtok-subscriber-loader"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/large-loader-cyan.gif" alt="loading" /><p>Connecting to Vidtok Video Broadcast...</p></div>
                    <div id="vidtok-subscriber" class="vidtok-subscriber-large"></div>
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
            
            
            <script>function fbs_click() { u=location.href; t=document.title; window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=500,height=360');return false;}</script>
            <a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank">
            <img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-48/facebook.png" alt="" /></a>
            
            <div class="vidtok-share-divide">|</div>
            
            <script>function tw_click() { u=location.href; t=document.title; window.open('http://twitter.com/intent/tweet?text=<?php echo urlencode('Join this Vidtok video broadcast! '.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>','sharer','toolbar=0,status=0,width=500,height=360');return false;}</script>
            <a rel="nofollow" href="#" onclick="return tw_click()" target="_blank">
            <img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-48/twitter.png" alt="" /></a>
                    
            <div class="vidtok-share-divide">|</div>
            
            <div class="vidtok-share-panel-handle"><p>Share Panel</p><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/share.png" alt="Vidtok Share Panel" /></div>
        
        </div>


	<!--VIDTOK FOOTER-->
    	<div class="vidtok-footer">
        	<!--<p>Powered by <a href="http://vidtok.co" target="_blank">Vidtok</a> a <a href="http://blaccspot.com" target="_blank">Blacc Spot Media, Inc.</a> Company</p>-->
        </div>
            
        

</body>
</html>