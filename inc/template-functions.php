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
function pepite_world_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'pepite_world_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pepite_world_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'pepite_world_pingback_header');

function pepite_logo_font() {
	$directory = get_stylesheet_directory() ."/dist/fonts";
	$filecount = 0;
	$files = glob("$directory/Pepite-Logo?.otf");
	if ($files){
		$filecount = count($files);
	}
	$num = rand(1, $filecount);
	?>
	<style type="text/css" media="screen">
		/* Logo Font */
		#post-home .tab-title {
        	font-family: 'pepite-logo-<?php echo $num ?>';
    	}
    </style>
	<?php
}
add_action('wp_head', 'pepite_logo_font');

function pepite_world_risographie_content_filter($content) {
	global $submenu_post;
	if ( !$submenu_post || $submenu_post!=='risographie' ) return $content;

	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
	$nodes = $dom->getElementsByTagName('h2');
	
	if($nodes->length < 1)
		return pepite_world_infobar('risographie') . $content;

	$pepite_world_internal_nav_items = [];
	foreach ( $nodes as $node) {
		$anchor = $node->textContent;
		$href = sanitize_title( $anchor );
		if ( $node->setAttribute('id', $href) && stripos($anchor,'question')===false ) {
			array_push (
				$pepite_world_internal_nav_items, 
				sprintf(
					'<a class="button secondary %2$s skip-ajax-nav" href="#%2$s" rel="risographie">%1$s</a>',
					$anchor, 
					$href
				)
			);

		}
	}
	$content = $dom->saveHTML();
	return pepite_world_infobar('risographie', implode("<span>→</span>", $pepite_world_internal_nav_items) ) . $content;
}
add_filter('the_content', 'pepite_world_risographie_content_filter');

if( !function_exists('pepite_world_infobar')){
function pepite_world_infobar($post_format='default', $content=null){
		global $post, $submenu_post; //, $pepite_world_internal_nav_items;

		$format = [
			'default'   	=> '<div class="infobar no-nav"></div>',
			'risographie'   => '<div class="infobar"> %s <a href="mailto:message@pepite.world?subject=Contact Pepite World" class="button contact" rel="mailto">Contact</a> </div>',
			'font'  		=> '<div class="infobar"> <a class="button support-us" href="%3$s" target="_blank">Support us</a> <button class="download" data-modal-open="modal-%1$s" data-lighbox="content-%1$s">Download</button> <a class="button specimen" target="_blank" href="%2$s">Spécimen</a> <button class="back" data-url="/fonderie/"></button> </div>',
			'projet'    	=> '<div class="infobar"> <button data-modal-open="modal-%1$s" data-lighbox="content-%1$s">Informations</button> <div class="slider-counter diamond"><span data-slide-current="1"></span> / <span data-slide-total="-"></span></div> <button class="back" data-url="/direction-artistique/"></button> </div>',
			'edition'    	=> '<div class="infobar"> <button data-modal-open="modal-%1$s" data-lighbox="content-%1$s">Informations</button> <div class="slider-counter diamond"><span data-slide-current="1"></span> / <span data-slide-total="-"></span></div>  %2$s <button class="back" data-url="/editions/"></button> </div>',
		];
		// is archive ?
		if (is_archive()) {
			$infobar = sprintf( $format['default'] );
		}
		elseif ( is_archive($post_format) && array_key_exists($post_format, $format) ) {
			$infobar = sprintf( $format[$post_format], $post_format );
		}
		elseif ( $post_format=='risographie' && array_key_exists($post_format, $format) ) {
			//$submenu_post = 
			$infobar = sprintf( $format[$post_format], $content);
		}
		elseif ( $post && array_key_exists($post_format, $format) && $post_format=='edition' ) {
			$infobar = sprintf( $format[$post_format], $post->ID, do_shortcode(get_field("bouton_shopify", $post->ID)) );
		}
		elseif ( $post && array_key_exists($post_format, $format) && $post_format=='font' ) {
			$infobar = sprintf( $format[$post_format], $post->ID, get_field('lien_de_specimen', $post->ID), get_field('lien_de_support', $post->ID));
		}
		elseif ( $post && array_key_exists($post_format, $format) ) {
			$infobar = sprintf( $format[$post_format], $post->ID);
		}
		else {
			$infobar = sprintf( $format['default']);
		}		
	
		return $infobar;
}
}

function pepite_world_gallery_glide()
{

	global $post;

	if (!have_rows('galerie', $post->ID)) return false;

	$items = array();
	while (have_rows('galerie', $post->ID)) {
		the_row();
		$is_video = @get_sub_field('galerie_item_is_video',)[0] == 'Oui' ? true : false;
		$video = pepite_world_filter_video(get_sub_field('galerie_item_video', false, false)); //get_sub_field('galerie_item_video', false, false); //
		$image = wp_get_attachment_image(get_sub_field('galerie_item_image'), 'full');
		$items[] = $is_video ? $video : $image;
	}

	// Create gallery markup

	$gallery = '<div class="glide-wrapper">';
	$gallery .= '<div class="glide">';
	$gallery .= '<div data-glide-el="track" class="glide__track">';
	$gallery .= '<ul class="glide__slides">';
	$gallery .= '<li class="glide__slide">';
	$gallery .= implode("</li>\r\n<li class=\"glide__slide\">", $items);
	$gallery .= '</li>';
	$gallery .= '</ul>';
	$gallery .= '</div>';
	$gallery .= '<div class="glide__arrows" data-glide-el="controls">';
	$gallery .= '<button class="glide__arrow glide__arrow--left" data-glide-dir="<">Prev</button>';
	$gallery .= '<button class="glide__arrow glide__arrow--right" data-glide-dir=">">Next</button>';
	$gallery .= '</div>';
	$gallery .= '</div>';
	$gallery .= '</div>';

	return $gallery;
}


