<?php

namespace Framework\Register;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}


if (!class_exists('Framework\Register\Metaboxes'))
{
	class Metaboxes
	{
        /**
         * Supports definition
         */
        const SUPPORTS = ['title', 'editor', 'comments', 'revisions', 
            'trackbacks', 'author', 'excerpt', 'page-attributes', 
            'thumbnail', 'custom-fields', 'post-formats'];

        /**
         * Available Context
         */
        const CONTEXT = ['normal', 'side', 'advanced'];

        /**
         * Available Priority
         */
        const PRIORITY = ['high', 'low'];

        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
        protected $bs;

        /**
         * The post data
         * 
         * @param array
         */
        private $post;

        /**
         * Metaboxes config defined in the config.php
         */
        private $metaboxes;

        /**
         * Metaboxes Options defined in the config.php
         */
        private $metaboxes_options;

        /**
         * Supports fields
         */
        private $supports;

        /**
         * 
         */
        public function __construct($bs, array $post)
        {
            // Retrieve the bootstrap class instance
            $this->bs = $bs;

            // Retrieve the array Post data
            $this->setPost($post);

            // Retrieve the Metaboxes settings
            $this->setMetaboxes();

            // Retrieve Metaboxes global options
            $this->setMetaboxesOptions();

            // Retrieve Supports
            $this->setSupports();

            // Add metaboxes to the WP register
            add_action('add_meta_boxes',[$this, 'add_meta_boxes']);

            // Form tag
            add_action('post_edit_form_tag', [$this, 'post_edit_form_tag']);

            // Metabox Options
            add_action( 'admin_init', [$this, 'set_meta_boxes_options']);

            $this->bs->codeInjection('head', "<style>.js .postbox .handlediv {display: none;}</style>");
        }

        // --

        /**
         * Add Metaboxes
         */
        public function add_meta_boxes()
        {
            if (is_array($this->metaboxes))
            {
                foreach ($this->metaboxes as $metabox) 
                {
                    // Default display
                    $display = true;

                    $metabox['post_type'] = $this->getPost('type');

                    // Define the display
                    if (isset($metabox['key']) && isset($metabox['display']) && is_bool($metabox['display'])) 
                    {
                        $display = $metabox['display'];
                    }

                    if ($display)
                    {
                        $metabox['id'] = $this->getID($metabox);

                        // Add metabox to the register
                        add_meta_box( 
                            $metabox['id'], 
                            $this->getTitle($metabox), 
                            [$this, 'set_meta_box_content'], 
                            $this->getPost('type'), 
                            $this->getContext($metabox), 
                            $this->getPriority($metabox), 
                            $metabox
                        );

                    }
                }
            }
        }

        /**
         * Edit Form Tag
         * add NoValidate
         * add Enctype
         */
        public function post_edit_form_tag()
        {
            // Add No Validate
            if ($this->isNoValidate())
            {
                echo ' novalidate="novalidate"';
            }

            // Add Enctype
            if ($this->hasEnctype())
            {
                echo ' enctype="multipart/form-data"';
            }
        }

        /**
         * Set Metaboxes Options
         */
        public function set_meta_boxes_options()
        {
            // Unset metabox sortable
            if (!$this->isSortable())
            {
                wp_deregister_script('postbox');
                $this->bs->codeInjection('head', "<style>.js .postbox .hndle, .js .widget .widget-top {cursor: default;}</style>");
            }
        }

        /**
         * 
         */
        public function set_meta_box_content($wp_post, $args)
        {
            // Define the output
            $content = '';

            // Retrieve the Metabox data
            $metabox = $args['args'];
            
            // Retrieve metabox field (of schema)
            $metabox_fields = $metabox['schema'];

            // Retrieve schema definition
            $schema = $this->getPost('schema');

            if (isset($metabox['description']))
            {
                $content.= '<div class="metabox-header"><p>'.$metabox['description'].'</p></div>';
            }

            $content.= '<table class="form-table">';
            $content.= '<tbody>';

            foreach ($metabox_fields as $field_key) 
            {
                foreach ($schema as $schema_field) 
                {
                    if ($field_key == $schema_field['key'])
                    {
                        $schema_field['post_type'] = $metabox['post_type'];
                        // print_r($metabox);
                        // print_r($field_key);
                        // print_r($this->bs);

                        $fieldClass = ucfirst(strtolower($schema_field['type']));
                        $fieldClass = "\\Framework\\Components\\Form\\Fields\\".$fieldClass;
                        
                        $field = new $fieldClass($schema_field, ['metabox' => true]);
                        $content.= $field->render();
                        
                    }
                }
            }

            $content.= '</tbody>';
            $content.= '</table>';

            echo $content;
        }

        // --

        /**
         * Retrieve Post data
         * 
         * @param array $post
         * @return object $this
         */
        private function setPost(array $post)
        {
            $this->post = $post;

            return $this;
        }

        /**
         * Get Post data
         * 
         * @param string $key of $post data
         * @return mixed
         */
        private function getPost(string $key = '')
        {
            if (isset($this->post[$key])) 
            {
                return $this->post[$key];
            }

            return $this->post;
        }

        /**
         * 
         */
        private function setMetaboxes()
        {
            $post_ui = $this->getPost('ui');

            if (isset($post_ui['pages']['edit']['metaboxes']))
            {
                $this->metaboxes = $post_ui['pages']['edit']['metaboxes'];
            }

            return $this;
        }

        /**
         * Retrive Metaboxes Options
         */
        private function setMetaboxesOptions()
        {
            $post_ui = $this->getPost('ui');

            if (isset($post_ui['pages']['edit']['metaboxes_options']))
            {
                $this->metaboxes_options = $post_ui['pages']['edit']['metaboxes_options'];
            }

            return $this;
        }

        /**
         * Define Supports Array
         * Read metaboxes and unset row if row key is a Supports key
         */
        private function setSupports()
        {
            $metaboxes = $this->metaboxes;

            if (is_array($metaboxes))
            {
                if (!is_array($this->supports))
                {
                    $this->supports = array();
                }

                foreach ($metaboxes as $key => $metaboxe) 
                {
                    if (isset($metaboxe['key']) && in_array($metaboxe['key'], self::SUPPORTS))
                    {
                        array_push(
                            $this->supports,
                            $metaboxe
                        );
                        unset($metaboxes[$key]);
                    }
                }
            }

            $this->metaboxes = $metaboxes;

            return $this;
        }

        /**
         * Get Supports
         */
        public function getSupports()
        {
            return $this->supports;
        }

        /**
         * Generate Metabox ID
         * 
         * @param array $metabox
         */
        private function getID(array $metabox)
        {
            return 'metabox_'.$this->getPost('type').'_'.$metabox['key'];
        }

        /**
         * Get the metabox title
         * 
         * @param array $metabox
         */
        private function getTitle(array $metabox)
        {
            $title = "-";

            if (isset($metabox['title'])) 
            {
                $title = $metabox['title'];
            }

            return $title;
        }

        /**
         * Get the metabox Context
         * 
         * @param array $metabox
         */
        private function getContext(array $metabox)
        {
            $context = "normal";

            if (isset($metabox['context']) && in_array($metabox['context'], self::CONTEXT))
            {
                $context = $metabox['context'];
            }

            return $context;
        }

        /**
         * Get the metabox Priority
         * 
         * @param array $metabox
         */
        private function getPriority(array $metabox)
        {
            $priority = "high";

            if (isset($metabox['priority']) && !in_array($metabox['priority'], self::PRIORITY))
            {
                $priority = $metabox['priority'];
            }

            return $priority;
        }

        /**
         * Is Metabos Sortable
         * 
         * @param array $metabox
         */
        private function isSortable()
        {
            // Default Sortable
            $sortable = true;

            if (isset($this->metaboxes_options['sortable']) && is_bool($this->metaboxes_options['sortable']))
            {
                $sortable = $this->metaboxes_options['sortable'];
            }

            return $sortable;
        }

        /**
         * Is NoValidate
         * 
         * @return boolean
         */
        private function isNoValidate()
        {
            // Default No Validate
            $novalidate = true;

            // Define NoValidate
            $post_ui = $this->getPost('ui');
            if (isset($post_ui['pages']['edit']['form']['novalidate']) && is_bool($post_ui['pages']['edit']['form']['novalidate']))
            {
                $novalidate = $post_ui['pages']['edit']['form']['novalidate'];
            }
            
            return $novalidate;
        }

        /**
         * Has Enctype
         * 
         * @return boolean
         */
        public function hasEnctype()
        {
            // Default Enctype
            $enctype = false;

            // Array of valid fields
            $schema_fields = array();

            // Retrieve list of fields 
            foreach ($this->metaboxes as $metabox) 
            {
                if (
                    isset($metabox['key']) && 
                    isset($metabox['display']) && is_bool($metabox['display']) && $metabox['display'] == true &&
                    isset($metabox['schema']) && is_array($metabox['schema'])
                ) 
                {
                    foreach ($metabox['schema'] as $field) 
                    {
                        array_push($schema_fields, $field);
                    }
                }
            }

            // Check field settings
            $post_schema = $this->getPost('schema');
            foreach ($post_schema as $field) 
            {
                if (
                    isset($field['key']) && 
                    in_array ($field['key'], $schema_fields) &&
                    isset($field['type']) && $field['type'] == 'file'
                )
                {
                    $enctype = true;
                }
            }

            return $enctype;
        }
    }
}