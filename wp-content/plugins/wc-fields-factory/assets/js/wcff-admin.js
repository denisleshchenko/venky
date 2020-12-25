/**
 * @author  	: Saravana Kumar K
 * @author url 	: http://iamsark.com
 * @url			: http://sarkware.com/
 * @copyrights	: SARKWARE
 * @purpose 	: wcff Controller Object.
 */

(function($) {	

	var mask = null;
	var wcff = function() {
		/* used to holds next request's data (most likely to be transported to server) */
		this.request = null;
		/* used to holds last operation's response from server */
		this.response = null;
		/* to prevetn Ajax conflict. */
		this.ajaxFlaQ = true;
		/* Holds currently selected fields */
		this.activeField = null;
		/**/
		this.pricingRules = [];
		/**/
		this.feeRules = [];
		/* */
		this.fieldRules = [];
		/* */
		this.colorImage = [];
		/* */
		this.val_error = { message : "", elem : $(""), flg : false };
		/* */
		this.postSubmit = false;
		
		/* */
		this.mediaFrame = null;

		
		this.initialize = function() {
			this.registerEvents();
		};
		
		/* Responsible for registering handlers for various DOM events */
		this.registerEvents = function() {		
			/* catch post submit event */
			$(document).on( "submit", "#post", this, function(e) {
				if( !e.data.postSubmit ){
					e.data.onFieldSubmit();	
					e.preventDefault();
					return false;
				}
			});	
			
			/* Click handler for Adding Condition */
			$(document).on( "click", "a.condition-add-rule", this, function(e) {
				e.data.addCondition( $(this) );
				e.preventDefault();
			});		
			/* Click handler for Removing Condition */
			$(document).on( "click", "a.condition-remove-rule", this, function(e) {
				e.data.removeRule( $(this) );
				e.preventDefault();
			});			
			/* Click handler for Adding Condition Group */
			$(document).on( "click", "a.condition-add-group", this, function(e) {
				e.data.addConditionGroup( $(this) );
				e.preventDefault();
			});			
			/* Click handler for Adding Location Rule */
			$(document).on( "click", "a.location-add-rule", this, function(e) {
				e.data.addLocation( $(this) );
				e.preventDefault();
			});	
			
			/* Click handler for Removing Location Rule */
			$(document).on( "click", "a.location-remove-rule", this, function(e) {
				e.data.removeRule( $(this) );
				e.preventDefault();
			});			
			/* Click handler for Adding Location Group Rule */
			$(document).on( "click", "a.location-add-group", this, function(e) {
				e.data.addLocationGroup( $(this) );
				e.preventDefault();
			});			
			/* Click handler for Removing Pricing Rule */
			$(document).on( "click", "a.pricing-remove-rule", this, function(e) {
				e.data.ruleEmtyShow( $( this ) );
				$(this).parent().parent().parent().parent().remove();
				e.preventDefault();
			});			
			/* Click handler for Removing Fee Rule */
			$(document).on( "click", "a.fee-remove-rule", this, function(e) {
				e.data.ruleEmtyShow( $( this ) );
				$(this).parent().parent().parent().parent().remove();
				e.preventDefault();
			});		
			$(document).on("change", "[name=wcff-field-type-meta-choices]", this, function(e){
				if( $( this ).closest( ".wcff-locale-list-wrapper" ).length == 0 ){
					e.data.activeField["choices"] = $( this ).val();
				}
			});
			/* Click handler for Removing Field Rule */
			$(document).on( "click", "a.field-remove-rule", this, function(e) {
				e.data.ruleEmtyShow( $( this ) );
				$(this).closest(".wcff-field-row").remove();
				e.preventDefault();
			});		
			/* Click handler for Sub Field Group Rule */
			$(document).on( "click", "a.fgroup-remove-rule", function(e) {
				$(this).parent().parent().parent().parent().remove();
				e.preventDefault();
			});	
			/* */
			$( document ).on( "change", ".wcff-meta-row input[type=radio]", function(){
				if( $( this ).is( ":checked" ) ){
					$( this ).closest( "ul" ).find( "input" ).prop( "checked", false );
					$( this ).prop( "checked", true );
				}
			});
			
			$( document ).on( "click", ".wcff-upload-custom-img", this, function(e){
				    e.preventDefault();
				    var image_sel_holder = $( this ).parent().parent();
				    	image_prev = image_sel_holder.find( ".wcff-prev-image" ),
				    	image_url  = image_sel_holder.find( ".wcff-image-url-holder" ),
				    	addImgLink = image_sel_holder.find( ".wcff-upload-custom-img" ),
				        delImgLink = image_sel_holder.find( ".wcff-delete-custom-img" );
				    // If the media frame already exists, reopen it.
				    if (  e.data.mediaFrame ) {
				      e.data.mediaFrame.open();
				      return;
				    }
				    
				    e.data.mediaFrame = wp.media({
				      title: 'Select or Upload Media Of Your Chosen Persuasion',
				      button: {
				        text: 'Use this media'
				      },
				      multiple: false  
				    });
				    
				    e.data.mediaFrame.on( 'select', function() {
				    	var attachment = e.data.mediaFrame.state().get('selection').first().toJSON();
				    	image_prev.replaceWith( '<img class="wcff-prev-image" src="'+attachment.url+'" alt="" style="width:80px;"/>' );
				    	image_url.val( attachment.id );
				    	addImgLink.addClass( 'hidden' );
				    	delImgLink.removeClass( 'hidden' );
				    });
				    e.data.mediaFrame.open();
			});
			
			$( document ).on( "click", ".wcff-delete-custom-img", this, function(e){
					e.preventDefault();
			    var image_sel_holder = $( this ).parent().parent();
			    	image_prev = image_sel_holder.find( ".wcff-prev-image" ),
			    	image_url  = image_sel_holder.find( ".wcff-image-url-holder" ),
			    	addImgLink = image_sel_holder.find( ".wcff-upload-custom-img" );
			    	image_prev.replaceWith( '<img class="wcff-prev-image" src="'+wcff_var.plugin_dir+'/assets/img/placeholder-image.jpg" alt="" style="width:80px;"/>' );
			    	$( this ).addClass( 'hidden' );
			    	addImgLink.removeClass( 'hidden' );
			    	image_url.val( '' );
			});

			/* */
			$( document ).on( "change", ".wcff-color-image-select-container input[type=radio]", function(){
				if( $( this ).is( ":checked" ) ){
					$( this ).closest( ".wcff-color-image-select-container" ).find( ".color-active" ).removeClass( "color-active" );
					$( this ).closest( ".wcff-color-image-select-container" ).find( "input" ).prop( "checked", false );
					$( this ).prop( "checked", true );
					$( this ).parent().addClass( "color-active" );
				}
			});
			
			
			/* Click handler for Field Delete */
			$(document).on( "click", "a.wcff-field-delete", this, function(e) {
				uc = confirm("Are you sure, you want to delete this field.?");
				if (uc === true) {
					if( $(this).closest( ".wcff-meta-row" ).attr( "data-key" ).trim() != "" ){
						mask.doMask( $(this).closest( ".wcff_fields_factory_header" ) );
						e.data.prepareRequest( "DELETE", "wcff_fields", { field_key : $(this).attr("data-key") } );
						e.data.dock( "wcff_fields", $(this) );
					} else {
						$(this).closest( ".wcff-meta-row" ).remove();
						if( $( "#wcff-fields-set .wcff-meta-row" ).length == 0 ){
							$( "#wcff-empty-field-set" ).show();
						}
					}
				}				
				e.preventDefault();
			});	
				
			/* Click handler for Field Label - whenever user click on it, will go to Field Edit mode */
			$(document).on( "click", ".wcff-meta-row", this, function(e) {
				if( $( e.target ).closest( ".wcff_table" ).parent().is( ".wcff-meta-row" ) 
					&& !$( e.target ).is( "input" ) 
					&& !$( e.target ).is( "button.wcff-factory-multilingual-label-btn, img" )
					&& $( e.target ).closest( "div.wcff-meta-option" ).length == 0 ) {
					var target = $(this),
					targetArr =  target.closest( ".wcff-meta-row" ),
					hasActive = targetArr.is( ".active" );
					$( "#wcff-fields-set .active" ).removeClass( "active" ); 
					if( target.is( ".wcff-field-config-drawer-opened" ) ){
						if( hasActive ){
							targetArr.addClass( "active" );
							targetArr.removeClass( "wcff-field-config-drawer-opened" );
							targetArr.addClass( "wcff-field-config-drawer-closed" );
							targetArr.find( ".wcff-field-label" ).html( targetArr.find( "input[name=wcff-field-type-meta-label-temp]" ).val() );
							targetArr.find( ".wcff_fields_factory_config_wrapper" ).hide();
							targetArr.find( "> table .wcff-factory-locale-label-dialog" ).hide();
							targetArr.removeClass( "active" );
						} else {
							targetArr.addClass( "active" );
						}
					} else if ( target.is( ".wcff-field-config-drawer-closed" ) ){
						targetArr.removeClass( "wcff-field-config-drawer-closed" );
						targetArr.addClass( "wcff-field-config-drawer-opened" );
						targetArr.find( ".wcff-field-label" ).html( '<input type="text" name="wcff-field-type-meta-label-temp" value="'+targetArr.find( ".wcff-field-label" ).text()+'">' );
						targetArr.find( ".wcff_fields_factory_config_wrapper" ).fadeIn();
						target.addClass( "active" );
					} else {
						if( $("#wcff-field-type-meta-label").length != 0 && $("#wcff-field-type-meta-label").val().trim == "" ){
							$("#wcff-field-type-meta-label").addClass("wcff-form-invalid");	
						} else {
							targetArr.find("input[name=wcff-field-type-meta-label-temp]").parent().html( $("input[name=wcff-field-type-meta-label-temp]").val() );
							$( ".wcff-meta-option .wcff-field-edit" ).show();
							$(".wcff-meta-row").removeClass("active");
							$(this).addClass("active wcff-field-config-drawer-opened");
							mask.doMask( $(this).parent().parent().parent().parent().parent() );
							e.data.prepareRequest( "GET", "wcff_fields", { field_key : $(this).attr("data-key") } );
							e.data.dock( "wcff_fields", $(this) );		
							targetArr.find( ".wcff-field-label" ).html( '<input type="text" name="wcff-field-type-meta-label-temp" value="'+targetArr.find( ".wcff-field-label" ).text()+'">' );
						}
					} 
					e.preventDefault();
				}
			});			
		
			/* Keyup handler for Field Label field - as the user keep typing on it, 
			 * all the characters will be url sanitized and placed on the Name field */
			$(document).on( "keyup", "input[name=wcff-field-type-meta-label-temp]", this, function(e) {
				$(this).closest( "table" ).find( "label.wcff-field-name" ).val( e.data.sanitizeStr( $(this).val() ) );	
				if( $(this).val() !== "" ) {
					$(this).removeClass("wcff-form-invalid");
				}
			});			
			/* Keyup handler for Repeater Field Label - as the user keep typing on it, 
			 * all the characters will be url sanitized and placed on the Repeater Name field */
			$(document).on( "keyup", "#wcff-repeater-meta-label", this, function(e) {
				$( "#wcff-repeater-meta-name" ).val( e.data.sanitizeStr( $(this).val() ) );
				if( $(this).val() !== "" ) {
					$(this).removeClass("wcff-form-invalid");
				}
			});		
			/* Change handler for Condition Param - it has to reload the target ( Product List, Cat List, Tag List ... ) */
			$(document).on( "change", ".wcff_condition_param", this, function(e) {
				e.data.prepareRequest( "GET", $(this).val(), "" );
				e.data.dock( $(this).val(), $(this) );
			});		
			/* Condition param for variation product */
			$(document).on( "change", ".variation_product_list", this, function(e) {
				e.data.prepareRequest( "GET", "product_variation", { "product_id" : $(this).val() } );
				e.data.dock( "product_variation", $(this) );
			});	
			/* Change handler for Location Param - it has to reload the target ( Tab List, Meta Box Context List ... ) */
			$(document).on( "change", ".wcff_location_param", this, function(e) {
				e.data.prepareRequest( "GET", $(this).val(), "" );
				e.data.dock( $(this).val(), $(this) );
			});	
			$(document).on( "click", ".wcff-factory-tab-header > a", this, function(e) {
				e.preventDefault();
				var wrapper = $( this ).closest( ".wcff_fields_factory_config_wrapper" ),
					ftype   = wrapper.closest( ".wcff-meta-row" ).attr( "data-type" );
				wrapper.find(".wcff-factory-tab-fields-meta, .wcff-factory-tab-pricing-rules, .wcff-factory-tab-fields-rules, .wcff-factory-tab-color-image").fadeOut("fast");
				if( !wrapper.closest( ".wcff-meta-row" ).is( ".active" ) ){
					wrapper.closest( ".wcff-meta-row" ).find( ".wcff_table:first .wcff-field-label" ).click();
				}
				wrapper.find(".wcff-factory-tab-header > a").removeClass();
				$(this).addClass("selected");			
				wrapper.find($(this).attr("href")).fadeIn("fast");
				if( ftype == "radio" || ftype == "select" ){
					var rule_expected =	wrapper.find( "select[class*=choice-expected-value]" );
					var defVal = "";
					for( var i = 0; i < rule_expected.length; i++ ){
						defVal = $( rule_expected[i] ).val();
						var choices = e.data.activeField["choices"].trim().split("\n");
						if (choices) {
							var html = "",
								opt  = [];
							for (var j = 0; j < choices.length; j++) {
								opt = choices[j].split("|");
								html += '<option value="'+ opt[0] +'">'+ opt[1] +'</option>';
							}
							$( rule_expected[i] ).html( html );
						}
						if( defVal != "" ){
							$( rule_expected[i] ).val( defVal );
						}
					}
				} else if( ftype == "colorpicker" ){
					if( wrapper.find( "[name=wcff-field-type-meta-palettes]" ).length != 0 ){
						e.data.activeField["choices"] = wrapper.find( "[name=wcff-field-type-meta-palettes]" ).val().trim().replace("\n", ",");
					}
				}
			});
			$(document).on( "click", ".wcff-rule-toogle > a", function(e) {
				$(this).parent().find("a").removeClass("selected");
				$(this).addClass("selected");
				if( $(this).parent().is( ".wcff-rule-placeholder-change" ) ){
					$(this).closest( "tr" ).find( ".wcff-pricing-rules-amount" ).attr( "placeholder", $(this).attr( "data-tprice" ) == "cost" ? "Amount" : "Percentage" );
				}
				if( $( this ).parent().is( ".wcff-color-image-toggle" ) ){
					 $( this ).parent().parent().parent().find( "div.wcff-image-selector-container" ).toggle();
					 $( this ).parent().parent().parent().find( "div.wcff-url-selector-container" ).toggle();
				}
				e.preventDefault();
			});			
			/* Click handler for Pricing rule add button */
			$(document).on( "click", ".wcff-add-price-rule-btn", this, function(e) {
				e.data.addCommonRule( $(this), "pricing" );
			});
			/* Click handler for Fee rule add button */
			$(document).on( "click", ".wcff-add-fee-rule-btn", this, function(e) {
				e.data.addFeeRule( $(this) );
			});
			/* Click handler for Field rule add button */
			$(document).on( "click", ".wcff-add-field-rule-btn", this, function(e) {
				e.data.addCommonRule( $(this), "field" );
			});
			
			/* Click handler for color based product image rule add button */
			$(document).on( "click", ".wcff-add-color-image-rule-btn", this, function(e) {
				e.data.addCommonRule( $(this), "color-image" );
				e.preventDefault();
			});	
			
			/* Click hanlder tab headers */
			$(document).on( "click", "div.wcff-factory-tab-left-panel li", this, function(e) {					
				$(this).parent().parent().next().find(">div").hide()
				$(this).parent().find("> li").removeClass();
				$(this).addClass("selected");			
				$(this).parent().parent().next().find(">div:nth-child("+ ($(this).index() + 1) +")").show();
			});	
			/* Click hanlder for clearing Week ends and Week days radio buttons */
			$(document).on( "click", "a.wcff-date-disable-radio-clear", this, function(e) {	
				$(this).parent().prev().find( "input" ).prop( "checked", false );
				e.preventDefault();
			});
			/* Change event handler for File preview option radio button */
			$(document).on( "change", "input[name=wcff-field-type-meta-img_is_prev]", this, function(e) {
				if( $( this ).val() === "yes" ){
					$( "div[data-param=img_is_prev_width]" ).fadeIn();
				} else{
					$( "div[data-param=img_is_prev_width]" ).fadeOut();
				}
				e.preventDefault();
			});
			/* Keyup hanlder for Choices textarea - which is used to generate default options ( select, radio and check box ) */
			$(document).on( "keyup", "textarea.wcff-choices-textarea", this, function(e) {
				e.data.handleDefault($(this));
			});
			/* Change event handler for validtaing Choice's label and value text bix - Choice Widget */
			$(document).on( "change", ".wcff-option-value-text, .wcff-option-label-text", this, function(e) {
				if($(this).val() == "") {
					$(this).addClass("invalid");
				} else {
					$(this).removeClass("invalid");
				}
			});
			/* Click handler for add option button - Choice Widget */
			$(document).on( "click", "button.wcff-add-opt-btn", this, function(e) {				
				e.data.addOption($(this));
				e.preventDefault();
				e.stopPropagation();
			});
			
			/* */
			$(document).on( "change", ".wcff-fields-location-radio", this, function(){
				if( $( this ).val() == "woocommerce_single_product_tab" ){
					$( ".wcff-group-tab-secion" ).show();
				} else {
					$( ".wcff-group-tab-secion" ).hide();
				}
			});
			/**/
			$(document).on( "change", "input.wcff-field-type-meta-show_on_product_page", this, function (e) {
				var display = "table-row";
				if ($(this).val() === "no") {
					display = "none";
				}
				$("div.wcff-field-types-meta").each(function () {
					var flaq = false;
					if ($(this).attr("data-param") === "visibility" || 
						$(this).attr("data-param") === "order_meta" ||
						$(this).attr("data-param") === "login_user_field" ||
						$(this).attr("data-param") === "cart_editable" ||
						$(this).attr("data-param") === "cloneable" ||
						$(this).attr("data-param") === "show_as_read_only" ||
						$(this).attr("data-param") === "showin_value" ) {
						flaq = true;
					}					
					if (flaq) {
						$(this).closest("tr").css("display", display);
					}
				});
			});
			/* */
			$(document).on( "change", ".wcff-meta-row input, .wcff-meta-row select, .wcff-meta-row textarea", this, function (e) {	
				if( $( e.target ).closest( ".wcff-meta-option" ).length == 0 ){
					e.data.changeActiveField( $( this ).closest( ".wcff-meta-row" ) );
				}
			});
			/* */
			$(document).on( "change", ".wcff-field-type-meta-login_user_field", this, function (e) {				
				var display = ($(this).val() === "no") ? "none" : "table-row";				
				$(this).closest( ".wcff-meta-row" ).find("div[data-param=show_for_roles]").closest("tr").css("display", display);
			});
			/* */
			$(document).on( "change", "input[name=wcff-field-type-meta-timepicker]", this, function (e) {
				var display = ($(this).val() === "no") ? "none" : "table-row";
				$("div[data-param=min_max_hours_minutes]").closest("tr").css("display", display);
			});
			/**/
			$(document).on( "click", "button.wcff-factory-multilingual-label-btn, button.wcff-factory-multilingual-btn", function(e) {
				if ($(this).hasClass("wcff-factory-multilingual-btn")) {
					$(this).nextAll("div.wcff-locale-list-wrapper").first().toggle("normal");
				} else {
					$(this).next().toggle("normal");
				}
				e.preventDefault();
			});
			/**/
			$(document).on( "change", "input.invalid", function() {
				$(this).removeClass("invalid");
			});
			/* Submit action handler for Wordpress Update button */
			$(document).on( "submit", "form#post", this, function(e) {			
				return e.data.onPostSubmit( $(this));
			});		
			
			$(document).on( "change", ".wcff-field-type", this, function(e) {			
				/* Change handler for Field Type select field */
				e.data.prepareRequest( "GET", "wcff_meta_fields", { type : $( this ).text() } );
				e.data.dock( "wcff_meta_fields", $(this).closest( ".wcff-meta-row" ) );
				$( this ).html( '<span style="background: url('+wcff_var.plugin_dir+'/assets/img/'+ $( this ).text() +'.png) no-repeat left;"></span>'+  $( this ).text() +'' );
			});		
			
			$(document).on( "keyup", "input[name=wcff-field-type-meta-label-temp]", this, function(e){
				if( $(this).closest( ".wcff-meta-row" ).data( "key" ) == "" ){
					$(this).closest( ".wcff-meta-row" ).find( ".field-name label.wcff-field-name" ).text( e.data.sanitizeStr( $(this).val() ) );
				}
			});
			
			$(document).on( "dragstart", '.wcff-meta-row.active', function(e){
				e.preventDefault();
				return false;
			});
			
			/* */
			$(document).on( "change", '.wcff-field-type-meta-show_palette_only', function(e){
				target = $( this ).closest( ".wcff-meta-row" );
				if( $( this ).val() == "yes" ){
					target.find( "a[href='.wcff-factory-tab-color-image']" ).show();
				} else {
					target.find( "a[href='.wcff-factory-tab-color-image']" ).hide();
				}
			});
			/* To dismiss wcff rating asking */
			$( document ).on( "click", ".wcff-ask-rate-diss", this, function(e){
				e.preventDefault();
				$( this ).closest( ".notice" ).find( "button" ).click();
				e.data.prepareRequest( "POST", "wcff_ask_rate_diss", {} );
				e.data.dock( "wcff_ask_rate_diss", $("") );
			}); 
			/* Disable enable fields */
			$(document).on( "change", ".wcff-toggle-check", function(){
				var action = $( this ).is( ":checked" ) ? "true" : "false";
				$( this ).closest( ".wcff-meta-row" ).attr( "data-is_enable", action );
			});
			
		};
		
		this.ruleEmtyShow = function( _target ){
			if( _target.closest( ".wcff-rule-container" ).find( ">table" ).length == 1 ){
				_target.closest( ".wcff-rule-container" ).find( ".wcff-rule-container-is-empty" ).show();
			}
		};
		
		this.addOption = function(_btn) {
			var	value = _btn.prevAll("input.wcff-option-value-text").first(),	
				label = _btn.prevAll("input.wcff-option-label-text").first();							
			if (value.val() == "") {
				value.addClass("invalid");
				value.focus();
			} else {
				value.removeClass("invalid");
			}
			if (label.val() == "") {
				label.addClass("invalid");
				label.focus();
			} else {
				label.removeClass("invalid");
			}
			if (value.val() != "" && label.val() != "") {
				var opt_holder = _btn.closest( ".wcff-meta-row" ).find("textarea[name=" + _btn.attr("data-target") + "]");
				/* Make sure the textarea has newline as last character
				 * As newline is used as delimitter */
				if(opt_holder.val() != "") {
					if(opt_holder.val().slice(-1) != "\n") {
						opt_holder.val( opt_holder.val() + "\n" );
					}
				}
				opt_holder.val( opt_holder.val() + ( value.val() +"|"+ label.val()) +"\n" );
				if( _btn.closest( ".wcff-locale-block" ).length == 0 ){
					this.activeField["choices"] = opt_holder.val();
				}
				/* Clear the fields */
				value.val("");
				label.val("");
				/* Set the focus to value box
				 * So that user can start input next option */
				value.focus();
				/**/
				this.handleDefault(_btn.closest( ".wcff-meta-row" ).find("textarea[name=" + _btn.attr("data-target") + "]" ));
			}
		};
		
		this.handleDefault = function(_option_field) {
			var html = '',
				keyval = [],
				is_valid = true,				
				default_val = null,	
				options = _option_field.val(),		
				parent_field = _option_field.closest( ".wcff-meta-row" ),
				dcontainer = parent_field.find(".wcff-default-option-holder");
			
			var locale = _option_field.attr('data-locale');
			var ftype  = parent_field.attr( "data-type" );
			
			if (typeof locale !== typeof undefined && locale !== false) {
				dcontainer = parent_field.find(".wcff-default-option-holder-"+locale);
			}
				
			/* Shave of any unwanted character at both ends, includig \n */
			options = options.trim();
			options = options.split("\n");
			/* Handle the default option */
			if (ftype === "checkbox") {
				default_val = dcontainer.find("input[type=checkbox]:checked").map(function() {
				    return this.value;
				}).get();	
				/* Reset it */
				dcontainer.html("");				
				html += '<ul>';
				for (var i = 0; i < options.length; i++) {
					keyval = options[i].split("|");
					if (keyval.length == 2 && keyval[0].trim() != "" && keyval[1].trim() != "") {
						if (default_val && default_val.indexOf(keyval[0]) > -1) {
							html += '<li><input type="checkbox" value="'+ keyval[0] +'" checked /> '+ keyval[1] +'</li>';
						} else {
							html += '<li><input type="checkbox" value="'+ keyval[0] +'" /> '+ keyval[1] +'</li>';
						}						
					}
				}				
				html += '</ul>';
				dcontainer.html(html);
			} else if(ftype === "radio") {
				default_val = dcontainer.find("input[type=radio]:checked" ).val();
				/* Reset it */
				dcontainer.html("");
				html += '<ul>';
				for (var i = 0; i < options.length; i++) {
					keyval = options[i].split("|");
					if (keyval.length == 2 && keyval[0].trim() != "" && keyval[1].trim() != "") {
						if (default_val && default_val === keyval[0]) {
							html += '<li><input type="radio" value="'+ keyval[0] +'" checked /> '+ keyval[1] +'</li>';
						} else {
							html += '<li><input type="radio" value="'+ keyval[0] +'" /> '+ keyval[1] +'</li>';
						}						
					}
				}				
				html += '</ul>';
				dcontainer.html(html);
			} else {
				/* This must be select box */
				default_val = dcontainer.find("select").val();
				/* Reset it */
				dcontainer.html("");
				html += '<select>';
				html += '<option value="">-- Choose the default Option --</option>';
				for (var i = 0; i < options.length; i++) {
					keyval = options[i].split("|");
					if (keyval.length == 2 && keyval[0].trim() != "" && keyval[1].trim() != "") {
						if (default_val && default_val === keyval[0]) {
							html += '<option value="'+ keyval[0] +'" selected >'+ keyval[1] +'</option>';
						} else {
							html += '<option value="'+ keyval[0] +'">'+ keyval[1] +'</option>';
						}						
					}
				}				
				html += '</select>';
				dcontainer.html(html);
			}	
		};
		
		this.addCondition = function( target ) {
			var ruleTr = $( '<tr></tr>' );			
			ruleTr.html( target.parent().parent().parent().find("tr").last().html() );				
			if( target.parent().parent().parent().children().length == 1 ) {
				ruleTr.find("td.remove").html( '<a href="#" class="condition-remove-rule wcff-button-remove"></a>' );
			}			
			target.parent().parent().parent().append( ruleTr );		
			ruleTr.find( "select.wcff_condition_param" ).trigger( "change" );
		};
		
		this.addLocation = function( target ) {
			var locationTr = $( '<tr></tr>' );
			locationTr.html( target.parent().parent().parent().find("tr").last().html() );
			if( target.parent().parent().parent().children().length === 1 ) {
				locationTr.find("td.remove").html( '<a href="#" class="location-remove-rule wcff-button-remove"></a>' );
			}	
			target.parent().parent().parent().append( locationTr );			
			locationTr.find( "select.wcff_location_param" ).trigger( "change" );
		};
		
		this.removeRule = function( target ) {		
			var parentTable = target.parent().parent().parent().parent(),
			rows = parentTable.find( 'tr' );		
			if( rows.size() === 1 ) {
				parentTable.parent().remove();
			} else {
				target.parent().parent().remove();
			}
		}; 
		
		this.addConditionGroup = function( target ) {
			var groupDiv = $( 'div.wcff_logic_group:first' ).clone( true );
			var rulestr = groupDiv.find("tr");			
			if( rulestr.size() > 1 ) {
				var firstTr = groupDiv.find("tr:first").clone( true );
				groupDiv.find("tbody").html("").append( firstTr );				
			}
			groupDiv.find("h4").html( "or" );
			target.prev().before( groupDiv );			
			groupDiv.find("td.remove").html( '<a href="#" class="condition-remove-rule wcff-button-remove"></a>' );
			groupDiv.find( "select.wcff_condition_param" ).trigger( "change" );
		};
		
		this.addLocationGroup = function( target ) {
			var groupDiv = $( 'div.wcff_location_logic_group:first' ).clone( true );
			var rulestr = groupDiv.find("tr");			
			if( rulestr.size() > 1 ) {
				var firstTr = groupDiv.find("tr:first").clone( true );
				groupDiv.find("tbody").html("").append( firstTr );				
			}
			groupDiv.find("h4").html( "or" );
			target.prev().before( groupDiv );			
			groupDiv.find("td.remove").html( '<a href="#" class="location-remove-rule wcff-button-remove"></a>' );
			groupDiv.find( "select.wcff_condition_param" ).trigger( "change" );
		};
		
		this.addCommonRule = function( target, _type ) {
			var html = '',
				targetAttr = _type;
			if (this.activeField["type"] === "datepicker") {
				html = this.buildPricingWidgetDatePicker(targetAttr, null);
			} else if (this.activeField["type"] === "checkbox") {
				html = this.buildPricingWidgetMultiChoices(targetAttr, null);
			} else if (this.activeField["type"] === "radio" || this.activeField["type"] === "select") {
				html = this.buildPricingWidgetChoice(targetAttr, null);
			} else {
				html = this.buildPricingWidgetInput(targetAttr, null);
			}			
			target.parent().find(".wcff-rule-container-is-empty").hide();
			target.parent().find(".wcff-rule-container").append($(html));						
		};
		
		this.addFeeRule = function( target ) {
			var html = '';				
			if (this.activeField["type"] === "datepicker") {
				html = this.buildPricingWidgetDatePicker("fee", null);
			} else if (this.activeField["type"] === "checkbox") {
				html = this.buildPricingWidgetMultiChoices("fee", null);
			} else if (this.activeField["type"] === "radio" || this.activeField["type"] === "select") {
				html = this.buildPricingWidgetChoice("fee", null);
			} else {
				html = this.buildPricingWidgetInput("fee", null);
			}			
			target.parent().find(".wcff-rule-container-is-empty").hide();
			target.parent().find(".wcff-rule-container").append($(html));			
		};
		
		this.changeActiveField = function( _target ){
			if( this.activeField["key"] != _target.attr( "data-key" ) || _target.attr( "data-key" ) == "" ){
				this.activeField = {};
				this.activeField["type"] = _target.attr( "data-type" );
				this.activeField["key"]  = _target.attr( "data-key" ) == "" ? undefined : _target.attr( "data-key" );
				this.activeField["choices"] = _target.find( "[name=wcff-field-type-meta-choices]" ).length != 0  ? _target.find( "[name=wcff-field-type-meta-choices]" ).val() : this.activeField["type"] == "colorpicker" ? _target.find( "[name=wcff-field-type-meta-palettes]" ).val() : "";
			}
		}
		
		this.renderSingleView = function( _target ) {
			var i = 0,
				j = 0,
				me = this,
				html = '',
				keyval = [],
				options = [],
				fee_row = null,				
				pricing_row = null,
				default_val = null,
				temp_holder = null,
				dcontainer = _target.find( ".wcff-default-option-holder" ),
				wrapper = _target.find( ".wcff_fields_factory_config_wrapper" );
			/* Store meta key in to activeField */
			this.activeField["key"] = _target.attr( "data-key" );
						
			/* Scroll down to Field Factory Container */
			$('html,body').animate(
				{ scrollTop: wrapper.offset().top - 50  },
		        'slow'
		    );	
		
			wrapper.find(".wcff-factory-tab-header > a:first-child").trigger("click");				
			wrapper.find(".wcff-field-type-meta-type").val( this.unEscapeQuote( this.activeField["type"] ) );	
			wrapper.find(".wcff-field-type-meta-label").val( this.unEscapeQuote( this.activeField["label"] ) );
			wrapper.find(".wcff-field-type-meta-name").val( this.unEscapeQuote( this.activeField["name"] ) );
			
			/* Locales for Label */
			if (me.activeField["locale"]) {
				for(var i = 0; i < wcff_var.locales.length; i++) {
					if(_target.find("[name=wcff-field-type-meta-label-" + wcff_var.locales[i] + "]" ).length > 0) {
						if(this.activeField["locale"][wcff_var.locales[i]] && this.activeField["locale"][wcff_var.locales[i]]["label"]) {
							_target.find("[name=wcff-field-type-meta-label-" + wcff_var.locales[i] +"]" ).val(this.activeField["locale"][wcff_var.locales[i]]["label"]);
						}
					}
				}
			}
			/* Hide it, it may not necessory */
			_target.find(".wcff-factory-locale-label-dialog").hide();
			
			/* If it is Datepicker then reset the Disable Date widget */
			if (this.activeField["type"] === "datepicker") {
				$("div.wcff-factory-tab-right-panel").find("div.wcff-field-types-meta").each(function() {
					if ($(this).attr("data-param") !== "") {
						var param = $(this).attr("data-param");
						var type = $(this).attr("data-type");
						if (type === "checkbox" || type === "radio") {
							$(this).find("input[type="+ type +"]").prop('checked', false);
						} else {							
							$(this).find(type).val("");							
						}
					}
				});
			}
			
			/* Set the appropriate params with values */
			wrapper.find(".wcff-field-types-meta-body div.wcff-field-types-meta").each(function() {
				if ($(this).attr("data-param") === "choices" || $(this).attr("data-param") === "palettes") {					
					me.activeField[$(this).attr("data-param")] = me.activeField[$(this).attr("data-param")].replace( /;/g, "\n");
				}								
				if ($(this).attr("data-type") === "checkbox") {
					var choices = me.activeField[$(this).attr("data-param")];	
					if (choices) {
						for (i = 0; i < choices.length; i++) {					
							wrapper.find("input.wcff-field-type-meta-"+ $(this).attr("data-param") +"[value='"+ choices[i] +"']" ).prop('checked', true);
						}
					}					
				} else if ($(this).attr("data-type") === "radio") {
					wrapper.find(".wcff-field-type-meta-"+ $(this).attr("data-param") +"[value='"+ me.activeField[$(this).attr("data-param")] +"']").prop('checked', true);				
					wrapper.find(".wcff-field-type-meta-"+ $(this).attr("data-param") +"[value='"+ me.activeField[$(this).attr("data-param")] +"']" ).trigger( "change" );
				} else {
					if ($(this).attr("data-type") !== "html") {
						wrapper.find(".wcff-field-type-meta-"+$(this).attr("data-param")).val(me.unEscapeQuote(me.activeField[$(this).attr("data-param")]));
					}						
				}
				/* Load locale related fields */
				if (me.activeField["locale"]) {
					for (i = 0; i < wcff_var.locales.length; i++) {
						if (wrapper.find("[name=wcff-field-type-meta-"+ $(this).attr("data-param") + "-" + wcff_var.locales[i] + "]" ).length > 0) {
							if ($(this).attr("data-param") === "choices" && me.activeField["locale"][wcff_var.locales[i]] && me.activeField["locale"][wcff_var.locales[i]][$(this).attr("data-param")]) {
								me.activeField["locale"][wcff_var.locales[i]][$(this).attr("data-param")] = me.activeField["locale"][wcff_var.locales[i]][$(this).attr("data-param")].replace( /;/g, "\n");
							}
							if (me.activeField["locale"][wcff_var.locales[i]] && me.activeField["locale"][wcff_var.locales[i]][$(this).attr("data-param")]) {
								wrapper.find("[name=wcff-field-type-meta-"+ $(this).attr("data-param") + "-" + wcff_var.locales[i] + "]" ).val(me.activeField["locale"][wcff_var.locales[i]][$(this).attr("data-param")]);
							}						
						}					
					}
				}					
			});	
			
			if( typeof me.activeField["login_user_field"] != "undefined" && me.activeField["login_user_field"] == "yes" ){
				wrapper.find( "div.wcff-field-types-meta[data-param=show_for_roles]" ).closest( "tr" ).show(); 
			}
			
			dcontainer.html("");
			/* Render default section */
			/* Default section handling for Check Box */
			if (this.activeField["type"] === "checkbox") {
				if (this.activeField["choices"] != "") {
					/* Prepare default value property */
					default_val = [];
					/* CHeck for this property, until V1.4.0 check box for Admin Fields doesn't has this property */
					if (this.activeField["default_value"]) {
						temp_holder = this.activeField["default_value"];
						/* This is for backward compatibility - <= V 1.4.0 */
						if (Object.prototype.toString.call(temp_holder) !== '[object Array]') {
							/* Since we haven't replaced the default value - as we used before */
							temp_holder = temp_holder.split(";");
							for (i = 0; i < temp_holder.length; i++) {
								keyval = temp_holder[i].trim().split("|");
								if(keyval.length === 2) {
									default_val.push(keyval[0].trim());
								}							
							}
						} else {
							default_val = this.activeField["default_value"];
						}
					}					
					options = this.activeField["choices"].split("\n");
					html = '<ul>';
					for (i = 0; i < options.length; i++) {
						keyval = options[i].split("|");
						if (keyval.length === 2) {
							if (default_val.indexOf(keyval[0]) > -1) {
								html += '<li><input type="checkbox" value="'+ this.unEscapeQuote(keyval[0]) +'" checked /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
							} else {
								html += '<li><input type="checkbox" value="'+ this.unEscapeQuote(keyval[0]) +'" /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
							}							
						}						
					}
					html += '</ul>';
					dcontainer.html(html);
					/* Now inflate the default value for locale */	
					if (me.activeField["locale"]) {
						for(i = 0; i < wcff_var.locales.length; i++) {						
							if (this.activeField["locale"][wcff_var.locales[i]] && 
								this.activeField["locale"][wcff_var.locales[i]]["choices"] &&
								this.activeField["locale"][wcff_var.locales[i]]["choices"] != "") {							
								options = this.activeField["locale"][wcff_var.locales[i]]["choices"].split("\n");
								default_val = (this.activeField["locale"][wcff_var.locales[i]]["default_value"]) ? this.activeField["locale"][wcff_var.locales[i]]["default_value"] : "";
								
								html = '<ul>';
								for (j = 0; j < options.length; j++) {
									keyval = options[j].split("|");
									if (keyval.length === 2) {
										if (default_val.indexOf(keyval[0]) > -1) {
											html += '<li><input type="checkbox" value="'+ this.unEscapeQuote(keyval[0]) +'" checked /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
										} else {
											html += '<li><input type="checkbox" value="'+ this.unEscapeQuote(keyval[0]) +'" /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
										}	
									}
								}
								html += '</ul>';
								wrapper.find(".wcff-default-option-holder-" + wcff_var.locales[i]).html(html);							
							}					
						}
					}
				}		
			}			
			/* Default section handling for Radio Button */
			if (this.activeField["type"] === "radio") {
				if (this.activeField["choices"] != "") {
					/* Prepare default value property */
					default_val = "";
					if (this.activeField["default_value"]) {
						if (this.activeField["default_value"].indexOf("|") != -1) {
							/* This is for backward compatibility - <= V 1.4.0 */
							keyval = this.activeField["default_value"].trim().split("|");
							if (keyval.length === 2) {
								default_val = keyval[0];
							}							
						} else {
							default_val = this.activeField["default_value"].trim();
						}
					}					
					options = this.activeField["choices"].split("\n");
					html = '<ul>';
					for (i = 0; i < options.length; i++) {
						keyval = options[i].split("|");
						if (keyval.length === 2) {
							if (default_val === keyval[0]) {
								html += '<li><input name="wcff-default-choice" type="radio" value="'+ this.unEscapeQuote(keyval[0]) +'" checked /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
							} else {
								html += '<li><input name="wcff-default-choice" type="radio" value="'+ this.unEscapeQuote(keyval[0]) +'" /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
							}							
						}						
					}
					html += '</ul>';
					dcontainer.html(html);					
					/* Now inflate the default value for locale */	
					if (me.activeField["locale"]) {
						for(i = 0; i < wcff_var.locales.length; i++) {						
							if (this.activeField["locale"][wcff_var.locales[i]] && 
								this.activeField["locale"][wcff_var.locales[i]]["choices"] &&
								this.activeField["locale"][wcff_var.locales[i]]["choices"] != "") {
								
								options = this.activeField["locale"][wcff_var.locales[i]]["choices"].split("\n");
								default_val = (this.activeField["locale"][wcff_var.locales[i]]["default_value"]) ? this.activeField["locale"][wcff_var.locales[i]]["default_value"] : "";
								
								html = '<ul>';
								for (j = 0; j < options.length; j++) {
									keyval = options[j].split("|");
									if (keyval.length === 2) {
										if (default_val === keyval[0]) {
											html += '<li><input name="wcff-default-choice-'+ wcff_var.locales[i] +'" type="radio" value="'+ this.unEscapeQuote(keyval[0]) +'" checked /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
										} else {
											html += '<li><input name="wcff-default-choice-'+ wcff_var.locales[i] +'" type="radio" value="'+ this.unEscapeQuote(keyval[0]) +'" /> '+ this.unEscapeQuote(keyval[1]) +'</li>';
										}	
									}
								}
								html += '</ul>';
								wrapper.find(".wcff-default-option-holder-" + wcff_var.locales[i]).html(html);							
							}					
						}
					}
				}
			}		
			/* Default section handling for Select */
			if (this.activeField["type"] === "select") {
				/* Prepare default value property */
				default_val = "";
				if (this.activeField["default_value"]) {
					if (this.activeField["default_value"].indexOf("|") != -1) {
						/* This is for backward compatibility - <= V 1.4.0 */
						keyval = this.activeField["default_value"].trim().split("|");
						if (keyval.length === 2) {
							default_val = keyval[0];
						}							
					} else {
						default_val = this.activeField["default_value"].trim();
					}
				}		
				options = this.activeField["choices"].split("\n");
				html = '<select>';
				html += '<option value="">-- Choose the default Option --</option>';
				for (i = 0; i < options.length; i++) {
					keyval = options[i].split("|");
					if (keyval.length === 2) {
						if (default_val === keyval[0]) {
							html += '<option value="'+ this.unEscapeQuote(keyval[0]) +'" selected>'+ this.unEscapeQuote(keyval[1]) +'</option>';
						} else {
							html += '<option value="'+ this.unEscapeQuote(keyval[0]) +'">'+ this.unEscapeQuote(keyval[1]) +'</option>';
						}					
					}						
				}
				html += '</select>';
				dcontainer.html(html);
				/* Now inflate the default value for locale */	
				if (me.activeField["locale"]) {
					for(i = 0; i < wcff_var.locales.length; i++) {						
						if (this.activeField["locale"][wcff_var.locales[i]] && 
							this.activeField["locale"][wcff_var.locales[i]]["choices"] &&
							this.activeField["locale"][wcff_var.locales[i]]["choices"] != "") {
							
							options = this.activeField["locale"][wcff_var.locales[i]]["choices"].split("\n");
							default_val = (this.activeField["locale"][wcff_var.locales[i]]["default_value"]) ? this.activeField["locale"][wcff_var.locales[i]]["default_value"] : "";
							
							html = '<select>';
							html += '<option value="">-- Choose the default Option --</option>';
							for (j = 0; j < options.length; j++) {
								keyval = options[j].split("|");
								if (keyval.length === 2) {
									if (default_val === keyval[0]) {
										html += '<option value="'+ this.unEscapeQuote(keyval[0]) +'" selected>'+ this.unEscapeQuote(keyval[1]) +'</option>';
									} else {
										html += '<option value="'+ this.unEscapeQuote(keyval[0]) +'">'+ this.unEscapeQuote(keyval[1]) +'</option>';
									}	
								}
							}
							html += '</select>';
							wrapper.find( ".wcff-default-option-holder-" + wcff_var.locales[i]).html(html);							
						}					
					}
				}
			}
			
			/* Show or hide Img width config row - for file field */
			if (this.activeField["type"] === "file") {
				var isPrev = $("input[name=wcff-field-type-meta-img_is_prev]:checked").val();
				if (isPrev && isPrev === "yes") {
					$("div[data-param=img_is_prev_width]").show();
				} else {
					$("div[data-param=img_is_prev_width]").hide();
				}
			}
			
			if (this.activeField["type"] === "datepicker") {
				var isTimePicker = $("input[name=wcff-field-type-meta-timepicker]:checked").val();				
				if (isTimePicker && isTimePicker === "yes") {
					$("div[data-param=min_max_hours_minutes]").closest("tr").css("display", "table-row");
				} else {
					$("div[data-param=min_max_hours_minutes]").closest("tr").css("display", "none");
				}
				/* Set the min max hours & minutes */
				if (this.activeField["min_max_hours_minutes"] && this.activeField["min_max_hours_minutes"] !== "") {
					var min_max = this.activeField["min_max_hours_minutes"].split("|");
					if (min_max instanceof Array) {
						if (min_max.length >= 1) {
							$("#wccpf-datepicker-min-max-hours").val(min_max[0])
						}
						if (min_max.length >= 2) {
							$("#wccpf-datepicker-min-max-minutes").val(min_max[1])
						}
					}										
				}
			}
			
			/* Show the roles selector config, if the field is private */
			var isPrivate = wrapper.find("input[name=wcff-field-type-meta-login_user_field]:checked").val();
			if (isPrivate === "yes") {
				wrapper.find(".div[data-param=show_for_roles]").closest("tr").css("display", "table-row");
			} else {
				wrapper.find(".div[data-param=show_for_roles]").closest("tr").css("display", "none");
			}			
			
			/* Render Pricing & Fee rules */
			if (wcff_var.post_type === "wccpf") {			
				var pricing_rules = this.activeField["pricing_rules"];
				if (Object.prototype.toString.call(pricing_rules) === '[object Array]') {
				    for (i = 0; i < pricing_rules.length; i++) {	
				    	this.renderPricingRow("pricing", pricing_rules[i], wrapper.find(".wcff-add-price-rule-btn").parent().find(".wcff-rule-container"));	
				    }
				    if( pricing_rules.length != 0 ){
				    	wrapper.find(".wcff-add-price-rule-btn").parent().find(".wcff-rule-container-is-empty").hide();
				    }
				}			
				var fee_rules = this.activeField["fee_rules"];
				if( Object.prototype.toString.call( fee_rules ) === '[object Array]' ) {
				    for(i = 0; i < fee_rules.length; i++) {				    	
				    	this.renderPricingRow("fee", fee_rules[i], wrapper.find(".wcff-add-fee-rule-btn").parent().find(".wcff-rule-container"));
				    }	
				    if( fee_rules.length != 0 ){
				    	wrapper.find(".wcff-add-fee-rule-btn").parent().find(".wcff-rule-container-is-empty").hide();
				    }
				}	
				var field_rules = this.activeField["field_rules"];
				if( Object.prototype.toString.call( field_rules ) === '[object Array]' ) {
				    for(i = 0; i < field_rules.length; i++) {				    	
				    	this.renderPricingRow("field", field_rules[i], wrapper.find(".wcff-add-field-rule-btn").parent().find(".wcff-rule-container"));
				    }			
	    			var field_rules_count = wrapper.find(".wcff-tab-rules-wrapper.field .wcff-field-row");
	    			for( var i = 0; i < field_rules_count.length; i++ ){
						for(var j in this.activeField["field_rules"][i]["field_rules"] ){
							$( field_rules_count[i] ).find("a[data-field_label='"+j+"'][data-vfield="+this.activeField["field_rules"][i]["field_rules"][j]+"]").addClass( "selected" );
						}
	    			}
    			   if( field_rules.length != 0 ){
				    	wrapper.find(".wcff-add-field-rule-btn").parent().find(".wcff-rule-container-is-empty").hide();
				   }
				}	
				if( this.activeField["type"]  == "colorpicker" ){
					this.activeField["choices"] = this.activeField["palettes"].replace( /\n/g, "," );
				}
				var colorImage = this.activeField["color_image"];
				if( Object.prototype.toString.call( colorImage ) === '[object Array]' ) {
					for(i = 0; i < colorImage.length; i++) {				    	
				    	this.renderPricingRow("color-image", colorImage[i], wrapper.find(".wcff-add-color-image-rule-btn").parent().find(".wcff-rule-container"));
				    }	
					 if( colorImage.length != 0 ){
					    	wrapper.find(".wcff-add-color-image-rule-btn").parent().find(".wcff-rule-container-is-empty").hide();
					 }
				}
			}
			
			/* Hides the unnecessory config rows - ( only for Admin Fields ) */
			if (wcff_var.post_type === "wccaf") {
				if (this.activeField["show_on_product_page"]) {
					var display = "table-row";
					if (this.activeField["show_on_product_page"] === "no") {
						display = "none";
					} 
					wrapper.find("div.wcff-field-types-meta").each(function () {
						var flaq = false;
						if ($(this).attr("data-param") === "visibility" || 
							$(this).attr("data-param") === "order_meta" ||
							$(this).attr("data-param") === "login_user_field" ||
							$(this).attr("data-param") === "cart_editable" ||
							$(this).attr("data-param") === "cloneable" ||
							$(this).attr("data-param") === "show_as_read_only" ||
							$(this).attr("data-param") === "showin_value" ) {
							flaq = true;
						}					
						if (flaq) {
							$(this).closest("tr").css("display", display);
						}
					});
				} 
			}
			
			
			/* Show pricing tab */
			if ( this.activeField["type"] !== "email" && this.activeField["type"] !== "label" && this.activeField["type"] !== "hidden") {
				_target.find(".wcff-factory-tab-header a[href='.wcff-factory-tab-pricing-rules'], .wcff-factory-tab-header a[href='.wcff-factory-tab-fields-rules']").show();
			} else {
				/* Pricing rules not applicable for the following field type 
				 * 1. File
				 * 2. Email
				 * 3. Hidden
				 * 4. Label */
				_target.find(".wcff-factory-tab-header a[href='.wcff-factory-tab-pricing-rules'], .wcff-factory-tab-header a[href='.wcff-factory-tab-fields-rules']").hide();
			}
			
			if( this.activeField["type"]  == "colorpicker" && this.activeField["show_palette_only"] == "yes" ){
				wrapper.find( ".wcff-factory-tab-header" ).find( "a[href='.wcff-factory-tab-color-image']" ).show();
			}
		
			wrapper.show();
		};
		
		this.onFieldSubmit = function( target ) {
			var i = 0,
			me = this,			
			payload = {},
			open_fields = $( ".wcff-field-config-drawer-closed, .wcff-field-config-drawer-opened" );
			liveLoopField  = $("");
			/**/
			this.fieldsConfig = [];
			/**/
			this.pricingRules = [];
			/**/
			this.feeRules = [];
			/**/
			this.fieldRules = [];
			/* */
			this.colorImage = [];
			/* */
			this.val_error = { message : "", elem : $(""), flg : false };
			
			for( var z = 0; z < open_fields.length; z++ ){
				payload = {};
				liveLoopField = $( open_fields[z] );
				var dcontainer = liveLoopField.find(".wcff-default-option-holder")
				/* Since jQuery some time unrliable (I guess) on retriving values from select box */
				payload.type = liveLoopField.data( "type" );
				var flabel = liveLoopField.find( ".field-label .wcff-field-label input" ).length == 0 ? liveLoopField.find( ".field-label .wcff-field-label" ).text() : liveLoopField.find( ".field-label .wcff-field-label input" ).val();
				var fname = liveLoopField.find( ".field-name .wcff-field-name" ).text().trim();
				payload.key = liveLoopField.attr( "data-key" ).trim() == "" ? undefined : liveLoopField.attr( "data-key" ).trim();
				payload.label = me.escapeQuote( flabel );
				payload.name = me.escapeQuote( fname );
				payload.is_unremovable = liveLoopField.attr( "data-unremovable" ) == "true" ? true : false;
				payload.is_enable = liveLoopField.attr( "data-is_enable" ) == "true" ? true : false;
				/* */
				payload["order"] = liveLoopField.find("input.wcff-field-order-index").val();
				
				if( payload.type == "" || payload.type == null || payload.type == undefined ){
					this.val_error = { message : "Sorry, something went wrong, nothing to worry though, just reload the page and try again.!", elem : liveLoopField, flg : true };
				}
				if( payload.label !== "" ) {
					/* Fetching regular config meta starts here */
					liveLoopField.find( ".wcff-field-types-meta-body div.wcff-field-types-meta").each(function() {		
						if( $(this).attr("data-type") === "checkbox" ) {			
							payload[ $(this).attr("data-param") ] = $(this).find("input.wcff-field-type-meta-"+ $(this).attr("data-param") +":checked").map(function() {
							    return me.escapeQuote(this.value);
							}).get();
						} else if( $(this).attr("data-type") === "radio" ) {
							payload[ $(this).attr("data-param") ] = me.escapeQuote( liveLoopField.find("input[type=radio].wcff-field-type-meta-"+ $(this).attr("data-param") +":checked" ).val() );			
						} else {
							if ($(this).attr("data-type") !== "html") {
								payload[ $(this).attr("data-param") ] = me.escapeQuote( liveLoopField.find("[name=wcff-field-type-meta-"+ $(this).attr("data-param")+"]" ).val() );				
								if( $(this).attr("data-param") === "choices" || $(this).attr("data-param") === "palettes" ) {
									payload[ $(this).attr("data-param") ] = payload[ $(this).attr("data-param") ].replace( /\n/g, ";" );
								}
							}						
						}
					});		
					/* Fetching regular config meta ends here */
					
					/* If it is date picker then */
					if (payload.type === "datepicker") {
						var isTimePicker = liveLoopField.find("input[name=wcff-field-type-meta-timepicker]:checked").val();
						var min_max_hours = "0:23";
						var min_max_minutes = "0:59";
						if (liveLoopField.find(".wccpf-datepicker-min-max-hours").val() != "") {
							min_max_hours = liveLoopField.find(".wccpf-datepicker-min-max-hours").val();
						}
						if (liveLoopField.find(".wccpf-datepicker-min-max-minutes").val() != "") {
							min_max_minutes = liveLoopField.find(".wccpf-datepicker-min-max-minutes").val();
						}
						payload[ "min_max_hours_minutes" ] = min_max_hours + "|" + min_max_minutes;
					}
					
					/* Fetching locale related config meta starts here */
					var resources = {};
					var properties = {};
					for(i = 0; i < wcff_var.locales.length; i++) {
						properties = {};
						liveLoopField.find("div.wcff-locale-block").each(function() {	
							if( $(this).find("[name=wcff-field-type-meta-"+ $(this).attr("data-param") +"-"+ wcff_var.locales[i] + "]" ).length != 0 ){
								properties[$(this).attr("data-param")] = $(this).find("[name=wcff-field-type-meta-"+ $(this).attr("data-param") +"-"+ wcff_var.locales[i] + "]" ).val();
								if ($(this).attr("data-param") === "choices") {
									properties[$(this).attr("data-param")] = properties[$(this).attr("data-param")].replace( /\n/g, ";" );
								}
							}
						});
						resources[wcff_var.locales[i]] = properties;
					}			
					
					/* Fetching default values related config meta starts here */
					if (payload.type === "checkbox") {
						payload["default_value"] = dcontainer.find("input[type=checkbox]:checked").map(function() {
						    return me.escapeQuote(this.value);
						}).get();					
						/* Fetch default value for locale */				
						for(i = 0; i < wcff_var.locales.length; i++) {						
							resources[wcff_var.locales[i]]["default_value"] = liveLoopField.find(".wcff-default-option-holder-" + wcff_var.locales[i]).find("input[type=checkbox]:checked").map(function() {
							    return me.escapeQuote(this.value);
							}).get();						
						}
					} 				
					if (payload.type === "radio") {
						payload["default_value"] = this.escapeQuote(dcontainer.find("input[type=radio]:checked").val());
						/* Fetch default value for locale */					
						for(i = 0; i < wcff_var.locales.length; i++) {						
							resources[wcff_var.locales[i]]["default_value"] = this.escapeQuote(liveLoopField.find(".wcff-default-option-holder-" + wcff_var.locales[i]).find("input[type=radio]:checked").val());						
						}
					}				
					if (payload.type === "select") {
						payload["default_value"] = this.escapeQuote(dcontainer.find("select").val());
						/* Fetch default value for locale */					
						for(i = 0; i < wcff_var.locales.length; i++) {						
							resources[wcff_var.locales[i]]["default_value"] = this.escapeQuote(liveLoopField.find(".wcff-default-option-holder-" + wcff_var.locales[i]).find("select").val());						
						}
					}
					/* Fetching default values related config meta ends here */
					
					/* Put the locale resource on payload object */
					payload["locale"] = resources;				
					
					
					/* Fetch the pricing and fee rules only on PUT mode */
					liveLoopField.find("table.wcff-pricing-row").each(function() {												
						me.commonRulesFetch($(this), "pricing");							
					});					
					liveLoopField.find("table.wcff-fee-row").each(function() {				
						me.commonRulesFetch($(this), "fee");				
					});
					
					liveLoopField.find("table.wcff-field-row").each(function() {				
						me.commonRulesFetch($(this), "field");				
					});
					
					liveLoopField.find("table.wcff-color-image-row").each(function() {				
						me.commonRulesFetch($(this), "color-image");				
					});
					
					if( this.pricingRules.length > 0 ) {
						payload["pricing_rules"] = JSON.parse( JSON.stringify( this.pricingRules ) );
						this.pricingRules = [];
					}
					if( this.feeRules.length > 0 ) {
						payload["fee_rules"] = JSON.parse( JSON.stringify( this.feeRules ) );
						this.feeRules = [];
					}
					if( this.fieldRules.length > 0 ) {
						payload["field_rules"] = JSON.parse( JSON.stringify( this.fieldRules ) );
						this.fieldRules = [];
					}
					if( this.colorImage.length > 0 ){
						payload["color_image"] = JSON.parse( JSON.stringify( this.colorImage ) );
						this.colorImage = [];
					}
					this.fieldsConfig.push( payload );
				} else {
					this.val_error = { message : "Please check all field have endered name.", elem : liveLoopField, flg : true };
				}
			}
			
			/* Double make sure the Type property is there
			 * For some unknown reason it is keeping randomly disappearing */	
			if ( this.val_error.flg ){
				$( "#publish" ).removeClass( "disabled" );
				alert( this.val_error.message );
				return false;
			} else {
				this.postSubmit = true;
			}
			
			var unopen_field = $( "#wcff-fields-set .wcff-meta-row:not(.wcff-field-config-drawer-closed):not(.wcff-field-config-drawer-opened)" ),
				unopen_field_data = {};
			for( var i = 0; i < unopen_field.length; i++ ){
				var nAfield = $( unopen_field[i] );
				unopen_field_data[nAfield.attr( "data-key" )] = { "order" : nAfield.find( ".wcff-field-order-index" ).val(), "is_enable" : ( nAfield.attr( "data-is_enable" ) == "true" ? true : false ) };
			}
			// save to server 
			this.prepareRequest( "POST", "wcff_fields", { "wcff_field_metas" : this.fieldsConfig, "wcff_unopen_details" : unopen_field_data } );
			this.dock( "wcff_fields", target );
		};
		
		this.onPostSubmit = function( _target ) {		
			var location_rules_group = [], 
				condition_rules_group = [];			
			$(".wcff_logic_group").each(function() {
				var rules = [];
				$(this).find("table.wcff_rules_table tr").each(function() {
					rule = {};
					rule["context"] = $(this).find("select.wcff_condition_param").val();
					rule["logic"] = $(this).find("select.wcff_condition_operator").val();
					rule["endpoint"] = $(this).find("select.wcff_condition_value").val();
					rules.push( rule );
				});
				condition_rules_group.push( rules );
			});
			$(".wcff_location_logic_group").each(function() {
				var rules = [];
				$(this).find("table.wcff_location_rules_table tr").each(function() {
					rule = {};
					rule["context"] = $(this).find("select.wcff_location_param").val();
					rule["logic"] = $(this).find("select.wcff_location_operator").val();					
					if( $(this).find("select.wcff_location_param").val() !== "location_product_data" ) {
						rule["endpoint"] = { 
							"context" : $(".wcff_location_metabox_context_value").val(),
							"priority": $(".wcff_location_metabox_priorities_value").val()
						}
					} else {
						rule["endpoint"] = $(this).find("select.wcff_location_product_data_value").val();
					}					
					rules.push( rule );
				});				
				location_rules_group.push( rules );
			});			
			$("#wcff_condition_rules").val( JSON.stringify( condition_rules_group ) );
			if( location_rules_group.length > 0 ) {
				$("#wcff_location_rules").val( JSON.stringify( location_rules_group ) );
			}			
			return true;
		};	
		
		this.commonRulesFetch = function(_current, _type) {
			var rule = {},
				me = this,
				dtype = "",
				pvalue = "",
				logic = "",
				amount = 0,
				ftype = _current.closest( ".wcff-meta-row" ).attr("data-type"),
				ctype = _type == "pricing" ? "price" : _type;
			
			rule["expected_value"] = {};
			rule["amount"] = _current.find("input.wcff-"+ _type +"-rules-amount").val();
			rule["ptype"] = _current.find("div.wcff-"+ _type +"-rule-toggle > a.selected").data("ptype");
			rule["tprice"] = _current.find("div.wcff-"+ _type +"-type-of-"+ ctype +"-toggle > a.selected").data("tprice");
			
			if (_type === "fee") {
				rule["title"] = this.escapeQuote(_current.find("input.wcff-fee-rules-title").val());	
				if (rule["title"] === "" || !rule["title"]) {
					return;
				}
				rule["is_tx"]  = _current.find("div.wcff-fee-type-of-fee-tx-toggle > a.selected").data("is_tx");
			} else if(_type === "pricing") {
				rule["title"] = this.escapeQuote(_current.find("input.wcff-pricing-rules-title").val());	
				if (rule["title"] === "" || !rule["title"]) {
					return;
				}
			} else if(_type === "color-image"){
				rule["prev_image_url"] = _current.find( ".wcff-prev-image" ).attr( "src" );
				rule["image_or_url"] = _current.find( ".wcff-color-image-toggle .selected" ).data( "type" );
				rule["url"] = ( rule["image_or_url"] == "image" ? _current.find( ".wcff-image-url-holder" ).val() : _current.find( ".wcff-product-color-url" ).val() ); 
				if( rule["url"].trim() == "" ||  rule["color"] == "" ){
					this.val_error = { flg : true, message : "Please insert image or url in color image.", elem : _current.find( ".wcff-color-image-toggle .selected" ) };
				}
			} else {
				var rules_for_field = _current.find("div.wcff-"+ _type +"-type-of-"+ ctype +"-toggle > a.selected");
				rule["field_rules"] = {};
				for( var i = 0; i < rules_for_field.length; i++ ){
					rule["field_rules"][ $(rules_for_field[i]).data("field_label")] = $(rules_for_field[i]).data("vfield");
				}
			} 
			
			if (ftype === "datepicker") {				
				dtype = _current.find("ul.wcff-"+ _type +"-date-type-header > li.selected").attr("data-dtype");
				rule["expected_value"]["dtype"] = dtype;
				rule["expected_value"]["value"] = null; 			
				if (dtype === "days") {					
					rule["expected_value"]["value"] = _current.find("input[type=checkbox]:checked").map(function() {
					    return this.value;
					}).get();
				} else if (dtype === "specific-dates") {
					rule["expected_value"]["value"] = _current.find("textarea.wcff-field-type-meta-specific_dates").val();
				} else if (dtype === "weekends-weekdays") {
					rule["expected_value"]["value"] = _current.find(".wcff-field-type-meta-weekend_weekdays:checked").val();
				} else {
					rule["expected_value"]["value"] = _current.find("textarea.wcff-field-type-meta-specific_date_each_months").val();
				}
				
				if (rule["expected_value"]["value"] !== null && rule["amount"] !== "") {
					if (_type === "pricing") {
						this.pricingRules.push(rule);					
					} else if(_type === "fee"){
						this.feeRules.push(rule);
					} else {
						this.fieldRules.push(rule);
					}					
				}						
			} else if(ftype === "select" || ftype === "radio") {
				pvalue = _current.find("select.wcff-"+ _type +"-choice-expected-value").val();
				logic = _current.find("select.wcff-"+ _type +"-choice-condition-value").val();
				
				if( pvalue !== "" && logic !== "" && rule["amount"] !== "" ) {
					rule["expected_value"] = pvalue;
					rule["logic"] = logic;					
					if (_type === "pricing") {
						this.pricingRules.push(rule);					
					} else if(_type === "fee"){
						this.feeRules.push(rule);
					} else {
						this.fieldRules.push(rule);
					}	
				}	
			} else if(ftype === "checkbox") {
				pvalue = [];
				pvalue = _current.find("input[type=checkbox]:checked").map(function() {
				    return this.value;
				}).get();
				logic = _current.find("select.wcff-"+ _type +"-multi-choice-condition-value").val();
				
				if( pvalue.length > 0 && logic !== "" && rule["amount"] !== "" ) {
					rule["expected_value"] = pvalue;
					rule["logic"] = logic;
					if (_type === "pricing") {
						this.pricingRules.push(rule);					
					} else if(_type === "fee"){
						this.feeRules.push(rule);
					} else {
						this.fieldRules.push(rule);
					}	
				}	
			} else {
				pvalue = _current.find("input.wcff-"+ _type +"-input-expected-value").val();
				logic = _current.find("select.wcff-"+ _type +"-input-condition-value").val();
				if( _type === "color-image" ){
					pvalue = _current.find( ".wcff-color-image-select-container input:checked" ).val();
				}
				if( pvalue !== "" && logic !== "" && rule["amount"] !== "" ) {
					rule["expected_value"] = pvalue;
					rule["logic"] = logic;
					if (_type === "pricing") {
						this.pricingRules.push(rule);					
					} else if(_type === "fee"){
						this.feeRules.push(rule);
					} else if(_type === "color-image"){
						this.colorImage.push(rule);
					} else {
						this.fieldRules.push(rule);
					}	
				}	
			}		
		};
		
		this.renderPricingRow = function(_type, _obj, _aBtn) {
			var widget = "";
			if (this.activeField["type"] === "text" || this.activeField["type"] === "number" || 
	    		this.activeField["type"] === "colorpicker" || this.activeField["type"] === "textarea" || this.activeField["type"] === "file") {
					widget = $(this.buildPricingWidgetInput(_type));
					widget.find("select.wcff-"+ _type +"-input-condition-value").val(_obj.logic);
					widget.find("input.wcff-"+ _type +"-input-expected-value").val(this.unEscapeQuote(_obj.expected_value));	
					if(_type == "color-image"){
						widget.find(".wcff-color-image-select-container input[value='"+_obj.expected_value+"']").parent().addClass( "color-active" ).children().prop( "checked", true );
						widget.find( ".wcff-color-image-toggle a" ).removeClass( "selected" );
						widget.find( ".wcff-color-image-toggle a[data-type='"+_obj["image_or_url"]+"']" ).addClass( "selected" );
						if( _obj["image_or_url"] == "image" ){
							widget.find(".wcff-prev-image").attr( "src", _obj["prev_image_url"] );
							widget.find(".wcff-image-url-holder").val( _obj["url"] );
							widget.find(".wcff-upload-custom-img").addClass( "hidden" );
							widget.find(".wcff-delete-custom-img").removeClass( "hidden" );
						} else {
							widget.find(".wcff-image-selector-container" ).hide();
							widget.find(".wcff-url-selector-container" ).show();
							widget.find(".wcff-product-color-url").val( _obj["url"] );
						}
					}
		    	} else if (this.activeField["type"] === "select" || this.activeField["type"] === "radio") {
		    		widget = $(this.buildPricingWidgetChoice(_type));
		    		widget.find("select.wcff-"+ _type +"-choice-condition-value").val(_obj.logic);
		    		widget.find("select.wcff-"+ _type +"-choice-expected-value").val(_obj.expected_value);		
		    	} else if (this.activeField["type"] === "checkbox") {			    		
		    		widget = $(this.buildPricingWidgetMultiChoices(_type));
		    		widget.find("select.wcff-"+ _type +"-multi-choice-condition-value").val(_obj.logic);
		    		if (_obj.expected_value) {
		    			for (var j = 0; j < _obj.expected_value.length; j++) {				    		
			    			widget.find("input[type=checkbox][value='"+ _obj.expected_value[j] +"']").prop('checked', true);
			    		}
		    		}		    		
		    	} else {
		    		/* This must be date picker */				    		
		    		widget = $(this.buildPricingWidgetDatePicker(_type));
		    		widget.find("ul.wcff-"+ _type +"-date-type-header li").removeClass("selected");
		    		var pos = widget.find("ul.wcff-"+ _type +"-date-type-header li[data-dtype='"+ _obj.expected_value.dtype +"']").addClass("selected").index();
		    		widget.find("div.wcff-factory-tab-right-panel > div").hide();
		    		widget.find("div.wcff-factory-tab-right-panel > div:nth-child("+ (pos + 1) +")").show();
		    		if (_obj.expected_value.dtype === "days" && _obj.expected_value && _obj.expected_value.value) {
		    			for (var k = 0; k < _obj.expected_value.value.length; k++) {
		    				widget.find("input[type=checkbox][value='"+ _obj.expected_value.value[k] +"']").prop('checked', true);	
		    			}
		    		} else if (_obj.expected_value.dtype === "specific-dates") {
		    			widget.find("textarea.wcff-field-type-meta-specific_dates").val(_obj.expected_value.value);
		    		} else if (_obj.expected_value.dtype === "weekends-weekdays") {
		    			widget.find("input[type=radio][value='"+ _obj.expected_value.value +"']").prop('checked', true);	
		    		} else {
		    			widget.find("textarea.wcff-field-type-meta-specific_date_each_months").val(_obj.expected_value.value);
		    		}
		    	}
			
			if(_type === "pricing") {
				widget.find("input.wcff-pricing-rules-title").val(this.unEscapeQuote(_obj.title));
			} else if (_type === "fee") {
				widget.find("input.wcff-fee-rules-title").val(this.unEscapeQuote(_obj.title));
				widget.find("div.wcff-fee-type-of-fee-tx-toggle > a[data-is_tx=" + _obj.is_tx +"]").addClass("selected");
			}
			
				widget.find("input.wcff-"+ _type +"-rules-amount").val(_obj.amount);
				widget.find("div.wcff-"+ _type +"-rule-toggle > a").removeClass("selected");
				widget.find("div.wcff-"+ _type +"-rule-toggle > a[data-ptype=" + _obj.ptype +"]").addClass("selected");
				var field = _type == "pricing" ? "price" : _type;
				widget.find("div.wcff-"+ _type +"-type-of-"+ field +"-toggle > a").removeClass("selected");
				widget.find("div.wcff-"+ _type +"-type-of-"+ field +"-toggle > a[data-tprice=" + _obj.tprice +"]").addClass("selected");
				
    			_aBtn.append(widget);	
			
			
		};
		
		this.buildPricingWidgetInput = function(_type, _val) {
			var html = '<table class="wcff-'+ _type +'-row">';			
			html += '<tr>';
			html += '<td class="wcff-'+ _type +'-left-td">';
			
			html += '<table class="wcff-'+ _type +'-table wcff-'+ _type +'-input-table">';
			html += '<tr>';
			html += '<td class="wcff-'+ _type +'-label-td">';
			if (this.activeField["type"] === "number") {
				html += '<label>If user entered number =></label>';
			} else if (this.activeField["type"] === "colorpicker") {
				html += '<label>Select Color =></label>';
			} else {
				html += '<label>If user entered text =></label>';
			}			
			html += '</td>';
			
			/* Condition field section */
			html += '<td class="wcff-'+ _type +'-condition-td">';
			html += '<select class="wcff-'+ _type +'-input-condition-value">';
			if (this.activeField["type"] === "number") {
				html += '<option value="equal">is equal to</option>';
				html += '<option value="not-equal">is not equal to</option>';
				html += '<option value="less-than">less than</option>';
				html += '<option value="less-than-equal">less than or equal to</option>';
				html += '<option value="greater-than">greater than</option>';
				html += '<option value="greater-than-equal">greater than or equal to</option>';
			} else if( this.activeField["type"] !== "file" ) {
				html += '<option value="equal">is equal to</option>';
				html += '<option value="not-equal">is not equal to</option>';
			}			
			html += '<option value="not-null">is not null</option>';
			html += '</select>';
			html += '</td>';
			
			/* Expected value field section */
			html += '<td class="wcff-'+ _type +'-value-td">';
			if (this.activeField["type"] !== "colorpicker") {
				html += '<input type="text" class="wcff-'+ _type +'-input-expected-value" value="" placeholder="Expected Value.?" />';
			} else {
				if( _type == "color-image" ){
					html += '<div class="wcff-color-image-select-container">';
					var choiceOfColors = this.activeField["choices"].split( "," ),
						$hex = '',
						$split = '';
					for( var p = 0; p < choiceOfColors.length; p++ ){
						$split = choiceOfColors[p].trim();
						$hex = $split.length == 4 && $split.length >= 4 ?  '#' + $split[1] + $split[1] + $split[2] + $split[2] + $split[3] + $split[3] : $split;
						html += '<label style="background-color: '+$hex+'; "><input type="radio" value="'+$hex+'"></label>';
					}
					html += '</div>';
				} else {
					html += '<input type="text" class="wcff-'+ _type +'-input-expected-value" value="" placeholder="Expected Color.? (Use comma if more then one color value)" />';
				}
			}			
			html += '</td>';
			html += '</tr>';
			html += '</table>';
			
			/* Bottom table which holds Amount field and Change Mode widget */
			html += this.buildAmountWidget(_type, "input");
			
			html += '</td>';
			/* Pricing rule remove button section starts here */
			html += '<td class="wcff-'+ _type +'-right-td wcff-rule-table-td-remove">';
			html += '<a href="#" class="'+ _type +'-remove-rule wcff-button-remove"></a>';
			html += '</td>';
			/* Pricing rule remove button section ends here */
			html += '</tr>';			
			html += '</table>';
			
			return html;
		};
		
		this.buildPricingWidgetChoice = function(_type, _val) {
			var html = '<table class="wcff-'+ _type +'-row">';			
			html += '<tr>';
			/* Pricing rules section starts here */
			html += '<td class="wcff-'+ _type +'-left-td">';
			
			html += '<table class="wcff-'+ _type +'-table wcff-'+ _type +'-choice-table">';
			html += '<tr>';
			html += '<td class="wcff-'+ _type +'-label-td">';
			html += '<label>If user chosen option =></label>';
			html += '</td>';
			
			/* Condition field section */
			html += '<td class="wcff-'+ _type +'-condition-td">';
			html += '<select class="wcff-'+ _type +'-choice-condition-value">';			
			var isNumber = this.isNumberChoices(this.activeField["choices"]);			
			if (isNumber) {
				html += '<option value="equal">is equal to</option>';
				html += '<option value="not-equal">is not equal to</option>';
				html += '<option value="less-than">less than</option>';
				html += '<option value="less-than-equal">less than or equal to</option>';
				html += '<option value="greater-than">greater than</option>';
				html += '<option value="greater-than-equal">greater than or equal to</option>';
			} else {
				html += '<option value="equal">is equal to</option>';
				html += '<option value="not-equal">is not equal to</option>';
			}			
			html += '</select>';
			html += '</td>';
			
			/* Expected value field section */
			html += '<td class="wcff-'+ _type +'-value-td">';
			html += '<select class="wcff-'+ _type +'-choice-expected-value">';
			var opt = [];
			var choices = this.activeField["choices"].trim().split("\n");
			if (choices) {
				for (var i = 0; i < choices.length; i++) {
					opt = choices[i].split("|");
					html += '<option value="'+ opt[0] +'">'+ opt[1] +'</option>';
				}
			}			
			html += '</select>';
			html += '</td>';
			html += '</tr>';
			html += '</table>';
			
			/* Bottom table which holds Amount field and Change Mode widget */
			html += this.buildAmountWidget(_type, "choice");
			
			html += '</td>';
			/* Pricing rules section ends here */
			
			/* Pricing rule remove button section starts here */
			html += '<td class="wcff-'+ _type +'-right-td wcff-rule-table-td-remove">';
			html += '<a href="#" class="'+ _type +'-remove-rule wcff-button-remove"></a>';
			html += '</td>';
			/* Pricing rule remove button section end here */
			html += '</tr>';			
			html += '</table>';
			
			return html;
		};
		
		this.buildPricingWidgetMultiChoices = function(_type, _val) {
			var html = '<table class="wcff-'+ _type +'-row">';			
			html += '<tr>';
			/* Pricing rules section starts here */
			html += '<td class="wcff-'+ _type +'-left-td">';
			
			html += '<table class="wcff-'+ _type +'-table wcff-'+ _type +'-multi-choice-table">';
			html += '<tr>';
			html += '<td class="wcff-'+ _type +'-label-td">';
			html += '<label>If user chosen option =></label>';
			html += '</td>';
			
			/* Condition field section */
			html += '<td class="wcff-'+ _type +'-condition-td">';
			html += '<select class="wcff-'+ _type +'-multi-choice-condition-value">';		
			html += '<option value="is-only">is only these</option>';
			html += '<option value="is-also">is also these</option>';
			html += '<option value="any-one-of">any of these</option>';	
			html += '</select>';
			html += '</td>';
			
			/* Expected value field section */
			html += '<td class="wcff-'+ _type +'-value-td">';
			html += '<ul class="wcff-'+ _type +'-multi-choices-ul">';
			var opt = [];
			var choices = this.activeField["choices"].trim().split("\n");
			if (choices) {
				for (var i = 0; i < choices.length; i++) {
					opt = choices[i].split("|");
					html += '<li><label><input type="checkbox" name="wcff-'+ _type +'-multi-choice-expected-value" value="'+ opt[0] +'" /> '+ opt[1] +'</label></li>';
				}
			}			
			html += '</ul>';
			html += '</td>';
			html += '</tr>';
			html += '</table>';
			
			/* Bottom table which holds Amount field and Change Mode widget */
			html += this.buildAmountWidget(_type, "multi-choice");
			
			html += '</td>';
			/* Pricing rules section ends here */
			
			/* Pricing rule remove button section starts here */
			html += '<td class="wcff-'+ _type +'-right-td wcff-rule-table-td-remove">';
			html += '<a href="#" class="'+ _type +'-remove-rule wcff-button-remove"></a>';
			html += '</td>';
			/* Pricing rule remove button section end here */
			html += '</tr>';			
			html += '</table>';
			
			return html;
		};
		
		this.buildPricingWidgetDatePicker = function(_type, _val) {
			var html = '<table class="wcff-'+ _type +'-row">';			
			html += '<tr>';
			/* Pricing rules section starts here */
			html += '<td class="wcff-'+ _type +'-left-td">';
			
			html += '<table class="wcff-'+ _type +'-table wcff-'+ _type +'-date-table">';
			html += '<tr>';
			html += '<td class="wcff-'+ _type +'-label-td">';
			html += '<label>If user picked date =></label>';
			html += '</td>';			
			html += '<td class="wcff-'+ _type +'-date-config-td">';
			
			html += '<div class="wcff-factory-tab-container">';
			html += '<div class="wcff-factory-tab-left-panel">';
			html += '<ul class="wcff-'+ _type +'-date-type-header">';
			html += '<li class="selected" data-dtype="days">Days</li>';
			html += '<li data-dtype="specific-dates">Specific Dates</li>';
			html += '<li data-dtype="weekends-weekdays">Weekends Or Weekdays</li>';
			html += '<li data-dtype="specific-dates-each-month">Specific Dates Each Months</li>';
			html += '</ul>';
			html += '</div>';
			html += '<div class="wcff-factory-tab-right-panel">';
			html += '<div class="wcff-factory-tab-content" style="display: block;">';
			html += '<div class="wcff-field-types-meta">';
			html += '<ul class="wcff-field-layout-horizontal">';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="sunday"> Sunday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="monday"> Monday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="tuesday"> Tuesday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="wednesday"> Wednesday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="thursday"> Thursday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="friday"> Friday</label></li>';
			html += '<li><label><input type="checkbox" name="wcff-field-type-meta-'+ _type +'-disable_days[]" value="saturday"> Saturday</label></li>';
			html += '</ul>';
			html += '</div>';
			html += '</div>';
			html += '<div class="wcff-factory-tab-content" style="display: none;">';
			html += '<div class="wcff-field-types-meta">';
			html += '<textarea class="wcff-field-type-meta-specific_dates" placeholder="Format: MM-DD-YYYY Example: 1-22-2017,10-7-2017" rows="2"></textarea>';
			html += '</div>';
			html += '</div>';
			html += '<div class="wcff-factory-tab-content" style="display: none;">';
			html += '<div class="wcff-field-types-meta">';
			html += '<ul class="wcff-field-layout-horizontal">';
			html += '<li><label><input type="radio" class="wcff-field-type-meta-weekend_weekdays" value="weekends"> Week Ends</label></li>';
			html += '<li><label><input type="radio" class="wcff-field-type-meta-weekend_weekdays" value="weekdays"> Week Days</label></li>';
			html += '</ul>';
			html += '</div>';
			html += '<div class="wcff-field-types-meta" data-type="html"><a href="#" class="wcff-date-disable-radio-clear button">Clear</a></div>';
			html += '</div>';
			html += '<div class="wcff-factory-tab-content" style="display: none;">';
			html += '<div class="wcff-field-types-meta">';
			html += '<textarea class="wcff-field-type-meta-specific_date_each_months" placeholder="Example: 5,10,12" rows="2"></textarea>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			
			html += '</td>';			
			html += '</tr>';
			html += '</table>';		
			
			/* Bottom table which holds Amount field and Change Mode widget */
			html += this.buildAmountWidget(_type, "date");
			
			html += '</td>';
			/* Pricing rules section ends here */
			
			/* Pricing rule remove button section starts here */
			html += '<td class="wcff-'+ _type +'-right-td wcff-rule-table-td-remove">';
			html += '<a href="#" class="'+ _type +'-remove-rule wcff-button-remove"></a>';
			html += '</td>';
			/* Pricing rule remove button section end here */
			html += '</tr>';			
			html += '</table>';
			
			return html;
		};
		
		this.buildAmountWidget = function(_type, _ftype) {
			var html = '';
			if (_type === "pricing" || _type === "field") {
				html += '<table class="wcff-pricing-table wcff-pricing-amount-table '+ _ftype +'">';
				html += '<tr>';
				html += '<td class="wcff-'+_type+'-label-td">';
				if( _type === "pricing" ){
					html += '<label>Then change the price to =></label>';
				} else {
					var field_lists = $('#wcff-fields-set .wcff-meta-row:not(.active)').filter(function(){
						if( $(this).find(".wcff_table").length != 0 ) return $( this );
					});
					html += '<label>Choose field rules =></label>';
					html += '</td>';
					html += '<td>';
					html += '<table><tbody>';
					for( var i = 0; i < field_lists.length; i++ ){
						var fieldLabel = $(field_lists[i]).find(".wcff-field-type").text() == "checkbox" ? $(field_lists[i]).find(".wcff-field-name").text().trim()+"[]" :  $(field_lists[i]).find(".wcff-field-name").text().trim();
						html += '<tr>';
						html += '<td>';
						html += '<label>'+ ($(field_lists[i]).find(".wcff-field-label").find( "input" ).length != 0 ? $(field_lists[i]).find(".wcff-field-label").find( "input" ).val() : $(field_lists[i]).find(".wcff-field-label").text() ) +'<label>';
						html += '</td>';
						html += '<td>';
						html += '<div class="wcff-field-type-of-field-toggle wcff-rule-toogle"><a href="#" data-field_label="'+ fieldLabel +'" data-vfield="show" title="Show Field" class="field-show">Show</a><a href="#" data-vfield="hide" data-field_label="'+ fieldLabel +'" title="Hide Field" class="field-hide">Hide</a><a href="#" data-vfield="Nill" data-field_label="'+ fieldLabel +'" title="No rule" class="field-nill-rule selected">Nill</a></div>';
						html += '</td>';
						html += '</tr>';
					}
					html += '</tbody></table>';
				}
				html += '</td>';
				if( _type === "pricing" ){
				html += '<td class="wcff-pricing-title-td">';
				html += '<input type="text" class="wcff-pricing-rules-title" value="" placeholder="Pricing Title" />';
				html += '</td>';
				html += '<td class="wcff-pricing-amount-td">';
				html += '<input type="number" class="wcff-pricing-rules-amount" value="" placeholder="Amount" step="any" />';
				html += '</td>';
			
				} else {
					
				}
				html += '</tr>';
				if( _type === "pricing" ){
					html += '<tr>';
					html += '<td class="wcff-'+_type+'-label-td"><label>Price mode =></label></td>';
					html += '<td class="wcff-pricing-type-of-price-td">';
					html += '<div class="wcff-pricing-type-of-price-toggle wcff-rule-toogle wcff-rule-placeholder-change">';
					html += '<a href="#" data-tprice="cost" title="percentage calculate with product base price - add this amount with product original price" class="price-is-amount selected">Cost</a>';
					html += '<a href="#" data-tprice="percentage" title="percentage calculate with product base price - replace the original product price with this amount" class="price-is-percentage">%</a>';
					html += '</div>';
					html += '</td>';
					html += '<td class="wcff-pricing-mode-td">';
					html += '<div class="wcff-pricing-rule-toggle wcff-rule-toogle wcff-rule-placeholder-change">';
					html += '<a href="#" data-ptype="add" title="add this amount with product original price" class="price-rule-add selected">Add</a>';
					html += '<a href="#" data-ptype="change" title="replace the original product price with this amount" class="price-rule-change">Change</a>';
					html += '</div>';
					html += '</td>';
					html += '</tr>';
				}
				html += '</table>';
			} else if( _type == "color-image" ){
				html += '<table class="wcff-color-image-table wcff-fee-amount-table '+ _ftype +'">';
				html += '<tr>';
				html += '<td class="wcff-color-image-label-td"><label for="post_type">Select Image</label></td>';
				html += '<td class="wcff-color-image-or-url"><div class="wcff-color-image-toggle wcff-rule-toogle wcff-rule-placeholder-change"><a href="#" data-type="image" title="Select Image will change the product image" class="color-image-image selected">Image</a><a href="#" data-type="url" title="Put url it will goto that page" class="color-image-url">Url</a></div>';
				html += '</td>';
				html += '<td class="wcff-color-image-chooser-td">';
				html += '<div class="hide-if-no-js wcff-image-selector-container"><div class=""><img class="wcff-prev-image" src="'+wcff_var.plugin_dir+'/assets/img/placeholder-image.jpg" alt="" style="width:80px;"><input type="hidden" class="wcff-image-url-holder"></div><div class="">'+
						'<a class="wcff-upload-custom-img button"  href="#"> Add </a>'+
				        '<a class="wcff-delete-custom-img hidden button" href="#"> Remove </a> </div></div><div class="wcff-url-selector-container" style="display:none;"><input type="text" class="wcff-product-color-url" placeholder="Paste another product url here"></div>';
				html += '</td>';
				html += '</tr>';
				html += '</table>';
			}else {
				html += '<table class="wcff-fee-table wcff-fee-table wcff-fee-amount-table '+ _ftype +'">';
				html += '<tr>';
				html += '<td class="wcff-fee-label-td">';
				html += '<label>Then add this Fee =></label>';
				html += '</td>';
				html += '<td class="wcff-fee-title-td">';
				html += '<input type="text" class="wcff-fee-rules-title" value="" placeholder="Fee Title" />';
				html += '</td>';
				
				html += '<td class="wcff-fee-amount-td">';
				html += '<input type="number" class="wcff-fee-rules-amount" value="" placeholder="Fee Amount" step="any" />';
				html += '</td>';
				html += '</tr>';	
				
				html += '<tr>';	
				html += '<td class="wcff-fee-label-td">';
				html += '<label>Fee mode =></label>';
				html += '</td>';
				html += '<td class="wcff-fee-type-of-price-td">';
				html += '<div class="wcff-fee-type-of-fee-toggle wcff-rule-toogle">';
				html += '<a href="#" data-tprice="cost" title="percentage calculate with cart tottal - Add this fee for all quantity" class="fee-is-amount selected">Cost</a>';
				html += '<a href="#" data-tprice="percentage" title="percentage calculate with product base price - Add this fee per quantity" class="fee-is-percentage">%</a>';
				html += '</div>';
				html += '</td>';
				html += '<td class="wcff-fee-type-of-price-td">';
				html += '<div class="wcff-fee-type-of-fee-tx-toggle wcff-rule-toogle">';
				html += '<a href="#" data-is_tx="tax" title="Is taxable" class="fee-is-tax">Tax</a>';
				html += '<a href="#" data-is_tx="non_tax" title="Is non-taxable" class="fee-is-non_tax">Non Tax</a>';
				html += '</div>';
				html += '</td>';
				html += '</tr>';	
				
				
				html += '</table>';
			}
			return html;
		};
		
		this.buildFeeWidgetInput = function(_val) {
			var html = '<table class="wcff-pricing-row">';			
			html += '<tr>';
			html += '<td class="wcff-pricing-left-td">';
			
			html += '</td>';
			/* Pricing rules section ends here */
			
			/* Pricing rule remove button section starts here */
			html += '<td class="wcff-pricing-right-td wcff-rule-table-td-remove">';
			html += '<a href="#" class="pricing-remove-rule wcff-button-remove"></a>';
			html += '</td>';
			/* Pricing rule remove button section end here */
			html += '</tr>';			
			html += '</table>';
			
			return html;
		};
		
		this.isNumberChoices = function(_options) {
			var opt = [];
			var flaq = false;
			var choices = _options.split("\n");
			if (choices) {
				flaq = true;
				for (var i = 0; i < choices.length; i++) {
					if (isNaN(choices[i].split("|")[0])) {
						flaq = false;
						break;
					}
				}
			}			
			return flaq;
		};
				
		this.reloadHtml = function( _where ) {
			_where.html( this.response.payload );
		};
		
		/* convert string to url slug */
		this.sanitizeStr = function( str ) {
			if( str ) {
				return str.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'_');
			}
			return str;
		};	 
		
		this.escapeQuote = function( str ) {	
			if( str ) {
				str = str.replace( /'/g, '&#39;' );
				str = str.replace( /"/g, '&#34;' );
			}			
			return str;
		};
		
		this.unEscapeQuote = function( str ) {
			if( str ) {
				str = str.replace( /&#39;/g, "'" );
				str = str.replace( /&#34;/g, '"' );
			}
			return str;
		};
		
		/**
		 * Converts a string to its html characters completely.
		 *
		 * @param {String} str String with unescaped HTML characters
		 **/
		this.encode = function(str) {
			var buf = [];			
			for (var i=str.length-1;i>=0;i--) {
				buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
			}			
			return buf.join('');
		},
		/**
		 * Converts an html characterSet into its original character.
		 *
		 * @param {String} str htmlSet entities
		 **/
		this.decode = function(str) {
			return str.replace(/&#(\d+);/g, function(match, dec) {
				return String.fromCharCode(dec);
			});
		}
		
		/**/
		this.prepareFactoryMeta = function( _target ) {
			var ftype = _target.attr("data-type");
			this.activeField = {};
			this.activeField["type"] = ftype;
			/* Product field related house keeping */
			if (wcff_var.post_type === "wccpf") {				
				if (ftype === "file") {
					_target.find("div[data-param=img_is_prev_width]").hide();
				}
				if ( _target.find(".wcff-factory-multilingual-label-btn").length > 0 ) {
					if (ftype === "hidden" || ftype === "label") {
						_target.find(".wcff-factory-multilingual-label-btn").hide();
					} else {
						_target.find(".wcff-factory-multilingual-label-btn").show();
					}
				}				
			}
			/* Admin field related house keeping */
			if (wcff_var.post_type === "wccaf") {
				_target.find("div.wcff-field-types-meta").each(function () {					
					if ($(this).attr("data-param") === "visibility" || 
						$(this).attr("data-param") === "order_meta" ||
						$(this).attr("data-param") === "login_user_field" ||
						$(this).attr("data-param") === "cart_editable" ||
						$(this).attr("data-param") === "cloneable" ||
						$(this).attr("data-param") === "show_as_read_only" ||
						$(this).attr("data-param") === "showin_value" ) {
						$(this).closest("tr").hide();
					}				
				});
				/* For url field we need to show the cloneable */
				if (ftype === "url") {
					_target.find("div.wcff-field-types-meta").each(function () {	
						if ($(this).attr("data-param") === "login_user_field" || $(this).attr("data-param") === "cloneable") {
							$(this).closest("tr").show();
						}
					});
				}
			}
			
			/* Show pricing tab */
			if ( this.activeField["type"] !== "email" && this.activeField["type"] !== "label" && this.activeField["type"] !== "hidden") {
				_target.find(".wcff-factory-tab-header a[href='.wcff-factory-tab-pricing-rules'], .wcff-factory-tab-header a[href='.wcff-factory-tab-fields-rules']").show();
			} else {
				/* Pricing rules not applicable for the following field type 
				 * 1. File
				 * 2. Email
				 * 3. Hidden
				 * 4. Label */
				_target.find(".wcff-factory-tab-header a[href='.wcff-factory-tab-pricing-rules'], .wcff-factory-tab-header a[href='.wcff-factory-tab-fields-rules']").hide();
			}
		
		};
		
		this.prepareRequest = function( _request, _context, _payload ) {
			this.request = {
				request 	: _request,
				context 	: _context,
				post 		: wcff_var.post_id,
				post_type 	: wcff_var.post_type,
				payload 	: _payload
			};
		};
		
		this.prepareResponse = function( _status, _msg, _data ) {
			this.response = {
				status : _status,
				message : _msg,
				payload : _data
			};
		};
		
		this.dock = function( _action, _target ) {		
			var me = this;
			/* see the ajax handler is free */
			if( !this.ajaxFlaQ ) {
				return;
			}		
			
			$.ajax({  
				type       : "POST",  
				data       : { action : "wcff_ajax", wcff_param : JSON.stringify(this.request)},  
				dataType   : "json",  
				url        : wcff_var.ajaxurl,  
				beforeSend : function(){  				
					/* enable the ajax lock - actually it disable the dock */
					me.ajaxFlaQ = false;				
				},  
				success    : function(data) {				
					/* disable the ajax lock */
					me.ajaxFlaQ = true;				
					me.prepareResponse( data.status, data.message, data.data );		               
	
					/* handle the response and route to appropriate target */
					if( me.response.status ) {
						me.responseHandler( _action, _target );
					} else {
						/* alert the user that some thing went wrong */
						//me.responseHandler( _action, _target );
					}				
				},  
				error      : function(jqXHR, textStatus, errorThrown) {                    
					/* disable the ajax lock */
					me.ajaxFlaQ = true;
				},
				complete   : function() {
					mask.doUnMask();
				} 
			});		
		};
		
		this.responseHandler = function( _action, _target ){		
			if( _action === "product" ||
				_action === "product_cat" ||
				_action === "product_tag" ||
				_action === "product_type" ||
				_action === "product_variation") {
				this.reloadHtml( _target.parent().parent().find("td.condition_value_td") );
			} else if(  _action === "location_product_data" ||
						_action === "location_product" ||
						_action === "location_product_cat" ) {
				this.reloadHtml( _target.parent().parent().find("td.location_value_td") );
			} else if( _action === "wcff_meta_fields" ) {
				this.reloadHtml( _target.find(".wcff-factory-tab-fields-meta .wcff-field-types-meta-body") );
				/* Does some house keeping work for each fields type */
				this.prepareFactoryMeta( _target );
			} else if( _action === "wcff_fields" ) {			
				if( this.request.request === "GET" ) {
					this.activeField = JSON.parse( this.response.payload );				
					if( this.activeField["type"] === $("#wcff-field-type-meta-type").val() ) {
						this.renderSingleView( _target );
					} else {
						if (this.activeField["type"]) {
							this.prepareRequest( "GET", "wcff_meta_fields", { type : this.activeField["type"] } );
							this.dock( "single", _target );
						} else {
							alert("Looks like this field's meta is corrupted, please remove it and re create it.!");
						}						
					}				
				} else {
					if( this.request.request === "DELETE" ) {					
						_target.closest( ".wcff-meta-row" ).remove();
						if( $( "#wcff-fields-set .wcff-meta-row" ).length == 0 ){
							$( "#wcff-empty-field-set" ).show();
						}
						return;
					}
					if( this.response.status ) {
						/* Clear the Active Field property */
						this.activeField = null;
						/* Enable Field Type options */
						$( "#publish" ).removeClass( "disabled" ).trigger( "click" );			
					} else {
						$( "#publish" ).removeClass( "disabled" );
						alert( "Something went wrong please try again." );
					}
				}
			} else if( _action === "single" ) {
				this.reloadHtml( _target.find(".wcff-factory-tab-fields-meta .wcff-field-types-meta-body") );
				this.renderSingleView( _target );
			} 	
		};
	};	
	
	/* Masking object ( used to mask any container whichever being refreshed ) */
	var wcffMask = function() {
		this.top = 0;
		this.left = 0;
		this.bottom = 0;
		this.right = 0;
		
		this.target = null;
		this.mask = null;
		
		this.getPosition = function( target ) {
			this.target = target;		
			
			var position = this.target.position();
			var offset = this.target.offset();
		
			this.top = offset.top;
			this.left = offset.left;
			this.bottom = $( window ).width() - position.left - this.target.width();
			this.right = $( window ).height() - position.right - this.target.height();
		};

		this.doMask = function( target ) {
			this.target = target;
			this.mask = $('<div class="wcff-dock-loader"></div>');						
			this.target.append( this.mask );

			this.mask.css("left", "0px");
			this.mask.css("top", "0px");
			this.mask.css("right", this.target.innerWidth()+"px");
			this.mask.css("bottom", this.target.innerHeight()+"px");
			this.mask.css("width", this.target.innerWidth()+"px");
			this.mask.css("height", this.target.innerHeight()+"px");
		};

		this.doUnMask = function() {
			if( this.mask ) {
				this.mask.remove();
			}			
		};
	};
		
	$(document).ready( function() {
		if( $('#wcff-fields-set').length != 0 ){
			$('#wcff-fields-set').sortable( {
				update : function(){
					var order = wcff_var.post_type == "wcccf" ? 1 : 0;
					$('.wcff-meta-row:not([data-unremovable="true"][data-is_enable="false"])').each(function(){
						if( !$(this).is( "#wcff-add-field-placeholder" ) ){
							$(this).find("input.wcff-field-order-index").val(order);
							$(this).find("span.wcff-field-order-number").text((wcff_var.post_type == "wcccf" ? order: (order+1) ));
							order++;
						}
					});
				},
				cancel : ".active, #wcff-add-field-placeholder, .wcff-field-config-drawer-opened, .wcff-field-delete, .wcff-meta-option"
			});	
		}
		
			
		// for field drag and drop start
		var currenctDraggedField = "";
		var currenctDropField = "";
		function dragStart(e){
			 e.stopPropagation();
			 var fieldObj = this;
			 fieldObj.className = fieldObj.className + " is-dragged";
			 currenctDraggedField = e.target;
		}

		function dragEnd(e){
			 e.stopPropagation();
			 var fieldObj = this;
			 fieldObj.className = fieldObj.className.replace('is-dragged',''); 
			 currenctDraggedField = "";
			 $( currenctDropField ).removeClass( "dropover" );
			 currenctDropField = "";
		}

		function dragEnter(e){
			  e.stopPropagation();
			  this.className = this.className + " dropover";
		}

		function dragLeave(e){
			  e.stopPropagation();
			  this.className = this.className.replace('dropover','');
		}

		function dragOver(e){
			  e.stopPropagation();
			  currenctDropField = e.target;
			  e.preventDefault();
			  return false; 
		}

		function drop(e){
			 if (e.stopPropagation) e.stopPropagation();
			 var $new_field_config = $("#wcff_factory .inside").html();
			 var newField = $( "#wcff-add-field-placeholder" ).after( '<div class="wcff-meta-row active wcff-field-config-drawer-opened" data-key="" data-type="'+$( currenctDraggedField ).attr( "value" ).trim()+'">'+$new_field_config+'</div>' );
			 newField = newField.next();
			 newField.find( ".wcff-field-type" ).text( $( currenctDraggedField ).attr( "value" ).trim() );
			 newField.find( ".wcff-field-type" ).trigger( "change" );
			 ////
			 var order = wcff_var.post_type == "wcccf" ? 1 : 0;
				$('.wcff-meta-row').each(function(){
					if( !$(this).is( "#wcff-add-field-placeholder" ) ){
						$(this).find("input.wcff-field-order-index").val(order);
						$(this).find("span.wcff-field-order-number").text((wcff_var.post_type == "wcccf" ? order: (order+1) ));
						order++;
					}
			});
			if( $('.wcff-meta-row').length != 0  ){
				$( "#wcff-empty-field-set" ).hide();
			}
			return false;
		}

		  var fields = document.querySelectorAll('.wcff-drag-fields'),
		      dropArea = document.getElementById('wcff-add-field-placeholder');
		  
		  for( var i=0,len=fields.length; i<len; i++ ){
			  fields[i].addEventListener('dragstart', dragStart, false);
			  fields[i].addEventListener('dragend', dragEnd, false);
		  }
		  if( dropArea != null ){
			  dropArea.addEventListener('dragenter', dragEnter, false);
			  dropArea.addEventListener('dragleave', dragLeave, false);
			  dropArea.addEventListener('dragover', dragOver, false);
			  dropArea.addEventListener('drop', drop, false);
		  }
	
		// for field drag and drop end
		  
		  
		  $(document).on( "dragover", ".wcff-meta-row", function(e){
			  if( currenctDraggedField != "" ){
				  if(  $( e.currentTarget ).is( ":first-child" ) &&  $( e.currentTarget ).outerHeight() / 2 + e.clientY > $( e.currentTarget ).offset().top ){
					  $( e.currentTarget ).before( dropArea );
				  } else {
					  $( e.currentTarget ).after( dropArea );
				  }
			  }
		  });
		
	});
	
	mask = new wcffMask();
	
	var wcffObj = new wcff();
	wcffObj.initialize();
	
})(jQuery);