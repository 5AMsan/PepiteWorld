<?php

/**
 *  Register custom post types
 */



// CPT
function edition_post_type() {
  
    $labels = array(
        'name'                  => _x( 'Editions', 'Post Type General Name', 'pepite-world' ),
        'singular_name'         => _x( 'Edition', 'Post Type Singular Name', 'pepite-world' ),
        'menu_name'             => __( 'Editions', 'pepite-world' ),
        'name_admin_bar'        => __( 'Editions', 'pepite-world' ),
        'archives'              => __( 'Editions', 'pepite-world' ),
        'attributes'            => __( 'Item Attributes', 'pepite-world' ),
        'parent_item_colon'     => __( 'Parent Item:', 'pepite-world' ),
        'all_items'             => __( 'All Items', 'pepite-world' ),
        'add_new_item'          => __( 'Add New Item', 'pepite-world' ),
        'add_new'               => __( 'Add Edition', 'pepite-world' ),
        'new_item'              => __( 'New Edition', 'pepite-world' ),
        'edit_item'             => __( 'Edit Edition', 'pepite-world' ),
        'update_item'           => __( 'Update Edition', 'pepite-world' ),
        'view_item'             => __( 'View Edition', 'pepite-world' ),
        'view_items'            => __( 'View Editions', 'pepite-world' ),
        'search_items'          => __( 'Search Edition', 'pepite-world' ),
        'not_found'             => __( 'Not found', 'pepite-world' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'pepite-world' ),
        'featured_image'        => __( 'Featured Image', 'pepite-world' ),
        'set_featured_image'    => __( 'Set featured image', 'pepite-world' ),
        'remove_featured_image' => __( 'Remove featured image', 'pepite-world' ),
        'use_featured_image'    => __( 'Use as featured image', 'pepite-world' ),
        'insert_into_item'      => __( 'Insert into item', 'pepite-world' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'pepite-world' ),
        'items_list'            => __( 'Items list', 'pepite-world' ),
        'items_list_navigation' => __( 'Items list navigation', 'pepite-world' ),
        'filter_items_list'     => __( 'Filter items list', 'pepite-world' ),
    );

    $args = array(
        'label'                 => __( 'Edition', 'pepite-world' ),
        'description'           => __( 'Editions from Pépite', 'pepite-world' ),
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // Important !
        'supports' => array('title', 'thumbnail', 'editor'), // Ne pas oublier editor
        'menu_position' => 33,
        'menu_icon' => 'dashicons-awards',
        "rewrite" => array( "slug" => "editions", "with_front" => true ),
    );

    register_post_type( 'edition', $args );

}
add_action('init', 'edition_post_type' );


