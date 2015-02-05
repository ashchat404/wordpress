<?php
/* 
 * General Template Functions
 */

function wpjb_conf($param, $default = null) {
    return Wpjb_Project::getInstance()->conf($param, $default);
}

function wpjb_find_jobs(array $options = null) {
    return Wpjb_Model_JobSearch::search($options);
}

function wpjb_find_resumes(array $options = null) {
    return Wpjb_Model_ResumeSearch::search($options);
}

function wpjb_view($param, $default = null)
{
    $ph = Wpjb_Project::getInstance()->placeHolder;
    
    if($param == "job" && $ph->get("func_i", null) !== null) {
        $param = "func_job";
    }
    if($param == "resume" && $ph->get("func_ri", null) !== null) {
        $param = "func_resume";
    }
    if($param == "application" && $ph->get("func_ai", null) !== null) {
        $param = "func_application";
    }

    return $ph->get($param, $default);
}

function wpjb_api_url($action, $param) {
    global $wp_rewrite;
    
    /* @var $wp_rewrite WP_Rewrite */
    
    if($wp_rewrite->using_permalinks()) {

        $url = site_url()."/wpjobboard/".trim($action, "/")."/";
    
        if(!empty($param)) {
            $url .= "?".http_build_query($param);
        }
        
    } else {
        
        $url = site_url()."?wpjobboard=".urlencode(trim($action, "/")."/");
        
        if(!empty($param)) {
            $url .= "&".http_build_query($param);
        }
    }
    
    return $url;
}

function wpjb_link_to($key, $object = null, $param = array())
{
    $router = Wpjb_Project::getInstance()->router();
    $link = $router->linkTo($key, $object, $param);
    $url = Wpjb_Project::getInstance()->getUrl()."/".$link;
    
    return apply_filters("wpjb_link_to", $url, array("key"=>$key, "object"=>$object, "param"=>$param));
}

function wpjb_url()
{
    return Wpjb_Project::getInstance()->getUrl();
}

function wpjb_is_routed_to($path, $module = "frontend") {
    $i = Wpjb_Project::getInstance();
    $path = (array)$path;

    foreach($path as $p) {
        $router = false;
        if($module == "frontend" && is_wpjb()) {
            $router = $i->getApplication($module)->getRouter()->isRoutedTo($p);
        } elseif($module == "resumes" && is_wpjr()) {
            $router = $i->getApplication($module)->getRouter()->isRoutedTo($p);
        } 

        if($router) {
            return true;
        }
    }

    return false;
}

function wpjb_img($file)
{
    return Wpjb_Project::getInstance()->media()."/".$file;
}

function wpjb_view_set($param, $value)
{
    $ph = Wpjb_Project::$placeHolder;
    $ph->set($param, $value);
}

function wpjr_paginate_links()
{
    _wpjb_paginate_links("resumes");
}

function wpjb_paginate_links($url, $count, $page, $query = null, $format = null)
{
    $glue = "?";
    if(stripos($url.$format, "?")) {
        $glue = "&";
    }
    
    if($format === null) {
        $format = 'page/%#%/';
    }
    
    if(empty($query)) {
        $query = "";
    } elseif(is_array($query)) {
        $query = $glue.http_build_query($query);
    } elseif(is_string($query)) {
        $query = $glue.$query;
    }
    
    echo paginate_links( array(
        'base' => rtrim($url, "/")."/%_%".$query,
        'format' => $format,
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => $count,
        'current' => $page
    ));
}

function _wpjb_paginate_links($app = "frontend")
{
    $pFormat = wpjb_view("cDir");
    if(!empty($pFormat)) {
        $pFormat = "/".rtrim($pFormat, "/");
    }

    $qString = "";
    $qs = trim(wpjb_view("qString"));
    if(!empty($qs)) {
        $qString = "?".$qs;
    }

    $baseUrl = wpjb_view("baseUrl");
    if(empty($baseUrl)) {
        $baseUrl = Wpjb_Project::getInstance()->getUrl($app);
    }

    echo paginate_links( array(
        'base' => $baseUrl.$pFormat."%_%".$qString,
        'format' => '/page/%#%',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => wpjb_view("jobCount"),
        'current' => wpjb_view("jobPage")
    ));
}

