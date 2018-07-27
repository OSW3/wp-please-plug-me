<?php

namespace Framework\Components\Form\Form;

// Make sure we don't expose any info if called directly
if (!defined('WPINC'))
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
	exit;
}

if (!class_exists('Framework\Components\Form\Form\Form'))
{
    abstract class Form
    {
        /**
         * Field config
         */
        private $config;

        /**
         * Field Attrs
         */
        private $attrs;

        private $template;

        private $type;
        private $id;
        private $class;
        private $placeholder;
        private $required;
        private $readonly;
        private $disabled;
        private $maxLength;
        private $step;
        private $min;
        private $max;
        private $width;
        private $cols;
        private $rows;

        /**
         * 
         */
        public function __construct(array $config)
        {
            // define Field Type
            $this->setConfig($config);
            
            // Build the field
            $this->builder();
        }

        /**
         * Default Field Builder
         */
        public function builder() {}

        /**
         * Render
         * 
         * Rendering the field
         */
        public function render()
        {
            $this->settemplate();


            // Init the Output
            $output = '';

            // Init the Attributes
            $attributes = '';

            $attributes.= $this->getAttrType();
            $attributes.= $this->getAttrID();
            $attributes.= $this->getAttrClass();
            $attributes.= $this->getAttrPlaceholder();
            $attributes.= $this->getAttrRequired();
            $attributes.= $this->getAttrReadonly();
            $attributes.= $this->getAttrDisabled();
            $attributes.= $this->getAttrMaxLength();
            $attributes.= $this->getAttrMin();
            $attributes.= $this->getAttrMax();
            $attributes.= $this->getAttrStep();
            $attributes.= $this->getAttrCols();
            $attributes.= $this->getAttrRows();
            $attributes.= $this->getAttrWidth();

            $output.= preg_replace("/{{attributes}}/", $attributes, $this->getTemplate());
            $output.= "<".preg_replace("/{{attributes}}/", $attributes, $this->getTemplate())."/>";

            return $output;
        }

        /**
         * Set Field type
         */
        private function setConfig(array $config)
        {
            $this->config = $config;

            $this->setAttrs();

            return $this;
        }

        /**
         * Get Field Type
         */
        private function getConfig(string $key = '')
        {
            if (isset( $this->config[$key] )) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Retrive Atributes
         */
        private function setAttrs()
        {
            // Default Attrs
            $this->attrs = array();

            $config = $this->config;

            if (isset($config['attr']) && is_array($config['attr']))
            {
                $this->attrs = $config['attr'];
            }

            return $this;
        }

        /**
         * Get Attribute
         */
        private function getAttr(string $key = '')
        {
            if (isset( $this->attrs[$key] )) 
            {
                return $this->attrs[$key];
            }

            return null;
        }

        /**
         * 
         */
        private function setTemplate()
        {
            switch ($this->getType())
            {
                case '':
                    break;

                default:
                    $this->template = "input {{attributes}} ";
                    // $this->template = "<input {{attributes}} />";
            }

            return $this;
        }
        private function getTemplate()
        {
            return $this->template;
        }

        /**
         * Set Field Type
         */
        protected function setType(string $type)
        {
            $this->type = $type;
            // Default type
            // $type = "text";

            // if ($this->getConfig('type'))
            // {
            //     $this->type = $this->getConfig('type');
            // }

            return $this;
        }
        private function getType()
        {
            return $this->type;
        }
        private function getAttrType()
        {
            return ' type="'. $this->getType() .'"';
        }

        /**
         * Set Attribute ID
         */
        protected function setID()
        {
            $id = $this->getAttr('id');

            if (null != $id) 
            {
                $this->id = $id;
            }
            else
            {
                $this->id = $this->getConfig('key');
            }

            return $this;
        }
        private function getID()
        {
            return $this->id;
        }
        private function getAttrID()
        {
            return ' id="'. $this->getID() .'"';
        }

        /**
         * Set Attribute Required
         */
        protected function setRequired()
        {
            // Default required
            $this->required = false;

            // Retrive Required parameters
            $required = $this->getAttr('required');

            if (is_bool($required))
            {
                $this->required = $required;
            }

            return $this;
        }
        private function getRequired()
        {
            return $this->required;
        }
        private function getAttrRequired()
        {
            return $this->getRequired() ? ' required="required"' : null;
        }

        /**
         * Set Attribute Readonly
         */
        protected function setReadonly()
        {
            // Default readonly
            $this->readonly = false;

            // Retrive Readonly parameters
            $readonly = $this->getAttr('readonly');

            if (is_bool($readonly))
            {
                $this->readonly = $readonly;
            }

            return $this;
        }
        private function getReadonly()
        {
            return $this->readonly;
        }
        private function getAttrReadonly()
        {
            return $this->getReadonly() ? ' readonly="readonly"' : null;
        }

        /**
         * Set Attribute Disabled
         */
        protected function setDisabled()
        {
            // Default readonly
            $this->disabled = false;

            // Retrive Disabled parameters
            $disabled = $this->getAttr('disabled');

            if (is_bool($disabled))
            {
                $this->disabled = $disabled;
            }

            return $this;
        }
        private function getDisabled()
        {
            return $this->disabled;
        }
        private function getAttrDisabled()
        {
            return $this->getDisabled() ? ' disabled="disabled"' : null;
        }

        /**
         * Set Attribute Class
         */
        protected function setClass()
        {
            // Default class
            $this->class = null;

            // Retrive Class parameters
            $class = $this->getAttr('class');

            if (is_string($class))
            {
                $this->class = $class;
            }

            return $this;
        }
        private function getClass()
        {
            return $this->class;
        }
        private function getAttrClass()
        {
            if (null != $this->getClass())
            {
                return ' class="'.$this->getClass().'"';
            }

            return null;
        }

        /**
         * Set Attribute Placeholder
         */
        protected function setPlaceholder()
        {
            // Default placeholder
            $this->placeholder = null;

            // Retrive placeholder parameters
            $placeholder = $this->getAttr('placeholder');

            if (is_string($placeholder))
            {
                $this->placeholder = $placeholder;
            }

            return $this;
        }
        private function getPlaceholder()
        {
            return $this->placeholder;
        }
        private function getAttrPlaceholder()
        {
            if (null != $this->getPlaceholder())
            {
                return ' placeholder="'.$this->getPlaceholder().'"';
            }

            return null;
        }

        /**
         * Set Attribute Max Length
         */
        protected function setMaxLength()
        {
            // Default maxLength
            $this->maxLength = null;

            // Retrive Max parameters
            $maxLength = $this->getAttr('maxlength');

            if (is_int($maxLength))
            {
                $this->maxLength = $maxLength;
            }

            return $this;
        }
        private function getMaxLength()
        {
            return $this->maxLength;
        }
        private function getAttrMaxLength()
        {
            return $this->getMaxLength() ? ' maxlength="'.$this->getMaxLength().'"' : null;
        }

        /**
         * Set Attribute Step
         */
        protected function setStep()
        {
            // Default Step
            $this->step = null;

            // Retrive Step parameters
            $step = $this->getAttr('step');

            if (is_int($step))
            {
                $this->step = $step;
            }

            return $this;
        }
        private function getStep()
        {
            return $this->step;
        }
        private function getAttrStep()
        {
            return $this->getStep() ? ' step="'.$this->getStep().'"' : null;
        }

        /**
         * Set Attribute Max 
         */
        protected function setMax()
        {
            // Default max
            $this->max = null;

            // Retrive Max parameters
            $max = $this->getAttr('max');

            if (is_int($max))
            {
                $this->max = $max;
            }

            return $this;
        }
        private function getMax()
        {
            return $this->max;
        }
        private function getAttrMax()
        {
            return $this->getMax() ? ' max="'.$this->getMax().'"' : null;
        }

        /**
         * Set Attribute Min
         */
        protected function setMin()
        {
            // Default min
            $this->min = null;

            // Retrive Min parameters
            $min = $this->getAttr('min');

            if (is_int($min))
            {
                $this->min = $min;
            }

            return $this;
        }
        private function getMin()
        {
            return $this->min;
        }
        private function getAttrMin()
        {
            return $this->getMin() ? ' min="'.$this->getMin().'"' : null;
        }

        /**
         * Set Attribute Cols
         */
        protected function setCols()
        {
            // Default cols
            $this->cols = null;

            // Retrive cols parameters
            $cols = $this->getAttr('cols');

            if (is_int($cols))
            {
                $this->cols = $cols;
            }

            return $this;
        }
        private function getCols()
        {
            return $this->cols;
        }
        private function getAttrCols()
        {
            return $this->getCols() ? ' cols="'.$this->getCols().'"' : null;
        }

        /**
         * Set Attribute Rows
         */
        protected function setRows()
        {
            // Default Rows
            $this->rows = null;

            // Retrive rows parameters
            $rows = $this->getAttr('rows');

            if (is_int($rows))
            {
                $this->rows = $rows;
            }

            return $this;
        }
        private function getRows()
        {
            return $this->rows;
        }
        private function getAttrRows()
        {
            return $this->getRows() ? ' rows="'.$this->getRows().'"' : null;
        }

        /**
         * Set Attribute Width
         */
        protected function setWidth()
        {
            // Default Width
            $this->width = null;

            // Retrive rows parameters
            $width = $this->getAttr('width');

            if (is_int($width))
            {
                $this->width = $width;
            }

            return $this;
        }
        private function getWidth()
        {
            return $this->width;
        }
        private function getAttrWidth()
        {
            return $this->getWidth() ? ' width="'.$this->getWidth().'"' : null;
        }


    }
}
