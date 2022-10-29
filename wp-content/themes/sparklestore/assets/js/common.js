jQuery(document).ready(function($) {    
    "use strict"; 
    /**
     * Add RTL Class in Body
    */
   var brtl = false;

   if ($("body").hasClass('rtl')) { brtl = true; }

    /**
     * Single Product Qty Item
    */
    $(document).on("click", ".quantity input.plus", function(){
        var parent = $(this).parent().find('input.qty');
        var max = parent.attr('max');
        var val = parseInt(parent.val() || 0 ) + 1;
        if( max === val - 1) return;
        parent.val(val);
        parent.trigger('change');
    });

    $(document).on("click", ".quantity input.minus", function(){
        var parent = $(this).parent().find('input.qty');
        var min = parent.attr('min');
        var val = parseInt(parent.val() || 0 ) -1 ;
        if( min == val + 1) return;
        parent.val(val);
        parent.trigger('change');
    });
    /**
     * sparklestore pro banner slider
    */
    if( $('.sparklestore-slider').length > 0 ) {
        $('.sparklestore-slider').flexslider({
            animation: "fade",
            animationSpeed: 500,
            animationLoop: true,
            prevText: '<i class="icofont-thin-left"></i>',
            nextText: '<i class="icofont-thin-right"></i>',
            before: function(slider) {
                $('.sparklestore-caption').fadeOut().animate({top:'35%'},{queue:false, easing: 'swing', duration: 700});
                slider.slides.eq(slider.currentSlide).delay(500);
                slider.slides.eq(slider.animatingTo).delay(500);
            },
            after: function(slider) {
                $('.sparklestore-caption').fadeIn().animate({top:'50%'},{queue:false, easing: 'swing', duration: 700});
            },
            useCSS: true
        });
    }


    /**
     * Gallery Post Slider Carousel
    */
    $('.postgallery-carousel').lightSlider({
        item:1,
        loop:true,
        pause:3000,
        enableDrag:true,
        controls:true,
        rtl: brtl,
        prevHtml:'<i class="icofont-thin-left"></i>',
        nextHtml:'<i class="icofont-thin-right"></i>',
        speed:500,
        pager:false,
        onSliderLoad: function() {
            $('.postgallery-carousel').removeClass('cS-hidden');
        },
    });

    /**
     * Masonry Posts Layout
    */
    var grid = document.querySelector(
            '.sparklestore-masonry'
        ),
        masonry;

    if (
        grid &&
        typeof Masonry !== undefined &&
        typeof imagesLoaded !== undefined
    ) {
        imagesLoaded( grid, function( instance ) {
            masonry = new Masonry( grid, {
                itemSelector: '.hentry',
                gutter: 15
            } );
        } );
    }


    /**
     * Advance Search Product Category Dropdown
    */
    if ( $('.category-search-option').length > 0 ) {
        $('.category-search-option').chosen();
    }


    /**
     * Vertical Menu
    */
    if ( $('.block-nav-category').length > 0 ) {
        var _value2 = 0;
        $('.block-nav-category').each(function () {
            var _value1 = $(this).data('items') - 1;
            _value2     = $(this).find('.vertical-menu>li').length;

            if ( _value2 > (_value1 + 1) ) {
                $(this).addClass('show-button-all');
            }
            $(this).find('.vertical-menu>li').each(function (i) {
                _value2 = _value2 + 1;
                if ( i > _value1 ) {
                    $(this).addClass('link-other');
                }
            })
        })
    }

    $(document).on('click', '.open-cate', function (e) {
        $(this).closest('.block-nav-category').find('li.link-other').each(function () {
            $(this).slideDown();
        });
        $(this).addClass('close-cate').removeClass('open-cate').html($(this).data('closetext'));
        e.preventDefault();
    });
    $(document).on('click', '.close-cate', function (e) {
        $(this).closest('.block-nav-category').find('li.link-other').each(function () {
            $(this).slideUp();
        });
        //close parent menu
        $(this).closest('.block-nav-category').removeClass('has-open');
        $(this).addClass('open-cate').removeClass('close-cate').html($(this).data('alltext'));
        e.preventDefault();
    });

    $('.block-nav-category .block-title').on('click', function () {
        $(this).toggleClass('active');
        $(this).parent().toggleClass('has-open');
        $('body').toggleClass('category-open');
    });

    /**
     * Sparkle Tabs Category Product
    */
    $('.sparkletablinks li').first('li').addClass('active');

    $('.sparkletabs .sparkletablinks a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
        var product_num = $(this).parents('ul').attr('data-id');

        var block_layout = $(this).parents('ul').attr('data-layout');
        var $ele = $(this);

        $(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();

        $(this).parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.tabscontentwrap').hide();

        // Ajax Function
        $(this).parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.preloader').show();

        $.ajax({
            url : sparklestore_tabs_ajax_action.ajaxurl,                
            data:{
                    action        : 'sparklestore_tabs_ajax_action',
                    category_slug : currentAttrValue,
                    product_num   : product_num,
                    block_layout  : block_layout
                },
            type:'post',
                success: function(res){                                        
                    $ele.parents('.sparkletabsproductwrap').find('.tabscontentwrap').html(res);
                    $('.tabscontentwrap').show();
                    if($ele.parents('.sparkletabsproductwrap').find('.sparkletabs').hasClass('layout1')){
                        sparklestore_ajax_cat_tabs();
                    }
                    $('.preloader').hide();
                }
        });
    });
    
    /**
     * sidebar cart 
     */
     $('.close-side-widget').on('click', function(e)  {
        e.preventDefault();
        $(this).parents('.cart-widget-side').removeClass('sparkle-cart-opened');
    });

    $( document.body ).on( 'added_to_cart', function(){
        $('.cart-widget-side').addClass('sparkle-cart-opened');
    });

    /**
     * WooCommerce Tabs Category Products Functions Area
    */
    function sparklestore_ajax_cat_tabs(){
        $('.widget_sparklestore_cat_collection_tabs_widget_area').each(function(){
            
            var Id = $(this).attr('id');
            var NewId = Id; 

            NewId = $('#'+Id+" .tabsproduct").lightSlider({
                item:4,
                pager:false,
                loop:true,
                speed:600,
                rtl: brtl,
                controls:true,
                prevHtml:'<i class="icofont-thin-left"></i>',
                nextHtml:'<i class="icofont-thin-right"></i>',
                slideMargin:20,
                onSliderLoad: function() {
                    $('.tabsproduct').removeClass('cS-hidden');
                },
                responsive : [
                    {
                        breakpoint:1024,
                        settings: {
                            item:3,
                            slideMove:1,
                            slideMargin:15,
                        }
                    },
                    {
                        breakpoint:768,
                        settings: {
                            item:2,
                            slideMove:1,
                            slideMargin:15,
                        }
                    },
                    {
                        breakpoint:480,
                        settings: {
                            item:1,
                            slideMove:1,
                        }
                    }
                ]
            });

            $('#'+Id+' .sparkle-lSPrev').click(function(){
                NewId.goToPrevSlide(); 
            });
            $('#'+Id+' .sparkle-lSNext').click(function(){
                NewId.goToNextSlide(); 
            });

        });
    }

    sparklestore_ajax_cat_tabs();

    /**
     * WooCommerce Category With Products 
    */
    $('.widget_sparklestore_cat_with_product_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .catwithproduct").lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            rtl: brtl,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                $('.catwithproduct').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:1024,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:768,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                    }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });

    /**
     * WooCommerce Products Area 
    */
    $('.widget_sparklestore_product_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .productarea").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            rtl: brtl,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                $('.productarea').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:1024,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:768,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                    }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });


    /**
     * WooCommerce Category Collection Area 
    */
    $('.widget_sparklestore_cat_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .categoryslider").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            rtl: brtl,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                $('.categoryslider').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:1024,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:768,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                    }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });

    /**
     * Posts Display Area 
    */
    $('.widget_sparklestore_blog_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .blogspostarea").lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            rtl: brtl,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            adaptiveHeight:true,
            onSliderLoad: function() {
                $('.blogspostarea').removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:1024,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:768,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:15,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:1,
                        slideMove:1,
                    }
                }
            ]
        });

        $('#'+Id+' .sparkle-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .sparkle-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });
   
    /**
     * ScrollUp
    */
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 1000) {
            jQuery('.scrollup').fadeIn();
        } else {
            jQuery('.scrollup').fadeOut();
        }
    });

    jQuery('.scrollup').click(function() {
        jQuery("html, body").animate({
            scrollTop: 0
        }, 2000);
        return false;
    });    

});