function wpjb_flash()
{
    $flash = Wpjb_Project::getInstance()->placeHolder->_flash;

    if(!is_object($flash)) {
        return;
    }

    foreach($flash->getInfo() as $info):
    ?>
    <div class="wpjb-flash-info">
        <span class="wpjb-glyphs wpjb-icon-ok"><?php echo $info; ?></span>
    </div>
    <?php
    endforeach;

    foreach($flash->getError() as $error):
    ?>
    <div class="wpjb-flash-error">
        <span class="wpjb-glyphs wpjb-icon-attention"><?php echo $error; ?></span>
    </div>
    <?php
    endforeach;
    
    $flash->dispose();
    $flash->save();

}

function wpjb_date_display($format, $date, $relative = false) {
    
    $p = array(
        "format" => $format,
        "date" => $date,
        "relative" => $relative
    );
    
    extract(apply_filters("wpjb_date_display", $p));
    
    $time = time();
    $ptime = strtotime(date("Y-m-d H:i:s", $time))-strtotime(date("Y-m-d", $time));
    $ytime = strtotime("yesterday", $time)+$ptime+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
    $jtime = strtotime($date)+$ptime+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
    
    if($relative && date_i18n("Y-m-d", $time) == date_i18n("Y-m-d", $jtime)) {
        return __("Today", "wpjobboard");
    } elseif($relative && date_i18n("Y-m-d", $time) == date_i18n("Y-m-d", $ytime)) {
        return __("Yesterday", "wpjobboard");
    } else {
        return date_i18n($format, $jtime);
    }
}



function wpjb_time_ago($date, $format = "{time_ago}")
{
    if(!is_numeric($date)) {
        $date = strtotime($date);
    }
    
    echo str_replace(
        array("{time_ago}", "{date}"),
        array(
            daq_time_ago_in_words($date),
            date("Y-m-d")),
        $format
    );
}

function wpjb_job_features(Wpjb_Model_Job $job = null)
{
    if(!$job) {
       $job = wpjb_view("job"); 
    }
    
    if($job->is_featured) {
        echo " wpjb-featured";
    }
    if($job->is_filled) {
        echo " wpjb-filled";
    }
    if($job->isNew()) {
        echo " wpjb-new";
    }
    if($job->isFree()) {
        echo " wpjb-free";
    }
    if(isset($job->tag->type) && is_array($job->tag->type)) {
        foreach($job->tag->type as $type) {
            echo " wpjb-type-".$type->slug;
        }
    }
    if(isset($job->tag->category) && is_array($job->tag->category)) {
        foreach($job->tag->category as $category) {
            echo " wpjb-category-".$category->slug;
        }
    }
}

function wpjb_panel_features(Wpjb_Model_Job $job) 
{
    if($job->expired()) {
        echo " wpjb-expired";
    } elseif(time()-strtotime($job->job_expires_at) > 24*3600*3) {
        echo " wpjb-expiring";
    }
}

function wpjb_job_company(Wpjb_Model_Job $job = null)
{
    $company = esc_html($job->company_name);
    if(strlen($job->company_url) > 0) {
        $url = esc_html($job->company_url);
        /*echo '<a href="'.$url.'" class="wpjb-job-company">'.$company.'</a>';*/
        echo $company;
    } else {
        echo $company;
    }
}

function wpjb_job_company_profile($company, $text = null)
{
    /* @var $company Wpjb_Model_Company */

    if(!$company instanceof Wpjb_Model_Company) {
        return;
    }

    if(!$company->hasActiveProfile()) {
        return;
    }

    $link = wpjb_link_to("company", $company);

    if($text === null) {
        $text = __("view profile", "wpjobboard");
    }

    echo " - <a href=\"".esc_attr($link)."\">".esc_html($text)."</a>";

}

/* @deprecated */

function wpjb_job_category()
{
    $category = wpjb_view("job")->getCategory();
    $title = esc_html($category->title);
    echo '<a href="'.wpjb_link_to("category", $category).'">'.$title.'</a>';
}

function wpjb_job_type()
{
    $type = wpjb_view("job")->getType();
    $title = esc_html($type->title);
    echo '<a href="'.wpjb_link_to("type", $type).'">'.$title.'</a>';
}

function wpjb_job_created_time_ago($format, $job = null)
{
    if(!$job) {
        $job = wpjb_view("job");
    }
    
    echo str_replace(
        array("{time_ago}", "{date}"),
        array(
            daq_time_ago_in_words(strtotime($job->job_created_at)+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS)),
            date_i18n("Y-m-d", strtotime($job->job_created_at)+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS))),
        $format
    );
}

