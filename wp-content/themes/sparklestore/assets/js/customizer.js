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
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	/**
	 * Services Section
	*/
    wp.customize("sparklestore_services_area_settings", function(value) {
        value.bind(function(to) {
            if( to == 'enable') {
               $(".our-features-box").css("display", "block");
            } else {
                $(".our-features-box").css("display", "none");
            }
        } );
    });

    	wp.customize( 'sparklestore_services_icon_one', function( value )  {
    	    value.bind( function( to )  {          
    	       $( '.feature-box-div .one' ).find( 'span' ).attr( 'class', 'fa '+ to );
    	    } );
    	} );

    	wp.customize("sparklestore_service_title_one", function(value) {
    	    value.bind(function(to) {
    	        $( ".one .content > h3" ).text( to );
    	    } );
    	});

    	wp.customize("sparklestore_service_desc_one", function(value) {
    	    value.bind(function(to) {
    	        $( ".one .content > p" ).text( to );
    	    } );
    	});

    	wp.customize( 'sparklestore_services_icon_two', function( value )  {
    	    value.bind( function( to )  {          
    	       $( '.feature-box-div .two' ).find( 'span' ).attr( 'class', 'fa '+ to );
    	    } );
    	} );

    	wp.customize("sparklestore_service_title_two", function(value) {
    	    value.bind(function(to) {
    	        $( ".two .content > h3" ).text( to );
    	    } );
    	});

    	wp.customize("sparklestore_service_desc_two", function(value) {
    	    value.bind(function(to) {
    	        $( ".two .content > p" ).text( to );
    	    } );
    	});

    	wp.customize( 'sparklestore_services_icon_three', function( value )  {
    	    value.bind( function( to )  {          
    	       $( '.feature-box-div .three' ).find( 'span' ).attr( 'class', 'fa '+ to );
    	    } );
    	} );

    	wp.customize("sparklestore_service_title_three", function(value) {
    	    value.bind(function(to) {
    	        $( ".three .content > h3" ).text( to );
    	    } );
    	});

    	wp.customize("sparklestore_service_desc_three", function(value) {
    	    value.bind(function(to) {
    	        $( ".three .content > p" ).text( to );
    	    } );
    	});

    	wp.customize( 'sparklestore_services_icon_four', function( value )  {
    	    value.bind( function( to )  {          
    	       $( '.feature-box-div .last' ).find( 'span' ).attr( 'class', 'fa '+ to );
    	    } );
    	} );

    	wp.customize("sparklestore_service_title_four", function(value) {
    	    value.bind(function(to) {
    	        $( ".last .content > h3" ).text( to );
    	    } );
    	});

    	wp.customize("sparklestore_service_desc_four", function(value) {
    	    value.bind(function(to) {
    	        $( ".last .content > p" ).text( to );
    	    } );
    	});

    /**
     * Footer Social Icon
    */
	    wp.customize("sparklestore_social_facebook", function(value) {
	        value.bind(function(to) {
	        	alert(to);
	            $( ".fb > a" ).attr('href', to );
	        } );
	    });


	/**
	 * Custom live preview web layout
	*/
	/*wp.customize("sparklestore_web_page_layout", function( value ) {
		alert(to);
        value.bind( function( to ) {
            if( to == 'fulllayout' && $('body').hasClass('fulllayout') ) {
                $('body').removeClass('fulllayout');
                $('body').addClass('fulllayout');
            } else {
                $('body').removeClass('boxed');
                $('body').addClass('boxed');
            }
        } );
    });*/


} )( jQuery );
