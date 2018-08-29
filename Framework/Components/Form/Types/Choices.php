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
use \Framework\Components\Form\Types\Checkbox;
use \Framework\Components\Form\Types\Radio;
use \Framework\Components\Form\Types\Option;

if (!class_exists('Framework\Components\Form\Types\Choices'))
{
    class Choices extends Types 
    {
        /**
         * Tag Attributes
         */
        public function attributes()
        {
            return ['id', 'name', 'class', 'multiple'];
        }

        /**
         * Tag Template
         */
        public function tag()
        {
            switch ($this->getType())
            {
                case 'choices_checkbox':
                case 'choices_radio':
                    return $this->tagChoices();

                case 'select':
                case 'choices_select':
                default:
                    return $this->tagSelect();
            }
        }

        /**
         * Field Builder
         */
        public function builder()
        {
            $this->setChoices();
            $this->setExpanded();

            if ($this->getExpanded() && $this->getMultiple()) {
                $this->setType("choices_checkbox");
            }
            elseif ($this->getExpanded() && !$this->getMultiple()) {
                $this->setType("choices_radio");
            }
            else {
                $this->setType("choices_select");
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

                switch ($this->getType())
                {
                    case 'choices_checkbox':
                        $tag.= $this->tagOptionChoice(new Checkbox($options));
                        break;

                    case 'choices_radio':
                        $tag.= $this->tagOptionChoice(new Radio($options));
                        break;

                    case 'choices_select':
                        $field = new Option($options);
                        $tag.= $field->render();
                        break;
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
