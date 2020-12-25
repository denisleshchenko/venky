<?php
return array(
    "text" => array(
        "meta" => array(
            array(
                "label" => __('Place Holder', 'wc-fields-factory'),
                "desc" => __('Place holder text for this Text Box', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Value', 'wc-fields-factory'),
                "desc" => __('Default value for this Text Box<br/>If the field is empty then this will be used as default', 'wc-fields-factory'),
                "type" => "text",
                "param" => "default_value",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Maximum Characters', 'wc-fields-factory'),
                "desc" => __('Leave it blank for no limit', 'wc-fields-factory'),
                "type" => "number",
                "param" => "maxlength",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            
            )
        ),
        "title" => __('Text', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/text-field-wc-fields-factory/"
    ),
    "number" => array(
        "meta" => array(
            array(
                "label" => __('Place Holder', 'wc-fields-factory'),
                "desc" => __('Place holder text for this Text Box<br/>If the field is empty then this will be used as default', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Value', 'wc-fields-factory'),
                "desc" => __('Default value for this Text Box', 'wc-fields-factory'),
                "type" => "number",
                "param" => "default_value",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Minimum Value', 'wc-fields-factory'),
                "desc" => "Minimum value that this number field will accept.",
                "type" => "number",
                "param" => "min",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Maximum Value', 'wc-fields-factory'),
                "desc" => "Maximum value that this number field will accept.",
                "type" => "number",
                "param" => "max",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Step Size', 'wc-fields-factory'),
                "desc" => "Step size for Increment and Decrement.",
                "type" => "number",
                "param" => "step",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Number', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/number-field-wc-fields-factory/"
    ),
    "email" => array(
        "meta" => array(
            array(
                "label" => __('Place Holder', 'wc-fields-factory'),
                "desc" => __('Place holder text for this Text Box', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Value', 'wc-fields-factory'),
                "desc" => __('Default value for this Text Box<br/>If the field is empty then this will be used as default', 'wc-fields-factory'),
                "type" => "text",
                "param" => "default_value",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Email', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/email-field-wc-fields-factory/"
    ),
    "hidden" => array(
        "meta" => array(
            array(
                "label" => __('Hidden Value', 'wc-fields-factory'),
                "desc" => __('Value for this hidden field', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Hidden', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/hidden-field-wc-fields-factory/"
    ),
    "label" => array(
        "meta" => array(
            array(
                "label" => __('Message', 'wc-fields-factory'),
                "desc" => __('Any text which has to be displayed', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "message",
                "placeholder" => "",
                "rows" => "3",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Position', 'wc-fields-factory'),
                "desc" => __('Where this message has to be displayed ( before all the fields or after the all fields or along with other fields )', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "position",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "normal",
                        "label" => __('Normal', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "beginning",
                        "label" => __('At the  Beginning', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "end",
                        "label" => __('At the End', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Type', 'wc-fields-factory'),
                "desc" => __('Type of the message that is about to display', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "message_type",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "info",
                        "label" => __('Info', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "success",
                        "label" => __('Success', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "warning",
                        "label" => __('Warning', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "danger",
                        "label" => __('Danger', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Label', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/label-widget-wc-fields-factory/"
    ),
    "textarea" => array(
        "meta" => array(
            array(
                "label" => __('Place Holder', 'wc-fields-factory'),
                "desc" => __('Place holder text for this Text Area', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Value', 'wc-fields-factory'),
                "desc" => __('Default value for this Text Area<br/>If the field is empty then this will be used as default', 'wc-fields-factory'),
                "type" => "text",
                "param" => "default_value",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Maximum Characters', 'wc-fields-factory'),
                "desc" => __('Leave it blank for no limit', 'wc-fields-factory'),
                "type" => "number",
                "param" => "maxlength",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Rows', 'wc-fields-factory'),
                "desc" => __('Set the textarea height ( Line Count )', 'wc-fields-factory'),
                "type" => "number",
                "param" => "rows",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Text Area', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/textarea-field-wc-fields-factory/"
    ),
    "checkbox" => array(
        "meta" => array(
            array(
                "label" => __('Options', 'wc-fields-factory'),
                "desc" => __('Enter each options on a new line, like this <br/><br/>red|Red<br/>blue|Blue', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "choices",
                "placeholder" => "",
                "rows" => "5",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Options', 'wc-fields-factory'),
                "desc" => __('If no options selected then this will be used as default.', 'wc-fields-factory'),
                "type" => "html",
                "html" => "<div class=\"wcff-default-choice-wrapper wcff-default-option-holder\"></div>",
                "param" => "default_value",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Layout', 'wc-fields-factory'),
                "desc" => __('Row wise (or) Column wise', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "layout",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "horizontal",
                        "label" => __('Horizontal', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "vertical",
                        "label" => __('Vertical', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Check Box', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/checkbox-wc-fields-factory/"
    ),
    "radio" => array(
        "meta" => array(
            array(
                "label" => __('Options', 'wc-fields-factory'),
                "desc" => __('Enter each options on a new line, like this <br/><br/>red|Red<br/>blue|Blue', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "choices",
                "placeholder" => "",
                "rows" => "5",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Options', 'wc-fields-factory'),
                "desc" => __('If no option selected then this will be used as default option.', 'wc-fields-factory'),
                "type" => "html",
                "html" => "<div class=\"wcff-default-choice-wrapper wcff-default-option-holder\"></div>",
                "param" => "default_value",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Layout', 'wc-fields-factory'),
                "desc" => __('Row wise (or) Column wise', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "layout",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "horizontal",
                        "label" => __('Horizontal', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "vertical",
                        "label" => __('Vertical', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Radio Button', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/radio-buttons-wc-fields-factory/"
    ),
    "select" => array(
        "meta" => array(
            array(
                "label" => __('Options', 'wc-fields-factory'),
                "desc" => __('Enter each options on a new line, like this <br/><br/>red|Red<br/>blue|Blue', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "choices",
                "placeholder" => "",
                "rows" => "5",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Default Options', 'wc-fields-factory'),
                "desc" => __('If no option selected then this will be used as default option', 'wc-fields-factory'),
                "type" => "html",
                "html" => "<div class=\"wcff-default-choice-wrapper wcff-default-option-holder\"></div>",
                "param" => "default_value",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
        	array(
        		"label" => __('Place Holder', 'wc-fields-factory'),
        		"desc" => __('Placeholder option, which doesn\'t count as neither Option nor Default Option. ' , 'wc-fields-factory'),
        		"type" => "text",
        		"param" => "placeholder",
        		"placeholder" => "-- Choose any Option --",
        		"at_startup" => "show",
        		"translatable" => "yes"
        	)
        ),
        "title" => __('Select', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/select-field-wc-fields-factory/"
    ),
    "datepicker" => array(
        "meta" => array(
            array(
                "label" => __('Place Holder', 'wc-fields-factory'),
                "desc" => __('Place holder text for this Text Box', 'wc-fields-factory'),
                "type" => "text",
                "param" => "placeholder",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Read Only', 'wc-fields-factory'),
                "desc" => __('Make text field read only, so it won\'t pulls up mobile key board ( on mobile browsers )', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "readonly",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Show Time Picker', 'wc-fields-factory'),
                "desc" => __('Show time picker along with date picker', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "timepicker",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Localize Datepicker', 'wc-fields-factory'),
                "desc" => __('Choose the language in which the datepicker should be displayed', 'wc-fields-factory'),
                "type" => "select",
                "param" => "language",
                "options" => array(
                    array(
                        "value" => "none",
                        "label" => __('Choose Language', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "af",
                        "label" => __('Afrikaans', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ar",
                        "label" => __('Arabic', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ar-DZ",
                        "label" => __('Algerian Arabic', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "az",
                        "label" => __('Azerbaijani', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "be",
                        "label" => __('Belarusian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "bg",
                        "label" => __('Bulgarian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "bs",
                        "label" => __('Bosnian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ca",
                        "label" => __('Catalan', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "cs",
                        "label" => __('Czech', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "cy-GB",
                        "label" => __('Welsh/UK', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "da",
                        "label" => __('Danish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "de",
                        "label" => __('German', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "el",
                        "label" => __('Greek', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "en-AU",
                        "label" => __('English/Australia', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "en-GB",
                        "label" => __('English/UK', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "default",
                        "label" => __('English/US', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "en-NZ",
                        "label" => __('English/New Zealand', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "eo",
                        "label" => __('Esperanto', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "es",
                        "label" => __('Español', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "et",
                        "label" => __('Estonian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "eu",
                        "label" => __('Spanish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fa",
                        "label" => __('Persian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fi",
                        "label" => __('Finnish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fo",
                        "label" => __('Faroese', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fr-CA",
                        "label" => __('Canadian-French', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fr-CH",
                        "label" => __('Swiss-French', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "fr",
                        "label" => __('French', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "gl",
                        "label" => __('Galician', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "he",
                        "label" => __('Hebrew', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "hi",
                        "label" => __('Hindi', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "hr",
                        "label" => __('Croatian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "hu",
                        "label" => __('Hungarian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "hy",
                        "label" => __('Armenian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "id",
                        "label" => __('Indonesian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "is",
                        "label" => __('Icelandic', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "it-CH",
                        "label" => __('Italian - CH', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "it",
                        "label" => __('Italian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ja",
                        "label" => __('Japanese', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ka",
                        "label" => __('Georgian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "kk",
                        "label" => __('Kazakh', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "km",
                        "label" => __('Khmer', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ko",
                        "label" => __('Korean', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ky",
                        "label" => __('Kyrgyz', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "lb",
                        "label" => __('Luxembourgish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "lt",
                        "label" => __('Lithuanian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "lv",
                        "label" => __('Latvian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "mk",
                        "label" => __('Macedonian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ml",
                        "label" => __('Malayalam', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ms",
                        "label" => __('Malaysian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "nb",
                        "label" => __('Norwegian - Bokmål', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "nl-BE",
                        "label" => __('Dutch - Belgium', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "nl",
                        "label" => __('Dutch', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "nn",
                        "label" => __('Norwegian Nynorsk', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('Norwegian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "pl",
                        "label" => __('Polish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "pt-BR",
                        "label" => __('Brazilian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "pt",
                        "label" => __('Portuguese', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "rm",
                        "label" => __('Romansh', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ro",
                        "label" => __('Romanian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ru",
                        "label" => __('Russian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sk",
                        "label" => __('Slovak', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sl",
                        "label" => __('Slovenian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sq",
                        "label" => __('Albanian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sr-SR",
                        "label" => __('Serbian - SR', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sr",
                        "label" => __('Serbian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "sv",
                        "label" => __('Swedish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "ta",
                        "label" => __('Tamil', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "th",
                        "label" => __('Thai', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "tj",
                        "label" => __('Tajiki', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "tr",
                        "label" => __('Turkish', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "uk",
                        "label" => __('Ukrainian', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "vi",
                        "label" => __('Vietnamese', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "zh-CN",
                        "label" => __('Chinese - CN', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "zh-HK",
                        "label" => __('Chinese - HK', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "zh-TW",
                        "label" => __('Chinese - TW', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Month & Year Dropdown', 'wc-fields-factory'),
                "desc" => __('Display month & year in dropdown instead of static month/year header navigation', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "display_in_dropdown",
                "layout" => "vertical",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Show Dropdown', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('Show Default', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Year Range', 'wc-fields-factory'),
                "desc" => __('Before and after year range. By default Year Dropdown displays only 10 years, you modify it using this option.<br/>You may use either relative ( -100:+100 ) or absolute ( 1985:2065 )', 'wc-fields-factory'),
                "type" => "text",
                "param" => "dropdown_year_range",
                "placeholder" => "-10:+10",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Date Format', 'wc-fields-factory'),
                "desc" => __('The Date Format that will be used display & save the value', 'wc-fields-factory'),
                "type" => "text",
                "param" => "date_format",
                "placeholder" => "d-m-Y",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Disable Days', 'wc-fields-factory'),
                "desc" => __('Prevent user from selecting particular day(s)', 'wc-fields-factory'),
                "type" => "tab",
                "param" => "",
                "tabs" => array(
                    array(
                        "header" => array(
                            "title" => __('Past or Future', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-disable-past-future-dates",
                            "css_class" => "active"
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-disable-past-future-dates",
                            "fields" => array(
                                array(
                                    "type" => "radio",
                                    "param" => "disable_date",
                                    "layout" => "horizontal",
                                    "options" => array(
                                        array(
                                            "value" => "none",
                                            "label" => __('Enable All Date', 'wc-fields-factory'),
                                            "selected" => true
                                        ),
                                        array(
                                            "value" => "past",
                                            "label" => __('Disable Past Dates', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "future",
                                            "label" => __('Disable Future Dates', 'wc-fields-factory'),
                                            "selected" => false
                                        )
                                    ),
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Days', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-disable-days",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-disable-days",
                            "fields" => array(
                                array(
                                    "type" => "checkbox",
                                    "param" => "disable_days",
                                    "layout" => "horizontal",
                                    "options" => array(
                                        array(
                                            "value" => "sunday",
                                            "label" => __('Sunday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "monday",
                                            "label" => __('Monday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "tuesday",
                                            "label" => __('Tuesday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "wednesday",
                                            "label" => __('Wednesday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "thursday",
                                            "label" => __('Thursday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "friday",
                                            "label" => __('Friday', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "saturday",
                                            "label" => __('Saturday', 'wc-fields-factory'),
                                            "selected" => false
                                        )
                                    ),
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Specific Dates', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-disable-specific-dates",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-disable-specific-dates",
                            "fields" => array(
                                array(
                                    "type" => "textarea",
                                    "param" => "specific_dates",
                                    "placeholder" => __('Format: MM-DD-YYYY Example: 1-22-2017,10-7-2017', 'wc-fields-factory'),
                                    "rows" => "2",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Weekends Or Weekdays', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-disable-weekends-or-weekdays",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-disable-weekends-or-weekdays",
                            "fields" => array(
                                array(
                                    "type" => "radio",
                                    "param" => "weekend_weekdays",
                                    "layout" => "horizontal",
                                    "options" => array(
                                        array(
                                            "value" => "weekends",
                                            "label" => __('Week Ends', 'wc-fields-factory'),
                                            "selected" => false
                                        ),
                                        array(
                                            "value" => "weekdays",
                                            "label" => __('Week Days', 'wc-fields-factory'),
                                            "selected" => false
                                        )
                                    ),
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                ),
                                array(
                                    "type" => "html",
                                	"param" => "",
                                    "html" => '<a href="#" class="wcff-date-disable-radio-clear button">' . __('Clear', 'wc-fields-factory') . '</a>',
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Specific Dates All Months', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-disable-specific-date-all-months",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-disable-specific-date-all-months",
                            "fields" => array(
                                array(
                                    "type" => "textarea",
                                    "param" => "specific_date_all_months",
                                    "placeholder" => __('Example: 5,10,12', 'wc-fields-factory'),
                                    "rows" => "2",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Allow X Years', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-allow-only-next-x-years",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-allow-only-next-x-years",
                            "fields" => array(
                                array(
                                    "type" => "number",
                                    "param" => "allow_next_x_years",
                                    "placeholder" => __('Allow only next X years from the current date', 'wc-fields-factory'),
                                    "min" => "1",
                                    "step" => "1",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Allow X Months', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-allow-only-next-x-months",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-allow-only-next-x-months",
                            "fields" => array(
                                array(
                                    "type" => "number",
                                    "param" => "allow_next_x_months",
                                    "placeholder" => __('Allow only next X months from the current date', 'wc-fields-factory'),
                                    "min" => "1",
                                    "step" => "1",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Allow X Weeks', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-allow-only-next-x-weeks",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-allow-only-next-x-weeks",
                            "fields" => array(
                                array(
                                    "type" => "number",
                                    "param" => "allow_next_x_weeks",
                                    "placeholder" => __('Allow only next X weeks from the current date', 'wc-fields-factory'),
                                    "min" => "1",
                                    "step" => "1",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                    array(
                        "header" => array(
                            "title" => __('Allow X Days', 'wc-fields-factory'),
                            "target" => "#wcff-date-field-allow-only-next-x-days",
                            "css_class" => ""
                        ),
                        "content" => array(
                            "container" => "wcff-date-field-allow-only-next-x-days",
                            "fields" => array(
                                array(
                                    "type" => "number",
                                    "param" => "allow_next_x_days",
                                    "placeholder" => __('Allow only next X days from the current date', 'wc-fields-factory'),
                                    "min" => "1",
                                    "step" => "1",
                                    "at_startup" => "show",
                                    "translatable" => "no"
                                )
                            )
                        )
                    ),
                		array(
                				"header" => array(
                						"title" => __('Disable Next X Days', 'wc-fields-factory'),
                						"target" => "#wcff-date-field-disable-next-x-day",
                						"css_class" => ""
                				),
                				"content" => array(
                						"container" => "wcff-date-field-disable-next-x-day",
                						"fields" => array(
                								array(
                										"type" => "number",
                										"param" => "disable_next_x_day",
                										"placeholder" => __('Disable only next X days from the current date', 'wc-fields-factory'),
                										"min" => "1",
                										"step" => "1",
                										"at_startup" => "show",
                										"translatable" => "no"
                								)
                						)
                				)
                		)
                ),
                "translatable" => "no"
            )
        ),
        "title" => __('Date Picker', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/datepicker-wc-fields-factory/"
    ),
    "colorpicker" => array(
        "meta" => array(
            array(
                "label" => __('Color Format', 'wc-fields-factory'),
                "desc" => __('How you want the color value', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "color_format",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "hex",
                        "label" => __('HEX', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "hex3",
                        "label" => __('HEX3', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "hsl",
                        "label" => __('HSL', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "rgb",
                        "label" => __('RGB', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "name",
                        "label" => __('Name', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Default Color', 'wc-fields-factory'),
                "desc" => __('If customer doesn\'t choose any color then this color would be used instead', 'wc-fields-factory'),
                "type" => "text",
                "param" => "default_value",
                "placeholder" => "#ff6600",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Show Palette Only', 'wc-fields-factory'),
                "desc" => __('Want show only the palette.? or along with the color picker.?', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "show_palette_only",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Palettes', 'wc-fields-factory'),
                "desc" => __('Instead of showing only the color picker, you can show them personalized palettes, where customer chooce one of the color provided by you <br/><br/>#fff, #ccc, #555<br/>#f00, #0f0, #00f', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "palettes",
                "placeholder" => "",
                "rows" => "5",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Show Hex Value as', 'wc-fields-factory'),
                "desc" => __('Color value in color ( actual color displayed ) or just the color code.? ( This will be affect only on Cart & Checkout Page, in Order & Email the actual color value used )', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "hex_color_show_in",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Show as Color', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('Show the Color Code', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Show Text Field', 'wc-fields-factory'),
                "desc" => __('User can paste color code on this text field.', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "color_text_field",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Color Picker', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccaf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/color-picker-wc-fields-factory/"
    ),
    "file" => array(
        "meta" => array(
            array(
                "label" => __('Allowed File Types', 'wc-fields-factory'),
                "desc" => __('Enter comma seperated list of file type extensions <br/><br/>audio/*, video/*, image/*, .pdf,.docx,.jpg,.png', 'wc-fields-factory'),
                "type" => "textarea",
                "param" => "filetypes",
                "placeholder" => "",
                "rows" => "3",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Multiple Files Upload', 'wc-fields-factory'),
                "desc" => __('Whether to allow multiple files to be uploaded on this field.!', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "multi_file",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Preview Option', 'wc-fields-factory'),
                "desc" => __('If it is image File, preview image.? or just file name alone.?', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "img_is_prev",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => false
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => true
                    )
                ),
                "additonal" => array(
                    "type" => "text",
                    "param" => "img_is_prev_width",
                    "placeholder" => __('Image preview width px', 'wc-fields-factory'),
                    "at_startup" => "show",
                    "translatable" => "no"
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Upload Path', 'wc-fields-factory'),
                "desc" => __('Provide a separate upload path if you want, otherwise files will be uploaded in "wp-upload" folder', 'wc-fields-factory'),
                "type" => "text",
                "param" => "upload_url",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Allowed Size', 'wc-fields-factory'),
                "desc" => __('Maximum allowed size of uploaded file, enter value in kilobytes.!', 'wc-fields-factory'),
                "type" => "text",
                "param" => "max_file_size",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('File', 'wc-fields-factory'),
        "support" => array(
            "wccpf",
            "wccsf",
            "wccrf",
            "wcccf"
        ),
        "document" => "https://sarkware.com/file-upload-field-wc-fields-factory/"
    ),
    "image" => array(
        "meta" => array(
            array(
                "label" => __('Button Text', 'wc-fields-factory'),
                "desc" => __('Enter the upload button label text', 'wc-fields-factory'),
                "type" => "text",
                "param" => "upload_btn_label",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Probe Text', 'wc-fields-factory'),
                "desc" => __('Enter a description ( eg. You haven\'t added an image )', 'wc-fields-factory'),
                "type" => "text",
                "param" => "upload_probe_text",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Media Browser Title', 'wc-fields-factory'),
                "desc" => __('Give a title for the Media Library Browser', 'wc-fields-factory'),
                "type" => "text",
                "param" => "media_browser_title",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Image', 'wc-fields-factory'),
        "support" => array(
            "wccaf"
        ),
        "document" => "https://sarkware.com/image-field-wc-fields-factory/"
    ),
    "url" => array(
        "meta" => array(
            array(
                "label" => __('Tool Tip', 'wc-fields-factory'),
                "desc" => __('Show tooltip info', 'wc-fields-factory'),
                "type" => "text",
                "param" => "tool_tip",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Link Name', 'wc-fields-factory'),
                "desc" => __('Name of link, (The visible part of the link, on which user click to navigate)', 'wc-fields-factory'),
                "type" => "text",
                "param" => "link_name",
                "placeholder" => "",
                "at_startup" => "show",
                "translatable" => "yes"
            ),
            array(
                "label" => __('Display as', 'wc-fields-factory'),
                "desc" => __('Show as button.? or link.?', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "view_in",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "link",
                        "label" => __('Link', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "button",
                        "label" => __('Button', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Open in', 'wc-fields-factory'),
                "desc" => __('Open new tab.? or same tab.?', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "tab_open",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "_blank",
                        "label" => __('New Tab', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "_top",
                        "label" => __('Same Tab', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            ),
            array(
                "label" => __('Show Label', 'wc-fields-factory'),
                "desc" => __('Whether to show or hide the Field\'s Label (Left side)', 'wc-fields-factory'),
                "type" => "radio",
                "param" => "show_label",
                "layout" => "horizontal",
                "options" => array(
                    array(
                        "value" => "yes",
                        "label" => __('Yes', 'wc-fields-factory'),
                        "selected" => true
                    ),
                    array(
                        "value" => "no",
                        "label" => __('No', 'wc-fields-factory'),
                        "selected" => false
                    )
                ),
                "at_startup" => "show",
                "translatable" => "no"
            )
        ),
        "title" => __('Url', 'wc-fields-factory'),
        "support" => array(
            "wccaf"
        ),
        "document" => "https://sarkware.com/url-field-wc-fields-factory/"
    )
);

?>