/**
 * Wishlist count ajax update 
*/
jQuery( document ).ready( function($){
    $('body').on( 'added_to_wishlist', function(){
        $('.top-wishlist .count').load( yith_wcwl_l10n.ajax_url + ' .top-wishlist .bigcounter', { action: 'yith_wcwl_update_single_product_list' } );
    });

    //menu tab
   $('.we-tab-area button').click(function(e){
    $(this).parent().find('button').removeClass('active');
    $(this).parent().parent().find('.we-tab-content div.tab-content').addClass('hidden');
    var currentClass= $(this).attr('class').split(' ')[0]
    $(this).addClass('active');
    
    window.location.hash = currentClass;

    $(this).parent().parent().find(".we-tab-content").find('.' + currentClass + "-content").removeClass('hidden');

});
//active tab selector
var activeTab = window.location.hash;
    activeTab = activeTab.replace("#","");
if(  activeTab ){
    jQuery('.we-tab-area').find('.' + activeTab ).trigger('click');
}

    /** sidebar mobile menu */
    $('body').click(function(evt){    
        //For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
        if($(evt.target).closest('.cover-modal.active').length)
           return;             
 
       //Do processing of click event here for every element except with id menu_content
       if( $('body').hasClass('showing-menu-modal')){
            var body = document.body;
            
            $('.cover-modal.active').removeClass('active');
            body.classList.remove( 'showing-modal' );
            body.classList.add( 'hiding-modal' );
            body.classList.remove('showing-menu-modal');
            body.classList.remove('show-modal');

            document.documentElement.removeAttribute('style')
            document.body.style.removeProperty( 'padding-top' );
			

            // Remove the hiding class after a delay, when animations have been run.
            setTimeout( function() {
                body.classList.remove( 'hiding-modal' );
            }, 500 );
       }
       
    });

});