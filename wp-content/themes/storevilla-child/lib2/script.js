jQuery(function ($) {
	var templateUrl = themename.templateUrl;
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
            }else if ($radio.attr('id') == 'gridRadios2-3' && $radio.data('waschecked') == true)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/cross-rose.jpg');
            }else if ($radio.attr('id') == 'gridRadios2-4' && $radio.data('waschecked') == true)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/cross-rose.jpg');
            } else if ($radio.closest('.form-f').length > 0)
            {
                $('#body-construct-img img').attr('src', templateUrl+'/images/1.jpg');
            }
        }

    });
   
   
   
   
   
   document.querySelector('fieldset>.row').classList.add('opened');
   $('fieldset>.row.opened>div.col-sm-12').slideDown();
    $('legend.pt').click(function(){
        const parent = $(this).parent();        
        if(parent.hasClass('opened')){
            $('fieldset>.row.opened>div.col-sm-12').slideUp();
            parent.removeClass('opened');
        }else{
            $('fieldset>.row.opened>div.col-sm-12').slideUp();
            $('fieldset>.row').removeClass('opened');            
            parent.addClass('opened')
            $('fieldset>.row.opened>div.col-sm-12').slideDown();
        }
    });
//    $('.form-size input[type=radio]').click(function(e){
//       con_size = $(this).val();
//       //console.log(con_size);
//    });
    
    
    $('#for_slov,input').on('click',function(){
        $.ajax({
                    url: templateUrl+"/props.php",
                    type: 'post',
                    dataType: "json",
                    data: $("#form").serialize(),
                    success: function(data) {
                       $('#body-constr').text(data[0]);
                       $('#body-construct-order').html(data[1]);
                    },
                    error: function() {
                        
                    }
                });
    });
    
});