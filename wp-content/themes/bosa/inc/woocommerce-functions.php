<?php
/**
 * Functions for Woocommerce features
 *
 * @package Bosa
 */

/**
* Add a wrapper div to product
* @since Bosa 1.0.0
*/

function bosa_before_shop_loop_item(){
	echo '<div class="product-inner">';
}

add_action( 'woocommerce_before_shop_loop_item', 'bosa_before_shop_loop_item', 9 );

function bosa_after_shop_loop_item(){
	echo '</div>';
}

/**
* After shop loop item
* @since Bosa 1.0.0
*/

add_action( 'woocommerce_after_shop_loop_item', 'bosa_after_shop_loop_item', 11 );

/**
* Hide default page title
* @since Bosa 1.0.0
*/
function bosa_woo_show_page_title(){
    return false;
}
add_filter( 'woocommerce_show_page_title', 'bosa_woo_show_page_title' );

/**
* Change number or products per row to 3
* @since Bosa 1.0.0
*/
if ( !function_exists( 'bosa_loop_columns' ) ) {
	function bosa_loop_columns() {
        if( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
            return 2; // 2 products per row
        }
		return 3; // 3 products per row
	}
}
add_filter( 'loop_shop_columns', 'bosa_loop_columns' );

/**
* Add buttons in compare and wishlist
* @since Bosa 1.0.0
*/
if (!function_exists('bosa_compare_wishlist_buttons')) {
    function bosa_compare_wishlist_buttons() {
        $double = '';
        if ( function_exists( 'yith_woocompare_constructor' ) && function_exists( 'YITH_WCWL' ) ) {
            $double = ' d-compare-wishlist';
        }
        ?>
        <div class="product-compare-wishlist<?php echo esc_attr( $double ); ?>">
            <?php
            if ( function_exists( 'yith_woocompare_constructor' ) ) {
                global $product, $yith_woocompare;
                $product_id = !is_null($product) ? yit_get_prop($product, 'id', true) : 0;
                // return if product doesn't exist
                if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) )
                    return;
                $url = is_admin() ? "#" : $yith_woocompare->obj->add_product_url( $product_id );
                ?>
                <div class="product-compare">
                    <a class="compare" rel="nofollow" data-product_id="<?php echo absint( $product_id ); ?>" href="<?php echo esc_url($url); ?>">
                        <i class="fas fa-sync"></i>
                        <span class="info-tooltip">
                            <?php esc_html_e( 'Compare', 'bosa' ); ?>
                        </span>
                    </a>
                </div>
                <?php
            }
            if ( function_exists( 'YITH_WCWL' ) ) {
                ?>
                <div class="product-wishlist">
                    <?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ) ?>
                </div>
                <?php
            }
            ?>
        <?php
    }
    add_action( 'woocommerce_after_shop_loop_item', 'bosa_compare_wishlist_buttons', 15 );
}

/**
 * Closing for quick view, compare and wishlist.
 * @since Bosa 1.2.6
*/
if (!function_exists('bosa_compare_wishlist_buttons_close')) {
    add_action( 'woocommerce_after_shop_loop_item', 'bosa_compare_wishlist_buttons_close', 16 );
    function bosa_compare_wishlist_buttons_close() {
        echo '</div>'; /* .product-compare-wishlist */
    }
}


/**
* Change number of products that are displayed per page (shop page)
* @since Bosa 1.0.0
*/
function bosa_loop_shop_per_page( $cols ) {
    // $cols contains the current number of products per page based on the value stored on Options –> Reading
    // Return the number of products you wanna show per page.
    $cols = get_theme_mod( 'woocommerce_product_per_page', 9 );
    return $cols;
}
add_filter( 'loop_shop_per_page', 'bosa_loop_shop_per_page', 20 );

/**
 * Check if WooCommerce is activated and is shop page.
 *
 * @return bool
 * @since Bosa 1.0.0
 */
if( !function_exists( 'bosa_wooCom_is_shop' ) ){
    function bosa_wooCom_is_shop() {
        if ( class_exists( 'woocommerce' ) ) {  
            if ( is_shop()  ) {
                return true;
            }
        }else{
            return false;
        }
    }
    add_action( 'wp', 'bosa_wooCom_is_shop' );
}

/**
 * Check if WooCommerce is activated and is cart page.
 *
 * @return bool
 * @since Bosa 1.0.0
 */
