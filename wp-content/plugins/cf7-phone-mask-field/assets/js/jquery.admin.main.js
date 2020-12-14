(function($) {

	'use strict';
        
    $(window).load(function(){  
    	if (typeof wpcf7 === 'undefined' || wpcf7 === null) {
    		return;
    	}                   
        
        var wpcf7TaggenUpdate = wpcf7.taggen.update;
        
        wpcf7.taggen.update = function( $form ) {
            wpcf7TaggenUpdate( $form );
            
            // insert placeholder value into code input value
            if ('mask' === $form.attr('data-id')) {
                var $placInput = $('.placeholdervalue', $form);
                if ($placInput.val()) {
                    var $codeInput = $('.code', $form);
                    
                    $codeInput.val(
                        $codeInput.val().replace(']', ' "' + $placInput.val() + '"]')
                    );                    
                }                
            }
        } 
        
        $('[data-id="mask"].tag-generator-panel .placeholdervalue').change(function(){
            var $this = $(this)
            
            $this.val( $this.val().replace(/_/g, '') );
        })

    })     
})(jQuery);