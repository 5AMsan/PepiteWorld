<?php

define('DEFAULT_IMAGE', get_stylesheet_directory_uri().'/dist/img/default.png');

/** Get published projects for archive page direction-artistique **/
if( !function_exists('pepite_world_get_projets')){
function pepite_world_get_projets() {
    $posts = get_posts([
        'post_type'     => 'projet',
        'numberposts'   => -1,
    ]);
    wp_reset_postdata();
    return $posts;
}
}

if( !function_exists('pepite_world_projets_content')){
function pepite_world_archive_projet() {
    
    $content = pepite_world_infobar('projet');
    $content .= pepite_world_nav_projet();
    
    $projets = pepite_world_get_projets();
    foreach( $projets as $projet ) {
        $image = get_the_post_thumbnail_url($projet->ID) ?: DEFAULT_IMAGE;
        $link = get_the_permalink($projet->ID);
        $format = '<img src="%s" width="180" height="180" class="draggable" data-projet="%s" data-id="%s" />';
        $content .= sprintf ($format, $image, $link, $projet->ID );
    }
    wp_reset_postdata();
    
    // -a-d-d--l-i-g-h-t-b-o-x- and return new content
    return $content; //. pepite_world_content_lightbox($post_content);
}
// add_filter('the_content', 'pepite_world_projets_content');
}

if( !function_exists('pepite_world_projet')){
function pepite_world_projet($content=null) {
    
    global $post;

    // avoid filter loop, re-added at the end
    remove_filter('the_content', 'pepite_world_projet_content_filter');

    // $post_content = apply_filters( 'the_content', get_the_content($post) );
    $post_content = get_the_content($post);
    $content = pepite_world_infobar('projet');
    $content .= pepite_world_nav_projet();

    $content .= pepite_world_gallery_glide();

    // re-enable filter
    add_filter('the_content', 'pepite_world_projet_content_filter');

    // add lightbox and return new content
    return $content . pepite_world_content_lightbox($post_content);
}
}

function pepite_world_projet_content_filter($content) {
    
    if ( !pepite_is_ajax_request() || !is_singular('projet') ) return $content;
    
    return pepite_world_projet();
}
add_filter('the_content', 'pepite_world_projet_content_filter');

if( !function_exists('pepite_world_nav_projet')){
function pepite_world_nav_projet($echo=false){
    $query = new WP_Query( array(
        'post_type'     => 'projet',
        'numberposts'   => -1,

    ));
    
    if( $query->have_posts() ) {
        $nav = array();
        $nav[] = '<nav id="projet-nav" class="secondary">';
        $nav[] = '<ul class="vertical-nav">';

        while( $query->have_posts() ): 
            $query->the_post();
            $id = get_the_id();
            $url = get_the_permalink();
            $nav[] = "<li id=\"item-$id\" ><a href=\"$url\" data-link=\"$url\"  rel=\"projet\" data-id=\"$id\" >";
            $nav[] = get_the_title();
            $nav[] = "</a></li>";
        endwhile;

        $nav[] = '</ul>';
        $nav[] = '</nav>';
    }
    wp_reset_postdata();
    
    if ($nav) {
        if( $echo ) echo implode("\r\n", $nav);  
        else return implode("\r\n", $nav);
    }

    return;

}
}

// CPT
function projet_post_type() {
  
    $labels = array(
        'name'                  => _x( 'Projets', 'Post Type General Name', 'pepite-world' ),
        'singular_name'         => _x( 'Projet', 'Post Type Singular Name', 'pepite-world' ),
        'menu_name'             => __( 'Projets', 'pepite-world' ),
        'name_admin_bar'        => __( 'Projets', 'pepite-world' ),
        'archives'              => __( 'Direction Artistique', 'pepite-world' ),
        'attributes'            => __( 'Item Attributes', 'pepite-world' ),
        'parent_item_colon'     => __( 'Parent Item:', 'pepite-world' ),
        'all_items'             => __( 'All Items', 'pepite-world' ),
        'add_new_item'          => __( 'Add New Item', 'pepite-world' ),
        'add_new'               => __( 'Add Projet', 'pepite-world' ),
        'new_item'              => __( 'New Projet', 'pepite-world' ),
        'edit_item'             => __( 'Edit Projet', 'pepite-world' ),
        'update_item'           => __( 'Update Projet', 'pepite-world' ),
        'view_item'             => __( 'View Projet', 'pepite-world' ),
        'view_items'            => __( 'View Projets', 'pepite-world' ),
        'search_items'          => __( 'Search Projet', 'pepite-world' ),
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
        'label'                 => __( 'Projet', 'pepite-world' ),
        'description'           => __( 'Projets from Pépite', 'pepite-world' ),
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // Important !
        'supports' => array('title', 'thumbnail', 'editor'), // Ne pas oublier editor
        'menu_position' => 31,
        'menu_icon' => 'dashicons-awards',
        'rewrite' => array( 'slug' => 'direction-artistique', "with_front" => true ),
    );

    register_post_type( 'projet', $args );

}
add_action('init', 'projet_post_type' );