function wpjb_job_created_at($format, $job)
{
    $time = current_time("timestamp");
    $ytime = strtotime("yesterday", $time)+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
    $jtime = strtotime($job->job_created_at)+(get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);

    if(date_i18n("Y-m-d", $time) == date_i18n("Y-m-d", $jtime)) {
        return __("Today", "wpjobboard");
    } elseif(date_i18n("Y-m-d", $time) == date_i18n("Y-m-d", $ytime)) {
        return __("Yesterday", "wpjobboard");
    } else {
        return date_i18n($format, $jtime);
    }
}

function wpjb_field_value($field)
{
    if($field->getField()->type == 6) {
        $list = '<p><a><b><strong><em><i><ul><li><h3><h4><br>';
        echo nl2br(strip_tags($field->value, $list));
    } else {
        echo esc_html($field->value);
    }
}

function wpjb_resume_last_update_at($format, $resume)
{
    $t = strtotime($resume->updated_at);
    if($t <= strtotime("1970-01-01 00:00:00")) {
        echo __("never", "wpjobboard");
    } else {
        echo date_i18n($format, $t);
    }
}

function wpjb_resume_title()
{
    // @deprecated
    echo esc_html(wpjb_view("CV")->title);
}

function wpjb_resume_headline()
{
    // @deprecated
    echo esc_html(wpjb_view("CV")->headline);
}

function wpjb_resume_photo()
{
    $resume = wpjb_view("resume");
    /* @var $resume Wpjb_Model_Resume */

    $url = $resume->getImageUrl();
    if(is_null($url)) {
        $url = Wpjb_Project::getInstance()->media()."/user.png";
    }

    return $url;
}

function wpjb_resume_last_update($format, $object)
{
    if(strtotime($object->modified_at)) {
        $exchange = array(
            "{ago}" => daq_time_ago_in_words(strtotime($object->modified_at)),
            "{date}" => date_i18n("Y-m-d H:i:s", strtotime($object->modified_at))
        );
        return str_replace(array_keys($exchange), array_values($exchange), __("{ago} ago", "wpjobboard"));
    } else {
        return __("Never", "wpjobboard");
    }
}

function wpjb_form_helper_logo_upload(Daq_Form_Element $field, array $options = array())
{
    echo $options["tag"];
    
    $ext = Daq_Request::getInstance()->session("wpjb.job_logo_ext");
    if($ext) {
        /// some special treatment
        $path = get_bloginfo("url")."/wp-content/plugins/wpjobboard";
        $path.= Wpjb_List_Path::getRawPath("tmp_images")."/temp_".session_id().".".$ext;
        echo '<p class="wpjb-add-job-img"><img src="'.$path.'" alt="" /></p>';
    }
}

/* @deprecated end */


/*
* Job additional fields
 */




function wpjb_job_tracker()
{
    $url = wpjb_link_to("tracker", null, array("id"=>wpjb_view("job")->id));
    echo "<script type=\"text/javascript\" src=\"".$url."\"></script>";

}

/**
 * Add Job Form
 */

function wpjb_add_job_steps()
{
    $view = Wpjb_Project::getInstance()->getApplication("frontend")->getView();
    $view->render("step.php");
}

function wpjb_user_can_post_job()
{
    return (bool)wpjb_view("canPost");
}

function wpjb_form_render_hidden($form)
{
    /* @var $form Daq_Form_Abstract */
    echo $form->renderHidden();
}

function wpjb_form_render_input(Daq_Form_Abstract $form, Daq_Form_Element $input)
{   
    if($input->hasRenderer()) {
        $callback = $input->getRenderer();
        call_user_func($callback, $input, $form);
    } else {
        echo $input->render();
    }
}

function wpjb_form_input_features(Daq_Form_Element $e)
{
    $cl = array();
    if(count($e->getErrors())>0) {
        $cl[] = "wpjb-error";
    }
    
    $cl[] = "wpjb-element-".$e->getTypeTag();
    $cl[] = "wpjb-element-name-".$e->getName();
    
    echo join(" ", $cl);
}

function wpjb_form_input_hint(Daq_Form_Element $e, $tag = "small", $class = "wpjb-hint")
{
    $hint = $e->getHint();
    if(!empty($hint)) {
        $hint = esc_html($hint); 
        echo "<$tag class=\"$class\">$hint</$tag>";
    }
}

