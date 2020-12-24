<?php
/**
 * Pépite World functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Pépite_World
 */

// Clean up a bit
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	// add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' ); // generates warning function does not exist.
	// add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

if ( ! function_exists( 'pepite_world_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function pepite_world_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Pépite World, use a find and replace
		 * to change 'pepite-world' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'pepite-world', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'pepite-world' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'pepite_world_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'pepite_world_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function pepite_world_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'pepite_world_content_width', 640 );
}
add_action( 'after_setup_theme', 'pepite_world_content_width', 0 );

/** 
 * Manage Ajax page load in template
 */
// Load ajax template if necessary
add_filter("template_include", "pepite_redirect_to_ajax");
function pepite_redirect_to_ajax( $template ) {
    if( !pepite_is_ajax_request() )
        return $template;

    return locate_template('index-ajax.php');
}
// Helper for templates
function pepite_is_ajax_request() {
	//IF HTTP_X_REQUESTED_WITH is equal to xmlhttprequest
	$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0 ? true : false;
	// $is_singular = is_singular('projet') || is_singular('font') || is_singular('edition');
	return $is_ajax;
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pepite_world_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'pepite-world' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'pepite-world' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
// add_action( 'widgets_init', 'pepite_world_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function pepite_world_scripts() {
	wp_enqueue_style( 'pepite-world-style', get_stylesheet_directory_uri()."/dist/css/style.min.css" );

	//wp_enqueue_script( 'pepite-world-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_register_script( 'pepite-world-app', get_template_directory_uri() . '/dist/js/pepite-world.js', array('jquery', 'jquery-ui-draggable'), null, true );
	wp_localize_script( 'pepite-world-app', 'dragimage', array("image-0"=>'empty'));
	wp_enqueue_script( 'pepite-world-app');
	
	// wp_enqueue_script( 'pepite-world-ajax-load', get_template_directory_uri() . '/js/ajax-loader.js', array('jquery', 'jquery-ui-draggable'), null, true );
	
	// Direction Artistique
	// wp_register_script( 'pepite-world-direction-artistique', get_template_directory_uri() . '/dist/js/direction-artistique.js', array('jquery-ui-draggable'), null, true );

	//Glide Slider
	wp_enqueue_script( 'pepite-world-glide', get_template_directory_uri() . '/node_modules/@glidejs/glide/dist/glide.min.js', array(), null, true );
	wp_enqueue_script( 'pepite-world-glide', get_template_directory_uri() . '/js/glide.js', array(), null, true );

	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'pepite_world_scripts' );

// SVG
function pepite_world_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'pepite_world_mime_types');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Pepite World main object
 */
require get_template_directory() . '/inc/pepite-subpages.php';
require get_template_directory() . '/inc/pepite-projet.php';
require get_template_directory() . '/inc/pepite-font.php';
require get_template_directory() . '/inc/pepite-edition.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



function  pepite_world_favicon($url, $size, $blog_id){
	$files = preg_grep('~^fav_[1-9]+\.png$~', scandir( get_stylesheet_directory()."/dist/favicons/"));
	$fav = $files[ rand(2, count($files) + 1) ];
	$url = get_stylesheet_directory_uri() ."/dist/favicons/$fav";
	return $url;
}
add_filter('get_site_icon_url', 'pepite_world_favicon', 10, 3);

/**
 * Navigations
 */

/** Generate anchor nav on posts */
if( !function_exists('pepite_world_internal_nav')){
function pepite_world_internal_nav($content){
	
	//if ( !is_page('risographie') || empty($content) ) return $content;

	global $post, $pepite_world_internal_nav_items;

	$dom = new DOMDocument();
    libxml_use_internal_errors(true);
	$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
	$nodes = $dom->getElementsByTagName('h2');
	
	if($nodes->length < 1)
		return $content;

	$pepite_world_internal_nav_items = [];
    foreach ( $nodes as $node) {
		$anchor = $node->textContent;
		$href = sanitize_title( $anchor );
		if ( $node->setAttribute('id', $href) && stripos($anchor,'question')===false ) {
			array_push (
				$pepite_world_internal_nav_items, 
				sprintf(
					'<a class="button secondary %2$s skip-ajax-nav" href="#%2$s">%1$s</a>',
					$anchor, 
					$href
				)
			);

		}
    }
	$content = $dom->saveHtml();
	$menu = pepite_world_infobar($post->post_name);

	return $menu . $content;
	
}
}
//add_filter('the_content', 'pepite_world_internal_nav');

if( !function_exists('ZZ_pepite_world_nav_edition')){
function ZZ_pepite_world_nav_edition($echo=true){
	$query = new WP_Query( array(
		'post_type'     => 'edition',
		'numberposts'   => -1,

	));
	if( $query->have_posts() ) {
		$nav = array();
		$nav[] = '<nav id="edition-nav" class="secondary">';
		$nav[] = '<ul class="vertical-nav">';

		while( $query->have_posts() ): 
			$query->the_post();
			$id = get_the_id();
			$url = get_the_permalink();
			$nav[] = "<li id=\"item-$id\" ><a href=\"$url\" data-projet=\"$url\"  data-id=\"$id\" >";
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

