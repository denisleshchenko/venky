jQuery(document).ready(function ($) {

    /*WoocommerceAdd to cart fixed*/

    $('body').on('click', '.items-count', function(){
        $(this).parents('.quantity').find('.input-text').change();
     //$('.button[name="update_cart"]').prop('disabled', false);
    });

   /* Main Menu Responsive Toggle */    

   $('.menu-toggle').on('click keypress',function (e) {
        $(this).toggleClass("on");
       $("#primary-menu").slideToggle();
    });

   /* Mani Banner Slider */
    $('.store-gallery').lightSlider({
        adaptiveHeight:false,
        item:1,
        slideMargin:0,
        loop:true,
        pager:true,
        auto:true,
        speed: 1500,
        pause: 4200,
        onSliderLoad: function() {
            $('#store-gallery').removeClass('cS-hidden');
        }
    });
    
    /* Testimonial Slider */
    $('#testimonial-area').lightSlider({
        adaptiveHeight:false,
        item:1,
        slideMargin:0,
        loop:true,
        pager:true,
        auto:true,
        controls:false,
        speed: 1500,
        pause: 6000,
        mode: 'fade',
        onSliderLoad: function() {
            $('.testimonial-area').removeClass('cS-hidden');
        }
    });
    
    /* Brands Logo Slider */
    $('#brands-logo').lightSlider({
        adaptiveHeight:false,
        item:5,
        slideMargin:0,
        loop:true,
        pager:false,
        auto:true,
        speed: 3500,
        pause: 4200,
        controls:false,
        onSliderLoad: function() {
            $('.brands-logo').removeClass('cS-hidden');
        },
        responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:6,
                      }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:2,
                        slideMove:1,
                      }
                }
            ]
    });
    

    /* Only Category */

    $('.widget_storevilla_cat_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        NewId = $('#'+Id+" .category-slider").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            onSliderLoad: function() {
                $('.category-slider').removeClass('cS-hidden');
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

        $('#'+Id+' .villa-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .villa-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });


    /* Only Latest Product */
    $('.widget_storevilla_latest_product_cat_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        var NewId = $('#'+Id+" .latest-product-slider").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            enableDrag:false,
            onSliderLoad: function() {
                $('.latest-product-slider').removeClass('cS-hidden');
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

        $('#'+Id+' .villa-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .villa-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });


    /* Only Product Latest,Features,On Salse, Up Salse Product */
    $('.widget_storevilla_product_widget_area').each(function(){
        
        var Id = $(this).attr('id');
        var NewId = Id; 

        var NewId = $('#'+Id+" .store-product").lightSlider({
            item:4,
            pager:false,
            loop:true,
            speed:600,
            controls:false,
            enableDrag:false,
            onSliderLoad: function() {
                $('.store-product').removeClass('cS-hidden');
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

        $('#'+Id+' .villa-lSPrev').click(function(){
            NewId.goToPrevSlide(); 
        });
        $('#'+Id+' .villa-lSNext').click(function(){
            NewId.goToNextSlide(); 
        });

    });


    /* Category features image with related category product */
        $(".cat-with-product").lightSlider({
            item:3,
            pager:false,
            loop:true,
            speed:600,
            enableDrag:false,
            onSliderLoad: function() {
                $('.cat-with-product').removeClass('cS-hidden');
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
    
     /* Product Single Page Thumbinal Images */
    
    $(".storevilla-thumbnails").lightSlider({
        item:3,
        loop:true,
        pager:false,
        speed:600,
    });
    
    
    // ScrollUp
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
    
    jQuery('.store-promo-wrap').each(function(){
        var dis = $(this);
        $(window).resize(function(){
          var imageDataHeight = dis.height();
          var imageDataWidth = dis.width();
            imageProportions = 240/374;
            imageProportionsHeight = parseInt(imageDataWidth*imageProportions, 10);
            dis.find('.sv-promo-area').height(imageProportionsHeight);         
        }).resize();

    });
    
    $(document).on('click','.quantity button.increase, .quantity button.reduced', function(){ 
        $('input[type=submit]').each(function(){ 
            if($(this).attr('name')=='update_cart'){ 
                $(this).removeAttr('disabled'); 
            } 
        }); 
    });
});
