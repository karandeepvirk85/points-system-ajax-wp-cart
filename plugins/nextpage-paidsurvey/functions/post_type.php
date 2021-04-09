<?php

if( ! defined( 'ABSPATH' ) ) exit;

function register_cuztom_post_type( $name, $args = array(), $labels = array() ){
	$post_type = new Cuztom_Post_Type( $name, $args, $labels );
	return $post_type;
}

function getPostImage($intPostId, $strSIze){
	$intAttachmentId = get_post_thumbnail_id($intPostId);
	$strImageUrl = wp_get_attachment_image_src($intAttachmentId, $strSIze);
	$strImageUrl = $strImageUrl[0];
	return $strImageUrl;
}