<?php
if ( ! function_exists( 'sparklestore_ecommerce_items' ) ) :
    /**
     * eCommerce Items
     *
     * @since 1.0.0
     */
    function sparklestore_ecommerce_items() { ?>

        <ul class="sparklestore_ecommerce_items">
            
            <?php if (is_user_logged_in()) { ?>

                <li>
                    <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><i class="fab fa-opencart"></i><span class="name-text"><?php esc_html_e('My Account','sparklestore'); ?></span></a>
                </li>

                <li>
                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><i class="fas fa-sign-out-alt"></i><span class="name-text"><?php esc_html_e('Logout','sparklestore'); ?></span></a>
                </li> 

            <?php } else{ ?>

                <li>
                    <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"><i class="fas fa-sign-in-alt"></i><span class="name-text"><?php esc_html_e('Login / Signup','sparklestore'); ?></span></a>
                </li>

            <?php } if(function_exists( 'sparklestore_products_wishlist' )) { ?>

                <li>                                    
                    <?php sparklestore_products_wishlist(); ?>
                </li>

            <?php } ?>

        </ul>
    <?php }

endif;

/**
 * Header Type to Shopping Cart function 
*/
if (!function_exists('sparklestore_shopping_cart')) {

    function sparklestore_shopping_cart() {

        global $woocommerce; ?>

        <div class="shopcart-dropdown block-cart-link">
           <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'sparklestore' ); ?>">
                <div class="site-cart-items-wrap">
                    <div class="cart-icon icofont-cart-alt"></div>
                    <span class="count"><?php echo intval( WC()->cart->cart_contents_count ); ?></span>
                    <span class="item"><?php echo wp_kses_post( $woocommerce->cart->get_cart_subtotal() ); ?></span>
                </div>
            </a>
        </div> 
               
        <?php
    }

    if ( ! function_exists( 'sparklestore_cart_link_fragment' ) ) {
        function sparklestore_cart_link_fragment( $fragments ) {
            global $woocommerce;
            ob_start();
            sparklestore_shopping_cart();
            $fragments['a.cart-contents'] = ob_get_clean();
            return $fragments;
        }
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'sparklestore_cart_link_fragment' );
}


/**
 * Links 
 */
if(!function_exists('sparklestore_add_to_cart_links')){
	function sparklestore_add_to_cart_links(){ ?>
		<div class="store_products_items_info2">
			<?php if( function_exists( 'sparklestore_quickview' ) || function_exists( 'sparklestore_add_compare_link2' ) || function_exists( 'sparklestore_wishlist_products' ) ): ?>
			<div class="appzend-buttons-wrapper">
				<?php if(function_exists( 'sparklestore_quickview' )) { ?>
					<div class="products_item_info">
						<?php  sparklestore_quickview(1); ?>
					</div>
				<?php } ?>

				<?php if(function_exists( 'sparklestore_add_compare_link2' )) { ?>
					<div class="products_item_info">
						<?php  sparklestore_add_compare_link2(1); ?>
					</div>
				<?php } ?>

				<?php if(function_exists( 'sparklestore_wishlist_products' )) { ?>
					<div class="products_item_info">
						<?php  sparklestore_wishlist_products(1); ?>
					</div>
				<?php } ?>
			</div>
			<?php endif; ?>

			<div class="appzend-add-to-cart">
				<span class="products_item_info"> 
					<?php
						/**
						 * woocommerce_template_loop_add_to_cart
						*/
						woocommerce_template_loop_add_to_cart();
					?>
				</span>
			</div>

		</div>
		
		<?php
	}
	add_action( 'woocommerce_after_shop_loop_item', 'sparklestore_add_to_cart_links', 15 );
}

