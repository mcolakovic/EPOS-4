<?php
/**
 * Sparkle Store Theme Customizer.
 *
 * @package Sparklestore
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sparklestore_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    $wp_customize->get_section('static_front_page' )->priority = 2;
    $wp_customize->get_section('static_front_page' )->description = '';
    $wp_customize->remove_control("page_for_posts");

  /**
   * List All Pages
  */
  $slider_pages = array();
  $slider_pages_obj = get_pages();
  $slider_pages[''] = esc_html__('Select Slider Page','sparklestore');
  foreach ($slider_pages_obj as $page) {
    $slider_pages[$page->ID] = $page->post_title;
  }


  // List All Category
  $categories = get_categories();
  $blog_cat = array();

  foreach ($categories as $category) {
      $blog_cat[$category->term_id] = $category->name;
  }

    $sparklestorepro_features = '<ul class="upsell-features">
        <li>' . esc_html__( "20+ One Click Pre-defined demos" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Drag & Drop Header Builder" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Unlimited Color Options" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Advanced Top Header Setting" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Search Options (with category, Ajax)" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "GDPR Compliance & Cookies Consent" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Included Maintenance Mode" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Slider Type and Layout Options" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "27+ Custom Elementor Block" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "24+ Custom Widgets" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Remove Footer Credit Text" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Breadcrumb Layout and Option" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Website layout (Fullwidth or Boxed)" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "4+ advanced blog Layout" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Offers/Deals Section" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "WooCommerce Compatible" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Fully Multilingual and Translation ready" , "sparklestore" ) . '</li>
        <li>' . esc_html__( "Fully RTL ready" , "sparklestore" ) . '</li>
    </ul>';
    
    /**
     * Important Link
    */
    $wp_customize->add_section('sparklestore_implink_link_section',array(
        'title'       => esc_html__( 'Pro Theme Features', 'sparklestore' ),
        'priority'      => 1
    ));

      $wp_customize->add_setting('sparklestore_pro_theme_features', array(
          'title' => esc_html__('Pro Theme Features', 'sparklestore'),
          'sanitize_callback' => 'sparklestore_text_sanitize',
          'priority'      => 1
      ));

      $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize, 'sparklestore_pro_theme_features', array(
          'settings'    => 'sparklestore_pro_theme_features',
          'section'   => 'sparklestore_implink_link_section',
          'description' => $sparklestorepro_features,
      )));



      $wp_customize->add_setting('sparklestore_implink_link_options', array(
          'title' => esc_html__('Important Links', 'sparklestore'),
          'sanitize_callback' => 'sanitize_text_field',
          'priority'      => 2
      ));

      $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize, 'sparklestore_implink_link_options', array(
          'settings'    => 'sparklestore_implink_link_options',
          'section'   => 'sparklestore_implink_link_section',
          'description' => '<a class="implink" href="http://docs.sparklewpthemes.com/sparklestore/" target="_blank">'.esc_html__('Documentation', 'sparklestore').'</a><a class="implink" href="http://demo.sparklewpthemes.com/sparklestore/demos/" target="_blank">'.esc_html__('Live Demo', 'sparklestore').'</a><a class="implink" href="http://sparklewpthemes.com/support" target="_blank">'.esc_html__('Support Forum', 'sparklestore').'</a><a class="implink" href="https://www.facebook.com/sparklewpthemes" target="_blank">'.esc_html__('Like Us in Facebook', 'sparklestore').'</a>',
      )));



      $wp_customize->add_setting( 'sparklestore_rate_us', array(
          'title' => esc_html__('Rate / Review', 'sparklestore'),
          'sanitize_callback' => 'sparklestore_text_sanitize'
      ));

      $wp_customize->add_control( new Sparklestore_theme_Info_Text( $wp_customize, 'sparklestore_rate_us', array(
            'settings'    => 'sparklestore_rate_us',
            'section'   => 'sparklestore_implink_link_section',
            'description' => sprintf( __( 'Please do rate our theme if you liked it %1$s', 'sparklestore'), '<a class="implink" href="https://wordpress.org/support/theme/sparklestore/reviews/?filter=5" target="_blank">'.esc_html__('Rate/Review','sparklestore').'</a>' ),
          )
      ));

      $wp_customize->add_setting( 'sparklestore_setup_instruction', array(
          'title' => esc_html__('Instruction Setup Home Page', 'sparklestore'),
          'sanitize_callback' => 'sparklestore_text_sanitize'
      ));

      
    /**
     * General Settings Panel
    */
    $wp_customize->add_panel('sparklestore_general_settings', array(
       'capabitity' => 'edit_theme_options',
       'priority' => 5,
       'title' => esc_html__('General Settings', 'sparklestore')
    ));

        $wp_customize->get_section('title_tagline')->panel = 'sparklestore_general_settings';
        $wp_customize->get_section('title_tagline' )->priority = 1;

        $wp_customize->get_section('header_image')->panel = 'sparklestore_general_settings';
        $wp_customize->get_section('header_image' )->priority = 3;

        $wp_customize->get_section('background_image')->panel = 'sparklestore_general_settings';
        $wp_customize->get_section('header_image' )->priority = 4;

        $wp_customize->register_section_type('Sparkle_Store_Upgrade_Section');

        /**
         * Website Layout Section
        */
        $wp_customize->add_section( 'sparklestore_web_page_layout', array(
            'title'           => esc_html__('Website Layout', 'sparklestore'),
            'panel'           => 'sparklestore_general_settings'
        ));

          $wp_customize->add_setting('sparklestore_web_page_layout_options', array(
              'default' => 'disable',
              'capability' => 'edit_theme_options',
              'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize',
              //'transport' => 'postMessage'
          ));

          $wp_customize->add_control('sparklestore_web_page_layout_options', array(
              'type' => 'radio',
              'label' => esc_html__('Enable / Disable Top Header', 'sparklestore'),
              'section' => 'sparklestore_web_page_layout',
              'settings' => 'sparklestore_web_page_layout_options',
              'choices' => array(
                'enable' => esc_html__('Boxed Layout', 'sparklestore'),
                'disable' => esc_html__('Full Width Layout', 'sparklestore')
              )
          ));

          $wp_customize->add_section(new Sparkle_Store_Upgrade_Section($wp_customize, 'sparklestore_general_settings_upgrade_section', array(
            'title' => esc_html__('More Sections on Premium', 'sparklestore'),
            'panel' => 'sparklestore_general_settings',
            'priority' => 1000,
            'options' => array(
                esc_html__('- Preloader Section', 'sparklestore'),
                esc_html__('- General Options Section', 'sparklestore'),
                esc_html__('- Admin Controls Section', 'sparklestore'),
                esc_html__('- Default Image Section', 'sparklestore'),
                esc_html__('------------------------', 'sparklestore'),
                esc_html__('- Elementor Pagebuilder Compatible. All the above sections can be created with Elementor Page Builder or Customizer whichever you like.', 'sparklestore'),
            )
        )));

    /**
     * Main Header Panel
    */
    $wp_customize->add_panel( 'sparklestore_header_settings_panel', array(
        'priority'       => 6,
        'title'          => esc_html__( 'Main Header Settings', 'sparklestore' ),
    ));


        /**
         * Top Header Settings
        */
        $wp_customize->add_section(
            'sparklestore_top_header_section',
            array(
                'title'     => esc_html__( 'Top Header Settings', 'sparklestore' ),
                'panel'     => 'sparklestore_header_settings_panel',
                'priority'  => 1,
            )
        );

         /**
         * Enable/Disable Top Header Options
         */
         $wp_customize->add_setting( 
             'sparklestore_top_header_section_options', 

             array(
             'sanitize_callback' => 'sparklestore_sanitize_on_off',
             'default' => 'on'
           ) 
         );

         $wp_customize->add_control( new Sparklestore_Switch_Control( 
            $wp_customize, 
             'sparklestore_top_header_section_options', 

               array(
                  'settings'    => 'sparklestore_top_header_section_options',
                  'section'   => 'sparklestore_top_header_section',
                  'label'     => esc_html__( 'Top Header Section', 'sparklestore' ),
                  'on_off_label'  => array(
                    'on'  => esc_html__( 'Enable', 'sparklestore' ),
                    'off' => esc_html__( 'Disable', 'sparklestore' )
                  ) 
               ) 
            ) 
         );

         $leftside_options = array(
            'topmenu'           => esc_html__( 'Top Nav Menu', 'sparklestore' ),
            'quickinfo'         => esc_html__( 'Quick Contact Information', 'sparklestore' ),
            'socialicon'        => esc_html__( 'Social Media Link', 'sparklestore' )
         );

         $wp_customize->add_setting(
            'sparklestore_top_header_leftside_options',

            array(
                'default'           => 'quickinfo',
                'sanitize_callback' => 'sparklestore_select_type_sanitize',
            )       
         );

         $wp_customize->add_control(
            'sparklestore_top_header_leftside_options',
            array(
                'type'          => 'select',
                'label'         => esc_html__( 'Top Header Leftside Options', 'sparklestore' ),
                'section'       => 'sparklestore_top_header_section',
                'choices'       => $leftside_options
            )
         );


         $rightside_options = array(
            'none'              => esc_html__( 'None', 'sparklestore' ),
            'topmenu'           => esc_html__( 'Top Nav Menu', 'sparklestore' ),
            'socialicon'        => esc_html__( 'Social Media Link', 'sparklestore' ),
            'ecommerceitem'     => esc_html__( 'eCommerce Items ( Cart, My Account, Wishlist )', 'sparklestore' )
         );

        $wp_customize->add_setting(
            'sparklestore_top_header_rightside_options',

            array(
               'default'           => 'ecommerceitem',
               'sanitize_callback' => 'sparklestore_select_type_sanitize',
            )       
        );

         $wp_customize->add_control(
            'sparklestore_top_header_rightside_options',
               array(
                  'type'          => 'select',
                  'label'         => esc_html__( 'Top Header Rightside Options', 'sparklestore' ),
                  'section'       => 'sparklestore_top_header_section',
                  'choices'       => $rightside_options
               )
         );

         $wp_customize->add_setting('sparklestore_top_header_section_upgrade_text', array(
              'sanitize_callback' => 'sparklestore_text_sanitize'
          ));

          $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_top_header_section_upgrade_text', array(
              'section' => 'sparklestore_top_header_section',
              'label' => esc_html__('For more styling,', 'sparklestore'),
              'choices' => array(
                  esc_html__('Adjust Top Header Height', 'sparklestore'),
                  esc_html__('Customize Margin and Padding', 'sparklestore'),
                  esc_html__('Advanced Background Customization Options', 'sparklestore'),
                  esc_html__('Customize Border', 'sparklestore'),
                  esc_html__('Also Available Box Shadow Customization', 'sparklestore'),
              ),
              'priority' => 100
          )));

        /**
         * Main Header Settings Option
         */
         $wp_customize->add_section(
            'sparklestore_header_option_section',
            array(
               'title'     => esc_html__( 'Main Header Settings', 'sparklestore' ),
               'panel'     => 'sparklestore_header_settings_panel',
               'priority'  => 2,
            )
         );

         $wp_customize->add_setting(
            'sparklestore_search_options',

            array(
               'default'           => 'advancesearch',
               'sanitize_callback' => 'sparklestore_search_options_sanitize',
            )       
         );

         $wp_customize->add_control(
            'sparklestore_search_options',
            array(
               'type'          => 'radio',
               'label'         => esc_html__( 'Choose Search Options', 'sparklestore' ),
               'section'       => 'sparklestore_header_option_section',
               'choices'       => array(
                  'normalsearch'      => esc_html__( 'Normal Search', 'sparklestore' ),
                  'advancesearch'     => esc_html__( 'Advance Search ( With Category )', 'sparklestore' )
               ),
            )
         );


         /**
          * Text field for search placeholder caption
         */
         $wp_customize->add_setting(
            'sparklestore_search_placeholder_text',

            array(
               'default'    => esc_html__( 'Product Search...', 'sparklestore' ),
               'sanitize_callback' => 'sanitize_text_field'
            )
         );

         $wp_customize->add_control(
            'sparklestore_search_placeholder_text',

            array(
                'type'      => 'text',
                'label'     => esc_html__( 'Enter the Search Box Placeholder Text', 'sparklestore' ),
                'section'   => 'sparklestore_header_option_section',
            )
         );

         $wp_customize->add_setting('sparklestore_header_option_section_upgrade_text', array(
              'sanitize_callback' => 'sparklestore_text_sanitize'
          ));

          $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_header_option_section_upgrade_text', array(
              'section' => 'sparklestore_header_option_section',
              'label' => esc_html__('For more styling,', 'sparklestore'),
              'choices' => array(
                  esc_html__('Three Different Types of Background', 'sparklestore'),
                  esc_html__('Select Background Color', 'sparklestore'),
                  esc_html__('Customize Margin and Padding', 'sparklestore'),
                  esc_html__('Customize Border Style and Radius', 'sparklestore'),
                  esc_html__('Also Customize Box Shadow', 'sparklestore'),
              ),
              'priority' => 100
          )));


        /**
         * Main Header Settings Option
        */
        $wp_customize->add_section(
            'sparklestore_vertical_menu_section',

           array(
             'title'     => esc_html__( 'Vertical Menu Settings', 'sparklestore' ),
             'panel'     => 'sparklestore_header_settings_panel',
             'priority'  => 3,
           )
        );

        /**
         * Enable/Disable Options
        */
        $wp_customize->add_setting( 
            'sparklestore_vertical_menu_options', 

           array(
             'sanitize_callback' => 'sparklestore_sanitize_on_off',
             'default' => 'on'
           ) 
        );

        $wp_customize->add_control( new Sparklestore_Switch_Control( 
           $wp_customize, 
                'sparklestore_vertical_menu_options', 

              array(
                 'section'       => 'sparklestore_vertical_menu_section',
                 'label'         => esc_html__( 'Vertical Menu', 'sparklestore' ),
                 'description'   => esc_html__( 'Enable/Disable option for vertical menu', 'sparklestore' ),
                 'on_off_label'  => array(
                     'on'  => esc_html__( 'Enable', 'sparklestore' ),
                     'off' => esc_html__( 'Disable', 'sparklestore' )
                 )   
              ) 
           ) 
        );


        /**
         * Text field for Vertical Menu Show All Menu Text
        */
        $wp_customize->add_setting(
           'sparklestore_vertical_menu_show_all_menu',

           array(
              'default'    => esc_html__( 'More Categories', 'sparklestore' ),
              'sanitize_callback' => 'sanitize_text_field'
           )
        );

        $wp_customize->add_control(
           'sparklestore_vertical_menu_show_all_menu',

           array(
              'type'      => 'text',
              'label'     => esc_html__( 'Vertical Menu Show All Menu Text', 'sparklestore' ),
              'section'   => 'sparklestore_vertical_menu_section',
           )
        );


        /**
         * Text field for Vertical Menu Button Close Text
        */
        $wp_customize->add_setting(
           'sparklestore_vertical_menu_show_all_menu_close',

           array(
             'default'    => esc_html__( 'Close', 'sparklestore' ),
             'sanitize_callback' => 'sanitize_text_field'
           )
        );

        $wp_customize->add_control(
           'sparklestore_vertical_menu_show_all_menu_close',

           array(
             'type'      => 'text',
             'label'     => esc_html__( 'Vertical Menu Button Close Text', 'sparklestore' ),
             'section'   => 'sparklestore_vertical_menu_section',
           )
        );


        /**
         * Text field for Visible Vertical Menu Items
        */
        $wp_customize->add_setting(
           'sparklestore_vertical_menu_display_itmes',

           array(
             'default'    => 10,
             'sanitize_callback' => 'absint'
           )
        );

        $wp_customize->add_control(
           'sparklestore_vertical_menu_display_itmes',

           array(
              'type'      => 'number',
              'label'     => esc_html__( 'Visible Vertical Menu Items', 'sparklestore' ),
              'section'   => 'sparklestore_vertical_menu_section',
           )
        );

        $wp_customize->add_setting('sparklestore_vertical_menu_section_upgrade_text', array(
            'sanitize_callback' => 'sparklestore_text_sanitize'
        ));

        $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_vertical_menu_section_upgrade_text', array(
            'section' => 'sparklestore_vertical_menu_section',
            'label' => esc_html__('For more styling,', 'sparklestore'),
            'choices' => array(
                esc_html__('Change Vertical Heading Title Background Color', 'sparklestore'),
                esc_html__('Change Vertical Heading Title Color', 'sparklestore'),
                esc_html__('Customize Vertical Menu Background Color', 'sparklestore'),
                esc_html__('Customize Vertical Menu Items Color', 'sparklestore'),
                esc_html__('Customize Menu Item Background and Item Text Color on Hover', 'sparklestore'),
            ),
            'priority' => 100
        )));

         /**
          * Quick Contact Information Settings 
         */
         $wp_customize->add_section( 'sparklestore_header_quickinfo', array(
            'priority'       => 5,
            'title'          => esc_html__( 'Quick Contact Information', 'sparklestore' ),
            'panel'     => 'sparklestore_header_settings_panel'
         ));
            
            $wp_customize->add_setting('sparklestore_email_title', array(
                'default' => '',
                'sanitize_callback' => 'sanitize_email',  // done
            ));
            
            $wp_customize->add_control('sparklestore_email_title',array(
                'type' => 'text',
                'label' => esc_html__('Email Address', 'sparklestore'),
                'section' => 'sparklestore_header_quickinfo',
                'setting' => 'sparklestore_email_title',
            ));
            
            $wp_customize->selective_refresh->add_partial( 'sparklestore_email_title', array(
                'selector'        => '.footerservices .services_icon',
            ) );
            
            $wp_customize->add_setting('sparklestore_phone_number', array(
                'default' => '',
                'sanitize_callback' => 'sparklestore_text_sanitize',  // done
            ));
            
            $wp_customize->add_control('sparklestore_phone_number',array(
                'type' => 'text',
                'label' => esc_html__('Phone Number', 'sparklestore'),
                'section' => 'sparklestore_header_quickinfo',
                'setting' => 'sparklestore_phone_number',
            ));
            
            $wp_customize->add_setting('sparklestore_map_address', array(
                'default' => '',
                'sanitize_callback' => 'sparklestore_text_sanitize',  // done
            ));
            
            $wp_customize->add_control('sparklestore_map_address',array(
                'type' => 'text',
                'label' => esc_html__('Address', 'sparklestore'),
                'section' => 'sparklestore_header_quickinfo',
                'setting' => 'sparklestore_map_address',
            ));
            
            $wp_customize->add_setting('sparklestore_start_open_time', array(
                'default' => '',
                'sanitize_callback' => 'sparklestore_text_sanitize',  // done
            ));
            
            $wp_customize->add_control('sparklestore_start_open_time',array(
                'type' => 'text',
                'label' => esc_html__('Opening Time', 'sparklestore'),
                'section' => 'sparklestore_header_quickinfo',
                'setting' => 'sparklestore_start_open_time',
            ));

          $wp_customize->add_setting('sparklestore_header_quickinfo_upgrade_text', array(
              'sanitize_callback' => 'sparklestore_text_sanitize'
          ));

          $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_header_quickinfo_upgrade_text', array(
              'section' => 'sparklestore_header_quickinfo',
              'label' => esc_html__('For more settings,', 'sparklestore'),
              'choices' => array(
                  esc_html__('Select icon for the controls', 'sparklestore'),
                  esc_html__('Select color for text and icons', 'sparklestore'),
              ),
              'priority' => 100
          )));

          $wp_customize->add_section(new Sparkle_Store_Upgrade_Section($wp_customize, 'sparklestore_header_settings_panel_upgrade_section', array(
              'title' => esc_html__('More Sections on Premium', 'sparklestore'),
              'panel' => 'sparklestore_header_settings_panel',
              'priority' => 1000,
              'options' => array(
                  esc_html__('- General Header Settings Section', 'sparklestore'),
                  esc_html__('- Header Bottom Section', 'sparklestore'),
                  esc_html__('- Primary Menu Section', 'sparklestore'),
                  esc_html__('- Secondary Menu Section', 'sparklestore'),
                  esc_html__('- Account(Login/Register) Section', 'sparklestore'),
                  esc_html__('- Cart and Wishlist Controls Section', 'sparklestore'),
                  esc_html__('- Notice Controls Section', 'sparklestore'),
                  esc_html__('- Custom HTML Section', 'sparklestore'),
                  esc_html__('- Search Settings Options', 'sparklestore'),
                  esc_html__('- Mobile Menu Icon and Sidebar Customization', 'sparklestore'),
                  esc_html__('------------------------', 'sparklestore'),
                  esc_html__('- Elementor Pagebuilder Compatible. All the above sections can be created with Elementor Page Builder or Customizer whichever you like.', 'sparklestore'),
              )
          )));

      /**
       * WooCommerce Panel
       *
       * @since 1.0.0
       */
      $wp_customize->add_panel(
          'woocommerce',
          array(
              'priority'       => 6,
              'title'          => esc_html__( 'WooCommerce Settings', 'sparklestore' ),
          )
      );

      /**
       * Register the radio image control class as a JS control type.
      */
      $wp_customize->register_control_type( 'Sparklestore_Customize_Control_Radio_Image' );

        if ( class_exists( 'WooCommerce' ) ) {
            
            /**
             * WooCommerce Category/Archive Page Layout Settings
             *
             * @since 1.0.0
            */
            $wp_customize->get_section('woocommerce_product_catalog')->title = esc_html__( 'Shop & Category Page Settings', 'sparklestore' );
            $wp_customize->get_section('woocommerce_product_catalog' )->priority = 1;    

            /**
             * Image Radio field for woocommerce archive/category sidebar
             *
             * @since 1.0.0
             */
            $wp_customize->add_setting(
                'sparklestore_woocommerce_products_page_layout',

                array(
                    'default'           => 'rightsidebar',
                    'sanitize_callback' => 'sanitize_key',
                )
            );

            $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
                $wp_customize,
                'sparklestore_woocommerce_products_page_layout',

                    array(
                        'label'    => esc_html__( 'WooCommerce Archive/Category Page', 'sparklestore' ),
                        'description' => esc_html__( 'Choose sidebar from available layouts', 'sparklestore' ),
                        'section'  => 'woocommerce_product_catalog',
                        'priority' => 1,
                        'choices'  => array(
                                'leftsidebar' => array(
                                    'label' => esc_html__( 'Left Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/left-sidebar.png'
                                ),
                                'rightsidebar' => array(
                                    'label' => esc_html__( 'Right Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/right-sidebar.png'
                                ),
                                'nosidebar' => array(
                                    'label' => esc_html__( 'No Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/no-sidebar.png'
                                )
                        )
                    )
                )
            );

            /**
             * WooCommerce Single Page Layout Settings
             *
             * @since 1.0.0
            */

            $wp_customize->add_section(
                'sparklestore_woo_single_settings_section',

                array(
                    'title'     => esc_html__( 'Single Product Page Settings', 'sparklestore' ),
                    'panel'     => 'woocommerce',
                    'priority'  => 2,
                )
            );      

            /**
             * Image Radio field for woocommerce archive/category sidebar
             *
             * @since 1.0.0
             */
            $wp_customize->add_setting(
                'sparklestore_woocommerce_single_products_page_layout',

                array(
                    'default'           => 'rightsidebar',
                    'sanitize_callback' => 'sanitize_key',
                )
            );

            $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
                $wp_customize,
                'sparklestore_woocommerce_single_products_page_layout',

                    array(
                        'label'    => esc_html__( 'WooCommerce Single Product Page', 'sparklestore' ),
                        'description' => esc_html__( 'Choose sidebar from available layouts', 'sparklestore' ),
                        'section'  => 'sparklestore_woo_single_settings_section',
                        'choices'  => array(
                                'leftsidebar' => array(
                                    'label' => esc_html__( 'Left Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/left-sidebar.png'
                                ),
                                'rightsidebar' => array(
                                    'label' => esc_html__( 'Right Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/right-sidebar.png'
                                ),
                                'nosidebar' => array(
                                    'label' => esc_html__( 'No Sidebar', 'sparklestore' ),
                                    'url'   => '%s/assets/images/no-sidebar.png'
                                )
                        )
                    )
                )
            );

            /**
             * Text field for related product section title
             *
             * @since 1.0.0
             */
            $wp_customize->add_setting(
                'sparklestore_single_related_product_title',
                array(
                    'default'    => esc_html__( 'Related Products', 'sparklestore' ),
                    'sanitize_callback' => 'sanitize_text_field'
                )
            );

            $wp_customize->add_control(
                'sparklestore_single_related_product_title',

                array(
                    'type'      => 'text',
                    'label'     => esc_html__( 'Enter Related Product Title', 'sparklestore' ),
                    'section'   => 'sparklestore_woo_single_settings_section',
                )
            );


            /**
             * Number field for related product section
             *
             * @since 1.0.0
             */
            $wp_customize->add_setting(
                'sparklestore_single_num_related_product',
                array(
                    'default'    => 6,
                    'sanitize_callback' => 'absint'
                )
            );

            $wp_customize->add_control(
                'sparklestore_single_num_related_product',

                array(
                    'type'      => 'number',
                    'label'     => esc_html__( 'Display Number Related Product', 'sparklestore' ),
                    'section'   => 'sparklestore_woo_single_settings_section',
                )
            );


            /**
             * Number field for Upsells product section
             *
             * @since 1.0.0
             */
            $wp_customize->add_setting(
                'sparklestore_single_num_upsells_product',
                array(
                    'default'    => 6,
                    'sanitize_callback' => 'absint'
                )
            );

            $wp_customize->add_control(
                'sparklestore_single_num_upsells_product',

                array(
                    'type'      => 'number',
                    'label'     => esc_html__( 'Display Number Upsells Product', 'sparklestore' ),
                    'section'   => 'sparklestore_woo_single_settings_section',
                )
            );

        }


      /**
       * Theme Primary Color Options
      */
      $wp_customize->get_section('colors')->title = esc_html__( 'Theme Colors Settings', 'sparklestore' );
      $wp_customize->get_section('colors' )->priority = 8;

      $wp_customize->add_setting('colors_upgrade_text', array(
          'sanitize_callback' => 'sparklestore_text_sanitize'
      ));

      $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'colors_upgrade_text', array(
          'section' => 'colors',
          'label' => esc_html__('For more settings,', 'sparklestore'),
          'choices' => array(
              esc_html__('Change Content Area Background Color', 'sparklestore'),
              esc_html__('Change Widget Background Color', 'sparklestore'),
          ),
          'priority' => 100
      )));

      $wp_customize->add_setting('sparklestore_primary_theme_color_options', array(
          'default'     => '#003772',
          'sanitize_callback' => 'sanitize_hex_color',
      )); 

      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sparklestore_primary_theme_color_options', array(
          'label'      => esc_html__( 'Primary Color', 'sparklestore' ),
          'section'    => 'colors',
      )));


      $wp_customize->add_setting('sparklestore_secondary_theme_color_options', array(
          'default'     => '#f33c3c',
          'sanitize_callback' => 'sanitize_hex_color',
      )); 

      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sparklestore_secondary_theme_color_options', array(
          'label'      => esc_html__( 'Secondary Color', 'sparklestore' ),
          'section'    => 'colors',
      ))); 


    /**
     * Banner Slider
    */
      $wp_customize->add_section( 'sparklestore_banner_slider', array(
        'title'           => esc_html__('Slider Settings Options', 'sparklestore'),
        'priority'        => 9,
      ));


        $sliderlayout = array(
            'fullslider'         => esc_html__( 'Full Slider', 'sparklestore' ),
            'sliderpromo'        => esc_html__( 'Slider With Promo Images', 'sparklestore' )
        );

            $wp_customize->add_setting(
                'sparklestore_slider_layout_options',

                array(
                    'default'           => 'fullslider',
                    'sanitize_callback' => 'sparklestore_select_type_sanitize',
                )       
            );

            $wp_customize->add_control(
                'sparklestore_slider_layout_options',
                array(
                    'type'          => 'select',
                    'label'         => esc_html__( 'Slider Layout Settings', 'sparklestore' ),
                    'section'       => 'sparklestore_banner_slider',
                    'choices'       => $sliderlayout
                )
            );

          if ( ! function_exists( 'sparklestore_slider_type_options' ) ) {
              function sparklestore_slider_type_options(){
                  if(esc_attr(get_theme_mod('sparklestore_slider_layout_options','fullslider')) =='sliderpromo'){
                      return true;
                  }else{
                      return false;
                  }
              }
          }

      /**
       * Enable/Disable Options
      */
      $wp_customize->add_setting( 
          'sparklestore_slider_options', 

         array(
           'sanitize_callback' => 'sparklestore_sanitize_on_off',
           'default' => 'on'
         ) 
      );

      $wp_customize->add_control( new Sparklestore_Switch_Control( 
         $wp_customize, 
              'sparklestore_slider_options', 

            array(
               'section'       => 'sparklestore_banner_slider',
               'label'         => esc_html__( 'Enable/Disable Section', 'sparklestore' ),
               'on_off_label'  => array(
                   'on'  => esc_html__( 'Enable', 'sparklestore' ),
                   'off' => esc_html__( 'Disable', 'sparklestore' )
               )   
            ) 
         ) 
      );

      $wp_customize->add_setting( 'sparklestore_banner_all_sliders', array(
        'sanitize_callback' => 'sparklestore_sanitize_repeater',
        'default' => json_encode( array(
          array(
                'selectpage' => '' ,
                'button_text' => '',
                'button_url' => ''
              )
          ) )        
        ) );

      $wp_customize->add_control( new Sparklestore_Repeater_Controler( $wp_customize, 'sparklestore_banner_all_sliders', array(
        'label'   => __('Slider Settings Area','sparklestore'),
        'section' => 'sparklestore_banner_slider',
        'settings' => 'sparklestore_banner_all_sliders',
        'sparklestore_box_label' => __('Slider Settings Options','sparklestore'),
        'sparklestore_box_add_control' => __('Add New Slider','sparklestore'),
      ),
      array(
        'selectpage' => array(
          'type'        => 'select',
          'label'       => __( 'Select Slider Page', 'sparklestore' ),
          'options'   => $slider_pages
        ),
        'button_text' => array(
          'type'        => 'text',
          'label'       => __( 'Enter Button Text', 'sparklestore' ),
          'default'   => ''
        ),
        'button_url' => array(
          'type'        => 'text',
          'label'       => __( 'Enter Button Url', 'sparklestore' ),
          'default'   => ''
        )
      )
    ) 
  );


    $wp_customize->add_setting(
        'sparklestore_banner_promo_one', 

        array(
            'sanitize_callback' => 'absint'
        )
    );

    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control(
        $wp_customize, 
        'sparklestore_banner_promo_one', 
            array(
               'label' => esc_html__('Slider Promo Image One', 'sparklestore'),
               'section' => 'sparklestore_banner_slider',
                'width' => 365,
                'height' => 280,
               'active_callback' => 'sparklestore_slider_type_options',
            )
        )
    );


    $wp_customize->add_setting(
        'sparklestore_banner_promo_one_url', 

        array(     
            'default' => '',
            'sanitize_callback' => 'esc_url_raw' //done
        )
    );

    $wp_customize->add_control(
        'sparklestore_banner_promo_one_url', 

        array(
            'type' => 'url',
            'label' => esc_html__('Custom Promo Link', 'sparklestore'),
            'section' => 'sparklestore_banner_slider',
            'active_callback' => 'sparklestore_slider_type_options',
        )
    );


    $wp_customize->add_setting(
        'sparklestore_banner_promo_two', 

        array(
            'sanitize_callback' => 'absint'
        )
    );

    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control(
        $wp_customize, 
        'sparklestore_banner_promo_two', 
            array(
               'label' => esc_html__('Slider Promo Image Two', 'sparklestore'),
               'section' => 'sparklestore_banner_slider',
               'width' => 365,
               'height' => 280,
               'active_callback' => 'sparklestore_slider_type_options',
            )
        )
    );


    $wp_customize->add_setting(
        'sparklestore_banner_promo_two_url', 

        array(     
            'default' => '',
            'sanitize_callback' => 'esc_url_raw' //done
        )
    );

    $wp_customize->add_control(
        'sparklestore_banner_promo_two_url', 

        array(
            'type' => 'url',
            'label' => esc_html__('Custom Promo Link', 'sparklestore'),
            'section' => 'sparklestore_banner_slider',
            'active_callback' => 'sparklestore_slider_type_options',
        )
    );
    
    $wp_customize->add_setting( 
        'sparklestore_banner_promo_style', 

        array(
            'default'           => 'right',
            'sanitize_callback' => 'sparklestore_select_type_sanitize'
        ) 
    );
    
    $wp_customize->add_control( 
        'sparklestore_banner_promo_style', 

        array(
            'type' => 'select',
            'label' => esc_html__( 'Position', 'sparklestore' ),
            'section' => 'sparklestore_banner_slider',
            'choices' => array(
                'right' => esc_html__('Right', 'sparklestore'),
                'left' => esc_html__('Left', 'sparklestore'),
            )
        ) 
    );

    $wp_customize->selective_refresh->add_partial( 'sparklestore_banner_all_sliders', array(
        'selector'        => '.slides .sparklestore-caption',
    ));

    $wp_customize->add_setting('sparklestore_banner_slider_upgrade_text', array(
        'sanitize_callback' => 'sparklestore_text_sanitize'
    ));

    $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_banner_slider_upgrade_text', array(
        'section' => 'sparklestore_banner_slider',
        'label' => esc_html__('For more settings,', 'sparklestore'),
        'choices' => array(
            esc_html__('Five Different Types of Slider', 'sparklestore'),
            esc_html__('Advanced Slider Included', 'sparklestore'),
            esc_html__('Multiple Slider Items', 'sparklestore'),
            esc_html__('Numerous Controls in the Slider Items', 'sparklestore'),
            esc_html__('Four Different Slider Layout Options', 'sparklestore'),
            esc_html__('Enable/Disable Slider Arrow/Dots/Loop', 'sparklestore'),
            esc_html__('Enable/Disable Autoplay', 'sparklestore'),
            esc_html__('Enable/Disable Mouse Drag', 'sparklestore'),
            esc_html__('Change Background Overlay Color', 'sparklestore'),
            esc_html__('Change Caption Title and Sub-Title Color', 'sparklestore'),
            esc_html__('Change Caption Button Color', 'sparklestore'),
            esc_html__('Customize Margin and Padding', 'sparklestore'),
            esc_html__('Adjust Slider Height', 'sparklestore'),
             esc_html__('Customize Bottom Seperator and Bottom Seperator Color', 'sparklestore'),
            esc_html__('Adjust Seperator Height', 'sparklestore'),
        ),
        'priority' => 100
    )));


