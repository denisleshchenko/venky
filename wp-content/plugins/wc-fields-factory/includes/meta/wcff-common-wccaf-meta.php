<?php
return array(
    array(
        "label" => __('Read Only', 'wc-fields-factory'),
        "desc" => __('Show this field as readonly on front end product page', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "show_as_read_only",
        "layout" => "vertical",
        "options" => array(
            array(
                "value" => "yes",
                "label" => __('Show as Read Only', 'wc-fields-factory'),
                "selected" => false
            ),
            array(
                "value" => "no",
                "label" => __('Show as Normal', 'wc-fields-factory'),
                "selected" => true
            )
        ),
        "include_if_not" => array(
            "image",
            "url"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Value or Field', 'wc-fields-factory'),
        "desc" => __('Show value instead of field.?<br/>If you choose to show as value, then it won\'t be carried out to Cart -> Checkout -> Order ', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "showin_value",
        "layout" => "horizontal",
        "options" => array(
            array(
                "value" => "yes",
                "label" => __('Value', 'wc-fields-factory'),
                "selected" => false
            ),
            array(
                "value" => "no",
                "label" => __('Field', 'wc-fields-factory'),
                "selected" => true
            )
        ),
        "include_if_not" => array(
            "image",
            "url"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Format', 'wc-fields-factory'),
        "desc" => __('What kind of data will be used on this field.', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "value_type",
        "layout" => "horizontal",
        "options" => array(
            array(
                "value" => "text",
                "label" => __('Text', 'wc-fields-factory'),
                "selected" => true
            ),
            array(
                "value" => "price",
                "label" => __('Price', 'wc-fields-factory'),
                "selected" => false
            ),
            array(
                "value" => "decimal",
                "label" => __('Decimal', 'wc-fields-factory'),
                "selected" => false
            ),
            array(
                "value" => "stock",
                "label" => __('Stock', 'wc-fields-factory'),
                "selected" => false
            ),
            array(
                "value" => "url",
                "label" => __('Url', 'wc-fields-factory'),
                "selected" => false
            )
        ),
        "include_if_not" => array(
            "email",
            "number",
            "textarea",
            "checkbox",
            "radio",
            "select",
            "datepicker",
            "colorpicker",
            "image",
            "url"
        ),
        "at_startup" => "show",
        "translatable" => "no"
    ),
    array(
        "label" => __('Tips', 'wc-fields-factory'),
        "desc" => __('Whether to show tool tip icon or not', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "desc_tip",
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
        "include_if_not" => array(),
        "at_startup" => "show",
        "translatable" => "no"
    ),
    array(
        "label" => __('Description', 'wc-fields-factory'),
        "desc" => __('Description about this field, if user clicked tool tip icon', 'wc-fields-factory'),
        "type" => "textarea",
        "param" => "description",
        "placeholder" => "",
        "rows" => "3",
        "include_if_not" => array(),
        "at_startup" => "show",
        "translatable" => "no"
    )
);

?>