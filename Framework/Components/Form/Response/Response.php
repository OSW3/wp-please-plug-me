<?php

namespace Framework\Components\Form\Response;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Notices;
use \Framework\Components\Arrays;
use \Framework\Kernel\Session;

if (!class_exists('Framework\Components\Form\Response\Response'))
{
    class Response
    {
        const RE_TIME = "/^(00|[0-1][0-9]|2[0-3]):([0-5][0-9])$/";
        const RE_COLOR = "/#([a-f0-9]{3}){1,2}\b/i";

        /**
         * The instance of the bootstrap class
         * 
         * @param object instance
         */
        protected $bs;

        /**
         * Custom Post config
         * 
         * @param array
         */
        protected $post;

        /**
         * Post ID
         * 
         * @param array
         */
        protected $id;

        /**
         * Request Response
         * 
         * @param array
         */
        private $request_responses = [];

        /**
         * Post Types
         * 
         * @param array
         */
        protected $posttypes = [];

        /**
         * Metaboxes Types
         */
        protected $metatypes = [];

        /**
         * Metaboxes
         */
        private $metaboxes = [];

        private $errors = [];

        /**
         * 
         */
        public function __construct($bs, array $posts, int $id)
        {
            // Retrieve the bootstrap class instance
            $this->bs = $bs;
            
            // Define CustomPost config
            $this->setPost($posts);
            
            // Define Post ID
            $this->setID($id);

            // Retrieve Metaboxes config of current Post
            $this->setMetaboxes();

            // Retrieve Post Types
            $this->setPostTypes();

            // Retrieve Metaboxes types
            $this->setMetaTypes();
        }


        /**
         * ----------------------------------------
         * Response Config Getter / Setter
         * ----------------------------------------
         */

        /**
         * Post (current Post)
         */
        private function setPost(array $posts)
        {
            foreach ($posts as $post) 
            {
                if ($post['type'] == $this->bs->request()->getPostType())
                {
                    $this->post = $post;          
                }
            }

            return $this;
        }
        public function getPost(string $key = '')
        {
            if (!empty($key) && isset($this->post[$key])) 
            {
                return $this->post[$key];
            }

            return $this->post;
        }

        /**
         * Set Post ID
         */
        private function setID(int $id)
        {
            $this->id = $id;

            return $this;
        }
        private function getID()
        {
            return $this->id;
        }

        /**
         * Metaboxes
         */
        private function setMetaboxes()
        {
            $ui = $this->getPost('ui');
            if (isset($ui['pages']['edit']['metaboxes'])) 
            {
                $this->metaboxes = $ui['pages']['edit']['metaboxes'];
            }

            return $this;
        }
        private function getMetaboxes()
        {
            return $this->metaboxes;
        }

        /**
         * Post Types
         */
        private function setPostTypes()
        {
            foreach ($this->getPost('schema') as $key => $type) 
            {
                $this->posttypes[$type['key']] = $type;
            }

            return $this;
        }
        private function getPostTypes(string $key)
        {
            if (isset($this->posttypes[$key]))
            {
                return $this->posttypes[$key];
            }

            return null;
        }

        /**
         * Metaboxes Types
         */
        private function setMetaTypes()
        {
            // Retrieve types of Metaboxes
            foreach ($this->getMetaboxes() as $metabox)
            {
                if (isset($metabox['schema']))
                {
                    $this->metatypes = array_merge($this->metatypes, $metabox['schema']);
                }
            }

            foreach ($this->metatypes as $key => $type) 
            {
                $this->metatypes[$key] = $this->getPostTypes($type);
            }
        }
        public function getMetaTypes()
        {
            return $this->metatypes;
        }


        /**
         * ----------------------------------------
         * Response Compillation
         * ----------------------------------------
         */

        /** 
         * Retrieve response
         * 
         * Retrieve response form the Request and store the response 
         * into the field schema
         */
        public function responses()
        {
            // Define default response
            $responses = [];
            $files = [];

            if ($this->bs->request()->isPost())
            {
                // Retrieve Response Data and Files
                $responses = $this->bs->request()->responses();
                $files = $this->bs->request()->files();

                $this->metatypes = $this->responseCollection(
                    $this->getMetaTypes(), 
                    $responses 
                );

                $session = new Session( $this->bs->getNamespace() );
                $session->responses(
                    $this->bs->request()->getPostType(), 
                    $this->responseSession($this->metatypes)
                );
            }

            return $this;
        }


        public function responseSession(array $types)
        {
            $responses = [];

            foreach ($types as $key => $type) 
            {
                if ('collection' == $type['type'])
                {
                    $responses[$type['key']] = $this->responseSession($type['schema']);
                }
                else
                {
                    if (is_array($type['value']))
                    {
                        foreach ($type['value'] as $key => $value) 
                        {
                            $responses[$type['key']][$key] = $value;
                        }
                    }
                    else
                    {
                        $responses[$type['key']] = $type['value'];
                    }
                }
            }

            return $responses;
        }
        private function responseType(array $type, array $responses)
        {
            if (!$this->isDisabled($type))
            {
                switch ($type['type'])
                {
                    // Define checkbox value as ON or OFF
                    case 'checkbox':
                        $type['value'] = isset($responses[$type['key']]) ? "on" : "off";
                        break;

                    // Hash the Password
                    case 'password':
                        $type['plaintext'] = $responses[$type['key']];;
                        $type['value'] = !empty($type['plaintext']) 
                            ? password_hash($type['plaintext'], constant($type['algo']['type'])) 
                            : null;
                        break;

                    case 'file':
                        // TODO: File data
                        //     if (!empty($files['name'][$field['key']]))
                        //     {
                        //         $field['files'] = [];
                        //         foreach ($files as $key => $file)
                        //         {
                        //             if (isset($file[$field['key']]))
                        //             {
                        //                 if (!is_array($file[$field['key']]))
                        //                 {
                        //                     $field['files'][$key] = [$file[$field['key']]];
                        //                 }
                        //                 else
                        //                 {
                        //                     $field['files'][$key] = $file[$field['key']];
                        //                 }
                        //             }
                        //         }
                        //     }
                        break;

                    default:
                        $type['value'] = $responses[$type['key']];
                }

                return $type;
            }
        }
        private function responseCollection(array $collection, array $responses)
        {
            foreach ($collection as $key => $type)
            {
                if ('collection' == $type['type'] && isset($responses[$type['key']]))
                {
                    $collection[$key]['schema'] = $this->responseCollection($type['schema'], $responses[$type['key']]);
                }
                else
                {
                    $collection[$key] = $this->responseType($type, $responses);
                }
            }

            return $collection;
        }

        /**
         * Is type is disabled
         */
        private function isDisabled(array $type)
        {
            if (isset($type['attr']['disabled']) && !$type['attr']['disabled']) 
            {
                return $type['attr']['disabled'];
            }

            return false;
        }


        /**
         * ----------------------------------------
         * Response Validation
         * ----------------------------------------
         */

        /**
         * Validate response
         * 
         * Read each response, check rules and add a message error into 
         * the field schema
         */
        public function validate()
        {
            // $errors = [];

            $session = new Session( $this->bs->getNamespace() );
            $notices = new Notices( $this->bs->getNamespace() );
            
            $this->metatypes = $this->validateCollection($this->getMetaTypes());

            // Define errors
            $this->setErrors($this->metatypes);

            // Add message to a notice
            if (!empty($this->errors))
            {
                $notices->danger($this->bs->request()->getPostType(), "The form has not been saved.");
            }

            // Set errors to the session
            $session->errors($this->bs->request()->getPostType(), $this->errors);

            return empty($this->errors);
        }

        private function validateResponse($type, $response)
        {
            // Default State
            $error = null;

            // Is required
            if (isset($type['attr']['required']) && $type['attr']['required'] && empty(trim($response)))
            {
                $error = $type['messages']['required'];
            }

            // Is email
            elseif ('email' == $type['type'] && !empty($response) && !filter_var($response, FILTER_VALIDATE_EMAIL))
            {
                $error = $type['messages']['email'];
            }

            // Is URL
            elseif ('url' == $type['type'] && !empty($response) && !filter_var($response, FILTER_VALIDATE_URL))
            {
                $error = $type['messages']['url'];
            }
            
            // Is Number
            elseif ('number' == $type['type'] && !empty($response) && !(is_int(intval($response)) || is_double($type['value']) || is_float($type['value'])))
            {
                $error = $type['messages']['type'];
            }

            // Is Date
            elseif ('date' == $type['type'] && !empty($response))
            {
                $date = explode("-", $response);

                $year = isset($date[0]) ? $date[0] : null;
                $month = isset($date[1]) ? $date[1] : null;
                $day = isset($date[2]) ? $date[2] : null;

                if (null == $year || null == $month || null == $day || !checkdate($month, $day, $year)) 
                {
                    $error = $type['messages']['date'];
                }
            }

            // Is Time
            elseif ('time' == $type['type'] && !empty($response) && !preg_match(self::RE_TIME, $response))
            {
                $error = $type['messages']['time'];
            }

            // Is Datetime
            // TODO: checking value

            // Is Month
            // TODO: checking value

            // Is Week
            // TODO: checking value

            // Is Year
            elseif ('year' == $type['type'] && !empty($response) && !preg_match("/^\d{4}$/", $response))
            {
                $error = $type['messages']['year'];
            }

            // Is Color
            elseif ('color' == $type['type'] && !empty($response) && !preg_match(self::RE_COLOR, $response))
            {
                $error = $type['messages']['color'];
            }

            // Is Comfirmed password
            elseif ('password' == $type['type'] && isset($type['rules']['confirm']))
            {
                $password = '';
                $confirmation = $type['plaintext'];
                foreach ($this->getSchema() as $item) 
                {
                    if ($item['key'] == $type['rules']['confirm']) {
                        $password = $item['plaintext'];
                    }
                }

                if ($password !== $confirmation) {
                    $error = $type['messages']['confirm'];
                }
            }

            // Is file
            // TODO: checking value

            // Rule pattern
            elseif (!empty($response) && isset($type['rules']['pattern']))
            {
                $track_errors = ini_get('track_errors');

                ini_set('track_errors', 'on');
                $php_errormsg = '';
                @preg_match($type['rules']['pattern'], '');

                if (empty($php_errormsg)) 
                {
                    if (!preg_match($type['rules']['pattern'], $response))
                    {
                        $error = $type['messages']['pattern'];
                    }
                }

                ini_set('track_errors', $track_errors);
            }

            // Is > to Min
            elseif (!empty($type['attr']['min']) && $response < $type['attr']['min']) 
            {
                $error = preg_replace("/\\$1/", $type['attr']['min'], $type['messages']['min']);
            }

            // Is < to Max
            elseif (!empty($type['attr']['max']) && $response > $type['attr']['max']) 
            {
                $error = preg_replace("/\\$1/", $type['attr']['max'], $type['messages']['max']);
            }

            // Is < to Maxlegth
            elseif (!empty($type['attr']['maxlength']) && $type['attr']['maxlength'] > 0 && strlen($response) > $type['attr']['maxlength']) 
            {
                $error = $type['messages']['maxlength'];
            }

            // Define validation parameter
            if ($error == null)
            {
                $validation = array(
                    'state' => 'success',
                    'message' => null
                );
            }
            else
            {
                $validation = array(
                    'state' => 'danger',
                    'message' => $error
                );
            }

            //     // Push the error to the errors collector
            //     if (null != $error)
            //     {
            //         array_push($errors, [
            //             'key' => $type['key'],
            //             'message' => $error
            //         ]);
            //     }

            // echo "<pre style=\"padding-left: 180px;\">";
            // print_r($_SESSION);
            // echo "</pre>";
            // exit;

            return $validation;
        }
        private function validateType(array $type)
        {
            if (is_array($type['value']))
            {
                foreach ($type['value'] as $key => $value) 
                {
                    $type['validation'][$key] = $this->validateResponse($type, $value);
                }
            }
            else
            {
                $type['validation'] = $this->validateResponse($type, $type['value']);
            }

            return $type;

        }
        private function validateCollection(array $collection)
        {
            foreach ($collection as $key => $type)
            {
                if ('collection' == $type['type'])
                {
                    $collection[$key]['schema'] = $this->validateCollection($type['schema']);
                }
                else
                {
                    $collection[$key] = $this->validateType($type);
                }
            }

            return $collection;
        }

        private function setErrors(array $types, $parent = null)
        {
            foreach ($types as $type) 
            {
                // -- Create error Item

                if ($parent == null)
                {
                    if (!isset($this->errors[$type['key']]))
                    {
                        $this->errors[$type['key']] = array();
                    }
                }
                else
                {
                    if (!isset($this->errors[$parent]))
                    {
                        $this->errors[$parent] = array();
                    } 
                    if (!isset($this->errors[$parent][$type['key']]))
                    {
                        $this->errors[$parent][$type['key']] = array();
                    }
                }


                if ('collection' != $type['type'] && isset($type['validation']))
                {
                    if ($parent == null)
                    {
                        if ($type['validation']['state'] == 'danger')
                        {
                            $this->errors[$type['key']] = [
                                'key' => $type['key'],
                                'message' => $type['validation']['message']
                            ];
                        }
                    }
                    else
                    {
                        if (Arrays::isNumeric($type['validation']))
                        {
                            foreach ($type['validation'] as $key => $validation) 
                            {
                                if ($validation['state'] == 'danger')
                                {
                                    $this->errors[$parent][$type['key']][$key] = [
                                        'key' => $type['key'],
                                        'message' => $validation['message']
                                    ];
                                }
                            }
                        }
                    }
                }
                elseif ('collection' == $type['type'])
                {
                    $this->setErrors($type['schema'], $type['key']);
                }



                // -- Delete error Item if empty

                if ($parent == null)
                {
                    if (empty($this->errors[$type['key']]))
                    {
                        unset($this->errors[$type['key']]);
                    }
                }
                else
                {
                    if (empty($this->errors[$parent][$type['key']]))
                    {
                        unset($this->errors[$parent][$type['key']]);
                    }
                }



                
                // echo "<pre>";
                // print_r($type);
                // echo "</pre>";
            }
        }
    }
}