/**
 * Product wishlist button function area
*/
if (defined('YITH_WCWL')) {
    
    /**
     * Wishlist Header Count Ajax Function
    */
    if (!function_exists('sparklestore_products_wishlist')) {

        function sparklestore_products_wishlist() {
            if (function_exists('YITH_WCWL')) {
                $wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
                <div class="top-wishlist text-right">
                    <a href="<?php echo esc_url( $wishlist_url ); ?>" title="Wishlist" data-toggle="tooltip">
                        <i class="fa fa-heart"></i>
                        <span class="title-wishlist name-text">
                            <?php esc_html_e('Wishlist', 'sparklestore'); ?>
                        </span>
                        <span class="count">
                            <span class="bigcounter">
                                <span><?php echo " ( " . intval( yith_wcwl_count_products() ) . " ) "; ?></span>
                            </span>
                        </span>
                    </a>
                </div>
                <?php
            }
        }
    }
    add_action('wp_ajax_yith_wcwl_update_single_product_list', 'sparklestore_products_wishlist');
    add_action('wp_ajax_nopriv_yith_wcwl_update_single_product_list', 'sparklestore_products_wishlist');


    function sparklestore_wishlist_products() {

        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );

    }

    /**
     * define the yith-wcwl-browse-wishlist-label callback
    */
    function sparklestore_filter_yith_wcwl_browse_wishlist_label( $var ) { 

        return '<span class="sparkle-tooltip-label">'.$var.'</span>';

    }; 
    add_filter( 'yith-wcwl-browse-wishlist-label', 'sparklestore_filter_yith_wcwl_browse_wishlist_label', 10, 1 );

}

/**
 * Add the link to compare function area
*/
if (defined('YITH_WOOCOMPARE')) {

    function sparklestore_add_compare_link($product_id = false, $args = array()) {
        extract($args);
        if (!$product_id) {
            global $product;
            $productid = $product->get_id();
            $product_id = isset($productid) ? $productid : 0;
        }
        $is_button = !isset($button_or_link) || !$button_or_link ? get_option('yith_woocompare_is_button') : $button_or_link;

        if (!isset($button_text) || $button_text == 'default') {
            $button_text = get_option('yith_woocompare_button_text', esc_html__('Compare', 'sparklestore'));
            yit_wpml_register_string('Plugins', 'plugin_yit_compare_button_text', $button_text);
            $button_text = yit_wpml_string_translate('Plugins', 'plugin_yit_compare_button_text', $button_text);
        }
        printf('<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>', '#', 'compare link-compare',
        intval( $product_id ), '<span class="sparkle-tooltip-label">'.esc_html( $button_text ).'</span>'
        .esc_html( $button_text ) );
    }

    remove_action('woocommerce_after_shop_loop_item', array('YITH_Woocompare_Frontend', 'add_compare_link'), 20);

    function sparklestore_add_compare_link2($product_id = false, $args = array()) {
        extract($args);
        if (!$product_id) {
            global $product;
            $productid = $product->get_id();
            $product_id = isset($productid) ? $productid : 0;
        }
        $is_button = !isset($button_or_link) || !$button_or_link ? get_option('yith_woocompare_is_button') : $button_or_link;

        if (!isset($button_text) || $button_text == 'default') {
            $button_text = get_option('yith_woocompare_button_text', esc_html__('Compare', 'sparklestore'));
            yit_wpml_register_string('Plugins', 'plugin_yit_compare_button_text', $button_text);
            $button_text = yit_wpml_string_translate('Plugins', 'plugin_yit_compare_button_text', $button_text);
        }
        printf('<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s<i class="fas fa-random"></i></a>', '#', 'compare link-compare',
        intval( $product_id ), '<span class="sparkle-tooltip-label">'.esc_html( $button_text ).'</span>' );
    }
}

/**
 * Add the link to quickview function area
*/
if (defined('YITH_WCQV')) {
    function sparklestore_quickview($show_icon=false) {
        global $product;
        $quick_view = YITH_WCQV_Frontend();
        remove_action('woocommerce_after_shop_loop_item', array($quick_view, 'yith_add_quick_view_button'), 15);
        $label = get_option('yith-wcqv-button-label');
        $label_icon_or_text = $label;
        if($show_icon){
            $label_icon_or_text = '<i class="far fa-eye"></i>';
        }
        echo '<a href="#" class="link-quickview yith-wcqv-button" data-product_id="' . intval( $product->get_id() ) . '">
            <span class="sparkle-tooltip-label">'.esc_html( $label ).'</span>' 
            . force_balance_tags( $label_icon_or_text ) . 
        '</a>';
    }
}

