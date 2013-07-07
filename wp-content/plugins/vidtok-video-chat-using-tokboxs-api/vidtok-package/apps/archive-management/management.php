<!doctype html>
<html>
<head>
<meta charset="utf-8">
	
    <!--TITLE-->	
        <title>Vidtok: Archive Management Demo</title>

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

    <!--ARCHIVE APP CSS-->
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

				/*IMPORTANT*/
				/*Generate Token with BLANK Session ID*/                
				
			   /*Authorize Vidtok*/ 
					jQuery.vidtok.authorize({ vApi : '<?php echo $options['vapi']; ?>', vArchive : '', vType : 'management', vPlatform : 'wordpress', oApi : '<?php echo $options['oapi']; ?>', oSession : '', oToken : '<?php echo $token; ?>' }); 
				
            });
		</script>

</head>
<body>
	
    
    <!--HEADER-->
    	<div class="vidtok-header">
        	<div class="vidtok-logo"><a href="http://vidtok.co" target="_blank"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo.png" alt="Vidtok Video Chat" /></a></div>
            <div class="vidtok-delete"><a href="#" onClick="jQuery('.vidtok-show-delete').fadeIn();"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/trash.png" alt="Delete" /></a><p>Show Delete Archives</p></div>
        </div>
    
    <!--VIDTOK VIDEO-->
    	<div class="vidtok-w">
        
            <!--VIDTOK PLAYBACK-->
                <div class="vidtok-recorder-video-h" id="vidtok-pub">
                    <div id="vidtok-publisher"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/vidtok-logo-v-large.png" style="margin-top:20px;" alt="Vitdok" /></div>
                </div>
                
           	<!--VIDTOK ARCHIVE LIST-->
            	<div class="vidtok-archive-list-w">
                	
                    <?php foreach($archives as $archive){ ?> 

                        <div class="vidtok-archive-list-h" id="<?php echo $archive->archive_id; ?>">
                            <a href="#" onClick="$.vidtok.archive({ archive_id : '<?php echo $archive->archive_id; ?>', vApi : '<?php echo $options['vapi']; ?>', vDomain : document.domain });"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/archive-managment.png" alt=""/></a>
                            <a href="#" onClick="$.vidtok.download({ archive_id : '<?php echo $archive->archive_id; ?>', vApi : '<?php echo $options['vapi']; ?>', vDomain : document.domain });"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/download.png" alt="" /></a>
                            <a href="#" onClick="$.vidtok.remove({ archive_id : '<?php echo $archive->archive_id; ?>', vApi : '<?php echo $options['vapi']; ?>', vDomain : document.domain });" class="vidtok-show-delete"><img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-26/trash.png" alt="" /></a>
                            <div class="vidtok-archive-list-c">
                                <p><?php echo $archive->archive_title; ?></p>
                                <div class="vidtok-archive-share-h"> 
                                    <script>
                                        function fbs_click_<?php echo $archive->id; ?>() { u=location.href; t='Vidtok: Video Share'; window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent('<?php echo str_replace('management', 'archive', str_replace('management', 'share', ($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"])))."/?aid=$archive->archive_id"; ?>')+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=500,height=360');return false;}</script>
                                    <a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click_<?php echo $archive->id; ?>()" target="_blank">
                                    <img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-48/facebook.png" alt="" /></a>
                                     
                                    
                                    <script>function tw_click_<?php echo $archive->id; ?>() { u=location.href; t=document.title; window.open('http://twitter.com/intent/tweet?text=<?php echo urlencode('View our Vidtok video chat! '.str_replace('management', 'archive', str_replace('management', 'share', ($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]))))."/?aid=$archive->archive_id"; ?>','sharer','toolbar=0,status=0,width=500,height=360');return false;}</script>
                                    <a rel="nofollow" href="#" onclick="return tw_click_<?php echo $archive->id; ?>()" target="_blank">
                                    <img src="<?php echo VIDTOK_PLUGINFULLURL; ?>vidtok-package/images/icons/icons-48/twitter.png" alt="" /></a>
                                </div>
                            </div> 
                        </div>
                    
                    <?php } ?>
                                        
                    
                </div>     


        </div>

        
	<!--VIDTOK FOOTER-->
    	<div class="vidtok-footer">
        	<!--<p>Powered by <a href="http://vidtok.co" target="_blank">Vidtok</a> a <a href="http://blaccspot.com" target="_blank">Blacc Spot Media, Inc.</a> Company</p>-->
        </div>
            
        

</body>
</html>