<?php

// TODO : Gestion des effet si PostType : 'post' ou 'page'

namespace Framework\Register;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Strings;
use \Framework\Components\FileSystem as FS;
use \Framework\Register\Taxonomy;
use \Framework\Components\Form\Response\Response;
use \Framework\Components\Notices;
use \Framework\Kernel\Session;

if (!class_exists('Framework\Register\Posts'))
{
	class Posts
	{
        /**
         * Max size for the custom post type identifier
         */
        const TYPE_MAX_SIZE = 20;

        /**
         * List of custom post parameters we want to internationalize
         */
        // const I18N = ['label','description'];
        const LABELS_UI = ['singular_name','add_new','add_new_item','edit_item',
            'new_item','view_item','view_items','search_items','not_found',
            'not_found_in_trash','parent_item_colon','all_items','archives',
            'attributes','insert_into_item','uploaded_to_this_item',
            'featured_image','set_featured_image','remove_featured_image',
            'use_featured_image','menu_name','filter_items_list',
            'items_list_navigation','items_list'];

        /**
         * Available EndPoint Mask
         */
        const ENDPOINT_MASK = ["EP_NONE", "EP_PERMALINK", "EP_ATTACHMENT", 
            "EP_DATE", "EP_YEAR", "EP_MONTH", "EP_DAY", "EP_ROOT", 
            "EP_COMMENTS", "EP_SEARCH", "EP_CATEGORIES", "EP_TAGS", 
            "EP_AUTHORS", "EP_PAGES", "EP_ALL_ARCHIVES", "EP_ALL"];

        /**
         * Capapility Types
         */
        const CAPABILITY_TYPE = ['post', 'page'];

        /**
         * Default supports
         */
        const SUPPORTS = ['title', 'editor'];

        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
        protected $bs;

        /**
         * List of custom posts
         * 
         * @param array
         */
        private $posts = array();

        /**
         * Response data
         * 
         * @param array
         */
        private $response = array();

        /**
         * 
         */
        public function __construct($bs)
        {
            // Retrieve the bootstrap class instance
            $this->bs = $bs;

            // Define the list of Custom Posts
            $this->WP_Posts();
        }

        /**
         * 
         */
        private function WP_Posts()
        {
            // Retrieve the list of custom posts
            $posts = $this->bs->getPosts();
            $posts = is_array($posts) ? $posts : [];

            // Verify eah parameters and format the Post array and finaly,
            // add the post to the register
            foreach ($posts as $post) 
            {
                if ($this->isValidType($post) && $this->isValidLabel($post)) 
                {
                    $this->setPost($post);

                    // Define the Type
                    $post = $this->setType($post);

                    // Define the Label(s)
                    $post = $this->setLabel($post);
                    $post = $this->setLabels($post);
                    
                    // Define the Description
                    $post = $this->setDescription($post);

                    // Is public post ?
                    $post = $this->setPublic($post);

                    // Is Hierarchical post ?
                    $post = $this->setHierarchical($post);

                    // Show UI
                    $post = $this->setShowUI($post);

                    // Show in Menu
                    $post = $this->setShowInMenu($post);

                    // Show the custom posts in Menus Manager
                    $post = $this->setShowInNavMenus($post);

                    // Show in Menu Bar (topbar)
                    $post = $this->setShowInMenuBar($post);

                    // Menu Position
                    $post = $this->setMenuPosition($post);

                    // Menu Icon
                    $post = $this->setMenuIcon($post);

                    // Has Archive
                    $post = $this->setHasArchive($post);

                    // Define if is exportable
                    $post = $this->setCanExport($post);

                    // Define query rules
                    $post = $this->setQuery($post);

                    // REST
                    $post = $this->setREST($post);

                    // Define rewrite rules
                    $post = $this->setRewrite($post);

                    // Define Capabilities
                    $post = $this->setCapability($post);

                    // Define Edit Link (_edit_link)
                    $post = $this->setEditLink($post);

                    // Define Categories
                    new Taxonomy($this->bs, 'categories', $post);

                    // Define Tags
                    new Taxonomy($this->bs, 'tags', $post);

                    // Add Metaboxes (and supports)
                    $metaboxes = new Metaboxes($this->bs, $post);
                    $supports = $metaboxes->getSupports();

                    // Define Supports
                    $post = $this->setSupports($supports, $post);

                    // TODO : 'map_meta_cap'
                    // (bool) Whether to use the internal default meta capability handling. Default false.

                    // TODO : 'register_meta_box_cb'
                    // (callable) Provide a callback function that sets up the meta boxes for the edit form. Do remove_meta_box() and add_meta_box() calls in the callback. Default null.

                    // TODO : '_builtin'
                    // (bool) FOR INTERNAL USE ONLY! True if this post type is a native or "built-in" post_type. Default false.

                    // Internationalize the post data
                    // $post = $this->i18n($post);

                    // Add the custom post to the register
                    register_post_type( $post['type'], $post );

                    // Manage Post Column 
                    add_filter( "manage_{$post['type']}_posts_columns", array($this, 'setAdminColumns') );
                    add_action( "manage_{$post['type']}_posts_custom_column" , array($this, 'setAdminColumnsData'), 10, 2 );
                    add_filter( "manage_edit-{$post['type']}_sortable_columns", array($this, 'setAdminColumnsSortable') );

                    // Menu action on Admin index rows
                    add_filter('post_row_actions', array($this, 'setAdminRowActions'), 10, 1);

                    // Post submission
                    add_action('pre_post_update', array($this, "postValidation"));

                    // Notice (flashbag)
                    add_action('admin_notices', array(new Notices($this->bs->getNamespace()), "get"));


                    // Clear the post session
                    add_action('clear_post_session', function() use ($post) { $this->clearPostSession($post); });
                    add_action('wp_footer', [$this, "clearPostSession"], 10);
                    add_action('admin_footer', [$this, "clearPostSession"], 10);
                }
            }
        }

        /**
         * Retrieve Posts config in config.php
         */
        private function setPost(array $post)
        {
            $this->post = $post;

            return $this;
        }
        /**
         * 
         */
        private function getPost()
        {
            return $this->post;
        }

        /**
         * Define the Post type key
         * 
         * @param array $post
         * @return array $post
         */
        private function setType(array $post)
        {
            $post['type'] = isset($post['type']) ? $post['type'] : null;
            
            return $post;
        }
        /**
         * Define the label of the post
         * 
         * @param array $post
         * @return array $post
         */
        private function setLabel(array $post)
        {
            // Init "label"
            $post['label'] = null;

            // Define the label
            if (isset($post['name'])) 
            {
                $post['label'] = $post['name'];
                unset($post['name']);
            }
            
            return $post;
        }

        /**
         * Define labels of the post
         * 
         * @param array $post
         * @return array $post
         */
        private function setLabels(array $post)
        {
            $post['labels'] = array();

            $post['labels'] = ['name' => $post['label']];

            if (isset($post['ui']['labels'])) 
            {
                $post['labels'] = array_merge(
                    $post['labels'], 
                    $this->bs->i18n(self::LABELS_UI, $post['ui']['labels'])
                );

                unset($post['ui']['labels']);
            }

            return $post;
        }

        /**
         * Define the description of the post
         * 
         * @param array $post
         * @return array $post
         */
        private function setDescription(array $post)
        {
            $post['description'] = isset($post['description']) ? $post['description'] : null;

            return $post;
        }

        /**
         * Define if the post is a Public post
         * 
         * @param array $post
         * @return array $post
         */
        private function setPublic(array $post)
        {
            if (!isset($post['public']) || !is_bool($post['public']))
            {
                $post['public'] = true;
            }

            return $post;
        }

        /**
         * Whether the post type is hierarchical (e.g. page). 
         * 
         * @param array $post
         * @return array $post
         */
        private function setHierarchical(array $post)
        {
            if (!isset($post['hierarchical']) || !is_bool($post['hierarchical']))
            {
                $post['hierarchical'] = false;
            }

            return $post;
        }

        /**
         * Whether to exclude posts with this post type from front end search 
         * results. Default is the opposite value of $post['public'].
         * 
         * @param array $query
         * @param array $post
         * @return array $post
         */
        private function setExcludeFromSearch(array $query, array $post)
        {
            $post['exclude_from_search'] = !$post['public'];

            if (isset($query['exclude_from_search']) && is_bool($query['exclude_from_search']))
            {
                $post['exclude_from_search'] = $query['exclude_from_search'];
            }

            return $post;
        }

        /**
         * Whether queries can be performed on the front end for the post type 
         * as part of parse_request().
         * 
         * @param array $query
         * @param array $post
         * @return array $post
         */
        private function setPubliclyQueryable(array $query, array $post)
        {
            $post['publicly_queryable'] = $post['public'];

            if (isset($query['public']) && is_bool($query['public']))
            {
                $post['publicly_queryable'] = $query['public'];
            }

            return $post;
        }

        /**
         * Define the slug of custom post
         * 
         * @param array $rewrite
         * @param array $post
         * @return array $rewrite
         */
        private function setSlug(array $rewrite, array $post)
        {
            if (!isset($rewrite['slug']) || empty(trim($rewrite['slug'])))
            {
                $rewrite['slug'] = $post['type'];
            }

            switch ($rewrite['slug']) 
            {
                case '@type':
                    $rewrite['slug'] = $post['type'];
                    break;
                
                case '@name':
                    $rewrite['slug'] = Strings::slugify($post['label'], '_');
                    break;
                
                default:
                    $rewrite['slug'] = Strings::slugify($post['slug'], '_');
                    break;
            }

            return $rewrite;
        }

        /**
         * Define Whether to generate and allow a UI for managing this post 
         * type in the admin. 
         * 
         * @param array $post
         * @return array $post
         */
        private function setShowUI(array $post)
        {
            $post['show_ui'] = $post['public'];

            if (isset($post['ui']['show_ui']))
            {
                if (is_bool($post['ui']['show_ui']))
                {
                    $post['show_ui'] = $post['ui']['show_ui'];
                }
                unset($post['ui']['show_ui']);
            }

            return $post;
        }

        /**
         * Where to show the post type in the admin menu
         * 
         * @param array $post
         * @return array $post
         */
        private function setShowInMenu(array $post)
        {
            $post['show_in_menu'] = $post['show_ui'];

            if (isset($post['ui']['menus']['main']['display']))
            {
                if (is_bool($post['ui']['menus']['main']['display']))
                {
                    $post['show_in_menu'] = $post['ui']['menus']['main']['display'];
                }
                unset($post['ui']['menus']['main']['display']);
            }

            return $post;
        }

        /**
         * Define Makes this post type available for selection in navigation menus
         * 
         * @param array $post
         * @return array $post
         */
        private function setShowInNavMenus(array $post)
        {
            // WP Default value
            // $post['show_in_nav_menus'] = $post['public'];

            // PPM Default Value
            $post['show_in_nav_menus'] = false;

            if (isset($post['ui']['pages']['menus']['display']))
            {
                if (is_bool($post['ui']['pages']['menus']['display'])) 
                {
                    $post['show_in_nav_menus'] = $post['ui']['pages']['menus']['display'];
                }
                unset($post['ui']['pages']['menus']['display']);
            }

            return $post;
        }

        /**
         * Makes this post type available via the admin bar
         * 
         * @param array $post
         * @return array $post
         */
        private function setShowInMenuBar(array $post)
        {
            $post['show_in_admin_bar'] = $post['show_in_menu'];

            if (isset($post['ui']['menus']['top']['display']))
            {
                if (is_bool($post['ui']['menus']['top']['display']))
                {
                    $post['show_in_admin_bar'] = $post['ui']['menus']['top']['display'];
                }
                unset($post['ui']['menus']['top']['display']);
            }



            if (!isset($post['show_in_admin_bar']) || !is_bool($post['show_in_admin_bar']))
            {
                $post['show_in_admin_bar'] = $post['show_in_menu'];
            }

            return $post;
        }

        /**
         * The position in the menu order the post type should appear
         * 
         * @param array $post
         * @return array $post
         */
        private function setMenuPosition(array $post)
        {
            $post['menu_position'] = null;

            if (isset($post['ui']['menus']['main']['position']))
            {
                if (is_int($post['ui']['menus']['main']['position']))
                {
                    $post['menu_position'] = $post['ui']['menus']['main']['position'];
                }
                unset($post['ui']['menus']['main']['position']);
            }

            return $post;
        }

        /**
         * The icon of the menu
         * 
         * @param array $post
         * @return array $post
         */
        private function setMenuIcon(array $post)
        {
            $post['menu_icon'] = 'none';

            if (isset($post['ui']['menus']['main']['icon']) && is_string($post['ui']['menus']['main']['icon']))
            {
                if (preg_match("/^@/", $post['ui']['menus']['main']['icon']))
                {
                    $file = preg_replace("/^@/", null, $post['ui']['menus']['main']['icon']);
                    $file_path = $this->bs->getRoot().FS::DIRECTORY_IMAGES.$file;
                    $file_uri = $this->bs->getUri().FS::DIRECTORY_IMAGES.$file;
                    
                    if (file_exists($file_path))
                    {
                        $post['menu_icon'] = $file_uri;
                    }
                }
                unset($post['ui']['menus']['main']['icon']);
            }

            return $post;
        }

        /**
         * Whether there should be post type archives, or if a string, 
         * the archive slug to use. Will generate the proper rewrite rules if 
         * $rewrite is enabled.
         * 
         * @param array $post
         * @return array $post
         */
        private function setHasArchive(array $post)
        {
            if (!isset($post['has_archive']) || (!is_bool($post['has_archive']) && !is_string($post['has_archive'])))
            {
                $post['has_archive'] = $post['public'];
            }

            return $post;
        }

        /**
         * Whether the feed permastruct should be built for this post type
         * 
         * @param array $rewrite
         * @param array $post
         * @return array $rewrite
         */
        private function setFeeds(array $rewrite, array $post)
        {
            if (!isset($rewrite['feeds']) || !is_bool($rewrite['feeds']))
            {
                $rewrite['feeds'] = ($post['has_archive'] ? true : false);
            }

            return $rewrite;
        }

        /**
         * Define REST parameters
         * 
         * @param array $post
         * @return array $post
         */
        private function setREST(array $post)
        {
            // TODO: Include Rest Controller
            
            $post['show_in_rest'] = false;
            $post['rest_base'] = $post['type'];
            $post['rest_controller_class'] = false;

            if (isset($post['rest']))
            {
                if (isset($post['rest']['base']) && is_string($post['rest']['base']))
                {
                    $post['rest_base'] = $post['rest']['base'];
                }

                if (isset($post['rest']['controller']) && is_string($post['rest']['controller']))
                {
                    $post['rest_controller_class'] = $post['rest']['controller'];
                    $post['show_in_rest'] = true;
                }
                unset($post['rest']);
            }

            return $post;
        }

        /**
         * Define if structure has a pagination
         * 
         * @param array $rewrite
         * @return array $rewrite
         */
        private function setPages(array $rewrite)
        {
            $rewrite['pages'] = false;

            if (isset($rewrite['paged']))
            {
                if (is_bool($rewrite['paged']))
                {
                    $rewrite['pages'] = $rewrite['paged'];
                }
                unset($rewrite['paged']);
            }

            return $rewrite;
        }

        /**
         * Define if structure has a pagination
         * 
         * @param array $rewrite
         * @return array $rewrite
         */
        private function setWithFront(array $rewrite)
        {
            $rewrite['with_front'] = false;

            if (isset($rewrite['prefixed']))
            {
                if (is_bool($rewrite['prefixed']))
                {
                    $rewrite['with_front'] = $rewrite['prefixed'];
                }
                unset($rewrite['prefixed']);
            }

            return $rewrite;
        }

        /**
         * Define if is exportable
         * 
         * @param array $post
         * @return array $post
         */
        private function setCanExport(array $post)
        {
            $post['can_export'] = true;

            if (isset($post['exportable']))
            {
                if (is_bool($post['exportable']))
                {
                    $post['can_export'] = $post['exportable'];
                }
                unset($post['exportable']);
            }

            return $post;
        }

        /**
         * Define the endpoint mask
         * 
         * @param array $rewrite
         * @return array $rewrite
         */
        private function setEndPointMask(array $rewrite)
        {
            $rewrite['ep_mask'] = "EP_PERMALINK";

            if (isset($rewrite['endpoint']))
            {
                if (is_string($rewrite['endpoint']) && in_array($rewrite['endpoint'], self::ENDPOINT_MASK))
                {
                    $rewrite['ep_mask'] = $rewrite['endpoint'];
                }
                unset($rewrite['endpoint']);
            }

            return $rewrite;
        }

        /**
         * Define query var
         * 
         * @param array $query
         * @param array $post
         * @return array $post
         */
        private function setQueryVar(array $query, array $post)
        {
            $post['query_var'] = $post['type'];

            if (isset($query['var']) && (is_bool($query['var']) || is_string($query['var'])))
            {
                $post['query_var'] = $query['var'];
            }

            return $post;
        }

        /**
         * Define rewrite rules
         * 
         * @param array $post
         * @return array $post
         */
        private function setRewrite(array $post)
        {
            // Rewrite default value
            if (!isset($post['rewrite']) || (!is_bool($post['rewrite']) && !is_array($post['rewrite'])))
            {
                $post['rewrite'] = true;
            }

            // Is an array
            if (is_array($post['rewrite']))
            {
                $rewrite = $post['rewrite'];

                // Define the Slug
                $rewrite = $this->setSlug($rewrite, $post);

                // Define if prefixed (with_front)
                $rewrite = $this->setWithFront($rewrite);

                // Define if has feed
                $rewrite = $this->setFeeds($rewrite, $post);

                // Define if has pagination
                $rewrite = $this->setPages($rewrite);

                // Define EndPoint Mask
                $rewrite = $this->setEndPointMask($rewrite);

                $post['rewrite'] = $rewrite;
            }

            return $post;
        }

        /**
         * Define Query rules
         * 
         * @param array $post
         * @return array $post
         */
        private function setQuery(array $post)
        {
            // Default query rules
            if (!isset($post['query']) || (!is_bool($post['query']) && !is_array($post['query'])))
            {
                $post['exclude_from_search'] = !$post['public'];
                $post['publicly_queryable'] = $post['public'];
                $post['query_var'] = $post['type'];
                $post['delete_with_user'] = null;
            }

            if (isset($post['query']) && is_array($post['query']))
            {
                $query = $post['query'];

                // Define Query_var
                $post = $this->setQueryVar($query, $post);

                // Is Exclude from search
                $post = $this->setExcludeFromSearch($query, $post);
    
                // Is publicly Queryable
                $post = $this->setPubliclyQueryable($query, $post);

                // Delete with user
                $post = $this->setDeleteWithUser($query, $post);

            }

            return $post;
        }

        /**
         * Define if delete custom posts with user
         * 
         * @param array $query
         * @param array $post
         * @return array $post
         */
        private function setDeleteWithUser(array $query, array $post)
        {
            $post['delete_with_user'] = null;

            if (isset($query['delete_with_user']) && is_bool($query['delete_with_user']))
            {
                $post['delete_with_user'] = $query['delete_with_user'];
            }

            return $post;
        }

        /**
         * Define _edit_link
         * 
         * @param array $post
         * @return array $post
         */
        private function setEditLink(array $post)
        {
            $post['_edit_link'] = 'post.php?post=%d';

            if (isset($post['ui']['pages']['edit']['link']) && is_string($post['ui']['pages']['edit']['link']))
            {
                $post['_edit_link'] = $post['ui']['pages']['edit']['link'];
            }

            return $post;
        }

        /**
         * Define Capabilities
         * 
         * @param array $post
         * @return array $post
         */
        private function setCapability(array $post)
        {
            if (!isset($post['capability']) || (!is_bool($post['capability']) && !is_array($post['capability'])))
            {
                $post['capability_type'] = 'post';
                $post['capabilities'] = [];
            }

            if (isset($post['capability']) && is_array($post['capability']))
            {
                $cap = $post['capability'];

                // Define Capability Type
                $post = $this->setCapabilityType($cap, $post);

                // Define Capabilities
                $post = $this->setCapabilities($cap, $post);
            }

            return $post;
        }

        /**
         * Define Capability type
         * 
         * @param array $capability
         * @param array $post
         * @return array $post
         */
        private function setCapabilityType(array $capability, array $post)
        {
            $post['capability_type'] = 'post';

            if (isset($capability['type']) && in_array($capability['type'], self::CAPABILITY_TYPE))
            {
                $post['capability_type'] = $capability['type'];
            }

            else if (isset($capability['type']) && preg_match("/^@/", $capability['type']))
            {
                $type = preg_replace("/^@/", null, $capability['type']);

                switch ($type)
                {
                    case 'type':
                        $post['capability_type'] = $post['type'];
                        break;
                }
            }

            return $post;
        }

        /**
         * Define Capabilities
         * 
         * @param array $capability
         * @param array $post
         * @return array $post
         */
        private function setCapabilities(array $capability, array $post)
        {
            // Default capabilities
            if (!isset($capability['capablilities']))
            {
                $type = $post['capability_type'];

                $post['capablilities'] = [
                    'edit_post' => 'edit_'.$type,
                    'read_post' => 'read_'.$type,
                    'delete_post' => 'delete_'.$type,
                    'edit_posts' => 'edit_'.$type.'s',
                    'edit_others_posts' => 'edit_others_'.$type.'s',
                    'publish_posts' => 'publish_'.$type.'s',
                    'read_private_posts' => 'read_private_'.$type.'s',
                ];
            }

            // TODO: Custom Capabilities

            return $post;
        }

        /**
         * Define Supports
         * 
         * @param array $supports
         * @param array $post
         * @return array $post
         */
        private function setSupports($supports, array $post)
        {
            // Default Supports
            $post['supports'] = self::SUPPORTS;

            if (is_array($supports)) 
            {
                foreach ($supports as $support) 
                {
                    if (isset($support['key']))
                    {
                        // Default display
                        $display = in_array($support['key'], self::SUPPORTS) ? true : false;

                        // Define $display
                        if (isset($support['display']) && is_bool($support['display'])) 
                        {
                            $display = $support['display'];
                        }

                        if (!$display && in_array($support['key'], self::SUPPORTS)) 
                        {
                            $index = array_search($support['key'], $post['supports']);
                            unset($post['supports'][$index]);
                        }
                        else if ($display && !in_array($support['key'], $post['supports'])) 
                        {
                            array_push($post['supports'], $support['key']);
                        }
                    }
                }
            }

            // Add an empty string entry to $post['support] if this array is 
            // empty, to prevent the default WP supports.
            if (empty($post['supports']))
            {
                array_push($post['supports'], '');
                $this->bs->codeInjection('head', "<style>#post-body-content {margin-bottom: 0px;}</style>");
            }

            return $post;
        }

        /**
         * Verify the validity of the Type of a custom post
         * 
         * @param array $post
         * @return bool
         */
        private function isValidType(array $post)
        {
            // Default $type
            $type = null;

            // Default Is Valid (true)
            $isValid = true;

            // Default error message
            $errorMessage = null;

            // Retrieve the type
            if (isset($post['type']) && is_string($post['type'])) {
                $type = $post['type'];
            }

            // Define error if $type is empty
            if (empty($type)) {
                $isValid = false;
                $errorMessage = "<strong>Invalid Post Type</strong> : The post type can't be empty.";
            }

            // Define error if $type length > 20 chars
            if (strlen($type) > self::TYPE_MAX_SIZE) {
                $isValid = false;
                $errorMessage = "<strong>Invalid Post Type</strong> : The post type (".$post['type'].") must have ".self::TYPE_MAX_SIZE." chars max.";
            }

            // Set error
            if (!$isValid) 
            {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return $isValid;
        }

        /**
         * Verify the validity of the Name of a custom post
         * 
         * @param array $post
         * @return bool
         */
        private function isValidLabel(array $post)
        {
            // Default $label
            $label = null;

            // Default Is Valid (true)
            $isValid = true;

            // Default error message
            $errorMessage = null;

            // Retrieve the label
            if (isset($post['name']) && is_string($post['name'])) {
                $label = $post['name'];
            }

            // Define error if $type is empty
            if (empty($label)) {
                $isValid = false;
                $errorMessage = "<strong>Invalid Post Name</strong> : The post name can't be empty.";
            }

            // Set error
            if (!$isValid) 
            {
                trigger_error($errorMessage, E_USER_WARNING);
            }

            return $isValid;
        }

        // -- Admin Columns

        public function setAdminColumns($_columns)
        {
            $columns = array();
            $post = $this->getPost();

            // Define Columns
            if (isset($post['ui']['pages']['index']['columns'])) 
            {
                $columns = $post['ui']['pages']['index']['columns'];
            }

            foreach ($columns as $column) 
            {
                if (
                    isset($column['key']) && is_string($column['key']) && 
                    isset($column['display']) && is_bool($column['display']) &&
                    false === $column['display']
                ) 
                {
                    unset($_columns[$column['key']]);
                }
            }

            return $_columns;
        }

        public function setAdminColumnsData($columns, $postID)
        {
            
        }

        public function setAdminColumnsSortable($columns)
        {
            return $columns;
        }

        /**
         * Set actions for items row in Admin table
         */
        public function setAdminRowActions($_actions)
        {
            // Retieve Post config
            $post = $this->getPost();

            // Default 
            $edit = true;
            $inline = true;
            $trash = true;
            $view = true;

            // Rertieve Actions
            if (isset($post['ui']['pages']['index']['rows']['actions']))
            {
                if (false === $post['ui']['pages']['index']['rows']['actions'])
                {
                    $edit = false;
                    $inline = false;
                    $trash = false;
                    $view = false;
                }
                elseif (is_array($post['ui']['pages']['index']['rows']['actions']))
                {
                    if (isset($post['ui']['pages']['index']['rows']['actions']['edit']) && is_bool($post['ui']['pages']['index']['rows']['actions']['edit']))
                    {
                        $edit = $post['ui']['pages']['index']['rows']['actions']['edit'];
                    }
                    if (isset($post['ui']['pages']['index']['rows']['actions']['inline']) && is_bool($post['ui']['pages']['index']['rows']['actions']['inline']))
                    {
                        $inline = $post['ui']['pages']['index']['rows']['actions']['inline'];
                    }
                    if (isset($post['ui']['pages']['index']['rows']['actions']['trash']) && is_bool($post['ui']['pages']['index']['rows']['actions']['trash']))
                    {
                        $trash = $post['ui']['pages']['index']['rows']['actions']['trash'];
                    }
                    if (isset($post['ui']['pages']['index']['rows']['actions']['view']) && is_bool($post['ui']['pages']['index']['rows']['actions']['view']))
                    {
                        $view = $post['ui']['pages']['index']['rows']['actions']['view'];
                    }
                }
            }

            if (!$edit)   unset( $_actions['edit'] );
            if (!$inline) unset( $_actions['inline hide-if-no-js'] );
            if (!$trash)  unset( $_actions['trash'] );
            if (!$view)   unset( $_actions['view'] );

            return $_actions;
        }

        // -- Post Submission

        public function postValidation($_PID)
        {
            if (wp_is_post_revision($_PID))
            {
                return;
            }
            
            $response = new Response( $this->bs, $this->getPost(), $_PID);
            $this->response = $response->response();

            if ($this->response->validate())
            {
                add_action('save_post', array($this, "postSubmission"));
            }
            else 
            {
                header('Location: '.get_edit_post_link($_PID, 'redirect'));
                exit;
            }
        }

        public function postSubmission($_PID)
        {
            // -- Post Title replacement

            $post = $this->getPost();

            $metaboxes = new Metaboxes($this->bs, $post);
            $supports = $metaboxes->getSupports();
            $glue = " ";

            foreach ($supports as $support) 
            {
                if ('title' == $support['key'] && !$support['display'] && isset($support['replace']))
                {
                    if (isset($support['glue']))
                    {
                        $glue = $support['glue'];
                    }

                    $replacements = $support['replace'];
                    $replacements_val = [];

                    if (!is_array($replacements))
                    {
                        $replacements = [$replacements];
                    }

                    // Check if replacement field (schema) exists
                    foreach ($replacements as $replacement_key) 
                    {
                        foreach ($this->response->getSchema() as $item) 
                        {
                            if ($replacement_key == $item['key'] && 'password' != $item['type'])
                            {
                                array_push($replacements_val, $item['value']);
                            }
                        }
                    }

                    $replacement = trim(implode($glue, $replacements_val));

                    // if $_POST['post_type'] == $post['type']
                    global $wpdb;
                    $wpdb->update( $wpdb->posts, [
                        'post_title' => $replacement
                    ],[
                        'ID' => $_PID
                    ]);

                }
            }
            

            // -- Save Post Meta 

            foreach ($this->response->getSchema() as $item) 
            {
                // If  item type == file
                
                update_post_meta($_PID, $item['key'], $item['value']);
            }


            // echo "<pre>";
            // print_r(
            //     $this->response->getSchema()
            // );
            // echo "</pre>";

            
        }

        /**
         * Clear Post Session
         */
        public function clearPostSession( $post = null )
        {
            if (null == $post)
            {
                do_action('clear_post_session');
                return;
            }
    
            $session = new Session($this->bs->getNamespace());
            $session->clear($post['type']);
    
            $notices = new Notices($this->bs->getNamespace());
            $notices->clear();
        }
    }
}