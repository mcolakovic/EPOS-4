<?php
/**
 * WooCommerce Section Start Here
*/
if ( ! function_exists( 'sparklestore_is_woocommerce_activated' ) ) {
    function sparklestore_is_woocommerce_activated() {
        if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
    }
}

/**
  * Convert hexdec color string to rgb(a) string 
*/
if ( ! function_exists( 'sparklestore_hex2rgba' ) ) {
    function sparklestore_hex2rgba($color, $opacity = false) { 
        $default = 'rgb(0,0,0)'; 
        if(empty($color))
            return $default;  
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }
        $rgb =  array_map('hexdec', $hex);
        if($opacity){
            if(abs($opacity) > 1)
            $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }
        return $output;
    }
}

if( !function_exists( 'sparklestore_header_vertical' ) ) :

  function sparklestore_header_vertical(){

    $enable_vertical_menu    = get_theme_mod( 'sparklestore_vertical_menu_options','on' );
    $vertical_menu_title     = get_theme_mod( 'sparklestore_vertical_menu_title','All Categories' );
    $vertical_all_menu       = get_theme_mod( 'sparklestore_vertical_menu_show_all_menu','More Categories' );
    $vertical_all_close      = get_theme_mod( 'sparklestore_vertical_menu_show_all_menu_close','Close' );
    $vertical_item_visible   = get_theme_mod( 'sparklestore_vertical_menu_display_itmes', 10 );
    $vertical_menu_alignment = get_theme_mod( 'sparklestore_vertical_menu_display_itmes', 'on' );


    if ( !empty( $enable_vertical_menu ) && $enable_vertical_menu == 'on' ):

      $block_vertical_class = array( 'vertical-wapper block-nav-category has-vertical-menu' );
    ?>
          <div data-items="<?php echo esc_attr( $vertical_item_visible ); ?>" class="category-menu-main <?php echo esc_attr( implode( ' ', $block_vertical_class ) ); ?>">
              <button class="category-menu-title block-title">
                  <?php echo esc_html( $vertical_menu_title ); ?>
              </button>
              
              <div class="block-content verticalmenu-content menu-category">
                <?php
                  wp_nav_menu( array(
                      'theme_location'  => 'sparklecategory',
                      'depth'           => 4,
                      'container'       => '',
                      'container_class' => '',
                      'container_id'    => '',
                      'menu_class'      => 'vertical-menu',
                    )
                  );
                ?>
                <div class="view-all-category">
                    <a href="javascript:void(0);"
                       data-closetext="<?php echo esc_attr( $vertical_all_close ); ?>"
                       data-alltext="<?php echo esc_attr( $vertical_all_menu ) ?>"
                       class="btn-view-all open-cate"><?php echo esc_html( $vertical_all_menu ) ?>
                    </a>
                </div>
              </div>
          </div><!-- block category -->
    <?php endif;
  }
endif;
add_action( 'sparklestore_header_vertical', 'sparklestore_header_vertical' );

/**
 * Header Main Banner Function Area
*/
if ( ! function_exists( 'sparklestore_banner_slider' ) ) { 

  function sparklestore_banner_slider() { 
    $all_slider = get_theme_mod('sparklestore_banner_all_sliders');
    if(!empty( $all_slider )) { ?>

      <div id="home" class="home-section banner-height">
          <div class="sparklestore-slider">
              <ul class="slides">
                  <?php
                    $banner_slider = json_decode( $all_slider );
                    foreach($banner_slider as $slider){ 
                      $slider_page_id = $slider->selectpage;
                      if($slider_page_id == '-999'){
                        $slider_page_id = '';
                      }
                      if( !empty( $slider_page_id ) ) {
                      $slider_page = new WP_Query( 'page_id='.$slider_page_id );
                      if( $slider_page->have_posts() ) { while( $slider_page->have_posts() ) { $slider_page->the_post();
                        $image_path = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sparklestore-slider', true ); ?>
                        <li class="bg-dark" style="background-image: url('<?php echo esc_url( $image_path[0] ); ?>');">
                            <div class="home-slider-overlay"></div>
                            <div class="sparklestore-caption text-center">
                                <div class="caption-content">
                                    <h2><?php the_title(); ?></h2>
                                    <p><?php echo wp_kses_post( wp_trim_words( get_the_content(), 20 ) ); ?></p>
                                    <?php if($slider->button_text): ?>
                                      <div class="sliderbtn-wrp">
                                        <a class="btn btn-primary" href="<?php echo esc_url($slider->button_url); ?>">
                                          <?php echo esc_html($slider->button_text); ?>
                                        </a>
                                      </div>
                                    <?php endif; ?>
                                </div>                          
                            </div>
                        </li>
                    <?php } } wp_reset_query(); 
                    }else{ ?>
                      <li class="bg-dark" style="background-image: url('<?php echo esc_url( get_template_directory_uri( ). "/assets/images/computer-writing-screen-man-nature-outdoor-458001-pxhere.com.jpg" ); ?>');">
                            <div class="home-slider-overlay"></div>
                            <div class="sparklestore-caption text-center">
                                <div class="caption-content">
                                    <h2><?php echo esc_html__('45% off on all Smart Phones', 'sparklestore'); ?></h2>
                                    <p><?php echo esc_html__('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget,…', 'sparklestore'); ?></p>
                                    
                                    <div class="sliderbtn-wrp">
                                      <a class="btn btn-primary" href="#">
                                        <?php echo esc_html__( 'Buy Now', 'sparklestore' ); ?>
                                      </a>
                                    </div>
                                </div>                          
                            </div>
                        </li> 
                      <?php
                    }
                  } ?>                    
              </ul>
          </div>
      </div>
    <?php }
  }
}
add_action( 'sparklestore-slider', 'sparklestore_banner_slider', 30 );


if ( ! function_exists( 'sparklestore_slider_with_promo_images' ) ){
    function sparklestore_slider_with_promo_images(){ 
      $style = get_theme_mod( 'sparklestore_banner_promo_style', 'right' );
      ?>
        <?php if( $style == 'right'): ?>
          <div class="sparklestore_banner_promo_wrap">
              <?php do_action('sparklestore-slider'); ?>
          </div>
          <?php sparklestore_banner_promo_content(); ?>
          
          <?php else: ?>
            <?php sparklestore_banner_promo_content(); ?>

            <div class="sparklestore_banner_promo_wrap">
              <?php do_action('sparklestore-slider'); ?>
            </div>
          
          <?php endif; ?>
        <?php 
    }
}
add_action( 'sparklestore_slider_with_promo_images', 'sparklestore_slider_with_promo_images' );

/**
 * banner promo sections
 */
if(!function_exists('sparklestore_banner_promo_content')){
  function sparklestore_banner_promo_content(){ ?>
    <div class="sparklestore_wrap_promo">
      <?php 
          $promoimage_one     = get_theme_mod( 'sparklestore_banner_promo_one' );
          $promoimage_one_url = get_theme_mod( 'sparklestore_banner_promo_one_url' );
          $promoimage_two     = get_theme_mod( 'sparklestore_banner_promo_two' );
          $promoimage_two_url = get_theme_mod( 'sparklestore_banner_promo_two_url' );
          
      ?>
      <div class="sparklestore_promo_wrap">
          <a class="sparklestore_single_promo" href="<?php echo esc_url( $promoimage_one_url ); ?>" target="_blank">
              <img src="<?php echo esc_url( wp_get_attachment_url( $promoimage_one ) ); ?>">
          </a>
      </div>

      <div class="sparklestore_promo_wrap">
          <a class="sparklestore_single_promo second_single_promo" href="<?php echo esc_url( $promoimage_two_url ); ?>" target="_blank">
              <img src="<?php echo esc_url( wp_get_attachment_url( $promoimage_two ) ); ?>">
          </a>
      </div>
  </div>
  <?php
  }
}

if ( ! function_exists( 'sparklestore_services_area' ) ){
  
    function sparklestore_services_area(){

        $servicesarea = get_theme_mod('sparklestore_quick_services_settings_options');

        if(!empty( $servicesarea )) { ?>
        
            <div class="services_wrapper">
                <div class="container">
                    <div class="services_area">
                      <?php
                          $servicesarea = json_decode( $servicesarea );
                          foreach($servicesarea as $services){ 
                      ?>
                          <div class="services_item">

                              <?php if( !empty( $services->services_icon ) ){ ?>
                                  <div class="services_icon">
                                      <span class="<?php echo esc_attr( $services->services_icon ); ?>"></span>
                                  </div>
                              <?php } ?>

                              <div class="services_content">

                                  <?php if( !empty( $services->services_title ) ){ ?>

                                      <h3><?php echo esc_html( $services->services_title ); ?></h3>

                                  <?php } if( !empty( $services->services_subtitle ) ){ ?>

                                      <p><?php echo esc_html( $services->services_subtitle ); ?></p>

                                  <?php } ?>
                              </div>
                          </div>
                      <?php } ?>
                    </div>
                </div>
            </div>

        <?php
        }
    }
}
add_action( 'sparklestore_services_area', 'sparklestore_services_area' );

