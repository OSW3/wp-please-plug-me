<?php
$config = array(

    // 'namespace' => 'please_plug_me_namespace',

    /**
     * Assets for the Front
     */
    'assets' => array(
        'styles' => array(
            array(
                'name' => 'my-custom-front-style',
                'path' => 'my-custom-front',
            ),
        ),
        'scripts' => array(
            array(
                'name' => 'my-custom-front-script',
                'path' => 'my-custom-front',
            ),
        ),
    ),

    /**
     * Assets for the Admin
     */
    'assets_admin' => array(
        'styles' => array(
            array(
                'name' => 'my-custom-admin-style',
                'path' => 'my-custom-admin',
            ),
        ),
        'scripts' => array(
            array(
                'name' => 'my-custom-admin-script',
                'path' => 'my-custom-admin',
            ),
        ),
    ),

    /**
     * Options data
     */
    // 'options' => array(
    //     'option_name' => 'Option value',
    // ),

    /**
     * The register
     */
    'register' => array(
        'settings' => array(
            'menus' => array(

                'admin' => true,
                'action' => true,
                'settings' => true,

                // '_icon' => 'dashicons-email',
                'icon' => 'image:ppm.png',

                '_submenus' => array(),
            ),
            '_view' => true,
            'thumbnails' => array(
                '_strict' => true,
                'preserve_wp_sizes' => true,
                'sizes' => array(
                    array(
                        'name' => 'settings-primary',
                        'width' => 800,
                        'height' => 40,
                        '_crop' => true,
                    ),
                    array(
                        'name' => 'settings-secondary',
                        'width' => 400,
                        'height' => 270,
                        'crop' => true,
                    ),
                    array(
                        'name' => 'settings-preview',
                        'width' => 350,
                        'height' => 250,
                        'crop' => true,
                    ),
                ),
            ),
            'schema' => array(
                array(
                    'title' => 'Text fields demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_text',
                            'label' => 'Demo Text field',
                            'type' => 'text',
                            'helper' => 'The helper text for Text field.',
                            'default' => 'Default value',
                        ),
                        array(
                            'key' => 'demo_text_required',
                            'label' => 'Demo Text field (required)',
                            'type' => 'text',
                            'required' => true,
                        ),
                        array(
                            'key' => 'demo_text_readonly',
                            'label' => 'Demo Text field (readonly)',
                            'type' => 'text',
                            'default' => 'Default value',
                            'readonly' => true,
                        ),
                        array(
                            'key' => 'demo_text_disabled',
                            'label' => 'Demo Text field (disabled)',
                            'type' => 'text',
                            'default' => 'Default value',
                            'disabled' => true,
                        ),
                        array(
                            'key' => 'demo_text_maxlength',
                            'label' => 'Demo Text field (maxlength : 5)',
                            'type' => 'text',
                            'maxlength' => 5,
                        ),
                        array(
                            'key' => 'demo_text_customclass',
                            'label' => 'Demo Text field (with cusom class)',
                            'type' => 'text',
                            'class' => 'my-custom-class',
                        ),
                        array(
                            'label' => 'Demo WRONG field',
                            'type' => 'text',
                            'helper' => 'This field not appear because it has no key.',
                        ),
                    ),
                ),
                array(
                    'title' => 'Number field demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_number',
                            'label' => 'Demo Number field',
                            'type' => 'number',
                            'default' => 42,
                        ),
                        array(
                            'key' => 'demo_number_step',
                            'label' => 'Demo Number field (with step)',
                            'type' => 'number',
                            'default' => 42,
                            'step' => 0.01,
                        ),
                        array(
                            'key' => 'demo_number_min_max',
                            'label' => 'Demo Number field (with min & max)',
                            'type' => 'number',
                            'default' => 42,
                            'min' => 40,
                            'max' => 45,
                        ),
                    ),
                ),
                array(
                    'title' => 'Type field demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_tel',
                            'label' => 'Demo Tel field',
                            'type' => 'tel',
                            'default' => 'Default value',
                            'rule' => '/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/',
                        ),
                        array(
                            'key' => 'demo_url',
                            'label' => 'Demo url field',
                            'type' => 'url',
                            'default' => 'Default value',
                        ),
                        array(
                            'key' => 'demo_email',
                            'label' => 'Demo email field',
                            'type' => 'email',
                            'default' => 'Default value',
                        ),
                        array(
                            'key' => 'demo_date',
                            'label' => 'Demo date field',
                            'type' => 'date',
                        ),
                        array(
                            'key' => 'demo_date_min_max',
                            'label' => 'Demo date field (Min & Max)',
                            'type' => 'date',
                            'min' => '1982-03-15',
                            'max' => 'today',
                        ),
                        array(
                            'key' => 'demo_date_with_default',
                            'label' => 'Demo date field (with default)',
                            'type' => 'date',
                            'default' => '1982-03-15',
                        ),
                        array(
                            'key' => 'demo_date_with_default_today',
                            'label' => 'Demo date field (with default today)',
                            'type' => 'date',
                            'default' => 'today',
                        ),
                        array(
                            'key' => 'demo_time',
                            'label' => 'Demo time field',
                            'type' => 'time',
                        ),
                        array(
                            'key' => 'demo_time_with_default',
                            'label' => 'Demo time field (with default)',
                            'type' => 'time',
                            'default' => '16:30',
                        ),
                        array(
                            'key' => 'demo_datetime',
                            'label' => 'Demo datetime field',
                            'type' => 'datetime',
                        ),
                        array(
                            'key' => 'demo_month',
                            'label' => 'Demo month field',
                            'type' => 'month',
                        ),
                        array(
                            'key' => 'demo_week',
                            'label' => 'Demo week field',
                            'type' => 'week',
                        ),
                        array(
                            'key' => 'demo_range',
                            'label' => 'Demo range field',
                            'type' => 'range',
                            'default' => 25,
                            'min' => 0,
                            'max' => 100,
                            'step' => 5,
                        ),
                        array(
                            'key' => 'demo_color',
                            'label' => 'Demo color field',
                            'type' => 'color',
                        ),
                        array(
                            'key' => 'demo_password',
                            'label' => 'Demo password field',
                            'type' => 'password',
                        ),
                        array(
                            'key' => 'demo_search',
                            'label' => 'Demo search field',
                            'type' => 'search',
                        ),
                    ),
                ),
                array(
                    'title' => 'Choices field demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_choices_select',
                            'label' => 'Demo Choices (as select)',
                            'type' => 'choices',
                            'default' => 'b',
                            'choices' => array(
                                'a' => 'Choice A',
                                'b' => 'Choice B',
                                'c' => 'Choice C',
                            ),
                            'expanded' => false,
                            'multiple' => false,
                        ),
                        array(
                            'key' => 'demo_choices_select_multiple',
                            'label' => 'Demo Choices (as select multiple)',
                            'type' => 'choices',
                            'choices' => array(
                                'a' => 'Choice A',
                                'b' => 'Choice B',
                                'c' => 'Choice C',
                            ),
                            'expanded' => false,
                            'multiple' => true,
                        ),
                        array(
                            'key' => 'demo_choices_radio',
                            'label' => 'Demo Choices (as radio)',
                            'type' => 'choices',
                            'choices' => array(
                                'a' => 'Choice A',
                                'b' => 'Choice B',
                                'c' => 'Choice C',
                            ),
                            'expanded' => true,
                            'multiple' => false,
                        ),
                        array(
                            'key' => 'demo_choices_checkbox',
                            'label' => 'Demo Choices (as checkbox)',
                            'type' => 'choices',
                            'choices' => array(
                                'a' => 'Choice A',
                                'b' => 'Choice B',
                                'c' => 'Choice C',
                            ),
                            'expanded' => true,
                            'multiple' => true,
                        ),
                        array(
                            'key' => 'demo_simple_checkbox_checked',
                            'label' => 'Demo Simple Checkbox (checked)',
                            'type' => 'checkbox',
                            'default' => 'on',
                        ),
                        array(
                            'key' => 'demo_simple_checkbox_unchecked',
                            'label' => 'Demo Simple Checkbox (unchecked)',
                            'type' => 'checkbox',
                            '_default' => 'off',
                        ),
                    ),
                ),
                array(
                    'title' => 'Textarea field demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_textatrea',
                            'label' => 'Demo textarea',
                            'type' => 'textarea',
                            'default' => 'Default value',
                        ),
                        array(
                            'key' => 'demo_textatrea_with_cols',
                            'label' => 'Demo textarea (with cols)',
                            'type' => 'textarea',
                            'cols' => 30,
                        ),
                        array(
                            'key' => 'demo_textatrea_with_rows',
                            'label' => 'Demo textarea (with rows)',
                            'type' => 'textarea',
                            'rows' => 6,
                        ),
                    ),
                ),
                array(
                    'title' => 'File field demo',
                    'description' => 'Description of the schema section.',
                    'schema' => array(
                        array(
                            'key' => 'demo_file',
                            'label' => 'Demo file',
                            'type' => 'file',
                        ),
                        array(
                            'key' => 'demo_file_multiple',
                            'label' => 'Demo file Multiple',
                            'type' => 'file',
                            '_size' => 12300,
                            'multiple' => true,
                        ),
                    ),
                ),
            ),
        ),

        'posts' => array(
            array(
                'type' => 'wpppm_custom_post',
                'label' => 'WPPPM Custom Post',
                'description' => 'My custom post short description',
                '_hierarchical' => false,
                '_public' => true,
                '_publicly_queryable' => false,
                '_exclude_from_search' => false,
                '_show_ui' => false,
                '_show_in_menu' => true,
                '_show_in_nav_menus' => true,
                '_show_in_admin_bar' => true,
                '_show_in_rest' => true,
                '_rest_base' => 'my_custom_rest_base',
                '_rest_controller_class' => 'OSW3',
                '_menu_position' => 0,
                '_menu_icon' => 'image:16x16.png',
                'capability_type' => 'page',
                '_capabilities' => array(
                    'create_posts' => 'do_not_allow',
                ),
                '_map_meta_cap' => false,
                'supports' => array(
                    'title',
                    'editor',
                ),
                '_register_meta_box_cb' => 'OSW3_WP_PluginBoilerplate_registerMetaBoxCallback',
                'labels' => array(
                    'all_items' => 'All items',
                    '_add_new' => '(1) Add new button label',
                    '_search_items' => '(2) Search button label',
                    '_not_found' => '(3) No post found text',
                    '_add_new_item' => '(4)Add new item title',
                    '_edit_item' => '(5) Admin bar edit item',
                    '_singular_name' => '(6) Admin bar singular name (create new)',
                    '_not_found_in_trash' => '(7) not found in trash',
                    '_attributes' => '(9) Attributes box title',
                    '_featured_image' => '(10) Feat image box title',
                    '_set_featured_image' => '(11) set feat image link',
                    '_insert_into_item' => '(12) Add to item button media modal',
                    '_view_item' => '(13) View item > admin bar > page article',
                    '_view_items' => '(14) View items > admin bar > page article',
                    'view_items' => false,
                ),
                'has_archive' => true,
                'can_export' => false,
                'delete_with_user' => null,
                'rewrite' => array(
                    'slug' => 'do:my_custom_post',
                    '_with_front' => false,
                    '_feeds' => true,
                    '_pages' => true,
                ),
                'category' => array(
                    'labels' => array(
                        'name' => 'Categories',
                        '_add_new_item' => '(1) Add new item title',
                        '_search_items' => '(2) Search button label',
                        '_not_found' => '(3) No item found',
                        '_parent_item' => '(4) Parent categ',
                        '_edit_item' => '(5) Edit Item',
                        '_view_item' => '(6) View item > admin bar',
                        '_most_used' => '(7) Most used tab dans la page de gestion des menus',
                        '_all_items' => '(8) all items tab in create article form',
                        '__singular_name' => 'SINGULAR_NAME',
                        '__popular_items' => 'POPULAR_ITEMS',
                        '__parent_item_colo' => 'PARENT_ITEM_COLO',
                        '__new_item_name' => 'NEW_ITEM_NAME',
                        '__separate_items_with_commas' => 'SEPARATE_ITEMS_WITH_COMMAS',
                        '__add_or_remove_items' => 'ADD_OR_REMOVE_ITEMS',
                        '__choose_from_most_used' => 'CHOOSE_FROM_MOST_USED',
                        '__no_terms' => 'NO_TERMS',
                        '__back_to_items' => 'BACK_TO_ITEMS',
                    ),
                    'rel_objects' => array(
                        'post',
                        'truc',
                    ),
                    'public' => null,
                    'rewrite' => array(
                        'slug' => 'azertyu',
                        '_with_front' => '',
                        '_hierarchical' => '',
                        '_ep_mask' => '',
                    ),
                    'show_admin_column' => true,
                ),
                'tag' => array(
                    'labels' => array(
                        'name' => 'Tags',
                    ),
                    'rel_objects' => array(
                        'post',
                        'truc',
                    ),
                    '_public' => true,
                    'rewrite' => true,
                    '_show_admin_column' => true,
                ),
                'admin_columns' => array(
                    array(
                        'label' => 'My custom field',
                        '_data' => array(
                            'glue',
                            'field A',
                            'field B',
                        ),
                        'data' => array(
                            'at',
                            'date',
                            'time',
                        ),
                        'sortable' => true,
                    ),
                    array(
                        'key' => 'date',
                        'public' => false,
                    ),
                    array(
                        'key' => 'title',
                        'public' => true,
                    ),
                ),
                'show_admin_permalink' => false,
                '_novalidate' => false,
                '_view' => true,
                'thumbnails' => array(
                    'strict' => true,
                    'preserve_wp_sizes' => false,
                    'sizes' => array(
                        array(
                            'name' => 'my-custom-primary',
                            'width' => 800,
                            'height' => 540,
                            'crop' => true,
                        ),
                        array(
                            'name' => 'my-custom-secondary',
                            'width' => 400,
                            'height' => 270,
                            'crop' => true,
                        ),
                        array(
                            'name' => 'my-custom-preview',
                            'width' => 350,
                            'height' => 250,
                            'crop' => true,
                        ),
                    ),
                ),
                '_thumbnails' => array(
                    array(
                        'name' => 'my-custom-primary',
                        'width' => 800,
                        'height' => 540,
                        'crop' => true,
                    ),
                    array(
                        'name' => 'my-custom-secondary',
                        'width' => 400,
                        'height' => 270,
                        'crop' => true,
                    ),
                    array(
                        'name' => 'my-custom-preview',
                        'width' => 350,
                        'height' => 250,
                        'crop' => true,
                    ),
                ),
                'metas' => array(
                    array(
                        'title' => 'Text fields demo',
                        '_view' => 'view-test',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_text',
                                'label' => 'Demo Text field',
                                'type' => 'text',
                                'helper' => 'The helper text for Text field.',
                                'default' => 'Default value',
                            ),
                            array(
                                'key' => 'demo_text_required',
                                'label' => 'Demo Text field (required)',
                                'type' => 'text',
                                'required' => true,
                            ),
                            array(
                                'key' => 'demo_text_readonly',
                                'label' => 'Demo Text field (readonly)',
                                'type' => 'text',
                                'default' => 'Default value',
                                'readonly' => true,
                            ),
                            array(
                                'key' => 'demo_text_disabled',
                                'label' => 'Demo Text field (disabled)',
                                'type' => 'text',
                                'default' => 'Default value',
                                'disabled' => true,
                            ),
                            array(
                                'key' => 'demo_text_customclass',
                                'label' => 'Demo Text field (with cusom class)',
                                'type' => 'text',
                                'class' => 'my-custom-class',
                            ),
                            array(
                                'label' => 'Demo WRONG field',
                                'type' => 'text',
                                'helper' => 'This field not appear because it has no key.',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Number fields demo',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_number',
                                'label' => 'Demo Number field',
                                'type' => 'number',
                                'default' => 42,
                            ),
                            array(
                                'key' => 'demo_number_step',
                                'label' => 'Demo Number field (with step)',
                                'type' => 'number',
                                'default' => 42,
                                'step' => 0.01,
                            ),
                            array(
                                'key' => 'demo_number_min_max',
                                'label' => 'Demo Number field (with min & max)',
                                'type' => 'number',
                                'default' => 42,
                                'min' => 40,
                                'max' => 45,
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Type fields demo',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_tel',
                                'label' => 'Demo Tel field',
                                'type' => 'tel',
                                'default' => 'Default value',
                                'rule' => '/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/',
                            ),
                            array(
                                'key' => 'demo_url',
                                'label' => 'Demo url field',
                                'type' => 'url',
                                'default' => 'Default value',
                            ),
                            array(
                                'key' => 'demo_email',
                                'label' => 'Demo email field',
                                'type' => 'email',
                                'default' => 'Default value',
                            ),
                            array(
                                'key' => 'demo_date',
                                'label' => 'Demo date field',
                                'type' => 'date',
                            ),
                            array(
                                'key' => 'demo_date_with_default',
                                'label' => 'Demo date field (with default)',
                                'type' => 'date',
                                'default' => '1982-03-15',
                            ),
                            array(
                                'key' => 'demo_date_with_default_today',
                                'label' => 'Demo date field (with default today)',
                                'type' => 'date',
                                'default' => 'today',
                            ),
                            array(
                                'key' => 'demo_time',
                                'label' => 'Demo time field',
                                'type' => 'time',
                            ),
                            array(
                                'key' => 'demo_time_with_default',
                                'label' => 'Demo time field (with default)',
                                'type' => 'time',
                                'default' => '16:30',
                            ),
                            array(
                                'key' => 'demo_datetime',
                                'label' => 'Demo datetime field',
                                'type' => 'datetime',
                            ),
                            array(
                                'key' => 'demo_month',
                                'label' => 'Demo month field',
                                'type' => 'month',
                            ),
                            array(
                                'key' => 'demo_week',
                                'label' => 'Demo week field',
                                'type' => 'week',
                            ),
                            array(
                                'key' => 'demo_range',
                                'label' => 'Demo range field',
                                'type' => 'range',
                                'default' => 25,
                                'min' => 0,
                                'max' => 100,
                                'step' => 5,
                            ),
                            array(
                                'key' => 'demo_color',
                                'label' => 'Demo color field',
                                'type' => 'color',
                            ),
                            array(
                                'key' => 'demo_password',
                                'label' => 'Demo password field',
                                'type' => 'password',
                            ),
                            array(
                                'key' => 'demo_search',
                                'label' => 'Demo search field',
                                'type' => 'search',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Choices fields demo',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_choices_select',
                                'label' => 'Demo Choices (as select)',
                                'type' => 'choices',
                                'default' => 'b',
                                'choices' => array(
                                    'a' => 'Choice A',
                                    'b' => 'Choice B',
                                    'c' => 'Choice C',
                                ),
                                'expanded' => false,
                                'multiple' => false,
                            ),
                            array(
                                'key' => 'demo_choices_select_multiple',
                                'label' => 'Demo Choices (as select multiple)',
                                'type' => 'choices',
                                'choices' => array(
                                    'a' => 'Choice A',
                                    'b' => 'Choice B',
                                    'c' => 'Choice C',
                                ),
                                'expanded' => false,
                                'multiple' => true,
                            ),
                            array(
                                'key' => 'demo_choices_radio',
                                'label' => 'Demo Choices (as radio)',
                                'type' => 'choices',
                                'choices' => array(
                                    'a' => 'Choice A',
                                    'b' => 'Choice B',
                                    'c' => 'Choice C',
                                ),
                                'expanded' => true,
                                'multiple' => false,
                            ),
                            array(
                                'key' => 'demo_choices_checkbox',
                                'label' => 'Demo Choices (as checkbox)',
                                'type' => 'choices',
                                'choices' => array(
                                    'a' => 'Choice A',
                                    'b' => 'Choice B',
                                    'c' => 'Choice C',
                                ),
                                'expanded' => true,
                                'multiple' => true,
                            ),
                            array(
                                'key' => 'demo_simple_checkbox_checked',
                                'label' => 'Demo Simple Checkbox (checked)',
                                'type' => 'checkbox',
                                'default' => 'on',
                            ),
                            array(
                                'key' => 'demo_simple_checkbox_unchecked',
                                'label' => 'Demo Simple Checkbox (unchecked)',
                                'type' => 'checkbox',
                                '_default' => 'off',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Textarea fields demo',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_textatrea',
                                'label' => 'Demo textarea',
                                'type' => 'textarea',
                                'default' => 'Default value',
                            ),
                            array(
                                'key' => 'demo_textatrea_with_cols',
                                'label' => 'Demo textarea (with cols)',
                                'type' => 'textarea',
                                'cols' => 30,
                            ),
                            array(
                                'key' => 'demo_textatrea_with_rows',
                                'label' => 'Demo textarea (with rows)',
                                'type' => 'textarea',
                                'rows' => 6,
                            ),
                        ),
                    ),
                    array(
                        'title' => 'File fields demo',
                        '_context' => '',
                        '_priority' => '',
                        '_callback_args' => '',
                        'schema' => array(
                            array(
                                'key' => 'demo_file',
                                'label' => 'Demo file',
                                'type' => 'file',
                                '_size' => 20,
                                '_allowed_types' => array(
                                    'image/*',
                                    'audio/*',
                                ),
                                'allowed_types' => 'image/*, audio/*',
                                '_preview' => false,
                            ),
                            array(
                                'key' => 'demo_file_multiple',
                                'label' => 'Demo file multiple',
                                'type' => 'file',
                                '_size' => 20,
                                '_allowed_types' => array(
                                    'image/*',
                                    'audio/*',
                                ),
                                'allowed_types' => 'image/*, audio/*',
                                'multiple' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'hooks' => array(
        'OSW3_SiteLocker' => 'wp',
    ),

    'shortcodes' => array(
        'WP_PleasePlugMe_Shorcode_Exemple' => 'WP_PleasePlugMe_Shorcode_Exemple',
    ),
);
