<?php

if( ! function_exists('pepite_main_pages_set')){
function pepite_main_pages_set() {
        global $post, $wp_query;
        // Get main tabs
        $tabs = array( 
                'home' => get_page_id_by_slug( 'home' ),
                'direction-artistique' => 'projet',
                'fonderie' => 'font',
                'risographie' => get_page_id_by_slug( 'risographie' ),
                'editions' => 'edition',
        );
        $tabs = array_filter($tabs);        
        
        $item_fmt =  '<input type="radio" id="item-%5$s" name="pepite-tabbed-nav" %3$s>';
        $item_fmt .= '<label for="item-%5$s" id="post-%5$s" data-link="%4$s" rel="%1$s" >';
        $item_fmt .= '  <p class="tab-title"><a href="%4$s" rel="%1$s">%2$s</a></p>';
        $item_fmt .= '  <div class="content"><div class="content-wrapper" id="%5$s" data-loaded="%7$s">%6$s</div></div>';
        $item_fmt .= '</label>';
        $output = array();
        $output[] = '<div class="pepite-tab-container">';
        
        foreach($tabs as $cue => $tab) {
                
                $is_children = is_post_type_archive($tab) || is_singular($tab) ? true : false;
                $checked = null;
                // check input for current tab visibility
                if ( is_page($cue) || $is_children ) {
                        $checked = "checked";
                } elseif( $cue == 'home' && is_home() ) {
                        $checked = "checked";
                }

                $content = null;

                // load content of top lev object
                switch ($tab) {
                        case 'projet':
                                // archive or single load ?
                                $content = (!is_singular($tab)) ? pepite_world_archive_projet() : pepite_world_projet();
                                break;
                        case 'font':
                                // archive or single load ?
                                $content = (!is_singular($tab)) ? pepite_world_archive_font() : pepite_world_font();
                                break;
                        case 'edition':
                                // archive or single load ?
                                $content = (!is_singular($tab)) ? pepite_world_archive_edition() : pepite_world_edition();
                                break;
                        case 12:
                                global $submenu_post;
                                $submenu_post = 'risographie';
                                $content = apply_filters( 'the_content', get_the_content(null, false, $tab) );
                                break;
                        case 2:
                                global $submenu_post;
                                $submenu_post = 
                                $content = apply_filters( 'the_content', get_the_content(null, false, $tab) ) . pepite_world_privacy_modal();
                                break;
                        default:
                                global $submenu_post;
                                $submenu_post = 
                                $content = apply_filters( 'the_content', get_the_content(null, false, $tab) );
                                break;
                }
               

                 // page 
                if( is_int($tab) ) {
                        $title = get_the_title($tab);
                        $link = get_the_permalink($tab);
                        $loaded = wp_parse_url(get_the_permalink($tab))["path"];
                        $slug = get_post_field('post_name', $tab);
                }
                // archive
                else {
                        $title = get_post_type_object($tab)->labels->archives;
                        $link = get_post_type_archive_link($tab);
                        $loaded = (!is_singular($tab)) ? wp_parse_url(get_post_type_archive_link($tab))["path"] : wp_parse_url(get_the_permalink($post))["path"];
                        $slug = $tab;
                }
                
                $output[] = sprintf ( 
                        $item_fmt, 
                        $slug, 
                        $title,
                        $checked, 
                        $link, 
                        $slug,
                        $content,
                        $loaded,
                );
        }
        
        echo implode( "\r\n", $output );
}
}
add_action( 'pepite_tabbed_layout', 'pepite_main_pages_set' );

if( ! function_exists('get_page_id_by_slug')){
function get_page_id_by_slug($page_slug, $output = OBJECT, $post_type = 'page' ) { 
    global $wpdb; 
    $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) ); 

    return $page ? intval($page) : false;
}
}

