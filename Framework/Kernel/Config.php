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
        const SOURCES = "https://raw.githubusercontent.com/OSW3/wp-please-plug-me/develop/";
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
            "update"        => null,
            "widgets"       => null,
        ];

        /**
         * Array of plugin assets
         * 
         * @param array
         */
        private $assets;

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
         * Array of both Definition and Config of the plugin
         * 
         * @param array
         */
        private $config;

        /**
         * the plugin description
         * 
         * @param string
         */
        private $description;

        /**
         * the plugin domain path
         * 
         * @param string
         */
        private $domainpath;

        /**
         * The execution environment
         * 
         * @param string
         */
        private $environment;

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
         * Array of plugin images sizes
         * 
         * @param array
         */
        private $images;

        /**
         * the plugin License
         * 
         * @param string
         */
        private $license;

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
         * the plugin network
         * 
         * @param string
         */
        private $network;

        /**
         * Array of plugin options
         * 
         * @param array
         */
        private $options;

        /**
         * Array of RootFile and RootDir of the plugin
         * 
         * @param array
         */
        private $plugin;

        /**
         * the plugin uri
         * 
         * @param string
         */
        private $pluginuri;

        /**
         * Array of plugin posts
         * 
         * @param array
         */
        private $posts;
        
        /**
         * The absolute directory of the base of the plugin
         * 
         * @param string
         */
        private $root;

        /**
         * Array of plugin settings
         * 
         * @param array
         */
        private $settings;

        /**
         * Array of plugin shortcodes
         * 
         * @param array
         */
        private $shortcodes;

        /**
         * the plugin text-domain
         * 
         * @param string
         */
        private $textdomain;

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
         * Array of update modes
         * 
         * @param string
         */
        private $updatemode;

        /**
         * The absolute uri of the base of the plugin
         * 
         * @param string
         */
        private $uri;

        /**
         * the version number of the plugin
         * 
         * @param string
         */
        private $version;

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
            foreach ($this->config as $key => $config) 
            {
                // Create the Setter
                $method = ucfirst($key);
                $setter = "set".$method;
                $config = $key != 'posts' ? $config : null;

                // execute the setter
                $this->$setter($config);
            }
        }


        /* *** Statics *** */

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


        /* *** Methods *** */

        /**
         * Define Request Instance
         */
        public function request()
        {
            return new Request;
        }


        /* *** Getter / Setter *** */

        /**
         * Assets
         */
        public function setAssets()
        {
            $this->assets = $this->getConfig('assets');

            return $this;
        }
        public function getAssets()
        {
            return $this->assets;
        }

        /**
         * Author
         */
        public function setAuthor()
        {
            $this->setAuthorname();

            return $this;
        }
        public function setAuthorname()
        {
            $this->authorname = strip_tags($this->getConfig('author'));

            return $this;
        }
        public function getAuthor()
        {
            return $this->authorname;
        }

        /**
         * Author URI
         */
        public function setAuthoruri()
        {
            $this->authoruri = $this->getConfig('authoruri');

            return $this;
        }
        public function getAuthorURI()
        {
            return $this->authoruri;
        }

        /**
         * Configuration
         */
        public function setConfig(array $plugin = [])
        {
            $this->config = self::getStaticConfig($plugin);

            return $this;
        }
        public function getConfig($key = null )
        {
            if (null != $key && isset($this->config[$key])) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Description
         */
        public function setDescription()
        {
            $this->description = $this->getConfig('description');

            return $this;
        }
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * Domain Path
         */
        public function setDomainpath()
        {
            $this->domainpath = $this->getConfig('domainpath');

            return $this;
        }
        public function getDomainPath()
        {
            return $this->domainpath;
        }

        /**
         * Environment
         */
        public function setEnvironment()
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
        public function getEnvironment()
        {
            return $this->environment;
        }

        /**
         * Filters
         */
        public function setFilters()
        {
            $this->filters = $this->getConfig('filters');

            return $this;
        }
        public function getFilters()
        {
            return $this->filters;
        }

        /**
         * Hooks
         */
        public function setHooks()
        {
            $this->hooks = $this->getConfig('hooks');

            return $this;
        }
        public function getHooks()
        {
            return $this->hooks;
        }

        /**
         * Images
         */
        public function setImages()
        {
            $this->images = $this->getConfig('images');

            return $this;
        }
        public function getImages()
        {
            return $this->images;
        }

        /**
         * License
         */
        public function setLicense()
        {
            $this->license = $this->getConfig('license');

            return $this;
        }
        public function getLicense()
        {
            return $this->license;
        }

        /**
         * Name
         */
        public function setName()
        {
            $this->name = $this->getConfig('name');

            return $this;
        }
        public function getName()
        {
            return $this->name;
        }

        /**
         * Namespace
         */
        public function setNamespace()
        {
            $this->namespace = self::getStaticNamespace($this->plugin);
            
            return $this;
        }
        public function getNamespace()
        {
            return $this->namespace;
        }

        /**
         * Network
         */
        public function setNetwork()
        {
            $this->network = $this->getConfig('network');

            return $this;
        }
        public function getNetwork()
        {
            return $this->network;
        }

        /**
         * Options (recursive)
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
        public function getOptions()
        {
            return $this->options;
        }

        /**
         * Plugin
         */
        public function setPlugin(array $plugin = [])
        {
            $this->plugin = $plugin;

            return $this;
        }
        public function getPlugin(string $key = '')
        {
            if (null != $key && isset($this->plugin[$key])) 
            {
                return $this->plugin[$key];
            }

            return $this->plugin;
        }

        /**
         * Plugin URI
         */
        public function setPluginuri()
        {
            $this->pluginuri = $this->getConfig('pluginuri');

            return $this;
        }
        public function getPluginURI()
        {
            return $this->pluginuri;
        }

        /**
         * Posts
         */
        public function setPosts($schema = null)
        {
            if (null == $schema)
            {
                $this->posts = $this->getConfig('posts');
            }

            return $this;
        }
        public function getPosts()
        {
            return $this->posts;
        }

        /**
         * Set the plugin absolute root directory
         */
        public function setRoot()
        {
            $root = $this->getPlugin('root_dir');

            if (is_string($root))
            {
                if (substr($root, strlen($root), 1) != DIRECTORY_SEPARATOR) {
                    $root.= DIRECTORY_SEPARATOR;
                }
    
                (string) $this->root = $root;
            }

            return $this;
        }
        public function getRoot()
        {
            return $this->root;
        }

        /**
         * Settings
         */
        public function setSettings()
        {
            $this->settings = $this->getConfig('settings');

            return $this;
        }
        public function getSettings()
        {
            return $this->settings;
        }

        /**
         * Shortcodes
         */
        public function setShortcodes()
        {
            $this->shortcodes = $this->getConfig('shortcodes');

            return $this;
        }
        public function getShortcodes()
        {
            return $this->shortcodes;
        }

        /**
         * Text Domain
         */
        public function setTextdomain()
        {
            $this->textdomain = $this->getConfig('textdomain');

            return $this;
        }
        public function getTextDomain()
        {
            return $this->textdomain;
        }

        /**
         * Define the plugin Title
         */
        public function setTitle()
        {
            $this->title = strip_tags($this->getConfig('title'));

            return $this;
        }
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Define the plugin Title HTML
         */
        public function setTitlehtml()
        {
            $this->titlehtml = $this->getConfig('title');

            return $this;
        }
        public function getTitleHTML()
        {
            return $this->titlehtml;
        }

        /**
         * Set the update modes
         */
        public function setUpdate()
        {
            // Default modes
            $modes = [
                'plugin' => false,
                'framework' => false
            ];

            // Retrieve modes
            $config = $this->getConfig('update');

            // Redefine mode of Plugin updater
            if (isset($config['plugin']) && (is_bool($config['plugin']) || in_array($config['plugin'], ['auto', 'manual'])))
            {
                $modes['plugin'] = $config['plugin'];
            }

            // Redefine mode of Framework updater
            if (isset($config['framework']) && (is_bool($config['framework']) || in_array($config['framework'], ['auto', 'manual', 'plugin'])))
            {
                $modes['framework'] = $config['framework'];
            }

            $this->updatemode = $modes;

            return $this;
        }
        public function getUpdate()
        {
            return $this->updatemode;
        }

        /**
         * Set the plugin absolute root URI 
         */
        public function setUri()
        {
            $plugin = str_replace( dirname($this->getRoot()), '', $this->getRoot()  );
            $this->uri =  plugins_url().$plugin;

            return $this;
        }
        public function getUri()
        {
            return $this->uri;
        }

        /**
         * Define the plugin version number
         */
        public function setVersion()
        {
            $this->version = $this->getConfig('version');

            return $this;
        }
        public function getVersion()
        {
            return $this->version;
        }

        /**
         * Widgets
         */
        public function setWidgets()
        {
            $this->widgets = $this->getConfig('widgets');

            return $this;
        }
        public function getWidgets()
        {
            return $this->widgets;
        }
    }
}
