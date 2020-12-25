<?php
return array(
    array(
        "label" => __('Required', 'wc-fields-factory'),
        "desc" => __('Is this field Mandatory.?', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "required",
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
        "include_if_not" => array(
            "hidden",
            "label",
            "url"
        ),
        "at_startup" => "show",
        "translatable" => "no"
    ),
    array(
        "label" => __('Message', 'wc-fields-factory'),
        "desc" => __('Message to display whenever the validation failed for this field', 'wc-fields-factory'),
        "type" => "text",
        "param" => "message",
        "placeholder" => "",
        "include_if_not" => array(
            "hidden",
            "label",
            "url"
        ),
        "at_startup" => "show",
        "translatable" => "yes"
    ),
    array(
        "label" => __('Show on Cart & Checkout', 'wc-fields-factory'),
        "desc" => __('Whether to show this custom field ( the value ) on Cart & Checkout page', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "visibility",
        "layout" => "vertical",
        "options" => array(
            array(
                "value" => "yes",
                "label" => __('Show in Cart & Checkout Page', 'wc-fields-factory'),
                "selected" => true
            ),
            array(
                "value" => "no",
                "label" => __('Hide in Cart & Checkout Page', 'wc-fields-factory'),
                "selected" => false
            )
        ),
        "include_if_not" => array(
            "label",
            "image",
            "url"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Order Item Meta', 'wc-fields-factory'),
        "desc" => __('Whether to add this custom field to Order & Email', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "order_meta",
        "layout" => "vertical",
        "options" => array(
            array(
                "value" => "yes",
                "label" => __('Add as Order Meta', 'wc-fields-factory'),
                "selected" => true
            ),
            array(
                "value" => "no",
                "label" => __('Do not add', 'wc-fields-factory'),
                "selected" => false
            )
        ),
        "include_if_not" => array(
            "label",
            "image",
            "url"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Logged in Users Only', 'wc-fields-factory'),
        "desc" => __('Show this field only if user has logged-in', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "login_user_field",
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
        "include_if_not" => array(
            "hidden",
            "image"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Editable', 'wc-fields-factory'),
        "desc" => __('Make this field editable ( Updatable ) on cart', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "cart_editable",
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
        "include_if_not" => array(
            "hidden",
            "label",
            "image",
            "url"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Cloneable', 'wc-fields-factory'),
        "desc" => __('Whether to allow this field to be cloned.?<br/>(Works only if cloning option is enabled in the settings)', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "cloneable",
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
        "include_if_not" => array(
            "hidden",
            "image"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    ),
    array(
        "label" => __('Field Class', 'wc-fields-factory'),
        "desc" => __('Add custom css class to this field', 'wc-fields-factory'),
        "type" => "text",
        "param" => "field_class",
        "placeholder" => "CSS Class Name",
        "include_if_not" => array(
            "hidden",
            "image"
        ),
        "at_startup" => "show",
        "translatable" => "no"
    ),
    array(
        "label" => __('Onload', 'wc-fields-factory'),
        "desc" => __('Show it on initial load.', 'wc-fields-factory'),
        "type" => "radio",
        "param" => "initial_show",
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
        "include_if_not" => array(
            "hidden",
            "image"
        ),
        "at_startup" => "hide",
        "translatable" => "no"
    )
)?>
