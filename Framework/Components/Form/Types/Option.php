<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Form\Form;

if (!class_exists('Framework\Components\Form\Types\Option'))
{
    class Option extends Form 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            // TODO: Disabled
            // TODO: OptGroup
            return ['value', 'disabled'];
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            return '<option{{attributes}}>'.$this->getLabel().'</option>';
        }

        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setType('option');
        }

        /**
         * Override Attr Value
         */
        public function getAttrValue()
        {
            // Retrieve default value
            $defaults = $this->getConfig('default');

            // Make sure $defaults is array (array needed for multiple)
            if (!is_array($defaults))
            {
                $defaults = [$defaults];
            }


            $attr = parent::getAttrValue();

            foreach ($defaults as $default) 
            {
                if ($this->getValue() === $default)
                {
                    $attr.= ' selected="selected"';
                }
            }

            return $attr;
        }
    }
}
