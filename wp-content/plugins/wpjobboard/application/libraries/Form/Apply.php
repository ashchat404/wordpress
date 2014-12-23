<?php
/**
 * Description of Apply
 *
 * @author greg
 * @package 
 */

class Wpjb_Form_Apply extends Wpjb_Form_Abstract_Application
{   
    private $_userId = null;

    private $_jobId = null;

    public function getUserId()
    {
        return $this->_userId;
    }

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function getJobId()
    {
        return $this->_jobId;
    }

    public function setJobId($jobId)
    {
        $this->_jobId = $jobId;
    }


    public function init()
    {
        parent::init();

	$this->addGroup("_internal", "");
	
        $e = $this->create("_wpjb_action", "hidden");
        $e->setRequired(true);
        $e->setValue("apply");
        $this->addElement($e, "_internal");
        
        $e = $this->create("protection", "hidden");
        $e->addValidator(new Daq_Validate_InArray(array(Wpjb_Project::getInstance()->conf("front_protection", "pr0t3ct1on"))));
        $e->setRequired(true);
        $this->addElement($e, "_internal");

        add_filter("wpjb_form_init_apply", array($this, "apply"), 9);
        apply_filters("wpjb_form_init_apply", $this);

    }

    protected function _quickAdd($name, $value)
    {
        $e = $this->create($name, "hidden");
        $e->setValue($value);
        $this->addElement($e, "apply");
    }

    public function save()
    {
        
        $this->_quickAdd("applied_at", date("Y-m-d H:i:s"));
        $this->_quickAdd("job_id", $this->getJobId());
        $this->_quickAdd("user_id", $this->getUserId());
        $this->_quickAdd("status", 1);

        $id = parent::save();
        
        $this->upload(wpjb_upload_dir("{object}", "{field}", "{id}", "basedir"));
        
        $temp = wpjb_upload_dir("application", "", null, "basedir");
        $finl = dirname($temp)."/".$this->getId();
        wpjb_rename_dir($temp, $finl);

        apply_filters("wpjb_form_save_apply", $this);
        
        return $id;

    }
    

}

?>