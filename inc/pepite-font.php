<?php



/** 
 * Fonts
 */

/** Get published fonts for archive page fonderie **/
if( !function_exists('pepite_world_get_fonts')){
function pepite_world_get_fonts() {
	$posts = get_posts([
		'post_type'     => 'font',
		'numberposts'   => -1,
	]);
	wp_reset_postdata();
	return $posts;
}
}

if( !function_exists('pepite_world_archive_font')){
function pepite_world_archive_font() {
	$sets = pepite_world_get_fonts();
	
	if ($sets) {
        // Add nav
        // $output = pepite_world_infobar('font');
        $output = '';

		$format = '<div class="fontsampler-nav-item" data-fontsampler="%1$s" data-link="%6$s">
		<a href="%6$s" rel="font">Détails <div class="font-title font-%1$s">%2$s</div> <div class="font-author">%3$s <3 %4$s</div> <div class="font-variants">%5$s</div></a></div>';
		
		foreach($sets as $set) {
			$font_id = get_field('font_main', $set->ID);
			$author_id = get_field('font_author', $set->ID);
			$shortcode = do_shortcode("[fontsampler id=$font_id text=\"{$set->post_title}\"]");
			$name = null;
			$fname = null;
			$author = get_userdata($author_id);
			$variants = pepite_world_get_fontsampler_set_variant($font_id);
			if ($author) {
				$name = $author->last_name;
				$fname = $author->first_name;
			}
			$output .= sprintf( 
				$format, 
				$font_id, 
				$shortcode, 
				$set->post_title, 
				$fname." ".$name, 
				$variants.' '._n('Style', 'Styles', $variants, 'pepite-world'), 
				// "/fonderie/font/".$set->post_name //
				get_permalink($set->ID)
			);
        }
        wp_reset_postdata();
		return $output;
    }
    
}
}

function pepite_world_font() {
	global $post;
	$font_id = get_field('font_main', $post->ID);
	remove_filter('the_content', 'pepite_world_font_content_filter');
	$content = pepite_world_infobar('font') . do_shortcode("[fontsampler id=$font_id text=\"{$post->post_title}\"]") . apply_filters( 'the_content', get_the_content($post) );
	add_filter('the_content', 'pepite_world_font_content_filter');

	$lightbox = get_field('texte_download', $post->ID);
	$dl_button = '<a class="button download" target="_blank" href="'.get_field('lien_de_telechargement', $post->ID).'">Download</a>';
	$support_button = '<a class="button support-us" target="_blank" href="'.get_field('lien_de_support', $post->ID).'">Support us</a>';
	$lightbox .= "<footer>$dl_button $support_button</footer>";
	// add lightbox and return new content
	return $content . pepite_world_content_lightbox($lightbox);
}

function pepite_world_font_content_filter($content) {
	if ( !pepite_is_ajax_request() || !is_singular('font') ) return $content;
	$content = pepite_world_font() . $content;
	return $content;
}
add_filter('the_content', 'pepite_world_font_content_filter');

function pepite_world_acf_load_fonts_field_choices( $field ) {
    // reset choices
    $field['choices'] = array();
	$choices = pepite_world_get_fontsampler_headers(false);
    if( is_array($choices) ) {
        foreach( $choices as $choice ) {
            $field['choices'][ $choice['id'] ] = $choice['initial']?:$choice['id'];
        }
        
    }
    // return the field
    return $field;
}
// add_filter('acf/load_field/name=author_fonts', 'pepite_world_acf_load_fonts_field_choices');
add_filter('acf/load_field/name=font_main', 'pepite_world_acf_load_fonts_field_choices');

function pepite_world_add_eot($mime_types){
	$mime_types['eot'] = 'application/vnd.ms-fontobject';
	return $mime_types;
}
add_filter('upload_mimes', 'pepite_world_add_eot', 1, 1);

function pepite_world_get_fontsampler_set_variant($set_id) {
	global $wpdb;
	$sql = 'SELECT COUNT(font_id) as c FROM ' . $wpdb->prefix . 'fontsampler_sets_x_fonts WHERE set_id ='.intval($set_id);
	$variants = $wpdb->get_var	( $sql );
	return $variants;
}

