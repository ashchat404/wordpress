<?php

/**
 * Description of Login
 *
 * @author greg
 * @package
 */

class Wpjb_Form_Frontend_Register extends Wpjb_Form_Abstract_Company
{
    public function init()
    {
        parent::init();
        add_filter("wpjb_form_init_company", array($this, "apply"), 9);
        apply_filters("wpjb_form_init_company", $this);
    }

    public function isValid($values)
    {
        $isValid = parent::isValid($values);
        
        if($this->hasElement("company_info")) {
            $e = $this->create("company_info_format", "hidden");
            $e->setValue($this->getElement("company_info")->usesEditor() ? "html" : "text");
            $e->setBuiltin(false);
            $this->addElement($e, "_internal");
        }
        
        
        return $isValid;
    }
    
    public function save()
    {
        parent::save();

        $temp = wpjb_upload_dir("company", "", null, "basedir");
        $finl = dirname($temp)."/".$this->getId();
        wpjb_rename_dir($temp, $finl);
        
        apply_filters("wpjb_form_save_company", $this);
        
    }
}

?>