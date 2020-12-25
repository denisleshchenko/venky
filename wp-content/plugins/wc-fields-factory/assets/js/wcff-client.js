(function($) {	
	
	var wcff_cart_handler = function() {
		/* to prevetn Ajax conflict. */
		this.ajaxFlaQ = true;
		
		this.initialize = function() {
			this.registerEvent();
		};
		
		/* self object */
		var self = this;
		
		this.registerEvent = function() {
			/* Double clikc handler for vcart field - which will show the editor window for that field */
			$(document).on("dblclick", "li.wcff_cart_editor_field", this, function(e) {	
				if ($("div.wccpf-cart-edit-wrapper").length > 0) {
					/* Do nothing since already one field is in edit mode */
					return;
				}
				var target = $(this);	
				target.closest("ul.wccpf-is-editable-yes").removeClass("wccpf-is-editable-yes");
				if(  !target.find( "input, select, textarea, label" ).length != 0 && target.is( ".wcff_cart_editor_field" ) ){					
					e.data.getFieldForEdit(target);
				}				
			});					
			/* Click event hanlder cart field Update button */
			$(document).on( "click", ".wccpf-update-cart-field-btn", this, function(e) {
				e.data.updateField( $( this ) );
				e.preventDefault();
			});	
			/* Click event hanlder for Cart Editor close button */
			$(document).on("click", "#wccpf-cart-editor-close-btn", function(e) {
				var editor = $(this).parent();
				editor.closest("ul.wccpf-cart-editor-ul").addClass("wccpf-is-editable-yes");
				editor.prev().show();
				editor.remove();
				e.preventDefault();
			});
			
			/* Key down event handler - for ESC key 
			 * If pressed the editor window will be closed */
			$(window).on("keydown", function(e) {
				var keyCode = (event.keyCode ? event.keyCode : event.which);   
				var editor = $("div.wccpf-cart-edit-wrapper");
				if (keyCode === 27 && editor.length > 0) {
					editor.closest("ul.wccpf-cart-editor-ul").addClass("wccpf-is-editable-yes");
					editor.prev().show();
					editor.remove();
				}
			});
			
			$(document).on("change", "[data-is_pricing_rules=yes]", function(e) {
				self.update_negotiate_price( $(this) );
			});
			// on load pring negotiation
			setTimeout(function(){
				$( '[data-has_field_rules="yes"]' ).trigger( "change" );
				if( wccpf_opt["is_page"] != "archive" ){
					self.update_negotiate_price();
				}
			}, 180 );
			
			
			
			
			$(document).on("change", "input[name=variation_id]", function(){
				var variation_id = $("input[name=variation_id]").val();
				if( variation_id.trim() != "" ){
					self.get_variation_product_fields( $("input[name=variation_id]").val() );
				} else {
					$( ".wcff-variation-field" ).html( "" );
					self.update_negotiate_price( $(this) );
				}
			});
			
		};
		
		this.getFieldForEdit = function(_target) {					
			/* Retrieve the value (for color picker it is different, if store admin chosen to display as color itself) */
			var fieldValue = (_target.find(".wcff-color-picker-color-show").length != 0) ? _target.find(".wcff-color-picker-color-show").css("background-color") : $.trim(_target.find("p").text());			
			var payload = { 
				product_id: _target.attr("data-productid"), 
				product_cart_id: _target.attr("data-itemkey"), 
				data: { 
					value: fieldValue,
					field: _target.attr("data-field"),
					name: _target.attr("data-fieldname")					 
				} 
			};
			this.prepareRequest("wcff_render_field_on_cart_edit", "GET", payload);
			this.dock("inflate_field_for_edit", _target);
		};
		
		this.update_negotiate_price = function( _target ){
			var dataObj = wccpf_opt["is_page"] == "archive" ? _target.closest( "li.product" ).find( "[data-is_pricing_rules=yes]" )  : $( "[data-is_pricing_rules=yes]" ),
			currentField = $( "" ),
			prod_id = wccpf_opt["is_page"] == "archive" ? _target.closest( "li.product" ).find( "a.add_to_cart_button" ).attr( "data-product_id" ) : $( "input[name=add-to-cart]" ).length != 0 ? $( "input[name=add-to-cart]" ).val() :  $( "button[name=add-to-cart]" ).val(),
			data	= { "_product_id" : prod_id, "_variation_id" : $( "input[name=variation_id]" ).val(), "_fields_data" : [] },
			is_field_cloneable = "no",
			is_globe_cloneable	= wccpf_opt.cloning == "yes" ? "yes" : "no",
			variation_not_null =  $( "input[name=variation_id]" ).length != 0 && (  $( "input[name=variation_id]" ).val().trim() == "" || $( "input[name=variation_id]" ).val().trim() == "0" ) ? false : true;
			if( variation_not_null ){
				for( var i = 0; i < dataObj.length; i++ ){
					currentField = $( dataObj[i] );
					if( currentField.is(":visible") || ( currentField.is(".wccpf-color") && currentField.closest("table").is(":visible") && !currentField.closest("table").is( ".wcff_is_hidden_from_field_rule" ) ) ){
						is_field_cloneable = is_globe_cloneable == "yes" ? currentField.is("[type=radio]") || currentField.is("[type=checkbox]") ? currentField.closest("ul").data( "cloneable" ) : currentField.data( "cloneable" ) : is_globe_cloneable;
						var field_name  = currentField.is("[type=checkbox]") ? currentField.attr( "name" ).replace("[", "").replace("]", "") : currentField.attr( "name" ),
							field_value = currentField.is("[type=checkbox]") ? currentField.prop("checked") ? [currentField.val()] : [] : currentField.is("[type=radio]") ? currentField.is(":checked") ? currentField.val() : "" : currentField.val();
						data._fields_data.push( { "is_clonable" : is_field_cloneable, "name" : field_name, "value" : field_value } );
					}
				}
				self.prepareRequest( "wcff_ajax_get_negotiated_price", "GET", data );
				self.dock("wcff_ajax_get_negotiated_price", _target);
			}
		}
		
		this.get_variation_product_fields = function(variation_id){
			self.prepareRequest( "wcff_variation_fields", "GET", {"variation_id" : variation_id} );
			self.dock("wcff_variation_fields");
		};
		
		this.updateField = function(_btn) {
			var payload,
			fvalue = null,
			validator = new wcffValidator(),
			field_key = _btn.closest( "div.wccpf-cart-edit-wrapper" ).attr( "data-field" ),
			field_name = _btn.closest( "div.wccpf-cart-edit-wrapper" ).attr( "data-field_name" ),
			field_type = _btn.closest( "div.wccpf-cart-edit-wrapper" ).attr( "data-field_type" ),
			productId = _btn.closest( "div.wccpf-cart-edit-wrapper" ).attr( "data-product_id" ),
			cartItemKey = _btn.closest( "div.wccpf-cart-edit-wrapper" ).attr( "data-item_key" );		
			
			if (field_type === "radio") {
				validator.doValidate( _btn.closest( "div.wccpf-cart-edit-wrapper" ).find( "input" ) );				
				fvalue = _btn.closest( "div.wccpf-cart-edit-wrapper" ).find( "input:checked" ).val();								
			} else if (field_type === "checkbox") {
				validator.doValidate( _btn.closest( "div.wccpf-cart-edit-wrapper" ).find( "input" ) );
				fvalue = _btn.closest( "div.wccpf-cart-edit-wrapper" ).find("input:checked").map(function() {
				    return this.value;
				}).get();
			} else {				
				validator.doValidate( _btn.closest( "div.wccpf-cart-edit-wrapper" ).find( ".wccpf-field" ) );
				fvalue = _btn.closest( "div.wccpf-cart-edit-wrapper" ).find( ".wccpf-field" ).val();
			}			
			
			if (validator.isValid) {
				/* Initiate the ajax Request */
				payload = { 
					product_id : productId, 
					cart_item_key : cartItemKey,
					data : { 
						field: field_key, 
						name: field_name, 
						value: fvalue, 
						field_type : field_type
					}
				}
				this.prepareRequest( "wcff_update_cart_field_data", "PUT", payload );
				this.dock( "update_cart_field_data", _btn );
			}		
		};
		
		
		
		/* Request object for all the wcff cart related Ajax operation */
		this.prepareRequest = function(_request, _method, _data) {
			this.request = {
				request 	: _method,
				context 	: _request,
				post 		: "",
				post_type 	: "wccpf",
				payload 	: _data,
			};
		};
		
		/* Ajax response wrapper object */
		this.prepareResponse = function(_status, _msg, _data) {
			this.response = {
				status : _status,
				message : _msg,
				payload : _data
			};
		};
		
		this.dock = function(_action, _target, is_file) {		
			var me = this;
			/* see the ajax handler is free */
			if( !this.ajaxFlaQ ) {
				return;
			}
			$.ajax({  
				type       : "POST",  
				data       : { action : "wcff_ajax", wcff_param : JSON.stringify( this.request ) },  
				dataType   : "json",  
				url        : woocommerce_params.ajax_url,  
				beforeSend : function(){  				
					/* enable the ajax lock - actually it disable the dock */
					me.ajaxFlaQ = false;	
					me.beforeAjaxSend( _action );
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
					
				}   
			});		
		};
		
		/* Before ajax send callback */
		this.beforeAjaxSend = function( _action ){
			if( _action == "wcff_ajax_get_negotiated_price" ){
				$( ".woocommerce-variation-add-to-cart .button, button[name=add-to-cart]" ).addClass( "disabled" );
			}			
		};
		
		this.responseHandler = function(_action, _target) {
			
			if (!this.response.status) {
				/* Something went wrong - Do nothing */
				return;
			}			
			
			if (_action === "inflate_field_for_edit" && this.response.payload) {
				var wrapper = '';
				/* Get the reference of head tag, we might need to inject some script tag there
				 * incase if the data being edited is either datepicker or color picker */
				var dHeader = $("head");
				/* Find the last td of the field wrapper to add update button */
				var editFieldHtml = $(this.response.payload.html).find("td:last");
				/* Construct update button */
				var updateBtn = '<button data-color_show="'+ this.response.payload.color_showin +'" class="button wccpf-update-cart-field-btn">Update</button>';
				
				if (this.response.payload.field_type !== "file") {		
					wrapper = '<div class="wccpf-cart-edit-wrapper wccpf-cart-edit-'+ this.response.payload.field_type +'-wrapper" data-field_type="'+ this.response.payload.field_type +'" data-field="'+ _target.attr("data-field") +'" data-field_name="'+ _target.attr("data-fieldname") +'" data-product_id="'+ _target.attr("data-productid") +'" data-item_key="'+ _target.attr("data-itemkey") +'">';
					wrapper += '<a href="#" id="wccpf-cart-editor-close-btn" title="Close Editor"></a>';
					wrapper += (editFieldHtml.html() + updateBtn);
					wrapper += '<div>';
					wrapper = $(wrapper);
					_target.hide();
					_target.parent().append(wrapper);
				}				
				if( this.response.payload.field_type == "email" || this.response.payload.field_type == "text" || this.response.payload.field_type == "number" || this.response.payload.field_type == "textarea" ){
					//_target.parent().find( ".wccpf-field" ).val( this.request.payload.data.value );
					wrapper.find("input").trigger( "focus" );
				} else if( this.response.payload.field_type == "colorpicker" ){
					dHeader.append( this.response.payload.script );
				} else if( this.response.payload.field_type == "datepicker" ){
					_target.parent().find( ".wccpf-field" ).val( this.request.payload.data.value );
					if( dHeader.find( "script[data-type=wpff-datepicker-script]" ).length == 0 ){
						dHeader.append( this.response.payload.script );
					}
					dHeader.append( $( this.response.payload.html )[2] );
				}
			} else if( _action == "update_cart_field_data" ){
				if( this.response.payload.status ) {
					if (this.response.payload.field_type !== "colorpicker") {							
						_target.closest( "div.wccpf-cart-edit-wrapper" ).parent().find("li.wcff_cart_editor_field").show().html( '<p>'+ decodeURI( this.response.payload.value ) +'</p>' );
					} else {
						if (_target.closest( "div.wccpf-cart-edit-wrapper" ).parent().find("li.wcff_cart_editor_field").attr("data-color-box") === "yes") {
							_target.closest( "div.wccpf-cart-edit-wrapper" ).parent().find("li.wcff_cart_editor_field").show().html( '<p><span class="wcff-color-picker-color-show" style="background: '+ decodeURI( this.response.payload.value ) + ';"></span></p>' );
						} else {
							_target.closest( "div.wccpf-cart-edit-wrapper" ).parent().find("li.wcff_cart_editor_field").show().html( '<p>'+ decodeURI( this.response.payload.value ) +'</p>' );
						}
					}					
					_target.closest( "ul.wccpf-cart-editor-ul" ).addClass("wccpf-is-editable-yes");
					_target.closest( "div.wccpf-cart-edit-wrapper" ).remove();
				} else {
					_target.prev().html( this.response.payload.message ).show();
				}
			} else if( _action == "wcff_ajax_get_negotiated_price" ){
				var parent = typeof _target == "undefined" ? $( "div.product" ) : wccpf_opt.is_page == "single" ? _target.closest( "div.product" ) :  _target.closest( "li.product" );
				if( this.response.payload.status ) {
					var wcff_p_title_container = parent.find( ".wcff_pricing_rules_title_container" ),
						p_title_html = "";
					if( wccpf_opt.ajax_pricing_rules_title.trim() == "show" && this.response.payload.data["data_title"].length != 0 ){
						p_title_html += '<h4 class="wcff_pricing_rules_title_container">'+wccpf_opt.ajax_pricing_rules_title_header.trim()+'</h4>';
					}
					p_title_html += "<table><tbody>";
					for( var i = 0; i < this.response.payload.data["data_title"].length; i++ ){
						p_title_html += "<tr><td>"+this.response.payload.data["data_title"][i]["title"]+"</td><td>"+this.response.payload.data["data_title"][i]["amount"]+"</td></tr>";
					}
					p_title_html += "</table></tbody>";
					if( wccpf_opt.price_details ){
						if( wcff_p_title_container.length != 0 ){
							wcff_p_title_container.html( p_title_html );
							// If negotiate price are empty - remove price titles 
							if( this.response.payload.data.data_title.length == 0 ){
								$( ".wcff_pricing_rules_title_container" ).hide();
							} else {
								$( ".wcff_pricing_rules_title_container" ).show();
							}
						} else {
							parent.find( ".wccpf_fields_table:last" ).parent().after( '<div class="wcff_pricing_rules_title_container">'+p_title_html+'</div>' );
						}
					}
					if( wccpf_opt["is_page"] == "archive" ){
						parent.find( "span.price span.amount:last" ).replaceWith( this.response.payload.data["amount"] );
					} else {
						if( wccpf_opt.ajax_pricing_rules_price_container_is == "default" || wccpf_opt.ajax_pricing_rules_price_container_is == "both" ){
							if( $( ".summary.entry-summary .woocommerce-variation-price:visible" ).length != 0 ){
								$( ".summary.entry-summary .woocommerce-variation-price" ).html( this.response.payload.data["amount"] )
							} else {
								$( ".summary.entry-summary .price .woocommerce-Price-amount" ).replaceWith( this.response.payload.data["amount"] );
							}
							if( wccpf_opt.ajax_pricing_rules_price_container_is == "both" ){
								$( wccpf_opt.ajax_price_replace_container ).html( this.response.payload.data["amount"] );
							}
						} else {
							$( wccpf_opt.ajax_price_replace_container ).html( this.response.payload.data["amount"] );
						}
					}
				} else {
					
				}
				$( ".woocommerce-variation-add-to-cart .button, button[name=add-to-cart]" ).removeClass( "disabled" );
			} else if( _action == "wcff_variation_fields" ){
				var variation_container = $( ".wcff-variation-field" );
				variation_container.html( "" );
				if( variation_container.length != 0 ){
					var variation_fields = this.response.payload.data;
					for( var i = 0; i < variation_fields.length; i++ ){
						if( variation_fields[i]["location"] == "color_picker_scripts" ){
							$( "body" ).append(variation_fields[i]["html"]);
						} else {
							$( ".wcff-variation-field[data-area='"+variation_fields[i]["location"]+"']" ).append(variation_fields[i]["html"]);
						}
					}
				} 
				var variation_container = $( ".wcff-variation-cloning-field-container" );
				for( var i = 0; i < variation_container.length; i++ ){
					var container = $( variation_container[i] );
					if( container.find( ".wcff-variation-field" ).children().length == 0 ){
						container.hide();
					} else {
						container.show();
					}
				}
				// trigger init field rule
				$( '[data-has_field_rules="yes"]' ).trigger( "change" );
				
				self.update_negotiate_price();
			}
		};
		
	};
	
	$(document).on( "submit", "form.cart", function() {				
		if( typeof( wccpf_opt.location ) !== "undefined" /*&& 
				wccpf_opt.location !== "woocommerce_before_add_to_cart_button" && 
				wccpf_opt.location !== "woocommerce_after_add_to_cart_button"*/ ) {			
			var me = $(this);		
			$(".wccpf_fields_table").each(function(){
				if( $(this).closest( "form.cart" ).length == 0 ){
					var cloned = $(this).clone( true );
					cloned.css("display", "none");
					
					/* Since selected flaq doesn't carry over by Clone method, we have to do it manually */
					/* carry all field value to server */
					if ($(this).find( ".wccpf-field " ).attr("wccpf-type") === "select") {
						cloned.find( "select.wccpf-field" ).val( $(this).find( "select.wccpf-field" ).val() );
					}
					me.append( cloned );
				}
			});
			/* me.find(".wccpf_fields_table").each(function(){
				$(this).remove();
			});	*/
		}
		// To remove hidden field table
		$(".wcff_is_hidden_from_field_rule").remove();
	});
	
	
	
	var wcffCloner = function() {
		this.initialize = function() {
			$( document ).on( "change", "input[name=quantity]", function() {
				var product_count = $(this).val();
				var fields_count = parseInt( $("#wccpf_fields_clone_count").val() );
				$("#wccpf_fields_clone_count").val( product_count );
				
				if( fields_count < product_count ) {
					for( var i = fields_count + 1; i <= product_count; i++ ) {
						var groups = $( ".wccpf-fields-container" );
						for( var j = 0; j < groups.length; j++ ){
							var group = $( groups[j] );
							var cloned = group.find('.wccpf-fields-group:first').clone( true );
							cloned.find("script").remove();				
							cloned.find("div.sp-replacer").remove();
							cloned.find("span.wccpf-fields-group-title-index").html( i );
							cloned.find(".hasDatepicker").attr( "id", "" );
							cloned.find(".hasDatepicker").removeClass( "hasDatepicker" );						
							cloned.find(".wccpf-field").each(function(){
								var cloneable = $(this).attr('data-cloneable');
								if ($(this).attr( "wccpf-type" ) === "checkbox" || $(this).attr( "wccpf-type" ) === "radio") {
									cloneable = $(this).closest("ul").attr('data-cloneable');
								}
								/* Check if the field is allowed to clone */
								if (typeof cloneable !== typeof undefined && cloneable !== false) {
									var name_attr = $(this).attr("name");					
									if( name_attr.indexOf("[]") != -1 ) {
										var temp_name = name_attr.substring( 0, name_attr.lastIndexOf("_") );							
										name_attr = temp_name + "_" + i + "[]";						
									} else {
										name_attr = name_attr.slice( 0, -1 ) + i;
									}
									$(this).attr( "name", name_attr );
								} else {
									/* Otherwise remove from cloned */								
									$(this).closest("table.wccpf_fields_table").remove();																
								}			 				
							});
							/* Check for the label field - since label is using different class */
							cloned.find(".wcff-label").each(function() {
								var cloneable = $(this).attr('data-cloneable');	
								var label_name_attr = $(this).find("input").attr( "name" ).slice( 0, -1 ) + i;
								$(this).find("input").attr( "name", label_name_attr );
								if (typeof cloneable === typeof undefined || cloneable === false) {
									$(this).remove();
								}
							});
							/* Append the cloned fields to the DOM */
							group.append( cloned );		
							/* Trigger the color picker init function */
							setTimeout( function(){ 
								if( typeof( wccpf_init_color_pickers ) == 'function' ) { wccpf_init_color_pickers(); }
								if( typeof( wccpf_init_color_pickers_variation ) == 'function' ) { wccpf_init_color_pickers_variation(); }
								var color_fields = $( ".wccpf-color" );
								for( var x = 0; x <= wccpf_opt.color_picker_functions.length; x++  ){
									if( typeof window[ wccpf_opt.color_picker_functions[x] ] == 'function' ) { window[ wccpf_opt.color_picker_functions[x] ](); }
									if( typeof window[ wccpf_opt.color_picker_functions[x] ] == 'function' ) { window[ wccpf_opt.color_picker_functions[x] ](); }
								}
								group.find( '[data-has_field_rules="yes"]' ).trigger( "change" );
							}, 500 );
						}
					}
				} else {					
					$("div.wccpf-fields-group:eq("+ ( product_count - 1 ) +")").nextAll().remove();
				}
				
				if( $(this).val() == 1 ) {
		            $(".wccpf-fields-group-title-index").hide();
		        } else {
		            $(".wccpf-fields-group-title-index").show();
		        }
				
				var variation_container = $( ".wcff-variation-cloning-field-container" );
				for( var i = 0; i < variation_container.length; i++ ){
					var container = $( variation_container[i] );
					if( container.find( ".wcff-variation-field" ).children().length == 0 ){
						container.hide();
					} else {
						container.show();
					}
				}
				
			});			
			/* Trigger to change event - fix for min product quantity */
			setTimeout( function(){ $( "input[name=quantity]" ).trigger("change"); }, 300 );
		};
	};
	
	var wcffValidator = function() {		
		this.isValid = true;		
		this.initialize = function(){						
			$( document ).on( "submit", "form.cart", this, function(e) {
				var me = e.data; 
				e.data.isValid = true;				
				$( ".wccpf-field" ).each(function() {
					if ($(this).attr("wccpf-mandatory") === "yes") {
						me.doValidate( $(this) );
					}					
				});					
				return e.data.isValid;
			});
			if( wccpf_opt.validation_type === "blur" ) {
				$( document ).on( "blur", ".wccpf-field", this, function(e) {	
					if ($(this).attr("wccpf-mandatory") === "yes") {
						e.data.doValidate( $(this) );
					}
				});
			}
		};
		
		this.doValidate = function( field ) {			
			if( field.attr("wccpf-type") !== "radio" && field.attr("wccpf-type") !== "checkbox" && field.attr("wccpf-type") !== "file" ) {
				if(field.attr("wccpf-type") !== "select") {
					if( this.doPatterns( field.attr("wccpf-pattern"), field.val() ) ) {						
						field.nextAll( ".wccpf-validation-message" ).hide();
					} else {						
						this.isValid = false;
						field.nextAll( ".wccpf-validation-message" ).show();
					}
				} else {
					if (field.val() !== "" && field.val() !== "wccpf_none") {
						field.nextAll( ".wccpf-validation-message" ).hide();
					} else {
						this.isValid = false;
						field.nextAll( ".wccpf-validation-message" ).show();
					}
				}							
			} else if( field.attr("wccpf-type") === "radio" ) {				
				if( field.closest("ul").find("input[type=radio]").is(':checked') ) {
					field.closest("ul").next().hide();
				} else {
					field.closest("ul").next().show();
					this.isValid = false;					
				}	 			
			} else if( field.attr("wccpf-type") === "checkbox" ) {			
				var values = field.closest("ul").find("input[type=checkbox]:checked").map(function() {
				    return this.value;
				}).get();
				if( values.length === 0 ) {
					field.closest("ul").next().show();
					this.isValid = false;
				} else {						
					field.closest("ul").next().hide();
				}			
			} else if( field.attr("wccpf-type") === "file" ) {		
				if( field.val() == "" ) {
					field.next().show();
					this.isValid = false;
				} else {
					field.next().hide();
				}									
			}
		}
		
		this.doPatterns = function( patt, val ){
			var pattern = {
				mandatory	: /\S/, 
				number		: /^-?\d+\.?\d*$/,
				email		: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,	      	
			};			    
		    return pattern[ patt ].test(val);	
		};
		
	};
	
	$(document).ready(function() {		

		$(document).on( "change", ".wccpf-field", function( e ) {
			var target = $( this ),
				prevExt = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];

			if(target.is( "input[type=file]" ) && target.attr("data-preview") === "yes") {
				if ( $.inArray( target.val().split('.').pop().toLowerCase(), prevExt ) !== -1 ) {
			        if( !target.next().is( ".wcff_image_prev_shop_continer" ) ) {
			        	   	target.after( '<div class="wcff_image_prev_shop_continer" style="width:'+ target.attr("data-preview-width") +'"></div>' );
			        }		          
		        	    var html = "";
		        	    for( var i = 0; i < target[0].files.length; i++ ) {
		        		   html += '<img class="wcff-prev-shop-image" src="'+ URL.createObjectURL( target[0].files[i] ) +'">';
		        		   target[0].files[i].name = target[0].files[i].name.replace(/'|$|,/g, '');
		        		   target[0].files[i].name = target[0].files[i].name.replace('$', '');
		        	    }
		        	    target.next( ".wcff_image_prev_shop_continer" ).html( html );			           
			    }
			}
		});		
		
		if( typeof wccpf_opt != "undefined" ){
			if (typeof(wccpf_opt.cloning) !== "undefined" && wccpf_opt.cloning === "yes") {
				var wcff_cloner_obj = new wcffCloner();
				wcff_cloner_obj.initialize();
			}
			if (typeof(wccpf_opt.validation) !== "undefined" && wccpf_opt.validation === "yes") {			
				var wcff_validator_obj = new wcffValidator();
				wcff_validator_obj.initialize();
			}
			if ( $( ".single-product" ).length != 0 || ( typeof(wccpf_opt.editable) !== "undefined" && wccpf_opt.editable === "yes" ) || $( "[data-is_pricing_rules=yes]" ).length != 0 ) {
				var editor_obj = new wcff_cart_handler();
				editor_obj.initialize();
			} else {
				var editors = $( "li.wcff_cart_editor_field" );
				editors.removeClass("wcff_cart_editor_field").removeAttr( 'title data-field data-fieldname data-productid data-itemkey' );
				editors.closest( ".wccpf-is-editable-yes" ).removeClass( 'wccpf-is-editable-yes' );
			}
			
			if( wccpf_opt.is_page === "archive" ){
				
				function wcff_get_fields_value(product_fields, parent){
					var data = {},
					single_field = $("");
					for( var i = 0; i < product_fields.length; i++ ){
						single_field = $( product_fields[i] );
						if( single_field.closest( ".wcff_is_hidden_from_field_rule" ).length == 0 ){
							if( !single_field.is( "[type=checkbox]" ) && !single_field.is( "[type=file]" ) ){
								data[single_field.attr("name")] = parent.find( '[name="'+single_field.attr("name")+'"]' ).val();
							} else if( single_field.is( "[type=checkbox]" ) && single_field.is( ":checked" ) ){
								var key = single_field.attr("name").replace( "[]", "" );
								if( typeof data[key] == "undefined" ){
									data[key] = [];
								}
								data[key].push( single_field.val() );
							} 
						}
					}
					return data;
				};
				
				$( document.body ).on( 'adding_to_cart', function(e, _btn, _data){
					var parent =  _btn.closest( "li.product" ),
						product_fields = parent.find( ".wccpf_fields_table .wccpf-field" ),
						data = wcff_get_fields_value(product_fields, parent);
					$.extend(_data, data);
					//return _data;
				});
				
				
				if( wccpf_opt.is_ajax_add_to_cart == "no" ){
					$( document ).on( 'click', ".add_to_cart_button:not(.product_type_variable)", function(e){
						var parent =  $( this ).closest( "li.product" ),
							product_fields = parent.find( ".wccpf_fields_table:not(.wcff_is_hidden_from_field_rule) .wccpf-field" ),
							data = wcff_get_fields_value(product_fields, parent),
							query_string = "";
						for( var j in data ){
							query_string += "&"+j+"="+data[j];
						}
						if(query_string != ""){
							$( this ).attr( "href", $( this ).attr( "href" )+query_string );
						}
					});
					
				}
			}
		}
		
		
		
	});
	
})(jQuery);