<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Strings;
use \Framework\Components\FileSystem as FS;
use \Framework\Kernel\Session;

if (!class_exists('Framework\Kernel\Config'))
{
    abstract class Config 
    {
        const DEFAULT = [
            "root"          => null,
            "uri"           => null,
            "title"         => null,
            "titlehtml"     => null,
            "name"          => null,
            "namespace"     => null,
            "author"        => null,
            "authoruri"     => null,
            "environment"   => null,
            "version"       => null,
            "description"   => null,
            "pluginuri"     => null,
            "textdomain"    => null,
            "domainpath"    => null,
            "network"       => null,
            "license"       => null,
            "assets"        => null,
            "options"       => null,
            "filters"       => null,
            "hooks"         => null,
            "shortcodes"    => null,
            "images"        => null,
            "settings"      => null,
            "posts"         => null,
            "widgets"       => null,
        ];

        /**
         * Array of RootFile and RootDir of the plugin
         * 
         * @param array
         */
        private $plugin;

        /**
         * Array of merged Definition and Config of the plugin
         * 
         * @param array
         */
        private $config;
        
        /**
         * The absolute directory of the base of the plugin
         * 
         * @param string
         */
        private $root;

        /**
         * The absolute uri of the base of the plugin
         * 
         * @param string
         */
        private $uri;

        /**
         * the plugin title
         * 
         * @param string
         */
        private $title;

        /**
         * the plugin title html
         * 
         * @param string
         */
        private $titlehtml;

        /**
         * The plugin name
         * 
         * @param string
         */
        private $name;

        /**
         * The plugin namespace
         * 
         * @param string
         */
        private $namespace;

        /**
         * The execution environment
         * 
         * @param string
         */
        private $environment;

        /**
         * the version number of the plugin
         * 
         * @param string
         */
        private $version;

        /**
         * the plugin description
         * 
         * @param string
         */
        private $description;

        /**
         * the plugin author name
         * 
         * @param string
         */
        private $author;

        /**
         * the plugin author URI
         * 
         * @param string
         */
        private $authoruri;

        /**
         * the plugin uri
         * 
         * @param string
         */
        private $pluginuri;

        /**
         * the plugin text-domain
         * 
         * @param string
         */
        private $textdomain;

        /**
         * the plugin domain path
         * 
         * @param string
         */
        private $domainpath;

        /**
         * the plugin network
         * 
         * @param string
         */
        private $network;

        /**
         * the plugin License
         * 
         * @param string
         */
        private $license;

        /**
         * Array of plugin assets
         * 
         * @param array
         */
        private $assets;

        /**
         * Array of plugin options
         * 
         * @param array
         */
        private $options;

        /**
         * Array of plugin filters
         * 
         * @param array
         */
        private $filters;

        /**
         * Array of plugin hooks
         * 
         * @param array
         */
        private $hooks;

        /**
         * Array of plugin shortcodes
         * 
         * @param array
         */
        private $shortcodes;

        /**
         * Array of plugin images sizes
         * 
         * @param array
         */
        private $images;

        /**
         * Array of plugin settings
         * 
         * @param array
         */
        private $settings;

        /**
         * Array of plugin posts
         * 
         * @param array
         */
        private $posts;

        /**
         * Array of plugin widgets
         * 
         * @param array
         */
        private $widgets;

        /**
         * Constructor
         * 
         * @param array $plugin data defined on PPM Register item
         */
        public function __construct(array $plugin = [])
        {
            // Define the plugin config
            $this->setPlugin($plugin);
            $this->setConfig( $this->getPlugin() );

            // Set each parameters
            foreach ($this->config as $key => $value) 
            {
                // Create the Setter
                $method = ucfirst($key);
                $setter = "set".$method;
                

                if ($key == 'posts')
                {
                    $value = null;
                    // echo "<pre>";
                    // print_r( $value );
                    // echo "</pre>";
                }

                // execute the setter
                $this->$setter($value);
            }
        }


        /* *** Statics *** */

        /**
         * Plugin Definition
         * 
         * Its a part of plugin configuration from the wordpress 
         * plugin bootstrap file (default ppm.php)
         * 
         * @param array $plugin
         * @return array
         */
        static function getStaticDefinition(array $plugin = [])
        {
            global $ppm_root_file;
            $root_file = isset($plugin['root_file']) ? $plugin['root_file'] : $ppm_root_file;
            $plugin_data = [];

            if (file_exists($root_file))
            {
                $headers_xtra = ['License' => 'License'];

                $plugin_data = array_merge(
                    get_plugin_data($root_file), 
                    get_file_data( $root_file, $headers_xtra, 'plugin' )
                );

                foreach ($plugin_data as $key => $value) {
                    $plugin_data[strtolower($key)] = $value;
                    unset($plugin_data[$key]);
                }
            }

            return $plugin_data;
        }

        /**
         * Plugin configuration
         * 
         * @param array $plugin
         * @return array Configuration and Definition merged in the same array
         */
        static function getStaticConfig(array $plugin = [])
        {
            static $config = [];
            
            if (!empty($plugin)) 
            {
                // Custom config
                $custom_conf = self::getStaticRoot($plugin).FS::FILE_CONFIG;
                if (file_exists($custom_conf)) {
                    include_once $custom_conf;
                }

                $config = array_merge(self::DEFAULT, $config, self::getStaticDefinition($plugin));

            }

            // Sanitize the config array
            foreach ($config as $key => $value) 
            {
                if (!array_key_exists($key, self::DEFAULT)) {
                    unset($config[$key]);
                }
            }

            return $config;
        }

        /**
         * The real name of the plugin
         * 
         * @param array $plugin
         * @return string
         */
        static function getStaticName(array $plugin = [])
        {
            $config = self::getStaticConfig($plugin);
            return isset($config['name']) ? $config['name'] : null;
        }

        /**
         * Return the root directory of the plugin
         * 
         * @param array $plugin
         * @return string
         */
        static function getStaticRoot(array $plugin = [])
        {
            $directory = isset($plugin['root_dir']) ? $plugin['root_dir'] : dirname(dirname(__DIR__));
            $root = $directory.DIRECTORY_SEPARATOR;

            return $root;
        }

        /**
         * Plugin namespace
         * 
         * @param array $plugin
         * @return string
         */
        static function getStaticNamespace(array $plugin = [])
        {
            
            $config = self::getStaticConfig($plugin);
            $namespace = null;
            
            if (isset($config['namespace'])) 
            {
                $namespace = $config['namespace'];
            }
            
            // If the namespace was not found
            if (empty($namespace)) 
            {    
                $plugin_name = self::getStaticName($plugin);
                if (!empty($plugin_name)) {
                    $namespace = $plugin_name;
                }
            }
            
            return Strings::slugify($namespace, "_");
        }


        /* *** Private *** */

        /**
         * Define array of RootFile and RootDir of the plugin
         * 
         * @param array $plugin
         * @return object instance
         */
        private function setPlugin(array $plugin = [])
        {
            $this->plugin = $plugin;

            return $this;
        }

        /**
         * return array or string of RootFile and RootDir of the plugin
         * 
         * @return array|string
         */
        public function getPlugin(string $key = '')
        {
            if (null != $key && isset($this->plugin[$key])) 
            {
                return $this->plugin[$key];
            }

            return $this->plugin;
        }

        /**
         * Define Request Instance
         */
        public function request()
        {
            return new Request;
        }


        /* *** Public *** */

        /**
         * Plugin configuration
         * 
         * @param array $plugin
         * @return $this
         */
        private function setConfig(array $plugin = [])
        {
            $this->config = self::getStaticConfig($plugin);

            return $this;
        }

        /**
         * Get value of a plugin config
         * 
         * @param string|null $key
         * @return array|null
         */
        public function getConfig($key = null )
        {
            if (null != $key && isset($this->config[$key])) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Set the plugin absolute root directory 
         * 
         * @param string $path
         * @return string
         */
        private function setRoot()
        {
            $root = $this->getPlugin('root_dir');

            if (substr($root, strlen($root), 1) != DIRECTORY_SEPARATOR) {
                $root.= DIRECTORY_SEPARATOR;
            }

            (string) $this->root = $root;

            return $this;
        }

        /**
         * Get the plugin absolute root directory
         * 
         * @return string
         */
        public function getRoot()
        {
            return $this->root;
        }

        /**
         * Set the plugin absolute root URI 
         * 
         * @return string
         */
        private function setUri()
        {
            $plugin = str_replace( dirname($this->getRoot()), '', $this->getRoot()  );
            $this->uri =  plugins_url().$plugin;

            return $this;
        }

        /**
         * Get the plugin absolute root directory
         * 
         * @return string
         */
        public function getUri()
        {
            return $this->uri;
        }

        /**
         * Define the plugin Title
         * 
         * @return object instance
         */
        public function setTitle()
        {
            $this->title = strip_tags($this->getConfig('title'));

            return $this;
        }

        /**
         * Return the plugin Title
         * 
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Define the plugin Title HTML
         * 
         * @return object instance
         */
        public function setTitlehtml()
        {
            $this->titlehtml = $this->getConfig('title');

            return $this;
        }

        /**
         * Return the plugin Title HTML
         * 
         * @return string
         */
        public function getTitleHTML()
        {
            return $this->titlehtml;
        }

        /**
         * Define the plugin name
         * 
         * @return object instance
         */
        public function setName()
        {
            $this->name = $this->getConfig('name');

            return $this;
        }

        /**
         * Return the plugin name
         * 
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * define the plugin namespace
         * 
         * @return object instance
         */
        public function setNamespace()
        {
            $this->namespace = self::getStaticNamespace($this->plugin);
            
            return $this;
        }

        /**
         * Return the plugin namespace
         * 
         * @return string
         */
        public function getNamespace()
        {
            return $this->namespace;
        }

        /**
         * Define the environment
         * 
         * @return object instance
         */
        private function setEnvironment()
        {
            $environment = $this->getConfig('environment');

            switch ($environment)
            {
                case 'production':
                case 'development':
                    $this->environment = $environment;
                    break;
                
                case 'auto':
                default: 
                    if (preg_match("/(127\.0\.Ø\.1|localhost|\.local$)/i", $_SERVER['SERVER_NAME'])) 
                    {
                        $this->environment = 'development';
                    }
                    else {
                        $this->environment = 'production';
                    }
            }

            return $this;
        }

        /**
         * return the environment state
         * 
         * @return string
         */
        public function getEnvironment()
        {
            return $this->environment;
        }

        /**
         * Define the plugin version number
         * 
         * @return object instance
         */
        public function setVersion()
        {
            $this->version = $this->getConfig('version');

            return $this;
        }

        /**
         * Return the plugin version number
         * 
         * @return string
         */
        public function getVersion()
        {
            return $this->version;
        }

        /**
         * Define the plugin description
         * 
         * @return object instance
         */
        public function setDescription()
        {
            $this->description = $this->getConfig('description');

            return $this;
        }

        /**
         * Return the plugin description
         * 
         * @return string
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * Define the plugin author
         * 
         * @return object instance
         */
        public function setAuthorname()
        {
            $this->authorname = strip_tags($this->getConfig('author'));

            return $this;
        }

        /**
         * Alias for setAuthorname
         * 
         * @return object instance
         */
        public function setAuthor()
        {
            $this->setAuthorname();

            return $this;
        }

        /**
         * Alias for getAuthorname
         * 
         * @return string
         */
        public function getAuthor()
        {
            return $this->authorname;
        }

        /**
         * Define the plugin author URI
         * 
         * @return object instance
         */
        public function setAuthoruri()
        {
            $this->authoruri = $this->getConfig('authoruri');

            return $this;
        }

        /**
         * Return the plugin author URI
         * 
         * @return string
         */
        public function getAuthorURI()
        {
            return $this->authoruri;
        }

        /**
         * Define the plugin URI
         * 
         * @return object instance
         */
        public function setPluginuri()
        {
            $this->pluginuri = $this->getConfig('pluginuri');

            return $this;
        }

        /**
         * Return the plugin URI
         * 
         * @return string
         */
        public function getPluginURI()
        {
            return $this->pluginuri;
        }

        /**
         * Define the plugin Text Domain
         * 
         * @return object instance
         */
        public function setTextdomain()
        {
            $this->textdomain = $this->getConfig('textdomain');

            return $this;
        }

        /**
         * Return the plugin Text Domain
         * 
         * @return string
         */
        public function getTextDomain()
        {
            return $this->textdomain;
        }

        /**
         * Define the plugin Domain Path
         * 
         * @return object instance
         */
        public function setDomainpath()
        {
            $this->domainpath = $this->getConfig('domainpath');

            return $this;
        }

        /**
         * Return the plugin Domain Path
         * 
         * @return string
         */
        public function getDomainPath()
        {
            return $this->domainpath;
        }

        /**
         * Define the plugin Network
         * 
         * @return object instance
         */
        public function setNetwork()
        {
            $this->network = $this->getConfig('network');

            return $this;
        }

        /**
         * Return the plugin Network
         * 
         * @return string
         */
        public function getNetwork()
        {
            return $this->network;
        }

        /**
         * Define the plugin License
         * 
         * @return object instance
         */
        public function setLicense()
        {
            $this->license = $this->getConfig('license');

            return $this;
        }

        /**
         * Return the plugin License
         * 
         * @return string
         */
        public function getLicense()
        {
            return $this->license;
        }

        /**
         * Define the plugin Assets
         * 
         * @return object instance
         */
        public function setAssets()
        {
            $this->assets = $this->getConfig('assets');

            return $this;
        }

        /**
         * Return the plugin Assets
         * 
         * @return string
         */
        public function getAssets()
        {
            return $this->assets;
        }

        /**
         * Define the plugin Options (recursive)
         * 
         * @return object instance
         */
        public function setOptions( $data = null )
        {
            // TODO: Define options form Options and Schemas

            // // Retrieve options data of the config if $data is Null
            // if (null == $data) {
            //     $data = $this->getConfig('options');
            // }
            
            // foreach ($data as $key => $value) {
            //     # code...
            // }

            $this->options = $this->getConfig('options');

            return $this;
        }

        /**
         * Return the plugin Options
         * 
         * @return string
         */
        public function getOptions()
        {
            return $this->options;
        }

        /**
         * Define the plugin Hooks
         * 
         * @return object instance
         */
        public function setHooks()
        {
            $this->hooks = $this->getConfig('hooks');

            return $this;
        }

        /**
         * Return the plugin Hooks
         * 
         * @return string
         */
        public function getHooks()
        {
            return $this->hooks;
        }

        /**
         * Define the plugin Filters
         * 
         * @return object instance
         */
        public function setFilters()
        {
            $this->filters = $this->getConfig('filters');

            return $this;
        }

        /**
         * Return the plugin Filters
         * 
         * @return string
         */
        public function getFilters()
        {
            return $this->filters;
        }

        /**
         * Define the plugin Shortcodes
         * 
         * @return object instance
         */
        public function setShortcodes()
        {
            $this->shortcodes = $this->getConfig('shortcodes');

            return $this;
        }

        /**
         * Return the plugin Shortcodes
         * 
         * @return string
         */
        public function getShortcodes()
        {
            return $this->shortcodes;
        }

        /**
         * Define the plugin Images
         * 
         * @return object instance
         */
        public function setImages()
        {
            $this->images = $this->getConfig('images');

            return $this;
        }

        /**
         * Return the plugin Images
         * 
         * @return string
         */
        public function getImages()
        {
            return $this->images;
        }

        /**
         * Define the plugin Settings
         * 
         * @return object instance
         */
        public function setSettings()
        {
            $this->settings = $this->getConfig('settings');

            return $this;
        }

        /**
         * Return the plugin Settings
         * 
         * @return string
         */
        public function getSettings()
        {
            return $this->settings;
        }

        /**
         * Define the plugin Posts
         * 
         * @return object instance
         */
        public function setPosts($schema = null)
        {
            if (null == $schema)
            {
                $this->posts = $this->getConfig('posts');
            }

            // // Retrieve schema declaration and search for Collection
            // foreach ($this->posts as $post) 
            // {
            //     if (is_array($post['schema']))
            //     {
            //         foreach ($post['schema'] as $type) 
            //         {
            //             if ('collection' == $type['type'])
            //             {

            //                 echo "<pre>";
            //                 print_r( $post['type']."__[". $type['key']."]");
            //                 echo "</pre>";
            //                 echo "<pre>";
            //                 print_r($type);
            //                 echo "</pre>";

            //             }
            //         }
            //     }
            // }


            // echo "<pre>";
            // echo count($this->posts);
            // echo "</pre>";
            // // echo "<pre>";
            // // print_r($this->posts);
            // // echo "</pre>";

            return $this;
        }

        /**
         * Return the plugin Posts
         * 
         * @return string
         */
        public function getPosts()
        {
            return $this->posts;
        }

        /**
         * Define the plugin Widgets
         * 
         * @return object instance
         */
        public function setWidgets()
        {
            $this->widgets = $this->getConfig('widgets');

            return $this;
        }

        /**
         * Return the plugin Widgets
         * 
         * @return string
         */
        public function getWidgets()
        {
            return $this->widgets;
        }
    }
}
