<?php
/**
 * Description of Job
 *
 * @author greg
 * @package 
 */

class Wpjb_Form_Admin_AddJob extends Wpjb_Form_Abstract_Job
{
    protected $_model = "Wpjb_Model_Job";
   
    protected $_custom = "wpjb_form_job";
    
    protected $_key = "job";
    
    protected $_approved = null;
    
    public $dateFormat = "Y/m/d";
    
   

    public function _exclude()
    {
        if($this->_object->getId()) {
            return array("id" => $this->_object->getId());
        } else {
            return array();
        }
    }
    
    protected function _pricing()
    {
        $pricing = wpjb_form_get_listings();
        array_unshift($pricing, array("key"=>"0", "value"=>"0", "description"=>"Custom"));
        return $pricing;
    }
    
    protected function _employers()
    {
        $query = new Daq_Db_Query();
        $query->select();
        $query->from("Wpjb_Model_Company t");
        $query->order("company_name ASC");
        
        return $query->execute();
    }

    public function init()
    {
        parent::init();
        
        $this->_approved = (bool)$this->getObject()->is_approved;
        $this->addGroup("_internal", "");

        $e = $this->create("job_slug", "hidden");
        $e->setRequired(true);
        $e->setValue($this->_object->job_slug);
        $e->addValidator(new Daq_Validate_StringLength(1, 120));
        $e->addValidator(new Daq_Validate_Slug());
        $e->addValidator(new Daq_Validate_Db_NoRecordExists("Wpjb_Model_Job", "job_slug", $this->_exclude()));
        $this->addElement($e, "_internal");
        
        $e = $this->create("is_active", "checkbox");
        $e->setValue($this->ifNew(1, $this->_object->is_active));
        $e->addFilter(new Daq_Filter_Int());
        $e->addOption(1, 1, __("Listing is approved.", "wpjobboard"));
        $this->addElement($e, "_internal");

        $e = $this->create("is_featured", "checkbox");
        $e->setValue($this->getObject()->is_featured);
        $e->addOption(1, 1, __("Display job as featured.", "wpjobboard"));
        $e->addFilter(new Daq_Filter_Int());
        $this->addElement($e, "_internal");

        $e = $this->create("is_filled", "checkbox");
        $e->setValue($this->getObject()->is_filled);
        $e->addOption(1, 1, __("This position is already taken.", "wpjobboard"));
        $e->addFilter(new Daq_Filter_Int());
        $this->addElement($e, "_internal");
        
        $e = $this->create("listing_type", "select");
        $e->setLabel(__("Listing Type","wpjobboard"));
        $e->addOptions($this->_pricing());
        $this->addElement($e, "_internal");
            
        $e = $this->create("job_created_at", "text_date");
        $e->setDateFormat(wpjb_date_format());
        $e->setValue($this->ifNew(date("Y-m-d"), $this->_object->job_created_at));
        $e->addValidator(new Daq_Validate_Date());
        $e->addFilter(new Daq_Filter_Date(wpjb_date_format()));
        $this->addElement($e, "_internal");
        
        $t = wpjb_time("today +30 day");
        $e = $this->create("job_expires_at", "text_date");
        $e->setDateFormat(wpjb_date_format());
        $e->setValue($this->ifNew(date("Y-m-d", $t), $this->_object->job_expires_at));
        $this->addElement($e, "_internal");
        
        $e = $this->create("employer_id", "select");
        $e->addOption("0", "0", __("Anonymous", "wpjobboard"));
        foreach($this->_employers() as $emp) {
            $e->addOption($emp->id, $emp->id, $emp->company_name." (ID: ".$emp->id.")");
        }
        $e->setValue($this->getObject()->employer_id);
        $this->addElement($e, "_internal");
        
        $pay = $this->getPayment();
        if($this->getObject()->id && $pay->id) {
            $pm = $pay->engine;
        } elseif(!$this->getObject()->id) {
            $pm = wpjb_default_payment_method();
        } else {
            $pm = "none";
        }
        
        $e = $this->create("payment_method", "select");
        $e->addOption("none", "none", "None");
        foreach(Wpjb_Project::getInstance()->payment->getEnabled() as $engine) {
            $engine = new $engine;
            $e->addOption($engine->getEngine(), $engine->getEngine(), $engine->getCustomTitle());
        }
        $e->setValue($pm);
        $this->addElement($e, "_internal");
        
        $e = $this->create("payment_sum");
        $e->setValue($pay->id ? $pay->payment_sum : "10.00");
        $e->setAttr("size", "6");
        $this->addElement($e, "_internal");
        
        $e = $this->create("payment_currency", "select");
        foreach(Wpjb_List_Currency::getAll() as $c) {
            $e->addOption($c["code"], $c["code"], $c["code"]." ".$c["symbol"]);
        }
        $e->setValue($pay->id ? $pay->payment_currency : wpjb_default_currency());
        $this->addElement($e, "_internal");
        
        if($this->hasElement("job_title")) {
            $this->getElement("job_title")->setRenderer("wpjb_title_slug");
        }

        add_filter("wpja_form_init_job", array($this, "apply"), 9);
        apply_filters("wpja_form_init_job", $this);
    }

