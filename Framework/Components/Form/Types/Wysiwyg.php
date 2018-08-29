<?php

namespace Framework\Components\Form\Types;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

use \Framework\Components\Form\Types;

if (!class_exists('Framework\Components\Form\Types\Wysiwyg'))
{
    class Wysiwyg extends Types 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            // TODO: Placeholder alternative
            // TODO: Maxlength alternative
            // TODO: Autofocus alternative
            return ['type', 'id', 'name', 'class', 'value', 'autofocus', 'disabled', 'required', 'readonly', 'rows'];
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            // -- Define Editor Settings
            $settings = array();

            // Display the button "Medias"
            $settings['media_buttons'] = false;

            // Editor class
            $settings['editor_class'] = $this->getClass();

            // Editor Name Attribute
            // $settings['textarea_name'] = $this->getId();
            $settings['textarea_name'] = $this->getName();

            // Editor Height
            $settings['textarea_rows'] = $this->getRows() ? $this->getRows() : 10;


            ob_start();
            wp_editor( $this->getValue(), $this->getId(), $settings );
            return ob_get_clean();
        }

        /**
         * Builder
         */
        public function builder()
        {
            // $id = preg_replace("/\\[|\\]/", "____", $this->getName());
            $id = $this->getName();
            $id = preg_replace("/\\[|\\]/", "†", $id);
            $this->setId($id);

            // Readonly or Disabled attribute
            add_filter( 'tiny_mce_before_init', function( $args ) {
                $args['readonly'] = $this->getReadonly() || $this->getDisabled();
                return $args;
            });
        }
    }
}