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

if (!class_exists('Framework\Components\Form\Types\Url'))
{
    class Url extends Form 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            // TODO: List
            // TODO: Dirname
            // TODO: Pattern
            return ['type', 'id', 'name', 'class', 'value', 'autocomplete', 'autofocus', 'disabled', 'maxlength', 'placeholder', 'readonly', 'required', 'size'];
        }
    }
}
