<?php
/**
 * Description of ${name}
 *
 * @author ${user}
 * @package 
 */

class Wpjb_Module_Resumes_Index extends Wpjb_Controller_Frontend
{
    private $_perPage = 20;

    public function init()
    {
        $this->_perPage = Wpjb_Project::getInstance()->conf("front_jobs_per_page", 20);
        $this->view->baseUrl = Wpjb_Project::getInstance()->getUrl("resumes");
        $this->view->query = null;
        $this->view->format = null;
        $this->view->tolock = apply_filters("wpjb_lock_resume", array("user_email", "phone"));
    }

    protected function _canView($id)
    {
        $m = null;
        $premium = false;
        $button = array("contact"=>0, "login"=>0, "register"=>0, "purchase"=>0, "verify"=>0);
        $cv_access = wpjb_conf("cv_access");
        $request = Daq_Request::getInstance();
        
        if(Wpjb_Model_Resume::current() && Wpjb_Model_Resume::current()->id == $id) {
            // candidate can always access his resume
            $premium = true;
        }
        if($this->_hasPremiumAccess($id)) {
            // if has valid hash, always allow
            $premium = true;
        }
        if(is_array(wpjb_conf("cv_show_applicant_resume")) && $request->get("application_id") && Wpjb_Model_Company::current()) {
            $employer_id = Wpjb_Model_Company::current()->id;
            $application_id = $request->get("application_id");
            $resume = new Wpjb_Model_Resume($id);
            
            $query = new Daq_Db_Query();
            $query->from("Wpjb_Model_Application t");
            $query->join("t.job t2");
            $query->where("t.id = ?", $application_id);
            $query->where("t.user_id = ?", $resume->user_id);
            $query->where("t2.employer_id = ?", $employer_id);
            $query->limit(1);
            
            $result = $query->execute();

            if(is_array($result) && isset($result[0])) {
                $premium = true;
            }
        }
        

        if($premium) {
            // premium user alsways has access
            $button["contact"] = 1;
            
        } elseif(!get_current_user_id()) {
            // not registered user
            if(in_array($cv_access, array(2,3,4))) {
                $m = __("Login or register as Employer to contact this candidate.", "wpjobboard");
                $button["login"] = 1;
                $button["register"] = 1;
            } elseif($cv_access == 5) {
                $m = __("Login or purchase this resume contact details.", "wpjobboard");
                $button["login"] = 1;
                $button["purchase"] = 1;
            }
            
        } elseif(current_user_can("manage_jobs")) {
            // employer
            $company = Wpjb_Model_Company::current();
            if($cv_access == 4 && !$company->is_verified) {
                $m = __("You need to verify your account before contacting candidate.", "wpjobboard");
                $button["verify"] = 1;
            } elseif($cv_access == 4 && in_array($company->is_verified, array(Wpjb_Model_Company::ACCESS_PENDING, Wpjb_Model_Company::ACCESS_DECLINED))) {
                $m = __("Your account is pending verification or verification was declined.", "wpjobboard");
                $button["none"] = 1;
            } elseif($cv_access == 5 && !$premium) {
                $m = __("Purchase this resume contact details", "wpjobboard");
                $button["purchase"] = 1;
            } 
            
        } elseif(get_current_user_id()) {
            // other registered user
            if(in_array($cv_access, array(3,4))) {
                $m = __("Incorrect account type. You need to be registered as Employer to contact Candidates", "wpjobboard");
            } elseif($cv_access == 5) {
                $m = __("Purchase this resume contact details", "wpjobboard");
                $button["purchase"] = 1;
            }
        } else {
            // can contact
            $button["contact"] = 1;
        }
        
        if(array_sum($button) == 0) {
            $button["contact"] = 1;
        }
        
        $this->view->c_message = $m;
        $this->view->button = (object)$button;
    }
    
    protected function _canViewErr()
    {
        $c = (int)wpjb_conf("cv_privacy")."/".(int)wpjb_conf("cv_access");

        switch($c) {
            case "0/2":
                $m = __("Only registered members can contact candidates.", "wpjobboard");
                break;
            case "0/3":
                $m = __("Only Employers can contact candidates.", "wpjobboard");
                break;
            case "0/4":
                $m = __("Only <strong>verified</strong> Employers can contact candidates.", "wpjobboard");
                break;
            case "0/5":
                $m = __("Contacting candidaes requires premium access.", "wpjobboard");
                break;
        }
        
        if($m) {
            $this->view->_flash->addError($m);
            $this->view->error_message = $m;
        }
    }
    
