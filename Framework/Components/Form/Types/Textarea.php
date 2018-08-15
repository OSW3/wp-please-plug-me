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

if (!class_exists('Framework\Components\Form\Types\Textarea'))
{
    class Textarea extends Form 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            return ['id', 'name', 'class', 'value', 'autofocus', 'disabled', 'maxlength', 'required', 'readonly', 'placeholder', 'cols', 'rows'];
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            return '<textarea{{attributes}}>'.$this->getValue().'</textarea>';
        }
    }
}
