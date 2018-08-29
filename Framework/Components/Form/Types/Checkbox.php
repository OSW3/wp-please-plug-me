<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Types\Text;

if (!class_exists('Framework\Components\Form\Types\Checkbox'))
{
    class Checkbox extends Text 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            return ['type', 'id', 'name', 'class', 'value', 'autofocus', 'disabled', 'readonly', 'required'];
        }

        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setType('checkbox');
        }

        /**
         * Override Attr Value
         */
        public function getAttrValue()
        {
            // Define attribute string
            $attr = '';

            // Retrieve default value
            $defaults = $this->getConfig('default');

            // Make sure $defaults is array (array needed for multiple)
            if (!is_array($defaults))
            {
                $defaults = [$defaults];
            }

            foreach ($defaults as $default) 
            {
                if ($this->getValue() === $default || 'on' === strtolower($this->getValue()))
                {
                    $attr.= ' checked="checked""';
                }
            }

            return $attr;
        }
    }
}