    protected function _canBrowseErr()
    {
        $c = (int)wpjb_conf("cv_privacy")."/".(int)wpjb_conf("cv_access");

        switch($c) {
            case "1/2": 
                $this->view->_flash->addError(__("Only registered members can browse resumes.", "wpjobboard"));
                break;
            case "1/3":
                $this->view->_flash->addError(__("Only Employers can browse resumes.", "wpjobboard"));
                break;
            case "1/4":
                $this->view->_flash->addError(__("Only <strong>verified</strong> Employers can browse resumes.", "wpjobboard"));
                break;
            case "1/5":
                $this->view->_flash->addError(__("Resumes browsing requires premium access.", "wpjobboard"));
                break;
        }
    }
    
    protected function _canBrowse($id = null)
    {   
        $access = wpjb_conf("cv_access", 1);
        $hasPriv = false;
        $company = Wpjb_Model_Company::current();
        $candidate = Wpjb_Model_Resume::current();
        
        if($candidate && $candidate->id == $id) {
            // candidate can always see his resume
            return true;
        }

        if($access == 1) {
            // to all
            $hasPriv = true;
        } elseif($access == 2) {
            // registered members
            if(get_current_user_id()>0) {
                $hasPriv = true;
            }
        } elseif($access == 3) {
            // employers
            if(current_user_can("manage_jobs")) {
                $hasPriv = true;
            }
        } elseif($access == 4) {
            // employers verified
            if(current_user_can("manage_jobs") && $company && $company->is_verified == 1) {
                $hasPriv = true;
            }
        } elseif($access == 5) {
            // premium
            $hasPriv = $this->_hasPremiumAccess($id);
        }
        
        
        $this->view->can_browse = $hasPriv;
        
        return $hasPriv;
    }
    
    protected function _hasPremiumAccess($id)
    {
        $hash = $this->_request->get("hash");
        $price_for_c = Wpjb_Model_Pricing::PRICE_SINGLE_RESUME;
        $mlist = array();
        
        if(Wpjb_Model_Company::current()) {
            $mlist = Wpjb_Model_Company::current()->membership();
        }
        
        foreach($mlist as $membership) {
            $package = new Wpjb_Model_Pricing($membership->package_id);
            $data = $membership->package();
            
            if(!isset($data[$price_for_c])) {
                continue;
            }
            
            foreach($data[$price_for_c] as $pid => $use) {
                
                $pricing = new Wpjb_Model_Pricing($pid);
                
                if(!$pricing->exists()) {
                    continue;
                }
                
                if($use["status"] == "unlimited") {
                    return true;
                }
            }
        }
        
        if(get_current_user_id() > 0) {
            $query = new Daq_Db_Query();
            $query->from("Wpjb_Model_Payment t");
            $query->where("object_type = ?", Wpjb_Model_Payment::FOR_RESUMES);
            $query->where("object_id = ?", $id);
            $query->where("user_id = ?", get_current_user_id());
            $query->where("is_valid = 1");
            $query->limit(1);
            
            $result = $query->execute();
            
            if(!empty($result)) {
                return true;
            }
        } elseif($hash) {
            
            // "{$payment->id}|{$payment->object_id}|{$payment->object_type}|{$payment->paid_at}";
            
            $query = new Daq_Db_Query();
            $query->from("Wpjb_Model_Payment t");
            $query->where("MD5(CONCAT_WS('|', t.id, t.object_id, t.object_type, t.paid_at)) = ?", $hash);
            $query->where("is_valid = 1");
            $query->limit(1);
            
            $result = $query->execute();
            
            if(!empty($result)) {
                return true;
            }
        }
        
        return false;
    }

    public function indexAction()
    {
        $text = wpjb_conf("seo_resumes_name", __("Browse Resumes", "wpjobboard"));
        $this->setTitle($text);
        
        if(!$this->_canBrowse()) {
            $this->_canBrowseErr();
            if(wpjb_conf("cv_privacy") == 1) {
                return false;
            }
        }
        
        $param = array(
            "filter" => "active",
            "page" => $this->_request->get("page", 1),
            "count" => $this->_perPage
        );
        
        $this->view->search_bar = wpjb_conf("cv_search_bar", "disabled");
        $this->view->param = $param;
        $this->view->url = wpjr_link_to("home");
        
        return "index";
    }

