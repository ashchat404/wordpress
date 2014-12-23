<?php
/**
 * Description of Category
 *
 * @author greg
 * @package
 */

class Wpjb_Module_Admin_Application extends Wpjb_Controller_Admin
{
    public function init()
    {
        $this->view->slot("logo", "user_app.png");
        $this->_virtual = array(
           "redirectAction" => array(
               "accept" => array("query", "posted", "job", "filter"),
               "object" => "application"
           ),
           "addAction" => array(
                "form" => "Wpjb_Form_Admin_Application",
                "info" => __("New application has been created.", "wpjobboard"),
                "error" => __("There are errors in your form.", "wpjobboard"),
                "url" => wpjb_admin_url("application", "edit", "%d")
            ),
            "editAction" => array(
                "form" => "Wpjb_Form_Admin_Application",
                "info" => __("Form saved.", "wpjobboard"),
                "error" => __("There are errors in your form.", "wpjobboard")
            ),
            "deleteAction" => array(
                "info" => __("Application #%d deleted.", "wpjobboard"),
                "page" => "application"
            ),
            "_delete" => array(
                "model" => "Wpjb_Model_Application",
                "info" => __("Application deleted.", "wpjobboard"),
                "error" => __("There are errors in your form.", "wpjobboard")
            ),
            "_multi" => array(
                "delete" => array(
                    "success" => __("Number of deleted applications: {success}", "wpjobboard")
                ),
                "accept" => array(
                    "success" => __("Number of accepted applications: {success}", "wpjobboard")
                ),
                "reject" => array(
                    "success" => __("Number of rejected applications: {success}", "wpjobboard")
                ),
                "read" => array(
                    "success" => __("Number of applications marked as read: {success}", "wpjobboard")
                ),
            ),
            "_multiDelete" => array(
                "model" => "Wpjb_Model_Application"
            )
        );
    }

    protected function _multiDelete($id)
    {

        try {
            $model = new Wpjb_Model_Application($id);
            $model->delete();
            return true;
        } catch(Exception $e) {
            // log error
            return false;
        }
    }

