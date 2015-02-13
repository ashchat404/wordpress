<?php
/**
 * Description of Plain
 *
 * @author greg
 * @package 
 */

class Wpjb_Module_Frontend_Plain extends Wpjb_Controller_Frontend
{
    public function notifyAction()
    {
        $payment = $this->getObject();
        /* @var $payment Wpjb_Model_Payment */
        
        $payment->made_at = date("Y-m-d H:i:s");
        $paypal = Wpjb_Project::getInstance()->payment->factory($payment);
        try {
            $paypal->processTransaction($this->getRequest()->getAll());

            $payment->accepted();

            $payment->payment_paid = $this->getRequest()->post("mc_gross");
            $payment->external_id = $this->getRequest()->post("txn_id");
            $payment->is_valid = 1;
            $payment->save();

        } catch(Exception $e) {
            $payment->message = $e->getMessage();
            $payment->is_valid = 0;
            $payment->save();
        }

        exit;
        return false;
    }

    private function _open($tag, array $param = null)
    {
        $list = "";
        if(is_array($param)) {
            $list = array();
            foreach($param as $k => $v) {
                $list[] = $k."=\"".esc_html($v)."\"";
            }
            $list = " ".join(" ", $list);
        }
        echo "<".$tag.$list.">";
    }

    private function _close($tag)
    {
        echo "</".$tag.">";
    }


    private function _xmlEntities($text, $charset = 'UTF-8')
    {
        return esc_html($text);
    }
    
    private function _tagIf($tag, $content, array $param = null)
    {
        if(strlen($content)>0) {
            $this->_tag($tag, $content, $param);
        }
    }

    private function _tag($tag, $content, array $param = null)
    {
        $this->_open($tag, $param);
        echo $this->_xmlEntities($content);
        $this->_close($tag);
    }

    private function _tagCIf($tag, $content, array $param = null)
    {
        if(!empty($content)) {
            $this->_tagC($tag, $content, $param);
        }
    }

    private function _tagC($tag, $content, array $param = null)
    {
        $this->_open($tag, $param);
        echo "<![CDATA[".$content."]]>";
        $this->_close($tag);
    }
    
    public function apiAction()
    {
        if(!$this->hasParam("engine")) {
            return false;
        }
        
        $engine = $this->getRequest()->getParam("engine");

        switch($engine) {
            case "indeed": $this->_indeed(); break;
            case "trovit": $this->_trovit(); break;
            case "simply-hired": $this->_simplyHired(); break;
            case "google-base": $this->_googleBase(); break;
            case "juju": $this->_juju(); break;
        }

        exit;
        return false;
    }

    private function _jobs()
    {
        return Wpjb_Model_Job::activeSelect();
    }

    private function _indeed()
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
        $url = Wpjb_Project::getInstance()->getUrl();
        $this->_open("source");
        $this->_tag("publisher", Wpjb_Project::getInstance()->conf("seo_job_board_title"));
        $this->_tag("publisherurl", $url);
        $this->_tag("lastBuildDate", date(DATE_RSS));

        $jobs = wpjb_find_jobs(array(
            "filter" => "active",
            "ids_only" => true
        ));

