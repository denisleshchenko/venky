<?php 

/**
 * Added this file v3.0.0 onwards
 * For multi view field config
 * added by pj
 *  */
if (!defined('ABSPATH')) { exit; }
function wcff_field_config_wrapper( $post_type ){
    $wrapper = '';
    $wrapper .= '<div class="wcff_fields_factory wcff_fields_factory_config_wrapper" action="POST" style="display:none;">
                    <div class="wcff_fields_factory_config_container">';
    if(  $post_type == "wccpf" ) :
        	$wrapper .= '<div class="wcff-factory-tab-header">
        		<a href=".wcff-factory-tab-fields-meta" class="selected">Fields Meta</a>		
        		<a href=".wcff-factory-tab-pricing-rules" style="display: none;">Pricing Rules</a>	
        		<a href=".wcff-factory-tab-fields-rules" style="display: none;">Fields Rules</a>
                <a href=".wcff-factory-tab-color-image" style="display: none;">Color Image</a>
        	</div>';
    endif;
    
    $wrapper .= '<div class="wcff-factory-tab-container">
    	<div class="wcff-field-types-meta-container wcff-factory-tab-child wcff-factory-tab-fields-meta" style="display:block;">
    		<table class="wcff_table">
    			<tbody class="wcff-field-types-meta-body">				
    				'.wcff()->builder->build_factory_fields( "text", $post_type ).'
    			</tbody>
    		</table>
    	</div>';
    if(  $post_type == "wccpf" ) : 
	   $wrapper .= '<div class="wcff-factory-tab-child wcff-factory-tab-pricing-rules">			
    			<table class="wcff_table">
    				<tbody class="wcff-field-types-meta-body">
    					<tr>
    						<td class="summary">
    							<label for="post_type"><a href="https://sarkware.com/pricing-fee-rules-wc-fields-factory/" target="_blank" title="Documentation">Click here for Documentation</a></label>
    							<br/>
    							<label for="post_type">'. __( 'Pricing Rules', 'wc-fields-factory' ) .'</label>
    							<p class="description">'. __( 'Change the product price whenever user submit the product along with this field', 'wc-fields-factory' ) .'</p>
    							<br/>
    							<label for="post_type">'. __( 'How it works', 'wc-fields-factory' ) .'</label>
    							<p class="description">'. __( 'Use "Add Pricing Rule" button to add add a rule, specify the field value and the corresponding price, when the user submit the field with the given value while adding to cart, then the given price will be applied to the submitted product', 'wc-fields-factory' ) .'</p>
    							<br/>
    							<label for="post_type">'. __( 'Pricing Type', 'wc-fields-factory' ) .'</label>
    							<p class="description">'. __( '<strong>Add :</strong> this option will add the given price with the product amount<br/><strong>Change :</strong> this option will replace the product original price with the given one', 'wc-fields-factory' ) .'</p>							
    						</td>
    						<td style="vertical-align: top;"  class="wcff-content-config-cell">
    							<div class="wcff-tab-rules-wrapper price" class="wcff-factory-pricing-rules-wrapper">	
                                    <div class="wcff-parent-rule-title">Pricing Rules</div>
                                    <div class="wcff-rule-container">
                                        <div class="wcff-rule-container-is-empty">Pricing rule is empty!</div>
                                    </div>																
    								<input type="button" class="wcff-add-price-rule-btn button" value="Add Pricing Rule">
    							</div>
    							<div class="wcff-tab-rules-wrapper fee" class="wcff-factory-fee-rules-wrapper">	
                                    <div class="wcff-parent-rule-title">Fee Rules</div>	
                                    <div class="wcff-rule-container">
                                        <div class="wcff-rule-container-is-empty">Fee rule is empty!</div>
                                    </div>													
    								<input type="button" class="wcff-add-fee-rule-btn button" class="button" value="Add Fee Rule">
    							</div>
    							<input type="hidden" name="wcff_pricing_rules" class="wcff_pricing_rules" value="" />
    							<input type="hidden" name="wcff_fee_rules" class="wcff_fee_rules" value="" />
    						</td>
    					</tr>					
    				</tbody>
    			</table>		
    		</div>';
    	 $wrapper .= '<div class="wcff-factory-tab-child wcff-factory-tab-fields-rules">			
    		<table class="wcff_table">
    			<tbody class="wcff-field-types-meta-body">
    				<tr>
    					<td class="summary">
    						<label for="post_type"><a href="https://sarkware.com/field-rule-wc-fields-factory/" target="_blank" title="Documentation">Click here for Documentation</a></label>
    						<br/>
    						<label for="post_type">'. __( 'Field Rules', 'wc-fields-factory' ) .'</label>
    						<p class="description">'. __( 'Hide or show fields based on user interaction.', 'wc-fields-factory' ) .'</p>
    						<br/>
    						<label for="post_type">'. __( 'How it works', 'wc-fields-factory' ) .'</label>
    						<p class="description">'. __( 'Use \'Add Field rule\' to add a field rule, specify the field value and select a condition. Then choose which are the field want to hide or show.', 'wc-fields-factory' ) .'</p>
    						<br/>
    						<label for="post_type">'. __( 'Rule Type', 'wc-fields-factory' ) .'</label>
    						<p class="description">'. __( '<strong>Hide :</strong> Field will be hidden if the condition met. <br/><strong>Show :</strong> Field will be visible if the condition met.<br/><strong>Nill :</strong> Doesn\'t affect .', 'wc-fields-factory' ) .'</p>							
    					</td>
    					<td style="vertical-align: top;" class="wcff-content-config-cell">
    						<div class="wcff-tab-rules-wrapper field">		
                               <div class="wcff-parent-rule-title">Field Rules</div>	
                               <div class="wcff-rule-container">
                                   <div class="wcff-rule-container-is-empty">Field rule is empty!</div>
                               </div>																											
    							<input type="button" class="wcff-add-field-rule-btn button wcff-add-field-rule-btn" value="Add Field Rule">
    						</div>
    					</td>
    				</tr>					
    			</tbody>
    		</table>		
    	</div>';
	 
    	 $wrapper .= '<div class="wcff-factory-tab-child wcff-factory-tab-color-image" style="display:none;">
           <table class="wcff_table">
    			<tbody class="wcff-field-types-meta-body">
    				<tr>
    					<td class="summary">
    						<label for="post_type"><a href="https://sarkware.com/field-rule-wc-fields-factory/" target="_blank" title="Documentation">Click here for Documentation</a></label>
    						<br/>
    						<label for="post_type">'. __( 'Product Image', 'wc-fields-factory' ) .'</label>
    						<p class="description">'. __( 'Choose your color pallet and perticular color based image.', 'wc-fields-factory' ) .'</p>
                            <br/>
    						<label for="post_type">'. __( 'Choose Option', 'wc-fields-factory' ) .'</label>
    						<p class="description">'. __( 'Choose image or color related another product.', 'wc-fields-factory' ) .'</p>
    					</td>
    					<td style="vertical-align: top;" class="wcff-content-config-cell">
    						<div class="wcff-tab-rules-wrapper color-image">		
                               <div class="wcff-parent-rule-title">Color Image</div>	
                               <div class="wcff-rule-container">
                                   <div class="wcff-rule-container-is-empty">Product Image rule is empty!</div>
                               </div>
    							<input type="button" class="wcff-add-color-image-rule-btn button wcff-add-color-image-rule-btn" value="Add Field Rule">
    						</div>
    					</td>
    				</tr>					
    			</tbody>
    		</table>		
    	</div>';
    	endif;
    	$wrapper .= '</div>
                    </div>
                    </div>';
    	return $wrapper;
}