if( !function_exists( 'bosa_wooCom_is_cart' ) ){
    function bosa_wooCom_is_cart() {
        if ( class_exists( 'woocommerce' ) ) {  
            if ( is_cart()  ) {
                return true;
            }
        }else{
            return false;
        }
    }
    add_action( 'wp', 'bosa_wooCom_is_cart' );
}

/**
 * Check if WooCommerce is activated and is checkout page.
 *
 * @return bool
 * @since Bosa 1.0.0
 */
if( !function_exists( 'bosa_wooCom_is_checkout' ) ){
    function bosa_wooCom_is_checkout() {
        if ( class_exists( 'woocommerce' ) ) {  
            if ( is_checkout()  ) {
                return true;
            }
        }else{
            return false;
        }
    }
    add_action( 'wp', 'bosa_wooCom_is_checkout' );
}

/**
 * Check if WooCommerce is activated and is account page.
 *
 * @return bool
 * @since Bosa 1.0.0
 */
if( !function_exists( 'bosa_wooCom_is_account_page' ) ){
    function bosa_wooCom_is_account_page() {
        if ( class_exists( 'woocommerce' ) ) {  
            if ( is_account_page()  ) {
                return true;
            }
        }else{
            return false;
        }
    }
    add_action( 'wp', 'bosa_wooCom_is_account_page' );
}

/**
* Modify excerpt item priority to last in product detail page.
* @since Bosa 1.2.6
*/
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 55 );

/**
 * Change column number of related products in product detail page.
 *
 * @return array
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_related_products_args' ) ){
    add_filter( 'woocommerce_output_related_products_args', 'bosa_related_products_args', 20 );
      function bosa_related_products_args( $args ) {
        $args[ 'columns'] = 3;
        return $args;
    }
}

/**
 * Check if WooCommerce is activated and is product detail page.
 *
 * @return bool
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_wooCom_is_product_page' ) ){
    function bosa_wooCom_is_product_page() {
        if ( class_exists( 'woocommerce' ) ) {  
            if ( is_product() ) {
                return true;
            }
        }else{
            return false;
        }
    }
    add_action( 'wp', 'bosa_wooCom_is_product_page' );
}

/**
 * Adds breadcrumb before product title in product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_product_detail_breadcrumb' ) ){
    add_action( 'woocommerce_single_product_summary', 'bosa_product_detail_breadcrumb', 1 );
    function bosa_product_detail_breadcrumb(){
        if( bosa_wooCom_is_product_page() ){
           if( get_theme_mod( 'breadcrumbs_controls', 'show_in_all_page_post' ) == 'disable_in_all_pages' || get_theme_mod( 'breadcrumbs_controls', 'show_in_all_page_post' ) == 'show_in_all_page_post' ){
                bosa_breadcrumb_wrap();
            }
        }
    }
}

/**
 * Add left sidebar to product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_woo_product_detail_left_sidebar' ) ){
    function bosa_woo_product_detail_left_sidebar( $sidebarColumnClass ){
        if( !get_theme_mod( 'disable_sidebar_woocommerce_page', false ) ){
            if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ){ 
                if( is_active_sidebar( 'woocommerce-left-sidebar') ){ ?>
                    <div id="secondary" class="sidebar left-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
                        <?php dynamic_sidebar( 'woocommerce-left-sidebar' ); ?>
                    </div>
                <?php }
            }elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
                if( is_active_sidebar( 'woocommerce-left-sidebar') || is_active_sidebar( 'woocommerce-right-sidebar') ){ ?>
                    <div id="secondary" class="sidebar left-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
                        <?php dynamic_sidebar( 'woocommerce-left-sidebar' ); ?>
                    </div>
                <?php
                }
            }
        }
    }
}

/**
 * Add right sidebar to product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_woo_product_detail_right_sidebar' ) ){
    function bosa_woo_product_detail_right_sidebar( $sidebarColumnClass ){
        if( !get_theme_mod( 'disable_sidebar_woocommerce_page', false ) ){
            if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ){ 
                if( is_active_sidebar( 'woocommerce-right-sidebar') ){ ?>
                    <div id="secondary" class="sidebar right-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
                        <?php dynamic_sidebar( 'woocommerce-right-sidebar' ); ?>
                    </div>
                <?php }
            }elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
                if( is_active_sidebar( 'woocommerce-left-sidebar') || is_active_sidebar( 'woocommerce-right-sidebar') ){ ?>
                    <div id="secondary-sidebar" class="sidebar right-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
                        <?php dynamic_sidebar( 'woocommerce-right-sidebar' ); ?>
                    </div>
                <?php
                }
            }
        }
    }
}

/**
 * Returns the sidebar column classes in product detail page.
 *
 * @return array
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_get_sidebar_class' ) ){
    function bosa_get_sidebar_class(){
        $sidebarClass = 'col-lg-8';
        $sidebarColumnClass = 'col-lg-4';

        if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ){
            if( !is_active_sidebar( 'woocommerce-right-sidebar') ){
                $sidebarClass = "col-12";
            }   
        }elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ){
            if( !is_active_sidebar( 'woocommerce-left-sidebar') ){
                $sidebarClass = "col-12";
            }   
        }elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
            $sidebarClass = 'col-lg-6';
            $sidebarColumnClass = 'col-lg-3';
            if( !is_active_sidebar( 'woocommerce-left-sidebar') && !is_active_sidebar( 'woocommerce-right-sidebar') ){
                $sidebarClass = "col-12";
            }
        }
        if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'no-sidebar' || get_theme_mod( 'disable_sidebar_woocommerce_page', false ) ){
            $sidebarClass = 'col-12';
        }
        $colClasses = array(
            'sidebarClass' => $sidebarClass, 
            'sidebarColumnClass' => $sidebarColumnClass, 
        );
        return $colClasses;
    }
}

/**
 * Add wrapper product gallery in product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_woocommerce_before_single_product_summary' ) ){
    add_action( 'woocommerce_before_single_product_summary', 'bosa_woocommerce_before_single_product_summary', 5 );
    function bosa_woocommerce_before_single_product_summary(){
        echo '<div class="product-detail-wrapper">';
    }
}

/**
 * Add left sidebar before tabs in product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_woocommerce_after_single_product_summary' ) ){
    add_action( 'woocommerce_after_single_product_summary', 'bosa_woocommerce_after_single_product_summary', 5 );
    function bosa_woocommerce_after_single_product_summary(){
        $getSidebarClass = bosa_get_sidebar_class();
        echo '</div>';/* .product-detail-wrapper */
        echo '<div class="row">';
            bosa_woo_product_detail_left_sidebar( $getSidebarClass[ 'sidebarColumnClass' ] );
            echo '<div class="'. $getSidebarClass[ 'sidebarClass' ] .'">';
    }
}

