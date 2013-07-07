<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

 <!-- Twitter @Anywhere code -->
 
	 <script src="http://platform.twitter.com/anywhere.js?id=8yMcloDj7YEslrRi2irA&amp;v=1">
      </script>
      <script type="text/javascript">
         twttr.anywhere(function(twitter) {
                  twitter.hovercards();
         });
      </script>
  
  <!-- End Twitter @Anywhere code -->

<?php wp_head(); ?>

</head>

<body>

<div id="wrapper">

<!-- Begin Mutual Designs Menu -->

<div id="uppermenu">

		<?php /*?>
        <ul class="alignleft">
        	<li>Client <?php wp_loginout(); ?></li>
        </ul>
		<?php */?>
        
        <ul id="nav">
			   
            <?php wp_list_pages('title_li=&sort_column=post_date&sort_order=DESC&exclude=4'); ?>
            <?php if (is_page('Introduction')) { ?>
                <li style="border:none;" class="current_page_item"><a href="<?php echo get_option('home'); ?>">Home</a></li>
            <?php } else { ?>
            	<li style="border:none;"><a href="<?php echo get_option('home'); ?>">Home</a></li>
            <?php } ?>
		</ul>

</div>

<!-- End Mutual Designs Menu -->

<!-- Beging Mutual Designs Header -->

<div id="header">

</div>

<!-- End Mutual Designs Header -->