/**
 * Advance Product function area
*/
if(!function_exists ('sparklestore_advance_product_search_form')){

    /**
     * Advance Search
     *
     * @since 1.0.0
    */
    function sparklestore_advance_product_search_form(){   

            $searchplaceholder = get_theme_mod('sparklestore_search_placeholder_text','I&#39;m searching for...' ); 
            
            $searchtype = get_theme_mod( 'sparklestore_search_options', 'advancesearch' );

            $selected     = '';
            
            if ( isset( $_GET['product_cat'] ) && sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) ) {

                $selected = sanitize_text_field( wp_unslash( $_GET['product_cat'] ) );

            }
            $args               = array(
                'show_option_none'  => esc_html__( 'All Categories', 'sparklestore' ),
                'taxonomy'          => 'product_cat',
                'class'             => 'category-search-option',
                'hide_empty'        => 1,
                'orderby'           => 'name',
                'order'             => "ASC",
                'tab_index'         => true,
                'hierarchical'      => true,
                'id'                => rand(),
                'name'              => 'product_cat',
                'value_field'       => 'slug',
                'selected'          => $selected,
                'option_none_value' => '0',
            );
        ?>
            <div class="block-search">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>" class="form-search block-search <?php echo esc_attr( $searchtype ); ?>">
                    <?php 
                        if( class_exists( 'WooCommerce' ) && !empty($searchtype) && $searchtype == 'advancesearch' ){
                    ?>
                        <input type="hidden" name="post_type" value="product"/>
                        <input type="hidden" name="taxonomy" value="product_cat">
                        <div class="form-content search-box results-search">
                            <div class="inner">
                                <input autocomplete="off" type="text" class="input searchfield txt-livesearch" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $searchplaceholder ); ?>">
                            </div>
                        </div>
                        <div class="category">
                            <?php wp_dropdown_categories( $args ); ?>
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="fa fa-search" aria-hidden="true"></span>
                        </button>
                    <?php } else{ ?>
                        <input type="hidden" name="post_type" value="post"/>
                        <div class="form-content search-box results-search">
                            <div class="inner">
                                <input autocomplete="off" type="text" class="input searchfield txt-livesearch" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $searchplaceholder ); ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="fa fa-search" aria-hidden="true"></span>
                        </button>
                    <?php } ?>
                </form><!-- block search -->
            </div>
        <?php
    }
}

/**
 * Sparkle Tabs Category Products Ajax Function
*/
if (!function_exists('sparklestore_tabs_ajax_action')) {

    function sparklestore_tabs_ajax_action() {
        
        if ( isset( $_POST['category_slug'] ) ) {
            $cat_slug       = sanitize_text_field( wp_unslash( $_POST['category_slug'] ) );
        }

        if ( isset( $_POST['product_num'] ) ) {
            $product_number = sanitize_text_field( wp_unslash( $_POST['product_num'] ) );
        }

        if ( isset( $_POST['block_layout'] ) ) {
            $block_layout = sanitize_text_field( wp_unslash( $_POST['block_layout'] ) );
        }
        
        ob_start(); ?>

        <div class="sparkletabproductarea">

            <ul class="<?php if( !empty( $block_layout ) && $block_layout == 'layout1' ){ echo esc_attr( 'tabsproduct cS-hidden' ); }else{ echo 'storeproductlist'; }  ?>">                            
                <?php
                    $product_args = array(
                        'post_type' => 'product',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $cat_slug
                            )),
                        'posts_per_page' => $product_number
                    );

                    $query = new WP_Query($product_args);

                    if ($query->have_posts()) { 
                        while ($query->have_posts()) { $query->the_post();
                           wc_get_template_part('content', 'product');
                        } 
                    }                     
                    wp_reset_postdata(); 
                ?>
            </ul>

        </div>
        <?php
            $sparkle_html = ob_get_contents();
            ob_get_clean();
            echo $sparkle_html;
            die();
    }
}
add_action('wp_ajax_sparklestore_tabs_ajax_action', 'sparklestore_tabs_ajax_action');
add_action('wp_ajax_nopriv_sparklestore_tabs_ajax_action', 'sparklestore_tabs_ajax_action');


