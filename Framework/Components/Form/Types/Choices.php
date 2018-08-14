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
use \Framework\Components\Form\Types\Checkbox;
use \Framework\Components\Form\Types\Radio;
use \Framework\Components\Form\Types\Option;

if (!class_exists('Framework\Components\Form\Types\Choices'))
{
    class Choices extends Form 
    {
        /**
         * Tag Template
         */
        protected function tag()
        {
            switch ($this->getType())
            {
                case 'choices_checkbox':
                case 'choices_radio':
                    return $this->tagChoices();

                // case 'select':
                // case 'choices_select':
                default:
                    return $this->tagSelect();
            }
        }

        /**
         * 
         */
        private function tagChoices()
        {
            return '<div class="choices-expanded '.$this->getClass().'">'.$this->options().'</div>';
        }

        /**
         * 
         */
        private function tagSelect()
        {
            return '<select{{attributes}}>'.$this->options().'</select>';
        }

        /**
         * 
         */
        private function options()
        {
            $tag = '';

            foreach ($this->getChoices() as $value => $label) 
            {
                // Tag options
                $options = array_merge($this->config,[
                    "label"     => $label,
                    "value"     => $value,
                    // "selected"  => $this->selected === $value,
                    "choices"   => []
                ]);

                // Tag object (checkbox)
                if ($this->getExpanded() && $this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Types\Checkbox($options));
                }

                // Tag object (radio)
                elseif ($this->getExpanded() && !$this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Types\Radio($options));
                }

                // Tag object (select)
                else {
                    $field = new \Framework\Components\Form\Types\Option($options);
                    $tag.= $field->render();
                }
            }

            return $tag;
        }


        private function tagOptionChoice( $field )
        {
            $tag = '<div class="choices-option"><label>$1 $2</label></div>';

            if ('checkbox' == $field->getType())
            {
                $field->setName( $field->getName().'['.$field->getValue().']' );
            }

            $tag = preg_replace("/\\$1/", $field->render(), $tag);
            $tag = preg_replace("/\\$2/", $field->getLabel(), $tag);

            return $tag;
        }
    }
}
