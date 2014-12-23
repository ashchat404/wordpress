<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Textarea
 *
 * @author greg
 */
class Daq_Form_Element_Textarea extends Daq_Form_Element
{
    const EDITOR_NONE = 0;
    const EDITOR_TINY = 1;
    const EDITOR_FULL = 2;
    
    protected $_wysiwyg = false;
    
    public function usesEditor()
    {
        if($this->_wysiwyg > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public final function getType()
    {
        return "textarea";
    }
    
    public function dump()
    {
        $dump = parent::dump();
        $dump->textarea_wysiwyg = $this->getEditor();
        
        return $dump;
    }
    
    public function getEditor()
    {
        return $this->_wysiwyg;
    }
    
    public function setEditor($editor)
    {
        $this->_wysiwyg = $editor;
    }
    
    public function overload(array $data) 
    {
        parent::overload($data);
        $this->setEditor($data["textarea_wysiwyg"]);

    }
    
    public function render() 
    {
        $options = array(
            "id" => $this->getName(),
            "name" => $this->getName(),
            "class" => $this->getClasses()
        );
        
        $options += $this->getAttr();
        
        $input = new Daq_Helper_Html("textarea", $options, $this->getValue());
        $input->forceLongClosing();
        
        if($this->getEditor() > 0 && function_exists("wp_editor")) {
            add_filter('the_editor_content', 'wp_richedit_pre');
            
            if($this->getEditor() == self::EDITOR_FULL) {
                $params = array();
            } else {
                $params = array(
                    "quicktags"=>false, 
                    "media_buttons"=>false, 
                    "teeny"=>false,
                    'tinymce' => array(
                        'toolbar1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,spellchecker,wp_adv',
                        'theme_advanced_buttons2' => 'formatselect,justifyfull,forecolor,pastetext,pasteword,removeformat,charmap,outdent,indent,undo,redo',
                        
                        'theme_advanced_buttons1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,spellchecker,wp_adv',
                        'theme_advanced_buttons2' => 'formatselect,justifyfull,forecolor,pastetext,pasteword,removeformat,charmap,outdent,indent,undo,redo',
                     )
                );
            }
            
            ob_start();
            wp_editor($this->getValue(), $this->getName(), $params);
            return ob_get_clean();
        } else {
            return $input->render();
        }
    }
    
    public function validate()
    {
        $this->_hasErrors = false;
        
        $value = $this->getValue();
        $value = trim($value);
        $this->setValue($value);
        
        if($this->usesEditor()) {
            $value = strip_tags($value);
        }
        
        $value = trim($value);
        
        if(empty($value) && !$this->isRequired()) {
            return true;
        } else {
            $this->addValidator(new Daq_Validate_Required());
        }
        
        $value = trim($this->getValue());
        
        foreach($this->getFilters() as $filter) {
            $value = $filter->filter($value);
        }
        
        $this->setValue($value);
        
        foreach($this->getValidators() as $validator) {
            if(!$validator->isValid($value)) {
                $this->_hasErrors = true;
                $this->_errors = $validator->getErrors();
            }
        }

        return !$this->_hasErrors;
    }
    
    public function htmlEdit()
    {
        return "tinymce";
    }
}

?>
