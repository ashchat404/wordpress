<?php
/**
 * Description of Index
 *
 * @author greg
 * @package 
 */

class Wpjb_Module_Frontend_Index extends Wpjb_Controller_Frontend
{
    private $_perPage = 20;

    public function init()
    {
        $this->_perPage = wpjb_conf("front_jobs_per_page", 20);
        $this->view->placeholder = false;
        $this->view->query = null;
        $this->view->pagination = true;
        $this->view->format = null;
    }
    
    public function indexAction()
    {   
        $text = Wpjb_Project::getInstance()->conf("seo_job_board_name", __("Job Board", "wpjobboard"));
        $this->setTitle($text);
        $this->setCanonicalUrl(Wpjb_Project::getInstance()->getUrl());
        
        $param = array(
            "filter" => "active",
            "page" => $this->_request->get("page", 1),
            "count" => $this->_perPage
        );
        
        $this->view->search_bar = wpjb_conf("search_bar", "disabled");
        $this->view->search_init = array();
        $this->view->param = $param;
        $this->view->url = wpjb_link_to("home");
    }

    public function companyAction()
    {
        $company = $this->getObject();
        /* @var $company Wpjb_Model_Employer */

        $text = wpjb_conf("seo_job_employer", __("{company_name}", "wpjobboard"));
        $param = array(
            'company_name' => $this->getObject()->company_name
        );
        $this->setTitle($text, $param);

        if(Wpjb_Model_Company::current() && Wpjb_Model_Company::current()->id==$company->id) {
            // do nothing
        } elseif($company->is_active == Wpjb_Model_Company::ACCOUNT_INACTIVE) {
            $this->view->_flash->addError(__("Company profile is inactive.", "wpjobboard"));
        } elseif(!$company->is_public) {
            $this->view->_flash->addInfo(__("Company profile is hidden.", "wpjobboard"));
        } elseif(!$company->isVisible()) {
            $this->view->_flash->addError(__("Company profile will be visible once employer will post at least one job.", "wpjobboard"));
        }

        $this->view->company = $company;
        $this->view->param = array(
            "filter" => "active",
            "employer_id" => $company->id
        );
    }

    public function categoryAction()
    {
        $object = $this->getObject();
        if($object->type != Wpjb_Model_Tag::TYPE_CATEGORY) {
            $this->view->_flash->addError(__("Category does not exist.", "wpjobboard"));
            return false;
        }
        
        $text = wpjb_conf("seo_category", __("Category: {category}", "wpjobboard"));
        $param = array(
            'category' => $this->getObject()->title
        );

        $this->setCanonicalUrl(wpjb_link_to("category", $this->getObject()));

        $this->view->current_category = $this->getObject();
        $this->setTitle($text, $param);

        $this->view->param = array(
            "filter" => "active",
            "page" => $this->_request->get("page", 1),
            "count" => $this->_perPage,
            "category" => $this->getObject()->id
        );
        
        $this->view->search_bar = wpjb_conf("search_bar", "disabled");
        $this->view->search_init = array("category" => $this->getObject()->id);
        $this->view->url = $object->url();
        
        return "index";
    }

    public function typeAction()
    {
        $object = $this->getObject();
        if($object->type != Wpjb_Model_Tag::TYPE_TYPE) {
            $this->view->_flash->addError(__("Job type does not exist.", "wpjobboard"));
            return false;
        }
        
        $text = wpjb_conf("seo_job_type", __("Job Type: {type}", "wpjobboard"));
        $param = array(
            'type' => $this->getObject()->title
        );
        $this->setCanonicalUrl(wpjb_link_to("type", $this->getObject()));

        $this->view->current_type = $this->getObject();
        $this->setTitle($text, $param);

        $this->view->param = array(
            "filter" => "active",
            "page" => $this->_request->get("page", 1),
            "count" => $this->_perPage,
            "type" => $this->getObject()->id
        );
        
        $this->view->search_bar = wpjb_conf("search_bar", "disabled");
        $this->view->search_init = array("type" => $this->getObject()->id);
        $this->view->url = $object->url();
        
        return "index";
    }