/**
 * Home 1 - Full Width Section
*/
$sparklestore_home_section = $wp_customize->get_section( 'sidebar-widgets-sparklemainwidgetarea' );
if ( ! empty( $sparklestore_home_section ) ) {
    $sparklestore_home_section->panel = '';
    $sparklestore_home_section->title = esc_html__( 'Sparkle: Main Widget Area', 'sparklestore' );
    $sparklestore_home_section->priority = 10;
}


/**
 * Breadcrumbs Settings
*/
$wp_customize->add_section('sparklestore_breadcrumbs_normal_page_section', array(
    'priority' => 11,
    'title' => esc_html__('Breadcrumbs Settings', 'sparklestore'),
 ));

    $wp_customize->add_setting('sparklestore_breadcrumbs_normal_page_background_image', array(
       'default' => '',
       'capability' => 'edit_theme_options',
       'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'sparklestore_breadcrumbs_normal_page_background_image', array(
       'label' => esc_html__('Breadcrumbs Background Image', 'sparklestore'),
       'section' => 'sparklestore_breadcrumbs_normal_page_section',
       'setting' => 'sparklestore_breadcrumbs_normal_page_background_image'
      )));

    $wp_customize->add_setting('sparklestore_breadcrumbs_normal_page_section_upgrade_text', array(
        'sanitize_callback' => 'sparklestore_text_sanitize'
    ));

    $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_breadcrumbs_normal_page_section_upgrade_text', array(
        'section' => 'sparklestore_breadcrumbs_normal_page_section',
        'label' => esc_html__('For more settings,', 'sparklestore'),
        'choices' => array(
            esc_html__('Enable/Disable Breadcrumbs Section', 'sparklestore'),
            esc_html__('Select Breadcrumb Layout', 'sparklestore'),
            esc_html__('Select Breadcrumb Alignment', 'sparklestore'),
            esc_html__('Show/Hide Title', 'sparklestore'),
            esc_html__('Show/Hide Breadcrumbs Menu', 'sparklestore'),
            esc_html__('Customize Margin and Padding', 'sparklestore'),
            esc_html__('Show/Hide Breadcrumbs Menu', 'sparklestore'),
            esc_html__('Customize Breadcrumb Position', 'sparklestore'),
            esc_html__('Select Background Type', 'sparklestore'),
            esc_html__('Select Background Color', 'sparklestore'),
            esc_html__('Select Background Image', 'sparklestore'),
            esc_html__('More CSS Controls for Background Image', 'sparklestore'),
            esc_html__('Change Breadcrumbs Title and Text Color', 'sparklestore'),
            esc_html__('Change Breadcrumbs Anchor and Hover Color', 'sparklestore'),
        ),
        'priority' => 100
    )));

