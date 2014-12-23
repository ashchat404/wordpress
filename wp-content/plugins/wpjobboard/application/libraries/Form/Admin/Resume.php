<?php
/**
 * Description of Resume
 *
 * @author greg
 * @package 
 */

class Wpjb_Form_Admin_Resume extends Wpjb_Form_Abstract_Resume
{
    public function init() 
    {
        parent::init();
        
        add_filter("wpja_form_init_resume", array($this, "apply"), 9);
        apply_filters("wpja_form_init_resume", $this);
    }
    
    public function save()
    {
        parent::save();
        
        apply_filters("wpja_form_save_resume", $this);
    }
    
}

?>