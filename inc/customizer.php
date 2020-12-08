<?php
/**
 * Pépite World Theme Customizer
 *
 * @package Pépite_World
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pepite_world_customize_register( $wp_customize ) {
	// Custom sections and settings
	$wp_customize->add_panel( 'pepite_world_panel', array(
		'title' => __( 'Options Pépite World' ),
		'description' => '', // Include html tags such as <p>.
		'priority' => 160, // Mixed with top-level-section hierarchy.
	) );
	$wp_customize->add_section( 'pepite_world_section_visuals' , array(
		'title'      => __( 'Options visuelles', 'pepite-world' ),
		'panel'		=> 'pepite_world_panel'
    ) );
	$wp_customize->add_section( 'pepite_world_section_colors' , array(
		'title'      => __( 'Couleurs', 'pepite-world' ),
		'panel'		=> 'pepite_world_panel'
    ) );
	
	// add visuals settings
	$wp_customize->add_setting(
		'pepite_world_tabs_border', array(
			'default' => true,
			'transport' => 'postMessage',
		)
	);
    $wp_customize->add_control( 'pepite_world_tabs_border', array(
        'label'      => __( 'Bordure pour les onglets', 'pepite-world' ),
        'section'    => 'pepite_world_section_visuals',
        'settings'   => 'pepite_world_tabs_border',
        'type'       => 'checkbox',
        'std'        => '1'
	) );
	$wp_customize->add_setting(
		'pepite_world_tabs_border_color', array(
			'default' => '#000',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pepite_world_tabs_border_color', 
			array('label' => 'Couleur de la bordure', 
			'section' => 'pepite_world_section_visuals',
			'settings' => 'pepite_world_tabs_border_color'),
		)
	);
	$wp_customize->add_setting(
		'pepite_world_tabs_border_radius', array(
			'default' => '5',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control( 'pepite_world_tabs_border_radius', array(
		'type' => 'number',
		'section' => 'pepite_world_section_visuals', // Add a default or your own section
		'label' => __( 'Arrondi de la bordure en pixel' ),
	) );
	
	// link colors
	$wp_customize->add_setting(
		'pepite_world_link_color', array(
			'default' => '#4169e1',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pepite_world_link_color', 
			array('label' => 'Liens', 
			'section' => 'pepite_world_section_colors',
			'settings' => 'pepite_world_link_color'),
		)
	);
	$wp_customize->add_setting(
		'pepite_world_link_color_hover', array(
			'default' => '#800080',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pepite_world_link_color_hover', 
			array('label' => 'Liens au survol', 
			'section' => 'pepite_world_section_colors',
			'settings' => 'pepite_world_link_color_hover'),
		)
	);

    // add the settings and controls for each tab color
    $tab_colors = array(
		array(
			'slug'=>'home', 
			'default_bg' => '#E57373',
			'default_txt' => '#000',
			'label' => 'Accueil'
		),
		array(
			'slug'=>'direction-artistique', 
			'default_bg' => '#81C784',
			'default_txt' => '#000',
			'label' => 'Direction Artistique'
		),
		array(
			'slug'=>'fonderie', 
			'default_bg' => '#64B5F6',
			'default_txt' => '#000',
			'label' => 'Fonderie'
		),
		array(
			'slug'=>'risographie', 
			'default_bg' => '#9575CD',
			'default_txt' => '#000',
			'label' => 'Risographie'
		),
		array(
			'slug'=>'editions', 
			'default_bg' => '#a19ea5',
			'default_txt' => '#000',
			'label' => 'Editions'
		)
	);
	
	foreach( $tab_colors as $i => $tab_color ) {
		
        // SETTINGS
        $wp_customize->add_setting(
            'pepite_world_tabs_color_bg_'.$tab_color['slug'], array(
                'default' => $tab_color['default_bg'],
                'transport' => 'postMessage',
            )
        );
        // CONTROLS
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'bg_'.$tab_color['slug'], 
                array('label' => 'Fond '.$tab_color['label'], 
                'section' => 'pepite_world_section_colors',
                'settings' => 'pepite_world_tabs_color_bg_'.$tab_color['slug']),
            )
		);

		// SETTINGS
        $wp_customize->add_setting(
            'pepite_world_tabs_color_txt_'.$tab_color['slug'], array(
                'default' => $tab_color['default_txt'],
                'transport' => 'postMessage',
            )
        );
        // CONTROLS
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'txt_'.$tab_color['slug'], 
                array('label' => 'Textes '.$tab_color['label'], 
                'section' => 'pepite_world_section_colors',
                'settings' => 'pepite_world_tabs_color_txt_'.$tab_color['slug']),
            )
		);

	}

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'pepite_world_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'pepite_world_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'pepite_world_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function pepite_world_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function pepite_world_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pepite_world_customize_preview_js() {
	wp_enqueue_script( 'pepite-world-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'pepite_world_customize_preview_js' );
