<?php get_header(); ?>
<script src="../../../Scripts/swfobject_modified.js" type="text/javascript"></script>

        
        	<div class="greybox" style="width:100%; height:240px">
  
                <div class="videobox"><div><object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="232" height="164">
                               <param name="movie" value="http://www.mutual-designs.com/wp-content/themes/md-twopointoh/videos/who-are-we.swf" />
                               <param name="quality" value="high" />
                               <param name="wmode" value="opaque" />
                               <param name="swfversion" value="6.0.65.0" />
                               <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
                               <!--[if !IE]>-->
                               <object type="application/x-shockwave-flash" data="http://www.mutual-designs.com/wp-content/themes/md-twopointoh/videos/who-are-we.swf" width="232" height="164">
                                  <!--<![endif]-->
                                  <param name="quality" value="high" />
                                  <param name="wmode" value="opaque" />
                                  <param name="swfversion" value="6.0.65.0" />
                                  <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
                                  <div>
                                 	 <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
                                     <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
                                </div>
                                   <!--[if !IE]>-->
                              </object>
                               <!--<![endif]-->
	          	</object></div><!--.video-->
                <span class="whoarewe"></span>
                </div><!--div-->

 	            <div class="videobox"><div><object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="232" height="164">
                              <param name="movie" value="http://www.mutual-designs.com/wp-content/themes/md-twopointoh/videos/what-do-we-do.swf" />
                              <param name="quality" value="high" />
                              <param name="wmode" value="opaque" />
                              <param name="swfversion" value="6.0.65.0" />
                              <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
                              <!--[if !IE]>-->
                              <object type="application/x-shockwave-flash" data="http://www.mutual-designs.com/wp-content/themes/md-twopointoh/videos/what-do-we-do.swf" width="232" height="164">
                                <!--<![endif]-->
                                <param name="quality" value="high" />
                                <param name="wmode" value="opaque" />
                                <param name="swfversion" value="6.0.65.0" />
                                <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
                                <div>
                                  <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
                                  <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
                              </div>
                                <!--[if !IE]>-->
                            </object>
                              <!--<![endif]-->
                </object></div><!--.video-->
                <span class="whatdowedo"></span>
                </div><!--div-->
               
               <div style="float:left; margin:50px 0 0 10px;"><a href="<?php bloginfo('url'); ?>/contact" class="startyourdesign"></a></div><!--div--> 
               
                
            </div><!--#greybox-->
            
            <hr />
        
<div id="content" role="main">
            
            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					
						<?php the_content(); ?>

			<?php endwhile; ?>

			</div><!-- #content -->

<?php get_footer(); ?>
<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
//-->
</script>