function wpjb_form_input_errors(Daq_Form_Element $e, $wrap1 = "ul", $wrap2 = "li")
{
    $err = $e->getErrors();

    if(count($err) == 0) {
        return null;
    }

    $html = "";
    if($wrap1) {
        $html .= "<".$wrap1." class=\"wpjb-errors\">";
    }
    foreach($err as $e) {
        if($wrap2) {
            $html .= "<$wrap2>".esc_html($e)."</$wrap2>";
        } else {
            $html .= esc_html($e);
        }
    }
    if($wrap1) {
        $html .= "</$wrap1>";
    }
    echo $html;
}

function wpjb_form_input_classes()
{
    $class = array();
    if(wpjb_form_input_errors()) {
        $class[] = "wpjb_error";
    }

    $input = wpjb_form_element();
    $class[] = "wpjb-".$input->getTypeTag();

    return join(" ", $class);
}

/**
 *
 * @return Wpjb_Model_Job
 */
function wpjb_job()
{
    return wpjb_view("job");
}

function wpjb_job_template()
{
    $view = Wpjb_Project::getInstance()->getApplication("frontend")->getView();
    $view->render("job.php");
}

// steps

function wpjb_render_step($num, $mark = "<strong>&raquo; {text}</strong>")
{
    $steps = array(
        array(esc_html(Wpjb_Project::getInstance()->conf("seo_step_1")), __("Create ad", "wpjobboard")),
        array(esc_html(Wpjb_Project::getInstance()->conf("seo_step_2")), __("Preview", "wpjobboard")),
        array(esc_html(Wpjb_Project::getInstance()->conf("seo_step_3")), __("Done!", "wpjobboard"))
    );

    $current = $steps[($num-1)];
    if(strlen($current[0])==0) {
        $current = $current[1];
    } else {
        $current = $current[0];
    }

    $currentStep = wpjb_view("current_step");
    
    if($currentStep == $num) {
        $title = str_replace("{text}", $current, $mark);
        echo $title;
        Wpjb_Project::getInstance()->title = $current;
    } else {
        echo $current;
    }
}

// resumes functions

function wpjr_link_to($key, $object = null, $param = array())
{
    $app = Wpjb_Project::getInstance()->getApplication("resumes");
    $router = $app->getRouter();
    $url = $app->getUrl();
    $link = $router->linkTo($key, $object, $param);
    $url = $url."/".$link;
    
    return apply_filters("wpjr_link_to", $url, array("key"=>$key, "object"=>$object, "param"=>$param));
}

function wpjb_block_resume_details()
{
    $basedir = basename(Wpjb_Project::getInstance()->getBaseDir());

    $img = new Daq_Helper_Html("img", array(
        "alt" => "lock",
        "src" => plugins_url("$basedir/public/images/icon-padlock.gif")
    ));
    
    $m  = $img->render()." ";
    $m .= __("<i>locked</i>", "wpjobboard");
    
    return $m;
}

function wpjb_resume_degree($resume)
{
    $d = Wpjb_Form_Admin_Resume::getDegrees();
    echo $d[$resume->degree];
}

function wpjb_resume_experience($resume)
{
    $d = Wpjb_Form_Admin_Resume::getExperience();
    echo $d[$resume->years_experience];
}

function wpjr_url()
{
    return Wpjb_Project::getInstance()->getApplication("resumes")->getUrl();
}

function is_wpjb()
{
    return is_page(Wpjb_Project::getInstance()->conf("link_jobs"));
}

function is_wpjr()
{
    return is_page(Wpjb_Project::getInstance()->conf("link_resumes"));
}

function wpjb_current_category_link($default = null)
{
    if(!wpjb_view("current_category")) {
        $url = Wpjb_Project::getInstance()->getUrl();
        $title = __("All Categories", "wpjobboard");
    } else {
        $current_category = wpjb_view("current_category");
        $url = wpjb_link_to("category", $current_category);
        $title = esc_html($current_category->title);
    }

    echo "<a href=\"$url\" class=\"wpjb-current-category\">$title</a>";
}

function wpjb_current_type_link()
{
    $url = Wpjb_Project::getInstance()->getUrl();
    $title = __("All Jobs", "wpjobboard");

    if(wpjb_view("current_type")) {
        $current_type = wpjb_view("current_type");
        $url = wpjb_link_to("type", $current_type);
        $title = esc_html($current_type->title);
    }

    echo "<a href=\"$url\" class=\"wpjb-current-jobtype\">$title</a>";
}

