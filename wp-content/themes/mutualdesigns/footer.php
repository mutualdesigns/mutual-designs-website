<!-- Begin Mutual Designs Footer -->

<div id="footer">
	<hr />
        <ul id="nav">
        	<?php /*?><li>Client <?php wp_loginout(); ?></li><?php */?>		   
            <?php wp_list_pages('title_li=&sort_column=post_date&sort_order=DESC&exclude=4'); ?>
            <?php if (is_page('Introduction')) { ?>
                <li style="border:none;" class="current_page_item"><a href="<?php echo get_option('home'); ?>">Home</a></li>
            <?php } else { ?>
            	<li style="border:none;"><a href="<?php echo get_option('home'); ?>">Home</a></li>
            <?php } ?>
        </ul>

		<p>&copy; <?php echo date("Y"); ?> All rights reserved | <?php bloginfo('name'); ?> | 217 Meadow Drive Latrobe, PA 15650</p>

</div>

<!-- End Mutual Designs Footer -->
	
    	<?php wp_footer(); ?>

</div> 

<!-- End of wrapper -->

</body>
</html>
