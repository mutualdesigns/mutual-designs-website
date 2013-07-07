<!doctype html>
<html>
<head>
<meta charset="utf-8">
	
    <!--TITLE-->	
        <title>Individual Video Chat</title>

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

    <!--ARCHIVE SHARE APP CSS-->
    	<link href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/css/archive-management.css" rel="stylesheet" />
        
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
			
			/*OPENTOK SDK*/
				require_once VIDTOK_PLUGINFULLDIR.'libs/opentok-sdk/OpenTokSDK.php';
			
			/*CREATE TOKEN*/
				$apiObj = new OpenTokSDK($options['oapi'], $options['osecert']);

				$token = $apiObj->generateToken('', RoleConstants::MODERATOR);

			
		?>
    
   	<!--VIDTOK SHARE-->
       	<script type="text/javascript">
			jQuery(document).ready(function(e) {
				
				
				/*IMPORTANT*/
				/*Generate Token with BLANK Session ID*/                
				
			   /*Authorize Vidtok*/ 
					jQuery.vidtok.authorize({ vApi : '<?php echo $options['vapi']; ?>', vArchive : '<?php echo $_GET['aid']; ?>', vType : 'share', vPlatform : 'wordpress', oApi : '<?php echo $options['oapi']; ?>', oSession : '', oToken : '<?php echo $token; ?>' }); 
				
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
        
            <!--VIDTOK PLAYBACK-->
                <div class="vidtok-recorder-video-share-h" id="vidtok-pub">
                    <div id="vidtok-publisher"></div>
                </div>
                

        </div>

        
	<!--VIDTOK FOOTER-->
    	<div class="vidtok-footer">
        	<!--<p>Powered by <a href="http://vidtok.co" target="_blank">Vidtok</a> a <a href="http://blaccspot.com" target="_blank">Blacc Spot Media, Inc.</a> Company</p>-->
        </div>
            
        

</body>
</html>