<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Pépite_World
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function pepite_world_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'pepite_world_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pepite_world_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'pepite_world_pingback_header' );


function pepite_world_gallery_glide() {

	global $post;

	if ( !have_rows('galerie', $post->ID)) return false;

	$items = array();
    while( have_rows('galerie', $post->ID) ) {
        the_row();
        $is_video = @get_sub_field('galerie_item_is_video', )[0] == 'Oui' ? true:false;
        $video = pepite_world_filter_video(get_sub_field('galerie_item_video', false, false)); //get_sub_field('galerie_item_video', false, false); //
        $image = wp_get_attachment_image(get_sub_field('galerie_item_image'), 'large');
        $items[] = $is_video ? $video : $image;
    }

	// Create gallery markup

    $gallery = '<div class="glide">';
    $gallery .= '<div data-glide-el="track" class="glide__track">';
    $gallery .= '<ul class="glide__slides">';
    $gallery .= '<li class="glide__slide">';
    $gallery .= implode("</li>\r\n<li class=\"glide__slide\">", $items) ;
    $gallery .= '</li>';
    $gallery .= '</ul>';
    $gallery .= '</div>';
    $gallery .= '<div class="glide__arrows" data-glide-el="controls">';
    $gallery .= '<button class="glide__arrow glide__arrow--left" data-glide-dir="<">Prev</button>';
    $gallery .= '<button class="glide__arrow glide__arrow--right" data-glide-dir=">">Next</button>';
    $gallery .= '</div>';
    $gallery .= '</div>';
	
	return $gallery;
}


if( !function_exists('pepite_world_content_lightbox')){
function pepite_world_content_lightbox($content){
	global $post;

	$lightbox_fmts = [];
	$lightbox_fmts[] = '<div id="modal-%1$s" aria-hidden="true" class="modal">';
	$lightbox_fmts[] = '    <div tabindex="-1" data-modal-close>';
	$lightbox_fmts[] = '        <div role="dialog" aria-modal="true" aria-labelledby="modal-%1$s-title">';
	$lightbox_fmts[] = '            <header class="modal-header">';
	$lightbox_fmts[] = '                <p id="modal-%1$s-title" class="modal-title">%3$s</p>';
	$lightbox_fmts[] = '                <!--<button aria-label="Close modal" data-modal-close>X</button>-->';
	$lightbox_fmts[] = '            </header>';
	$lightbox_fmts[] = '            <div id="modal-%1$s-content">';
	$lightbox_fmts[] = '                %2$s';
	$lightbox_fmts[] = '            </div>';
	$lightbox_fmts[] = '        </div>';
	$lightbox_fmts[] = '    </div>';
	$lightbox_fmts[] = '</div><!-- #modal-%s -->';
	$lightbox_fmts[] = '<div id="modal-overlay" data-modal-close></div><!-- #modal-ovrlay -->';
	$lightbox_format = implode("\r\n", $lightbox_fmts);

	return sprintf($lightbox_format, $post->ID, $content, get_the_title($post));
}
}

if( !function_exists('pepite_world_footer_loader')){
function pepite_world_footer_loader($content){
	echo '<div class="loader-wrapper"><div class="loader"></div></div>';
}
add_action('wp_footer', 'pepite_world_footer_loader');
}

if( !function_exists('pepite_world_filter_video')){
function pepite_world_filter_video($content){
	
	if (!$content) return;
	
	preg_match('%vimeo\.com/(\d+)/?%', $content, $matches);
	if (!$matches || !$matches[1]) return $content;

	$id = $matches[1];
	$content = "<div style=\"padding:64.52% 0 0 0;position:relative;\">
		<iframe src=\"https://player.vimeo.com/video/{$id}?autoplay=1&loop=1&title=0&byline=0&portrait=0\" 
			style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" frameborder=\"0\" allow=\"autoplay; fullscreen\" allowfullscreen>
		</iframe>
	</div>
	<script src=\"https://player.vimeo.com/api/player.js\"></script>;";

	return $content;    
}
}

// ACF Blocks
add_action('acf/init', 'pepite_world_slider_block');
function pepite_world_slider_block() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {
        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'slider',
            'title'             => __('Slider'),
            'description'       => __('Bloc galerie.'),
            'render_template'   => 'template-parts/blocks/slider.php',
            'category'          => 'Pépite',
            'icon'              => 'admin-comments',
            'keywords'          => array( 'slider', 'galerie' ),
        ));
    }
}