<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Membership
 *
 * @author Grzegorz
 */
class Wpjb_Form_Admin_Membership extends Daq_Form_ObjectAbstract
{
    protected $_model = "Wpjb_Model_Membership";
    
    protected $_membership = null;
    
    public function getMembership()
    {
        return $this->_membership;
    }
    
    public function init() 
    {
        $this->addGroup("default");
        
        $e = $this->create("user_id", "select");
        $e->setValue($this->getObject()->user_id);
        $e->setLabel(__("User", "wpjobboard"));
        foreach(get_users() as $user) {
            $e->addOption($user->ID, $user->ID, $user->display_name);
        }
        $this->addElement($e, "default");
        
        $pricing = new Daq_Db_Query();
        $pricing->from("Wpjb_Model_Pricing t");
        $pricing->where("price_for = ?", Wpjb_Model_Pricing::PRICE_EMPLOYER_MEMBERSHIP);
        
        $e = $this->create("package_id", "select");
        $e->setValue($this->getObject()->package_id);
        $e->setLabel(__("Package", "wpjobboard"));
        foreach ($pricing->execute() as $p) {
            $e->addOption($p->id, $p->id, $p->title);
        }
        $this->addElement($e, "default");
        
        $e = $this->create("started_at", "text_date");
        $e->setDateFormat(wpjb_date_format());
        $e->setValue($this->ifNew(date("Y-m-d"), $this->getObject()->started_at));
        $e->setLabel(__("Started At", "wpjobboard"));
        $this->addElement($e, "default");
        
        $e = $this->create("expires_at", "text_date");
        $e->setDateFormat(wpjb_date_format());
        $e->setValue($this->ifNew(date("Y-m-d"), $this->getObject()->expires_at));
        $e->setLabel(__("Expires At", "wpjobboard"));
        $this->addElement($e, "default");
        
        $mlist = unserialize($this->getObject()->package);
        
        $price = array(
            array(
                "title" => __("Job Posting", "wpjobboard"),
                "price_for" => Wpjb_Model_Pricing::PRICE_SINGLE_JOB,
                "hint" => __("Select which Job Postings will be included in this package and how many times Employer will be able to use them.", "wpjobboard"),
                
            ),
            array(
                "title" => __("Resumes Access", "wpjobboard"),
                "price_for" => Wpjb_Model_Pricing::PRICE_SINGLE_RESUME,
                "hint" => "",
            ),
        );
        
        $order = 105;
        
        foreach($price as $p) {
            
            $pfor = $p["price_for"];
            
            $query = new Daq_Db_Query();
            $query->from("Wpjb_Model_Pricing t");
            $query->where("price_for = ?", $pfor);
            
            
            if(isset($mlist[$p["price_for"]])) {
                $mdata = $mlist[$p["price_for"]];
            } else {
                $mdata = null;
            }
            
            $e = $this->create("items_".$pfor, "checkbox");
            $e->setLabel($p["title"]);
            $e->setHint($p["hint"]);
            foreach($query->execute() as $item) {
                $e->addOption($item->id, $item->id, $item->title);
            }
            $e->setRenderer("wpjb_admin_membership_render");
            $e->setOrder($order++);
            $e->setMaxChoices(100);
            $e->setValue($mdata);
            $this->addElement($e);
            
            $e = $this->create("items_".$pfor."_usage", "checkbox");
            $this->addElement($e, "_internal");
        }
        
    }
    
    public function save()
    {
        parent::save();
        
        $object = $this->getObject();
        
        $data = array(
            Wpjb_Model_Pricing::PRICE_SINGLE_JOB => array(),
            Wpjb_Model_Pricing::PRICE_SINGLE_RESUME => array(),
        );
        
        foreach(array_keys($data) as $key) {
            $post = $this->value("items_".$key);
            foreach((array)$post as $id => $usage) {
                if($usage["status"] != "disabled") {
                    $data[$key][$id] = $usage;
                }
            }
        }
        
        $object->package = serialize($data);
        $object->save();
    }
}

?>
