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
        const TYPES = ['Choices','Cols','Disabled','Expanded','Helper',
            'Id','Label','Max','MaxLength','Min','Multiple','Name',
            'Placeholder','Readonly','Required','Rows','Step','Type','Value',
            'Width','Class'];

        /**
         * Field Attrs
         */
        private $attrs;
        
        /**
         * 
         */ 
        private $choices;
        
        /**
         * 
         */ 
        private $class;
        
        /**
         * 
         */ 
        private $cols;
        
        /**
         * Field config
         */
        private $config;

        /**
         * 
         */ 
        private $disabled;
        
        /**
         * 
         */ 
        private $expanded;
        
        /**
         * 
         */ 
        private $helper;
        
        /**
         * 
         */ 
        private $id;
        
        /**
         * 
         */ 
        private $label;
        
        /**
         * 
         */ 
        private $max;
        
        /**
         * 
         */ 
        private $maxLength;
        
        /**
         * 
         */ 
        private $min;
        
        /**
         * 
         */ 
        private $multiple;
        
        /**
         * 
         */ 
        private $name;
        
        /**
         * 
         */ 
        private $placeholder;
        
        /**
         * 
         */ 
        private $readonly;
        
        /**
         * 
         */ 
        private $required;
        
        /**
         * 
         */ 
        private $rows;
        
        /**
         * 
         */ 
        private $step;

        /**
         * 
         */
        private $template;

        /**
         * 
         */
        private $tmplOptions;
        
        /**
         * 
         */ 
        private $type;
        
        /**
         * 
         */ 
        private $value;
        
        /**
         * 
         */ 
        private $width;

        /**
         * 
         */
        public function __construct(array $config, array $template_options = [])
        {
            // define Field Type
            $this->setConfig($config);
            $this->tmplOptions = array_merge([
                "metabox" => false,
                "choices" => false,
            ],$template_options);

            // Call setter methods
            foreach (self::TYPES as $type) 
            {
                $method = 'set'.$type;
                $this->$method();
            }

            // Build the field
            $this->builder();


            
            
            
            
            
            
            
            


            // echo "<pre>";
            // print_r([
            //     "type" => $this->getType(),



            //     "Label" => $this->getLabel(),
            //     "Helper" => $this->getHelper(),
            //     "Value" => $this->getValue(),

            //     "Multiple" => $this->getMultiple(),
            //     "Expanded" => $this->getExpanded(),
            //     "ID" => $this->getID(),
            //     "Class" => $this->getClass(),
            //     "Placeholder" => $this->getPlaceholder(),
            //     "Required" => $this->getRequired(),
            //     "Readonly" => $this->getReadonly(),
            //     "Disabled" => $this->getDisabled(),
            //     "MaxLength" => $this->getMaxLength(),
            //     "Width" => $this->getWidth(),
            //     "Min" => $this->getMin(),
            //     "Max" => $this->getMax(),
            //     "Step" => $this->getStep(),
            //     "Cols" => $this->getCols(),
            //     "Rows" => $this->getRows(),
            //     "Choices" => $this->getChoices(),
            // ]);
            // echo "</pre>";
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
            //  Template Options
            $options = $this->tmplOptions;

            // print_r($options);


            // $this->setTemplate();

            // Init the Output
            $output = '';

            

            // Output for metabox
            if ($options['metabox']) 
            {
                $output.= '<tr>';
                $output.= '<th scope="row">';
                $output.= $this->tagLabel();
                $output.= '</th>';
                $output.= '<td>';
                $output.= $this->tagTemplate();
                $output.= $this->tagHelper();
                $output.= '</td>';
                $output.= '</tr>';
            }

            else {

                $output.= $this->tagTemplate();
                // $output.= preg_replace("/{{attributes}}/", $attributes, $this->getTemplate());
            }

            return $output;
        }


        /**
         * ----------------------------------------
         * Tag templates
         * ----------------------------------------
         */

        /**
         * Label
         */
        private function tagLabel()
        {
            $tag = '<label$1>$2$3</label>';

            if (!empty($this->getId())) { 
                $tag = preg_replace("/\\$1/", ' for="'.$this->getId().'"', $tag);
            }

            if (!empty($this->getLabel())) {
                $tag = preg_replace("/\\$2/", $this->getLabel(), $tag);
            }

            if ($this->getRequired()) { 
                $tag = preg_replace("/\\$3/", ' <span>*</span>', $tag);
            }

            $tag = preg_replace("/(:?\\$1|\\$2|\\$3)/", null, $tag);

            return $tag;
        }
        /**
         * Helper
         */
        private function tagHelper()
        {
            if (!empty($this->getHelper()))
            {
                return '<p class="description">' . $this->getHelper() . '</p>';
            }

            return null;
        }
        /**
         * <option>
         */
        private function tagOption()
        {
            return '<option{{attributes}}>'.$this->getLabel().'</option>';
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
        /**
         * Choices Container for not expanded Choices
         * <select>
         */
        private function tagChoicesSelect()
        {
            return '<select{{attributes}}>'.$this->tagChoicesOption().'</select>';
        }
        /**
         * Choices Container for expanded Choices
         * <input type="checkbox">
         * <input type="radio">
         */
        private function tagChoicesInput()
        {
            return '<div class="choices-expanded '.$this->getClass().'">'.$this->tagChoicesOption().'</div>';
        }
        /**
         * Choice item
         */
        private function tagChoicesOption()
        {
            $tag = '';

            foreach ($this->getChoices() as $value => $label) 
            {
                // Tag options
                $options = array_merge($this->config,[
                    "label"     => $label,
                    "value"     => $value,
                    "choices"   => []
                ]);

                // Tag object (checkbox)
                if ($this->getExpanded() && $this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Fields\Checkbox($options));
                }

                // Tag object (radio)
                elseif ($this->getExpanded() && !$this->getMultiple()) {
                    $tag.= $this->tagOptionChoice(new \Framework\Components\Form\Fields\Radio($options));
                }

                // Tag object (select)
                else {
                    $field = new \Framework\Components\Form\Fields\Option($options);
                    $tag.= $field->render();
                }
            }

            return $tag;
        }
        /**
         * <input/>
         */
        private function tagInput()
        {
            return '<input{{attributes}} />';
        }
        /**
         * <textarea>
         */
        private function tagTextarea()
        {
            return '<textarea{{attributes}}>'.$this->getValue().'</textarea>';
        }
        /**
         * <output>
         */
        private function tagOutput()
        {
            // TODO: Inject JS calculation into <form> --> https://www.w3schools.com/tags/tag_output.asp

            // $for = $this->getConfig('post_type');
            // $for.= '['.$this->getValue().']';

            $for = $this->getValue();

            return '<output name="'.$this->getName().'" for="'.$for.'"></output>';
        }

        private function tagAttributes()
        {
            $attr = '';
            $attr.= $this->getAttrType();
            $attr.= $this->getAttrId();
            $attr.= $this->getAttrName();
            $attr.= $this->getAttrClass();
            $attr.= $this->getAttrPlaceholder();
            $attr.= $this->getAttrRequired();
            $attr.= $this->getAttrReadonly();
            $attr.= $this->getAttrDisabled();
            $attr.= $this->getAttrMaxLength();
            $attr.= $this->getAttrMultiple();
            $attr.= $this->getAttrMin();
            $attr.= $this->getAttrMax();
            $attr.= $this->getAttrStep();
            $attr.= $this->getAttrCols();
            $attr.= $this->getAttrRows();
            $attr.= $this->getAttrWidth();
            $attr.= $this->getAttrValue();

            return $attr;
        }

        /**
         * 
         */
        private function tagTemplate()
        {
            switch ($this->getType())
            {
                case 'select':
                case 'choices_select':
                    $template = $this->tagChoicesSelect();
                    break;

                case 'choices_checkbox':
                case 'choices_radio':
                    $template = $this->tagChoicesInput();
                    break;
                
                case 'option':
                    $template = $this->tagOption();
                    break;
                
                case 'textarea':
                    $template = $this->tagTextarea();
                    break;
                
                case 'output':
                    $template = $this->tagOutput();
                    break;

                default:
                    $template = $this->tagInput();
            }

            return preg_replace("/{{attributes}}/", $this->tagAttributes(), $template);;
        }


        /**
         * ----------------------------------------
         * Retrieve Attribute and config options from config.php
         * ----------------------------------------
         */

        /**
         * Config from config.php
         */
        private function setConfig(array $config)
        {
            $this->config = $config;

            $this->setAttrs();

            return $this;
        }
        private function getConfig(string $key = '')
        {
            if (isset( $this->config[$key] )) 
            {
                return $this->config[$key];
            }

            return null;
        }

        /**
         * Attributes from this->getConfig()
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
        private function getAttr(string $key = '')
        {
            if (isset( $this->attrs[$key] )) 
            {
                return $this->attrs[$key];
            }

            return null;
        }


        /**
         * ----------------------------------------
         * Options and Attribute Getters / Setters
         * ----------------------------------------
         */

        /**
         * Class
         */
        protected function setClass()
        {
            // Default class
            $this->class = 'ppm-control';
            
            if (
                is_admin() && 
                $this->tmplOptions['metabox'] &&
                !in_array($this->getType(), ['color'])
            ){
                $this->class.= ' regular-text';
            }

            // Retrive Class parameters
            $class = $this->getAttr('class');

            if (is_string($class))
            {
                $this->class.= ' '.$class;
            }

            return $this;
        }
        protected function getClass()
        {
            return $this->class;
        }
        protected function getAttrClass()
        {
            if (null != $this->getClass())
            {
                return ' class="'.$this->getClass().'"';
            }

            return null;
        }

        /**
         * Choices
         */
        protected function setChoices()
        {
            // Default choices
            $this->choices = [];

            if (!in_array($this->getType(), ['option']))
            {
                if ($this->getConfig('choices'))
                {
                    $this->choices = $this->getConfig('choices');
                }
            }

            return $this;
        }
        protected function getChoices()
        {
            return $this->choices;
        }

        /**
         * Cols
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
        protected function getCols()
        {
            return $this->cols;
        }
        protected function getAttrCols()
        {
            return $this->getCols() ? ' cols="'.$this->getCols().'"' : null;
        }

        /**
         * Disabled
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
        protected function getDisabled()
        {
            return $this->disabled;
        }
        protected function getAttrDisabled()
        {
            return $this->getDisabled() ? ' disabled="disabled"' : null;
        }

        /**
         * Expanded
         */
        protected function setExpanded()
        {
            // Default expanded
            $this->expanded = false;

            // Retrive Readonly parameters
            $expanded = $this->getConfig('expanded');

            if (is_bool($expanded))
            {
                $this->expanded = $expanded;
            }

            return $this;
        }
        protected function getExpanded()
        {
            return $this->expanded;
        }

        /**
         * Set Helper
         */
        protected function setHelper()
        {
            // Default helper
            $this->helper = '';

            if ($this->getConfig('helper'))
            {
                $this->helper = $this->getConfig('helper');
            }

            return $this;
        }
        protected function getHelper()
        {
            return $this->helper;
        }

        /**
         * ID
         */
        protected function setId()
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
        protected function getId()
        {
            return $this->id;
        }
        protected function getAttrId()
        {
            if (!in_array($this->getType(), ['option']) && $this->getId())
            {
                return ' id="'. $this->getId() .'"';
            }

            return null;
        }

        /**
         * Label
         */
        protected function setLabel()
        {
            // Default label
            $this->label = '';

            if ($this->getConfig('label'))
            {
                $this->label = $this->getConfig('label');
            }

            return $this;
        }
        protected function getLabel()
        {
            return $this->label;
        }

        /**
         * Max 
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
        protected function getMax()
        {
            return $this->max;
        }
        protected function getAttrMax()
        {
            return $this->getMax() ? ' max="'.$this->getMax().'"' : null;
        }

        /**
         * Max Length
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
        protected function getMaxLength()
        {
            return $this->maxLength;
        }
        protected function getAttrMaxLength()
        {
            return $this->getMaxLength() ? ' maxlength="'.$this->getMaxLength().'"' : null;
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
        protected function getMin()
        {
            return $this->min;
        }
        protected function getAttrMin()
        {
            return $this->getMin() ? ' min="'.$this->getMin().'"' : null;
        }

        /**
         * Multiple
         */
        protected function setMultiple()
        {
            // Default multiple
            $this->multiple = false;

            // Retrive Readonly parameters
            $multiple = $this->getAttr('multiple');

            if (is_bool($multiple))
            {
                $this->multiple = $multiple;
            }

            return $this;
        }
        protected function getMultiple()
        {
            return $this->multiple;
        }
        protected function getAttrMultiple()
        {
            if (in_array($this->getType(), ['file','select']) && $this->getMultiple()) 
            {
                return ' multiple="multiple"';
            }

            return null;
        }

        /**
         * Name
         */
        protected function setName(string $name = '')
        {
            if (empty($name)) 
            {
                $name = $this->getConfig('post_type');
                $name.= '['.$this->getConfig('key').']';
            }

            $this->name = $name;
            
            return $this;
        }
        protected function getName()
        {
            return $this->name;
        }
        protected function getAttrName()
        {
            return ' name="'.$this->getName().'"';
        }

        /**
         * Placeholder
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
        protected function getPlaceholder()
        {
            return $this->placeholder;
        }
        protected function getAttrPlaceholder()
        {
            if (null != $this->getPlaceholder())
            {
                return ' placeholder="'.$this->getPlaceholder().'"';
            }

            return null;
        }

        /**
         * Readonly
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
        protected function getReadonly()
        {
            return $this->readonly;
        }
        protected function getAttrReadonly()
        {
            return $this->getReadonly() ? ' readonly="readonly"' : null;
        }

        /**
         * Required
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
        protected function getRequired()
        {
            return $this->required;
        }
        protected function getAttrRequired()
        {
            return $this->getRequired() ? ' required="required"' : null;
        }

        /**
         * Rows
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
        protected function getRows()
        {
            return $this->rows;
        }
        protected function getAttrRows()
        {
            return $this->getRows() ? ' rows="'.$this->getRows().'"' : null;
        }

        /**
         * Step
         */
        protected function setStep()
        {
            // Default Step
            $this->step = null;

            // Retrive Step parameters
            $step = $this->getAttr('step');

            if (is_int($step) || is_float($step))
            {
                $this->step = $step;
            }

            return $this;
        }
        protected function getStep()
        {
            return $this->step;
        }
        protected function getAttrStep()
        {
            return $this->getStep() ? ' step="'.$this->getStep().'"' : null;
        }

        /**
         * Type
         */
        protected function setType($type = null)
        {
            if (null === $type)
            {
                $called_class = get_called_class();
                $called_class = str_replace("\\", "/", $called_class);
                $called_class = basename($called_class);
                $called_class = strtolower($called_class);

                $type = $called_class;
            }

            if ($type == 'choices')
            {
                if ($this->getExpanded() && $this->getMultiple()) {
                    $type = "choices_checkbox";
                }
                elseif ($this->getExpanded() && !$this->getMultiple()) {
                    $type = "choices_radio";
                }
                else {
                    $type = "choices_select";
                }
            }

            $this->type = $type;

            return $this;
        }
        protected function getType()
        {
            return $this->type;
        }
        protected function getAttrType()
        {
            if (!in_array($this->getType(), ['option','select','textarea']))
            {            
                return ' type="'. $this->getType() .'"';
            }
        }

        /**
         * Value
         */
        protected function setValue()
        {
            // Default Value
            $this->value = null;

            // Retrive Default parameters
            if ($this->getConfig('default'))
            {
                $this->value = $this->getConfig('default');
            }

            // Override default value
            if ($this->getConfig('value'))
            {
                $this->value = $this->getConfig('value');
            }

            switch ($this->getType()) 
            {
                case 'date':
                    if ('today' == $this->value) {
                        $this->value = date('Y-m-d');
                    }
                    break;

                case 'time':
                    if ('now' == $this->value) {
                        $this->value = date('H:i');
                    }
                    break;
            }
            return $this;
        }
        protected function getValue()
        {
            return $this->value;
        }
        protected function getAttrValue()
        {
            return $this->getValue() ? ' value="'.$this->getValue().'"' : null;
        }

        /**
         * Width
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
        protected function getWidth()
        {
            return $this->width;
        }
        protected function getAttrWidth()
        {
            return $this->getWidth() ? ' width="'.$this->getWidth().'"' : null;
        }
    }
}