/**
 * Percentage calculation function area
*/
if( !function_exists ('sparklestore_sale_percentage_loop') ){
    /**
     * Woocommerce Products Discount Show
     *
     * @since 1.0.0
    */
    function sparklestore_sale_percentage_loop() {

        global $product;
        
        if ( $product->is_on_sale() ) {
            
            if ( ! $product->is_type( 'variable' ) and $product->get_regular_price() and $product->get_sale_price() ) {
                
                $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
            
            } else {
                $max_percentage = 0;
                
                foreach ( $product->get_children() as $child_id ) {

                    $variation = wc_get_product( $child_id );

                    if( !$variation ) continue;
                    
                    $price = $variation->get_regular_price();

                    $sale = $variation->get_sale_price();

                    $percentage = '';

                    if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;

                        if ( $percentage > $max_percentage ) {
                            $max_percentage = $percentage;
                        }
                }
            
            }
            
            echo "<span class='on_sale'>" . esc_html( round( - $max_percentage ) ) . esc_html__("%", 'sparklestore')."</span>";
        
        }

    }
}

if(!function_exists('sparklestore_flash_sale_new_tag')){

    function sparklestore_flash_sale_new_tag(){

        echo '<span class="onnew"><span class="text">' . esc_html__( 'New!', 'sparklestore' ) . '</span></span>';
    }
}

if(!function_exists('sparklestore_flash_sale_tag')){

    function sparklestore_flash_sale_tag(){

        return '<span class="store_sale_label"><span class="text">' . esc_html__( 'Sale!', 'sparklestore' ) . '</span></span>';
    }
}

/**
 *  Remove WooCommerce Default Breadcrumb & Title
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_filter('woocommerce_show_page_title', '__return_false');


/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'sparklestore_woocommerce_wrapper_before' ) ) {
    /**
     * Before Content.
     *
     * Wraps all WooCommerce content in wrappers which match the theme markup.
     *
     * @return void
     */
    function sparklestore_woocommerce_wrapper_before() { ?>

        <?php do_action( 'sparklestore-breadcrumbs' ); ?>

        <div class="site-wrapper">

            <div class="container">

                <div id="primary" class="content-area">

                    <main id="main" class="site-main" role="main">

                <?php
    }
}
add_action( 'woocommerce_before_main_content', 'sparklestore_woocommerce_wrapper_before' );

if ( ! function_exists( 'sparklestore_woocommerce_wrapper_after' ) ) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function sparklestore_woocommerce_wrapper_after() { ?>

                    </main>

                </div>

                <?php get_sidebar('woocommerce'); ?>
            </div>
        </div>

        <?php
    }
}
add_action( 'woocommerce_after_main_content', 'sparklestore_woocommerce_wrapper_after' );


/********
 * Category List Title
 */
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

function sparklestore_template_loop_category_title( $category ) { ?> 
    <div class="products-cat-info">
        <h3 class="woocommerce-loop-category__title">
            <?php 
                echo $category->name; 
    
                if ( $category->count > 0 ) 
                    echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . ' '.esc_html__('Products','sparklestore').'</span>', $category ); 
            ?> 
        </h3> 
    </div>
    <?php 
} 
add_action( 'woocommerce_shop_loop_subcategory_title', 'sparklestore_template_loop_category_title', 10 );

/**
 * Remove Default Sidebar
*/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Remove Before & After Product Linbk
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

function sparklestore_woocommerce_template_loop_product_thumbnail(){ ?>

    <div class="product_wrapper">
        <div class="store_products_item">
            <div class="store_products_item_body">
                <?php
                    global $post, $product, $product_label_custom; 

                    $sale_class = '';
                    if( $product->is_on_sale() == 1 ){
                        $sale_class = 'new_sale';
                    }
                ?>
                <div class="flash <?php echo esc_attr( $sale_class ); ?>">
                    <?php 
                        /**
                         * Flash Item
                        */
                        sparklestore_sale_percentage_loop(); 

                        $newness_days = 7;
                        $created = strtotime( $product->get_date_created() );
                        if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
                            sparklestore_flash_sale_new_tag();
                        }
                        
                        if( $product->is_on_sale() )
                            echo apply_filters( 'woocommerce_sale_flash', sparklestore_flash_sale_tag(), $post, $product );
                    ?>
                </div>

                <a href="<?php the_permalink(); ?>" class="store_product_item_link">

                    <?php the_post_thumbnail('woocommerce_thumbnail'); #Products Thumbnail ?>

                </a>
            </div>
        </div>

    <?php 
}
add_action( 'woocommerce_before_shop_loop_item_title', 'sparklestore_woocommerce_template_loop_product_thumbnail', 10 );

