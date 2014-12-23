<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Action
 *
 * @author Grzegorz
 */
class Wpjb_Module_Api_Action extends Daq_Controller_Abstract
{
    public function getRequest()
    {
        return Daq_Request::getInstance();
    }
    
    public function reply($reply)
    {
        $redirect_to = $this->getRequest()->getParam("redirect_to");
        
        if($this->isXmlHttpRequest()) {
            
            echo json_encode($reply);
            
        } else {
            
            if($reply->status != 200) {
                $this->view->_flash->addError($reply->message);
            } else {
                $this->view->_flash->addInfo($reply->message);
            }
            
            wp_redirect($redirect_to);
            exit;
        }
    }
    
    public function bookmarkAction()
    {
        $object = $this->getRequest()->getParam("object");
        $object_id = $this->getRequest()->getParam("object_id");
        $user_id = get_current_user_id();
        $do = $this->getRequest()->getParam("do");
        
        $reply = new stdClass();
        $reply->status = 0;
        $reply->message = "";
        
        $allowed = array();
        
        if(current_user_can("manage_resumes")) {
            $allowed[] = "job";
            $allowed[] = "company";
        } 
        
        if(current_user_can("manage_jobs")) {
            $allowed[] = "resume";
        }
        
        if(!in_array($do, array("post", "delete"))) {
            $reply->message = __("Invalid action", "wpjobboard");
            $this->reply($reply);
            return;
        }
        
        if(!$user_id) {
            $reply->message = __("Only registered members can create bookmarks.", "wpjobboard");
            $this->reply($reply);
            return;
        }
        
        if(!in_array($object, $allowed)) {
            $reply->message = __("Invalid object type", "wpjobboard");
            $this->reply($reply);
            return;
        }
        
        switch($object) {
            case "job": $item = new Wpjb_Model_Job($object_id); break;
            case "company": $item = new Wpjb_Model_Company($object_id); break;
            case "resume": $item = new Wpjb_Model_Resume($object_id); break;
        }
        
        if(!$item->exists() && $do=="post") {
            $reply->message = __("Object with given ID does not exist.", "wpjobboard");
            $this->reply($reply);
            return;
        }
        
        $query = new Daq_Db_Query;
        $query->from("Wpjb_Model_Shortlist t");
        $query->where("object = ?", $object);
        $query->where("object_id = ?", $object_id);
        $query->where("user_id = ?", $user_id);
        $query->limit(1);
        
        $list = $query->execute();
        
        if($do=="delete") {
            if(isset($list[0])) {
                $list[0]->delete();
                $reply->status = 200;
                $reply->message = __("Bookmark deleted.", "wpjobboard");
            } else {
                $reply->message = __("Bookmark does not exist.", "wpjobboard");
            }
        }
        
        if($do=="post") {
            if(!isset($list[0])) {
                
                $sh = new Wpjb_Model_Shortlist();
                $sh->object_id = $object_id;
                $sh->object = $object;
                $sh->user_id = $user_id;
                $sh->shortlisted_at = date("Y-m-d");
                $sh->save();
                
                $reply->status = 200;
                $reply->message = __("Bookmark created.", "wpjobboard");
            } else {
                $reply->message = __("Bookmark already exists.", "wpjobboard");
            }
        }

        $this->reply($reply);
    }
}

?>
