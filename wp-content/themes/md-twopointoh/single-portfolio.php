<?php get_header();

if ( have_posts() ) while ( have_posts() ) : the_post();

		$custom = get_post_custom($post->ID);
		$url = $custom["url"][0];
		$screenshot = $custom["screenshot"][0];
		$do_not_duplicate = $post->ID;
?>
  
	<div class="greybox" style="width:350px; height:auto; float:left;">
  
	<img src="<?php echo $screenshot; ?>" alt="<?php the_title(); ?>" class="screenshot" />
  
	</div><!--.greybox-->   

	<div id="content" role="main">
    
    <div class="problem-solution">

	<h2><?php the_title(); ?></h2>
    <p style="padding:0;"><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p><br /></p>
	<?php the_content(); ?>
    
    </div><!--problem-solution-->
	
	</div><!-- #content -->
    
     <?php endwhile; ?>
    
    <div class="clear"></div>
    
<?php $loop = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => 20 ) );
while ( $loop->have_posts() ) : $loop->the_post();
if( $post->ID == $do_not_duplicate ) continue; update_post_caches($posts);

		$custom = get_post_custom($post->ID);
		$thumbnail = $custom["thumbnail"][0];
?>

	<div class="thumbnail">
    
    <div class="greybox">

	<a href="<?php the_permalink(); ?>"><img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>" class="thumbnail" /></a>
    
    </div><!--.greybox-->
    
    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
    
    </div><!--.thumbnail-->
    
 <?php endwhile; ?>
 
    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> more' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'more <span class="meta-nav">&rarr;</span>' ) ); ?></div>
		
<?php get_footer(); ?>
