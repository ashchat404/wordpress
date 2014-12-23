<?php
/**
 * Description of Index
 *
 * @author greg
 * @package 
 */

class Wpjb_Module_Admin_Job extends Wpjb_Controller_Admin
{
    public function init()
    {
        $this->view->slot("logo", "job_board.png");
        
        
       $this->_virtual = array(
           "redirectAction" => array(
               "accept" => array("filter", "posted", "query"),
               "object" => "job"
           ),
           "addAction" => array(
                "form" => "Wpjb_Form_Admin_AddJob",
                "info" => __("New job has been created.", "wpjobboard"),
                "error" => __("There are errors in your form.", "wpjobboard"),
                "url" => wpjb_admin_url("job", "edit", "%d")
            ),
            "editAction" => array(
                "form" => "Wpjb_Form_Admin_AddJob",
                "info" => __("Form saved.", "wpjobboard"),
                "error" => __("There are errors in your form.", "wpjobboard")
            ),
            "_delete" => array(
                "model" => "Wpjb_Model_Job",
                "info" => __("Job deleted.", "wpjobboard"),
                "error" => __("Job could not be deleted.", "wpjobboard")
            ),
            "_multi" => array(
                "delete" => array(
                    "success" => __("Number of deleted jobs: {success}", "wpjobboard")
                ),
                "activate" => array(
                    "success" => __("Number of activated jobs: {success}", "wpjobboard")
                ),
                "deactivate" => array(
                    "success" => __("Number of deactivated jobs: {success}", "wpjobboard")
                ),
                "approve" => array(
                    "success" => __("Number of approved jobs: {success}", "wpjobboard")
                ),
                "read" => array(
                    "success" => __("Number of jobs marked as read: {success}", "wpjobboard")
                ),
                "unread" => array(
                    "success" => __("Number of jobs marked as unread: {success}", "wpjobboard")
                )
            ),
            "_multiDelete" => array(
                "model" => "Wpjb_Model_Job"
            )
        );
    }

    public function addAction($param = array())
    {
        $query = new Daq_Db_Query();
        $query->select();
        $query->from("Wpjb_Model_Pricing t");
        $query->where("price_for = ?", Wpjb_Model_Pricing::PRICE_SINGLE_JOB);
        $query->where("is_active = 1");
        $list = $query->execute();
        $pricing = array(array("id"=>0, "title"=>__("Custom", "wpjobboard")));
        foreach($list as $p) {
            $pricing[] = array(
                "id" => $p->id,
                "title" => $p->title,
                "amount" => $p->amount,
                "currency" => $p->currency,
                "visible" => $p->meta->visible->getFirst()->value,
                "is_featured" => $p->meta->is_featured
            );
        }
        $this->view->pricing = $pricing;
        
        parent::addAction();
    }
    
    public function editAction()
    {
        $query = new Daq_Db_Query();
        $query->select();
        $query->from("Wpjb_Model_Pricing t");
        $query->where("price_for = ?", Wpjb_Model_Pricing::PRICE_SINGLE_JOB);
        $query->where("is_active = 1");
        $list = $query->execute();
        $pricing = array(array("id"=>0, "title"=>__("Custom", "wpjobboard")));
        foreach($list as $p) {
            $pricing[] = array(
                "id" => $p->id,
                "title" => $p->title,
                "amount" => $p->amount,
                "currency" => $p->currency,
                "visible" => $p->meta->visible->getFirst()->value,
                "is_featured" => $p->meta->is_featured
            );
        }
        $this->view->pricing = $pricing;
        
        extract($this->_virtual[__FUNCTION__]);
        
        $job = new Wpjb_Model_Job($this->_request->getParam("id"));
        $job->read = 1;
        $job->save();
        
        $approved = (int)$job->is_approved;

        $form = new $form($this->_request->getParam("id"));
        if($this->isPost()) {
            $isValid = $form->isValid($this->_request->getAll());
            if($isValid) {
                $this->_addInfo($info);
                $form->save();
                if(!$approved && $form->getObject()->is_approved) {
                    $this->_approve($form->getObject());
                }
                
            } else {
                $this->_addError($error);
            }
        }

        $this->view->form = $form;
    }

    public function markaspaidAction()
    {
        $id = $this->_request->getParam("id");
        
        $job = new Wpjb_Model_Job($id);
        
        if(!$job->exists()) {
            $this->view->_flash->addError(__("Job does not exist.", "wpjobboard"));

            wp_redirect(wpjb_admin_url("job", "edit", $job->id));
            exit;
        }
        
        $payment = $job->getPayment(true);
        $payment->payment_paid = $payment->payment_sum;
        $payment->paid_at = date("Y-m-d H:i:s");
        $payment->is_valid = 1;
        $payment->save();
        
        $this->view->_flash->addInfo(__("Job has been marked as paid.", "wpjobboard"));
        
        wp_redirect(wpjb_admin_url("job", "edit", $job->id));
        exit;
    }
    