/**
 * Returns resume object
 *
 * @return Wpjb_Model_Resume
 */
function wpjb_resume()
{
    return wpjb_view("resume");
}

function wpjb_resume_status($resume)
{
    $object = $resume;

    if($object->is_approved < Wpjb_Model_Resume::RESUME_PENDING) {
        return __("None", "wpjobboard");
    } elseif($object->is_approved == Wpjb_Model_Resume::RESUME_PENDING) {
        return __("Pending approval", "wpjobboard");
    } elseif($object->is_approved == Wpjb_Model_Resume::RESUME_DECLINED) {
        return __("Declined (update your profile and submit it again)", "wpjobboard");
    } else {
        return __("Approved", "wpjobboard");
    }
}

/**
 * Returns employer object
 *
 * @return Wpjb_Model_Employer
 */



function wpjb_rich_text($text, $format = "text")
{
    if($format == "html") {
        $text = wpautop($text);
        $text = strip_tags($text, "<blockquote><ol><ul><li><p><a><span><em><strong><br><h1><h2><h3><h4><h5><h6><img><del><ins><code><pre><b><i>");
    } else {
        $text = nl2br(esc_html($text));
    }

    echo $text;
}


function wpjb_format_bytes($size) {
    $units = array(' bytes', ' kB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) {
        $size /= 1024;
    }
    return round($size, 2).$units[$i];
}

function wpjb_get_categories($options = null) {
    
    return Wpjb_Utility_Registry::getCategories();
}

function wpjb_get_jobtypes($options = null) {
    
    return Wpjb_Utility_Registry::getJobTypes();
}

/**
 * FORM HELPERS
 */


function wpjb_locale() {

    list($lang, $cc) = explode("_", get_locale());
    $country = Wpjb_List_Country::getAll();
    if(isset($country[$cc])) {
        $r = $country[$cc]["code"];
    } else {
        $r = 840;
    }
    
    $r = apply_filters("wpjb_locale", $r);
    
    return $r;
}

function wpjb_recaptcha_form() {
    
    if(!function_exists("recaptcha_get_html")) {
        $rc = "/application/vendor/recaptcha/recaptchalib.php";
        $rc = Wpjb_Project::getInstance()->getBaseDir().$rc;
        require_once $rc;
    }
    
    $captchaType = wpjb_conf("front_recaptcha_type", "re-captcha");
    
    if($captchaType == "re-captcha") {
        echo '<style type="text/css">#recaptcha_widget_div div { padding: 0px; margin: 0px }</style>';
        echo recaptcha_get_html(Wpjb_Project::getInstance()->getConfig("front_recaptcha_public"), null, is_ssl());
    } else {
        $key = Wpjb_Project::getInstance()->getConfig("front_recaptcha_public");
        $html = new Daq_Helper_Html("div", array(
            "class" => "g-recaptcha",
            "data-sitekey" => wpjb_conf("front_recaptcha_public"),
            "data-theme" => wpjb_conf("front_recaptcha_theme", "light"),
            "data-type" => wpjb_conf("front_recaptcha_media", "image"),
        ));
        $html->forceLongClosing(true);
        
        echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
        echo $html->render();
        echo '<input type="hidden" name="recaptcha_response_field" value="1" />';
    }

}

function wpjb_recaptcha_check() {
    
    if(wpjb_conf("front_recaptcha_type", "re-captcha") == "re-captcha") {
    
        if(!function_exists("recaptcha_get_html")) {
            $rc = "/application/vendor/recaptcha/recaptchalib.php";
            include_once Wpjb_Project::getInstance()->getBaseDir().$rc;
        }

        $recaptcha_challenge_field = null;
        if(isset($_POST["recaptcha_challenge_field"])) {
            $recaptcha_challenge_field = $_POST["recaptcha_challenge_field"];
        }

        $recaptcha_response_field = null;
        if(isset($_POST["recaptcha_response_field"])) {
            $recaptcha_response_field = $_POST["recaptcha_response_field"];
        }

        $resp = recaptcha_check_answer (
            Wpjb_Project::getInstance()->getConfig("front_recaptcha_private"),
            $_SERVER["REMOTE_ADDR"],
            $recaptcha_challenge_field,
            $recaptcha_response_field
        );

        if (!$resp->is_valid) {
            return $resp->error;
        } else {
            return true;
        }
        
    } else {
        
        $query = array(
            "secret" => wpjb_conf("front_recaptcha_private"),
            "response" => $_POST["g-recaptcha-response"],
            "remoteip" => $_SERVER["REMOTE_ADDR"]
        );
        
        $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?".http_build_query($query));
        
        if(is_wp_error($response)) {
            return $response->get_error_message();
        } 
        
        $data = json_decode($response["body"]);
        
        if($data->success) {
            return true;
        }
        
        $ec = 'error-codes';
        
        $errors = array(
            "missing-input-secret" => __("The secret parameter is missing.", "wpjobboard"),
            "invalid-input-secret" => __("The secret parameter is invalid or malformed.", "wpjobboard"),
            "missing-input-response" => __("The response parameter is missing.", "wpjobboard"),
            "invalid-input-response" => __("The response parameter is invalid or malformed.", "wpjobboard"),
        );
        
        foreach($errors as $key => $err) {
            if(in_array($key, $data->$ec)) {
                return $err; 
            }
        }
        
        return null;
    }

}

