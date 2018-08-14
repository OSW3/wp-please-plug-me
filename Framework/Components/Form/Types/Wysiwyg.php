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

if (!class_exists('Framework\Components\Form\Types\Wysiwyg'))
{
    class Wysiwyg extends Form 
    {
        /**
         * Tag Template
         */
        protected function tag()
        {
            $settings = array(
                'media_buttons' => false,
                // 'quicktags' => array( 
                //     'buttons' => 'strong,em,del,ul,ol,li,close' 
                // ),
                'editor_class' => $this->getClass(),
                'textarea_name' => $this->getId(),
                'textarea_rows' => 8
            );

            ob_start();
            wp_editor( $this->getValue(), $this->getId(), $settings );
            return ob_get_clean();
        }
    }
}