    public function indexAction()
    {
        global $wpdb;
        
        $query = $this->_request->get("query");
        
        $this->view->rquery = $this->readableQuery($query);
        $param = $this->deriveParams($query, new Wpjb_Model_Application);
        $param["filter"] = $this->_request->get("filter", "all");
        $param["page"] = (int)$this->_request->get("p", 1);
        $param["count"] = $this->_getPerPage();
        $param["posted"] = null;
        
        if(!isset($param["job"])) {
            $param["job"] = null;
        }
        
        if($this->_request->get("posted")) {
            $p = $this->_request->get("posted");
            $df = date("Y-m-01", strtotime($p));
            $param["date_from"] = $df;
            $param["date_to"] = date("Y-m-t", $df);
            $param["posted"] = $this->_request->get("posted");;
        }
        
        $result = Wpjb_Model_Application::search($param);
        
        $stat = new stdClass();
        $stat->all = Wpjb_Model_Application::search(array_merge($param, array("filter"=>"all", "count_only"=>1)));
        $stat->new = Wpjb_Model_Application::search(array_merge($param, array("filter"=>"new", "count_only"=>1)));
        $stat->accepted = Wpjb_Model_Application::search(array_merge($param, array("filter"=>"accepted", "count_only"=>1)));
        $stat->rejected = Wpjb_Model_Application::search(array_merge($param, array("filter"=>"rejected", "count_only"=>1)));
        
        $this->view->data = $result->application;
        $this->view->show = $param["filter"];
        $this->view->current = $param["page"];
        $this->view->total = $result->pages;
        $this->view->param = array("filter"=>$param["filter"], "posted"=>$param["posted"], "job"=>$param["job"], "query"=>$query);
        $this->view->query = $this->_request->get("query");
        $this->view->stat = $stat;
        
        foreach($param as $k=>$v) {
            $this->view->$k = $v;
        }
        
        $name = new Wpjb_Model_Application();
        $name = $name->tableName();
        /* @var $wpdb wpdb */
        $result = $wpdb->get_results("
            SELECT DATE_FORMAT(applied_at, '%Y-%m') as dt
            FROM $name GROUP BY dt ORDER BY applied_at DESC
        ");

        $months = array();
        foreach($result as $r) {
            $months[$r->dt] = date("Y, F", strtotime($r->dt));
        }

        $this->view->months = $months;
        
        return;
        
        $param = array(
            "filter" => "all",
            "job" => "",
            "query" => "",
            "posted" => ""
        );
        
        $page = (int)$this->_request->get("p", 1);
        if($page < 1) {
            $page = 1;
        }
        $perPage = $this->_getPerPage();

        $query = new Daq_Db_Query();
        $query->select("*");
        $query->from("Wpjb_Model_Application t");
        $query->join("t.job t2");
        $query->order("t.applied_at DESC, t.id DESC");
        
        if($this->_request->get("job") > 0) {
            $jId = $this->_request->get("job");
            $query->where("job_id = ?", $jId);
            $this->view->jobObject = new Wpjb_Model_Job($jId);
            $param["job"] = $jId;
        }

        if($this->_request->get("query")) {
            $q = $this->_request->get("query");
            $query->where("(applicant_name LIKE ? OR email LIKE ?)", "%$q%");
            $param["query"] = $q;
        }
        
        if($this->_request->get("posted")) {
            $p = $this->_request->get("posted");
            $df = date("Y-m-01", strtotime($p));
            $query->where("applied_at >= ?", $df);
            $query->where("applied_at <= ?", date("Y-m-t", $df));
            $param["posted"] = $p;
        }

        $stat = new stdClass();
        $qcopy = clone $query;
        $stat->all = (int)$qcopy->select("COUNT(*) as `total`")->limit(1)->fetchColumn();
        $qcopy = clone $query;
        $stat->new = (int)$qcopy->select("COUNT(*) as `total`")->where("status IN(1,3)")->limit(1)->fetchColumn();
        $qcopy = clone $query;
        $stat->accepted = (int)$qcopy->select("COUNT(*) as `total`")->where("status=2")->limit(1)->fetchColumn();
        $qcopy = clone $query;
        $stat->rejected = (int)$qcopy->select("COUNT(*) as `total`")->where("status=0")->limit(1)->fetchColumn();
        
        if($this->_request->get("filter")) {
            $param["filter"] = $this->_request->get("filter");
            switch($param["filter"]) {
                case "rejected": $query->where("status = ?", 0); break;
                case "new": $query->where("status IN(1,3)"); break;
                case "accepted": $query->where("status = ?", 2); break;
            }
        }
        
        $query->limitPage($page, $perPage);
        
        $result = $query->execute();
        $total = $stat->all;

        $this->view->stat = $stat;
        $this->view->current = $page;
        $this->view->total = ceil($total/$perPage);
        $this->view->data = $result;
        $this->view->param = $param;
        
        foreach($param as $k=>$v) {
            $this->view->$k = $v;
        }
        
        $name = new Wpjb_Model_Application();
        $name = $name->tableName();
        /* @var $wpdb wpdb */
        $result = $wpdb->get_results("
            SELECT DATE_FORMAT(applied_at, '%Y-%m') as dt
            FROM $name GROUP BY dt ORDER BY applied_at DESC
        ");

        $months = array();
        foreach($result as $r) {
            $months[$r->dt] = date("Y, F", strtotime($r->dt));
        }

        $this->view->months = $months;
    }
    
    public function editAction() 
    {
        parent::editAction();
        
        $uid = $this->view->form->getObject()->user_id;

        if($uid > 0) {
            $this->view->user = new WP_User($uid);

            $query = new Daq_Db_Query();
            $query->select("t.id");
            $query->from("Wpjb_Model_Resume t");
            $query->where("user_id = ?", $uid);
            $query->limit(1);
            $this->view->resumeId = $query->fetchColumn();
        } else {
            $this->view->user = null;
            $this->view->resumeId = null;
        }
        
        // status change email!!!
    }
    
    protected function _multiAccept($id)
    {
        $object = new Wpjb_Model_Application($id);
        $object->status = Wpjb_Model_Application::STATUS_ACCEPTED;
        $object->save();
        
        $mail = Wpjb_Utility_Message::load("notify_applicant_status_change");
        $mail->assign("application", $object);
        $mail->assign("status", wpjb_application_status($object->status));
        $mail->setTo($object->email);
        //$mail->send();
        
        return true;
    }
    
    protected function _multiReject($id)
    {
        $object = new Wpjb_Model_Application($id);
        $object->status = Wpjb_Model_Application::STATUS_REJECTED;
        $object->save();
        
        $mail = Wpjb_Utility_Message::load("notify_applicant_status_change");
        $mail->assign("application", $object);
        $mail->assign("status", wpjb_application_status($object->status));
        $mail->setTo($object->email);
        //$mail->send();
        
        return true;
    }
    
    protected function _multiRead($id)
    {
        $object = new Wpjb_Model_Application($id);
        $object->status = Wpjb_Model_Application::STATUS_READ;
        $object->save();
        return true;
    }
    
    public function exportAction()
    {
        $query = $this->_request->get("query");
        
        $param = $this->deriveParams($query, new Wpjb_Model_Application);
        $param["filter"] = $this->_request->get("filter", "all");
        $param["job"] = $this->_request->get("job");
        $param["posted"] = null;
        $param["ids_only"] = 1;
        
        if($this->_request->get("posted")) {
            $p = $this->_request->get("posted");
            $df = date("Y-m-01", strtotime($p));
            $param["date_from"] = $df;
            $param["date_to"] = date("Y-m-t", $df);
            $param["posted"] = $this->_request->get("posted");;
        }
        
        $result = Wpjb_Model_Application::search($param);
        
        header("Content-type: text/plain; charset=utf-8");
        header('Content-Disposition: attachment; filename="applications.csv";');

        $app = new Wpjb_Model_Application();
        $csv = fopen("php://output", "w");
        $fields = array();

        foreach($app->getFieldNames() as $f) {
            if(!in_array($f, array("message"))) {
                $fields[] = $f;
            }
            
        }
        
        $select = new Daq_Db_Query();
        $select->select("*");
        $select->from("Wpjb_Model_Meta t1");
        $select->where("meta_object = ?", "apply");
        $meta = $select->execute();
        
        foreach($meta as $r) {
            if(!in_array($r->conf("type"), array("ui-input-textarea", "ui-input-file"))) {
                $fields[] = $r->name;
            }
        }
        
        fputcsv($csv, $fields);
        
        
        foreach($result->application as $r) {
            $app = new Wpjb_Model_Application($r);
            $arr = $app->toArray();
            
            $entry = array();
            foreach($fields as $f) {
                if(isset($arr[$f])) {
                    $entry[$f] = $arr[$f];
                } elseif(isset($arr["meta"][$f]) ) {
                    $entry[$f] = $arr["meta"][$f]["value"];
                } else {
                    $entry[$f] = "";
                }
            }
            
            fputcsv($csv, $entry);
            
            unset($app);
        }
        
        fclose($csv);
        
        exit;
    }

}

?>