/**
 * Schema type
*/
function sparklestore_html_tag_schema() {
    $schema     = 'http://schema.org/';
    $type       = 'WebPage';
    // Is single post
    if ( is_singular( 'post' ) ) {
        $type   = 'Article';
    }
    // Is author page
    elseif ( is_author() ) {
        $type   = 'ProfilePage';
    }
    // Is search results page
    elseif ( is_search() ) {
        $type   = 'SearchResultsPage';
    }
    echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

if ( ! function_exists( 'sparklestore_post_meta' ) ){
    /**
     * Post Meta Function
     *
     * @since 1.0.0
     */
    function sparklestore_post_meta() { 
        
        $postdate    = get_theme_mod( 'sparklestore_post_date_options', 'on' );
        $postcomment = get_theme_mod( 'sparklestore_post_comments_options', 'on' );
        $postauthor  = get_theme_mod( 'sparklestore_post_author_options', 'on' );
        if( $postdate == 'on' || $postcomment == 'on' || $postauthor == 'on'){
      ?>

        <div class="site-entry-meta metainfo">
            <?php
                if( !empty( $postdate ) && $postdate == 'on' ) { sparklestore_posted_on(); }
                if( !empty( $postauthor ) && $postauthor == 'on' ) { sparklestore_posted_by(); }
                if( !empty( $postcomment ) && $postcomment == 'on' ) { sparklestore_comments(); }
            ?>
        </div><!-- .entry-meta -->

       <?php
        }
    }
}
add_action( 'sparklestore_post_meta', 'sparklestore_post_meta' , 10 );


if( ! function_exists( 'sparklestore_post_format_media' ) ) :

    /**
     * Posts format declaration function.
     *
     * @since 1.0.0
     */
    function sparklestore_post_format_media( $postformat ) {

        global $post;

        if( is_singular( ) ){

          $imagesize = 'post-thumbnail';

        }else{

            $imagesize = '';
        }

        if ($postformat == "gallery") {

            if (function_exists('has_block') && has_block('gallery', $post->post_content)) {

                $post_blocks = parse_blocks($post->post_content);

                $key = array_search('core/gallery', array_column($post_blocks, 'blockName'));
                
                $gallery_attachment_ids = $post_blocks[$key]['attrs']['ids'];

            }else {
                
                $gallery = get_post_gallery( $post->ID, false );

                $gallery_attachment_ids = array();

                if( count($gallery) and isset($gallery['ids'])) {

                    $gallery_attachment_ids = explode ( ",", $gallery['ids'] );

                }
            }

            if ( ! empty( $gallery_attachment_ids ) ){ ?>

                
                <div class="blog-post-thumbnail">
                  <div class="postgallery-carousel cS-hidden">

                    <?php foreach ( $gallery_attachment_ids as $gallery_attachment_id ) { ?>
                        
                        <img src="<?php echo wp_get_attachment_image_url($gallery_attachment_id, 'post-thumbnail'); ?>"/>
                        
                    <?php } ?>

                  </div>
                </div>
                
            <?php } else { ?>
                
                <div class="blog-post-thumbnail">
                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                        <?php
                          the_post_thumbnail( $imagesize );
                        ?>
                    </a>
                </div>

            <?php } } else if( $postformat == "video" ){
            
              $get_content  = apply_filters( 'the_content', get_the_content() );
              $get_video    = get_media_embedded_in_content( $get_content, array( 'video', 'object', 'embed', 'iframe' ) );

              if( !empty( $get_video ) ){ ?>
                  <div class="blog-post-thumbnail">
                      <div class="video">
                          <?php echo $get_video[0]; // WPCS xss ok. ?>
                      </div>
                  </div>

          <?php }else{ ?>

                  <div class="blog-post-thumbnail">
                      <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                          <?php
                            the_post_thumbnail( $imagesize );
                          ?>
                      </a>
                  </div>

          <?php  } }else if( $postformat == "audio" ){

              $get_content  = apply_filters( 'the_content', get_the_content() );
              $get_audio    = get_media_embedded_in_content( $get_content, array( 'audio', 'iframe' ) );

              if( !empty( $get_audio ) ){ ?>

                  <div class="blog-post-thumbnail">
                      <div class="audio">
                          <?php echo $get_audio[0]; // WPCS xss ok. ?>
                      </div>
                  </div>

          <?php }else{  ?>

                  <div class="blog-post-thumbnail">
                      <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                          <?php
                            the_post_thumbnail( $imagesize );
                          ?>
                      </a>
                  </div>

          <?php } }else if( $postformat == "quote" ) { ?>

            <div class="blog-post-thumbnail">
                <div class="post-format-media-quote">
                  <?php
                    if (function_exists('has_block') && has_block('quote', $post->post_content)) {
                      $post_blocks = parse_blocks($post->post_content);
                      $key = array_search('core/quote', array_column($post_blocks, 'blockName'));
                      $wuote_content = $post_blocks[$key];
                      echo $wuote_content['innerContent'][0];
                    }
                  ?>
                </div>
            </div>

          <?php }else{ ?>

                  <div class="blog-post-thumbnail">
                      <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                          <?php
                            the_post_thumbnail( $imagesize );
                          ?>
                      </a>
                  </div>

          <?php }

    }

endif;


/**
 * Home page blog meta info
*/
if(! function_exists('sparklestore_meta_options')) {
  function sparklestore_meta_options($meta_options = array()){
      if(empty($meta_options)) { ?>
      <ul class="post-meta">
        <li><i class="fa fa-user"></i> <?php the_author_posts_link(); ?> </li>
        <li><i class="fa fa-comments"></i><?php comments_popup_link( esc_html__( '0 Comment', 'sparklestore' ),  esc_html__( '1 Comment', 'sparklestore' ), esc_html__( '% Comments', 'sparklestore' ), esc_html__( 'Comments are Closed', 'sparklestore' ) ); ?></li>
        <li><i class="fa fa-clock-o"></i><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date() ); ?></a></li>
      </ul>
      <?php } else {
        echo '<ul>';
          if(in_array('author', $meta_options)){ ?>
              <li><i class="fa fa-user"></i> <?php the_author_posts_link(); ?> </li>
          <?php }        
          if(in_array('comments', $meta_options)){ ?>
            <li><i class="fa fa-comments"></i><?php comments_popup_link( esc_html__( '0 Comment', 'sparklestore' ),  esc_html__( '1 Comment', 'sparklestore' ), esc_html__( '% Comments', 'sparklestore' ), esc_html__( 'Comments are Closed', 'sparklestore' ) ); ?></li>
          <?php }
          if(in_array('time', $meta_options)){ ?>
            <li><i class="fa fa-clock-o"></i><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date() );?></a></li>
          <?php } 
        echo '</ul>';      
      }     
  }
}


