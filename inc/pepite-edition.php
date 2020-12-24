<?php

/**
 *  Editions
 */

/** Get published editions for archive page editions **/
if( !function_exists('pepite_world_get_editions')){
function pepite_world_get_editions() {
    $posts = get_posts([
        'post_type'     => 'edition',
        'numberposts'   => -1,
    ]);
    wp_reset_postdata();
    return $posts;
}
}
    
function pepite_world_archive_edition() {

    $content = "<div class=\"editions-set\">";
    
    $editions = pepite_world_get_editions();
    foreach( $editions as $edition ) {
        $image = get_the_post_thumbnail_url($edition->ID) ?: DEFAULT_IMAGE;
        $link = get_the_permalink($edition->ID);
        $format = '<div class="edition-item"><a style="background-image: url(%s);" href="%s"  data-edition="%s" rel="edition"><span>%s</span></a></div>';
        $content .= sprintf ($format, $image, $link, $edition->ID, $edition->post_title );
    }
    wp_reset_postdata();
    
    $content .= "</div>";
    return $content;

}

function pepite_world_edition() {

    global $post;

    // avoid filter loop, re-added at the end
    remove_filter('the_content', 'pepite_world_edition_content_filter');

    // $post_content = apply_filters( 'the_content', get_the_content($post) );
    $post_content = get_the_content($post);
    $content = pepite_world_infobar('edition');
    $content .= pepite_world_gallery_glide();

    // re-enable filter
    add_filter('the_content', 'pepite_world_edition_content_filter');

    // add lightbox and return new content
    return $content . pepite_world_content_lightbox($post_content);
}

function pepite_world_edition_content_filter($content) {
	if ( !pepite_is_ajax_request() || !is_singular('edition') ) return $content;
    
    return pepite_world_edition();
}
add_filter('the_content', 'pepite_world_edition_content_filter');

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


