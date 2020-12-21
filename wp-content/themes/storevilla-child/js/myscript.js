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
    $('body').on('click', '.plus, .minus', function () {

        $('.input-text.qty.text').val($('.input-text.qty.text').val()).change();
        $('[name="update_cart"]').trigger('click');
    });
    if (window.location.pathname != '/') {
        //$('.menu-item-home').hide();
    } else {
        //$('#primary-menu.menu')
    }

});
