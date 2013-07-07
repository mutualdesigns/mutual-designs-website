<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<?php get_header(); ?>

		<div id="content" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		
		<h2 class="entry-title"><?php the_title(); ?></h2>
				
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
	

		<?php comments_template( '', true ); ?>

<?php endwhile; ?>

			</div><!-- #content -->
	
<?php get_footer(); ?>
