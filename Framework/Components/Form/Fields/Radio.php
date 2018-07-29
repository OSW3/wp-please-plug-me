<?php

namespace Framework\Components\Form\Fields;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Fields\Text;

if (!class_exists('Framework\Components\Form\Fields\Radio'))
{
    class Radio extends Text 
    {
        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setType('radio');
        }
    }
}
