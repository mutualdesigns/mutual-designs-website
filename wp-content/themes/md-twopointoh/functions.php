<?php

add_action( 'after_setup_theme', 'md_setup' );

if ( ! function_exists( 'md_setup' ) ):

function md_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => 'Primary Navigation',
		'footer' => 'Footer Navigation'
		
	) );

}
endif;


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

add_action("admin_init", "admin_init");
add_action('save_post', 'save_details');

function admin_init(){
		add_meta_box("detailsInfo-meta", "Project Details", "meta_options", "portfolio", "normal", "low");
	}

function meta_options(){
		global $post;
		$custom = get_post_custom($post->ID);
		$url = $custom["url"][0];
		$screenshot = $custom["screenshot"][0];
		$thumbnail = $custom["thumbnail"][0];
		
?>
	<label>URL:</label><input name="url" value="<?php echo $url; ?>" /> <br /> 
	<label>Screenshot:</label><input name="screenshot" value="<?php echo $screenshot; ?>" /> <br />
    <label>Thumbnail:</label><input name="thumbnail" value="<?php echo $thumbnail; ?>" /> <br />  
<?php
	}

function save_details(){
	global $post;
	update_post_meta($post->ID, "url", $_POST["url"]);
	update_post_meta($post->ID, "screenshot", $_POST["screenshot"]);
	update_post_meta($post->ID, "thumbnail", $_POST["thumbnail"]);
}

?>