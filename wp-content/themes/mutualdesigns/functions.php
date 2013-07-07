<?php

automatic_feed_links();

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}

add_action('init', 'portfolio_register');

	function portfolio_register() {
    	$args = array(
        	'label' => __('Portfolio Projects'),
        	'singular_label' => __('Portfolio Project'),
        	'public' => true,
        	'show_ui' => true,
        	'capability_type' => 'post',
        	'hierarchical' => false,
        	'rewrite' => true,
        	'supports' => array('title', 'editor', 'thumbnail')
        );

    	register_post_type( 'portfolio' , $args );
	}

register_taxonomy("types", array("portfolio"), array("hierarchical" => true, "label" => "Types", "singular_label" => "Type", "rewrite" => true));

add_filter("manage_edit-product_columns", "prod_edit_columns");
add_action("manage_posts_custom_column",  "prod_custom_columns");

add_action("admin_init", "admin_init");
add_action('save_post', 'save_details');

function admin_init(){
		add_meta_box("detailsInfo-meta", "Project Details", "meta_options", "portfolio", "side", "low");
	}

function meta_options(){
		global $post;
		$custom = get_post_custom($post->ID);
		$url = $custom["url"][0];
		
?>
	<label>URL:</label><input name="url" value="<?php echo $url; ?>" /> <br /> 
<?php
$testamonial = $custom["testamonial"][0];
?>
	<label>Testamonial:</label><input name="testamonial" value="<?php echo $testamonial; ?>" />
<?php
	}

function save_details(){
	global $post;
	update_post_meta($post->ID, "url", $_POST["url"], "testamonial", $_POST["testamonial"]);
}

?>