function wpjb_form_helper_listing(Daq_Form_Element $field, $form)
{
    $group_titles = array();
    $groups = array(
        Wpjb_Model_Pricing::PRICE_EMPLOYER_MEMBERSHIP => array("item"=>array(), "title"=>__("Purchased Membership", "wpjobboard")),
        Wpjb_Model_Pricing::PRICE_SINGLE_JOB => array("item"=>array(), "title"=>__("Single Job Posting", "wpjobboard")),
        
    );
    foreach($field->getOptions() as $o) {
        list($price_for, $package_id, $id) = explode("_", $o["value"]);
        
        $groups[$price_for]["item"][] = $o;
        $group_titles[$price_for] = 1;
    }
    
    $group_titles = array_sum($group_titles)>1 ? true : false;
    
    foreach($groups as $k => $group) {
        
        if($group_titles && !empty($group["item"])) {
            echo "<em class=wpjb-listing-group>".$group["title"]."</em>";
        }
        
        foreach($group["item"] as $option) {

            $lid = $option["value"];
            
            list($price_for, $membership_id, $id) = explode("_", $lid);
            
            if($membership_id > 0) {
                $membership = new Wpjb_Model_Membership($membership_id);
                $usage = $membership->package();
                $usage = $usage[Wpjb_Model_Pricing::PRICE_SINGLE_JOB];
                foreach($usage as $k => $use) {

                    if($k == $id) {
                        $u = $use;
                    }
                    
                    if($k == $id && $use["status"] == "limited") {
                        $credits = $use["usage"]-$use["used"];
                        break;
                    }
                }
            } else {
                $membership = null;
            }
            
            $l = new Wpjb_Model_Pricing($id);
        ?>
            <label class="wpjb-listing-type-item" for="listing_<?php echo $lid ?>">
                <input name="listing" class="wpjb-listing-type-input" id="listing_<?php echo $lid ?>" type="radio" value="<?php echo $lid ?>" <?php checked($field->getValue()==$lid) ?> <?php //disabled($credits<1) ?> />
                <span class="wpjb-listing-type-item-s1"><?php esc_html_e($option["desc"]) ?></span>
                <span class="wpjb-listing-type-item-s2">
                    <?php if($membership && $u["status"] == "limited"): ?>
                    <?php printf(_n("(1 posting left)", "(%d postings left)", $credits, "wpjobboard"), $credits) ?>
                    <?php elseif(!$membership): ?>
                    <?php esc_html_e(wpjb_price($l->price, $l->currency)) ?>
                    <?php endif; ?>
                </span>
                <span class="wpjb-listing-type-item-s3">
                    <?php $visible = (int)$l->meta->visible->value(); ?>
                    <?php $m = sprintf(_n('visible for 1 day', 'visible for %d days', $visible, "wpjobboard"), $visible) ?>
                    <?php esc_html_e($m); ?>
                </span>
                <script type="text/javascript">
                    if(typeof WpjbListing === 'undefined') {
                        WpjbListing = {};
                    }
                    <?php if($membership): ?>
                    WpjbListing.id<?php echo $lid ?> = {
                      price: "<?php echo esc_js(wpjb_price(0, $l->currency)) ?>",
                      value: <?php echo esc_js(0) ?>,
                      id: <?php echo $l->id ?>,
                      membership: true
                    };
                    <?php else: ?>
                    WpjbListing.id<?php echo $lid ?> = {
                      price: "<?php echo esc_js(wpjb_price($l->price, $l->currency)) ?>",
                      value: <?php echo esc_js($l->price) ?>,
                      id: <?php echo $l->id ?>,
                      membership: false
                    };
                    <?php endif; ?>
                </script>
            </label>    

        <?php 

        }
    }
}


