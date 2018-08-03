<?php

namespace Framework\Components\Form\Response;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

if (!class_exists('Framework\Components\Form\Response\Response'))
{
    class Response
    {
        const RE_TIME = "/^(00|[0-1][0-9]|2[0-3]):([0-5][0-9])$/";
        const RE_COLOR = "/#([a-f0-9]{3}){1,2}\b/i";

        const ALGO = ['PASSWORD_BCRYPT','PASSWORD_ARGON2I','PASSWORD_ARGON2_DEFAULT_MEMORY_COST','PASSWORD_ARGON2_DEFAULT_TIME_COST','PASSWORD_ARGON2_DEFAULT_THREADS','PASSWORD_DEFAULT'];

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
        protected $config;

        /**
         * Post ID
         * 
         * @param array
         */
        protected $id;

        /**
         * Post Type
         * 
         * @param string
         */
        protected $type;

        /**
         * Request Response
         * 
         * @param array
         */
        protected $response = [];

        /**
         * Custom Post used Schema items
         * 
         * @param array
         */
        protected $schema = [];

        /**
         * 
         */
        public function __construct($bs, array $config, int $id)
        {
            // Retrieve the bootstrap class instance
            $this->bs = $bs;

            // Define CustomPost config
            $this->setConfig($config);

            // Define Post ID
            $this->setID($id);
        }

        /**
         * Set config
         */
        private function setConfig(array $config)
        {
            $this->config = $config;

            return $this;
        }
        /**
         * Get config
         */
        protected function getConfig(string $key)
        {
            if (isset($this->config[$key])) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Set ID
         */
        private function setID(int $id)
        {
            $this->id = $id;

            return $this;
        }
        /**
         * Get ID
         */
        protected function getID()
        {
            return $this->id;
        }

        /**
         * Set ID
         */
        protected function setType(string $type)
        {
            $this->type = $type;

            return $this;
        }
        /**
         * Get ID
         */
        protected function getType()
        {
            return $this->type;
        }

        /**
         * Set Schema used in UI > edit > metaboxes
         */
        protected function setSchema()
        {
            $metaboxes = [];

            // Retrieve Metaboxes
            $ui = $this->getConfig('ui');
            if (isset($ui['pages']['edit']['metaboxes'])) 
            {
                $metaboxes = $ui['pages']['edit']['metaboxes'];
            }

            // Retrieve Schema Key for each metaboxes
            foreach ($metaboxes as $metabox)
            {
                if (isset($metabox['schema']))
                {
                    $this->schema = array_merge($this->schema, $metabox['schema']);
                }
            }

            // Retrieve schemas rules (in schema declaration)
            foreach ($this->schema as $index => $key) 
            {
                foreach ($this->getConfig('schema') as $schema) 
                {
                    if ($key == $schema['key'])
                    {
                        $this->schema[$index] = $this->setDefaultSchemaSettings($schema);
                    }
                }

                // remove from $schemas list all items are not found in 
                // schema declaration
                if (!is_array($this->schema[$index]))
                {
                    unset($this->schema[$index]);
                }
            }
        }
        /**
         * Get Shecma
         */
        protected function getSchema()
        {
            return $this->schema;
        }

        protected function updateSchema(string $key, string $index, $value)
        {
            foreach ($this->schema as $k => $schema) 
            {
                if ($key == $schema['key']) 
                {
                    $this->schema[$k][$index] = $value;
                }
            }
        }

        private function setDefaultSchemaSettings( array $item )
        {
            // -- Default item settings

            $item['type']                   = isset($item['type'])                      ? $item['type']                     : "text";
            $item['key']                    = isset($item['key'])                       ? $item['key']                      : null;
            $item['label']                  = isset($item['label'])                     ? $item['label']                    : null;
            $item['default']                = isset($item['default'])                   ? $item['default']                  : null;
            $item['helper']                 = isset($item['helper'])                    ? $item['helper']                   : null;
            $item['rules']['pattern']       = isset($item['rules']['pattern'])          ? $item['rules']['pattern']         : null;
            $item['rules']['size']          = isset($item['rules']['size'])             ? $item['rules']['size']            : null;
            $item['rules']['allowed_types'] = isset($item['rules']['allowed_types'])    ? $item['rules']['allowed_types']   : null;
            $item['attr']['id']             = isset($item['attr']['id'])                ? $item['attr']['id']               : null;
            $item['attr']['placeholder']    = isset($item['attr']['placeholder'])       ? $item['attr']['placeholder']      : null;
            $item['attr']['class']          = isset($item['attr']['class'])             ? $item['attr']['class']            : null;
            $item['attr']['maxlength']      = isset($item['attr']['maxlength'])         ? $item['attr']['maxlength']        : null;
            $item['attr']['max']            = isset($item['attr']['max'])               ? $item['attr']['max']              : null;
            $item['attr']['min']            = isset($item['attr']['min'])               ? $item['attr']['min']              : null;
            $item['attr']['step']           = isset($item['attr']['step'])              ? $item['attr']['step']             : null;
            $item['attr']['width']          = isset($item['attr']['width'])             ? $item['attr']['width']            : null;
            $item['attr']['cols']           = isset($item['attr']['cols'])              ? $item['attr']['cols']             : null;
            $item['attr']['rows']           = isset($item['attr']['rows'])              ? $item['attr']['rows']             : null;
            $item['attr']['required']       = isset($item['attr']['required'])          ? $item['attr']['required']         : false;
            $item['attr']['readonly']       = isset($item['attr']['readonly'])          ? $item['attr']['readonly']         : false;
            $item['attr']['disabled']       = isset($item['attr']['disabled'])          ? $item['attr']['disabled']         : false;
            $item['attr']['multiple']       = isset($item['attr']['multiple'])          ? $item['attr']['multiple']         : false;
            $item['expanded']               = isset($item['expanded'])                  ? $item['expanded']                 : false;
            $item['shortcode']              = isset($item['shortcode'])                 ? $item['shortcode']                : false;
            $item['preview']                = isset($item['preview'])                   ? $item['preview']                  : true;
            $item['choices']                = isset($item['choices'])                   ? $item['choices']                  : [];
            $item['messages']               = isset($item['messages'])                  ? $item['messages']                 : [];
            $item['algo']                   = isset($item['algo'])                      ? $item['algo']                     : [];

            // -- Default messages

            // Field is required
            if (!isset($item['messages']['required'])) 
            {
                $item['messages']['required'] = __("This field is required.", $this->bs->getTextDomain());
            }

            // Email type
            if (!isset($item['messages']['email'])) 
            {
                $item['messages']['email'] = __("This field is not a valid email address.", $this->bs->getTextDomain());
            }

            // URL type
            if (!isset($item['messages']['url'])) 
            {
                $item['messages']['url'] = __("This field is not a valid url.", $this->bs->getTextDomain());
            }

            // Time type
            if (!isset($item['messages']['time'])) 
            {
                $item['messages']['time'] = __("This field is not a valid time.", $this->bs->getTextDomain());
            }

            // Date type
            if (!isset($item['messages']['date'])) 
            {
                $item['messages']['date'] = __("This field is not a valid date.", $this->bs->getTextDomain());
            }

            // Year type
            if (!isset($item['messages']['year'])) 
            {
                $item['messages']['year'] = __("This field is not a valid year.", $this->bs->getTextDomain());
            }

            // Color type
            if (!isset($item['messages']['color'])) 
            {
                $item['messages']['color'] = __("This field is not a valid color.", $this->bs->getTextDomain());
            }

            // Password Confirmation type
            if (!isset($item['messages']['password_confirmation'])) 
            {
                $item['messages']['password_confirmation'] = __("Password is not confirmed.", $this->bs->getTextDomain());
            }

            // Invalid rule
            if (!isset($item['messages']['pattern'])) 
            {
                $item['messages']['pattern'] = __("This field is not valid.", $this->bs->getTextDomain());
            }

            // Invalide type
            if (!isset($item['messages']['type'])) 
            {
                $item['messages']['type'] = __("This field is not valid.", $this->bs->getTextDomain());
            }

            // Invalid file size
            if (null != $item['rules']['size'] && !isset($item['messages']['size'])) 
            {
                $item['messages']['size'] = __("This file size is not valid.", $this->bs->getTextDomain());
            }

            // Invalid file type
            if (null != $item['rules']['allowed_types'] && !isset($item['messages']['allowed_types'])) 
            {
                $item['messages']['allowed_types'] = __("This file is not valid.", $this->bs->getTextDomain());
            }

            // Default algo for password
            if ('password' == $item['type']) 
            {
                // default $algo
                $algo = [
                    'type' => null,
                    'options' => []
                ];

                // retrieve algo settings
                if (!empty($item['algo'])) 
                {
                    if (is_array($item['algo'])) 
                    {
                        if (isset($item['algo']['type'])) {
                            $algo['type'] = $item['algo']['type'];
                            unset($item['algo']['type']);
                        }
                        $algo['options'] = $item['algo'];
                    }
                    elseif (is_string($item['algo'])) 
                    {
                        $algo['type'] = $item['algo'];
                    }
                }

                // Is a valid algo
                if (!in_array($algo['type'], self::ALGO)) 
                {
                    $algo['type'] = "PASSWORD_DEFAULT";
                }

                $item['algo'] = $algo;
            }

            return $item;
        }












        public function response()
        {
            // Define default response
            $response_request = [];
            $response_files = [];

            // Define Post Type
            $this->setType($_REQUEST['post_type']);

            if ('POST' === $_SERVER['REQUEST_METHOD']) 
            {
                if ($this->getConfig('type') == $this->getType())
                {
                    // Retrieve schema default rules
                    $this->setSchema();

                    // Retrieve response from Request Header
                    if (isset($_REQUEST[$this->getType()])) {
                        $response_request = $_REQUEST[$this->getType()];
                    }
                    if (isset($_FILES[$this->getType()])) {
                        $response_files = $_FILES[$this->getType()];
                    }

                    // Add response to schema
                    foreach ($this->getSchema() as $key => $schema) 
                    {
                        $value = null;
                        if (!$schema['attr']['disabled']) 
                        {
                            switch ($schema['type']) 
                            {
                                // Define checkbox value to ON or OFF
                                case 'checkbox':
                                    $value = isset($response_request[$schema['key']]) ? "on" : "off";
                                    break;

                                // Hash the Password
                                case 'password':
                                    $value = $response_request[$schema['key']];
                                    $this->updateSchema($schema['key'], "plaintext", $value);
                                    $value = !empty($value) 
                                        ? password_hash($value, constant($schema['algo']['type'])) 
                                        : null;
                                    
                                    break;

                                case 'file':
                                    // TODO: File data
                                        // if (!empty($files['name'][$field['key']]))
                                        // {
                                        //     $field['files'] = [];
                                        //     foreach ($files as $key => $file)
                                        //     {
                                        //         if (isset($file[$field['key']]))
                                        //         {
                                        //             if (!is_array($file[$field['key']]))
                                        //             {
                                        //                 $field['files'][$key] = [$file[$field['key']]];
                                        //             }
                                        //             else
                                        //             {
                                        //                 $field['files'][$key] = $file[$field['key']];
                                        //             }
                                        //         }
                                        //     }
                                        // }
                                    break;

                                // Add value
                                default:
                                    if (isset($response_request[$schema['key']])) {
                                        $value = $response_request[$schema['key']];
                                    }
                                    break;
                            }
                        }

                        $this->updateSchema($schema['key'], "value", $value);
                    }

                }
            }





            // $schema = $this->getSchema();

            // foreach ($this->getSchema() as $key => $field)
            // {
            //     echo "<pre>";
            //     print_r($field);
            //     echo "</pre>";
            // }
            // return $this->getSchema();

            return $this;
        }

        public function validate()
        {
            foreach ($this->getSchema() as $key => $schema) 
            {

                // Default
                $error = [
                    'valid' => true,
                    'state' => 'success',
                    'message' => null,
                ];

                // $schema['attr']['required'] = true;
                // $schema['type'] = 'year';
                // $schema['value'] = 'Ba1985';

                // $schema['rules']['pattern'] = "/^a/";
            

                // Is String

                // Is required
                if ($schema['attr']['required'] && empty(trim($schema['value'])))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['required'],
                    ];
                }

                // Is email
                elseif ('email' == $schema['type'] && !empty($schema['value']) && !filter_var($schema['value'], FILTER_VALIDATE_EMAIL))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['email'],
                    ];
                }

                // Is URL
                elseif ('url' == $schema['type'] && !empty($schema['value']) && !filter_var($schema['value'], FILTER_VALIDATE_URL))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['email'],
                    ];
                }
                
                // Is Number
                elseif ('number' == $schema['type'] && !empty($schema['value']) && !(is_int($schema['value']) || is_double($schema['value']) || is_float($schema['value'])))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['type'],
                    ];
                }

                // Is Date
                elseif ('date' == $schema['type'])
                {
                    $date = explode("-", $schema['value']);

                    $year = isset($date[0]) ? $date[0] : null;
                    $month = isset($date[1]) ? $date[1] : null;
                    $day = isset($date[2]) ? $date[2] : null;

                    if (null == $year || null == $month || null == $day || !checkdate($month, $day, $year)) 
                    {
                        $error = [
                            'valid' => false,
                            'state' => 'danger',
                            'message' => $schema['messages']['date'],
                        ];
                    }
                }

                // Is Time
                elseif ('time' == $schema['type'] && !empty($schema['value']) && !preg_match(self::RE_TIME, $schema['value']))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['time'],
                    ];
                }

                // Is Datetime

                // Is Month

                // Is Week

                // Is Year
                elseif ('year' == $schema['type'] && !empty($schema['value']) && !preg_match("/^\d{4}$/", $schema['value']))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['year'],
                    ];
                }

                // Is Color
                elseif ('color' == $schema['type'] && !empty($schema['value']) && !preg_match(self::RE_COLOR, $schema['value']))
                {
                    $error = [
                        'valid' => false,
                        'state' => 'danger',
                        'message' => $schema['messages']['color'],
                    ];
                }

                // Is Comfirmed password
                elseif ('password' == $schema['type'] && isset($schema['rules']['confirm']))
                {
                    $password = '';
                    $confirmation = $schema['plaintext'];
                    foreach ($this->getSchema() as $item) 
                    {
                        if ($item['key'] == $schema['rules']['confirm']) {
                            $password = $item['plaintext'];
                        }
                    }

                    if ($password !== $confirmation) {
                        $error = [
                            'valid' => false,
                            'state' => 'danger',
                            'message' => $schema['messages']['password_confirmation'],
                        ];
                    }
                }

                // Is file

                // Rule pattern
                elseif (!empty($schema['value']) && isset($schema['rules']['pattern']))
                {
                    $track_errors = ini_get('track_errors');

                    ini_set('track_errors', 'on');
                    $php_errormsg = '';
                    @preg_match($schema['rules']['pattern'], '');

                    if (empty($php_errormsg)) 
                    {
                        if (!preg_match($schema['rules']['pattern'], $schema['value']))
                        {
                            $error = [
                                'valid' => false,
                                'state' => 'danger',
                                'message' => $schema['messages']['pattern'],
                            ];
                        }
                    }

                    ini_set('track_errors', $track_errors);
                }

                // Is > to Min

                // Is < to Max

                // Is < to Maxlegth

                $this->updateSchema($schema['key'], "error", $error);
            }



            // echo "<pre>";
            // print_r(
            //     $_REQUEST['ppm_custom_post']
            // );
            // echo "</pre>";
            // echo "<pre>";
            // print_r(
            //     $_POST
            // );
            // echo "</pre>";
            echo "<pre>";
            print_r(
                $this->getSchema()
            );
            echo "</pre>";
        }
    }
}