/**
 * Sparkle Store social links
*/
if ( ! function_exists( 'sparklestore_social_links' ) ) {
  function sparklestore_social_links() {
    if( intval(get_theme_mod('sparklestore_social_link_activate', 1 ) ) == 1 ) { ?>
      <div class="social">
        <ul>
          <?php if (esc_url(get_theme_mod('sparklestore_social_facebook'))) { ?>
              <li class="fb">
                <a href="<?php echo esc_url(get_theme_mod('sparklestore_social_facebook')); ?>" <?php if (intval(get_theme_mod('sparklestore_social_facebook_checkbox', 0 )) == 1){ echo "target=_blank"; } ?>><i class="fab fa-facebook-f"></i></a></li>
          <?php } ?>
          <?php if (esc_url(get_theme_mod('sparklestore_social_twitter'))) { ?>
              <li class="tw">
                <a href="<?php echo esc_url(get_theme_mod('sparklestore_social_twitter')); ?>" <?php if (esc_attr(get_theme_mod('sparklestore_social_twitter_checkbox', 0 )) == 1){ echo "target=_blank"; } ?>><i class="fab fa-twitter"></i></a></li>
          <?php } ?>
          <?php if (esc_url(get_theme_mod('sparklestore_social_pinterest'))) { ?>
              <li class="pinterest">
                <a href="<?php echo esc_url(get_theme_mod('sparklestore_social_pinterest')); ?>" <?php if (esc_attr(get_theme_mod('sparklestore_social_pinterest_checkbox', 0 )) == 1){ echo "target=_blank"; } ?>><i class="fab fa-pinterest"></i></a></li>
          <?php } ?>
          <?php if (esc_url(get_theme_mod('sparklestore_social_linkedin'))) { ?>
              <li class="linkedin">
                <a href="<?php echo esc_url(get_theme_mod('sparklestore_social_linkedin')); ?>" <?php if (esc_attr(get_theme_mod('sparklestore_social_linkedin_checkbox', 0 )) == 1){ echo "target=_blank"; } ?>><i class="fab fa-linkedin"></i></a></li>
          <?php } ?>
          <?php if (esc_url(get_theme_mod('sparklestore_social_youtube'))) { ?>
              <li class="youtube">
                <a href="<?php echo esc_url(get_theme_mod('sparklestore_social_youtube')); ?>" <?php if (esc_attr(get_theme_mod('sparklestore_social_youtube_checkbox', 0 )) == 1){ echo "target=_blank"; } ?>><i class="fab fa-youtube"></i></a></li>
          <?php } ?>
        </ul>
      </div>
    <?php }
  } 
}
add_filter( 'sparklestore_social_links', 'sparklestore_social_links', 5 );


if ( ! function_exists( 'sparklestore_quick_contact' ) ) :
    /**
     * Quick Contact Information Section
     *
     * @since 1.0.0
     */
    function sparklestore_quick_contact(){

            $map_address      = get_theme_mod( 'sparklestore_map_address' );
            $quick_email      = get_theme_mod( 'sparklestore_email_title' );
            $quick_wroking    = get_theme_mod( 'sparklestore_start_open_time' );
            $quick_phone      = get_theme_mod( 'sparklestore_phone_number' );
            $phonenumber      = preg_replace("/[^0-9]/","",$quick_phone);
        ?>
            <ul class="sparklestore_quick_contact">
                <?php if( !empty( $quick_phone ) ) { ?>
                    
                    <li>
                        <span class="fas fa-phone-alt" aria-hidden="true"></span>
                        <?php echo esc_html( $quick_phone ); ?>
                    </li>
                <?php } if( !empty( $quick_email ) ) { ?>
                    <li>
                        <span class="fa fa-envelope-open"></span>
                        <a href="mailto:'<?php echo esc_attr( antispambot( $quick_email ) ); ?>"><?php echo esc_html( antispambot( $quick_email ) ); ?></a>
                    </li>
                <?php } if( !empty( $map_address ) ) { ?>
                    <li>
                        <span class="fa fa-map-marker"></span>
                        <a target="_blank" href="https://www.google.com.np/maps/place/<?php echo esc_attr( $map_address ); ?>">
                            <?php echo esc_html( $map_address ); ?>
                        </a>
                    </li>
                <?php } if( !empty( $quick_wroking ) ) { ?>
                    <li>
                        <span class="fas fa-clock" aria-hidden="true"></span>
                        <?php echo esc_html( $quick_wroking ); ?>
                    </li>
                <?php } ?>
            </ul>
        <?php
    }
endif;


/**
 * Sparkle Store payment logo section
*/
if ( ! function_exists( 'sparklestore_payment_logo' ) ) {
  function sparklestore_payment_logo() { 
      $payment_logo_one   = esc_url( get_theme_mod('paymentlogo_image_one') );
      $payment_logo_two   = esc_url( get_theme_mod('paymentlogo_image_two') );
      $payment_logo_three = esc_url( get_theme_mod('paymentlogo_image_three') );
      $payment_logo_four  = esc_url( get_theme_mod('paymentlogo_image_four') );
      $payment_logo_five  = esc_url( get_theme_mod('paymentlogo_image_five') );
      $payment_logo_six   = esc_url( get_theme_mod('paymentlogo_image_six') ); ?>
      <div class="payment-accept">
        <?php if(!empty($payment_logo_one)) { ?>
            <img src="<?php echo esc_url($payment_logo_one)?>" />
        <?php } ?>
        <?php if(!empty($payment_logo_two)) { ?>
            <img src="<?php echo esc_url($payment_logo_two)?>" />
        <?php } ?>
        <?php if(!empty($payment_logo_three)) { ?>
            <img src="<?php echo esc_url($payment_logo_three)?>" />
        <?php } ?>
        <?php if(!empty($payment_logo_four)) { ?>
            <img src="<?php echo esc_url($payment_logo_four)?>" />
        <?php } ?>
        <?php if(!empty($payment_logo_five)) { ?>
            <img src="<?php echo esc_url($payment_logo_five)?>" />
        <?php } ?>
        <?php if(!empty($payment_logo_six)) { ?>
            <img src="<?php echo esc_url($payment_logo_six)?>" />
        <?php } ?>
      </div>
      <?php
  }
}
add_filter( 'sparklestore_payment_logo', 'sparklestore_payment_logo', 10 );