    public function isValid($values)
    {
        $isValid = parent::isValid($values);
        
        if($this->hasElement("job_description")) {
            $e = $this->create("job_description_format", "hidden");
            $e->setValue($this->getElement("job_description")->usesEditor() ? "html" : "text");
            $e->setBuiltin(false);
            $this->addElement($e, "_internal");
        }
        
        
        return $isValid;
    }
    
    public function getPayment()
    {
        $objectType = 1;
        $objectId = $this->getId();
        
        if($objectId < 1) {
            return new Wpjb_Model_Payment;
        } 
        
        $query = Daq_Db_Query::create();
        $query->from("Wpjb_Model_Payment t");
        $query->where("object_type=?", $objectType);
        $query->where("object_id=?", $objectId);
        $query->limit(1);
        $result = $query->execute();
        
        if(isset($result[0])) {
            return $result[0];
        } else {
            return new Wpjb_Model_Payment();
        }
    }
    
    protected function _savePayment()
    {
        if(!$this->hasElement("payment_method")) {
            return;
        }
        
        $objectType = 1;
        $objectId = $this->getId();
        
        $query = Daq_Db_Query::create();
        $query->from("Wpjb_Model_Payment t");
        $query->where("object_type=?", $objectType);
        $query->where("object_id=?", $objectId);
        $query->limit(1);
        $result = $query->execute();
        
        if(isset($result[0])) {
            if($this->value("payment_method") == "none") {
                $result[0]->delete();
                return;
            }
            $payment = $result[0];
        } elseif($this->value("payment_method") == "none") {
            // do nothing
            return;
        } else {
            $payment = new Wpjb_Model_Payment;
            $payment->object_type = $objectType;
            $payment->object_id = $objectId;
            $payment->email = $this->getObject()->company_email;
            $payment->user_id = null;
            $payment->external_id = "";
            $payment->is_valid = 0;
            $payment->message = "";
            $payment->created_at = date("Y-m-d H:i:s");
            $payment->paid_at = "0000-00-00 00-00-00";
            $payment->payment_paid = 0;
        }
        
        $payment->engine = $this->value("payment_method");
        $payment->payment_sum = $this->value("payment_sum");
        $payment->payment_currency = $this->value("payment_currency");
        
        if($this->value("payment_paid")) {
            $payment->payment_paid = $this->value("payment_paid");
        }
        
        $payment->save();
    }
    
    public function save($append = array())
    {
        $isNew = $this->isNew();
        $id = parent::save($append);
        
        if($isNew) {
            $temp = wpjb_upload_dir("job", "", null, "basedir");
            $finl = dirname($temp)."/".$this->getId();
            wpjb_rename_dir($temp, $finl);
        }
        
        $this->getElement("id")->setValue($this->getId());
        $this->_savePayment();
        
        $this->getObject()->tag(true);
        $this->getObject()->meta(true);
        
        if(!$this->_approved && $this->getObject()->is_approved) {
            do_action("wpjb_job_published", $this->getObject());
        }
        
        apply_filters("wpja_form_save_job", $this);
        
        return $id;

    }
}

?>