<?php

function wpjb_title() {
    
    $title = "";
    
    if(is_wpjb() || is_wpjr()) {
        $title = "<h2>".esc_html(Wpjb_Project::getInstance()->title)."</h2>";
    }
    
    return $title;
}

function wpjb_jobs_search() {
    
    $view = Wpjb_Project::getInstance()->getApplication("frontend")->getView();
    $view->form = new Wpjb_Form_AdvancedSearch();
    if($view->form->hasElement("query")) {
        $view->form->getElement("query")->setValue("");
    }
    $view->shortcode = true;
    
    wp_enqueue_script("jquery");
    wp_enqueue_style("wpjb-css");
    
    ob_start();
    $view->render("search.php");
    return ob_get_clean();
}

function wpjb_jobs_list($atts) {
    
    $request = Daq_Request::getInstance();
    
    $params = shortcode_atts(array(
        "filter" => "active",
        "query" => null,
        "category" => null,
        "type" => null,
        "country" => null,
        "state" => null,
        "city" => null,
        "posted" => null,
        "location" => null,
        "is_featured" => null,
        "employer_id" => null,
        "meta" => array(),
        "hide_filled" => wpjb_conf("front_hide_filled", false),
        "sort" => null,
        "order" => null,
        "sort_order" => "t1.is_featured DESC, t1.job_created_at DESC, t1.id DESC",
        "search_bar" => "disabled",
        "pagination" => true,
        "standalone" => false,
        'page' => $request->get("pg", 1),
        'count' => 20,
    ), $atts);
    
    foreach((array)$atts as $k=>$v) {
        if(stripos($k, "meta__") === 0) {
            $params["meta"][substr($k, 6)] = $v;
        }
    }
    
    $init = array();
    foreach(array_keys((array)$atts) as $key) {
        if(isset($params[$key]) && !in_array($key, array("search_bar"))) {
            $init[$key] = $params[$key];
        }
    }
    
    $view = Wpjb_Project::getInstance()->getApplication("frontend")->getView();
    $view->param = $params;
    $view->pagination = $params["pagination"];
    $view->url = get_the_permalink();
    $view->query = "";
    $view->shortcode = true;
    $view->search_bar = $params["search_bar"];
    $view->search_init = $init;
    $view->format = '?pg=%#%';
    
    Wpjb_Project::getInstance()->placeHolder = $view;
    
    wp_enqueue_style("wpjb-css");
    wp_enqueue_script('wpjb-js');
    
    ob_start();
    $view->render("index.php");
    return ob_get_clean();
}

function wpjb_resumes_search() {
    
    $view = Wpjb_Project::getInstance()->getApplication("resumes")->getView();
    $view->form = new Wpjb_Form_ResumesSearch();
    $view->form->getElement("query")->setValue("");
    $view->shortcode = true;
    
    wp_enqueue_style("wpjb-css");
    
    ob_start();
    $view->render("search.php");
    return ob_get_clean();
}

function wpjb_resumes_list($atts) {
    
    $request = Daq_Request::getInstance();
    
    $params = shortcode_atts(array(
        "filter" => "active",
        "query" => null,
        "fullname" => null,
        "category" => null,
        "type" => null,
        "country" => null,
        "posted" => null,
        "location" => null,
        "is_featured" => null,
        "meta" => array(),
        "sort" => null,
        "order" => null,
        "sort_order" => "t1.modified_at DESC, t1.id DESC",
        'page' => $request->get("pg", 1),
        'count' => 20,
    ), $atts);
    
    foreach((array)$atts as $k=>$v) {
        if(stripos($k, "meta__") === 0) {
            $params["meta"][substr($k, 6)] = $v;
        }
    }
    
    $view = Wpjb_Project::getInstance()->getApplication("resumes")->getView();
    $view->param = $params;
    $view->url = get_the_permalink();
    $view->query = "";
    $view->shortcode = true;
    $view->format = '?pg=%#%';
        
    Wpjb_Project::getInstance()->placeHolder = $view;
    
    wp_enqueue_style("wpjb-css");
    
    ob_start();
    $view->render("index.php");
    return ob_get_clean();
}

function wpjb_apply_form() {
    
    $request = Daq_Request::getInstance();
    $job = new Wpjb_Model_Job();
    $view = Wpjb_Project::getInstance()->getApplication("frontend")->getView();
    
    wp_enqueue_script("jquery");
    wp_enqueue_script("wpjb-js");
    wp_enqueue_script("wpjb-plupload");
    wp_enqueue_style("wpjb-css");
    
    $form = new Wpjb_Form_Apply();
    $can_apply = true;
    
    if($request->post("_wpjb_action")=="apply" && $can_apply) {

        if($form->isValid($request->getAll())) {
            // send
            $var = $form->getValues();

            $user = null;
            if($job->user_id) {
                $user = new WP_User($job->user_id);
            }

            $form->setJobId($job->getId());
            $form->setUserId(Wpjb_Model_Resume::current()->user_id);

            $form->save();
            $application = new Wpjb_Model_Application($form->getObject()->id);

            // notify employer
            $files = array();
            foreach($application->getFiles() as $f) {
                $files[] = $f->dir;
            }

            // notify admin
            $mail = Wpjb_Utility_Message::load("notify_admin_general_application");
            $mail->assign("application", $application);
            $mail->assign("resume", Wpjb_Model_Resume::current());
            $mail->addFiles($files);
            $mail->setTo(get_option("admin_email"));
            $mail->send();

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

            $view->_flash->addInfo(__("Your application has been sent.", "wpjobboard"));
            $form = new Wpjb_Form_Apply();
            
        } else {
            $view->_flash->addError(__("There are errors in your form.", "wpjobboard"));
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
    
    $view->form = $form;
    $view->submit = __("Send Application", "wpjobboard");
    $view->action = "";
    $view->shortcode = true;
    
    if(Wpjb_Project::getInstance()->placeHolder === null) {
        Wpjb_Project::getInstance()->placeHolder = new stdClass();
    }
    
    Wpjb_Project::getInstance()->placeHolder->_flash = $view->_flash;
    
    ob_start();
    $view->render("../default/form.php");
    return ob_get_clean();
}

function wpjb_jobs_map() {
    ?>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php esc_attr_e(wpjb_conf("google_api_key")) ?>"></script>
    <script type="text/javascript">
        var map;
        function initialize() {
          var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(-34.397, 150.644)
          };
          map = new google.maps.Map(document.getElementById('map-canvas'),
              mapOptions);
        }

        google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <hr/>
    <div id="map-canvas" style="min-height:400px"></div>
    <hr/>
    <?php
}

?>
