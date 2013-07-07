<?php get_header(); ?>

<div class="pdf greybox">
<p>Help us get your project<br />
<span style="font-size:25px; line-height:30px; padding:0 0 0 40px; font-weight:bold;">Just Right</span></p>
<p style="margin-bottom:20px;"> Download and submit our <a href="<?php bloginfo('template_url'); ?>/pdfs/planner/mutual-planner-2010.pdf">Mutual Planner</a>. We'll get back to you within a week with our proposal!</p>
</div>

<div class="startproject">
<h2>Start a Project...</h2>
<?php echo do_shortcode('[contact-form 3 "Mutual Planner"]'); ?>
</div>

<div class="askanything">
<h2>Ask Us Anything...</h2>
<?php echo do_shortcode('[contact-form 2 "Contact Us"]'); ?>
</div>

<?php get_footer(); ?>
