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
         * Post ID
         */
        private $postid;

        /**
         * Post Type (of the request)
         */
        private $posttype;

        /**
         * Action
         */
        private $action;

        /**
         * 
         */
        // public function __construct($bs)
        public function __construct()
        {
            // Define Request Method
            $this->setMethod();

            // Retrieve the ID
            $this->setPostID();

            // Retrieve the PostType of the request
            $this->setPostType();

            // Retrieve the action
            $this->setAction();
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
         * Post ID
         */
        private function setPostID()
        {
            // if (isset($_REQUEST['post_type']))
            // {
            //     $this->postid = $_REQUEST['post_type'];
            // }
            if (isset($_GET['post']))
            {
                $this->postid = $_GET['post'];
            }

            return $this;
        }
        public function getPostID()
        {
            return $this->postid;
        }

        /**
         * Action
         */
        private function setAction()
        {
            if (isset($_GET['action']))
            {
                $this->action = $_GET['action'];
            }

            return $this;
        }
        public function getAction()
        {
            return $this->action;
        }

        public function isActionEdit()
        {
            return 'edit' === $this->getAction();
        }
        public function isActionTrash()
        {
            return 'trash' === $this->getAction();
        }
        public function isActionUntrash()
        {
            return 'untrash' === $this->getAction();
        }
        public function isActionDelete()
        {
            return 'delete' === $this->getAction();
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
            elseif (isset($_GET['post']))
            {
                $this->posttype = get_post_type($_GET['post']);
            }

            return $this;
        }
        public function getPostType()
        {
            return $this->posttype;
        }

        /**
         * Retrieve request responses
         */
        public function responses()
        {
            if (isset($_REQUEST[$this->getPostType()]))
            {
                return $_REQUEST[$this->getPostType()];
            }
            //     foreach ($_REQUEST as $key => $value) 
            //     {
            //         if (preg_match("/^".$_REQUEST['post_type']."____(.+)____$/", $key, $m))
            //         {
            //             $responses += [$m[1] => $value];
            //         }
            //     }
        }

        /**
         * Retrieve request files
         */
        public function files()
        {
            if (isset($_FILES[$this->getPostType()]))
            {
                return $_FILES[$this->getPostType()];
            }
        }
    }
}