    public function deleteAction() 
    {
        $id = $this->_request->getParam("id");
        $job = new Wpjb_Model_Job($id);
        $job_title = $job->job_title;

        if($this->_multiDelete($id)) {
            $m = sprintf(__("Job '%s' deleted.", "wpjobboard"), $job_title);
            $this->view->_flash->addInfo($m);
        }
        
        wp_redirect(wpjb_admin_url("job"));
        exit;
    }

    /**
     * Job list action
     * 
     * Allows to search jobs by:
     * - status
     * - title/description
     * - category
     * - job type
     * - sort and order 
     * - page
     */
    public function indexAction()
    {
        global $wpdb;

        $q = $this->_request->get("query");
        
        if($this->_request->get("employer")) {
            $q .= " employer_id:".$this->_request->get("employer");
        }
        
        $param = array(
            "filter" => "all",
            "location" => "",
            "posted" => "",
            "sort" => "",
            "order" => ""
        );

        $this->view->rquery = $this->readableQuery($q);
        
        $query = array_merge($param, $this->deriveParams($q, new Wpjb_Model_Job));
        
        if($this->_request->get("filter")) {
            $query["filter"] = $this->_request->get("filter");
        }
        if($this->_request->get("sort")) {
            $sort = $wpdb->escape($this->_request->get("sort"));
            $param["sort"] = $sort;
            $query["sort_order"] = "t1.".$sort;
        } else {
            $query["sort_order"] = "t1.job_created_at DESC, t1.id DESC";
        }

        if($this->_request->get("order")) {
            $order = $wpdb->escape($this->_request->get("order"));
            $param["order"] = $order;
            $query["sort_order"] .= " ".$order;
        }

        if($this->_request->get("posted")) {
            $p = $this->_request->get("posted");
            $query["date_from"] = date("Y-m-01", strtotime($p));
            $query["date_to"] = date("Y-m-t", strtotime($query["date_from"]));
            $this->view->posted = $p;
        }

        if($this->_request->get("p")) {
            $query["page"] = $this->_request->get("p");
        } else {
            $query["page"] = 1;
        }

        $name = new Wpjb_Model_Job();
        $name = $name->tableName();
        /* @var $wpdb wpdb */
        $result = $wpdb->get_results("
            SELECT DATE_FORMAT(job_created_at, '%Y-%m') as dt
            FROM $name GROUP BY dt ORDER BY job_created_at DESC
        ");

        $months = array();
        foreach($result as $r) {
            $months[$r->dt] = date("Y, F", strtotime($r->dt));
        }

        $this->view->months = $months;

        foreach($param as $k => $v) {
            $param[$k] = $this->_request->get($k, $v);
        }
        
        $query["count"] = $this->_getPerPage();
        $query["hide_filled"] = false;
        
        $param["query"] = $q;

        $this->view->result = Wpjb_Model_JobSearch::search($query);

        $this->view->param = $param;
        $this->view->filter = $param["filter"];
        $this->view->query = $q;
        $this->view->sort = $param["sort"];
        $this->view->order = $param["order"];
        $this->view->posted = $param["posted"];
        
        $stat = new stdClass();
        foreach(array("all", "active", "unread", "expired", "expiring", "awaiting", "inactive") as $f) {
            $stat->$f = Wpjb_Model_JobSearch::search(array_merge($query, array(
                "filter"=>$f,
                "count_only"=>1
            )));
        }
        $this->view->stat = $stat;

    }

    public function introAction()
    {
        
    }

    protected function _multiActivate($id)
    {
        $object = new Wpjb_Model_Job($id);
        $approved = (int)$object->is_approved;
        
        $object->is_approved = 1;
        $object->is_active = 1;
        $object->save();
        
        if(!$approved) {
            do_action("wpjb_job_published", $object);
            $this->_approve($object);
        }
        
        return true;
    }

    protected function _multiDeactivate($id)
    {
        $object = new Wpjb_Model_Job($id);
        $object->is_active = 0;
        $object->save();
        return true;
    }
    
    protected function _multiRead($id)
    {
        $object = new Wpjb_Model_Job($id);
        $object->read = 1;
        $object->save();
        return true;
    }

    protected function _multiUnread($id)
    {
        $object = new Wpjb_Model_Job($id);
        $object->read = 0;
        $object->save();
        return true;
    }
    
    protected function _approve(Wpjb_Model_Job $job)
    {
        $message = Wpjb_Utility_Message::load("notify_employer_job_paid");
        $message->assign("job", $job);
        $message->setTo($job->company_email);
        $message->send(); 
    }
}

?>