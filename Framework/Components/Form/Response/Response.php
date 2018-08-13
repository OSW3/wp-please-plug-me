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
use \Framework\Kernel\Session;

if (!class_exists('Framework\Components\Form\Response\Response'))
{
    class Response
    {
        const RE_TIME = "/^(00|[0-1][0-9]|2[0-3]):([0-5][0-9])$/";
        const RE_COLOR = "/#([a-f0-9]{3}){1,2}\b/i";

        /**
         * Available Encryption engine
         */
        const ALGO = [
            'PASSWORD_BCRYPT','PASSWORD_ARGON2I',
            'PASSWORD_ARGON2_DEFAULT_MEMORY_COST',
            'PASSWORD_ARGON2_DEFAULT_TIME_COST',
            'PASSWORD_ARGON2_DEFAULT_THREADS','PASSWORD_DEFAULT'
        ];

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
         * Set Post config
         */
        private function setConfig(array $config)
        {
            $this->config = $config;          

            return $this;
        }
        /**
         * Get Post config
         */
        private function getConfig(string $key)
        {
            if (isset($this->config[$key])) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Set Post ID
         */
        private function setID(int $id)
        {
            $this->id = $id;

            return $this;
        }
        /**
         * Get Post ID
         */
        private function getID()
        {
            return $this->id;
        }

        /**
         * Set Post Type
         */
        private function setType(string $type)
        {
            $this->type = $type;

            return $this;
        }
        /**
         * Get Post Type
         */
        private function getType()
        {
            return $this->type;
        }

        /**
         * Set Schema used in UI > edit > metaboxes
         */
        private function setSchema()
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
        public function getSchema()
        {
            return $this->schema;
        }
        /**
         * 
         */
        private function updateSchema(string $key, string $index, $value)
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

            // -- Default error messages

            $item['messages']['required']   = isset($item['messages']['required'])      ? $item['messages']['required']     : "This field is required.";
            $item['messages']['email']      = isset($item['messages']['email'])         ? $item['messages']['email']        : "This field is not a valid email.";
            $item['messages']['url']        = isset($item['messages']['url'])           ? $item['messages']['url']          : "This field is not a valid url.";
            $item['messages']['time']       = isset($item['messages']['time'])          ? $item['messages']['time']         : "This field is not a valid time.";
            $item['messages']['date']       = isset($item['messages']['date'])          ? $item['messages']['date']         : "This field is not a valid date.";
            $item['messages']['year']       = isset($item['messages']['year'])          ? $item['messages']['year']         : "This field is not a valid year.";
            $item['messages']['color']      = isset($item['messages']['color'])         ? $item['messages']['color']        : "This field is not a valid color";
            $item['messages']['confirm']    = isset($item['messages']['confirm'])       ? $item['messages']['confirm']      : "Password is not confirmed.";
            $item['messages']['pattern']    = isset($item['messages']['pattern'])       ? $item['messages']['pattern']      : "This field is not valid.";
            $item['messages']['type']       = isset($item['messages']['type'])          ? $item['messages']['type']         : "This field is not valid.";
            $item['messages']['min']        = isset($item['messages']['min'])           ? $item['messages']['min']          : "This value must not be less than $1.";
            $item['messages']['max']        = isset($item['messages']['max'])           ? $item['messages']['max']          : "This value must not be greater than $1.";
            $item['messages']['maxlength']  = isset($item['messages']['maxlength'])     ? $item['messages']['maxlength']    : "This value is too long.";
            $item['messages']['size']       = isset($item['messages']['size'])          ? $item['messages']['size']         : "This file size is not valid.";
            $item['messages']['file_types'] = isset($item['messages']['file_types'])    ? $item['messages']['file_types']   : "This file is not valid.";

            // -- Default algo for password

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

        /** 
         * Retrieve response
         * 
         * Retrieve response form the Request and store the response 
         * into the field schema
         */
        public function response()
        {
            // Define default response
            $response_request = [];
            $response_files = [];

            $session = new Session( $this->bs->getNamespace() );

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

                        foreach ($_REQUEST as $key => $value) 
                        {
                            if (preg_match("/^".$_REQUEST['post_type']."____(.+)____$/", $key, $m))
                            {
                                $response_request += [$m[1] => $value];
                            }
                        }
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
                                    $plaintext = $response_request[$schema['key']];
                                    $this->updateSchema($schema['key'], "plaintext", $plaintext);
                                    $value = !empty($plaintext) 
                                        ? password_hash($plaintext, constant($schema['algo']['type'])) 
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
                            }
                        }

                        // Set value to the schema
                        $this->updateSchema($schema['key'], "value", $value);

                        // Set value to the session
                        if ($schema['type'] == 'password') {
                            $session->responses($this->getType(), [$schema['key'] => $plaintext]);
                        } else {
                            $session->responses($this->getType(), [$schema['key'] => $value]);
                        }
                    }
                }
            }
            
            return $this;
        }


        /**
         * Validate response
         * 
         * Read each response, check rules and add a message error into 
         * the field schema
         */
        public function validate()
        {
            $errors = [];

            $session = new Session( $this->bs->getNamespace() );
            $notices = new Notices( $this->bs->getNamespace() );
            
            foreach ($this->getSchema() as $key => $schema) 
            {
                // Default State
                $error_message = null;

                // Is String

                // Is required
                if ($schema['attr']['required'] && empty(trim($schema['value'])))
                {
                    $error_message = $schema['messages']['required'];
                }

                // Is email
                elseif ('email' == $schema['type'] && !empty($schema['value']) && !filter_var($schema['value'], FILTER_VALIDATE_EMAIL))
                {
                    $error_message = $schema['messages']['email'];
                }

                // Is URL
                elseif ('url' == $schema['type'] && !empty($schema['value']) && !filter_var($schema['value'], FILTER_VALIDATE_URL))
                {
                    $error_message = $schema['messages']['url'];
                }
                
                // Is Number
                elseif ('number' == $schema['type'] && !empty($schema['value']) && !(is_int(intval($schema['value'])) || is_double($schema['value']) || is_float($schema['value'])))
                {
                    $error_message = $schema['messages']['type'];
                }

                // Is Date
                elseif ('date' == $schema['type'] && !empty($schema['value']))
                {
                    $date = explode("-", $schema['value']);

                    $year = isset($date[0]) ? $date[0] : null;
                    $month = isset($date[1]) ? $date[1] : null;
                    $day = isset($date[2]) ? $date[2] : null;

                    if (null == $year || null == $month || null == $day || !checkdate($month, $day, $year)) 
                    {
                        $error_message = $schema['messages']['date'];
                    }
                }

                // Is Time
                elseif ('time' == $schema['type'] && !empty($schema['value']) && !preg_match(self::RE_TIME, $schema['value']))
                {
                    $error_message = $schema['messages']['time'];
                }

                // Is Datetime
                // TODO: checking value

                // Is Month
                // TODO: checking value

                // Is Week
                // TODO: checking value

                // Is Year
                elseif ('year' == $schema['type'] && !empty($schema['value']) && !preg_match("/^\d{4}$/", $schema['value']))
                {
                    $error_message = $schema['messages']['year'];
                }

                // Is Color
                elseif ('color' == $schema['type'] && !empty($schema['value']) && !preg_match(self::RE_COLOR, $schema['value']))
                {
                    $error_message = $schema['messages']['color'];
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
                        $error_message = $schema['messages']['confirm'];
                    }
                }

                // Is file
                // TODO: checking value

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
                            $error_message = $schema['messages']['pattern'];
                        }
                    }

                    ini_set('track_errors', $track_errors);
                }

                // Is > to Min
                elseif (!empty($schema['attr']['min']) && $schema['value'] < $schema['attr']['min']) 
                {
                    $error_message = preg_replace("/\\$1/", $schema['attr']['min'], $schema['messages']['min']);
                }

                // Is < to Max
                elseif (!empty($schema['attr']['max']) && $schema['value'] > $schema['attr']['max']) 
                {
                    $error_message = preg_replace("/\\$1/", $schema['attr']['max'], $schema['messages']['max']);
                }

                // Is < to Maxlegth
                elseif (!empty($schema['attr']['maxlength']) && $schema['attr']['maxlength'] > 0 && strlen($schema['value']) > $schema['attr']['maxlength']) 
                {
                    $error_message = $schema['messages']['maxlength'];
                }

                // Push the error to the errors collector
                if (null != $error_message)
                {
                    array_push($errors, [
                        'key' => $schema['key'],
                        'message' => $error_message
                    ]);
                }

            }

            // Add message to a notice
            if (!empty($errors))
            {
                $notices->danger($this->getType(), "The form has not been saved.");
            }

            // Set errors to the session
            $session->errors($this->getType(), $errors);

            return empty($errors);
        }
    }
}