function ZZ__pepite_world_get_fontsampler_sets($echo=true) {
	global $wpdb, $post;
	$sql = 'SELECT ID FROM ' . $wpdb->prefix . 'fontsampler_sets';
	$sets = $wpdb->get_results( $sql, ARRAY_A );
	if ($sets) {
		if ($echo) {
			foreach($sets as $set) {
				echo do_shortcode("[fontsampler id={$set['ID']}]");
			}
		} else {
			return $sets;
		}
	}
	return false;
}

function pepite_world_get_fontsampler_headers($echo=true) {
	global $wpdb;
	$sql = "SELECT {$wpdb->prefix}fontsampler_sets.id, {$wpdb->prefix}fontsampler_settings.initial 
	FROM {$wpdb->prefix}fontsampler_sets
	LEFT JOIN {$wpdb->prefix}fontsampler_settings ON id = set_id";
	// $sql = 'SELECT initial, set_id FROM ' . $wpdb->prefix . 'fontsampler_settings WHERE set_id IS NOT NULL;';
	$sets = $wpdb->get_results( $sql, ARRAY_A );
	if ($sets) {
		if ($echo) {
			foreach($sets as $set) {
				echo do_shortcode("[fontsampler id={$set['id']}]");
			}
		} else {
			return $sets;
		}
	}
	return false;
}

function ZZ_pepite_world_get_font_author($set_id) {
	$args = array(
		'meta_query' => array(
			array(
				'key' => 'author_fonts',
				'compare' => 'EXISTS'
			)
		)
	);
	$users = get_users($args); 
	foreach( $users as $user) {
		$metas = get_user_meta( $user->ID, 'author_fonts', true );
		if( $metas ) foreach( $metas as $meta ) {
			if (intval($meta) == $set_id) return get_userdata($user->ID);
		}

	}
	return false;
}

function pepite_world_fontsampler_enqueue() {
	wp_enqueue_style('fontsampler-css', plugins_url().'/fontsampler/css/fontsampler-css.css');
	wp_enqueue_script('fontsampler-js', plugins_url().'/fontsampler/js/fontsampler.js', array('jquery'), false, false);
}
add_action('wp_enqueue_scripts', 'pepite_world_fontsampler_enqueue');

function ZZ_pepite_world_get_post_gallery($post=false) {
	return;
}

/** END FontSampler */

// CPT
function font_post_type() {
  
    $labels = array(
        'name'                  => _x( 'Fonts', 'Post Type General Name', 'pepite-world' ),
        'singular_name'         => _x( 'Font', 'Post Type Singular Name', 'pepite-world' ),
        'menu_name'             => __( 'Fonts', 'pepite-world' ),
        'name_admin_bar'        => __( 'Fonts', 'pepite-world' ),
        'archives'              => __( 'Fonderie', 'pepite-world' ),
        'attributes'            => __( 'Item Attributes', 'pepite-world' ),
        'parent_item_colon'     => __( 'Parent Item:', 'pepite-world' ),
        'all_items'             => __( 'All Items', 'pepite-world' ),
        'add_new_item'          => __( 'Add New Item', 'pepite-world' ),
        'add_new'               => __( 'Add Font', 'pepite-world' ),
        'new_item'              => __( 'New Font', 'pepite-world' ),
        'edit_item'             => __( 'Edit Font', 'pepite-world' ),
        'update_item'           => __( 'Update Font', 'pepite-world' ),
        'view_item'             => __( 'View Font', 'pepite-world' ),
        'view_items'            => __( 'View Fonts', 'pepite-world' ),
        'search_items'          => __( 'Search Font', 'pepite-world' ),
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
        'label'                 => __( 'Font', 'pepite-world' ),
        'description'           => __( 'Fonts from Pépite', 'pepite-world' ),
        'labels'                => $labels,
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true, // Important !
        'supports' => array('title', 'thumbnail', 'editor', 'custom-fields'), // Ne pas oublier editor
        'menu_position' => 32,
        'menu_icon' => 'dashicons-awards',
        "rewrite" => array( "slug" => "fonderie", "with_front" => true ),
    );

    register_post_type( 'font', $args );

}
add_action('init', 'font_post_type' );