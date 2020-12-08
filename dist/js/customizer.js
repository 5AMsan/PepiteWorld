/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
	
	// Links colors
	wp.customize( 'pepite_world_link_color', function( value ) {
		value.bind( function( to ) {
			console.log('bg',to);
			$( 'a, a:visited' ).css( {
				'color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_link_color_hover', function( value ) {
		value.bind( function( to ) {
			$( 'a:hover, a:active' ).css( {
				'color': to
			} );
		} );
	} );

	// Tabs colors
	wp.customize( 'pepite_world_tabs_color_bg_home', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(2)' ).css( {
				'background-color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_txt_home', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(2) .tab-title' ).css( {
				'color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_bg_direction-artistique', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(4)' ).css( {
				'background-color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_txt_direction-artistique', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(4) .tab-title' ).css( {
				'color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_bg_fonderie', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(6)' ).css( {
				'background-color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_txt_fonderie', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(6) .tab-title' ).css( {
				'color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_bg_risographie', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(8)' ).css( {
				'background-color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_txt_risographie', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(8) .tab-title' ).css( {
				'color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_bg_editions', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(10)' ).css( {
				'background-color': to
			} );
		} );
	} );
	wp.customize( 'pepite_world_tabs_color_txt_editions', function( value ) {
		value.bind( function( to ) {
			$( '.pepite-tab-container input[type=radio] + label:nth-child(10) .tab-title' ).css( {
				'color': to
			} );
		} );
	} );
	
	// Tabs border
	wp.customize( 'pepite_world_tabs_border', function( value ) {
		value.bind( function( to ) {
			if (to == 1) {
				if (window.matchMedia("(max-width: 800px)").matches) {
					$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-width: 1px 0 0 0; border-style: solid }</style>" ).appendTo( "head" );
				  } else {
					$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-width: 0 0 0 1px; border-style: solid }</style>" ).appendTo( "head" );
				  }
				
			} else {
				$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-width: 0; }</style>" ).appendTo( "head" );
			}
		} );
	} );
	wp.customize( 'pepite_world_tabs_border_color', function( value ) {
		value.bind( function( to ) {
			$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-color: "+to+"; }</style>" ).appendTo( "head" );
		} );
	} );
	wp.customize( 'pepite_world_tabs_border_radius', function( value ) {
		value.bind( function( to ) {
			if (window.matchMedia("(max-width: 800px)").matches) {
				$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-radius: "+to+"px "+to+"px 0 0;}</style>" ).appendTo( "head" );
			} else {
				$( "<style>.pepite-tab-container input[type=radio]:not(:first-of-type) + label:after { border-radius: "+to+"px 0 0 "+to+"px; }</style>" ).appendTo( "head" );
			}
		} );
	} );

} )( jQuery );
