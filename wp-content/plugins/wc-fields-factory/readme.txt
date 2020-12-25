=== WC Fields Factory ===
Contributors: mycholan, sarkparanjothi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U3ENPZS5CYMH4
Tags: wc fields factory, custom product fields, custom admin fields, overriding product price, custom woocommerce fee, customize woocommerce product page, add custom fields to woocommerce product page, custom fields validations, wmpl compatibility 
Requires at least: 3.5
Tested up to: 4.9.8
Stable tag: 3.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sell your products with customized, personalised options. Add custom fields or fields group to your products, your admin screens and customize everything.


== Description ==

WC Fields Factory is a Woocommerce extension which helps to add custom fields to your Product Page, using which you can gather additional information from the users while they adding the product to Cart. (like, getting the Name that has to be printed on the TShirt that your are selling).

The additional information that has been captured on the product page would be carried over to Cart -> Checkout -> Order & Email.

Not only on front end product page, also on backend Product, Product Category and Product Tabs (General, Inventory, Shipping, Attributes, Related and Advanced). You can also add custom fields to Variations (Individually). Apart from that, those admin fields can be configured to show on the front end product page as well (with the predefined values) which will also be carried over to Cart -> Checkout -> Order & Email.

Well using WC Fields Factory now you can even override the product price based on the custom field's value (given by the user, when adding to cart), also you can add to custom Woocommerce Fee based on user value.

All these could be done through the powerful initiative user interface, without a single line of additional code.

= Features =
* Supports 12 types of Product Fields (Text, Number, Email, Text Area, Check Box, Radio Buttons, Select, File Upload, Date Picker, Color Picker, Hidden & Label).
* Supports 11 types of Admin Fields (Text, Number, Email, Text Area, Check Box, Radio Buttons, Select, Date Picker, Color Picker, Image & Url).
* Supports 11 types of Checkout Fields (Text, Number, Email, Text Area, Check Box, Radio Buttons, Select, Date Picker, Color Picker, Hidden & Label).
* Client side as well as Server side validations.
* Assign custom fields to particular product, products category, product tag, product type, logged in users and users who has certin roles.
* Cloning Fields (Each quantity will have their own set of Fields).
* Pricing & Fee rules.
* Multilingual. Translate custom field's labels, messages and other attributes (WPML compatible).
* Powerful API to customize WC Fields Factory (Funcational as well as UI level)