    public function searchAction()
    {
        $request = $this->getRequest();
        $text = wpjb_conf("seo_search_results", __("Search Results: {keyword}", "wpjobboard"));
        $param = array(
            'keyword' => $request->get("query", "")
        );
        $this->setTitle($text, $param);

        $request = Daq_Request::getInstance();
        
        $job = new Wpjb_Model_Job();
        $meta = array();
        foreach($job->meta as $k => $m) {
            if($request->get($k)) {
                $meta[$k] = $request->get($k);
            }
        }
        
        $date_from = $request->get("date_from");
        $date_to = $request->get("date_to");
        
        if($request->get("posted")>0) {
            $posted = intval($request->get("posted"))-1;
            $date_to = date("Y-m-d");
            $date_from = date("Y-m-d", wpjb_time("$date_to -$posted DAY"));
        }
        
        $param = array(
            "query" => $request->get("query"),
            "category" => $request->get("category"),
            "type" => $request->get("type"),
            "page" => $request->get("page", 1),
            "count" => $request->get("count", $this->_perPage),
            "country" => $request->get("country"),
            "state" => $request->get("state"),
            "city" => $request->get("city"),
            "posted" => $request->get("posted"),
            "location" => $request->get("location"),
            "radius" => $request->get("radius"),
            "is_featured" => $request->get("is_featured"),
            "employer_id" => $request->get("employer_id"),
            "meta" => $meta,
            "sort" => $request->get("sort"),
            "order" => $request->get("order"),
            "date_from" => $date_from,
            "date_to" => $date_to
        );
        
        $this->view->param = $param;
        $this->view->url = wpjb_link_to("search");
        
        $query = array();
        foreach($request->get() as $k => $v) {
            if(!empty($v) && !in_array($k, array("page", "job_board", "page_id"))) {
                $query[$k] = $v;
            }
        }
        
        $init = array();
        foreach($param as $k => $v) {
            if(!empty($v) && !in_array($k, array("page", "job_board", "page_id"))) {
                $init[$k] = $v;
            }
        }
        
        $this->view->query = $query;
        
        $this->view->search_bar = wpjb_conf("search_bar", "disabled");
        $this->view->search_init = $init;
        
        return "index";
    }

    public function advsearchAction()
    {
        $this->setTitle(wpjb_conf("seo_adv_search", __("Advanced Search", "wpjobboard")));
        $form = new Wpjb_Form_AdvancedSearch();
        
        $this->view->form = $form;
        return "search";
    }

    public function singleAction()
    {
        $this->view->members_only = false;
        $this->view->form_error = null;
        
        $this->setTitle(" ");
        $job = $this->getObject();

        $url = wpjb_link_to("job", $job);
        $this->setCanonicalUrl($url);
       
        $inrange = $job->time->job_created_at < time() && $job->time->job_expires_at+86400 > time();
        
        $show_related = (bool)wpjb_conf("front_show_related_jobs");
        $this->view->show_related = $show_related;
        
        $this->view->show = new stdClass();
        $this->view->show->apply = 0;
        
        if($job->meta->job_source->value()) {
            $this->view->application_url = $job->company_url;
        } else {
            $this->view->application_url = null;
        }
        
        if($this->_request->get("form") == "apply") {
            $this->view->show->apply = 1;
        }

        if(($job->is_active && $job->is_approved && $inrange) || $this->_isUserAdmin()) {

            $this->view->job = $job;

            $text = wpjb_conf("seo_single", __("{job_title}", "wpjobboard"));
            $param = array('job_title' => $job->job_title, 'id' => $job->id);
            $this->setTitle($text, $param);

            $old = wpjb_conf("front_mark_as_old");

            if($old>0 && time()-strtotime($job->job_created_at)>$old*3600*24) {
                $diff = floor((time()-strtotime($job->job_created_at))/(3600*24));
                $msg = _n(
                    "Attention! This job posting is one day old and might be already filled.",
                    "Attention! This job posting is %d days old and might be already filled.",
                    $diff,
                    "wpjobboard"
                );
                $this->view->_flash->addInfo(sprintf($msg, $diff));
            }

            if($job->is_filled) {
                $msg = __("This job posting was marked by employer as filled and is probably no longer available", "wpjobboard");
                $this->view->_flash->addInfo($msg);
            }

            if($job->employer_id > 0) {
                $this->view->company = new Wpjb_Model_Company($job->employer_id);
            }
            
            $related = array(
                "query" => $job->job_title,
                "page" => 1,
                "count" => 5,
                "id__not_in" => $job->id
            );

            $this->view->related = $related;
            

        } else {
            // job inactive or not exists
            $goback = "javascript:history.back(-1);";
            
            if(isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], site_url())===0) {
                $goback = $_SERVER['HTTP_REFERER'];
            }
            
