window.onload = function () {
    let preloader = document.getElementById('preloader');
    if (window.location.pathname == '/') {
        var b = document.getElementsByClassName("menu-item-home");
        b[0].style.display = 'none';
    }
    preloader.style.display = 'none';
}

jQuery(document).ready(function ($) {
    function main_res() {
        var width = $(window).width(),
                aset = $('aside.widget');
        if (width < 768) {
            aset.addClass('wid-act');
            aset.find('h3').addClass('fot-act');
            aset.find('ul').addClass('widget-active');
        } else {
            aset.removeClass('wid-act');
            aset.find('h3').removeClass('fot-act');
            aset.find('ul').removeClass('widget-active');
        }
    }
    if ($(window).width() < 768) {
        main_res();
    }
    $(window).resize(function () {
        main_res();
    });
    $('aside.wid-act').click(function () {
        if ($(this).find('h3.fot-act')) {
            $(this).find('ul').slideToggle(300);
        }

    });
    $('body').on('change', '.qty', function () {
        $('[name="update_cart"]').trigger('click');
    });
    $('body').on('click', '.plus, .minus', function (e) {
        var v = $(this).siblings('.input-text.qty.text').val();
        //console.log(v);
        $(this).siblings('.input-text.qty.text').val(v).change();
        $('[name="update_cart"]').trigger('click');
    });
//   custum file cart 
    $(document).ready(function () {
        $('.prefix-cart-notes').on('change keyup paste', function () {
            $('.cart_totals').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            var cart_id = $(this).data('cart-id');
            $.ajax(
                    {
                        type: 'POST',
                        url: prefix_vars.ajaxurl,
                        data: {
                            action: 'prefix_update_cart_notes',
                            security: $('#woocommerce-cart-nonce').val(),
                            notes: $('#cart_notes_' + cart_id).val(),
                            cart_id: cart_id
                        },
                        success: function (response) {
                            $('.cart_totals').unblock();
                        }
                    }
            )
        });
    });
//  END custum file cart
});
