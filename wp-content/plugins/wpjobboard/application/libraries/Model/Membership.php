<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Membership
 *
 * @author Grzegorz
 */
class Wpjb_Model_Membership extends Daq_Db_OrmAbstract 
{
    protected $_name = "wpjb_membership";
    
    protected function _init()
    {
        $this->_reference["pricing"] = array(
            "localId" => "package_id",
            "foreign" => "Wpjb_Model_Pricing",
            "foreignId" => "id",
            "type" => "ONE_TO_ONE"
        );
    }
    
    public function package()
    {
        return unserialize($this->package);
    }
    
    public function inc($pricing_id)
    {
        $musage = $this->package();

        foreach(array_keys($musage) as $k) {
            if(isset($musage[$k][$pricing_id]["status"]) && $musage[$k][$pricing_id]["status"] == "limited") {
                $musage[$k][$pricing_id]["used"]++;
                $this->package = serialize($musage);
                break;
            }
        }
    }
    
    public function deriveFrom(Wpjb_Model_Pricing $pricing)
    {
        $package = array();
        
        $allows = unserialize($pricing->meta->package->value());
        
        foreach($allows as $k => $group){
            $package[$k] = array();
            foreach($group as $id => $usage) {
                $package[$k][$id] = array(
                    "id" => $id,
                    "status" => $usage["status"],
                    "usage" => $usage["usage"],
                    "used" => 0
                );
            }
        }

        $package["valid"] = $pricing->meta->visible->value();
        
        $this->package = serialize($package);
    }
    
    public function paymentAccepted()
    {
        $package = $this->package();
        $valid = $package["valid"];
        
        if($valid == 0) {
            $this->started_at = date("Y-m-d");
            $this->expires_at = WPJB_MAX_DATE;
        } else {
            $query = new Daq_Db_Query();
            $query->from(__CLASS__." t");
            $query->where("user_id = ?", $this->user_id);
            $query->where("package_id = ?", $this->package_id);
            $query->where("expires_at >= ?", date("Y-m-d"));
            $query->order("expires_at DESC");
            $query->limit(1);
            $result = $query->execute();
            if(!empty($result)) {
                $expires_at = $result[0]->expires_at;
                $started_at = date("Y-m-d", strtotime("{$expires_at} +1 DAY"));
            } else {
                $started_at = date("Y-m-d");
            }
        
            $this->started_at = $started_at;
            $this->expires_at = date("Y-m-d", strtotime("{$this->started_at} +{$valid} DAY"));
        }
        
        $this->save();
    }
    
    public function daysLeft()
    {
        $today = strtotime(date("Y-m-d"));
        $expires = $this->time->expires_at;
        
        return ($expires-$today)/(24*3600);
        
        
    }
    
    public static function getPackageSummary($package_id, $user_id)
    {
        $query = new Daq_Db_Query();
        $query->from(__CLASS__." t");
        $query->where("package_id = ?", $package_id);
        $query->where("user_id = ?", $user_id);
        $query->where("expires_at >= ?", date("Y-m-d"));
        $query->order("expires_at DESC");
        
        $member = $query->execute();
        $result = new stdClass();
        $result->expires_at = null;
        $result->updates_at = null;
        $result->days_left = null;
        $result->bundle = array();
        
        if(empty($member)) {
            return null;
        }
        
        $count = 0;
        
        $result->expires_at = $member[0]->expires_at;
        $result->days_left = (strtotime($result->expires_at)-strtotime(date("Y-m-d")))/(24*3600);
        
        foreach($member as $m) {
            $count++;
            $package = $m->package();
            foreach($package as $group => $product) {
                if(!isset($result->bundle[$group])) {
                    $result->bundle[$group] = array();
                }
                
                if(!is_array($product)) {
                    $result->bundle[$group] = $product;
                    continue;
                }
                
                foreach($product as $id => $use) {
                    if(!isset($result->bundle[$group][$id])) {
                        $result->bundle[$group][$id] = array(
                            "id"=>$id, 
                            "status"=>"limited",
                            "usage" => 0,
                            "used" => 0
                        );
                    }
                    
                    if($use["status"] == "unlimited") {
                        $result->bundle[$group][$id]["status"] = "unlimited";
                        
                    }
                    
                    if($result->bundle[$group][$id]["status"] == "unlimited") {
                        $result->bundle[$group][$id]["usage"] = "";
                    } else {
                        $result->bundle[$group][$id]["usage"]+= $use["usage"];
                    }
                    
                    $result->bundle[$group][$id]["used"] += $use["used"];
                }
            }
            
        }
        
        if($count > 1) {
            $result->updates_at = $m->expires_at;
        }
        
        return $result;
        
    }
    
    public static function search($params = array())
    {
        /**
         * @var $package_id int
         * package from wpjb_model_pricing
         */
        $package_id = null;
        
        /**
         * @var $user_id int
         * wp_user ID
         */
        $user_id = null;
        
        /**
         * @var $page int
         * current page number
         */
        $page = null;
        
        /**
         * @var $date_from string
         * when package becomes active
         */
        $date_from = null;
        
        /**
         * @var $date_to string
         * when package expires
         */
        $date_to = null;

        /**
         * @var $count int
         * items per page or maximum number of elements to return
         */
        $count = 20;
        
        /**
         * @var $sort_order mixed
         * string or array, specify sort column and order (either DESC or ASC),
         * you can add more then one sort order. 
         */
        $sort_order = "t1.started_at DESC";
        
        /**
         * @var $count_only boolean
         * Count jobs only
         */
        $count_only = false;
        
        /**
         * Return only list of job ids instead of objects
         * @var $ids_only boolean
         */
        $ids_only = false;  
        
        extract($params);
        
        $select = new Daq_Db_Query();
        $select->from(__CLASS__ . " t1");
        
        if($package_id) {
            $select->where("package_id = ?", $package_id);
        }
        
        if($user_id) {
            $select->where("user_id = ?", $user_id);
        }
        
        if($date_from) {
            $select->where("started_at >= ?", $date_from);
        }

        if($date_to) {
            $select->where("expires_at <= ?", $date_to);
        }
        
        if($sort_order) {
            $select->order($sort_order);
        }
        
        $sclone = clone $select;
        $sclone->select("COUNT(*) AS `cnt`");
        $itemsFound = $sclone->fetchColumn();
        unset($sclone);
        
        if($count_only) {
            return $itemsFound;
        }
        
        if($page && $count) {
            $select->limitPage($page, $count);
        }
        
        if($ids_only) {
            $select->select("t1.id");
            $list = $select->getDb()->get_col($select->toString());
        } else {   
            $list = $select->execute();
        }
        
        $response = new stdClass;
        $response->membership = $list;
        $response->page = $page;
        $response->perPage = $count;
        $response->count = count($list);
        $response->total = $itemsFound;
        
        if($response->perPage == 0) {
            $response->pages = 1;
        } else {
            $response->pages = ceil($response->total/$response->perPage);
        }
        
        return $response;
    }
    
}

?>
