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
            if (empty(session_id()))
            {
                session_start();
            }
            
            // new \Framework\Kernel\Session($bs->getNamespace());
            new \Framework\Register\Posts($bs);
            new \Framework\Register\Assets($bs);
            new \Framework\Register\Filters($bs);
            new \Framework\Register\Hooks($bs);
            new \Framework\Register\Shortcodes($bs);
            new \Framework\Register\Settings($bs);
            // new \Framework\Register\Widgets($bs);

            // Do on Admin
            if (is_admin()) 
            {
                new \Framework\Kernel\Updater($bs);
            }

            // Do on Front
            else 
            {

            }
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
