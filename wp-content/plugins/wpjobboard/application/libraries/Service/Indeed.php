<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Indeed
 *
 * @author Grzegorz
 */

class Wpjb_Service_Indeed 
{
    public function prepare($item, $import)
    {
        $country = Wpjb_List_Country::getByAlpha2((string)$item->country);
        $date = date("Y-m-d H:i:s", wpjb_time((string)$item->date));

        $result = new stdClass();
        $result->company_name = (string)$item->company;
        $result->company_url = (string)$item->url;
        $result->company_email = "";
        $result->job_title = (string)$item->jobtitle;
        $result->job_description = (string)$item->snippet;
        $result->job_country = $country["code"];
        $result->job_state = (string)$item->state;
        $result->job_zip_code = "";
        $result->job_city = (string)$item->city;
        $result->job_created_at = date("Y-m-d", wpjb_time($date));
        $result->job_expires_at = date("Y-m-d", wpjb_time("$date +30 day"));
        $result->is_active = 1;
        $result->is_approved = 1;
        
        $t1 = new stdClass();
        $t1->type = "type";
        $t1->title = "Full Time";
        $t1->slug = "full-time";
        
        $t2 = new stdClass();
        $t2->type = "category";
        $t2->id = $import->category_id;
        
        $result->tags = new stdClass();
        $result->tags->tag = array($t1, $t2);
        
        $m1 = new stdClass();
        $m1->name = "job_description_format";
        $m1->value = "html";
        
        $m2 = new stdClass();
        $m2->name = "job_source";
        $m2->value = $import->engine."-".(string)$item->jobkey;
        
        $result->metas = new stdClass();
        $result->metas->meta = array($m1, $m2);
        
        return $result;
    }
    
    public function find($param = array()) 
    {
        $result = new stdClass();
        $result->item = array();
        
        $posted = $param["posted"];
        $country = $param["country"];
        $location = $param["location"];
        $keyword = $param["keyword"];
        $max = $param["add_max"];

        $publisher = wpjb_conf("indeed_publisher");
        
        $url = "http://api.indeed.com/ads/apisearch?publisher=";
        $url.= $publisher."&co=".$country."&limit=";
        $url.= $max."&l=".urlencode($location)."&fromage=".$posted;
        $url.= "&q=".urlencode($keyword);
        $url.= "&v=2";

        $response = wp_remote_get($url);
        
        if($response instanceof WP_Error) {
            return $result;
        }
        
        $xml = new SimpleXMLElement($response["body"]);
        $keys = array();

        foreach($xml->results->result as $r) {
            $keys[] = (string)$r->jobkey;
        }
        
        $keys = join(",", $keys);
        
        $url = "http://api.indeed.com/ads/apigetjobs?publisher=$publisher&jobkeys=".$keys."&v=2";
        $response = wp_remote_get($url);
        
        if($response instanceof WP_Error) {
            return $result;
        }
        
        $xml = new SimpleXMLElement($response["body"]);

        foreach($xml->results->result as $r) {
            $r = (object)$r;
            $r->external_id = (string)$r->jobkey;
            $result->item[] = $r;
        }

        return $result;
    
    }
}

?>