/******
 * Remove Default Title
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

if ( !function_exists('sparklestore_woocommerce_shop_loop_item_title') ) {

    function sparklestore_woocommerce_shop_loop_item_title(){ ?>

        <div class="store_products_item_details">
            <h3>
                <a class="store_products_title" href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h3>
      <?php 
    }
}
add_action( 'woocommerce_shop_loop_item_title', 'sparklestore_woocommerce_shop_loop_item_title', 8 );

/**
 * Price & Rating Wrap
*/
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

if (!function_exists('sparklestore_woocommerce_before_rating_loop_price')) {

    function sparklestore_woocommerce_before_rating_loop_price(){ ?>

        <div class="price-rating-wrap">

            <?php woocommerce_template_loop_rating(); ?>

            <?php woocommerce_template_loop_price(); ?>

        </div> 

      <?php 
    }
}
add_action( 'woocommerce_after_shop_loop_item_title', 'sparklestore_woocommerce_before_rating_loop_price', 4 );


if (!function_exists('sparklestore_woocommerce_product_item_details_close')) {

    function sparklestore_woocommerce_product_item_details_close(){ ?>

        </div>

      <?php 
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'sparklestore_woocommerce_product_item_details_close', 12 );


/* 
 * Product Single Page
*/
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

function sparklestore_group_flash(){

    global $post, $product; ?>

    <div class="flash">
        <?php 

            sparklestore_sale_percentage_loop(); 

            $newness_days = 7;
            $created = strtotime( $product->get_date_created() );
            if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
                sparklestore_flash_sale_new_tag();
            }

            if ( $product->is_on_sale() ) :

                echo apply_filters( 'woocommerce_sale_flash', sparklestore_flash_sale_tag(), $post, $product );
            
            endif;
        ?>
    </div>

    <?php 
}
add_action( 'woocommerce_single_product_summary','sparklestore_group_flash', 10 );



/**
 * WooCommerce display related product.
*/
if (!function_exists('sparklestore_related_products_args')) {

  function sparklestore_related_products_args( $args ) {

        $args['posts_per_page'] = get_theme_mod('sparklestore_single_num_related_product', 3); // 4 related products

        $args['columns'] = get_theme_mod('sparklestore_woocommerce_product_row', 3); // arranged in 2 columns

        return $args;
  }
}
add_filter( 'woocommerce_output_related_products_args', 'sparklestore_related_products_args' );


/**
 * WooCommerce display upsell product.
*/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

if ( ! function_exists( 'sparklestore_woocommerce_upsell_display' ) ) {

    function sparklestore_woocommerce_upsell_display() {

        $number_product = get_theme_mod('sparklestore_single_num_upsells_product', 6);

        $number_column  = get_theme_mod('sparklestore_woocommerce_product_row', 3);

        woocommerce_upsell_display( $number_product , $number_column ); 
    }
}
add_action( 'woocommerce_after_single_product_summary', 'sparklestore_woocommerce_upsell_display', 15 );


/**
 * You may be interested in…
*/
add_filter( 'woocommerce_cross_sells_total', 'sparklestore_change_cross_sells_product_no' );

function sparklestore_change_cross_sells_product_no( $columns ) {

    return 2;
}
// *****************************************************************************// 
// ! WooCommerce Cart Items List in Sidebar
// *****************************************************************************// 

if ( ! function_exists( 'sparklestore_cart_side_widget' ) ) {
	function sparklestore_cart_side_widget() {
		?>
			<div class="cart-widget-side">
				<div class="widget-heading">
					<h3 class="widget-title"><?php esc_html_e( 'Shopping cart', 'sparklestore' ); ?></h3>
					<a href="#" class="close-side-widget"><?php esc_html_e( 'close', 'sparklestore' ); ?></a>
                </div>
                
                <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                
			</div>
		<?php
	}

	add_action( 'sparklestore_footer_after', 'sparklestore_cart_side_widget', 140 );
}