if(!function_exists('sparklestore_font_awesome_icon_array')){

  function sparklestore_font_awesome_icon_array(){

      return array("fab fa-500px",
                    "fab fa-accessible-icon",
                    "fab fa-accusoft",
                    "fas fa-address-book", "far fa-address-book",
                    "fas fa-address-card", "far fa-address-card",
                    "fas fa-adjust",
                    "fab fa-adn",
                    "fab fa-adversal",
                    "fab fa-affiliatetheme",
                    "fab fa-algolia",
                    "fas fa-align-center",
                    "fas fa-align-justify",
                    "fas fa-align-left",
                    "fas fa-align-right",
                    "fab fa-amazon",
                    "fas fa-ambulance",
                    "fas fa-american-sign-language-interpreting",
                    "fab fa-amilia",
                    "fas fa-anchor",
                    "fab fa-android",
                    "fab fa-angellist",
                    "fas fa-angle-double-down",
                    "fas fa-angle-double-left",
                    "fas fa-angle-double-right",
                    "fas fa-angle-double-up",
                    "fas fa-angle-down",
                    "fas fa-angle-left",
                    "fas fa-angle-right",
                    "fas fa-angle-up",
                    "fab fa-angrycreative",
                    "fab fa-angular",
                    "fab fa-app-store",
                    "fab fa-app-store-ios",
                    "fab fa-apper",
                    "fab fa-apple",
                    "fab fa-apple-pay",
                    "fas fa-archive",
                    "fas fa-arrow-alt-circle-down", "far fa-arrow-alt-circle-down",
                    "fas fa-arrow-alt-circle-left", "far fa-arrow-alt-circle-left",
                    "fas fa-arrow-alt-circle-right", "far fa-arrow-alt-circle-right",
                    "fas fa-arrow-alt-circle-up", "far fa-arrow-alt-circle-up",
                    "fas fa-arrow-circle-down",
                    "fas fa-arrow-circle-left",
                    "fas fa-arrow-circle-right",
                    "fas fa-arrow-circle-up",
                    "fas fa-arrow-down",
                    "fas fa-arrow-left",
                    "fas fa-arrow-right",
                    "fas fa-arrow-up",
                    "fas fa-arrows-alt",
                    "fas fa-arrows-alt-h",
                    "fas fa-arrows-alt-v",
                    "fas fa-assistive-listening-systems",
                    "fas fa-asterisk",
                    "fab fa-asymmetrik",
                    "fas fa-at",
                    "fab fa-audible",
                    "fas fa-audio-description",
                    "fab fa-autoprefixer",
                    "fab fa-avianex",
                    "fab fa-aviato",
                    "fab fa-aws",
                    "fas fa-backward",
                    "fas fa-balance-scale",
                    "fas fa-ban",
                    "fab fa-bandcamp",
                    "fas fa-barcode",
                    "fas fa-bars",
                    "fas fa-bath",
                    "fas fa-battery-empty",
                    "fas fa-battery-full",
                    "fas fa-battery-half",
                    "fas fa-battery-quarter",
                    "fas fa-battery-three-quarters",
                    "fas fa-bed",
                    "fas fa-beer",
                    "fab fa-behance",
                    "fab fa-behance-square",
                    "fas fa-bell", "far fa-bell",
                    "fas fa-bell-slash", "far fa-bell-slash",
                    "fas fa-bicycle",
                    "fab fa-bimobject",
                    "fas fa-binoculars",
                    "fas fa-birthday-cake",
                    "fab fa-bitbucket",
                    "fab fa-bitcoin",
                    "fab fa-bity",
                    "fab fa-black-tie",
                    "fab fa-blackberry",
                    "fas fa-blind",
                    "fab fa-blogger",
                    "fab fa-blogger-b",
                    "fab fa-bluetooth",
                    "fab fa-bluetooth-b",
                    "fas fa-bold",
                    "fas fa-bolt",
                    "fas fa-bomb",
                    "fas fa-book",
                    "fas fa-bookmark", "far fa-bookmark",
                    "fas fa-braille",
                    "fas fa-briefcase",
                    "fab fa-btc",
                    "fas fa-bug",
                    "fas fa-building", "far fa-building",
                    "fas fa-bullhorn",
                    "fas fa-bullseye",
                    "fab fa-buromobelexperte",
                    "fas fa-bus",
                    "fab fa-buysellads",
                    "fas fa-calculator",
                    "fas fa-calendar", "far fa-calendar",
                    "fas fa-calendar-alt", "far fa-calendar-alt",
                    "fas fa-calendar-check", "far fa-calendar-check",
                    "fas fa-calendar-minus", "far fa-calendar-minus",
                    "fas fa-calendar-plus", "far fa-calendar-plus",
                    "fas fa-calendar-times", "far fa-calendar-times",
                    "fas fa-camera",
                    "fas fa-camera-retro",
                    "fas fa-car",
                    "fas fa-caret-down",
                    "fas fa-caret-left",
                    "fas fa-caret-right",
                    "fas fa-caret-square-down", "far fa-caret-square-down",
                    "fas fa-caret-square-left", "far fa-caret-square-left",
                    "fas fa-caret-square-right", "far fa-caret-square-right",
                    "fas fa-caret-square-up", "far fa-caret-square-up",
                    "fas fa-caret-up",
                    "fas fa-cart-arrow-down",
                    "fas fa-cart-plus",
                    "fab fa-cc-amex",
                    "fab fa-cc-apple-pay",
                    "fab fa-cc-diners-club",
                    "fab fa-cc-discover",
                    "fab fa-cc-jcb",
                    "fab fa-cc-mastercard",
                    "fab fa-cc-paypal",
                    "fab fa-cc-stripe",
                    "fab fa-cc-visa",
                    "fab fa-centercode",
                    "fas fa-certificate",
                    "fas fa-chart-area",
                    "fas fa-chart-bar", "far fa-chart-bar",
                    "fas fa-chart-line",
                    "fas fa-chart-pie",
                    "fas fa-check",
                    "fas fa-check-circle", "far fa-check-circle",
                    "fas fa-check-square", "far fa-check-square",
                    "fas fa-chevron-circle-down",
                    "fas fa-chevron-circle-left",
                    "fas fa-chevron-circle-right",
                    "fas fa-chevron-circle-up",
                    "fas fa-chevron-down",
                    "fas fa-chevron-left",
                    "fas fa-chevron-right",
                    "fas fa-chevron-up",
                    "fas fa-child",
                    "fab fa-chrome",
                    "fas fa-circle", "far fa-circle",
                    "fas fa-circle-notch",
                    "fas fa-clipboard", "far fa-clipboard",
                    "fas fa-clock", "far fa-clock",
                    "fas fa-clone", "far fa-clone",
                    "fas fa-closed-captioning", "far fa-closed-captioning",
                    "fas fa-cloud",
                    "fas fa-cloud-download-alt",
                    "fas fa-cloud-upload-alt",
                    "fab fa-cloudscale",
                    "fab fa-cloudsmith",
                    "fab fa-cloudversify",
                    "fas fa-code",
                    "fas fa-code-branch",
                    "fab fa-codepen",
                    "fab fa-codiepie",
                    "fas fa-coffee",
                    "fas fa-cog",
                    "fas fa-cogs",
                    "fas fa-columns",
                    "fas fa-comment", "far fa-comment",
                    "fas fa-comment-alt", "far fa-comment-alt",
                    "fas fa-comments", "far fa-comments",
                    "fas fa-compass", "far fa-compass",
                    "fas fa-compress",
                    "fab fa-connectdevelop",
                    "fab fa-contao",
                    "fas fa-copy", "far fa-copy",
                    "fas fa-copyright", "far fa-copyright",
                    "fab fa-cpanel",
                    "fab fa-creative-commons",
                    "fas fa-credit-card", "far fa-credit-card",
                    "fas fa-crop",
                    "fas fa-crosshairs",
                    "fab fa-css3",
                    "fab fa-css3-alt",
                    "fas fa-cube",
                    "fas fa-cubes",
                    "fas fa-cut",
                    "fab fa-cuttlefish",
                    "fab fa-d-and-d",
                    "fab fa-dashcube",
                    "fas fa-database",
                    "fas fa-deaf",
                    "fab fa-delicious",
                    "fab fa-deploydog",
                    "fab fa-deskpro",
                    "fas fa-desktop",
                    "fab fa-deviantart",
                    "fab fa-digg",
                    "fab fa-digital-ocean",
                    "fab fa-discord",
                    "fab fa-discourse",
                    "fab fa-dochub",
                    "fab fa-docker",
                    "fas fa-dollar-sign",
                    "fas fa-dot-circle", "far fa-dot-circle",
                    "fas fa-download",
                    "fab fa-draft2digital",
                    "fab fa-dribbble",
                    "fab fa-dribbble-square",
                    "fab fa-dropbox",
                    "fab fa-drupal",
                    "fab fa-dyalog",
                    "fab fa-earlybirds",
                    "fab fa-edge",
                    "fas fa-edit", "far fa-edit",
                    "fas fa-eject",
                    "fas fa-ellipsis-h",
                    "fas fa-ellipsis-v",
                    "fab fa-ember",
                    "fab fa-empire",
                    "fas fa-envelope", "far fa-envelope",
                    "fas fa-envelope-open", "far fa-envelope-open",
                    "fas fa-envelope-square",
                    "fab fa-envira",
                    "fas fa-eraser",
                    "fab fa-erlang",
                    "fab fa-etsy",
                    "fas fa-euro-sign",
                    "fas fa-exchange-alt",
                    "fas fa-exclamation",
                    "fas fa-exclamation-circle",
                    "fas fa-exclamation-triangle",
                    "fas fa-expand",
                    "fas fa-expand-arrows-alt",
                    "fab fa-expeditedssl",
                    "fas fa-external-link-alt",
                    "fas fa-external-link-square-alt",
                    "fas fa-eye",
                    "fas fa-eye-dropper",
                    "fas fa-eye-slash", "far fa-eye-slash",
                    "fab fa-facebook",
                    "fab fa-facebook-f",
                    "fab fa-facebook-messenger",
                    "fab fa-facebook-square",
                    "fas fa-fast-backward",
                    "fas fa-fast-forward",
                    "fas fa-fax",
                    "fas fa-female",
                    "fas fa-fighter-jet",
                    "fas fa-file", "far fa-file",
                    "fas fa-file-alt", "far fa-file-alt",
                    "fas fa-file-archive", "far fa-file-archive",
                    "fas fa-file-audio", "far fa-file-audio",
                    "fas fa-file-code", "far fa-file-code",
                    "fas fa-file-excel", "far fa-file-excel",
                    "fas fa-file-image", "far fa-file-image",
                    "fas fa-file-pdf", "far fa-file-pdf",
                    "fas fa-file-powerpoint", "far fa-file-powerpoint",
                    "fas fa-file-video", "far fa-file-video",
                    "fas fa-file-word", "far fa-file-word",
                    "fas fa-film",
                    "fas fa-filter",
                    "fas fa-fire",
                    "fas fa-fire-extinguisher",
                    "fab fa-firefox",
                    "fab fa-first-order",
                    "fab fa-firstdraft",
                    "fas fa-flag", "far fa-flag",
                    "fas fa-flag-checkered",
                    "fas fa-flask",
                    "fab fa-flickr",
                    "fab fa-fly",
                    "fas fa-folder", "far fa-folder",
                    "fas fa-folder-open", "far fa-folder-open",
                    "fas fa-font",
                    "fab fa-font-awesome",
                    "fab fa-font-awesome-alt",
                    "fab fa-font-awesome-flag",
                    "fab fa-fonticons",
                    "fab fa-fonticons-fi",
                    "fab fa-fort-awesome",
                    "fab fa-fort-awesome-alt",
                    "fab fa-forumbee",
                    "fas fa-forward",
                    "fab fa-foursquare",
                    "fab fa-free-code-camp",
                    "fab fa-freebsd",
                    "fas fa-frown", "far fa-frown",
                    "fas fa-futbol", "far fa-futbol",
                    "fas fa-gamepad",
                    "fas fa-gavel",
                    "fas fa-gem", "far fa-gem",
                    "fas fa-genderless",
                    "fab fa-get-pocket",
                    "fab fa-gg",
                    "fab fa-gg-circle",
                    "fas fa-gift",
                    "fab fa-git",
                    "fab fa-git-square",
                    "fab fa-github",
                    "fab fa-github-alt",
                    "fab fa-github-square",
                    "fab fa-gitkraken",
                    "fab fa-gitlab",
                    "fab fa-gitter",
                    "fas fa-glass-martini",
                    "fab fa-glide",
                    "fab fa-glide-g",
                    "fas fa-globe",
                    "fab fa-gofore",
                    "fab fa-goodreads",
                    "fab fa-goodreads-g",
                    "fab fa-google",
                    "fab fa-google-drive",
                    "fab fa-google-play",
                    "fab fa-google-plus",
                    "fab fa-google-plus-g",
                    "fab fa-google-plus-square",
                    "fab fa-google-wallet",
                    "fas fa-graduation-cap",
                    "fab fa-gratipay",
                    "fab fa-grav",
                    "fab fa-gripfire",
                    "fab fa-grunt",
                    "fab fa-gulp",
                    "fas fa-h-square",
                    "fab fa-hacker-news",
                    "fab fa-hacker-news-square",
                    "fas fa-hand-lizard", "far fa-hand-lizard",
                    "fas fa-hand-paper", "far fa-hand-paper",
                    "fas fa-hand-peace", "far fa-hand-peace",
                    "fas fa-hand-point-down", "far fa-hand-point-down",
                    "fas fa-hand-point-left", "far fa-hand-point-left",
                    "fas fa-hand-point-right", "far fa-hand-point-right",
                    "fas fa-hand-point-up", "far fa-hand-point-up",
                    "fas fa-hand-pointer", "far fa-hand-pointer",
                    "fas fa-hand-rock", "far fa-hand-rock",
                    "fas fa-hand-scissors", "far fa-hand-scissors",
                    "fas fa-hand-spock", "far fa-hand-spock",
                    "fas fa-handshake", "far fa-handshake",
                    "fas fa-hashtag",
                    "fas fa-hdd", "far fa-hdd",
                    "fas fa-heading",
                    "fas fa-headphones",
                    "fas fa-heart", "far fa-heart",
                    "fas fa-heartbeat",
                    "fab fa-hire-a-helper",
                    "fas fa-history",
                    "fas fa-home",
                    "fab fa-hooli",
                    "fas fa-hospital", "far fa-hospital",
                    "fab fa-hotjar",
                    "fas fa-hourglass", "far fa-hourglass",
                    "fas fa-hourglass-end",
                    "fas fa-hourglass-half",
                    "fas fa-hourglass-start",
                    "fab fa-houzz",
                    "fab fa-html5",
                    "fab fa-hubspot",
                    "fas fa-i-cursor",
                    "fas fa-id-badge", "far fa-id-badge",
                    "fas fa-id-card", "far fa-id-card",
                    "fas fa-image", "far fa-image",
                    "fas fa-images", "far fa-images",
                    "fab fa-imdb",
                    "fas fa-inbox",
                    "fas fa-indent",
                    "fas fa-industry",
                    "fas fa-info",
                    "fas fa-info-circle",
                    "fab fa-instagram",
                    "fab fa-internet-explorer",
                    "fab fa-ioxhost",
                    "fas fa-italic",
                    "fab fa-itunes",
                    "fab fa-itunes-note",
                    "fab fa-jenkins",
                    "fab fa-joget",
                    "fab fa-joomla",
                    "fab fa-js",
                    "fab fa-js-square",
                    "fab fa-jsfiddle",
                    "fas fa-key",
                    "fas fa-keyboard", "far fa-keyboard",
                    "fab fa-keycdn",
                    "fab fa-kickstarter",
                    "fab fa-kickstarter-k",
                    "fas fa-language",
                    "fas fa-laptop",
                    "fab fa-laravel",
                    "fab fa-lastfm",
                    "fab fa-lastfm-square",
                    "fas fa-leaf",
                    "fab fa-leanpub",
                    "fas fa-lemon", "far fa-lemon",
                    "fab fa-less",
                    "fas fa-level-down-alt",
                    "fas fa-level-up-alt",
                    "fas fa-life-ring", "far fa-life-ring",
                    "fas fa-lightbulb", "far fa-lightbulb",
                    "fab fa-line",
                    "fas fa-link",
                    "fab fa-linkedin",
                    "fab fa-linkedin-in",
                    "fab fa-linode",
                    "fab fa-linux",
                    "fas fa-lira-sign",
                    "fas fa-list",
                    "fas fa-list-alt", "far fa-list-alt",
                    "fas fa-list-ol",
                    "fas fa-list-ul",
                    "fas fa-location-arrow",
                    "fas fa-lock",
                    "fas fa-lock-open",
                    "fas fa-long-arrow-alt-down",
                    "fas fa-long-arrow-alt-left",
                    "fas fa-long-arrow-alt-right",
                    "fas fa-long-arrow-alt-up",
                    "fas fa-low-vision",
                    "fab fa-lyft",
                    "fab fa-magento",
                    "fas fa-magic",
                    "fas fa-magnet",
                    "fas fa-male",
                    "fas fa-map", "far fa-map",
                    "fas fa-map-marker",
                    "fas fa-map-marker-alt",
                    "fas fa-map-pin",
                    "fas fa-map-signs",
                    "fas fa-mars",
                    "fas fa-mars-double",
                    "fas fa-mars-stroke",
                    "fas fa-mars-stroke-h",
                    "fas fa-mars-stroke-v",
                    "fab fa-maxcdn",
                    "fab fa-medapps",
                    "fab fa-medium",
                    "fab fa-medium-m",
                    "fas fa-medkit",
                    "fab fa-medrt",
                    "fab fa-meetup",
                    "fas fa-meh", "far fa-meh",
                    "fas fa-mercury",
                    "fas fa-microchip",
                    "fas fa-microphone",
                    "fas fa-microphone-slash",
                    "fab fa-microsoft",
                    "fas fa-minus",
                    "fas fa-minus-circle",
                    "fas fa-minus-square", "far fa-minus-square",
                    "fab fa-mix",
                    "fab fa-mixcloud",
                    "fab fa-mizuni",
                    "fas fa-mobile",
                    "fas fa-mobile-alt",
                    "fab fa-modx",
                    "fab fa-monero",
                    "fas fa-money-bill-alt", "far fa-money-bill-alt",
                    "fas fa-moon", "far fa-moon",
                    "fas fa-motorcycle",
                    "fas fa-mouse-pointer",
                    "fas fa-music",
                    "fab fa-napster",
                    "fas fa-neuter",
                    "fas fa-newspaper", "far fa-newspaper",
                    "fab fa-nintendo-switch",
                    "fab fa-node",
                    "fab fa-node-js",
                    "fab fa-npm",
                    "fab fa-ns8",
                    "fab fa-nutritionix",
                    "fas fa-object-group", "far fa-object-group",
                    "fas fa-object-ungroup", "far fa-object-ungroup",
                    "fab fa-odnoklassniki",
                    "fab fa-odnoklassniki-square",
                    "fab fa-opencart",
                    "fab fa-openid",
                    "fab fa-opera",
                    "fab fa-optin-monster",
                    "fab fa-osi",
                    "fas fa-outdent",
                    "fab fa-page4",
                    "fab fa-pagelines",
                    "fas fa-paint-brush",
                    "fab fa-palfed",
                    "fas fa-paper-plane", "far fa-paper-plane",
                    "fas fa-paperclip",
                    "fas fa-paragraph",
                    "fas fa-paste",
                    "fab fa-patreon",
                    "fas fa-pause",
                    "fas fa-pause-circle", "far fa-pause-circle",
                    "fas fa-paw",
                    "fab fa-paypal",
                    "fas fa-pen-square",
                    "fas fa-pencil-alt",
                    "fas fa-percent",
                    "fab fa-periscope",
                    "fab fa-phabricator",
                    "fab fa-phoenix-framework",
                    "fas fa-phone",
                    "fas fa-phone-square",
                    "fas fa-phone-volume",
                    "fab fa-pied-piper",
                    "fab fa-pied-piper-alt",
                    "fab fa-pied-piper-pp",
                    "fab fa-pinterest",
                    "fab fa-pinterest-p",
                    "fab fa-pinterest-square",
                    "fas fa-plane",
                    "fas fa-play",
                    "fas fa-play-circle", "far fa-play-circle",
                    "fab fa-playstation",
                    "fas fa-plug",
                    "fas fa-plus",
                    "fas fa-plus-circle",
                    "fas fa-plus-square", "far fa-plus-square",
                    "fas fa-podcast",
                    "fas fa-pound-sign",
                    "fas fa-power-off",
                    "fas fa-print",
                    "fab fa-product-hunt",
                    "fab fa-pushed",
                    "fas fa-puzzle-piece",
                    "fab fa-python",
                    "fab fa-qq",
                    "fas fa-qrcode",
                    "fas fa-question",
                    "fas fa-question-circle", "far fa-question-circle",
                    "fab fa-quora",
                    "fas fa-quote-left",
                    "fas fa-quote-right",
                    "fas fa-random",
                    "fab fa-ravelry",
                    "fab fa-react",
                    "fab fa-rebel",
                    "fas fa-recycle",
                    "fab fa-red-river",
                    "fab fa-reddit",
                    "fab fa-reddit-alien",
                    "fab fa-reddit-square",
                    "fas fa-redo",
                    "fas fa-redo-alt",
                    "fas fa-registered", "far fa-registered",
                    "fab fa-rendact",
                    "fab fa-renren",
                    "fas fa-reply",
                    "fas fa-reply-all",
                    "fab fa-replyd",
                    "fab fa-resolving",
                    "fas fa-retweet",
                    "fas fa-road",
                    "fas fa-rocket",
                    "fab fa-rocketchat",
                    "fab fa-rockrms",
                    "fas fa-rss",
                    "fas fa-rss-square",
                    "fas fa-ruble-sign",
                    "fas fa-rupee-sign",
                    "fab fa-safari",
                    "fab fa-sass",
                    "fas fa-save", "far fa-save",
                    "fab fa-schlix",
                    "fab fa-scribd",
                    "fas fa-search",
                    "fas fa-search-minus",
                    "fas fa-search-plus",
                    "fab fa-searchengin",
                    "fab fa-sellcast",
                    "fab fa-sellsy",
                    "fas fa-server",
                    "fab fa-servicestack",
                    "fas fa-share",
                    "fas fa-share-alt",
                    "fas fa-share-alt-square",
                    "fas fa-share-square", "far fa-share-square",
                    "fas fa-shekel-sign",
                    "fas fa-shield-alt",
                    "fas fa-ship",
                    "fab fa-shirtsinbulk",
                    "fas fa-shopping-bag",
                    "fas fa-shopping-basket",
                    "fas fa-shopping-cart",
                    "fas fa-shower",
                    "fas fa-sign-in-alt",
                    "fas fa-sign-language",
                    "fas fa-sign-out-alt",
                    "fas fa-signal",
                    "fab fa-simplybuilt",
                    "fab fa-sistrix",
                    "fas fa-sitemap",
                    "fab fa-skyatlas",
                    "fab fa-skype",
                    "fab fa-slack",
                    "fab fa-slack-hash",
                    "fas fa-sliders-h",
                    "fab fa-slideshare",
                    "fas fa-smile", "far fa-smile",
                    "fab fa-snapchat",
                    "fab fa-snapchat-ghost",
                    "fab fa-snapchat-square",
                    "fas fa-snowflake", "far fa-snowflake",
                    "fas fa-sort",
                    "fas fa-sort-alpha-down",
                    "fas fa-sort-alpha-up",
                    "fas fa-sort-amount-down",
                    "fas fa-sort-amount-up",
                    "fas fa-sort-down",
                    "fas fa-sort-numeric-down",
                    "fas fa-sort-numeric-up",
                    "fas fa-sort-up",
                    "fab fa-soundcloud",
                    "fas fa-space-shuttle",
                    "fab fa-speakap",
                    "fas fa-spinner",
                    "fab fa-spotify",
                    "fas fa-square", "far fa-square",
                    "fab fa-stack-exchange",
                    "fab fa-stack-overflow",
                    "fas fa-star", "far fa-star",
                    "fas fa-star-half", "far fa-star-half",
                    "fab fa-staylinked",
                    "fab fa-steam",
                    "fab fa-steam-square",
                    "fab fa-steam-symbol",
                    "fas fa-step-backward",
                    "fas fa-step-forward",
                    "fas fa-stethoscope",
                    "fab fa-sticker-mule",
                    "fas fa-sticky-note", "far fa-sticky-note",
                    "fas fa-stop",
                    "fas fa-stop-circle", "far fa-stop-circle",
                    "fab fa-strava",
                    "fas fa-street-view",
                    "fas fa-strikethrough",
                    "fab fa-stripe",
                    "fab fa-stripe-s",
                    "fab fa-studiovinari",
                    "fab fa-stumbleupon",
                    "fab fa-stumbleupon-circle",
                    "fas fa-subscript",
                    "fas fa-subway",
                    "fas fa-suitcase",
                    "fas fa-sun", "far fa-sun",
                    "fab fa-superpowers",
                    "fas fa-superscript",
                    "fab fa-supple",
                    "fas fa-sync",
                    "fas fa-sync-alt",
                    "fas fa-table",
                    "fas fa-tablet",
                    "fas fa-tablet-alt",
                    "fas fa-tachometer-alt",
                    "fas fa-tag",
                    "fas fa-tags",
                    "fas fa-tasks",
                    "fas fa-taxi",
                    "fab fa-telegram",
                    "fab fa-telegram-plane",
                    "fab fa-tencent-weibo",
                    "fas fa-terminal",
                    "fas fa-text-height",
                    "fas fa-text-width",
                    "fas fa-th",
                    "fas fa-th-large",
                    "fas fa-th-list",
                    "fab fa-themeisle",
                    "fas fa-thermometer-empty",
                    "fas fa-thermometer-full",
                    "fas fa-thermometer-half",
                    "fas fa-thermometer-quarter",
                    "fas fa-thermometer-three-quarters",
                    "fas fa-thumbs-down", "far fa-thumbs-down",
                    "fas fa-thumbs-up", "far fa-thumbs-up",
                    "fas fa-thumbtack",
                    "fas fa-ticket-alt",
                    "fas fa-times",
                    "fas fa-times-circle", "far fa-times-circle",
                    "fas fa-tint",
                    "fas fa-toggle-off",
                    "fas fa-toggle-on",
                    "fas fa-trademark",
                    "fas fa-train",
                    "fas fa-transgender",
                    "fas fa-transgender-alt",
                    "fas fa-trash",
                    "fas fa-trash-alt", "far fa-trash-alt",
                    "fas fa-tree",
                    "fab fa-trello",
                    "fab fa-tripadvisor",
                    "fas fa-trophy",
                    "fas fa-truck",
                    "fas fa-tty",
                    "fab fa-tumblr",
                    "fab fa-tumblr-square",
                    "fas fa-tv",
                    "fab fa-twitch",
                    "fab fa-twitter",
                    "fab fa-twitter-square",
                    "fab fa-typo3",
                    "fab fa-uber",
                    "fab fa-uikit",
                    "fas fa-umbrella",
                    "fas fa-underline",
                    "fas fa-undo",
                    "fas fa-undo-alt",
                    "fab fa-uniregistry",
                    "fas fa-universal-access",
                    "fas fa-university",
                    "fas fa-unlink",
                    "fas fa-unlock",
                    "fas fa-unlock-alt",
                    "fab fa-untappd",
                    "fas fa-upload",
                    "fab fa-usb",
                    "fas fa-user", "far fa-user",
                    "fas fa-user-circle", "far fa-user-circle",
                    "fas fa-user-md",
                    "fas fa-user-plus",
                    "fas fa-user-secret",
                    "fas fa-user-times",
                    "fas fa-users",
                    "fab fa-ussunnah",
                    "fas fa-utensil-spoon",
                    "fas fa-utensils",
                    "fab fa-vaadin",
                    "fas fa-venus",
                    "fas fa-venus-double",
                    "fas fa-venus-mars",
                    "fab fa-viacoin",
                    "fab fa-viadeo",
                    "fab fa-viadeo-square",
                    "fab fa-viber",
                    "fas fa-video",
                    "fab fa-vimeo",
                    "fab fa-vimeo-square",
                    "fab fa-vimeo-v",
                    "fab fa-vine",
                    "fab fa-vk",
                    "fab fa-vnv",
                    "fas fa-volume-down",
                    "fas fa-volume-off",
                    "fas fa-volume-up",
                    "fab fa-vuejs",
                    "fab fa-weibo",
                    "fab fa-weixin",
                    "fab fa-whatsapp",
                    "fab fa-whatsapp-square",
                    "fas fa-wheelchair",
                    "fab fa-whmcs",
                    "fas fa-wifi",
                    "fab fa-wikipedia-w",
                    "fas fa-window-close", "far fa-window-close",
                    "fas fa-window-maximize", "far fa-window-maximize",
                    "fas fa-window-minimize",
                    "fas fa-window-restore", "far fa-window-restore",
                    "fab fa-windows",
                    "fas fa-won-sign",
                    "fab fa-wordpress",
                    "fab fa-wordpress-simple",
                    "fab fa-wpbeginner",
                    "fab fa-wpexplorer",
                    "fab fa-wpforms",
                    "fas fa-wrench",
                    "fab fa-xbox",
                    "fab fa-xing",
                    "fab fa-xing-square",
                    "fab fa-y-combinator",
                    "fab fa-yahoo",
                    "fab fa-yandex",
                    "fab fa-yandex-international",
                    "fab fa-yelp",
                    "fas fa-yen-sign",
                    "fab fa-yoast",
                    "fab fa-youtube"
                  );
  }
}