/**
 * Services Section
*/
	$wp_customize->add_section( 'sparklestore_services_area', array(
		'title'           => esc_html__('Quick Services Settings', 'sparklestore'),
		'priority'        => 12,
  ));

      /**
       * Enable/Disable Options
      */
      $wp_customize->add_setting( 
          'sparklestore_services_area_settings', 

         array(
           'sanitize_callback' => 'sparklestore_sanitize_on_off',
           'default' => 'off'
         ) 
      );

      $wp_customize->add_control( new Sparklestore_Switch_Control( 
         $wp_customize, 
              'sparklestore_services_area_settings', 

            array(
               'section'       => 'sparklestore_services_area',
               'label'         => esc_html__( 'Enable/Disable Section', 'sparklestore' ),
               'on_off_label'  => array(
                   'on'  => esc_html__( 'Enable', 'sparklestore' ),
                   'off' => esc_html__( 'Disable', 'sparklestore' )
               )   
            ) 
         ) 
      );

    	$wp_customize->add_setting('sparklestore_services_section', array(
          'default' => 'disable',
          'sanitize_callback' => 'sparklestore_radio_enable_disable_sanitize'  //done
    	));

    	$wp_customize->add_control('sparklestore_services_section', array(
    		'type' => 'radio',
    		'label' => esc_html__('Manage Services Area Location', 'sparklestore'),
    		'section' => 'sparklestore_services_area',
    		'settings' => 'sparklestore_services_section',
    		'description' => esc_html__('Options to Manage Service Area Below the Header or Abote the Footer Area', 'sparklestore'),
    		'choices' => array(
             'enable' => esc_html__('Below the Header', 'sparklestore'),
             'disable' => esc_html__('Abover the Footer', 'sparklestore')
          )
    	));


      /**
       * Services Settings Options
       *
       * @since 1.0.0
       */
      $wp_customize->add_setting( 
          'sparklestore_quick_services_settings_options', 

          array(
              'sanitize_callback' => 'sparklestore_sanitize_repeater',
              'default' => json_encode( array(
                  array(
                      'services_icon' => 'fa fa-truck',
                      'services_title' => '',
                      'services_subtitle'  => ''
                  )
              ) )        
          )
      );

      $wp_customize->add_control( new Sparklestore_Repeater_Controler( 
          $wp_customize, 
              'sparklestore_quick_services_settings_options', 

              array(
                  'label'   => esc_html__('More Services Settings','sparklestore'),
                  'section' => 'sparklestore_services_area',
                  'sparklestore_box_label' => esc_html__('Services Settings','sparklestore'),
                  'sparklestore_box_add_control' => esc_html__('Add New Services','sparklestore'),
              ),

              array(

                  'services_icon' => array(
                      'type'      => 'icon',
                      'label'     => esc_html__( 'Select Services Icon', 'sparklestore' ),
                      'default'   => 'fa fa-truck'
                  ),

                  'services_title' => array(
                      'type'      => 'text',
                      'label'     => esc_html__( 'Enter Services Title', 'sparklestore' ),
                      'default'   => ''
                  ),

                  'services_subtitle' => array(
                      'type'      => 'text',
                      'label'     => esc_html__( 'Enter Services Sub Title', 'sparklestore' ),
                      'default'   => ''
                  )             
              )
          )
      );

      $wp_customize->add_setting('sparklestore_services_area_upgrade_text', array(
          'sanitize_callback' => 'sparklestore_text_sanitize'
      ));

      $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_services_area_upgrade_text', array(
          'section' => 'sparklestore_services_area',
          'label' => esc_html__('For more settings,', 'sparklestore'),
          'choices' => array(
              esc_html__('Select Services Layout', 'sparklestore'),
              esc_html__('Enable/Disable Services Item', 'sparklestore'),
              esc_html__('Customize Padding', 'sparklestore'),
              esc_html__('Three Background Types', 'sparklestore'),
              esc_html__('Select Service Box Background Color', 'sparklestore'),
              esc_html__('Customize Icon Color', 'sparklestore'),
              esc_html__('Customize Text and Description Color', 'sparklestore'),
          ),
          'priority' => 100
      )));

      /**
       * Add Design Settings Panel
       *
       * @since 1.0.0
       */
      $wp_customize->add_panel(
          'sparklestore_design_settings_panel',

          array(
              'priority'       => 13,
              'title'          => esc_html__( 'Design Layout Settings', 'sparklestore' ),
          )
      );


        /**
         * Home Page Blog Settings
         *
         * @since 1.0.0
        */

        $wp_customize->add_section(
            'sparklestore_home_blog_settings_section',

            array(
                'title'     => esc_html__( 'Home & Blog Template Settings', 'sparklestore' ),
                'priority'       => 12,
                //'panel'     => 'sparklestore_design_settings_panel',
            )
        );

          /**
           * Image Radio field for archive/category layout
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting(
              'sparklestore_home_page_blog_layout',

              array(
                  'default'           => 'none',
                  'sanitize_callback' => 'sanitize_key',
              )
          );

          $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
              $wp_customize,
              'sparklestore_home_page_blog_layout',

                  array(
                      'label'    => esc_html__( 'Home Page Blog Layout', 'sparklestore' ),
                      'description' => esc_html__( 'Choose layout from available layouts', 'sparklestore' ),
                      'section'  => 'sparklestore_home_blog_settings_section',
                      'choices'  => array(
                              'none' => array(
                                  'label' => esc_html__( 'Normal Layout', 'sparklestore' ),
                                  'url'   => '%s/assets/images/blog-list.png'
                              ),
                              
                              'masonry' => array(
                                  'label' => esc_html__( 'Masonry Layout', 'sparklestore' ),
                                  'url'   => '%s/assets/images/masonry-layout.png'
                              )
                      )
                  )
              )
          );


          /**
           * Image Radio field for archive/category sidebar
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting(
              'sparklestore_home_page_blog_sidebar',

              array(
                  'default'           => 'rightsidebar',
                  'sanitize_callback' => 'sanitize_key',
              )
          );

          $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
              $wp_customize,
              'sparklestore_home_page_blog_sidebar',

                  array(
                      'label'    => esc_html__( 'Home Page Blog Sidebars', 'sparklestore' ),
                      'description' => esc_html__( 'Choose sidebar from available layouts', 'sparklestore' ),
                      'section'  => 'sparklestore_home_blog_settings_section',
                      'choices'  => array(
                              'leftsidebar' => array(
                                  'label' => esc_html__( 'Left Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/left-sidebar.png'
                              ),
                              'rightsidebar' => array(
                                  'label' => esc_html__( 'Right Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/right-sidebar.png'
                              ),
                              'nosidebar' => array(
                                  'label' => esc_html__( 'No Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/no-sidebar.png'
                              )
                      )
                  )
              )
          );


            //  Blog Template Blog Posts by Category.
            $wp_customize->add_setting('sparklestore_blogtemplate_postcat', array(
                'sanitize_callback' => 'sanitize_text_field',     //done
            ));

            $wp_customize->add_control(new sparklestore_Multiple_Check_Control($wp_customize, 'sparklestore_blogtemplate_postcat', array(
                'label'    => esc_html__('Select Category To Show Posts', 'sparklestore'),
                'settings' => 'sparklestore_blogtemplate_postcat',
                'section'  => 'sparklestore_home_blog_settings_section',
                'choices'  => $blog_cat,
                'description' => esc_html__('Note: Selected Category Only Work When you can select page template (
                Blog Template )','sparklestore'),
            )));

            $post_description = array(
                'none'     => esc_html__( 'None', 'sparklestore' ),
                'excerpt'  => esc_html__( 'Post Excerpt', 'sparklestore' ),
                'content'  => esc_html__( 'Post Content', 'sparklestore' )
            );
              
            $wp_customize->add_setting( 
                'sparklestore_post_description_options', 

                array(
                    'default'           => 'excerpt',
                    'sanitize_callback' => 'sparklestore_select_type_sanitize'
                ) 
            );
            
            $wp_customize->add_control( 
                'sparklestore_post_description_options', 

                array(
                    'type' => 'select',
                    'label' => esc_html__( 'Post Description', 'sparklestore' ),
                    'section' => 'sparklestore_home_blog_settings_section',
                    'choices' => $post_description
                ) 
            );


            // Blog Template Read More Button.
            $wp_customize->add_setting( 'sparklestore_blogtemplate_btn', array(

              'sanitize_callback' => 'sanitize_text_field',   //done
              'default'   => esc_html__( 'Read More', 'sparklestore' ),

            ));

            $wp_customize->add_control('sparklestore_blogtemplate_btn', array(

              'label'     => esc_html__( 'Enter Blog Button Text', 'sparklestore' ),
              'section'   => 'sparklestore_home_blog_settings_section',
              'type'      => 'text',
            ));


            /**
             * Number field for Excerpt Length section
             *
             * @since 1.0.0
            */
            $wp_customize->add_setting(
                'sparklestore_post_excerpt_length',
                array(
                    'default'    => 45,
                    'sanitize_callback' => 'absint'
                )
            );

            $wp_customize->add_control(
                'sparklestore_post_excerpt_length',

                array(
                    'type'      => 'number',
                    'label'     => esc_html__( 'Enter Posts Excerpt Length', 'sparklestore' ),
                    'section'   => 'sparklestore_home_blog_settings_section',
                )
            );

            /**
             * Enable/Disable Post Date
            */
            $wp_customize->add_setting( 
                'sparklestore_post_date_options', 

               array(
                 'sanitize_callback' => 'sparklestore_sanitize_on_off',
                 'default' => 'on'
               ) 
            );

            $wp_customize->add_control( new Sparklestore_Switch_Control( $wp_customize, 

                  'sparklestore_post_date_options', 

                  array(
                     'section'       => 'sparklestore_home_blog_settings_section',
                     'label'         => esc_html__( 'Enable/Disable Post Date', 'sparklestore' ),
                     'on_off_label'  => array(
                         'on'  => esc_html__( 'Enable', 'sparklestore' ),
                         'off' => esc_html__( 'Disable', 'sparklestore' )
                     )   
                  ) 
               ) 
            );


            /**
             * Enable/Disable Post Author
            */
            $wp_customize->add_setting( 
                'sparklestore_post_author_options', 

               array(
                 'sanitize_callback' => 'sparklestore_sanitize_on_off',
                 'default' => 'on'
               ) 
            );

            $wp_customize->add_control( new Sparklestore_Switch_Control( $wp_customize, 

                  'sparklestore_post_author_options', 

                  array(
                     'section'       => 'sparklestore_home_blog_settings_section',
                     'label'         => esc_html__( 'Enable/Disable Post Author', 'sparklestore' ),
                     'on_off_label'  => array(
                         'on'  => esc_html__( 'Enable', 'sparklestore' ),
                         'off' => esc_html__( 'Disable', 'sparklestore' )
                     )   
                  ) 
               ) 
            );


            /**
             * Enable/Disable Post Comments
            */
            $wp_customize->add_setting( 
                'sparklestore_post_comments_options', 

               array(
                 'sanitize_callback' => 'sparklestore_sanitize_on_off',
                 'default' => 'on'
               ) 
            );

            $wp_customize->add_control( new Sparklestore_Switch_Control( $wp_customize, 

                  'sparklestore_post_comments_options', 

                  array(
                     'section'       => 'sparklestore_home_blog_settings_section',
                     'label'         => esc_html__( 'Enable/Disable Post Comments', 'sparklestore' ),
                     'on_off_label'  => array(
                         'on'  => esc_html__( 'Enable', 'sparklestore' ),
                         'off' => esc_html__( 'Disable', 'sparklestore' )
                     )   
                  ) 
               ) 
            );

            $wp_customize->add_setting('sparklestore_home_blog_settings_section_upgrade_text', array(
                'sanitize_callback' => 'sparklestore_text_sanitize'
            ));

            $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_home_blog_settings_section_upgrade_text', array(
                'section' => 'sparklestore_home_blog_settings_section',
                'label' => esc_html__('For more settings and controls,', 'sparklestore'),
                'choices' => array(
                    esc_html__('Four Different Blog Layouts', 'sparklestore'),
                    esc_html__('Change Text Alignment', 'sparklestore'),
                    esc_html__('Select Single Post Sidebar', 'sparklestore'),
                    esc_html__('Show/Hide Features Image in Single Post Page', 'sparklestore'),
                    esc_html__('Show/Hide Post Tags in Single Post Page', 'sparklestore'),
                    esc_html__('Show/Hide Post Author in Single Post Page', 'sparklestore'),
                    esc_html__('Enable/Disable Pagination Navigation in Single Post Page', 'sparklestore'),
                    esc_html__('Select Item Background Color', 'sparklestore'),
                    esc_html__('Select Text and Text Hover Color', 'sparklestore'),
                    esc_html__('Change Button Color', 'sparklestore'),
                ),
                'priority' => 100
            )));

        /**
         * Archive/Category Settings
         *
         * @since 1.0.0
        */

        $wp_customize->add_section(
            'sparklestore_archive_settings_section',

            array(
                'title'     => esc_html__( 'Archive/Category Settings', 'sparklestore' ),
                'panel'     => 'sparklestore_design_settings_panel',
            )
        );

          /**
           * Image Radio field for archive/category layout
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting(
              'sparklestore_archive_layout',

              array(
                  'default'           => 'none',
                  'sanitize_callback' => 'sanitize_key',
              )
          );

          $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
              $wp_customize,
              'sparklestore_archive_layout',

                  array(
                      'label'    => esc_html__( 'Archive/Category Layout', 'sparklestore' ),
                      'description' => esc_html__( 'Choose layout from available layouts', 'sparklestore' ),
                      'section'  => 'sparklestore_archive_settings_section',
                      'choices'  => array(
                              'none' => array(
                                  'label' => esc_html__( 'Normal Layout', 'sparklestore' ),
                                  'url'   => '%s/assets/images/blog-list.png'
                              ),

                              'masonry' => array(
                                  'label' => esc_html__( 'Masonry Layout', 'sparklestore' ),
                                  'url'   => '%s/assets/images/masonry-layout.png'
                              )
                      )
                  )
              )
          );      

          /**
           * Image Radio field for archive/category sidebar
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting(
              'sparklestore_archive_sidebar',

              array(
                  'default'           => 'rightsidebar',
                  'sanitize_callback' => 'sanitize_key',
              )
          );

          $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
              $wp_customize,
              'sparklestore_archive_sidebar',

                  array(
                      'label'    => esc_html__( 'Archive/Category Sidebars', 'sparklestore' ),
                      'description' => esc_html__( 'Choose sidebar from available layouts', 'sparklestore' ),
                      'section'  => 'sparklestore_archive_settings_section',
                      'choices'  => array(
                              'leftsidebar' => array(
                                  'label' => esc_html__( 'Left Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/left-sidebar.png'
                              ),
                              'rightsidebar' => array(
                                  'label' => esc_html__( 'Right Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/right-sidebar.png'
                              ),
                              'nosidebar' => array(
                                  'label' => esc_html__( 'No Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/no-sidebar.png'
                              )
                      )
                  )
              )
          );

          $wp_customize->add_setting('sparklestore_archive_settings_section_upgrade_text', array(
              'sanitize_callback' => 'sparklestore_text_sanitize'
          ));

          $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_archive_settings_section_upgrade_text', array(
              'section' => 'sparklestore_archive_settings_section',
              'label' => esc_html__('For more layouts,', 'sparklestore'),
              'choices' => array(
                  esc_html__('Four Different Category Layouts', 'sparklestore'),
              ),
              'priority' => 100
          )));

        /**
         * Search Settings
         *
         * @since 1.0.0
        */

        $wp_customize->add_section(
            'sparklestore_search_settings_section',

            array(
                'title'     => esc_html__( 'Search Page Settings', 'sparklestore' ),
                'panel'     => 'sparklestore_design_settings_panel',
            )
        );      

          /**
           * Image Radio field for archive/category sidebar
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting(
              'sparklestore_search_sidebar',

              array(
                  'default'           => 'rightsidebar',
                  'sanitize_callback' => 'sanitize_key',
              )
          );

          $wp_customize->add_control( new Sparklestore_Customize_Control_Radio_Image(
              $wp_customize,
              'sparklestore_search_sidebar',

                  array(
                      'label'    => esc_html__( 'Search Page Sidebar', 'sparklestore' ),
                      'description' => esc_html__( 'Choose sidebar from available layouts', 'sparklestore' ),
                      'section'  => 'sparklestore_search_settings_section',
                      'choices'  => array(
                              'leftsidebar' => array(
                                  'label' => esc_html__( 'Left Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/left-sidebar.png'
                              ),
                              'rightsidebar' => array(
                                  'label' => esc_html__( 'Right Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/right-sidebar.png'
                              ),
                              'nosidebar' => array(
                                  'label' => esc_html__( 'No Sidebar', 'sparklestore' ),
                                  'url'   => '%s/assets/images/no-sidebar.png'
                              )
                      )
                  )
              )
          );

          $wp_customize->add_section(new Sparkle_Store_Upgrade_Section($wp_customize, 'square-upgrade-section', array(
              'title' => esc_html__('More Sections on Premium', 'sparklestore'),
              'panel' => 'sparklestore_design_settings_panel',
              'priority' => 1000,
              'options' => array(
                  esc_html__('- Post and Page Layout Section', 'sparklestore'),
                  esc_html__('------------------------', 'sparklestore'),
                  esc_html__('- Elementor Pagebuilder Compatible. All the above sections can be created with Elementor Page Builder or Customizer whichever you like.', 'sparklestore'),
              )
          )));


      /**
       * Footer Settings Area 
      */
      $wp_customize->add_section('sparklestore_footer_settings', array(
         'priority' => 15,
         'title' => esc_html__('Footer Settings', 'sparklestore')
      ));
      

          /**
           * Enable/Disable Option for Main Footer Widget Section
           *
           * @since 1.0.0
           */
          $wp_customize->add_setting( 
            'sparklestore_footer_widget_area_option', 

            array(
            'sanitize_callback' => 'sparklestore_sanitize_on_off',
            'default' => 'on'
          ) 
        );


        $wp_customize->add_control( new Sparklestore_Switch_Control( 
          $wp_customize, 
            'sparklestore_footer_widget_area_option', 

            array(
              'settings'    => 'sparklestore_footer_widget_area_option',
              'section'   => 'sparklestore_footer_settings',
              'label'     => esc_html__( 'Footer Widget Area Option', 'sparklestore' ),
              'description'   => esc_html__( 'Enable/Disable option for footer widget section.', 'sparklestore' ),
              'on_off_label'  => array(
                'on'  => esc_html__( 'Enable', 'sparklestore' ),
                'off' => esc_html__( 'Disable', 'sparklestore' )
              ) 
            ) 
          ) 
        );

        /**
         * Payment Logo 
        */
        $wp_customize->add_setting( 'paymentlogo_image_one', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_one', array(
            'section'       => 'sparklestore_footer_settings',
            'label'         => esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          => 'image',
        )));

        $wp_customize->add_setting( 'paymentlogo_image_two', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'  // done
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_two', array(
            'section'       => 'sparklestore_footer_settings',
            'label'         => esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          => 'image',
        )));

        $wp_customize->add_setting( 'paymentlogo_image_three', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'  // done
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_three', array(
            'section'       =>      'sparklestore_footer_settings',
            'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          =>      'image',
        )));

        $wp_customize->add_setting( 'paymentlogo_image_four', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'   // done
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_four', array(
            'section'       =>      'sparklestore_footer_settings',
            'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          =>      'image',
        )));

        $wp_customize->add_setting( 'paymentlogo_image_five', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'   // done
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_five', array(
            'section'       =>      'sparklestore_footer_settings',
            'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          =>      'image',
        )));

        $wp_customize->add_setting( 'paymentlogo_image_six', array(
            'default'       =>      '',
            'sanitize_callback' => 'esc_url_raw'  // done
        ));
       
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_six', array(
            'section'       =>      'sparklestore_footer_settings',
            'label'         =>      esc_html__('Upload Payment Logo Image', 'sparklestore'),
            'type'          =>      'image',
        )));



        /**
         * Enable/Disable Option for Social Icon and Payment Logo
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 
          'sparklestore_footer_social_icon_payment_logo_option', 

          array(
          'sanitize_callback' => 'sparklestore_sanitize_on_off',
          'default' => 'on'
        ) 
      );


      $wp_customize->add_control( new Sparklestore_Switch_Control( 
        $wp_customize, 
          'sparklestore_footer_social_icon_payment_logo_option', 

          array(
            'section'   => 'sparklestore_footer_settings',
            'label'     => esc_html__( 'Social Icon & Payment Logo', 'sparklestore' ),
            'description'   => esc_html__( 'Enable/Disable option for footer social icon and payment logo.', 'sparklestore' ),
            'on_off_label'  => array(
              'on'  => esc_html__( 'Enable', 'sparklestore' ),
              'off' => esc_html__( 'Disable', 'sparklestore' )
            ) 
          ) 
        ) 
      );


      /**
       * Footer Content (Copyright Text)
      */
      $wp_customize->add_setting('sparklestore_footer_copyright', array(
          'default' => '',
          'sanitize_callback' => 'sparklestore_text_sanitize'  //done
      ));

      $wp_customize->add_control('sparklestore_footer_copyright', array(
          'type' => 'textarea',
          'label' => esc_html__('Footer Content (Copyright Text)', 'sparklestore'),
          'section' => 'sparklestore_footer_settings',
          'settings' => 'sparklestore_footer_copyright'
      ));

      $wp_customize->add_setting('sparklestore_footer_settings_upgrade_text', array(
          'sanitize_callback' => 'sparklestore_text_sanitize'
      ));

      $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_footer_settings_upgrade_text', array(
          'section' => 'sparklestore_footer_settings',
          'label' => esc_html__('For more settings and controls,', 'sparklestore'),
          'choices' => array(
              esc_html__('Show/Hide Top Footer in Home Page or All or None', 'sparklestore'),
              esc_html__('Enable/Disable Main Footer', 'sparklestore'),
              esc_html__('Select Footer Column Layout for Main Area', 'sparklestore'),
              esc_html__('Enable/Disable Full Width Footer', 'sparklestore'),
              esc_html__('Three Different Types of Footer Options for Bottom Background', 'sparklestore'),
              esc_html__('Select Background Type for Top Footer Section', 'sparklestore'),
              esc_html__('Change Top Footer Text and Anchor Color', 'sparklestore'),
              esc_html__('Change Top Footer Background Color', 'sparklestore'),
              esc_html__('Select Background Type for Middle Footer Section', 'sparklestore'),
              esc_html__('Change Middle Footer Text and Anchor Color', 'sparklestore'),
              esc_html__('Change Social and Payment Section Background Color', 'sparklestore'),
              esc_html__('Change Main Footer Background Color', 'sparklestore'),
              esc_html__('Change Main Footer Text and Anchor Color', 'sparklestore'),
          ),
          'priority' => 100
      )));

      $wp_customize->add_setting(
          'sparklestore_sub_footer_bg_color',

          array(
              'default'     => '#111111',
              'sanitize_callback' => 'sanitize_hex_color',
          )
      ); 

    /**
     * Start of the Social Link Options
    */
    $wp_customize->add_section('sparklestore_social_link_activate_settings', array(
        'priority' => 16,
        'title'    => esc_html__('Social Media Settings', 'sparklestore'),
    ));

        $wp_customize->add_setting('sparklestore_social_link_activate', array(
            'default' => 1,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sparklestore_checkbox_sanitize'  //done
        ));

        $wp_customize->add_control('sparklestore_social_link_activate', array(
            'type' => 'checkbox',
            'label' => esc_html__('Check to activate social links area', 'sparklestore'),
            'section' => 'sparklestore_social_link_activate_settings',
            'settings' => 'sparklestore_social_link_activate'
        ));

        $sparklestore_social_links = array( 
            'sparklestore_social_facebook' => array(
                'id' => 'sparklestore_social_facebook',
                'title' => esc_html__('Facebook', 'sparklestore'),
                'default' => '',
            ),
            'sparklestore_social_twitter' => array(
                'id' => 'sparklestore_social_twitter',
                'title' => esc_html__('Twitter', 'sparklestore'),
                'default' => '',
            ),
            'sparklestore_social_pinterest' => array(
                'id' => 'sparklestore_social_pinterest',
                'title' => esc_html__('Pinterest', 'sparklestore'),
                'default' => '',
            ),
            'sparklestore_social_linkedin' => array(
                'id' => 'sparklestore_social_linkedin',
                'title' => esc_html__('Linkedin', 'sparklestore'),
                'default' => '',
            ),
            'sparklestore_social_youtube' => array(
                'id' => 'sparklestore_social_youtube',
                'title' => esc_html__('YouTube', 'sparklestore'),
                'default' => '',
            )
        );

        $i = 20;
        foreach($sparklestore_social_links as $sparklestore_social_link) {
            $wp_customize->add_setting($sparklestore_social_link['id'], array(
                'default' => $sparklestore_social_link['default'],
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
            ));

            $wp_customize->add_control($sparklestore_social_link['id'], array(
                'label' => $sparklestore_social_link['title'],
                'section'=> 'sparklestore_social_link_activate_settings',
                'settings'=> $sparklestore_social_link['id'],
                'priority' => $i
            ));

            $wp_customize->add_setting($sparklestore_social_link['id'].'_checkbox', array(
                'default' => 0,
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sparklestore_checkbox_sanitize'  // done
            ));
            $wp_customize->add_control($sparklestore_social_link['id'].'_checkbox', array(
                'type' => 'checkbox',
                'label' => esc_html__('Check to show in new tab', 'sparklestore'),
                'section'=> 'sparklestore_social_link_activate_settings',
                'settings'=> $sparklestore_social_link['id'].'_checkbox',
                'priority' => $i
            ));
            $i++;

        }

        $wp_customize->add_setting('sparklestore_social_link_activate_settings_upgrade_text', array(
              'sanitize_callback' => 'sparklestore_text_sanitize'
          ));

          $wp_customize->add_control(new Sparkle_Store_Upgrade_Text($wp_customize, 'sparklestore_social_link_activate_settings_upgrade_text', array(
              'section' => 'sparklestore_social_link_activate_settings',
              'label' => esc_html__('For more settings,', 'sparklestore'),
              'choices' => array(
                  esc_html__('Repeatable Social Links With Icons', 'sparklestore'),
                  esc_html__('Option to Open Link in New Tab', 'sparklestore'),
                  esc_html__('Change Icon Color and Icon Background Color', 'sparklestore'),
                  esc_html__('Change Icon Color and Icon Background Color on Hover', 'sparklestore'),
                  esc_html__('Change Alignment of Social Links Area', 'sparklestore'),
              ),
              'priority' => 100
          )));
            

    function sparklestore_checkbox_sanitize($input) {
        if ( $input == 1 ) {
            return 1;
        } else {
            return 0;
        }
    }

    function sparklestore_radio_enable_disable_sanitize($input) {
        $valid_keys = array(
            'enable' => esc_html__('Enable', 'sparklestore'),
            'disable' => esc_html__('Disable', 'sparklestore')
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
            return $input;
        } else {
            return '';
        }
    }

    function sparklestore_text_sanitize( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    function sparklestore_page_layout_sanitize($input) {
        $imagepath =  get_template_directory_uri() . '/images/';
        $valid_keys = array(
          'leftsidebar' => $imagepath.'left-sidebar.png',  
          'rightsidebar' => $imagepath.'right-sidebar.png',
          'onsidebar' => $imagepath.'no-sidebar.png ',
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
            return $input;
        } else {
            return '';
        }
    }

    function sparklestore_row_layout_sanitize($input) {
        $valid_keys = array(
            '2' => '2',  
            '3' => '3', 
            '4' => '4',
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
            return $input;
        } else {
            return '';
        }
    }

    function sparklestore_number_sanitize( $int ) {
        return absint( $int );
    }


    /**
    * On/Off Sanitization Function
    *
    * @since 1.0.0
    */
    function sparklestore_sanitize_on_off($input) {

       $valid_keys = array(
            'on'  => esc_html__( 'Enable', 'sparklestore' ),
         'off' => esc_html__( 'Disable', 'sparklestore' )
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }

    }

    /**
     * Advance Search Sanitization Function
     *
     * @since 1.0.0
     */
    function sparklestore_search_options_sanitize($input) {

       $valid_keys = array(
            'normalsearch'      => esc_html__( 'Normal Search', 'sparklestore' ),
            'advancesearch'     => esc_html__( 'Advance Search ( With Category )', 'sparklestore' )
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }

    }

    /**
     * Select Box Sanitization Function
     *
     * @since 1.0.0
    */
    function sparklestore_select_type_sanitize( $input, $setting ) {
        
        // get all select options
        $options = $setting->manager->get_control( $setting->id )->choices;
        
        // return default if not valid
        return ( array_key_exists( $input, $options ) ? $input : $setting->default );
    }

    function sparklestore_sanitize_repeater($input){        
      $input_decoded = json_decode( $input, true );
      $allowed_html = array(
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'a' => array(
          'href' => array(),
          'class' => array(),
          'id' => array(),
          'target' => array()
        ),
        'button' => array(
          'class' => array(),
          'id' => array()
        )
      ); 

      if(!empty($input_decoded)) {
        foreach ($input_decoded as $boxes => $box ){
          foreach ($box as $key => $value){
            $input_decoded[$boxes][$key] = sanitize_text_field( $value );
          }
        }
        return json_encode($input_decoded);
      }      
      return $input;
    }

}
add_action( 'customize_register', 'sparklestore_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
*/
function sparklestore_customize_preview_js() {
	wp_enqueue_script( 'sparklestore_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'sparklestore_customize_preview_js' );