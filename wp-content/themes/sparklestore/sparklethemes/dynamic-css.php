<?php
/**
 * Dynamic css
*/
if ( ! function_exists( 'sparklestore_dynamic_css' ) ) {

    function sparklestore_dynamic_css() {
        
        $primary_color = get_theme_mod('sparklestore_primary_theme_color_options', '#003772');
        
        $rgba = sparklestore_hex2rgba($primary_color, 0.8);

        $sparklestore_colors = '';

        /**
         *  Background Color
        */         
        $sparklestore_colors .= "

            .chosen-container .chosen-results li.result-selected,
            .chosen-container .chosen-results li.highlighted,
            .block-nav-category .view-all-category a,
            .posts-tag ul li,
            .woocommerce div.product .woocommerce-tabs ul.tabs li,
            .store_products_items_info .products_item_info,
            .flex-control-nav > li > a:hover::before, .flex-control-nav > li > a.flex-active::before,
            .header-nav,
            .btn-primary,
            .scrollup,
            .social ul li a,
            .blocktitle,
            .blocktitlewrap .SparkleStoreAction>div:hover:before,
            .layout_two .sparkletabs,
            #respond .form-submit input#submit, a.button, button, input[type='submit']{
                background-color: $primary_color;

            }\n";
        
        /**
         *  Border Color
        */
        $sparklestore_colors .= "
            .cross-sells h2, .cart_totals h2, .up-sells > h2, .related > h2, .woocommerce-billing-fields h3, .woocommerce-shipping-fields h3, .woocommerce-additional-fields h3, #order_review_heading, .woocommerce-order-details h2, .woocommerce-column--billing-address h2, .woocommerce-column--shipping-address h2, .woocommerce-Address-title h3, .woocommerce-MyAccount-content h3, .wishlist-title h2, .comments-area h2.comments-title, .woocommerce-Reviews h2.woocommerce-Reviews-title, .woocommerce-Reviews #review_form_wrapper .comment-reply-title, .woocommerce-account .woocommerce h2, .woocommerce-customer-details h2.woocommerce-column__title, .widget .widget-title,

            .woocommerce div.product .woocommerce-tabs ul.tabs::before,
            .woocommerce div.product .woocommerce-tabs .panel,
            .woocommerce div.product .woocommerce-tabs ul.tabs li,
            .btn-primary,
            .social ul li a{
                border-color: $primary_color;
            }\n";
        
        /**
         *  Primary Color
        */
        $sparklestore_colors .= "
            .blocktitlewrap .SparkleStoreAction>div,
            .top-header-inner .social ul li a,
            .top-header-inner .social ul li a:hover{
                color: $primary_color;
            }\n";
        


        /**
         * seconday color
         */

        $second_color = get_theme_mod('sparklestore_secondary_theme_color_options', '#f33c3c');
        $sparklestore_colors .= "
            .woocommerce .widget_shopping_cart .cart_list li a.remove:hover, .woocommerce.widget_shopping_cart .cart_list li a.remove:hover,
            .widget .woocommerce-mini-cart__buttons a.checkout:last-child,
            .widget_search .search-submit,
            .block-search .btn-submit,
            .site-cart-items-wrap,
            .box-header-nav .main-menu .page_item.current_page_item > a, .box-header-nav .main-menu .page_item:hover > a, .box-header-nav .main-menu > .menu-item.current-menu-item > a, .box-header-nav .main-menu > .menu-item:hover > a,
            .box-header-nav .main-menu > .menu-item.focus > a,
            .widget_product_search button,
            .block-nav-category .block-title,
            .block-nav-category .vertical-menu .page_item.current_page_item > a, .block-nav-category .vertical-menu li:hover > a,.block-nav-category .vertical-menu li>a.focus-visible, .block-nav-category .view-all-category a:hover,.block-nav-category .view-all-category a.focus-visible,
            .lSAction .lSPrev, .lSAction .lSNext,
            .woocommerce a.added_to_cart::before, .woocommerce a.product_type_simple::before, .woocommerce a.button.add_to_cart_button::before, .woocommerce a.button.product_type_grouped::before, .woocommerce a.button.product_type_external::before, .woocommerce a.button.product_type_variable::before,
            .woocommerce a.added_to_cart, .woocommerce a.product_type_simple, .woocommerce a.button.add_to_cart_button, .woocommerce a.button.product_type_grouped, .woocommerce a.button.product_type_external, .woocommerce a.button.product_type_variable,

            .box-header-nav .main-menu .children > .page_item:hover > a, .box-header-nav .main-menu .sub-menu > .menu-item:hover > a,
            .box-header-nav .main-menu .children > .page_item.focus > a, .box-header-nav .main-menu .sub-menu > .menu-item.focus > a,
            .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
            .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current,
            .single-product div.product .entry-summary .single_add_to_cart_button::before,
            .single-product div.product .entry-summary .single_add_to_cart_button,
            .layout_one .sparkletabs .sparkletablinks > li:hover a, .layout_one .sparkletabs .sparkletablinks > li.active a,
            .layout_one .sparkletabs .sparkletablinks > li:hover a, .layout_one .sparkletabs .sparkletablinks > li.active a,
            .store_products_items_info .yith-wcwl-add-button a.add_to_wishlist span, .store_products_items_info .sparkle-tooltip-label,
            .woocommerce a.remove:hover,
            .menu-modal .sparkle-tabs button.active,
            .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,
            .woocommerce-MyAccount-navigation ul li a,
            .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
            .widget.yith-woocompare-widget .compare, 
            .widget.yith-woocompare-widget .clear-all,
            .articlesListing .article .metainfo div:after,
            .page-numbers,
            .posts-tag ul li:first-child,
            .posts-tag ul li:hover,
            .reply .comment-reply-link,
            .admin-bar .woocommerce-store-notice, p.demo_store,
            .search-wrapper{
                background-color: $second_color;
            }

            .woocommerce a.added_to_cart, .woocommerce a.product_type_simple, .woocommerce a.button.add_to_cart_button, .woocommerce a.button.product_type_grouped, .woocommerce a.button.product_type_external, .woocommerce a.button.product_type_variable,
            .woocommerce nav.woocommerce-pagination ul li,

            .woocommerce-MyAccount-navigation ul li a:hover,
            .footer-widgets .widget .widget-title::before,
            .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
            .single-product div.product .entry-summary .single_add_to_cart_button,
            .layout_one .sparkletabs .sparkletablinks > li:hover a, .layout_one .sparkletabs .sparkletablinks > li.active a,
            .layout_one .sparkletabs .sparkletablinks > li:hover a, .layout_one .sparkletabs .sparkletablinks > li.active a,
            .woocommerce-message, .woocommerce-info,
            .btn-primary:hover,
            .widget.yith-woocompare-widget .compare, 
            .widget.yith-woocompare-widget .clear-all,
            .widget.yith-woocompare-widget .compare:hover, 
            .widget.yith-woocompare-widget .clear-all:hover,
            .page-numbers,
            .page-numbers:hover,
            .woocommerce #respond input#submit:hover{
                border-color: $second_color;
            }

            .store_products_items_info .yith-wcwl-add-button a.add_to_wishlist span::before, .store_products_items_info .sparkle-tooltip-label::before{
                border-left-color: $second_color;
            }

            .woocommerce a.added_to_cart:hover, .woocommerce a.product_type_simple:hover, .woocommerce a.button.add_to_cart_button:hover, .woocommerce a.button.product_type_grouped:hover, .woocommerce a.button.product_type_external:hover, .woocommerce a.button.product_type_variable:hover,

            .footer-widgets .widget_top_rated_products .product_list_widget .product-title:hover, .footer-widgets .widget a:hover, .footer-widgets .widget a:hover::before, .footer-widgets .widget li:hover::before,
            .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,
            .woocommerce nav.woocommerce-pagination ul li .page-numbers,
            .breadcrumbs .trail-items li a,
            .woocommerce-MyAccount-navigation ul li:hover::before,
            .woocommerce-MyAccount-navigation ul li a:hover,
            .sub-footer-inner .coppyright a,
            .woocommerce-MyAccount-content a,
            .woocommerce .product_list_widget .woocommerce-Price-amount,
            .single-product div.product .entry-summary .single_add_to_cart_button:hover,
            .woocommerce-message::before, .woocommerce-info::before,
            a:hover, a:focus, a:active,
            .layout_two .sparkletabs .sparkletablinks > li a:hover, 
            .layout_two .sparkletabs .sparkletablinks > li.active a,
            .widget.yith-woocompare-widget .compare:hover, 
            .widget.yith-woocompare-widget .clear-all:hover,
            .page-numbers.current,
            .page-numbers:hover,
            #cancel-comment-reply-link,
            #cancel-comment-reply-link:before,
            .single-product div.product .entry-summary a.compare:hover,
            .services_item .services_icon,
            .woocommerce #respond input#submit.alt:hover, 
            .woocommerce a.button.alt:hover, 
            .woocommerce button.button.alt:hover, 
            .woocommerce input.button.alt:hover{
                color: $second_color;
            }
            
            .woocommerce a.remove:hover,
            .woocommerce a.remove{
                color: $second_color !important;
            }
        ";

        /**
         * Main Footer Area
        */  
        $footer_bg_color   = get_theme_mod('sparklestore_footer_bg_color', '#797979');
        $footer_font_color = get_theme_mod('sparklestore_footer_fonts_color', '#ffffff');

        $sparklestore_colors .= "
            .footer.footer{
                background-color: $footer_bg_color;

            }\n";

        $sparklestore_colors .= "
            .footer.footer,
            footer.footer ul li, footer.footer ul li:before, .woocommerce footer.footer ul.cart_list li a, .woocommerce footer.footer ul.product_list_widget li a, .woocommerce-page footer.footer ul.cart_list li a, .woocommerce-page footer.footer ul.product_list_widget li a, footer.footer .widget_top_rated_products .product_list_widget .product-title,
            footer.footer .footer-bottom ul li a:hover, .footer-bottom a:hover{
                color: $footer_font_color;

            }\n";

        wp_add_inline_style( 'sparklestore-style', $sparklestore_colors );
        wp_add_inline_style( 'sparklestore-lite-style', $sparklestore_colors );
        wp_add_inline_style( 'crazystore-style', $sparklestore_colors );
    }
}
add_action( 'wp_enqueue_scripts', 'sparklestore_dynamic_css', 99 );