/**
 * Custom Control for Customizer Page Layout Settings
*/
if( class_exists( 'WP_Customize_control') ) {
    
    /**
     * Page/Post Layout Controller
     *
     * @since 1.0.0
    */
    class Sparklestore_Customize_Control_Radio_Image extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $type = 'radio-image';

        /**
         * Loads the jQuery UI Button script and custom scripts/styles.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
        }

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();

            // We need to make sure we have the correct image URL.
            foreach ( $this->choices as $value => $args )
                $this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );

            $this->json['choices'] = $this->choices;
            $this->json['link']    = $this->get_link();
            $this->json['value']   = $this->value();
            $this->json['id']      = $this->id;
        }


        /**
         * Underscore JS template to handle the control's output.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */

        public function content_template() { ?>
            <# if ( data.label ) { #>
                <span class="customize-control-title">{{ data.label }}</span>
            <# } #>

            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <div class="buttonset">

                <# for ( key in data.choices ) { #>
                    <div class="img-btn-wrap <# if ( key === data.value ) { #> active <# } #>">
                    <input type="radio" value="{{ key }}" name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked="checked" <# } #> /> 

                    <label for="{{ data.id }}-{{ key }}">
                        <span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
                        <img src="{{ data.choices[ key ]['url'] }}" title="{{ data.choices[ key ]['label'] }}" alt="{{ data.choices[ key ]['label'] }}" />
                    </label>
                    </div>
                <# } #>

            </div><!-- .buttonset -->
        <?php }
    }

    /**
     * Switch Controller ( Enable or Disable )
    */
    class Sparklestore_Switch_Control extends WP_Customize_Control{

        public $type = 'switch';

        public $on_off_label = array();

        public function __construct($manager, $id, $args = array() ){
            $this->on_off_label = $args['on_off_label'];
            parent::__construct( $manager, $id, $args );
        }

        public function render_content(){
        ?>
            <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
            </span>

            <?php if($this->description){ ?>
                <span class="description customize-control-description">
                  <?php echo wp_kses_post($this->description); ?>
                </span>
            <?php } ?>

            <?php
                $switch_class = ($this->value() == 'on') ? 'switch-on' : '';
                $on_off_label = $this->on_off_label;
            ?>
            <div class="onoffswitch <?php echo esc_attr( $switch_class ); ?>">
                <div class="onoffswitch-inner">
                    <div class="onoffswitch-active">
                        <div class="onoffswitch-switch"><?php echo esc_html($on_off_label['on']) ?></div>
                    </div>

                    <div class="onoffswitch-inactive">
                        <div class="onoffswitch-switch"><?php echo esc_html($on_off_label['off']) ?></div>
                    </div>
                </div>  
            </div>
            <input <?php $this->link(); ?> type="hidden" value="<?php echo esc_attr($this->value()); ?>"/>
            <?php
        }
    }
    
    /**
     * Important Link Information
    */
    class Sparklestore_theme_Info_Text extends WP_Customize_Control{
        public function render_content(){  ?>
            <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
            </span>
            <?php if($this->description){ ?>
                <span class="description customize-control-description">
                  <?php echo wp_kses_post($this->description); ?>
                </span>
            <?php }
        }
    }

    class Sparklestore_Repeater_Controler extends WP_Customize_Control {
      /**
       * The control type.
       *
       * @access public
       * @var string
      */
      public $type = 'repeater';

      public $sparklestore_box_label = '';

      public $sparklestore_box_add_control = '';

      private $cats = '';

      /**
       * The fields that each container row will contain.
       *
       * @access public
       * @var array
      */
      public $fields = array();

      /**
       * Repeater drag and drop controler
       *
       * @since  1.0.0
      */
      public function __construct( $manager, $id, $args = array(), $fields = array() ) {
        $this->fields = $fields;
        $this->sparklestore_box_label = $args['sparklestore_box_label'] ;
        $this->sparklestore_box_add_control = $args['sparklestore_box_add_control'];
        $this->cats = get_categories(array( 'hide_empty' => false ));
        parent::__construct( $manager, $id, $args );
      }

      public function render_content() {
        $values = json_decode($this->value());
        ?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php if($this->description){ ?>
          <span class="description customize-control-description">
          <?php echo wp_kses_post($this->description); ?>
          </span>
        <?php } ?>

        <ul class="sparklestore-repeater-field-control-wrap">
          <?php $this->sparklestore_get_fields(); ?>
        </ul>
        <input type="hidden" <?php esc_attr( $this->link() ); ?> class="sparklestore-repeater-collector" value="<?php echo esc_attr( $this->value() ); ?>" />
        <button type="button" class="button sparklestore-add-control-field"><?php echo esc_html( $this->sparklestore_box_add_control ); ?></button>
        <?php
      }

      private function sparklestore_get_fields(){
        $fields = $this->fields;
        $values = json_decode($this->value());
        if(is_array($values)){
        foreach($values as $value){    ?>
          <li class="sparklestore-repeater-field-control">
            <h3 class="sparklestore-repeater-field-title accordion-section-title"><?php echo esc_html( $this->sparklestore_box_label ); ?></h3>
            <div class="sparklestore-repeater-fields">
              <?php
                foreach ($fields as $key => $field) {
                $class = isset($field['class']) ? $field['class'] : '';
              ?>
                <div class="sparklestore-fields sparklestore-type-<?php echo esc_attr( $field['type'] ).' '.esc_attr( $class ); ?>">
                  <?php 
                    $label = isset($field['label']) ? $field['label'] : '';
                    $description = isset($field['description']) ? $field['description'] : '';
                    if($field['type'] != 'checkbox'){ ?>
                      <span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
                      <span class="description customize-control-description"><?php echo esc_html( $description ); ?></span>
                  <?php }

                    $new_value = isset($value->$key) ? $value->$key : '';
                    $default = isset($field['default']) ? $field['default'] : '';

                    switch ($field['type']) {
                      case 'text':
                        echo '<input data-default="'.esc_attr($default).'" data-name="'.esc_attr($key).'" type="text" value="'.esc_attr($new_value).'"/>';
                        break;

                      case 'textarea':
                        echo '<textarea data-default="'.esc_attr($default).'"  data-name="'.esc_attr($key).'">'.esc_textarea($new_value).'</textarea>';
                        break;

                      case 'icon':
                        echo '<div class="sparklestore-selected-icon">';
                            echo '<i class="'.esc_html($new_value).'"></i>';
                            echo '<span><i class="fa fa-angle-down"></i></span>';
                        echo '</div>';
                        echo '<ul class="sparklestore-icon-list clearfix">';
                            $sparklestore_font_awesome_icon_array = sparklestore_font_awesome_icon_array();
                            foreach ($sparklestore_font_awesome_icon_array as $sparklestore_font_awesome_icon) {
                                $icon_class = $new_value == $sparklestore_font_awesome_icon ? 'icon-active' : '';
                                echo '<li class='.esc_attr( $icon_class ).'><i class="'.esc_attr( $sparklestore_font_awesome_icon ).'"></i></li>';
                            }
                        echo '</ul>';
                        echo '<input data-default="'.esc_attr($default).'" type="hidden" value="'.esc_attr($new_value).'" data-name="'.esc_attr($key).'"/>';
                        break;

                      case 'select':
                        $options = $field['options'];
                        echo '<select  data-default="'.esc_attr($default).'"  data-name="'.esc_attr($key).'">';
                              foreach ( $options as $option => $val )
                              {
                                  printf('<option value="%s" %s>%s</option>', esc_attr($option), selected($new_value, $option, false), esc_html($val));
                              }
                        echo '</select>';
                        break;

                      default:
                        break;
                    }
                  ?>
                </div>
              <?php } ?>
              <div class="clearfix sparklestore-repeater-footer">
                <div class="alignright">
                  <a class="sparklestore-repeater-field-remove" href="#remove"><?php esc_html_e('Delete', 'sparklestore') ?></a> |
                  <a class="sparklestore-repeater-field-close" href="#close"><?php esc_html_e('Close', 'sparklestore') ?></a>
                </div>
              </div>
            </div>
          </li>
        <?php }
        }
      }

    }

    /**
     * Multiple Check
    */
    class sparklestore_Multiple_Check_Control extends WP_Customize_Control {
        /**
          * The type of customize control being rendered.
          *
          * @since  1.0.0
          * @access public
          * @var    string
        */
        public $type = 'checkbox-multiple';

        /**
          * Displays the control content.
          *
          * @since  1.0.0
          * @access public
          * @return void
        */
        public function render_content() {

          if ( empty( $this->choices ) )

           return; ?>
         
            <?php if ( !empty( $this->label ) ) : ?>
              <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>

            <?php if ( !empty( $this->description ) ) : ?>
              <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>

            <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
            <ul>
              <?php foreach ( $this->choices as $value => $label ) : ?>
                  <li>
                      <label>
                          <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
                          <?php echo esc_html( $label ); ?>
                      </label>
                  </li>
              <?php endforeach; ?>
            </ul>
            <input type="hidden" <?php esc_url( $this->link() ); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
       <?php } 
    }
}

