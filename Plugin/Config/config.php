<?php

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

include_once "schema_declaration.php";

$config = [

    /**
     * Environment
     * 
     * @param optional
     * @type string "production" || "development" || "auto"
     * 
     * define the execution environment, Default is "auto"
     * Set "auto" to define query from localhost as "development"
     */
    // 'environment' => 'production',
    'environment' => 'auto',

    /**
     * Namespace
     * 
     * @param optional
     * @type string or null
     * 
     * You can define the namespace of your plugin here
     * If this parameter is NULL or not defined, PPM will generate automatically 
     * the namespace by the slug of your plugin name (defined on /index.php).
     * 
     * Ex: Please Plug Me -> please_plug_me
     * Ex: Please-Plug-Me -> please_plug_me
     */
    'namespace' => null,

    /**
     * Assets
     * 
     * @param optional
     * @type array
     * 
     * Place your assets in the /Plugin/Public/assets/ directory
     * 
     * Define the list of assets the plugin needs for :
     * - the front side ['assets']['frontend'] 
     * - wp-admin side ['assets']['admin']
     * 
     * It contains lists of 
     * - "Styles" for the CSS declaration
     * - "Scripts" for the JavaScript declaration
     * 
     * asset parameters : 
     * @param string $handle Required. Name of the style/script
     * @param string $src Required. Name of local file or fule URL of CDN
     * @param string $version Optional. Specify the style/script version number
     * @param array $dependencies Optional. Array of registered style/script handles this style/script depends on.
     * @param bool $in_header Optional. Specified if the script is loaded in <head>.
     */
    'assets' => [

        /**
         * Assets for Front side ONLY
         * 
         * @param optional
         * @type array
         */
       'frontend' => [

            /**
             * Styles for FrontEnd
             * 
             * @param optional
             * @type array
             */
           'styles' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the stylsheet
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-style",

               /**
                * Src
                * 
                * Determine the uri of the stylesheet
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the stylsheet
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the stylsheet
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * Enqueue
                * 
                * If true, the stylesheet will be enqueued
                * If false, the stylesheet will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true

           ]],

           /**
            * Script for FrontEnd
            * 
            * @param optional
            * @type array
            */
           'scripts' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the script
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-script",

               /**
                * Src
                * 
                * Determine the uri of the script
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the script
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the script
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * In Header
                * 
                * If true, the script will be loaded in the <head>
                * 
                * @param optional
                * @type boolean
                * @default false
                */
               'in_header' => false,

               /**
                * Enqueue
                * 
                * If true, the script will be enqueued
                * If false, the script will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true

           ]],
       ],

       /**
        * Assets for Admin side ONLY
        * 
        * @param optional
        * @type array
        */
       'admin' => [

            /**
             * Styles for Admin
             * 
             * @param optional
             * @type array
             */
           'styles' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the stylsheet
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-style",

               /**
                * Src
                * 
                * Determine the uri of the styleshhet
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the stylsheet
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the stylsheet
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * Enqueue
                * 
                * If true, the stylesheet will be enqueued
                * If false, the stylesheet will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true
               
           ]],

           /**
            * Script for Admin
            * 
            * @param optional
            * @type array
            */
           'scripts' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the script
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-script",

               /**
                * Src
                * 
                * Determine the uri of the script
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the script
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the script
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * In Header
                * 
                * If true, the script will be loaded in the <head>
                * 
                * @param optional
                * @type boolean
                * @default false
                */
               'in_header' => false,

               /**
                * Enqueue
                * 
                * If true, the script will be enqueued
                * If false, the script will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true

           ]],
       ],

       /**
        * Assets for Front and Admin
        * 
        * @param optional
        * @type array
        */
       'both' => [

            /**
             * Styles for FrontEnd and Admin
             * 
             * @param optional
             * @type array
             */
           'styles' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the stylsheet
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-style",

               /**
                * Src
                * 
                * Determine the uri of the styleshhet
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the stylsheet
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the stylsheet
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * Enqueue
                * 
                * If true, the stylesheet will be enqueued
                * If false, the stylesheet will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true
               
           ]],

           /**
            * Script for FrontEnd and Admin
            * 
            * @param optional
            * @type array
            */
           'scripts' => [[

                /**
                 * Handle
                 * 
                 * Determine the ID of the script
                 * 
                 * @param required
                 * @type string
                 */
               'handle' => "frontend-script",

               /**
                * Src
                * 
                * Determine the uri of the script
                * 
                * @param required
                * @type string
                */
               'src' => null,

               /**
                * Version
                * 
                * Determine the version of the script
                * 
                * @param optional
                * @type string
                */
               'version' => null,

               /**
                * Dependencies
                * 
                * Determine the list of dependencies (handle) of the script
                * 
                * @param optional
                * @type array
                */
               'dependencies' => [],

               /**
                * In Header
                * 
                * If true, the script will be loaded in the <head>
                * 
                * @param optional
                * @type boolean
                * @default false
                */
               'in_header' => false,

               /**
                * Enqueue
                * 
                * If true, the script will be enqueued
                * If false, the script will be registred only
                * 
                * @param optional
                * @type boolean
                * @default true
                */
               'enqueue' => true

           ]],
       ]
    ],

    /**
     * Options data
     * 
     * @param Optional
     * @type Array
     * 
     * Set some data for the plugin 
     * These data will be stored in the table wp_options when the plugin 
     * will activated
     */
    'options' => [
        'option_name' => 'Option value',
    ],

    /**
     * Filters
     * 
     * @param Optional
     * @type Array
     * 
     * Define the list of Filters for the plugin.
     * Each needs a pair of "Filter Function Name" and "trigger".
     * 
     * 'filters' => [
     *      'PPM_Filter_Exemple' => 'wp',
     * ],
     */
    'filters' => [
        'PPM_Filter_Exemple' => 'wp',
    ],

    /**
     * Hooks
     * 
     * @param Optional
     * @type Array
     * 
     * Define the list of Hooks for the plugin.
     * Each needs a pair of "Hook Function Name" and "trigger".
     * 
     * 'hooks' => [
     *      'PPM_Hook_Exemple' => 'wp',
     * ],
     */
    'hooks' => [
        'PPM_Hook_Exemple' => 'wp',
    ],

    /**
     * Shortcodes
     * 
     * @param Optional
     * @type Array
     * 
     * Define the list of Shortcodes of the plugin.
     * Each needs a pair of "Shortcode Identifiant" and "Shortcode Function 
     * Name".
     * 
     * 'shortcodes' => [
     *      'PPM_Shorcode_Exemple_Code' => 'PPM_Shorcode_Exemple_Function',
     * ]
     */
    'shortcodes' => [
        'PPM_Shorcode_Exemple_Function' => 'PPM_Shorcode_Exemple_Code',
    ],

    /**
     * Settings
     * 
     * @param Optional
     * @type Array
     */
    'settings' => [

        /**
         * Menus
         * 
         * @param Optional
         * @type Array
         */
        'menus' => [

            /**
             * Admin
             * 
             * Show the Setting link in the main menu (sidebar)
             * 
             * @param Optional
             * @type Boolean|array
             * @default false
             * 
             * 'admin' => false/true,
             * 'admin' => [
             *  'display' => true/false,
             *  'position' => 42
             * ],
             */
            // 'admin' => false,
            'admin' => [

                /**
                 * 
                 */
                'display' => false,
                'position' => null,
                'label' => null,

                /**
                 * Icon
                 * 
                 * Define the file of the Admin Menu icon
                 * 
                 * @param Optional
                 * @type string
                 * @default null
                 * @exemple for dashicon : 
                 * - 'dashicons-email'
                 * @exemple for image in /Public/assets/images/ directory :
                 * - 'image:ppm.png'
                 */
                'icon' => null
            ],

            /**
             * Action
             * 
             * Show the Setting link in the action menu (under the name of the plugin)
             * 
             * @param Optional
             * @type Boolean
             * @default false
             */
            'action' => false,

            /**
             * Settings Tab
             * 
             * Show the Setting link in the submenu of the Settings Tab
             * 
             * @param Optional
             * @type Boolean
             * @default true
             */
            'settingTag' => true

        ],

    ],

    /**
     * Posts
     * 
     * List of posts
     * 
     * @param Optional
     * @type Array
     */
    'posts' => [
        [

            /**
             * Type
             * 
             * Define the identifier of the custom post
             * 
             * @param Required
             * @type String
             * @max-size 20 chars
             */
            'type' => "ppm_custom_post",

            /**
             * Name
             * 
             * Define the Name of the custom post
             * 
             * @param Required
             * @type String
             */
            'name' => "My PPM Custom Post",

            /**
             * Description
             * 
             * A short descriptive summary of what the post type is.
             * 
             * @param Optional
             * @type string
             * @default ''
             */
            'description' => "A short descriptive summary of what the post type is.",

            /**
             * Is public
             * 
             * Whether a post type is intended for use publicly either via the 
             * admin interface or by front-end users. While the default settings
             * of $exclude_from_search, $publicly_queryable, $show_ui, and 
             * $show_in_nav_menus are inherited from public, each does not rely 
             * on this relationship and controls a very specific intention.
             * 
             * @param Optional
             * @type Boolean
             * @default true
             * @wp-default false
             */
            'public' => true,

            /**
             * Is Hierarchical
             * 
             * Whether the post type is hierarchical (e.g. page). 
             * 
             * @param Optional
             * @type Boolean
             * @default false
             */
            'hierarchical' => false,

            /**
             * Has Archive
             * 
             * Whether there should be post type archives, or if a string, 
             * the archive slug to use. Will generate the proper rewrite rules 
             * if $rewrite is enabled.
             * 
             * @param Optional
             * @type Boolean|String
             * @default false
             */
            'has_archive' => false,

            /**
             * Exportable
             * 
             * Whether to allow this post type to be exported
             * 
             * @param Optional
             * @type Boolean
             * @default true
             */
            'exportable' => true,

            /**
             * Capabilities
             * 
             * @param Optional
             * @type array
             */
            'capability' => [

                /**
                 * Capability Type
                 * 
                 * Possible values '@type', 'post', 'page'
                 * if '@type' = Post Type
                 * 
                 * @param Optional
                 * @type string
                 * @default 'post'
                 */
                'type' => 'post',

                /**
                 * Capabilities
                 * 
                 * @param Optional
                 * @type array|string
                 */
                // 'capabilities' => [
                // ]

            ],

            /**
             * Rewrite rules
             * 
             * @param Optional
             * @type bool|array
             * @default true
             */
            'rewrite' => [
                
                /**
                 * Slug
                 * 
                 * Customize the permastruct slug
                 * 
                 * If not set or empty -> PostType
                 * If @type -> PostType
                 * If @name -> slugify the Name
                 * 
                 * @param Optional
                 * @type String
                 * @default PostType
                 * 
                 * @exemple for PostType :
                 * - '@type'
                 * 
                 * @exemple for Post Name :
                 * - '@name'
                 * 
                 * @exemple for custom slug :
                 * - 'my_custom_slug'
                 */
                'slug' => '@type',

                /**
                 * Prefixed (with_front)
                 * 
                 * Whether the permastruct should be prepended with WP_Rewrite::$front
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default $true
                 */
                'prefixed' => true,

                /**
                 * Feed
                 * 
                 * Whether the feed permastruct should be built for this post type.
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default $has_archive
                 */
                'feeds' => true,

                /**
                 * Pagination
                 * 
                 * Whether the permastruct should provide for pagination.
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default true
                 */
                'paged' => true,

                /**
                 * EndPoint Mask
                 * 
                 * Endpoint mask to assign
                 * Possible value : "EP_NONE", "EP_PERMALINK", "EP_ATTACHMENT", "EP_DATE", "EP_YEAR", "EP_MONTH", "EP_DAY", "EP_ROOT", "EP_COMMENTS", "EP_SEARCH", "EP_CATEGORIES", "EP_TAGS", "EP_AUTHORS", "EP_PAGES", "EP_ALL_ARCHIVES", "EP_ALL"
                 * 
                 * @param Optional
                 * @type string
                 * @default "EP_PERMALINK"
                 */
                'endpoint' => "EP_PERMALINK"

            ],

            /**
             * Query rules
             * 
             * @param Optional
             * @type array
             */
            'query' => [

                /**
                 * Var 'query_var'
                 * 
                 * @param Optional
                 * @type string|bool
                 * @default $postType
                 */
                'var' => true,

                /**
                 * Exclude from search
                 * 
                 * Whether to exclude posts with this post type from front end 
                 * search results
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default opposite value of 'is_public'
                 */
                'exclude_from_search' => false,
    
                /**
                 * Is publicly Queryable
                 * 'publicly_queryable
                 * 
                 * Whether queries can be performed on the front end for the post 
                 * type as part of parse_request().
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default inherit value of 'is_public'
                 */
                'public' => true,

                /**
                 * delete_with_user
                 * 
                 * @param Optional
                 * @type boolean|null
                 * @defaul null
                 */
                'delete_with_user' => null

            ],

            /**
             * UI
             * 
             * Define if UI elements are displayed
             */
            'ui' => [

                /**
                 * Show UI
                 * 
                 * Whether to generate and allow a UI for managing this post 
                 * type in the admin. 
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default inherit value of 'is_public'
                 */
                'show_ui' => true,

                /**
                 * Labels
                 * 
                 * Define all labels
                 */
                'labels' => [

                    /**
                     * singular_name
                     * 
                     * Name for one object of this post type. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Post’ / ‘Page’.
                     */
                    'singular_name' => null,

                    /**
                     * add_new
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Add New’ for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x( 'Add New', 'product', 'textdomain' );.
                     */
                    'add_new' => null,

                    /**
                     * add_new_item
                     * 
                     * Label for adding a new singular item. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Add New Post’ / ‘Add New Page’.
                     */
                    'add_new_item' => null,

                    /**
                     * edit_item
                     * 
                     * Label for editing a singular item. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Edit Post’ / ‘Edit Page’.
                     */
                    'edit_item' => null,

                    /**
                     * new_item
                     * 
                     * Label for the new item page title. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘New Post’ / ‘New Page’.
                     */
                    'new_item' => null,

                    /**
                     * view_item
                     * 
                     * Label for viewing a singular item. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘View Post’ / ‘View Page’.
                     */
                    'view_item' => null,

                    /**
                     * view_items
                     * 
                     * Label for viewing post type archives. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘View Posts’ / ‘View Pages’.
                     */
                    'view_items' => null,

                    /**
                     * search_items
                     * 
                     * Label for searching plural items. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Search Posts’ / ‘Search Pages’.
                     */
                    'search_items' => null,

                    /**
                     * not_found
                     * 
                     * Label used when no items are found. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘No posts found’ / ‘No pages found’.
                     */
                    'not_found' => null,

                    /**
                     * not_found_in_trash
                     * 
                     * Label used when no items are in the trash. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘No posts found in Trash’ / ‘No pages found in Trash’.
                     */
                    'not_found_in_trash' => null,

                    /**
                     * parent_item_colon
                     * 
                     * Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Parent Page:’.
                     */
                    'parent_item_colon' => null,

                    /**
                     * all_items
                     * 
                     * Label to signify all items in a submenu link. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘All Posts’ / ‘All Pages’.
                     */
                    'all_items' => null,

                    /**
                     * archives
                     * 
                     * Label for archives in nav menus. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Post Archives’ / ‘Page Archives’.
                     */
                    'archives' => null,

                    /**
                     * attributes
                     * 
                     * Label for the attributes meta box. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Post Attributes’ / ‘Page Attributes’.
                     */
                    'attributes' => null,

                    /**
                     * insert_into_item
                     * 
                     * Label for the media frame button. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Insert into post’ / ‘Insert into page’.
                     */
                    'insert_into_item' => null,

                    /**
                     * uploaded_to_this_item
                     * 
                     * Label for the media frame filter. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Uploaded to this post’ / ‘Uploaded to this page’.
                     */
                    'uploaded_to_this_item' => null,

                    /**
                     * featured_image
                     * 
                     * Label for the Featured Image meta box title. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Featured Image’.
                     */
                    'featured_image' => null,

                    /**
                     * set_featured_image
                     * 
                     * Label for setting the featured image. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Set featured image’.
                     */
                    'set_featured_image' => null,

                    /**
                     * remove_featured_image
                     * 
                     * Label for removing the featured image. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Remove featured image’.
                     */
                    'remove_featured_image' => null,

                    /**
                     * use_featured_image
                     * 
                     * Label in the media frame for using a featured image. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Use as featured image’.
                     */
                    'use_featured_image' => null,

                    /**
                     * menu_name
                     * 
                     * Label for the menu name. 
                     * 
                     * @param Optional
                     * @type String
                     * @default the same as name.
                     */
                    'menu_name' => null,

                    /**
                     * filter_items_list
                     * 
                     * Label for the table views hidden heading. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Filter posts list’ / ‘Filter pages list’.
                     */
                    'filter_items_list' => null,

                    /**
                     * items_list_navigation
                     * 
                     * Label for the table pagination hidden heading. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Posts list navigation’ / ‘Pages list navigation’.
                     */
                    'items_list_navigation' => null,

                    /**
                     * items_list
                     * 
                     * Label for the table hidden heading. 
                     * 
                     * @param Optional
                     * @type String
                     * @default ‘Posts list’ / ‘Pages list’.
                     */
                    'items_list' => null,

                ],

                /**
                 * Menus
                 * 
                 * Define parameters for UI menus
                 * (main menu / topbar)
                 */
                'menus' => [

                    /**
                     * Main Admin Menu
                     */
                    'main' => [

                        /**
                         * Show in menu
                         * show_in_menu
                         * 
                         * Where to show the post type in the admin menu. To work, $show_ui 
                         * must be true. 
                         * If true, the post type is shown in its own top level menu. 
                         * If false, no menu is shown. 
                         * If a string of an existing top level menu (eg. 'tools.php' or 
                         * 'edit.php?post_type=page'), the post type will be placed as a 
                         * sub-menu of that. 
                         * 
                         * @param Optional
                         * @type Boolean
                         * @default inherit value of 'show_ui'
                         */
                        'display' => true,

                        /**
                         * Menu Position
                         * menu_position
                         * 
                         * The position in the menu order the post type should appear. 
                         * To work, $show_in_menu must be true.
                         * 
                         * @param Optional
                         * @type Int
                         * @default null
                         */
                        'position' => null,
            
                        /**
                         * Menu Icon
                         * menu_icon
                         * 
                         * The icon of the menu
                         * 
                         * @param Optional
                         * @type String
                         * @default null
                         * 
                         * @exemple for dashicon :
                         * - 'dashicons-email'
                         * 
                         * @exemple for image in /Public/assets/images/ directory :
                         * - '@ppm.svg'
                         * 
                         * @exemple for image uri :
                         * - 'http://www.com/image.jpg'
                         */
                        'icon' => '@ppm.png'

                    ],

                    /**
                     * Top Menu
                     */
                    'top' => [

                        /**
                         * Show in Menu Bar (wp-admin topbar)
                         * show_in_admin_bar
                         * 
                         * Makes this post type available via the admin bar
                         * 
                         * @param Optional
                         * @type Boolean
                         * @default inherit value of 'show_in_menu'
                         */
                        'display' => true,

                    ]

                ],

                /**
                 * Pages
                 * 
                 * Define parameters for UI pages
                 */
                'pages' => [

                    /**
                     * Index
                     * 
                     * Define the parameters for the page of the list of
                     * custom posts.
                     */
                    'index' => [
    
                        /**
                         * Columns
                         * 
                         * List of displayed columns
                         * 
                         * @param Optional
                         * @type Array
                         */
                        'columns' => [

                            // Column Checkbox
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "cb",

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Title
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "title",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Date
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "date",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Categories, based on Categories settings
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "categories",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Tags, based on Tags settings
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "tags",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Author, only if the metaboxes Author is displayed
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "author",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Column Comments, only if the metaboxes Author is displayed
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "comments",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true
                            ],

                            // Custom column
                            [
                                /**
                                 * Column Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => "custom",

                                /**
                                 * Is column sortable
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default false
                                 */
                                'sortable' => true,

                                /**
                                 * Is column displayed
                                 * 
                                 * @param optional
                                 * @type boolean
                                 * @default true
                                 */
                                'display' => true,

                                /**
                                 * Column title
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'label' => "My custom column",

                                /**
                                 * Column data
                                 * 
                                 * If string, its define a schema['key]
                                 * If array, its define multiple schema['key'], 
                                 * the first index of array define a data glue
                                 * ex: [" à ", "date", "time"]
                                 * => 01-10-1970 à 00:00
                                 * 
                                 * @param required
                                 * @type string|array
                                 */
                                'data' => ["glue", "field_a", "field_b"],
                            ]
                        ],

                        /**
                         * Rows
                         */
                        'rows' => [

                            /**
                             * Action of row admin
                             * 
                             * If is array, values are 'edit', 'inline', trash'
                             * & 'view'
                             * 
                             * @param Optional
                             * @type bool|array
                             * @default true
                             */
                            // 'actions' => false,
                            'actions' => [

                                /**
                                 * Display the action "edit" in row action menu
                                 * 
                                 * @param Optional
                                 * @type bool
                                 * @default true
                                 */ 
                                'edit' => true,

                                /**
                                 * Display the action "Quick Edit" in row action menu
                                 * 
                                 * @param Optional
                                 * @type bool
                                 * @default true
                                 */ 
                                'inline' => true,

                                /**
                                 * Display the action "Trash" in row action menu
                                 * 
                                 * @param Optional
                                 * @type bool
                                 * @default true
                                 */ 
                                'trash' => true,

                                /**
                                 * Display the action "View" in row action menu
                                 * 
                                 * @param Optional
                                 * @type bool
                                 * @default true
                                 */ 
                                'view' => true
                            ]

                        ],
                    ],

                    /**
                     * Edit
                     * 
                     * Define the parameters for the page of the editor of
                     * custom posts.
                     */
                    'edit' => [
                        
                        /**
                         * Link (_edit_link)
                         * 
                         * FOR INTERNAL USE ONLY! URL segment to use for edit 
                         * link of this post type
                         * 
                         * @param Optional
                         * @type string
                         * @default 'post.php?post=%d'
                         */
                        'link' => null,

                        // TODO // Main view file of page edit
                        // null for default wp page
                        'src' => null,

                        /**
                         * Form
                         * 
                         * Edit Form settings
                         * 
                         * @param Optional
                         * @type array|null
                         */
                        'form' => [

                            /**
                             * NoValidate
                             * 
                             * If true, add novalidate attribute to the Edit Form
                             * 
                             * @param Optional
                             * @type boolean
                             * @default true
                             */
                            'novalidate' => true

                        ],

                        /**
                         * Metaboxes
                         * 
                         * @param Optional
                         * @type array|null
                         */
                        'metaboxes' => [

                            /**
                             * Support Title
                             */
                            [
                                'key' => 'title',
                                'display' => false,

                                /**
                                 * Replace
                                 * 
                                 * Only if display is false
                                 * Define each fields are used to generate the post title
                                 * 
                                 * @param Optional
                                 * @type string|array
                                 */
                                'replace' => ["demo_text", "demo_range"],

                                /**
                                 * Glue of replacement field values
                                 * 
                                 * @param Optional
                                 * @type string
                                 * @default ' ' (white space)
                                 */
                                'glue' => " "
                            ],

                            /**
                             * Support Editor
                             */
                            [
                                'key' => 'editor',
                                'display' => false
                            ],

                            /**
                             * Support Comments
                             */
                            [
                                'key' => 'comments',
                                'display' => false
                            ],

                            /**
                             * Support Revision
                             */
                            [
                                'key' => 'revisions',
                                'display' => false
                            ],

                            /**
                             * Support trackbacks
                             */
                            [
                                'key' => 'trackbacks',
                                'display' => false
                            ],

                            /**
                             * Support author
                             */
                            [
                                'key' => 'author',
                                'display' => false
                            ],

                            /**
                             * Support excerpt
                             */
                            [
                                'key' => 'excerpt',
                                'display' => false
                            ],

                            /**
                             * Support page-attributes
                             */
                            [
                                'key' => 'page-attributes',
                                'display' => false
                            ],

                            /**
                             * Support thumbnail
                             */
                            [
                                'key' => 'thumbnail',
                                'display' => false
                            ],

                            /**
                             * Support custom-fields
                             */
                            [
                                'key' => 'custom-fields',
                                'display' => false
                            ],

                            /**
                             * Support post-formats
                             */
                            [
                                'key' => 'post-formats',
                                'display' => false
                            ],
                            
                            /**
                             * Custom Metabox
                             */
                            [

                                /**
                                 * Key
                                 * 
                                 * @param required
                                 * @type string
                                 */
                                'key' => 'test',
                                
                                /**
                                 * Title
                                 */
                                'title' => 'test metaboxes',

                                /**
                                 * Description
                                 */
                                'description' => 'This is a metabox description value.',
                                
                                /**
                                 * Source
                                 */
                                'src' => null,
                                
                                /**
                                 * Context
                                 * 
                                 * possible values 'advancd', 'normal', 'side'
                                 * 
                                 * @param Optional
                                 * @type string
                                 * @default 'normal'
                                 * @wp-default: 'advanced'
                                 */
                                'context' => 'normal',
                                
                                /**
                                 * Priority
                                 * 
                                 * possible value 'high' or 'low'
                                 * 
                                 * @param Optional
                                 * @type string
                                 * @default 'high'
                                 */
                                'priority' => 'high',
                                
                                /**
                                 * Display
                                 * 
                                 * @param Optional
                                 * @type boolean
                                 * @defaul true
                                 */
                                'display' => true,

                                /**
                                 * Schema
                                 */
                                'schema' => [
                                    // 'demo_url',
                                ]

                            ],

                            
                            /**
                             * Demo Metabox : Text
                             */
                            [
                                'key' => 'demo_metabox_fieldtypes',
                                'title' => 'Metabox : Fields types',
                                'src' => null,
                                'context' => 'normal',
                                'priority' => 'high',
                                'display' => true,
                                'schema' => [
                                    'demo_choices_expanded',
                                    // 'demo_text',
                                    // 'demo_password',
                                    // 'demo_password_confirm',
                                    // 'demo_email',
                                    // 'demo_tel',
                                    // 'demo_url',
                                    // 'demo_search',
                                    // 'demo_range',
                                    // 'demo_number',
                                    // 'demo_output',
                                    // 'demo_color',
                                    // 'demo_checkbox',
                                    // 'demo_hidden',
                                ]
                            ],

                            
                            /**
                             * Demo Metabox : Options
                             */
                            // [
                            //     'key' => 'demo_metabox_options',
                            //     'title' => 'Metabox : Field options',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_placeholder',
                            //         'demo_required',
                            //         'demo_readonly',
                            //         'demo_disabled',
                            //         'demo_helper',
                            //         'demo_custom_class',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : Choices
                             */
                            // [
                            //     'key' => 'demo_metabox_choices',
                            //     'title' => 'Metabox : Choices',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_choices',
                            //         'demo_choices_multiple',
                            //         'demo_choices_expanded',
                            //         'demo_choices_expanded_multiple',
                            //         'demo_choices_expanded_inline',
                            //         'demo_choices_expanded_multiple_inline',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : Options
                             */
                            // [
                            //     'key' => 'demo_metabox_options_number',
                            //     'title' => 'Metabox : Number type options',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_number',
                            //         'demo_number_default',
                            //         'demo_number_min',
                            //         'demo_number_max',
                            //         'demo_number_step10',
                            //         'demo_number_step001',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : Date & Time
                             */
                            // [
                            //     'key' => 'demo_metabox_datetime',
                            //     'title' => 'Metabox : Date & Time',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_date',
                            //         'demo_time',
                            //         'demo_datetime',
                            //         'demo_month',
                            //         'demo_week',
                            //         'demo_year',
                            //         'demo_date_with_default',
                            //         'demo_date_with_default_today',
                            //         'demo_time_with_default',
                            //         'demo_time_with_default_now',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : Textarea
                             */
                            // [
                            //     'key' => 'demo_metabox_textarea',
                            //     'title' => 'Metabox : Textarea options',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_textarea',
                            //         'demo_textarea_cols',
                            //         'demo_textarea_rows',
                            //         'demo_textarea_autosize',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : File
                             */
                            // [
                            //     'key' => 'demo_metabox_file',
                            //     'title' => 'Metabox : File options',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_file',
                            //         'demo_file_multiple',
                            //         'demo_file_type',
                            //         'demo_file_size',
                            //         'demo_file_preview',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : WYSIWYG
                             */
                            // [
                            //     'key' => 'demo_metabox_wysiwyg',
                            //     'title' => 'Metabox : WYSIWYG',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_wysiwyg',
                            //     ]
                            // ],

                            
                            /**
                             * Demo Metabox : Collection
                             */
                            // [
                            //     'key' => 'demo_metabox_collection',
                            //     'title' => 'Metabox : Collection',
                            //     'src' => null,
                            //     'context' => 'normal',
                            //     'priority' => 'high',
                            //     'display' => true,
                            //     'schema' => [
                            //         'demo_collection',
                            //     ]
                            // ],

                        ],

                        /**
                         * Metaboxes Options
                         */
                        'metaboxes_options' => [

                            /**
                             * Sortable
                             * 
                             * Define if metaboxes are sortable
                             * 
                             * @param Optional
                             * @type boolean
                             * @defaul true
                             */
                            'sortable' => false,
                        ]

                    ],

                    /**
                     * Menus
                     * 
                     * Define the parameters for the page of the nav menus 
                     * manager.
                     */
                    'menus' => [

                        /**
                         * show_in_nav_menus
                         * 
                         * Makes this post type available for selection in navigation menus.
                         * 
                         * @param Optional
                         * @type Boolean
                         * @default inherit value of 'is_public'
                         */
                        'display' => true,

                    ]
                ],

                /**
                 * 
                 */
            ],

            /**
             * REST
             * 
             * @param Optional
             * @type Boolean|Array
             * @default false
             */
            'rest' => [

                /**
                 * REST Base
                 * 
                 * To change the base url of REST API route
                 * 
                 * @param Optional
                 * @type String
                 * @default PostType
                 */
                'base' => null,

                /**
                 * REST Controller
                 * 
                 * Add the name of class controller to turn 'show_in_rest' true
                 * 
                 * @param Optional
                 * @type String
                 * @default false
                 * @wp-default 'WP_REST_Posts_Controller'
                 */
                'controller' => false,

            ],

            /**
             * Post Categories
             * 
             * @param Optional
             * @type Array
             */
            'categories' => [

                /**
                 * Name
                 * 
                 * @param Optional
                 * @type String
                 * @Default "Categories"
                 */
                'name' => null,

                /** 
                 * Description
                 * 
                 * A short descriptive summary of what the category is for
                 * 
                 * @param Optional
                 * @type string
                 * @default ''
                 */
                'description' => "A short descriptive summary of what the category is for",

                /**
                 * Is Public
                 * 
                 * Whether a taxonomy is intended for use publicly either via 
                 * the admin interface or by front-end users.
                 * 
                 * @param Optional
                 * @type Boolean
                 * @Default 'is_public'
                 */ 
                'public' => true,

                /**
                 * Is publicly Queryable
                 * 
                 * Whether the taxonomy is publicly queryable
                 * 
                 * @param Optional
                 * @type Boolean
                 * @default inherit value of 'public'
                 */
                'publicly_queryable' => true,

                /**
                 * Associated Object
                 * 
                 * @param Optional
                 * @type string|array
                 */
                'objects' => ['post'],

                /**
                 * UI
                 */
                'ui' => [

                    /**
                     * Show UI
                     * 
                     * Whether to generate and allow a UI for managing terms in 
                     * this taxonomy in the admin
                     * 
                     * @param Optional
                     * @type Boolean
                     * @default true
                     */
                    'show_ui' => true,

                    /**
                     * Labels
                     * 
                     * @param Optional
                     * @type Array
                     */
                    'labels' => [

                        /**
                         * 'singular_name'
                         * 
                         * Name for one object of this taxonomy.
                         * 
                         * @type string
                         * @default 'Category'.
                         */
                        'singular_name' => 'Category',

                        /**
                         * 'search_items'
                         * 
                         * @type string
                         * @default 'Search Tags'/'Search Categories'.
                         */
                        'search_items' => null,

                        /**
                         * 'popular_items'
                         * 
                         * This label is only used for non-hierarchical taxonomies.
                         * 
                         * @type string
                         * @default 'Popular Tags'.
                         */
                        'popular_items' => null,

                        /**
                         * 'all_items'
                         * 
                         * @type string
                         * @default 'All Tags'/'All Categories'.
                         */
                        'all_items' => null,

                        /**
                         * 'parent_item'
                         * 
                         * This label is only used for hierarchical taxonomies.
                         * 
                         * @type string
                         * @default 'Parent Category'.
                         */
                        'parent_item' => null,

                        /**
                         * 'parent_item_colon'
                         * 
                         * The same as parent_item, but with colon : in the end.
                         * 
                         * @type string
                         */
                        'parent_item_colon' => null,

                        /**
                         * 'edit_item'
                         * 
                         * @type string
                         * @default 'Edit Tag'/'Edit Category'.
                         */
                        'edit_item' => null,

                        /**
                         * 'view_item'
                         * 
                         * @type string
                         * @default 'View Tag'/'View Category'.
                         */
                        'view_item' => null,

                        /**
                         * 'update_item'
                         * 
                         * @type string
                         * @default 'Update Tag'/'Update Category'.
                         */
                        'update_item' => null,

                        /**
                         * 'add_new_item'
                         * 
                         * @type string
                         * @default 'Add New Tag'/'Add New Category'.
                         */
                        'add_new_item' => null,

                        /**
                         * 'new_item_name'
                         * 
                         * @type string
                         * @default 'New Tag Name'/'New Category Name'.
                         */
                        'new_item_name' => null,

                        /**
                         * 'separate_items_with_commas'
                         * 
                         * This label is only used for non-hierarchical taxonomies.
                         * 
                         * @type string
                         * @default 'Separate tags with commas', used in the meta box.
                         */
                        'separate_items_with_commas' => null,

                        /**
                         * 'add_or_remove_items'
                         * 
                         * This label is only used for non-hierarchical taxonomies.
                         * 
                         * @type string
                         * @default 'Add or remove tags', used in the meta box when JavaScript is disabled.
                         */
                        'add_or_remove_items' => null,

                        /**
                         * 'choose_from_most_used'
                         * 
                         * This label is only used on non-hierarchical taxonomies.
                         * 
                         * @type string
                         * @default 'Choose from the most used tags', used in the meta box.
                         */
                        'choose_from_most_used' => null,

                        /**
                         * 'not_found'
                         * 
                         * @type string
                         * @default 'No tags found'/'No categories found', used in the meta box and taxonomy list table.
                         */
                        'not_found' => null,

                        /**
                         * 'no_terms'
                         * 
                         * @type string
                         * @default 'No tags'/'No categories', used in the posts and media list tables.
                         */
                        'no_terms' => null,

                        /**
                         * 'items_list_navigation'
                         * 
                         * Label for the table pagination hidden heading.
                         * 
                         * @type string
                         */
                        'items_list_navigation' => null,

                        /**
                         * 'items_list'
                         * 
                         * Label for the table hidden heading.
                         * 
                         * @type string
                         */
                        'items_list' => null,

                        /**
                         * 'most_used'
                         * 
                         * Title for the Most Used tab.
                         * 
                         * @type string
                         * @default 'Most Used'.
                         */
                        'most_used' => null,

                        /**
                         * 'back_to_items'
                         * 
                         * Label displayed after a term has been updated.
                         * 
                         * @type string
                         */
                        'back_to_items' => null,

                    ],

                    /**
                     * Menus
                     */

                    /**
                     * Pages
                     */
                ],

                /**
                 * Rest
                 */


            ],

            /**
             * Post Tags
             * 
             * @param Optional
             * @type Array
             */
            'tags' => [

                /**
                 * Name
                 * 
                 * @param Optional
                 * @type String
                 * @Default "Tags"
                 */
                'name' => null,

                /** 
                 * Description
                 * 
                 * A short descriptive summary of what the tag is for
                 * 
                 * @param Optional
                 * @type string
                 * @default ''
                 */
                'description' => "A short descriptive summary of what the tag is for",

                /**
                 * Is Public
                 * 
                 * Whether a taxonomy is intended for use publicly either via 
                 * the admin interface or by front-end users.
                 * 
                 * @param Optional
                 * @type Boolean
                 * @Default 'is_public'
                 */ 
                'public' => true,

                /**
                 * Labels
                 * 
                 * @param Optional
                 * @type Array
                 */
                'labels' => [

                    /**
                     * 'singular_name'
                     * 
                     * Name for one object of this taxonomy.
                     * 
                     * @type string
                     * @default 'Tag'.
                     */
                    'singular_name' => 'Tag',

                    /**
                     * 'search_items'
                     * 
                     * @type string
                     * @default 'Search Tags'/'Search Categories'.
                     */
                    'search_items' => null,

                    /**
                     * 'popular_items'
                     * 
                     * This label is only used for non-hierarchical taxonomies.
                     * 
                     * @type string
                     * @default 'Popular Tags'.
                     */
                    'popular_items' => null,

                    /**
                     * 'all_items'
                     * 
                     * @type string
                     * @default 'All Tags'/'All Categories'.
                     */
                    'all_items' => null,

                    /**
                     * 'parent_item'
                     * 
                     * This label is only used for hierarchical taxonomies.
                     * 
                     * @type string
                     * @default 'Parent Category'.
                     */
                    'parent_item' => null,

                    /**
                     * 'parent_item_colon'
                     * 
                     * The same as parent_item, but with colon : in the end.
                     * 
                     * @type string
                     */
                    'parent_item_colon' => null,

                    /**
                     * 'edit_item'
                     * 
                     * @type string
                     * @default 'Edit Tag'/'Edit Category'.
                     */
                    'edit_item' => null,

                    /**
                     * 'view_item'
                     * 
                     * @type string
                     * @default 'View Tag'/'View Category'.
                     */
                    'view_item' => null,

                    /**
                     * 'update_item'
                     * 
                     * @type string
                     * @default 'Update Tag'/'Update Category'.
                     */
                    'update_item' => null,

                    /**
                     * 'add_new_item'
                     * 
                     * @type string
                     * @default 'Add New Tag'/'Add New Category'.
                     */
                    'add_new_item' => null,

                    /**
                     * 'new_item_name'
                     * 
                     * @type string
                     * @default 'New Tag Name'/'New Category Name'.
                     */
                    'new_item_name' => null,

                    /**
                     * 'separate_items_with_commas'
                     * 
                     * This label is only used for non-hierarchical taxonomies.
                     * 
                     * @type string
                     * @default 'Separate tags with commas', used in the meta box.
                     */
                    'separate_items_with_commas' => null,

                    /**
                     * 'add_or_remove_items'
                     * 
                     * This label is only used for non-hierarchical taxonomies.
                     * 
                     * @type string
                     * @default 'Add or remove tags', used in the meta box when JavaScript is disabled.
                     */
                    'add_or_remove_items' => null,

                    /**
                     * 'choose_from_most_used'
                     * 
                     * This label is only used on non-hierarchical taxonomies.
                     * 
                     * @type string
                     * @default 'Choose from the most used tags', used in the meta box.
                     */
                    'choose_from_most_used' => null,

                    /**
                     * 'not_found'
                     * 
                     * @type string
                     * @default 'No tags found'/'No categories found', used in the meta box and taxonomy list table.
                     */
                    'not_found' => null,

                    /**
                     * 'no_terms'
                     * 
                     * @type string
                     * @default 'No tags'/'No categories', used in the posts and media list tables.
                     */
                    'no_terms' => null,

                    /**
                     * 'items_list_navigation'
                     * 
                     * Label for the table pagination hidden heading.
                     * 
                     * @type string
                     */
                    'items_list_navigation' => null,

                    /**
                     * 'items_list'
                     * 
                     * Label for the table hidden heading.
                     * 
                     * @type string
                     */
                    'items_list' => null,

                    /**
                     * 'most_used'
                     * 
                     * Title for the Most Used tab.
                     * 
                     * @type string
                     * @default 'Most Used'.
                     */
                    'most_used' => null,

                    /**
                     * 'back_to_items'
                     * 
                     * Label displayed after a term has been updated.
                     * 
                     * @type string
                     */
                    'back_to_items' => null,

                ],

            ],

            /**
             * Images
             * 
             * @param Optional
             * @type Array|null
             * @default null
             */
            'images' => [
        
                /**
                 * 
                 */
                'strict' => true,
        
                /**
                 * 
                 */
                'preserve_wp_sizes' => true,
        
                /**
                 * 
                 */
                'sizes' => [
        
                ]
        
            ],

            /**
             * Schemas
             * 
             * Liste de déclaration des champs
             * ici on declare les champs et on appel les clés dans ui>edit>metboxes
             * 
             * @param Optional
             * @type array
             */
            'schema' => $schema_declaration

        ]
    ],

    /**
     * Widgets
     */
    'widgets' => [
        
    ]
];

        

        // @ Name : show_error (optional)
        // @ Type : boolean
        // @ Default value : true
        // --
        // If False, the error message was not printed for this field
        
        // @ Name : error_messages (optional)
        // @ Type : string | array 
        // @ Default value : is an array
        // @ Exemple : "error_messages": "You write something wrong."
        // @ Exemple : "error_messages": {
        //     "email": "WTF !? This is not an email address !"
        // }
        // -- 
        // The default array values content
        // - "required" : "This field is required.", 
        // - "email" : "This field is not a valid email address.", 
        // - "url" : "This field is not a valid url.", 
        // - "rule" : "This field is not valid."
        // --
        // If String, the message was apply on all errors type : "error_messages": "Has error !"
        // If Array, you can specify each error type message

