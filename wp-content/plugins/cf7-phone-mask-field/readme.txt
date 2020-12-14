=== Contact Form 7 - Phone mask field ===
Contributors: heorhiiev
Tags: Contact Form 7, Contact Form 7 phone, mask, mask field, phone field, telephone field, telephone, custom mask, custom mask field, cf7, contact form 7 mask field, contact form 7 phone mask, contact form 7 phone field, маска ввода, маска телефонного номера
Requires at least: 4.0
Tested up to: 5.3
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a new field in which you can set the phone number mask or other to Contact Form 7.

== Description ==

This plugin adds a new field in which you can set the phone number mask or other to Contact Form 7.

Please notice that Contact Form 7 (version 5.0.3 or latest) must be installed and active.

A new field &quot;mask field&quot; will be added to the Contact Form 7 panel buttons.

Example: [mask* your-tel &quot;mask&quot;  &quot;Placeholder&quot;] 

Mask definitions:
&quot;_&quot; - any numeric character.


== Installation ==

Just install from your WordPress "Plugins > Add New" screen and all will be well. Manual installation is very straightforward as well:

1. Upload the `cf7-phone-mask-field` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's it!

== Screenshots ==

1. New field in Contact Form 7

== Upgrade Notice ==

== Changelog ==

= 1.0 =
* First released version.

= 1.1 =
* Added support for the placeholder field.

= 1.2 =
* Added a form-tag generator field for the placeholder value.

= 1.3 =
* Added support for the type value.

= 1.4 =
* Remove autoclear. Added filter "wpcf7mf_validate_mask_units". Mask definition "*" changed to "."

= 1.4.1 =
* Removed any character mask definition (".")