            $msg = __("Selected job is inactive or does not exist. <a href=\"%s\">Go back</a>.", "wpjobboard");
            $this->view->_flash->addError(sprintf($msg, $goback));
            $this->view->job = null;
            return false;
        }
        
        $this->applyAction();
    }

    public function applyAction()
    {
        $standalone = false;
        
        if(!$this->isMember() && wpjb_conf("front_apply_members_only", false)) {
            $this->view->members_only = true;
            $m = __("Only registered members can apply for jobs.", "wpjobboard");
            if($standalone) {
                $this->view->_flash->addError($m);
            } else {
                $this->view->form_error = $m;
            }
            return;
        }

        $can_apply = apply_filters("wpjb_user_can_apply", true, $this->getObject(), $this);
        
        $this->view->can_apply = $can_apply;
        $this->view->form_sent = false;
        
        $form = new Wpjb_Form_Apply();
        if($this->isPost() && $this->getRequest()->post("_wpjb_action")=="apply" && $can_apply) {
            
            $this->view->form_sent = true;
            
            if($form->isValid($this->getRequest()->getAll())) {
                // send
                $var = $form->getValues();
                $job = $this->getObject();
                
                $user = null;
                if($job->user_id) {
                    $user = new WP_User($job->user_id);
                }

                $form->setJobId($job->getId());
                $form->setUserId(Wpjb_Model_Resume::current()->user_id);

                $form->save();
                $application = new Wpjb_Model_Application($form->getObject()->id);
                $job->applications++;
                
                // notify employer
                $files = array();
                foreach($application->getFiles() as $f) {
                    $files[] = $f->dir;
                }
                
                // notify admin
                $mail = Wpjb_Utility_Message::load("notify_admin_new_application");
                $mail->assign("job", $job);
                $mail->assign("application", $application);
                $mail->assign("resume", Wpjb_Model_Resume::current());
                $mail->addFiles($files);
                $mail->setTo(get_option("admin_email"));
                $mail->send();
                
                // notify employer
                $notify = null;
                if($job->company_email) {
                    $notify = $job->company_email;
                } elseif($user && $user->user_email) {
                    $notify = $user->user_email;
                }
                if($notify == get_option("admin_email")) {
                    $notify = null;
                }
                $mail = Wpjb_Utility_Message::load("notify_employer_new_application");
                $mail->assign("job", $job);
                $mail->assign("application", $application);
                $mail->assign("resume", Wpjb_Model_Resume::current());
                $mail->addFiles($files);
                $mail->setTo($notify);
                if($notify !== null) {
                    $mail->send();
                }
                
                // notify applicant
                $notify = null;
                if(isset($var["email"]) && $var["email"]) {
                    $notify = $var["email"];
                } elseif(wp_get_current_user()->ID > 0) {
                    $notify = wp_get_current_user()->user_email;
                }
                $mail = Wpjb_Utility_Message::load("notify_applicant_applied");
                $mail->setTo($notify);
                $mail->assign("job", $job);
                $mail->assign("application", $application);
                if($notify !== null) {
                    $mail->send();
                }

                $this->view->application_sent = true;
                $this->view->_flash->addInfo(__("Your application has been sent.", "wpjobboard"));

                $this->redirectIf(!$standalone, wpjb_link_to("job", $job)."#wpjb-sent");

            } else {
                $m = __("There are errors in your form.", "wpjobboard");
                if($standalone) {
                    $this->view->_flash->addError($m);
                } else {
                    $this->view->form_error = $m;
                }
            }

        } elseif(Wpjb_Model_Resume::current()) {
            $resume = Wpjb_Model_Resume::current();
            if(!is_null($resume) && $form->hasElement("email")) {
                $form->getElement("email")->setValue($resume->user->user_email);
            }
            if(!is_null($resume) && $form->hasElement("applicant_name")) {
                $form->getElement("applicant_name")->setValue($resume->user->first_name." ".$resume->user->last_name);
            }
        }
        
        $this->view->form = $form;

    }
    
    public function paymentAction()
    {
        $payment = $this->getObject();
        $button = Wpjb_Project::getInstance()->payment->factory($payment);
        
        $this->setTitle(__("Payment", "wpjobboard"));
        
        if($payment->payment_sum == $payment->payment_paid) {
            $this->view->_flash->addInfo(__("This payment was already processed correctly.", "wpjobboard"));
            return false;
        }
        
        if($payment->object_type == 1) {
            $this->view->job = new Wpjb_Model_Job($payment->object_id);
        }
        
        $this->view->payment = $payment;
        $this->view->button = $button;
        $this->view->currency = Wpjb_List_Currency::getCurrencySymbol($payment->payment_currency);
    }
    
    public function alertAction()
    {
        $this->setTitle(__("Job Alerts", "wpjobboard"));
        
        $request = Daq_Request::getInstance();
        $form = new Wpjb_Form_Frontend_Alert();

        if($form->isValid($request->getAll())) {
            
            $alert = new Wpjb_Model_Alert;
            $alert->user_id = get_current_user_id();
            $alert->keyword = $request->post("keyword");
            $alert->email = $request->post("email");
            $alert->created_at = date("Y-m-d H:i:s");
            $alert->last_run = "0000-00-00 00:00:00";
            $alert->frequency = 1;
            $alert->params = serialize(array("filter"=>"active", "keyword"=>$alert->keyword));
            $alert->save();
            
            $this->view->_flash->addInfo(__("Your alert was successfully set up!.", "wpjobboard"));
        } else {
            $this->view->_flash->addError(__("Alert could not be added. There was an error in the form.", "wpjobboard"));
        }
        
        return false;
    }
    
    public function deleteAlertAction()
    {
        $request = Daq_Request::getInstance();
        $this->setTitle(__("Job Alerts", "wpjobboard"));
        $hash = $request->get("hash");
        
        if(empty($hash)) {
            $this->view->_flash->addError(__("Provided hash code is empty.", "wpjobboard"));
            return false;
        }
        
        $query = new Daq_Db_Query();
        $query->from("Wpjb_Model_Alert t");
        $query->where("MD5(CONCAT(t.id, '|', t.email)) = ?", $hash);
        $query->limit(1);
        
        $result = $query->execute();
        
        if(empty($result)) {
            $this->view->_flash->addError(__("Provided hash code is invalid.", "wpjobboard"));
            return false;
        }
        
        $result[0]->delete();
        
        $this->view->_flash->addInfo(__("Alert deleted.", "wpjobboard"));
        
        return false;
    }
}

?>