function wpjb_form_helper_membership(Daq_Form_Element $field, $form)
{
    $group_titles = array();
    $groups = array(
        Wpjb_Model_Pricing::PRICE_EMPLOYER_MEMBERSHIP => array("item"=>array(), "title"=>__("Purchased Membership", "wpjobboard")),
        Wpjb_Model_Pricing::PRICE_SINGLE_JOB => array("item"=>array(), "title"=>__("Single Job Posting", "wpjobboard")),
        
    );
    foreach($field->getOptions() as $o) {
        list($price_for, $package_id, $id) = explode("_", $o["value"]);
        
        $groups[$price_for]["item"][] = $o;
        $group_titles[$price_for] = 1;
    }
    
    $group_titles = array_sum($group_titles)>1 ? true : false;
    
    foreach($groups as $k => $group) {
        
        if($group_titles && !empty($group["item"])) {
            echo "<em class=wpjb-listing-group>".$group["title"]."</em>";
        }
        
        foreach($group["item"] as $option) {

            $lid = $option["value"];
            
            list($price_for, $membership_id, $id) = explode("_", $lid);
            
            if($membership_id > 0) {
                $membership = new Wpjb_Model_Membership($membership_id);
                $usage = $membership->package();
                $usage = $usage[Wpjb_Model_Pricing::PRICE_SINGLE_JOB];
                foreach($usage as $k => $use) {

                    if($k == $id) {
                        $u = $use;
                    }
                    
                    if($k == $id && $use["status"] == "limited") {
                        $credits = $use["usage"]-$use["used"];
                        break;
                    }
                }
            } else {
                $membership = null;
            }
            
            $l = new Wpjb_Model_Pricing($id);
        ?>
            <label class="wpjb-listing-type-item" for="listing_<?php echo $lid ?>">
                <input name="listing" class="wpjb-listing-type-input" id="listing_<?php echo $lid ?>" type="radio" value="<?php echo $lid ?>" <?php checked($field->getValue()==$lid) ?> <?php //disabled($credits<1) ?> />
                <span class="wpjb-listing-type-item-s1"><?php esc_html_e($option["desc"]) ?></span>
                <span class="wpjb-listing-type-item-s2">
                    <?php if($membership && $u["status"] == "limited"): ?>
                    <?php printf(_n("(1 posting left)", "(%d postings left)", $credits, "wpjobboard"), $credits) ?>
                    <?php elseif(!$membership): ?>
                    <?php esc_html_e(wpjb_price($l->price, $l->currency)) ?>
                    <?php endif; ?>
                </span>
                <script type="text/javascript">
                    if (typeof ajaxurl === 'undefined') {
                        ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
                    }
                    if(typeof WpjbListing === 'undefined') {
                        WpjbListing = {};
                    }
                    <?php if($membership): ?>
                    WpjbListing.id<?php echo $lid ?> = {
                      price: "<?php echo esc_js(wpjb_price(0, $l->currency)) ?>",
                      value: <?php echo esc_js(0) ?>,
                      id: <?php echo $l->id ?>,
                      membership: true
                    };
                    <?php else: ?>
                    WpjbListing.id<?php echo $lid ?> = {
                      price: "<?php echo esc_js(wpjb_price($l->price, $l->currency)) ?>",
                      value: <?php echo esc_js($l->price) ?>,
                      id: <?php echo $l->id ?>,
                      membership: false
                    };
                    <?php endif; ?>
                </script>
            </label>    

        <?php 

        }
    }
}

