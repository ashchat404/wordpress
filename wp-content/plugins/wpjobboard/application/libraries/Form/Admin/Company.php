<?php
/**
 * Description of Company
 *
 * @author greg
 * @package 
 */

class Wpjb_Form_Admin_Company extends Wpjb_Form_Abstract_Company
{
    public function init()
    {
        parent::init();
        
        $e = $this->create("is_active", "checkbox");
        $e->setLabel(__("Activity", "wpjobboard"));
        $e->addOption(1, 1, __("Company account is active", "wpjobboard"));
        $e->setValue($this->_object->id ? $this->_object->is_active : 1);
        $e->addFilter(new Daq_Filter_Int());
        $this->addElement($e, "_internal");

        $opt = array(
            Wpjb_Model_Company::ACCESS_UNSET => "-",
            Wpjb_Model_Company::ACCESS_DECLINED => __("Declined", "wpjobboard"),
            Wpjb_Model_Company::ACCESS_GRANTED => __("Verified", "wpjobboard"),
            Wpjb_Model_Company::ACCESS_PENDING => __("Pending Approval", "wpjobboard"),
        );
        
        $e = $this->create("is_verified", "select");
        $e->setLabel(__("Verification", "wpjobboard"));
        foreach($opt as $k => $v) {
            $e->addOption((string)$k, (string)$k, $v);
        }
        $e->setValue($this->_object->is_verified);
        $e->addFilter(new Daq_Filter_Int());
        $this->addElement($e, "_internal");
        
        add_filter("wpja_form_init_company", array($this, "apply"), 9);
        apply_filters("wpja_form_init_company", $this);
    }
    
    public function save() 
    {
        parent::save();
        
        apply_filters("wpja_form_save_company", $this);
    }
    


}

?>