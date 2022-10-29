jQuery( window ).on( 'elementor/frontend/init', function() {
    //hook name is 'frontend/element_ready/{widget-name}.{skin} - i dont know how skins work yet, so for now presume it will
    //always be 'default', so for example 'frontend/element_ready/slick-slider.default'
    //$scope is a jquery wrapped parent element

    /**
     * sparklestore pro banner slider
    */
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-slider.default', function($scope, $){
        
        var ele = $scope.find('.sparklestore-slider');

        if( ele.length > 0 ) {
            ele.flexslider({
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
    });

    /**
     * blog section
    */
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-blog.default', function($scope, $){
        var ele = $scope.find('.blogspostarea');
        var parent = ele.parent();
        ele.lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            adaptiveHeight:true,
            onSliderLoad: function() {
                ele.removeClass('cS-hidden');
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

        parent.find('.sparkle-lSPrev').click(function(){
            ele.goToPrevSlide(); 
        });
        parent.find('.sparkle-lSNext').click(function(){
            ele.goToNextSlide(); 
        });
        
    });

    /**
     * WooCommerce Category Collection Area 
    */
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-category.default', function($scope, $){
        var ele = $scope.find('.categoryslider');
        var parent = ele.parent();

        ele.lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                ele.removeClass('cS-hidden');
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

        parent.find('.sparkle-lSPrev').click(function(){
            ele.goToPrevSlide(); 
        });
        parent.find('.sparkle-lSNext').click(function(){
            ele.goToNextSlide(); 
        });

    });

    /**
     * WooCommerce Category With Products 
    */
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-category-products.default', function($scope, $){
        var ele = $scope.find('.catwithproduct');
        var parent = ele.parent().parent();

        ele.lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                ele.removeClass('cS-hidden');
            },
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:2,
                        slideMove:1,
                        slideMargin:6,
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

        parent.find('.sparkle-lSPrev').click(function(){
            ele.goToPrevSlide(); 
        });
        parent.find('.sparkle-lSNext').click(function(){
            ele.goToNextSlide(); 
        });

    });

    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-woo-prodcuts.default', function($scope, $){
        var ele = $scope.find('.productarea');
        var parent = ele.parent();
        ele.lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                ele.removeClass('cS-hidden');
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

        parent.find('.sparkle-lSPrev').click(function(){
            ele.goToPrevSlide(); 
        });
        parent.find('.sparkle-lSNext').click(function(){
            ele.goToNextSlide(); 
        });

    });


    /** category tabs */
    class ProductTabsHandlerClass extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    linkSelector: '.sparkletablinks_ele a',
                    sliderSelector: '.tabsproduct_ele',
                    ajaxSliderSelector: '.tabsproduct',
                    sliderNext  : '.sparkle-lSPrev',
                    sliderPrevious: '.sparkle-lSNext',
                    mainContent: '.sparkletabsproductwrap'
                },
            };
        }
    
        getDefaultElements() {
            const selectors = this.getSettings( 'selectors' );
            return {
                $linkSelector: this.$element.find( selectors.linkSelector ),
                $sliderSelector: this.$element.find( selectors.sliderSelector ),
                $sliderNext: this.$element.find( selectors.sliderNext ),
                $sliderPrevious: this.$element.find( selectors.sliderPrevious ),
                $mainContent: this.$element.find( selectors.mainContent ),
            };
        }
    
        bindEvents() {
            this.elements.$linkSelector.on( 'click', this.onlinkSelectorClick.bind( this ) );

            this.elements.$sliderNext.on( 'click', this.sliderNext.bind( this ) );
            this.elements.$sliderPrevious.on( 'click', this.sliderPrevious.bind( this ) );
            this.elements.$mainContent.on( 'change', this.sliderContentChange.bind( this ) );
            
            this.runSlider(this.elements.$sliderSelector)
        }
    
        onlinkSelectorClick( event ) {
            event.preventDefault();
            var that = this;
            var $ele = jQuery(event.target);
            var currentAttrValue = $ele.attr('href');
            var product_num = $ele.parents('ul').attr('data-id');

            var block_layout = $ele.parents('ul').attr('data-layout');
            

            $ele.parent('li').addClass('active').siblings().removeClass('active');
            $ele.parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.tabscontentwrap').hide();

            // Ajax Function
            $ele.parents('.sparkletabs').siblings('.sparkletabsproductwrap .sparkletablinkscontent').find('.preloader').show();

            jQuery.ajax({
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
                        jQuery('.tabscontentwrap').show();
                        if($ele.parents('.sparkletabsproductwrap').find('.sparkletabs').hasClass('layout1')){
                            var ele = that.$element.find( that.getSettings().selectors.ajaxSliderSelector )
                            setTimeout(function(){
                                that.runSlider(ele)
                            }, 500)

                            
                            
                        }
                        jQuery('.preloader').hide();
                    }
            });
            
       }

       sliderNext(event){
            event.preventDefault();
            this.elements.$sliderSelector.goToNextSlide(); 
       }
       sliderPrevious(event){
            event.preventDefault();
            this.elements.$sliderSelector.goToPrevSlide(); 
       }

       sliderContentChange(){
           alert('congent change init liser');
           this.runSlider(this.elements.$sliderSelector)
       }

      

       runSlider(ele){
        ele.lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:true,
            prevHtml:'<i class="icofont-thin-left"></i>',
            nextHtml:'<i class="icofont-thin-right"></i>',
            slideMargin:20,
            onSliderLoad: function() {
                ele.removeClass('cS-hidden');
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
       }
    }

    const tabsProducts = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( ProductTabsHandlerClass, {
            $element,
        } );
    };
 
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparklestore-woo-tab-products.default', tabsProducts );

 } );