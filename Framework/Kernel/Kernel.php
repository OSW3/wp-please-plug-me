<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Kernel\Config;
use \Framework\Kernel\Updater;

if (!class_exists('Framework\Kernel\Kernel'))
{
    abstract class Kernel extends Config 
    {
        /**
         * String of code we need to inject in WP
         */
        private $_codeInjection;

        /**
         * 
         */
        public function start($bs)
        {
            // Start the PHP Session
            empty(session_id()) ? session_start() : null;

            // Start Assets Register
            new \Framework\Register\Assets($bs);
            new \Framework\Register\Filters($bs);
            new \Framework\Register\Hooks($bs);
            new \Framework\Register\Shortcodes($bs);
            // new \Framework\Register\Settings($bs);
            new \Framework\Register\Posts($bs);
            // new \Framework\Register\Widgets($bs);

            if (is_admin()) 
            {
                new \Framework\Kernel\Updater($bs);
            }
            



            // global $wp_styles, $wp_scripts;
            // print_r($wp_styles->registered['colors']);
            // print_r($wp_scripts);
            // global $wp_filter;
            // print_r($wp_filter);

            // print_r( "\n\n\n");            
            // print_r( "Root: ".$this->getRoot() ."\n");
            // print_r( "URI: ".$this->getUri() ."\n");
            // print_r( "Title: ".$this->getTitle() ."\n");
            // print_r( "Title HTML: ".$this->getTitleHTML() ."\n");
            // print_r( "Name: ".$this->getName() ."\n");
            // print_r( "Namespace: ".$this->getNamespace() ."\n");
            // print_r( "Author: ".$this->getAuthor() ."\n");
            // print_r( "Author URI: ".$this->getAuthorURI() ."\n");
            // print_r( "Environement: ".$this->getEnvironment() ."\n");
            // print_r( "Version: ".$this->getVersion() ."\n");
            // print_r( "Description: ".$this->getDescription() ."\n");
            // print_r( "Plugin URI: ".$this->getPluginURI() ."\n");
            // print_r( "Text Domain: ".$this->getTextDomain() ."\n");
            // print_r( "Domain Path: ".$this->getDomainPath() ."\n");
            // print_r( "Network: ".$this->getNetwork() ."\n");
            // print_r( "License: ".$this->getLicense() ."\n");
            // print_r( $this->getOptions());
            // var_dump( $this->getFilters());
            // var_dump( $this->getHooks());
            // var_dump( $this->getShortcodes());
            // print_r( "\n\n\n");
            // print_r( $this->getFullConfig());
        }


        /**
         * Internationalization
         * 
         * @param array $pattern Array of WP labels index
         * @param array $suject Array of label index + original values
         * @return array of translated array $subject
         */
        public function i18n($pattern, $subject)
        {
            // retrieve the TextDomain identifier
            $textdomain = $this->getTextDomain();

            // Define output
            $output = array();

            foreach ($pattern as $index) {
                if (isset($subject[$index]) && is_string($subject[$index])) 
                {
                    $output[$index] = __($subject[$index], $textdomain);
                }
            }

            return $output;
        }

        /**
         * Admin Header code injection
         */
        public function codeInjection(string $part, string $code)
        {
            $this->_codeInjection.= $code."\n";

            switch ($part) 
            {
                case 'head':
                    add_action('admin_head', [$this, 'admin_head']);
                    break;
            
                case 'foot':
                    break;
            }
        }

        public function admin_head()
        {
            echo $this->_codeInjection;
        }
    }
}
