<!doctype html>
<html>
<head>
<meta charset="utf-8">
	
    <!--TITLE-->	
        <title>Vidtok: Archiving & Recording Demo</title>

    <!--OPEN GRAPH META DATA-->
        <meta property="og:site_name" content="Vidtok Video Chat" />
        <meta property="og:title" content="Vidtok -stream video to the world-" />
        <meta property="og:url" content="http://vidtok.co" />
		<meta property="og:type" content="website" />
        <meta property="og:locale" content="en_US" />       
        <meta property="og:image" content="http://vidtok.co/images/logos/vidtok-logo-v-large.png" /> 
        <meta property="og:description" content="Vidtok Video allows you to create 1:1 and multiuser video streams using Opentok's Tokbox API platform. Start streaming video to the world today!" /> 

    <!--MAIN CSS-->
    	<link href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/css/main.css" rel="stylesheet" />

    <!--ARCHIVE APP CSS-->
    	<link href="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/css/archive.css" rel="stylesheet" />
        
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
			
			/*GLOBAL VARIABLES*/
		 		var vPluginURL = '<?php echo VIDTOK_PLUGINFULLURL; ?>';
				
			jQuery(document).ready(function(e) {
				
				/*Watermark*/
					$('#archive-title').watermark('ARCHIVE TITLE');  
					
			    /*Authorize Vidtok*/ 
				  jQuery.vidtok.authorize({ vApi : '<?php echo $options['vapi']; ?>', vArchive : '', vType : 'archive', vPlatform : 'wordpress', oApi : '<?php echo $options['oapi']; ?>', oSession : '<?php echo $vidtok->opentok_session_id; ?>', oToken : '<?php echo $token; ?>' }); 
				
				
            });
		</script> 


</head>
<body>
	
    
    <!--HEADER-->
    	<div class="vidtok-header">
        	<div class="vidtok-logo"><a href="http://vidtok.co" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo.png" alt="Vidtok Video Chat" /></a></div>
            <div class="vidtok-archive-management"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/archive-managment.png" alt="Connections" /><p><a href="<?php echo site_url(); ?>/vidtok/management">Archive Management</a></p></div> 
        </div>
        
    
    <!--VIDTOK VIDEO-->
    	<div class="vidtok-w">
        
            <!--VIDTOK PLAYBACK-->
                <div class="vidtok-recorder-video-h" id="vidtok-pub">
                    <div id="vidtok-publisher"></div>
                </div>
            
            <!--VIDTOK ARCHIVE TITLE-->    
				<div class="vidtok-archive-title">
                	<input type="text" id="archive-title" name="archive-title" value="" /> 
                    <p style="text-transform:uppercase;">Enter a Archive Title before clicking save or your Archive Title will be a timestamp.</p>
                </div>
                
        </div>


	<!--VIDTOK FOOTER-->
    	<div class="vidtok-footer">
        	<!--<p>Powered by <a href="http://vidtok.co" target="_blank">Vidtok</a> a <a href="http://blaccspot.com" target="_blank">Blacc Spot Media, Inc.</a> Company</p>-->
        </div>
            
        

</body>
</html>