/**
 * Page and Post Page Display Layout Metabox function
*/
add_action('add_meta_boxes', 'sparklestore_metabox_section');
if ( ! function_exists( 'sparklestore_metabox_section' ) ) {
    function sparklestore_metabox_section(){   
        add_meta_box('sparklestore_display_layout', 
            esc_html__( 'Display Layout Options', 'sparklestore' ), 
            'sparklestore_display_layout_callback', 
            array('page','post'), 
            'normal', 
            'high'
        );
    }
}

$sparklestore_page_layouts =array(

    'leftsidebar' => array(
        'value'     => 'leftsidebar',
        'label'     => esc_html__( 'Left Sidebar', 'sparklestore' ),
        'thumbnail' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
    ),
    'rightsidebar' => array(
        'value'     => 'rightsidebar',
        'label'     => esc_html__( 'Right (Default)', 'sparklestore' ),
        'thumbnail' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
    ),
     'nosidebar' => array(
        'value'     => 'nosidebar',
        'label'     => esc_html__( 'Full width', 'sparklestore' ),
        'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar.png',
    ),
    'bothsidebar' => array(
        'value'     => 'bothsidebar',
        'label'     => esc_html__( 'Both Sidebar', 'sparklestore' ),
        'thumbnail' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
    )
);

