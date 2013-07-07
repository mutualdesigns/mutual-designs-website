<?php get_header(); ?>

		<div id="content" style="width:490px; float:left;" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
			<?php the_content(); ?>

<?php endwhile; ?>
		<div  style="float:left; margin: 0 10px 0 10px; text-align:center;">
		<div class="steps facebook"></div>
        <div id="fb-root" style="padding:6px;"></div>
             <script>
 			 window.fbAsyncInit = function() {
    			FB.init({appId: 'your app id', status: true, cookie: true,
            	 xfbml: true});
  				};
  			(function() {
    			var e = document.createElement('script'); e.async = true;
    			e.src = document.location.protocol +
      			'//connect.facebook.net/en_US/all.js';
    		document.getElementById('fb-root').appendChild(e);
 			 }());
			</script>
        <fb:like href="http://www.facebook.com/pages/Latrobe-PA/Mutual-Designs/35572946736?ref=ts" layout="button_count" show_faces="false" width="100"></fb:like>
        </div>
        
        <div  style="float:right; margin: 0 10px 0 10px; text-align:center;">
        <div class="steps twitter"></div>
		<p>@MutualDesigns</p>
        </div>
        
		</div><!-- #content -->
        
        <div class="greybox about" style="padding: 15px; width: 230px; height: auto; margin: 0pt 25px; color:#FFF; float:right;">
                    
            <h2>How we work...</h2>
        
        	<div style="margin-top:35px;" class="steps step1"></div>
        	<p><span class="pink">Step ONE...</span><br />
			We work together to create mockups of your new site/logo to determine a final "look" for your project.</p>
        	
            
            <div class="steps step2"></div>
        	<p><span class="pink">Step TWO...</span><br />
			<span style="color:#FFF">We code your site to meet XHTML standards before creating any dynamic PHP pieces of your site.</span></p>
        	
            
            <div class="steps step3"></div>
        	<p><span class="pink">Step THREE...</span><br />
			<span style="color:#FFF">We build your CMS system and configure any plugins/code to make your site "work."</span></p>
        	
            
            <div class="steps step4"></div>
        	<p><span class="pink">Step FOUR...</span><br />
			At this point, we will test your site extensively and create tutorial videos to help you use it!</p>
 
            
        </div>

 <!-- Twitter @Anywhere code -->
 
	 <script src="http://platform.twitter.com/anywhere.js?id=8yMcloDj7YEslrRi2irA&amp;v=1">
      </script>
      <script type="text/javascript">
         twttr.anywhere(function(twitter) {
                  twitter.hovercards();
         });
      </script>
  
  <!-- End Twitter @Anywhere code -->
	
<?php get_footer(); ?>