        foreach($jobs->job as $id) {
            $job = new Wpjb_Model_Job($id);
            $ct = Wpjb_List_Country::getByCode($job->job_country);

            $this->_open("job");
            $this->_tagC("title", $job->job_title);
            $this->_tagC("date", $job->job_created_at);
            $this->_tagC("referencenumber", $job->id);
            $this->_tagC("url", wpjb_link_to("job", $job));
            $this->_tagC("company", $job->company_name);
            $this->_tagC("city",  $job->job_city);
            $this->_tagC("state",  $job->job_state);
            $this->_tagC("country",  $ct['iso2']);
            $this->_tagC("description", strip_tags($job->job_description));
            $this->_tagC("type", $job->getTag()->category[0]->title);
            $this->_tagC("category", $job->getTag()->type[0]->title);
            $this->_close("job");
        }
        $this->_close("source");
    }

    private function _trovit()
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
        $this->_open("trovit");

        $param = array(
            "filter" => "active",
            "ids_only" => true
        );
        
        if($this->getRequest()->getParam("country")) {
            $c = strtoupper($this->getRequest()->getParam("country"));
            $country = Wpjb_List_Country::getByAlpha2($c);
            if($country) {
                $param["country"] = $country["code"];
            }
        }
        
        $jobs = wpjb_find_jobs($param);

        foreach($jobs->job as $id) {
            $job = new Wpjb_Model_Job($id);
            $ct = Wpjb_List_Country::getByCode($job->job_country);

            $this->_open("ad");
            
            $this->_tagC("id", $job->id);
            $this->_tagC("title", $job->job_title);
            $this->_tagC("content", strip_tags($job->job_description));
            $this->_tagC("url", wpjb_link_to("job", $job));
            $this->_tagCIf("company", $job->company_name);
            $this->_tagCIf("category", $job->getTag()->category->title);
            $this->_tagCIf("contract", $job->getTag()->type->title);
            $this->_tagCIf("working_hours", $job->getTag()->type->title);
            $this->_tagCIf("city",  $job->job_city);
            $this->_tagCIf("region",  $job->job_state);
            $this->_tagCIf("postcode",  $job->job_zip_code);
            $this->_tagC("date", date("Y/m/d", $job->time->job_created_at));
            $this->_tagCIf("expiration_date", date("Y/m/d", $job->time->job_expires_at));
            

            
            $this->_close("ad");
        }
        $this->_close("trovit");
    }
    
    private function _simplyHired()
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        $url = Wpjb_Project::getInstance()->getUrl();
        $router = Wpjb_Project::getInstance()->router();
        $this->_open("jobs");
        
        $jobs = wpjb_find_jobs(array(
            "filter" => "active",
            "ids_only" => true
        ));
        
        foreach($jobs->job as $id) {
            $job = new Wpjb_Model_Job($id);
            $ct = Wpjb_List_Country::getByCode($job->job_country);
            $addr = array(
                $job->job_city,
                $job->job_state,
                $job->job_zip_code
            );

            $this->_open("job");
            $this->_tag("title", $job->job_title);
            $this->_tag("detail-url", wpjb_link_to("job", $job));
            $this->_tag("job-code", $job->id);
            $this->_tag("posted-date", $job->job_created_at);
            $this->_open("description");
            $this->_tagC("summary", strip_tags($job->job_description));
            $this->_close("description");
            $this->_open("location");
            $this->_tag("address", join(", ", $addr));
            $this->_tag("state", $job->job_state);
            $this->_tagIf("city", $job->job_city);
            $this->_tagIf("zip", $job->job_zip_code);
            $this->_tagIf("country", $ct['iso2']);
            $this->_close("location");
            $this->_open("company");
            $this->_tag("name", $job->company_name);
            $this->_tagIf("url", $job->company_url);
            $this->_close("company");
            $this->_close("job");
        }

        $this->_close("jobs");
    }

    private function _googleBase()
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        echo '<rss version ="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $url = Wpjb_Project::getInstance()->getUrl();
        $router = Wpjb_Project::getInstance()->router();
        
        $this->_open("channel");
        $this->_tag("description", Wpjb_Project::getInstance()->conf("seo_job_board_title"));
        $this->_tag("link", $url);

        $jobs = wpjb_find_jobs(array(
            "filter" => "active",
            "ids_only" => true
        ));
        foreach($jobs->job as $id) {
            $job = new Wpjb_Model_Job($id);
            $this->_open("item");
            $this->_tag("g:location", $job->job_city);
            $this->_tag("title", $job->job_title);
            $this->_tagC("description", strip_tags($job->job_description));
            $this->_tag("link", $url."/".$router->linkTo("job", $job));
            $this->_tag("g:publish_date", $job->job_created_at);
            $this->_tag("g:employer", $job->company_name);
            $this->_tag("guid", wpjb_link_to("job", $job));
            $this->_close("item");
        }
        $this->_close("channel");
        $this->_close("rss");
    }

    private function _juju()
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        echo '<positionfeed
            xmlns="http://www.job-search-engine.com/employers/positionfeed-namespace/"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.job-search-engine.com/employers/positionfeed-namespace/ http://www.job-search-engine.com/employers/positionfeed.xsd" version="2006-04">';

        $url = Wpjb_Project::getInstance()->getUrl();
        $router = Wpjb_Project::getInstance()->router();

        $this->_tag("source", Wpjb_Project::getInstance()->conf("seo_job_board_title"));
        $this->_tag("sourcurl", $url);
        $this->_tag("feeddate", date(DATE_ISO8601));

        $jobs = wpjb_find_jobs(array(
            "filter" => "active",
            "ids_only" => true
        ));
        foreach($jobs->job as $id) {
            $job = new Wpjb_Model_Job($id);
            $code = Wpjb_List_Country::getByCode($job->job_country);
            $code = $code['iso2'];
            $this->_open("job", array("id"=>$job->id));
            $this->_tag("employer", $job->company_name);
            $this->_tag("title", $job->job_title);
            $this->_tagC("description", strip_tags($job->job_description));
            $this->_tag("postingdate", date(DATE_ISO8601, $job->job_created_at));
            $this->_tag("joburl", wpjb_link_to("job", $job));
            $this->_open("location");
            $this->_tag("nation", $code);
            $this->_tagIf("state", $job->job_state);
            $this->_tagIf("zip", $job->job_zip_code);
            $this->_tagIf("city", $job->job_city);
            $this->_close("location");
            $this->_close("job");
        }
        $this->_close("positionfeed");

    }

    private function _esc($text)
    {
        return esc_html(ent2ncr($text));
    }

    protected function _resolve($str, $type)
    {
        $c = new Daq_Db_Query;
        $c->select("*");
        $c->from("Wpjb_Model_Tag t");
        $c->where("t.type = ?", $type);
        $c = $c->execute();

        $cl = array();
        foreach($c as $t) {
            $cl[$t->slug] = $t->id;
        }

        if(is_array($str)) {
            $strArr = $str;
        } elseif(!empty($str)) {
            $strArr = explode(",", $str);
        } else {
            $strArr = array();
        }
        
        $category = array();
        foreach($strArr as $c) {
            $c = trim($c);
            if(isset($cl[$c])) {
                $category[] = $cl[$c];
            } elseif(is_numeric($c)) {
                $category[] = $c;
            }
        }
        
        if(empty($category)) {
            return null;
        } else {
            return $category;
        }
    }
    
    public function rssAction()
    {
        $request = $this->getRequest();
        
        $query = $request->get("query", "all");
        $category = $request->get("category", "all");
        $type = $request->get("type", "all");
        
        if(empty($query) || $query == "all") {
            $query = "";
        } 
        
        if(empty($category) || $category == "all") {
            $category = null;
        } else {
            $category = $this->_resolve($category, "category");
        }
        
        if(empty($type) || $type == "all") {
            $type = null;
        } else {
            $type = $this->_resolve($type, "type");
        }
        
        $param = array(
            "filter" => "active",
            "query" => $query,
            "category" => $category,
            "type" => $type,
            "posted" => $request->get("posted"),
            "country" => $request->get("country"),
            "location" => $request->get("location"),
            "is_featured" => $request->get("is_featured"),
            "employer_id" => $request->get("employer_id"),
            "field" => $request->get("field", array()),
            "sort" => $request->get("sort"),
            "order" => $request->get("order"),
            "ids_only" => true
        );
        
        $search = Wpjb_Model_JobSearch::search($param);
        $this->_feed($search->job);
    }
    
    public function feedAction()
    {
        $category = $this->getRequest()->get("slug", "all");

        $param = array(
            "filter" => "active",
            "ids_only" => true
        );
        
        if($category != "all") {
            $param["category"] = $this->_resolve($category, "category");
        } 

        $this->_feed(wpjb_find_jobs($param)->job);
    }
    
    protected function _feed($result)
    {
        header("Content-type: application/xml");

        $site_title = wpjb_conf("seo_job_board_title", get_bloginfo("name"));
        
        $rss = new DOMDocument();
        $rss->formatOutput = true;

        $wraper = $rss->createElement("rss");
        $wraper->setAttribute("version", "2.0");
        $wraper->setAttribute('xmlns:atom', "http://www.w3.org/2005/Atom");

        $channel = $rss->createElement("channel");

        $title = $rss->createElement("title", $this->_esc($site_title));
        $channel->appendChild($title);
        $link = $rss->createElement("link", $this->_esc(Wpjb_Project::getInstance()->getUrl()));
        $channel->appendChild($link);
        $description = $rss->createElement("description", $this->_esc($site_title));
        $channel->appendChild($description);

        foreach($result as $id) {
            $job = new Wpjb_Model_Job($id);
            $desc = strip_tags($job->job_description);
            $desc = iconv("UTF-8", "UTF-8//IGNORE", substr($desc, 0));
            $desc = htmlspecialchars($desc, ENT_COMPAT, 'UTF-8');
            $description = $rss->createCDATASection($desc);
            $desc = $rss->createElement("description");
            $desc->appendChild($description);

            $i_id = $rss->createCDATASection($job->id);
            $j_id = $rss->createElement("id");
            $j_id->appendChild($i_id);

            $title = $rss->createCDATASection($job->job_title);
            $j_title = $rss->createElement("title");
            $j_title->appendChild($title);

            $location = $rss->createCDATASection($job->locationToString());
            $j_location = $rss->createElement("location");
            $j_location->appendChild($location);

            $AdvertiserName = $rss->createCDATASection($job->company_name);
            $j_AdvertiserName = $rss->createElement("AdvertiserName");
            $j_AdvertiserName->appendChild($AdvertiserName);

            $salary = $rss->createCDATASection($job->meta->salary->value());
            $j_salary = $rss->createElement("salary");
            $j_salary->appendChild($salary);

            foreach ($job->getTag()->category as $category){
                $ind = $category->title;
            }

            $industry = $rss->createCDATASection($ind);
            $j_industry = $rss->createElement("industry");
            $j_industry->appendChild($industry);



            $link = Wpjb_Project::getInstance()->getUrl()."/";
            $link.= Wpjb_Project::getInstance()->router()->linkTo("job", $job);
            $pubDate = date(DATE_RSS, strtotime($job->job_created_at));
            $ats_link = $this->_esc($job->meta->application_url->value());


            if(!$job->meta->salary->value()){

            }else{
                $item = $rss->createElement("job");
                $item->appendChild($j_id);
                $item->appendChild($j_title);
                $item->appendChild($j_location);
                $item->appendChild($j_AdvertiserName);

                if(!empty($ats_link)){
                    $AdvertiserATS = $rss->createCDATASection($ats_link);
                    $j_AdvertiserATS = $rss->createElement("AdvertiserATS");
                    $j_AdvertiserATS->appendChild($AdvertiserATS);
                    $item->appendChild($j_AdvertiserATS);
                }else{

                    $AdvertiserEmail = $rss->createCDATASection($job->company_email);
                    $j_AdvertiserEmail = $rss->createElement("AdvertiserEmail");
                    $j_AdvertiserEmail->appendChild($AdvertiserEmail);
                    $item->appendChild($j_AdvertiserEmail);
                }

                $item->appendChild($j_salary);
                $item->appendChild($j_industry);
                

                $l_link = $rss->createCDATASection($this->_esc($link));
                $j_link = $rss->createElement("link");
                $j_link->appendChild($l_link);
                $item->appendChild($j_link);

                $item->appendChild($desc);

                $pubDate = $rss->createCDATASection($pubDate);
                $j_pubDate = $rss->createElement("pubDate");
                $j_pubDate->appendChild($pubDate);
                $item->appendChild($j_pubDate);

                $guid = $rss->createCDATASection($this->_esc($link));
                $j_guid = $rss->createElement("guid");
                $j_guid->appendChild($guid);
                $item->appendChild($j_guid);

                $channel->appendChild($item);
            }

        }

        $wraper->appendChild($channel);
        $rss->appendChild($wraper);

        print $rss->saveXML();

        exit;
        return false;

    }

    public function discountAction()
    {
        $code = $this->getRequest()->post("code");

        $validator = new Wpjb_Validate_Coupon();
        if($validator->isValid($code)) {
            $query = new Daq_Db_Query();
            $result = $query->select()->from("Wpjb_Model_Discount t")
                ->where("code = ?", $code)
                ->limit(1)
                ->execute();
            print json_encode($result[0]->toArray());
        } else {
            $class = new stdClass();
            $err = $validator->getErrors();
            $class->isError = true;
            $class->error = $err[0];
            print json_encode($class);
        }

        exit;
        return false;
    }

    public function trackerAction()
    {
        $job = $this->getObject();
        if(!$job->is_active) {
            return false;
        }

        $job->stat_views++;

        $id = $job->getId();
        if(!isset($_COOKIE['wpjb'][$id])) {
            $job->stat_unique++;
        }
        
        $job->save();

        $find = array("https://www.", "https://", "http://www.", "http://");
        $domain = get_bloginfo("url");
        $domain = str_replace($find, "", $domain);

        setcookie("wpjb[$id]", time(), time()+(3600*24*30), "/", $domain);

        echo "var WpjbTracker = {};";

        exit;
        return false;
    }
    

}

?>
