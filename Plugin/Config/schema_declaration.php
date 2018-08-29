<?php
$schema_declaration = [

    [
        /**
         * Key
         * 
         * The identifier of the field
         * The Key is used to generate attributes ID an NAME
         * 
         * @param required
         * @type string
         */
        'key' => 'ppm_field',

        /**
         * Type
         * 
         * Define the type of the HTML field
         * 
         * Possible value :
         * choices, collection, color, date, datetime, email, file, 
         * hidden, month, number, output, password, password_confirm, 
         * range, search, tel, text, textarea, time, url, week, 
         * wysiwyg, year.
         * 
         * @param optional
         * @type string
         * @default 'text'
         */
        'type' => 'text',

        /**
         * Label
         * 
         * Text of the tag <label>
         * 
         * @param optional
         * @type string
         * @default null
         * @field-type: all
         */
        'label' => "Demo text input",

        /**
         * Default
         * 
         * Define the default value
         * 
         * If type is "checkbox" : "default":"on" to check by default
         * 
         * @param optional
         * @type string
         * @default null
         * @field-type: all
         */
        'default' => null,

        /**
         * Helper
         * 
         * Define the field helper text
         * 
         * @param optional
         * @type string
         * @default null
         * @field-type: all
         */
        'helper' => "Demo helper text",

        /**
         * Error messages
         * 
         * Define error message
         * 
         * @param Optional
         * @type string|array
         * @Default null
         */
        // 'error' => "This field is required",
        'messages' => [
            'required'      => "This field is required.",
            'email'         => "This field is not a valid email address.",
            'url'           => "This field is not a valid url.",
            'time'          => "This field is not a valid time.",
            'date'          => "This field is not a valid date.",
            'year'          => "This field is not a valid year.",
            'color'         => "This field is not a valid color.",
            'pattern'       => "This field is not valid.",
            'type'          => "This field is not valid.",
            'size'          => "This file size is not valid.",
            'file_types'    => "This file is not valid.",
            'confirm'       => "Password is not confirmed.",
            'min'           => "This value must not be less than $1.",
            'max'           => "This value must not be greater than $1.",
            'maxlength'     => "This value is too long.",
        ],

        /**
         * Choices
         * 
         * @param Optional
         * @type array
         */
        'choices' => [
            'value' => "Label"
        ],

        /**
         * Attributes
         */
        'attr' => [

            /**
             * ID
             * 
             * @param optional
             * @type string
             * @default null
             * @field-type: all
             */
            'id' => null,

            /**
             * Required
             * 
             * Define if the field is required
             * 
             * @param optional
             * @type boolean
             * @default false
             * @field-type: all
             */
            'required' => true,

            /**
             * Readonly
             * 
             * Define if the field is readonly
             * 
             * @param optional
             * @type boolean
             * @default false
             * @field-type: all
             */
            'readonly' => false,

            /**
             * Disabled
             * 
             * Define if the field is disabled
             * 
             * @param optional
             * @type boolean
             * @default false
             * @field-type: all
             */
            'disabled' => false,

            /**
             * Class
             * 
             * Define the class attribute
             * 
             * @param optional
             * @type string|null
             * @default 'regular-text'
             * @field-type: all
             */
            'class' => null,

            /**
             * Placeholder
             * 
             * Define the plceholder attribute
             * 
             * @param optional
             * @type string|null
             * @default null
             * @field-type: all
             */
            'placeholder' => null,

            /**
             * Max Length
             * 
             * Define the max length attribute
             * 
             * @param optional
             * @type integer|null
             * @default null
             * @field-type: xxx
             */
            'maxlength' => null,

            /**
             * Step
             * 
             * Define the step attribute
             * 
             * @param optional
             * @type integer|float|null
             * @default null
             * @field-type: xxx
             */
            'step' => null,

            /**
             * Max
             * 
             * Define the max attribute
             * 
             * @param optional
             * @type integer|float|null
             * @default null
             * @field-type: xxx
             */
            'max' => null,

            /**
             * Min
             * 
             * Define the min attribute
             * 
             * @param optional
             * @type integer|float|null
             * @default null
             * @field-type: xxx
             */
            'min' => null,

            /**
             * Width
             * 
             * Define the width attribute
             * 
             * @param optional
             * @type integer|float|null
             * @default null
             * @field-type: xxx
             */
            'width' => null,

            /**
             * Cols
             * 
             * define the <textarea> cols attribute
             * 
             * @param optional
             * @type integer|null
             * @default null
             * @field-type: textarea
             */
            'cols' => null,

            /**
             * Rows
             * 
             * define the <textarea> rows attribute
             * 
             * @param optional
             * @type integer|null
             * @default null
             * @field-type: textarea
             */
            'rows' => null,

            /**
             * Multiple
             * 
             * If true <select> is multiple
             * 
             * @param optional
             * @type boolean
             * @default false
             * @field-type: choices
             */
            'multiple' => false,

        ],

        /**
         * Rules
         */
        'rules' => [

            /**
             * Rule
             * 
             * Define a regex
             * 
             * @param optional
             * @type string
             * @default null
             * @field-type: xxx
             */
            'pattern' => null,

            /**
             * Size
             * 
             * --
             * 
             * @param optional
             * @type integer|null
             * @default null
             * @field-type: xxx
             */
            'size' => null,

            /**
             * Allowed Types
             * 
             * List of authorized file type
             * 
             * @param optional
             * @type string|array|null
             * @default null
             * @field-type: file
             */
            'allowed_types' => null,

            /**
             * Confirm
             */
            'confirm' => "paswword_key"
        ],

        /**
         * Algo
         * 
         * If used with a type "password" => A password 
         * algorithm constant. more info at 
         * http://php.net/manual/en/function.password-hash.php
         * 
         * @param Optional
         * @type string|array
         * @default null
         */
        // 'algo' => "PASSWORD_DEFAULT",
        'algo' => [

            /**
             * @param Optional
             * @type string
             */
            'type' => "PASSWORD_DEFAULT",

            /**
             * @param Optional
             * @type string
             */
            'salt' => null,

            /**
             * @param Optional
             * @type integer
             */
            'cost' => null,

            /**
             * @param Optional
             * @type integer
             */
            'memory_cost' => null,

            /**
             * @param Optional
             * @type integer
             */
            'time_cost' => null,

            /**
             * @param Optional
             * @type integer
             */
            'threads' => null

        ],

        /**
         * Expended
         * 
         * -
         * 
         * @param optional
         * @type boolean
         * @default false
         * @field-type: choices
         */
        'expanded' => false,

        /**
         * Preview
         * 
         * if file input have a preview
         * 
         * @param optional
         * @type boolean
         * @default true
         * @field-type: file
         */
        'preview' => true,

        /**
         * Shortcode
         * 
         * If true, a shortcode was automaticaly created for this field.
         * Syntaxe : namespace:post_type:type_key
         * 
         * @param optional
         * @type boolean
         * @default false
         * @field-type: all
         */
        'shortcode' => false,

    ],

    /**
     * ====================
     * SCHEMA FOR DEMO
     * ====================
     */

    // Type
    
    /**
     * Text
     */
    [
        'key' => 'demo_text',
        'type' => 'text',
        'label' => "Text",
        'default' => "Ceci est un Texte",
        'attr' => [
            // 'required' => true
        ],
        'shortcode' => true,
    ],
    
    /**
     * Textarea
     */
    [
        'key' => 'demo_textarea',
        'type' => 'textarea',
        'label' => "Textarea",
        'attr' => [
            // 'required' => true
        ]
    ],
    
    /**
     * Password
     */
    [
        'key' => 'demo_password',
        'type' => 'password',
        'label' => "Password",
        'algo' => [
            'type' => "PASSWORD_BCRYPT",
            'salt' => "azert"
        ]
    ],
    
    /**
     * Password Confirmation
     */
    [
        'key' => 'demo_password_confirm',
        'type' => 'password',
        'label' => "Password Confirmation",
        'rules' => [
            'confirm' => 'demo_password'
        ]
    ],
    
    /**
     * Email
     */
    [
        'key' => 'demo_email',
        'type' => 'email',
        'label' => "Email",
    ],
    
    /**
     * Tel
     */
    [
        'key' => 'demo_tel',
        'type' => 'tel',
        'label' => "Tel",
        'rules' => [
            'pattern' => '/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/'
        ]
    ],
    
    /**
     * URL
     */
    [
        'key' => 'demo_url',
        'type' => 'url',
        'label' => "URL",
    ],
    
    /**
     * Number
     */
    [
        'key' => 'demo_number',
        'type' => 'number',
        'label' => "Number",
    ],
    
    /**
     * Date
     */
    [
        'key' => 'demo_date',
        'type' => 'date',
        'label' => "Date",
    ],
    
    /**
     * Time
     */
    [
        'key' => 'demo_time',
        'type' => 'time',
        'label' => "Time",
    ],
    
    /**
     * Datetime
     */
    [
        'key' => 'demo_datetime',
        'type' => 'datetime',
        'label' => "DateTime",
    ],
    
    /**
     * Month
     */
    [
        'key' => 'demo_month',
        'type' => 'month',
        'label' => "Month",
    ],
    
    /**
     * Week
     */
    [
        'key' => 'demo_week',
        'type' => 'week',
        'label' => "Week",
    ],
    
    /**
     * Search
     */
    [
        'key' => 'demo_search',
        'type' => 'search',
        'label' => "Search",
        'attr' => [
        ]
    ],
    
    /**
     * Range
     */
    [
        'key' => 'demo_range',
        'type' => 'range',
        'label' => "Range",
        'attr' => [
            'min' => 42
        ]
    ],
    
    /**
     * Output
     */
    [
        'key' => 'demo_output',
        'type' => 'output',
        'label' => "Output",
        'value' => "demo_range demo_number",
        'attr' => [
            'readonly' => true
        ]
    ],
    
    /**
     * Color
     */
    [
        'key' => 'demo_color',
        'type' => 'color',
        'label' => "Color",
    ],
    [// Todo
        'key' => 'demo_color_list',
        'type' => 'color',
        'label' => "Color List",
    ],
    
    /**
     * File
     */
    [
        'key' => 'demo_file',
        'type' => 'file',
        'label' => "File",
    ],
    
    /**
     * Checkbox
     */
    [
        'key' => 'demo_checkbox',
        'type' => 'checkbox',
        'label' => "Checkbox",
        'default' => "on"
    ],
    
    /**
     * Hidden
     */
    [
        'key' => 'demo_hidden',
        'type' => 'hidden',
        'label' => "Hidden",
    ],
    
    /**
     * wysiwyg
     */
    [
        'key' => 'demo_wysiwyg',
        'type' => 'wysiwyg',
        'label' => "WYSIWYG",
        'attr' => [
            // 'required' => true,

            'placeholder' => "With placeholder data",
            'cols' => 5,

            'autofocus' => true
        ],
        'shortcode' => true

    ],

    // Options
    
    /**
     * Placeholder
     */
    [
        'key' => 'demo_placeholder',
        'type' => 'text',
        'label' => "Placeholder",
        'attr' => [
            'placeholder' => "With placeholder data"
        ]
    ],
    /**
     * Required
     */
    [
        'key' => 'demo_required',
        'type' => 'text',
        'label' => "Required",
        'attr' => [
            'required' => true
        ]
    ],
    /**
     * Readonly
     */
    [
        'key' => 'demo_readonly',
        'type' => 'text',
        'label' => "Read Only",
        'default' => "Data read only",
        'attr' => [
            'readonly' => true
        ]
    ],
    /**
     * Disabled
     */
    [
        'key' => 'demo_disabled',
        'type' => 'text',
        'label' => "Disabled",
        'default' => "Data disabled",
        'attr' => [
            'disabled' => true
        ]
    ],
    /**
     * With helper
     */
    [
        'key' => 'demo_helper',
        'type' => 'text',
        'label' => "Helper",
        'helper' => "This field have a helper text.",
    ],
    /**
     * With Custom class
     */
    [
        'key' => 'demo_custom_class',
        'type' => 'text',
        'label' => "With Custom Class attribute",
        'attr' => [
            'class' => "this-is-my-custom-class"
        ]
    ],

    // Number Options
    
    /**
     * Number Default
     */
    [
        'key' => 'demo_number_default',
        'type' => 'number',
        'label' => "Number with Default",
        'default' => 42
    ],
    /**
     * Number Min
     */
    [
        'key' => 'demo_number_min',
        'type' => 'number',
        'label' => "Number with Min",
        'attr' => [
            'min' => 42
        ]
    ],
    /**
     * Number Max
     */
    [
        'key' => 'demo_number_max',
        'type' => 'number',
        'label' => "Number with Max",
        'attr' => [
            'max' => 42
        ]
    ],
    /**
     * Number Step
     */
    [
        'key' => 'demo_number_step10',
        'type' => 'number',
        'label' => "Number with Step (10)",
        'attr' => [
            'step' => 10
        ]
    ],
    [
        'key' => 'demo_number_step001',
        'type' => 'number',
        'label' => "Number with Step (0.01)",
        'attr' => [
            'step' => 0.01
        ]
    ],

    // Choices

    /**
     * Select
     */
    [
        'key' => 'demo_choices',
        'type' => 'choices',
        'label' => "Choices Simple",
        'default' => "b",
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
    ],
    /**
     * Select Multiple
     */
    [
        'key' => 'demo_choices_multiple',
        'type' => 'choices',
        'label' => "Choices Multiple",
        // 'default' => "c",
        'default' => ["a", "c"],
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
        'attr' => [
            'multiple' => true
        ]
    ],
    /**
     * Select Expanded
     */
    [
        'key' => 'demo_choices_expanded',
        'type' => 'choices',
        'label' => "Choices Expanded Simple",
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
        'expanded' => true
    ],
    /**
     * Select Expanded Multiple
     */
    [
        'key' => 'demo_choices_expanded_multiple',
        'type' => 'choices',
        'label' => "Choices Expanded Multiple",
        'default' => ["a", "c"],
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
        'expanded' => true,
        'attr' => [
            'multiple' => true
        ]
        ],

    /**
     * Select Expanded Inline
     */
    [
        'key' => 'demo_choices_expanded_inline',
        'type' => 'choices',
        'label' => "Expanded Simple Inline",
        'default' => "c",
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
        'expanded' => true,
        'attr' => [
            'class' => 'inline'
        ]
    ],
    /**
     * Select Expanded Multiple Inline
     */
    [
        'key' => 'demo_choices_expanded_multiple_inline',
        'type' => 'choices',
        'label' => "Expanded Multiple Inline",
        'default' => ["a", "b"],
        'choices' => array(
            'a' => 'Choice A',
            'b' => 'Choice B',
            'c' => 'Choice C',
        ),
        'expanded' => true,
        'attr' => [
            'class' => 'inline',
            'multiple' => true
        ],
        'shortcode' => true
    ],

    // Date & Time
    
    /**
     * Date Year
     */
    [
        'key' => 'demo_year',
        'type' => 'year',
        'label' => "Date year",
        'default' => '1982',
        // 'range' => [date('Y'), date('Y')+2]
        // 'range' => [2000, null],
        'attr' => [
            'size' => 5,
            'multiple' => true,
            'autofocus' => true
        ]
    ],
    
    /**
     * Date with default
     */
    [
        'key' => 'demo_date_with_default',
        'type' => 'date',
        'label' => "Date with default",
        'default' => '1982-03-15'
    ],
    /**
     * Date with default (today)
     */
    [
        'key' => 'demo_date_with_default_today',
        'type' => 'date',
        'label' => "Date with default (today)",
        'default' => 'today'
    ],
    /**
     * Time with default
     */
    [
        'key' => 'demo_time_with_default',
        'type' => 'time',
        'label' => "Time with default",
        'default' => '16:32'
    ],
    /**
     * Time with default
     */
    [
        'key' => 'demo_time_with_default_now',
        'type' => 'time',
        'label' => "Time with default (now)",
        'default' => 'now'
    ],

    // Textarea options
    
    /**
     * Textarea with cols
     */
    [
        'key' => 'demo_textarea_cols',
        'type' => 'textarea',
        'label' => "Textarea with cols",
        'attr' => [
            'cols' => 10
        ]
    ],
    
    /**
     * Textarea with rows
     */
    [
        'key' => 'demo_textarea_rows',
        'type' => 'textarea',
        'label' => "Textarea with rows",
        'attr' => [
            'rows' => 10,
            'placeholder' => "With placeholder data",
            'cols' => 5,
        ]
    ],
    
    /**
     * Textarea with autisize
     */
    [
        'key' => 'demo_textarea_autosize',
        'type' => 'textarea',
        'label' => "Textarea with autosize",
        'attr' => [
            'class' => 'autosize'
        ]
    ],

    // File options
    
    /**
     * File with multiple
     */
    [
        'key' => 'demo_file_multiple',
        'type' => 'file',
        'label' => "File multiple",
        'attr' => [
            'multiple' => true
        ]
    ],
    
    /**
     * File with allowed type
     */
    [
        'key' => 'demo_file_type',
        'type' => 'file',
        'label' => "File (allowed type)",
        'rules' => [
            'allowed_types' => 'image/*, audio/*'
        ]
    ],
    
    /**
     * File with size
     */
    [
        'key' => 'demo_file_size',
        'type' => 'file',
        'label' => "File (size)",
        'rules' => [
            'size' => 200
        ]
    ],
    
    /**
     * File with preview
     */
    [
        'key' => 'demo_file_preview',
        'type' => 'file',
        'label' => "File (preview)",
        'preview' => true
    ],
    
    /**
     * reCaptcha
     */
    [
        'key' => 'demo_recaptcha',
        'type' => 'captcha',
        'rules' => [
            'type' => 'recaptcha',
            'key' => "6LeXR2wUAAAAANE1gXv4mdYpL_ZmnuLGZeXwlH1L",
            'secret' => "6LeXR2wUAAAAADJAVfbf_MSMErIYfqAY0Tv_gHp2",
        ],
        'shortcode' => true
    ],
    
    
    /**
     * Collection
     */
    [
        'key' => 'demo_collection',
        'type' => 'collection',

        /**
         * Label of each item of the collection
         * 
         * use the string {{number}} 
         * use the string {{serial}} 
         */
        'label' => "My default label {{serial}}",
        'helper' => "Collection helper",

        /**
         * Schema of collection
         * 
         * ONLY FOR COLLECTION
         * 
         * @param Required
         * @type array|string
         */
        // 'schema' => "demo_text",
        'schema' => [
            "demo_text",
            "demo_textarea",
            // "demo_collection_2",
            "demo_checkbox",
            // "demo_choices_expanded_inline",
            // "demo_wysiwyg"
        ],
        // 'loop' => 1,

        'rules' => [
            // 'label' => "My default label {{number}}",

            /**
             * Init
             * 
             * ONLY FOR COLLECTION
             * 
             * Number of items whene the plugin is loaded
             * "0" for init with no loop
             * 
             * @param Optional
             * @type integer
             * @default 1
             */
            // 'init' => 2,
            // 'init' => 0,
        ]
    ],

    // [
    //     'key' => 'demo_collection_2',
    //     'type' => 'collection',
    //     'label' => "My second collection {{number}}",
    //     'schema' => [
    //         "demo_password",
            
    //         "demo_collection_4"
    //     ]
    // ],

    // [
    //     'key' => 'demo_collection_3',
    //     'type' => 'collection',
    //     'label' => "My second collection {{number}}",
    //     'schema' => [
    //         "demo_year",
    //         // "demo_collection_2"
    //     ]
    // ],
];