function wpjb_form_helper_resume_listing(Daq_Form_Element $field, $form)
{
    $group_titles = array();
    $groups = array(
        Wpjb_Model_Pricing::PRICE_EMPLOYER_MEMBERSHIP => array("item"=>array(), "title"=>__("Purchased Membership", "wpjobboard")),
        Wpjb_Model_Pricing::PRICE_SINGLE_RESUME => array("item"=>array(), "title"=>__("Single CV Access", "wpjobboard")),
        
    );
    foreach($field->getOptions() as $o) {
        list($price_for, $package_id, $id) = explode("_", $o["value"]);
        
        $groups[$price_for]["item"][] = $o;
        $group_titles[$price_for] = 1;
    }
    
    $group_titles = array_sum($group_titles)>1 ? true : false;
    
    foreach($groups as $k => $group) {
        
        if($group_titles && !empty($group["item"])) {
            echo "<em class=wpjb-listing-group>".$group["title"]."</em>";
        }
        
        foreach($group["item"] as $option) {

            $lid = $option["value"];
            
            list($price_for, $membership_id, $id) = explode("_", $lid);
            
            if($membership_id > 0) {
                $membership = new Wpjb_Model_Membership($membership_id);
                $usage = $membership->package();
                $usage = $usage[Wpjb_Model_Pricing::PRICE_SINGLE_RESUME];
                foreach($usage as $k => $use) {

                    if($k == $id) {
                        $u = $use;
                    }
                    
                    if($k == $id && $use["status"] == "limited") {
                        $credits = $use["usage"]-$use["used"];
                        break;
                    }
                }
            } else {
                $membership = null;
            }
            
            $l = new Wpjb_Model_Pricing($id);
        ?>
            <label class="wpjb-listing-type-item" for="listing_<?php echo $lid ?>">
                <input name="<?php esc_attr_e($field->getName()) ?>" class="wpjr-listing-type-input <?php if(!$membership && $l->price>0): ?>wpjr-payment-required<?php endif; ?>" id="listing_<?php echo $lid ?>" type="radio" value="<?php echo $lid ?>" <?php checked($field->getValue()==$lid) ?> <?php //disabled($credits<1) ?> />
                <span class="wpjb-listing-type-item-s1"><?php esc_html_e($option["desc"]) ?></span>
                <span class="wpjb-listing-type-item-s2">
                    <?php if($membership && $u["status"] == "limited"): ?>
                    <?php printf(_n("(1 CV left)", "(%d CV left)", $credits, "wpjobboard"), $credits) ?>
                    <?php elseif(!$membership): ?>
                    <?php esc_html_e(wpjb_price($l->price, $l->currency)) ?>
                    <?php endif; ?>
                </span>
            </label>    

        <?php 

        }
    }
}

function wpjb_mobile_notification_jobs(Wpjb_Model_Job $job) {
    
    $googleApiKey = wpjb_conf("google_api_key");
    $ids = array();
    echo $googleApiKey;
    $query = new Daq_Db_Query;
    $query->from("Wpjb_Model_Alert t");
    $query->where("user_id IS NOT NULL");
    $query->where("frequency = 0");
    
    $list = $query->execute();
    
    foreach($list as $alert) {
        
        $params = unserialize($alert->params);
        $params["query"] = $params["keyword"];
        $params["id"] = $job->id;
        $params["count_only"] = true;
        
        $jobs = wpjb_find_jobs($params);

        if($jobs == 1) {
            
            $mobile = get_user_meta($alert->user_id, "wpjb_mobile_device", true);
            
            foreach($mobile->device as $device) {
                if($device["mobile_os"] == "android") {
                    
                    $ids[] = $device["mobile_id"];
                    
                    $alert->last_run = date_i18n("Y-m-d H:i:s");
                    $alert->save();
                } // endif;
            } // endforeach;
        } // endif;
    }

    if(empty($ids)) {
        return;
    }

    // prep the bundle
    $msg = array (
        'message' 	=> 'here is a message. message',
        'title'		=> 'This is a title. title',
        'subtitle'	=> 'This is a subtitle. subtitle',
        'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
        'vibrate'	=> 0,
        'sound'		=> 0,
        'largeIcon'	=> 'large_icon',
        'smallIcon'	=> 'small_icon'
    );

    $fields = array(
        'registration_ids' 	=> $ids,
        'data'			=> $msg
    );

    $headers = array(
        'Authorization' => 'key=' . $googleApiKey,
        'Content-Type' => 'application/json'
    );

    $response = wp_remote_post('https://android.googleapis.com/gcm/send', array(
        "headers" => $headers,
        "body" => json_encode($fields)
        
    ));
    
    if ( is_wp_error( $response ) ) {
       $error_message = $response->get_error_message();
       echo "Something went wrong: $error_message";
    } else {
       echo 'Response:<pre>';
       print_r( $response );
       echo '</pre>';
    }
    
    
    return;
    
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );

    echo $result;
}

?>