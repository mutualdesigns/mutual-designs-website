	</div><!-- #main -->
	<div class="clear"></div>
	<div id="footer" role="contentinfo">
				<hr />
            
					<?php wp_nav_menu( array( 'container_class' => 'menu-footer', 'theme_location' => 'footer' ) ); ?>
              
              &copy; <?php echo date("Y"); ?>. All rights reserved. <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>, 217 Meadow Drive Latrobe, PA 15650. <br /> 724.804.8040. Email: <a href="mailto:contactus@mutual-designs.com">contactus@mutual-designs.com</a>
                

	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>
