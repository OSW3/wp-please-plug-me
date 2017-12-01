<?php

if (!class_exists('PPM'))
{
    abstract class PPM
    {
        private $path;
        private $url;
        private $basename;
        private $name;
        private $settingsNamespace;
        private $namespace;
        private $config;
        private $prefix;
        private $prefixTable;
        private $version;
        private $description;
        private $assetsFrontStyles;
        private $assetsFrontScripts;
        private $assetsAdminStyles = [[
            "name" => "bxslider",
            "path" => "https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css"
        ],[
            "name" => "ppm",
            "path" => "ppm"
        ]];
        private $assetsAdminScripts = [[
            "name" => "jquery-3.1.1",
            "path" => "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"
        ],[
            "name" => "bxslider",
            "path" => "https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"
        ],[
            "name" => "ppm",
            "path" => "ppm"
        ]];
        private $shortcodes;
        private $hooks;
        private $imagesSizes;
        private $menus;
        private $submenus;
        private $options = [];
        private $register;
        private $schemas;
        private $settings;
        private $posts;
        private $plugin_uri;
        
        /**
         * Constructor
         */
        public function __construct( $directory=null )
        {
            session_start();
            
            // Define the plugin path
            $this->setPath( $directory );
            $this->setUrl( trailingslashit( plugins_url( '/', $this->getPath()."index.php" ) ) );
            
            // Read the configuration
            $this->configuration();

            // Define registers
            $this->setRegister();
            $this->setRegisterPosts();
            $this->setRegisterSettings();
            
            $this->setSchemas();
            $this->setImagesSizes();
            $this->setHooks();
            $this->setMenus();
            $this->setName();
            $this->setNamespace();
            $this->setOptions();
            $this->setPluginUri();
            $this->setPrefix();
            $this->setPrefixTable();
            $this->setShortcodes();
            $this->setVersion();
            $this->setDescription();

            // echo "<pre>";
            // print_r( $this->getConfig() );
            // echo "</pre>";
        }
        
        /**
         * The Installer
         * 
         * Include & Instantiate the PPM_Installer class
         * PPM_Installer contain the activate() & deactivate() methods
         * 
         * @return (object) PPM_Installer
         */
        protected function installer()
        {
            $class_file = __DIR__."/installer.php";
            $class_name = "PPM_Installer";
            $class_params = array(
                "config" => $this->getConfig()
            );
            
            return PPM::include_class( $class_file, $class_name, $class_params );
        }
        
        /**
         * Initialize the plugin
         * The initialisation process when the plugin is activate
         */
        protected function init()
        {
            // -- Assets
            // Assets injections for front
            add_action('wp_print_styles', array($this, 'enqueue_front_styles'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_front_scripts'));
            // Assets injections for Admin
            add_action('admin_print_styles', array($this, 'enqueue_admin_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

            // -- Hooks & Shortcodes
            if ($this->has_functions_file())
            {
                // Hooks injections
                foreach ($this->getHooks() as $function => $event)
                {
                    if ( function_exists($function) )
                    {
                        $order = $event == 'init' ? 1 : 10;
                        add_action($event, $function, $order);
                    }
                }
                
                // Shortcodes injections
                foreach ($this->getShortcodes() as $shortcode => $function)
                {
                    if ( function_exists($function) )
                    {
                        add_shortcode($shortcode, $function);
                    }
                }
            }

            // -- Init registers

            $params = array(
                "config" => $this->getConfig()
            );

            // Init Custom Posts register
            PPM::include_class(
                $this->getPath().'ppm/register/posts.php', 
                'PPM_RegisterPosts',
                $params
            );

            // Init Settings register
            PPM::include_class(
                $this->getPath().'ppm/register/settings.php', 
                'PPM_RegisterSettings',
                $params
            );
        }
        

        // Configuration builder ///////////////////////////////////////////////
        
        /**
         * Configuration builder
         */
        private function configuration()
        {
            $config = [];
            $config_json = $this->getPath().'config.json';
            $config_php = $this->getPath().'config.php';

            // Parse config from jSon file
            if (file_exists($config_json))
            {
                $config_json = file_get_contents($config_json);
                $config_json = json_decode($config_json, true);

                $config = array_merge( $config, $config_json );
            }
            else if (file_exists($config_php))
            {
                include_once $config_php;
            }

            // Parse config from Index.php
            $config = array_merge(
                $config,
                self::configuration_index($this->getPath())
            );

            $this->config = (object) $config;
        }

        /**
         * Get configuration from the index.php
         */
        private static function configuration_index( $path )
        {
            $config = [];

            $config_php = $path."index.php";
            
            if (file_exists($config_php)) 
            {
                $config_php = file_get_contents($config_php);
                
                preg_match(
                    "/\/\*\*(.*)\*\//uis", 
                    $config_php, 
                    $config_params
                );
                $config_params = explode("* ", $config_params[0]);

                foreach( $config_params as $config_param )
                {
                    list($config_param_key, $config_param_value) = explode(": ", $config_param);
                    $config_param_key   = PPM::slugify($config_param_key, "_");
                    $config_param_value = trim(preg_replace("/\*\//", null, $config_param_value));

                    if (!empty($config_param_key))
                    {
                        $config[$config_param_key] = trim($config_param_value);
                    }
                }
            }

            return $config;
        }
        

        // Gettre / Setter /////////////////////////////////////////////////////
        
        /**
         * Get Plugin Name
         * @return (string)
         */
        public function getConfig()
        {
            return (object) [
                "Name"                  => $this->getName(),
                "Namespace"             => $this->getNamespace(),
                "Classname"             => self::getClassname($this->path),
                "Prefix"                => $this->getPrefix(),
                "PrefixTable"           => $this->getPrefixTable(),
                "Version"               => $this->getVersion(),
                "Description"           => $this->getDescription(),

                "Path"                  => $this->getPath(),
                "Url"                   => $this->getUrl(),
                "PluginUri"             => $this->getPluginUri(),
                "Menus"                 => $this->getMenus(),

                "Hooks"                 => $this->getHooks(),
                "Shortcodes"            => $this->getShortcodes(),

                "Options"               => $this->getOptions(),
                "Registers"             => $this->getRegistersWithoutSchemas(),
                "Schemas"               => $this->getSchemas(),
                "ImagesSizes"           => $this->getImagesSizes(),

                "AssetsStyles"          => $this->getAssetsStyles(),
                "AssetsScripts"         => $this->getAssetsScripts(),
                "AssetsAdminStyles"     => $this->getAssetsAdminStyles(),
                "AssetsAdminScripts"    => $this->getAssetsAdminScripts(),
            ];
        }

        
        /**
         * Get Plugin Classname
         * @return (string)
         */
        public static function getClassname()
        {
            $base = self::base(__DIR__);
            $conf = self::configuration_index( $base."/" );

            return self::slugify(implode(" ", [
                $conf['author'],
                $conf['plugin_name']
            ]), "_");
        }


        /**
         * Set Plugin Hooks
         */
        private function setHooks()
        {
            $this->hooks = isset($this->config->hooks) 
                ? (object) $this->config->hooks 
                : (object) [];
        }
        /**
         * Get Plugin Hooks
         * @return (object)
         */
        public function getHooks()
        {
            return $this->hooks;
        }
        

        /**
         * Set Plugin ImagesSizes
         */
        private function setImagesSizes()
        {
            $posts = $this->posts;
            $settings = $this->settings;
            $imagesSizes = [];

            // Get ImagesSizes for the Settings Register
            if (isset($settings->thumbnails) && is_array($settings->thumbnails) && !empty($settings->thumbnails))
            {
                $thumbnails = (object) $settings->thumbnails;

                if (!isset( $imagesSizes['Settings'] )) $imagesSizes['Settings'] = [];

                if (isset($thumbnails->sizes))
                {
                    $imagesSizes['Settings']['strict'] = isset($thumbnails->strict) ? $thumbnails->strict : false;
                    $imagesSizes['Settings']['preserve_wp_sizes'] = isset($thumbnails->preserve_wp_sizes) ? $thumbnails->preserve_wp_sizes : true;
                    $imagesSizes['Settings']['sizes'] = $this->checkImagesSizes($thumbnails->sizes);
                }
                else
                {
                    $imagesSizes['Settings']['strict'] = false;
                    $imagesSizes['Settings']['preserve_wp_sizes'] = true;
                    $imagesSizes['Settings']['sizes'] = $this->checkImagesSizes($thumbnails);
                }
            }
            
            // Get ImagesSizes for the Custom posts Register
            foreach ($posts as $post)
            {
                $post = (object) $post;

                if (isset($post->thumbnails) && is_array($post->thumbnails) && !empty($post->thumbnails))
                {
                    $thumbnails = (object) $post->thumbnails;

                    if (!isset( $imagesSizes['CustomPosts'] )) $imagesSizes['CustomPosts'] = [];
                    if (!isset( $imagesSizes['CustomPosts'][$post->type] )) $imagesSizes['CustomPosts'][$post->type] = [];

                    if (isset($thumbnails->sizes))
                    {
                        $imagesSizes['CustomPosts'][$post->type]['strict'] = isset($thumbnails->strict) ? $thumbnails->strict : false;
                        $imagesSizes['CustomPosts'][$post->type]['preserve_wp_sizes'] = isset($thumbnails->preserve_wp_sizes) ? $thumbnails->preserve_wp_sizes : true;
                        $imagesSizes['CustomPosts'][$post->type]['sizes'] = $this->checkImagesSizes($thumbnails->sizes);
                    }
                    else
                    {
                        $imagesSizes['CustomPosts'][$post->type]['strict'] = false;
                        $imagesSizes['CustomPosts'][$post->type]['preserve_wp_sizes'] = true;
                        $imagesSizes['CustomPosts'][$post->type]['sizes'] = $this->checkImagesSizes($thumbnails);
                    }
                }
            }


            // Retrieve Thumbnails filters only
            $filters = [];
            if (is_array($imagesSizes['Settings']['sizes']))
            {
                $filters = array_merge($filters, $imagesSizes['Settings']['sizes']);
            }
            if (is_array($imagesSizes['CustomPosts']))
            {
                foreach ($imagesSizes['CustomPosts'] as $CustomPosts)
                {
                    $filters = array_merge($filters, $CustomPosts['sizes']);
                }
            }


            // Add images Sizes to wp register
            foreach ($filters as $filter)
            {
                add_image_size( 
                    $filter['name'], 
                    $filter['width'], 
                    $filter['height'], 
                    $filter['crop'] 
                );
            }
            
            $this->imagesSizes = (object) $imagesSizes;
        }
        /**
         * Check Image Sizes
         */
        private function checkImagesSizes( $sizes )
        {
            foreach ($sizes as $key => $data)
            {
                if (
                    (isset($data['name']) && is_string($data['name'])) &&
                    (isset($data['width']) && is_integer($data['width'])) &&
                    (isset($data['height']) && is_integer($data['height']))
                )
                {
                    $crop = false;

                    if (isset($data['crop']) && (is_bool($data['crop']) || is_array($data['crop'])))
                    {
                        $crop = $data['crop'];
                    }

                    $sizes[$key] = [
                        "name" => $data['name'],
                        "width" => $data['width'],
                        "height" => $data['height'],
                        "crop" => $crop
                    ];
                }
            }
            return $sizes;
        }
        /**
         * Get Plugin ImagesSizes
         * @return (object)
         */
        public function getImagesSizes()
        {
            return $this->imagesSizes;
        }


        /**
         * Set Plugin Name
         * The name of the plugin was defined in the index.php of the plugin at the line "Plugin Name"
         */
        private function setName()
        {
            $this->name = isset($this->config->plugin_name) 
                ? $this->config->plugin_name 
                : null;
        }
        /**
         * Get Plugin Name
         * @return (string)
         */
        public function getName()
        {
            return $this->name;
        }


        /**
         * Set Plugin NameSpace
         * The namespace of the plugin was defined in the config.json as the line "namespace"
         * If the namespace is not set in the config file, The plugin namespace will automatically defined by the plugin name's slug (with underscore)
         */
        private function setNamespace()
        {
            $this->namespace = isset($this->config->namespace) 
                ? self::slugify($this->config->namespace, "_")
                : self::slugify($this->getName(), "_");
        }
        /**
         * Get Plugin NameSpace
         * @return (string)
         */
        public function getNamespace()
        {
            return $this->namespace;
        }
        

        /**
         * Set Plugin Menus
         * Define the menus location for the plugin
         */
        private function setMenus()
        { 
            $this->menus = [
                "locations" => [],
                "icon" => null
            ];

            $menus = isset($this->settings->menus ) 
                ? (object) $this->settings->menus 
                : (object) [];

            if (true === $menus->admin) array_push($this->menus['locations'], "admin");
            if (true === $menus->action) array_push($this->menus['locations'], "action");
            if (true === $menus->settings) array_push($this->menus['locations'], "settings");

            if (isset($menus->icon))
            {
                if (preg_match("/^image:/", $menus->icon))
                {
                    $this->menus['icon'] = $this->getUrl()."assets/images/".preg_replace("/^image:/", null, $menus->icon);
                }
                else 
                {
                    $this->menus['icon'] = $menus->icon;
                }
            }
        }
        /**
         * Get Plugin Menus
         * @return (string)
         */
        public function getMenus()
        {
            return $this->menus;
        }
        

        /**
         * Set Plugin Options
         */
        private function setOptions( $data=null )
        {
            if (null === $data)
            {
                $data = $this->getSchemas();
            }

            foreach ($data as $key => $value) {
                if (is_array($value))
                {
                    $this->setOptions($value);
                }
                else {
                    if ('default' === $key)
                    {
                        $this->options[$data['key']] = $value;
                    }
                }
            }

            if (isset($this->config->options))
            {
                $this->options = array_merge(
                    $this->options,
                    $this->config->options
                );
            }
        }
        /**
         * Get Plugin Options
         * @return (string)
         */
        public function getOptions( $key=null )
        {
            if (null !== $key) {
                return isset($this->options->$key) 
                    ? $this->options->$key 
                    : null;
            }
            
            return $this->options;
        }
        

        /**
         * Set Plugin Path
         */
        private function setPath( $path )
        {
            $this->path = $path.DIRECTORY_SEPARATOR;
        }
        /**
         * Get Plugin Path
         * @return (string) something like : /var/www/my_webstie/wp-content/plugins/plugin-name/
         */
        public function getPath()
        {
            return $this->path;
        }
        

        /**
         * Set Plugin URI
         * The name of the plugin was defined in the index.php of the plugin at the line "Plugin Name"
         */
        private function setPluginUri()
        {
            $this->plugin_uri = isset($this->config->plugin_uri) ? $this->config->plugin_uri : null;
        }
        /**
         * Get Plugin URI
         * @return (string)
         */
        public function getPluginUri()
        {
            return $this->plugin_uri;
        }


        /**
         * Set Plugin Prefix
         */
        private function setPrefix()
        {
            $this->prefix = $this->getNamespace()."_";
        }
        /**
         * Get Plugin Prefix
         * @return (string)
         */
        public function getPrefix()
        {
            return $this->prefix;
        }
        

        /**
         * Set Plugin Database Table Prefix
         */
        private function setPrefixTable()
        {
            global $wpdb;
            $this->prefixTable =  $wpdb->prefix.$this->getPrefix();
        }
        /**
         * Get Plugin Database Table Prefix
         * @return (string)
         */
        public function getPrefixTable()
        {
            return $this->prefixTable;
        }
        

        /**
         * Set Plugin Schemas
         */
        private function setSchemas()
        {
            $posts = $this->posts;
            $settings = $this->settings;
            $schemas = (object) [];

            // Build Custom posts schema
            foreach ($posts as $post)
            {
                if (isset($post['type']) && !empty($post['type']))
                {
                    if (isset($post['metas']))
                    {
                        $schemas->CustomPosts[$post['type']] = $post['metas'];
                    }
                }
            }

            // Build Settings schema
            $schemas->Settings = [];
            if (isset($settings->schema) && !empty($settings->schema))
            {
                foreach ($settings->schema as $section_key => $section_data)
                {
                    if (isset($section_data['schema']) && !empty($section_data['schema']))
                    {
                        array_push(
                            $schemas->Settings, 
                            $section_data
                        );
                    }
                }
            }

            $this->schemas = $schemas;
        }
        /**
         * Get Plugin Schemas
         * @return (object)
         */
        public function getSchemas()
        {
            return $this->schemas;
        }
        

        /**
         * Set Plugin Shortcodes
         */
        private function setShortcodes()
        {
            $this->shortcodes = isset($this->config->shortcodes) 
                ? (object) $this->config->shortcodes 
                : (object) [];
        }
        /**
         * Get Plugin Shortcodes
         * @return (object)
         */
        public function getShortcodes()
        {
            return $this->shortcodes;
        }
        

        /**
         * Set Plugin URL
         */
        private function setUrl( $url )
        {
            $this->url = preg_replace("/ppm\/$/", null, $url);
        }
        /**
         * Get Plugin URL
         * @return (string) something like : http://my-website.com/wp-content/plugins/plugin-name/
         */
        public function getUrl()
        {
            return $this->url;
        }
        

        /**
         * Set Plugin Version
         */
        private function setVersion()
        {
            $this->version = isset($this->config->version) ? $this->config->version : null;
        }
        /**
         * Get Plugin Version
         * @return (string)
         */
        public function getVersion()
        {
            return $this->version;
        }
        

        /**
         * Set Plugin Description
         */
        private function setDescription()
        {
            $this->description = isset($this->config->description) ? $this->config->description : null;
        }
        /**
         * Get Plugin Description
         * @return (string)
         */
        public function getDescription()
        {
            return $this->description;
        }


        /**
         * Assets
         */
        private function setAssets( $type, $side="front" )
        {
            // Admin assets
            if ("admin" === strtolower($side))
            {
                if (isset($this->config->assets_admin[$type]))
                    return $this->config->assets_admin[$type];
            }

            // Front assets
            else
            {
                if (isset($this->config->assets[$type]))
                    return $this->config->assets[$type];
            }

            return [];
        }
        public function getAssetsStyles()
        {
            return $this->setAssets("styles", "front");
        }
        public function getAssetsScripts()
        {
            return $this->setAssets("scripts", "front");
        }
        public function getAssetsAdminStyles()
        {
            return array_merge($this->assetsAdminStyles, $this->setAssets("styles", "admin"));
        }
        public function getAssetsAdminScripts()
        {
            return array_merge($this->assetsAdminScripts, $this->setAssets("scripts", "admin"));
        }
        
        
        /**
         * The Register (Settings & Custom Posts)
         */
        private function setRegister()
        {
            $this->register = isset($this->config->register)
                ? (object) $this->config->register
                : (object) [];
        }
        /**
         * Register : Posts
         */
        private function setRegisterPosts()
        {
            $this->posts = isset($this->register->posts)
                ? (object) $this->register->posts
                : (object) [];
        }
        /**
         * Register : Settings
         */
        private function setRegisterSettings()
        {
            $this->settings = isset($this->register->settings)
                ? (object) $this->register->settings
                : (object) [];
        }
        /**
         * Custom Post infos exept schemas
         */
        protected function getCustomPosts()
        {
            $posts = (array) $this->posts;

            foreach ($posts as $key => $post)
            {
                if (isset($post['metas']))
                {
                    unset($posts[$key]['metas']);
                }
            }

            return $posts;
        }
        private function getSettings()
        {
            $settings = (array) $this->settings;

            if (!isset($settings['view']) || $settings['view'] !== true)
            {
                $settings['view'] = false;
            }

            if (isset($settings['schema']))
            {
                unset($settings['schema']);
            }
            return $settings;
        }
        private function getRegistersWithoutSchemas()
        {

            return (object) array(
                "Settings" => (object) $this->getSettings(),
                "CustomPosts" => (object) $this->getCustomPosts()
            );
        }
        
        // Utils ///////////////////////////////////////////////////////////////
        
        /**
         * Include Classe, its dependencies & instantiate
         * @param (string) $file
         * @param (string) $className
         * @param (string) $params
         * @param (string) $dependencies
         */
        public static function include_class( $file, $className, $params=[], $dependencies=[] )
        {
            // Load dependencies
            foreach ($dependencies as $dependency) 
            {
                if (file_exists($dependency))
                {
                    require_once($dependency);
                }
            }

            // Load class
            if (file_exists($file))
            {
                require_once($file);

                if (is_array($className))
                {
                    $method = isset($className[1]) ? $className[1] : null;
                    $args = (isset($className[2]) && is_array($className[2])) ? $className[2] : [];
                    $className = $className[0];

                    if (class_exists($className))
                    {
                        if (null === $method)
                        {
                            return new $className( $params );
                        }
                        else
                        {
                            return $className::$method( $params );
                        }
                    }
                }
                else
                {
                    if (class_exists($className))
                    {
                        return new $className( $params );
                    }
                }

            }
        }

        /**
         * Slugify
         * Generate & return a slug string
         */
        public static function slugify( $text, $separator="-" )
        {
            $text = preg_replace('~[^\pL\d]+~u', $separator, $text);
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = preg_replace('~[^-\w]+~', '', $text);
            $text = trim($text, $separator);
            $text = preg_replace('~-+~', $separator, $text);
            $text = strtolower($text);

            if (empty($text)) return false;

            return $text;
        }

        /**
         * Has functions file
         * Check if the plugin have the file "functions.php"
         */
        private function has_functions_file()
        {
            $file = $this->getPath().'functions.php';

            if (file_exists($file))
            {
                require_once($file);
                return true;
            }

            return false;
        }

        /**
         * Try To Do
         * Try to execute a function
         */
        public static function tryToDo( $expression )
        {
            list($a, $b) = explode(":", $expression);

            if ("do" === $a && function_exists($b)) {
                return $b();
            }

            else if ("menu" === $a) {
                $o = [];
                $items = wp_get_nav_menu_items($b);

                if (is_array($items))
                {
                    foreach ($items as $item) 
                    {
                        $o[ PPM::slugify($item->title) ] = $item->title;
                    }
                }
                return $o;
            }

            return false;
        }

        public static function base( $directory )
        {
            return preg_replace("/\/ppm$/", null, $directory);
        }

        public static function notice( $type, $message, $dismissible=true )
        {
            $output = null;
            $class = [];

            array_push($class, "notice");
            array_push($class, "notice-".$type);
            ($dismissible) ? array_push($class, "is-dismissible") : null;

            $output.= '<h2 style="display: none;"></h2>';
            $output.= '<div class="'. implode(" ", $class) .'">';
            $output.= "<p>". $message ."</p>";
            $output.= '</div>';

            return $output;
        }

        public static function exptrim( $delimiter, $string )
        {
            $array = explode($delimiter, $string);
            
            foreach ($array as $key => $value)
            {
                $array[$key] = trim($value);
            }

            return $array;
        }
        
        // Assets //////////////////////////////////////////////////////////////

        public function enqueue_front_styles()
        {
            if (!is_admin()) $this->enqueue_assets(
                $this->getAssetsStyles(),
                "css"
            );
        }
        public function enqueue_front_scripts()
        {
            if (!is_admin()) $this->enqueue_assets(
                $this->getAssetsScripts() ,
                "js"
            );
        }
        public function enqueue_admin_styles()
        {
            if (is_admin()) $this->enqueue_assets(
                $this->getAssetsAdminStyles(),
                "css"
            );
        }
        public function enqueue_admin_scripts()
        {
            if (is_admin()) $this->enqueue_assets(
                $this->getAssetsAdminScripts() ,
                "js"
            );
        }

        private function enqueue_assets( $assets, $type )
        {
            $directory = "assets/$type/";
            $path = $this->getPath().$directory;
            $url = $this->getUrl().$directory;

            foreach ($assets as $asset)
            {
                $asset_id   = isset($asset['name']) ? $asset['name'] : uniqid();
                $asset_file = isset($asset['path']) ? $asset['path'] : null;
                $asset_dep  = [];

                if (!empty($asset_file))
                {
                    $asset_filename = $asset_file.".".$type;
                    
                    if (file_exists($path.$asset_filename))
                    {
                        if ($type === "css") 
                            wp_enqueue_style($asset_id, $url.$asset_filename);
                        
                        elseif ($type === "js") 
                            wp_enqueue_script($asset_id, $url.$asset_filename, $asset_dep);
                    }
                    else if (preg_match("/^http(s)?/i", $asset_file))
                    {
                        if ($type === "css") 
                            wp_enqueue_style($asset_id, $asset_file);
                        
                        elseif ($type === "js") 
                            wp_enqueue_script($asset_id, $asset_file, $asset_dep);
                    }
                }
            }
        }
        
        // Form Requests ///////////////////////////////////////////////////////

        public static function responses( $params )
        {
            $config = $params['config'];
            $schema = $params['schema'];
            $request = $_REQUEST[$config->Namespace];
            $files = !empty($_FILES[$config->Namespace]) ? $_FILES[$config->Namespace] : [];
            $responses = [];

            // Extract fields form sections of the schema
            foreach ($schema as $section)
            {
                if (isset($section["schema"]))
                {
                    foreach ($section['schema'] as $field) 
                    {
                        // Default field settings
                        $field['allowed_types'] = isset($field['allowed_types']) ? $field['allowed_types'] : null;
                        $field['class']         = isset($field['class']) ? $field['class'] : false;
                        $field['choices']       = isset($field['choices']) ? $field['choices'] : false;
                        $field['cols']          = isset($field['cols']) ? $field['cols'] : false;
                        $field['default']       = isset($field['default']) ? $field['default'] : null;
                        $field['disabled']      = isset($field['disabled']) ? $field['disabled'] : false;
                        $field['expanded']      = isset($field['expanded']) ? $field['expanded'] : false;
                        $field['file']          = isset($field['file']) ? $field['file'] : null;
                        $field['helper']        = isset($field['helper']) ? $field['helper'] : null;
                        $field['key']           = isset($field['key']) ? $field['key'] : null;
                        $field['label']         = isset($field['label']) ? $field['label'] : null;
                        $field['max']           = isset($field['max']) ? $field['max'] : null;
                        $field['min']           = isset($field['min']) ? $field['min'] : null;
                        $field['multiple']      = isset($field['multiple']) ? $field['multiple'] : false;
                        $field['readonly']      = isset($field['readonly']) ? $field['readonly'] : false;
                        $field['required']      = isset($field['required']) ? $field['required'] : false;
                        $field['rows']          = isset($field['rows']) ? $field['rows'] : false;
                        $field['rule']          = isset($field['rule']) ? $field['rule'] : false;
                        $field['size']          = isset($field['size']) ? $field['size'] : 0;
                        $field['step']          = isset($field['step']) ? $field['step'] : null;
                        $field['type']          = isset($field['type']) ? $field['type'] : "text";
                        $field['error']         = false;
                        $field['thumbnails']    = false;
                        // $field['value']     = isset($field['value']) ? $field['value'] : $request[$field['key']];
                        // $field['ID'] = isset($field['ID']) ? $field['ID'] : false;
                        // $field['section'] = isset($field['section']) ? $field['section'] : false;

                        // Format data
                        if (!is_array($field['allowed_types']) && null !== $field['allowed_types'])
                        {
                            $field['allowed_types'] = self::exptrim(",", $field['allowed_types']);
                        }

                        // Add response to the field params
                        switch ($field['type'])
                        {
                            // Define checkbox value to ON or OFF
                            case 'checkbox':
                                $field['value'] = isset($request[$field['key']]) ? "on" : "off";
                                break;

                            // Hash the Password
                            case 'password':
                                $field['value'] = !empty($request[$field['key']]) 
                                    ? password_hash($request[$field['key']], PASSWORD_DEFAULT) 
                                    : null;
                                break;
                                
                            // Retrieve file data
                            case 'file':
                                if (!empty($files['name'][$field['key']]))
                                {
                                    $field['files'] = [];
                                    foreach ($files as $key => $file)
                                    {
                                        if (isset($file[$field['key']]))
                                        {
                                            if (!is_array($file[$field['key']]))
                                            {
                                                $field['files'][$key] = [$file[$field['key']]];
                                            }
                                            else
                                            {
                                                $field['files'][$key] = $file[$field['key']];
                                            }

                                            // $field['file'][$key] = $file[$field['key']];

                                            // echo "<pre>";
                                            // print_r($field['file'][$key]);
                                            // print_r($files);
                                            // echo "</pre>";
                                        }
                                    }
                                }
                                break;

                            // Add value
                            default:
                                $field['value'] = $request[$field['key']];
                                break;
                        }
                        
                        if (!empty($field['key']))
                        {
                            $responses[$field['key']] = (object) $field;
                        }
                    }
                }
            }

            return $responses;
        }
        
        /**
         * Format response for wp_options
         */
        public static function responses_sanitized( $responses )
        {
            foreach ($responses as $response)
            {
                $responses[ $response->key ] = $response->value;
            }

            return $responses;
        }

        /**
         * Check response and generate errors tab
         */
        public static function validate( $params )
        {
            $config = $params['config'];
            $responses = $params['responses'];
            $errors = [];

            if (!empty($responses) && is_array($responses))
            {
                foreach ($responses as $response)
                {
                    // Is Required
                    if ($response->required && empty($response->value))
                    {
                        $response->error = true;

                        $errors[$response->key] = array(
                            "field" => $response->key,
                            "message" => "This field is required."
                        );
                    }
                    
                    // Is Email
                    else if ('email' === $response->type && !filter_var($response->value, FILTER_VALIDATE_EMAIL))
                    {
                        $response->error = true;

                        $errors[$response->key] = array(
                            "field" => $response->key,
                            "message" => "This field is not a valid email address."
                        );
                    }
                    
                    // Is url
                    else if ('url' === $response->type && !filter_var($response->value, FILTER_VALIDATE_URL))
                    {
                        $response->error = true;

                        $errors[$response->key] = array(
                            "field" => $response->key,
                            "message" => "This field is not a valid url."
                        );
                    }
                    
                    // Is file
                    else if ('file' === $response->type && !empty($response->files))
                    {
                        $validate_file = self::validate_file( $response );

                        if (true !== $validate_file)
                        {
                            $response->error = true;
                            $errors[$response->key] = $validate_file;
                        }
                    }
                                        
                    // Rule Pattern
                    else if (!empty($response->rule) && !preg_match($response->rule, $response->value))
                    {
                        $response->error = true;

                        $errors[$response->key] = array(
                            "field" => $response->key,
                            "message" => "This field is not valid."
                        );
                    }
                }
            }

            return (object) array(
                "errors" => $errors,
                "isValide" => empty($errors)
            );
        }

        public static function validate_file( $field )
        {
            $files = [];
            $errors = [];

            // Rebuild $files array
            foreach ($field->files['name'] as $key => $file)
            {
                $files[$key] = array(
                    "name" => $field->files['name'][$key],
                    "type" => $field->files['type'][$key],
                    "tmp_name" => $field->files['tmp_name'][$key],
                    "error" => $field->files['error'][$key],
                    "size" => $field->files['size'][$key]
                );
            }

            foreach ($files as $file)
            {
                // Defaults
                $size = false;
                $type = false;


                // -- Check file size

                // No sizes restrictions // Size is not defined
                if (empty($field->size) || $field->size <= 0)
                {
                    $size = true;
                }
                // File size is less or equal to the allowed size
                else if ($file['size'] <= $field->size)
                {
                    $size = true;
                }


                // -- Check file type
    
                // All types are allowed, Allowed type are not defined
                if (empty($field->allowed_types))
                {
                    $type = true;
                }
                // Specific type is allowed
                else if (in_array($file['type'], $field->allowed_types))
                {
                    $type = true;
                }
                // Type like "image/*" is allowed
                else
                {
                    $iniversal_pattern = "/\/\*$/";
                    foreach ($field->allowed_types as $allowed_type)
                    {
                        if (preg_match($iniversal_pattern, $allowed_type))
                        {
                            $allowed_type = preg_replace($iniversal_pattern, null, $allowed_type);
    
                            if (preg_match("/^".$allowed_type."/", $file['type'])) 
                            {
                                $type = true;
                                break;
                            }
                        }
                    }
                }


                // -- Set errors

                if (!$size)
                {
                    array_push( $errors, $file['name']." - File is over size." );
                }
                if (!$type)
                {
                    array_push( $errors, $file['name']." - File type is not allowed." );
                }                
            }

            if (!empty($errors))
            {
                return array(
                    "field" => $file->key,
                    "message" => implode("<br>", $errors)
                );
            }

            return true;
        }

        /**
         * Upload file
         * Check wp-upload add to media and return upload data
         */
        public static function upload( $field, $pid, $config )
        {
            $uploads = [];

            if ('file' === $field->type && !empty($field->files))
            {
                $wp_sizes = ['medium', 'medium_large', 'large'];
                $register = ($pid != null) ? 'CustomPosts' : 'Settings';
                $restrictions = $config->ImagesSizes->$register;
                $posttype = $_REQUEST['post_type'];
                $GLOBALS['preserved_sizes'] = ['thumbnail'];
                $files = [];

                // Thumbnails Restrictions
                if ($register == "CustomPosts")
                {
                    $restrictions = $restrictions[$posttype];
                }
    
                // Rebuild $files array
                foreach ($field->files['name'] as $key => $file)
                {
                    if (!empty($field->files['name'][$key]))
                    {
                        $files[$key] = array(
                            "name" => $field->files['name'][$key],
                            "type" => $field->files['type'][$key],
                            "tmp_name" => $field->files['tmp_name'][$key],
                            "error" => $field->files['error'][$key],
                            "size" => $field->files['size'][$key]
                        );
                    }
                }
    
                foreach ($files as $file)
                {
                    $upload = wp_handle_upload($file, array('test_form' => false));

                    if (isset($upload['error']))
                    {
                        return array(
                            "errors" => array(
                                $file->key => array(
                                    "field" => $file->key,
                                    "message" => $upload['error']
                                )
                            )
                        );
                    }
                    else
                    {
                        // Add to medias list
                        $attach_id = wp_insert_attachment( [
                            'post_mime_type'    => $upload['type'],
                            'post_title'        => addslashes($file['name']),
                            'post_content'      => '',
                            'post_status'       => 'inherit',
                            'post_parent'       => $pid
                        ], $upload['file'], $pid);

                        // Apply Restrictions
                        if (true === $restrictions['strict'])
                        {
                            foreach ($restrictions['sizes'] as $size)
                            {
                                array_push($GLOBALS['preserved_sizes'], $size['name']);
                            }

                            if (true === $restrictions['preserve_wp_sizes'])
                            {
                                $GLOBALS['preserved_sizes'] = array_merge($GLOBALS['preserved_sizes'], $wp_sizes);
                            }

                            add_filter( 'intermediate_image_sizes_advanced', function( $sizes ) 
                            {
                                foreach ($sizes as $size => $_)
                                {
                                    if (!in_array($size, $GLOBALS['preserved_sizes']))
                                    {
                                        unset( $sizes[$size]);
                                    }
                                }

                                return $sizes;
                            } );
                        }

                        // Generate thumbnails
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
                        wp_update_attachment_metadata( $attach_id,  $attach_data );
                        
                        $existing_download = (int) get_post_meta($pid, $key, true);
                        if(is_numeric($existing_download)) {
                            wp_delete_attachment($existing_download);
                        }
                        
                        $upload['attachment'] = $attach_id;

                        array_push($uploads, $upload);
                    }
                }
            }
            
            return $uploads;
        }


// //////////////////////////////////////////////////////////////////////////////////////

        // public static function getPostSchema($configFile, $params)
        // {
        //     $config = [];
        //     $calssName = null;
        //     $attrNameIsArray = false;
            
        //     if (file_exists($configFile))
        //         $config = json_decode(file_get_contents($configFile));

        //     if (isset($config->register->posts))
        //         $config = $config->register->posts;

        //     foreach ($config as $postConfig)
        //         if (isset($postConfig->type) && isset($params['post']) && $postConfig->type == $params['post'])
        //             $config = $postConfig;
            
        //     if (isset($config->view) && $config->view === true)
        //         $attrNameIsArray = true;
            
        //     if (isset($config->metas->schema))
        //         $config = $config->metas->schema;

            
        //     foreach ($config as $field)
        //         if (isset($field->key) && isset($params['key']) && $field->key == $params['key'])
        //             $config = $field;
            
        //     if (isset($config->type))
        //     {
        //         if (isset($params['value']))
        //             $config->value = $params['value'];
                
        //         if (isset($params['disabled']))
        //             $config->disabled = $params['disabled'] === 'true' ? true : false;
                
        //         if (isset($params['class']))
        //             $config->class = $params['class'];

        //         if (isset($params['cols']))
        //             $config->cols = $params['cols'];

        //         if (isset($params['rows']))
        //             $config->rows = $params['rows'];

        //         $className = "PPM_".ucfirst(strtolower($config->type))."Type";
        //         require_once $path.'form/form.php';
        //         require_once $path.'form/'.strtolower($config->type).'.php';

        //         $field = new $className($config);
        //         $render = $field->render( $params['post'], false );

        //         if (!$attrNameIsArray)
        //         {
        //             $render = preg_replace("/".$params['post']."\[(.*)\]/", "$1", $render);
        //         }

        //         return $render;
        //     }
        // }
        // public static function checkPostSchema($configFile, $postType)
        // {
        //     $hasErrors = false;
        //     $_SESSION[$postType] = [];
        //     $typeFiles = [];
        //     $schema = [];

        //     $post = $_POST;
        //     if (isset($_POST[$postType]))
        //     {
        //         $post = $_POST[$postType];
        //     }
            
        //     if($_SERVER['REQUEST_METHOD'] == 'POST' 
        //     && isset($_POST[$postType.'-token'])
        //     && wp_verify_nonce($_POST[$postType.'-token'], $postType)
        //     ) {
        //         if (file_exists($configFile))
        //             $config = json_decode(file_get_contents($configFile));

        //         if (isset($config->register->posts))
        //             $config = $config->register->posts;

        //         foreach ($config as $postConfig)
        //             if (isset($postConfig->type) && $postConfig->type == $postType)
        //                 $config = $postConfig;
                
        //         if (isset($config->metas->schema))
        //             $schema = $config->metas->schema;

        //         // Checking fields
        //         foreach ($schema as $field) 
        //         {
        //             $_SESSION[$postType][$field->key] = [];

        //             if ($field->type === 'file')
        //             {
        //                 $typeFiles[$field->key] = $field;
        //             }
                    
        //             if (isset( $post[$field->key] ))
        //             {
        //                 $_SESSION[$postType][$field->key]['value'] = $post[$field->key];
                        
        //                 // Required
        //                 if (isset($field->required) && $field->required === true && empty($post[$field->key]))
        //                 {
        //                     $_SESSION[$postType][$field->key]['error'] = isset($field->required_message) ? $field->required_message : "This field is required.";
        //                     $hasErrors = true;
        //                 }
                        
        //                 // Is Email
        //                 else if (($field->type == 'email' && !filter_var($post[$field->key], FILTER_VALIDATE_EMAIL)) && (isset($field->required) && $field->required === true))
        //                 {
        //                     $_SESSION[$postType][$field->key]['error'] = isset($field->syntax_error) ? $field->syntax_error : "This field is not a valid email address.";
        //                     $hasErrors = true;
        //                 }
                        
        //                 // Is URL
        //                 else if (($field->type == 'url' && !filter_var($post[$field->key], FILTER_VALIDATE_URL)) && (isset($field->required) && $field->required === true))
        //                 {
        //                     $_SESSION[$postType][$field->key]['error'] = isset($field->syntax_error) ? $field->syntax_error : "This field is not a valid url address.";
        //                     $hasErrors = true;
        //                 }
                        
        //                 // RegEx
        //                 else if (isset($field->regex) && !empty($field->regex) && !preg_match($field->regex, $post[$field->key]))
        //                 {
        //                     $_SESSION[$postType][$field->key]['error'] = isset($field->syntax_error) ? $field->syntax_error : "This field is not a valid.";
        //                     $hasErrors = true;
        //                 }
        //             }
        //         }

        //         // Checking files
        //         if (!empty($_FILES[$_REQUEST['post_type']]))
        //         {
        //             $files = [];
        //             $allowed_types = [];

        //             foreach ($_FILES[$_REQUEST['post_type']] as $key => $file)
        //             {
        //                 foreach (array_keys($file) as $keyName)
        //                 {
        //                     if (!isset($files[$keyName]))
        //                     {
        //                         $files[$keyName] = [];
        //                     }
        //                     $files[$keyName][$key] = $file[$keyName];
        //                 }
        //             }

        //             foreach ($files as $key => $file)
        //             {
        //                 if (!empty($file['name']))
        //                 {
        //                     // Get allowed types
        //                     if (isset($typeFiles[$key]->allowed_types))
        //                     {
        //                         $allowed_types = $typeFiles[$key]->allowed_types;
        //                     }
    
        //                     // Get file type
        //                     $fileType = wp_check_filetype(basename($file['name']));
    
        //                     // Check if type is allowed
        //                     if (!empty($allowed_types))
        //                     {
        //                         if (!in_array($fileType['type'], $allowed_types))
        //                         {
        //                             $_SESSION[$_REQUEST['post_type']][$key]['error'] = isset($typeFiles[$key]->type_error) ? $typeFiles[$key]->type_error : "The file type that you've uploaded is not allowed.";
        //                             $hasErrors = true;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
                
        //         if (!$hasErrors)
        //         {
        //             $_SESSION[$postType]['success'] = true;
        //         }

        //         return !$hasErrors;
        //     }
        // }
    }
}