/**
 * Function for Page layout meta box
*/
if ( ! function_exists( 'sparklestore_display_layout_callback' ) ) {
    function sparklestore_display_layout_callback(){
        global $post, $sparklestore_page_layouts;
        wp_nonce_field( basename( __FILE__ ), 'sparklestore_settings_nonce' ); ?>
        <table>
            <tr>
              <td>            
                <?php
                  $i = 0;  
                  foreach ($sparklestore_page_layouts as $field) {  
                  $sparklestore_page_metalayouts = esc_attr( get_post_meta( $post->ID, 'sparklestore_page_layouts', true ) ); 
                ?>            
                  <div class="radio-image-wrapper slidercat" id="slider-<?php echo intval( $i ); ?>" style="float:left; margin-right:30px;">
                    <label class="description">
                        <span>
                          <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" />
                        </span></br>
                        <input type="radio" name="sparklestore_page_layouts" value="<?php echo esc_html( $field['value'] ); ?>" <?php checked( esc_html( $field['value'] ), 
                            $sparklestore_page_metalayouts ); if(empty($sparklestore_page_metalayouts) && esc_html( $field['value'] )=='rightsidebar'){ echo "checked='checked'";  } ?>/>
                         <?php echo esc_html( $field['label'] ); ?>
                    </label>
                  </div>
                <?php  $i++; }  ?>
              </td>
            </tr>            
        </table>
    <?php
    }
}

