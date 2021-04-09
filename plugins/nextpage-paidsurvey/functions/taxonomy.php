<?php

if( ! defined( 'ABSPATH' ) ) exit;

function register_cuztom_taxonomy( $name, $post_type, $args = array(), $labels = array() ){
	$taxonomy = new Cuztom_Taxonomy( $name, $post_type, $args, $labels );
	return $taxonomy;
}

function get_cuztom_term_meta( $term, $taxonomy, $key = null){
    if( empty( $taxonomy ) || empty( $term ) ) return false;
    if( ! is_numeric( $term ) )
    {
    	$term = get_term_by( 'slug', $term, $taxonomy );
    	$term = $term->term_id;
    }
    $meta = get_option( 'term_meta_' . $taxonomy . '_' . $term );
    if( $key ) if( ! empty( $meta[$key] ) ) return $meta[$key]; else return '';
    return $meta;
}

function the_cuztom_term_meta( $term, $taxonomy, $key = null){
    if( empty( $term ) || empty( $taxonomy ) ) return false;
    echo get_cuztom_term_meta( $term, $taxonomy, $key );
}
