<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<h2>Client Area...</h2>

<div class="greybox" style="width:450px; height:700px;">

			<?php the_content(); ?>

<?php endwhile; ?>

</div>



<?php get_footer(); ?>