if (!function_exists('pepite_world_content_lightbox')) {
	function pepite_world_content_lightbox($content)
	{
		global $post;

		$lightbox_fmts = [];
		$lightbox_fmts[] = '<div id="modal-%1$s" aria-hidden="true" class="modal">';
		$lightbox_fmts[] = '    <div tabindex="-1" data-micromodal-close>';
		$lightbox_fmts[] = '        <div role="dialog" aria-modal="true" aria-labelledby="modal-%1$s-title">';
		$lightbox_fmts[] = '            <header class="modal-header">';
		$lightbox_fmts[] = '                <h1 id="modal-%1$s-title" class="modal-title">%3$s</h1>';
		$lightbox_fmts[] = '                <!--<button aria-label="Close modal" data-micromodal-close>X</button>-->';
		$lightbox_fmts[] = '            </header>';
		$lightbox_fmts[] = '            <div id="modal-%1$s-content">';
		$lightbox_fmts[] = '                %2$s';
		$lightbox_fmts[] = '            </div>';
		$lightbox_fmts[] = '        </div>';
		$lightbox_fmts[] = '    </div>';
		$lightbox_fmts[] = '</div><!-- #modal-%s -->';
		$lightbox_fmts[] = '<div id="modal-overlay" data-micromodal-close></div><!-- #modal-ovrlay -->';
		$lightbox_format = implode("\r\n", $lightbox_fmts);

		return sprintf($lightbox_format, $post->ID, $content, get_the_title($post));
	}
}

if (!function_exists('pepite_world_modal')) {
	function pepite_world_modal($post_id)
	{

		$cgupost = get_post($post_id);

		$lightbox_fmts = [];
		$lightbox_fmts[] = '<div id="modal-%1$s" aria-hidden="true" class="modal %4$s">';
		$lightbox_fmts[] = '    <div tabindex="-1" data-micromodal-close>';
		$lightbox_fmts[] = '        <div role="dialog" aria-modal="true" aria-labelledby="modal-%1$s-title">';
		$lightbox_fmts[] = '            <header class="modal-header">';
		$lightbox_fmts[] = '                <p id="modal-%1$s-title" class="modal-title">%3$s</p>';
		$lightbox_fmts[] = '                <!--<button aria-label="Close modal" data-micromodal-close>X</button>-->';
		$lightbox_fmts[] = '            </header>';
		$lightbox_fmts[] = '            <div id="modal-%1$s-content">';
		$lightbox_fmts[] = '                %2$s';
		$lightbox_fmts[] = '            </div>';
		$lightbox_fmts[] = '        </div>';
		$lightbox_fmts[] = '    </div>';
		$lightbox_fmts[] = '</div><!-- #modal-%s -->';
		$lightbox_fmts[] = '<div id="modal-overlay" data-micromodal-close></div><!-- #modal-overlay -->';
		$lightbox_format = implode("\r\n", $lightbox_fmts);

		return sprintf($lightbox_format, $cgupost->ID, $cgupost->post_content, get_the_title($cgupost), $cgupost->post_name);
	}
}

if (!function_exists('pepite_world_footer_loader')) {
	function pepite_world_footer_loader($content)
	{
		echo '<div class="loader-wrapper"><div class="loader"></div></div>';
	}
	add_action('wp_footer', 'pepite_world_footer_loader');
}

if (!function_exists('pepite_world_filter_video')) {
	function pepite_world_filter_video($content)
	{

		if (!$content) return;

		preg_match('%vimeo\.com/(\d+)/?%', $content, $matches);
		if (!$matches || !$matches[1]) return $content;

		$id = $matches[1];
		$content = "<div style=\"padding:64.52% 0 0 0;position:relative;\">
		<iframe src=\"https://player.vimeo.com/video/{$id}\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" frameborder=\"0\" allow=\"autoplay\" ></iframe>
		</div>";
		$content .= "<script src=\"https://player.vimeo.com/api/player.js\"></script>";

		return $content;
	}
}

// ACF Blocks
add_action('acf/init', 'pepite_world_slider_block');
function pepite_world_slider_block()
{
	// Check function exists.
	if (function_exists('acf_register_block_type')) {
		// register a testimonial block.
		acf_register_block_type(array(
			'name'              => 'slider',
			'title'             => __('Slider'),
			'description'       => __('Bloc galerie.'),
			'render_template'   => 'template-parts/blocks/slider.php',
			'category'          => 'Pépite',
			'icon'              => 'admin-comments',
			'keywords'          => array('slider', 'galerie'),
		));
	}
}
