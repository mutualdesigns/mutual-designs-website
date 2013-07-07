<?php get_header(); ?>

			<div id="content" style="width:490px; float:left;" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
            <?php the_title(); ?>
			<?php the_content(); ?>

<?php endwhile; ?>
		
        
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

<?php get_footer(); ?>
