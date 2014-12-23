<?php
/**
Plugin Name: [WPJB] Broadbean Integration
Version: 1.0
Author: Mark Winiarski, Greg Winiarski
Description: WPJobBoard - Broadbean integration. This plugin allows to automatically publish jobs from Broadbean to WPJobBoard. See <a href="http://api.adcourier.com/docs/index.cgi?page=jobboards_overview">integration docs.
*/

add_action( 'wp_ajax_nopriv_wpjb_broadbean', 'wpjb_broadbean_add_job' );
function wpjb_broadbean_add_job() 
{
    $post = file_get_contents('php://input');
	$xml = simplexml_load_string($post);
	$id = wpjbm_isEmployer($xml->username);
	
	if($id) {
		
		if($xml->command == "add") {
			$arrayMap = wpjbm_getArrayMap();
			
			$job = new stdClass();
			$job->employer_id = $id;
			$job->job_created_at = null;
			foreach($xml as $key => $value) {
				if(array_key_exists($key, $arrayMap)) {
					$k = trim($arrayMap[$key]);
					$v = trim((string)$value);
					$job->$k = $v;

					if(stripos($k, "tag__") === 0) {
						if(!isset($job->tags)) {
							$job->tags = new stdClass();
							$job->tags->tag = array();
						}
						$tag = new stdClass();
						$tag->slug = $v;
						$tag->type = str_replace("tag__", "", $k);
						$job->tags->tag[] = $tag;
					}

					if(stripos($k, "meta__") === 0) {
						if(!isset($job->metas)) {
							$job->metas = new stdClass();
							$job->metas->meta = array();
						}
						
						$metaName = str_replace("meta__", "", $k);

						$item = new stdClass();
						$item->name = $metaName;
						$item->meta_object = "job";
						
						$response = Wpjb_Model_Meta::import($item);
						
						$meta = new stdClass();
						$meta->name = $metaName;
						$meta->value = $v;
						$job->metas->meta[] = $meta;
					}
				}
			}

            remove_all_actions("wpjb_job_published");
			Wpjb_Model_Job::import($job);
			
			$q = new Daq_Db_Query();
			$result = $q->select("MAX(id) AS M_id")->from("Wpjb_Model_Job t")->where("employer_id = ?", $id)->execute();
			$result = $result[0];
			$mid = $result->helper->M_id;

			$j = new Wpjb_Model_Job($mid);
			
			echo "OK ID=$mid URL=" . wpjb_link_to("job", $j);
			die();
		
		} 
		/*if($xml->command == "delete") {
		
		}*/
		
		echo "Unknow command";
		die;
	}
	    
	echo "Employer does not exist";
    die(); // this is required to return a proper result
}

#add_filter("wpjb_message", "wpjb_broadbean_");
function wpjb_broadbean_message($mail) {

  if($mail["key"]->name == "notify_employer_new_application" || $mail["key"]->name == "notify_admin_new_application") {
    $mail["headers"][] = "Reply-To: ".$_POST["email"];
  }
  
  return $mail;
}

function wpjbm_isEmployer($name)
{
	$q = new Daq_Db_Query();
	$result = $q->select()->from("Wpjb_Model_Company t")->join("t.user t2")->where("user_login = ?", $name)->execute();
	
	if(isset($result) && !empty($result)) {
		$result = $result[0];
		return $result->id;
	}
	
	return false;
}

function wpjbm_getArrayMap()
{
    $result = array();
    $filePath = plugins_url()."/wpjb-broadbean/array-map.txt";

    $file = fopen($filePath, "r");
    while($buffer = fgets($file, 4096)) {
        $result[] = $buffer;
    }
    fclose($file);
	
	$ret = array();
	foreach($result as $v) {
		$tmp = split(";", $v);
		$ret[$tmp[0]] = $tmp[1];
	}

    return apply_filters("wpjb_broadbean_map", $ret);
}


?>