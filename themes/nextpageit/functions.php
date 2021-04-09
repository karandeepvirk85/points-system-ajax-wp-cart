<?php
/**
 * Functions and definitions
 * Next Page It Theme
 */

// This theme requires WordPress 5.3 or later.
add_action('init', 'includeThemeControllers');
add_action( 'wp_enqueue_scripts', 'themeStyles');
add_action( 'wp_enqueue_scripts', 'themeScripts');
add_action('wp_head', 'setAjaxUrl');
add_action('wp_head','setHomeUrl');
add_action('init', 'registerCartSession');
add_action( 'after_setup_theme', 'nextPageThemeSetUp' );

function includeThemeControllers(){
	include_once(get_template_directory().'/controllers/theme_controller.php');
}

function themeStyles() {
	wp_enqueue_style('dataTable2','https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');	
	wp_enqueue_style('font-awesome','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('bootstrap-style','https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css',false, '1.0', 'all');
	wp_enqueue_style('animate','https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
	wp_enqueue_style('theme-style', get_template_directory_uri().'/style.css');
}

function themeScripts() {
	wp_enqueue_script('jquery-script','https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js');
    wp_enqueue_script('bootstrap-scripts', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', false, '1.0', 'all' );
	wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/scripts/theme-front.js', false, '1.0', 'all' );
	wp_enqueue_script('data-table','https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js');

}

function setAjaxUrl() {
   echo '<script type="text/javascript"> var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}

function setHomeUrl() {
	echo '<script type="text/javascript"> var homeurl = "' .home_url(). '";</script>';
 }

function registerCartSession(){
  if(!session_id()){
    session_start();
  }
}

function nextPageThemeSetUp() {
	load_theme_textdomain( 'twentytwentyone', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support(
		'post-formats',
		array(
			'link',
			'aside',
			'gallery',
			'image',
			'quote',
			'status',
			'video',
			'audio',
			'chat',
		)
	);
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1568, 9999 );
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
			)
		);
	register_nav_menus(
		array(
		'primary-menu' => __( 'Primary Menu' ),
		'secondary-menu' => __( 'Secondary Menu' )
		)
	);
}