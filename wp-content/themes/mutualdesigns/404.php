<?php get_header(); ?>

	<div id="content" >

		<h2 class="center">Error 404 - Page Not Found</h2>
        <p>Sorry, but the page you are looking for cannot be found. Please select from one of the following pages:</p>
        <ul> 
            <?php wp_list_pages('title_li=&sort_column=menu_order&exclude='); ?>
		</ul>
        <p>Nothing there that you are looking for?  Visit our <a class="pink" href="<?php get_bloginfo('url'); ?>/contactus/">contact</a> page, and send us an email with your question.  At <a class="pink" href="<?php get_bloginfo('url'); ?>">Mutual Designs</a>, we strive to always make sure you receive the service you deserve.  Telling us what you need will help us with that.</a>

	</div>

<?php get_footer(); ?>