    public function advsearchAction()
    {
        $this->setTitle(wpjb_conf("seo_resume_adv_search", __("Advanced Search", "wpjobboard")));
        $form = new Wpjb_Form_ResumesSearch();

        $this->view->form = $form;
        return "search";
    }

    public function searchAction()
    {
        $request = $this->getRequest();
        
        $text = wpjb_conf("seo_search_resumes", __("Search Results: {keyword}", "wpjobboard"));
        $param = array(
            'keyword' => $request->get("query")
        );
        $this->setTitle($text, $param);

        if(!$this->_canBrowse()) {
            $this->_canBrowseErr();
            if(wpjb_conf("cv_privacy") == 1) {
                return false;
            }
        }
        
        $resume = new Wpjb_Model_Resume();
        $meta = array();
        foreach($resume->meta as $k => $m) {
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
            "filter" => "active",
            "query" => $request->get("query"),
            "category" => $request->get("category"),
            "fullname" => $request->get("fullname"),
            "page" => $request->get("page", 1),
            "count" => $request->get("count", $this->_perPage),
            "country" => $request->get("country"),
            "location" => $request->get("location"),
            "radius" => $request->get("radius"),
            "meta" => $meta,
            "sort" => $request->get("sort"),
            "order" => $request->get("order"),
            "date_from" => $date_from,
            "date_to" => $date_to
        );
        
        $this->view->param = $param;
        $this->view->url = wpjr_link_to("search");
        
        $query = array();
        foreach($request->get() as $k => $v) {
            if(!empty($v) && !in_array($k, array("page", "job_resumes", "page_id"))) {
                $query[$k] = $v;
            }
        }
        $this->view->query = $query;
        
        return "index";
    }