/**
 * Add right sidebar before tabs in product detail page.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_woocommerce_after_single_product' ) ){
    add_action( 'woocommerce_after_single_product', 'bosa_woocommerce_after_single_product' );
    function bosa_woocommerce_after_single_product(){
        $getSidebarClass = bosa_get_sidebar_class();
        bosa_woo_product_detail_right_sidebar( $getSidebarClass[ 'sidebarColumnClass' ] );
            echo '</div>';/* col woocommerce tabs and related products */
        echo '</div>';/* .row */
    }
}

/**
 * Add icon and tooltip text for Yith Woocommerce Quick View.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_yith_add_quick_view_button_html' ) ){
    add_filter( 'yith_add_quick_view_button_html', 'bosa_yith_add_quick_view_button_html', 10, 3 );
    function bosa_yith_add_quick_view_button_html( $button, $label, $product ){
        
        $product_id = $product->get_id();

        $button = '<div class="product-view"><a href="#" class="yith-wcqv-button" data-product_id="' . esc_attr( $product_id ) . '"><i class="fas fa-search"></i><span class="info-tooltip">' . $label . '</span></a></div>';
        return $button;
    }
}

/**
 * Modify $label for Yith Woocommerce Wishlist.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_yith_wcwl_button_label' ) ){
    add_filter( 'yith_wcwl_button_label', 'bosa_yith_wcwl_button_label' );
    function bosa_yith_wcwl_button_label( $label_option ){
        $label_option = '<span class="info-tooltip">'.$label_option.'</span>';
        return $label_option;
    }
}

/**
 * Modify $browse_wishlist_text for Yith Woocommerce Wishlist.
 *
 * @since Bosa 1.2.6
 */
if( !function_exists( 'bosa_yith_wcwl_browse_wishlist_label' ) ){
    add_filter( 'yith_wcwl_browse_wishlist_label', 'bosa_yith_wcwl_browse_wishlist_label' );
    function bosa_yith_wcwl_browse_wishlist_label( $browse_wishlist_text ){
        if( strpos( $browse_wishlist_text, 'info-tooltip' ) === false ){
            $browse_wishlist_text = '<i class="fas fa-heart"></i><span class="info-tooltip">'.$browse_wishlist_text.'</span>';
        }
        return $browse_wishlist_text;
    }
}