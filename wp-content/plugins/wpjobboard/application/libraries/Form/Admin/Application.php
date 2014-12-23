<?php
/**
 * Description of Application
 *
 * @author greg
 * @package
 */

class Wpjb_Form_Admin_Application extends Wpjb_Form_Abstract_Application
{

    public function _exclude()
    {
        if($this->_object->getId()) {
            return array("id" => $this->_object->getId());
        } else {
            return array();
        }
    }

    public function init()
    {
        parent::init();

        $new = !$this->getId();
        
        if($new) {
            $this->getObject()->applied_at = date("Y-m-d");
        }
        
        $this->addGroup("_internal", "");
        
        $query = new Daq_Db_Query();
        $query->select("id, job_title");
        $query->from("Wpjb_Model_Job t");
        $result = $query->fetchAll();
        $e = $this->create("job_id", "select");
        $e->addOption("0", "0", __("- None -", "wpjobboard"));
        $e->setLabel(__("Job", "wpjobboard"));
        if($new) {
            $e->addOption("", "", __("-- Select Job --", "wpjobboard"));
        }
        foreach($result as $o) {
            $e->addOption($o->id, $o->id, "{$o->job_title} (ID: {$o->id})");
        }
        $e->setValue($this->_object->job_id);
        $this->addElement($e, "_internal");
        
        $e = $this->create("status", "select");
        $e->setValue($this->_object->status);
        $e->setLabel(__("Status", "wpjobboard"));
        $e->addFilter(new Daq_Filter_Int());
        $e->addOption("1", "1", __("New", "wpjobboard"));
        $e->addOption("3", "3", __("Read", "wpjobboard"));        
        $e->addOption("2", "2", __("Accepted", "wpjobboard"));
        $e->addOption("0", "0", __("Rejected", "wpjobboard"));
        $this->addElement($e, "_internal");

        $e = $this->create("user_id", "select");
        $e->setValue($this->_object->user_id);
        $e->setLabel(__("Status", "wpjobboard"));
        $e->addFilter(new Daq_Filter_Int());
        $e->addOption("0", "0", __("- None -", "wpjobboard"));
        foreach(get_users() as $user) {
            $e->addOption($user->ID, $user->ID, $user->display_name);
        }
        $this->addElement($e, "_internal");
        
        add_filter("wpja_form_init_application", array($this, "apply"), 9);
        apply_filters("wpja_form_init_application", $this);
    }
    
    public function save()
    {
        if(!$this->getObject()->getId()) {
            $e = $this->create("applied_at");
            $e->setValue(date("Y-m-d"));
            $this->addElement($e, "application");
        }
        
        parent::save();
        
        apply_filters("wpja_form_save_application", $this);
    }
}

?>