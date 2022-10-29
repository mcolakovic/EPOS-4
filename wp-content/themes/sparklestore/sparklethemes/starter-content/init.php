<?php
    
function chankhe_starter_content_setup(){

    add_theme_support( 'starter-content', array(
        'attachments' => array(
            'featured-image-home' => array(
                'post_title'   => __( 'Featured Image Homepage', 'sparklestore' ),
                'post_content' => __( 'Featured Image Homepage', 'sparklestore' ),
                'file'         => 'assets/default/contact.jpg',
            ),
            'featured-slide1'     => array(
                'post_title' => 'First slide',
                'file'       => 'assets/default/slider1.jpg',
            ),
            'featured-slide2'     => array(
                'post_title' => 'Second slide',
                'file'       => 'assets/default/slider2.jpg',
            ),
            'featured-slide2'     => array(
                'post_title' => 'Third slide',
                'file'       => 'assets/default/slider3.jpg',
            ),
            'post-1'              => array(
                'post_title' => 'Landscape',
                'file'       => 'assets/default/default1.jpg',
            )
        ),

        'posts' => array(
            'home'    => require __DIR__ . '/home.php',
            'contact' => require __DIR__ . '/contact.php',
            'blog' => array(
                'post_type'    => 'page',
                'post_title'   => _x( 'Blog', 'Theme starter content', 'sparklestore' ),
                'post_content' => 'Blog Page content',
            ),
        ),
        
        'options' => array(
            'show_on_front' => 'page',
            'page_on_front' => '{{home}}',
            'page_for_posts' => '{{blog}}',
            // Our Custom
            'blogdescription' => 'Just another WordPress site ',
            
        ),

        'theme_mods'  => array(
            'sparklestore_services_area_settings' => 'on',
            'sparklestore_services_section' => 'enable',
            'sparklestore_quick_services_settings_options' => json_encode( array(
                array(
                    'services_icon' => 'fa fa-truck',
                    'services_title' => __('Free Shipping', 'sparklestore'),
                    'services_subtitle'  => __('Pellentesque habitant morbi tristique senectus et netus et fames ac turpis egestas.', 'sparklestore')
                ),
                
                array(
                    'services_icon' => 'fab fa-cc-apple-pay',
                    'services_title' => __('Secure Payment', 'sparklestore'),
                    'services_subtitle'  => __('Pellentesque habitant morbi tristique senectus et netus et fames ac turpis egestas.', 'sparklestore')
                ),
                array(
                    'services_icon' => 'fas fa-comments',
                    'services_title' => __('24/7 Customer Support', 'sparklestore'),
                    'services_subtitle'  => __('Pellentesque habitant morbi tristique senectus et netus et fames ac turpis egestas.', 'sparklestore')
                ),

            ) ),

            'sparklestore_search_placeholder_text' => esc_html__( 'Search...', 'sparklestore' ),



            'sparklestore_banner_all_sliders' => json_encode( array(
                array(
                      'selectpage' => '-999',
                      'button_text' => '',
                      'button_url' => ''
                    )
                ) ),
            /** foter */
            'sparklestore_footer_widget_area_option' => 'off',
            'sparklestore_footer_social_icon_payment_logo_option' => 'off',


        ),

        'nav_menus' => array(
            'sparkleprimary' => array(
				'name' => __( 'Primary Menu', 'sparklestore' ),
				'items' => array(
					'page_home',
					'page_blog',
					'page_contact'
				),
			),
		),
    ));
}
add_action( 'after_setup_theme', 'chankhe_starter_content_setup' );