/**
 * Save the custom metabox data
*/
if ( ! function_exists( 'sparklestore_save_page_settings' ) ) {
    function sparklestore_save_page_settings( $post_id ) { 
        global $sparklestore_page_layouts, $post; 
        if ( !isset( $_POST[ 'sparklestore_settings_nonce' ] ) || !wp_verify_nonce( sanitize_key( $_POST[ 'sparklestore_settings_nonce' ] ) , basename( __FILE__ ) ) )
            return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
            return;  

        if (isset( $_POST['post_type'] ) && 'page' == $_POST['post_type']) {  
            if (!current_user_can( 'edit_page', $post_id ) )  
                return $post_id;  
        } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
                return $post_id;  
        } 

        foreach ($sparklestore_page_layouts as $field) {  
            
            $old = esc_attr( get_post_meta( $post_id, 'sparklestore_page_layouts', true) ); 
            
            if ( isset( $_POST['sparklestore_page_layouts']) ) { 
                $new = sanitize_text_field( wp_unslash( $_POST['sparklestore_page_layouts'] ) );
            }
            if ($new && $new != $old) {  
                update_post_meta($post_id, 'sparklestore_page_layouts', $new);  
            } elseif ('' == $new && $old) {  
                delete_post_meta($post_id,'sparklestore_page_layouts', $old);  
            } 
         } 
    }
}
add_action('save_post', 'sparklestore_save_page_settings');



/**
 * Sparklestore_breadcrumbs
*/
if ( ! function_exists( 'sparklestore_breadcrumbs' ) ) {

    function sparklestore_breadcrumbs(){ 

       $bg_image = esc_url( get_theme_mod('sparklestore_breadcrumbs_normal_page_background_image') );
    ?>
        <div class="breadcrumbs-wrap breadcrumbs <?php if(!empty( $bg_image )){ echo esc_attr( 'withimage' ); } ?>" <?php if(!empty( $bg_image )){ ?> style="background:url('<?php echo esc_url( $bg_image ); ?>') no-repeat center; background-size: cover; background-attachment:fixed;" <?php } ?>>
            <div class="container">
                <?php if( class_exists('WooCommerce') && ( is_shop() || is_archive() ) ){ ?>

                      <h2 class="page-title"><?php woocommerce_page_title(); ?></h2>

                <?php
                    }elseif( is_archive() || is_category() ) {

                        the_archive_title( '<h2 class="entry-title">', '</h2>' );

                    }elseif( is_search() ){ ?>

                        <h2 class="entry-title"><?php printf( esc_html__( 'Search Results for : %s', 'sparklestore' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
                    
                    <?php }elseif( is_404() ){ ?>

                        <h2 class="entry-title"><?php echo esc_html__('404','sparklestore'); ?></h2>

                    <?php }else{

                        the_title( '<h2 class="entry-title">', '</h2>' ); 
                    }

                    breadcrumb_trail( array(
                      'container'   => 'div',
                      'show_browse' => false,
                    ) );
                ?>
            </div>
        </div>
      <?php 
    } 
}
add_action( 'sparklestore-breadcrumbs', 'sparklestore_breadcrumbs' );


/**
 * Descriptions on Header Menu
 * @author Sparkle themes
 * @param string $item_output , HTML outputp for the menu item
 * @param object $item , menu item object
 * @param int $depth , depth in menu structure
 * @param object $args , arguments passed to wp_nav_menu()
 * @return string $item_output
 */
function sparklestore_header_menu_desc($item_output, $item, $depth, $args){

    if ('sparkleprimary' == $args->theme_location && $item->description)

        $item_output = str_replace('</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output);

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'sparklestore_header_menu_desc', 10, 4);