    public function viewAction()
    {
        $this->view->form_error = null;
        $resume = $this->getObject();
        /* @var $resume Wpjb_Model_Resume */
        
        $this->_canView($resume->id);
        
        if(!$this->_canBrowse($resume->id)) {
            if(wpjb_conf("cv_privacy") == 1) {
                $this->_canViewErr();
                return false;
            }
        }
        
        $this->setTitle(wpjb_conf("seo_resumes_view", __("{full_name}", "wpjobboard")), array(
            "full_name" => trim($resume->user->first_name." ".$resume->user->last_name),
            "headline" => $resume->headline
        ));

        $this->view->current_url = wpjr_link_to("resume", $resume);
        $this->view->resume = $resume;
        
        $f = array();
        $show = array("contact"=>0, "purchase"=>0);
        
        if($this->_request->get("form") == "contact") {
            $show["contact"] = 1;
        }
        if($this->_request->get("form") == "purchase") {
            $show["purchase"] = 1;
        }
        
        if($this->view->button->contact == 1) {
            $f["contact"] = new Wpjb_Form_Resumes_Contact;
        }
        if($this->view->button->purchase == 1) {
            $f["purchase"] = new Wpjb_Form_Resumes_Purchase;
        }
        
        if($this->_request->post("purchase")) {
            $valid = $f["purchase"]->isValid($this->_request->getAll());
            if($valid) {
                
                list($price_for, $membership_id, $pricing_id) = explode("_", $f["purchase"]->value("listing_type"));
                
                if($membership_id) {
                    $membership = new Wpjb_Model_Membership($membership_id);
                    $membership->inc($pricing_id);
                    $membership->save();
                    
                    $f_is_valid = 1;
                    $f_paid_at = date("Y-m-d H:i:s");
                    $f_engine = "Credits";
                    $f_payment_sum = 0;
                    $f_payment_discount = 0;
                    $f_payment_currency = wpjb_default_currency();
                } else {
                    $listing = new Wpjb_Model_Pricing($pricing_id);
                    
                    $f_is_valid = 0;
                    $f_paid_at = date("Y-m-d H:i:s");
                    $f_engine = $f["purchase"]->value("payment_method");
                    $f_payment_sum = $listing->getTotal();
                    $f_payment_discount = $listing->getDiscount();
                    $f_payment_currency = $listing->currency;
                }
                
                $payment = new Wpjb_Model_Payment;
                $payment->object_type = Wpjb_Model_Payment::RESUME;
                $payment->object_id = $resume->getId();
                $payment->user_id = wp_get_current_user()->ID;
                $payment->email = $f["purchase"]->value("email");
                $payment->external_id = "";
                $payment->is_valid = $f_is_valid;
                $payment->message = "";
                $payment->created_at = date("Y-m-d H:i:s");
                $payment->paid_at = $f_paid_at;
                $payment->payment_paid = 0;
                $payment->engine = $f_engine;
                $payment->payment_sum = $f_payment_sum;
                $payment->payment_discount = $f_payment_discount;
                $payment->payment_currency = $f_payment_currency;
                $payment->save();
                
                $button = Wpjb_Project::getInstance()->payment->factory($payment);

                $this->setTitle(__("Payment", "wpjobboard"));

                $this->view->payment = $payment;
                $this->view->button = $button;
                $this->view->currency = Wpjb_List_Currency::getCurrencySymbol($payment->payment_currency);
                
                if($membership_id) {
                    $this->view->_flash->addInfo(__("Access to resume details has been granted.", "wpjobboard"));
                    $this->redirect(wpjr_link_to("resume", $this->getObject())."#wpjb-sent");
                } else {
                    return "resume-purchase";
                }
            } else {
                $show["purchase"] = 1;
                $this->view->form_error = __("There are errors in your form", "wpjobboard");
            }
        }
        
        if($this->_request->post("contact")) {
            $valid = $f["contact"]->isValid($this->_request->getAll());
            if($valid) {
                
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $body = $f["contact"]->value("message");
                $body.= "\r\n\r\n------------\r\n";
                $body.= $f["contact"]->value("fullname")." ".$f["contact"]->value("email");
                $headers = array(
                    "Reply-to: ".$f["contact"]->value("email")
                );
                
                wp_mail(
                    $resume->getUser(true)->user_email, 
                    sprintf(__('[%1$s] Message from %2$s', "wpjobboard"), $blogname, $f["contact"]->value("fullname")),
                    $body,
                    $headers
                );
                
                $this->view->_flash->addInfo(__("Your message has been sent.", "wpjobboard"));
                $this->redirect(wpjr_link_to("resume", $resume)."#wpjb-sent");
            } else {
                $show["contact"] = 1;
                $this->view->form_error = __("There are errors in your form", "wpjobboard");
            }
        }
        
        $this->view->f = $f;
        $this->view->show = (object)$show;
        
        return "resume";
    }

    public function myresumeAction()
    {
        $this->setTitle(wpjb_conf("seo_resume_my_resume", __("My profile details", "wpjobboard")));

        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page. Your current account type is Employer.", "wpjobboard"));
            return false;
        }
        
        $object = Wpjb_Model_Resume::current();
        if(!is_object($object)) {
            $this->view->_flash->addInfo(__("Please update (if required) and save your profile before continuing.", "wpjobboard"));
            $this->redirect(wpjr_link_to("myresume_edit", null, array("group"=>"default")));
        } else {
            $id = $object->getId();
            $this->view->disable_details = false;
        }

        $form = new Wpjb_Form_Resume($id);
        if($this->isPost() && !$this->_request->post("remove_image")) {
            $isValid = $form->isValid($this->_request->getAll());
            if($isValid) {
                $this->view->_flash->addInfo(__("Your resume has been saved.", "wpjobboard"));
                $form->save();
            } else {
                $this->view->_flash->addError(__("Cannot save your resume. There are errors in your form.", "wpjobboard"));
            }
        }

        $this->view->resume = $form->getObject();
        $this->view->form = $form;