add_action('wp_head', 'pepite_set_tab_color', 100);
if( ! function_exists('pepite_set_tab_color')){
function pepite_set_tab_color() {
    ?>
    <style type="text/css" media="screen">
        /* LINKS */
        a, a:visited {
                color: <?php echo get_theme_mod( 'pepite_world_link_color', '#4169e1' ) ?>;
        }
        a:hover, a:active {
                color: <?php echo get_theme_mod( 'pepite_world_link_color_hover', '#800080' ) ?>;
        }
        /* BG */
        .pepite-tab-container input[type=radio] + label:nth-child(2) {
                background-color: <?php echo get_theme_mod( 'pepite_world_tabs_color_bg_home', '#E57373' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(4) {
                background-color: <?php echo get_theme_mod( 'pepite_world_tabs_color_bg_direction-artistique', '#81C784' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(6) {
                background-color: <?php echo get_theme_mod( 'pepite_world_tabs_color_bg_fonderie', '#64B5F6' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(8) {
                background-color: <?php echo get_theme_mod( 'pepite_world_tabs_color_bg_risographie', '#9575CD' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(10) {
                background-color: <?php echo get_theme_mod( 'pepite_world_tabs_color_bg_editions', '#a19ea5' ) ?>;
        }
        /* TITLE */
        .pepite-tab-container input[type=radio] + label:nth-child(2) > * {
                color: <?php echo get_theme_mod( 'pepite_world_tabs_color_txt_home', '#000' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(4) > *{
                color: <?php echo get_theme_mod( 'pepite_world_tabs_color_txt_direction-artistique', '#000' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(6) > * {
                color: <?php echo get_theme_mod( 'pepite_world_tabs_color_txt_fonderie', '#000' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(8) > * {
                color: <?php echo get_theme_mod( 'pepite_world_tabs_color_txt_risographie', '#000' ) ?>;
        }
        .pepite-tab-container input[type=radio] + label:nth-child(10) > * {
                color: <?php echo get_theme_mod( 'pepite_world_tabs_color_txt_editions', '#000' ) ?>;
        }

        <?php if ( get_theme_mod('pepite_world_tabs_border') == 1 ) : ?>
        /* BORDERS */
        .pepite-tab-container input[type=radio]:not(:first-of-type) + label:after {
                border-width: 1px 0 0 0;
                border-style: solid;
                border-color: <?php echo get_theme_mod( 'pepite_world_tabs_border_color', '#000' ) ?>;
        }
        @media screen and (min-width: 50rem) {
                .pepite-tab-container input[type=radio]:not(:first-of-type) + label:after {
                        border-width: 0 0 0 1px;
                        border-style: solid;
                        border-color: <?php echo get_theme_mod( 'pepite_world_tabs_border_color', '#000' ) ?>;
                }
        }
        <?php endif; ?>
        <?php if ( get_theme_mod('pepite_world_tabs_border_radius', '5') > 0 ) : ?>
        /* BORDERS RADIUS */
        .pepite-tab-container input[type=radio]:not(:first-of-type) + label:after {
                border-radius: <?php echo get_theme_mod( 'pepite_world_tabs_border_radius', '5' ) ?>px  <?php echo get_theme_mod( 'pepite_world_tabs_border_radius', '5' ) ?>px 0 0;
        }
        @media screen and (min-width: 50rem) {
                .pepite-tab-container input[type=radio]:not(:first-of-type) + label:after {
                        border-radius: <?php echo get_theme_mod( 'pepite_world_tabs_border_radius', '5' ) ?>px 0 0 <?php echo get_theme_mod( 'pepite_world_tabs_border_radius', '5' ) ?>px;
                }
        }
        <?php endif; ?>


    </style>
    <?php
}
}

/**
 * Remove adminbar on front
 */
add_action('get_header', 'pepite_filter_head');
function pepite_filter_head() {
        remove_action('wp_head', '_admin_bar_bump_cb');
}


/** LOAD content in model for HOME */
function pepite_world_privacy_modal() {
        return pepite_world_modal( get_option( 'wp_page_for_privacy_policy', 0 ) );
}
