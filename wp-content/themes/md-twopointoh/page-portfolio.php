<?php get_header();

$loop = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => 20 ) );
while ( $loop->have_posts() ) : $loop->the_post();

		$custom = get_post_custom($post->ID);
		$url = $custom["url"][0];
		$screenshot = $custom["screenshot"][0];
		$thumbnail = $custom["thumbnail"][0];

if ($loop->current_post < 1) { ?>
  
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
    
    <div class="clear"></div>
    
<?php } else { ?>
	<div class="thumbnail">
    
    <div class="greybox">

	<a href="<?php the_permalink(); ?>"><img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>" class="thumbnail" /></a>
    
    </div><!--.greybox-->
    
    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
    
    </div><!--div-->
    
<?php } endwhile; ?>

    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> more' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'more <span class="meta-nav">&rarr;</span>' ) ); ?></div>
		
<?php get_footer(); ?>