        return "my-resume";
    }
    
    public function myapplicationsAction() 
    {
        $this->setTitle(__("My Applications", "wpjobboard"));

        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page. Your current account type is Employer.", "wpjobboard"));
            return false;
        }
        
        $query = new Daq_Db_Query();
        $query->from("Wpjb_Model_Application t");
        $query->where("user_id = ?", get_current_user_id());
        $query->order("t.applied_at DESC");
        
        $total = $query->select("COUNT(*) as cnt")->fetchColumn();
        $page = $this->getRequest()->getParam("page", 0);
        $perPage = 20;
        
        $query->select("*");
        $query->limitPage($page, $perPage);
        $query->join("t.job t2");
                
        $apps = $query->execute();
        
        $result = new stdClass();
        $result->perPage = $perPage;
        $result->total = $total;
        $result->application = $apps;
        $result->count = count($apps);
        $result->pages = ceil($result->total/$result->perPage);
        $result->page = $page;
        
        $this->view->result = $result;
        $this->view->param = array("page"=>$page);
        $this->view->url = wpjr_link_to("myapplications");
        
        
        return "my-applications";
    }
    
    public function mybookmarksAction() 
    {
        $this->setTitle(__("My Bookmarks", "wpjobboard"));

        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page. Your current account type is Employer.", "wpjobboard"));
            return false;
        }
        
        $query = new Daq_Db_Query();
        $query->from("Wpjb_Model_Shortlist t");
        $query->where("user_id = ?", get_current_user_id());
        $query->where("object = ?", "job");
        $query->order("id DESC");
        
        $total = $query->select("COUNT(*) as cnt")->fetchColumn();
        $page = $this->getRequest()->getParam("page", 0);
        $perPage = 20;
        
        $query->select("*");
        $query->limitPage($page, $perPage);
                
        $apps = $query->execute();
        
        $result = new stdClass();
        $result->perPage = $perPage;
        $result->total = $total;
        $result->shortlist = $apps;
        $result->count = count($apps);
        $result->pages = ceil($result->total/$result->perPage);
        $result->page = $page;
        
        $this->view->result = $result;
        $this->view->param = array("page"=>$page);
        $this->view->url = wpjr_link_to("mybookmarks");
        
        
        return "my-bookmarks";
    }
    
    
    public function editAction()
    {
        $this->setTitle(wpjb_conf("seo_resume_my_resume", __("My profile details", "wpjobboard")));

        $object = Wpjb_Model_Resume::current();
        if(!is_object($object)) {
            $id = null;
        } else {
            $id = $object->getId();
        }

        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page.", "wpjobboard"));
            return false;
        }
        
        $form = new Wpjb_Form_Resume($id);
        $part = $this->_request->getParam("group");
        $groups = array();
        
        if($part) {
            $groups = array_keys($form->getGroups());
            $diff = array_diff($groups, (array)$part);
            $form->removeGroup($diff);
        } 
        
        if(!in_array($part, $groups)) {
            $this->view->_flash->addError(__("Incorrect group name.", "wpjobboard"));
            return false;
        }
        
        if($this->isPost()) {
            $isValid = $form->isValid($this->_request->getAll());
            if($isValid) {
                $this->view->_flash->addInfo(__("Your resume has been saved.", "wpjobboard"));
                $form->save();
                $this->updateModDate();
                
                if($this->_request->post("wpjb_savenclose")) {
                    $this->redirect(wpjr_link_to("myresume"));
                }
                
            } else {
                $this->view->_flash->addError(__("Cannot save your resume. There are errors in your form.", "wpjobboard"));
            }
        }
        
        $this->view->form = $form;
        
        return "my-resume-edit";
    }
    
    public function passwordAction()
    {
        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page.", "wpjobboard"));
            return false;
        }
        
        $url = wpjr_link_to("myresume");
        
        $this->setTitle(__("Change Password", "wpjobboard"));
        $this->view->action = "";
        $this->view->submit = __("Change Password", "wpjobboard");
        
        $form = new Wpjb_Form_PasswordChange();
        if($this->isPost()) {
            $isValid = $form->isValid($this->getRequest()->getAll());
            if($isValid) {
                $result = wp_update_user(array("ID"=> get_current_user_id(), "user_pass"=>$form->value("user_password")));
                $s = __("Your password has been changed. <a href=\"%s\">Go Back &rarr;</a>", "wpjobboard");
                $this->view->_flash->addInfo(sprintf($s, $url));
                return false;
            } else {
                $this->view->_flash->addError(__("There are errors in your form", "wpjobboard"));
            }
        }
        
        foreach(array("user_password", "user_password2", "old_password") as $f) {
            if($form->hasElement($f)) {
                $form->getElement($f)->setValue("");
            }
        }
        
        $this->view->form = $form;
        
        return "../default/form"; 
    }
    
    public function deleteAction()
    {
        global $current_user;
        
        if(!is_user_logged_in()) {
            $this->view->_flash->addError(__("You need to be logged in to access this page.", "wpjobboard"));
            return false;
        }
        
        if(!current_user_can("manage_resumes")) {
            $this->view->_flash->addError(__("You need to be registered as Candidate in order to access this page.", "wpjobboard"));
            return false;
        }
        
        $user = Wpjb_Model_Resume::current();
        $full = Wpjb_Model_Resume::DELETE_FULL;
        
        $this->setTitle(__("Delete Account", "wpjobboard"));
        $this->view->action = "";
        $this->view->submit = __("Delete Account", "wpjobboard");
        
        $form = new Wpjb_Form_DeleteAccount();
        
        if($this->isPost()) {
            $isValid = $form->isValid($this->getRequest()->getAll());
            if($isValid) {
                $user->delete($full);
                $current_user = null;
                wp_logout();
                $this->setTitle(__("Account Deleted", "wpjobboard"));
                $s = __("Your account has been deleted. <a href=\"%s\">Go Back &rarr;</a>", "wpjobboard");
                $this->view->_flash->addInfo(sprintf($s, get_home_url()));
                return false;
            } else {
                $this->view->_flash->addError(__("There are errors in your form", "wpjobboard"));
            }
        }
        
        foreach(array("user_password") as $f) {
            if($form->hasElement($f)) {
                $form->getElement($f)->setValue("");
            }
        }
        
        $this->view->form = $form;
        
        return "../default/form"; 
    }
    
    public function detailaddAction()
    {
        if($this->_request->getParam("detail") == "experience") {
            $form = "Wpjb_Form_Resumes_Experience";
            $info = __("New work experience has been added.", "wpjobboard");
            $error = __("There are errors in your form", "wpjobboard");
            $title = __("Add work experience", "wpjobboard"); 
        } else {
            $form = "Wpjb_Form_Resumes_Education";
            $info = __("New education has been added.", "wpjobboard");
            $error = __("There are errors in your form", "wpjobboard");
            $title = __("Add education", "wpjobboard");
        }
        
        $saveclose = $this->_request->getParam("wpjb_savenclose", false);
        
        $resume = Wpjb_Model_Resume::current();
        $this->redirectIf($resume->id<1, wpjr_link_to("myresume"));
        
        $form = new $form();
        $form->getElement("resume_id")->setValue($resume->id);
        $id = false;
        
        if($this->isPost()) {
            $isValid = $form->isValid($this->_request->getAll());
            if($isValid) {
                $this->view->_flash->addInfo($info);
                $id = $form->save();
                $this->updateModDate();
                if(!$id) {
                    $id = $form->getId();
                }
            } else {
                $this->view->_flash->addError($error);
            }
        }

        $redir = wpjr_link_to("myresume_detail_edit", $form->getObject());
        
        $this->redirectIf($id && $saveclose, wpjr_link_to("myresume"));
        $this->redirectIf($id, $redir);
        $this->view->form = $form;
        
        $this->setTitle($title);
        $this->view->detail = $this->_request->getParam("detail");
        
        
        return "my-resume-detail";
    }
    
    public function detaileditAction()
    {
        $rid = $this->_request->getParam("id");
        $saveclose = $this->_request->getParam("wpjb_savenclose", false);
        
        $resume = new Wpjb_Model_ResumeDetail($rid);
        
        if($resume->resume_id != Wpjb_Model_Resume::current()->id) {
            $this->view->_flash->addError(__("It seems this resume detail does not belong to you!", "wpjobboard"));
            return false;
        }
        
        $this->redirectIf($resume->id<1, wpjb_admin_url("resumes"));

        if($resume->type == Wpjb_Model_ResumeDetail::EXPERIENCE) {
            $form = "Wpjb_Form_Resumes_Experience";
            $info = __("Work experience has been updated.", "wpjobboard");
            $error = __("There are errors in your form", "wpjobboard");
            $title = __("Edit work experience", "wpjobboard");
            
        } else {
            $form = "Wpjb_Form_Resumes_Education";
            $info = __("Education has been updated.", "wpjobboard");
            $error = __("There are errors in your form", "wpjobboard");
            $title = __("Edit education", "wpjobboard");
        }
        
        $form = new $form($resume->id);
        $form->removeElement("resume_id");
        $form->removeElement("type");
        $id = false;
        
        if($this->isPost()) {
            $isValid = $form->isValid($this->_request->getAll());
            if($isValid) {
                $this->view->_flash->addInfo($info);
                $id = $form->save();
                if(!$id) {
                    $id = $form->getId();
                }
                $this->updateModDate();
            } else {
                $this->view->_flash->addError($error);
            }
        }
        
        $this->redirectIf($saveclose, wpjr_link_to("myresume"));

        $this->view->form = $form;
        
        $this->view->title = $title;
        $this->view->detail = $this->_request->getParam("detail");
        
        return "my-resume-detail";
    }
    
    public function detaildeleteAction()
    {
        $rid = $this->_request->getParam("id");
        
        $resume = new Wpjb_Model_ResumeDetail($rid);
        
        if($resume->resume_id != Wpjb_Model_Resume::current()->id) {
            $this->view->_flash->addError(__("It seems this resume detail does not belong to you!", "wpjobboard"));
            return false;
        }
        
        $resume->delete();
        $this->updateModDate();
        $this->view->_flash->addInfo(__("Resume detail deleted.", "wpjobboard"));
        
        $this->redirect(wpjr_link_to("myresume"));
    }

    public function loginAction()
    {
        $object = Wpjb_Model_Resume::current();
        if(is_object($object) && $object->exists()) {
            wp_redirect(wpjr_link_to("myresume"));
        }

        $this->setTitle(__("Login", "wpjobboard"));
        $form = new Wpjb_Form_Resumes_Login();
        $this->view->errors = array();

        if($this->isPost()) {
            $user = $form->isValid($this->getRequest()->getAll());
            if($user instanceof WP_Error) {
                foreach($user->get_error_messages() as $error) {
                    $this->view->_flash->addError($error);
                }
            } elseif($user === false) {
                $this->view->_flash->addError(__("Incorrect username or password", "wpjobboard"));
            } else {
                $this->view->_flash->addInfo(__("You have been logged in.", "wpjobboard"));
                $redirect = $form->value("redirect_to");
                
                if(!$redirect) {
                    $redirect = wpjr_link_to("myresume");
                }
                
                $this->redirect($redirect);
            }
        }

        $this->view->form = $form;
        
        return "login";
    }
    
    public function logoutAction()
    {
        wp_logout();
        $this->view->_flash->addInfo(__("You have been logged out", "wpjobboard"));
        $this->redirect(wpjr_url());
    }
    
    public function registerAction()
    {
        if(!get_option('users_can_register')) {
            $this->view->_flash->addError(__("User registration is disabled.", "wpjobboard"));
            return false;
        }

        $object = Wpjb_Model_Resume::current();
        if($object) {
            wp_redirect(wpjr_link_to("myresume"));
        }
        
        $this->setTitle(__("Register", "wpjobboard"));

        $form = new Wpjb_Form_Resumes_Register();
        $this->view->errors = array();

        if($this->isPost()) {
            $isValid = $form->isValid($this->getRequest()->getAll());
            if($isValid) {

                $form->save();
               
                $url = wpjr_link_to("login");
                $username = $form->value("user_login");
                $password = $form->value("user_password");
                $email = $form->value("user_email");
                
                $mail = Wpjb_Utility_Message::load("notify_canditate_register");
                $mail->setTo($email);
                $mail->assign("username", $username);
                $mail->assign("password", $password);
                $mail->assign("login_url", $url);
                $mail->send();

                $this->view->_flash->addInfo(__("You have been registered.", "wpjobboard"));
                
                $form = new Wpjb_Form_Resumes_Login();
                if($form->hasElement("recaptcha_response_field")) {
                    $form->removeElement("recaptcha_response_field");
                }
                $form->isValid(array(
                    "user_login" => $username,
                    "user_password" => $password,
                    "remember" => 0
                ));
                
                $this->redirect(wpjr_link_to("myresume"));
            } else {
                $this->view->_flash->addError(__("There are errors in your form.", "wpjobboard"));
            }
        }

        $this->view->form = $form;

        return "register";
    }


    public function updateModDate()
    {
        $resume = Wpjb_Model_Resume::current();
        
        if($resume === null) {
            return;
        }
        
        $resume->modified_at = date("Y-m-d H:i:s");
        $resume->save();
    }
}

?>