= Documentation =
* [Product Fields](https://sarkware.com/wc-fields-factory-a-wordpress-plugin-to-add-custom-fields-to-woocommerce-product-page/)
* [Admin Fields](https://sarkware.com/add-custom-fields-woocommerce-admin-products-admin-product-category-admin-product-tabs-using-wc-fields-factory/)
* [Pricing & Fee Rules](https://sarkware.com/pricing-fee-rules-wc-fields-factory/)
* [Multilingual](https://sarkware.com/multilingual-wc-fields-factory/)
* [Troubleshoot](https://sarkware.com/troubleshoot-wc-fields-factory/)
* [WC Fields Factory APIs](https://sarkware.com/wc-fields-factory-api/)
* [Overriding Product Prices](http://sarkware.com/woocommerce-change-product-price-dynamically-while-adding-to-cart-without-using-plugins/#override-price-wc-fields-factory)
* [Customize Rendering Behavior](http://sarkware.com/how-to-change-wc-fields-factory-custom-product-fields-rendering-behavior/)


== Installation ==
1. Ensure you have latest version of WooCommerce plugin installed ( 2.2 or above )
2. Unzip and upload contents of the plugin to your /wp-content/plugins/ directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Use the "Add New" button from "Fields Factory" menu in the wp-admin to create custom fields for woocommerce product page

== Screenshots ==
1. Single Product Page with Custom Fields
1. Wcff Product Custom Fields List
2. Wcff Factory View
3. Wcff Rules
4. Wcff Pricing Rules View
5. Wcff Fee Rules View

== Changelog ==

= 3.0.3 =
* Php 5.4 version conflict - fixed
* Date picker front end valitation - fixed
* wcff-admin.css style chache - fixed

= 3.0.2 =
* Archive page admin and product fields with optional
* Pricing rule % without calculated value show to user - fixed
* Cart editor validation - fixed
* Field cloning with pricing rule - split cart item
* Cart editor with pricing rule - fixed
* Pricing and fee rule decimal number enabled
* wcff_before_rendering_cart_data - miss value passed - fixed
* wcff_before_inserting_order_item_meta - miss value passed - fixed
* Added an option for pricing details info show/hide
* JavaScript errors related to timepicker/datepicker - fixed

= 3.0.1 =
* Product tab not working fixed
* Cart editor validation fixed
* Jquery exception handled
* Checkout field without state validation issue fixed
* Checkout default field label language issue fixed.
* Checkout hidden field not  working - fixed
* Variation field initial field rule not working - fixed

= 3.0.0 =
* Group wise field location for product and admin fields.
* Unnessary sortable on field config - fixed
* Cart update not working after wordpress update - fixed
* Price rule product ajax price replace improved
* Role based field default hide - fixed
* Color-field text input for user can type or paste color code - optional
* Required field with hidden valitation miss match - fixed
* Fields not in cart form, fields value not carry on cart - fixed
* Color-field extra option for change product image color based
* Variation fields show with this appropriate location on product page
* Product image not showing after update fixed
* variation fields showing only when user login - fixed
* label not cloning - fixed
* label not woking with fields rule - fixed
* Added checkout fields for billing, shipping and custom.
* Manipulate woocommerce billing and shipping fields.
* Admin side field config UI Changes ( Look & feel and can view & edit multiple field config same time ).
* Added wcff setting page link on Installed plugin page.
* hidden field value not carry on cart - fixed

= 2.0.8 = 
* Fields for Variations
* Ability to show & hide fields based on user interaction
* Updating the product price on single product page (Based on pricing rules) 
* Percentage option for fees and price rules 
* NOT NULL rule added for Pricing & Fee
* Select option before rendering filter added
* Date disable next x days added
* Field show on Before Product Meta and After Product Meta added
* Filters added for wcff_realtime_negotiate_price_after_calculation and wcff_negotiate_price_after_calculation

= 2.0.7 = 
* Pricing rules re-modify
* Interfering with wooCommerce bookings and cart totals - fixed
* Woocommerce dynamic pricing interferes with fields factory - fixed

= 2.0.6 = 
* Pricing rules issue fixed

= 2.0.5 = 
* Admin field checkbox issue fixed
* Admin field readonly issue fixed
* Timepicker not working issue fixed
* Client side validation issue fixed
* Translation config added for Cloning Group Title (Setting Page)
* 'wccpf_cloning_fields_group_title' filter added to override the cloning group title text
* Empty editor wrapper on Cart Line Items issue fixed
* Datepicker issue Cart Editor fixed
* Placeholder option added for Select Field.
* Priority for woocommerce releated hook changed (To prevent overridden by other plugins).
* Minimum Maximum hours & Minute option added for Time Picker

= 2.0.4 =
* Color picker validation issue fix
* Wrapper class for each field's wrapper added 
* Suppressed unnecessary warning messages (like accessing undefined index).

= 2.0.3 =
* Parse error: syntax error, unexpected ‘[‘ fixed

= 2.0.2 =
* Date picker issue fix

= 2.0.1 =
* Call to undefined function WC() fixed 
* "wp_register_style" was called incorrectly, warning message (along with other warning message) fixed

= 2.0.0 =
* Pricing & Fee rules for custom fields, now you change product price based on fields value.
* Multilingual support added (right now it support WPML).
* Field level cloning option (Exclude field from cloning).
* Show fields based on user roles.
* Fields value retained whenever validation is failed.
* Option factory widget added for Check, Radio and Select box.
* Default option will be the actual tag (genrated from the choices param on real time).
* \' \" escaping issue resolved.
* HTML tags on label message issue resolved. 
* Enable plugin access to Woocommerce Shop Manager role.
* Date picker and Colorpicker issue on Variation tab & Product cat page fixed.
* Now cloned fields (Also if you enabled Editable on Cart option) will be rendered on cart & check out page by the Field Factory itself, so exsisting users might experiance some styling changes on Cart & Checkout.
* Replaced all "/" with "_" on WC Fields Factory related actions and filters. (eg. "wccpf/before/field/start" has become 'wccpf_before_field_start')

= 1.4.0 =
* White screen of death issue solved

= 1.3.9 =
* WC 3+ compatibility updates
* woocommerce_add_order_item_meta ( woo 3.0.6 ) update
* Option to change file upload directory ( Within wp-contents only )
* New field type for Admin Fields ( for posting URLs )
* Multi check box option for Admin Field
* Conflict resolved with Ticket Plus plugin
* Returns label instead of value for select field - option added
* Admin field display issue on cart - solved ( happened only when Fields Cloning Enabled )
* Export order meta option added for 'WooCommerce Simply Order Export' plugin ( more plugin support coming soon )

= 1.3.8 =
* Cart editing issue fixed ( Earlier, editing not worked for cloning fields )
* Cart editing option added for both Global as well as individual fields ( By default it will be non editable )
* Custom css class option has been added for all fields
* Minor code tuning to suppress unnecessary warnings

= 1.3.7 =
* Cart page field editing option added ( Except file upload all other fields can be edited )
* Image preview option on Cart & Checkout page added ( Only for File upload with Image type )
* Option added for showing admin field's value instead of field itself on front end ( Default option will be field )

= 1.3.6 =
* Issue fixed on Product pages generated by Visual Composer fixed
* wp_register_style & wp_enqueue_style warning fixed
* File upload restriction issue fixed
* File upload max size option included
* Additional options for Disable dates in Date picker ( Disable Week days, Week end, Specific dates, specific dates for all months )
* Color picker fields now displaying color palette instead of raw value
* Default color picker value issue fixed
* Showing fields for logged in users option added ( for both Globally or Field wise )
* Allowing decimal type on Number field issue fixed ( You can now give 'auto' on 'step count' option )
 
= 1.3.5 =
* File upload validation issue fixed
* New field ( Image Upload ) has been added ( available only for Admin Fields )
* Now you can display your custom fields under Product Tab ( New Product Tab will be created, you have to enable it via WCFF Settings Screen )
* Single & Double quotes escaping problem fix ( on Fields Label )
* Year range option has been added for Date Picker ( '-50:+0',-100:+100 or absolute 1985:2065 )
* Date picker default language added ( English/US )
* Variable product Admin Fields saving issue fix
* Client side validation on blur settings added ( now you can specify whether the validation done on on submit or on field out focus )
* Show fields group title on Front End ( Post Title ( Fields group ) will be displayed )
* Number field validation Reg Exp fix ( Client Side )
* WCFF option access has been centralized ( now you can add 'wcff_options' filter to update options before it reaches to WCFF )
* Woocommerce ( If it is not activated yet ) not found alert added ( It's funny that I didn't checked this far, but this plugin will work even without woocommerce but there won't be much use then )
* Overly mask will be displayed while trying to edit or remove fields meta ( on wp-admin screen )

= 1.3.4 =
* Default color option for Color Field
* Admin Select field shows wrong value on Product Front End page issue fixed
* i18n support for Field's Label ( now you can create fields on Arabic, Chinese, korean .... ) 

= 1.3.3 =
* Validation error fix for Admin Field ( "this field can't be empty" is shown )

= 1.3.2 =
* fix for : Undefined variable ( Trying to get property of non-object ): product in /wc-fields-factory/classes/wcff-product-form.php on line 247

= 1.3.1 =
* Product rules error fixed
* Datepicker on chinese language issue fixed
* Checkout order review table heading spell mistakes fixed
* Rendering admin fields on product front end support added ( By default it's not, you will have to enable the option for each fields - for product page, cart & checkout page and order meta )
* Fields location not supported fix ( now you can use 'woocommerce_before_add_to_cart_form', 'woocommerce_after_add_to_cart_form', 'woocommerce_before_single_product_summary', 'woocommerce_after_single_product_summary' and 'woocommerce_single_product_summary' )

= 1.3.0 =
* Fields update issue fixed.
* File validation issue ( Fatal error: Call to undefined function finfo_open() ) fixed.

= 1.2.9 =
* Admin fields validation ( for mandatory ) added.
* File types server side validation - fixed.
* Validation $passed var usage - added.
* wccpf_unique_key conditional - removed ( as it no longer needed ).
* Time picker option added.
* Localization ( multi language support ) for datepicker added.
* Show dropdowns for month and year - datepicker.
* Uncaught ReferenceError: wcff_fields_cloning is not defined - fixed.
* Enque script without protocol ( caused issue over https ) - fixed.
* Show & hide on cart & checkoput pge option added for hidden field
* from V1.2.9, we are using Fileinfo module to validate file uploads ( using their mime types )
  PHP 5.3.0 and later have Fileinfo built in, but on Windows you must enable it manually in your php.ini


= 1.2.8 =
* "Display on Cart & Checkout" option on Setting page - issue fixed.

= 1.2.7 =
* Check box field's choice option not updated - issue fixed.

= 1.2.6 =
* Product rules broken issue fixed. 

= 1.2.5 =
* Two new fields has been added. Label ( you can now display custom message on product page ) & Hidden fields
* Client side validation included ( by default it's disabled, you will have to enable it through settings pags )
* Validation error message for each field, will be shown at the bottom of each fields.
* wccaf post type introduced ( custom fields for backend admin prducts section )
* Now you can add custom fields for back end as well ( on Product Data tabs, like you can add extra fields on general, inventory, shipping, variables, attributes tabs too )
* Multi file uploads support added ( for file field )
* Support for rules by tags & rules by product types added
* Order Item Meta visibility option added
* Datepicker disable dates issue solved
* Fields cancel button issue ( on the edit screen ) solved
* "Allowed File Types" in the File field, you will have to prefix DOT for all extensions 
* Entire plugin code has been re structured, proper namespace added for all files & classes, more comments added

= 1.2.4 =
* Fix for "Fields Group Title showing on all products since the V1.2.3"
* Wrapper added for each field groups

= 1.2.3 =
* Multiple colour pickers issue fix
* wccpf_init_color_pickers undefined issue fix
* Group title index will be hidden if product count is 1
* Minimum product quantity issue fix
* File type validation issue fix
* "Zero fields message" while deleting custom fields ( on wp-admin )

= 1.2.2 =
* Fields cloning option added ( Fields per count, If customer increase product count custom fields also cloned )
* Visibility of custom meta can be set ( show or hide on cart & checkout page )

* Setting page added
* Visibility Option - you can set custom data visibility globally ( applicable for all custom fields - created by this plugin )
* Field Location - you can specifiy where the custom fields should be included.
* Enable or Disbale - fields cloning option.
* Grouping the meta on cart & checkout page, option added.
* Grouping custom fields on cart & checkout page, option added.
* Set label for fields group
* Option to disable past or future dates
* Option to disbale particular week days
* Read only option added for Datepicker textbox ( usefull for mobile view )
* heigher value z-index applied for datepickers
* Pallete option added to color picker
* Option to show only palette or along with color picker
* Color format option added

= 1.2.1 =
* Add to cart validation issue fixed

= 1.2.0 =
* Woocommerce 2.4.X compatible 
* File upload field type added
* Internationalization ( i18n ) support added

= 1.1.6 =
* fixed "Missing argument" error log warning message

= 1.1.5 =
* Select field with variable product - issue fixed
* Order conflict while updating fields - issue fixed
* Newline character ( for select, checkbox and radio ) - issue fixed

= 1.1.4 =
* utf-8 encoding issue fixed
* Internationalization support.

= 1.1.3 =
* Order meta ( as well as email ) not added Issue fixed  

= 1.1.2 =
* Removed unnecessary hooks ( 'woocommerce_add_to_cart', 'woocommerce_cart_item_name' and 'woocommerce_checkout_cart_item_quantity' ) 
  yes they no longer required.
* Now custom fields data has been saved in session through 'woocommerce_add_cart_item_data' hook
* Custom fields rendered on cart & checkout page using 'woocommerce_get_item_data' ( actually rendered via 'cart-item-data.php' template )

= 1.1.1 =
* Color picker field type added

= 1.1.0 =
* Date picker field type added

= 1.0.4 =
* Validation issue fixed.
* Issue fixed ( warning log for each non mandatory custom fields ).
* Some css changes ( only class name ) to avoid collision with Bootstrap. 

= 1.0.3 =
* Hiding empty fields from cart table, checkout order review table and order meta.

= 1.0.2 =
* Issue fixing with "ACF" meta key namespace collition. 

= 1.0.1 =
* "wccpf/before/field/rendering" and "wccpf/after/field/rendering" actions has been added to customize wccpf fields rendering

= 1.0.0 =
* First Public Release.