<?php

namespace Framework\Kernel;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

if (!class_exists('Framework\Kernel\Request'))
{
    class Request
    {
        /**
         * Request Method
         */
        private $method;

        /**
         * Post Type (of the request)
         */
        private $posttype;

        /**
         * 
         */
        // public function __construct($bs)
        public function __construct()
        {
            // Define Request Method
            $this->setMethod();

            // Retrieve the PostType of the request
            $this->setPostType();
        }

        /**
         * Request Method
         */
        private function setMethod()
        {
            $this->method = $_SERVER['REQUEST_METHOD'];

            return $this;
        }
        public function getMethod()
        {
            return $this->method;
        }

        public function isPost()
        {
            return 'POST' === $this->getMethod();
        }

        /**
         * Post Type
         */
        private function setPostType()
        {
            if (isset($_REQUEST['post_type']))
            {
                $this->posttype = $_REQUEST['post_type'];
            }

            return $this;
        }
        public function getPostType()
        {
            return $this->posttype;
        }
    }
}