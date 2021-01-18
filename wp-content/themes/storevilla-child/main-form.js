jQuery(function ($) {
    $(document).on('input', 'textarea#slov', function () {
        var con = $(this).val().replace(/\s+/g, '').length;
        $('#sum-slov').text("( " + con + " зн. - " + con * 35 + " руб. )");
    });
    $('input[type=checkbox]').click(function (e) {
        if ($(this).is(':checked')) {
            $(this).siblings('input').removeAttr('disabled');
        } else {
            $(this).siblings('input').attr('disabled', 'disabled');
        }
    });
    $('input[type="radio"]').on('click', function () {
var templateUrl = themename.templateUrl;
        var $radio = $(this);
        if ($radio.closest('.form-size').length == 0) {
            // if this was previously checked
            if ($radio.data('waschecked') == true)
            {
                $radio.prop('checked', false);
                $radio.data('waschecked', false);
            } else
            {
                $radio.prop('checked', true);
                $radio.data('waschecked', true);
            }
            if ($radio.attr('id') == 'con_form_wave' && $radio.data('waschecked') == true)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/volna.jpg');
            } else if ($radio.attr('id') == 'con_form_notwave' && $radio.data('waschecked') == true)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/not_wave.jpg');
            }else if ($radio.attr('id') == 'gridRadios2-3' || $radio.attr('id') == 'gridRadios2-4' && $radio.data('waschecked') == true)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/cross-rose.jpg');
            } else if ($radio.closest('.form-f').length > 0)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/1.jpg');
            }
        }

    });


});